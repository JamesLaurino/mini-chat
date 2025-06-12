<?php

namespace App\Services;

use App\Models\Preference;
use Illuminate\Support\Facades\Http;
use OpenAI\Responses\StreamResponse;

class ChatService
{
    private $baseUrl;
    private $apiKey;
    private $client;
    public const DEFAULT_MODEL = 'openai/gpt-4.1-mini';

    public function __construct()
    {
        $this->baseUrl = config('services.openrouter.base_url', 'https://openrouter.ai/api/v1');
        $this->apiKey = config('services.openrouter.api_key');
        $this->client = $this->createOpenAIClient();
    }

    public function getStream(array $messages, string $model = null, float $temperature = 0.7): StreamResponse
    {
        try {
            logger()->info('Envoi du message', [
                'model' => $model,
                'temperature' => $temperature,
            ]);

            $models = collect($this->getModels());
            if (!$model || !$models->contains('id', $model)) {
                $model = self::DEFAULT_MODEL;
                logger()->info('Modèle par défaut utilisé:', ['model' => $model]);
            }

            $messages = [$this->getChatSystemPrompt(), ...$messages];
            return $this->client->chat()->createStreamed([
                'model' => $model,
                'messages' => $messages,
                'temperature' => $temperature,
                'stream' => true
            ]);

        } catch (\Exception $e) {
            logger()->error('Erreur dans sendMessage:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }


    /**
     * @return array<array-key, array{
     *     id: string,
     *     name: string,
     *     context_length: int,
     *     max_completion_tokens: int,
     *     pricing: array{prompt: int, completion: int}
     * }>
     */
    public function getModels(): array
    {
        return cache()->remember('openai.models', now()->addHour(), function () {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . '/models');

            return collect($response->json()['data'])
                ->sortBy('name')
                ->map(function ($model) {
                    return [
                        'id' => $model['id'],
                        'name' => $model['name'],
                        'context_length' => $model['context_length'],
                        'max_completion_tokens' => $model['top_provider']['max_completion_tokens'],
                        'pricing' => $model['pricing'],
                    ];
                })
                ->values()
                ->all()
                ;
        });
    }

    public function generateLoremIpsum(int $numParagraphs = 1, int $wordsPerParagraph = 100): string
    {
        $loremIpsumWords = [
            'lorem', 'ipsum', 'dolor', 'sit', 'amet', 'consectetur', 'adipiscing', 'elit', 'sed', 'do',
            'eiusmod', 'tempor', 'incididunt', 'ut', 'labore', 'et', 'dolore', 'magna', 'aliqua', 'ut',
            'enim', 'ad', 'minim', 'veniam', 'quis', 'nostrud', 'exercitation', 'ullamco', 'laboris', 'nisi',
            'ut', 'aliquip', 'ex', 'ea', 'commodo', 'consequat', 'duis', 'aute', 'irure', 'dolor', 'in',
            'reprehenderit', 'in', 'voluptate', 'velit', 'esse', 'cillum', 'dolore', 'eu', 'fugiat', 'nulla',
            'pariatur', 'excepteur', 'sint', 'occaecat', 'cupidatat', 'non', 'proident', 'sunt', 'in', 'culpa',
            'qui', 'officia', 'deserunt', 'mollit', 'anim', 'id', 'est', 'laborum'
        ];

        $text = [];
        for ($p = 0; $p < $numParagraphs; $p++) {
            $paragraph = [];
            for ($w = 0; $w < $wordsPerParagraph; $w++) {
                // Sélectionne un mot aléatoire
                $word = $loremIpsumWords[array_rand($loremIpsumWords)];

                // Ajoute de la ponctuation de manière aléatoire (pour un rendu plus naturel)
                if ($w > 0 && $w % 10 === 0 && random_int(0,1)) { // Une virgule tous les 10 mots environ
                    $word = ', ' . $word;
                }
                if ($w === $wordsPerParagraph - 1) { // Le dernier mot du paragraphe
                    $word .= '.';
                } elseif (random_int(0, 15) === 0) { // Un point de temps en temps
                    $word .= '.';
                }

                $paragraph[] = $word;
            }
            $currentParagraph = implode(' ', $paragraph);

            // Met la première lettre de la phrase en majuscule
            $currentParagraph = ucfirst($currentParagraph);

            $text[] = $currentParagraph;
        }

        return implode("\n\n", $text); // Joindre les paragraphes avec deux retours à la ligne
    }

    /**
     * @param array{role: 'user'|'assistant'|'system'|'function', content: string} $messages
     * @param string|null $model
     * @param float $temperature
     *
     * @return string
     */
    public function sendMessage(array $messages, string $model = null, float $temperature = 0.7): string
    {
        try {
            logger()->info('Envoi du message', [
                'model' => $model,
                'temperature' => $temperature,
            ]);

            $models = collect($this->getModels());
            if (!$model || !$models->contains('id', $model)) {
                $model = self::DEFAULT_MODEL;
                logger()->info('Modèle par défaut utilisé:', ['model' => $model]);
            }

            $messages = [$this->getChatSystemPrompt(), ...$messages];
            $response = $this->client->chat()->create([ // created tream
                'model' => $model,
                'messages' => $messages,
                'temperature' => $temperature,
            ]);

            logger()->info('Réponse reçue:', ['response' => $response]);

            $content = $response->choices[0]->message->content;

            return $content;
        } catch (\Exception $e) {
            logger()->error('Erreur dans sendMessage:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    private function createOpenAIClient(): \OpenAI\Client
    {
        return \OpenAI::factory()
            ->withApiKey($this->apiKey)
            ->withBaseUri($this->baseUrl)
            ->make()
            ;
    }

    /**
     * @return array{role: 'system', content: string}
     */
    private function getChatSystemPrompt(): array
    {
        $user = auth()->user();
        $now = now()->locale('fr')->format('l d F Y H:i');


        $preferences = Preference::where("user_id",auth()->user()->getAuthIdentifier())
            ->first();

        $about = '';
        $instruction = '';
        $behaviour = '';

        if ($preferences !== null) {
            if (!empty($preferences->about)) {
                $about = "\n\nÀ propos de l'utilisateur :\n" . $preferences->about;
            }

            if (!empty($preferences->instruction)) {
                $instruction = "\n\nInstructions personnalisées :\n" . $preferences->instruction;
            }

            if (!empty($preferences->behaviour)) {
                $behaviour = "\n\nComportement attendu de l'assistant :\n" . $preferences->behaviour;
            }
        }

        $content = <<<EOT
            Tu es un assistant de chat. La date et l'heure actuelle est le {$now}.
            Tu es actuellement utilisé par {$user}{$about}{$instruction}{$behaviour}.
          EOT;

        return [
            'role' => 'system',
            'content' => $content
        ];
    }
}

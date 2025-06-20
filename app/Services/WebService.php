<?php

namespace App\Services;


class WebService
{

    public function getResponse($request,?string $prefix = null) {

        try {

            $content = $prefix ? "{$prefix} {$request->message}" : $request->message;
            $messages = [[
                'role' => 'user',
                'content' => $content,
            ]];

            return (new ChatService())->sendMessage(
                messages: $messages,
                model: $request->model
            );
        }
        catch (\Exception $e) {
            logger()->warning('Error web call Open router', [
                'error' => "Une erreur est survenue lors du retour de la réponse",
                'exception' => $e
            ]);

            return $e;

        }
    }
}

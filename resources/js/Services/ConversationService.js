import { useForm } from '@inertiajs/vue3';

export default class ConversationService {

    static addConversation(conversation) {

        const form = useForm({
            response: conversation.response,
            question: conversation.question,
            created_at: conversation.created_at,
            updated_at: conversation.updated_at,
            space_id: conversation.space_id
        });

        form.post('/conversation', {
            onSuccess: () => {
                console.log('Conversation sauvegardée !');
            },
            onError: (errors) => {
                console.log('Erreur !', errors);
            }
        });
    }
}

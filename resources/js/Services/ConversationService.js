import { useForm } from '@inertiajs/vue3';

export default class ConversationService {
    static addConversation(conversation) {
        return new Promise((resolve, reject) => {
            const form = useForm({
                response: conversation.response,
                question: conversation.question,
                created_at: conversation.created_at,
                updated_at: conversation.updated_at,
                space_id: conversation.space_id
            });

            form.post('/conversation', {
                onSuccess: () => {
                    resolve(form);
                },
                onError: (errors) => {
                    reject(errors);
                }
            });
        });
    }
}

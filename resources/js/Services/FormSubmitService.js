import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import dateFormatService from '@/Helpers/DateFormatService.js';
import ConversationService from '@/Services/ConversationService.js';

export function useFormSubmission(props) {
    const isLoading = ref(false);
    const errorMessage = ref(null);
    const responseMessage = ref('');

    const handleFormSubmission = (form, conversationsRef, hasExistingConversations) => {
        isLoading.value = true;
        errorMessage.value = null;

        if (!hasExistingConversations) {
            console.log("space call");
            form.post('/space', {
                onSuccess: () => {
                    isLoading.value = false;
                },
                onError: (errors) => {
                    if (errors && Object.keys(errors).length === 0) {
                        errorMessage.value = 'Une erreur inattendue est survenue lors de l\'envoi du message.';
                    } else if (errors && errors.message) {
                        errorMessage.value = errors.message;
                    } else {
                        errorMessage.value = 'Veuillez vérifier les informations saisies.';
                    }
                    responseMessage.value = '';
                    console.error("Erreur lors de l'envoi du formulaire:", errors);
                },
                onFinish: () => {
                    isLoading.value = false;
                }
            });
        } else {
            console.log("ask call");
            form.post('/ask', {
                onSuccess: () => {
                    responseMessage.value = props.flash.message || '';
                    errorMessage.value = null;
                    let conversationObjet = {
                        "response": responseMessage.value,
                        "question": form.message,
                        "created_at": dateFormatService(),
                        "updated_at": dateFormatService(),
                        "space_id": conversationsRef.value[0]?.['space_id']
                    };
                    console.log("Objet conversationObjet avant ajout:", conversationObjet);
                    ConversationService.addConversation(conversationObjet);
                    conversationsRef.value = [...conversationsRef.value, conversationObjet];
                    form.message = '';
                },
                onError: (errors) => {
                    if (errors && Object.keys(errors).length === 0) {
                        errorMessage.value = 'Une erreur inattendue est survenue lors de l\'envoi du message.';
                    } else if (errors && errors.message) {
                        errorMessage.value = errors.message;
                    } else {
                        errorMessage.value = 'Veuillez vérifier les informations saisies.';
                    }
                    responseMessage.value = '';
                    console.error("Erreur lors de l'envoi du formulaire:", errors);
                },
                onFinish: () => {
                    isLoading.value = false;
                }
            });
        }
    };

    return {
        isLoading,
        errorMessage,
        responseMessage,
        handleFormSubmission,
    };
}

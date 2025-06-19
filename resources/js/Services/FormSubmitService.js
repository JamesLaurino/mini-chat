import { ref } from 'vue';
import dateFormatHelper from '@/Helpers/DateFormatHelper.js';
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
                    if(props.flash.error) {
                        isLoading.value = false;
                        errorMessage.value = props.flash.error;
                        return;
                    }
                    isLoading.value = false;
                },
                onError: (errors) => {
                    errorMessage.value = 'Veuillez vérifier les informations saisies :' + errors;
                    responseMessage.value = '';
                },
                onFinish: () => {
                    isLoading.value = false;
                }
            });
        } else {
            console.log("ask call");
            form.post('/ask', {
                onSuccess: () => {

                    if(props.flash.error) {
                        isLoading.value = false;
                        errorMessage.value = props.flash.error;
                        return;
                    }
                    responseMessage.value = props.flash.message || '';
                    errorMessage.value = null;
                    let conversationObjet = {
                        "response": responseMessage.value,
                        "question": form.message,
                        "created_at": dateFormatHelper(),
                        "updated_at": dateFormatHelper(),
                        "space_id": conversationsRef.value[0]?.['space_id']
                    };

                    /* SAUVEGARDE DES CONVERSATION */
                    ConversationService.addConversation(conversationObjet)
                        .then((result) => {
                            console.log("Conversation ajoutée avec succès :", result);
                        })
                        .catch((error) => {
                            console.error("Erreur lors de l'ajout de la conversation :", error);
                            errorMessage.value = "Erreur lors de la sauvegarde de la conversation.";
                        });

                    conversationsRef.value = [...conversationsRef.value, conversationObjet];
                    form.message = '';
                },
                onError: (errors) => {
                    errorMessage.value = 'Veuillez vérifier les informations saisies : ' + errors;
                    responseMessage.value = '';
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

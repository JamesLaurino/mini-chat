<script setup>
import {Head, Link, useForm} from '@inertiajs/vue3';
import {computed, nextTick, ref, watch} from "vue";
import SidePanel from '@/Components/SidePanel.vue';
import ConversationHistorique from "@/Components/ConversationHistorique.vue";
import {useStream} from "@laravel/stream-vue";
import dateFormatHelper from "@/Helpers/DateFormatHelper.js";
import ConversationService from "@/Services/ConversationService.js";
import IconButton from "@/Components/IconButton.vue";

const props = defineProps({
    flash: {
        type: Object
    },
    models: {
        type: Array,
        default: () => []
    },
    selectedModel: {
        type: String
    },
    spaces: {
        type: Array,
        default: () => []
    },
    conversations: {
        type: Array,
        default: () => []
    },
    space: {
        type: [Number,String]
    }
});

const form = useForm({
    message: '',
    model: props.selectedModel || (props.models.length > 0 ? props.models[0].id || props.models[0].name : 'openai/gpt-4.1-mini'),
    conversationId: props.spaces.length > 0 ? props.spaces[0]["id"] : -1
});

let conversationsRef = computed(() => props.conversations);
let spaceRef = computed(() => props.space)


const currentQuestion = ref('');
const isLoading = ref(false);
const errorMessage = ref(null);
const responseMessage = ref('');
const isStreamingResponse = ref(false);
const scrollContainer = ref(null)


const { data, send } = useStream("/stream", {
    onFinish:() =>
    {
        let conversationObjet = {
            "response": data.value,
            "question": currentQuestion.value,
            "created_at": dateFormatHelper(),
            "updated_at": dateFormatHelper(),
            "space_id": spaceRef.value ? spaceRef.value : conversationsRef.value[0]?.['space_id']
        };

        if(props.flash.error) {
            isLoading.value = false;
            errorMessage.value = props.flash.error;
        }
        responseMessage.value = '';
        errorMessage.value = null;
        ConversationService.addConversation(conversationObjet)
            .then(() => {
                isLoading.value = false;
            })
            .catch(() => {
                errorMessage.value = "Erreur lors de la sauvegarde de la conversation.";
                isLoading.value = false;
            });

        conversationsRef.value = [...conversationsRef.value, conversationObjet];
        isLoading.value = false;
        form.message = '';
        isStreamingResponse.value = false;
    }
});

const sendMessage = () => {
    const segments = window.location.pathname.split('/');
    const spaceId = segments[segments.length - 1];
    form.conversationId = spaceId;
    send({
        message: form.message,
        model:form.model,
        conversationId: form.conversationId
    });
};

const showOptions = ref(false);
const sidePanelRef = ref(null);
const messageTextarea = ref(null);

const submit = async () => {
    isLoading.value = true;
    currentQuestion.value = form.message;

    if (!props.conversations.length > 0) {
        form.post('/space', {
            onSuccess: () => {
                if (props.flash.error) {
                    isLoading.value = false;
                    errorMessage.value = props.flash.error;
                    return;
                }

                isStreamingResponse.value = true;
                sendMessage();
            },
            onError: (errors) => {
                errorMessage.value = 'Veuillez vérifier les informations saisies :' + errors;
                responseMessage.value = '';
                isLoading.value = false;
            }
        });
    } else {
        isStreamingResponse.value = true;
        sendMessage();
    }
};

const autoResize = () => {
    const el = messageTextarea.value
    if (!el) return

    el.style.height = 'auto'
    const maxHeight = 192
    const scrollHeight = el.scrollHeight

    if (scrollHeight > maxHeight) {
        el.style.height = maxHeight + 'px'
        el.style.overflowY = 'auto'
    } else {
        el.style.height = scrollHeight + 'px'
        el.style.overflowY = 'hidden'
    }

    nextTick(() => {
        if (scrollContainer.value) {
            scrollContainer.value.scrollTop = scrollContainer.value.scrollHeight
        }
    })
}

watch(() => form.message, () => {
    nextTick(() => autoResize())
})
const toggleOptions = () => {
    showOptions.value = !showOptions.value;
};

const openSidePanel = () => {
    if (sidePanelRef.value) {
        sidePanelRef.value.openPanel();
    }
};
</script>

<template>
    <Head title="Bienvenue" />
    <div class="flex flex-col min-h-screen bg-base-200 p-4">
        <IconButton
            @click="openSidePanel"
            position="fixed top-4 left-4 z-40"
            aria-label="Ouvrir le panneau latéral">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </IconButton>

        <IconButton
            position="fixed top-4 right-4 z-40">
            <Link :href="route('dashboard')">
                <i class="fa-solid fa-user"></i>
            </Link>
        </IconButton>

        <div ref="scrollContainer" class="flex-grow container mx-auto max-w-xl flex flex-col pt-16 overflow-y-auto">

            <ConversationHistorique
                :new-data="data"
                :conversations="conversationsRef"
                :current-question="currentQuestion"
                :is-streaming-response="isStreamingResponse"
            />

            <div v-if="errorMessage" role="alert" class="alert alert-error mb-4">
                {{ errorMessage }}
            </div>
        </div>
        <div class="container mx-auto max-w-xl p-0">
            <div v-if="isLoading && !responseMessage" role="alert" class="alert alert-success alert-outline mb-4">
                <span class="loading loading-spinner"></span>
                Chargement de la réponse...
            </div>

            <form @submit.prevent="submit" class="card bg-base-100 shadow-xl overflow-hidden p-2">
                <div class="flex items-end gap-2 relative px-2 py-1">

                    <IconButton
                        type="button"
                        @click="toggleOptions"
                        btnClass="btn-ghost btn-circle btn-sm"
                        aria-label="Afficher/Masquer les options">
                        <svg
                            v-if="!showOptions"
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <svg
                            v-else
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
                        </svg>
                    </IconButton>

                    <textarea
                        ref="messageTextarea"
                        v-model="form.message"
                        id="message"
                        class="textarea textarea-neutral flex-grow resize-none overflow-hidden h-10 min-h-[2.5rem] max-h-48 py-2 pr-2 pl-0"
                        rows="1"
                        :disabled="isLoading"
                        @input="autoResize"
                    ></textarea>

                    <IconButton
                        type="submit"
                        btnClass="btn-accent btn-circle btn-sm"
                        :disabled="isLoading || !form.message.trim()"
                        aria-label="Envoyer le message">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 transform rotate-45"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </IconButton>

                </div>

                <div class="label text-error px-4 pt-1" v-if="form.errors.message">
                    <span class="label-text-alt">{{ form.errors.message }}</span>
                </div>

                <div :class="['px-2 pb-2 transition-all duration-300 ease-in-out', {'max-h-96 opacity-100 pt-2': showOptions, 'max-h-0 opacity-0 overflow-hidden': !showOptions}]">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text">Choisissez un modèle</span>
                        </label>
                        <select id="model" v-model="form.model" class="select select-bordered w-full">
                            <option disabled v-if="props.models.length === 0">Chargement des modèles...</option>
                            <option v-for="model in props.models" :key="model.id || model.name" :value="model.id || model.name">
                                {{model.name}}
                            </option>
                        </select>
                        <div class="label text-error" v-if="form.errors.model">
                            <span class="label-text-alt">{{ form.errors.model }}</span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <SidePanel :currentSpace="spaceRef" :spaces="spaces" ref="sidePanelRef" />
</template>

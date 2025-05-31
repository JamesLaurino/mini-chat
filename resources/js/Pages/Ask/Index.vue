<script setup>
import {Head, useForm} from '@inertiajs/vue3';
import MarkdownRenderer from '@/Components/MarkdownRenderer.vue';
import {ref, onMounted} from "vue";
import SidePanel from '@/Components/SidePanel.vue'; // Importez le composant SidePanel

// DÉCLARATION DE PROPS EN PREMIER
const props = defineProps({
    flash: {
        type:Object
    },
    models: {
        type:Array,
        default: () => []
    },
    selectedModel: {
        type:String
    }
});

// INITIALISATION DE FORM APRÈS LA DÉCLARATION DE PROPS
const form = useForm({
    message: '',
    model: props.selectedModel || (props.models.length > 0 ? props.models[0].id || props.models[0].name : 'openai/gpt-4.1-mini')
})

const responseMessage = ref('');
const isLoading = ref(false);
const errorMessage = ref(null);
const showOptions = ref(false);

const sidePanelRef = ref(null); // Ref pour accéder au composant SidePanel


const submit = () => {
    isLoading.value = true;
    errorMessage.value = null;
    console.log(form);

    // form.post('/ask', {
    //     onSuccess: () => {
    //         responseMessage.value = props.flash.message || '';
    //         errorMessage.value = null;
    //         console.log(responseMessage.value);
    //         form.message = '';
    //         const textarea = document.getElementById('message');
    //         if (textarea) {
    //             autoResizeTextarea({ target: textarea });
    //         }
    //     },
    //     onError: (errors) => {
    //         if (errors && Object.keys(errors).length === 0) {
    //             errorMessage.value = 'Une erreur inattendue est survenue lors de l\'envoi du message.';
    //         } else if (errors && errors.message) {
    //             errorMessage.value = errors.message;
    //         } else {
    //             errorMessage.value = 'Veuillez vérifier les informations saisies.';
    //         }
    //         responseMessage.value = '';
    //         console.error("Erreur lors de l'envoi du formulaire:", errors);
    //     },
    //     onFinish: () => {
    //         isLoading.value = false;
    //     }
    // });
};

const autoResizeTextarea = (event) => {
    const textarea = event.target;
    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';
    const maxHeight = parseInt(textarea.style.maxHeight) || 192;
    if (textarea.scrollHeight > maxHeight) {
        textarea.style.overflowY = 'auto';
    } else {
        textarea.style.overflowY = 'hidden';
    }
};

const toggleOptions = () => {
    showOptions.value = !showOptions.value;
};

const openSidePanel = () => {
    if (sidePanelRef.value) {
        sidePanelRef.value.openPanel();
    }
};

onMounted(() => {
    const textarea = document.getElementById('message');
    if (textarea) {
        autoResizeTextarea({ target: textarea });
    }
});

</script>

<template>
    <Head title="Bienvenue" />

    <div class="tw-container tw-mx-auto tw-p-4 tw-max-w-xl">
        <button
            @click="openSidePanel"
            class="tw-fixed tw-top-4 tw-left-4 tw-z-40 tw-p-2 tw-rounded-md tw-bg-gray-200 dark:tw-bg-gray-700 hover:tw-bg-gray-300 dark:hover:tw-bg-gray-600 tw-text-gray-600 dark:tw-text-gray-300 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-blue-500"
        >
            <svg class="tw-h-6 tw-w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <div v-if="isLoading && !responseMessage" class="tw-bg-blue-100 tw-border tw-border-blue-400 tw-text-blue-700 tw-px-4 tw-py-3 tw-rounded tw-relative tw-mb-4" role="alert">
            Chargement de la réponse...
        </div>
        <div v-if="errorMessage" class="tw-bg-red-100 tw-border tw-border-red-400 tw-text-red-700 tw-px-4 tw-py-3 tw-rounded tw-relative tw-mb-4" role="alert">
            {{ errorMessage }}
        </div>
        <div class="tw-prose dark:tw-prose-invert" v-if="responseMessage && !errorMessage">
            <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900 dark:tw-text-gray-100">Réponse :</h3>
            <MarkdownRenderer :content="responseMessage" />
        </div>
        <div v-if="!responseMessage && !isLoading && !errorMessage" class="tw-bg-gray-100 mt-5 tw-border tw-border-gray-400 tw-text-gray-700 tw-px-4 tw-py-3 tw-rounded tw-relative tw-mb-4" role="alert">
            Bienvenue dans le mini chat !
        </div>

        <form @submit.prevent="submit" class="tw-bg-white dark:tw-bg-gray-800 tw-rounded-lg tw-shadow-lg tw-overflow-hidden tw-relative tw-p-2">
            <div class="tw-flex tw-items-end tw-gap-2 tw-relative tw-border tw-border-gray-300 dark:tw-border-gray-600 tw-rounded-xl tw-px-2 tw-py-1 focus-within:tw-border-blue-500 focus-within:tw-ring-1 focus-within:tw-ring-blue-500">
                <button
                    type="button"
                    @click="toggleOptions"
                    class="tw-flex-shrink-0 tw-w-8 tw-h-8 tw-flex tw-items-center tw-justify-center tw-rounded-full tw-bg-transparent hover:tw-bg-gray-200 dark:hover:tw-bg-gray-700 tw-text-gray-600 dark:tw-text-gray-300 tw-transition-colors tw-duration-200"
                >
                    <svg
                        v-if="!showOptions"
                        xmlns="http://www.w3.org/2000/svg"
                        class="tw-h-5 tw-w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    <svg
                        v-else
                        xmlns="http://www.w3.org/2000/svg"
                        class="tw-h-5 tw-w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
                    </svg>
                </button>

                <textarea
                    v-model="form.message"
                    id="message"
                    class="tw-flex-grow tw-resize-none tw-overflow-hidden tw-h-10 tw-min-h-[2.5rem] tw-max-h-48 tw-py-2 tw-pr-2 tw-pl-0 tw-text-gray-900 dark:tw-text-white tw-bg-transparent focus:tw-outline-none tw-placeholder-gray-500 dark:tw-placeholder-gray-400"
                    placeholder="Entrez votre message"
                    rows="1"
                    :disabled="isLoading"
                    @input="autoResizeTextarea"
                ></textarea>

                <button
                    type="submit"
                    class="tw-flex-shrink-0 tw-w-8 tw-h-8 tw-flex tw-items-center tw-justify-center tw-rounded-full tw-bg-blue-500 hover:tw-bg-blue-600 tw-text-white tw-transition-colors tw-duration-200 disabled:tw-opacity-50 disabled:tw-cursor-not-allowed"
                    :disabled="isLoading || !form.message.trim()"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="tw-h-5 tw-w-5 tw-transform tw-rotate-45"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </button>
            </div>

            <div class="tw-text-red-500 tw-text-sm tw-px-4 tw-pt-1" v-if="form.errors.message">
                {{ form.errors.message }}
            </div>

            <div :class="['tw-px-2 tw-pb-2 tw-transition-all tw-duration-300 tw-ease-in-out', {'tw-max-h-96 tw-opacity-100 tw-pt-2': showOptions, 'tw-max-h-0 tw-opacity-0 tw-overflow-hidden': !showOptions}]">
                <div class="form-group tw-mb-2">
                    <label for="model" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 dark:tw-text-gray-300 tw-mb-1">Choisissez un modèle</label>
                    <select id="model" v-model="form.model" class="tw-block tw-w-full tw-py-2 tw-px-3 tw-border tw-border-gray-300 dark:tw-border-gray-600 tw-bg-white dark:tw-bg-gray-700 tw-rounded-md tw-shadow-sm focus:tw-outline-none focus:tw-ring-blue-500 focus:tw-border-blue-500 tw-text-gray-900 dark:tw-text-white tw-sm:text-sm">
                        <option v-if="props.models.length === 0" disabled>Chargement des modèles...</option>
                        <option v-for="model in props.models" :key="model.id || model.name" :value="model.id || model.name">
                            {{model.name}}
                        </option>
                    </select>
                    <div class="tw-text-red-500 tw-text-sm tw-mt-1" v-if="form.errors.model">
                        {{ form.errors.model }}
                    </div>
                </div>
            </div>
        </form>
    </div>

    <SidePanel ref="sidePanelRef" />
</template>

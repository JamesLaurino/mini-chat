<script setup>
import {Head, useForm} from '@inertiajs/vue3';
import MarkdownRenderer from '@/Components/MarkdownRenderer.vue';
import {ref, onMounted} from "vue";
import SidePanel from '@/Components/SidePanel.vue';

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
    },
    conversations: {
        type:Array
    }
});

console.log(props.conversations["test"])

const form = useForm({
    message: '',
    model: props.selectedModel || (props.models.length > 0 ? props.models[0].id || props.models[0].name : 'openai/gpt-4.1-mini')
})

const responseMessage = ref('');
const isLoading = ref(false);
const errorMessage = ref(null);
const showOptions = ref(false);

const sidePanelRef = ref(null);


const submit = () => {
    isLoading.value = true;
    errorMessage.value = null;
    form.post('/ask', {
        onSuccess: () => {
            responseMessage.value = props.flash.message || '';
            errorMessage.value = null;
            console.log(responseMessage.value);
            form.message = '';
            const textarea = document.getElementById('message');
            if (textarea) {
                autoResizeTextarea({ target: textarea });
            }
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

    <div class="flex flex-col min-h-screen bg-gray-100 dark:bg-gray-900 p-4">
        <button
            @click="openSidePanel" class="fixed top-4 left-4 z-40 p-2 rounded-md bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <div class="flex-grow container mx-auto max-w-xl pb-4">
            <div v-if="errorMessage" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ errorMessage }}
            </div>
            <div class="prose dark:prose-invert" v-if="responseMessage && !errorMessage">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Réponse :</h3>
                <MarkdownRenderer :content="responseMessage" />
            </div>
        </div>
        <div class="container mx-auto max-w-xl p-0">
            <div v-if="isLoading && !responseMessage" class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                Chargement de la réponse...
            </div>
            <div v-if="!responseMessage && !isLoading && !errorMessage" class="bg-gray-100 border border-gray-400 text-gray-700 px-4 py-3 rounded relative mb-4" role="alert">
                Bienvenue dans le mini chat !
            </div>

            <form @submit.prevent="submit" class="bg-white p-4 dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden relative p-2">
                <div class="flex items-end gap-2 relative border border-gray-300 dark:border-gray-600 rounded-xl px-2 py-1">
                    <button
                        type="button"
                        @click="toggleOptions"
                        class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-transparent hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors duration-200"
                    >
                        <svg
                            v-if="!showOptions"
                            xmlns="http://www.w3.org/200_svg"
                            class="h-5 w-5"
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
                            class="h-5 w-5"
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
                        class="flex-grow resize-none overflow-hidden h-10 min-h-[2.5rem] max-h-48 py-2 pr-2 pl-0 text-gray-900 dark:text-white bg-transparent focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 border border-transparent hover:border-gray-400 dark:hover:border-gray-500 rounded-md"
                        placeholder="Entrez votre message"
                        rows="1"
                        :disabled="isLoading"
                        @input="autoResizeTextarea"
                    ></textarea>

                    <button
                        type="submit"
                        class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-blue-500 hover:bg-blue-600 text-white transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="isLoading || !form.message.trim()"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 transform rotate-45"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </button>
                </div>

                <div class="text-red-500 text-sm px-4 pt-1" v-if="form.errors.message">
                    {{ form.errors.message }}
                </div>

                <div :class="['px-2 pb-2 transition-all duration-300 ease-in-out', {'max-h-96 opacity-100 pt-2': showOptions, 'max-h-0 opacity-0 overflow-hidden': !showOptions}]">
                    <div class="form-group mb-2">
                        <label for="model" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Choisissez un modèle</label>
                        <select id="model" v-model="form.model" class="block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white sm:text-sm">
                            <option v-if="props.models.length === 0" disabled>Chargement des modèles...</option>
                            <option v-for="model in props.models" :key="model.id || model.name" :value="model.id || model.name">
                                {{model.name}}
                            </option>
                        </select>
                        <div class="text-red-500 text-sm mt-1" v-if="form.errors.model">
                            {{ form.errors.model }}
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <SidePanel ref="sidePanelRef" />
</template>

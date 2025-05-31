<script setup>
import {Head, useForm} from '@inertiajs/vue3';
import MarkdownRenderer from '@/Components/MarkdownRenderer.vue';
import {ref} from "vue";

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

const form = useForm({
    message: '',
    model:'openai/gpt-4.1-mini'
})

const responseMessage = ref('');
const isLoading = ref(false);
const errorMessage = ref(null);


const submit = () => {
    isLoading.value = true;
    errorMessage.value = null;

    form.post('/ask', {
        onSuccess: () => {
            responseMessage.value = props.flash.message || '';
            errorMessage.value = null;
            console.log(responseMessage.value);
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

</script>

<template>
    <Head title="Bienvenue" />

    <div class="container mt-4">
        <div v-if="isLoading" class="alert alert-info">
            Chargement de la réponse...
        </div>
        <div v-if="errorMessage" class="alert alert-danger">
            {{ errorMessage }}
        </div>
        <div class="tw-prose" v-if="responseMessage && !errorMessage">
            <h3>Réponse :</h3>
            <MarkdownRenderer :content="responseMessage" />
        </div>
        <div v-if="!responseMessage && !isLoading && !errorMessage" class="alert alert-secondary">
            Bienvenue dans le mini chat !
        </div>
    </div>
    <div class="container mt-4">
        <form @submit.prevent="submit">
            <div class="row">
                <div class="form-group col-md-6"> <label for="message">Votre message</label>
                <textarea
                    v-model="form.message"
                    type="text"
                    id="message"
                    class="form-control"
                    placeholder="Entrez votre message"
                    :disabled="isLoading" />
                <div class="text-danger" v-if="form.errors.message">
                    {{ form.errors.message }}
                </div>
            </div>
                <div class="form-group col-md-6"> <label for="model">Choisissez un modèle</label>
                    <select id="model" v-model="form.model" class="form-control">
                        <option v-if="models.length === 0" disabled>Chargement des modèles...</option>
                        <option v-for="model in models" :key="model.id || model.name">
                            {{model.name}}
                        </option>
                    </select>
                    <div class="text-danger" v-if="form.errors.model">
                        {{ form.errors.model }}
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" :disabled="isLoading">
                {{ isLoading ? 'Envoi en cours...' : 'Envoyer' }}
            </button>
        </form>
    </div>
</template>

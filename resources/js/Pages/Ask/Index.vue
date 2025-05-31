<script setup>
import {Head, useForm} from '@inertiajs/vue3';
import MarkdownRenderer from '@/Components/MarkdownRenderer.vue';
import 'highlight.js/styles/github.css';
import {ref} from "vue";

const props = defineProps({
    flash: {
        type:Object
    },
    models: {
        type:Array
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


const submit = () => {
    form.post('/ask', {
        onSuccess: () => {
            responseMessage.value = props.flash.message || '';
            console.log(responseMessage.value)
        }
    });
};

</script>

<template>
    <Head title="Bienvenue" />
    <div class="container-fluid d-flex justify-content-end mt-5">
        <div class="form-group">
            <label for="model">Choisissez un modèle</label>
            <select
                id="model"
                v-model="form.model"
                class="form-control"
            >
                <option v-for="model in models">
                    {{model.name}}
                </option>

            </select>
            <div class="text-danger" v-if="form.errors.model">
                {{ form.errors.model }}
            </div>
        </div>
    </div>


    <div class="container mt-4 tw-prose" v-if="responseMessage">
        <h3>Réponse :</h3>
        <MarkdownRenderer :content="responseMessage" />
    </div>

    <div class="container mt-4">
        <form @submit.prevent="submit">
            <div class="form-group">
                <label for="message">Votre message</label>
                <input v-model="form.message" type="text" id="message" class="form-control" placeholder="Entrez votre message"/>
                <div class="text-danger" v-if="form.errors.message">
                    {{ form.errors.message }}
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Envoyer</button>
        </form>

    </div>
</template>

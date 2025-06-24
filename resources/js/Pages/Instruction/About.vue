<script setup>

import {useForm} from "@inertiajs/vue3";
import {ref} from "vue";

const props = defineProps({
    about: {
        type: String
    }
});

const form = useForm({
    message: props.about,
});

const isLoading = ref(false);

function postAbout() {
    isLoading.value = true;
    form.post('/preference/about', {
        onSuccess: () => {
            isLoading.value = false;
        },
        onError: (errors) => {
            form.errors.model = "Une erreur est survenue : " + errors;
            isLoading.value = false
        },
        onFinish: () => {
            isLoading.value = false;
        }
    });
}

</script>

<template>
    <div class="flex flex-col lg:flex-row gap-6 h-full">
        <div class="w-full lg:w-1/4 p-4 bg-base-200 rounded-lg shadow-md flex flex-col justify-between">
            <div>
                <h2 class="text-xl font-bold mb-4 text-base-content">À propos de vous</h2>
                <p class="text-sm text-base-content mb-4">
                    Présentez-vous brièvement pour personnaliser l'interaction avec votre assistant.
                </p>

                <h3 class="text-lg font-semibold mb-2 text-base-content">Idées :</h3>
                <ul class="list-disc list-inside text-sm text-base-content space-y-1">
                    <li>Qui êtes-vous?</li>
                    <li>Votre domaine d'expertise</li>
                </ul>
                <p class="text-xs text-base-content mt-4 italic">
                    Exemple : "Je suis philosophe, intégrant des jeux de rôles et des technologies interactives dans mes écris,
                    ce qui permet d'aborder la langue de manière dynamique et engageante,
                    encourageant l'expression et la compréhension orale"
                </p>
            </div>
        </div>

        <div class="w-full lg:w-3/4 flex flex-col bg-base-100 rounded-lg shadow-md p-4">
            <h2 class="text-xl font-bold mb-4 text-base-content">Informations</h2>

            <div v-if="about !== null" class="flex-grow mb-4">
                <textarea
                    id="about-textarea"
                    v-model="form.message"
                    :disabled="isLoading"
                    class="textarea textarea-bordered w-full h-full min-h-[200px] md:min-h-[300px] lg:min-h-[400px] resize-y"
                >{{about}}</textarea>
            </div>
            <div v-else class="flex-grow mb-4">
                <textarea
                    id="about-textarea"
                    v-model="form.message"
                    :disabled="isLoading"
                    class="textarea textarea-bordered w-full h-full min-h-[200px] md:min-h-[300px] lg:min-h-[400px] resize-y"
                    placeholder="Hello moi c'est Hal Colik. Je suis philosophe de formation. Je suis belge."></textarea>
            </div>

            <div class="flex justify-center" v-if="form.errors.model" >
                <span class="label-text-alt text-red-700">{{ form.errors.model }}</span>
            </div>

            <div class="flex justify-end">
                <button @click="postAbout"
                        dusk="submit-about"
                        :disabled="isLoading"
                        class="bg-black text-white font-bold py-2 px-4 rounded">Envoyer</button>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .textarea:focus {
        outline: none;
        box-shadow: none;
    }
</style>

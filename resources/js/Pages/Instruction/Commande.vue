<script setup>

import {ref} from "vue";
import {useForm} from "@inertiajs/vue3";

const props = defineProps({
    instruction: {
        type: String
    }
});

const form = useForm({
    message: props.instruction
});

const isLoading = ref(false);

function postCommande() {
    isLoading.value = true;
    form.post('/preference/instructions', {
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
                <h2 class="text-xl font-bold mb-4 text-base-content">Créer des commandes personnalisées</h2>
                <p class="text-sm text-base-content mb-4">
                    Ici, vous pouvez définir vos propres commandes pour interagir de manière unique avec votre assistant.
                    Les commandes vous permettent de simplifier les actions récurrentes ou de personnaliser vos interactions.
                </p>

                <h3 class="text-lg font-semibold mb-2 text-base-content">Comment ça marche ?</h3>
                <ul class="list-disc list-inside text-sm text-base-content space-y-1">
                    <li>Commencez chaque commande par '/' suivi du nom de la commande.</li>
                    <li>Expliquez clairement l'action souhaitée après le nom de la commande.</li>
                </ul>
                <p class="text-xs text-base-content mt-4 italic">
                    Exemple : Créez une commande "/resume" pour demander un résumé du texte précédent.
                    Incluez dans la description : "Résume le texte fourni ou précédent en points clés."
                </p>
            </div>
        </div>

        <div class="w-full lg:w-3/4 flex flex-col bg-base-100 rounded-lg shadow-md p-4">
            <h2 class="text-xl font-bold mb-4 text-base-content">Commandes</h2>

            <div v-if="instruction !== null" class="flex-grow mb-4">
                <textarea
                    :disabled="isLoading"
                    id="commande-textarea"
                    v-model="form.message"
                    class="textarea textarea-bordered w-full h-full min-h-[200px] md:min-h-[300px] lg:min-h-[400px] resize-y"
                >{{instruction}}</textarea>
            </div>
            <div v-else class="flex-grow mb-4">
                <textarea
                    class="textarea textarea-bordered w-full h-full min-h-[200px] md:min-h-[300px] lg:min-h-[400px] resize-y"
                    :disabled="isLoading"
                    id="commande-textarea"
                    v-model="form.message"
                    placeholder="/aide donner de l'aide sur les commandes
        /recherche effectuer une recherche avec une query
        /idees donner une liste d'idées sur un sujet
        /reflexion mode réflexion : l'assistant découpera sa réponse en 2 parties. Une partie réflexion pour expliquer à haute voix son processus de pensée. Ensuite une partie Réponse, se nourrissant de ses propres réflexions pour générer une réponse de meilleure qualité.
        /ameliore amélioration du style : améliorer le texte précédent. Raccourcir les phrases, retravailler les tournures de phrases, organiser l'information efficacement avec des titres de différents niveaux, des listes et des tableaux. Le texte doit être agréable à lire."
                ></textarea>
            </div>

            <div class="flex justify-center" v-if="form.errors.model" >
                <span class="label-text-alt text-red-700">{{ form.errors.model }}</span>
            </div>

            <div v-if="isLoading" role="alert" class="alert alert-info mb-4">
                <span class="loading loading-spinner"></span>
                Chargement de la réponse...
            </div>

            <div class="flex justify-end">
                <button @click="postCommande"
                        :disabled="isLoading"
                        dusk="submit-commande"
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


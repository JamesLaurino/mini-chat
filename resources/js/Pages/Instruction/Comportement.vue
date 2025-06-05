<script setup>

import {ref} from "vue";
import {useForm} from "@inertiajs/vue3";

const props = defineProps({
    behaviour: {
        type: String
    }
});

const form = useForm({
    message: props.behaviour
});

const isLoading = ref(false);

function postBehaviour() {
    isLoading.value = true;
    form.post('/preference/behaviour', {
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
                <h2 class="text-xl font-bold mb-4 text-base-content">Comportement de l'assistant</h2>
                <p class="text-sm text-base-content mb-4">
                    Définissez comment vous souhaitez que l'assistant interagisse avec vous. Cela inclut le ton, le niveau de détail
                    des réponses, et toute autre préférence qui rendra l'utilisation de l'assistant plus naturelle et conforme à vos attentes.
                </p>

                <h3 class="text-lg font-semibold mb-2 text-base-content">Suggestions :</h3>
                <ul class="list-disc list-inside text-sm text-base-content space-y-1">
                    <li>Préférence de ton (amical, formel, etc.)</li>
                    <li>Format des réponses (listes, paragraphes détaillés, etc.)</li>
                    <li>Approches spécifiques pour expliquer des concepts</li>
                </ul>
                <p class="text-xs text-base-content mt-4 italic">
                    Exemple : "Je préfère un ton décontracté. Pour les sujets techniques, utilisez des exemples pratiques
                    pour clarifier les concepts. J'aime les résumés suivis de détails si nécessaire."
                </p>
            </div>
        </div>

        <div class="w-full lg:w-3/4 flex flex-col bg-base-100 rounded-lg shadow-md p-4">
            <h2 class="text-xl font-bold mb-4 text-base-content">Instructions</h2>

            <div  v-if="behaviour !== null" class="flex-grow mb-4">
                <textarea
                    v-model="form.message"
                    :disabled="isLoading"
                    class="textarea textarea-bordered w-full h-full min-h-[200px] md:min-h-[300px] lg:min-h-[400px] resize-y"
                >{{behaviour}}</textarea>
            </div>
            <div  v-else class="flex-grow mb-4">
                <textarea
                    v-model="form.message"
                    :disabled="isLoading"
                    class="textarea textarea-bordered w-full h-full min-h-[200px] md:min-h-[300px] lg:min-h-[400px] resize-y"
                    placeholder="- Tu es un modèle de langage. Tu es un assistant dont l'utilisateur t'a donné le nom de Aria.
        - Veilles à l'orthographe. Soigne ton style. Utilise des synonymes. Ne commence pas tes phrases par 'en tant que modèle de langage'.
        - Si besoin, demande des précisions. N'invente rien si je ne le demande pas explicitement."
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
                <button @click="postBehaviour"
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



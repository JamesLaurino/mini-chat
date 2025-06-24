<script setup lang="ts">

import AppLayout from "@/Layouts/AppLayout.vue";
import About from "@/Pages/Instruction/About.vue";
import Command from "@/Pages/Instruction/Command.vue";
import Behaviour from "@/Pages/Instruction/Behaviour.vue";
import {ref, watch} from "vue";

const props = defineProps({
    flash: {
        type: Object
    },
    preferences: {
        type: Array,
        default: () => []
    }
});

const message = ref('');
const showMessage = ref(false);

const error = ref('');
const showError = ref(false);

watch(
    () => props.flash.message,
    (newVal) => {
        if (newVal) {
            message.value = newVal;
            showMessage.value = true;

            setTimeout(() => {
                showMessage.value = false;
                message.value = '';
            }, 3000);
        }
    },
    { immediate: true }
);

watch(
    () => props.flash.error,
    (newVal) => {
        if (newVal) {
            error.value = newVal;
            showError.value = true;

            setTimeout(() => {
                showError.value = false;
                error.value = '';
            }, 3000);
        }
    },
    { immediate: true }
);

</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Instruction
            </h2>
        </template>

        <div v-if="showMessage" class="bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded mb-4">
            {{ message }}
        </div>

        <div v-if="showError" class="bg-red-100 border border-red-300 text-red-800 px-4 py-2 rounded mb-4">
            {{ error }}
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="tabs tabs-lift p-2">
                        <input type="radio" name="tabs" class="tab" aria-label="About" checked="checked" />
                        <div class="tab-content bg-base-100 border-base-300 p-6">
                            <About :about="props.preferences[0]['about']"/>
                        </div>

                        <input type="radio" name="tabs" class="tab" aria-label="Commandes"/>
                        <div class="tab-content bg-base-100 border-base-300 p-6">
                            <Command :instruction="props.preferences[0]['instruction']"/>
                        </div>

                        <input type="radio" name="tabs" class="tab" aria-label="Comportements" />
                        <div class="tab-content bg-base-100 border-base-300 p-6">
                            <Behaviour :behaviour="props.preferences[0]['behaviour']"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>

    .tab:focus {
        outline: none;
        box-shadow: none;
    }

</style>

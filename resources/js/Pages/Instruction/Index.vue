<script setup lang="ts">

import AppLayout from "@/Layouts/AppLayout.vue";
import About from "@/Pages/Instruction/About.vue";
import Command from "@/Pages/Instruction/Command.vue";
import Behaviour from "@/Pages/Instruction/Behaviour.vue";

const props = defineProps({
    preferences: {
        type: Array,
        default: () => []
    }
});

</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Instruction
            </h2>
        </template>

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

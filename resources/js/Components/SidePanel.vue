<script setup>
import { ref } from 'vue';

const isOpen = ref(false);

const openPanel = () => {
    isOpen.value = true;
};

const closePanel = () => {
    isOpen.value = false;
};

defineExpose({
    openPanel,
    closePanel
});
</script>


<template>
    <div class="relative z-50">
        <div
            v-if="isOpen"
            @click="closePanel"
            class="fixed inset-0 bg-black bg-opacity-50 transition-opacity duration-300"
        ></div>

        <div
            :class="[
        'fixed top-0 left-0 h-full w-64 bg-white dark:bg-gray-800 shadow-lg transform transition-transform duration-300 ease-in-out',
        isOpen ? 'translate-x-0' : 'translate-x-[-100%]' // CHANGEMENT ICI
      ]"
        >
            <div class="p-4 flex items-center justify-between border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Conversations</h2>
                <button
                    @click="closePanel"
                    class="p-1 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-4">
                <ul class="space-y-2">
                    <li
                        v-for="i in 5"
                        :key="i"
                        class="p-2 rounded-md cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200"
                    >
                        Conversation {{ i }}
                    </li>
                    <li class="p-2 rounded-md cursor-pointer bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-200 font-medium">
                        Nouvelle conversation
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>


<style scoped>
/* Pas besoin de styles personnalisés si toutes les classes Tailwind sont préfixées */
</style>

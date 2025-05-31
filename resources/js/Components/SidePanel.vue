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
    <div class="tw-relative tw-z-50">
        <div
            v-if="isOpen"
            @click="closePanel"
            class="tw-fixed tw-inset-0 tw-bg-black tw-bg-opacity-50 tw-transition-opacity tw-duration-300"
        ></div>

        <div
            :class="[
        'tw-fixed tw-top-0 tw-left-0 tw-h-full tw-w-64 tw-bg-white dark:tw-bg-gray-800 tw-shadow-lg tw-transform tw-transition-transform tw-duration-300 tw-ease-in-out',
        isOpen ? 'tw-translate-x-0' : 'tw-translate-x-[-100%]' // CHANGEMENT ICI
      ]"
        >
            <div class="tw-p-4 tw-flex tw-items-center tw-justify-between tw-border-b tw-border-gray-200 dark:tw-border-gray-700">
                <h2 class="tw-text-lg tw-font-semibold tw-text-gray-900 dark:tw-text-white">Conversations</h2>
                <button
                    @click="closePanel"
                    class="tw-p-1 tw-rounded-md hover:tw-bg-gray-200 dark:hover:tw-bg-gray-700 tw-text-gray-500 dark:tw-text-gray-300 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-blue-500"
                >
                    <svg class="tw-h-6 tw-w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="tw-p-4">
                <ul class="tw-space-y-2">
                    <li
                        v-for="i in 5"
                        :key="i"
                        class="tw-p-2 tw-rounded-md tw-cursor-pointer hover:tw-bg-gray-100 dark:hover:tw-bg-gray-700 tw-text-gray-700 dark:tw-text-gray-200"
                    >
                        Conversation {{ i }}
                    </li>
                    <li class="tw-p-2 tw-rounded-md tw-cursor-pointer tw-bg-blue-100 dark:tw-bg-blue-800 tw-text-blue-800 dark:tw-text-blue-200 tw-font-medium">
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

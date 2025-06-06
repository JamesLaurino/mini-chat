<script setup>
import MarkdownRenderer from "@/Components/MarkdownRenderer.vue";
import {nextTick, ref, watch} from "vue";
import { onMounted } from 'vue';

const props = defineProps({
    conversations: {
        type: Array,
        default: () => []
    },
    newData: {
        type: String,
        default:() => ''
    },

    currentQuestion: {
        type: String,
        default: () => ''
    },
    isStreamingResponse: {
        type: Boolean,
        default: false
    }
})


const chatContainerRef = ref(null);

const scrollToBottom = () => {
    nextTick(() => {
        if (chatContainerRef.value) {
            chatContainerRef.value.scrollTop = chatContainerRef.value.scrollHeight;
        }
    });
};

watch(() => [props.newData, props.conversations, props.isStreamingResponse], () => {
    scrollToBottom();
}, { deep: true });

onMounted(() => {
    scrollToBottom();
});

</script>

<template>
    <div ref="chatContainerRef" class="h-[calc(100vh-200px)] overflow-y-auto px-4 py-2 mb-4 space-y-4 custom-scrollbar-hidden">
        <div v-if="props.conversations.length === 0 && !props.isStreamingResponse" class="text-center text-gray-500">
            Commencez une nouvelle conversation !
        </div>

        <div v-for="(conv, index) in props.conversations" :key="index" class="space-y-2">
            <template v-if="!props.isStreamingResponse || index < props.conversations.length">
                <div class="chat chat-end">
                    <div class="chat-header">
                        Vous
                        <time class="text-xs opacity-50 ml-2" v-if="conv.created_at">{{ new Date(conv.created_at).toLocaleTimeString() }}</time>
                    </div>
                    <div class="chat-bubble chat-bubble-primary">
                        <MarkdownRenderer :content="String(conv.question || '')" />
                    </div>
                </div>

                <div class="chat chat-start">
                    <div class="chat-header">
                        Assistant
                        <time class="text-xs opacity-50 ml-2" v-if="conv.created_at">{{ new Date(conv.created_at).toLocaleTimeString() }}</time>
                    </div>
                    <div class="chat-bubble chat-bubble-info">
                        <MarkdownRenderer :content="String(conv.response || '')" />
                    </div>
                </div>
            </template>
        </div>

        <div v-if="isStreamingResponse">
            <div class="chat chat-end">
                <div class="chat-header">
                    Vous
                    <time class="text-xs opacity-50 ml-2">{{ new Date().toLocaleTimeString() }}</time>
                </div>
                <div class="chat-bubble chat-bubble-primary">
                    <MarkdownRenderer :content="String(props.currentQuestion || '')" />
                </div>
            </div>

            <div class="chat chat-start">
                <div class="chat-header">
                    Assistant
                    <time class="text-xs opacity-50 ml-2">{{ new Date().toLocaleTimeString() }}</time>
                </div>
                <div class="chat-bubble chat-bubble-info">
                    <MarkdownRenderer :content="String(props.newData || '')" />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

.custom-scrollbar-hidden::-webkit-scrollbar {
    display: none;
    width: 0;
    height: 0;
}


.custom-scrollbar-hidden {
    scrollbar-width: none;
    -ms-overflow-style: none;
}
</style>

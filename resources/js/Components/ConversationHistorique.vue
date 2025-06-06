<script setup>
import MarkdownRenderer from "@/Components/MarkdownRenderer.vue";
import {nextTick, ref, watch} from "vue";

const props = defineProps({
    conversations: {
        type: Array,
        default: () => []
    },
    newData: {
        type: String,
        default:() => {}
    }
})

</script>

<template>
    <div class="flex-grow overflow-y-auto px-4 py-2 mb-4 space-y-4">
        <div v-if="props.conversations.length === 0" class="text-center text-gray-500">
            Commencez une nouvelle conversation !
        </div>

        <div v-for="(conv, index) in props.conversations" :key="index" class="space-y-2">
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
        </div>
        <div v-if="newData" class="chat-bubble chat-bubble-info">
            {{newData}}
        </div>
    </div>
</template>

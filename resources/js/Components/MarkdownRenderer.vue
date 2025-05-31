<script setup>
import { ref, watch } from 'vue';
import MarkdownIt from 'markdown-it';
import hljs from 'highlight.js';
import 'highlight.js/styles/github.css'; // un thème pour le code

const props = defineProps({
    content: {
        type: String,
        required: true
    }
});

const renderedHtml = ref('');

const md = new MarkdownIt({
    html: true,
    linkify: true,
    typographer: true,
    highlight: function (str, lang) {
        if (lang && hljs.getLanguage(lang)) {
            try {
                return '<pre class="hljs"><code>' +
                    hljs.highlight(str, { language: lang, ignoreIllegals: true }).value +
                    '</code></pre>';
            } catch (__) {}
        }
        return '<pre class="hljs"><code>' + md.utils.escapeHtml(str) + '</code></pre>';
    }
});

const renderMarkdown = () => {
    renderedHtml.value = md.render(props.content);
};

watch(() => props.content, renderMarkdown, { immediate: true });

</script>

<template>
    <div v-html="renderedHtml"></div>
</template>

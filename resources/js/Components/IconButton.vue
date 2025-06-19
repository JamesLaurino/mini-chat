<script setup>
defineProps({
    type: {
        type: String,
        default: 'button'
    },
    disabled: {
        type: Boolean,
        default: false
    },
    position: {
        type: String,
        default: ''
    },
    btnClass: {
        type: String,
        default: 'btn-square'
    },
    ariaLabel: {
        type: String,
        required: false
    }
});
</script>

<template>
    <button
        :type="type"
        :class="['btn', btnClass, position]"
        :disabled="disabled"
        :aria-label="ariaLabel">
        <slot></slot>
    </button>
</template>

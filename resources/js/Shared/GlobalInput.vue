<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: String,
    error: String,
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input :class="error ? 'border-red-500 dark:border-red-500' : ''" ref="input"
        class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
        :value="modelValue" @input="$emit('update:modelValue', $event.target.value)">
</template>

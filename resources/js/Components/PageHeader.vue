<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        default: "",
    },
});
</script>

<template>
    <div
        class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between"
    >
        <div>
            <h1
                class="text-2xl font-bold tracking-tight text-gray-900 dark:text-gray-100"
            >
                {{ title }}
            </h1>
            <p
                v-if="description"
                class="mt-1 text-sm text-gray-500 dark:text-gray-400"
            >
                {{ description }}
            </p>
        </div>

        <!-- Add actions slot -->
        <div v-if="$slots.actions" class="mt-4 flex sm:ml-4 sm:mt-0">
            <slot name="actions" />
        </div>
    </div>
</template>

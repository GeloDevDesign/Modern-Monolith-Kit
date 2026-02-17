<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { computed, onMounted } from "vue";
import { useThemeStore } from "@/Stores/theme";
import { config } from "@/Utils/config";

const props = defineProps({
    status: {
        type: Number,
        required: true,
    },
    title: {
        type: String,
        default: null,
    },
    description: {
        type: String,
        default: null,
    },
});

const defaultMessages = {
    404: {
        title: "Page Not Found",
        description: "Sorry, the page you are looking for could not be found.",
    },
    403: {
        title: "Forbidden",
        description: "Sorry, you are not authorized to access this page.",
    },
    500: {
        title: "Server Error",
        description: "Whoops, something went wrong on our servers.",
    },
    503: {
        title: "Service Unavailable",
        description:
            "Sorry, we are doing some maintenance. Please check back soon.",
    },
    default: {
        title: "Error",
        description: "An unexpected error occurred.",
    },
};

const title = computed(
    () =>
        props.title ||
        defaultMessages[props.status]?.title ||
        defaultMessages.default.title,
);
const description = computed(
    () =>
        defaultMessages[props.status]?.description ||
        defaultMessages.default.description,
);

const themeStore = useThemeStore();

onMounted(() => {
    themeStore.initTheme();
});
</script>

<template>
    <Head :title="title" />

    <div
        class="min-h-screen bg-white dark:bg-zinc-950 flex flex-col items-center justify-center p-4 text-center transition-colors duration-300"
    >
        <!-- Icon Section -->
        <div class="mb-10 flex justify-center">
            <img
                :src="config.images.siteIcon"
                alt="Logo"
                class="w-10 h-10"
            />
        </div>

        <!-- Status Code -->
        <h1
            class="text-8xl font-black text-primary-500 mb-4 tracking-tight drop-shadow-sm"
        >
            {{ status }}
        </h1>

        <!-- Title -->
        <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-6">
            {{ title }}
        </h2>

        <!-- Description -->
        <p
            class="text-gray-500 dark:text-gray-400 text-xl mb-12 max-w-lg mx-auto leading-relaxed"
        >
            {{ description }}
        </p>

        <!-- Button -->
        <div>
            <Link :href="route('dashboard.index')">
                <Button
                    label="Go back to Home"
                    icon="pi pi-home"
                    rounded
                    class="px-10 py-4 !font-bold shadow-xl shadow-primary-500/20 transition-all transform hover:-translate-y-1"
                />
            </Link>
        </div>
    </div>
</template>

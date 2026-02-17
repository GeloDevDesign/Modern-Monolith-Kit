<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { onMounted, computed } from 'vue';
import { useThemeStore } from '@/Stores/theme';
import { config } from "@/Utils/config";

const themeStore = useThemeStore();
const page = usePage();
const user = computed(() => page.props.auth.user);

onMounted(() => {
    themeStore.initTheme();
});
</script>

<template>
    <Head title="Maintenance" />

    <div class="min-h-screen bg-white dark:bg-zinc-950 flex flex-col items-center justify-center p-4 text-center transition-colors duration-300">
        <!-- Icon Section -->
        <div class="mb-10 flex justify-center">
            <img
                :src="config.images.siteIcon"
                alt="Logo"
                class="w-10 h-10"
            />
        </div>

        <!-- Maintenance Character -->
        <span class="text-9xl mb-6 block animate-bounce">🚧</span>
        
        <!-- Title -->
        <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-6">
            Under Maintenance
        </h2>
        
        <!-- Description -->
        <p class="text-gray-500 dark:text-gray-400 text-xl mb-12 max-w-lg mx-auto leading-relaxed">
            Our system is currently undergoing scheduled maintenance to bring you an even better experience. We'll be back online shortly.
        </p>

        <!-- Buttons -->
        <div class="flex gap-4">
            <Link :href="route('login')">
                <Button
                    label="Back to Login"
                    icon="pi pi-arrow-left"
                    rounded
                    class="px-8 py-3 !font-bold shadow-lg shadow-primary-500/20 transition-all transform hover:-translate-y-1"
                />
            </Link>
        </div>
    </div>
</template>

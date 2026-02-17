<script setup>
import { Link } from "@inertiajs/vue3";
import { onMounted } from "vue";
import { useThemeStore } from "@/Stores/theme";
import { config } from "@/Utils/config";

const themeStore = useThemeStore();

onMounted(() => {
    themeStore.initTheme();
});
</script>

<template>
    <div
        class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-zinc-950 p-6"
    >
        <div class="w-full max-w-md">
            <!-- Branding -->
            <div class="mb-8 text-center">
                <Link href="/" class="inline-flex items-center gap-3">
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-lg bg-primary-600 dark:bg-primary-500 text-white"
                    >
                        <i class="pi pi-th-large text-lg"></i>
                    </div>
                    <span
                        class="text-xl font-bold tracking-tight text-gray-900 dark:text-white"
                    >
                        {{ config.appName }}
                    </span>
                </Link>
            </div>

            <!-- Card -->
            <div
                class="bg-white dark:bg-zinc-900 rounded-2xl shadow-lg ring-1 ring-gray-900/5 dark:ring-white/10 p-8"
            >
                <slot />
            </div>


            <!-- Footer -->
            <div class="mt-8 text-center">
                <slot name="footer" />
                <p class="mt-4 text-xs text-gray-400 dark:text-gray-600">
                    &copy; {{ new Date().getFullYear() }} {{ config.appName }}.
                </p>
            </div>
        </div>
    </div>
</template>

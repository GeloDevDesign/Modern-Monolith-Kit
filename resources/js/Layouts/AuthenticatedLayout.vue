<script setup>
import { ref, computed, onMounted, onUnmounted, watch, nextTick } from "vue";
import { usePage, Head, router, Link } from "@inertiajs/vue3";
import { useSideBarStore } from "../stores/sidebar";
import { useThemeStore } from "@/Stores/theme";
import { useToast } from "primevue/usetoast";
import { route } from "ziggy-js";
import { config } from "@/Utils/config";

const store = useSideBarStore();
const themeStore = useThemeStore();
const toast = useToast();

import AppSidebar from "@/Components/AppSidebar.vue";
import Notifications from "@/Components/Notifications.vue";
import PrimaryColorSelector from "@/Components/Theme/PrimaryColorSelector.vue";

const isMobile = ref(false);
const drawerVisible = ref(false);
const preferencesDrawerVisible = ref(false);
const page = usePage();

const themeMode = ref("Light");
const themeOptions = ref(["Light", "Dark"]);

onMounted(() => {
    checkScreenSize();
    window.addEventListener("resize", checkScreenSize);

    themeStore.initTheme();
    themeMode.value = themeStore.isDark ? "Dark" : "Light";
});

watch(
    () => themeStore.isDark,
    (isDark) => {
        themeMode.value = isDark ? "Dark" : "Light";
    },
);

const onThemeChange = (value) => {
    if (!value) {
        // Prevent deselecting (keep current value if user clicks the active one)
        themeMode.value = themeStore.isDark ? "Dark" : "Light";
        return;
    }

    themeStore.setTheme(value.toLowerCase());
};

const checkScreenSize = () => {
    isMobile.value = window.innerWidth < 1024; // lg breakpoint
    if (!isMobile.value) {
        drawerVisible.value = false;
    }
};

const toggleSidebar = () => {
    if (isMobile.value) {
        drawerVisible.value = true;
    } else {
        store.collapsed = !store.collapsed;
    }
};

// TO CHECK

onUnmounted(() => {
    window.removeEventListener("resize", checkScreenSize);
});

watch(
    () => page.props.flash,
    async (flash) => {
        if (!flash) return;

        await nextTick();

        if (flash.success) {
            toast.add({
                severity: "success",
                summary: "Success",
                detail: flash.success,
                life: 3000,
            });
        }
        if (flash.error) {
            toast.add({
                severity: "error",
                summary: "Error",
                detail: flash.error,
                life: 5000,
            });
        }
    },
    { deep: true, immediate: true },
);

const props = defineProps({
    title: {
        type: String,
        default: null,
    },
    breadcrumbs: {
        type: Array,
        default: () => [],
    },
});

const currentBreadcrumbs = computed(() => {
    if (props.breadcrumbs && props.breadcrumbs.length > 0) {
        return props.breadcrumbs;
    }
    return page.props.breadcrumbs || [];
});
</script>

<template>
    <Head :title="`${title || store.currentPage} - ${config.appName}`" />
    <Toast
        position="top-right"
        :style="{ zIndex: 99999 }"
        :breakpoints="{ '920px': { width: '100%', right: '0', left: '0' } }"
    />
    <ConfirmDialog :draggable="false" />
    
    <!-- Preferences Drawer -->
    <Drawer
        v-if="!config.isProduction"
        v-model:visible="preferencesDrawerVisible"
        header="Preferences"
        position="right"
        class="!w-full sm:!w-96"
    >
        <div class="flex flex-col gap-6">
            <div>
                <h3
                    class="text-lg font-medium text-gray-900 dark:text-gray-100"
                >
                    Appearance
                </h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Customize how the application looks.
                </p>
            </div>

            <div class="flex flex-col gap-3">
                <span
                    class="text-sm font-medium text-gray-700 dark:text-gray-300"
                    >Theme</span
                >
                <SelectButton
                    v-model="themeMode"
                    :options="themeOptions"
                    @update:modelValue="onThemeChange"
                    :allowEmpty="false"
                    class="w-full"
                    :pt="{
                        button: ({ context }) => ({
                            class: [
                                'flex-1',
                                context.active
                                    ? 'bg-primary-500 border-primary-500 text-white'
                                    : 'bg-gray-100 dark:bg-zinc-800 border-gray-100 dark:border-zinc-800 text-gray-700 dark:text-gray-200',
                            ],
                        }),
                    }"
                />
            </div>

            <div class="flex flex-col gap-3">
                <span
                    class="text-sm font-medium text-gray-700 dark:text-gray-300"
                    >Primary Color</span
                >
                <PrimaryColorSelector />
            </div>
        </div>
    </Drawer>

    <div class="h-screen overflow-hidden bg-gray-100 dark:bg-zinc-900 flex">
        <!-- Desktop Sidebar -->
        <AppSidebar
            v-if="!isMobile"
            v-model:collapsed="store.collapsed"
            @show-preferences="preferencesDrawerVisible = true"
            class="flex-shrink-0"
        />

        <!-- Mobile Drawer -->
        <Drawer
            v-model:visible="drawerVisible"
            :show-close-icon="false"
            class="!w-auto !p-0 border-none"
            :pt="{
                root: { class: '!border-none !bg-transparent' },
                mask: {
                    class: 'backdrop-blur-sm !transition-all !duration-75',
                },
                header: { class: '!hidden' },
                content: { class: '!p-0' },
            }"
        >
            <AppSidebar :collapsed="false" class="!h-full !border-none" />
        </Drawer>

        <div
            class="flex-1 flex flex-col min-w-0 overflow-hidden transition-all duration-300"
        >
            <!-- Top Navigation / Header -->
            <header
                class="bg-white dark:bg-zinc-900 shadow h-16 flex items-center justify-between px-6 shrink-0 z-10 border-b border-gray-100 dark:border-zinc-800 gap-4"
            >
                <div class="flex items-center gap-4 flex-1 min-w-0">
                    <Button
                        @click="toggleSidebar"
                        text
                        rounded
                        :icon="
                            !isMobile && store.collapsed
                                ? 'pi pi-chevron-right'
                                : 'pi pi-chevron-left'
                        "
                        :aria-label="
                            !isMobile && store.collapsed
                                ? 'Expand Sidebar'
                                : 'Collapse Sidebar'
                        "
                        class="!w-8 !h-8 shrink-0"
                    />

                    <div
                        class="flex-1 min-w-0 overflow-x-auto whitespace-nowrap scrollbar-hide"
                    >
                        <Breadcrumb
                            v-if="currentBreadcrumbs.length"
                            :model="currentBreadcrumbs"
                            :pt="{
                                root: {
                                    class: 'bg-transparent border-none p-0',
                                },
                                separator: { class: 'text-gray-400 mx-2' },
                                list: { class: 'flex-nowrap' },
                            }"
                        >
                            <template #item="{ item }">
                                <Link
                                    v-if="item.route || item.url"
                                    :href="item.url ? item.url : route(item.route)"
                                    class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 hover:text-primary-500 dark:hover:text-primary-400 transition-colors whitespace-nowrap"
                                >
                                    <span :class="item.icon" v-if="item.icon" />
                                    <span v-if="item.label">{{
                                        item.label
                                    }}</span>
                                </Link>
                                <span
                                    v-else
                                    class="flex items-center gap-2 text-sm whitespace-nowrap"
                                    :class="[
                                        item ===
                                        currentBreadcrumbs[currentBreadcrumbs.length - 1]
                                            ? 'font-semibold text-primary-600 dark:text-primary-400'
                                            : 'text-gray-500 dark:text-gray-400',
                                    ]"
                                >
                                    <span :class="item.icon" v-if="item.icon" />
                                    <span v-if="item.label">{{
                                        item.label
                                    }}</span>
                                </span>
                            </template>
                        </Breadcrumb>

                        <!-- Fallback: Show title if no breadcrumbs provided (legacy support) -->
                        <h2
                            v-else-if="title"
                            class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight truncate"
                        >
                            {{ title }}
                        </h2>

                        <slot name="header" />
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <Notifications />
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6">
                <slot />
            </main>
        </div>
    </div>
</template>

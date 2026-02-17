<script setup>
import { ref, computed, watch, onMounted } from "vue";
import { Link, usePage, router } from "@inertiajs/vue3";
import { useSideBarStore } from "@/stores/sidebar.js";
import { useToast } from "primevue/usetoast";
import { useConfirm } from "primevue/useconfirm";
import { route } from "ziggy-js";
import { useRole } from "@/Composables/useRole";

import SidebarItem from "@/Components/SidebarItem.vue";
import LogoutConfirmModal from "@/Components/LogoutConfirmModal.vue";
import { config } from "@/Utils/config";

const { hasRole } = useRole();
const confirm = useConfirm();

const stores = ref([
    { name: "Capstone", code: "C" },
    { name: "Store 2", code: "S2" },
]);
const selectedStore = ref(stores.value[0]);

const confirmLogout = () => {
    confirm.require({
        group: "templating",
        message: "Are you sure you want to log out?",
        header: "Confirm Logout",
        icon: "pi pi-exclamation-triangle",
        rejectProps: {
            label: "Cancel",
            severity: "secondary",
            outlined: true,
            size: "small",
        },
        acceptProps: {
            label: "Yes, Logout",
            severity: "danger",
            size: "small",
        },
        accept: () => {
            router.post(route("logout"));
        },
        reject: () => {
            // Optional: toast.add(...) or do nothing
        },
    });
};

const props = defineProps({
    collapsed: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["update:collapsed", "show-preferences"]);
const page = usePage();
const sidebar = useSideBarStore();

const user = computed(
    () =>
        page.props.auth?.user || {
            name: "NO USER NAME",
            email: "NO EMAIL",
            first_name: "NO",
            last_name: "NAME",
        }
);

const menu = ref();
const menuItems = computed(() => [
    {
        label: "Profile",
        icon: "pi pi-user",
        command: () => {
            router.get(route("profile.edit"));
        },
    },
    ...(config.isProduction
        ? []
        : [
              {
                  label: "Preferences",
                  icon: "pi pi-cog",
                  command: () => {
                      emit("show-preferences");
                  },
              },
          ]),
    {
        separator: true,
    },
    {
        label: "Logout",
        icon: "pi pi-sign-out",
        command: () => {
            confirmLogout();
        },
    },
]);

const toggleMenu = (event) => {
    menu.value.toggle(event);
};

const nodes = sidebar.nodes;

const handleItemClick = (item) => {
    if (props.collapsed && item.children && item.children.length > 0) {
        emit("update:collapsed", false);
        item.expanded = true;
    }
};

watch(
    () => page.url,
    () => {
        sidebar.refreshActive();
    },
    { immediate: true },
);
</script>

<template>
    <div
        class="bg-primary-900 dark:bg-zinc-900 border-r border-primary-800 dark:border-zinc-800 transition-all duration-300 flex flex-col h-full overflow-x-hidden"
        :class="[collapsed ? 'w-20' : 'w-72']"
    >
        <!-- Header -->
        <div
            class="flex items-center py-4 shrink-0"
            :class="collapsed ? 'justify-center px-2' : 'justify-start px-4'"
        >
            <Link
                :href="route('dashboard.index')"
                class="inline-flex items-center gap-3 text-white no-underline"
            >
                <div
                    class="h-10 w-10 flex items-center justify-center rounded-lg bg-primary-800 dark:bg-zinc-800"
                >
                    <i class="pi pi-th-large text-xl"></i>
                </div>
                <span
                    v-if="!collapsed"
                    class="text-lg font-semibold tracking-tight"
                >
                    {{ config.appName || "Dashboard" }}
                </span>
            </Link>
        </div>
       

        <!-- Content -->
        <div class="flex-1 overflow-y-auto custom-scrollbar">
            

            <template v-for="(group, index) in nodes" :key="index">
                <div v-if="group.group" class="px-4 mt-6 mb-2">
                    <div
                        v-if="!collapsed"
                        class="text-xs font-semibold text-primary-200/70 uppercase tracking-wider pl-2"
                    >
                        {{ group.group }}
                    </div>
                    <div
                        v-else
                        class="text-[10px] text-center font-semibold text-primary-200/70 uppercase tracking-wider"
                    >
                        {{ group.group }}
                    </div>
                </div>
                <ul class="list-none px-4 py-0 m-0">
                    <template v-for="item in group.items" :key="item.key">
                        <SidebarItem
                            v-if="hasRole(item.roles)"
                            @item-click="handleItemClick"
                            :item="item"
                            :collapsed="collapsed"
                            :depth="0"
                        />
                    </template>
                </ul>
            </template>
        </div>

        <!-- Footer / User -->
        <div class="mt-auto">
            <hr
                class="mb-4 mx-4 border-t border-0 border-primary-800 dark:border-zinc-800"
            />
            <div class="px-4 pb-4">
                <div
                    v-ripple
                    @click="toggleMenu"
                    class="relative flex items-center cursor-pointer px-2 py-2 rounded-lg text-primary-100 dark:text-zinc-200 hover:bg-primary-500/50 dark:hover:bg-zinc-800 hover:text-white dark:hover:text-white duration-150 transition-colors p-ripple no-underline border border-transparent hover:border-primary-500/50 dark:hover:border-zinc-700"
                    :class="{ 'justify-center': collapsed }"
                >
                    <Avatar
                        :image="
                            user.avatar_url ||
                            `https://ui-avatars.com/api/?name=${user.first_name || 'U'}+${user.last_name || 'N'}&color=7F9CF5&background=EBF4FF`
                        "
                        shape="circle"
                        class="shrink-0 rounded-full overflow-hidden bg-primary-200"
                        :class="collapsed ? 'w-8 h-8' : 'w-8 h-8 mr-3'"
                    />
                    
                    <div v-if="!collapsed" class="flex flex-col items-start min-w-0 flex-1 overflow-hidden">
                        <span class="font-semibold text-sm text-white truncate w-full">{{ user.name }}</span>
                        <span class="text-xs text-primary-300 dark:text-zinc-400 truncate w-full">{{ user.email }}</span>
                    </div>
                    
                    <i v-if="!collapsed" class="pi pi-angle-up text-primary-300 dark:text-zinc-400 ml-2"></i>
                </div>
                <Menu ref="menu" :model="menuItems" :popup="true" />
            </div>
        </div>
        <LogoutConfirmModal group="templating" />
    </div>
</template>

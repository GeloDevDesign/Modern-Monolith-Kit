<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import { useSideBarStore } from "@/stores/sidebar.js";
import { useRole } from "@/Composables/useRole";

const { hasRole } = useRole();

const props = defineProps({
    item: { type: Object, required: true },
    collapsed: { type: Boolean, default: false },
    depth: { type: Number, default: 0 },
});

const sidebar = useSideBarStore();
const emit = defineEmits(["item-click"]);

// Toggle only if sidebar is OPEN
const toggleItem = (item) => {
    if (!props.collapsed) {
        item.expanded = !item.expanded;
        emit("item-click", item);
    }
};

const isBadgeIcon = (badge) => badge && typeof badge === 'string' && badge.startsWith('pi ');

const hasChildBadge = (item) => {
    if (item.badge) return true;
    if (item.children) return item.children.some(child => hasChildBadge(child));
    return false;
};

const showParentIndicator = computed(() => {
    return props.collapsed && props.item.children && hasChildBadge(props.item);
});
</script>

<template>
    <li v-if="!item.children">
        <Link
            :href="sidebar.resolveUrl(item)"
            v-ripple
            :style="{ paddingLeft: !collapsed ? (0.75 + depth * 1.5) + 'rem' : undefined }"
            @click="$emit('item-click', item)"
            class="relative flex items-center cursor-pointer px-3 py-2 rounded text-primary-100 dark:text-zinc-200 hover:bg-primary-500/50 dark:hover:bg-primary-900 hover:text-white dark:hover:text-white duration-150 transition-colors p-ripple no-underline mb-2"
            :class="{
                'bg-primary-500/20 text-white border border-primary-500/40 shadow-inner font-semibold': item.active,
                'justify-center': collapsed,
            }"
        >
            <i v-if="item.icon" :class="[item.icon, collapsed ? 'mr-0' : 'mr-2']" style="font-size: 1.1rem"></i>
            <span v-if="!collapsed" class="font-medium">{{ item.label }}</span>
            
            <span v-if="!collapsed && item.badge"
                  class="ml-auto flex items-center justify-center font-bold rounded-full px-2"
                  :class="[item.badgeColor || 'bg-red-500 text-white', isBadgeIcon(item.badge) ? 'p-1' : 'py-0.5 text-xs']">
                <i v-if="isBadgeIcon(item.badge)" :class="item.badge" style="font-size: 0.75rem;"></i>
                <span v-else>{{ item.badge }}</span>
            </span>

            <span v-if="collapsed && item.badge" class="absolute top-1.5 right-2 flex h-2.5 w-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" :class="item.badgeColor || 'bg-red-500'"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 border border-primary-900 dark:border-zinc-900" :class="item.badgeColor || 'bg-red-500'"></span>
            </span>
        </Link>
    </li>

    <li v-else class="relative group">
        <div
            v-ripple
            @click="toggleItem(item)"
            :style="{ paddingLeft: !collapsed ? (0.75 + depth * 1.5) + 'rem' : undefined }"
            class="relative flex items-center cursor-pointer px-3 py-2 rounded text-primary-100 dark:text-zinc-200 hover:bg-primary-500/50 dark:hover:bg-primary-800 hover:text-white dark:hover:text-white duration-150 transition-colors p-ripple mb-2"
            :class="{
                'bg-primary-500/10 text-white/90 font-semibold': item.active,
                'justify-center': collapsed,
            }"
        >
            <i :class="[item.icon, collapsed ? 'mr-0' : 'mr-2']" style="font-size: 1.1rem"></i>
            <span v-if="!collapsed" class="font-medium">{{ item.label }}</span>
            <i v-if="!collapsed" class="pi pi-chevron-down ml-auto transition-transform duration-200" :class="{ 'rotate-180': item.expanded }"></i>

            <span v-if="showParentIndicator" class="absolute top-1.5 right-2 flex h-2.5 w-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 bg-blue-500"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 border border-primary-900 dark:border-zinc-900 bg-blue-500"></span>
            </span>
        </div>

        <transition
            enter-active-class="overflow-hidden transition-all duration-300 ease-in-out"
            enter-from-class="max-h-0 opacity-0"
            enter-to-class="max-h-96 opacity-100"
            leave-active-class="overflow-hidden transition-all duration-300 ease-in-out"
            leave-from-class="max-h-96 opacity-100"
            leave-to-class="max-h-0 opacity-0"
        >
            <ul class="list-none py-0 pr-0 m-0 overflow-y-hidden" v-show="!collapsed && item.expanded">
                <template v-for="child in item.children" :key="child.key">
                    <SidebarItem v-if="hasRole(child.roles)" :item="child" :collapsed="collapsed" :depth="depth + 1" />
                </template>
            </ul>
        </transition>

        <div v-if="collapsed"
             class="fixed hidden group-hover:block ml-2 w-64 bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 shadow-xl rounded-lg z-50 py-2 overflow-y-auto max-h-80 custom-scrollbar"
             style="left: 3.6rem; margin-top: -2.5rem;">
            
            <div class="px-4 py-2 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider border-b border-gray-100 dark:border-zinc-800 mb-1 sticky top-0 bg-white dark:bg-zinc-900 z-10">
                {{ item.label }}
            </div>

            <template v-for="child in item.children" :key="child.key">
                <template v-if="child.children && child.children.length > 0">
                    <div v-if="hasRole(child.roles)" class="px-4 py-1.5 text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">
                        {{ child.label }}
                    </div>
                    <template v-for="grandChild in child.children" :key="grandChild.key">
                        <Link
                            v-if="hasRole(grandChild.roles)"
                            :href="sidebar.resolveUrl(grandChild)"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-primary-50 dark:hover:bg-zinc-800 hover:text-primary-600 dark:hover:text-primary-400 transition-colors no-underline pl-6"
                            :class="{ 'bg-primary-50 dark:bg-zinc-800/50 text-primary-600 dark:text-primary-400 font-semibold': grandChild.active }"
                        >
                            <i v-if="grandChild.icon" :class="[grandChild.icon, 'mr-2 text-gray-400 dark:text-gray-500 text-xs']"></i>
                            <span>{{ grandChild.label }}</span>
                             <span v-if="grandChild.badge"
                                  class="ml-auto flex items-center justify-center font-bold rounded-full px-2"
                                  :class="[grandChild.badgeColor || 'bg-red-500 text-white', isBadgeIcon(grandChild.badge) ? 'p-1' : 'py-0.5 text-[10px]']">
                                <i v-if="isBadgeIcon(grandChild.badge)" :class="grandChild.badge" style="font-size: 0.75rem;"></i>
                                <span v-else>{{ grandChild.badge }}</span>
                            </span>
                        </Link>
                    </template>
                </template>

                <Link
                    v-else-if="hasRole(child.roles)"
                    :href="sidebar.resolveUrl(child)"
                    class="flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-primary-50 dark:hover:bg-zinc-800 hover:text-primary-600 dark:hover:text-primary-400 transition-colors no-underline"
                    :class="{ 'bg-primary-50 dark:bg-zinc-800/50 text-primary-600 dark:text-primary-400 font-semibold': child.active }"
                >
                    <i :class="[child.icon, 'mr-3 text-gray-400 dark:text-gray-500']" style="font-size: 1rem;"></i>
                    <span>{{ child.label }}</span>
                    
                    <span v-if="child.badge"
                          class="ml-auto flex items-center justify-center font-bold rounded-full px-2"
                          :class="[child.badgeColor || 'bg-red-500 text-white', isBadgeIcon(child.badge) ? 'p-1' : 'py-0.5 text-[10px]']">
                        <i v-if="isBadgeIcon(child.badge)" :class="child.badge" style="font-size: 0.75rem;"></i>
                        <span v-else>{{ child.badge }}</span>
                    </span>
                </Link>
            </template>
        </div>
    </li>
</template>
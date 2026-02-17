import { defineStore } from "pinia";
import { ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";
import sidebarRoutes from "@/routes.js";

export const useSideBarStore = defineStore("sidebar", () => {
    const isOpen = ref(true);
    // Initialize collapsed state from localStorage
    const collapsed = ref(localStorage.getItem("sidebarCollapsed") === "true");
    
    // Watch for changes and persist to localStorage
    watch(collapsed, (newValue) => {
        localStorage.setItem("sidebarCollapsed", newValue);
    });

    const currentPage = ref("");

    // Helper: Get clean path without query strings or trailing slashes
    const getPath = (url) => {
        if (!url) return "/";
        try {
            const path = new URL(url, window.location.origin).pathname;
            return path.replace(/\/$/, "") || "/";
        } catch {
            return url.split("?")[0].replace(/\/$/, "") || "/";
        }
    };

    const resolveUrl = (item) => {
        if (item.url) return item.url;
        if (item.name) {
            return typeof route === "function"
                ? route(item.name)
                : `/${item.name.replaceAll(".", "/")}`;
        }
        return null;
    };

    const isActive = (item) => {
        return !!item.active;
    };

    const processItems = (items, prefix = "") => {
        return items.map((item, index) => {
            const key = prefix ? `${prefix}-${index}` : `${index}`;
            const newItem = { ...item, key, active: false, expanded: false };
            if (newItem.children) {
                newItem.children = processItems(newItem.children, key);
            }
            return newItem;
        });
    };

    const processGroups = (groups) => {
        return groups.map((group, index) => {
            return {
                ...group,
                items: processItems(group.items, `group-${index}`),
            };
        });
    };

    const nodes = ref(processGroups(sidebarRoutes()));

    const refreshActive = () => {
        const currentPath = getPath(usePage().url);
        let bestMatchNode = null;
        let bestMatchLength = -1;

        // Pass 1: Find best match (longest matching URL)
        const findBest = (list) => {
            for (const item of list) {
                if (item.group && item.items) {
                    findBest(item.items);
                    continue;
                }

                const targetUrl = resolveUrl(item);
                if (targetUrl) {
                    const targetPath = getPath(targetUrl);
                    // Match if exact or prefix
                    if (
                        targetPath === currentPath ||
                        (targetPath !== "/" &&
                            currentPath.startsWith(targetPath + "/"))
                    ) {
                        // Prefer longer matches (more specific)
                        if (targetPath.length > bestMatchLength) {
                            bestMatchLength = targetPath.length;
                            bestMatchNode = item;
                        }
                    }
                }

                if (item.children) {
                    findBest(item.children);
                }
            }
        };

        findBest(nodes.value);

        // Pass 2: Set active states recursively
        const applyState = (list) => {
            let anyChildActive = false;

            for (const item of list) {
                if (item.group && item.items) {
                    applyState(item.items);
                    continue;
                }

                // Reset first
                item.active = false;

                // Check children first
                let childActive = false;
                if (item.children) {
                    childActive = applyState(item.children);
                }

                // If this node is the best match
                if (item === bestMatchNode) {
                    item.active = true;
                    currentPage.value = item.label;
                }

                // If any child is active, this node is active (as parent)
                if (childActive) {
                    item.active = true;
                    item.expanded = true;
                }

                if (item.active) {
                    anyChildActive = true;
                }
            }
            return anyChildActive;
        };

        applyState(nodes.value);
    };

    watch(
        () => usePage().url,
        () => refreshActive(),
        { immediate: true },
    );

    const sideBarHover = () => {
        isOpen.value = !isOpen.value;
    };

    return {
        isOpen,
        collapsed,
        sideBarHover,
        nodes,
        resolveUrl,
        isActive,
        refreshActive,
        currentPage,
    };
});

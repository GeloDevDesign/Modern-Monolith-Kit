import { router } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { ref } from "vue";

export function useRequestParams(url = null) {
    const isLoading = ref(false);
    const baseUrl = url || route(route().current());

    const handlePageChange = (event) => {

        console.log(event);
        const params = { ...route().params };

        params.page = event.page + 1;
        params.per_page = event.rows;

        isLoading.value = true;
        router.get(baseUrl, params, {
            preserveState: true,
            preserveScroll: true,
            onFinish: () => {
                isLoading.value = false;
            },
        });
    };

    const handleSortChange = (event) => {
        console.log(event);
        const params = { ...route().params };

        params.sort_field = event.sortField;
        params.sort_order = event.sortOrder;

        isLoading.value = true;
        router.get(baseUrl, params, {
            preserveState: true,
            preserveScroll: true,
            onFinish: () => {
                isLoading.value = false;
            },
        });
    };

    return {
        handlePageChange,
        handleSortChange,
        isLoading,
    };
}

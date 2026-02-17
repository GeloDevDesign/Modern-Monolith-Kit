<script setup>
import { ref, reactive } from "vue";
import { router } from "@inertiajs/vue3";
import { route } from "ziggy-js";

const props = defineProps({
    routeName: { type: String, default: "" },
    hasSearch: { type: Boolean, default: true },
    hasUserType: { type: Boolean, default: false },
    dataUserRole: { type: Array, default: () => [] },
    hasDate: { type: Boolean, default: false },
    hasDateRange: { type: Boolean, default: false },
    numberOfMonths: { type: Number, default: 1 },
    hasLoginStatus: { type: Boolean, default: false },
    dataLoginStatus: { type: Array, default: () => [] },
    hasTrashed: { type: Boolean, default: false },
});

const filterData = reactive({
    search: route().params.search || "",
    role: route().params.role || null,
    login_status: route().params.login_status || null,
    date: route().params.date || null,
    date_range: route().params.date_range?.map((d) => new Date(d)) || null,
    trashed: route().params.trashed || null,
});

const isSearching = ref(false);
const isResetting = ref(false);

const submit = () => {
    // Filter out empty/null values before sending dont include on the request
    const params = Object.keys(filterData).reduce((acc, key) => {
        if (
            filterData[key] !== null &&
            filterData[key] !== "" &&
            filterData[key] !== undefined
        ) {
            acc[key] = filterData[key];
        }
        return acc;
    }, {});

    router.get(props.routeName || route(route().current()), params, {
        preserveState: true,
        preserveScroll: true,
        replace: true, // Optional: prevents creating too many history entries
        onStart: () => (isSearching.value = true),
        onFinish: () => (isSearching.value = false),
    });
};

const handleReset = () => {
    router.get(
        route(props.routeName || route().current()),
        {},
        {
            preserveState: false,
            preserveScroll: true,
            onStart: () => (isResetting.value = true),
            onFinish: () => (isResetting.value = false),
        },
    );
};
</script>

<template>
    <div
        class="p-4 bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 overflow-hidden"
    >
        <form
            @submit.prevent="submit"
            class="flex flex-col sm:flex-row gap-4 items-end sm:items-end w-full sm:w-auto"
        >
            <div v-if="hasUserType" class="w-full sm:w-48 flex flex-col gap-2">
                <label for="role" class="font-medium text-sm">Role</label>
                <Select
                    size="small"
                    id="role"
                    v-model="filterData.role"
                    :options="dataUserRole"
                    optionLabel="label"
                    optionValue="value"
                    placeholder="Select Role"
                    showClear
                    class="w-full"
                />
            </div>

            <div v-if="hasLoginStatus" class="w-full sm:w-48 flex flex-col gap-2">
                <label for="login_status" class="font-medium text-sm">Login Status</label>
                <Select
                    size="small"
                    id="login_status"
                    v-model="filterData.login_status"
                    :options="dataLoginStatus"
                    optionLabel="label"
                    optionValue="value"
                    placeholder="Select Login Status"
                    showClear
                    class="w-full"
                />
            </div>

            <div v-if="hasTrashed" class="w-full sm:w-48 flex flex-col gap-2">
                <label for="trashed" class="font-medium text-sm">Trashed</label>
                <Select
                    id="trashed"
                    v-model="filterData.trashed"
                    :options="[
                        { label: 'Include Deleted', value: 'with' },
                        { label: 'Deleted Only', value: 'only' },
                    ]"
                    size="small"
                    optionLabel="label"
                    optionValue="value"
                    placeholder="Filter Deleted"
                    showClear
                    class="w-full"
                />
            </div>  

            <div v-if="hasDate" class="w-full sm:w-48 flex flex-col gap-2">
                <label for="date" class="font-medium text-sm">Date</label>
                <DatePicker 
                    size="small"
                    id="date"
                    v-model="filterData.date"
                    placeholder="Select Date"
                    showIcon
                    class="w-full"
                />
            </div>

            <div v-if="hasDateRange" class="w-full sm:w-64 flex flex-col gap-2">
                <label for="date_range" class="font-medium text-sm"
                    >Date Range</label
                >
                <DatePicker 
                    size="small"
                    id="date_range"
                    v-model="filterData.date_range"
                    selectionMode="range"
                    placeholder="Select Date Range"
                    showIcon
                    :manualInput="false"
                    :numberOfMonths="numberOfMonths"
                    :hideOnRangeSelection="true"
                    showButtonBar
                    class="w-full"
                    panelClass="text-sm"
                />
            </div>

            <div v-if="hasSearch" class="w-full sm:w-auto flex flex-col gap-2">
                <label for="search" class="font-medium text-sm">Search</label>
                <IconField iconPosition="left" class="w-full sm:w-auto">
                    <InputIcon class="pi pi-search" />
                    <InputText
                        size="small"
                        id="search"
                        v-model="filterData.search"
                        placeholder="Search..."
                        class="w-full sm:w-64"
                    />
                </IconField>
            </div>

            <div class="flex gap-2 w-full sm:w-auto">
                <Button
                    type="button"
                    :label="isResetting ? 'Resetting...' : 'Reset'"
                    icon="pi pi-refresh"
                    size="small"
                    severity="secondary"
                    @click="handleReset"
                    :loading="isResetting"
                    :disabled="isSearching || isResetting"
                />
                <Button
                    type="submit"
                    :label="isSearching ? 'Searching...' : 'Search'"
                    icon="pi pi-search"
                    :loading="isSearching"
                    :disabled="isSearching || isResetting"
                    size="small"
                    severity="primary"
                />
            </div>
        </form>
    </div>
</template>

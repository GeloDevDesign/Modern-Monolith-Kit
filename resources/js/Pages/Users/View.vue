<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import PageHeader from "@/Components/PageHeader.vue";
import DataTable from "primevue/datatable";
import Column from "primevue/column";

import Tag from "primevue/tag";
import EntityActions from "@/Components/EntityActions.vue";
import Filters from "@/Components/Filters.vue";

import { useRequestParams } from "@/Composables/useRequestParams";

const props = defineProps({
    users: Object,
    roles: Array,
    loginStatuses: Array,
});

const { handlePageChange, handleSortChange, isLoading } = useRequestParams();

// Actions

const getSeverity = (status) => {
    switch (status) {
        case "Active":
            return "success";
        case "Inactive":
            return "warn";
        case "Banned":
            return "danger";
        default:
            return null;
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="mx-auto flex flex-col gap-6">
            <PageHeader
                title="User Management"
                description="Manage users, roles, and permissions."
            >
                <template #actions>
                    <Link :href="route('users.create')" class="w-full sm:w-auto">
                        <Button
                            label="Add New User"
                            icon="pi pi-plus"
                            size="small"
                            severity="primary"
                            class="w-full sm:w-auto"
                        />
                    </Link>
                </template>
            </PageHeader>

            <!-- Toolbar -->
            <Filters
                :hasUserType="true"
                :hasSearch="true"
                :hasTrashed="true"
                :hasDateRange="true"
                :dataUserRole="roles"
                :hasLoginStatus="true"
                :dataLoginStatus="props.loginStatuses"
            />

            <!-- DataTable -->
            <DataTable
                :value="props.users.data"
                showGridlines
                lazy
                :loading="isLoading"
                paginator
                :rows="props.users.per_page"
                :totalRecords="props.users.total"
                :first="(props.users.current_page - 1) * props.users.per_page"
                @page="handlePageChange"
                @sort="handleSortChange"
                :sortField="route().params.sort_field"
                :sortOrder="route().params.sort_order ? Number(route().params.sort_order) : null"
                :rowsPerPageOptions="[2, 10, 20, 50, 100, 200, 500]"
                tableStyle="min-width: 50rem"
            >
                <template #empty>
                    <div
                        class="text-center py-8 text-gray-500 dark:text-gray-400"
                    >
                        No users found.
                    </div>
                </template>

                <Column field="name" header="Name" sortable>
                    <template #body="slotProps">
                        <div
                            class="flex items-center gap-3 p-2 -m-2 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors group"
                        >
                            <img
                                v-if="slotProps.data.avatar_url"
                                :src="slotProps.data.avatar_url"
                                :alt="slotProps.data.name"
                                class="w-8 h-8 rounded-full object-cover"
                            />
                            <div
                                v-else
                                class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-700 dark:text-primary-300 font-bold text-xs group-hover:bg-primary-200 dark:group-hover:bg-primary-900/50 transition-colors"
                            >
                                {{ slotProps.data.name.charAt(0) }}
                            </div>
                            <div class="flex flex-col">
                                <span
                                    class="font-medium text-gray-900 dark:text-gray-100 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors"
                                    >{{ slotProps.data.name }}</span
                                >
                                <span class="text-xs text-gray-500">{{
                                    slotProps.data.email
                                }}</span>
                            </div>
                        </div>
                    </template>
                </Column>
                <Column field="role" header="Role" sortable>
                    <template #body="slotProps">
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-700 dark:bg-zinc-800 dark:text-zinc-300"
                        >
                            {{ slotProps.data.role }}
                        </span>
                    </template>
                </Column>
                <Column field="status" header="Status" sortable>
                    <template #body="slotProps">
                        <Tag
                            v-if="slotProps.data.deleted_at"
                            value="Deleted"
                            severity="danger"
                        />
                        <Tag
                            v-else
                            :value="slotProps.data.status"
                            :severity="getSeverity(slotProps.data.status)"
                        />
                    </template>
                </Column>
                <Column
                    field="last_login"
                    header="Last Login"
                    sortable
                    class="hidden sm:table-cell"
                >
                    <template #body="slotProps">
                        {{ slotProps.data.last_login || "Never" }}
                    </template>
                </Column>
                <Column
                    header="Actions"
                    :exportable="false"
                    style="min-width: 8rem"
                >
                    <template #body="slotProps">
                        <EntityActions
                            :editUrl="route('users.edit', slotProps.data.id)"
                            :deleteUrl="route('users.destroy', slotProps.data.id)"
                            :viewUrl="route('users.show', slotProps.data.id)"
                            :restoreUrl="route('users.restore', slotProps.data.id)"
                            :forceDeleteUrl="route('users.force-delete', slotProps.data.id)"
                            :hasView="true"
                            :deletedAt="slotProps.data.deleted_at"
                        />
                    </template>
                </Column>
            </DataTable>
        </div>
    </AuthenticatedLayout>
</template>

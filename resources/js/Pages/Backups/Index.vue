<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, useForm, Link } from "@inertiajs/vue3";
import PageHeader from "@/Components/PageHeader.vue";
import { ref } from "vue";

import { useRequestParams } from "@/Composables/useRequestParams";
import { route } from "ziggy-js";

const props = defineProps({
    backups: {
        type: Object,
        default: () => ({}),
    },
});

const { handlePageChange, handleSortChange, isLoading } = useRequestParams();

const form = useForm({});

const createDatabaseBackup = () => {
    form.post(route("backups.generate-db"));
};

const createFullBackup = () => {
    form.post(route("backups.generate-full"));
};

const deleteBackup = (backup) => {
    if (confirm("Are you sure you want to delete this backup?")) {
        router.delete(route("backups.delete", { file: backup.path }));
    }
};

const getSeverity = (type) => {
    switch (type) {
        case "Database":
            return "info";
        case "Full Backup":
            return "warn";
        default:
            return "secondary";
    }
};

const downloadBackup = (backup) => {
    window.location.href = route("backups.download", { file: backup.path });
};
</script>

<template>
    <AuthenticatedLayout title="Backups">
        <div class="mx-auto flex flex-col gap-6">
            <PageHeader
                title="Backups"
                description="Manage system database and file backups."
            >
                <template #actions>
                    <div
                        class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto"
                    >
                        <Button
                            label="Create Database Backup"
                            icon="pi pi-database"
                            size="small"
                            severity="primary"
                            class="w-full sm:w-auto"
                            :loading="form.processing"
                            @click="createDatabaseBackup"
                        />
                        <Button
                            label="Create Full Backup"
                            icon="pi pi-folder"
                            severity="secondary"
                            outlined
                            size="small"
                            class="w-full sm:w-auto"
                            :loading="form.processing"
                            @click="createFullBackup"
                        />
                    </div>
                </template>
            </PageHeader>

            <Filters
                :hasSearch="true"
                :hasUserType="false"
                :hasStatus="false"
                :hasTrashed="false"
                :hasDateRange="false"
            />

            <div class="">
                <!-- DataTable -->
                <DataTable
                    :value="props.backups.data"
                    showGridlines
                    lazy
                    :loading="isLoading"
                    paginator
                    :rows="props.backups.per_page"
                    :totalRecords="props.backups.total"
                    :first="
                        (props.backups.current_page - 1) *
                        props.backups.per_page
                    "
                    @page="handlePageChange"
                    @sort="handleSortChange"
                    :sortField="route().params.sort_field"
                    :sortOrder="
                        route().params.sort_order
                            ? Number(route().params.sort_order)
                            : null
                    "
                    :rowsPerPageOptions="[5, 10, 20, 50, 100]"
                    tableStyle="min-width: 50rem"
                >
                    <template #empty>
                        <div
                            class="text-center py-8 text-gray-500 dark:text-gray-400"
                        >
                            No backups found.
                        </div>
                    </template>

                    <Column field="date" header="Backup Date" sortable>
                        <template #body="slotProps">
                            <span class="font-medium">{{
                                slotProps.data.date
                            }}</span>
                        </template>
                    </Column>

                    <Column field="type" header="Type" sortable>
                        <template #body="slotProps">
                            <Tag
                                :value="slotProps.data.type"
                                :severity="getSeverity(slotProps.data.type)"
                                class="!text-[10px] !px-2 !py-0.5"
                            />
                        </template>
                    </Column>

                    <Column field="size" header="File Size" sortable>
                        <template #body="slotProps">
                            <span class="font-mono text-xs">{{
                                slotProps.data.size
                            }}</span>
                        </template>
                    </Column>

                    <Column field="filename" header="File" sortable>
                        <template #body="slotProps">
                            <div class="flex items-center gap-2">
                                <i
                                    :class="
                                        slotProps.data.type === 'Database'
                                            ? 'pi pi-database text-blue-500'
                                            : 'pi pi-folder text-yellow-500'
                                    "
                                ></i>
                                <span
                                    class="text-gray-600 dark:text-gray-400 truncate max-w-[200px]"
                                    :title="slotProps.data.filename"
                                >
                                    {{ slotProps.data.filename }}
                                </span>
                            </div>
                        </template>
                    </Column>

                    <Column header="Actions" class="w-32">
                        <template #body="slotProps">
                            <div class="flex gap-2">
                                <Button
                                    icon="pi pi-download"
                                    severity="secondary"
                                    text
                                    rounded
                                    aria-label="Download"
                                    @click="downloadBackup(slotProps.data)"
                                    v-tooltip.top="'Download'"
                                />
                                <Button
                                    icon="pi pi-trash"
                                    severity="danger"
                                    text
                                    rounded
                                    aria-label="Delete"
                                    @click="deleteBackup(slotProps.data)"
                                    v-tooltip.top="'Delete'"
                                />
                            </div>
                        </template>
                    </Column>
                </DataTable>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

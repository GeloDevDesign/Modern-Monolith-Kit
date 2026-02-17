<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import PageHeader from "@/Components/PageHeader.vue";
import { ref, computed } from "vue";
import { FilterMatchMode } from '@primevue/core/api';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import Tag from 'primevue/tag';
import SelectButton from 'primevue/selectbutton';

const generateRandomLogs = () => {
    const actions = [
        { status: 'Logged In', icon: 'pi pi-sign-in', severity: 'success', description: 'Successfully logged into the system' },
        { status: 'Updated Profile', icon: 'pi pi-user-edit', severity: 'info', description: 'Updated profile information' },
        { status: 'Changed Password', icon: 'pi pi-key', severity: 'warn', description: 'Changed account password' },
        { status: 'Created Ticket', icon: 'pi pi-ticket', severity: 'secondary', description: 'Opened a new support ticket' },
        { status: 'Downloaded Report', icon: 'pi pi-download', severity: 'help', description: 'Downloaded monthly activity report' },
        { status: 'Viewed Dashboard', icon: 'pi pi-chart-bar', severity: 'danger', description: 'Viewed main dashboard analytics' },
        { status: 'Deleted User', icon: 'pi pi-trash', severity: 'danger', description: 'Deleted user account' },
        { status: 'Restored User', icon: 'pi pi-refresh', severity: 'success', description: 'Restored user account' },
    ];

    const users = [
        { name: 'John Doe', email: 'john@example.com' },
        { name: 'Jane Smith', email: 'jane@example.com' },
        { name: 'Alice Johnson', email: 'alice@example.com' },
        { name: 'Bob Brown', email: 'bob@example.com' },
        { name: 'Charlie Davis', email: 'charlie@example.com' },
        { name: 'Diana Evans', email: 'diana@example.com' },
        { name: 'Evan Foster', email: 'evan@example.com' },
        { name: 'Fiona Green', email: 'fiona@example.com' },
    ];

    const logs = [];
    for (let i = 0; i < 50; i++) {
        const action = actions[Math.floor(Math.random() * actions.length)];
        const user = users[Math.floor(Math.random() * users.length)];
        const date = new Date();
        date.setDate(date.getDate() - Math.floor(Math.random() * 30));
        date.setHours(Math.floor(Math.random() * 24), Math.floor(Math.random() * 60));

        logs.push({
            id: i + 1,
            user: user,
            action: action.status,
            description: action.description,
            severity: action.severity,
            icon: action.icon,
            date: date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
            ip: `192.168.1.${Math.floor(Math.random() * 255)}`,
            browser: Math.random() > 0.5 ? 'Chrome' : 'Firefox'
        });
    }
    return logs.sort((a, b) => new Date(b.date) - new Date(a.date));
};

const logs = ref(generateRandomLogs());

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const actionFilter = ref('All');
const actionOptions = ['All', 'Login', 'Update', 'Delete', 'Other'];

const filteredLogs = computed(() => {
    if (actionFilter.value === 'All') return logs.value;
    
    return logs.value.filter(log => {
        if (actionFilter.value === 'Login') return log.action.includes('Log');
        if (actionFilter.value === 'Update') return log.action.includes('Update') || log.action.includes('Change');
        if (actionFilter.value === 'Delete') return log.action.includes('Delete');
        if (actionFilter.value === 'Other') return !['Log', 'Update', 'Change', 'Delete'].some(k => log.action.includes(k));
        return true;
    });
});

const selectButtonPt = {
    root: { class: 'flex' },
    button: ({ context }) => ({
        class: [
            'px-4 py-2 text-sm font-medium transition-colors border-y border-l last:border-r first:rounded-l-md last:rounded-r-md border-gray-200 dark:border-zinc-700 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:z-10',
            context.active 
                ? 'bg-primary-50 text-primary-700 border-primary-200 dark:bg-primary-500/20 dark:text-primary-300 dark:border-primary-500/30 z-10' 
                : 'bg-white text-gray-700 hover:bg-gray-50 dark:bg-zinc-900 dark:text-gray-300 dark:hover:bg-zinc-800'
        ]
    })
};
</script>

<template>
    <Head title="Activity Logs" />

    <AuthenticatedLayout>
        <div class="mx-auto flex flex-col gap-6">
            <PageHeader
                title="Activity Logs"
                description="View and track system activities and user actions."
            />

            <div class="bg-white dark:bg-zinc-900 shadow-sm sm:rounded-xl border border-gray-100 dark:border-zinc-800 overflow-hidden">
                <!-- Toolbar -->
                <div class="p-4 border-b border-gray-100 dark:border-zinc-800 flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
                    <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center w-full sm:w-auto">
                        <div class="w-full sm:w-auto overflow-x-auto pb-2 sm:pb-0">
                            <SelectButton v-model="actionFilter" :options="actionOptions" aria-labelledby="basic" :pt="selectButtonPt" class="whitespace-nowrap" />
                        </div>
                        
                        <IconField iconPosition="left" class="w-full sm:w-auto">
                            <InputIcon class="pi pi-search" />
                            <InputText v-model="filters['global'].value" placeholder="Search logs..." class="w-full sm:w-64" />
                        </IconField>
                    </div>
                    <Button icon="pi pi-download" label="Export" severity="secondary" outlined size="small" class="w-full sm:w-auto" />
                </div>

                <!-- DataTable -->
                <DataTable 
                    v-model:filters="filters"
                    :value="filteredLogs" 
                    paginator 
                    :rows="10" 
                    :rowsPerPageOptions="[5, 10, 20, 50]"
                    tableStyle="min-width: 50rem"
                    :globalFilterFields="['user.name', 'user.email', 'action', 'description', 'ip']"
                    :pt="{
                        thead: { class: 'bg-gray-50 dark:bg-zinc-800/50' },
                        headerRow: { class: 'bg-transparent' },
                        headerCell: { class: 'bg-transparent text-gray-500 dark:text-gray-400 font-medium text-xs uppercase tracking-wider py-3' },
                        bodyRow: { class: 'hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors' },
                        bodyCell: { class: 'py-4 text-sm text-gray-700 dark:text-gray-300 border-b border-gray-100 dark:border-zinc-800' },
                        paginator: { root: { class: 'bg-transparent border-t border-gray-100 dark:border-zinc-800' } }
                    }"
                >
                    <template #empty>
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            No activity logs found.
                        </div>
                    </template>

                    <Column field="user.name" header="User" sortable>
                        <template #body="slotProps">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-700 dark:text-primary-300 font-bold text-xs">
                                    {{ slotProps.data.user.name.charAt(0) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ slotProps.data.user.name }}</span>
                                    <span class="text-xs text-gray-500">{{ slotProps.data.user.email }}</span>
                                </div>
                            </div>
                        </template>
                    </Column>

                    <Column field="action" header="Action" sortable>
                        <template #body="slotProps">
                            <Tag :value="slotProps.data.action" :severity="slotProps.data.severity" size="small" />
                        </template>
                    </Column>

                    <Column field="description" header="Description" sortable class="hidden md:table-cell">
                        <template #body="slotProps">
                            {{ slotProps.data.description }}
                        </template>
                    </Column>

                    <Column field="date" header="Date" sortable>
                        <template #body="slotProps">
                            <span class="text-gray-500 text-xs">{{ slotProps.data.date }}</span>
                        </template>
                    </Column>

                    <Column field="ip" header="IP Address" sortable class="hidden lg:table-cell">
                        <template #body="slotProps">
                            <span class="font-mono text-xs text-gray-500">{{ slotProps.data.ip }}</span>
                        </template>
                    </Column>
                </DataTable>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
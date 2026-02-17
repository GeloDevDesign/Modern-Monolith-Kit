<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import PageHeader from "@/Components/PageHeader.vue";
import Button from "primevue/button";
import Tag from "primevue/tag";
import { route } from "ziggy-js";

const props = defineProps({
    user: Object,
});

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
    <AuthenticatedLayout title="View User">
        <div class="mx-auto flex flex-col gap-6">
            <PageHeader
                title="User Details"
                description="View complete user information."
            >
                <template #actions>
                    <div class="flex gap-2">
                        <Link :href="route('users.edit', user.id)">
                            <Button
                                label="Edit User"
                                icon="pi pi-pencil"
                                severity="info"
                                outlined
                                size="small"
                            />
                        </Link>
                        <Link :href="route('users.index')">
                            <Button
                                label="Back to List"
                                icon="pi pi-arrow-left"
                                severity="secondary"
                                outlined
                                size="small"
                            />
                        </Link>
                    </div>
                </template>
            </PageHeader>

            <div
                class="bg-white dark:bg-zinc-900 shadow-sm sm:rounded-xl border border-gray-100 dark:border-zinc-800 overflow-hidden"
            >
                <div class="p-8">
                    <div class="flex flex-col md:flex-row gap-10 items-start">
                        <!-- Avatar Section (Left Side) -->
                        <div class="w-full md:w-80 shrink-0 flex flex-col items-center gap-6">
                            <div class="relative group">
                                <div class="p-1 rounded-full border-2 border-dashed border-gray-200 dark:border-zinc-700">
                                    <img
                                        v-if="user.avatar_url"
                                        :src="user.avatar_url"
                                        :alt="user.name"
                                        class="w-48 h-48 rounded-full object-cover shadow-lg ring-4 ring-white dark:ring-zinc-800"
                                    />
                                    <div
                                        v-else
                                        class="w-48 h-48 rounded-full bg-primary-50 dark:bg-zinc-800 flex items-center justify-center shadow-lg ring-4 ring-white dark:ring-zinc-800"
                                    >
                                        <span class="text-6xl font-bold text-primary-500 dark:text-zinc-500">
                                            {{ user.first_name?.charAt(0) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">
                                    {{ user.first_name }} {{ user.middle_name }} {{ user.last_name }}
                                </h2>
                                <p class="text-gray-500 dark:text-gray-400 font-medium">{{ user.email }}</p>
                                <div class="mt-4 flex justify-center gap-2">
                                    <Tag :value="user.role" severity="info" class="px-3 py-1 text-sm uppercase tracking-wide" />
                                    <Tag :value="user.status" :severity="getSeverity(user.status)" class="px-3 py-1 text-sm uppercase tracking-wide" />
                                </div>
                            </div>
                        </div>

                        <!-- Details Section (Right Side) -->
                        <div class="flex-1 w-full grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-8 py-2">
                            <div class="col-span-1 md:col-span-2 pb-2 border-b border-gray-100 dark:border-zinc-800">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                    <i class="pi pi-id-card text-primary-500"></i>
                                    Personal Information
                                </h3>
                            </div>

                            <div class="flex flex-col gap-1">
                                <span class="text-sm text-gray-500 dark:text-gray-400">First Name</span>
                                <span class="font-medium text-gray-900 dark:text-gray-200 text-lg">{{ user.first_name }}</span>
                            </div>

                            <div class="flex flex-col gap-1">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Last Name</span>
                                <span class="font-medium text-gray-900 dark:text-gray-200 text-lg">{{ user.last_name }}</span>
                            </div>

                            <div class="flex flex-col gap-1">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Middle Name</span>
                                <span class="font-medium text-gray-900 dark:text-gray-200 text-lg">{{ user.middle_name || '-' }}</span>
                            </div>

                            <div class="flex flex-col gap-1">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Email Address</span>
                                <span class="font-medium text-gray-900 dark:text-gray-200 text-lg">{{ user.email }}</span>
                            </div>

                            <div class="col-span-1 md:col-span-2 pt-6 pb-2 border-b border-gray-100 dark:border-zinc-800">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                    <i class="pi pi-clock text-primary-500"></i>
                                    Activity & Metadata
                                </h3>
                            </div>

                            <div class="flex flex-col gap-1">
                                <span class="text-sm text-gray-500 dark:text-gray-400">User Created</span>
                                <span class="font-medium text-gray-900 dark:text-gray-200">{{ user.created_at }}</span>
                            </div>

                            <div class="flex flex-col gap-1">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Last Login</span>
                                <span class="font-medium text-gray-900 dark:text-gray-200">{{ user.last_login || 'Never' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

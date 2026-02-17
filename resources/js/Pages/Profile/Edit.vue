<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import { Head } from "@inertiajs/vue3";
import PageHeader from "@/Components/PageHeader.vue";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const breadcrumbs = [
    { icon: 'pi pi-home', route: 'dashboard.index' },
    { label: 'Profile' }
];
</script>

<template>
    <AuthenticatedLayout :title="'Profile'" :breadcrumbs="breadcrumbs">
        <div class="mx-auto flex flex-col gap-6">
            <PageHeader title="Profile" description="Manage your account settings and password." />
            
            <div class="bg-white dark:bg-zinc-900 shadow-sm sm:rounded-xl border border-gray-100 dark:border-zinc-800 overflow-hidden">
                <Tabs value="0">
                    <TabList>
                        <Tab value="0">Profile Information</Tab>
                        <Tab value="1">Update Password</Tab>
                        <Tab value="2" class="!text-red-500">Delete Account</Tab>
                    </TabList>
                    <TabPanels>
                        <TabPanel value="0">
                            <div class="p-4 sm:p-8">
                                <UpdateProfileInformationForm
                                    :must-verify-email="mustVerifyEmail"
                                    :status="status"
                                    class="max-w-4xl"
                                />
                            </div>
                        </TabPanel>
                        <TabPanel value="1">
                             <div class="p-4 sm:p-8">
                                <UpdatePasswordForm class="max-w-xl" />
                             </div>
                        </TabPanel>
                        <TabPanel value="2">
                             <div class="p-4 sm:p-8">
                                <DeleteUserForm class="max-w-xl" />
                             </div>
                        </TabPanel>
                    </TabPanels>
                </Tabs>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

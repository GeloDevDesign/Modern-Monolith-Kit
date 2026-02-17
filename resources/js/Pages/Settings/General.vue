<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import PageHeader from "@/Components/PageHeader.vue";
import { ref } from "vue";
import { useToast } from "primevue/usetoast";
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import Tabs from 'primevue/tabs';
import TabList from 'primevue/tablist';
import Tab from 'primevue/tab';
import TabPanels from 'primevue/tabpanels';
import TabPanel from 'primevue/tabpanel';
import Editor from 'primevue/editor';
import ToggleSwitch from 'primevue/toggleswitch';

const breadcrumbs = [
    { icon: 'pi pi-home', route: 'dashboard.index' },
    { label: 'Settings' },
    { label: 'General', route: 'settings.general' }
];

const toast = useToast();

const emailForm = useForm({
    maintenance_mode: false,
    maintenance_email: 'admin@example.com', // Default or loaded from props
});

const termsForm = useForm({
    content: '',
});

const saveEmailSettings = () => {
    emailForm.post(route('settings.general.update'), {
        preserveScroll: true,
    });
};

const saveTerms = () => {
    termsForm.post(route('settings.general.update'), {
        preserveScroll: true,
    });
};

const generateTerms = () => {
    termsForm.content = `TERMS AND CONDITIONS

1. ACCEPTANCE OF TERMS
By accessing and using this website, you accept and agree to be bound by the terms and provision of this agreement.

2. PROVISION OF SERVICES
We reserve the right to modify or discontinue the service with or without notice to the user.

3. PRIVACY POLICY
Your privacy is important to us. Please refer to our Privacy Policy for information on how we collect and use your data.

4. USER CONDUCT
You agree to use the website only for lawful purposes and in a way that does not infringe the rights of, restrict or inhibit anyone else's use and enjoyment of the website.

5. TERMINATION
We may terminate your access to the site, without cause or notice, which may result in the forfeiture and destruction of all information associated with you.

Generated on: ${new Date().toLocaleDateString()}`;
    
    toast.add({ severity: 'info', summary: 'Generated', detail: 'Default Terms and Conditions generated.', life: 3000 });
};
</script>

<template>
  <Head title="General Settings" />

  <AuthenticatedLayout :breadcrumbs="breadcrumbs">
    <div class="mx-auto flex flex-col gap-6">
       <PageHeader title="General Settings" description="Manage system notifications and legal documents." />

       <div class="bg-white dark:bg-zinc-900 shadow-sm sm:rounded-xl border border-gray-100 dark:border-zinc-800 overflow-hidden">
          <Tabs value="0">
            <TabList>
                <Tab value="0">Maintenance</Tab>
                <Tab value="1">Terms and Conditions</Tab>
            </TabList>
            <TabPanels>
                <TabPanel value="0">
                    <div class="flex flex-col gap-6 p-4">
                        <div class="flex flex-col gap-2">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Maintenance Mode</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Enable or disable system maintenance mode. When enabled, only administrators can access the system.
                            </p>
                        </div>

                        <form @submit.prevent="saveEmailSettings" class="flex flex-col gap-6 max-w-md">
                            <div class="flex items-center gap-3 p-4 border border-gray-100 dark:border-zinc-800 rounded-lg bg-gray-50 dark:bg-zinc-800/50">
                                <ToggleSwitch v-model="emailForm.maintenance_mode" inputId="maintenance_mode" />
                                <label for="maintenance_mode" class="cursor-pointer flex flex-col">
                                    <span class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ emailForm.maintenance_mode ? 'Maintenance is ON' : 'Maintenance is OFF' }}
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ emailForm.maintenance_mode ? 'Users cannot access the system.' : 'System is live and accessible.' }}
                                    </span>
                                </label>
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="maintenance_email" class="text-sm font-medium text-gray-700 dark:text-gray-300">Notification Email</label>
                                <InputText id="maintenance_email" v-model="emailForm.maintenance_email" placeholder="admin@example.com" class="w-full" />
                                <small class="text-gray-500 dark:text-gray-400">This email will receive updates when maintenance status changes.</small>
                            </div>

                            <div class="flex justify-end">
                                <Button type="submit" label="Save Changes" :loading="emailForm.processing" />
                            </div>
                        </form>
                    </div>
                </TabPanel>
                
                <TabPanel value="1">
                    <div class="flex flex-col gap-6 p-4">
                         <div class="flex flex-col gap-2">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Terms and Conditions</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Edit or generate the Terms and Conditions for the application.
                            </p>
                        </div>

                        <form @submit.prevent="saveTerms" class="flex flex-col gap-4">
                            <div class="flex flex-col gap-2">
                                <div class="flex justify-between items-center mb-2">
                                    <label for="terms_content" class="text-sm font-medium text-gray-700 dark:text-gray-300">Content</label>
                                    <Button type="button" label="Generate Default" icon="pi pi-refresh" size="small" severity="secondary" @click="generateTerms" />
                                </div>
                                <div class="card">
                                    <Editor v-model="termsForm.content" editorStyle="height: 320px" />
                                </div>
                                <small v-if="termsForm.errors.content" class="text-red-500">{{ termsForm.errors.content }}</small>
                            </div>

                            <div class="flex justify-end">
                                <Button type="submit" label="Save Terms" :loading="termsForm.processing" />
                            </div>
                        </form>
                    </div>
                </TabPanel>
            </TabPanels>
          </Tabs>
        </div>
    </div>
  </AuthenticatedLayout>
</template>

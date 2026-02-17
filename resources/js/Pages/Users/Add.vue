<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import PageHeader from "@/Components/PageHeader.vue";
import { useToast } from "primevue/usetoast";
import Dropdown from "primevue/dropdown";
import { route } from "ziggy-js";
import { ref } from "vue";
import FileUpload from "primevue/fileupload";

const toast = useToast();
const fileUpload = ref();
const previewUrl = ref(null);

const props = defineProps({
    roles: Array,
});

const form = useForm({
    first_name: "",
    middle_name: "",
    last_name: "",
    email: "",
    role: null,
    password: "",
    password_confirmation: "",
    avatar: null,
});

const onSelectedFiles = (event) => {
    const file = event.files[0];
    form.avatar = file;
    previewUrl.value = URL.createObjectURL(file);
};

const onClearFiles = () => {
    form.avatar = null;
    previewUrl.value = null;
};

const submit = () => {
    form.post(route("users.store"), {
        onSuccess: () => {
            form.reset();
            fileUpload.value.clear();
            previewUrl.value = null;
        },
    });
};
</script>

<template>
    <AuthenticatedLayout :title="'Add User'">
        <div class="mx-auto flex flex-col gap-6">
            <PageHeader
                title="Add User"
                description="Create a new user account."
            />

            <div
                class="bg-white dark:bg-zinc-900 shadow-sm sm:rounded-xl border border-gray-100 dark:border-zinc-800 overflow-hidden"
            >
                <div class="p-6">
                    <form @submit.prevent="submit">
                        <div
                            class="flex flex-col md:flex-row gap-8 items-start"
                        >
                            <!-- Avatar Section (Left Side) -->
                            <div
                                class="w-full md:w-96 shrink-0 flex flex-col gap-4"
                            >
                                <label
                                    class="block font-medium text-gray-700 dark:text-gray-200"
                                    >Avatar</label
                                >
                                <div class="card">
                                    <div class="mb-4 p-4 border border-gray-100 dark:border-zinc-800 rounded-lg bg-gray-50 dark:bg-zinc-800/50 flex flex-col items-center justify-center gap-4">
                                        <div v-if="previewUrl" class="relative">
                                             <img :src="previewUrl" alt="Avatar Preview" class="w-32 h-32 rounded-full object-cover ring-4 ring-white dark:ring-zinc-700 shadow-md" />
                                        </div>
                                        <div v-else class="w-32 h-32 rounded-full bg-gray-200 dark:bg-zinc-700 flex items-center justify-center ring-4 ring-white dark:ring-zinc-700 shadow-md">
                                            <i class="pi pi-user text-4xl text-gray-400 dark:text-gray-500"></i>
                                        </div>
                                        <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">
                                            {{ previewUrl ? 'New Avatar Preview' : 'No Avatar Selected' }}
                                        </span>
                                    </div>

                                    <FileUpload
                                        ref="fileUpload"
                                        name="avatar"
                                        :multiple="false"
                                        accept="image/*"
                                        :maxFileSize="5242880"
                                        :showUploadButton="false"
                                        :showCancelButton="false"
                                        customUpload
                                        @select="onSelectedFiles"
                                        @clear="onClearFiles"
                                        @remove="onClearFiles"
                                    >
                                        <template #empty>
                                            <span
                                                >Drag and drop files to here to
                                                upload.</span
                                            >
                                        </template>
                                    </FileUpload>
                                    <small v-if="form.errors.avatar" class="text-red-500 block mt-2">{{ form.errors.avatar }}</small>
                                </div>
                            </div>

                            <!-- Form Fields -->
                            <div class="flex-1 w-full flex flex-col gap-6">
                                <!-- Name Fields -->
                                <div
                                    class="grid grid-cols-1 md:grid-cols-3 gap-4"
                                >
                                    <div class="flex flex-col gap-2">
                                        <label
                                            for="first_name"
                                            class="font-medium text-gray-700 dark:text-gray-200"
                                            >First Name <span class="text-red-500">*</span></label
                                        >
                                        <InputText
                                            id="first_name"
                                            v-model="form.first_name"
                                            placeholder="First Name"
                                            class="w-full"
                                            :invalid="!!form.errors.first_name"
                                        />
                                        <small
                                            v-if="form.errors.first_name"
                                            class="text-red-500"
                                            >{{ form.errors.first_name }}</small
                                        >
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label
                                            for="middle_name"
                                            class="font-medium text-gray-700 dark:text-gray-200"
                                            >Middle Name</label
                                        >
                                        <InputText
                                            id="middle_name"
                                            v-model="form.middle_name"
                                            placeholder="Middle Name"
                                            class="w-full"
                                            :invalid="!!form.errors.middle_name"
                                        />
                                        <small
                                            v-if="form.errors.middle_name"
                                            class="text-red-500"
                                            >{{
                                                form.errors.middle_name
                                            }}</small
                                        >
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label
                                            for="last_name"
                                            class="font-medium text-gray-700 dark:text-gray-200"
                                            >Last Name <span class="text-red-500">*</span> </label
                                        >
                                        <InputText
                                            id="last_name"
                                            v-model="form.last_name"
                                            placeholder="Last Name"
                                            class="w-full"
                                            :invalid="!!form.errors.last_name"
                                            required
                                        />
                                        <small
                                            v-if="form.errors.last_name"
                                            class="text-red-500"
                                            >{{ form.errors.last_name }}</small
                                        >
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="flex flex-col gap-2">
                                    <label
                                        for="email"
                                        class="font-medium text-gray-700 dark:text-gray-200"
                                        >Email <span class="text-red-500">*</span></label
                                    >
                                    <InputText
                                        id="email"
                                        v-model="form.email"
                                        type="email"
                                        placeholder="Enter email address"
                                        class="w-full"
                                        :invalid="!!form.errors.email"
                                    />
                                    <small
                                        v-if="form.errors.email"
                                        class="text-red-500"
                                        >{{ form.errors.email }}</small
                                    >
                                </div>

                                <!-- Role -->
                                <div class="flex flex-col gap-2">
                                    <label
                                        for="role"
                                        class="font-medium text-gray-700 dark:text-gray-200"
                                        >Role <span class="text-red-500">*</span></label
                                    >
                                    <Dropdown
                                        id="role"
                                        v-model="form.role"
                                        :options="roles"
                                        optionLabel="label"
                                        optionValue="value"
                                        placeholder="Select a role"
                                        class="w-full"
                                        :invalid="!!form.errors.role"
                                    />
                                    <small
                                        v-if="form.errors.role"
                                        class="text-red-500"
                                        >{{ form.errors.role }}</small
                                    >
                                </div>

                                <!-- Password -->
                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 gap-6"
                                >
                                    <div class="flex flex-col gap-2">
                                        <label
                                            for="password"
                                            class="font-medium text-gray-700 dark:text-gray-200"
                                            >Password <span class="text-red-500">*</span></label
                                        >
                                        <Password
                                            id="password"
                                            v-model="form.password"
                                            toggleMask
                                            class="w-full"
                                            inputClass="w-full"
                                            :invalid="!!form.errors.password"
                                        />
                                        <small
                                            v-if="form.errors.password"
                                            class="text-red-500"
                                            >{{ form.errors.password }}</small
                                        >
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label
                                            for="password_confirmation"
                                            class="font-medium text-gray-700 dark:text-gray-200"
                                            >Confirm Password <span class="text-red-500">*</span></label
                                        >
                                        <Password
                                            id="password_confirmation"
                                            v-model="form.password_confirmation"
                                            toggleMask
                                            :feedback="false"
                                            class="w-full"
                                            inputClass="w-full"
                                            :invalid="
                                                !!form.errors
                                                    .password_confirmation
                                            "
                                            required
                                        />
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div
                                    class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700"
                                >
                                    <Button
                                        label="Cancel"
                                        severity="secondary"
                                        outlined
                                        @click="
                                            router.visit(route('users.index'))
                                        "
                                    />
                                    <Button
                                        type="submit"
                                        label="Create User"
                                        icon="pi pi-check"
                                        :loading="form.processing"
                                    />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

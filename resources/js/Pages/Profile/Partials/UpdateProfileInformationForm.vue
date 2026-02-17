<script setup>
import { useForm, usePage, Link } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { useToast } from "primevue/usetoast";
import FileUpload from "primevue/fileupload";

const page = usePage();

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = computed(() => page.props.auth.user);
const fileUpload = ref();
const previewUrl = ref(null);

const form = useForm({
    _method: "PATCH",
    first_name: user.value.first_name,
    middle_name: user.value.middle_name,
    last_name: user.value.last_name,
    email: user.value.email,
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

const updateProfile = () => {
    form.post(route("profile.update"), {
        onSuccess: () => {
            fileUpload.value.clear();
            previewUrl.value = null; 
        
        },
    });
};

const getRoleSeverity = (role) => {
    switch (role) {
        case 'super_admin':
            return 'danger';
        case 'admin':
            return 'info';
        case 'user':
            return 'secondary';
        default:
            return 'contrast';
    }
};

const formatRole = (role) => {
    if (!role) return '';
    return role.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
};
</script>

<template>
    <section>
        <header class="mb-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Profile Information
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Update your account's profile information and email address.
            </p>
        </header>

        <form @submit.prevent="updateProfile">
            <div class="flex flex-col md:flex-row gap-8 md:gap-12 items-start">
                <!-- Avatar Section -->
                <div class="shrink-0 w-full md:w-80 flex flex-col gap-4">
                    <label class="block font-medium text-gray-700 dark:text-gray-200">Avatar</label>
                    <div class="card">
                        <div class="mb-4 p-4 border border-gray-100 dark:border-zinc-800 rounded-lg bg-gray-50 dark:bg-zinc-800/50 flex flex-col items-center justify-center gap-4">
                            <div class="relative">
                                <img
                                    :src="
                                        previewUrl ||
                                        user.avatar_url ||
                                        `https://ui-avatars.com/api/?name=${user.first_name}+${user.last_name}&color=7F9CF5&background=EBF4FF`
                                    "
                                    alt="Profile"
                                    class="w-32 h-32 rounded-full object-cover ring-4 ring-white dark:ring-zinc-700 shadow-md"
                                />
                            </div>
                            <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">
                                {{ previewUrl ? 'New Avatar Preview' : 'Current Avatar' }}
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
                                <div class="text-center text-sm text-gray-500">
                                    Drag and drop or select a file
                                </div>
                            </template>
                        </FileUpload>
                        <small v-if="form.errors.avatar" class="text-red-500 block mt-2">{{ form.errors.avatar }}</small>
                    </div>
                </div>

                <!-- Form Fields -->
                <div class="flex-1 w-full grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col gap-2">
                        <label for="first_name" class="text-sm font-medium text-gray-700 dark:text-gray-300">First Name</label>
                        <InputText
                            id="first_name"
                            v-model="form.first_name"
                            type="text"
                            class="w-full"
                            :invalid="!!form.errors.first_name"
                            required
                            autofocus
                            autocomplete="given-name"
                        />
                        <small class="text-red-500" v-if="form.errors.first_name">{{ form.errors.first_name }}</small>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label for="last_name" class="text-sm font-medium text-gray-700 dark:text-gray-300">Last Name</label>
                        <InputText
                            id="last_name"
                            v-model="form.last_name"
                            type="text"
                            class="w-full"
                            :invalid="!!form.errors.last_name"
                            required
                            autocomplete="family-name"
                        />
                        <small class="text-red-500" v-if="form.errors.last_name">{{ form.errors.last_name }}</small>
                    </div>

                    <div class="flex flex-col gap-2">
                         <label for="middle_name" class="text-sm font-medium text-gray-700 dark:text-gray-300">Middle Name <span class="text-gray-400 font-normal">(Optional)</span></label>
                        <InputText
                            id="middle_name"
                            v-model="form.middle_name"
                            type="text"
                            class="w-full"
                            :invalid="!!form.errors.middle_name"
                            autocomplete="additional-name"
                        />
                        <small class="text-red-500" v-if="form.errors.middle_name">{{ form.errors.middle_name }}</small>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label for="role" class="text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                        <div class="flex">
                             <Tag 
                                :value="formatRole(user.type)" 
                                :severity="getRoleSeverity(user.type)" 
                                class="px-3 py-1.5 text-sm uppercase tracking-wide"
                            />
                        </div>
                    </div>

                    <div class="flex flex-col gap-2 md:col-span-2">
                        <label for="email" class="text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
                        <InputText
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="w-full"
                            :invalid="!!form.errors.email"
                            required
                            autocomplete="username"
                        />
                        <small class="text-red-500" v-if="form.errors.email">{{ form.errors.email }}</small>
                    </div>

                    <div v-if="mustVerifyEmail && user.email_verified_at === null" class="md:col-span-2 mt-2 p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg border border-orange-100 dark:border-orange-800">
                        <div class="flex items-start gap-3">
                            <i class="pi pi-exclamation-triangle text-orange-500 mt-0.5"></i>
                            <div>
                                <p class="text-sm text-gray-800 dark:text-gray-200">
                                    Your email address is unverified.
                                    <Link
                                        :href="route('verification.send')"
                                        method="post"
                                        as="button"
                                        class="underline font-medium text-orange-600 dark:text-orange-400 hover:text-orange-800 dark:hover:text-orange-300 ml-1"
                                    >
                                        Click here to re-send the verification email.
                                    </Link>
                                </p>
                                <div v-show="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600 dark:text-green-400">
                                    A new verification link has been sent to your email address.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2 flex justify-end mt-4">
                        <Button
                            type="submit"
                            :loading="form.processing"
                            label="Save Changes"
                            icon="pi pi-check"
                        />
                    </div>
                </div>
            </div>
        </form>
    </section>
</template>

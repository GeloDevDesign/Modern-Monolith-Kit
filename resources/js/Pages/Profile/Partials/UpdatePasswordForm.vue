<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import Password from 'primevue/password';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.reset();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header class="mb-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Update Password</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Ensure your account is using a long, random password to stay secure.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="space-y-6">
            <div class="flex flex-col gap-2">
                <label for="current_password" class="text-sm font-medium text-gray-700 dark:text-gray-300">Current Password</label>
                <div class="relative">
                    <Password
                        id="current_password"
                        ref="currentPasswordInput"
                        v-model="form.current_password"
                        :feedback="false"
                        toggleMask
                        class="w-full"
                        inputClass="w-full"
                        :invalid="!!form.errors.current_password"
                        autocomplete="current-password"
                    />
                </div>
                <small class="text-red-500" v-if="form.errors.current_password">{{ form.errors.current_password }}</small>
            </div>

            <div class="flex flex-col gap-2">
                <label for="password" class="text-sm font-medium text-gray-700 dark:text-gray-300">New Password</label>
                <Password
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    class="w-full"
                    inputClass="w-full"
                    :invalid="!!form.errors.password"
                    toggleMask
                    autocomplete="new-password"
                    strongRegex="^(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\W_]).{8,}$"
                />
                <small class="text-red-500" v-if="form.errors.password">{{ form.errors.password }}</small>
            </div>

            <div class="flex flex-col gap-2">
                <label for="password_confirmation" class="text-sm font-medium text-gray-700 dark:text-gray-300">Confirm Password</label>
                <Password
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    class="w-full"
                    inputClass="w-full"
                    :invalid="!!form.errors.password_confirmation"
                    toggleMask
                    autocomplete="new-password"
                    :feedback="false"
                />
                <small class="text-red-500" v-if="form.errors.password_confirmation">{{ form.errors.password_confirmation }}</small>
            </div>

            <div class="flex items-center gap-4 pt-2">
                <Button type="submit" :loading="form.processing" label="Update Password" icon="pi pi-lock" />

                <transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <div v-if="form.recentlySuccessful" class="flex items-center gap-2 text-green-600 dark:text-green-400 text-sm font-medium">
                        <i class="pi pi-check-circle"></i>
                        <span>Saved successfully.</span>
                    </div>
                </transition>
            </div>
        </form>
    </section>
</template>

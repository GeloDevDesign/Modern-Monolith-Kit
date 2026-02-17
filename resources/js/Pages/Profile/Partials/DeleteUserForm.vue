<script setup>
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
    nextTick(() => passwordInput.value.$el.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.$el.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Delete Account</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting
                your account, please download any data or information that you wish to retain.
            </p>
        </header>

        <Button severity="danger" label="Delete Account" @click="confirmUserDeletion" />

        <Dialog v-model:visible="confirmingUserDeletion" modal header="Are you sure you want to delete your account?" :style="{ width: '50rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <div class="p-6">
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Once your account is deleted, all of its resources and data will be permanently deleted. Please
                    enter your password to confirm you would like to permanently delete your account.
                </p>

                <div class="mt-6 flex flex-col gap-2">
                    <label for="password" class="sr-only">Password</label>

                    <InputText
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="w-3/4"
                        placeholder="Password"
                        :class="{ 'p-invalid': form.errors.password }"
                        @keyup.enter="deleteUser"
                    />

                    <small class="text-red-500" v-if="form.errors.password">{{ form.errors.password }}</small>
                </div>
            </div>

            <template #footer>
                <Button label="Cancel" severity="secondary" @click="closeModal" />
                <Button label="Delete Account" severity="danger" :disabled="form.processing" @click="deleteUser" />
            </template>
        </Dialog>
    </section>
</template>

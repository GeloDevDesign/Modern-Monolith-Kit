<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div class="mb-8 space-y-2 text-center md:text-center">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                Forgot password?
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                No worries, we'll send you reset instructions.
            </p>
        </div>

        <div v-if="status" class="mb-8 font-medium text-sm text-green-600 dark:text-green-400">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="flex flex-col gap-1">
                <FloatLabel>
                    <InputText id="email" type="email" v-model="form.email" class="w-full" :invalid="!!form.errors.email" autofocus required autocomplete="username" />
                    <label for="email">Email</label>
                </FloatLabel>
                <Message v-if="form.errors.email" severity="error" size="small" variant="simple">{{ form.errors.email }}</Message>
            </div>

            <div class="flex items-center justify-end mt-2">
                <Button
                    type="submit"
                    :label="form.processing ? 'Sending link...' : 'Email password reset link'"
                    :loading="form.processing"
                    fluid
                />
            </div>
        </form>
    </GuestLayout>
</template>

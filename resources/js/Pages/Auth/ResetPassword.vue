<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password" />

        <div class="mb-8 space-y-2 text-center md:text-center">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                Set new password
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Must be at least 8 characters.
            </p>
        </div>

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="flex flex-col gap-1">
                <FloatLabel>
                    <InputText id="email" type="email" v-model="form.email" class="w-full" :invalid="!!form.errors.email" autofocus required autocomplete="username" />
                    <label for="email">Email</label>
                </FloatLabel>
                <Message v-if="form.errors.email" severity="error" size="small" variant="simple">{{ form.errors.email }}</Message>
            </div>

            <div class="flex flex-col gap-1">
                <FloatLabel>
                    <Password id="password" v-model="form.password" class="w-full" :invalid="!!form.errors.password" toggleMask :feedback="true" fluid required autocomplete="new-password" strongRegex="^(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\W_]).{8,}$" />
                    <label for="password">Password</label>
                </FloatLabel>
                <Message v-if="form.errors.password" severity="error" size="small" variant="simple">{{ form.errors.password }}</Message>
            </div>

            <div class="flex flex-col gap-1">
                <FloatLabel>
                    <Password id="password_confirmation" v-model="form.password_confirmation" class="w-full" :invalid="!!form.errors.password_confirmation" toggleMask :feedback="false" fluid required autocomplete="new-password" />
                    <label for="password_confirmation">Confirm Password</label>
                </FloatLabel>
                <Message v-if="form.errors.password_confirmation" severity="error" size="small" variant="simple">{{ form.errors.password_confirmation }}</Message>
            </div>

            <div class="flex items-center justify-end">
                <Button
                    type="submit"
                    :label="form.processing ? 'Resetting...' : 'Reset password'"
                    :loading="form.processing"
                    fluid
                />
            </div>
        </form>
    </GuestLayout>
</template>

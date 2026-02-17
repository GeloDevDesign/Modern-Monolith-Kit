<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

const form = useForm({
    first_name: '',
    last_name: '',
    middle_name: '',
    email: '',
    password: '',
    password_confirmation: '',
    type: 'user', // Default role if needed
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <div class="mb-8 space-y-2 text-center md:text-center">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                Create an account
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Access your tasks, notes, and projects anytime.
            </p>
        </div>

        <form @submit.prevent="submit" class="flex flex-col gap-8">
            <div class="flex flex-col gap-1">
                <FloatLabel>
                    <InputText id="first_name" type="text" v-model="form.first_name" class="w-full" :invalid="!!form.errors.first_name" autofocus required autocomplete="given-name" />
                    <label for="first_name">First Name</label>
                </FloatLabel>
                <Message v-if="form.errors.first_name" severity="error" size="small" variant="simple">{{ form.errors.first_name }}</Message>
            </div>

            <div class="flex flex-col gap-1">
                <FloatLabel>
                    <InputText id="last_name" type="text" v-model="form.last_name" class="w-full" :invalid="!!form.errors.last_name" required autocomplete="family-name" />
                    <label for="last_name">Last Name</label>
                </FloatLabel>
                <Message v-if="form.errors.last_name" severity="error" size="small" variant="simple">{{ form.errors.last_name }}</Message>
            </div>

            <div class="flex flex-col gap-1">
                <FloatLabel>
                    <InputText id="email" type="email" v-model="form.email" class="w-full" :invalid="!!form.errors.email" required autocomplete="username" />
                    <label for="email">Email</label>
                </FloatLabel>
                <Message v-if="form.errors.email" severity="error" size="small" variant="simple">{{ form.errors.email }}</Message>
            </div>

            <div class="flex flex-col gap-1">
                <FloatLabel>
                    <Password id="password" v-model="form.password" class="w-full" :invalid="!!form.errors.password" toggleMask :feedback="true" fluid required autocomplete="new-password" />
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

            <div class="flex items-center justify-between mt-4">
                <Link
                    :href="route('login')"
                    class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                >
                    Already registered?
                </Link>

                <Button
                    type="submit"
                    :label="form.processing ? 'Creating account...' : 'Register'"
                    :loading="form.processing"
                />
            </div>
        </form>
    </GuestLayout>
</template>

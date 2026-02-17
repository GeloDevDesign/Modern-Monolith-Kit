<script setup>
import { Head, useForm, Link } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";

defineProps({
    canResetPassword: {
        type: Boolean,
        default: true,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div class="mb-8 space-y-2 text-center md:text-center">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                Welcome back
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Enter your email and password to sign in.
            </p>
        </div>

        <form @submit.prevent="submit" class="flex flex-col gap-6 pb-4">
            <div class="flex flex-col gap-1">
                <FloatLabel>
                    <InputText
                        id="email"
                        type="email"
                        v-model="form.email"
                        class="w-full"
                        :invalid="!!form.errors.email"
                        autofocus
                        required
                        autocomplete="username"
                    />
                    <label for="email">Email</label>
                </FloatLabel>
                <Message
                    v-if="form.errors.email"
                    severity="error"
                    size="small"
                    variant="simple"
                    >{{ form.errors.email }}</Message
                >
            </div>

            <div class="flex flex-col gap-1 mt-2">
                <FloatLabel>
                    <Password
                        id="password"
                        v-model="form.password"
                        class="w-full"
                        :invalid="!!form.errors.password"
                        toggleMask
                        :feedback="false"
                        fluid
                        required
                        autocomplete="current-password"
                    />
                    <label for="password">Password</label>
                </FloatLabel>
                <Message
                    v-if="form.errors.password"
                    severity="error"
                    size="small"
                    variant="simple"
                    >{{ form.errors.password }}</Message
                >
            </div>

            <div class="flex items-center gap-2 justify-between">
                <div class="flex items-center gap-2">
                    <Checkbox
                        v-model="form.remember"
                        binary
                        inputId="remember"
                    />
                    <label
                        for="remember"
                        class="cursor-pointer text-sm text-gray-600 dark:text-gray-400"
                        >Remember me</label
                    >
                </div>
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                >
                    Forgot your password?
                </Link>
            </div>

            <div class="flex items-center justify-end mt-2">
                <Button
                    type="submit"
                    :label="form.processing ? 'Logging in...' : 'Log in'"
                    :loading="form.processing"
                    fluid
                />
            </div>

            <div class="text-center my-2">
                <Link
                    :href="route('register')"
                    class="rounded-md text-sm text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                >
                    Don't have an account?
                    <span class="underline">Register</span>
                </Link>
            </div>
        </form>

        <template #footer>
           
        </template>
    </GuestLayout>
</template>

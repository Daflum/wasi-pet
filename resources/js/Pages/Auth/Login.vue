<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <v-alert v-if="status" type="success" variant="tonal" class="mb-4">
            {{ status }}
        </v-alert>

        <v-form @submit.prevent="submit">
            <v-text-field
                v-model="form.email"
                label="Email"
                type="email"
                variant="outlined"
                prepend-inner-icon="mdi-email"
                :error-messages="form.errors.email"
                required
                autofocus
                autocomplete="username"
            ></v-text-field>

            <v-text-field
                v-model="form.password"
                label="Contraseña"
                type="password"
                variant="outlined"
                prepend-inner-icon="mdi-lock"
                :error-messages="form.errors.password"
                required
                autocomplete="current-password"
                class="mt-2"
            ></v-text-field>

            <v-checkbox
                v-model="form.remember"
                label="Recuérdame"
                color="primary"
                hide-details
                class="mt-1"
            ></v-checkbox>

            <div class="d-flex flex-column gap-2 mt-4">
                <v-btn
                    type="submit"
                    color="primary"
                    block
                    size="large"
                    :loading="form.processing"
                >
                    Iniciar Sesión
                </v-btn>
            </div>
        </v-form>
    </GuestLayout>
</template>

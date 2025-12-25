<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="Welcome" />
    <AppLayout>
        <v-container class="fill-height justify-center">
            <v-card class="pa-6" elevation="3" rounded="lg" max-width="800">
                <v-card-title class="text-h4 text-center mb-4 text-primary font-weight-bold">
                    Bienvenido a WasiPet
                </v-card-title>

                <v-card-text class="text-center">
                    <div class="text-h2 mb-6">🐾</div>
                    <p class="text-h6 mb-6">
                        Plataforma de adopción de mascotas (Adra Uni).
                    </p>
                    <p class="text-body-1 mb-6 text-medium-emphasis">
                        Desarrollada con Laravel 12, Vue 3, Inertia y Vuetify.
                    </p>

                    <div class="d-flex justify-center flex-wrap ga-4">
                        <template v-if="$page.props.auth.user">
                            <Link :href="route('dashboard')">
                                <v-btn color="primary" size="large" prepend-icon="mdi-view-dashboard">Dashboard</v-btn>
                            </Link>
                        </template>
                        <template v-else>
                             <Link :href="route('login')">
                                <v-btn color="primary" size="large" prepend-icon="mdi-login">Iniciar Sesión</v-btn>
                            </Link>
                            <Link v-if="canRegister" :href="route('register')">
                                <v-btn variant="outlined" color="primary" size="large" prepend-icon="mdi-account-plus">Registrarse</v-btn>
                            </Link>
                        </template>
                    </div>
                </v-card-text>

                <v-divider class="my-4"></v-divider>

                <v-card-actions class="justify-center text-caption text-grey">
                    Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
                </v-card-actions>
            </v-card>
        </v-container>
    </AppLayout>
</template>

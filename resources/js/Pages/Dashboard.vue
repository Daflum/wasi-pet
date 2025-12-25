<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout>
        <v-container>
            <h1 class="text-h4 mb-4">Dashboard</h1>
            <v-card class="pa-4">
                <v-card-text>
                    ¡Has iniciado sesión correctamente!
                </v-card-text>
            </v-card>
        </v-container>
    </AppLayout>
</template>

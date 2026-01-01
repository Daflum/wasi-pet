<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    totalPets: Number,
    pendingRequests: Number,
    pendingDonations: Number,
});
</script>

<template>
    <Head title="Dashboard" />

    <AdminLayout>
        <v-container>
            <h1 class="text-h4 mb-6">Dashboard Admin</h1>

            <v-row>
                <v-col cols="12" md="4">
                    <v-card color="primary" class="text-white">
                        <v-card-title class="text-h5">
                            <v-icon icon="mdi-paw" class="mr-2"></v-icon>
                            Total Mascotas
                        </v-card-title>
                        <v-card-text class="text-h2 font-weight-bold text-center">
                            {{ totalPets }}
                        </v-card-text>
                        <v-card-actions>
                            <Link :href="route('admin.pets.index')" as="div" class="w-100">
                                <v-btn variant="text" block>Gestionar Mascotas</v-btn>
                            </Link>
                        </v-card-actions>
                    </v-card>
                </v-col>

                <v-col cols="12" md="4">
                    <v-card color="success" class="text-white">
                        <v-card-title class="text-h5">
                            <v-icon icon="mdi-cash" class="mr-2"></v-icon>
                            Donaciones
                        </v-card-title>
                        <v-card-text class="text-h2 font-weight-bold text-center">
                            {{ pendingDonations }}
                        </v-card-text>
                        <v-card-actions>
                            <Link :href="route('admin.donations.index')" as="div" class="w-100">
                                <v-btn variant="text" block>Revisar</v-btn>
                            </Link>
                        </v-card-actions>
                    </v-card>
                </v-col>

                <v-col cols="12" md="4">
                    <v-card color="warning" class="text-white">
                        <v-card-title class="text-h5">
                            <v-icon icon="mdi-account-group" class="mr-2"></v-icon>
                            Solicitudes
                        </v-card-title>
                        <v-card-text class="text-h2 font-weight-bold text-center">
                            {{ pendingRequests }}
                        </v-card-text>
                        <v-card-actions>
                            <Link :href="route('admin.adoption-requests.index')" as="div" class="w-100">
                                <v-btn variant="text" block>Ver Pendientes</v-btn>
                            </Link>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </AdminLayout>
</template>

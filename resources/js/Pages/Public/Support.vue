<script setup>
import PublicLayout from "@/Layouts/PublicLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Colabora con WasiPet" />
    <PublicLayout>
        <v-container>
            <v-row class="justify-center mt-12">
                <v-col cols="12" md="8">
                    <v-card>
                        <v-card-title class="text-h5 text-center pa-4">
                            Colabora con el Proyecto
                        </v-card-title>
                        <v-card-text class="pa-5 text-center">
                            <p class="text-body-1">
                                WasiPet es una iniciativa independiente para digitalizar refugios y conectar mascotas con personas.
                            </p>
                            <p class="text-body-1 mt-4">
                                Ayúdanos a mantener los costos del servidor y seguir desarrollando nuevas funciones.
                            </p>
                            <div class="mt-6">
                                <v-img
                                    src="https://via.placeholder.com/250"
                                    alt="QR Code for Donation"
                                    class="mx-auto"
                                    max-width="250"
                                ></v-img>
                                <p class="text-caption mt-2">Escanea para donar vía Yape/Plin</p>
                            </div>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </PublicLayout>
</template>

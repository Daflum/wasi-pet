<script setup>
import PublicLayout from "@/Layouts/PublicLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Sobre WasiPet" />
    <PublicLayout>
        <v-container>
            <v-row class="justify-center mt-12">
                <v-col cols="12" md="8">
                    <v-card>
                        <v-card-title class="text-h5 text-center pa-4">
                            Sobre WasiPet
                        </v-card-title>
                        <v-card-text class="pa-5">
                            <p class="text-body-1">
                                WasiPet es una iniciativa tecnológica social que nace con el propósito de potenciar la noble labor de organizaciones como <strong>Adra Uni</strong>.
                            </p>
                            <p class="text-body-1 mt-4">
                                En esta etapa, nuestra tecnología está volcada al servicio de la comunidad de la Universidad Nacional de Ingeniería, facilitando la conexión entre los canes del campus y familias responsables.
                            </p>
                            <p class="text-body-1 mt-4">
                                Buscamos eliminar las barreras manuales para que los voluntarios puedan dedicarse a lo más importante: <strong>el bienestar y cuidado de sus protegidos</strong>.
                            </p>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </PublicLayout>
</template>

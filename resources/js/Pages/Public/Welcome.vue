<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import PetCard from '@/Components/PetCard.vue';
import { ref } from 'vue';

const showRequirements = ref(false);

defineProps({
    featuredPets: {
        type: Array,
        required: true,
    }
});
</script>

<template>
    <Head title="Bienvenido" />
    <PublicLayout>
        <!-- Seccion 1: Hero -->
        <!-- Desktop Hero -->
        <v-sheet height="500" class="position-relative d-md-block d-none">
            <v-img
                src="https://images.unsplash.com/photo-1548199973-03cce0bbc87b?q=80&w=2069&auto=format&fit=crop"
                cover
                height="100%"
            ></v-img>
            <div class="position-absolute top-0 left-0 w-100 h-100 d-flex flex-column justify-center align-center text-white" style="background-color: rgba(0,0,0,0.5)">
                <h1 class="text-h2 font-weight-bold mb-4">
                    Amor de cuatro patas
                </h1>
                <Link href="/mascotas" as="div">
                    <v-btn size="x-large" color="secondary" elevation="4">
                        Ver todos los peludos
                    </v-btn>
                </Link>
            </div>
        </v-sheet>

        <!-- Mobile Hero -->
        <v-sheet height="300" class="position-relative d-md-none d-block">
             <v-img
                src="https://images.unsplash.com/photo-1548199973-03cce0bbc87b?q=80&w=2069&auto=format&fit=crop"
                cover
                height="100%"
            ></v-img>
            <div class="position-absolute top-0 left-0 w-100 h-100 d-flex flex-column justify-center align-center text-white" style="background-color: rgba(0,0,0,0.5)">
                <h1 class="text-h4 font-weight-bold mb-4 text-center">
                    Amor de cuatro patas
                </h1>
                <Link href="/mascotas" as="div">
                    <v-btn color="secondary" elevation="4">
                        Ver todos los peludos
                    </v-btn>
                </Link>
            </div>
        </v-sheet>

        <!-- Seccion 2: Urgentes (Featured) -->
        <v-container class="my-12">
            <h2 class="text-h4 font-weight-bold text-center mb-8">Tu próximo mejor amigo está aquí</h2>
            <v-row v-if="featuredPets.length > 0">
                <v-col
                    v-for="pet in featuredPets"
                    :key="pet.id"
                    cols="12"
                    sm="6"
                    md="4"
                >
                    <PetCard :pet="pet" />
                </v-col>
            </v-row>
            <v-alert v-else type="info" variant="tonal" class="mt-4">
                Pronto tendremos nuevos amigos para mostrar. ¡Vuelve a visitarnos!
            </v-alert>
        </v-container>

        <!-- Seccion 3: Como Ayudar -->
        <v-sheet class="py-16" color="grey-lighten-4">
            <v-container>
                <h2 class="text-h4 font-weight-bold text-center mb-10">¿Cómo puedes ayudar?</h2>
                <v-row justify="center" align="center">
                    <v-col cols="12" md="5" class="text-center">
                        <v-icon size="80" color="primary">mdi-home-heart</v-icon>
                        <h3 class="text-h5 my-4">Adoptar</h3>
                        <p class="text-body-1 mb-4">
                            Abrir tu hogar a una mascota es un acto de amor que cambia dos vidas: la tuya y la de tu nuevo mejor amigo.
                        </p>
                        <v-btn color="primary" @click="showRequirements = true">Ver Requisitos</v-btn>
                    </v-col>
                    <v-col cols="12" md="5" class="text-center">
                         <v-icon size="80" color="secondary">mdi-gift-outline</v-icon>
                        <h3 class="text-h5 my-4">Donar</h3>
                        <p class="text-body-1 mb-4">
                            Tu contribución nos ayuda a cubrir gastos de alimentación, medicinas y cuidado para que más mascotas estén listas para un nuevo hogar.
                        </p>
                         <Link href="/donar" as="div">
                            <v-btn color="secondary">Ayudar Ahora</v-btn>
                        </Link>
                    </v-col>
                </v-row>
            </v-container>
        </v-sheet>

        <!-- Requirements Modal -->
        <v-dialog v-model="showRequirements" max-width="500">
            <v-card>
                <v-card-title class="text-h5 font-weight-bold text-primary pa-4 text-wrap">
                    Requisitos para Adoptar en Adra Uni
                </v-card-title>
                <v-card-text class="pa-4 pt-0">
                    <v-list density="compact">
                        <v-list-item prepend-icon="mdi-numeric-1-circle">
                            <v-list-item-title class="text-wrap">Ser mayor de 18 años y presentar DNI vigente.</v-list-item-title>
                        </v-list-item>
                        <v-list-item prepend-icon="mdi-numeric-2-circle">
                            <v-list-item-title class="text-wrap">Contar con el respaldo de todos los miembros de la familia.</v-list-item-title>
                        </v-list-item>
                        <v-list-item prepend-icon="mdi-numeric-3-circle">
                            <v-list-item-title class="text-wrap">Tener solvencia económica para cubrir alimentación y salud.</v-list-item-title>
                        </v-list-item>
                        <v-list-item prepend-icon="mdi-numeric-4-circle">
                            <v-list-item-title class="text-wrap">Disponer de espacio seguro y tiempo para la mascota.</v-list-item-title>
                        </v-list-item>
                        <v-list-item prepend-icon="mdi-numeric-5-circle">
                            <v-list-item-title class="text-wrap">Aceptar el seguimiento post-adopción (fotos/videos).</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-card-text>
                <v-card-actions class="justify-end pa-4">
                    <v-btn color="primary" variant="tonal" @click="showRequirements = false">
                        Entendido
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </PublicLayout>
</template>

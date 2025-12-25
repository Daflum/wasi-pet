<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
</script>

<template>
    <Head title="Bienvenido" />
    <PublicLayout>
        <!-- Hero Section -->
        <v-container fluid class="pa-0 hero-container fill-height">
            <v-img
                src="https://images.unsplash.com/photo-1548199973-03cce0bbc87b?q=80&w=2069&auto=format&fit=crop"
                cover
                class="align-center fill-height"
                gradient="to bottom, rgba(0,0,0,.3), rgba(0,0,0,.6)"
            >
                <div class="text-center w-100 text-white px-4">
                    <h1 class="text-h2 font-weight-bold mb-4">Adopta en la UNI</h1>
                    <p class="text-h5 mb-6 font-weight-light">
                        Dale una segunda oportunidad a un amigo fiel.
                    </p>
                    <Link href="/dogs" as="div">
                        <v-btn size="x-large" color="teal-lighten-1" elevation="4" prepend-icon="mdi-paw">
                            Ver Perros
                        </v-btn>
                    </Link>
                </div>
            </v-img>
        </v-container>
    </PublicLayout>
</template>

<style scoped>
.hero-container {
    height: 85vh;
}
</style>

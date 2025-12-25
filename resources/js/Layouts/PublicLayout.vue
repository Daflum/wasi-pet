<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const drawer = ref(false);
const page = usePage();
</script>

<template>
    <v-app>
        <!-- Public Navigation Drawer (Mobile) -->
        <v-navigation-drawer v-model="drawer" temporary>
            <v-list>
                <Link href="/" as="div" class="v-list-item--link">
                    <v-list-item prepend-icon="mdi-home" title="Inicio"></v-list-item>
                </Link>
                <!-- Placeholder for Dogs route -->
                <Link href="/dogs" as="div" class="v-list-item--link">
                    <v-list-item prepend-icon="mdi-dog" title="Ver Perros"></v-list-item>
                </Link>
                 <Link href="/donar" as="div" class="v-list-item--link">
                    <v-list-item prepend-icon="mdi-heart" title="Donar"></v-list-item>
                </Link>

                <!-- Login hidden from public menu -->
            </v-list>
        </v-navigation-drawer>

        <!-- Public App Bar -->
        <v-app-bar color="teal-darken-1" elevation="2">
            <v-app-bar-nav-icon @click="drawer = !drawer" class="d-md-none"></v-app-bar-nav-icon>

            <Link href="/" class="text-decoration-none text-white d-flex align-center ml-4">
                <v-icon icon="mdi-paw" class="mr-2"></v-icon>
                <v-toolbar-title class="font-weight-bold">WasiPet</v-toolbar-title>
            </Link>

            <v-spacer></v-spacer>

            <!-- Desktop Menu -->
            <div class="d-none d-md-flex align-center ga-2 mr-4">
                <Link href="/" as="div">
                    <v-btn variant="text">Inicio</v-btn>
                </Link>
                <Link href="/dogs" as="div">
                    <v-btn variant="text">Perros</v-btn>
                </Link>
                <Link href="/donar" as="div">
                    <v-btn variant="text">Donar</v-btn>
                </Link>

                <!-- Login hidden from public menu -->
            </div>
        </v-app-bar>

        <v-main class="bg-grey-lighten-4">
            <slot />
        </v-main>

        <v-footer class="bg-grey-darken-4 text-center d-flex flex-column py-6">
            <div>
                <v-btn icon="mdi-facebook" variant="text" class="mx-2"></v-btn>
                <v-btn icon="mdi-instagram" variant="text" class="mx-2"></v-btn>
            </div>
            <div class="pt-2">
                © {{ new Date().getFullYear() }} WasiPet - Adra Uni
            </div>
            <div class="pt-2">
                <a href="/admin" class="text-grey text-decoration-none text-caption">Admin</a>
            </div>
        </v-footer>
    </v-app>
</template>

<style scoped>
.v-list-item--link {
    cursor: pointer;
}
</style>

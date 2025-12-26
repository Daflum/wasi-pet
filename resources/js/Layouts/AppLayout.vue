<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const drawer = ref(false);
const page = usePage();
</script>

<template>
    <v-app>
        <!-- Navigation Drawer -->
        <v-navigation-drawer v-model="drawer">
            <v-list>
                <v-list-item title="WasiPet" subtitle="Adra Uni"></v-list-item>
                <v-divider></v-divider>

                <Link href="/" as="div" class="v-list-item--link">
                    <v-list-item prepend-icon="mdi-home" title="Inicio"></v-list-item>
                </Link>

                <div v-if="$page.props.auth.user">
                    <Link :href="route('admin.dashboard')" class="v-list-item--link text-decoration-none text-high-emphasis">
                        <v-list-item prepend-icon="mdi-view-dashboard" title="Dashboard"></v-list-item>
                    </Link>
                    <Link :href="route('admin.pets.index')" class="v-list-item--link text-decoration-none text-high-emphasis">
                        <v-list-item prepend-icon="mdi-paw" title="Mascotas"></v-list-item>
                    </Link>
                    <!-- TODO: Requests Link -->
                    <!-- <Link :href="route('admin.requests.index')" class="v-list-item--link text-decoration-none text-high-emphasis">
                        <v-list-item prepend-icon="mdi-account-group" title="Solicitudes"></v-list-item>
                    </Link> -->
                    <Link :href="route('admin.donations.index')" class="v-list-item--link text-decoration-none text-high-emphasis">
                        <v-list-item prepend-icon="mdi-cash" title="Donaciones"></v-list-item>
                    </Link>
                    <Link :href="route('admin.profile.edit')" class="v-list-item--link text-decoration-none text-high-emphasis">
                        <v-list-item prepend-icon="mdi-account" title="Perfil"></v-list-item>
                    </Link>
                     <Link :href="route('logout')" method="post" as="div" class="v-list-item--link">
                        <v-list-item prepend-icon="mdi-logout" title="Cerrar Sesión"></v-list-item>
                    </Link>
                </div>

            </v-list>
        </v-navigation-drawer>

        <!-- App Bar -->
        <v-app-bar color="primary">
            <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
            <v-app-bar-title>WasiPet</v-app-bar-title>
        </v-app-bar>

        <!-- Main Content -->
        <v-main>
            <v-container>
                <slot />
            </v-container>
        </v-main>
    </v-app>
</template>

<style scoped>
.v-list-item--link {
    cursor: pointer;
}
</style>

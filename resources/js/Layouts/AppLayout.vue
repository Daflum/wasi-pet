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
                    <Link :href="route('admin.dashboard')" as="div" class="v-list-item--link">
                        <v-list-item prepend-icon="mdi-view-dashboard" title="Dashboard"></v-list-item>
                    </Link>
                     <Link :href="route('logout')" method="post" as="div" class="v-list-item--link">
                        <v-list-item prepend-icon="mdi-logout" title="Cerrar Sesión"></v-list-item>
                    </Link>
                </div>

                <div v-else>
                    <Link :href="route('login')" as="div" class="v-list-item--link">
                        <v-list-item prepend-icon="mdi-login" title="Iniciar Sesión"></v-list-item>
                    </Link>
                    <Link :href="route('register')" as="div" class="v-list-item--link">
                        <v-list-item prepend-icon="mdi-account-plus" title="Registrarse"></v-list-item>
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

<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <v-app>
        <v-main class="d-flex align-center justify-center bg-grey-lighten-4 fill-height">
            <v-container class="fill-height justify-center align-center">
                <v-card width="100%" max-width="450" class="pa-6 rounded-lg" elevation="2">
                    <div class="text-center mb-6">
                        <Link href="/">
                            <v-icon icon="mdi-paw" size="64" color="primary"></v-icon>
                        </Link>
                        <div class="text-h5 font-weight-bold mt-2 text-primary">WasiPet Admin</div>
                    </div>
                    <slot />
                </v-card>
            </v-container>
        </v-main>
    </v-app>
</template>

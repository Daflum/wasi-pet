<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    donations: Array
});
</script>

<template>
    <Head title="Donaciones" />
    <AppLayout>
        <v-container>
            <h1 class="text-h4 mb-6">Administrar Donaciones</h1>

            <v-card>
                <v-data-table
                    :items="donations"
                    :headers="[
                        { title: 'Usuario', key: 'user.name' },
                        { title: 'Monto', key: 'amount' },
                        { title: 'Método', key: 'payment_method' },
                        { title: 'Mensaje', key: 'message' },
                        { title: 'Fecha', key: 'created_at' },
                        { title: 'Comprobante', key: 'proof_path' },
                    ]"
                >
                    <template v-slot:item.amount="{ item }">
                        S/ {{ item.amount }}
                    </template>

                    <template v-slot:item.created_at="{ item }">
                        {{ new Date(item.created_at).toLocaleDateString() }}
                    </template>

                    <template v-slot:item.proof_path="{ item }">
                        <a v-if="item.proof_path" :href="`/storage/${item.proof_path}`" target="_blank">
                            <v-btn size="small" variant="text" icon="mdi-file-document"></v-btn>
                        </a>
                        <span v-else class="text-grey">Sin comprobante</span>
                    </template>
                </v-data-table>
            </v-card>
        </v-container>
    </AppLayout>
</template>

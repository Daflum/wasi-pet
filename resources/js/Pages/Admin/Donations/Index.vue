<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    donations: Array
});

const dialog = ref(false);
const selectedProof = ref(null);

const viewProof = (url) => {
    selectedProof.value = url;
    dialog.value = true;
};
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
                        { title: 'Usuario/Contacto', key: 'user' },
                        { title: 'Monto', key: 'amount' },
                        { title: 'Método', key: 'payment_method' },
                        { title: 'Mensaje', key: 'message' },
                        { title: 'Fecha', key: 'created_at' },
                        { title: 'Comprobante', key: 'proof_path' },
                        { title: 'Acciones', key: 'actions' },
                    ]"
                >
                    <template v-slot:item.user="{ item }">
                        {{ item.user ? item.user.name : item.guest_contact }}
                    </template>

                    <template v-slot:item.amount="{ item }">
                        S/ {{ item.amount }}
                    </template>

                    <template v-slot:item.created_at="{ item }">
                        {{ new Date(item.created_at).toLocaleDateString() }}
                    </template>

                    <template v-slot:item.proof_path="{ item }">
                        <v-btn
                            v-if="item.proof_path"
                            size="small"
                            variant="text"
                            icon="mdi-image"
                            color="primary"
                            @click="viewProof(`/storage/${item.proof_path}`)"
                        ></v-btn>
                        <span v-else class="text-grey">Sin comprobante</span>
                    </template>

                    <template v-slot:item.actions="{ item }">
                        <!-- TODO: Add logic to mark as verified -->
                        <v-btn size="small" color="success" variant="text" icon="mdi-check-circle"></v-btn>
                    </template>
                </v-data-table>
            </v-card>

            <!-- Proof Dialog -->
            <v-dialog v-model="dialog" max-width="800">
                <v-card>
                    <v-img :src="selectedProof" cover></v-img>
                    <v-card-actions class="justify-end">
                        <v-btn color="primary" variant="text" @click="dialog = false">Cerrar</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-container>
    </AppLayout>
</template>

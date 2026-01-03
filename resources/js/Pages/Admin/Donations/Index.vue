<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    donations: Array,
});

const form = useForm({
    status: '',
    admin_note: '',
});

const showProofDialog = ref(false);
const selectedProof = ref('');

const getProofUrl = (path) => {
    if (!path) return '';
    if (path.startsWith('http')) return path;
    return `/storage/${path}`;
};

const openProof = (proofPath) => {
    selectedProof.value = getProofUrl(proofPath);
    showProofDialog.value = true;
};

const updateStatus = (donation, newStatus) => {
    if (newStatus === 'Rechazado') {
        const note = prompt('Por favor, ingresa la razón del rechazo:');
        if (note === null) return; // User cancelled
        form.admin_note = note;
    }

    form.status = newStatus;
    form.put(route('admin.donations.update', donation.id), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};

const getStatusColor = (status) => {
    const colors = { 'Pendiente': 'warning', 'Verificado': 'success', 'Rechazado': 'error' };
    return colors[status] || 'grey';
};
</script>

<template>
    <Head title="Gestión de Donaciones" />

    <AdminLayout>
        <v-container>
            <h1 class="text-h4 mb-6">Gestión de Donaciones</h1>

            <v-card>
                <v-data-table
                    :items="donations"
                    :headers="[
                        { title: 'Donante', key: 'donor_name' },
                        { title: 'Monto (S/)', key: 'amount' },
                        { title: 'Método', key: 'payment_method' },
                        { title: 'Mascota', key: 'pet_name' },
                        { title: 'Comprobante', key: 'proof_path', sortable: false },
                        { title: 'Estado', key: 'status' },
                        { title: 'Acciones', key: 'actions', sortable: false, align: 'end' },
                    ]"
                >
                    <template v-slot:item.pet_name="{ item }">
                        {{ item.pet ? item.pet.name : 'General' }}
                    </template>

                    <template v-slot:item.proof_path="{ item }">
                        <v-avatar @click="openProof(item.proof_path)" class="cursor-pointer">
                            <v-img :src="getProofUrl(item.proof_path)" cover></v-img>
                        </v-avatar>
                    </template>

                    <template v-slot:item.status="{ item }">
                        <v-menu>
                          <template v-slot:activator="{ props }">
                            <v-chip
                                v-bind="props"
                                :color="getStatusColor(item.status)"
                                size="small"
                                link
                                label
                                append-icon="mdi-chevron-down"
                            >
                                {{ item.status }}
                            </v-chip>
                          </template>
                          <v-list density="compact">
                            <v-list-item @click="updateStatus(item, 'Pendiente')" title="Pendiente" value="Pendiente">
                                <template v-slot:prepend><v-icon color="warning" icon="mdi-clock-outline" size="small"></v-icon></template>
                            </v-list-item>
                            <v-list-item @click="updateStatus(item, 'Verificado')" title="Verificado" value="Verificado">
                                <template v-slot:prepend><v-icon color="success" icon="mdi-check-circle" size="small"></v-icon></template>
                            </v-list-item>
                             <v-list-item @click="updateStatus(item, 'Rechazado')" title="Rechazado" value="Rechazado">
                                <template v-slot:prepend><v-icon color="error" icon="mdi-close-circle" size="small"></v-icon></template>
                            </v-list-item>
                          </v-list>
                        </v-menu>
                    </template>

                    <template v-slot:item.actions="{ item }">
                        <!-- Additional actions if needed -->
                    </template>
                </v-data-table>
            </v-card>

            <v-dialog v-model="showProofDialog" max-width="600">
                <v-card>
                    <v-img :src="selectedProof"></v-img>
                </v-card>
            </v-dialog>

        </v-container>
    </AdminLayout>
</template>

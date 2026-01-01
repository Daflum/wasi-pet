<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    adoption_requests: Object,
});

const form = useForm({
    status: '',
});

const updateStatus = (request, newStatus) => {
    form.status = newStatus;
    form.put(route('admin.adoption-requests.update', request.id), {
        preserveScroll: true,
    });
};

const generateWhatsAppLink = (request) => {
    let phone = request.phone.replace(/\D/g, '');

    if (phone.length === 9 && phone.startsWith('9')) {
        phone = '51' + phone;
    }

    const message = `Hola ${request.name}, te escribo de Adra Uni sobre tu solicitud para ${request.pet.name}...`;
    return `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
};

const getStatusColor = (status) => {
    const colors = { 'Pendiente': 'warning', 'Aprobado': 'success', 'Rechazado': 'error' };
    return colors[status] || 'grey';
};
</script>

<template>
    <Head title="Solicitudes de Adopción" />

    <AdminLayout>
        <v-container>
            <h1 class="text-h4 mb-6">Solicitudes de Adopción</h1>

            <v-card>
                <v-data-table
                    :items="adoption_requests.data"
                    :headers="[
                        { title: 'Solicitante', key: 'name' },
                        { title: 'DNI', key: 'dni' },
                        { title: 'Teléfono', key: 'phone' },
                        { title: 'Mascota', key: 'pet.name' },
                        { title: 'Estado', key: 'status' },
                        { title: 'Acciones', key: 'actions', sortable: false, align: 'end' },
                    ]"
                >
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
                            <v-list-item @click="updateStatus(item, 'Aprobado')" title="Aprobado" value="Aprobado">
                                <template v-slot:prepend><v-icon color="success" icon="mdi-check-circle" size="small"></v-icon></template>
                            </v-list-item>
                             <v-list-item @click="updateStatus(item, 'Rechazado')" title="Rechazado" value="Rechazado">
                                <template v-slot:prepend><v-icon color="error" icon="mdi-close-circle" size="small"></v-icon></template>
                            </v-list-item>
                          </v-list>
                        </v-menu>
                    </template>

                    <template v-slot:item.actions="{ item }">
                        <v-btn
                            :href="generateWhatsAppLink(item)"
                            target="_blank"
                            icon="mdi-whatsapp"
                            variant="text"
                            size="small"
                            color="green"
                            title="Contactar por WhatsApp"
                        ></v-btn>
                    </template>
                </v-data-table>
            </v-card>
        </v-container>
    </AdminLayout>
</template>

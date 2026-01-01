<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    pets: Array,
});

const form = useForm({});

const deletePet = (slug) => {
    if (confirm('¿Estás seguro de eliminar esta mascota?')) {
        form.delete(route('admin.pets.destroy', slug));
    }
};

const updateStatus = (pet, newStatus) => {
    const statusForm = useForm({ status: newStatus });
    statusForm.patch(route('admin.pets.update-status', pet.slug), {
        preserveScroll: true
    });
};

const getStatusColor = (status) => {
    const colors = { 'Disponible': 'success', 'Adoptado': 'info', 'En Proceso': 'warning' };
    return colors[status] || 'grey';
};
</script>

<template>
    <Head title="Gestión de Mascotas" />

    <AdminLayout>
        <v-container>
            <div class="d-flex justify-space-between align-center mb-6">
                <h1 class="text-h4">Mascotas</h1>
                <Link :href="route('admin.pets.create')">
                    <v-btn color="primary" prepend-icon="mdi-plus">Nueva Mascota</v-btn>
                </Link>
            </div>

            <v-card>
                <v-data-table
                    :items="pets"
                    :headers="[
                        { title: 'Imagen', key: 'image', sortable: false },
                        { title: 'Nombre', key: 'name' },
                        { title: 'Especie', key: 'type' },
                        { title: 'Estado', key: 'status' },
                        { title: 'Acciones', key: 'actions', sortable: false, align: 'end' },
                    ]"
                >
                    <template v-slot:item.image="{ item }">
                        <v-avatar size="48" rounded="0" class="my-2">
                            <v-img
                                :src="item.image"
                                cover
                            ></v-img>
                        </v-avatar>
                    </template>

                    <template v-slot:item.type="{ item }">
                        <v-icon v-if="item.type === 'dog'" icon="mdi-dog" color="brown"></v-icon>
                        <v-icon v-else-if="item.type === 'cat'" icon="mdi-cat" color="orange"></v-icon>
                        <span v-else>{{ item.type }}</span>
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
                            <v-list-item @click="updateStatus(item, 'Disponible')" title="Disponible" value="Disponible">
                                <template v-slot:prepend><v-icon color="success" icon="mdi-check-circle" size="small"></v-icon></template>
                            </v-list-item>
                            <v-list-item @click="updateStatus(item, 'En Proceso')" title="En Proceso" value="En Proceso">
                                <template v-slot:prepend><v-icon color="warning" icon="mdi-progress-clock" size="small"></v-icon></template>
                            </v-list-item>
                             <v-list-item @click="updateStatus(item, 'Adoptado')" title="Adoptado" value="Adoptado">
                                <template v-slot:prepend><v-icon color="info" icon="mdi-home-heart" size="small"></v-icon></template>
                            </v-list-item>
                          </v-list>
                        </v-menu>
                    </template>

                    <template v-slot:item.actions="{ item }">
                        <Link :href="route('admin.pets.edit', item.slug)">
                            <v-btn icon="mdi-pencil" variant="text" size="small" color="primary"></v-btn>
                        </Link>
                        <v-btn
                            icon="mdi-delete"
                            variant="text"
                            size="small"
                            color="error"
                            @click="deletePet(item.slug)"
                        ></v-btn>
                    </template>
                </v-data-table>
            </v-card>
        </v-container>
    </AdminLayout>
</template>

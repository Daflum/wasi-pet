<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    pets: Array,
});

const form = useForm({});

const deletePet = (id) => {
    if (confirm('¿Estás seguro de eliminar esta mascota?')) {
        form.delete(route('admin.pets.destroy', id));
    }
};
</script>

<template>
    <Head title="Gestión de Mascotas" />

    <AppLayout>
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
                        { title: 'Raza', key: 'breed' },
                        { title: 'Estado', key: 'status' },
                        { title: 'Acciones', key: 'actions', sortable: false, align: 'end' },
                    ]"
                >
                    <template v-slot:item.image="{ item }">
                        <v-avatar size="48" rounded="0" class="my-2">
                            <v-img
                                :src="item.image ? `/storage/${item.image}` : 'https://via.placeholder.com/150'"
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
                        <v-chip
                            :color="item.status === 'available' ? 'success' : (item.status === 'adopted' ? 'info' : 'warning')"
                            size="small"
                        >
                            {{ item.status === 'available' ? 'En Adopción' : (item.status === 'adopted' ? 'Adoptado' : 'Tratamiento') }}
                        </v-chip>
                    </template>

                    <template v-slot:item.actions="{ item }">
                        <Link :href="route('admin.pets.edit', item.id)">
                            <v-btn icon="mdi-pencil" variant="text" size="small" color="primary"></v-btn>
                        </Link>
                        <v-btn
                            icon="mdi-delete"
                            variant="text"
                            size="small"
                            color="error"
                            @click="deletePet(item.id)"
                        ></v-btn>
                    </template>
                </v-data-table>
            </v-card>
        </v-container>
    </AppLayout>
</template>

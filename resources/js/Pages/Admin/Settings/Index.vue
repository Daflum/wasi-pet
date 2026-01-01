<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm, router, usePage } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';

const props = defineProps({
    settings: Object,
    paymentMethods: Array,
});

const page = usePage();

// --- Snackbar ---
const snackbar = ref(false);
const snackbarText = ref('');
watch(() => page.props.flash.success, (newValue) => {
    if (newValue) {
        snackbarText.value = newValue;
        snackbar.value = true;
    }
}, { immediate: true });


// --- General Settings Form ---
const settingsForm = useForm({
    facebook_url: props.settings.facebook_url || '',
    instagram_url: props.settings.instagram_url || '',
    tiktok_url: props.settings.tiktok_url || '',
    x_url: props.settings.x_url || '',
    youtube_url: props.settings.youtube_url || '',
});

const submitSettings = () => {
    settingsForm.post(route('admin.settings.update'), {
        preserveScroll: true,
        forceFormData: true,
    });
};


// --- Payment Methods CRUD ---
const dialog = ref(false);
const editing = ref(null);

const initialFormValues = {
    name: '',
    account_number: '',
    cci: '',
    qr_code_path: null,
    instructions: '',
    is_active: true,
};

const paymentMethodForm = useForm({ ...initialFormValues });

const formTitle = computed(() => editing.value ? 'Editar Método de Pago' : 'Nuevo Método de Pago');

const headers = [
    { title: 'Nombre', key: 'name' },
    { title: 'Nº de Cuenta', key: 'account_number' },
    { title: 'CCI', key: 'cci' },
    { title: 'Activo', key: 'is_active' },
    { title: 'Acciones', key: 'actions', sortable: false, align: 'end' },
];

const openNewDialog = () => {
    editing.value = null;
    paymentMethodForm.defaults({ ...initialFormValues }).reset();
    dialog.value = true;
};

const openEditDialog = (item) => {
    editing.value = { ...item };
    paymentMethodForm.defaults({
        ...item,
        is_active: !!item.is_active, // Ensure boolean value for the switch
        qr_code_path: null, // Don't pre-fill file input
    }).reset();
    dialog.value = true;
};

const closeDialog = () => {
    dialog.value = false;
    editing.value = null;
};

const submitPaymentMethod = () => {
    const options = {
        preserveScroll: true,
        onSuccess: () => closeDialog(),
        onError: () => {}, // Keep dialog open on validation error
    };

    if (editing.value) {
        router.post(route('admin.payment-methods.update', editing.value.id), {
            _method: 'patch',
            ...paymentMethodForm.data(),
        }, options);
    } else {
        paymentMethodForm.post(route('admin.payment-methods.store'), options);
    }
};

const deleteItem = (item) => {
    if (confirm(`¿Estás seguro de que quieres eliminar "${item.name}"?`)) {
        router.delete(route('admin.payment-methods.destroy', item.id), {
            preserveScroll: true,
        });
    }
};

</script>

<template>
    <Head title="Configuración" />

    <AdminLayout>
        <v-container>
            <h1 class="text-h4 mb-6">Configuración</h1>

            <!-- General Settings -->
            <v-card class="pa-6 mb-8">
                <v-form @submit.prevent="submitSettings">
                    <h2 class="text-h6 mb-4">Redes Sociales</h2>
                    <v-row>
                        <v-col cols="12" md="6">
                            <v-text-field
                                v-model="settingsForm.facebook_url"
                                label="Facebook URL"
                                prepend-inner-icon="mdi-facebook"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-text-field
                                v-model="settingsForm.instagram_url"
                                label="Instagram URL"
                                prepend-inner-icon="mdi-instagram"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-text-field
                                v-model="settingsForm.tiktok_url"
                                label="TikTok URL"
                                prepend-inner-icon="mdi-music-note"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-text-field
                                v-model="settingsForm.x_url"
                                label="X URL"
                            >
                                <template v-slot:prepend-inner>
                                    <v-icon>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 24px; height: 24px;">
                                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                        </svg>
                                    </v-icon>
                                </template>
                            </v-text-field>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-text-field
                                v-model="settingsForm.youtube_url"
                                label="YouTube URL"
                                prepend-inner-icon="mdi-youtube"
                            ></v-text-field>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12" class="d-flex justify-end">
                            <v-btn type="submit" color="primary" :loading="settingsForm.processing">
                                Guardar Redes Sociales
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card>

            <!-- Payment Methods -->
            <v-card class="pa-6">
                 <v-row justify="space-between" class="mb-4">
                    <v-col>
                        <h2 class="text-h6">Métodos de Pago</h2>
                    </v-col>
                    <v-col class="text-right">
                        <v-btn color="primary" @click="openNewDialog">
                            <v-icon left>mdi-plus</v-icon>
                            Agregar Método
                        </v-btn>
                    </v-col>
                </v-row>

                <v-data-table
                    :headers="headers"
                    :items="paymentMethods"
                    item-value="id"
                    class="elevation-1"
                >
                    <template v-slot:item.is_active="{ item }">
                        <v-chip :color="item.is_active ? 'green' : 'red'" small>
                            {{ item.is_active ? 'Sí' : 'No' }}
                        </v-chip>
                    </template>
                    <template v-slot:item.actions="{ item }">
                         <v-btn icon="mdi-pencil" variant="plain" @click="openEditDialog(item)"></v-btn>
                         <v-btn icon="mdi-delete" variant="plain" color="error" @click="deleteItem(item)"></v-btn>
                    </template>
                </v-data-table>
            </v-card>

        </v-container>

        <!-- Payment Method Dialog -->
        <v-dialog v-model="dialog" max-width="600px" persistent>
            <v-card>
                <v-card-title>
                    <span class="headline">{{ formTitle }}</span>
                </v-card-title>
                <v-card-text>
                    <v-form @submit.prevent="submitPaymentMethod">
                        <v-container>
                            <v-row>
                                <v-col cols="12">
                                    <v-text-field
                                        v-model="paymentMethodForm.name"
                                        label="Nombre del Banco/Plataforma"
                                        :error-messages="paymentMethodForm.errors.name"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-text-field
                                        v-model="paymentMethodForm.account_number"
                                        label="Número de Cuenta"
                                        :error-messages="paymentMethodForm.errors.account_number"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-text-field
                                        v-model="paymentMethodForm.cci"
                                        label="CCI (Opcional)"
                                        :error-messages="paymentMethodForm.errors.cci"
                                    ></v-text-field>
                                </v-col>
                                 <v-col cols="12">
                                    <v-textarea
                                        v-model="paymentMethodForm.instructions"
                                        label="Instrucciones (Opcional)"
                                        rows="3"
                                        :error-messages="paymentMethodForm.errors.instructions"
                                    ></v-textarea>
                                </v-col>
                                <v-col cols="12">
                                     <v-file-input
                                        label="Código QR (Opcional)"
                                        accept="image/*"
                                        @update:modelValue="paymentMethodForm.qr_code_path = $event"
                                        :error-messages="paymentMethodForm.errors.qr_code_path"
                                     ></v-file-input>
                                     <div v-if="editing && editing.qr_code_path && !paymentMethodForm.qr_code_path" class="mt-2">
                                         <p class="text-caption">QR Actual:</p>
                                         <v-img :src="editing.qr_code_path" max-height="100" max-width="100"></v-img>
                                     </div>
                                </v-col>
                                <v-col cols="12">
                                    <v-switch
                                        v-model="paymentMethodForm.is_active"
                                        label="Activo"
                                        color="primary"
                                        inset
                                    ></v-switch>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="closeDialog">Cancelar</v-btn>
                    <v-btn color="blue darken-1" @click="submitPaymentMethod" :loading="paymentMethodForm.processing">Guardar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!-- Snackbar -->
        <v-snackbar v-model="snackbar" color="success" timeout="3000">
            {{ snackbarText }}
            <template v-slot:actions>
                <v-btn color="white" variant="text" @click="snackbar = false">
                    Cerrar
                </v-btn>
            </template>
        </v-snackbar>

    </AdminLayout>
</template>

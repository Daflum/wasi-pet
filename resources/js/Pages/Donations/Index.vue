<script setup>
import { useForm } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    flash: Object
});

const form = useForm({
    amount: '',
    payment_method: 'Yape',
    message: '',
    proof: null,
});

const submit = () => {
    form.post(route('donations.store'), {
        forceFormData: true,
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <PublicLayout>
        <v-container>
            <v-alert
                v-if="$page.props.flash.success"
                type="success"
                variant="tonal"
                class="mb-6"
                closable
            >
                {{ $page.props.flash.success }}
            </v-alert>

            <v-row>
                <!-- Static Info Column -->
                <v-col cols="12" md="6">
                    <v-card class="pa-6" elevation="2">
                        <v-card-title class="text-h5 text-primary mb-4">
                            Canales de Donación
                        </v-card-title>
                        <v-card-text>
                            <p class="mb-4">
                                Tu ayuda nos permite seguir rescatando y cuidando a más peluditos.
                            </p>

                            <div class="text-center mb-6">
                                <!-- Placeholder QR -->
                                <v-sheet color="grey-lighten-2" height="200" width="200" class="mx-auto d-flex align-center justify-center rounded">
                                    <v-icon icon="mdi-qrcode" size="64" color="grey-darken-1"></v-icon>
                                </v-sheet>
                                <p class="text-caption mt-2">Escanea para Yape/Plin</p>
                            </div>

                            <v-list density="compact">
                                <v-list-item prepend-icon="mdi-bank" title="BCP" subtitle="191-12345678-0-01"></v-list-item>
                                <v-list-item prepend-icon="mdi-bank" title="Interbank" subtitle="200-3001234567"></v-list-item>
                                <v-list-item prepend-icon="mdi-email" title="Contacto" subtitle="donaciones@wasipet.pe"></v-list-item>
                            </v-list>
                        </v-card-text>
                    </v-card>
                </v-col>

                <!-- Registration Form Column -->
                <v-col cols="12" md="6">
                    <v-card class="pa-6" elevation="2">
                        <v-card-title class="text-h5 text-primary mb-4">
                            Registrar Donación
                        </v-card-title>
                        <v-card-subtitle class="mb-4 text-wrap">
                            Sube tu comprobante para que podamos validar tu donación.
                            (Solo visible para administradores)
                        </v-card-subtitle>

                        <div v-if="$page.props.auth.user">
                            <v-form @submit.prevent="submit">
                                <v-text-field
                                    v-model="form.amount"
                                    label="Monto (S/)"
                                    type="number"
                                    min="1"
                                    prefix="S/"
                                    :error-messages="form.errors.amount"
                                    required
                                ></v-text-field>

                                <v-select
                                    v-model="form.payment_method"
                                    :items="['Yape', 'Plin', 'Transferencia BCP', 'Transferencia Interbank']"
                                    label="Método de Pago"
                                    :error-messages="form.errors.payment_method"
                                    required
                                ></v-select>

                                <v-file-input
                                    v-model="form.proof"
                                    label="Comprobante (Imagen)"
                                    accept="image/*"
                                    prepend-icon="mdi-camera"
                                    :error-messages="form.errors.proof"
                                    @input="form.proof = $event.target.files[0]"
                                    required
                                ></v-file-input>

                                <v-textarea
                                    v-model="form.message"
                                    label="Mensaje (Opcional)"
                                    rows="3"
                                    :error-messages="form.errors.message"
                                ></v-textarea>

                                <v-btn
                                    type="submit"
                                    color="primary"
                                    block
                                    size="large"
                                    :loading="form.processing"
                                >
                                    Enviar Comprobante
                                </v-btn>
                            </v-form>
                        </div>
                        <div v-else class="text-center pa-4">
                            <v-icon icon="mdi-lock" size="48" color="grey"></v-icon>
                            <p class="mt-4 mb-4">Para registrar tu donación y subir el comprobante, necesitas iniciar sesión.</p>
                            <Link :href="route('login')">
                                <v-btn color="primary" variant="outlined">Iniciar Sesión</v-btn>
                            </Link>
                        </div>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </PublicLayout>
</template>

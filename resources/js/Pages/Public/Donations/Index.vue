<script setup>
import { computed, ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import PublicLayout from "@/Layouts/PublicLayout.vue";

const props = defineProps({
    paymentMethods: Array,
});

const qrMethods = computed(() => {
    return props.paymentMethods.filter(method => method.qr_code_path !== null);
});

const bankMethods = computed(() => {
    return props.paymentMethods.filter(method => method.qr_code_path === null);
});

const snackbar = ref(false);

const copyToClipboard = (text) => {
    navigator.clipboard.writeText(text);
    snackbar.value = true;
};

const form = useForm({
    donor_name: '',
    amount: '',
    payment_method: '',
    proof: null,
});

const submit = () => {
    form.post(route('public.donations.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        }
    });
};
</script>

<template>
    <Head title="Donaciones"/>

    <PublicLayout>
        <v-container>
            <div class="text-center mb-10">
                <h1 class="text-h3 font-weight-bold text-primary mb-2">Tu Ayuda Salva Vidas</h1>
                <p class="text-body-1 text-medium-emphasis">Elige tu método preferido y registra tu donación para ayudarnos a seguir.</p>
            </div>

            <v-row align="start">
                <!-- Left Column: Payment Methods -->
                <v-col cols="12" md="7">
                    <div class="mb-8">
                        <!-- Header aligned with Right Column Header -->
                        <h2 class="text-h5 font-weight-bold mb-4 d-flex align-center">
                            <v-icon color="secondary" class="mr-2">mdi-qrcode-scan</v-icon>
                            Billeteras Digitales
                        </h2>
                        <v-row>
                            <v-col v-for="method in qrMethods" :key="method.id" cols="12" sm="6">
                                <v-card class="rounded-xl elevation-2 h-100 border-thin" color="surface">
                                    <v-card-text class="text-center pa-6">
                                        <div class="text-h6 font-weight-bold mb-4 text-primary">{{ method.name }}</div>
                                        <v-sheet class="pa-2 rounded-lg d-inline-block mb-4" color="white" elevation="1">
                                            <v-img
                                                :src="method.qr_code_path"
                                                :alt="method.name"
                                                width="160"
                                                height="160"
                                                cover
                                                class="rounded"
                                            ></v-img>
                                        </v-sheet>
                                        <p class="text-body-2 mb-2 text-medium-emphasis">{{ method.instructions }}</p>
                                        <v-chip
                                            color="secondary"
                                            variant="tonal"
                                            class="font-weight-bold"
                                            @click="copyToClipboard(method.account_number)"
                                        >
                                            {{ method.account_number }}
                                            <v-icon end size="small">mdi-content-copy</v-icon>
                                        </v-chip>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                        </v-row>
                    </div>

                    <div v-if="bankMethods.length > 0">
                        <h2 class="text-h5 font-weight-bold mb-4 d-flex align-center">
                            <v-icon color="secondary" class="mr-2">mdi-bank</v-icon>
                            Transferencias Bancarias
                        </h2>
                        <v-card class="rounded-xl elevation-2 border-thin">
                            <v-list lines="two" class="rounded-xl py-2">
                                <template v-for="(method, index) in bankMethods" :key="method.id">
                                    <v-list-item class="py-3 px-10">

                                        <v-list-item-title class="font-weight-bold text-body-1 mb-1">
                                            {{ method.name }}
                                        </v-list-item-title>

                                        <v-list-item-subtitle class="mb-2">
                                            {{ method.instructions }}
                                        </v-list-item-subtitle>

                                        <div class="d-flex flex-wrap gap-2 align-center mt-1">
                                            <v-chip
                                                size="small"
                                                variant="outlined"
                                                color="grey-darken-2"
                                                @click="copyToClipboard(method.account_number)"
                                                class="mr-2 mb-1"
                                            >
                                                <v-icon start size="small">mdi-card-account-details</v-icon>
                                                {{ method.account_number }}
                                            </v-chip>

                                            <v-chip
                                                v-if="method.cci"
                                                size="small"
                                                variant="outlined"
                                                color="grey-darken-2"
                                                @click="copyToClipboard(method.cci)"
                                                class="mb-1"
                                            >
                                                <v-icon start size="small">mdi-bank-transfer</v-icon>
                                                CCI: {{ method.cci }}
                                            </v-chip>
                                        </div>
                                    </v-list-item>
                                    <v-divider v-if="index < bankMethods.length - 1" inset></v-divider>
                                </template>
                            </v-list>
                        </v-card>
                    </div>
                </v-col>

                <!-- Right Column: Registration Form -->
                <v-col cols="12" md="5">
                    <!-- Title OUTSIDE the card to match Left Column alignment -->
                    <h2 class="text-h5 font-weight-bold mb-4 d-flex align-center">
                        <v-icon color="secondary" class="mr-2">mdi-file-document-edit-outline</v-icon>
                        Registrar Donación
                    </h2>

                    <v-card class="rounded-xl elevation-4">
                        <!-- Clean card without heavy header, matching the QR cards style -->
                        <v-card-text class="pa-6 pt-8">
                            <v-alert
                                color="primary"
                                variant="tonal"
                                class="mb-6 rounded-lg"
                                border="start"
                                density="compact"
                            >
                                <div class="text-caption font-weight-medium">
                                    Envíanos tu comprobante para validar tu ayuda.
                                </div>
                            </v-alert>

                            <v-form @submit.prevent="submit">
                                <v-text-field
                                    v-model="form.donor_name"
                                    label="Tu Nombre Completo"
                                    variant="outlined"
                                    density="comfortable"
                                    prepend-inner-icon="mdi-account"
                                    :rules="[v => !!v || 'Requerido']"
                                    :error-messages="form.errors.donor_name"
                                    class="mb-2"
                                ></v-text-field>

                                <v-text-field
                                    v-model="form.amount"
                                    label="Monto Donado (S/)"
                                    type="number"
                                    variant="outlined"
                                    density="comfortable"
                                    prepend-inner-icon="mdi-cash"
                                    :rules="[v => !!v || 'Requerido']"
                                    :error-messages="form.errors.amount"
                                    class="mb-2"
                                ></v-text-field>

                                <v-select
                                    v-model="form.payment_method"
                                    :items="paymentMethods.map(method => method.name)"
                                    label="Método Utilizado"
                                    variant="outlined"
                                    density="comfortable"
                                    prepend-inner-icon="mdi-credit-card-outline"
                                    :rules="[v => !!v || 'Requerido']"
                                    :error-messages="form.errors.payment_method"
                                    class="mb-2"
                                ></v-select>

                                <v-file-input
                                    label="Adjuntar Comprobante (Imagen)"
                                    @input="form.proof = $event.target.files[0]"
                                    variant="outlined"
                                    density="comfortable"
                                    prepend-icon=""
                                    prepend-inner-icon="mdi-paperclip"
                                    :rules="[v => !!v || 'Requerido']"
                                    :error-messages="form.errors.proof"
                                    show-size
                                    class="mb-6"
                                ></v-file-input>

                                <v-btn
                                    type="submit"
                                    :loading="form.processing"
                                    color="secondary"
                                    block
                                    size="large"
                                    class="rounded-pill font-weight-bold elevation-2"
                                >
                                    Enviar Registro
                                    <v-icon end>mdi-send</v-icon>
                                </v-btn>
                            </v-form>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>

            <v-snackbar
                v-model="snackbar"
                color="success"
                :timeout="2000"
                location="bottom center"
                rounded="pill"
            >
                <v-icon start>mdi-check</v-icon>
                Número copiado al portapapeles
            </v-snackbar>
        </v-container>
    </PublicLayout>
</template>

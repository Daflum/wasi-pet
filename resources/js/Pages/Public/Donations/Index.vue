<script setup>
import { computed } from 'vue';
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

const copyToClipboard = (text) => {
    navigator.clipboard.writeText(text);
};

const form = useForm({
    donor_name: '',
    amount: '',
    payment_method: '',
    proof: null,
});

const submit = () => {
    form.post(route('public.donations.store'));
};
</script>

<template>
    <Head title="Donaciones"/>

    <PublicLayout>
        <v-container>
            <v-row>
                <v-col cols="12" md="6">
                    <v-card>
                        <v-card-title>Canales de Donación</v-card-title>
                        <v-card-text>
                            <v-row>
                                <v-col v-for="method in qrMethods" :key="method.id" cols="12" sm="6" class="text-center">
                                    <v-card flat>
                                        <v-card-title>{{ method.name }}</v-card-title>
                                        <v-card-text>
                                            <v-img :src="method.qr_code_path" :alt="method.name" class="mx-auto" max-width="150"></v-img>
                                            <p class="mt-2">{{ method.instructions }}</p>
                                            <p class="font-weight-bold">{{ method.account_number }}</p>
                                        </v-card-text>
                                    </v-card>
                                </v-col>
                            </v-row>
                            <v-divider class="my-4"></v-divider>
                            <v-list>
                                <v-list-item v-for="method in bankMethods" :key="method.id">
                                    <v-list-item-title>{{ method.name }}</v-list-item-title>
                                    <v-list-item-subtitle>{{ method.instructions }}</v-list-item-subtitle>
                                    <v-list-item-content>
                                        <p>Cuenta: {{ method.account_number }}</p>
                                        <p v-if="method.cci">CCI: {{ method.cci }}</p>
                                    </v-list-item-content>
                                    <template v-slot:append>
                                        <v-btn icon @click="copyToClipboard(method.account_number)">
                                            <v-icon>mdi-content-copy</v-icon>
                                        </v-btn>
                                    </template>
                                </v-list-item>
                            </v-list>
                        </v-card-text>
                    </v-card>
                </v-col>

                <v-col cols="12" md="6">
                    <v-card>
                        <v-card-title>Registrar Donación</v-card-title>
                        <v-card-text>
                            <v-form @submit.prevent="submit">
                                <v-text-field
                                    v-model="form.donor_name"
                                    label="Nombre del Donante"
                                    required
                                    :error-messages="form.errors.donor_name"
                                ></v-text-field>

                                <v-text-field
                                    v-model="form.amount"
                                    label="Monto"
                                    type="number"
                                    required
                                    :error-messages="form.errors.amount"
                                ></v-text-field>

                                <v-select
                                    v-model="form.payment_method"
                                    :items="paymentMethods.map(method => method.name)"
                                    label="Método de Pago"
                                    required
                                    :error-messages="form.errors.payment_method"
                                ></v-select>

                                <v-file-input
                                    label="Comprobante"
                                    @input="form.proof = $event.target.files[0]"
                                    :error-messages="form.errors.proof"
                                ></v-file-input>

                                <v-btn type="submit" :loading="form.processing" color="primary">
                                    Registrar
                                </v-btn>
                            </v-form>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </PublicLayout>
</template>

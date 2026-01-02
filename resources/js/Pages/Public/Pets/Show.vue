<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import SponsorshipModal from '@/Components/SponsorshipModal.vue';
import { ref, computed } from 'vue';
import { useDisplay } from 'vuetify';

const props = defineProps({
    pet: Object,
    settings: Object,
    paymentMethods: Array,
});

const { mdAndUp } = useDisplay();
const dialog = ref(false);
const sponsorshipDialog = ref(false);
const snackbar = ref(false);
const formRef = ref(null);

const statusChip = computed(() => {
    switch (props.pet.status) {
        case 'Disponible':
            return { color: 'success', text: 'Disponible' };
        case 'En Proceso':
            return { color: 'warning', text: 'En Proceso' };
        case 'Adoptado':
            return { color: 'grey', text: 'Adoptado' };
        default:
            return { color: 'grey', text: 'Desconocido' };
    }
});

const adoptionButton = computed(() => {
    switch (props.pet.status) {
        case 'Disponible':
            return { text: '¡Quiero Adoptar!', icon: 'mdi-home-heart', disabled: false };
        case 'En Proceso':
            // Shortened text for better fit
            return { text: 'Lista de Espera', icon: 'mdi-clock-outline', disabled: false };
        case 'Adoptado':
            return { text: 'Ya Adoptado', icon: 'mdi-check-circle', disabled: true };
        default:
            return { text: 'No Disponible', icon: 'mdi-cancel', disabled: true };
    }
});

const rules = {
    required: v => !!v || 'Este campo es obligatorio.',
    email: v => /.+@.+\..+/.test(v) || 'Debe ser un correo electrónico válido.',
    dni: v => (v && v.length === 8 && /^\d+$/.test(v)) || 'El DNI debe tener 8 dígitos numéricos.',
    phone: v => (v && v.length === 9 && /^\d+$/.test(v)) || 'El celular debe tener 9 dígitos numéricos.',
};

const form = useForm({
    pet_id: props.pet.id,
    name: '',
    email: '',
    dni: '',
    phone: '',
    address: '',
});

const submit = async () => {
    const { valid } = await formRef.value.validate();
    if (!valid) return;

    form.post(route('public.adoption-requests.store'), {
        preserveScroll: true,
        onSuccess: () => {
            dialog.value = false;
            form.reset();
            snackbar.value = true;
        },
    });
};
</script>

<template>
    <Head :title="pet.name" />
    <PublicLayout>
        <v-container>
            <div class="mb-6">
                <v-btn
                    :href="route('public.pets.index')"
                    variant="text"
                    prepend-icon="mdi-arrow-left"
                    is="Link"
                    class="font-weight-bold"
                >
                    Volver a Mascotas
                </v-btn>
            </div>
            <v-row>
                <!-- Pet Image -->
                <v-col cols="12" md="6" :style="mdAndUp ? 'position: sticky; top: 100px;' : ''">
                    <v-img
                        :src="pet.image"
                        :alt="pet.name"
                        cover
                        height="500"
                        class="rounded-xl elevation-6"
                    ></v-img>
                </v-col>

                <!-- Pet Details -->
                <v-col cols="12" md="6" class="pl-md-8">
                    <div class="d-flex flex-column flex-sm-row align-start align-sm-center mb-6">
                        <h1 class="text-h3 font-weight-bold text-primary mr-4 mb-2 mb-sm-0">{{ pet.name }}</h1>
                        <v-chip
                            :color="statusChip.color"
                            variant="elevated"
                            class="font-weight-bold"
                            size="large"
                        >
                            {{ statusChip.text }}
                        </v-chip>
                    </div>

                    <div class="d-flex flex-wrap ga-4 mb-8">
                        <v-card class="pa-4 text-center rounded-xl flex-grow-1 elevation-1" color="grey-lighten-5">
                             <v-icon icon="mdi-cake-variant" color="secondary" size="large" class="mb-2"></v-icon>
                            <div class="text-caption text-uppercase text-medium-emphasis font-weight-bold">Edad</div>
                            <div class="text-h6 font-weight-bold">{{ pet.age_label }}</div>
                        </v-card>
                         <v-card class="pa-4 text-center rounded-xl flex-grow-1 elevation-1" color="grey-lighten-5">
                            <v-icon icon="mdi-ruler" color="secondary" size="large" class="mb-2"></v-icon>
                            <div class="text-caption text-uppercase text-medium-emphasis font-weight-bold">Tamaño</div>
                            <div class="text-h6 font-weight-bold">{{ pet.size_label || 'N/A' }}</div>
                        </v-card>
                         <v-card class="pa-4 text-center rounded-xl flex-grow-1 elevation-1" color="grey-lighten-5">
                            <v-icon :icon="pet.gender === 'Macho' ? 'mdi-gender-male' : 'mdi-gender-female'" :color="pet.gender === 'Macho' ? 'blue' : 'pink'" size="large" class="mb-2"></v-icon>
                            <div class="text-caption text-uppercase text-medium-emphasis font-weight-bold">Género</div>
                            <div class="text-h6 font-weight-bold">{{ pet.gender || 'N/A' }}</div>
                        </v-card>
                    </div>

                    <h3 class="text-h5 font-weight-bold mb-3 text-primary">Mi Historia</h3>
                    <p class="text-body-1 mb-8 text-medium-emphasis" style="white-space: pre-line; line-height: 1.8;">
                        {{ pet.description || 'Esta mascota aún no tiene una historia descrita, pero está ansiosa por conocerte.' }}
                    </p>

                    <v-alert
                        v-if="pet.status === 'En Proceso'"
                        type="warning"
                        variant="tonal"
                        class="mb-8 rounded-lg border-warning"
                        icon="mdi-clock-alert-outline"
                        title="¡En Lista de Espera!"
                    >
                        Esta mascota ya tiene un proceso de adopción en curso. Si continúas, entrarás en una lista de espera y te contactaremos si la adopción actual no se completa.
                    </v-alert>

                    <v-row dense>
                        <v-col cols="12" sm="6" class="mb-2 mb-sm-0">
                            <v-btn
                                color="primary"
                                size="x-large"
                                :prepend-icon="adoptionButton.icon"
                                @click="dialog = true"
                                :disabled="adoptionButton.disabled"
                                block
                                class="rounded-pill font-weight-bold elevation-4"
                                height="56"
                            >
                                {{ adoptionButton.text }}
                            </v-btn>
                        </v-col>
                        <v-col cols="12" sm="6">
                            <v-btn
                                color="secondary"
                                variant="tonal"
                                size="x-large"
                                prepend-icon="mdi-gift-outline"
                                @click="sponsorshipDialog = true"
                                block
                                class="rounded-pill font-weight-bold"
                                height="56"
                            >
                                Apadrinar
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-col>
            </v-row>

            <!-- Adoption Modal -->
            <v-dialog v-model="dialog" max-width="500">
                <v-card class="rounded-xl">
                    <v-card-title class="text-h5 bg-primary text-white pa-6 font-weight-bold">
                        Solicitud de Adopción
                    </v-card-title>
                    <v-card-text class="pa-6">
                        <p class="mb-6 text-body-1">
                            Estás a un paso de cambiar la vida de <strong class="text-primary">{{ pet.name }}</strong>.
                            Déjanos tus datos y te contactaremos.
                        </p>

                        <v-form ref="formRef" @submit.prevent="submit">
                            <v-text-field
                                v-model="form.name"
                                label="Tu Nombre Completo"
                                variant="outlined"
                                density="comfortable"
                                prepend-inner-icon="mdi-account"
                                :rules="[rules.required]"
                                :error-messages="form.errors.name"
                                class="mb-2"
                            ></v-text-field>

                            <v-text-field
                                v-model="form.email"
                                label="Correo Electrónico"
                                variant="outlined"
                                density="comfortable"
                                prepend-inner-icon="mdi-email"
                                :rules="[rules.required, rules.email]"
                                :error-messages="form.errors.email"
                                class="mb-2"
                            ></v-text-field>

                             <v-text-field
                                v-model="form.dni"
                                label="DNI"
                                variant="outlined"
                                density="comfortable"
                                prepend-inner-icon="mdi-card-account-details"
                                :rules="[rules.required, rules.dni]"
                                :error-messages="form.errors.dni"
                                maxlength="8"
                                counter
                                class="mb-2"
                            ></v-text-field>

                            <v-text-field
                                v-model="form.phone"
                                label="Tu Teléfono / WhatsApp"
                                variant="outlined"
                                density="comfortable"
                                prepend-inner-icon="mdi-whatsapp"
                                :rules="[rules.required, rules.phone]"
                                :error-messages="form.errors.phone"
                                maxlength="9"
                                counter
                                class="mb-2"
                            ></v-text-field>

                             <v-text-field
                                v-model="form.address"
                                label="Dirección"
                                variant="outlined"
                                density="comfortable"
                                prepend-inner-icon="mdi-map-marker"
                                :rules="[rules.required]"
                                :error-messages="form.errors.address"
                                class="mb-4"
                            ></v-text-field>

                            <div class="d-flex justify-end ga-3 mt-2">
                                <v-btn variant="text" class="rounded-pill" @click="dialog = false">Cancelar</v-btn>
                                <v-btn
                                    type="submit"
                                    color="primary"
                                    class="rounded-pill px-6"
                                    elevation="2"
                                    :loading="form.processing"
                                >
                                    Enviar Solicitud
                                </v-btn>
                            </div>
                        </v-form>
                    </v-card-text>
                </v-card>
            </v-dialog>

            <SponsorshipModal
                :show="sponsorshipDialog"
                :pet="pet"
                :settings="settings"
                :payment-methods="paymentMethods"
                @close="sponsorshipDialog = false"
            />

            <v-snackbar
                v-model="snackbar"
                color="success"
                :timeout="3000"
                location="top right"
                rounded="pill"
            >
                <div class="d-flex align-center">
                    <v-icon start icon="mdi-check-circle" class="mr-2"></v-icon>
                    ¡Solicitud enviada! Pronto te contactaremos.
                </div>
            </v-snackbar>

        </v-container>
    </PublicLayout>
</template>

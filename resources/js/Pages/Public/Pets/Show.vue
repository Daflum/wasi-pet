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
            return { text: '¡Quiero Adoptar!', disabled: false };
        case 'En Proceso':
            return { text: 'Unirme a la Lista de Espera', disabled: false };
        case 'Adoptado':
            return { text: 'Adoptado', disabled: true };
        default:
            return { text: 'No Disponible', disabled: true };
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
            <div class="mb-4">
                <v-btn
                    :href="route('public.pets.index')"
                    variant="text"
                    prepend-icon="mdi-arrow-left"
                    is="Link"
                >
                    Volver
                </v-btn>
            </div>
            <v-row>
                <!-- Pet Image -->
                <v-col cols="12" md="6" :style="mdAndUp ? 'position: sticky; top: 20px;' : ''">
                    <v-img
                        :src="pet.image"
                        :alt="pet.name"
                        cover
                        height="400"
                        rounded="lg"
                        elevation="10"
                    ></v-img>
                </v-col>

                <!-- Pet Details -->
                <v-col cols="12" md="6">
                    <div class="d-flex flex-column flex-sm-row align-start align-sm-center mb-4">
                        <h1 class="text-h3 font-weight-bold text-primary mr-4 mb-2 mb-sm-0">{{ pet.name }}</h1>
                        <v-chip
                            :color="statusChip.color"
                            variant="flat"
                            class="mr-2"
                        >
                            {{ statusChip.text }}
                        </v-chip>
                    </div>

                    <div class="d-flex flex-wrap ga-4 mb-6">
                        <v-card class="pa-3 text-center rounded-lg flex-grow-1" color="grey-lighten-4">
                             <v-icon icon="mdi-cake-variant" size="x-large" class="mb-1"></v-icon>
                            <div class="text-caption text-uppercase text-medium-emphasis">Edad</div>
                            <div class="text-h6 font-weight-bold">{{ pet.age_label }}</div>
                        </v-card>
                         <v-card class="pa-3 text-center rounded-lg flex-grow-1" color="grey-lighten-4">
                            <v-icon icon="mdi-ruler" size="x-large" class="mb-1"></v-icon>
                            <div class="text-caption text-uppercase text-medium-emphasis">Tamaño</div>
                            <div class="text-h6 font-weight-bold">{{ pet.size || 'N/A' }}</div>
                        </v-card>
                         <v-card class="pa-3 text-center rounded-lg flex-grow-1" color="grey-lighten-4">
                            <v-icon :icon="pet.gender === 'Macho' ? 'mdi-gender-male' : 'mdi-gender-female'" size="x-large" class="mb-1"></v-icon>
                            <div class="text-caption text-uppercase text-medium-emphasis">Género</div>
                            <div class="text-h6 font-weight-bold">{{ pet.gender || 'N/A' }}</div>
                        </v-card>
                    </div>

                    <h3 class="text-h5 font-weight-bold mb-2">Mi Historia</h3>
                    <p class="text-body-1 mb-8" style="white-space: pre-line;">
                        {{ pet.description || 'Esta mascota aún no tiene una historia descrita, pero está ansiosa por conocerte.' }}
                    </p>

                    <v-alert
                        v-if="pet.status === 'En Proceso'"
                        type="warning"
                        variant="tonal"
                        class="mb-6"
                        title="¡En Lista de Espera!"
                        text="Esta mascota ya tiene un proceso de adopción en curso. Si continúas, entrarás en una lista de espera y te contactaremos si la adopción actual no se completa."
                    ></v-alert>

                    <v-row>
                        <v-col cols="12" sm="6">
                            <v-btn
                                color="primary"
                                size="x-large"
                                prepend-icon="mdi-home-heart"
                                @click="dialog = true"
                                :disabled="adoptionButton.disabled"
                                block
                            >
                                {{ adoptionButton.text }}
                            </v-btn>
                        </v-col>
                        <v-col cols="12" sm="6">
                            <v-btn
                                color="secondary"
                                size="x-large"
                                prepend-icon="mdi-gift"
                                @click="sponsorshipDialog = true"
                                block
                            >
                                Apadrinar
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-col>
            </v-row>

            <!-- Adoption Modal -->
            <v-dialog v-model="dialog" max-width="500">
                <v-card>
                    <v-card-title class="text-h5 bg-primary text-white pa-4">
                        Solicitud de Adopción
                    </v-card-title>
                    <v-card-text class="pa-4">
                        <p class="mb-4">
                            Estás a un paso de cambiar la vida de <strong>{{ pet.name }}</strong>.
                            Déjanos tus datos y te contactaremos.
                        </p>

                        <v-form ref="formRef" @submit.prevent="submit">
                            <v-text-field
                                v-model="form.name"
                                label="Tu Nombre Completo"
                                variant="outlined"
                                :rules="[rules.required]"
                                :error-messages="form.errors.name"
                            ></v-text-field>

                            <v-text-field
                                v-model="form.email"
                                label="Correo Electrónico"
                                variant="outlined"
                                :rules="[rules.required, rules.email]"
                                :error-messages="form.errors.email"
                            ></v-text-field>

                             <v-text-field
                                v-model="form.dni"
                                label="DNI"
                                variant="outlined"
                                :rules="[rules.required, rules.dni]"
                                :error-messages="form.errors.dni"
                                maxlength="8"
                                counter
                            ></v-text-field>

                            <v-text-field
                                v-model="form.phone"
                                label="Tu Teléfono / WhatsApp"
                                variant="outlined"
                                :rules="[rules.required, rules.phone]"
                                :error-messages="form.errors.phone"
                                maxlength="9"
                                counter
                            ></v-text-field>

                             <v-text-field
                                v-model="form.address"
                                label="Dirección"
                                variant="outlined"
                                :rules="[rules.required]"
                                :error-messages="form.errors.address"
                            ></v-text-field>

                            <div class="d-flex justify-end ga-2 mt-4">
                                <v-btn variant="text" @click="dialog = false">Cancelar</v-btn>
                                <v-btn
                                    type="submit"
                                    color="primary"
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
            >
                ¡Solicitud enviada! Pronto te contactaremos.
            </v-snackbar>

        </v-container>
    </PublicLayout>
</template>

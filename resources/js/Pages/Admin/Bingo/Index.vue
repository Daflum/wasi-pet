<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import {
    VCard,
    VCardTitle,
    VCardText,
    VRow,
    VCol,
    VList,
    VListItem,
    VListItemTitle,
    VListItemSubtitle,
    VTextField,
    VFileInput,
    VBtn,
    VDivider,
    VIcon,
} from 'vuetify/components';

const props = defineProps({
    events: Array,
});

const form = useForm({
    event_slug: '',
    quantity: 10,
    start_sequence: 1,
    background_image: null,
});

const selectedEvent = ref(null);

const selectEvent = (event) => {
    selectedEvent.value = event;
    form.event_slug = event.event_slug;
    form.start_sequence = event.total + 1;
};

const createNewEvent = () => {
    selectedEvent.value = null;
    form.reset();
};

const downloadTemplateGuide = () => {
    window.location.href = route('admin.bingo.template-guide');
};

const submit = () => {
    form.post(route('admin.bingo.store'), {
        onSuccess: (page) => {
            const downloadUrl = page.props.flash.download_url;
            if (downloadUrl) {
                const link = document.createElement('a');
                link.href = downloadUrl;
                link.setAttribute('download', '');
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }

            const updatedEvent = page.props.events.find(e => e.event_slug === form.event_slug);

            if (updatedEvent) {
                selectedEvent.value = updatedEvent;
                form.start_sequence = updatedEvent.total + 1;
            }
        },
        onFinish: () => {
            form.reset('background_image');
        },
    });
};
</script>

<template>
    <AdminLayout title="Generador de Bingos">
        <VRow>
            <VCol cols="12" md="4">
                <VCard>
                    <VCardTitle>Eventos Existentes</VCardTitle>
                    <VCardText>
                        <VBtn v-if="events.length > 0" block color="primary" @click="createNewEvent" class="mb-4">
                            Crear Nuevo Evento
                        </VBtn>
                        <VList v-if="events.length > 0" lines="two">
                            <VListItem
                                v-for="event in events"
                                :key="event.event_slug"
                                @click="selectEvent(event)"
                                :active="selectedEvent && selectedEvent.event_slug === event.event_slug"
                            >
                                <VListItemTitle>{{ event.event_slug }}</VListItemTitle>
                                <VListItemSubtitle>
                                    {{ event.total }} cartones generados. Última vez: {{ new Date(event.last_generated).toLocaleString() }}
                                </VListItemSubtitle>
                            </VListItem>
                        </VList>
                        <p v-else>No se han generado bingos todavía.</p>
                    </VCardText>
                </VCard>
            </VCol>
            <VCol cols="12" md="8">
                <VCard>
                    <VCardTitle>
                        <span v-if="selectedEvent">Continuar Evento: {{ selectedEvent.event_slug }}</span>
                        <span v-else>Nuevo Evento de Bingo</span>
                    </VCardTitle>
                    <VDivider />
                    <VCardText>
                        <form @submit.prevent="submit">
                            <VTextField
                                v-model="form.event_slug"
                                label="Identificador del Evento (slug)"
                                :error-messages="form.errors.event_slug"
                                :disabled="!!selectedEvent"
                                required
                                placeholder="ej: navidad-2024"
                            />

                            <VTextField
                                v-model.number="form.quantity"
                                label="Cantidad de Cartones a Generar"
                                type="number"
                                :error-messages="form.errors.quantity"
                                required
                            />

                            <VTextField
                                v-model.number="form.start_sequence"
                                label="Número de Secuencia Inicial"
                                type="number"
                                :error-messages="form.errors.start_sequence"
                                required
                                help-text="El primer cartón tendrá este número en su nombre."
                            />

                            <div class="d-flex align-center justify-space-between mb-2">
                                <span class="text-subtitle-1">Plantilla de Fondo</span>
                                <VBtn
                                    variant="text"
                                    color="info"
                                    size="small"
                                    prepend-icon="mdi-download"
                                    @click="downloadTemplateGuide"
                                >
                                    Descargar Guía de Diseño
                                </VBtn>
                            </div>

                            <VFileInput
                                v-model="form.background_image"
                                label="Seleccionar archivo (Opcional)"
                                accept="image/*"
                                :error-messages="form.errors.background_image"
                            />

                            <VBtn
                                type="submit"
                                color="primary"
                                :loading="form.processing"
                                :disabled="form.processing"
                                block
                                class="mt-4"
                            >
                                <VIcon left>mdi-creation</VIcon>
                                Generar y Descargar ZIP
                            </VBtn>
                        </form>
                    </VCardText>
                </VCard>
            </VCol>
        </VRow>
    </AdminLayout>
</template>

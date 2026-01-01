<script setup>
import { useForm } from '@inertiajs/vue3';
import { defineProps, defineEmits, ref, computed } from 'vue';

const props = defineProps({
  show: Boolean,
  pet: Object,
  settings: Object,
  paymentMethods: Array,
});

const emit = defineEmits(['close']);

const qrMethods = computed(() => {
    return (props.paymentMethods || []).filter(method => method.qr_code_path !== null);
});

const bankMethods = computed(() => {
    return (props.paymentMethods || []).filter(method => method.qr_code_path === null);
});

const form = useForm({
  donor_name: '',
  amount: null,
  proof: null,
  pet_id: props.pet?.id,
  payment_method: '',
});

const submit = async () => {
  const { valid } = await formRef.value.validate();
  if (!valid) return;

  form.post(route('public.donations.store'), {
    onSuccess: () => {
      emit('close');
      form.reset();
    },
  });
};

const close = () => {
  emit('close');
};

const copyToClipboard = (text) => {
  navigator.clipboard.writeText(text);
};

const rules = {
    required: v => !!v || 'Este campo es obligatorio.',
    positive: v => (v && v > 0) || 'El monto debe ser mayor a 0.',
};

const formRef = ref(null);
</script>

<template>
  <v-dialog :model-value="show" @update:model-value="close" max-width="600px">
    <v-card>
      <v-card-title class="bg-primary text-white pa-4">
        <span class="headline">Apadrinar a {{ pet.name }}</span>
      </v-card-title>
      <v-card-text class="pa-4">

        <!-- Canales de Donación (Diseño tipo Donations/Index) -->
        <div v-if="paymentMethods && paymentMethods.length > 0">
            <h3 class="text-subtitle-1 font-weight-bold mb-4">Canales de Donación:</h3>

            <!-- QRs arriba -->
            <v-row v-if="qrMethods.length > 0">
                <v-col v-for="method in qrMethods" :key="method.id" cols="12" sm="6" class="text-center mb-4">
                    <v-card flat variant="tonal" class="pa-3 rounded-lg">
                        <p class="font-weight-bold text-primary mb-2">{{ method.name }}</p>
                        <v-img :src="method.qr_code_path" :alt="method.name" class="mx-auto mb-2" max-width="150" rounded></v-img>
                        <p class="text-body-2 font-weight-bold mb-0">{{ method.account_number }}</p>
                        <p v-if="method.instructions" class="text-caption text-medium-emphasis italic">{{ method.instructions }}</p>
                    </v-card>
                </v-col>
            </v-row>

            <v-divider v-if="qrMethods.length > 0 && bankMethods.length > 0" class="my-4"></v-divider>

            <!-- Cuentas Bancarias debajo -->
            <div v-if="bankMethods.length > 0">
                <h4 class="text-subtitle-2 font-weight-bold mb-3">Cuentas Bancarias:</h4>
                <v-list class="pa-0">
                    <v-list-item v-for="method in bankMethods" :key="method.id" class="px-0 mb-2 border rounded-lg">
                        <template v-slot:prepend>
                            <v-icon color="primary" class="ml-2">mdi-bank</v-icon>
                        </template>
                        <v-list-item-title class="font-weight-bold">{{ method.name }}</v-list-item-title>
                        <v-list-item-subtitle>
                            <div>Cuenta: {{ method.account_number }}</div>
                            <div v-if="method.cci">CCI: {{ method.cci }}</div>
                        </v-list-item-subtitle>
                        <template v-slot:append>
                            <v-btn
                                icon="mdi-content-copy"
                                size="small"
                                variant="text"
                                color="primary"
                                @click="copyToClipboard(method.account_number)"
                                title="Copiar cuenta"
                            ></v-btn>
                        </template>
                    </v-list-item>
                </v-list>
            </div>

            <v-divider class="my-6"></v-divider>
        </div>
        <div v-else class="text-center mb-6">
            <p class="text-body-2 text-medium-emphasis">No hay métodos de pago configurados en este momento.</p>
        </div>

        <!-- Formulario de Registro -->
        <h3 class="text-subtitle-1 font-weight-bold mb-4">Registra tu Donación:</h3>
        <v-form ref="formRef" @submit.prevent="submit">
          <v-text-field
            v-model="form.donor_name"
            label="Tu Nombre Completo"
            variant="outlined"
            density="comfortable"
            :rules="[rules.required]"
            :error-messages="form.errors.donor_name"
          ></v-text-field>

          <v-row>
              <v-col cols="12" sm="6">
                  <v-text-field
                    v-model="form.amount"
                    label="Monto (S/.)"
                    type="number"
                    variant="outlined"
                    density="comfortable"
                    min="1"
                    :rules="[rules.required, rules.positive]"
                    :error-messages="form.errors.amount"
                  ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6">
                  <v-select
                    v-model="form.payment_method"
                    :items="paymentMethods.map(m => m.name)"
                    label="Método Utilizado"
                    variant="outlined"
                    density="comfortable"
                    :rules="[rules.required]"
                    :error-messages="form.errors.payment_method"
                  ></v-select>
              </v-col>
          </v-row>

          <v-file-input
            label="Subir Voucher (Imagen)"
            variant="outlined"
            density="comfortable"
            prepend-icon="mdi-camera"
            @update:modelValue="form.proof = $event"
            :rules="[rules.required]"
            :error-messages="form.errors.proof"
            accept="image/*"
          ></v-file-input>

          <div class="d-flex justify-end ga-2 mt-4">
              <v-btn variant="text" @click="close">Cancelar</v-btn>
              <v-btn
                type="submit"
                color="primary"
                size="large"
                :loading="form.processing"
              >
                Enviar Apadrinamiento
              </v-btn>
          </div>
        </v-form>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    paymentMethod: Object,
});

const form = useForm({
    name: props.paymentMethod.name,
    account_number: props.paymentMethod.account_number,
    cci: props.paymentMethod.cci,
    qr_code_path: props.paymentMethod.qr_code_path,
    instructions: props.paymentMethod.instructions,
    is_active: props.paymentMethod.is_active,
});

const submit = () => {
    form.put(route('admin.payment-methods.update', props.paymentMethod.id));
};
</script>

<template>
    <Head title="Edit Payment Method" />

    <AdminLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h1 class="text-2xl font-semibold mb-4">Edit Payment Method</h1>

                        <form @submit.prevent="submit">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <InputLabel for="name" value="Name" />
                                    <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus />
                                    <InputError class="mt-2" :message="form.errors.name" />
                                </div>

                                <div>
                                    <InputLabel for="account_number" value="Account Number" />
                                    <TextInput id="account_number" type="text" class="mt-1 block w-full" v-model="form.account_number" required />
                                    <InputError class="mt-2" :message="form.errors.account_number" />
                                </div>

                                <div>
                                    <InputLabel for="cci" value="CCI" />
                                    <TextInput id="cci" type="text" class="mt-1 block w-full" v-model="form.cci" />
                                    <InputError class="mt-2" :message="form.errors.cci" />
                                </div>

                                <div>
                                    <InputLabel for="qr_code_path" value="QR Code Path" />
                                    <TextInput id="qr_code_path" type="text" class="mt-1 block w-full" v-model="form.qr_code_path" />
                                    <InputError class="mt-2" :message="form.errors.qr_code_path" />
                                </div>

                                <div class="md:col-span-2">
                                    <InputLabel for="instructions" value="Instructions" />
                                    <TextInput id="instructions" type="text" class="mt-1 block w-full" v-model="form.instructions" />
                                    <InputError class="mt-2" :message="form.errors.instructions" />
                                </div>

                                <div>
                                    <InputLabel for="is_active" value="Active" />
                                    <select id="is_active" class="mt-1 block w-full" v-model="form.is_active">
                                        <option :value="true">Yes</option>
                                        <option :value="false">No</option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.is_active" />
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Update
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

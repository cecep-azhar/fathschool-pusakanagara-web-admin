<script setup>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import JetButton from '@/Jetstream/Button.vue';
import JetFormSection from '@/Jetstream/FormSection.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetActionMessage from '@/Jetstream/ActionMessage.vue';
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue';

const props = defineProps({
    user: Object,
});

const form = useForm({
    _method: 'PUT',
    name: props.user.name,
    email: props.user.email,
    photo: null,
});

const verificationLinkSent = ref(null);
const photoPreview = ref(null);
const photoInput = ref(null);

const updateProfileInformation = () => {
    if (photoInput.value) {
        form.photo = photoInput.value.files[0];
    }

    form.post(route('user-profile-information.update'), {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
        onSuccess: () => clearPhotoFileInput(),
    });
};

const sendEmailVerification = () => {
    verificationLinkSent.value = true;
};

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];

    if (!photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };

    reader.readAsDataURL(photo);
};

const deletePhoto = () => {
    Inertia.delete(route('current-user-photo.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
            clearPhotoFileInput();
        },
    });
};

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
};
</script>

<template>
    <JetFormSection @submitted="updateProfileInformation">

        <template #form>
            <!-- Photo  -->
            <div class="col-span-6">
                <JetLabel for="photo" value="Photo" />
                <!-- Current Profile Photo -->
                <div v-show="! photoPreview" class="mt-2">
                    <img :src="user.profile_photo_url" :alt="user.name" class="rounded-full h-20 w-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div v-show="photoPreview" class="mt-2">
                    <span class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                        :style="'background-image: url(\'' + photoPreview + '\');'" />
                </div>

                <JetSecondaryButton class="mt-2 mr-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400"
                    type="button" @click.prevent="selectNewPhoto">
                    {{ __('Select A New Photo') }}
                </JetSecondaryButton>

                <JetSecondaryButton v-if="user.profile_photo_path" type="button"
                    class="mt-2 mr-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400"
                    @click.prevent="deletePhoto">
                    {{ __('Remove Photo') }}
                </JetSecondaryButton>

                <JetInputError :message="form.errors.photo" class="mt-2" />
            </div>

            <!-- Name  -->
            <div class="col-span-6">
                <JetLabel for="name" value="Name" />
                <JetInput id="name" :error="form.errors.name" v-model="form.name" type="text"
                    class="mt-1 block w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400"
                    autocomplete="name" />
                <JetInputError :message="form.errors.name" class="mt-2" />
            </div>

            <!-- Email  -->
            <div class="col-span-6">
                <JetLabel for="email" value="Email" />
                <JetInput id="email" v-model="form.email" type="email"
                    class="mt-1 block w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400" />
                <JetInputError :message="form.errors.email" class="mt-2" />

                <div v-if="$page.props.jetstream.hasEmailVerification && user.email_verified_at === null">
                    <p class="text-sm mt-2 dark:text-gray-400">
                        {{ __('Your email address is unverified') }}

                        <Link preserve-scroll :href="route('verification.send')" method="post" as="button"
                            class="underline text-gray-600 hover:text-gray-900 dark:text-gray-400"
                            @click.prevent="sendEmailVerification">
                        {{ __('Click here to re-send the verification email') }}
                        </Link>
                    </p>

                    <div v-show="verificationLinkSent"
                        class="mt-2 font-medium text-sm dark:text-gray-400 text-green-600">
                        {{ __('A new verification link has been sent to your email address') }}
                    </div>
                </div>
            </div>
        </template>

        <template #actions>
            <div class="flex items-center gap-6">
                <JetButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    {{ __('Save') }}
                </JetButton>
                <JetActionMessage :on="form.recentlySuccessful" class="mr-3 dark:text-gray-100">
                    {{ __('Saved') }}
                </JetActionMessage>
            </div>
        </template>
    </JetFormSection>
</template>

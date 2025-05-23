<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetCheckbox from '@/Jetstream/Checkbox.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>

    <Head title="Register" />

    <JetAuthenticationCard>
        <template #logo>
            <Link class="flex-none text-xl font-semibold dark:text-white" :href="route('dashboard')" aria-label="Brand">
                <app-logo class="block h-1/2 w-auto" />
            </Link>
        </template>

        <JetValidationErrors class="mb-4" />

        <form @submit.prevent="submit">
            <div>
                <JetLabel for="name" :value="__('Name')" />
                <JetInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required autofocus
                    autocomplete="name" />
            </div>

            <div class="mt-4">
                <JetLabel for="email" :value="__('Email')" />
                <JetInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" required />
            </div>

            <div class="mt-4">
                <JetLabel for="password" :value="__('Password')" />
                <JetInput id="password" v-model="form.password" type="password" class="mt-1 block w-full" required
                    autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <JetLabel for="password_confirmation" :value="__('Confirm Password')" />
                <JetInput id="password_confirmation" v-model="form.password_confirmation" type="password"
                    class="mt-1 block w-full" required autocomplete="new-password" />
            </div>

            <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-4">
                <JetLabel for="terms">
                    <div class="flex items-center">
                        <JetCheckbox id="terms" v-model:checked="form.terms" name="terms" />

                        <div class="ml-2">
                            {{ __('I agree') }} <a target="_blank" :href="route('terms.show')"
                                class="underline text-sm text-gray-600 hover:text-gray-900">{{ __('Terms of Service') }}</a> {{ __('and') }} <a
                                target="_blank" :href="route('policy.show')"
                                class="underline text-sm text-gray-600 hover:text-gray-900">{{ __('Privacy Policy') }}</a>
                        </div>
                    </div>
                </JetLabel>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link preserve-scroll :href="route('login')"
                    class="underline text-sm text-gray-600 hover:text-gray-900">
                    {{ __('Already registered') }}
                </Link>

                <global-button :loading="form.processing" type="submit"
                    cssClass="ml-4" theme="primary">
                    {{ __('Register') }}
                </global-button>
            </div>
        </form>
    </JetAuthenticationCard>
</template>

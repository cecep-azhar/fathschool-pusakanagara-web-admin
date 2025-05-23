<template>
    <Head :title="__('Teacher / Administartion Leave List')" />
    <AppLayout>
        <Breadcrumb>
            <BreadcrumbLink :title="__('Teacher / Administartion Leave')" />
        </Breadcrumb>

        <div class="pb-12 overflow-x-auto">
            <page-header>
                <div class="dark:text-gray-400 flex items-center">
                    <span>{{ __('Teacher / Adminstration Leave List') }}</span>
                    <span class="bg-blue-500 text-white rounded ml-2 px-1 text-xs py-0.5">
                        {{ leaves.total }}
                    </span>
                </div>
                <template #content>
                    <global-button :loading="false" type="link" :url="route('leave-type.index')" theme="primary" id="testLeaveType">
                        {{ __('Leave Type') }}
                    </global-button>
                </template>
            </page-header>

            <div>
                <div class="mb-4">
                    <form
                        class="grid grid-cols-1 gap-4 xl:grid-cols-7 xl:gap-4 items-end"
                        @submit.prevent="filterData()"
                    >
                        <!-- Search -->
                        <div class="xl:col-span-2">
                            <div class="relative w-full">
                                <global-input
                                    v-model="searchQuery"
                                    type="text"
                                    class="block w-full pr-10 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400"
                                    :placeholder="__('Keyword')"
                                />
                                <button
                                    type="submit"
                                    class="absolute inset-y-0 right-0 flex items-center px-3 text-white bg-blue-700 rounded-r-lg hover:bg-blue-800 focus:ring-2 focus:ring-blue-500"
                                >
                                    <MagnifyingGlassIcon class="w-5 h-5" />
                                    <span class="sr-only">{{
                                        __("Search")
                                    }}</span>
                                </button>
                            </div>
                        </div>

                        <!-- From Date -->
                        <div class="xl:col-span-1">
                            <label
                                for="start_date"
                                class="block mb-1 text-sm font-medium"
                            >
                                {{ __("From") }}
                            </label>
                            <input
                                type="date"
                                id="start_date"
                                v-model="filter.start_date"
                                class="w-full p-2 border rounded"
                            />
                        </div>

                        <!-- To Date -->
                        <div class="xl:col-span-1">
                            <label
                                for="end_date"
                                class="block mb-1 text-sm font-medium"
                            >
                                {{ __("To") }}
                            </label>
                            <input
                                type="date"
                                id="end_date"
                                v-model="filter.end_date"
                                class="w-full p-2 border rounded"
                            />
                        </div>

                        <div class="xl:col-span-1">
                            <a-select
                                class="width-100"
                                size="large"
                                v-model:value="filter.role"
                                show-search
                                :placeholder="__('Select a role')"
                                :options="[
                                    { title: 'All', id: 'all', value: 'all' },
                                    { title: 'Teacher', id: 'teacher', value: 'Teacher' },
                                    { title: 'Administration', id: 'administration', value: 'Administration' },
                                    { title: 'Accountant', id: 'accountant', value: 'Accountant' },
                                ]"
                                :filter-option="filterOption"
                                @focus="handleFocus"
                                @blur="handleBlur"
                                @change="handleChange"
                            />
                        </div>

                        <!-- Leave Type -->
                        <div class="xl:col-span-2">
                            <a-select
                                class="w-full"
                                size="large"
                                v-model:value="filter.leave_type"
                                show-search
                                :placeholder="__('Select a leave type')"
                                :options="options"
                                @focus="handleFocus"
                                @blur="handleBlur"
                                @change="handleChange"
                            />
                        </div>

                        <!-- Status -->
                        <div class="xl:col-span-1">
                            <a-select
                                class="w-full"
                                size="large"
                                v-model:value="filter.status"
                                show-search
                                :placeholder="__('Select a status')"
                                :options="[
                                    { title: 'All', id: 'all', value: 'all' },
                                    {
                                        title: 'Pending',
                                        id: 'pending',
                                        value: 'pending',
                                    },
                                    {
                                        title: 'Accepted',
                                        id: 'accepted',
                                        value: 'accepted',
                                    },
                                    {
                                        title: 'Rejected',
                                        id: 'rejected',
                                        value: 'rejected',
                                    },
                                ]"
                                @focus="handleFocus"
                                @blur="handleBlur"
                                @change="handleChange"
                            />
                        </div>

                        <!-- Button -->
                        <div class="xl:col-span-1">
                            <global-button
                                :loading="loading"
                                type="submit"
                                theme="primary"
                                class="w-full"
                            >
                                {{ __("Search") }}
                            </global-button>
                        </div>
                    </form>
                </div>

                <div class="overflow-hidden">
                    <global-table>
                        <template #head>
                            <th scope="col" class="py-4 px-5 whitespace-nowrap">{{ __('Name') }}</th>
                            <th scope="col" class="py-4 px-5 whitespace-nowrap">{{ __('Role') }}</th>
                            <th scope="col" class="py-4 px-5 whitespace-nowrap">{{ __('Leave Type') }}</th>
                            <th scope="col" class="py-4 px-5 whitespace-nowrap">{{ __('Date') }}</th>
                            <th scope="col" class="py-4 px-5 whitespace-nowrap">{{ __('Status') }}</th>
                            <th scope="col" class="py-4 px-5 whitespace-nowrap">{{ __('Action') }}</th>
                        </template>
                        <template #body>
                            <template v-if="leaves.data.length > 0">
                                <template v-for="leave in leaves.data" :key="leave.id">
                                    <tr class="border-t dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td scope="row"
                                            class="flex items-center py-4 px-5 text-gray-900 whitespace-nowrap dark:text-white">
                                            <td-user-show :image="leave.user.profile_photo_url" :name="leave.user.name"
                                                :email="leave.user.email" />
                                        </td>
                                        <td class="py-4 px-5">
                                            <div class="inline-block mb-2 bg-blue-100 dark:bg-gray-700 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:text-gray-400">
                                                {{ leave.user.role }}
                                            </div>
                                            <div v-if="leave.user.department && leave.user.role == 'Teacher'">
                                                {{ leave.user.department.name }}
                                            </div>
                                        </td>
                                        <td class="py-4 px-5">
                                            <div class="inline-block mb-2 bg-blue-100 dark:bg-gray-700 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:text-gray-400">
                                                {{ leave.type?.name }}
                                            </div>
                                        </td>
                                        <td class="py-4 px-5 whitespace-nowrap">
                                            <div class="mb-2">{{ leave.days }}</div>
                                            <div>{{ formatTime(leave.start,'') }} - {{ formatTime(leave.end,'') }}</div>
                                        </td>
                                        <td class="py-4 px-5">
                                            <span :class="leave.status == 'pending'
                                                ? 'bg-purple-100 text-purple-800 dark:bg-purple-200 dark:text-purple-900 dark:opacity-60'
                                                : '' || leave.status == 'accepted'
                                                    ? 'bg-green-100 text-green-800 dark:bg-green-200 dark:text-green-900 dark:opacity-60'
                                                    : '' || leave.status == 'rejected'
                                                        ? 'bg-red-100 text-red-800 dark:bg-red-200 dark:text-red-900 dark:opacity-60'
                                                        : ''"
                                                class="capitalize text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">
                                                {{ leave.status }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-5">
                                            <div class="flex gap-1 items-center">
                                                <template v-if="leave.status == 'pending'">
                                                    <button type="button" @click="statusChange(leave.id, 'accepted')"
                                                        class="group relative" :id="'testAccept' + leave.id">
                                                        <CheckIcon class="text-green-500 w-6 h-6" />
                                                        <tool-tip :text="__('Accept')" />
                                                    </button>
                                                    <button type="button" @click="rejectStatus(leave.id)"
                                                        class="group relative" :id="'testReject' + leave.id">
                                                        <XMarkIcon class="text-red-500 w-6 h-6" />
                                                        <tool-tip :text="__('Reject')" />
                                                    </button>
                                                    <button @click="edit(leave)" type="button" class="group relative" :id="'testEdit' + leave.id">
                                                        <PencilSquareIcon
                                                            class="text-blue-400 hover:text-blue-300 w-6 h-6" />
                                                        <tool-tip :text="__('Edit')" />
                                                    </button>
                                                </template>
                                                <button @click="show(leave)" type="button" class="group relative" :id="'testShow' + leave.id">
                                                    <eye-icon class="w-6 h-6 text-pink-400 hover:text-pink-300" />
                                                    <tool-tip :text="__('View')" />
                                                </button>
                                                <button type="button" @click="destroy(leave.id)" class="group relative" :id="'testDelete' + leave.id">
                                                    <trash-icon class="w-6 h-6 text-red-400 hover:text-red-300" />
                                                    <tool-tip :text="__('Delete')" />
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </template>
                            <template v-else>
                                <NothingFound asShow="tr" cssClass="text-center" />
                            </template>
                        </template>
                    </global-table>
                    <div class="flex justify-center">
                        <pagination class="mt-6" :links="leaves.links" />
                    </div>
                </div>
            </div>
        </div>
        <!-- calendar -->
        <div class="bg-white rounded-lg overflow-x-auto dark:bg-gray-800">
            <full-calendar :data="leaves.data" />
        </div>
        <edit-form ref="editModal" :users="users" :leave="leave" />
        <!-- show Modal  -->
        <a-modal v-model:visible="visible" :title="show_leave && show_leave.user.name" @ok="hide">
            <template #footer>
                <a-button key="back" @click="hide">{{ __('Close') }}</a-button>
            </template>
            <show-modal :leave="show_leave" />
        </a-modal>
        <!-- reject cause modal  -->
        <reject-modal :show="causeModal" @close="closeModal">
            <template #title>
                {{ __('Insert Reject Cause') }}
            </template>

            <template #content>
                <!-- Content -->
                <div class="mb-1">
                    <global-label for="description" value="Cause" :required="true" />
                    <global-textarea :placeholder="__('Description')" id="name" :error="$page.props.errors.cause"
                        v-model="reject.cause_description"
                        class="mt-1 block w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400" />
                    <input-error :error="$page.props.errors.cause" />
                </div>
            </template>

            <template #footer>
                <global-button :loading="false" type="button" @click="closeModal()" class="mr-2" theme="outline-danger">
                    {{ __('Cancel') }}
                </global-button>
                <global-button :loading="reject.processing" type="button" @click="statusChange(reject.id, 'rejected')">
                    {{ __('Save') }}
                </global-button>
            </template>
        </reject-modal>
    </AppLayout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import Pagination from "@/Shared/Admin/Pagination.vue";
import { MagnifyingGlassIcon, TrashIcon, PencilSquareIcon, EyeIcon, CheckIcon, XMarkIcon } from '@heroicons/vue/24/outline';
import EditForm from "./Form.vue";
import ToolTip from '@/Shared/ToolTip.vue';
import ShowModal from './Show.vue';
import RejectModal from '@/Shared/Modal.vue';
import FullCalendar from '@/Shared/Components/FullCalendar.vue';
import TdUserShow from "@/Shared/TdUserShow.vue";
import { useForm } from "@inertiajs/inertia-vue3";
import NothingFound from "@/Shared/NothingFound.vue";
import { filterOption } from "ant-design-vue/lib/vc-mentions/src/util";

export default {
    props: {
        leaves: Object,
        filter_data: Object,
        users: Object,
        leave_types: Object,
    },
    data() {
        return {
            loading: false,
            options: [],
            causeModal: false,
            filter: {
                keyword: this.filter_data.keyword ?? "",
                start_date: this.filter_data.start_date ?? null,
                end_date: this.filter_data.end_date ?? null,
                status: this.filter_data.status ?? null,
                role: this.filter_data.role ?? null,
                leave_type: this.filter_data.leave_type ?? null,

            },
            searchQuery: this.filter_data.keyword ?? "", // Sinkronisasi keyword pencarian
            leave: "",
            visible: false,
            show_leave: '',
            reject: useForm({
                id: '',
                cause_description: ''
            }),
        };
    },
    created() {
        this.options.push({
            value: "",
            label: "All",
        });
        for (const [key, value] of Object.entries(this.leave_types)) {
            this.options.push({
                value: value.slug,
                label: value.name,
            });
        }
    },

    components: {
        AppLayout,
        Pagination,
        PencilSquareIcon,
        TrashIcon,
        RejectModal,
        MagnifyingGlassIcon,
        EditForm,
        EyeIcon,
        CheckIcon,
        XMarkIcon,
        TdUserShow,
        ToolTip,
        ShowModal,
        FullCalendar,
        NothingFound
    },
    watch: {
        searchQuery(newQuery) {
            this.filter.keyword = newQuery; // Update filter keyword dengan nilai baru dari searchQuery
            this.filterData();
        },
    },
    methods: {
        filterData() {
            this.loading = true;
            this.$inertia.get(this.route("manage-leave.index"), this.filter, {
                preserveScroll: true,
                onFinish: visit => { this.loading = false },
            });
        },
        filterOption(input, option) {
            return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
        },
        statusChange(id, status) {
            this.loading = true;
            this.$inertia.post(
                this.route("leave.status.change", id), {
                status: status,
                cause: this.reject.cause_description,
            }, {
                preserveScroll: true,
                onSuccess: page => {
                    this.closeModal();
                },
                onFinish: visit => { this.loading = false },
            });
        },
        rejectStatus(id) {
            this.reject.id = id;
            this.causeModal = true;
        },
        closeModal() {
            this.reject.cause_description = '';
            this.reject.id = '';
            this.causeModal = false;
        },
        destroy(arg) {
            if (confirm("Are you sure ?")) {
                this.$inertia.delete(this.route("manage-leave.destroy", arg), {
                    preserveScroll: true,
                });
            } else {
                return false;
            }
        },
        edit(arg) {
            this.leave = arg;
            this.$refs.editModal.showModal();
        },
        show(arg) {
            this.show_leave = arg;
            this.visible = true;
        },
        hide() {
            this.visible = false
        },
    },
};
</script>


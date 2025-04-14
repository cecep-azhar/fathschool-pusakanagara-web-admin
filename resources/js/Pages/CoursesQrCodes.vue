<template>
  <AppLayout :title="__('Class QR Codes')">
    <template #header>
      {{ __('Class QR Codes') }}
    </template>
    <Breadcrumb>
      <BreadcrumbLink :title="__('All Courses')" :href="route('courses.qr-codes')" />
    </Breadcrumb>
    <div>
      <page-header class="flex-col sm:flex-row">
        {{ __('All Courses') }}
        <template #content>
          <div class="flex flex-col sm:flex-row gap-4">
            <!-- Tambahkan tombol-tombol sesuai kebutuhan -->
          </div>
        </template>
      </page-header>
      <div class="mb-4">
        <!-- Form pencarian bisa ditambahkan di sini jika diperlukan -->
      </div>
      <global-table>
        <template #head>
          <th class="py-4 px-5">{{ __('Unique Id') }}</th>
          <th class="py-4 px-5">{{ __('Name') }}</th>
          <th class="py-4 px-5">{{ __('QR Code') }}</th>
          <th class="py-4 px-5">{{ __('Action') }}</th>
        </template>
        <template #body>
          <template v-if="courses.length > 0">
            <template v-for="course in courses" :key="course.id">
              <tr class="border-t dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td class="py-4 px-5 text-gray-900 dark:text-white">
                  {{ course.qr_code_id }}
                </td>
                <td class="py-4 px-5 text-gray-900 dark:text-white">
                  {{ course.name }}
                </td>
                <td class="py-4 px-5">
                  <img :src="course.qr_code_url" alt="QR Code">
                </td>
                <td class="py-4 px-5">
                  <button @click="printQrCode(course.id)" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 disabled:opacity-25 transition">
                    Print QR Code
                  </button>
                </td>
              </tr>
            </template>
          </template>
          <template v-else>
            <tr>
              <td colspan="3" class="text-center p-4">
                <NothingFound asShow="div" />
              </td>
            </tr>
          </template>
        </template>
      </global-table>
      <div class="flex justify-center">
        <!-- Pagination bisa ditambahkan di sini jika diperlukan -->
      </div>
    </div>
  </AppLayout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import Breadcrumb from "@/Shared/Breadcrumb.vue";
import BreadcrumbLink from "@/Shared/BreadcrumbLink.vue";
import NothingFound from "@/Shared/NothingFound.vue";
import { ref } from 'vue';

export default {
  components: {
    AppLayout,
    Breadcrumb,
    BreadcrumbLink,
    NothingFound,
  },
  props: {
    courses: {
      type: Array,
      required: true,
    },
  },
  methods: {
    printQrCode(courseId) {
      const printUrl = `/courses/${courseId}/print-qr-code`;
      window.open(printUrl, '_blank');
    },
  },
  setup(props) {
    const courses = ref(props.courses);

    return {
      courses,
    };
  },
};
</script>

<style scoped>
.course {
  margin-bottom: 20px;
}
</style>

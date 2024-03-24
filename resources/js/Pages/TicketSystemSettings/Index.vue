<template>
  <div>
    <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />
    <div class="flex items-center justify-end mb-6"></div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">{{ $t("Users") }}</th>
          <!-- <th class="pb-4 pt-6 px-6">
                        {{ $t("Interval Setting Type") }}
                    </th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Managers") }}</th>
                    <th class="pb-4 pt-6 px-6 text-center">
                        {{ $t("Action") }}
                    </th> -->
        </tr>
        <tr
          v-for="user in filteredUsers"
          :key="user.id"
          class="hover:bg-gray-100 focus-within:bg-gray-100"
        >
          <td class="border-t">
            <a
              href="javascript:void(0)"
              class="flex items-center px-6 py-4 focus:text-indigo-500"
            >
              {{ user.first_name + " " + user.last_name }}
            </a>
          </td>
        </tr>
        <tr v-if="intervalSettings?.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">
            {{ $t("No User in global notification list found") }}.
          </td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
import Pagination from "laravel-vue-pagination";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";

export default {
  computed: {
    ...mapGetters("globalNotification", ["globalNotification"]),
    ...mapGetters("auth", {
      users: "users",
    }),

    filteredUsers() {
      return this.users.filter((user) =>
        this.globalNotification.includes(user.id)
      );
    },
  },
  components: {
    Pagination,

    PageHeader,
  },
  props: {
    filters: Object,
    can: Object,
  },
  data() {
    return {
      breadcrumbItems: [
        {
          text: "Admin portal",
          to: "/dashboard",
        },
        {
          text: "Ticket System Settings",
          active: true,
        },
      ],
      optionalItems: {
        isBtn2Show: true,
        btn2Text: this.$t("Create Global notification List"),
        btn2Path: "/ticket-system-settings/create",
      },
    };
  },
  mounted() {
    this.refresh();
  },
  methods: {
    async refresh() {
      await this.$store.dispatch("globalNotification/list");
      await this.$store.dispatch("auth/list", {
        limit_start: 0,
        limit_count: 25,
        type: "employee",
      });
    },
  },
};
</script>

<style scoped>
:deep(.page-item.active .page-link) {
  background-color: #2957a4;
  border-color: #2957a4;
}
:deep(.page-link) {
  color: #2957a4;
}
</style>

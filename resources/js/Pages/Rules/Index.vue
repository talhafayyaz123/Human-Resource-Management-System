<template>
  <div>
    <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />
    <div class="flex items-center justify-end mb-6"></div>
    <div class="table-responsive">
      <table class="w-full doc-table">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">{{ $t("Rule Name") }}</th>
          <th class="pb-4 pt-6 px-6">{{ $t("Product") }}</th>
          <th class="pb-4 pt-6 px-6">{{ $t("Deny If") }}</th>
          <th class="pb-4 pt-6 px-6">{{ $t("Reset Pattern") }}</th>
          <th class="pb-4 pt-6 px-6">{{ $t("Next Reset") }}</th>
          <th class="pb-4 pt-6 px-6">{{ $t("Reset Type") }}</th>
          <th class="pb-4 pt-6 px-6">{{ $t("Reset Value") }}</th>
          <th class="pb-4 pt-6 px-6">{{ $t("Allow Overusage") }}</th>
          <th class="pb-4 pt-6 px-6">{{ $t("Status") }}</th>
          <th class="pb-4 pt-6 px-6 text-center">
            {{ $t("Actions") }}
          </th>
        </tr>
        <tr
          v-for="rule in rules ?? []"
          :key="rule.id"
          class="hover:bg-gray-100 focus-within:bg-gray-100"
          @contextmenu.stop.prevent="
              (e) => {
                  e.preventDefault();
                  let route = $router.resolve(`/rules/${rule.id}/edit`);
                  window.open(route.href, '_blank');
              }
          "
        >
          <td class="border-t">
            <a
              href="javascript:void(0)"
              class="flex items-center px-6 py-4 focus:text-indigo-500"
            >
              {{ rule.rule_name }}
            </a>
          </td>
          <td class="border-t">
            <a
              href="javascript:void(0)"
              class="flex items-center px-6 py-4 focus:text-indigo-500"
            >
              {{ rule.product }}
            </a>
          </td>
          <td class="border-t">
            <a
              href="javascript:void(0)"
              class="flex items-center px-6 py-4 focus:text-indigo-500"
            >
              {{
                (eventNames.find((eventName) => eventName.id == rule.event_name)
                  ?.name ?? "") +
                " " +
                rule.deny_if_op +
                " " +
                rule.deny_if_value
              }}
            </a>
          </td>
          <td class="border-t">
            <a
              href="javascript:void(0)"
              class="flex items-center px-6 py-4 focus:text-indigo-500"
            >
              {{ rule.reset_pattern }}
            </a>
          </td>
          <td class="border-t">
            <a
              href="javascript:void(0)"
              class="flex items-center px-6 py-4 focus:text-indigo-500"
            >
              {{ rule.next_reset }}
            </a>
          </td>
          <td class="border-t">
            <a
              href="javascript:void(0)"
              class="flex items-center px-6 py-4 focus:text-indigo-500"
            >
              {{ rule.reset_type }}
            </a>
          </td>
          <td class="border-t">
            <a
              href="javascript:void(0)"
              class="flex items-center px-6 py-4 focus:text-indigo-500"
            >
              {{ rule.reset_value }}
            </a>
          </td>
          <td class="border-t">
            <a
              href="javascript:void(0)"
              class="flex items-center px-6 py-4 focus:text-indigo-500"
            >
              {{ rule.allow_overusage }}
            </a>
          </td>
          <td class="border-t">
            <a
              href="javascript:void(0)"
              class="flex items-center px-6 py-4 focus:text-indigo-500"
            >
              {{ rule.status == 1 ? $t("Active") : $t("Inactive") }}
            </a>
          </td>
          <td class="border-t">
            <div class="flex items-center gap-2">
              <router-link
                v-if="$can(`${$route.meta.permission}.edit`)"
                :to="`/rules/${rule.id}/edit`"
              >
                <font-awesome-icon icon="fa-regular fa-pen-to-square"/>
              </router-link>
              <button
                v-if="$can(`${$route.meta.permission}.delete`)"
                @click="destroy(rule.id)"
              >
                <font-awesome-icon icon="fa-regular fa-trash-can"/>
              </button>
            </div>
          </td>
        </tr>
        <tr v-if="(rules?.length ?? 0) === 0">
          <td class="px-6 py-4 border-t text-center" colspan="4">
            {{ $t("No Rules found") }}.
          </td>
        </tr>
      </table>
    </div>
    <div style="margin-top: 3rem" class="flex justify-center">
      <pagination
        :limit="10"
        align="center"
        :count="count"
        :perPage="perPage"
        :start="start"
        :length="rules.length"
        :currentPage="currentPage"
        @paginationInfo="refresh(true, $event.start, $event.end)"
      ></pagination>
      <br />
      <br />
    </div>
  </div>
</template>

<script>
import Icon from "../../Components/Icon.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import Pagination from "../../Components/Pagination.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";

export default {
  computed: {
    ...mapGetters("rules", ["rules", "count"]),
  },
  components: {
    Icon,
    Pagination,
    MultiSelectInput,
    SelectInput,
    PageHeader,
  },
  props: {
    filters: Object,
  },
  data() {
    return {
      breadcrumbItems: [
        {
          text: "Admin portal",
          to: "/dashboard",
        },
        {
          text: "Rules",
          active: true,
        },
      ],
      optionalItems: {
        isBtn2Show: true,
        btn2Text: this.$t("Create Rule"),
        btn2Path: "/rules/create",
      },
      eventNames: [],
      page: 1,
      currentPage: 1,
      start: 0,
      perPage: 25,
      window
    };
  },
  async mounted() {
    this.refresh(true, this.start, this.perPage);
    const response = await this.$store.dispatch("eventName/list");
    this.eventNames = response?.data;
  },
  methods: {
    async changePageOrSearch(page = 1) {
      this.page = page;
      await this.$store.dispatch("rules/list", {
        page: this.page,
      });
    },
    async refresh(bypass, start, end) {
      try {
        if (!this.rules.length || bypass) {
          await this.$store.dispatch("rules/list", {
            limit_start: start,
            limit_count: end,
          });
        }
      } catch (e) {}
    },
    async destroy(id) {
      this.$swal({
          title: this.$t("Do you want to delete this record?"),
          text: this.$t("You can't revert your action"),
          type: "warning",
          showCancelButton: true,
          confirmButtonText: this.$t("Yes delete it!"),
          cancelButtonText: this.$t("No"),
          showCloseButton: true,
          showLoaderOnConfirm: true,
      }).then(async (result) => {
          if (result.isConfirmed === true) {
            try {
              await this.$store.dispatch("rules/destroy", {
                id: id,
              });
              this.refresh(true, this.start, this.perPage);
            } catch (e) {}
          }
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

.roles > :only-child {
  overflow: inherit !important;
  text-overflow: clip !important;
  white-space: normal !important;
  word-break: break-all !important;
}
</style>

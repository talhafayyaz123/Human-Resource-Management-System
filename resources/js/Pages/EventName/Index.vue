<template>
  <div>
    <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

    <div class="flex items-center justify-end mb-6">
      <!-- <search-filter
                :isFilter="true"
                v-model="form.search"
                class="mr-4 w-full max-w-md"
                @reset="reset"
            >
                <MultiSelectInput
                    @update:model-value="refresh(true, start, perPage)"
                    v-model="form.companyId"
                    :options="companies.data"
                    :multiple="false"
                    textLabel="Company"
                    label="companyName"
                    trackBy="id"
                    moduleName="companies"
                />
            </search-filter> -->
    </div>
    <div class="table-responsive">
      <table class="w-full doc-table">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">{{ $t("Name") }}</th>
          <th class="pb-4 pt-6 px-6">{{ $t("Company") }}</th>
          <!-- <th class="pb-4 pt-6 px-6">{{ $t("Tenant") }}</th> -->
          <th class="pb-4 pt-6 px-6">{{ $t("Creation Date") }}</th>
          <th class="pb-4 pt-6 px-6">{{ $t("Action") }}</th>
        </tr>
        <tr
          v-for="eventName in eventNames ?? []"
          :key="eventName.id"
          class="hover:bg-gray-100 focus-within:bg-gray-100"
          @contextmenu.stop.prevent="
              (e) => {
                  e.preventDefault();
                  let route = $router.resolve(`/event-name/${eventName.id}/edit`);
                  window.open(route.href, '_blank');
              }
          "
        >
          <td
            class="border-t"
            @click="
              $router.push(
                `/event-name/${eventName.id}/edit?companyId=${
                  eventName.company_id
                }&tenantId=${eventName.tenant_id ?? ''}`
              )
            "
          >
            <a
              href="javascript:void(0)"
              class="flex items-center px-6 py-4 focus:text-indigo-500"
            >
              {{ eventName.name }}
            </a>
          </td>
          <td
            class="border-t"
            @click="
              $router.push(
                `/event-name/${eventName.id}/edit?companyId=${
                  eventName.company_id
                }&tenantId=${eventName.tenant_id ?? ''}`
              )
            "
          >
            <a
              href="javascript:void(0)"
              class="flex items-center px-6 py-4 focus:text-indigo-500"
            >
              {{ companyNames[eventName.company_id] ?? "" }}
            </a>
          </td>
          <!-- <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ companyNames[eventName.tenant_id] ?? "" }}
                        </a>
                    </td> -->
          <td
            class="border-t"
            @click="
              $router.push(
                `/event-name/${eventName.id}/edit?companyId=${
                  eventName.company_id
                }&tenantId=${eventName.tenant_id ?? ''}`
              )
            "
          >
            <a
              href="javascript:void(0)"
              class="flex items-center px-6 py-4 focus:text-indigo-500"
            >
              {{
                $dateFormatter(
                  new Date((eventName?.creation_time ?? 0) * 1000)
                    ?.toISOString()
                    ?.slice(0, 10),
                  globalLanguage
                )
              }}
            </a>
          </td>

          <td class="border-t">
            <!-- <router-link
                            class="mx-1"
                            @click.stop=""
                            v-if="$can(`${$route.meta.permission}.show`)"
                            :to="`/event-name/${eventName.id}`"
                        >
                            <font-awesome-icon icon="fa-solid fa-eye"/>
                        </router-link> -->

           <div class="flex items-center gap-2">
              <router-link
                :to="`/event-name/${eventName.id}/edit?companyId=${
                  eventName.company_id
                }&tenantId=${eventName.tenant_id ?? ''}`"
              >
                <font-awesome-icon icon="fa-regular fa-pen-to-square"/>
              </router-link>
              <button
                v-if="$can(`${$route.meta.permission}.delete`)"
                class="flex items-center"
                tabindex="-1"
                method="delete"
                as="button"
                @click="destroy(eventName.id)"
              >
                <icon name="trash" class="mr-2 w-4 h-4" />
              </button>
            </div>
          </td>
        </tr>
        <tr v-if="(eventNames.length ?? 0) === 0">
          <td class="px-6 py-4 border-t text-center" colspan="4">
            {{ $t("No Event Names found") }}.
          </td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
import Icon from "../../Components/Icon.vue";

import SearchFilter from "../../Components/SearchFilter.vue";

import PageHeader from "@/Components/Layouts/Page-header.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";

export default {
  computed: {
    ...mapGetters("companies", ["companies"]),
  },
  components: {
    Icon,
    SearchFilter,
    MultiSelectInput,
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
          text: "Event Name",
          active: true,
        },
      ],
      optionalItems: {
        isBtn2Show: true,
        btn2Text: this.$t("Create Event Name"),
        btn2Path: "/event-name/create",
      },
      eventNames: [],
      companyNames: {},
      currentPage: 1,
      start: 0,
      perPage: 25,
      form: {
        search: "",
        companyId: "",
      },
      window
    };
  },
  watch: {
    eventNames: {
      handler: async function (val) {
        for (let i = 0; i < val?.length ?? 0; i++) {
          if (this.companyNames[val[i].company_id]) {
            continue;
          }
          let company = this.companies?.data?.find(
            (company) => company.id == val[i].company_id
          );
          if (company) {
            this.companyNames[val[i].company_id] = company.companyName;
          } else if (val[i].company_id) {
            try {
              const response = await this.$store.dispatch(
                "companies/show",
                val.company_id
              );
              this.companyNames[val[i].company_id] =
                response?.data?.modelData?.companyName ?? "";
            } catch (e) {
              console.log(e);
            }
          }
        }
      },
      deep: true,
    },
  },
  mounted() {
    this.refresh(false, this.start, this.perPage);
    if (!this.companies?.data?.length)
      this.$store.dispatch("companies/list", {
        page: 1,
        perPage: 25,
      });
  },
  methods: {
    async refresh(bypass, start, end) {
      const response = await this.$store.dispatch("eventName/list");
      this.eventNames = response?.data;
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
                const obj = {
                  id: id,
                };
                await this.$store.dispatch("eventName/destroy", obj);
                this.refresh(true, this.start, this.perPage);
              } catch (e) {}
            }
        });
    },
    reset() {
      this.form = this.mapValues(this.form, () => null);
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

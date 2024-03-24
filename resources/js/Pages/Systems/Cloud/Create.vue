<template>
  <div>
    <PageHeader :items="breadcrumbItems" />
    <div class="tab-header">
      <ul>
          <li class="nav-item">
            <a class="nav-link" :class="activeClasses">
              {{ $t("Tenants") }}
            </a>
          </li>
      </ul>
    </div>
    <form @submit.prevent="store">
      <div>
        <tenants-tab
          @tenantValueChanged="tenantValueChanged"
          :customers="companies.data"
          :distributedFileSystemOptions="distributedFileSystemOptions"
          :databaseCloudOptions="databaseCloudOptions"
          :errors="errors"
          :cloudSystems="systemCloud.data"
        ></tenants-tab>
      </div>
      <div class="flex items-center justify-end mt-4">
        <router-link to="/systems/cloud" class="primary-btn mr-3">
            <span class="mr-1">
                <CustomIcon name="cancelIcon" />
            </span>
            <span>{{ $t("Cancel") }}</span>
        </router-link>
        <loading-button :loading="isLoading" class="secondary-btn">
            <span class="mr-1">
                <CustomIcon name="saveIcon" />
            </span>
            {{ $t("Save and Proceed") }}
        </loading-button>
      </div>
      <!-- <div class="mt-4 max-w-3xl flex flex-row-reverse">
        <loading-button :loading="isLoading" class="btn-green">{{
          $t("Create System")
        }}</loading-button>
      </div> -->
    </form>
  </div>
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import SelectLinkInput from "@/Components/SelectLinkInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";
import ServerTabs from "../Components/ServerTabs.vue";
import TenantsTab from "../Components/TenantsTab.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
  components: {
    MultiSelectInput,
    LoadingButton,
    SelectInput,
    TextInput,
    SelectLinkInput,
    ServerTabs,
    TenantsTab,
    PageHeader,
  },
  props: {
    products: Object,
  },
  computed: {
    ...mapGetters(["errors", "isLoading"]),
    ...mapGetters("systemCloud", ["systemCloud"]),
    ...mapGetters("companies", ["companies"]),
  },
  data() {
    return {
      breadcrumbItems: [
        {
          text: "Admin portal",
          to: "/dashboard",
        },
        {
                    text: "Master Data",
                    to: "/systems/cloud",
                },
        {
          text: this.$t("Systems"),
          to: "/systems/cloud",
        },
        {
          text: "Cloud",
          to: "/systems/cloud",
        },
        {
          text: this.$t("Create"),
          active: true,
        },
      ],
      databaseCloudOptions: [],
      distributedFileSystemOptions: [],
      form: {
        type: "cloud",
        tenant: {},
      },
      activeClasses: "active",
      inactiveClasses: "inactive",
    };
  },
  async mounted() {
    try {
      const filesystemResponse = await this.$store.dispatch(
        "distributedFilesystem/list"
      );
      this.distributedFileSystemOptions = filesystemResponse?.data?.data ?? [];

      const cloudResponse = await this.$store.dispatch("databaseCloud/list");
      this.databaseCloudOptions = cloudResponse?.data?.data ?? [];

      const response = await this.$store.dispatch("companies/list", {
        perPage: 25,
        page: 1,
        isSystem: true,
      });
      await this.$store.dispatch("systemCloud/list");
    } catch (e) {}
  },
  methods: {
    async store() {
      try {
        this.$store.commit("isLoading", true);
        await this.$store.dispatch("systems/create", {
          ...this.form,
        });
        await this.$store.dispatch("systemCloud/list", {
          type: "cloud",
          withTenant: true,
        });
        this.$router.push("/systems/cloud");
      } catch (e) {}
    },
    tenantValueChanged(val) {
      this.form.tenant = val;
    },
  },
};
</script>

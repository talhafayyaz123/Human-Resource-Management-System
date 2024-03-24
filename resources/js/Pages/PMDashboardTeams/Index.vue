<template>
  <div>
    <PageHeader :items="breadcrumbItems" />

    <form @submit.prevent="store">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Pm Dashboard Teams</h3>
        </div>
        <div class="card-body">
          <div class="grid items-center grid-cols-2 gap-6">
            <div class="w-full">
                <div class="form-group">
                  <MultiSelectInput
                    v-model="form.teams"
                    :key="form.teams"
                    :options="teams?.data"
                    :multiple="true"
                    :textLabel="$t('Teams')"
                    label="name"
                    trackBy="id"
                    moduleName="teams"
                    :error="errors.teams"
                  />
                </div>
            </div>
        </div>
        </div>
      </div>

      <div class="flex items-center justify-end mt-4">
        <loading-button
        v-if="$can(`${$route.meta.permission}.save`)"
              :loading="isLoading"
              class="secondary-btn"
              >
              <span class="mr-1">
            <CustomIcon name="saveIcon"/>
          </span>
              {{ $t("Save and Proceed") }}
        </loading-button
          >
      </div>
    </form>
  </div>
</template>

<script>
import LoadingButton from "../../Components/LoadingButton.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";

export default {
  computed: {
    ...mapGetters(["errors", "isLoading"]),
    ...mapGetters("userTeams", ["teams"]),
  },
  components: {
    LoadingButton,
    MultiSelectInput,
    PageHeader,
  },
  data() {
    return {
      breadcrumbItems: [
        {
          text: "Admin portal",
          to: "/dashboard",
        },
        {
          text: "PM Dashboard Teams",
          active: true,
        },
      ],
      form: {
        teams: [],
      },
    };
  },
  async mounted() {
    try {
      this.$store.commit("showLoader", true);
      // fetch the user teams listing
      if (!this.teams?.data?.length)
        await this.$store.dispatch("userTeams/list");
      // fetch the consulting teams listing
      const response = await this.$store.dispatch(
        "userTeams/pmDashboardTeamsList"
      );
      // set the consulting teams
      this.form.teams = response?.data?.data ?? [];
    } catch (e) {
      console.log(e);
    }
    finally {
      this.$store.commit("showLoader", false);
    }
  },
  methods: {
    /**
     * hits the consulting teams save API
     */
    async store() {
      try {
        this.$store.commit("isLoading", true);
        await this.$store.dispatch("userTeams/pmDashboardTeamsSave", {
          teams: this.form.teams.map((team) => {
            return {
              id: team.id,
            };
          }),
        });
      } catch (e) {}
    },
  },
};
</script>

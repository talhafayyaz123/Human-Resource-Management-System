<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Update Affected Group Details") }}
                    </h3>
                </div>
                <div class="card-body">
                  <div class="grid items-center grid-cols-2 gap-6">
                    <div class="w-full">
                        <div class="form-group">
                          <text-input
                              v-model="form.name"
                              :error="errors.name"
                              :label="$t('Name')"
                              :required="true"
                          />
                          <!-- :error="form.errors.name" -->
                        </div>
                    </div>
                  </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link :to="`/affected-groups?page=${$route.query.page}`" class="primary-btn mr-3">
                    <span class="mr-1">
                        <CustomIcon name="cancelIcon" />
                    </span>
                    <span>{{ $t("Cancel") }}</span>
                </router-link>
                <loading-button
                    v-if="$can(`${$route.meta.permission}.edit`)"
                    :loading="isLoading"
                    class="secondary-btn"
                >
                    <span class="mr-1">
                        <CustomIcon name="saveIcon" />
                    </span>
                    {{ $t("Save and Proceed") }}
                </loading-button>
            </div>
        </form>
    </div>
</template>

<script>
import TextInput from "../../Components/TextInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";

export default {
  computed: {
    ...mapGetters(["errors", "isLoading"]),
  },
  components: {
    LoadingButton,
    TextInput,
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
          text: this.$t("Affected Groups"),
          to: `/affected-groups?page=${this.$route.query.page}`,
        },
        {
          text: this.$t("Update"),
          active: true,
        },
      ],
      form: {
        name: "",
      },
    };
  },
  mounted() {
    this.refresh();
  },
  methods: {
    async refresh() {
      try {
        this.$store.commit("showLoader", true);
        const response = await this.$store.dispatch(
          "affectedGroups/show",
          this.$route.params.id
        );
        this.form = response?.data?.data ?? { name: "" };
      } catch (e) {}
      finally{
        this.$store.commit("showLoader", false);
      }
    },
    async update() {
      this.$store.commit("isLoading", true);
      await this.$store.dispatch("affectedGroups/update", {
        id: this.$route.params.id,
        data: { ...this.form },
      });
      await this.$store.dispatch("affectedGroups/list");
      this.$router.push("/affected-groups");
    },
    async destroy() {
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
            await this.$store.dispatch(
              "affectedGroups/destroy",
              this.$route.params.id
            );
            await this.$store.dispatch("affectedGroups/list");
  
            this.$router.push("/affected-groups");
          } catch (e) {}
        }
      });
    },
  },
};
</script>

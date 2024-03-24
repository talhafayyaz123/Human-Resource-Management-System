<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <trashed-message
            v-if="modelData?.deleted_at"
            class="mb-6"
            @restore="restore"
        >
            {{ $t("This report category has been deleted") }}.
        </trashed-message>

        <form @submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Update Report Category Details") }}
                    </h3>
                </div>
                <div class="card-body">
                  <div class="grid items-center grid-cols-2 gap-6">
                    <div class="w-full">
                        <div class="form-group">
                          <text-input
                            v-model="form.name"
                            :error="errors.name"
                            :required="true"
                            :label="$t('Name')"
                          />
                        </div>
                    </div>
                  </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link :to="`/report-categories?page=${$route.query.page}`" class="primary-btn mr-3">
                    <span class="mr-1">
                        <CustomIcon name="cancelIcon" />
                    </span>
                    <span>{{ $t("Cancel") }}</span>
                </router-link>
                <button
                    v-if="!modelData.deleted_at"
                    class="btn-red mx-3 py-2"
                    tabindex="-1"
                    type="button"
                    @click="destroy"
                >
                    {{ $t("Delete report category") }}
                </button>
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
import TrashedMessage from "../../Components/TrashedMessage.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";

export default {
  mounted() {
    this.refresh();
  },
  computed: {
    ...mapGetters(["errors", "isLoading"]),
  },
  components: {
    LoadingButton,
    TextInput,
    TrashedMessage,
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
          text: "Report Categories",
          to: `/report-categories?page=${this.$route.query.page}`,
        },
        {
          text: this.$t("Update"),
          active: true,
        },
      ],
      modelData: {},
      form: {
        name: "",
      },
    };
  },
  methods: {
    async refresh() {
      try {
        this.$store.commit("showLoader", true);
        const response = await this.$store.dispatch(
          "reportCategories/show",
          this.$route.params.id
        );
        this.modelData = response?.data?.modelData ?? {};
        this.form = {
          name: this.modelData.name,
        };
      } catch (e) {}
      finally {
        this.$store.commit("showLoader", false);
      }
    },
    async update() {
      try {
        this.$store.commit("isLoading", true);
        await this.$store.dispatch("reportCategories/update", {
          id: this.$route.params.id,
          data: { ...this.form },
        });
        await this.$store.dispatch("reportCategories/list");
        this.$router.push("/report-categories");
      } catch (e) {}
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
                "reportCategories/destroy",
                this.$route.params.id
              );
              await this.$store.dispatch("reportCategories/list");
              this.$router.push("/report-categories");
            } catch (e) {}
          }
      });
    },
    restore() {
      if (confirm("Are you sure you want to restore this report category?")) {
      }
    },
  },
};
</script>

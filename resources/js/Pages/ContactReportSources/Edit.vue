<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Update Contact Report Source Details") }}
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
                <router-link
                    :to="`/contact-report-source?page=${$route.query.page}`"
                    class="primary-btn mr-3"
                >
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
                    text: this.$t("Contact Report Sources"),
                    to: `/contact-report-source?page=${this.$route.query.page}`,
                },
                {
                    text: this.$t("Create"),
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
                    "contactSource/show",
                    this.$route.params.id
                );
                let contactSource = response?.data?.data ?? {};
                this.form = {
                    name: contactSource.name,
                };
            } catch (e) {}
            finally {
                this.$store.commit("showLoader", false);
            }
        },
        async update() {
            this.$store.commit("isLoading", true);
            await this.$store.dispatch("contactSource/update", {
                id: this.$route.params.id,
                data: { ...this.form },
            });
            await this.$store.dispatch("contactSource/list");
            this.$router.push("/contact-report-source");
        },
    },
};
</script>

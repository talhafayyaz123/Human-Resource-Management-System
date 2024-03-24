<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <form @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Fill Contract Type Details") }}
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link to="/contract-types" class="primary-btn mr-3">
                    <span class="mr-1">
                        <CustomIcon name="cancelIcon" />
                    </span>
                    <span>{{ $t("Cancel") }}</span>
                </router-link>
                <loading-button
                    v-if="$can(`${$route.meta.permission}.create`)"
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
import LoadingButton from "../../Components/LoadingButton.vue";
import TextInput from "../../Components/TextInput.vue";
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
                    text: this.$t("Contract Types"),
                    to: "/contract-types",
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
    methods: {
        // hit the contact type create API and redirect to listing page upon success
        async store() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("contractTypes/create", {
                    ...this.form,
                });
                await this.$store.dispatch("contractTypes/list");
                this.$router.push("/contract-types");
            } catch (e) {}
        },
    },
};
</script>

<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form novalidate @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Fill Invoice Type Details") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="form.name"
                                    :error="errors?.name"
                                    :label="$t('Name')"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link
                    to="/travel-expenses/invoice-types"
                    class="primary-btn mr-3"
                >
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
import TextInput from "@/Components/TextInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";

export default {
    components: {
        LoadingButton,
        TextInput,
        PageHeader,
    },
    remember: "form",
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Invoice Types",
                    to: "/travel-expenses/invoice-types",
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
    computed: {
        ...mapGetters(["errors", "isLoading"]),
    },
    methods: {
        filterOptions(options, selectedValues) {
            return options.filter(
                (option) => !selectedValues.includes(option.value)
            );
        },
        async store() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("travelExpenseInvoiceType/create", {
                    ...this.form,
                });
                await this.$store.dispatch("travelExpenseInvoiceType/list");
                this.$router.push("/travel-expenses/invoice-types");
            } catch (e) {}
        },
    },
};
</script>

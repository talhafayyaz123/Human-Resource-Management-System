<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Fill Checklist Details") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="form.text"
                                    :error="errors.text"
                                    :label="$t('Text')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    v-model="form.process"
                                    :key="form.process"
                                    :error="errors.process"
                                    :required="true"
                                    :label="$t('Process')"
                                >
                                    <option value="name-change">
                                        {{ $t("Name Change") }}
                                    </option>
                                    <option value="bank-account-change">
                                        {{ $t("Bank Account Change") }}
                                    </option>
                                    <option value="change-of-health-insurance-company">
                                        {{ $t("Change Of Health Insurance Company") }}
                                    </option>
                                    <option value="change-of-address">
                                        {{ $t("Change Of Address") }}
                                    </option>
                                    <option value="change-of-tax-class">
                                        {{ $t("Change Of Tax Class") }}
                                    </option>
                                    <option value="change-of-child-allowance">
                                        {{ $t("Change Of Child Allowance") }}
                                    </option>
                                </select-input>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end mt-4">
                <router-link
                    to="/personal-modification-process-checklists"
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
                        <CustomIcon name="updateIcon" />
                    </span>
                    {{ $t("Update") }}
                </loading-button>
            </div>
        </form>
    </div>
</template>

<script>
import LoadingButton from "@/Components/LoadingButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
    },
    components: {
        LoadingButton,
        TextInput,
        SelectInput,
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
                    text: "Personal Modification Process Checklists",
                    to: "/personal-modification-process-checklists",
                },
                {
                    text: this.$t("Update"),
                    active: true,
                },
            ],
            form: {
                text: "",
                process: "",
            },
        };
    },
    async mounted() {
        try {
            const response = await this.$store.dispatch(
                "personalModificationProcessChecklists/show",
                this.$route.params.id
            );
            this.form = {
                text: response?.data?.text ?? "",
                process: response?.data?.process ?? "",
            };
        } catch (e) {
            console.log(e);
        }
    },
    methods: {
        async update() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch(
                    "personalModificationProcessChecklists/update",
                    { id: this.$route.params.id, data: { ...this.form } }
                );
                await this.$store.dispatch(
                    "personalModificationProcessChecklists/list"
                );
                this.$router.push("/personal-modification-process-checklists");
            } catch (e) {}
        },
    },
};
</script>

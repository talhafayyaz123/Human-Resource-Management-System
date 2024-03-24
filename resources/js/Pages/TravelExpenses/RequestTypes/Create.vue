<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form novalidate @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Fill Request Type Details") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6 my-5">
                        <div class="w-full">
                            <div class="flex form-group items-center">
                                <input
                                    v-model="form.customerSpecific"
                                    type="checkbox"
                                    class="border-gray-300 rounded h-5 w-5"
                                />
                                <label class="ml-2">{{ $t("Customer Specific") }}</label>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="flex form-group items-center">
                                <input
                                    v-model="form.projectSpecific"
                                    type="checkbox"
                                    class="border-gray-300 rounded h-5 w-5"
                                />
                                <label class="ml-2">{{
                                    $t("Project Specific")
                                }}</label>
                            </div>
                        </div>
                    </div>
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
                        <div class="w-full">
                            <div class="form-group">
                                <!-- Approval Level 1 -->
                                <select-input
                                    v-model="form.approvalLevel1"
                                    :label="$t('Approval Level 1')"
                                    :error="errors?.approvalLevel1 ?? ''"
                                    :key="form.approvalLevel1"
                                >
                                    <option value="">- Select -</option>
                                    <option
                                        v-for="option in approvalLevel1Options"
                                        :value="option.value"
                                        :key="option.value"
                                    >
                                        {{ option.label }}
                                    </option>
                                </select-input>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <!-- Approval Level 2 -->
                                <select-input
                                    v-if="form.approvalLevel1"
                                    v-model="form.approvalLevel2"
                                    :label="$t('Approval Level 2')"
                                    :error="errors?.approvalLevel2 ?? ''"
                                    :key="form.approvalLevel2"
                                >
                                    <option value="">- Select -</option>
                                    <option
                                        v-for="option in approvalLevel2Options"
                                        :value="option.value"
                                        :key="option.value"
                                    >
                                        {{ option.label }}
                                    </option>
                                </select-input>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <!-- Approval Level 3 -->
                                <select-input
                                    v-if="form.approvalLevel1 && form.approvalLevel2"
                                    v-model="form.approvalLevel3"
                                    :label="$t('Approval Level 3')"
                                    :error="errors?.approvalLevel3 ?? ''"
                                    :key="form.approvalLevel3"
                                >
                                    <option value="">- Select -</option>
                                    <option
                                        v-for="option in approvalLevel3Options"
                                        :value="option.value"
                                        :key="option.value"
                                    >
                                        {{ option.label }}
                                    </option>
                                </select-input>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link
                    to="/travel-expenses/request-types"
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
import SelectInput from "@/Components/SelectInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";

export default {
    components: {
        LoadingButton,
        TextInput,
        SelectInput,
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
                    text: "Request Types",
                    to: "/travel-expenses/request-types",
                },
                {
                    text: this.$t("Create"),
                    active: true,
                },
            ],
            form: {
                name: "",
                customerSpecific: false,
                projectSpecific: false,
            },
            approvalOptions: [
                { value: "team", label: this.$t("Team Lead") },
                { value: "department", label: this.$t("Department head") },
                { value: "project", label: this.$t("Project Manager") },
            ],
        };
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        approvalLevel1Options() {
            return this.approvalOptions;
        },
        approvalLevel2Options() {
            if (this.form.approvalLevel1) {
                return this.filterOptions(
                    this.approvalOptions,
                    this.form.approvalLevel1
                );
            } else {
                return [];
            }
        },
        approvalLevel3Options() {
            if (this.form.approvalLevel1 && this.form.approvalLevel2) {
                return this.filterOptions(this.approvalOptions, [
                    this.form.approvalLevel1,
                    this.form.approvalLevel2,
                ]);
            } else {
                return [];
            }
        },
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
                await this.$store.dispatch("travelExpenseRequestType/create", {
                    ...this.form,
                });
                await this.$store.dispatch("travelExpenseRequestType/list");
                this.$router.push("/travel-expenses/request-types");
            } catch (e) {}
        },
    },
};
</script>

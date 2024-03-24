<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <trashed-message
            v-if="modelData?.deleted_at"
            class="mb-6"
            @restore="restore"
        >
            {{ $t("This record has been deleted") }}.
        </trashed-message>

        <form novalidate @submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Update Request Type Details") }}
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
                                <span class="ml-2">{{
                                    $t("Customer Specific")
                                }}</span>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="flex form-group items-center">
                                <input
                                    v-model="form.projectSpecific"
                                    type="checkbox"
                                    class="border-gray-300 rounded h-5 w-5"
                                />
                                <span class="ml-2">{{
                                    $t("Project Specific")
                                }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
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
                                    <option value="">
                                        - {{ $t("Select") }} -
                                    </option>
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
                                    v-if="
                                        form.approvalLevel1 &&
                                        form.approvalLevel2
                                    "
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
                    :to="`/travel-expenses/request-types?page=${this.$route.query.page}`"
                    class="primary-btn mr-3"
                >
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
        </form>
    </div>
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import TrashedMessage from "@/Components/TrashedMessage.vue";
import SelectInput from "@/Components/SelectInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";

export default {
    async mounted() {
        this.refresh();
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
    components: {
        LoadingButton,
        TextInput,
        TrashedMessage,
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
                    text: "Request Types",
                    to: `/travel-expenses/request-types?page=${this.$route.query.page}`,
                },
                {
                    text: this.$t("Update"),
                    active: true,
                },
            ],
            isLoaded: false,
            modelData: {},
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
    methods: {
        filterOptions(options, selectedValues) {
            return options.filter(
                (option) => !selectedValues.includes(option.value)
            );
        },
        async refresh() {
            try {
                this.$store.commit("showLoader", true);
                const response = await this.$store.dispatch(
                    "travelExpenseRequestType/show",
                    this.$route.params.id
                );
                this.modelData = response?.data?.data ?? {};
                this.form = {
                    name: this.modelData.name,
                    approvalLevel1: this.modelData.approvalLevel1,
                    approvalLevel2: this.modelData.approvalLevel2,
                    approvalLevel3: this.modelData.approvalLevel3,
                    customerSpecific: this.modelData.customerSpecific === 1,
                    projectSpecific: this.modelData.projectSpecific === 1,
                };
            } catch (e) {
            } finally {
                this.isLoaded = true;
                this.$store.commit("showLoader", false);
            }
        },
        async update() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("travelExpenseRequestType/update", {
                    id: this.$route.params.id,
                    data: {
                        ...this.form,
                    },
                });
                await this.$store.dispatch("travelExpenseRequestType/list");
                this.$router.push(
                    `/travel-expenses/request-types?page=${this.$route.query.page}`
                );
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
                            "travelExpenseRequestType/destroy",
                            this.$route.params.id
                        );
                        await this.$store.dispatch(
                            "travelExpenseRequestType/list"
                        );
                        this.$router.push(
                            `/travel-expenses/request-types?page=${this.$route.query.page}`
                        );
                    } catch (e) {}
                }
            });
        },
    },
};
</script>

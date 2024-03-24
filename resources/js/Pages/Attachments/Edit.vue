<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <form @submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Fill Attachment Details") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-3 gap-6">
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
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    :required="true"
                                    :textLabel="'Software'"
                                    :trackBy="'id'"
                                    :label="'name'"
                                    :multiple="false"
                                    v-model="form.softwareId"
                                    :key="form.softwareId"
                                    moduleName="softwares"
                                    :search-param-name="'search_string'"
                                    :options="softwares?.data ?? []"
                                    :error="errors.softwareId"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    :required="true"
                                    :textLabel="'Template'"
                                    :trackBy="'id'"
                                    :label="'name'"
                                    :multiple="false"
                                    v-model="form.template"
                                    :key="form.template"
                                    moduleName="documentService"
                                    :search-param-name="'search'"
                                    :options="documentServices?.data ?? []"
                                    :error="errors.template"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    :required="true"
                                    :textLabel="'Contract Type'"
                                    :trackBy="'id'"
                                    :label="'name'"
                                    :multiple="false"
                                    v-model="form.contractTypeId"
                                    :key="form.contractTypeId"
                                    moduleName="contractTypes"
                                    :search-param-name="'search'"
                                    :options="contractTypes?.data ?? []"
                                    :error="errors.contractTypeId"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.prefix"
                                    :error="errors.prefix"
                                    :label="$t('Prefix')"
                                    :required="true"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.version"
                                    :error="errors.version"
                                    :label="$t('Version')"
                                    :required="true"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.runtimeIn"
                                    :error="errors.runtimeIn"
                                    :label="$t('Runtime (months)')"
                                    :required="true"
                                    :type="`number`"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-if="form.renewalPeriod"
                                    v-model="form.renewalPeriodIn"
                                    :error="errors.renewalPeriodIn"
                                    :label="$t('Renewal Period (months)')"
                                    :required="true"
                                    :type="`number`"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <div class="">
                                    <label class="form-label"
                                        >{{ $t("Contract End") }}:</label
                                    >
                                    <datepicker
                                        :clearable="false"
                                        :enable-time-picker="false"
                                        auto-apply
                                        :close-on-auto-apply="false"
                                        v-model="form.contractEndAt"
                                        :class="
                                            errors.contractEndAt ? 'error' : ''
                                        "
                                    />
                                    <div
                                        v-if="errors?.contractEndAt"
                                        class="form-error"
                                    >
                                        {{ errors.contractEndAt }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.noticePeriodIn"
                                    :error="errors.noticePeriodIn"
                                    :label="$t('Notice Period (months)')"
                                    :required="true"
                                    :type="`number`"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="grid items-center grid-cols-3 gap-6 my-5">
                        <div class="w-full">
                            <div class="form-group flex items-center">
                                <input
                                    v-model="form.renewalPeriod"
                                    :checked="form.renewalPeriod"
                                    class="mr-5"
                                    type="checkbox"
                                    id="renewal-period"
                                />
                                <label for="allow-to-add-slas">{{
                                    $t("Renewal Period")
                                }}</label>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group flex items-center">
                                <input
                                    v-model="form.rightOfTermination"
                                    :checked="form.rightOfTermination"
                                    class="mr-5"
                                    type="checkbox"
                                    id="right-of-termination"
                                />
                                <label for="right-of-termination">{{
                                    $t("Special right of termination")
                                }}</label>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group flex items-center">
                                <input
                                    v-model="form.allowToAddSlas"
                                    :checked="form.allowToAddSlas"
                                    class="mr-5"
                                    type="checkbox"
                                    id="allow-to-add-slas"
                                />
                                <label for="allow-to-add-slas">{{
                                    $t("Allow To Add SLAs")
                                }}</label>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group flex items-center">
                                <input
                                    v-model="form.selectUser"
                                    :checked="form.selectUser"
                                    class="mr-5"
                                    type="checkbox"
                                    id="select-user"
                                />
                                <label for="select-user">{{
                                    $t("Select User")
                                }}</label>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group flex items-center">
                                <input
                                    v-model="form.addProductsToCustomerLicenses"
                                    :checked="
                                        form.addProductsToCustomerLicenses
                                    "
                                    class="mr-5"
                                    type="checkbox"
                                    id="add-products-to-customer-licenses"
                                />
                                <label
                                    for="add-products-to-customer-licenses"
                                    >{{
                                        $t("Add products to customer licenses")
                                    }}</label
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <ContractFields
                ref="contractFields"
                :contractFieldsParent="form.contractFields"
            />

            <div class="flex items-center justify-end mt-4">
                <router-link
                    :to="`/attachments?page=${this.$route.query.page}`"
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
import LoadingButton from "../../Components/LoadingButton.vue";
import TextInput from "../../Components/TextInput.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import ContractFields from "./Components/ContractFields.vue";
import NumberInput from "../../Components/NumberInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("softwares", ["softwares"]),
        ...mapGetters("documentService", ["documentServices"]),
        ...mapGetters("contractTypes", ["contractTypes"]),
    },
    components: {
        LoadingButton,
        TextInput,
        ContractFields,
        MultiSelectInput,
        NumberInput,
        PageHeader,
    },
    async mounted() {
        // fetch the softwares, documentServices and contractTypes if not already fetched
        try {
            this.$store.commit("showLoader", true);
            if (!this.softwares?.data?.length) {
                await this.$store.dispatch("softwares/list");
            }
            if (!this.documentServices?.data?.length) {
                await this.$store.dispatch("documentService/list");
            }
            if (!this.contractTypes?.data?.length) {
                await this.$store.dispatch("contractTypes/list");
            }
        } catch (e) {
            console.log(e);
        }
        // get the attachment details
        try {
            const response = await this.$store.dispatch(
                "attachments/show",
                this.$route.params.id
            );
            // set the attachment form data using data from response
            this.form = {
                name: response?.data?.data?.name,
                softwareId: response?.data?.data?.software ?? "",
                template: "",
                contractTypeId: response?.data?.data?.contractType ?? "",
                prefix: response?.data?.data?.prefix,
                version: response?.data?.data?.version,
                contractFields: response?.data?.data?.contractFields ?? [],
                allowToAddSlas: response?.data?.data?.allowToAddSlas ?? false,
                runtimeIn: response?.data?.data?.runtimeIn ?? 0,
                renewalPeriod: response?.data?.data?.renewalPeriod == 1,
                renewalPeriodIn: response?.data?.data?.renewalPeriodIn ?? 0,
                noticePeriodIn: response?.data?.data?.noticePeriodIn ?? 0,
                rightOfTermination:
                    response?.data?.data?.rightOfTermination == 1,
                contractEndAt: response?.data?.data?.contractEndAt ?? "",
                selectUser: response?.data?.data?.selectUser ?? 0,
                addProductsToCustomerLicenses:
                    response?.data?.data?.addProductsToCustomerLicenses ?? 0,
            };
            // find the template from documentServices listing
            let template = this.documentServices?.data?.find(
                (document) => document.id == response?.data?.data?.template
            );
            // if the template was not found in documentServices listing then fetch it individually using the show API
            if (!template && response?.data?.data?.template) {
                const templateResponse = await this.$store.dispatch(
                    "documentService/show",
                    response?.data?.data?.template
                );
                template = templateResponse?.data ?? "";
            }
            this.form.template = template;
        } catch (e) {
            console.log(e);
        } finally {
            this.$store.commit("showLoader", false);
        }
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: this.$t("Attachments"),
                    to: `/attachments?page=${this.$route.query.page}`,
                },
                {
                    text: this.$t("Update"),
                    active: true,
                },
            ],
            form: {
                name: "",
                softwareId: "",
                template: "",
                contractTypeId: "",
                prefix: "",
                version: "",
                contractFields: [],
                allowToAddSlas: false,
                runtimeIn: 0,
                renewalPeriod: false,
                renewalPeriodIn: 0,
                noticePeriodIn: 0,
                addProductsToCustomerLicenses: 0,
                rightOfTermination: false,
                contractEndAt: "",
            },
        };
    },
    methods: {
        /**
         * hit the attachment update API and redirect to listing page upon success
         */
        async update() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("attachments/update", {
                    id: this.$route.params.id,
                    data: {
                        ...this.form,
                        softwareId: this.form.softwareId?.id,
                        contractTypeId: this.form.contractTypeId?.id,
                        template: this.form.template?.id,
                        contractFields:
                            this.$refs.contractFields.contractFields,
                        renewalPeriod: !!this.form.renewalPeriod ? 1 : 0,
                        rightOfTermination: !!this.form.rightOfTermination
                            ? 1
                            : 0,
                    },
                });
                await this.$store.dispatch("attachments/list");
                this.$router.push(
                    `/attachments?page=${this.$route.query.page}`
                );
            } catch (e) {}
        },
    },
};
</script>

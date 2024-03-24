<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="save">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Save Document Assignment") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <!-- Invoice template -->
                                <MultiSelectInput
                                    :required="true"
                                    v-model="invoiceTemplateId"
                                    :options="documentServices.data"
                                    :key="invoiceTemplateId"
                                    :multiple="false"
                                    :textLabel="$t('Invoice template')"
                                    label="name"
                                    trackBy="id"
                                    :error="errors.invoiceTemplateId"
                                    moduleName="documentService"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <!-- Invoice Correction template -->
                                <MultiSelectInput
                                    :required="true"
                                    v-model="invoiceCorrectionTemplateId"
                                    :options="documentServices.data"
                                    :key="invoiceCorrectionTemplateId"
                                    :multiple="false"
                                    :textLabel="$t('Invoice Correction template')"
                                    label="name"
                                    trackBy="id"
                                    :error="errors.invoiceCorrectionTemplateId"
                                    moduleName="documentService"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <!-- Invoice Storno template -->
                                <MultiSelectInput
                                    :required="true"
                                    v-model="invoiceStornoTemplateId"
                                    :options="documentServices.data"
                                    :key="invoiceStornoTemplateId"
                                    :multiple="false"
                                    :textLabel="$t('Invoice Storno template')"
                                    label="name"
                                    trackBy="id"
                                    :error="errors.invoiceStornoTemplateId"
                                    moduleName="documentService"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    :required="true"
                                    v-model="perfRecordTemplateId"
                                    :options="documentServices.data"
                                    :key="perfRecordTemplateId"
                                    :multiple="false"
                                    :textLabel="$t('Performance record template')"
                                    label="name"
                                    trackBy="id"
                                    :error="errors.perfRecordTemplateId"
                                    moduleName="documentService"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap my-3">
                        <MultiSelectInput
                            v-for="item in invoiceWarningLevels"
                            :required="true"
                            :key="item?.reminderTemplateId"
                            v-model="item.reminderTemplateId"
                            :options="documentServices.data"
                            :multiple="false"
                            :textLabel="item.label"
                            label="name"
                            trackBy="id"
                            :error="errors.orderConfirmationTemplateId"
                            class="pb-8 pr-6 w-full lg:w-1/3"
                            moduleName="documentService"
                        />
                    </div>
                </div>
            </div>

            <div class="card my-2">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Sales") }}</h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <!-- Contact report module -->
                                <MultiSelectInput
                                    :required="true"
                                    v-model="contactReportTemplateId"
                                    :options="documentServices.data"
                                    :key="contactReportTemplateId"
                                    :multiple="false"
                                    :textLabel="$t('Contact report template')"
                                    label="name"
                                    trackBy="id"
                                    :error="errors.contactReportTemplateId"
                                    moduleName="documentService"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    :required="true"
                                    v-model="offerTemplateId"
                                    :options="documentServices.data"
                                    :key="offerTemplateId"
                                    :multiple="false"
                                    :textLabel="$t('Offer template')"
                                    label="name"
                                    trackBy="id"
                                    :error="errors.offerTemplateId"
                                    moduleName="documentService"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    :required="true"
                                    v-model="orderConfirmationTemplateId"
                                    :options="documentServices.data"
                                    :key="orderConfirmationTemplateId"
                                    :multiple="false"
                                    :textLabel="$t('Offer Confirmation Template')"
                                    label="name"
                                    trackBy="id"
                                    :error="errors.orderConfirmationTemplateId"
                                    moduleName="documentService"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card my-2">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Project Management") }}</h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    :required="true"
                                    v-model="projectProtocolTemplateId"
                                    :options="documentServices.data"
                                    :key="projectProtocolTemplateId"
                                    :multiple="false"
                                    :textLabel="$t('Project Protocol Template')"
                                    label="name"
                                    trackBy="id"
                                    :error="errors.projectProtocolTemplateId"
                                    moduleName="documentService"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card my-2">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Service") }}</h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    :required="true"
                                    v-model="serviceReportTemplateId"
                                    :options="documentServices.data"
                                    :key="serviceReportTemplateId"
                                    :multiple="false"
                                    :textLabel="$t('Service Report Template')"
                                    label="name"
                                    trackBy="id"
                                    :error="errors.serviceReportTemplateId"
                                    moduleName="documentService"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card my-2">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Time Tracker") }}</h3>
                </div>
                <div class="card-body">
                    <div class="flex flex-wrap"></div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link to="/dashboard" class="primary-btn mr-3">
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
import SelectInput from "../../Components/SelectInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";

export default {
    mounted() {
        this.refresh();
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("documentService", ["documentServices", "count"]),
        ...mapGetters("invoicereminder", ["invoiceReminder"]),
    },
    components: {
        LoadingButton,
        SelectInput,
        PageHeader,
        MultiSelectInput,
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Document Assignment",
                    active: true,
                },
            ],
            modelData: {},
            contactReportTemplateId: "",
            invoiceTemplateId: "",
            invoiceCorrectionTemplateId: "",
            invoiceStornoTemplateId: "",
            offerTemplateId: "",
            perfRecordTemplateId: "",
            orderConfirmationTemplateId: "",
            projectProtocolTemplateId: "",
            serviceReportTemplateId: "",
            invoiceWarningLevels: [],
        };
    },
    methods: {
        async refresh() {
            try {
                this.$store.commit("showLoader", true);

                await this.$store.dispatch("documentService/list", {
                    limit_start: 0,
                    limit_count: 3,
                });
                await this.$store.dispatch("invoicereminder/list");
                const response = await this.$store.dispatch(
                    "globalSettings/documentAssignmentList"
                );
                this.modelData = response?.data ?? {};

                try {
                    const response = await this.$store.dispatch(
                        "documentService/show",
                        this.modelData.contactReportTemplateId
                    );
                    this.contactReportTemplateId = response?.data ?? "";
                } catch (e) {}
                try {
                    const response = await this.$store.dispatch(
                        "documentService/show",
                        this.modelData.invoiceTemplateId
                    );
                    this.invoiceTemplateId = response?.data ?? "";
                } catch (e) {}
                try {
                    const response = await this.$store.dispatch(
                        "documentService/show",
                        this.modelData.invoiceCorrectionTemplateId
                    );
                    this.invoiceCorrectionTemplateId = response?.data ?? "";
                } catch (e) {}
                try {
                    const response = await this.$store.dispatch(
                        "documentService/show",
                        this.modelData.invoiceStornoTemplateId
                    );
                    this.invoiceStornoTemplateId = response?.data ?? "";
                } catch (e) {}
                try {
                    const response = await this.$store.dispatch(
                        "documentService/show",
                        this.modelData.offerTemplateId
                    );
                    this.offerTemplateId = response?.data ?? "";
                } catch (e) {}
                try {
                    const response = await this.$store.dispatch(
                        "documentService/show",
                        this.modelData.perfRecordTemplateId
                    );
                    this.perfRecordTemplateId = response?.data ?? "";
                } catch (e) {}
                try {
                    const response = await this.$store.dispatch(
                        "documentService/show",
                        this.modelData.orderConfirmationTemplateId
                    );
                    this.orderConfirmationTemplateId = response?.data ?? "";
                } catch (e) {}
                try {
                    const response = await this.$store.dispatch(
                        "documentService/show",
                        this.modelData.projectProtocolTemplateId
                    );
                    this.projectProtocolTemplateId = response?.data ?? "";
                } catch (e) {}
                try {
                    const response = await this.$store.dispatch(
                        "documentService/show",
                        this.modelData.serviceReportTemplateId
                    );
                    this.serviceReportTemplateId = response?.data ?? "";
                } catch (e) {}

                this.invoiceReminder?.data?.map((data) => {
                    var levelName = data.levelName;
                    var modelDataForLevel = this.modelData?.[levelName];
                    this.invoiceWarningLevels.push({
                        label: levelName,
                        reminderTemplateId: modelDataForLevel
                            ? modelDataForLevel
                            : "",
                    });
                });
                this.invoiceWarningLevels.forEach(async (level) => {
                    try {
                        const response = await this.$store.dispatch(
                            "documentService/show",
                            level.reminderTemplateId
                        );
                        level.reminderTemplateId = response?.data ?? "";
                    } catch (e) {}
                });
            } catch (e) {
                console.log(e);
            } finally {
                this.$store.commit("showLoader", false);
            }
        },
        async save() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch(
                    "globalSettings/saveDocumentAssignment",
                    {
                        contactReportTemplateId:
                            this.contactReportTemplateId?.id,
                        invoiceTemplateId: this.invoiceTemplateId?.id,
                        invoiceCorrectionTemplateId:
                            this.invoiceCorrectionTemplateId?.id,
                        invoiceStornoTemplateId:
                            this.invoiceStornoTemplateId?.id,
                        offerTemplateId: this.offerTemplateId?.id,
                        perfRecordTemplateId: this.perfRecordTemplateId?.id,
                        orderConfirmationTemplateId:
                            this.orderConfirmationTemplateId?.id,
                        projectProtocolTemplateId:
                            this.projectProtocolTemplateId?.id,
                        serviceReportTemplateId:
                            this.serviceReportTemplateId?.id,
                        invoiceWarningLevels: this.invoiceWarningLevels.map(
                            (level) => {
                                return {
                                    ...level,
                                    reminderTemplateId:
                                        level?.reminderTemplateId?.id,
                                };
                            }
                        ),
                    }
                );
            } catch (e) {
                console.log(e);
            }
        },
    },
};
</script>

<style scoped>
label {
    font-weight: bold;
}
</style>

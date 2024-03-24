<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <!-- <h6 class="text-xl font-normal leading-normal mt-0 mb-2 text-cyan-800">
      {{ $t("Save Mail Template Assignment") }}
    </h6> -->
        <form @submit.prevent="save">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Finance") }}</h3>
                </div>
                <div class="card-body">
                    <div class="">
                        <div class="flex flex-wrap w-full">
                            <!-- Invoice template -->
                            <div class="w-full lg:w-1/4">
                                <div class="form-group">
                                    <MultiSelectInput
                                        v-model="invoiceTemplate.mailTemplateId"
                                        :error="errors.invoiceTemplateId"
                                        :options="templates"
                                        :key="invoiceTemplate.mailTemplateId"
                                        :multiple="false"
                                        :textLabel="$t('Invoice Template')"
                                        label="subject"
                                        trackBy="id"
                                        class="pb-8 pr-6"
                                        moduleName="mailTemplates"
                                        :search-param-name="'search_string'"
                                    >
                                    </MultiSelectInput>
                                </div>
                            </div>
                            <div class="w-full lg:w-1/4">
                                <div class="form-group">
                                    <TextInput
                                        class="pb-8 pr-6"
                                        :label="$t('CC')"
                                        v-model="invoiceTemplate.cc"
                                    />
                                </div>
                            </div>
                            <div class="w-full lg:w-1/4">
                                <div class="form-group">
                                    <TextInput
                                        class="pb-8 pr-6"
                                        :label="$t('BCC')"
                                        v-model="invoiceTemplate.bcc"
                                    />
                                </div>
                            </div>
                            <div class="w-full lg:w-1/4">
                                <div class="form-group">
                                    <TextInput
                                        class="pb-8 pr-6"
                                        :label="$t('Sender Mail')"
                                        v-model="invoiceTemplate.senderMail"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Invoice Correction template -->
                        <div class="flex flex-wrap w-full">
                            <div class="w-full lg:w-1/4">
                                <div class="form-group">
                                    <MultiSelectInput
                                        v-model="
                                            invoiceCorrectionTemplate.mailTemplateId
                                        "
                                        :error="
                                            errors.invoiceCorrectionTemplateId
                                        "
                                        :options="templates"
                                        :key="
                                            invoiceCorrectionTemplate.mailTemplateId
                                        "
                                        :multiple="false"
                                        :textLabel="
                                            $t('Invoice Correction Template')
                                        "
                                        label="subject"
                                        trackBy="id"
                                        class="pb-8 pr-6"
                                        moduleName="mailTemplates"
                                        :search-param-name="'search_string'"
                                    >
                                    </MultiSelectInput>
                                </div>
                            </div>
                            <div class="w-full lg:w-1/4">
                                <div class="form-group">
                                    <TextInput
                                        class="pb-8 pr-6"
                                        :label="$t('CC')"
                                        v-model="invoiceCorrectionTemplate.cc"
                                    />
                                </div>
                            </div>
                            <div class="w-full lg:w-1/4">
                                <div class="form-group">
                                    <TextInput
                                        class="pb-8 pr-6"
                                        :label="$t('BCC')"
                                        v-model="invoiceCorrectionTemplate.bcc"
                                    />
                                </div>
                            </div>
                            <div class="w-full lg:w-1/4">
                                <div class="form-group">
                                    <TextInput
                                        class="pb-8 pr-6"
                                        :label="$t('Sender Mail')"
                                        v-model="
                                            invoiceCorrectionTemplate.senderMail
                                        "
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Invoice Storno template -->
                        <div class="flex flex-wrap w-full">
                            <div class="w-full lg:w-1/4">
                                <div class="form-group">
                                    <MultiSelectInput
                                        v-model="
                                            invoiceStornoTemplate.mailTemplateId
                                        "
                                        :error="errors.invoiceStornoTemplateId"
                                        :options="templates"
                                        :key="
                                            invoiceStornoTemplate.mailTemplateId
                                        "
                                        :multiple="false"
                                        :textLabel="
                                            $t('Invoice Storno Template')
                                        "
                                        label="subject"
                                        trackBy="id"
                                        class="pb-8 pr-6"
                                        moduleName="mailTemplates"
                                        :search-param-name="'search_string'"
                                    >
                                    </MultiSelectInput>
                                </div>
                            </div>
                            <div class="w-full lg:w-1/4">
                                <div class="form-group">
                                    <TextInput
                                        class="pb-8 pr-6"
                                        :label="$t('CC')"
                                        v-model="invoiceStornoTemplate.cc"
                                    />
                                </div>
                            </div>
                            <div class="w-full lg:w-1/4">
                                <div class="form-group">
                                    <TextInput
                                        class="pb-8 pr-6"
                                        :label="$t('BCC')"
                                        v-model="invoiceStornoTemplate.bcc"
                                    />
                                </div>
                            </div>
                            <div class="w-full lg:w-1/4">
                                <div class="form-group">
                                    <TextInput
                                        class="pb-8 pr-6"
                                        :label="$t('Sender Mail')"
                                        v-model="
                                            invoiceStornoTemplate.senderMail
                                        "
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap w-full">
                            <div class="w-full lg:w-1/4">
                                <div class="form-group">
                                    <MultiSelectInput
                                        v-model="
                                            perfRecordTemplate.mailTemplateId
                                        "
                                        :error="errors.perfRecordTemplateId"
                                        :options="templates"
                                        :key="perfRecordTemplate.mailTemplateId"
                                        :multiple="false"
                                        :textLabel="
                                            $t('Performance Record Template')
                                        "
                                        label="subject"
                                        trackBy="id"
                                        class="pb-8 pr-6"
                                        moduleName="mailTemplates"
                                        :search-param-name="'search_string'"
                                    >
                                    </MultiSelectInput>
                                </div>
                            </div>
                            <div class="w-full lg:w-1/4">
                                <div class="form-group">
                                    <TextInput
                                        class="pb-8 pr-6"
                                        :label="$t('CC')"
                                        v-model="perfRecordTemplate.cc"
                                    />
                                </div>
                            </div>
                            <div class="w-full lg:w-1/4">
                                <div class="form-group">
                                    <TextInput
                                        class="pb-8 pr-6"
                                        :label="$t('BCC')"
                                        v-model="perfRecordTemplate.bcc"
                                    />
                                </div>
                            </div>
                            <div class="w-full lg:w-1/4">
                                <div class="form-group">
                                    <TextInput
                                        class="pb-8 pr-6"
                                        :label="$t('Sender Mail')"
                                        v-model="perfRecordTemplate.senderMail"
                                    />
                                </div>
                            </div>
                        </div>

                        <div
                            v-for="(item, index) in invoiceWarningLevels"
                            :key="'reminder-' + index"
                            class="flex flex-wrap w-full"
                        >
                            <div class="w-full lg:w-1/4">
                                <div class="form-group">
                                    <MultiSelectInput
                                        v-model="item.mailTemplateId"
                                        :options="templates"
                                        :key="item.mailTemplateId"
                                        :multiple="false"
                                        :textLabel="$t(item.module)"
                                        label="subject"
                                        trackBy="id"
                                        class="pb-8 pr-6"
                                        moduleName="mailTemplates"
                                        :search-param-name="'search_string'"
                                    >
                                    </MultiSelectInput>
                                </div>
                            </div>
                            <div class="w-full lg:w-1/4">
                                <div class="form-group">
                                    <TextInput
                                        class="pb-8 pr-6"
                                        :label="$t('CC')"
                                        v-model="item.cc"
                                    />
                                </div>
                            </div>
                            <div class="w-full lg:w-1/4">
                                <div class="form-group">
                                    <TextInput
                                        class="pb-8 pr-6"
                                        :label="$t('BCC')"
                                        v-model="item.bcc"
                                    />
                                </div>
                            </div>
                            <div class="w-full lg:w-1/4">
                                <div class="form-group">
                                    <TextInput
                                        class="pb-8 pr-6"
                                        :label="$t('Sender Mail')"
                                        v-model="item.senderMail"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card my-3">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Sales") }}</h3>
                </div>
                <div class="card-body">
                    <div class="flex flex-wrap w-full">
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="
                                        contactReportTemplate.mailTemplateId
                                    "
                                    :options="templates"
                                    :key="contactReportTemplate.mailTemplateId"
                                    :multiple="false"
                                    :textLabel="$t('Contact report template')"
                                    label="subject"
                                    trackBy="id"
                                    class="pb-8 pr-6"
                                    moduleName="mailTemplates"
                                    :search-param-name="'search_string'"
                                >
                                </MultiSelectInput>
                            </div>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <TextInput
                                    class="pb-8 pr-6"
                                    :label="$t('CC')"
                                    v-model="contactReportTemplate.cc"
                                />
                            </div>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <TextInput
                                    class="pb-8 pr-6"
                                    :label="$t('BCC')"
                                    v-model="contactReportTemplate.bcc"
                                />
                            </div>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <TextInput
                                    class="pb-8 pr-6"
                                    :label="$t('Sender Mail')"
                                    v-model="contactReportTemplate.senderMail"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap w-full">
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="offerTemplate.mailTemplateId"
                                    :options="templates"
                                    :key="offerTemplate.mailTemplateId"
                                    :multiple="false"
                                    :textLabel="$t('Offer template')"
                                    label="subject"
                                    trackBy="id"
                                    class="pb-8 pr-6"
                                    moduleName="mailTemplates"
                                    :search-param-name="'search_string'"
                                >
                                </MultiSelectInput>
                            </div>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <TextInput
                                    class="pb-8 pr-6"
                                    :label="$t('CC')"
                                    v-model="offerTemplate.cc"
                                />
                            </div>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <TextInput
                                    class="pb-8 pr-6"
                                    :label="$t('BCC')"
                                    v-model="offerTemplate.bcc"
                                />
                            </div>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <TextInput
                                    class="pb-8 pr-6"
                                    :label="$t('Sender Mail')"
                                    v-model="offerTemplate.senderMail"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap w-full">
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="
                                        orderConfirmationTemplate.mailTemplateId
                                    "
                                    :options="templates"
                                    :key="
                                        orderConfirmationTemplate.mailTemplateId
                                    "
                                    :multiple="false"
                                    :textLabel="
                                        $t('Offer Confirmation template')
                                    "
                                    label="subject"
                                    trackBy="id"
                                    class="pb-8 pr-6"
                                    moduleName="mailTemplates"
                                    :search-param-name="'search_string'"
                                >
                                </MultiSelectInput>
                            </div>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <TextInput
                                    class="pb-8 pr-6"
                                    :label="$t('CC')"
                                    v-model="orderConfirmationTemplate.cc"
                                />
                            </div>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <TextInput
                                    class="pb-8 pr-6"
                                    :label="$t('BCC')"
                                    v-model="orderConfirmationTemplate.bcc"
                                />
                            </div>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <TextInput
                                    class="pb-8 pr-6"
                                    :label="$t('Sender Mail')"
                                    v-model="
                                        orderConfirmationTemplate.senderMail
                                    "
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
                    <div class="flex flex-wrap w-full">
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="
                                        projectProtocolTemplate.mailTemplateId
                                    "
                                    :options="templates"
                                    :key="
                                        projectProtocolTemplate.mailTemplateId
                                    "
                                    :multiple="false"
                                    :textLabel="$t('Project Protocol Template')"
                                    label="subject"
                                    trackBy="id"
                                    class="pb-8 pr-6"
                                    moduleName="mailTemplates"
                                    :search-param-name="'search_string'"
                                >
                                </MultiSelectInput>
                            </div>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <TextInput
                                    class="pb-8 pr-6"
                                    :label="$t('CC')"
                                    v-model="projectProtocolTemplate.cc"
                                />
                            </div>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <TextInput
                                    class="pb-8 pr-6"
                                    :label="$t('BCC')"
                                    v-model="projectProtocolTemplate.bcc"
                                />
                            </div>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <TextInput
                                    class="pb-8 pr-6"
                                    :label="$t('Sender Mail')"
                                    v-model="projectProtocolTemplate.senderMail"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card my-2">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Support") }}</h3>
                </div>
                <div class="card-body">
                    <div class="flex flex-wrap w-full">
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="ticketTemplate.mailTemplateId"
                                    :options="templates"
                                    :key="ticketTemplate.mailTemplateId"
                                    :multiple="false"
                                    :textLabel="$t('Ticket Template')"
                                    label="subject"
                                    trackBy="id"
                                    class="pb-8 pr-6"
                                    moduleName="mailTemplates"
                                    :search-param-name="'search_string'"
                                >
                                </MultiSelectInput>
                            </div>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <TextInput
                                    class="pb-8 pr-6"
                                    :label="$t('CC')"
                                    v-model="ticketTemplate.cc"
                                />
                            </div>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <TextInput
                                    class="pb-8 pr-6"
                                    :label="$t('BCC')"
                                    v-model="ticketTemplate.bcc"
                                />
                            </div>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <TextInput
                                    class="pb-8 pr-6"
                                    :label="$t('Sender Mail')"
                                    v-model="ticketTemplate.senderMail"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card my-2">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Vacation Request") }}</h3>
                </div>
                <div class="card-body">
                    <div class="flex flex-wrap w-full">
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="
                                        vacationRequestTemplate.mailTemplateId
                                    "
                                    :options="templates"
                                    :key="
                                        vacationRequestTemplate.mailTemplateId
                                    "
                                    :multiple="false"
                                    :textLabel="$t('Vacation Request Template')"
                                    label="subject"
                                    trackBy="id"
                                    class="pb-8 pr-6"
                                    moduleName="mailTemplates"
                                    :search-param-name="'search_string'"
                                >
                                </MultiSelectInput>
                            </div>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <TextInput
                                    class="pb-8 pr-6"
                                    :label="$t('CC')"
                                    v-model="vacationRequestTemplate.cc"
                                />
                            </div>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <TextInput
                                    class="pb-8 pr-6"
                                    :label="$t('BCC')"
                                    v-model="vacationRequestTemplate.bcc"
                                />
                            </div>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <TextInput
                                    class="pb-8 pr-6"
                                    :label="$t('Sender Mail')"
                                    v-model="vacationRequestTemplate.senderMail"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card my-2">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("My Tasks") }}</h3>
                </div>
                <div class="card-body">
                    <div class="flex flex-wrap w-full">
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="taskCreateTemplate.mailTemplateId"
                                    :options="templates"
                                    :key="taskCreateTemplate.mailTemplateId"
                                    :multiple="false"
                                    :textLabel="$t('Task Create Template')"
                                    label="subject"
                                    trackBy="id"
                                    class="pb-8 pr-6"
                                    moduleName="mailTemplates"
                                    :search-param-name="'search_string'"
                                >
                                </MultiSelectInput>
                            </div>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <TextInput
                                    class="pb-8 pr-6"
                                    :label="$t('CC')"
                                    v-model="taskCreateTemplate.cc"
                                />
                            </div>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <TextInput
                                    class="pb-8 pr-6"
                                    :label="$t('BCC')"
                                    v-model="taskCreateTemplate.bcc"
                                />
                            </div>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <TextInput
                                    class="pb-8 pr-6"
                                    :label="$t('Sender Mail')"
                                    v-model="taskCreateTemplate.senderMail"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap w-full">
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="taskUpdateTemplate.mailTemplateId"
                                    :options="templates"
                                    :key="taskUpdateTemplate.mailTemplateId"
                                    :multiple="false"
                                    :textLabel="$t('Task Update Template')"
                                    label="subject"
                                    trackBy="id"
                                    class="pb-8 pr-6"
                                    moduleName="mailTemplates"
                                    :search-param-name="'search_string'"
                                >
                                </MultiSelectInput>
                            </div>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <TextInput
                                    class="pb-8 pr-6"
                                    :label="$t('CC')"
                                    v-model="taskUpdateTemplate.cc"
                                />
                            </div>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <TextInput
                                    class="pb-8 pr-6"
                                    :label="$t('BCC')"
                                    v-model="taskUpdateTemplate.bcc"
                                />
                            </div>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <TextInput
                                    class="pb-8 pr-6"
                                    :label="$t('Sender Mail')"
                                    v-model="taskUpdateTemplate.senderMail"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap w-full">
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="
                                        taskStatusChangeTemplate.mailTemplateId
                                    "
                                    :options="templates"
                                    :key="
                                        taskStatusChangeTemplate.mailTemplateId
                                    "
                                    :multiple="false"
                                    :textLabel="
                                        $t('Task Status Change Template')
                                    "
                                    label="subject"
                                    trackBy="id"
                                    class="pb-8 pr-6"
                                    moduleName="mailTemplates"
                                    :search-param-name="'search_string'"
                                >
                                </MultiSelectInput>
                            </div>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <TextInput
                                    class="pb-8 pr-6"
                                    :label="$t('CC')"
                                    v-model="taskStatusChangeTemplate.cc"
                                />
                            </div>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <TextInput
                                    class="pb-8 pr-6"
                                    :label="$t('BCC')"
                                    v-model="taskStatusChangeTemplate.bcc"
                                />
                            </div>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <div class="form-group">
                                <TextInput
                                    class="pb-8 pr-6"
                                    :label="$t('Sender Mail')"
                                    v-model="
                                        taskStatusChangeTemplate.senderMail
                                    "
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
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import TextInput from "../../Components/TextInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";

export default {
    mounted() {
        this.refresh();
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("mailTemplates", ["templates"]),
        ...mapGetters("invoicereminder", ["invoiceReminder"]),
    },
    components: {
        LoadingButton,
        MultiSelectInput,
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
                    text: "Mail Template Assignment",
                    active: true,
                },
            ],
            shouldShow: true,
            contactReportTemplate: {
                module: "contactReportTemplate",
                mailTemplateId: "",
                cc: "",
                bcc: "",
                senderMail: "",
            },
            invoiceTemplate: {
                module: "invoiceTemplate",
                mailTemplateId: "",
                cc: "",
                bcc: "",
                senderMail: "",
            },
            ticketTemplate: {
                module: "ticketTemplate",
                mailTemplateId: "",
                cc: "",
                bcc: "",
                senderMail: "",
            },
            taskCreateTemplate: {
                module: "taskCreateTemplate",
                mailTemplateId: "",
                cc: "",
                bcc: "",
                senderMail: "",
            },
            taskUpdateTemplate: {
                module: "taskUpdateTemplate",
                mailTemplateId: "",
                cc: "",
                bcc: "",
                senderMail: "",
            },
            taskStatusChangeTemplate: {
                module: "taskStatusChangeTemplate",
                mailTemplateId: "",
                cc: "",
                bcc: "",
                senderMail: "",
            },
            invoiceCorrectionTemplate: {
                module: "invoiceCorrectionTemplate",
                mailTemplateId: "",
                cc: "",
                bcc: "",
                senderMail: "",
            },
            invoiceStornoTemplate: {
                module: "invoiceStornoTemplate",
                mailTemplateId: "",
                cc: "",
                bcc: "",
                senderMail: "",
            },
            offerTemplate: {
                module: "offerTemplate",
                mailTemplateId: "",
                cc: "",
                bcc: "",
                senderMail: "",
            },
            perfRecordTemplate: {
                module: "perfRecordTemplate",
                mailTemplateId: "",
                cc: "",
                bcc: "",
                senderMail: "",
            },
            orderConfirmationTemplate: {
                module: "orderConfirmationTemplate",
                mailTemplateId: "",
                cc: "",
                bcc: "",
                senderMail: "",
            },
            projectProtocolTemplate: {
                module: "projectProtocolTemplate",
                mailTemplateId: "",
                cc: "",
                bcc: "",
                senderMail: "",
            },
            vacationRequestTemplate: {
                module: "vacationRequestTemplate",
                mailTemplateId: "",
                cc: "",
                bcc: "",
                senderMail: "",
            },
            invoiceWarningLevels: [],
        };
    },
    methods: {
        async refresh() {
            this.shouldShow = false;
            try {
                this.$store.commit("showLoader", true);
                // fetch listing
                await this.$store.dispatch("mailTemplates/list");
                const response = await this.$store.dispatch(
                    "mailTemplates/mailTemplateAssignmentList"
                );
                // get invoice reminder listing
                await this.$store.dispatch("invoicereminder/list");
                // loop over the invoice reminders and add to invoiceWarningLevels. The template dropdowns for invoice reminders are set dynamically
                this.invoiceReminder?.data?.map((data) => {
                    var levelName = data.levelName;
                    this.invoiceWarningLevels.push({
                        module: levelName,
                        mailTemplateId: "",
                        cc: "",
                        bcc: "",
                        senderMail: "",
                    });
                });
                // loop over all the mail template assignments received from the API response
                response?.data?.data?.forEach(async (item) => {
                    if (item.module === "contactReportTemplate") {
                        this.contactReportTemplate = item;
                        if (this.contactReportTemplate.mailTemplateId)
                            //find the template object in the mail templates listing and set the found object to the mailTemplateId
                            this.contactReportTemplate.mailTemplateId =
                                await this.setTemplate(
                                    this.contactReportTemplate.mailTemplateId
                                );
                    }
                    if (item.module === "invoiceTemplate") {
                        this.invoiceTemplate = item;
                        if (this.invoiceTemplate.mailTemplateId)
                            //find the template object in the mail templates listing and set the found object to the mailTemplateId
                            this.invoiceTemplate.mailTemplateId =
                                await this.setTemplate(
                                    this.invoiceTemplate.mailTemplateId
                                );
                    }
                    if (item.module === "ticketTemplate") {
                        this.ticketTemplate = item;
                        if (this.ticketTemplate.mailTemplateId)
                            //find the template object in the mail templates listing and set the found object to the mailTemplateId
                            this.ticketTemplate.mailTemplateId =
                                await this.setTemplate(
                                    this.ticketTemplate.mailTemplateId
                                );
                    }
                    if (item.module === "taskCreateTemplate") {
                        this.taskCreateTemplate = item;
                        if (this.taskCreateTemplate.mailTemplateId)
                            //find the template object in the mail templates listing and set the found object to the mailTemplateId
                            this.taskCreateTemplate.mailTemplateId =
                                await this.setTemplate(
                                    this.taskCreateTemplate.mailTemplateId
                                );
                    }
                    if (item.module === "taskUpdateTemplate") {
                        this.taskUpdateTemplate = item;
                        if (this.taskUpdateTemplate.mailTemplateId)
                            //find the template object in the mail templates listing and set the found object to the mailTemplateId
                            this.taskUpdateTemplate.mailTemplateId =
                                await this.setTemplate(
                                    this.taskUpdateTemplate.mailTemplateId
                                );
                    }
                    if (item.module === "taskStatusChangeTemplate") {
                        this.taskStatusChangeTemplate = item;
                        if (this.taskStatusChangeTemplate.mailTemplateId)
                            //find the template object in the mail templates listing and set the found object to the mailTemplateId
                            this.taskStatusChangeTemplate.mailTemplateId =
                                await this.setTemplate(
                                    this.taskStatusChangeTemplate.mailTemplateId
                                );
                    }
                    if (item.module === "vacationRequestTemplate") {
                        this.vacationRequestTemplate = item;
                        if (this.vacationRequestTemplate.mailTemplateId)
                            //find the template object in the mail templates listing and set the found object to the mailTemplateId
                            this.vacationRequestTemplate.mailTemplateId =
                                await this.setTemplate(
                                    this.vacationRequestTemplate.mailTemplateId
                                );
                    }
                    if (item.module === "invoiceCorrectionTemplate") {
                        this.invoiceCorrectionTemplate = item;
                        if (this.invoiceCorrectionTemplate.mailTemplateId)
                            //find the template object in the mail templates listing and set the found object to the mailTemplateId

                            this.invoiceCorrectionTemplate.mailTemplateId =
                                await this.setTemplate(
                                    this.invoiceCorrectionTemplate
                                        .mailTemplateId
                                );
                    }
                    if (item.module === "invoiceStornoTemplate") {
                        this.invoiceStornoTemplate = item;
                        if (this.invoiceStornoTemplate.mailTemplateId)
                            //find the template object in the mail templates listing and set the found object to the mailTemplateId

                            this.invoiceStornoTemplate.mailTemplateId =
                                await this.setTemplate(
                                    this.invoiceStornoTemplate.mailTemplateId
                                );
                    }
                    if (item.module === "offerTemplate") {
                        this.offerTemplate = item;
                        if (this.offerTemplate.mailTemplateId)
                            //find the template object in the mail templates listing and set the found object to the mailTemplateId

                            this.offerTemplate.mailTemplateId =
                                await this.setTemplate(
                                    this.offerTemplate.mailTemplateId
                                );
                    }
                    if (item.module === "perfRecordTemplate") {
                        this.perfRecordTemplate = item;
                        if (this.perfRecordTemplate.mailTemplateId)
                            //find the template object in the mail templates listing and set the found object to the mailTemplateId

                            this.perfRecordTemplate.mailTemplateId =
                                await this.setTemplate(
                                    this.perfRecordTemplate.mailTemplateId
                                );
                    }
                    if (item.module === "orderConfirmationTemplate") {
                        this.orderConfirmationTemplate = item;
                        if (this.orderConfirmationTemplate.mailTemplateId)
                            //find the template object in the mail templates listing and set the found object to the mailTemplateId

                            this.orderConfirmationTemplate.mailTemplateId =
                                await this.setTemplate(
                                    this.orderConfirmationTemplate
                                        .mailTemplateId
                                );
                    }
                    if (item.module === "projectProtocolTemplate") {
                        this.projectProtocolTemplate = item;
                        if (this.projectProtocolTemplate.mailTemplateId)
                            //find the template object in the mail templates listing and set the found object to the mailTemplateId

                            this.projectProtocolTemplate.mailTemplateId =
                                await this.setTemplate(
                                    this.projectProtocolTemplate.mailTemplateId
                                );
                    } else {
                        const levelIndex = this.invoiceWarningLevels.findIndex(
                            (level) => level.module === item.module
                        );
                        if (levelIndex > -1) {
                            this.invoiceWarningLevels[levelIndex] = item;
                            if (
                                this.invoiceWarningLevels[levelIndex]
                                    .mailTemplateId
                            )
                                //find the template object in the mail templates listing and set the found object to the mailTemplateId
                                this.invoiceWarningLevels[
                                    levelIndex
                                ].mailTemplateId = await this.setTemplate(
                                    this.invoiceWarningLevels[levelIndex]
                                        .mailTemplateId
                                );
                        }
                    }
                });
            } catch (e) {
                console.log(e);
            } finally {
                this.shouldShow = true;
                this.$store.commit("showLoader", false);
            }
        },
        /**
         * find the template from the templates listing and returns the found object
         * @param {value} the template id used for searching
         */
        setTemplate(value) {
            return new Promise(async (resolve, reject) => {
                try {
                    let template = null;
                    // set the 'mailTemplateId' from the listing
                    template = this.templates?.find((template) => {
                        return template.id === value ?? {};
                    });
                    // if not found then fetch individually using the show API
                    if (!template && value) {
                        response = await this.$store.dispatch(
                            "mailTemplates/show",
                            value ?? {}
                        );
                        template = response?.data ?? {};
                    }
                    resolve(template);
                } catch (e) {
                    reject("");
                }
            });
        },
        /**
         * create the payload and hit the mail template assignment save API
         */
        async save() {
            try {
                this.$store.commit("isLoading", true);
                // map the mail template assignments to form the correct payload
                const modules = [];
                modules.push({
                    ...this.contactReportTemplate,
                    mailTemplateId:
                        this.contactReportTemplate.mailTemplateId?.id ?? "",
                });
                modules.push({
                    ...this.invoiceTemplate,
                    mailTemplateId:
                        this.invoiceTemplate.mailTemplateId?.id ?? "",
                });
                modules.push({
                    ...this.ticketTemplate,
                    mailTemplateId:
                        this.ticketTemplate.mailTemplateId?.id ?? "",
                });
                modules.push({
                    ...this.taskCreateTemplate,
                    mailTemplateId:
                        this.taskCreateTemplate.mailTemplateId?.id ?? "",
                });
                modules.push({
                    ...this.taskUpdateTemplate,
                    mailTemplateId:
                        this.taskUpdateTemplate.mailTemplateId?.id ?? "",
                });
                modules.push({
                    ...this.taskStatusChangeTemplate,
                    mailTemplateId:
                        this.taskStatusChangeTemplate.mailTemplateId?.id ?? "",
                });
                modules.push({
                    ...this.invoiceCorrectionTemplate,
                    mailTemplateId:
                        this.invoiceCorrectionTemplate.mailTemplateId?.id ?? "",
                });
                modules.push({
                    ...this.invoiceStornoTemplate,
                    mailTemplateId:
                        this.invoiceStornoTemplate.mailTemplateId?.id ?? "",
                });
                modules.push({
                    ...this.offerTemplate,
                    mailTemplateId: this.offerTemplate.mailTemplateId?.id ?? "",
                });
                modules.push({
                    ...this.perfRecordTemplate,
                    mailTemplateId:
                        this.perfRecordTemplate.mailTemplateId?.id ?? "",
                });
                modules.push({
                    ...this.orderConfirmationTemplate,
                    mailTemplateId:
                        this.orderConfirmationTemplate.mailTemplateId?.id ?? "",
                });
                modules.push({
                    ...this.projectProtocolTemplate,
                    mailTemplateId:
                        this.projectProtocolTemplate.mailTemplateId?.id ?? "",
                });
                modules.push({
                    ...this.vacationRequestTemplate,
                    mailTemplateId:
                        this.vacationRequestTemplate.mailTemplateId?.id ?? "",
                });
                this.invoiceWarningLevels.forEach((reminder) => {
                    modules.push({
                        ...reminder,
                        mailTemplateId: reminder.mailTemplateId?.id ?? "",
                    });
                });
                await this.$store.dispatch(
                    "mailTemplates/mailTemplateAssignmentSave",
                    {
                        modules: modules,
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

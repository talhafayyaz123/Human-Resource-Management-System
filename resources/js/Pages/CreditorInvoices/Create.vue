<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $t("Configuration") }}</h3>
            </div>
            <div class="card-body">
                <div class="grid items-center grid-cols-2 gap-6">
                    <div class="w-full">
                        <div class="form-group">
                            <select-input
                                class=""
                                :required="true"
                                v-model="form.invoiceType"
                                :key="form.invoiceType"
                                :error="errors.invoiceType"
                                :label="$t('Invoice Type')"
                            >
                                <option value="invoice">
                                    {{ $t("Invoice") }}
                                </option>
                                <option value="invoice-correction">
                                    {{ $t("Invoice Correction") }}
                                </option>
                                <option value="invoice-storno">
                                    {{ $t("Invoice Storno") }}
                                </option>
                            </select-input>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <select-input
                                class=""
                                :required="true"
                                v-model="form.receiverType"
                                :key="form.receiverType"
                                @update:model-value="form.contactPerson = ''"
                                :error="errors.receiverType"
                                :label="$t('Receiver Type')"
                            >
                                <option value="customer">
                                    {{ $t("Customer") }}
                                </option>
                                <option value="partner">
                                    {{ $t("Partner") }}
                                </option>
                            </select-input>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <MultiSelectInput
                                @open="fetchCustomers"
                                @update:model-value="
                                    fetchSystemsAndCompanyEmployees
                                "
                                v-model="form.customers"
                                :key="form.customers"
                                :required="true"
                                class=""
                                :textLabel="
                                    $t(
                                        form.receiverType === 'customer'
                                            ? 'Customer'
                                            : 'Partner'
                                    )
                                "
                                :placeholder="$t('Select Receiver')"
                                :options="
                                    form.receiverType === 'customer'
                                        ? companies.data
                                        : partners.data
                                "
                                :multiple="false"
                                label="companyName"
                                trackBy="id"
                                moduleName="companies"
                                :query="{ customerType: form.receiverType }"
                                :countStore="
                                    form.receiverType === 'partner'
                                        ? 'partnerCount'
                                        : 'count'
                                "
                                :error="errors.customers"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <MultiSelectInput
                                :customLabel="customLabel"
                                v-model="form.contactPerson"
                                :key="form.contactPerson"
                                class=""
                                :textLabel="$t('Contact Person')"
                                :options="users"
                                :multiple="false"
                                trackBy="id"
                                moduleName="companyEmployees"
                                :error="errors.contactPerson"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <label class="input-label"
                                ><span style="color: red">*</span>&nbsp;{{
                                    $t("Due Date")
                                }}:</label
                            >
                            <datepicker
                                class="form-control"
                                :style="dropdownStyles"
                                v-model="form.dueDate"
                                :clearable="false"
                                :enable-time-picker="false"
                                auto-apply
                                :close-on-auto-apply="false"
                                :class="errors.dueDate ? 'error' : ''"
                            />
                            <div v-if="errors?.dueDate" class="form-error">
                                {{ errors.dueDate }}
                            </div>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <label class="input-label"
                                ><span style="color: red">*</span>&nbsp;{{
                                    $t("Start Date")
                                }}:</label
                            >
                            <datepicker
                                class="form-control"
                                :min-date="
                                    new Date(
                                        `${new Date().getFullYear()}-01-01`
                                    )
                                "
                                :style="dropdownStyles"
                                :clearable="false"
                                :enable-time-picker="false"
                                auto-apply
                                :close-on-auto-apply="false"
                                v-model="form.startDate"
                                :class="errors.startDate ? 'error' : ''"
                            />
                            <div v-if="errors?.startDate" class="form-error">
                                {{ errors.startDate }}
                            </div>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <label class="input-label"
                                ><span style="color: red">*</span>&nbsp;{{
                                    $t("End Date")
                                }}:</label
                            >
                            <datepicker
                                class="form-control"
                                :disabled="form.category === 'maintenance'"
                                :clearable="false"
                                :enable-time-picker="false"
                                auto-apply
                                :close-on-auto-apply="false"
                                :style="dropdownStyles"
                                v-model="form.endDate"
                                :class="errors.endDate ? 'error' : ''"
                            />
                            <div v-if="errors?.endDate" class="form-error">
                                {{ errors.endDate }}
                            </div>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <label class="input-label"
                                >{{ $t("Invoice Date") }}:</label
                            >
                            <datepicker
                                class="form-control"
                                :style="dropdownStyles"
                                :clearable="false"
                                :enable-time-picker="false"
                                auto-apply
                                :close-on-auto-apply="false"
                                v-model="form.draftStatusChangeDate"
                                :class="
                                    errors.draftStatusChangeDate ? 'error' : ''
                                "
                            />
                            <div
                                v-if="errors?.draftStatusChangeDate"
                                class="form-error"
                            >
                                {{ errors.draftStatusChangeDate }}
                            </div>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <NumberInput
                                class=""
                                :label="$t('Netto')"
                                :required="true"
                                @input-event="calculateBruttoTotal"
                                v-model="form.totalAmountWithoutTax"
                                :error="errors.totalAmountWithoutTax"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <NumberInput
                                class=""
                                :label="$t('Tax')"
                                :required="true"
                                @input-event="calculateBruttoTotal"
                                v-model="form.totalTaxAmount"
                                :error="errors.totalTaxAmount"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <NumberInput
                                class=""
                                :label="$t('Brutto')"
                                :required="true"
                                :is-readonly="true"
                                v-model="form.totalAmount"
                                :error="errors.totalAmount"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form @submit.prevent="store">
            <div class="card mt-3" v-if="form.customers?.companyName">
                <div class="card-body">
                    <h6>
                        {{ form.customers?.companyName }}
                        <br />
                        <span v-if="form.contactPerson"
                            >{{ form.contactPerson?.first_name }}
                            {{ form.contactPerson?.last_name }}
                            <br />
                        </span>
                        {{ form.customers?.address }}
                        <br />
                        {{ form.customers?.code }} &nbsp;
                        {{ form.customers?.city }}
                        <br />
                        {{ form.customers?.country }}
                        <br />
                    </h6>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header flex justify-between">
                    <h3 class="card-title">
                        Draft {{ invoiceTypeEnums[this.form.invoiceType] }}
                    </h3>
                    <h6>
                        {{
                            $dateFormatter(
                                formatDateLite(new Date()),
                                globalLanguage
                            )
                        }}
                    </h6>
                </div>
                <div class="card-body">
                    <div
                        class="grid gap-2"
                        style="grid-template-columns: repeat(auto-fit, 500px)"
                    >
                        <div class="mb-8" style="color: #2957a4">
                            <div
                                class="grid gap-2"
                                style="
                                    grid-template-columns: repeat(
                                        auto-fit,
                                        200px
                                    );
                                "
                            >
                                <b>{{ $t("Customer Nr.") }}</b>
                                <p>
                                    {{ form?.customers?.companyNumber ?? "-" }}
                                </p>
                            </div>
                            <div
                                class="grid gap-2"
                                style="
                                    grid-template-columns: repeat(
                                        auto-fit,
                                        200px
                                    );
                                "
                            >
                                <b>{{ $t("Invoice Nr.") }}</b>
                                <p>
                                    Draft
                                    {{
                                        invoiceTypeEnums[this.form.invoiceType]
                                    }}
                                </p>
                            </div>
                        </div>
                        <div class="mb-8" style="color: #2957a4">
                            <div
                                v-if="user"
                                class="grid gap-2"
                                style="
                                    grid-template-columns: repeat(
                                        auto-fit,
                                        200px
                                    );
                                "
                            >
                                <b>{{ $t("Created By") }}:</b>
                                <p>
                                    {{ user?.first_name }} {{ user?.last_name }}
                                </p>
                            </div>
                            <div
                                v-if="form?.dueDate"
                                class="grid gap-2"
                                style="
                                    grid-template-columns: repeat(
                                        auto-fit,
                                        200px
                                    );
                                "
                            >
                                <b>{{ $t("Due Date") }}:</b>
                                <p>
                                    {{
                                        $dateFormatter(
                                            formatDateLite(
                                                new Date(form?.dueDate)
                                            ),
                                            globalLanguage
                                        )
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Fill Invoice Details") }}</h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.invoicePeriod"
                                    :key="form.invoicePeriod"
                                    :error="errors.invoicePeriod"
                                    :required="true"
                                    class=""
                                    :textLabel="$t('Payment Period')"
                                    :options="periods.data"
                                    :multiple="false"
                                    label="name"
                                    trackBy="id"
                                    moduleName="periods"
                                ></MultiSelectInput>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <div
                                    class=""
                                    :class="[
                                        'grid',
                                        `grid-cols-[${
                                            form.termsOfPayment
                                                ? '5fr,1fr'
                                                : '100%'
                                        }]`,
                                        'gap-2',
                                    ]"
                                >
                                    <MultiSelectInput
                                        :required="true"
                                        v-model="form.termsOfPayment"
                                        :key="form.termsOfPayment"
                                        :error="errors.termsOfPayment"
                                        :multiple="false"
                                        trackBy="id"
                                        label="name"
                                        :options="termsOfPayment.data"
                                        moduleName="termsOfPayment"
                                        :textLabel="$t('Terms Of Payment')"
                                        @update:model-value="
                                            updatedTermsOfPayment
                                        "
                                    ></MultiSelectInput>
                                    <input
                                        v-if="form.termsOfPayment"
                                        style="
                                            width: -webkit-fill-available;
                                            align-self: end;
                                        "
                                        class="py-2 pl-1 border rounded mr-2"
                                        readonly
                                        type="text"
                                        :value="
                                            form.termsOfPayment?.paymentTerms
                                        "
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-area-input
                                    v-model="form.customNotesField"
                                    :error="errors.customNotesField"
                                    class=""
                                    :label="$t('Custom Notes Field')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-area-input
                                    v-model="form.paymentTerms"
                                    class=""
                                    :label="$t('Payment Terms')"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end mt-4">
                <router-link to="/creditor-invoices" class="primary-btn me-3">
                    <span class="mr-1">
                        <CustomIcon name="cancelIcon" />
                    </span>
                    <span>{{ $t("Cancel") }}</span>
                </router-link>
                <loading-button
                    @click="store"
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
import NumberInput from "../../Components/NumberInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import DateInput from "../../Components/DateInput.vue";
import TextAreaInput from "../../Components/TextareaInput.vue";
import Icon from "../../Components/Icon.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";

import { mapGetters } from "vuex";
import { v4 as uuid } from "uuid";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    components: {
        NumberInput,
        MultiSelectInput,
        LoadingButton,
        SelectInput,
        TextInput,
        TextAreaInput,
        Icon,
        DateInput,
        PageHeader,
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Finance"),
                    to: "/creditor-invoices",
                },
                {
                    text: this.$t("Creditor Invoices"),
                    to: "/creditor-invoices",
                },
                {
                    text: "Create",
                    active: true,
                },
            ],
            productTypeEnums: {
                software: "software",
                service: "service",
                ams: "ams",
                hosting: "hosting",
                "cloud-software": "cloudSoftware",
            },
            invoiceTypeEnums: {
                invoice: "Invoice",
                "invoice-correction": "Invoice Correction",
                "invoice-storno": "Invoice Storno",
            },
            show: true,
            disabled: false,
            form: {
                paymentTerms: "",
                receiverType: "customer",
                invoiceType: "invoice",
                dueDate: new Date(),
                startDate: new Date(),
                endDate: new Date(`${new Date().getFullYear()}-12-31`),
                invoicePeriod: "",
                paymentPeriod: "",
                termsOfPayment: "",
                customNotesField: "",
                contactPerson: "",
                draftStatusChangeDate: new Date(),
                totalAmount: 0,
                totalTaxAmount: 0,
                totalAmountWithoutTax: 0,
            },
            invoiceTax: [],
        };
    },
    async mounted() {
        await this.$store.dispatch("termsOfPayment/list", {
            perPage: 25,
            page: 1,
        });
        await this.$store.dispatch("periods/list");
    },
    computed: {
        ...mapGetters("companyEmployees", ["users"]),
        ...mapGetters("auth", ["user"]),
        ...mapGetters("companies", ["companies", "partners"]),
        ...mapGetters("units", ["units"]),
        ...mapGetters("periods", ["periods"]),
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("termsOfPayment", {
            termsOfPayment: "termsOfPayment",
        }),
        dropdownStyles() {
            return {
                "--elem-hover-bg-color": "#312E81",
                "--elem-selected-bg-color": "#312E81",
            };
        },
    },
    watch: {
        "form.draftStatusChangeDate"(val) {
            this.form.dueDate = val;
            this.addTermsOfPaymentDaysToDueDate();
        },
        "form.startDate"() {
            if (this.form.category === "maintenance")
                this.recalculateSoftwareMaintenanceTax(); // recalculate softwareMaintenanceTax
        },
        "form.endDate"() {
            if (this.form.category === "maintenance")
                this.recalculateSoftwareMaintenanceTax(); // recalculate softwareMaintenanceTax
        },
    },
    methods: {
        /**
         * coverts regular date to YYYYMMDDHHMMSS format
         * @param {date} date to be formatted
         */
        formatDateToYYYYMMDDHHMMSS(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, "0"); // Months are zero-based
            const day = String(date.getDate()).padStart(2, "0");
            const hour = String(date.getHours()).padStart(2, "0");
            const minute = String(date.getMinutes()).padStart(2, "0");
            const second = String(date.getSeconds()).padStart(2, "0");

            return `${year}${month}${day}${hour}${minute}${second}`;
        },
        async calculateBruttoTotal() {
            await this.$nextTick();
            this.form.totalAmount =
                (+this.form.totalAmountWithoutTax *
                    (100 + +this.form.totalTaxAmount)) /
                100;
        },
        /**
         * formats the option label in multiselect input
         * @param {props} the options received from the multiselect input
         * @returns the formated label
         */
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
        /**
         * triggered when a company is selected
         * fetches the systems, company employees, projects, terms of payment related to the selected company
         */
        fetchSystemsAndCompanyEmployees() {
            return new Promise(async (resolve, reject) => {
                await this.$nextTick();
                try {
                    this.form.contactPerson = ""; // reset the contact person when the customer changes
                    await this.$store.dispatch("companyEmployees/list", {
                        limit_start: 0,
                        limit_count: 25,
                        type: this.form.receiverType,
                        company_id: this.form.customers?.id,
                    });
                    this.addTermsOfPayment(this.form.customers);
                    resolve();
                } catch (e) {
                    console.log(e);
                    reject(e);
                }
            });
        },
        /**
         * fetches companies when the multiselect input is opened
         */
        async fetchCustomers() {
            return new Promise(async (resolve, reject) => {
                try {
                    if (
                        !this.companies?.data?.length &&
                        this.form.receiverType === "customer"
                    )
                        await this.$store.dispatch("companies/list", {
                            perPage: 25,
                            page: 1,
                        });
                    else if (
                        !this.partners?.data?.length &&
                        this.form.receiverType === "partner"
                    )
                        await this.$store.dispatch("companies/list", {
                            perPage: 25,
                            page: 1,
                            customerType: "partner",
                        });
                    resolve();
                } catch (e) {
                    console.log(e);
                    reject(e);
                }
            });
        },
        /**
         * when the termsOfPayment is selected, adds the noOfDays1 from termsOfPayment to
         * dueDate
         */
        async addTermsOfPaymentDaysToDueDate() {
            const noOfDays = this.form?.termsOfPayment?.noOfDays1 ?? 0;
            const newDate = new Date();
            // Add the specified number of days to the date
            newDate.setDate(newDate.getDate() + noOfDays);
            await this.$nextTick();
            this.form.dueDate = newDate;
        },
        async updatedTermsOfPayment() {
            this.addPaymentTerms();
            this.addTermsOfPaymentDaysToDueDate();
        },
        async addPaymentTerms() {
            await this.$nextTick();
            this.form.paymentTerms = this.form?.termsOfPayment?.invoiceText;
        },
        /**
         * adds terms of payment based on the customer
         * @param {customer} the customer from which the terms of payment is selected
         */
        addTermsOfPayment(customer) {
            this.form.termsOfPayment =
                this.termsOfPayment?.data?.find(
                    (terms) => terms.id == customer?.termsOfPayment
                ) ?? "";
            this.updatedTermsOfPayment();
        },
        async store() {
            await this.$nextTick();
            if (this.disabled) return;
            this.disabled = true;
            try {
                let templatePayload = "invoiceTemplateId";
                //Get invoice type
                if (this.form.invoiceType == "invoice-correction") {
                    templatePayload = "invoiceCorrectionTemplateId";
                } else if (this.form.invoiceType == "invoice-storno") {
                    templatePayload = "invoiceStornoTemplateId";
                }

                this.$store.commit("isLoading", true);
                const payload = {
                    objKeys: {
                        ACCOUNTING_STATUS: "7 - Gebucht",
                        ACCOUNTING_TYPE: null,
                        ACCOUNTING_COMPLIANT: "1",
                        ACCOUNTING_PAYED: "0",
                        COMPANY_CODE: "1000",
                        COMPANY_NAME: this.form.customers?.companyName,
                        VENDOR_NO: "100001",
                        VENDOR_NAME: "Mustergeschäftspartner",
                        VENDOR_ADDRESS_STREET: "Musterstr",
                        VENDOR_ADDRESS_ZIPCODE: "Musterplz",
                        VENDOR_ADDRESS_CITY: "Musterort",
                        VENDOR_ADDRESS_COUNTRY: "Musterland",
                        VENDOR_IBAN: "MusterIBAN3422123",
                        VENDOR_BIC: "AJDHFS",
                        VENDOR_TAX_NO: "DE672461",
                        VENDOR_VAT_ID_NO: "768246825",
                        ACCOUNTING_NUMBER: "RE762429",
                        ACCOUNTING_DATE: this.formatDateToYYYYMMDDHHMMSS(
                            this.form.draftStatusChangeDate
                        ),
                        ACCOUNTING_DUE_DATE: this.formatDateToYYYYMMDDHHMMSS(
                            this.form.dueDate
                        ),
                        PO_PURCHASE_USER: null,
                        ACCOUNTING_NET_AMOUNT: this.$formatter(
                            this.form.totalAmountWithoutTax,
                            "de",
                            "EUR",
                            true
                        ),
                        ACCOUNTING_TOTAL_AMOUNT: this.$formatter(
                            this.form.totalAmount,
                            "de",
                            "EUR",
                            true
                        ),
                        ACCOUNTING_TAX_AMOUNT: this.$formatter(
                            this.form.totalTaxAmount,
                            "de",
                            "EUR",
                            true
                        ),
                        ACCOUNTING_CURRENCY_CODE: "EUR",
                        ERP_BOOKING_DATE: this.formatDateToYYYYMMDDHHMMSS(
                            new Date()
                        ),
                        ACCOUNTING_CASH_DISCOUNT_AMOUNT: "0,00",
                        PROJECT_NO: "P654381",
                        PROJECT_NAME: "Musterprojekt",
                        ACCOUNTING_DATACOLLECTION: null,
                        ACCOUNTING_CURRENCY_EXCHANGE_RATE: null,
                        ACCOUNTING_NET_AMOUNT_LOCAL_CURR: this.$formatter(
                            this.form.totalAmountWithoutTax,
                            "de",
                            "EUR",
                            true
                        ),
                        ACCOUNTING_TOTAL_AMOUNT_LOCAL_CURR: this.$formatter(
                            this.form.totalAmount,
                            "de",
                            "EUR",
                            true
                        ),
                        ACCOUNTING_CASH_DISCOUNT_AMOUNT_LOC_CURR: "0,00",
                        ACCOUNTING_DELIVERY_DATE: null,
                        DX_STATUS: null,
                        DX_DOC_CLASS: null,
                        CURRENT_USER: null,
                        PROCESS_STATUS: null,
                        ACCOUNTING_NO_ERP: null,
                        BUSINESS_AREA_CODE: null,
                        _RESERVED: null,
                        ACCOUNTING_DATEV_FISCALYEAR:
                            this.formatDateToYYYYMMDDHHMMSS(
                                this.form.startDate
                            ),
                        ACCOUNTING_DATEV_POSTINGPROPOSAL_CREATED:
                            this.formatDateToYYYYMMDDHHMMSS(this.form.endDate),
                        DATEV_GUID: null,
                        ACCOUNTING_DATEV_INVOICE_NO: null,
                        ACCOUNTING_REQUEST_BP_WORKFLOW: null,
                        ELO_FNAME: "Test Belegmaske.txt",
                        contactPerson: this.form?.contactPerson?.id,
                        userId: this.user.id,
                        termsOfPayment: this.form?.termsOfPayment?.id,
                        invoicePeriod: this.form?.invoicePeriod?.id,
                        receiverType: this.form.receiverType,
                        customNotesField: this.form.customNotesField,
                        paymentTerms: this.form.paymentTerms,
                        invoiceType: this.form.invoiceType,
                    },
                    mapKeys: {
                        GL_ACCOUNT: ["1000", "2000", "3000"],
                        RANDOM: "abcdefg",
                        MAP_FELD_INFO_TOLL: "Fantastisch",
                    },
                    objId: 16522,
                    GUID: "(3C9392F4-FB26-BCE1-B205-85864B66B90A)",
                };
                await this.$store.dispatch("creditorInvoices/create", payload);
                await this.$store.dispatch("creditorInvoices/list");
                this.$router.push("/creditor-invoices");
            } catch (e) {
                console.log(e);
                this.disabled = false;
            }
        },
    },
};
</script>

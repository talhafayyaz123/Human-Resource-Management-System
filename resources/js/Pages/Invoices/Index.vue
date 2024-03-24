<template>
    <div>
        <PageHeader
            :items="breadcrumbItems"
            :optionalItems="optionalItems"
            @csvBtn1="downloadCSV"
            @csvBtn2="downloadLatestCsv"
        />

        <div class="flex items-center justify-between mb-6">
            <div class="flex">
                <!-- sends emails to all the selected invoices -->
                <loading-button
                    :disabled="!isAnyChecked"
                    class="secondary-btn self-center ml-2"
                    :loading="processingAll"
                    @click="sendAllSelected"
                >
                    {{ $t("Send All Selected") }}
                </loading-button>
            </div>
            <search-filter
                v-model="form.search"
                class="mr-4 w-full max-w-md"
                @reset="reset"
            >
                <div class="form-group">
                    <select-input
                        v-model="form.type"
                        :label="$t('Invoice Type')"
                    >
                        <option
                            v-for="type in invoiceTypes"
                            :key="type"
                            :value="type"
                        >
                            {{ type }}
                        </option>
                    </select-input>
                </div>
                <div class="form-group my-[20px]">
                    <select-input
                        v-model="form.status"
                        :label="$t('Invoice Status')"
                    >
                        <option
                            v-for="status in [
                                'draft',
                                'approved',
                                'sent',
                                'warning level 1',
                                'warning level 2',
                                'warning level 3',
                                'paid',
                            ]"
                            :key="status"
                            :value="status"
                        >
                            {{ status }}
                        </option>
                    </select-input>
                </div>
                <div class="form-group">
                    <MultiSelectInput
                        v-model="form.company"
                        :options="companies"
                        :multiple="false"
                        :textLabel="$t('Customer')"
                        label="companyName"
                        trackBy="id"
                        moduleName="companies"
                        @asyncSearch="companiesSearch"
                    />
                </div>
            </search-filter>
        </div>
        <div class="table-responsive">
            <table class="w-full doc-table">
                <tr class="text-left font-bold">
                    <th
                        @click="sort('invoiceNumber', 'invoices')"
                        class="cursor-pointer flex items-center"
                    >
                        <!-- checks all the invoices that have checkboxes -->
                        <input
                            :checked="areAllChecked"
                            @click.stop="selectAll"
                            class="mr-5"
                            type="checkbox"
                        />
                        {{ $t("Invoice number")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'invoiceNumber'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('Company.companyName', 'invoices')"
                        class="cursor-pointer"
                    >
                        {{ $t("Company")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'Company.companyName'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('invoiceType', 'invoices')"
                        class="cursor-pointer"
                    >
                        {{ $t("Invoice type")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'invoiceType'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('invoiceCategory', 'invoices')"
                        class="cursor-pointer"
                    >
                        {{ $t("Invoice Category")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'invoiceCategory'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('invoicePeriod', 'invoices')"
                        class="cursor-pointer"
                    >
                        {{ $t("Invoice Period")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'invoicePeriod'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('dueDate', 'invoices')"
                        class="cursor-pointer"
                    >
                        {{ $t("Due Date")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'dueDate'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('status', 'invoices')"
                        class="cursor-pointer"
                    >
                        {{ $t("Status")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'status'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="
                            sort('totalAmountWithoutTax_numeric', 'invoices')
                        "
                        class="cursor-pointer"
                    >
                        {{ $t("Netto")
                        }}<font-awesome-icon
                            v-if="
                                form.sortBy === 'totalAmountWithoutTax_numeric'
                            "
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('createdAt', 'invoices')"
                        class="cursor-pointer"
                    >
                        {{ $t("Created At")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'createdAt'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th class="">{{ $t("Action") }}</th>
                </tr>
                <tr
                    v-for="invoice in invoices.data"
                    :key="invoice.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                    @contextmenu.stop.prevent="
                        (e) => {
                            e.preventDefault();
                            let route = $router.resolve(
                                `/invoices/${invoice.id}/edit?page=${page}`
                            );
                            window.open(route.href, '_blank');
                        }
                    "
                >
                    <td class="">
                        <!-- selectes the invoice for mail sending -->
                        <div class="flex items-center gap-3">
                            <input
                                @click.stop=""
                                v-if="
                                    !!invoice.invoiceEmail &&
                                    invoice.status === 'approved'
                                "
                                v-model="selectedInvoices[invoice.id]"
                                :checked="!!selectedInvoices[invoice.id]"
                                type="checkbox"
                            />
                            <a class="flex items-center cursor-pointer">
                                {{ invoice.invoiceNumber }}
                            </a>
                        </div>
                    </td>
                    <td class="">
                        <a class="flex items-center cursor-pointer">
                            {{ invoice.company }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            class="flex items-center cursor-pointer"
                            tabindex="-1"
                        >
                            {{ invoice.invoiceType }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            class="flex items-center cursor-pointer"
                            tabindex="-1"
                        >
                            {{ invoice.category }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            class="flex items-center cursor-pointer"
                            tabindex="-1"
                        >
                            {{ invoice.invoicePeriod }} {{ $t("Day(s)") }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            class="flex items-center cursor-pointer"
                            tabindex="-1"
                        >
                            {{
                                $dateFormatter(invoice.dueDate, globalLanguage)
                            }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            class="flex items-center cursor-pointer"
                            tabindex="-1"
                        >
                            {{ invoice.status }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            class="flex items-center cursor-pointer"
                            tabindex="-1"
                        >
                            {{ $formatter(invoice.netto, globalLanguage) }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            class="flex items-center cursor-pointer"
                            tabindex="-1"
                        >
                            {{
                                $dateFormatter(
                                    invoice.createdAt,
                                    globalLanguage
                                )
                            }}
                        </a>
                    </td>
                    <td class="w-px text-center">
                        <div class="flex items-center gap-x-2">
                            <font-awesome-icon
                                v-if="
                                    invoice.status === 'approved' ||
                                    invoice.status === 'sent'
                                "
                                :title="$t('Generate Email')"
                                :class="[
                                    'cursor-pointer',
                                    invoice.status === 'sent'
                                        ? 'text-red-500'
                                        : '',
                                ]"
                                @click.stop="openMailModal(invoice)"
                                icon="fa-envelope"
                            />
                            <font-awesome-icon
                                :title="$t('Generate Document')"
                                class="cursor-pointer"
                                @click.stop="generateDocument(invoice.id)"
                                icon="fa-print"
                            />
                            <router-link
                                @click.stop=""
                                v-if="$can(`${$route.meta.permission}.show`)"
                                :to="`/invoices/${invoice.id}?page=${page}`"
                            >
                                <font-awesome-icon icon="fa-solid fa-eye" />
                            </router-link>
                            <router-link
                                @click.stop=""
                                v-if="$can(`${$route.meta.permission}.edit`)"
                                :to="`/invoices/${invoice.id}/edit?page=${page}`"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-pen-to-square"
                                />
                            </router-link>
                            <button
                                v-if="
                                    $can(`${$route.meta.permission}.delete`) &&
                                    invoice.status === 'draft'
                                "
                                @click.stop="destroy(invoice.id)"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-trash-can"
                                />
                            </button>
                        </div>
                    </td>
                </tr>
                <tr v-if="invoices?.data?.length === 0">
                    <td class="px-6 py-4" colspan="9">
                        {{ $t("No invoices found") }}.
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="invoices"
                @pagination-change-page="changePageOrSearch"
            ></pagination>
            <br />
            <br />
        </div>
    </div>
    <MailModal
        :toggleModal="toggleModal"
        @refresh="refresh"
        v-if="toggleModal"
        @toggle-modal="toggleModal = $event"
        :invoice="invoice"
        :performanceRecord="performanceRecord"
        :invoiceData="invoiceData"
        :invoiceTemplate="
            invoiceData?.invoiceType === 'invoice'
                ? invoiceTemplate
                : invoiceData?.invoiceType === 'invoice-correction'
                ? invoiceCorrectionTemplate
                : invoiceStornoTemplate
        "
        :processing="processing"
        :mergedPdf="mergedPdf"
    />
    <Modal
        :title="$t('Do you really want to resend the invoice?')"
        v-if="togglePromptModal"
        @toggleModal="togglePromptModal = false"
        width="50%"
    >
        <template #footer>
            <loading-button
                style="cursor: pointer"
                class="secondary-btn mx-2"
                @click="
                    togglePromptModal = false;
                    toggleMailModal(invoiceData);
                "
            >
                {{ $t("Send Mail") }}
            </loading-button>
            <button
                @click="
                    togglePromptModal = false;
                    invoiceData = {};
                "
                type="button"
                class="primary-btn mr-3"
            >
                {{ $t("Cancel") }}
            </button>
        </template>
    </Modal>
</template>

<script>
import Icon from "@/Components/Icon.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import Pagination from "laravel-vue-pagination";
import SearchFilter from "@/Components/SearchFilter.vue";
import { mapGetters } from "vuex";
import SelectInput from "@/Components/SelectInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import MailModal from "./MailModal.vue";
import Modal from "@/Components/EditModal.vue";
import PDFMerger from "pdf-merger-js/browser";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { debounce } from "@/utils/debounce";
export default {
    computed: {
        ...mapGetters("invoices", ["invoices", "invoiceTypes"]),
        // returns true if any invoice in the selectedInvoices object has true value
        isAnyChecked() {
            return Object.values(this.selectedInvoices).includes(true);
        },
        // returns true if all the invoices in the selectedInvoices object have true values
        areAllChecked() {
            return (
                Object.values(this.selectedInvoices).length > 0 &&
                Object.values(this.selectedInvoices).length ===
                    Object.values(this.selectedInvoices).filter(
                        (item) => !!item
                    ).length
            );
        },
    },
    components: {
        LoadingButton,
        Icon,
        Pagination,
        SearchFilter,
        MultiSelectInput,
        MailModal,
        Modal,
        PageHeader,
        SelectInput,
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
                    to: "/invoices",
                },
                {
                    text: this.$t("Invoices"),
                    active: true,
                },
            ],
            optionalItems: {
                csvBtn1Show: true,
                csvBtn1Text: this.$t("Export CSV"),
                csvBtn2Show: true,
                csvBtn2Text: this.$t("Export Latest CSV"),
                isBtn2Show: true,
                btn2Text: this.$t("Create Invoice"),
                btn2Path: "/invoices/create",
            },
            togglePromptModal: false, // toggles confirmation modal
            processingAll: false, // toggles the load functionality in the 'send all selected' mail button
            selectedInvoices: {}, // keeps track of all the selected invoices
            processing: false,
            page: 1,
            companies: [],
            window,
            toggleModal: false,
            form: {
                company: "",
                type: "",
                search: "",
                status: "",
                sortBy:
                    localStorage.getItem("sort_invoices_column") ??
                    "invoiceNumber",
                sortOrder: localStorage.getItem("sort_invoices_order") ?? "asc",
            },
            invoiceTemplate: "", // invoice mail template assignment id
            invoiceCorrectionTemplate: "", // invoice correction mail template assignment id
            invoiceStornoTemplate: "", // invoice storno mail template assignment id
            // invoice file details for sending mail
            invoice: {
                name: "",
                base64: "",
            },
            // performanceRecord file details for sending mail
            performanceRecord: {
                name: "",
                base64: "",
            },
            mergedPdf: {
                name: "",
                base64: "",
            },
            // invoice detials for sending mail
            invoiceData: {},
        };
    },
    mounted() {
        // get companies list
        this.$store
            .dispatch("companies/list", {
                page: 1,
                perPage: 25,
            })
            .then((res) => {
                this.companies = res?.data?.data ?? [];
            });
        // get, set the invoices listing
        this.refresh();
        // get the mail template assignment id for the invoice module
        this.getTemplateByModule();
    },
    watch: {
        toggleModal(val) {
            // if modal is toggled off, then reset the values send though props
            if (!val) {
                this.invoice = {
                    name: "",
                    base64: "",
                };
                this.performanceRecord = {
                    name: "",
                    base64: "",
                };
                this.invoiceData = {};
            }
        },
        "form.search"(...val) {
            this.debouncedFetch(...val);
        },
        "form.company"(...val) {
            this.debouncedFetch(...val);
        },
        "form.status"(...val) {
            this.debouncedFetch(...val);
        },
        "form.type"(...val) {
            this.debouncedFetch(...val);
        },
        "form.sortBy"(...val) {
            this.debouncedFetch(...val);
        },
        "form.sortOrder"(...val) {
            this.debouncedFetch(...val);
        },
    },
    created() {
        this.debouncedFetch = debounce(async (newValue, oldValue) => {
            try {
                await this.$store.dispatch("invoices/list", {
                    ...this.form,
                    customerId: this.form.company?.id,
                });
            } catch (e) {}
        }, 300);
    },
    methods: {
        /**
         * opens the confirmation modal if invoice status is 'sent'
         * otherwise it just opens the mail sending modal
         **/
        openMailModal(invoiceData) {
            this.invoiceData = invoiceData; //set invoiceData prop value
            if (this.invoiceData?.status === "sent") {
                this.togglePromptModal = true;
            } else if (this.invoiceData?.status === "approved") {
                this.toggleMailModal(invoiceData);
            }
        },
        /**
         * sends mail for all the selected invoices
         **/
        async sendAllSelected() {
            try {
                this.processingAll = true;
                const selectedInvoices = this.invoices.data.filter(
                    (invoice) => !!this.selectedInvoices[invoice.id]
                );
                for (let i = 0; i < selectedInvoices.length; i++) {
                    const response = await this.$store.dispatch(
                        "invoices/downloadGeneratedInvoice",
                        selectedInvoices[i].id
                    );
                    const invoice = response?.data?.invoice;
                    let mergedFilename = "";
                    const performanceRecord = response?.data?.performanceRecord;
                    const invoiceTemplateId = response?.data?.invoiceTemplateId;
                    const perfRecordTemplateId =
                        response?.data?.perfRecordTemplateId;
                    let invoiceMailData = {};
                    let performanceRecordMailData = {};
                    // if there is invoice details, then generate the document
                    if (invoice) {
                        const filename =
                            "invoice-" +
                            (invoice.invoiceNumber == null
                                ? "draft"
                                : invoice.invoiceNumber) +
                            ".pdf";
                        mergedFilename = filename;
                        const invoiceResponse = await this.$store.dispatch(
                            "documentService/processTemplateBase64",
                            {
                                data: { ...invoice, id: invoiceTemplateId },
                                filename: filename,
                                documentType: "pdf",
                            }
                        );
                        // generate and set base64 and filename for invoice prop
                        await new Promise((resolve, reject) => {
                            var reader = new FileReader();
                            reader.readAsDataURL(invoiceResponse);
                            reader.onerror = (err) => {
                                reject(err);
                            };
                            reader.onloadend = () => {
                                invoiceMailData = {
                                    name: filename,
                                    base64: reader.result,
                                };
                                resolve();
                            };
                        });
                    }
                    // if there is performanceRecord details, then generate the document
                    if (performanceRecord) {
                        const filename =
                            "performance-records-" +
                            (performanceRecord.performanceNumber == null
                                ? "draft"
                                : performanceRecord.performanceNumber) +
                            ".pdf";
                        const performanceRecordResponse =
                            await this.$store.dispatch(
                                "documentService/processTemplateBase64",
                                {
                                    data: {
                                        ...performanceRecord,
                                        id: perfRecordTemplateId,
                                    },
                                    filename: filename,
                                    documentType: "pdf",
                                }
                            );
                        // generate and set base64 and filename for invoice prop
                        await new Promise((resolve, reject) => {
                            var reader = new FileReader();
                            reader.readAsDataURL(performanceRecordResponse);
                            reader.onerror = (err) => {
                                reject(err);
                            };
                            reader.onloadend = () => {
                                performanceRecordMailData = {
                                    name: filename,
                                    base64: reader.result,
                                };
                                resolve();
                            };
                        });
                    }
                    let files = []; // contains invoice, performance record names and base64
                    // if invoice is prop is set then push the relevant info to files
                    if (invoiceMailData.name) {
                        files.push({
                            name: invoiceMailData.name,
                            base64_content: invoiceMailData.base64,
                        });
                    }
                    // if performanceRecord is prop is set then push the relevant info to files
                    if (performanceRecordMailData.name) {
                        files.push({
                            name: performanceRecordMailData.name,
                            base64_content: performanceRecordMailData.base64,
                        });
                    }
                    // create the payload for sending mail
                    const invoiceTemplate =
                        invoice?.invoiceType === "invoice"
                            ? this.invoiceTemplate
                            : invoice?.invoiceType === "invoice-correction"
                            ? this.invoiceCorrectionTemplate
                            : this.invoiceStornoTemplate;
                    const payload = {
                        data: selectedInvoices[i],
                        id: invoiceTemplate?.mailTemplateId,
                        mails: selectedInvoices[i]?.invoiceEmail
                            ? [selectedInvoices[i].invoiceEmail]
                            : [],
                        files: files,
                        from: invoiceTemplate?.senderMail,
                        cc: invoiceTemplate?.cc ? [invoiceTemplate?.cc] : [],
                        bcc: invoiceTemplate?.bcc ? [invoiceTemplate?.bcc] : [],
                    };
                    // send the mail
                    await this.$store.dispatch(
                        "mailTemplates/sendMailTemplate",
                        payload
                    );
                    // update invoice status to 'sent'
                    await this.$store.dispatch("invoices/updateStatus", {
                        id: selectedInvoices[i].id,
                        data: {
                            status: "sent",
                        },
                    });
                }
                this.selectedInvoices = {}; // reset the selected invoices
            } catch (e) {
                console.log(e);
            } finally {
                this.processingAll = false; // stop the loading button
                this.refresh(); // refetch the listing
            }
        },
        selectAll(event) {
            this.invoices.data.forEach((invoice) => {
                if (invoice.status === "approved" && !!invoice.invoiceEmail) {
                    this.selectedInvoices[invoice.id] = event.target.checked;
                }
            });
        },
        /**
         * gets the invoice and performance record process template payloads and then gets the blobs by hitting the process template API
         * and converts that blob to base64 and sends as props to the MailModal and toggles the MailModal
         * @param {invoiceData} invoice information
         **/
        async toggleMailModal(invoiceData) {
            // toggle the MailModal
            this.toggleModal = true;
            try {
                this.processing = true;
                // get the invoice, performanceRecord details
                const response = await this.$store.dispatch(
                    "invoices/downloadGeneratedInvoice",
                    invoiceData.id
                );
                const invoice = response?.data?.invoice;
                let mergedFilename = "";
                const performanceRecord = response?.data?.performanceRecord;
                const invoiceTemplateId = response?.data?.invoiceTemplateId;
                const perfRecordTemplateId =
                    response?.data?.perfRecordTemplateId;
                const merger = new PDFMerger();
                // if there is invoice details, then generate the document
                if (invoice) {
                    const filename =
                        "invoice-" +
                        (invoice.invoiceNumber == null
                            ? "draft"
                            : invoice.invoiceNumber) +
                        ".pdf";
                    mergedFilename = filename;
                    const invoiceResponse = await this.$store.dispatch(
                        "documentService/processTemplateBase64",
                        {
                            data: { ...invoice, id: invoiceTemplateId },
                            filename: filename,
                            documentType: "pdf",
                        }
                    );
                    await merger.add(invoiceResponse);
                    // generate and set base64 and filename for invoice prop
                    var reader = new FileReader();
                    reader.readAsDataURL(invoiceResponse);
                    reader.onloadend = () => {
                        this.invoice = {
                            name: filename,
                            base64: reader.result,
                        };
                    };
                }
                // if there is performanceRecord details, then generate the document
                if (performanceRecord) {
                    const filename =
                        "performance-records-" +
                        (performanceRecord.performanceNumber == null
                            ? "draft"
                            : performanceRecord.performanceNumber) +
                        ".pdf";
                    const performanceRecordResponse =
                        await this.$store.dispatch(
                            "documentService/processTemplateBase64",
                            {
                                data: {
                                    ...performanceRecord,
                                    id: perfRecordTemplateId,
                                },
                                filename: filename,
                                documentType: "pdf",
                            }
                        );
                    await merger.add(performanceRecordResponse);
                    // generate and set base64 and filename for invoice prop
                    var reader = new FileReader();
                    reader.readAsDataURL(performanceRecordResponse);
                    reader.onloadend = () => {
                        this.performanceRecord = {
                            name: filename,
                            base64: reader.result,
                        };
                    };
                }
                const mergedPdf = await merger.saveAsBlob();
                var mergedReader = new FileReader();
                mergedReader.readAsDataURL(mergedPdf);
                mergedReader.onloadend = () => {
                    this.mergedPdf = {
                        name: mergedFilename,
                        base64: mergedReader.result,
                    };
                };
            } catch (e) {
                console.log(e);
            } finally {
                this.processing = false;
            }
        },
        companiesSearch(data) {
            this.companies = data?.data;
        },
        /**
         * get the mail template assignment id for the invoice module
         **/
        async getTemplateByModule() {
            try {
                let response = await this.$store.dispatch(
                    "mailTemplates/mailTemplateByModule",
                    {
                        module: "invoiceTemplate",
                    }
                );
                this.invoiceTemplate = response?.data?.data ?? "";
                response = await this.$store.dispatch(
                    "mailTemplates/mailTemplateByModule",
                    {
                        module: "invoiceCorrectionTemplate",
                    }
                );
                this.invoiceCorrectionTemplate = response?.data?.data ?? "";
                response = await this.$store.dispatch(
                    "mailTemplates/mailTemplateByModule",
                    {
                        module: "invoiceStornoTemplate",
                    }
                );
                this.invoiceStornoTemplate = response?.data?.data ?? "";
            } catch (e) {
                console.log(e);
            }
        },
        /**
         *  fetched the invoice and performance record details and the template id for each module and generates the document
         *  and downloads the files
         *  @param {id} id of the invoice
         **/
        async generateDocument(id) {
            try {
                this.$store.commit("showLoader", true);
                // get the invoice, performanceRecord details
                const response = await this.$store.dispatch(
                    "invoices/downloadGeneratedInvoice",
                    id
                );
                const invoice = response?.data?.invoice;
                const performanceRecord = response?.data?.performanceRecord;
                const invoiceTemplateId = response?.data?.invoiceTemplateId;
                const perfRecordTemplateId =
                    response?.data?.perfRecordTemplateId;
                // if there is invoice details, then generate the document
                if (invoice) {
                    const filename =
                        "invoice-" +
                        (invoice.invoiceNumber == null
                            ? "draft"
                            : invoice.invoiceNumber) +
                        ".pdf";
                    const invoiceBlob = await this.$store.dispatch(
                        "documentService/processTemplate",
                        {
                            data: { ...invoice, id: invoiceTemplateId },
                            filename: filename,
                            documentType: "pdf",
                        }
                    );
                    // check if the response of the process template is of type Blob
                    if (invoiceBlob instanceof Blob) {
                        // convert blob to base64
                        const base64 = await this.convertBlobToBase64(
                            invoiceBlob
                        );
                        const eloRequestDataResponse =
                            await this.$store.dispatch(
                                "invoices/eloRequestData",
                                invoice.id
                            );

                        // send the elo request
                        await this.$store.dispatch(
                            "globalSettings/sendEloApiRequest",
                            {
                                content: {
                                    moduleAction: "updatePDFInvoice",
                                    data: {
                                        ...(eloRequestDataResponse?.data
                                            ?.data ?? {}),
                                        base64:
                                            (base64 ?? "").split(
                                                ";base64,"
                                            )?.[1] ?? "",
                                    },
                                },
                            }
                        );
                    }
                }
                // if there is performanceRecord details, then generate the document
                if (performanceRecord) {
                    const filename =
                        "performance-records-" +
                        (performanceRecord.performanceNumber == null
                            ? "draft"
                            : performanceRecord.performanceNumber) +
                        ".pdf";
                    const performanceRecordBlob = await this.$store.dispatch(
                        "documentService/processTemplate",
                        {
                            data: {
                                ...performanceRecord,
                                id: perfRecordTemplateId,
                            },
                            filename: filename,
                            documentType: "pdf",
                        }
                    );
                    // check if the response of the process template is of type Blob
                    if (performanceRecordBlob instanceof Blob) {
                        // convert blob to base64
                        const base64 = await this.convertBlobToBase64(
                            performanceRecordBlob
                        );
                        // send the elo request
                        await this.$store.dispatch(
                            "globalSettings/sendEloApiRequest",
                            {
                                content: {
                                    moduleAction: "updatePDFPerformanceRecord",
                                    data: {
                                        ...performanceRecord,
                                        id: performanceRecord.id,
                                        base64:
                                            (base64 ?? "").split(
                                                ";base64,"
                                            )?.[1] ?? "",
                                    },
                                },
                            }
                        );
                    }
                }
            } catch (e) {
                console.log(e);
            } finally {
                this.$store.commit("showLoader", false);
            }
        },
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("invoices/list", {
                page: this.page,
                search: this.form.search,
                sortBy: this.form.sortBy,
                sortOrder: this.form.sortOrder,
            });
        },
        async downloadCSV() {
            try {
                await this.$store.dispatch("invoices/download");
            } catch (e) {}
        },
        async downloadLatestCSV() {
            try {
                await this.$store.dispatch("invoices/downloadLatestCSV");
            } catch (e) {}
        },
        async destroy(id) {
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
                    await this.$store.dispatch("invoices/destroy", id);
                    this.refresh();
                }
            });
        },
        async refresh() {
            if (this.$route.query.page) {
                this.page = this.$route.query.page;
                this.$router.replace({ query: null });
            }
            await this.$store.dispatch("invoices/list", {
                ...this.pickBy(this.form),
                page: this.page,
            });
        },
        reset() {
            this.form = this.mapValues(this.form, () => null);
        },
    },
};
</script>

<style scoped>
:deep(.page-item.active .page-link) {
    background-color: #2957a4;
    border-color: #2957a4;
}

:deep(.page-link) {
    color: #2957a4;
}
</style>

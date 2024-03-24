<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <div class="flex items-center justify-between mb-6">
            <!-- sends emails to all the selected invoices -->
            <loading-button
                :disabled="!isAnyChecked"
                class="secondary-btn self-center ml-2"
                :loading="processingAll"
                @click="sendAllSelected"
            >
                {{ $t("Send All Selected") }}
            </loading-button>
            <search-filter
                v-model="form.search"
                class="mr-4 w-full max-w-md"
                @reset="reset"
            >
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
            </search-filter>
        </div>
        <div class="table-responsive">
            <table class="w-full doc-table">
                <tr class="text-left font-bold">
                    <th
                        @click="sort('invoiceNumber', 'openposts')"
                        class="pb-4 pt-6 px-6 cursor-pointer flex"
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
                        @click="sort('createdAt', 'openposts')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
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
                    <th
                        @click="sort('dueDate', 'openposts')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
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
                        @click="sort('invoiceCategory', 'openposts')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
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
                        @click="sort('Company.companyName', 'openposts')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
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
                        @click="sort('status', 'openposts')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Reminder Level")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'status'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('reminderSent', 'openposts')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Reminder Sent")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'reminderSent'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('reminderStopUntil', 'openposts')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Stop Date")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'reminderStopUntil'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>

                    <th
                        @click="sort('totalAmountWithoutTax', 'openposts')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Netto")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'totalAmountWithoutTax'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>

                    <th class="pb-4 pt-6 px-6">{{ $t("Action") }}</th>
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
                    <td class="border-t">
                        <!-- selectes the invoice for mail sending -->
                        <div class="px-6 flex">
                            <input
                                @click.stop=""
                                v-if="
                                    !!invoice.invoiceEmail &&
                                    !!invoice.reminderLevelId
                                "
                                v-model="selectedInvoices[invoice.id]"
                                :checked="!!selectedInvoices[invoice.id]"
                                type="checkbox"
                            />
                            <a
                                class="flex items-center px-6 py-4 focus:text-indigo-500"
                            >
                                {{ invoice.invoiceNumber }}
                            </a>
                        </div>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
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
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
                            tabindex="-1"
                        >
                            {{
                                $dateFormatter(invoice.dueDate, globalLanguage)
                            }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4 focus:text-indigo-500"
                        >
                            {{ invoice.category }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4 focus:text-indigo-500"
                        >
                            {{ invoice.company }}
                        </a>
                    </td>

                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
                            tabindex="-1"
                        >
                            {{ invoice.reminderLevel }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
                            tabindex="-1"
                            :class="
                                invoice.reminderSent ? 'text-green-800' : ''
                            "
                        >
                            {{ invoice.reminderSent ? "Yes" : "No" }}
                        </a>
                    </td>

                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
                            tabindex="-1"
                        >
                            {{
                                $dateFormatter(
                                    invoice.reminderStopUntil,
                                    globalLanguage
                                )
                            }}
                        </a>
                    </td>

                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
                            tabindex="-1"
                        >
                            {{ $formatter(invoice.netto, globalLanguage) }}
                        </a>
                    </td>

                    <td class="w-px border-t">
                        <div class="flex items-center gap-2">
                            <font-awesome-icon
                                :title="$t('Generate Email')"
                                class="cursor-pointer"
                                :class="
                                    invoice.reminderLevel ? 'text-red-500' : ''
                                "
                                @click.stop="toggleMailModal(invoice)"
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
                                v-if="$can(`${$route.meta.permission}.edit`)"
                                :to="`/invoices/${invoice.id}/edit?page=${page}`"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-pen-to-square"
                                />
                            </router-link>
                            <router-link
                                @click.stop=""
                                v-if="$can(`${$route.meta.permission}.edit`)"
                                :to="`/open-posts/${invoice.id}/edit?page=${page}`"
                            >
                                <font-awesome-icon
                                    icon="fa fa-exclamation-triangle"
                                    aria-hidden="true"
                                ></font-awesome-icon>
                            </router-link>
                        </div>
                    </td>
                </tr>
                <tr v-if="invoices?.data?.length === 0">
                    <td class="px-6 py-4 border-t" colspan="9">
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
        v-if="toggleModal"
        @toggle-modal="toggleModal = $event"
        :invoice="invoice"
        :invoiceData="invoiceData"
        :invoiceTemplate="mailTemplate"
        :processing="processing"
        :fromOpenPost="true"
        @openPostReminder="refresh"
    />
</template>

<script>
import Icon from "../../Components/Icon.vue";

import Pagination from "laravel-vue-pagination";
import SearchFilter from "../../Components/SearchFilter.vue";
import { mapGetters } from "vuex";

import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import MailModal from "@/Pages/Invoices/MailModal.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { debounce } from "../../utils/debounce";
export default {
    computed: {
        ...mapGetters("openposts", ["invoices", "invoiceTypes"]),
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
        Icon,
        Pagination,
        SearchFilter,
        MultiSelectInput,
        MailModal,
        LoadingButton,
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
                    text: this.$t("Open Posts"),
                    active: true,
                },
            ],
            window,
            processingAll: false, // toggles the load functionality in the 'send all selected' mail button
            selectedInvoices: {}, // keeps track of all the selected invoices
            mailTemplate: "",
            page: 1,
            companies: [],
            form: {
                company: "",
                type: "",
                search: "",
                status: "",
                sortBy:
                    localStorage.getItem("sort_openposts_column") ??
                    "invoiceNumber",
                sortOrder:
                    localStorage.getItem("sort_openposts_order") ?? "asc",
            },
            // invoice file details for sending mail
            invoice: {
                name: "",
                base64: "",
            },
            // invoice detials for sending mail
            invoiceData: {},
            processing: false,
            toggleModal: false,
        };
    },
    mounted() {
        if (this.$route.query.page) {
            this.page = this.$route.query.page;
            this.$router.replace({ query: null });
        }
        this.refresh();
    },
    watch: {
        "form.search"(...val) {
            this.debouncedFetch(...val);
        },
        "form.company"(...val) {
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
                await this.$store.dispatch("openposts/list", {
                    ...this.form,
                    customerId: this.form.company?.id,
                });
            } catch (e) {}
        }, 300);
    },
    methods: {
        selectAll(event) {
            this.invoices.data.forEach((invoice) => {
                if (!!invoice.invoiceEmail && !!invoice.reminderLevelId) {
                    this.selectedInvoices[invoice.id] = event.target.checked;
                }
            });
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
                    // get the invoice, performanceRecord details
                    const response = await this.$store.dispatch(
                        "invoices/downloadGeneratedInvoice",
                        selectedInvoices[i].id
                    );
                    const invoice = response?.data?.invoice;
                    let warningLevelResponse = "";
                    let warningLevel = {};
                    let invoiceMailData = {};
                    let templateId = "";
                    if (!!invoice?.reminderLevelId) {
                        warningLevelResponse = await this.$store.dispatch(
                            "invoicereminder/show",
                            invoice?.reminderLevelId
                        );
                    } else {
                        warningLevelResponse = await this.$store.dispatch(
                            "invoicereminder/showByLevelName",
                            {
                                levelName: invoice?.invoiceStatus,
                            }
                        );
                    }
                    warningLevel = warningLevelResponse?.data?.data ?? {};
                    templateId =
                        warningLevelResponse?.data?.data?.documentTemplateId ??
                        "";
                    let mailTemplateResponse = await this.$store.dispatch(
                        "mailTemplates/mailTemplateByModule",
                        {
                            module: warningLevel?.levelName,
                        }
                    );
                    this.mailTemplate = mailTemplateResponse?.data?.data ?? "";
                    // if there is invoice details, then generate the document
                    if (invoice) {
                        const filename =
                            "invoice-reminder-" +
                            (invoice.invoiceNumber == null
                                ? "draft"
                                : invoice.invoiceNumber) +
                            ".pdf";
                        const invoiceResponse = await this.$store.dispatch(
                            "documentService/processTemplateBase64",
                            {
                                data: {
                                    ...invoice,
                                    id: templateId,
                                    ...warningLevel,
                                },
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
                    let files = []; // contains invoice, performance record names and base64
                    // if invoice is prop is set then push the relevant info to files
                    if (invoiceMailData.name) {
                        files.push({
                            name: invoiceMailData.name,
                            base64_content: invoiceMailData.base64,
                        });
                    }
                    const payload = {
                        data: selectedInvoices[i],
                        id: this.mailTemplate?.mailTemplateId,
                        mails: selectedInvoices[i]?.invoiceEmail
                            ? [selectedInvoices[i].invoiceEmail]
                            : [],
                        files: files,
                        from: this.mailTemplate?.senderMail,
                        cc: this.mailTemplate?.cc
                            ? [this.mailTemplate?.cc]
                            : [],
                        bcc: this.mailTemplate?.bcc
                            ? [this.mailTemplate?.bcc]
                            : [],
                    };
                    // send the mail
                    await this.$store.dispatch(
                        "mailTemplates/sendMailTemplate",
                        payload
                    );
                }
                this.selectedInvoices = {}; // reset the selected invoices
            } catch (e) {
                console.log(e);
            } finally {
                this.processingAll = false; // stop the loading button
                this.refresh(); // refetch the listing
            }
        },
        /**
         * gets the invoice process template payloads and then gets the blobs by hitting the process template API
         * and converts that blob to base64 and sends as props to the MailModal and toggles the MailModal
         * @param {invoiceData} the details of the selected invoice
         **/
        async toggleMailModal(invoiceData) {
            if (invoiceData.reminderSent) {
                const result = await this.$swal({
                    title: this.$t(
                        "Do you really want to send the reminder again?"
                    ),
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: this.$t("Yes"),
                    cancelButtonText: this.$t("No"),
                    showCloseButton: true,
                    showLoaderOnConfirm: true,
                });
                if (!result.isConfirmed) {
                    // User clicked 'No' or closed the modal, stop execution
                    return;
                }
            }

            this.invoiceData = invoiceData; //set invoiceData prop value
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
                let warningLevelResponse = "";
                let warningLevel = {};
                let templateId = "";
                if (!!invoice?.reminderLevelId) {
                    warningLevelResponse = await this.$store.dispatch(
                        "invoicereminder/show",
                        invoice?.reminderLevelId
                    );
                } else {
                    warningLevelResponse = await this.$store.dispatch(
                        "invoicereminder/showByLevelName",
                        {
                            levelName: invoice?.invoiceStatus,
                        }
                    );
                }
                warningLevel = warningLevelResponse?.data?.data ?? {};
                templateId =
                    warningLevelResponse?.data?.data?.documentTemplateId ?? "";
                let mailTemplateResponse = await this.$store.dispatch(
                    "mailTemplates/mailTemplateByModule",
                    {
                        module: warningLevel?.levelName,
                    }
                );
                this.mailTemplate = mailTemplateResponse?.data?.data ?? "";
                // if there is invoice details, then generate the document
                if (invoice) {
                    const filename =
                        "invoice-reminder-" +
                        (invoice.invoiceNumber == null
                            ? "draft"
                            : invoice.invoiceNumber) +
                        ".pdf";
                    const invoiceResponse = await this.$store.dispatch(
                        "documentService/processTemplateBase64",
                        {
                            data: {
                                ...invoice,
                                id: templateId,
                                ...warningLevel,
                            },
                            filename: filename,
                            documentType: "pdf",
                        }
                    );
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
            } catch (e) {
                console.log(e);
            } finally {
                this.processing = false;
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
                // get the invoice details
                const response = await this.$store.dispatch(
                    "invoices/downloadGeneratedInvoice",
                    id
                );
                const invoice = response?.data?.invoice;
                let warningLevelResponse = "";
                let warningLevel = {};
                let templateId = "";
                if (!!invoice?.reminderLevelId) {
                    warningLevelResponse = await this.$store.dispatch(
                        "invoicereminder/show",
                        invoice?.reminderLevelId
                    );
                } else {
                    warningLevelResponse = await this.$store.dispatch(
                        "invoicereminder/showByLevelName",
                        {
                            levelName: invoice?.invoiceStatus,
                        }
                    );
                }
                warningLevel = warningLevelResponse?.data?.data ?? {};
                templateId =
                    warningLevelResponse?.data?.data?.documentTemplateId ?? "";
                // if there is invoice details, then generate the document
                if (invoice) {
                    const filename =
                        "invoice-reminder-" +
                        (invoice.invoiceNumber == null
                            ? "draft"
                            : invoice.invoiceNumber) +
                        ".pdf";
                    await this.$store.dispatch(
                        "documentService/processTemplate",
                        {
                            data: {
                                ...invoice,
                                id: templateId,
                                ...warningLevel,
                            },
                            filename: filename,
                            documentType: "pdf",
                        }
                    );
                }
            } catch (e) {
                console.log(e);
            } finally {
                this.$store.commit("showLoader", false);
            }
        },
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("openposts/list", {
                page: this.page,
                search: this.form.search,
                sortBy: this.form.sortBy,
                sortOrder: this.form.sortOrder,
                customerId: this.form.company?.id,
            });
        },
        async refresh() {
            this.$store
                .dispatch("companies/list", {
                    page: 1,
                    perPage: 25,
                })
                .then((res) => {
                    this.companies = res?.data?.data ?? [];
                });
            await this.$store.dispatch(
                "openposts/list",
                this.pickBy({ ...this.form, customerId: this.form.company?.id })
            );
        },
        companiesSearch(data) {
            this.companies = data?.data;
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

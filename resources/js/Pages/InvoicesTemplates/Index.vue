<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />
        <div class="header flex justify-between items-baseline">
            <!-- TODO : may be need to implement later -->
            <!-- <div class="flex">
                <div
                    role="button"
                    @click="downloadCSV"
                    class="text-xs mr-4 font-medium text-blue-700"
                >
                    {{ $t("Export CSV") }}
                </div>
                <div
                    role="button"
                    @click="downloadLatestCSV"
                    class="text-xs font-medium text-blue-700"
                >
                    {{ $t("Export Latest CSV") }}
                </div>
            </div> -->
        </div>
        <div class="flex items-center justify-end mb-6">
            <search-filter
                v-model="form.search"
                class="mr-4 w-full max-w-md"
                @reset="reset"
            >
                <!-- <label class="block text-gray-700"
                    >{{ $t("Invoice Status") }}:</label
                >
                <select-input v-model="form.status" class="form-select mt-1 w-full">
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
                </select-input> -->
                <div class="form group">
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
                <div class="form group">
                    <select-input
                        :label="$t('Category')"
                        v-model="form.category"
                        @change="companiesSearch"
                    >
                        <option value="license">
                            {{ $t("Software License") }}
                        </option>
                        <option value="maintenance">
                            {{ $t("Software Maintenance") }}
                        </option>
                        <option value="service">{{ $t("Service") }}</option>
                        <option value="ams">
                            {{ $t("Application Management Service") }}
                        </option>
                        <option value="hosting">{{ $t("Hosting") }}</option>
                        <option value="cloud-software">{{ $t("Cloud") }}</option>
                    </select-input>
                </div>
            </search-filter>
        </div>
        <div class="table-responsive">
            <table class="w-full doc-table">
                <tr class="text-left font-bold">
                    <th
                        @click="sort('Company.companyName', 'invoiceTemplates')"
                        class="cursor-pointer"
                    >
                        {{ $t("Company")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'Company.companyName'"
                            class="cursor-pointer"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('invoiceCategory', 'invoiceTemplates')"
                        class="cursor-pointer"
                    >
                        {{ $t("Invoice Category")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'invoiceCategory'"
                            class="cursor-pointer"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('TermsOfPayment.name', 'invoiceTemplates')"
                        class="cursor-pointer"
                    >
                        {{ $t("Term Of Payment")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'TermsOfPayment.name'"
                            class="cursor-pointer"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="
                            sort('totalAmountWithoutTax', 'invoiceTemplates')
                        "
                        class="cursor-pointer"
                    >
                        {{ $t("Netto")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'totalAmountWithoutTax'"
                            class="cursor-pointer"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('createdAt', 'invoiceTemplates')"
                        class="cursor-pointer"
                    >
                        {{ $t("Created At")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'createdAt'"
                            class="cursor-pointer"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('updatedAt', 'invoiceTemplates')"
                        class="cursor-pointer"
                    >
                        {{ $t("Updated At")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'updatedAt'"
                            class="cursor-pointer"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th class="cursor-pointer">
                        {{ $t("User")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'UserProfileData.firstName'"
                            class="cursor-pointer"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th class="">{{ $t("Action") }}</th>
                </tr>
                <tr
                    v-for="invoice in invoiceTemplates.data"
                    :key="invoice.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                    @contextmenu.stop.prevent="
                        (e) => {
                            e.preventDefault();
                            let route = $router.resolve(`/invoices-templates/${invoice.id}/edit?page=${page}`);
                            window.open(route.href, '_blank');
                        }
                    "
                >
                    <td class="">
                        <a
                            class="flex items-center cursor-pointer  focus:text-indigo-500"
                        >
                            {{ invoice.company }}
                        </a>
                    </td>

                    <td class="">
                        <a
                            class="flex items-center cursor-pointer "
                            tabindex="-1"
                        >
                            {{ $t(invoice.category) }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            class="flex items-center cursor-pointer  focus:text-indigo-500"
                        >
                            {{ invoice?.termOfPayment?.name }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            class="flex items-center cursor-pointer "
                            tabindex="-1"
                        >
                            {{ $formatter(invoice.netto, globalLanguage) }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            class="flex items-center cursor-pointer "
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
                    <td class="">
                        <a
                            class="flex items-center cursor-pointer "
                            tabindex="-1"
                        >
                            {{
                                $dateFormatter(
                                    invoice.updatedAt,
                                    globalLanguage
                                )
                            }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            class="flex items-center cursor-pointer  focus:text-indigo-500"
                        >
                            {{ invoice.userName }}
                        </a>
                    </td>
                    <td class="w-px  text-center">
                        <div class="flex items-center gap-x-2">
                            <router-link
                                @click.stop=""
                                v-if="$can(`${$route.meta.permission}.show`)"
                                :to="`/invoices-templates/${invoice.id}?page=${page}`"
                            >
                                <font-awesome-icon icon="fa-solid fa-eye" />
                            </router-link>
                            <router-link
                                @click.stop=""
                                v-if="$can(`${$route.meta.permission}.edit`)"
                                :to="`/invoices-templates/${invoice.id}/edit?page=${page}`"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-pen-to-square"
                                />
                            </router-link>
                            <button
                                v-if="$can(`${$route.meta.permission}.delete`)"
                                @click.stop="destroy(invoice.id)"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-trash-can"
                                />
                            </button>
                        </div>
                    </td>
                </tr>
                <tr v-if="invoiceTemplates?.data?.length !== 0">
                    <td></td>
                    <td></td>
                    <td class="text-right">{{ $t('Sum') }} :</td>
                    <td>  <span class=""> {{$formatter(totalSumOfInvoice, globalLanguage)}}</span></td>
                </tr>
                <tr v-if="invoiceTemplates?.data?.length === 0">
                    <td class=" " colspan="9">
                        {{ $t("No invoices found") }}.
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="invoiceTemplates"
                @pagination-change-page="changePageOrSearch"
            ></pagination>
            <br />
            <br />
        </div>
    </div>
</template>

<script>
import Icon from "../../Components/Icon.vue";
import LoadingButton from "../../Components/LoadingButton.vue";

import Pagination from "laravel-vue-pagination";
import SearchFilter from "../../Components/SearchFilter.vue";
import { mapGetters } from "vuex";

import MultiSelectInput from "../../Components/MultiSelectInput.vue";
// import MailModal from "./MailModal.vue";
import Modal from "@/Components/EditModal.vue";
import PDFMerger from "pdf-merger-js/browser";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { debounce } from "../../utils/debounce"
import SelectInput from "@/Components/SelectInput.vue";
export default {
    computed: {
        ...mapGetters("invoiceTemplates", ["invoiceTemplates", "totalSumOfInvoice"]),
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
        SelectInput,
        LoadingButton,
        Icon,
        Pagination,
        SearchFilter,
        MultiSelectInput,
        // MailModal,
        Modal,
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
                    to: "/invoices",
                },
                {
                    text: this.$t("Invoices Template"),
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Invoice Template"),
                btn2Path: "/invoices-templates/create",
            },
            togglePromptModal: false, // toggles confirmation modal
            processingAll: false, // toggles the load functionality in the 'send all selected' mail button
            selectedInvoices: {}, // keeps track of all the selected invoices
            processing: false,
            page: 1,
            companies: [],
            toggleModal: false,
            form: {
                company: "",
                type: "",
                search: "",
                category:"",
                // status: "",
                sortBy:
                    localStorage.getItem("sort_invoice_templates_column") ??
                    "Company.companyName",
                sortOrder:
                    localStorage.getItem("sort_invoice_templates_order") ??
                    "asc",
            },
            invoiceTemplate: "", // invoice mail template assignment id
            invoiceCorrectionTemplate: "", // invoice correction mail template assignment id
            invoiceStornoTemplate: "", // invoice storno mail template assignment id
            window
        };
    },
    mounted() {
        if(this.$route.query.page){
             this.page=this.$route.query.page;
             this.$router.replace({'query': null});
        }
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
    },
    watch: {
        'form.search'(...val) {
            this.debouncedFetch(...val);
        },
        'form.company'(...val) {
            this.debouncedFetch(...val);
        },
        'form.type'(...val) {
            this.debouncedFetch(...val);
        },
        'form.sortBy'(...val) {
            this.debouncedFetch(...val);
        },
        'form.category'(...val) {
            this.debouncedFetch(...val);
        },
        'form.sortOrder'(...val) {
            this.debouncedFetch(...val);
        },
    },
    created() {
        this.debouncedFetch = debounce(async (newValue, oldValue)=>{
            try {
                await this.$store.dispatch("invoiceTemplates/list", {
                    ...this.form,
                    customerId: this.form.company?.id,
                });
            } catch (e) {}
        }, 300);
    },
    methods: {
        companiesSearch(data) {
            this.companies = data?.data;
        },
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("invoiceTemplates/list", {
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
                    await this.$store.dispatch("invoiceTemplates/destroy", id);
                    this.refresh();
                }
            });
        },
        async refresh() {
            await this.$store.dispatch(
                "invoiceTemplates/list",
                {
                    ...this.pickBy(this.form),
                    page: this.page,
                }
            );

            console.log("Invoice templates are", this.invoiceTemplates);
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

<template>
    <div>
        <PageHeader
            :items="breadcrumbItems"
            :optionalItems="optionalItems"
            @csvBtn1="downloadCSV"
            @csvBtn2="downloadLatestCsv"
        />

        <div class="flex items-center justify-end mb-6">
            <search-filter
                v-model="form.search"
                class="mr-4 w-full max-w-md"
                @reset="reset"
            >
                <label class="block text-gray-700"
                    >{{ $t("Invoice Type") }}:</label
                >
                <select v-model="form.type" class="form-select mt-1 w-full">
                    <option value="invoice">
                        {{ $t("Invoice") }}
                    </option>
                    <option value="invoice-correction">
                        {{ $t("Invoice Correction") }}
                    </option>
                    <option value="invoice-storno">
                        {{ $t("Invoice Storno") }}
                    </option>
                </select>
                <label class="block text-gray-700"
                    >{{ $t("Invoice Status") }}:</label
                >
                <select v-model="form.status" class="form-select mt-1 w-full">
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
                </select>
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
            <table class="doc-table">
                <thead>
                    <tr class="text-left font-bold">
                        <th
                            @click="sort('Company.companyName', 'invoices')"
                            class="cursor-pointer flex"
                        >
                            {{ $t("Supplier")
                            }}<font-awesome-icon
                                v-if="form.sortBy === 'Company.companyName'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('invoiceNumber', 'invoices')"
                            class="cursor-pointer"
                        >
                            {{ $t("Invoice Number")
                            }}<font-awesome-icon
                                v-if="form.sortBy === 'invoiceNumber'"
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
                            @click="sort('draftStatusChangeDate')"
                            class="cursor-pointer"
                        >
                            {{ $t("Invoice Date")
                            }}<font-awesome-icon
                                v-if="form.sortBy === 'draftStatusChangeDate'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('TermsOfPayment.name', 'invoices')"
                            class="cursor-pointer"
                        >
                            {{ $t("Terms Of Payment")
                            }}<font-awesome-icon
                                v-if="form.sortBy === 'TermsOfPayment.name'"
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
                            @click="sort('totalAmountWithoutTax', 'invoices')"
                            class="cursor-pointer"
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
                        <th
                            @click="sort('totalTaxAmount', 'invoices')"
                            class="cursor-pointer"
                        >
                            {{ $t("Tax")
                            }}<font-awesome-icon
                                v-if="form.sortBy === 'totalTaxAmount'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('totalAmount', 'invoices')"
                            class="cursor-pointer"
                        >
                            {{ $t("Brutto Total")
                            }}<font-awesome-icon
                                v-if="form.sortBy === 'totalAmount'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th class="">{{ $t("Action") }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="invoice in invoices.data"
                        :key="invoice.id"
                        class="hover:bg-gray-100 focus-within:bg-gray-100"
                        @contextmenu.stop.prevent="
                            (e) => {
                                e.preventDefault();
                                let route = $router.resolve(
                                    `/creditor-invoices/${invoice.id}/edit?page=${page}`
                                );
                                window.open(route.href, '_blank');
                            }
                        "
                    >
                        <td class="flex pl-6">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ invoice.customer?.companyName }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ invoice.invoiceNumber }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center"
                                tabindex="-1"
                            >
                                {{ $t(invoice.invoiceType) }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center"
                                tabindex="-1"
                            >
                                {{ $dateFormatter(invoice.invoiceDate, globalLanguage) }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center"
                                tabindex="-1"
                            >
                                {{ invoice.termsOfPayment?.name }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center"
                                tabindex="-1"
                            >
                                {{
                                    $dateFormatter(
                                        invoice.dueDate,
                                        globalLanguage
                                    )
                                }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center"
                                tabindex="-1"
                            >
                                {{ $t(invoice.status) }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center"
                                tabindex="-1"
                            >
                                {{
                                    $formatter(
                                        invoice.totalAmountWithoutTax,
                                        globalLanguage
                                    )
                                }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center"
                                tabindex="-1"
                            >
                                {{
                                    $formatter(
                                        invoice.totalTaxAmount,
                                        globalLanguage
                                    )
                                }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center"
                                tabindex="-1"
                            >
                                {{
                                    $formatter(
                                        invoice.totalAmount,
                                        globalLanguage
                                    )
                                }}
                            </a>
                        </td>
                        <td class="w-px text-center">
                            <router-link
                                @click.stop=""
                                v-if="$can(`${$route.meta.permission}.edit`)"
                                class="px-1"
                                :to="`/creditor-invoices/${invoice.id}/edit?page=${page}`"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-pen-to-square"
                                />
                            </router-link>
                        </td>
                    </tr>
                    <tr v-if="invoices?.data?.length === 0">
                        <td class="" colspan="9">
                            {{ $t("No invoices found") }}.
                        </td>
                    </tr>
                </tbody>
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
</template>

<script>
import Icon from "../../Components/Icon.vue";
import LoadingButton from "../../Components/LoadingButton.vue";

import Pagination from "laravel-vue-pagination";
import SearchFilter from "../../Components/SearchFilter.vue";
import { mapGetters } from "vuex";

import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { debounce } from "../../utils/debounce";
export default {
    computed: {
        ...mapGetters("creditorInvoices", ["invoices"]),
    },
    components: {
        LoadingButton,
        Icon,
        Pagination,
        SearchFilter,
        MultiSelectInput,
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
                    active:true
                },
            ],
            optionalItems: {
                csvBtn1Show: true,
                csvBtn1Text: this.$t("Export CSV"),
                csvBtn2Show: true,
                csvBtn2Text: this.$t("Export Latest CSV"),
                isBtn2Show: true,
                btn2Text: this.$t("Create Invoice"),
                btn2Path: "/creditor-invoices/create",
            },
            page: 1,
            companies: [],
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
            window,
        };
    },

    mounted() {
        if (this.$route.query.page) {
            this.page = this.$route.query.page;
            this.$router.replace({ query: null });
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
        "form.search"(...val) {
            this.debouncedFetch(...val);
        },
        "form.company"(...val) {
            this.debouncedFetch(...val);
        },
        "form.type"(...val) {
            this.debouncedFetch(...val);
        },
        "form.status"(...val) {
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
                await this.$store.dispatch("creditorInvoices/list", {
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
            await this.$store.dispatch("creditorInvoices/list", {
                page: this.page,
                search: this.form.search,
                sortBy: this.form.sortBy,
                sortOrder: this.form.sortOrder,
            });
        },
        async downloadCSV() {
            try {
                await this.$store.dispatch("creditorInvoices/download");
            } catch (e) {}
        },
        async downloadLatestCSV() {
            try {
                await this.$store.dispatch(
                    "creditorInvoices/downloadLatestCSV"
                );
            } catch (e) {}
        },
        async refresh() {
            await this.$store.dispatch("creditorInvoices/list", {
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

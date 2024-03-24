<template>
    <div>
        <div class="table-responsive modal-table">
            <table class="doc-table">
                <tr class="text-left font-bold">
                    <th
                        @click="sort('invoiceNumber', 'invoice_finance')"
                        style="width: 12%"
                        class="cursor-pointer"
                    >
                        {{ $t("Invoice Number") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'invoiceNumber'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('companyName', 'invoice_finance')"
                        style="width: 30%"
                        class="cursor-pointer"
                    >
                        {{ $t("Company") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'companyName'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('invoiceType', 'invoice_finance')"
                        style="width: 10%"
                        class="cursor-pointer"
                    >
                        {{ $t("Invoice Type") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'invoiceType'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('status', 'invoice_finance')"
                        style="width: 10%"
                        class="cursor-pointer"
                    >
                        {{ $t("Status") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'status'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('category', 'invoice_category')"
                        style="width: 11%"
                        class="cursor-pointer"
                    >
                        {{ $t("Invoice Category") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'category'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('netto', 'invoice_finance')"
                        style="width: 15%"
                        class="cursor-pointer"
                    >
                        {{ $t("Netto") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'netto'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('createdAt', 'invoice_finance')"
                        style="width: 12%"
                        class="cursor-pointer"
                    >
                        {{ $t("Created At") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'createdAt'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('dueDate', 'invoice_finance')"
                        style="width: 12%"
                        class="cursor-pointer"
                    >
                        {{ $t("Due Date") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'dueDate'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                </tr>
                <tr
                    v-for="item in invoiceListing?.data ?? []"
                    :key="item.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                    @click="$router.push(`/invoices/${item.id}`)"
                >
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center  focus:text-indigo-500"
                        >
                            {{ item.invoiceNumber }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center  focus:text-indigo-500"
                        >
                            {{ item.companyName }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center  focus:text-indigo-500"
                        >
                            {{ item.invoiceType }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center  focus:text-indigo-500"
                        >
                            {{ item.status }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center  focus:text-indigo-500"
                        >
                            {{ item.category }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center  focus:text-indigo-500"
                        >
                            {{ $formatter(item.netto, globalSettings) }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center  focus:text-indigo-500"
                        >
                            <!-- {{ item.createdAt }} -->
                            {{ $dateFormatter(item.createdAt, globalLanguage) }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center  focus:text-indigo-500"
                        >
                            <!-- {{ item.dueDate }} -->
                            {{ $dateFormatter(item.dueDate, globalLanguage) }}
                        </a>
                    </td>
                </tr>
                <tr v-if="(invoiceListing?.data?.length ?? 0) === 0">
                    <td class=" " colspan="4">
                        {{ $t("No invoices found") }}.
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="invoiceListing"
                @pagination-change-page="$emit('changePage', $event)"
            ></pagination>
            <br />
            <br />
        </div>
    </div>
</template>

<script>
import Pagination from "laravel-vue-pagination";
import { debounce } from "@/utils/debounce";


export default {
    emits: ["changePage", "orderInvoicesTable"],
    props: {
        invoiceListing: {
            type: Object,
            required: true,
            default: () => ({
                data: [],
                links: [],
            }),
        },
    },
    mounted() {
        const financeColumn = localStorage.getItem(
            "sort_invoice_finance_column"
        );
        const financeOrder = localStorage.getItem("sort_invoice_finance_order");
        // Apply the stored values to the form object for 'customers' table
        if (financeColumn) {
            this.form.sortBy = financeColumn;
        }

        if (financeOrder) {
            this.form.sortOrder = financeOrder;
        }
    },
    created() {
        this.debouncedFetch = debounce(async (newValue, oldValue) => {
            try {
                this.$emit("orderInvoicesTable", this.form);
            } catch (e) {
                console.log(e);
            }
        }, 300);
    },
    watch: {
        "form.sortOrder"(...val) {
            this.debouncedFetch(...val);
        },
        "form.sortBy"(...val) {
            this.debouncedFetch(...val);
        },
        
    },
    data() {
        return {
            form: {
                sortBy: "",
                sortOrder: "",
            },
        };
    },
    components: {
        Pagination,
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
table {
    font-size: 0.7rem;
}
td > :only-child {
    max-width: 50ch;
}
</style>

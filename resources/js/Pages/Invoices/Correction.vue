<template>
    <div>
        <h1 class="header mb-8 text-3xl font-bold">
            <router-link
                class="text-indigo-400 hover:text-indigo-600"
                to="/invoices"
                >{{ $t("Correction") }}</router-link
            >
            <span class="text-indigo-400 font-medium">/</span>
            {{ $t("Create") }}
        </h1>
        <form @submit.prevent="store">
            <h6 v-if="form.customers?.name" class="customer-design">
                {{ form.customers?.name }} - {{ form.customers?.state }} -
                {{ form.customers?.country }}
                <br />
            </h6>
            <div v-if="form.errors?.customers" class="form-error">
                {{ form.errors.customers }}
            </div>
            <div class="max-w-5xl bg-white rounded-md shadow">
                <div class="flex flex-wrap -mb-8 -mr-6 p-4">
                    <div class="sm:px-6 w-full">
                        <div class="bg-white py-4">
                            <div class="mt-7 overflow-x-auto">
                                <table class="w-full whitespace-nowrap">
                                    <thead>
                                        <tr>
                                            <th
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Article number") }}
                                            </th>
                                            <th
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Quantity") }}
                                            </th>
                                            <th
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Product price") }}
                                            </th>
                                            <th
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Discount") }}
                                            </th>
                                            <th
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Tax") }}
                                            </th>
                                            <th
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Total price") }}
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr
                                            v-for="(
                                                product, index
                                            ) in form.products"
                                            :key="index"
                                            :tabindex="index"
                                            class="focus:outline-none h-16 border border-gray-100 rounded"
                                        >
                                            <td class="pl-5">
                                                <div class="md:w-2/3">
                                                    <input
                                                        class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:border-purple-500"
                                                        type="text"
                                                        readonly
                                                        v-model="
                                                            product.articleNumber
                                                        "
                                                    />
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div class="md:w-2/3">
                                                    <input
                                                        class="appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        @input="
                                                            calculateProductValue(
                                                                index
                                                            )
                                                        "
                                                        v-model="
                                                            product.quantity
                                                        "
                                                    />
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div class="md:w-2/3">
                                                    <input
                                                        class="appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="text"
                                                        @input="
                                                            calculateProductValue(
                                                                index
                                                            )
                                                        "
                                                        v-model="
                                                            product.salePrice
                                                        "
                                                    />
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div class="md:w-2/3">
                                                    <input
                                                        class="appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="text"
                                                        @input="
                                                            calculateProductValue(
                                                                index
                                                            )
                                                        "
                                                        v-model="
                                                            product.discount
                                                        "
                                                    />
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div class="md:w-2/3">
                                                    <input
                                                        class="appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="text"
                                                        @input="
                                                            calculateProductValue(
                                                                index
                                                            )
                                                        "
                                                        v-model="product.tax"
                                                    />
                                                </div>
                                            </td>
                                            <td class="pl-5 text-center">
                                                <div class="md:w-2/3">
                                                    {{
                                                        product.totalPrice
                                                            ? String(
                                                                  product?.totalPrice
                                                              ).replace(
                                                                  /\B(?=(\d{3})+(?!\d))/g,
                                                                  ","
                                                              )
                                                            : (product.totalPrice = 0)
                                                    }}
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <br />
                                <div class="flex flex-row-reverse">
                                    <p class="customer-design">
                                        {{ $t("Total") }}: {{ totalAmount }}
                                    </p>
                                </div>
                            </div>
                            <div
                                v-if="form.errors?.products"
                                class="form-error"
                            >
                                {{ form.errors.products }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <br />

            <h6
                class="text-xl font-normal leading-normal mt-0 mb-2 text-cyan-800"
            >
                {{ $t("Fill Invoice Details") }}
            </h6>

            <div class="max-w-5xl bg-white rounded-md shadow">
                <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                    <select-input
                        v-model="form.invoiceType"
                        :error="form.errors.invoiceType"
                        class="pb-8 pr-6 w-full lg:w-1/2"
                        :label="$t('Invoice Type')"
                        disabled
                    >
                        <option value="correction">
                            {{ $t("Invoice-Correction") }}
                        </option>
                        <option value="invoice">{{ $t("Invoice") }}</option>
                        <option value="storeno">
                            {{ $t("Invoice-Storno") }}
                        </option>
                    </select-input>

                    <div class="pr-6 w-full lg:w-1/2 mb-8">
                        <label class="form-label">{{ $t("Due date") }}</label>
                        <datepicker
                            :clearable="false"
                            :enable-time-picker="false"
                            auto-apply
                            :close-on-auto-apply="false"
                            :style="dropdownStyles"
                            v-model="form.dueDate"
                        />
                        <div v-if="form.errors?.dueDate" class="form-error">
                            {{ form.errors.dueDate }}
                        </div>
                    </div>
                    <text-input
                        v-model="form.invoicePeriod"
                        :error="form.errors.invoicePeriod"
                        class="pb-8 pr-6 w-full lg:w-1/2"
                        :label="$t('Invoice Period (Days)')"
                    />

                    <text-input
                        v-model="form.termsOfPayments"
                        :error="form.errors.termsOfPayments"
                        class="pb-8 pr-6 w-full lg:w-1/2"
                        :label="$t('Terms of payment')"
                    />

                    <text-area-input
                        v-model="form.customNotesFields"
                        :error="form.errors.customNotesFields"
                        class="pb-8 pr-6 w-full lg:w-1/2"
                        :label="$t('Custom Notes Field')"
                    />
                </div>
            </div>

            <br />
            <br />

            <div class="max-w-5xl flex flex-row-reverse">
                <loading-button
                    :loading="form.processing"
                    @click="store"
                    class="secondary-btn"
                    >{{ $t("Create Invoice") }}</loading-button
                >
            </div>
        </form>
    </div>
</template>

<script>
import TextInput from "../../Components/TextInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import TextAreaInput from "../../Components/TextareaInput.vue";
import Icon from "../../Components/Icon.vue";

export default {
    components: {
        LoadingButton,
        SelectInput,
        TextInput,
        TextAreaInput,
        Icon,
    },
    props: { errors: Object, pInvoices: { type: Object, default: () => ({}) } },
    data() {
        return {
            form: {
                invoiceType: "correction",
                dueDate: new Date(this.pInvoices?.dueDate),
                invoicePeriod: this.pInvoices?.invoicePeriod,

                termsOfPayments: this.pInvoices?.termsOfPayment,
                customNotesFields: this.pInvoices?.notes,
                customers: {
                    id: this.pInvoices?.customer?.id,
                    name: this.pInvoices?.customer?.name,
                    state: this.pInvoices?.customer_state,
                    country: this.pInvoices?.customer_country,
                },
                products: this.pInvoices?.products,
            },
            productToggle: false,
            customerToggle: false,
        };
    },
    computed: {
        dropdownStyles() {
            return {
                "--elem-hover-bg-color": "#312E81",
                "--elem-selected-bg-color": "#312E81",
            };
        },
        totalAmount() {
            const sum = this.form.products.reduce((accumulator, object) => {
                console.log(object.totalPrice);
                return parseInt(accumulator) + parseInt(object.totalPrice);
            }, 0);

            return sum
                ? `€${String(sum).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`
                : `€` + 0;
        },
    },
    methods: {
        addCustomer(customer) {
            this.form.customers = customer;
            this.customerToggle = false;
        },
        addProducts(products) {
            this.form.products = products;
            this.productToggle = false;
        },
        calculateProductValue(index) {
            this.form.products[index].totalPrice =
                this.form.products[index].salePrice *
                this.form.products[index].quantity;
            if (this.form.products[index]?.discount) {
                this.form.products[index].totalPrice =
                    this.form.products[index].totalPrice *
                    (1 - this.form.products[index]?.discount / 100);
            }
            if (this.form.products[index]?.tax) {
                this.form.products[index].totalPrice =
                    this.form.products[index].totalPrice *
                    (this.form.products[index]?.tax / 100 + 1);
            }
            this.form.products[index].totalPrice = Math.trunc(
                this.form.products[index].totalPrice
            );
        },

        store() {
            // this.form.post("/invoices/store");
        },
    },
};
</script>

<style></style>

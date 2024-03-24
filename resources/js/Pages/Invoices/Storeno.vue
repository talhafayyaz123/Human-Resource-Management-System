<template>
    <div>
        <h1 class="header mb-8 text-3xl font-bold">
            <router-link class="secondary-color" to="/invoices">{{
                $t("Credit Note")
            }}</router-link>
            <span class="secondary-color font-medium">/</span>
            <span class="text-color">{{ $t("Create") }}</span>
        </h1>
        <form @submit.prevent="store">
            <div class="flex justify-between">
                <h6 v-if="form.customers?.name" class="customer-design">
                    {{ form.customers?.name }}
                    <br />
                    {{ form.customers?.address }}
                    <br />
                    {{ form.customers?.code }} &nbsp; {{ form.customers?.city }}
                    <br />
                    {{ form.customers?.country }}
                    <br />
                </h6>
                <div>
                    <h6 v-if="form?.dueDate" class="customer-design">
                        <b>{{ $t("Due Date") }}:</b>
                        {{ $dateFormatter(formatDateLite(new Date(form?.dueDate)), globalLanguage) }}
                        <br />
                        <b>{{ $t("Created Date") }}:</b>
                        {{ $dateFormatter(pInvoices.created_dt, globalLanguage) }}
                    </h6>
                    <h6
                        v-if="form.systems?.systemNumber"
                        class="customer-design"
                    >
                        <b>{{ $t("System number") }}:</b>
                        {{ form.systems?.systemNumber }}
                    </h6>
                </div>
            </div>
            <div v-if="form.errors?.customers" class="form-error">
                {{ form.errors.customers }}
            </div>
            <div class="max-w-6xl bg-white rounded-md shadow">
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
                                                {{ $t("Product name") }}
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
                                                {{ $t("Discount") }}(%)
                                            </th>
                                            <th
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Netto total") }}
                                            </th>
                                            <th
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Tax") }}(%)
                                            </th>
                                            <th
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Brutto total") }}
                                            </th>
                                            <th
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Actions") }}
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
                                                        :style="
                                                            inputWidth(
                                                                index,
                                                                'articleNumber'
                                                            )
                                                        "
                                                    />
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div class="md:w-2/3">
                                                    <input
                                                        class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:border-purple-500"
                                                        type="text"
                                                        readonly
                                                        v-model="product.name"
                                                        :style="
                                                            inputWidth(index)
                                                        "
                                                    />
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div class="md:w-2/3">
                                                    <input
                                                        class="appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        readonly
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
                                                        readonly
                                                        v-model="
                                                            product.salePrice
                                                        "
                                                        @input="
                                                            calculateProductValue(
                                                                index
                                                            )
                                                        "
                                                    />
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div class="md:w-2/3">
                                                    <input
                                                        class="appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="text"
                                                        readonly
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
                                            <td class="pl-5 text-center">
                                                <div class="md:w-2/3">
                                                    {{
                                                        product.priceWithoutTax
                                                            ? String(
                                                                  Math.round(
                                                                      product?.priceWithoutTax
                                                                  ).toFixed(2)
                                                              ).replace(
                                                                  /\B(?=(\d{3})+(?!\d))/g,
                                                                  ","
                                                              )
                                                            : (product.priceWithoutTax = 0)
                                                    }}
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div class="md:w-2/3">
                                                    <input
                                                        class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="text"
                                                        readonly
                                                        :style="
                                                            inputWidth(index)
                                                        "
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
                                                                  Math.round(
                                                                      product.totalPrice
                                                                  ).toFixed(2)
                                                              ).replace(
                                                                  /\B(?=(\d{3})+(?!\d))/g,
                                                                  ","
                                                              )
                                                            : (product.totalPrice = 0)
                                                    }}
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div
                                                    style="
                                                        cursor: pointer;
                                                        margin: auto;
                                                    "
                                                    @click="
                                                        () => {
                                                            form.products.splice(
                                                                index,
                                                                1
                                                            );
                                                            this.invoiceTax =
                                                                this.calculateTax();
                                                        }
                                                    "
                                                    class="md:w-2/3"
                                                >
                                                    <icon
                                                        name="trash"
                                                        class="mr-2 w-4 h-4 fill-red"
                                                    />
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <br />
                                <div
                                    v-if="Object.keys(form.products).length > 0"
                                    style="
                                        flex-direction: column;
                                        align-items: end;
                                    "
                                    class="flex flex-row-reverse"
                                >
                                    <div>
                                        <h1 class="customer-design">
                                            Netto:&nbsp;
                                            {{ totalAmountWithoutTax }}
                                            <hr
                                                style="border-top: dotted 1px"
                                            />
                                        </h1>
                                        <div
                                            style="
                                                display: flex;
                                                justify-content: space-between;
                                                border-bottom: dotted 1px;
                                            "
                                            v-for="(item, index) in invoiceTax"
                                            :key="index"
                                        >
                                            <h1 class="customer-tax-design">
                                                {{ item.percent }}%
                                                {{ $t("Tax") }}
                                            </h1>
                                            &nbsp;
                                            <h1 class="customer-tax-design">
                                                {{
                                                    `€${String(
                                                        item.amount.toFixed(2)
                                                    ).replace(
                                                        /\B(?=(\d{3})+(?!\d))/g,
                                                        ","
                                                    )}`
                                                }}
                                            </h1>
                                        </div>
                                        <h1 class="customer-design">
                                            {{ $t("Total") }}:&nbsp;
                                            {{ totalAmount }}
                                        </h1>
                                    </div>
                                </div>
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

            <div class="max-w-6xl bg-white rounded-md shadow">
                <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                    <select-input
                        v-model="form.invoiceType"
                        :error="form.errors.invoiceType"
                        class="pb-8 pr-6 w-full lg:w-1/2"
                        :label="$t('Invoice Type')"
                        disabled
                    >
                        <option value="invoice-correction">
                            {{ $t("Invoice-Correction") }}
                        </option>
                        <option value="invoice">{{ $t("Invoice") }}</option>
                        <option selected value="invoice-storno">
                            {{ $t("Invoice-Storno") }}
                        </option>
                    </select-input>

                    <div class="pr-6 w-full lg:w-1/2 mb-8">
                        <label class="form-label">{{ $t("Due date") }}</label>
                        <datepicker
                            :style="dropdownStyles"
                            :clearable="false"
                            :enable-time-picker="false"
                            auto-apply
                            :close-on-auto-apply="false"
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

            <div class="max-w-6xl flex flex-row-reverse">
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
    props: {
        errors: Object,
        pInvoices: { type: Object, default: () => ({}) },
        systems: Object,
    },
    data() {
        return {
            form: {
                invoiceType: "invoice-storno",
                dueDate: new Date(this.pInvoices?.dueDate),
                invoicePeriod: this.pInvoices?.invoicePeriod,

                termsOfPayments: this.pInvoices?.termsOfPayment,
                customNotesFields: this.pInvoices?.notes,
                customers: {
                    id: this.pInvoices?.customer?.id,
                    name: this.pInvoices?.customer?.company_name,
                    code: this.pInvoices?.customer_code,
                    city: this.pInvoices?.customer_city,
                    country: this.pInvoices?.customer_country,
                    address: this.pInvoices?.customer_address,
                },
                products: this.pInvoices?.products,
                systems: this.pInvoices?.systems ?? {},
            },
            productToggle: false,
            customerToggle: false,
            invoiceTax: [],
        };
    },
    mounted() {
        this.invoiceTax = this.form.products.reduce((result, product) => {
            const tax = product.tax;
            const existingGroup = result.find((group) => group.percent === tax);

            if (existingGroup) {
                existingGroup.amount +=
                    (parseFloat(product.totalPrice) || 0) -
                    (parseFloat(product.priceWithoutTax) || 0);
            } else {
                result.push({
                    percent: tax,
                    amount:
                        (parseFloat(product.totalPrice) || 0) -
                        (parseFloat(product.priceWithoutTax) || 0),
                });
            }

            return result;
        }, []);
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
        totalAmountWithoutTax() {
            const sum = this.form.products.reduce((accumulator, object) => {
                return parseInt(accumulator) + parseInt(object.priceWithoutTax);
            }, 0);

            return sum
                ? `€${String(sum).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`
                : `€` + 0;
        },
    },
    methods: {
        inputWidth(index, productName = "name") {
            const width = this.form.products[index][productName]?.length;
            return { width: parseInt(width) + 4 + "ch" };
        },
        addCustomer(customer) {
            this.form.customers = customer;
            this.customerToggle = false;
        },
        addProducts(products) {
            this.form.products = products;
            this.productToggle = false;
        },

        store() {
            this.form.post("/invoices/store");
        },
    },
};
</script>

<style></style>

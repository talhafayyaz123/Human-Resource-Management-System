<template>
    <div class="">
        <div v-if="$route.name !== 'OrderShowRecord'" class="flex items-center justify-end">
            <button
                type="button"
                @click="toggleProductsModal()"
                class="secondary-btn"
            >
                <span>{{ $t("Add Products") }}</span>
            </button>
        </div>
        <div class="table-responsive mt-3">
            <div>
                <table class="doc-table">
                    <thead>
                        <tr class="text-left">
                            <th>
                                {{ $t("POS") }}
                            </th>
                            <th>
                                {{ $t("Article Nr.") }}
                            </th>
                            <th>
                                {{ $t("Name") }}
                            </th>
                            <!-- <th>
                                {{ $t("Software") }}
                            </th> -->
                            <th>{{ $t("Quantity") }}/{{ $t("Unit") }}</th>
                            <th>
                                {{ $t("Product Price") }}
                            </th>
                            <!-- <th>
                                {{ $t("Hourly Rate") }}
                            </th>
                            <th>
                                {{ $t("Price Per Period") }}
                            </th>
                            <th>
                                {{ $t("Duration") }}
                            </th>-->
                            <th>
                                {{ $t("Discount Rate") }}
                            </th>
                             <th>
                                {{ $t("Discount Price") }}
                            </th>
                            <!-- <th>
                                {{ $t("Tax") }}
                            </th>
                            <th>
                                {{ $t("Tax Rate") }}
                            </th> -->
                            <th>
                                {{ $t("Period") }}
                            </th>
                            <th>
                                <div style="width: 140px">
                                    {{ $t("Netto Total") }}
                                </div>
                            </th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        <template
                            v-for="(product, index) in selectedProducts"
                            :key="index"
                            :tabindex="index"
                        >
                            <tr class="text-left">
                                <td>
                                    <div class="">
                                        {{ index + 1 }}
                                    </div>
                                </td>
                                <td>
                                    <div class="">
                                        {{ product.articleNumber }}
                                    </div>
                                </td>
                                <td>
                                    <div class="table-align">
                                        <input
                                            v-if="product.isProductNameEdit"
                                            :title="product.name"
                                            class="appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                            type="text"
                                            name="name"
                                            v-model="product.name"
                                            :class="{
                                                Perror: errors[
                                                    `products.${index}.name`
                                                ],
                                            }"
                                        />
                                        <span v-else>{{ product.name }}</span>
                                    </div>
                                </td>
                                <!-- <td>
                                    <div
                                        v-if="
                                            [
                                                'ams',
                                                'hosting',
                                                'cloud-software',
                                            ].includes(product.type)
                                        "
                                    >
                                        {{ product.productSoftware?.name }}
                                    </div>
                                </td> -->
                                <td>
                                    <div style="display: flex !important">
                                        <number-input
                                            @inputEvent="
                                                fieldChanged(index, $event)
                                            "
                                            :show-prefix="false"
                                            class=""
                                            type="number"
                                            v-model="product.quantity"
                                            name="quantity"
                                        />
                                        <p class="self-center ml-2">
                                            {{ product?.unit?.name }}
                                        </p>
                                    </div>
                                </td>
                                <td>
                                    <div
                                        v-if="
                                            [
                                                'software',
                                                'traveling',
                                                'cloud-software',
                                                'pauschal',
                                            ].includes(product.type)
                                        "
                                    >
                                        <number-input
                                            @inputEvent="
                                                fieldChanged(index, $event)
                                            "
                                            class=""
                                            type="number"
                                            v-model="product.salePrice"
                                            name="salePrice"
                                            :allowNegative="true"
                                        />
                                    </div>
                                    <div
                                        v-if="
                                            ['ams', 'service'].includes(
                                                product.type
                                            )
                                        "
                                    >
                                        <number-input
                                            @inputEvent="
                                                fieldChanged(index, $event)
                                            "
                                            class=""
                                            type="number"
                                            v-model="product.hourlyRate"
                                            name="hourlyRate"
                                        />
                                    </div>
                                     <div
                                        v-if="
                                            ['hosting'].includes(product.type)
                                        "
                                    >
                                        <number-input
                                            @inputEvent="
                                                fieldChanged(index, $event)
                                            "
                                            class=""
                                            type="number"
                                            v-model="product.pricePerPeriod"
                                            name="pricePerPeriod"
                                            :allowNegative="true"
                                        />
                                    </div>
                                    <div
                                        v-if="product.type === 'cloud-software'"
                                        class="flex"
                                    >
                                        <number-input
                                            @inputEvent="
                                                fieldChanged(index, $event)
                                            "
                                            class=""
                                            type="number"
                                            v-model="product.duration"
                                            name="duration"
                                            :show-prefix="false"
                                        />
                                    </div>
                                </td>
                                <!-- <td>
                                    <div
                                        v-if="
                                            ['ams', 'service'].includes(
                                                product.type
                                            )
                                        "
                                    >
                                        <number-input
                                            @inputEvent="
                                                fieldChanged(index, $event)
                                            "
                                            class=""
                                            type="number"
                                            v-model="product.hourlyRate"
                                            name="hourlyRate"
                                        />
                                    </div>
                                </td>
                                <td>
                                    <div
                                        v-if="
                                            ['hosting'].includes(product.type)
                                        "
                                    >
                                        <number-input
                                            @inputEvent="
                                                fieldChanged(index, $event)
                                            "
                                            class=""
                                            type="number"
                                            v-model="product.pricePerPeriod"
                                            name="pricePerPeriod"
                                            :allowNegative="true"
                                        />
                                    </div>
                                </td>
                                <td>
                                    <div
                                        v-if="product.type === 'cloud-software'"
                                        class="flex"
                                    >
                                        <number-input
                                            @inputEvent="
                                                fieldChanged(index, $event)
                                            "
                                            class=""
                                            type="number"
                                            v-model="product.duration"
                                            name="duration"
                                            :show-prefix="false"
                                        />
                                    </div>
                                </td> -->
                                 <td>
                                    <div>
                                        <number-input
                                            @inputEvent="
                                                fieldChanged(index, $event)
                                            "
                                            class=""

                                            type="number"
                                            v-model="product.discount"
                                            name="discount"
                                            :show-prefix="false"
                                        />
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <number-input
                                            :isReadonly="true"
                                            class=""
                                            type="number"
                                            v-model="product.discountAmount"
                                            name="discount"
                                            :show-prefix="false"
                                        />
                                    </div>
                                </td>
                                <!-- <td>
                                    <div
                                        v-if="
                                            ['service'].includes(product.type)
                                        "
                                        class="flex"
                                    >
                                        <number-input
                                            style="
                                                min-width: 8ch;
                                                max-width: 8ch;
                                            "
                                            @inputEvent="
                                                fieldChanged(index, $event)
                                            "
                                            class=""
                                            type="number"
                                            v-model="product.tax"
                                            name="tax"
                                        />
                                    </div>
                                </td>
                                <td>
                                    <div
                                        v-if="
                                            ['service'].includes(product.type)
                                        "
                                        class="flex"
                                    >
                                        <number-input
                                            @inputEvent="
                                                taxRateChanged(index, $event)
                                            "
                                            class=""
                                            type="number"
                                            v-model="product.taxRate"
                                            name="taxRate"
                                            :allowNegative="true"
                                        />
                                    </div>
                                </td> -->
                                <td>
                                    <div
                                        v-if="
                                            [
                                                'ams',
                                                'hosting',
                                                'cloud-software',
                                            ].includes(product.type)
                                        "
                                        class="form-group"
                                    >
                                        <select-input
                                            class="input-width"
                                            v-model="product.paymentPeriod"
                                        >
                                            <option
                                                v-for="period in periods.data"
                                                :key="'period-' + period.id"
                                                :value="period.id"
                                            >
                                                {{ period.name }}
                                            </option>
                                        </select-input>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <number-input
                                            @inputEvent="
                                                nettoTotalChanged(index, $event)
                                            "
                                            class=""
                                            type="number"
                                            v-model="product.nettoTotal"
                                            name="nettoTotal"
                                            :allowNegative="true"
                                        />
                                    </div>
                                </td>
                                <td>
                                    <font-awesome-icon
                                        v-if="$route.name !== 'OrderShowRecord'"
                                        @click="removeOption(index)"
                                        class="cursor-pointer cross"
                                        icon="fa-solid fa-xmark"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td class="" colspan="15">
                                    <div
                                        @click="
                                            toggleAdditionalDescriptionDropdowns(
                                                product.attachmentProductId,
                                                index,
                                                additionalDescriptionToggled[
                                                    product.attachmentProductId
                                                ]
                                                    ? 'remove'
                                                    : 'add',
                                                'ams'
                                            )
                                        "
                                        class="add-additional-btn"
                                    >
                                        <font-awesome-icon
                                            :class="`cursor-pointer cross mr-2 self-center text-${
                                                additionalDescriptionToggled[
                                                    product.attachmentProductId
                                                ]
                                                    ? 'red'
                                                    : 'blue'
                                            }-500`"
                                            :icon="`fa-solid fa-circle-${
                                                additionalDescriptionToggled[
                                                    product.attachmentProductId
                                                ]
                                                    ? 'minus'
                                                    : 'plus'
                                            }`"
                                        />
                                        <p
                                            :class="`text-sm text-${
                                                additionalDescriptionToggled[
                                                    product.attachmentProductId
                                                ]
                                                    ? 'red'
                                                    : 'blue'
                                            }-500`"
                                        >
                                            {{
                                                $t(
                                                    `${
                                                        additionalDescriptionToggled[
                                                            product
                                                                .attachmentProductId
                                                        ]
                                                            ? "Remove"
                                                            : "Add"
                                                    } Additional Description`
                                                )
                                            }}
                                        </p>
                                    </div>
                                    <div
                                        v-if="
                                            additionalDescriptionToggled[
                                                product.attachmentProductId
                                            ]
                                        "
                                    >
                                        <div class="form-group mt-6">
                                            <TextAreaInput
                                                style="
                                                    overflow: visible !important;
                                                "
                                                v-model="
                                                    product.additionalDescription
                                                "
                                                class=""
                                                :label="
                                                    $t('Additional Description')
                                                "
                                                :error="
                                                    errors[
                                                        'additionalDescription'
                                                    ]
                                                "
                                            ></TextAreaInput>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
        <div
            v-if="Object.keys(selectedProducts).length > 0"
            class="flex items-center justify-end"
        >
            <div class="offer-table-totals">
                <ul>
                    <li
                    >
                        <h3>{{ $t("Netto") }}:</h3>
                        <p :class="[globalLanguage === 'de' ? 'text-end' : '']">
                            {{ $formatter(computeNetto, globalLanguage) }}
                        </p>
                    </li>
                    <li
                        v-for="(value, key) in computeMwst"
                        :key="index"
                    >
                        <h3>{{ key }}% {{ $t("Tax") }}:</h3>
                        <p
                            :class="[globalLanguage === 'de' ? 'text-end' : '']"
                        >
                            {{ $formatter(value, globalLanguage) }}
                        </p>
                    </li>
                    <li
                        class="total"

                    >
                        <h3>{{ $t("Total") }}:</h3>
                        <p :class="[globalLanguage === 'de' ? 'text-end' : '']">
                            {{ $formatter(computeSummary, globalLanguage) }}
                        </p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <select-all-products-modal
        v-if="productToggle"
        :productType="productType"
        @selected="addProducts"
        @cancelled="hideModal"
        :selectedItems="[]"
        :products="products"
    ></select-all-products-modal>
</template>

<script>
import SelectAllProductsModal from "./SelectAllProductsModal.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TextAreaInput from "@/Components/TextareaInput.vue";
import NumberInput from "@/Components/NumberInput.vue";
import { v4 as uuid } from "uuid";
import { mapGetters } from "vuex";

export default {
    props: {
        contractField: {
            type: Object,
            default: () => ({}),
        },
        type: {
            type: String,
            default: "",
        },
        partnerId: {
            type: String,
            default: "",
        },
        propProducts: {
            type: Array,
            default: [],
        },
    },
    components: {
        SelectAllProductsModal,
        SelectInput,
        TextAreaInput,
        NumberInput,
    },
    data() {
        return {
            productToggle: false,
            products: [],
            selectedProducts: [],
            additionalDescriptionToggled: {},
        };
    },
    computed: {
        ...mapGetters("periods", ["periods"]),
        ...mapGetters(["errors"]),
        // sums and returns the nettoTotal for all products
        computeNetto() {
            return this.selectedProducts.reduce((accumulator, currentValue) => {
                return +accumulator + +currentValue.nettoTotal;
            }, 0);
        },
        // returns key, value pairs of tax rate with tax amount
        computeMwst() {
            const mwst = {};
            this.selectedProducts.forEach((product) => {
                // fix tax to 0 floating points
                const tax =
                    typeof product.tax == "string"
                        ? (+product.tax).toFixed(0)
                        : product.tax;
                // if tax exists then add
                if (tax) {
                    mwst[tax] = (
                        +(mwst?.[tax] ?? 0) + +product.taxRate
                    ).toFixed(2);
                }
            });
            return mwst;
        },
        // sums the netto total and the total tax accross products
        computeSummary() {
            let sum = +this.computeNetto;
            for (let key in this.computeMwst) {
                sum += +this.computeMwst[key];
            }
            return sum.toFixed(2);
        }
    },
    watch: {
        selectedProducts: {
            handler: function (val) {
                this.contractField.value = [...val];
            },
            deep: true,
        },
        productToggle(newVal) {
            if (newVal) {
                document.body.classList.add("modal-layout");
            } else {
                document.body.classList.remove("modal-layout");
            }
        },
        propProducts(newVal) {
            if (newVal) {
                this.selectedProducts = newVal
            }
        },
    },
    async mounted() {
        // sync products received from API
        this.addProducts(this.propProducts);
        try {
            if (!this.period?.data?.length) {
                await this.$store.dispatch("periods/list");
            }
        } catch (e) {
            console.log(e);
        }
    },
    methods: {
        hideModal(){
            this.productToggle = false;
        },
        /**
         * adjusts all the dependant netto total product properties when netto total is modifed
         * @param {index} index of the modified product
         * @param {event} input event
         */
        nettoTotalChanged(index, event) {
            const product = this.selectedProducts[index];
            if (product.type === "ams") {
                const hourlyRate =
                    (100 * product.nettoTotal) /
                    (100 * product.quantity -
                        product.discount * product.quantity);
                const taxRate = (+product.nettoTotal * +product.tax) / 100;
                product.taxRate = taxRate.toFixed(2);
                product.hourlyRate = hourlyRate.toFixed(2);
            } else if (product.type === "service") {
                const hourlyRate =
                    (100 * product.nettoTotal) /
                    (100 * product.quantity -
                        product.discount * product.quantity);
                const taxRate = (+product.nettoTotal * +product.tax) / 100;
                product.taxRate = taxRate.toFixed(2);
                product.hourlyRate = hourlyRate.toFixed(2);
                product.bruttoTotal = (+product.nettoTotal + +taxRate).toFixed(
                    2
                );
            } else if (product.type === "hosting") {
                const pricePerPeriod =
                    (100 * product.nettoTotal) /
                    (100 * product.quantity -
                        product.discount * product.quantity);
                const taxRate = (+product.nettoTotal * +product.tax) / 100;
                product.taxRate = taxRate.toFixed(2);
                product.pricePerPeriod = pricePerPeriod.toFixed(2);
            } else if (product.type === "cloud-software") {
                const pricePerPeriod =
                    (100 * product.nettoTotal) /
                    (100 * product.quantity -
                        product.discount * product.quantity);
                const taxRate = (+product.nettoTotal * +product.tax) / 100;
                product.taxRate = taxRate.toFixed(2);
                product.pricePerPeriod = pricePerPeriod.toFixed(2);
            } else if (
                product.type === "software" ||
                product.type === "pauschal" ||
                product.type === "traveling"
            ) {
                product.salePrice = (
                    product.nettoTotal /
                    (1 - product.discount / 100) /
                    2
                ).toFixed(2);
            }
            this.selectedProducts[index] = { ...product };
        },
        /**
         * recalculates the values of all the tax rate dependant properties of the service product when tax rate input value is changed
         * @param {index} index of the modified product
         * @param {event} input event
         */
        taxRateChanged(index, event) {
            const service = this.selectedProducts[index];
            service.nettoTotal = (
                (100 * service.taxRate) /
                service.tax
            ).toFixed(2);
            const hourlyRate =
                (100 * service.nettoTotal) /
                (100 * service.quantity - service.discount * service.quantity);
            service.hourlyRate = hourlyRate.toFixed(2);
            service.bruttoTotal = (
                +service.nettoTotal + +service.taxRate
            ).toFixed(2);
            this.selectedProducts[index] = { ...service };
        },
        /**
         * whenever the quantity, sale price etc of a product is modified we recalculate all the properties of products that are dependant on the changed property
         * @param {index} index of modified product
         * @param {event} input event
         */
        fieldChanged(index, event) {
            this.selectedProducts[index] = this.modifyProduct(
                this.selectedProducts[index]
            );
        },
        /**
         * adds additional properties to product, needed for calculations
         * @param {product} product to be modifed
         */
        modifyProduct(product) {
            // toggle additional description if present on the product
            if (product.attachmentProductId) {
                this.additionalDescriptionToggled[product.attachmentProductId] =
                    (product?.additionalDescription ?? "").length;
            }

            if (product.type === "hosting") {
                const modifiedProduct = { ...product };
                const totalWithoutDiscount =
                    +modifiedProduct.quantity * +modifiedProduct.pricePerPeriod;
                const discountAmount =
                    (+totalWithoutDiscount * +modifiedProduct.discount) / 100;
                const nettoTotal = +totalWithoutDiscount - +discountAmount;
                const taxRate = (+nettoTotal * +modifiedProduct.tax) / 100;
                const partnerPrice = totalWithoutDiscount - discountAmount
                return {
                    ...modifiedProduct,
                    productId: product.id,
                    componentType: this.type,
                    totalNetto: nettoTotal,
                    partnerPrice: partnerPrice,
                    discountAmount: modifiedProduct?.discountPriceFromGlobalSetting ?? 0,
                    attachmentProductId: product.attachmentProductId ?? uuid(), //uuid is required because we need a unique identifier for additionalDescriptionToggled since multiple products with the same id can be added
                    discount: modifiedProduct.discount ?? 0,
                    paymentPeriod: modifiedProduct?.paymentPeriod?.id,
                    nettoTotal: nettoTotal,
                    taxRate: taxRate,
                    additionalDescription:
                        modifiedProduct?.additionalDescription ?? "",
                };
            } else if (product.type === "cloud-software") {
                const totalWithoutDiscount =
                    +product.quantity * +product.salePrice;
                const discountAmount =
                    (+totalWithoutDiscount * +product.discount) / 100;
                const nettoTotal = +totalWithoutDiscount - +discountAmount;
                const taxRate = (+nettoTotal * +product.tax) / 100;
                const partnerPrice = totalWithoutDiscount - discountAmount
                return {
                    ...product,
                    productId: product.id,
                    componentType: this.type,
                    totalNetto: nettoTotal,
                    partnerPrice: partnerPrice,
                    discountAmount: discountAmount,
                    attachmentProductId: product.attachmentProductId ?? uuid(), //uuid is required because we need a unique identifier for additionalDescriptionToggled since multiple products with the same id can be added
                    discount: product.discount ?? 0,
                    paymentPeriod: product?.paymentPeriod?.id,
                    nettoTotal: nettoTotal,
                    taxRate: taxRate,
                    duration: 1,
                    additionalDescription: product?.additionalDescription ?? "",
                };
            } else if (
                product.type === "software" ||
                product.type === "pauschal" ||
                product.type === "traveling"
            ) {
                const totalWithoutDiscount =
                    +product.quantity * +product.salePrice;
                const discountAmount =
                    (+totalWithoutDiscount * +product.discount) / 100;
                const modifiedProduct = { ...product };
                modifiedProduct.totalPrice =
                    modifiedProduct.salePrice * modifiedProduct.quantity;
                modifiedProduct.totalSalePriceAdjustedForQuantity =
                    modifiedProduct.salePrice * modifiedProduct.quantity;
                if (modifiedProduct?.discount) {
                    modifiedProduct.totalPrice =
                        modifiedProduct.totalPrice *
                        (1 - modifiedProduct?.discount / 100);
                }
                modifiedProduct.nettoTotal =
                    modifiedProduct.totalPrice.toFixed(2);
                if (!!modifiedProduct?.tax)
                    modifiedProduct.totalPrice =
                        modifiedProduct.totalPrice *
                        (modifiedProduct?.tax / 100 + 1);
                modifiedProduct.totalPrice =
                    modifiedProduct.totalPrice.toFixed(2);
                const taxRate =
                    (+modifiedProduct.nettoTotal * +modifiedProduct.tax) / 100;
                const partnerPrice = totalWithoutDiscount - discountAmount
                return {
                    ...modifiedProduct,
                    productId: product.id,
                    componentType: this.type,
                    partnerPrice: partnerPrice,
                    discountAmount: discountAmount,
                    totalNetto: modifiedProduct.nettoTotal,
                    paymentPeriod: modifiedProduct?.paymentPeriod?.id,
                    attachmentProductId: product.attachmentProductId ?? uuid(), //uuid is required because we need a unique identifier for additionalDescriptionToggled since multiple products with the same id can be added
                    discount: modifiedProduct.discount ?? 0,
                    taxRate: taxRate,
                    additionalDescription:
                        modifiedProduct?.additionalDescription ?? "",
                };
            } else if (product.type === "ams") {
                const modifiedProduct = { ...product };
                const totalWithoutDiscount =
                    +modifiedProduct.hourlyRate * +modifiedProduct.quantity;
                const discountAmount =
                    (+totalWithoutDiscount * +modifiedProduct.discount) / 100;
                const nettoTotal = +totalWithoutDiscount - +discountAmount;
                const taxRate = (+nettoTotal * +modifiedProduct.tax) / 100;
                const partnerPrice = totalWithoutDiscount - discountAmount
                return {
                    productId: product.id,
                    componentType: this.type,
                    totalNetto: nettoTotal,
                    partnerPrice: partnerPrice,
                    discountAmount: discountAmount,
                    ...modifiedProduct,
                    attachmentProductId: product.attachmentProductId ?? uuid(), //uuid is required because we need a unique identifier for additionalDescriptionToggled since multiple products with the same id can be added
                    discount: modifiedProduct.discount ?? 0,
                    paymentPeriod: modifiedProduct?.paymentPeriod?.id,
                    nettoTotal: nettoTotal,
                    taxRate: taxRate,
                    additionalDescription:
                        modifiedProduct?.additionalDescription ?? "",
                };
            } else if (product.type === "service") {

                const modifiedProduct = { ...product };
                const totalWithoutDiscount =
                    +modifiedProduct.quantity * +modifiedProduct.hourlyRate;
                const discountAmount =
                    (+totalWithoutDiscount * +modifiedProduct.discount) / 100;
                const nettoTotal = +totalWithoutDiscount - +discountAmount;
                const taxRate = (+nettoTotal * +modifiedProduct.tax) / 100;
                const partnerPrice = totalWithoutDiscount - discountAmount
                return {
                    ...modifiedProduct,
                    productId: product.id,
                    componentType: this.type,
                    totalNetto: nettoTotal,
                    partnerPrice: partnerPrice,
                    discountAmount: discountAmount,
                    paymentPeriod: modifiedProduct?.paymentPeriod?.id,
                    attachmentProductId: product.attachmentProductId ?? uuid(), //uuid is required because we need a unique identifier for additionalDescriptionToggled since multiple products with the same id can be added
                    discount: modifiedProduct.discount ?? 0,
                    tax: +modifiedProduct.tax,
                    nettoTotal: nettoTotal,
                    taxRate: taxRate,
                    bruttoTotal: +nettoTotal + +taxRate,
                    additionalDescription:
                        modifiedProduct?.additionalDescription ?? "",
                    children: modifiedProduct?.children ?? [],
                };
            }
        },
        /**
         * removes the product from the ams array
         * @param {index} the index in ams array for which the product must be removed
         */
        removeOption(index) {
            this.selectedProducts.splice(index, 1);
        },
        /**
         * toggles the products modal
         * calls the products API to load the relevant products based on type if not already loaded
         */
        async toggleProductsModal() {
            try {
                if (this.type == "partner_price_list") {
                    const response = await this.$store.dispatch(
                        "products/productsWithQuantity",
                        {
                            docsheroPriceList: true, // filter products by the default price list of this company
                        }
                    );
                    this.products = response?.data?.products ?? {
                        data: [],
                        links: [],
                    };
                    this.productToggle = true; // toggles the select-ams-modal
                }
                if (this.type == 'own_price_list') {
                    const response = await this.$store.dispatch(
                        "products/productsWithQuantity",
                        {
                            partnerId: this.partnerId?.id ?? '', // filter products by the default price list of this company
                        }
                    );
                    this.products = response?.data?.products ?? {
                        data: [],
                        links: [],
                    };
                    this.productToggle = true; // toggles the select-ams-modal
                }
                // checks if the products have already been loaded
                // sets the products from the API response
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * add selected products to selectedProducts array
         * @param {products} selected products
         */
        addProducts(products) {
            products.forEach((product) => {
                this.selectedProducts = [
                    ...this.selectedProducts,
                    this.modifyProduct(product),
                ].filter((product) => !!product);
            });
            this.productToggle = false;
        },
        /**
         * sets the additionalDescriptionToggled object value based on the index and type
         * @param {id} id of the product for which to toggle additional information dropdown
         * @param {index} index of the product
         * @param {type} possible values (add, remove)
         */
        toggleAdditionalDescriptionDropdowns(id, index, type) {
            this.additionalDescriptionToggled[id] = type === "add";
            // if removed set the additional description values of the product back to default
            if (type === "remove") {
                this.ams[index]["additionalDescription"] = "";
            }
        },
    },
};
</script>

<style scoped lang="scss">
td > :only-child {
    max-width: none !important;
    overflow: none !important;
    text-overflow: none;
    white-space: nowrap;
    display: block;
    padding: 0px;
}

/* break text on hover */
td > :only-child:hover {
    text-overflow: clip;
    white-space: normal;
    word-break: break-all;
}
.table-align {
    width: 250px !important;
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
    &:hover {
        text-overflow: inherit;
        white-space: wrap;
    }
}
.input-width {
    width: 100px !important;
}
</style>

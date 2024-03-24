<template>
    <div class="py-2">
        <div class="flex items-center justify-between">
            <h3 class="card-title">
                {{ category.name }}
            </h3>
            <button
                type="button"
                @click="toggleProductsModal()"
                class="secondary-btn"
            >
                    {{ $t("Add Products") }}
            </button>
        </div>
        <div class="table-responsive mt-3">
            <table class="doc-table">
                <thead>
                    <tr>
                        <th
                            class=""
                        >
                            {{ $t("POS") }}
                        </th>
                        <th
                            class=""
                        >
                            {{ $t("Article Nr.") }}
                        </th>
                        <th
                            class=""
                        >
                            {{ $t("Name") }}
                        </th>
                        <th
                            class=""
                        >
                            {{ $t("Software") }}
                        </th>
                        <th
                            class=""
                        >
                            {{ $t("Quantity") }}/{{ $t("Unit") }}
                        </th>
                        <th
                            class=""
                        >
                            {{ $t("Hourly Rate") }}
                        </th>
                        <th
                            class=""
                        >
                            {{ $t("Discount") }}
                        </th>
                        <th
                            class=""
                        >
                            {{ $t("Period") }}
                        </th>
                        <th
                            class=""
                        >
                            {{ $t("Netto Total") }}
                        </th>
                        <th
                            class=""
                        ></th>
                    </tr>
                </thead>

                <tbody>
                    <template
                        v-for="(product, index) in ams"
                        :key="index"
                        :tabindex="index"
                    >
                        <tr
                            class="focus:outline-none h-16 border border-gray-100 rounded"
                        >
                            <td class="">
                                <div class="">
                                    {{ index + 1 }}
                                </div>
                            </td>
                            <td class="">
                                <div class="">
                                    {{ product.articleNumber }}
                                </div>
                            </td>
                            <td class="">
                                <div>
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
                                <div
                                    @click="
                                        toggleAdditionalDescriptionDropdowns(
                                            product.contractFieldProductId,
                                            index,
                                            additionalDescriptionToggled[
                                                product.contractFieldProductId
                                            ]
                                                ? 'remove'
                                                : 'add',
                                            'ams'
                                        )
                                    "
                                    class="flex mt-2 cursor-pointer"
                                >
                                    <font-awesome-icon
                                        :class="`cursor-pointer cross mr-2 self-center text-${
                                            additionalDescriptionToggled[
                                                product.contractFieldProductId
                                            ]
                                                ? 'red'
                                                : 'blue'
                                        }-500`"
                                        :icon="`fa-solid fa-circle-${
                                            additionalDescriptionToggled[
                                                product.contractFieldProductId
                                            ]
                                                ? 'minus'
                                                : 'plus'
                                        }`"
                                    />
                                    <p
                                        :class="`text-sm text-${
                                            additionalDescriptionToggled[
                                                product.contractFieldProductId
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
                                                            .contractFieldProductId
                                                    ]
                                                        ? "Remove"
                                                        : "Add"
                                                } Additional Description`
                                            )
                                        }}
                                    </p>
                                </div>
                            </td>
                            <td class="">
                                <div>
                                    {{ product.productSoftware?.name }}
                                </div>
                            </td>
                            <td class="">
                                <div style="display: flex">
                                    <number-input
                                        :show-prefix="false"
                                        @inputEvent="
                                            changedQuantityAMS(index, $event)
                                        "
                                        class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                        type="number"
                                        v-model="product.quantity"
                                        name="quantity"
                                    />
                                    <p class="self-center ml-2">
                                        {{ product?.unit?.name }}
                                    </p>
                                </div>
                            </td>
                            <td class="pl-5 text-center align-top">
                                <div>
                                    <number-input
                                        @inputEvent="
                                            changedQuantityAMS(index, $event)
                                        "
                                        class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                        type="number"
                                        v-model="product.hourlyRate"
                                        name="hourlyRate"
                                    />
                                </div>
                            </td>
                            <td class="pl-5 text-center align-top py-2">
                                <div>
                                    <number-input
                                        @inputEvent="
                                            changedQuantityAMS(index, $event)
                                        "
                                        class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                        type="number"
                                        :show-prefix="false"
                                        v-model="product.discount"
                                        name="discount"
                                    />
                                </div>
                            </td>
                            <td class="pl-5 text-center align-top py-2">
                                <div class="form-group">
                                    <select-input
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
                            <td class="pl-5 text-center align-top">
                                <div>
                                    <number-input
                                        @inputEvent="
                                            amsNettoTotalChanged(event, index)
                                        "
                                        class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                        type="number"
                                        v-model="product.nettoTotal"
                                        name="nettoTotal"
                                        :allowNegative="true"
                                    />
                                </div>
                            </td>
                            <td class="text-center">
                                <font-awesome-icon
                                    @click="removeOption(index)"
                                    class="cursor-pointer cross"
                                    icon="fa-solid fa-xmark"
                                />
                            </td>
                        </tr>
                        <tr
                            v-if="
                                additionalDescriptionToggled[
                                    product.contractFieldProductId
                                ]
                            "
                        >
                            <td></td>
                            <td></td>

                            <td class="pl-6">
                                <TextAreaInput
                                    style="overflow: visible !important"
                                    v-model="product.additionalDescription"
                                    class="pb-1 pr-6 w-full"
                                    :label="$t('Additional Description')"
                                    :error="errors['additionalDescription']"
                                ></TextAreaInput>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </template>
                </tbody>
            </table>
            <br />
            <div
                v-if="Object.keys(ams).length > 0"
                style="
                    flex-direction: column;
                    align-items: end;
                    justify-content: flex-end;
                "
            >
                <div>
                    <div
                        class="grid gap-2 justify-end"
                        style="grid-template-columns: repeat(auto-fit, 100px)"
                    >
                        <p>{{ $t("Netto") }}:</p>
                        <p :class="[globalLanguage === 'de' ? 'text-end' : '']">
                            {{ $formatter(computeNettoAms, globalLanguage) }}
                        </p>
                    </div>
                    <div
                        class="grid gap-2 mt-1 justify-end"
                        style="grid-template-columns: repeat(auto-fit, 100px)"
                        v-for="(value, key) in computeMwstAms"
                        :key="index"
                    >
                        <h1>{{ key }}% {{ $t("Tax") }}:</h1>
                        <h1
                            :class="[globalLanguage === 'de' ? 'text-end' : '']"
                        >
                            {{ $formatter(value, globalLanguage) }}
                        </h1>
                    </div>
                    <div
                        class="grid gap-2 mt-2 justify-end font-bold bg-gray-300"
                        style="
                            grid-template-columns: repeat(auto-fit, 100px);
                            background-image: -webkit-linear-gradient(
                                left,
                                white,
                                white 81%,
                                transparent 70%,
                                transparent 100%
                            );
                        "
                    >
                        <p>{{ $t("Total") }}:</p>
                        <p :class="[globalLanguage === 'de' ? 'text-end' : '']">
                            {{ $formatter(computeSummaryAms, globalLanguage) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <select-ams-modal
        v-if="amsToggle"
        @selected="addAMS"
        @cancelled="amsToggle = false"
        :selectedItems="[]"
        :products="amsProducts"
        :productCategoryId="category.id"
        :removeFilters="true"
    ></select-ams-modal>
</template>

<script>
import SelectAmsModal from "@/Components/SelectAmsModal.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TextAreaInput from "@/Components/TextareaInput.vue";
import NumberInput from "@/Components/NumberInput.vue";
import { v4 as uuid } from "uuid";
import { mapGetters } from "vuex";

export default {
    props: {
        companyId: {
            type: String,
            default: "",
        },
        // products received from API
        products: {
            type: Array,
            default: () => [],
        },
        category: {
            type: Object,
            default: () => ({}),
        },
        attachmentId: {
            type: Number,
            default: 0,
        },
    },
    components: {
        SelectAmsModal,
        SelectInput,
        TextAreaInput,
        NumberInput,
    },
    data() {
        return {
            amsToggle: false,
            amsProducts: [],
            ams: [],
            additionalDescriptionToggled: {},
        };
    },
    watch: {
        ams(val) {
            this.$emitter.emit("amsProductsByCategory", {
                attachment: this.attachmentId,
                category: this.category.id,
                products: val,
            });
        },
    },
    computed: {
        ...mapGetters("periods", ["periods"]),
        ...mapGetters(["errors"]),
        // sums and returns the nettoTotal for all products
        computeNettoAms() {
            return this.ams.reduce((accumulator, currentValue) => {
                return +accumulator + +currentValue.nettoTotal;
            }, 0);
        },
        // returns key, value pairs of tax rate with tax amount
        computeMwstAms() {
            const mwst = {};
            this.ams.forEach((product) => {
                // fix tax to 0 floating points
                const tax =
                    typeof product.tax == "string"
                        ? (+product.tax).toFixed(0)
                        : product.tax;
                // only add if tax is truthy
                if (tax) {
                    mwst[tax] = (
                        +(mwst?.[tax] ?? 0) + +product.taxRate
                    ).toFixed(2);
                }
            });
            return mwst;
        },
        // sums the netto total and the total tax accross products
        computeSummaryAms() {
            let sum = +this.computeNettoAms;
            for (let key in this.computeMwstAms) {
                sum += +this.computeMwstAms[key];
            }
            return sum.toFixed(2);
        },
    },
    async mounted() {
        // sync products received from API
        this.addAMS(this.products);
        // fetch the periods listing if not fetched
        try {
            if (!this.periods?.data?.length) {
                await this.$store.dispatch("periods/list");
            }
        } catch (e) {
            console.log(e);
        }
    },
    methods: {
        /**
         * toggles the products modal
         * calls the products API to load the relevant products based on type if not already loaded
         */
        async toggleProductsModal() {
            try {
                // checks if the products have already been loaded
                const response = await this.$store.dispatch(
                    "products/productsWithQuantity",
                    {
                        type: "ams",
                        companyId: this.companyId, // filter products by the default price list of this company
                        productCategoryId: this.category.id,
                    }
                );
                // sets the products from the API response
                this.amsProducts = response?.data?.products ?? {
                    data: [],
                    links: [],
                };
                this.amsToggle = true; // toggles the select-ams-modal
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * removes the product from the ams array
         * @param {index} the index in ams array for which the product must be removed
         */
        removeOption(index) {
            this.ams.splice(index, 1);
        },
        /**
         * readjusts all the product properties that are dependant on netto total when netto total is changed
         * @param {event} input event
         * @param {index} product index whose netto total was modified
         */
        async amsNettoTotalChanged(event, index) {
            await this.$nextTick();
            const product = this.ams[index];
            const hourlyRate =
                (100 * product.nettoTotal) /
                (100 * product.quantity - product.discount * product.quantity);
            const taxRate = (+product.nettoTotal * +product.tax) / 100;
            product.taxRate = taxRate.toFixed(2);
            product.hourlyRate = hourlyRate.toFixed(2);
            this.ams[index] = { ...product };
        },
        /**
         * readjusts all the product properties that are dependant on quantity when quantity is changed
         * @param {event} input event
         * @param {index} product index whose netto total was modified
         */
        async changedQuantityAMS(index, event) {
            await this.$nextTick();
            const product = this.ams[index];
            const totalWithoutDiscount =
                +product.quantity * +product.hourlyRate;
            const discountAmount =
                (+totalWithoutDiscount * +product.discount) / 100;
            const nettoTotal = +totalWithoutDiscount - +discountAmount;
            const taxRate = (+nettoTotal * +product.tax) / 100;
            product.nettoTotal = nettoTotal.toFixed(2);
            product.taxRate = taxRate.toFixed(2);
            this.ams[index] = { ...product };
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
        /**
         * add selected products from modal to 'ams' array
         * @param {products} the selected products array
         */
        async addAMS(products) {
            this.amsToggle = false;
            this.globalPeriod = products?.[0]?.paymentPeriod?.id ?? "";
            this.ams = [
                ...this.ams,
                ...products.map((product) => {
                    // toggle additional description if present on the product
                    if (product.contractFieldProductId) {
                        this.additionalDescriptionToggled[
                            product.contractFieldProductId
                        ] = (product?.additionalDescription ?? "").length;
                    }
                    const modifiedProduct = { ...product };
                    const totalWithoutDiscount =
                        +modifiedProduct.hourlyRate * +modifiedProduct.quantity;
                    const discountAmount =
                        (+totalWithoutDiscount * +modifiedProduct.discount) /
                        100;
                    const nettoTotal = +totalWithoutDiscount - +discountAmount;
                    const taxRate = (+nettoTotal * +modifiedProduct.tax) / 100;
                    return {
                        ...modifiedProduct,
                        contractFieldProductId:
                            product.contractFieldProductId ?? uuid(), //uuid is required because we need a unique identifier for additionalDescriptionToggled since multiple products with the same id can be added
                        discount: modifiedProduct.discount ?? 0,
                        paymentPeriod: modifiedProduct?.paymentPeriod?.id,
                        nettoTotal: nettoTotal,
                        taxRate: taxRate,
                        additionalDescription:
                            modifiedProduct?.additionalDescription ?? "",
                    };
                }),
            ];
        },
    },
};
</script>

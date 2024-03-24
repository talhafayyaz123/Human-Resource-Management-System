<template>
    <div>
        <div
            class="custom-modal"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true"
        >
            <div class="modal-overlay"></div>

            <div class="modal-content">
                <div
                    class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0"
                >
                    <div class="modal-bg modal-lg">
                        <div class="modal-header">
                            <div>
                                <h2 class="">
                                    {{ $t("Add" + " " + typeOfItem) }}
                                </h2>
                                <slot name="warning"></slot>
                            </div>
                            <div
                                class="cursor-pointer"
                                @click="$emit('toggleModal', false)"
                            >
                                <CustomIcon name="xCircleIcon" />
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="grid items-center grid-cols-3 gap-6">
                                <div class="w-full">
                                    <div class="form-group">
                                        <MultiSelectInput
                                            v-model="form.productPriceId"
                                            :options="productPrices?.data"
                                            :multiple="false"
                                            :textLabel="$t('Price List')"
                                            label="name"
                                            :trackBy="'id'"
                                            :key="form.productPriceId"
                                            :moduleName="'productPrice'"
                                            :taggable="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <MultiSelectInput
                                            v-model="form.productSoftwareId"
                                            :options="softwares.data"
                                            :multiple="false"
                                            :textLabel="$t('Software')"
                                            label="name"
                                            :key="form.productSoftwareId"
                                            :trackBy="'id'"
                                            :moduleName="'Software'"
                                            :taggable="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <search-filter
                                            v-model="form.search"
                                            class="mr-4 w-full max-w-md"
                                            @reset="reset"
                                        >
                                            <div class="form-group">
                                                <select-input
                                                    v-model="form.status"
                                                    :label="$t('Status')"
                                                >
                                                    <option value="active">
                                                        {{ $t("active") }}
                                                    </option>
                                                    <option value="inactive">
                                                        {{ $t("inactive") }}
                                                    </option>
                                                </select-input>
                                            </div>
                                            <div class="form-group my-[20px]">
                                                <MultiSelectInput
                                                    v-model="
                                                        form.product_group_id
                                                    "
                                                    :options="groups.data"
                                                    :multiple="false"
                                                    textLabel="Product Group"
                                                    label="name"
                                                    :trackBy="'id'"
                                                    :moduleName="'groups'"
                                                    :taggable="true"
                                                />
                                            </div>
                                            <div class="form-group">
                                                <select-input
                                                    v-model="form.type"
                                                    :label="$t('Type')"
                                                >
                                                    <option value="">
                                                        {{ $t("All") }}
                                                    </option>
                                                    <option value="software">
                                                        {{ $t("Software") }}
                                                    </option>
                                                    <option value="service">
                                                        {{ $t("Service") }}
                                                    </option>
                                                    <option value="pauschal">
                                                        {{ $t("Pauschal") }}
                                                    </option>
                                                    <option value="ams">
                                                        {{ $t("AMS") }}
                                                    </option>
                                                    <option value="hosting">
                                                        {{ $t("Hosting") }}
                                                    </option>
                                                    <option
                                                        value="cloud-software"
                                                    >
                                                        {{
                                                            $t("Cloud Software")
                                                        }}
                                                    </option>
                                                </select-input>
                                            </div>
                                        </search-filter>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive modal-table mt-4">
                                <table class="doc-table">
                                    <thead>
                                        <tr>
                                            <th class="">
                                                {{ $t("Article number") }}
                                            </th>
                                            <th class="">
                                                {{ $t("Name") }}
                                            </th>
                                            <th class="">
                                                {{ $t("Product Group") }}
                                            </th>
                                            <th v-if="!isLicense" class="">
                                                {{ $t("Status") }}
                                            </th>

                                            <th
                                                v-if="
                                                    productType ===
                                                        'software' ||
                                                    productType ===
                                                        'cloud-software'
                                                "
                                                class=""
                                            >
                                                {{ $t("Sale price") }}
                                            </th>
                                            <th
                                                v-if="
                                                    productType === 'hosting' ||
                                                    productType ===
                                                        'cloud-software'
                                                "
                                                class=""
                                            >
                                                {{ $t("Price Per Period") }}
                                            </th>
                                            <th v-if="!isLicense" class="">
                                                {{ $t("Discount") }}
                                            </th>
                                            <th v-if="isLicense" class="">
                                                {{ $t("Software Type") }}
                                            </th>
                                            <th class="">
                                                {{ $t("Quantity") }}
                                            </th>
                                            <th
                                                v-if="
                                                    productType ===
                                                        'software' ||
                                                    productType ===
                                                        'cloud-software'
                                                "
                                                class=""
                                            >
                                                {{ $t("Profit") }}
                                            </th>
                                            <th
                                                v-if="
                                                    productType ===
                                                        'software' ||
                                                    productType ===
                                                        'cloud-software'
                                                "
                                                class=""
                                            >
                                                {{ $t("Listing price") }}
                                            </th>
                                            <th v-if="!isLicense" class="">
                                                {{ $t("Tax") }}
                                            </th>
                                            <th v-if="isLicense" class="">
                                                {{ $t("Sale Price Per Unit") }}
                                            </th>
                                            <th v-if="isLicense" class="">
                                                {{ $t("Sale Price") }}
                                            </th>
                                            <th v-if="isLicense" class="">
                                                {{
                                                    $t(
                                                        "Maintenance Rate Per Unit"
                                                    )
                                                }}
                                            </th>
                                            <th v-if="isLicense" class="">
                                                {{ $t("Maintenance Rate") }}
                                            </th>
                                            <th v-if="isLicense" class="">
                                                {{ $t("Evaluation License") }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(
                                                item, index
                                            ) in dataProducts?.data"
                                            :key="index"
                                        >
                                            <td
                                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                            >
                                                <div class="flex items-center">
                                                    <input
                                                        @click="
                                                            onSelected(
                                                                $event,
                                                                item,
                                                                index
                                                            )
                                                        "
                                                        :checked="
                                                            isInputChecked(item)
                                                        "
                                                        type="checkbox"
                                                        class="border-gray-300 rounded h-5 w-5"
                                                    />
                                                    &nbsp;

                                                    {{ item.articleNumber }}
                                                </div>
                                            </td>
                                            <td
                                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                            >
                                                <TextInput
                                                    v-if="
                                                        item.isProductNameEdit &&
                                                        isLicense
                                                    "
                                                    v-model="item.name"
                                                />
                                                <p v-else>
                                                    {{ item.name }}
                                                </p>
                                            </td>
                                            <td
                                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                            >
                                                {{ item.productGroup }}
                                            </td>
                                            <td
                                                v-if="!isLicense"
                                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                            >
                                                {{ item.status }}
                                            </td>
                                            <td
                                                v-if="
                                                    productType ===
                                                        'software' ||
                                                    productType ===
                                                        'cloud-software'
                                                "
                                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                            >
                                                {{
                                                    $formatter(
                                                        item?.salePrice,
                                                        globalLanguage
                                                    )
                                                }}
                                            </td>
                                            <td
                                                v-if="productType === 'hosting'"
                                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                            >
                                                {{
                                                    $formatter(
                                                        item?.pricePerPeriod ??
                                                            item?.salePrice,
                                                        globalLanguage
                                                    )
                                                }}
                                            </td>
                                            <td
                                                v-if="!isLicense"
                                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                            >
                                                {{ item.discount }}
                                            </td>
                                            <td
                                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                                v-if="isLicense"
                                            >
                                                {{ item.productSoftware?.name }}
                                            </td>
                                            <td
                                                :class="`${
                                                    isJson(item.quantity)
                                                        ? 'h-5rem'
                                                        : ''
                                                } px-5 py-5 border-b border-gray-200 bg-white text-sm flex`"
                                            >
                                                <div
                                                    v-if="
                                                        !formulaInput &&
                                                        isInputChecked(item) &&
                                                        isEdit
                                                    "
                                                >
                                                    {{
                                                        selectedProducts.find(
                                                            (selected) =>
                                                                selected.id ===
                                                                item.id
                                                        ).quantity
                                                    }}
                                                </div>
                                                <input
                                                    @keypress.enter="
                                                        executeFormula(
                                                            item,
                                                            index
                                                        )
                                                    "
                                                    v-model="item.quantity"
                                                    v-else-if="
                                                        formulaInput &&
                                                        !isJson(
                                                            item.quantity
                                                        ) &&
                                                        !isLicense
                                                    "
                                                    type="text"
                                                    :class="`appearance-none border-2 w-24 ${
                                                        !checkForFormula(
                                                            item.quantity
                                                        )
                                                            ? 'border-gray-200'
                                                            : success
                                                            ? 'border-green-500'
                                                            : 'border-red-500'
                                                    } rounded py-2 px-2 text-gray-700 leading-tight`"
                                                />
                                                <input
                                                    v-else-if="
                                                        (!checkForFormula(
                                                            item.quantity
                                                        ) ||
                                                            !isJson(
                                                                item.quantity
                                                            )) &&
                                                        !isLicense
                                                    "
                                                    class="appearance-none border-2 w-24 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                    type="number"
                                                    @change="
                                                        addChangeQuantity(item)
                                                    "
                                                    v-model="item.quantity"
                                                    :min="0"
                                                    @keypress="
                                                        limitToPositiveNumbers
                                                    "
                                                />
                                                <input
                                                    v-if="isLicense"
                                                    class="appearance-none border-2 w-24 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                    type="number"
                                                    :min="0"
                                                    @keypress="
                                                        limitToPositiveNumbers
                                                    "
                                                    @change="
                                                        quantityChanged(index)
                                                    "
                                                    v-model="item.quantity"
                                                />
                                                <p
                                                    class="self-center ml-2"
                                                    v-if="isJson(item.quantity)"
                                                >
                                                    {{ result(item.quantity) }}
                                                </p>
                                                <p
                                                    class="self-center ml-2"
                                                    v-if="
                                                        checkForFormula(
                                                            item.quantity
                                                        ) &&
                                                        !isJson(item.quantity)
                                                    "
                                                >
                                                    {{
                                                        executeFormulaAndReturnResult(
                                                            item
                                                        )
                                                    }}
                                                </p>
                                                <button
                                                    @click="
                                                        executeFormula(
                                                            item,
                                                            index
                                                        )
                                                    "
                                                    v-if="
                                                        checkForFormula(
                                                            item.quantity
                                                        ) &&
                                                        !isJson(item.quantity)
                                                    "
                                                    class="rounded ml-2 text-white px-2 bg-gray-500"
                                                >
                                                    Add
                                                </button>
                                                <button
                                                    v-if="formulaInput"
                                                    @click="openModal(item)"
                                                    :class="`rounded ml-2 text-white px-2 bg-${
                                                        isJson(item.quantity)
                                                            ? 'green'
                                                            : 'gray'
                                                    }-500`"
                                                >
                                                    f(x)
                                                </button>
                                                <button
                                                    @click="item.quantity = 1"
                                                    v-if="isJson(item.quantity)"
                                                    :class="`rounded ml-2 text-white px-2 bg-red-500`"
                                                >
                                                    Remove
                                                </button>
                                            </td>
                                            <td
                                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                                v-if="
                                                    productType ===
                                                        'software' ||
                                                    productType ===
                                                        'cloud-software'
                                                "
                                            >
                                                {{
                                                    $formatter(
                                                        item.profit,
                                                        globalLanguage
                                                    )
                                                }}
                                            </td>
                                            <td
                                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                                v-if="
                                                    productType ===
                                                        'software' ||
                                                    productType ===
                                                        'cloud-software'
                                                "
                                            >
                                                {{
                                                    $formatter(
                                                        item.listingPrice,
                                                        globalLanguage
                                                    )
                                                }}
                                            </td>
                                            <td
                                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                                v-if="!isLicense"
                                            >
                                                {{
                                                    $formatter(
                                                        item.tax,
                                                        globalLanguage
                                                    )
                                                }}
                                            </td>
                                            <td
                                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                                v-if="isLicense"
                                            >
                                                <NumberInputField
                                                    class="w-24"
                                                    type="number"
                                                    @update:model-value="
                                                        salePriceChanged(index)
                                                    "
                                                    :allowNegative="true"
                                                    v-model="item.salePrice"
                                                />
                                            </td>
                                            <td
                                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                                v-if="isLicense"
                                            >
                                                <NumberInputField
                                                    class="w-24"
                                                    type="number"
                                                    @update:model-value="
                                                        salePriceTotalChanged(
                                                            index
                                                        )
                                                    "
                                                    :allowNegative="true"
                                                    v-model="
                                                        item.salePriceTotal
                                                    "
                                                />
                                            </td>
                                            <td
                                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                                v-if="isLicense"
                                            >
                                                <NumberInputField
                                                    class="w-24"
                                                    type="number"
                                                    @update:model-value="
                                                        maintenancePriceChanged(
                                                            index
                                                        )
                                                    "
                                                    :allowNegative="true"
                                                    v-model="
                                                        item.maintenancePrice
                                                    "
                                                />
                                            </td>
                                            <td
                                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                                v-if="isLicense"
                                            >
                                                <NumberInputField
                                                    class="w-24"
                                                    type="number"
                                                    @update:model-value="
                                                        maintenancePriceTotalChanged(
                                                            index
                                                        )
                                                    "
                                                    :allowNegative="true"
                                                    v-model="
                                                        item.maintenancePriceTotal
                                                    "
                                                />
                                            </td>
                                            <td
                                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                                v-if="isLicense"
                                            >
                                                <input
                                                    class="w-24"
                                                    type="checkbox"
                                                    v-model="item.isEvaluation"
                                                />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="flex justify-center mt-5">
                                <pagination
                                    :limit="10"
                                    align="center"
                                    :data="dataProducts"
                                    @pagination-change-page="searchProduct"
                                ></pagination>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button
                                @click="onCancel"
                                type="button"
                                class="primary-btn mr-3"
                            >
                                <div class="mr-1">
                                    <CustomIcon name="cancelIcon" />
                                </div>
                                {{ $t("Cancel") }}
                            </button>
                            <button
                                @click="onAdded"
                                type="button"
                                class="secondary-btn"
                            >
                                <div class="mr-1">
                                    <CustomIcon name="addIcon" />
                                </div>
                                {{ $t("Add") }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Modal
            title="Formula Builder"
            v-if="toggleModal"
            @toggleModal="toggleModal = $event"
            width="50%"
        >
            <template #body>
                <div class="flex ml-6 mr-6 border mb-3 p-3">
                    <NumberInput
                        :type="'single'"
                        :numberInputs="selectedProduct.quantity"
                        :items="options"
                        :questions="questions"
                    />
                </div>
            </template>
            <template #footer>
                <button
                    @click="addFormula"
                    type="button"
                    class="inline-flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm"
                >
                    Add
                </button>
                <button
                    type="button"
                    class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                >
                    Cancel
                </button>
            </template>
        </Modal>
    </div>
</template>

<script>
import pagination from "laravel-vue-pagination";
import Modal from "./EditModal.vue";
import NumberInput from "../Pages/Surveys/NumberInput.vue";
import TextInput from "@/Components/TextInput.vue";
import NumberInputField from "@/Components/NumberInput.vue";
import allOptionsMixin from "@/Mixins/allOptionsMixin";
import surveyFormulaMixin from "@/Mixins/surveyFormulaMixin";
import licensesCalculationMixin from "@/Mixins/licensesCalculationMixin";
import SearchFilter from "./SearchFilter.vue";
import MultiSelectInput from "./MultiSelectInput.vue";
import { mapGetters } from "vuex";
import SelectInput from "./SelectInput.vue";

import { toRaw } from "vue";

export default {
    mixins: [allOptionsMixin, surveyFormulaMixin, licensesCalculationMixin],
    computed: {
        ...mapGetters("groups", ["groups"]),
        ...mapGetters("softwares", ["softwares"]),
        ...mapGetters("productprice", ["productPrices"]),
    },
    watch: {
        form: {
            deep: true,
            handler: function () {
                this.throttle(async () => {
                    try {
                        const productResponse = await this.$store.dispatch(
                            "products/productsWithQuantity",
                            {
                                ...this.form,
                                productGroupId:
                                    this.form.product_group_id?.id ?? "",
                                productSoftwareId:
                                    this.form.productSoftwareId?.id ?? "",
                                productPriceId:
                                    this.form.productPriceId?.id ?? "",
                                type: this.form.type
                                    ? this.form.type
                                    : this.productType,
                            }
                        );
                        this.dataProducts = productResponse?.data?.products ?? {
                            data: [],
                        };
                    } catch (e) {}
                }, 150)();
            },
        },
    },
    props: {
        isLicense: { type: Boolean, default: false },
        productType: { type: String, default: "software" },
        questions: { type: Array, default: () => [] },
        products: { type: Object, default: () => ({}) },
        selectedItems: { type: Array, default: () => [] },
        typeOfItem: { default: "Products", type: String },
        isEdit: { default: false, type: Boolean },
        fromOffers: { default: false, type: Boolean },
        fromOffersEdit: { default: false, type: Boolean },
        groupedBy: { default: "nothing", type: String },
        formulaInput: { default: false, type: Boolean },
        options: { default: [], type: Array },
        option: { default: {}, type: Object },
        defaultCategory: { default: "", type: String },
    },
    components: {
        pagination,
        Modal,
        NumberInput,
        NumberInputField,
        SearchFilter,
        MultiSelectInput,
        SelectInput,
        TextInput,
    },
    data() {
        return {
            page: 1,
            selectedProduct: null,
            toggleModal: false,
            selectedProducts: [],
            search: "",
            dataProducts: toRaw(this.products), //to remove reactivity
            success: false,
            form: {
                product_group_id: "",
                status: "",
                search: "",
                productSoftwareId: "",
                type: "",
                productPriceId: "",
            },
        };
    },
    mounted() {
        /**
         * if we are coming from the licenses section in companies, then the 'isLicense' will be true
         * if so then we must modify the products listing because there are some values for the products listing
         * that must be calculted on the run
         */

        this.modifyProductsIfLicense();
        this.modifySelectProducts(); // sync products listing with selectedItems
        if (!this.groups.data.length) {
            this.$store.dispatch("groups/list");
        }
        if (!this.softwares.data.length) {
            this.$store.dispatch("softwares/list");
        }
        this.$store.dispatch("productprice/list", {
            status: "active",
        });
        const clonedValue = this.cloneDeep(this.selectedItems); //added lodash because dont want two way data binding
        this.selectedProducts = clonedValue;
    },

    methods: {
        /**
         * modifies the products listing based on the selected products received as props
         * the quantity, tax, discount, maintenanceRate and salePrice can be different in selectedItems
         * hence we need to sync the products listing from API and the selected items
         */
        modifySelectProducts() {
            this.selectedItems.forEach((product) => {
                const productIndex = this.dataProducts.data.findIndex(
                    (pr) =>
                        pr.id ==
                        (this.fromOffersEdit
                            ? product?.productId ?? product.id
                            : product.id)
                );
                if (productIndex > -1) {
                    const foundProduct = {
                        ...(this.fromOffers
                            ? product?.product
                                ? {
                                      ...product.product,
                                      quantity: product.quantity,
                                      tax: product.tax,
                                      discount: product.discount,
                                      maintenanceRate: product.maintenanceRate,
                                      salePrice: product.salePrice,
                                  }
                                : product
                            : product),
                    };
                    this.dataProducts.data[productIndex] = {
                        ...foundProduct,
                        status:
                            typeof foundProduct.status === "string"
                                ? foundProduct.status
                                : foundProduct.status == 1
                                ? "active"
                                : "inactive",
                        quantity: this.checkForFormula(foundProduct.quantity)
                            ? (() => {
                                  let matches = foundProduct.quantity.match(
                                      /(?<=\$).+?(?=\+|\*|\-|\/|\%|\^|\)|$)/g
                                  );
                                  let quantity = foundProduct.quantity;
                                  matches?.forEach((match) => {
                                      const foundOption = this.options.find(
                                          (option) =>
                                              this.allOptions[option].uuid ===
                                              match
                                      );
                                      if (!foundOption) {
                                          return 1;
                                      }
                                      quantity = quantity.replace(
                                          "$" + match,
                                          "$" +
                                              this.allOptions[
                                                  foundOption
                                              ]?.title.toLowerCase()
                                      );
                                  });
                                  return quantity;
                              })()
                            : this.isJson(foundProduct.quantity)
                            ? this.result(foundProduct.quantity)
                            : foundProduct.quantity,
                    };
                }
            });
            /**
             * to preserve the changed quantity of a selected product when swtiching between pages
             * we find the product in selectedProducts, because selectedProducts contains the latest modified
             * quanity of the product. The products in dataProducts changes when the page changes, so the quantity is
             * reset and we are left with the previous quantity received from selecteItems. This preserved the user
             * entered quantity even when the pages are swtiched
             */
            this.selectedProducts.forEach((product) => {
                const productIndex = this.dataProducts.data.findIndex(
                    (pr) => pr.id == product.id
                );
                if (productIndex > -1) {
                    const foundProduct = this.dataProducts.data[productIndex];
                    const productInSelectedProducts =
                        this.selectedProducts.find(
                            (pr) => pr.id == foundProduct.id
                        );
                    if (productInSelectedProducts) {
                        foundProduct.quantity =
                            productInSelectedProducts.quantity;
                    }
                    this.dataProducts.data[productIndex] = { ...foundProduct };
                }
            });
        },
        /**
         * adds the 'salePriceTotal' and 'maintenancePriceTotal'to products listing, we are not retrieving it from the backend
         */
        modifyProductsIfLicense() {
            if (this.isLicense && this.dataProducts?.data instanceof Array) {
                this.dataProducts.data = this.dataProducts.data.map(
                    (product) => {
                        return {
                            ...product,
                            salePriceTotal:
                                product.quantity * product.salePrice,
                            maintenancePrice: product.maintenancePrice ?? 0,
                            maintenancePriceTotal:
                                product.quantity * product.maintenancePrice,
                        };
                    }
                );
            }
        },
        reset() {
            this.form = this.mapValues(this.form, () => null);
        },
        addFormula() {
            const productIndex = this.dataProducts?.data.findIndex(
                (product) => product.id == this.selectedProduct.id
            );
            if (productIndex > -1) {
                const product = this.dataProducts.data[productIndex];
                product.quantity = JSON.stringify(
                    this.selectedProduct.quantity
                );
                this.dataProducts.data[productIndex] = { ...product };
            }
            this.toggleModal = false;
        },
        openModal(item) {
            this.selectedProduct = { ...item };
            this.selectedProduct["quantity"] = this.isJson(
                this.selectedProduct["quantity"]
            )
                ? JSON.parse(this.selectedProduct["quantity"])
                : [
                      {
                          type: "single",
                          operator: null,
                          value: "",
                          parenthesis: [],
                      },
                  ];
            this.toggleModal = true;
        },
        executeFormulaAndReturnResult(item) {
            try {
                if (this.checkForFormula(item.quantity)) {
                    let matches = item?.quantity?.match(
                        /(?<=\$).+?(?=\+|\*|\-|\/|\%|\^|\)|$)/g
                    );
                    let quantity = item.quantity;
                    matches?.forEach((match) => {
                        const foundOption = this.options.find(
                            (option) =>
                                this.allOptions[option].title.toLowerCase() ===
                                match.toLowerCase()
                        );
                        if (!foundOption) {
                            return 1;
                        }
                        quantity = quantity.replace(
                            "$" + match,
                            +this.allOptions[foundOption]?.value
                        );
                    });
                    try {
                        quantity = this.javascriptExecuter(quantity.slice(1));
                        this.success = quantity === undefined ? false : true;
                    } catch (e) {
                        quantity = 1;
                        this.success = false;
                    }
                    return quantity;
                } else {
                    return item.quantity;
                }
            } catch (e) {
                return item?.quantity ?? 1;
            }
        },
        onSelected(event, item) {
            if (event.target.checked) {
                this.selectedProducts.push(item);
            } else {
                this.selectedProducts = this.selectedProducts.filter(
                    (product) => product.id !== item.id
                );
            }
        },
        addChangeQuantity(item) {
            try {
                this.selectedProducts.find(
                    (selected) => selected.id === item.id
                ).quantity = item.quantity;
            } catch (e) {}
        },
        isInputChecked(product) {
            const found = this.selectedProducts.some((el) => {
                if (this.fromOffers) {
                    return (
                        (el?.product ? el?.product?.id : el.id) === product.id
                    );
                } else {
                    return el.id === product.id;
                }
            });
            return found;
        },
        onAdded() {
            this.$emit(
                "selected",
                this.selectedProducts.map((product) => {
                    const foundProduct =
                        this.dataProducts.data.find((pr) => {
                            return (
                                pr.id ==
                                (this.fromOffersEdit
                                    ? product?.productId ?? product.id
                                    : product.id)
                            );
                        }) ?? product;
                    return {
                        ...foundProduct,
                        quantity: this.checkForFormula(foundProduct.quantity)
                            ? (() => {
                                  let matches = foundProduct?.quantity?.match(
                                      /(?<=\$).+?(?=\+|\*|\-|\/|\%|\^|\)|$)/g
                                  );
                                  let quantity = foundProduct.quantity;
                                  matches?.forEach((match) => {
                                      const foundOption = this.options.find(
                                          (option) =>
                                              this.allOptions[
                                                  option
                                              ].title.toLowerCase() ===
                                              match.toLowerCase()
                                      );
                                      if (!foundOption) {
                                          return 1;
                                      }
                                      quantity = quantity.replace(
                                          "$" + match,
                                          "$" +
                                              this.allOptions[foundOption]?.uuid
                                      );
                                  });
                                  return quantity;
                              })()
                            : foundProduct.quantity,
                    };
                })
            );
        },
        onCancel() {
            this.$emit("cancelled");
        },
        async searchProduct(page = 1) {
            this.page = page;
            try {
                const productResponse = await this.$store.dispatch(
                    "products/productsWithQuantity",
                    {
                        page: page,
                        search: this.form.search,
                        type: this.form.type
                            ? this.form.type
                            : this.productType,
                        productPriceId: this.form.productPriceId?.id ?? "",
                        productGroupId: this.form.product_group_id?.id ?? "",
                    }
                );
                this.dataProducts = productResponse?.data?.products ?? {
                    data: [],
                };
                /**
                 * if we are coming from the licenses section in companies, then the 'isLicense' will be true
                 * if so then we must modify the products listing because there are some values for the products listing
                 * that must be calculted on the run
                 */
                this.modifyProductsIfLicense();
                this.modifySelectProducts(); // sync products listing with selectedItems
            } catch (e) {
                console.log(e);
            }
        },
    },
};
</script>

<style scoped>
.h-5rem {
    height: 5rem;
}
</style>

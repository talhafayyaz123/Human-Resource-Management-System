<template>
    <div>
        <div
            v-if="flashMessage.type === 'success' && flashMessage.show"
            :class="`flex items-center justify-between mb-8 max-w-3xl bg-green-500 rounded flash-message`"
        >
            <div class="flex items-center">
                <svg
                    class="flex-shrink-0 ml-4 mr-2 w-4 h-4 fill-white"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                >
                    <polygon points="0 11 2 9 7 14 18 3 20 5 7 18" />
                </svg>
                <div class="py-4 text-white text-sm font-medium">
                    {{ flashMessage.message }}
                </div>
            </div>
            <button
                type="button"
                class="group mr-2 p-2"
                @click="
                    flashMessage = {
                        show: false,
                        type: '',
                        message: '',
                    }
                "
            >
                <svg
                    class="block w-2 h-2 fill-green-800 group-hover:fill-white"
                    xmlns="http://www.w3.org/2000/svg"
                    width="235.908"
                    height="235.908"
                    viewBox="278.046 126.846 235.908 235.908"
                >
                    <path
                        d="M506.784 134.017c-9.56-9.56-25.06-9.56-34.62 0L396 210.18l-76.164-76.164c-9.56-9.56-25.06-9.56-34.62 0-9.56 9.56-9.56 25.06 0 34.62L361.38 244.8l-76.164 76.165c-9.56 9.56-9.56 25.06 0 34.62 9.56 9.56 25.06 9.56 34.62 0L396 279.42l76.164 76.165c9.56 9.56 25.06 9.56 34.62 0 9.56-9.56 9.56-25.06 0-34.62L430.62 244.8l76.164-76.163c9.56-9.56 9.56-25.06 0-34.62z"
                    />
                </svg>
            </button>
        </div>
        <div
            v-else-if="flashMessage.type === 'error' && flashMessage.show"
            :class="`flex items-center justify-between mb-8 max-w-3xl bg-red-500 rounded flash-message`"
        >
            <div class="flex items-center">
                <svg
                    class="flex-shrink-0 ml-4 mr-2 w-4 h-4 fill-white"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                >
                    <path
                        d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm1.41-1.41A8 8 0 1 0 15.66 4.34 8 8 0 0 0 4.34 15.66zm9.9-8.49L11.41 10l2.83 2.83-1.41 1.41L10 11.41l-2.83 2.83-1.41-1.41L8.59 10 5.76 7.17l1.41-1.41L10 8.59l2.83-2.83 1.41 1.41z"
                    />
                </svg>
                <div
                    v-if="flashMessage.type === 'error'"
                    class="py-4 text-white text-sm font-medium"
                >
                    {{ flashMessage.message }}
                </div>
            </div>
            <button
                type="button"
                class="group mr-2 p-2"
                @click="
                    flashMessage = {
                        show: false,
                        type: '',
                        message: '',
                    }
                "
            >
                <svg
                    class="block w-2 h-2 fill-red-800 group-hover:fill-white"
                    xmlns="http://www.w3.org/2000/svg"
                    width="235.908"
                    height="235.908"
                    viewBox="278.046 126.846 235.908 235.908"
                >
                    <path
                        d="M506.784 134.017c-9.56-9.56-25.06-9.56-34.62 0L396 210.18l-76.164-76.164c-9.56-9.56-25.06-9.56-34.62 0-9.56 9.56-9.56 25.06 0 34.62L361.38 244.8l-76.164 76.165c-9.56 9.56-9.56 25.06 0 34.62 9.56 9.56 25.06 9.56 34.62 0L396 279.42l76.164 76.165c9.56 9.56 25.06 9.56 34.62 0 9.56-9.56 9.56-25.06 0-34.62L430.62 244.8l76.164-76.163c9.56-9.56 9.56-25.06 0-34.62z"
                    />
                </svg>
            </button>
        </div>
    </div>
    <div
        class="cart-card-header relative"
        @click="$emit('minimizeCart', !minimizeCart)"
        :style="
            'color: ' +
            stylesConfiguration.cart?.cardHeaderTextColor +
            '; background-color: ' +
            stylesConfiguration.cart?.cardHeaderBgColor
        "
    >
        <h3
            :style="'color: ' + stylesConfiguration.cart?.cardHeaderTextColor"
            class=""
        >
            {{ $t("Shopping Cart") }}
        </h3>
    </div>
    <div class="cart-card-body">
        <div class="table-responsive modal-table">
            <table class="doc-table">
                <thead>
                    <tr>
                        <th class="">
                            {{ $t("Product name") }}
                        </th>
                        <th class="">
                            {{ $t("Quantity") }}
                        </th>
                        <th class="">
                            {{ $t("Product price") }}
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="(product, index) in cart.products ?? []"
                        :key="index"
                        :tabindex="index"
                        class="focus:outline-none h-16 border border-gray-100 rounded"
                    >
                        <td class="">
                            <div
                                style="width: 13ch; white-space: break-spaces"
                                class="md:w-2/3"
                            >
                                {{ product.name }}
                            </div>
                        </td>
                        <td class="">
                            <div class="md:w-2/3">
                                <input
                                    class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight"
                                    type="number"
                                    style="width: 13ch"
                                    readonly
                                    :value="
                                        checkForFormula(product.quantity)
                                            ? executeFormula(product)
                                            : isJson(product.quantity)
                                            ? result(product.quantity)
                                            : product.quantity
                                    "
                                />
                            </div>
                        </td>
                        <td class="">
                            <div class="md:w-2/3">
                                <input
                                    style="width: 13ch"
                                    class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight"
                                    type="text"
                                    :title="
                                        $formatter(
                                            product.salePrice,
                                            globalLanguage
                                        )
                                    "
                                    :value="
                                        $formatter(
                                            product.salePrice,
                                            globalLanguage
                                        )
                                    "
                                    readonly
                                />
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="px-4 pb-4">
            <div
                style="
                    justify-self: center;
                    grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
                "
                class="p-2 grid gap-2"
            >
                <div
                    v-if="cart.addProductsManually"
                    class="flex justify-center p-2"
                >
                    <button
                        style="width: 170px; word-break: break-word"
                        @click="toggleProductsModal = true"
                        type="button"
                        class="docsHeroLayoutColor text-white px-3 py-2 bg-gray-500 rounded"
                    >
                        {{ $t("Add Products") }}
                    </button>
                </div>
                <div
                    v-for="(config, index) in cart.config"
                    :key="'button-' + index"
                    class="p-2"
                    style="justify-self: center"
                >
                    <div v-if="config?.title?.length">
                        <button
                            @click="callAPI(config)"
                            v-if="!config.isExpertMode"
                            type="button"
                            style="width: 170px; word-break: break-word"
                            class="docsHeroLayoutColor text-white px-3 py-2 bg-gray-500 rounded"
                        >
                            {{ config?.title }}
                        </button>
                        <button
                            @click="runCustomCode(config)"
                            v-else
                            type="button"
                            style="width: 170px; word-break: break-word"
                            class="docsHeroLayoutColor text-white px-3 py-2 bg-gray-500 rounded"
                        >
                            {{ config?.title }}
                        </button>
                    </div>
                </div>
            </div>
            <div v-if="$route.name !== 'SurveysShow'" class="cart-card">
                <div class="cart-card-header">
                    <h3>{{ $t("Configuration") }}</h3>
                </div>
                <div class="cart-card-body p-3">
                    <div
                        v-for="(config, index) in cart.config"
                        :key="'config' + index"
                        class="relative p-2"
                    >
                        <font-awesome-icon
                            @click="removeConfig(index)"
                            class="cursor-pointer cross"
                            style="z-index: 999;position: absolute; right: -5px; top: -5px;"
                            icon="fa-solid fa-xmark"
                        />
                        <div class="grid items-center grid-cols-2 gap-3">
                            <div class="w-full">
                                <div class="form-group">
                                    <text-input
                                        v-model="config.title"
                                        :label="$t('Title')"
                                        placeholder=""
                                        type="text"
                                    />
                                </div>
                            </div>
                            <div class="w-full" v-if="!config.isExpertMode">
                                <div class="form-group">
                                    <text-input
                                        
                                        v-model="config.route"
                                        :label="$t('Route')"
                                        placeholder=""
                                        type="text"
                                    />
                                </div>
                            </div>
                            <div class="w-full col-span-2" v-if="config.isExpertMode">
                                <div class="form-group  flex justify-center">
                                        <button
                                            @click="addRoute(index)"
                                            type="button"
                                            class="primary-btn"
                                        >
                                            Add Route
                                        </button>
                                </div>
                            </div>
                            <div class="w-full col-span-2">
                                <div class="form-group flex justify-center">
                                        <button
                                            @click="addFunction(config, index)"
                                            type="button"
                                            class="primary-btn"
                                        >
                                            Expert Mode
                                        </button>
                                </div>
                            </div>
                            <div class="w-full col-span-2">
                                <div class="form-group">
                                    <text-input
                                        v-model="config.successFeedback"
                                        :label="$t('Success Feedback')"
                                        placeholder=""
                                        type="text"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button
                @click="addconfig"
                type="button"
                class="secondary-btn gap-2 w-full"
            >
                <CustomIcon name="addIcon" />
                {{ $t("Add Button") }}
            </button>
        </div>
    </div>
    <Modal
        :title="`${
            cart.config?.[selectedAction?.configIndex]?.functionName
                ? 'Edit'
                : 'Add'
        } Function`"
        v-if="toggleModal"
        @toggleModal="toggleModal = $event"
        width="70%"
    >
        <template #body>
            <div class="custom-action-grid mx-2">
                <div>
                    <div class="grid grid-cols-1 mb-2 ml-1 mr-1">
                        <div>
                            <text-input
                                @input="toggleErrors(selectedAction.code)"
                                v-model="selectedAction.functionName"
                                name="default-button"
                                :label="
                                    $t('Function Name (without parenthesis)')
                                "
                                type="text"
                            >
                            </text-input>
                            <p class="text-red-500" v-if="toggleError">
                                {{
                                    $t(
                                        "Function name must match with the function name in the code"
                                    )
                                }}
                            </p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 mt-5 ml-1 mr-1">
                        <div>
                            <label for="">{{ $t("Define Arguments") }}</label>
                        </div>
                    </div>
                    <div
                        v-if="selectedAction?.arguments?.length"
                        class="rounded card shadow-md mt-2 p-2"
                    >
                        <div
                            v-for="(
                                argument, index
                            ) in selectedAction.arguments"
                            :key="'custom-action-argument' + index"
                        >
                            <div class="grid grid-cols-1 mb-2 ml-1 mr-1">
                                <div>
                                    <label for=""
                                        >{{ $t("Argument") }}
                                        {{ index + 1 }}</label
                                    >
                                    <text-input
                                        @input="
                                            toggleErrors(selectedAction.code)
                                        "
                                        v-model="argument.value"
                                        name="default-button"
                                        class="mt-2"
                                        type="text"
                                    >
                                    </text-input>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 mb-2 mt-2">
                        <div>
                            <button
                                @click="addArgument"
                                type="button"
                                class="secondary-btn"
                            >
                                + {{ $t("Add Argument") }}
                            </button>
                        </div>
                    </div>
                </div>
                <MonacoEditor
                    @contentChange="
                        selectedAction.code = $event;
                        toggleErrors($event);
                    "
                    :readOnly="false"
                    :codeString="code"
                    :language="'javascript'"
                />
            </div>
        </template>
        <template #footer>
            <button type="button" @click="createFunction" class="secondary-btn">
                {{
                    cart.config?.[selectedAction?.configIndex]?.functionName
                        ? "Edit"
                        : "Add"
                }}
            </button>
            <button
                @click="toggleModal = false"
                type="button"
                class="primary-btn mr-3"
            >
                Cancel
            </button>
        </template>
    </Modal>
    <select-product-modal
        v-if="toggleProductsModal"
        @selected="addProducts"
        @cancelled="toggleProductsModal = false"
        :selectedItems="manualProducts"
        :products="products"
        :formulaInput="false"
    ></select-product-modal>
</template>

<script>
import MonacoEditor from "@/Components/MonacoEditor.vue";
import TextInput from "@/Components/TextInput.vue";
import Modal from "@/Components/EditModal.vue";
import SelectProductModal from "@/Components/SelectProductModal.vue";
import axios from "axios";
import { mapGetters } from "vuex";
import allOptionsMixin from "@/Mixins/allOptionsMixin";
import surveyFormulaMixin from "@/Mixins/surveyFormulaMixin";

export default {
    mixins: [allOptionsMixin, surveyFormulaMixin],
    computed: {
        ...mapGetters("surveys", {
            surveyId: "id",
        }),
        ...mapGetters("auth", ["authenticated"]),
        ...mapGetters(["isPublic"]),
    },
    components: {
        MonacoEditor,
        TextInput,
        Modal,
        SelectProductModal,
    },
    props: [
        "manualProductsParent",
        "minimizeCart",
        "selectedInputs",
        "cartParent",
        "stylesConfigurationParent",
        "selectedQuestionParent",
        "questionsParent",
    ],
    watch: {
        selectedQuestionParent(val) {
            this.selectedQuestion = val;
        },
        stylesConfigurationParent(val) {
            this.stylesConfiguration = val;
        },
        questionsParent(val) {
            this.questions = val;
        },
        cartParent: {
            handler: function (val) {
                this.cart = this.cartParent;
                try {
                    const surveyJson = JSON.parse(
                        localStorage.getItem("surveyJson")
                    );
                    surveyJson.cart.products = [...this.cart.products];
                    localStorage.setItem(
                        "surveyJson",
                        JSON.stringify(surveyJson)
                    );
                } catch (e) {
                    console.log(e);
                }
            },
            deep: true,
        },
    },
    emits: ["cartParent", "manualProducts", "minimizeCart"],
    async mounted() {
        this.manualProducts = [...this.manualProductsParent];
        this.selectedQuestion = this.selectedQuestionParent;
        this.cart = this.cartParent;
        this.stylesConfiguration = this.stylesConfigurationParent;
        try {
            const response = await this.$store.dispatch(
                "products/productsWithQuantity",
                {
                    public: !this.isPublic,
                    type: "software",
                }
            );
            this.products = response?.data?.products ?? this.products;
        } catch (e) {}
    },
    data() {
        return {
            public: this.$route.meta.public,
            flashMessage: {
                show: false,
                type: "",
                message: "",
            },
            code: "",
            questions: [],
            manualProducts: [],
            products: {
                data: [],
                links: [],
            },
            toggleProductsModal: false,
            selectedQuestion: null,
            selectedAction: {},
            errorList: {},
            toggleModal: false,
            toggleError: false,
            cart: [],
            stylesConfiguration: {},
        };
    },
    methods: {
        addRoute(index) {
            this.cart.config[index].isExpertMode = false;
            this.cart.config[index].arguments = [];
            this.cart.config[index].code = "";
            this.cart.config[index].functionName = "";
        },
        callAPI(config) {
            axios
                .post(config?.route ?? "", {
                    products: this.cart.products,
                    selectedInputs: this.selectedInputs,
                })
                .then((res) => {
                    this.flashMessage = {
                        show: true,
                        type: "success",
                        message: config?.successFeedback?.length
                            ? config?.successFeedback
                            : res?.data?.message,
                    };
                })
                .catch((e) => {
                    this.flashMessage = {
                        show: true,
                        type: "error",
                        message: e?.data?.message,
                    };
                });
            setTimeout(() => {
                this.flashMessage = {
                    show: false,
                    message: "",
                    type: "",
                };
            }, 5000);
        },
        addProducts(products) {
            const modifiedProducts = products.map((product) => {
                return {
                    ...product,
                };
            });
            //remove the previous manual products
            this.manualProducts.forEach((product) => {
                const foundProductIndex = this.cart.products.findIndex(
                    (selectedProduct) => selectedProduct.id == product.id
                );
                if (foundProductIndex == -1) return;
                const foundProduct = {
                    ...this.cart.products[foundProductIndex],
                };
                if (foundProduct?.belongsTo?.includes("manual")) {
                    let parsedQuantity = this.checkForFormula(product.quantity)
                        ? this.executeFormula(product)
                        : this.isJson(product.quantity)
                        ? this.result(product.quantity)
                        : product.quantity;
                    const salePriceProduct =
                        +product.salePrice * +parsedQuantity;
                    foundProduct.quantity =
                        +foundProduct.quantity - +parsedQuantity;
                    foundProduct.salePrice = (
                        +foundProduct.salePrice - +salePriceProduct
                    ).toString();
                    foundProduct.belongsTo = foundProduct?.belongsTo?.filter(
                        (id) => id != "manual"
                    );
                    if (!foundProduct?.belongsTo?.length) {
                        this.cart.products.splice(foundProductIndex, 1);
                    } else {
                        this.cart.products[foundProductIndex] = foundProduct;
                    }
                }
            });
            this.manualProducts = modifiedProducts;
            this.manualProducts.forEach((product) => {
                if (this.cart.products.some((cp) => cp.id == product.id)) {
                    const foundProductIndex = this.cart.products.findIndex(
                        (selectedProduct) => selectedProduct.id == product.id
                    );
                    const foundProduct = {
                        ...this.cart.products[foundProductIndex],
                    };
                    let parsedQuantity = this.checkForFormula(product.quantity)
                        ? this.executeFormula(product)
                        : this.isJson(product.quantity)
                        ? this.result(product.quantity)
                        : product.quantity;
                    foundProduct.quantity = +parsedQuantity;
                    foundProduct?.belongsTo?.forEach((optionId) => {
                        if (optionId !== "manual") {
                            let foundOption = {};
                            for (let i = 0; i < this.questions.length; i++) {
                                const question = this.questions[i];
                                foundOption =
                                    (
                                        question?.value ?? question
                                    ).configuration.options.find(
                                        (op) =>
                                            this.allOptions[op].id == optionId
                                    ) ??
                                    (
                                        question?.value ?? question
                                    ).configuration.conditions.find(
                                        (cond) => cond.id == optionId
                                    ) ??
                                    {};
                                if (Object.keys(foundOption).length > 0) {
                                    break;
                                }
                            }
                            const foundProductOther = {
                                ...foundOption?.products?.find(
                                    (pr) => pr.id == product.id ?? {}
                                ),
                            };
                            const parsedQuantityFoundProduct =
                                this.checkForFormula(foundProductOther.quantity)
                                    ? this.executeFormula(foundProductOther)
                                    : this.isJson(foundProductOther.quantity)
                                    ? this.result(foundProductOther.quantity)
                                    : foundProductOther.quantity;
                            foundProduct.quantity =
                                +foundProduct.quantity +
                                +parsedQuantityFoundProduct;
                        }
                    });
                    if (!foundProduct.belongsTo.includes("manual")) {
                        let parsedQuantity = this.checkForFormula(
                            product.quantity
                        )
                            ? this.executeFormula(product)
                            : this.isJson(product.quantity)
                            ? this.result(product.quantity)
                            : product.quantity;
                        const salePriceProduct =
                            +product.salePrice * +parsedQuantity;
                        foundProduct.salePrice = (
                            +foundProduct.salePrice + +salePriceProduct
                        ).toString();
                    }
                    foundProduct?.belongsTo?.push("manual");
                    const uniqueItems = Array.from(
                        new Set(foundProduct?.belongsTo)
                    );
                    foundProduct.belongsTo = [...uniqueItems];
                    this.cart.products[foundProductIndex] = {
                        ...foundProduct,
                    };
                } else {
                    let parsedQuantity = this.checkForFormula(product.quantity)
                        ? this.executeFormula(product)
                        : this.isJson(product.quantity)
                        ? this.result(product.quantity)
                        : product.quantity;
                    const cartProduct = { ...product };
                    cartProduct.belongsTo = ["manual"];
                    cartProduct.quantity = +parsedQuantity;
                    cartProduct.salePrice =
                        +parsedQuantity * +product.salePrice;
                    this.cart.products.push({ ...cartProduct });
                }
            });
            this.toggleProductsModal = false;
            this.$emit("manualProducts", this.manualProducts);
        },
        javascriptExecuter(obj) {
            let f = new Function('"use strict"; ' + obj);
            return f();
        },
        runCustomCode(config) {
            let functionString = `${config.code}; \n${
                config?.functionName
            }(${(() => {
                let argumentString = "";
                for (let i = 0; i < config?.arguments?.length; i++) {
                    argumentString += `${(() => {
                        if (typeof config?.arguments?.[i]?.value === "string") {
                            return `'${config?.arguments?.[i]?.value}'`;
                        } else return config?.arguments?.[i]?.value;
                    })()}${config.arguments[i + 1] ? "," : ""}`;
                }
                return argumentString;
            })()})`;
            this.javascriptExecuter(functionString);
        },
        cartUpdated() {
            this.$emit("cartParent", this.cart);
        },
        toggleErrors(val) {
            this.$nextTick(() => {
                this.errorList = {};
                if (this.selectedAction?.functionName?.length) {
                    this.toggleError =
                        val.match(/([a-zA-Z_{1}][a-zA-Z0-9_]+)(?=\()/g)?.[0] !=
                        this.selectedAction.functionName;
                }
                // if (this.selectedAction.arguments.length) {
                //     this.selectedAction.arguments.forEach((argument) => {
                //         const argumentListString =
                //             val.match(/\(\s*([^)]+?)\s*\)/)?.[1];
                //         const argumentList =
                //             argumentListString?.split(",") ?? [];
                //         argumentList.forEach((argumentFromString) => {
                //             if (
                //                 argument.dataType +
                //                     (argument.isNullable ? "?" : "") !==
                //                 argumentFromString
                //                     ?.split(" ")?.[0]
                //                     ?.toLowerCase()
                //             )
                //                 this.errorList[argument.name] =
                //                     argument.isNullable &&
                //                     argumentFromString
                //                         ?.split(" ")?.[0]
                //                         ?.includes("?")
                //                         ? "Datatype does not match for '" +
                //                           argument.name +
                //                           "'"
                //                         : "Argument is nullable";
                //             else if (
                //                 argument.name !==
                //                 argumentFromString?.split(" ")?.[1]
                //             )
                //                 this.errorList[argument.name] =
                //                     "Name does not match for '" +
                //                     argument.name +
                //                     "'";
                //         });
                //     });
                // }
            });
        },
        addArgument() {
            this.selectedAction.arguments.push({
                value: "",
            });
        },
        createFunction() {
            this.cart.config[this.selectedAction.configIndex] =
                this.selectedAction;
            this.cart.config[
                this.selectedAction.configIndex
            ].isExpertMode = true;
            this.toggleModal = false;
            this.cartUpdated();
        },
        addconfig() {
            this.cart.config.push({
                title: "",
                functionName: "",
                route: "",
                code: "",
                isExpertMode: false,
                successFeedback: "",
                arguments: [],
            });
            this.cartUpdated();
        },
        addFunction(config, index) {
            this.selectedAction = { ...config };
            this.code = this.selectedAction.code;
            this.selectedAction.configIndex = index;
            this.toggleModal = !this.toggleModal;
        },
        inputWidth(index, productName = "name") {
            const width = this.cart.products[index][productName]?.length;
            return { width: parseInt(width) + 4 + "ch" };
        },
        async removeConfig(index) {
            try {
                if (this.cart.config[index].id) {
                    const response = await this.$store.dispatch(
                        "surveyConfiguration/destroy",
                        this.cart.config[index].id
                    );
                    if (response === true) {
                        this.cart.config.splice(index, 1);
                        this.cartUpdated();
                    }
                } else {
                    this.cart.config.splice(index, 1);
                    this.cartUpdated();
                }
            } catch (e) {}
        },
    },
};
</script>

<style lang="scss" scoped>
.justify-evenly {
    justify-content: space-evenly;
}

:deep(.custom-action-grid) {
    display: grid;
    grid-template-columns: 30% 70%;
    gap: 10px;
}

.question:hover {
    background: #eee;
    color: #000;
    border: 1px solid #aaa;
}

.question:hover > .cross {
    display: block;
}

.chapter:hover > .cross {
    display: block;
}

ul {
    margin-left: 20px;
}

.chapter li {
    list-style-type: none;
    margin: 10px 0 10px 10px;
    position: relative;
}

.chapter .question:before {
    content: "";
    position: absolute;
    top: -13px;
    left: -20px;
    border-left: 1px solid #ddd;
    border-bottom: 1px solid #ddd;
    width: 20px;
    height: 27px;
}

.chapter .question-title-input:before {
    content: "";
    position: absolute;
    top: -20px;
    left: -20px;
    border-left: 1px solid #ddd;
    border-bottom: 1px solid #ddd;
    width: 20px;
    height: 40px;
}

.chapter li:after {
    position: absolute;
    content: "";
    top: 5px;
    left: -20px;
    border-left: 1px solid #ddd;
    width: 20px;
    height: 100%;
}
.chapter li:last-child:after {
    display: none;
}
.chapter li span {
    display: block;
    color: #000;
    text-decoration: none;
}
.chapter li span:hover,
.chapter li span:focus {
    background: #eee;
    color: #000;
}
.chapter li span:hover + ul li span,
.chapter li span:focus + ul li span {
    background: #eee;
    color: #000;
}
.chapter li span:hover + ul li:after,
.chapter li span:focus + ul li:after,
.chapter li span:hover + ul li:before,
.chapter li span:focus + ul li:before {
    border-color: #aaa;
}

.main-div {
    display: grid;
    grid-template-columns: 1fr 3fr;
    gap: 1rem;
}

.mid-card {
    display: grid;
    grid-template-rows: 1fr 2fr;
    gap: 1rem;
}

.bottom-card {
    display: grid;
    grid-template-columns: 3fr 2fr;
    gap: 1rem;
}

.card {
    background-color: white;
    border: 1px solid #eeeeee; /* darker white */
}

.card-title {
    color: black;
    background-color: #fafafa;
}

.question-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.option-card {
    border: 1px solid #eeeeee;
}

.cross {
    color: black !important;
    position: absolute;
    right: 10px;
}

.tabs .cross {
    color: black !important;
    position: absolute;
    right: 50%;
}

.top-4 {
    top: 4px;
}

.conditions {
    display: grid;
    grid-template-columns: 1fr 2fr 2fr;
    gap: 0.2rem;
}

.conditions-number {
    grid-template-columns: 1fr 2fr 2fr 2fr !important;
}

:deep(.ql-container) {
    min-height: 300px;
}

/* Accordion styles */
.tabs {
    overflow: hidden;
}

.tab {
    width: 100%;
    color: white;
    overflow: hidden;
    &-label {
        display: flex;
        justify-content: space-between;
        background: white;
        cursor: pointer;
        /* Icon */
        &:hover {
        }
        &::after {
            content: "\276F";
            text-align: center;
            transition: all 0.35s;
            color: black;
            position: absolute;
            right: 10px;
            margin-top: 15px;
        }
    }
    &-content {
        display: none;
        max-height: 0;
        color: black;
        transition: all 0.35s;
    }
    &-close {
        display: flex;
        justify-content: flex-end;
        font-size: 0.75em;
        background: white;
        cursor: pointer;
        &:hover {
        }
    }
}

.styles-configurator-tab-label::after {
    margin-top: 0px !important;
}

// :checked
input:checked {
    + .tab-label {
        &::after {
            transform: rotate(90deg);
            transform-origin: center;
        }
    }
    ~ .tab-content {
        display: block;
        max-height: 100vh;
    }
}

input[class="tab-checkbox"] {
    position: absolute;
    opacity: 0;
    z-index: -1;
}
.flash-message {
    position: absolute;
    right: 2%;
    top: 10%;
    z-index: 9999 !important;
}
</style>

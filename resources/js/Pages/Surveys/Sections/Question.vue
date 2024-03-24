<template>
    <div class="">
        <div class="no-question-selected" v-if="!selectedQuestion">
            <h3 class="">
                {{ $t("No Question Selected") }}
            </h3>
        </div>

        <div class="question-card-header" v-if="selectedQuestion">
            <h3 class="question-title">
                {{ selectedQuestion?.title }}
            </h3>
        </div>
        <div class="question-card-body" v-if="selectedQuestion">
            <div>
                <div>
                    <p class="font-bold" v-if="$route.name === 'SurveysShow'">
                        {{ selectedQuestion.text }}
                    </p>
                    <div class="form-group" v-else>
                        <text-input
                            
                            v-model="selectedQuestion.text"
                            @input="
                                $emit(
                                    'selectedQuestionParent',
                                    selectedQuestion
                                )
                            "
                            :label="$t('Text')"
                            placeholder=""
                            type="text"
                        />
                    </div>
                    <p
                        v-if="validator?.[selectedQuestion?.id]?.text?.error"
                        class="text-red-600"
                    >
                        {{ validator?.[selectedQuestion?.id]?.text?.message }}
                    </p>
                    <p
                        style="max-height: 10rem; overflow: auto"
                        class="mt-5 mb-5"
                        v-if="$route.name === 'SurveysShow'"
                    >
                        {{ selectedQuestion.description }}
                    </p>
                    <div class="form-group mt-5" v-else>
                        <text-area-input
                        
                        v-model="selectedQuestion.description"
                        label="Description"
                        @input="
                            $emit('selectedQuestionParent', selectedQuestion)
                        "
                    />
                    </div>
                    
                    <p
                        v-if="
                            validator?.[selectedQuestion?.id]?.description
                                ?.error
                        "
                        class="text-red-600"
                    >
                        {{
                            validator?.[selectedQuestion?.id]?.description
                                ?.message
                        }}
                    </p>
                </div>

                <div>
                    <div class="mt-3 flex justify-center flex-col items-center">
                        <div
                            v-if="
                                selectedQuestion.configuration.type ==
                                'image-input'
                            "
                            class="mt-3"
                        >
                            <image-input @images="addImages" />
                        </div>
                        <div
                            v-for="(group, groupIndex) in selectedQuestion
                                .configuration.groups"
                            :key="'group-' + groupIndex"
                            class="ml-3 justify-self-start"
                        >
                            <label
                                class="ml-3 justify-self-start"
                                :for="'question-options-' + index"
                                >{{ group.title }}</label
                            >
                            <div
                                v-for="(option, index) in group.options"
                                :key="'question-options-' + index"
                            >
                                <div
                                    v-if="
                                        allOptions[option].title &&
                                        allOptions[option].type ===
                                            'single-select'
                                    "
                                    class="grid grid-cols-2 gap-2 py-2"
                                >
                                    <input
                                        :checked="
                                            allOptions[option].value !== '' ||
                                            allOptions[option].value === true
                                        "
                                        @input="
                                            optionSelected(
                                                index,
                                                groupIndex,
                                                $event
                                            )
                                        "
                                        :name="`question-options-group-${group.id}`"
                                        :id="'question-options-' + index"
                                        class="self-center justify-self-end"
                                        type="radio"
                                        v-model="allOptions[option].value"
                                    />
                                    <label
                                        class="ml-3 justify-self-start"
                                        :for="'question-options-' + index"
                                        >{{ allOptions[option].title }}</label
                                    >
                                </div>
                                <div
                                    v-if="
                                        allOptions[option].title &&
                                        allOptions[option].type ===
                                            'multiple-select'
                                    "
                                    class="grid grid-cols-2 gap-2 py-2"
                                >
                                    <input
                                        :ref="
                                            'question-options-' +
                                            allOptions[option].id
                                        "
                                        @input="
                                            optionSelected(
                                                index,
                                                groupIndex,
                                                $event
                                            )
                                        "
                                        name="question-options"
                                        :id="'question-options-' + index"
                                        class="self-center justify-self-end"
                                        type="checkbox"
                                        v-model="allOptions[option].value"
                                    />
                                    <label
                                        class="ml-3 justify-self-start"
                                        :for="'question-options-' + index"
                                        >{{ allOptions[option].title }}</label
                                    >
                                </div>
                                <div
                                    v-if="
                                        allOptions[option].title &&
                                        allOptions[option].type ===
                                            'number-input'
                                    "
                                    class="grid grid-cols-2 gap-2 py-2"
                                >
                                    <label
                                        class="ml-3 self-center justify-self-end"
                                        :for="'question-options-' + index"
                                        >{{ allOptions[option].title }}:</label
                                    >
                                    <input
                                        data-previous-value="0"
                                        class="self-center ml-2 border justify-self-start"
                                        :ref="
                                            'question-options-' +
                                            allOptions[option].id
                                        "
                                        @input="
                                            optionSelected(
                                                index,
                                                groupIndex,
                                                $event
                                            )
                                        "
                                        name="question-options"
                                        :id="'question-options-' + index"
                                        label=""
                                        placeholder=""
                                        type="number"
                                        :min="allOptions[option].min"
                                        :max="allOptions[option].max"
                                        :step="allOptions[option].step"
                                        :value="
                                            allOptions[option].value
                                                ? allOptions[option].value
                                                : 0
                                        "
                                    />
                                </div>
                                <div
                                    v-if="
                                        allOptions[option].title &&
                                        allOptions[option].type ===
                                            'number-slider'
                                    "
                                    class="grid grid-cols-2 gap-2 py-2"
                                >
                                    <div
                                        class="ml-3 self-center justify-self-end"
                                    >
                                        <label
                                            :for="'question-options-' + index"
                                            >{{
                                                allOptions[option].title
                                            }}</label
                                        >
                                    </div>
                                    <div class="flex justify-self-start">
                                        <input
                                            data-previous-value="0"
                                            class="self-center ml-2"
                                            :ref="
                                                'question-options-' +
                                                allOptions[option].id
                                            "
                                            @input="
                                                optionSelected(
                                                    index,
                                                    groupIndex,
                                                    $event
                                                )
                                            "
                                            name="question-options"
                                            :id="'question-options-' + index"
                                            label=""
                                            placeholder=""
                                            type="range"
                                            :min="allOptions[option].min"
                                            :max="allOptions[option].max"
                                            :step="allOptions[option].step"
                                            :value="
                                                allOptions[option].value
                                                    ? allOptions[option].value
                                                    : 0
                                            "
                                        />
                                        <p class="ml-2">
                                            {{ allOptions[option].value }}
                                        </p>
                                    </div>
                                </div>
                                <div
                                    v-if="
                                        allOptions[option].title &&
                                        allOptions[option].type === 'text-input'
                                    "
                                    class="grid grid-cols-2 gap-2 py-2"
                                >
                                    <label
                                        class="ml-3 self-center justify-self-end"
                                        :for="'question-options-' + index"
                                        >{{ allOptions[option].title }}</label
                                    >
                                    <text-input
                                        class="self-center ml-2 justify-self-start"
                                        :ref="
                                            'question-options-' +
                                            allOptions[option].id
                                        "
                                        @input="
                                            optionSelected(
                                                index,
                                                groupIndex,
                                                $event
                                            )
                                        "
                                        name="question-options"
                                        :id="'question-options-' + index"
                                        label=""
                                        :placeholder="
                                            allOptions[option].placeholder
                                        "
                                        type="text"
                                        v-model="allOptions[option].value"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <p
                        v-if="validator?.[selectedQuestion?.id]?.value?.error"
                        class="text-red-600 text-center"
                    >
                        {{ validator?.[selectedQuestion?.id]?.value?.message }}
                    </p>
                </div>
            </div>
            <div class="flex items-center justify-end action-btn">
                <button @click="back" type="button" class="primary-btn gap-2 mr-3">
                    <CustomIcon name="backIcon" />
                    <span>{{ $t("Back") }}</span>
                </button>
                <button @click="next" type="button" class="secondary-btn gap-2">
                    <CustomIcon name="nextIcon" class="next-icon" />
                    <span>{{ $t("Next") }}</span>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import TextAreaInput from "@/Components/TextareaInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import ImageInput from "../ImageInput.vue";
import NumberInput from "../NumberInput.vue";
import allOptionsMixin from "@/Mixins/allOptionsMixin";
import surveyFormulaMixin from "@/Mixins/surveyFormulaMixin";

export default {
    mixins: [allOptionsMixin, surveyFormulaMixin],
    components: {
        TextInput,
        TextAreaInput,
        SelectInput,
        ImageInput,
        NumberInput,
    },
    props: [
        "selectedQuestionParent",
        "questionsParent",
        "validatorParent",
        "cartParent",
        "manualProducts",
    ],
    emits: [
        "selectedQuestionParent",
        "questionsParent",
        "validatorParent",
        "cartParent",
    ],
    mounted() {
        this.selectedQuestion = this.selectedQuestionParent;
        this.questions = this.questionsParent;
        this.validator = this.validatorParent;
        this.cart = this.cartParent;
    },
    data() {
        return {
            selectedInputs: {},
            selectedQuestion: null,
            questions: [],
            validator: {},
            cart: [],
        };
    },
    watch: {
        questionsParent(val) {
            this.questions = val;
        },
        selectedQuestionParent(val) {
            this.selectedQuestion = val;
        },
        cartParent(val) {
            this.cart = val;
        },
        validatorParent(val) {
            this.validator = val;
        },
    },
    methods: {
        /**
         * when the value changes for the selected option, it changes in the 'allOptions' state only
         * we have the option in the conditions of the selected question as well.
         * to update the value there, we need to find the currently changed option in the conditions
         * of the selected question and update the option value manually based on the 'event'
         * @param {index} index of the option that was modified for the selected question
         * @param {groupIndex} index of the group that the modified option belongs to
         */
        syncValueBetweenAllOptionsAndConditionOption(index, groupIndex) {
            // loop over conditions of the selected question
            for (
                let i = 0;
                i < this.selectedQuestion?.configuration?.conditions?.length ??
                0;
                i++
            ) {
                // condition on the current index
                const condition =
                    this.selectedQuestion.configuration.conditions[i];
                // index of the option inside the condition is found so that it's value can be updated
                const optionIndex = condition.options.findIndex(
                    (op) => this.allOptions[op.option.id]
                );
                // update the value of the option in condition based on the event that was received as argument for the function
                this.selectedQuestion.configuration.conditions[i]["options"][
                    optionIndex
                ].option.value =
                    this.selectedQuestion.configuration.groups[groupIndex]
                        .type === "multiple-select"
                        ? event.target.checked
                        : event.target.value; // if the type of the option is multiple-select then read the value of checked from the event
                // if the option was found then we no longer need to run the loop
                if (optionIndex > -1) break;
            }
        },
        /**
         * generates the conditionString that is fed to 'eval' to evaluate if the condition has become true or not
         * in the optionSelected method.
         * @param {option} the option for which the condition is being evaluated on. In case of multiple-select type
         * this is the option id. For number-input, number-slider and text-input it is the actual option object
         * @param {condition} the condition that is being evaluated, it contains the selected option, operators, products, discount etc.
         * @param {conditionString} the conditionString that is being concatenated
         * @param {i} the index of the option condition inside the main condition
         * @returns {conditionString} the generated conditionString
         */
        generateConditionString(option, condition, conditionString, i) {
            if (option?.option?.type === "multiple-select") {
                conditionString += `(${
                    this.allOptions[option?.option?.id].value ? true : false
                } == ${condition.options[i].condition == "checked"})${
                    condition.options[i + 1]
                        ? " " + condition.options[i + 1].operator + " "
                        : ""
                }`;
            } else if (
                option?.option?.type === "number-input" ||
                option?.option?.type === "number-slider"
            ) {
                conditionString += `(${
                    this.allOptions[option?.option?.id].value
                } ${option.condition} ${option.value})${
                    condition.options[i + 1]
                        ? " " + condition.options[i + 1].operator + " "
                        : ""
                }`;
            } else if (option?.option?.type === "text-input") {
                conditionString += `(${
                    option.condition === "contains" ? "" : "!"
                }'${this.allOptions[option?.option?.id].value}'.includes('${
                    option.value
                }'))${
                    condition.options[i + 1]
                        ? " " + condition.options[i + 1].operator + " "
                        : ""
                }`;
            }
            return conditionString;
        },
        addImages(images) {
            this.$nextTick(() => {
                if (this.selectedInputs[this.selectedQuestion.id]) {
                    this.selectedInputs[this.selectedQuestion.id]["images"] =
                        [];
                    images.forEach((image) => {
                        var reader = new FileReader();
                        reader.onload = () => {
                            const base64String = reader.result;
                            this.selectedInputs[this.selectedQuestion.id][
                                "images"
                            ].push(base64String);
                        };
                        reader.readAsDataURL(image);
                    });
                } else {
                    this.selectedInputs[this.selectedQuestion.id] = {
                        images: [],
                    };
                    images.forEach((image) => {
                        var reader = new FileReader();
                        reader.onload = () => {
                            const base64String = reader.result;
                            this.selectedInputs[this.selectedQuestion.id][
                                "images"
                            ].push(base64String);
                        };
                        reader.readAsDataURL(image);
                    });
                }
                this.$emit("selectedInputs", { ...this.selectedInputs });
            });
        },
        async optionSelected(index, groupIndex, event) {
            await this.$nextTick();
            const optionType =
                this.allOptions?.[
                    this.selectedQuestion.configuration.groups[groupIndex]
                        .options[index]
                ]?.type;
            if (optionType === "single-select") {
                this.selectedQuestion.configuration.groups[
                    groupIndex
                ].options.forEach((option) => {
                    let selectedProducts = [
                        ...this.allOptions[option].products,
                    ];
                    if (
                        this.allOptions[option].id ==
                        this.allOptions[
                            this.selectedQuestion.configuration.groups[
                                groupIndex
                            ].options[index]
                        ].id
                    ) {
                        this.selectedQuestion.next =
                            this.allOptions[option].next;
                        this.$nextTick(() => {
                            if (this.selectedInputs[this.selectedQuestion.id]) {
                                this.selectedInputs[this.selectedQuestion.id][
                                    "options"
                                ][this.allOptions[option].id] = {
                                    ...this.allOptions[option],
                                };
                                this.selectedInputs[this.selectedQuestion.id][
                                    "options"
                                ][this.allOptions[option].id].value =
                                    event.target.value;
                            } else {
                                this.selectedInputs[this.selectedQuestion.id] =
                                    {
                                        options: {},
                                        conditions: {},
                                    };
                                this.selectedInputs[this.selectedQuestion.id][
                                    "options"
                                ][this.allOptions[option].id] = {
                                    ...this.allOptions[option],
                                };
                                this.selectedInputs[this.selectedQuestion.id][
                                    "options"
                                ][this.allOptions[option].id].value =
                                    event.target.value;
                            }
                        });
                        selectedProducts.forEach((product) => {
                            let parsedQuantity = this.checkForFormula(
                                product.quantity
                            )
                                ? this.executeFormula(product)
                                : this.isJson(product.quantity)
                                ? this.result(product.quantity)
                                : product.quantity;
                            if (
                                this.cart.products.some(
                                    (cp) => cp.id == product.id
                                )
                            ) {
                                const foundProductIndex =
                                    this.cart.products.findIndex(
                                        (selectedProduct) =>
                                            selectedProduct.id == product.id
                                    );
                                const foundProduct = {
                                    ...this.cart.products[foundProductIndex],
                                };
                                foundProduct.quantity = +parsedQuantity;
                                foundProduct?.belongsTo?.forEach((optionId) => {
                                    if (
                                        optionId != this.allOptions[option].uuid
                                    ) {
                                        let foundOption = {};
                                        for (
                                            let i = 0;
                                            i < this.questions.length;
                                            i++
                                        ) {
                                            const question = this.questions[i];
                                            foundOption =
                                                Object.values(
                                                    this.allOptions
                                                ).find(
                                                    (op) => op.id == optionId
                                                ) ??
                                                (
                                                    question?.value ?? question
                                                ).configuration.conditions.find(
                                                    (cond) =>
                                                        cond.id == optionId
                                                ) ??
                                                {};
                                            if (
                                                Object.keys(foundOption)
                                                    .length > 0
                                            ) {
                                                break;
                                            }
                                        }
                                        const foundProductOther =
                                            optionId === "manual"
                                                ? this.manualProducts.find(
                                                      (pr) =>
                                                          pr.id == product.id
                                                  )
                                                : {
                                                      ...foundOption?.products?.find(
                                                          (pr) =>
                                                              pr.id ==
                                                                  product.id ??
                                                              {}
                                                      ),
                                                  };
                                        const parsedQuantityFoundProduct =
                                            this.checkForFormula(
                                                foundProductOther.quantity
                                            )
                                                ? this.executeFormula(
                                                      foundProductOther
                                                  )
                                                : this.isJson(
                                                      foundProductOther.quantity
                                                  )
                                                ? this.result(
                                                      foundProductOther.quantity
                                                  )
                                                : foundProductOther.quantity;
                                        foundProduct.quantity =
                                            +foundProduct.quantity +
                                            +parsedQuantityFoundProduct;
                                    }
                                });
                                foundProduct.salePrice = (
                                    +foundProduct.quantity * +product.salePrice
                                ).toString();
                                foundProduct?.belongsTo?.push(
                                    this.allOptions[option].uuid
                                );
                                const uniqueItems = Array.from(
                                    new Set(foundProduct?.belongsTo)
                                );
                                foundProduct.belongsTo = [...uniqueItems];
                                this.cart.products[foundProductIndex] = {
                                    ...foundProduct,
                                };
                            } else {
                                const cartProduct = { ...product };
                                cartProduct.belongsTo = [
                                    this.allOptions[option].uuid,
                                ];
                                cartProduct.quantity = +parsedQuantity;
                                cartProduct.salePrice =
                                    +parsedQuantity * +product.salePrice;
                                this.cart.products.push({ ...cartProduct });
                            }
                        });
                    } else {
                        delete this.selectedInputs?.[
                            this.selectedQuestion.id
                        ]?.[this.allOptions[option].id];
                        selectedProducts.forEach((product) => {
                            let parsedQuantity = this.checkForFormula(
                                product.quantity
                            )
                                ? this.executeFormula(product)
                                : this.isJson(product.quantity)
                                ? this.result(product.quantity)
                                : product.quantity;
                            const foundProductIndex =
                                this.cart.products.findIndex(
                                    (selectedProduct) =>
                                        selectedProduct.id == product.id
                                );
                            if (foundProductIndex == -1) return;
                            const foundProduct = {
                                ...this.cart.products[foundProductIndex],
                            };
                            if (
                                foundProduct?.belongsTo?.includes(
                                    this.allOptions[option].uuid
                                )
                            ) {
                                foundProduct.quantity =
                                    +foundProduct.quantity - parsedQuantity;
                                foundProduct.salePrice = (
                                    +foundProduct.quantity * +product.salePrice
                                ).toString();
                                foundProduct.belongsTo =
                                    foundProduct?.belongsTo?.filter(
                                        (id) =>
                                            id != this.allOptions[option].uuid
                                    );
                                if (!foundProduct?.belongsTo?.length) {
                                    this.cart.products.splice(
                                        foundProductIndex,
                                        1
                                    );
                                } else {
                                    this.cart.products[foundProductIndex] =
                                        foundProduct;
                                }
                            }
                        });
                    }
                });
            } else if (optionType === "multiple-select") {
                this.allOptions[
                    this.selectedQuestion.configuration.groups[
                        groupIndex
                    ].options[index]
                ].value = event.target.checked;
                this.syncValueBetweenAllOptionsAndConditionOption(
                    index,
                    groupIndex
                );
                const option =
                    this.selectedQuestion.configuration.groups[groupIndex]
                        .options[index];
                let selectedProducts = [...this.allOptions[option].products];
                if (event.target.checked) {
                    this.selectedQuestion.next = this.allOptions[option].next;
                    this.$nextTick(() => {
                        if (this.selectedInputs[this.selectedQuestion.id]) {
                            this.selectedInputs[this.selectedQuestion.id][
                                "options"
                            ][this.allOptions[option].id] = {
                                ...this.allOptions[option],
                            };
                            this.selectedInputs[this.selectedQuestion.id][
                                "options"
                            ][this.allOptions[option].id].value =
                                event.target.value;
                        } else {
                            this.selectedInputs[this.selectedQuestion.id] = {
                                options: {},
                                conditions: {},
                            };
                            this.selectedInputs[this.selectedQuestion.id][
                                "options"
                            ][this.allOptions[option].id] = {
                                ...this.allOptions[option],
                            };
                            this.selectedInputs[this.selectedQuestion.id][
                                "options"
                            ][this.allOptions[option].id].value =
                                event.target.value;
                        }
                    });
                    selectedProducts.forEach((product) => {
                        let parsedQuantity = this.checkForFormula(
                            product.quantity
                        )
                            ? this.executeFormula(product)
                            : this.isJson(product.quantity)
                            ? this.result(product.quantity)
                            : product.quantity;
                        if (
                            this.cart.products.some((cp) => cp.id == product.id)
                        ) {
                            const foundProductIndex =
                                this.cart.products.findIndex(
                                    (selectedProduct) =>
                                        selectedProduct.id == product.id
                                );
                            const foundProduct = {
                                ...this.cart.products[foundProductIndex],
                            };
                            foundProduct.quantity = +parsedQuantity;
                            foundProduct?.belongsTo?.forEach((optionId) => {
                                if (optionId != this.allOptions[option].uuid) {
                                    let foundOption = {};
                                    for (
                                        let i = 0;
                                        i < this.questions.length;
                                        i++
                                    ) {
                                        const question = this.questions[i];
                                        foundOption =
                                            Object.values(this.allOptions).find(
                                                (op) => op.id == optionId
                                            ) ??
                                            (
                                                question?.value ?? question
                                            ).configuration.conditions.find(
                                                (cond) => cond.id == optionId
                                            ) ??
                                            {};
                                        if (
                                            Object.keys(foundOption).length > 0
                                        ) {
                                            break;
                                        }
                                    }
                                    const foundProductOther =
                                        optionId === "manual"
                                            ? this.manualProducts.find(
                                                  (pr) => pr.id == product.id
                                              )
                                            : {
                                                  ...foundOption?.products?.find(
                                                      (pr) =>
                                                          pr.id == product.id ??
                                                          {}
                                                  ),
                                              };
                                    const parsedQuantityFoundProduct =
                                        this.checkForFormula(
                                            foundProductOther.quantity
                                        )
                                            ? this.executeFormula(
                                                  foundProductOther
                                              )
                                            : this.isJson(
                                                  foundProductOther.quantity
                                              )
                                            ? this.result(
                                                  foundProductOther.quantity
                                              )
                                            : foundProductOther.quantity;
                                    foundProduct.quantity =
                                        +foundProduct.quantity +
                                        +parsedQuantityFoundProduct;
                                }
                            });
                            if (
                                !foundProduct.belongsTo.includes(
                                    this.allOptions[option].uuid
                                )
                            ) {
                                const salePriceProduct =
                                    +product.salePrice * +parsedQuantity;
                                foundProduct.salePrice = (
                                    +foundProduct.salePrice + +salePriceProduct
                                ).toString();
                            }
                            foundProduct?.belongsTo?.push(
                                this.allOptions[option].uuid
                            );
                            const uniqueItems = Array.from(
                                new Set(foundProduct?.belongsTo)
                            );
                            foundProduct.belongsTo = [...uniqueItems];
                            this.cart.products[foundProductIndex] = {
                                ...foundProduct,
                            };
                        } else {
                            const cartProduct = { ...product };
                            cartProduct.belongsTo = [
                                this.allOptions[option].uuid,
                            ];
                            cartProduct.quantity = +parsedQuantity;
                            cartProduct.salePrice =
                                +parsedQuantity * +product.salePrice;
                            this.cart.products.push({ ...cartProduct });
                        }
                    });
                } else {
                    delete this.selectedInputs?.[this.selectedQuestion.id]?.[
                        this.allOptions[option].id
                    ];
                    selectedProducts.forEach((product) => {
                        let parsedQuantity = this.checkForFormula(
                            product.quantity
                        )
                            ? this.executeFormula(product)
                            : this.isJson(product.quantity)
                            ? this.result(product.quantity)
                            : product.quantity;
                        const foundProductIndex = this.cart.products.findIndex(
                            (selectedProduct) =>
                                selectedProduct.id == product.id
                        );
                        if (foundProductIndex == -1) return;
                        const foundProduct = {
                            ...this.cart.products[foundProductIndex],
                        };
                        if (
                            foundProduct?.belongsTo?.includes(
                                this.allOptions[option].uuid
                            )
                        ) {
                            const salePriceProduct =
                                +product.salePrice * +parsedQuantity;
                            foundProduct.quantity =
                                +foundProduct.quantity - parsedQuantity;
                            foundProduct.salePrice = (
                                +foundProduct.salePrice - +salePriceProduct
                            ).toString();
                            foundProduct.belongsTo =
                                foundProduct?.belongsTo?.filter(
                                    (id) => id != this.allOptions[option].uuid
                                );
                            if (!foundProduct?.belongsTo?.length) {
                                this.cart.products.splice(foundProductIndex, 1);
                            } else {
                                this.cart.products[foundProductIndex] =
                                    foundProduct;
                            }
                        }
                    });
                }
                // get the conditions from conditions object of the changed option
                let optionConditions = Object.keys(
                    this.allOptions[option]?.conditions ?? {}
                ).map((conditionId) => this.allConditions[conditionId]);
                optionConditions.forEach((condition) => {
                    let conditionString = "";
                    for (let i = 0; i < condition.options.length; i++) {
                        const option = condition.options[i];
                        conditionString = this.generateConditionString(
                            option,
                            condition,
                            conditionString,
                            i
                        );
                    }
                    try {
                        selectedProducts = [...condition.products];
                        if (this.javascriptExecuter(conditionString)) {
                            selectedProducts.forEach((product) => {
                                let parsedQuantity = this.checkForFormula(
                                    product.quantity
                                )
                                    ? this.executeFormula(product)
                                    : this.isJson(product.quantity)
                                    ? this.result(product.quantity)
                                    : product.quantity;
                                if (
                                    this.cart.products.some(
                                        (cp) => cp.id == product.id
                                    )
                                ) {
                                    const foundProductIndex =
                                        this.cart.products.findIndex(
                                            (selectedProduct) =>
                                                selectedProduct.id == product.id
                                        );
                                    const foundProduct = {
                                        ...this.cart.products[
                                            foundProductIndex
                                        ],
                                    };
                                    foundProduct.quantity = +parsedQuantity;
                                    foundProduct?.belongsTo?.forEach(
                                        (conditionId) => {
                                            if (conditionId != condition.id) {
                                                let foundCondition = {};
                                                for (
                                                    let i = 0;
                                                    i < this.questions.length;
                                                    i++
                                                ) {
                                                    const question =
                                                        this.questions[i];
                                                    foundCondition =
                                                        (
                                                            question?.value ??
                                                            question
                                                        ).configuration.conditions.find(
                                                            (cond) =>
                                                                cond.id ==
                                                                conditionId
                                                        ) ??
                                                        Object.values(
                                                            this.allOptions
                                                        ).find(
                                                            (op) =>
                                                                op.id ==
                                                                conditionId
                                                        ) ??
                                                        {};
                                                    if (
                                                        Object.keys(
                                                            foundCondition
                                                        ).length > 0
                                                    ) {
                                                        break;
                                                    }
                                                }
                                                const foundProductOther =
                                                    conditionId === "manual"
                                                        ? this.manualProducts.find(
                                                              (pr) =>
                                                                  pr.id ==
                                                                  product.id
                                                          )
                                                        : {
                                                              ...foundCondition?.products?.find(
                                                                  (pr) =>
                                                                      pr.id ==
                                                                          product.id ??
                                                                      {}
                                                              ),
                                                          };
                                                const parsedQuantityFoundProduct =
                                                    this.checkForFormula(
                                                        foundProductOther.quantity
                                                    )
                                                        ? this.executeFormula(
                                                              foundProductOther
                                                          )
                                                        : this.isJson(
                                                              foundProductOther.quantity
                                                          )
                                                        ? this.result(
                                                              foundProductOther.quantity
                                                          )
                                                        : foundProductOther.quantity;
                                                foundProduct.quantity =
                                                    +foundProduct.quantity +
                                                    +parsedQuantityFoundProduct;
                                            }
                                        }
                                    );
                                    if (
                                        !foundProduct?.belongsTo?.includes(
                                            condition.id
                                        )
                                    ) {
                                        const salePriceCondition =
                                            +product.salePrice *
                                                +parsedQuantity -
                                            (+product.salePrice *
                                                +parsedQuantity *
                                                +condition.discount) /
                                                100;
                                        foundProduct.salePrice = (
                                            +foundProduct.salePrice +
                                            +salePriceCondition
                                        ).toString();
                                    }
                                    foundProduct?.belongsTo?.push(condition.id);
                                    const uniqueItems = Array.from(
                                        new Set(foundProduct?.belongsTo)
                                    );
                                    foundProduct.belongsTo = [...uniqueItems];
                                    this.cart.products[foundProductIndex] = {
                                        ...foundProduct,
                                    };
                                } else {
                                    const cartProduct = { ...product };
                                    cartProduct.belongsTo = [condition.id];
                                    cartProduct.quantity = +parsedQuantity;
                                    cartProduct.salePrice =
                                        +parsedQuantity * +product.salePrice -
                                        (+parsedQuantity *
                                            +product.salePrice *
                                            +condition.discount) /
                                            100;
                                    this.cart.products.push({
                                        ...cartProduct,
                                    });
                                }
                            });
                            this.$nextTick(() => {
                                if (
                                    this.selectedInputs[
                                        this.selectedQuestion.id
                                    ]
                                ) {
                                    this.selectedInputs[
                                        this.selectedQuestion.id
                                    ]["conditions"][condition.id] = condition;
                                } else {
                                    this.selectedInputs[
                                        this.selectedQuestion.id
                                    ] = {
                                        options: {},
                                        conditions: {},
                                    };
                                    this.selectedInputs[
                                        this.selectedQuestion.id
                                    ]["conditions"][condition.id] = condition;
                                }
                            });
                            this.selectedQuestion.next = condition.next;
                        } else {
                            selectedProducts.forEach((product) => {
                                let parsedQuantity = this.checkForFormula(
                                    product.quantity
                                )
                                    ? this.executeFormula(product)
                                    : this.isJson(product.quantity)
                                    ? this.result(product.quantity)
                                    : product.quantity;
                                const foundProductIndex =
                                    this.cart.products.findIndex(
                                        (selectedProduct) =>
                                            selectedProduct.id == product.id
                                    );
                                if (foundProductIndex == -1) return;
                                const foundProduct = {
                                    ...this.cart.products[foundProductIndex],
                                };
                                if (
                                    foundProduct?.belongsTo?.includes(
                                        condition.id
                                    )
                                ) {
                                    foundProduct.quantity =
                                        +foundProduct.quantity - parsedQuantity;
                                    const salePriceCondition =
                                        +product.salePrice * +parsedQuantity -
                                        (+product.salePrice *
                                            +parsedQuantity *
                                            +condition.discount) /
                                            100;
                                    foundProduct.salePrice = (
                                        +foundProduct.salePrice -
                                        +salePriceCondition
                                    ).toString();
                                    foundProduct.belongsTo =
                                        foundProduct?.belongsTo?.filter(
                                            (id) => id != condition.id
                                        );
                                    if (!foundProduct?.belongsTo?.length) {
                                        this.cart.products.splice(
                                            foundProductIndex,
                                            1
                                        );
                                    } else {
                                        this.cart.products[foundProductIndex] =
                                            foundProduct;
                                    }
                                }
                            });
                            delete this.selectedInputs?.[
                                this.selectedQuestion.id
                            ]?.[condition.id];
                        }
                    } catch (e) {
                        console.log(e);
                    }
                });
            } else if (
                optionType === "number-input" ||
                optionType === "number-slider"
            ) {
                event.target.dataset.previousValue =
                    this.allOptions[
                        this.selectedQuestion.configuration.groups[
                            groupIndex
                        ].options[index]
                    ].value;
                this.allOptions[
                    this.selectedQuestion.configuration.groups[
                        groupIndex
                    ].options[index]
                ].value = event.target.value;
                this.syncValueBetweenAllOptionsAndConditionOption(
                    index,
                    groupIndex
                );
                const option =
                    this.selectedQuestion.configuration.groups[groupIndex]
                        .options[index];
                let selectedProducts = [...this.allOptions[option].products];
                if (this.allOptions[option].value > 0) {
                    this.selectedQuestion.next = this.allOptions[option].next;
                    this.$nextTick(() => {
                        if (this.selectedInputs[this.selectedQuestion.id]) {
                            this.selectedInputs[this.selectedQuestion.id][
                                "options"
                            ][this.allOptions[option].id] = {
                                ...this.allOptions[option],
                            };
                            this.selectedInputs[this.selectedQuestion.id][
                                "options"
                            ][this.allOptions[option].id].value =
                                event.target.value;
                        } else {
                            this.selectedInputs[this.selectedQuestion.id] = {
                                options: {},
                                conditions: {},
                            };
                            this.selectedInputs[this.selectedQuestion.id][
                                "options"
                            ][this.allOptions[option].id] = {
                                ...this.allOptions[option],
                            };
                            this.selectedInputs[this.selectedQuestion.id][
                                "options"
                            ][this.allOptions[option].id].value =
                                event.target.value;
                        }
                    });
                    selectedProducts.forEach((product) => {
                        let parsedQuantity = this.checkForFormula(
                            product.quantity
                        )
                            ? this.executeFormula(product)
                            : this.isJson(product.quantity)
                            ? this.result(product.quantity)
                            : product.quantity;
                        if (
                            this.cart.products.some((cp) => cp.id == product.id)
                        ) {
                            const foundProductIndex =
                                this.cart.products.findIndex(
                                    (selectedProduct) =>
                                        selectedProduct.id == product.id
                                );
                            const foundProduct = {
                                ...this.cart.products[foundProductIndex],
                            };
                            foundProduct.quantity = +parsedQuantity;
                            foundProduct?.belongsTo?.forEach((optionId) => {
                                if (optionId != this.allOptions[option].uuid) {
                                    let foundOption = {};
                                    for (
                                        let i = 0;
                                        i < this.questions.length;
                                        i++
                                    ) {
                                        const question = this.questions[i];
                                        foundOption =
                                            Object.values(this.allOptions).find(
                                                (op) => op.id == optionId
                                            ) ??
                                            (
                                                question?.value ?? question
                                            ).configuration.conditions.find(
                                                (cond) => cond.id == optionId
                                            ) ??
                                            {};
                                        if (
                                            Object.keys(foundOption).length > 0
                                        ) {
                                            break;
                                        }
                                    }

                                    const foundProductOther =
                                        optionId === "manual"
                                            ? this.manualProducts.find(
                                                  (pr) => pr.id == product.id
                                              )
                                            : {
                                                  ...foundOption?.products?.find(
                                                      (pr) =>
                                                          pr.id == product.id ??
                                                          {}
                                                  ),
                                              };
                                    const parsedQuantityFoundProduct =
                                        this.checkForFormula(
                                            foundProductOther.quantity
                                        )
                                            ? this.executeFormula(
                                                  foundProductOther
                                              )
                                            : this.isJson(
                                                  foundProductOther.quantity
                                              )
                                            ? this.result(
                                                  foundProductOther.quantity
                                              )
                                            : foundProductOther.quantity;
                                    foundProduct.quantity =
                                        +foundProduct.quantity +
                                        +parsedQuantityFoundProduct;
                                }
                            });
                            if (
                                !foundProduct?.belongsTo?.includes(
                                    this.allOptions[option].uuid
                                )
                            ) {
                                const salePriceProduct =
                                    +product.salePrice * +parsedQuantity;
                                foundProduct.salePrice = (
                                    +foundProduct.salePrice + +salePriceProduct
                                ).toString();
                            } else {
                                const salePriceProduct =
                                    +product.salePrice * +parsedQuantity;
                                foundProduct.salePrice = (
                                    +foundProduct.salePrice + +salePriceProduct
                                ).toString();
                                const previousQuantity = this.checkForFormula(
                                    product.quantity
                                )
                                    ? this.executeFormula(
                                          product,
                                          event.target.dataset.previousValue
                                      )
                                    : this.isJson(product.quantity)
                                    ? this.result(
                                          product.quantity,
                                          event.target.dataset.previousValue
                                      )
                                    : product.quantity;
                                const salePriceProductPrevious =
                                    +product.salePrice * +previousQuantity;
                                foundProduct.salePrice = (
                                    +foundProduct.salePrice -
                                    +salePriceProductPrevious
                                ).toString();
                            }
                            foundProduct?.belongsTo?.push(
                                this.allOptions[option].uuid
                            );
                            const uniqueItems = Array.from(
                                new Set(foundProduct?.belongsTo)
                            );
                            foundProduct.belongsTo = [...uniqueItems];
                            this.cart.products[foundProductIndex] = {
                                ...foundProduct,
                            };
                        } else {
                            const cartProduct = { ...product };
                            cartProduct.belongsTo = [
                                this.allOptions[option].uuid,
                            ];
                            cartProduct.quantity = +parsedQuantity;
                            cartProduct.salePrice =
                                +parsedQuantity * +product.salePrice;
                            this.cart.products.push({ ...cartProduct });
                        }
                    });
                } else {
                    delete this.selectedInputs?.[this.selectedQuestion.id]?.[
                        this.allOptions[option].id
                    ];
                    selectedProducts.forEach((product) => {
                        const foundProductIndex = this.cart.products.findIndex(
                            (selectedProduct) =>
                                selectedProduct.id == product.id
                        );
                        if (foundProductIndex == -1) return;
                        const foundProduct = {
                            ...this.cart.products[foundProductIndex],
                        };
                        if (
                            foundProduct?.belongsTo?.includes(
                                this.allOptions[option].uuid
                            )
                        ) {
                            const previousQuantity = this.checkForFormula(
                                product.quantity
                            )
                                ? this.executeFormula(
                                      product,
                                      event.target.dataset.previousValue
                                  )
                                : this.isJson(product.quantity)
                                ? this.result(
                                      product.quantity,
                                      event.target.dataset.previousValue
                                  )
                                : product.quantity;
                            const salePriceProductPrevious =
                                +product.salePrice * +previousQuantity;
                            foundProduct.quantity =
                                +foundProduct.quantity - +previousQuantity;
                            foundProduct.salePrice = (
                                +foundProduct.salePrice -
                                +salePriceProductPrevious
                            ).toString();
                            foundProduct.belongsTo =
                                foundProduct?.belongsTo?.filter(
                                    (id) => id != this.allOptions[option].uuid
                                );
                            if (!foundProduct?.belongsTo?.length) {
                                this.cart.products.splice(foundProductIndex, 1);
                            } else {
                                this.cart.products[foundProductIndex] =
                                    foundProduct;
                            }
                        }
                    });
                }
                // get the conditions from conditions object of the changed option
                let optionConditions = Object.keys(
                    this.allOptions[option]?.conditions ?? {}
                ).map((conditionId) => this.allConditions[conditionId]);
                optionConditions.forEach((condition) => {
                    let conditionString = "";
                    for (let i = 0; i < condition.options.length; i++) {
                        const option = condition.options[i];
                        conditionString = this.generateConditionString(
                            option,
                            condition,
                            conditionString,
                            i
                        );
                    }
                    try {
                        selectedProducts = [...condition.products];
                        if (this.javascriptExecuter(conditionString)) {
                            selectedProducts.forEach((product) => {
                                let parsedQuantity = this.checkForFormula(
                                    product.quantity
                                )
                                    ? this.executeFormula(product)
                                    : this.isJson(product.quantity)
                                    ? this.result(product.quantity)
                                    : product.quantity;
                                if (
                                    this.cart.products.some(
                                        (cp) => cp.id == product.id
                                    )
                                ) {
                                    const foundProductIndex =
                                        this.cart.products.findIndex(
                                            (selectedProduct) =>
                                                selectedProduct.id == product.id
                                        );
                                    const foundProduct = {
                                        ...this.cart.products[
                                            foundProductIndex
                                        ],
                                    };
                                    foundProduct.quantity = +parsedQuantity;
                                    foundProduct?.belongsTo?.forEach(
                                        (conditionId) => {
                                            if (conditionId != condition.id) {
                                                let foundCondition = {};
                                                for (
                                                    let i = 0;
                                                    i < this.questions.length;
                                                    i++
                                                ) {
                                                    const question =
                                                        this.questions[i];
                                                    foundCondition =
                                                        (
                                                            question?.value ??
                                                            question
                                                        ).configuration.conditions.find(
                                                            (cond) =>
                                                                cond.id ==
                                                                conditionId
                                                        ) ??
                                                        Object.values(
                                                            this.allOptions
                                                        ).find(
                                                            (op) =>
                                                                op.id ==
                                                                conditionId
                                                        ) ??
                                                        {};
                                                    if (
                                                        Object.keys(
                                                            foundCondition
                                                        ).length > 0
                                                    ) {
                                                        break;
                                                    }
                                                }
                                                const foundProductOther =
                                                    conditionId === "manual"
                                                        ? this.manualProducts.find(
                                                              (pr) =>
                                                                  pr.id ==
                                                                  product.id
                                                          )
                                                        : {
                                                              ...foundCondition?.products?.find(
                                                                  (pr) =>
                                                                      pr.id ==
                                                                          product.id ??
                                                                      {}
                                                              ),
                                                          };
                                                const parsedQuantityFoundProduct =
                                                    this.checkForFormula(
                                                        foundProductOther.quantity
                                                    )
                                                        ? this.executeFormula(
                                                              foundProductOther
                                                          )
                                                        : this.isJson(
                                                              foundProductOther.quantity
                                                          )
                                                        ? this.result(
                                                              foundProductOther.quantity
                                                          )
                                                        : foundProductOther.quantity;
                                                foundProduct.quantity =
                                                    +foundProduct.quantity +
                                                    +parsedQuantityFoundProduct;
                                            }
                                        }
                                    );
                                    if (
                                        !foundProduct?.belongsTo?.includes(
                                            condition.id
                                        )
                                    ) {
                                        const salePriceCondition =
                                            +product.salePrice *
                                                +parsedQuantity -
                                            (+product.salePrice *
                                                +parsedQuantity *
                                                +condition.discount) /
                                                100;
                                        foundProduct.salePrice = (
                                            +foundProduct.salePrice +
                                            +salePriceCondition
                                        ).toString();
                                    } else {
                                        const salePriceProduct =
                                            +product.salePrice *
                                                +parsedQuantity -
                                            (+product.salePrice *
                                                +parsedQuantity *
                                                +condition.discount) /
                                                100;
                                        foundProduct.salePrice = (
                                            +foundProduct.salePrice +
                                            +salePriceProduct
                                        ).toString();
                                        const previousQuantity =
                                            this.checkForFormula(
                                                product.quantity
                                            )
                                                ? this.executeFormula(
                                                      product,
                                                      event.target.dataset
                                                          .previousValue
                                                  )
                                                : this.isJson(product.quantity)
                                                ? this.result(
                                                      product.quantity,
                                                      event.target.dataset
                                                          .previousValue
                                                  )
                                                : product.quantity;
                                        const salePriceProductPrevious =
                                            +product.salePrice *
                                            +previousQuantity;
                                        foundProduct.salePrice = (
                                            +foundProduct.salePrice -
                                            +salePriceProductPrevious
                                        ).toString();
                                    }
                                    foundProduct?.belongsTo?.push(condition.id);
                                    const uniqueItems = Array.from(
                                        new Set(foundProduct?.belongsTo)
                                    );
                                    foundProduct.belongsTo = [...uniqueItems];
                                    this.cart.products[foundProductIndex] = {
                                        ...foundProduct,
                                    };
                                } else {
                                    const cartProduct = { ...product };
                                    cartProduct.belongsTo = [condition.id];
                                    cartProduct.quantity = +parsedQuantity;
                                    cartProduct.salePrice =
                                        +parsedQuantity * +product.salePrice -
                                        (+parsedQuantity *
                                            +product.salePrice *
                                            +condition.discount) /
                                            100;
                                    this.cart.products.push({
                                        ...cartProduct,
                                    });
                                }
                            });
                            this.$nextTick(() => {
                                if (
                                    this.selectedInputs[
                                        this.selectedQuestion.id
                                    ]
                                ) {
                                    this.selectedInputs[
                                        this.selectedQuestion.id
                                    ]["conditions"][condition.id] = condition;
                                } else {
                                    this.selectedInputs[
                                        this.selectedQuestion.id
                                    ] = {
                                        options: {},
                                        conditions: {},
                                    };
                                    this.selectedInputs[
                                        this.selectedQuestion.id
                                    ]["conditions"][condition.id] = condition;
                                }
                            });
                            this.selectedQuestion.next = condition.next;
                        } else {
                            selectedProducts.forEach((product) => {
                                const foundProductIndex =
                                    this.cart.products.findIndex(
                                        (selectedProduct) =>
                                            selectedProduct.id == product.id
                                    );
                                if (foundProductIndex == -1) return;
                                const foundProduct = {
                                    ...this.cart.products[foundProductIndex],
                                };
                                if (
                                    foundProduct?.belongsTo?.includes(
                                        condition.id
                                    )
                                ) {
                                    let parsedQuantityPrevious =
                                        this.checkForFormula(product.quantity)
                                            ? this.executeFormula(
                                                  product,
                                                  +event.target.dataset
                                                      .previousValue
                                              )
                                            : this.isJson(product.quantity)
                                            ? this.result(
                                                  product.quantity,
                                                  +event.target.dataset
                                                      .previousValue
                                              )
                                            : product.quantity;
                                    let salePriceProductPrevious =
                                        +product.salePrice *
                                            +parsedQuantityPrevious -
                                        (+product.salePrice *
                                            +parsedQuantityPrevious *
                                            +condition.discount) /
                                            100;
                                    foundProduct.quantity =
                                        +foundProduct.quantity -
                                        parsedQuantityPrevious;
                                    foundProduct.salePrice = (
                                        +foundProduct.salePrice -
                                        +salePriceProductPrevious
                                    ).toString();
                                    foundProduct.belongsTo =
                                        foundProduct?.belongsTo?.filter(
                                            (id) => id != condition.id
                                        );
                                    if (!foundProduct?.belongsTo?.length) {
                                        this.cart.products.splice(
                                            foundProductIndex,
                                            1
                                        );
                                    } else {
                                        this.cart.products[foundProductIndex] =
                                            foundProduct;
                                    }
                                }
                            });
                            delete this.selectedInputs?.[
                                this.selectedQuestion.id
                            ]?.[condition.id];
                        }
                    } catch (e) {
                        console.log(e);
                    }
                });
            } else if (optionType === "text-input") {
                this.allOptions[
                    this.selectedQuestion.configuration.groups[
                        groupIndex
                    ].options[index]
                ].value = event.target.value;
                this.syncValueBetweenAllOptionsAndConditionOption(
                    index,
                    groupIndex
                );
                const option =
                    this.selectedQuestion.configuration.groups[groupIndex]
                        .options[index];
                let selectedProducts = [...this.allOptions[option].products];
                if (event?.data?.length) {
                    this.selectedQuestion.next = this.allOptions[option].next;
                    this.$nextTick(() => {
                        if (this.selectedInputs[this.selectedQuestion.id]) {
                            this.selectedInputs[this.selectedQuestion.id][
                                "options"
                            ][this.allOptions[option].id] = {
                                ...this.allOptions[option],
                            };
                            this.selectedInputs[this.selectedQuestion.id][
                                "options"
                            ][this.allOptions[option].id].value =
                                event.target.value;
                        } else {
                            this.selectedInputs[this.selectedQuestion.id] = {
                                options: {},
                                conditions: {},
                            };
                            this.selectedInputs[this.selectedQuestion.id][
                                "options"
                            ][this.allOptions[option].id] = {
                                ...this.allOptions[option],
                            };
                            this.selectedInputs[this.selectedQuestion.id][
                                "options"
                            ][this.allOptions[option].id].value =
                                event.target.value;
                        }
                    });
                    selectedProducts.forEach((product) => {
                        let parsedQuantity = this.checkForFormula(
                            product.quantity
                        )
                            ? this.executeFormula(product)
                            : this.isJson(product.quantity)
                            ? this.result(product.quantity)
                            : product.quantity;
                        if (
                            this.cart.products.some((cp) => cp.id == product.id)
                        ) {
                            const foundProductIndex =
                                this.cart.products.findIndex(
                                    (selectedProduct) =>
                                        selectedProduct.id == product.id
                                );
                            const foundProduct = {
                                ...this.cart.products[foundProductIndex],
                            };
                            foundProduct.quantity = +parsedQuantity;
                            foundProduct?.belongsTo?.forEach((optionId) => {
                                if (optionId != this.allOptions[option].uuid) {
                                    let foundOption = {};
                                    for (
                                        let i = 0;
                                        i < this.questions.length;
                                        i++
                                    ) {
                                        const question = this.questions[i];
                                        foundOption =
                                            Object.values(this.allOptions).find(
                                                (op) => op.id == optionId
                                            ) ??
                                            (
                                                question?.value ?? question
                                            ).configuration.conditions.find(
                                                (cond) => cond.id == optionId
                                            ) ??
                                            {};
                                        if (
                                            Object.keys(foundOption).length > 0
                                        ) {
                                            break;
                                        }
                                    }
                                    const foundProductOther =
                                        optionId === "manual"
                                            ? this.manualProducts.find(
                                                  (pr) => pr.id == product.id
                                              )
                                            : {
                                                  ...foundOption?.products?.find(
                                                      (pr) =>
                                                          pr.id == product.id ??
                                                          {}
                                                  ),
                                              };
                                    const parsedQuantityFoundProduct =
                                        this.checkForFormula(
                                            foundProductOther.quantity
                                        )
                                            ? this.executeFormula(
                                                  foundProductOther
                                              )
                                            : this.isJson(
                                                  foundProductOther.quantity
                                              )
                                            ? this.result(
                                                  foundProductOther.quantity
                                              )
                                            : foundProductOther.quantity;
                                    foundProduct.quantity =
                                        +foundProduct.quantity +
                                        +parsedQuantityFoundProduct;
                                }
                            });
                            if (
                                !foundProduct.belongsTo.includes(
                                    this.allOptions[option].uuid
                                )
                            ) {
                                const salePriceProduct =
                                    +product.salePrice * +parsedQuantity;
                                foundProduct.salePrice = (
                                    +foundProduct.salePrice + +salePriceProduct
                                ).toString();
                            }
                            foundProduct?.belongsTo?.push(
                                this.allOptions[option].uuid
                            );
                            const uniqueItems = Array.from(
                                new Set(foundProduct?.belongsTo)
                            );
                            foundProduct.belongsTo = [...uniqueItems];
                            this.cart.products[foundProductIndex] = {
                                ...foundProduct,
                            };
                        } else {
                            const cartProduct = { ...product };
                            cartProduct.belongsTo = [
                                this.allOptions[option].uuid,
                            ];
                            cartProduct.quantity = +parsedQuantity;
                            cartProduct.salePrice =
                                +parsedQuantity * +product.salePrice;
                            this.cart.products.push({ ...cartProduct });
                        }
                    });
                } else {
                    delete this.selectedInputs?.[this.selectedQuestion.id]?.[
                        this.allOptions[option].id
                    ];
                    selectedProducts.forEach((product) => {
                        let parsedQuantity = this.checkForFormula(
                            product.quantity
                        )
                            ? this.executeFormula(product)
                            : this.isJson(product.quantity)
                            ? this.result(product.quantity)
                            : product.quantity;
                        const foundProductIndex = this.cart.products.findIndex(
                            (selectedProduct) =>
                                selectedProduct.id == product.id
                        );
                        if (foundProductIndex == -1) return;
                        const foundProduct = {
                            ...this.cart.products[foundProductIndex],
                        };
                        if (
                            foundProduct?.belongsTo?.includes(
                                this.allOptions[option].uuid
                            )
                        ) {
                            const salePriceProduct =
                                +product.salePrice * +parsedQuantity;
                            foundProduct.quantity =
                                +foundProduct.quantity - parsedQuantity;
                            foundProduct.salePrice = (
                                +foundProduct.salePrice - +salePriceProduct
                            ).toString();
                            foundProduct.belongsTo =
                                foundProduct?.belongsTo?.filter(
                                    (id) => id != this.allOptions[option].uuid
                                );
                            if (!foundProduct?.belongsTo?.length) {
                                this.cart.products.splice(foundProductIndex, 1);
                            } else {
                                this.cart.products[foundProductIndex] =
                                    foundProduct;
                            }
                        }
                    });
                }
                // get the conditions from conditions object of the changed option
                let optionConditions = Object.keys(
                    this.allOptions[option]?.conditions ?? {}
                ).map((conditionId) => this.allConditions[conditionId]);
                optionConditions.forEach((condition) => {
                    let conditionString = "";
                    for (let i = 0; i < condition.options.length; i++) {
                        const option = condition.options[i];
                        conditionString = this.generateConditionString(
                            option,
                            condition,
                            conditionString,
                            i
                        );
                    }
                    try {
                        let selectedProducts = JSON.parse(
                            JSON.stringify([...condition.products])
                        );
                        if (this.javascriptExecuter(conditionString)) {
                            selectedProducts.forEach((product) => {
                                let parsedQuantity = this.checkForFormula(
                                    product.quantity
                                )
                                    ? this.executeFormula(product)
                                    : this.isJson(product.quantity)
                                    ? this.result(product.quantity)
                                    : product.quantity;
                                if (
                                    this.cart.products.some(
                                        (cp) => cp.id == product.id
                                    )
                                ) {
                                    const foundProductIndex =
                                        this.cart.products.findIndex(
                                            (selectedProduct) =>
                                                selectedProduct.id == product.id
                                        );
                                    const foundProduct = {
                                        ...this.cart.products[
                                            foundProductIndex
                                        ],
                                    };
                                    foundProduct.quantity = +parsedQuantity;
                                    foundProduct?.belongsTo?.forEach(
                                        (conditionId) => {
                                            if (conditionId != condition.id) {
                                                const foundCondition =
                                                    this.selectedQuestion.configuration.conditions.find(
                                                        (cond) =>
                                                            cond.id ==
                                                            conditionId
                                                    ) ??
                                                    this.selectedQuestion.configuration.groups[
                                                        groupIndex
                                                    ].options.find(
                                                        (op) =>
                                                            this.allOptions[op]
                                                                .id ==
                                                            conditionId
                                                    );
                                                const foundProductOther =
                                                    conditionId === "manual"
                                                        ? this.manualProducts.find(
                                                              (pr) =>
                                                                  pr.id ==
                                                                  product.id
                                                          )
                                                        : {
                                                              ...foundCondition?.products?.find(
                                                                  (pr) =>
                                                                      pr.id ==
                                                                          product.id ??
                                                                      {}
                                                              ),
                                                          };
                                                const parsedQuantityFoundProduct =
                                                    this.checkForFormula(
                                                        foundProductOther.quantity
                                                    )
                                                        ? this.executeFormula(
                                                              foundProductOther
                                                          )
                                                        : this.isJson(
                                                              foundProductOther.quantity
                                                          )
                                                        ? this.result(
                                                              foundProductOther.quantity
                                                          )
                                                        : foundProductOther.quantity;
                                                foundProduct.quantity =
                                                    +foundProduct.quantity +
                                                    +parsedQuantityFoundProduct;
                                            }
                                        }
                                    );
                                    if (
                                        !foundProduct?.belongsTo?.includes(
                                            condition.id
                                        )
                                    ) {
                                        const salePriceCondition =
                                            +product.salePrice *
                                                +parsedQuantity -
                                            (+product.salePrice *
                                                +parsedQuantity *
                                                +condition.discount) /
                                                100;
                                        foundProduct.salePrice = (
                                            +foundProduct.salePrice +
                                            +salePriceCondition
                                        ).toString();
                                    }
                                    foundProduct?.belongsTo?.push(condition.id);
                                    const uniqueItems = Array.from(
                                        new Set(foundProduct?.belongsTo)
                                    );
                                    foundProduct.belongsTo = [...uniqueItems];
                                    this.cart.products[foundProductIndex] = {
                                        ...foundProduct,
                                    };
                                } else {
                                    const cartProduct = { ...product };
                                    cartProduct.belongsTo = [condition.id];
                                    cartProduct.quantity = +parsedQuantity;
                                    cartProduct.salePrice =
                                        +parsedQuantity * +product.salePrice -
                                        (+parsedQuantity *
                                            +product.salePrice *
                                            +condition.discount) /
                                            100;
                                    this.cart.products.push({
                                        ...cartProduct,
                                    });
                                }
                            });
                            this.$nextTick(() => {
                                if (
                                    this.selectedInputs[
                                        this.selectedQuestion.id
                                    ]
                                ) {
                                    this.selectedInputs[
                                        this.selectedQuestion.id
                                    ]["conditions"][condition.id] = condition;
                                } else {
                                    this.selectedInputs[
                                        this.selectedQuestion.id
                                    ] = {
                                        options: {},
                                        conditions: {},
                                    };
                                    this.selectedInputs[
                                        this.selectedQuestion.id
                                    ]["conditions"][condition.id] = condition;
                                }
                            });
                            this.selectedQuestion.next = condition.next;
                        } else {
                            selectedProducts.forEach((product) => {
                                let parsedQuantity = this.checkForFormula(
                                    product.quantity
                                )
                                    ? this.executeFormula(product)
                                    : this.isJson(product.quantity)
                                    ? this.result(product.quantity)
                                    : product.quantity;
                                const foundProductIndex =
                                    this.cart.products.findIndex(
                                        (selectedProduct) =>
                                            selectedProduct.id == product.id
                                    );
                                if (foundProductIndex == -1) return;
                                const foundProduct = {
                                    ...this.cart.products[foundProductIndex],
                                };
                                if (
                                    foundProduct?.belongsTo?.includes(
                                        condition.id
                                    )
                                ) {
                                    foundProduct.quantity =
                                        +foundProduct.quantity - parsedQuantity;
                                    const salePriceCondition =
                                        +product.salePrice * +parsedQuantity -
                                        (+product.salePrice *
                                            +parsedQuantity *
                                            +condition.discount) /
                                            100;
                                    foundProduct.salePrice = (
                                        +foundProduct.salePrice -
                                        +salePriceCondition
                                    ).toString();
                                    foundProduct.belongsTo =
                                        foundProduct?.belongsTo?.filter(
                                            (id) => id != condition.id
                                        );
                                    if (!foundProduct?.belongsTo?.length) {
                                        this.cart.products.splice(
                                            foundProductIndex,
                                            1
                                        );
                                    } else {
                                        this.cart.products[foundProductIndex] =
                                            foundProduct;
                                    }
                                }
                            });
                            delete this.selectedInputs?.[
                                this.selectedQuestion.id
                            ]?.[condition.id];
                        }
                    } catch (e) {
                        console.log(e);
                    }
                });
            }
            this.$emit("cartParent", this.cart);
            this.$nextTick(() => {
                this.$emit("selectedInputs", { ...this.selectedInputs });
            });
        },
        back() {
            const selectedQuestion = this.questions.find(
                (question) =>
                    (question?.value?.id ?? question?.id) ==
                    this.selectedQuestion.back
            );
            this.selectedQuestion =
                selectedQuestion?.value ??
                selectedQuestion ??
                this.selectedQuestion;
            this.$emit("selectedQuestionParent", this.selectedQuestion);
        },
        next() {
            const selectedQuestionId = this.selectedQuestion.id;
            let selectedQuestion = this.questions.find(
                (question) =>
                    (question?.value?.id ?? question?.id) ==
                    (this.selectedQuestion.next ||
                        (() => {
                            let questionIndex = this.questions.findIndex(
                                (q) =>
                                    (q?.value?.id ?? q?.id) ==
                                    this.selectedQuestion.id
                            );
                            if (questionIndex == this.questions.length - 1) {
                                return this.selectedQuestion.id;
                            } else {
                                return (
                                    this.questions[questionIndex + 1]?.value
                                        ?.id ??
                                    this.questions[questionIndex + 1]?.id
                                );
                            }
                        })())
            );
            this.selectedQuestion = selectedQuestion?.value ?? selectedQuestion;
            this.selectedQuestion.back = selectedQuestionId;
            this.$emit("selectedQuestionParent", this.selectedQuestion);
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
</style>

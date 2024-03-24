<template>
    <div style="word-break: break-word">
        <div
            v-if="cartToggle && minimizeCart"
            class="absolute bg-white rounded shadow-lg border border-blue-100 toggled-card"
            style="
                width: 30%;
                right: 0%;
                z-index: 1;
                max-height: 100vh;
                min-height: 100vh;
                overflow: auto;
            "
        >
            <Cart
                @minimizeCart="
                    minimizeCart = $event;
                    cartToggle = false;
                "
                :minimizeCart="minimizeCart"
                :cartParent="cart"
                :stylesConfigurationParent="stylesConfiguration"
                :selectedQuestionParent="selectedQuestion"
                @cartParent="
                    cart = $event;
                    $emit('cartParent', cart);
                "
                @manualProducts="$emit('manualProducts', $event)"
                :manualProductsParent="manualProducts"
                :selectedInputs="selectedInputs"
                :questionsParent="questions"
            />
        </div>
        <div
            v-if="isPublic && !$route.query.fullscreen"
            class="grid grid-cols-[5fr,1fr] mb-2 header"
        >
            <h1 class="header text-3xl font-bold">
                <router-link
                    class="text-indigo-400 hover:text-indigo-600"
                    :to="`/surveys?page=${returnPage}`"
                    >{{ $t("Surveys") }}</router-link
                >
                <span class="text-indigo-400 font-medium">/</span>
                {{ $t("Show") }}
            </h1>
        </div>
        <TextInput
            class="w-1/4 mb-5"
            :required="true"
            :isReadonly="true"
            :label="$t('Name')"
            v-model="name"
            @update:model-value="$store.commit('errors', {})"
        ></TextInput>
        <div
            v-if="minimizeCart"
            :style="`right: ${
                cartToggle ? '30' : '0'
            }%; top: 14%; background: #ed7d31; height: auto;`"
            class="absolute card px-3 py-2 bg-white-500 rounded shadow-md cursor-pointer"
            @click="cartToggle = !cartToggle"
        >
            <font-awesome-icon
                color="#2957a4"
                icon="fa-solid fa-cart-shopping"
            />
        </div>
        <Sections
            :layout="stylesConfiguration.layout"
            :stepsParent="steps"
            :selectedQuestionParent="selectedQuestion"
            :selectedChapterParent="selectedChapter"
            :cartParent="cart"
            :validatorParent="validator"
            :stylesConfigurationParent="stylesConfiguration"
            :questionsParent="questions"
            :manualProducts="manualProducts"
            @manualProducts="manualProducts = $event"
            :selectedInputs="selectedInputs"
            @selectedInputs="selectedInputs = $event"
            :minimizeCart="minimizeCart"
            @minimizeCart="minimizeCart = $event"
            @stepsParent="steps = $event"
            @selectedQuestionParent="selectedQuestion = $event"
            @selectedChapterParent="selectedChapter = $event"
            @cartParent="cart = $event"
            @validatorParent="validator = $event"
            @stylesConfigurationParent="stylesConfiguration = $event"
            @questionsParent="questions = $event"
        />
    </div>
</template>

<script>
import { v4 as uuidv4 } from "uuid";

import Sections from "./Sections/Index.vue";
import TextInput from "@/Components/TextInput.vue";
import { mapGetters } from "vuex";
import Cart from "./Sections/Cart.vue";
import allOptionsMixin from "@/Mixins/allOptionsMixin";
import surveyFormulaMixin from "@/Mixins/surveyFormulaMixin";

export default {
    mixins: [allOptionsMixin, surveyFormulaMixin],
    components: {
        Cart,
        Sections,
        TextInput,
    },
    async mounted() {
        if(this.$route.query.page){
            this.returnPage=this.$route.query.page; 
         }
        try {
            this.$store.commit("showLoader", true);
            const response = await this.$store.dispatch(
                "products/productsWithQuantity",
                {
                    public: !this.isPublic,
                    type: "software",
                }
            );
            this.products = response?.data?.products ?? this.products;
            this.$store
                .dispatch("surveys/surveyProducts", this.$route.params.id)
                .then((res) => {
                    this.surveyProducts = res?.data?.products;
                });
            this.refresh();
            this.$store
                .dispatch("surveyConfiguration/list", {
                    id: this.$route.params.id,
                    public: !this.isPublic,
                })
                .then((res) => {
                    this.cart.config = res?.data?.surveyConfigurations;
                });
        } catch (e) {
        } finally {
            this.saveSurveyJsonToLocalStorage();
            this.$store.commit("showLoader", false);
        }
    },
    data() {
        return {
            name: "",
            returnPage:'',
            surveyProducts: [],
            cartToggle: false,
            minimizeCart: false,
            minimizeSteps: false,
            public: this.$route.meta.public,
            manualProducts: [],
            selectedInputs: {},
            products: {
                data: [],
                links: [],
            },
            toggleError: false,
            surveyId: uuidv4(),
            errorList: {},
            validator: {},
            selectedCondition: null,
            selectedChapter: null,
            steps: [],
            cart: {
                products: [],
                config: [],
                addProductsManually: false,
                minimizeCart: false,
                minimizeSteps: false,
            },
            stylesConfiguration: {
                steps: {
                    cardBgColor: "#ffffff",
                    cardTextColor: "#000000",
                    cardHeaderBgColor: "#fafafa",
                    cardHeaderTextColor: "#000000",
                },
                questionDetails: {
                    cardBgColor: "#ffffff",
                    cardTextColor: "#000000",
                },
                cart: {
                    cardBgColor: "#ffffff",
                    cardTextColor: "#000000",
                    cardHeaderBgColor: "#fafafa",
                    cardHeaderTextColor: "#000000",
                },
                productDetails: {
                    cardBgColor: "#ffffff",
                    cardTextColor: "#000000",
                    cardHeaderBgColor: "#fafafa",
                    cardHeaderTextColor: "#000000",
                },
                layout: {
                    id: "main-container",
                    type: "row",
                    customCss: "min-height: 100vh;",
                    children: [
                        {
                            id: "steps",
                            type: "column",
                            children: [],
                            value: "25%",
                            contains: "steps",
                        },
                        {
                            id: "right-column",
                            type: "column",
                            children: [
                                {
                                    id: "question",
                                    type: "row",
                                    children: [],
                                    value: "minmax(300px, max-content)",
                                    contains: "question",
                                },
                                {
                                    id: "bottom-row",
                                    type: "row",
                                    children: [
                                        {
                                            id: "details",
                                            type: "column",
                                            children: [],
                                            value: "60%",
                                            contains: "details",
                                        },
                                        {
                                            id: "cart",
                                            type: "column",
                                            children: [],
                                            value: "40%",
                                            contains: "cart",
                                        },
                                    ],
                                    value: "auto",
                                    contains: null,
                                },
                            ],
                            value: "75%",
                            contains: null,
                        },
                    ],
                    value: "100%",
                    contains: null,
                },
            },
            chapters: [],
            option: {},
            details: "",
            questions: [],
            selectedQuestion: null,
        };
    },
    computed: {
        ...mapGetters("auth", ["authenticated"]),
        ...mapGetters(["isPublic"]),
        filteredQuestion() {
            return this.questions.filter((question) =>
                this.selectedQuestion
                    ? this.selectedQuestion.id != question.id
                    : true
            );
        },
    },
    methods: {
        /**
         * adds the condition to the selected option's condition object
         * trigerred when the option is selected for any condition
         * @param {oldValue} the previous value before the on change event is triggred for the select input
         * @param {newValue} the selected value on change
         * @param {condition} the condition to be added to the option's condition object
         * @param {conditionOptionIndex} index of the condition option (a condition can have several sub conditions, a condition option is that sub condition)
         */
        addConditionToSelectedOption(
            { oldValue, newValue },
            condition,
            conditionOptionIndex
        ) {
            let option = null;
            let subConditions = [];
            // since the the value has been changed we need to remove the condition from the previously selected option
            if (typeof oldValue === "object") {
                // check if oldValue exists and is object
                option = { ...oldValue };
                // grab the subConditions array of the selected option
                subConditions =
                    option.conditions[condition.id]?.subConditions ?? [];
                // find the index of conditionOptionIndex in the subConditions array
                const subConditionIndex = subConditions.findIndex(
                    (subCondition) => subCondition == conditionOptionIndex
                );
                // remove the subCondition from the subConditions array based on the computed subConditionIndex
                subConditions.splice(subConditionIndex, 1);
                // update the option since the subConditions array has changed
                option.conditions[condition.id].subConditions = subConditions;
                /**
                 * if the subConditions array becomes empty this means that the current option is not part of any condition
                 * hence we check the length and if 0 we delete the condition from the conditions object of the selected option
                 */
                if (subConditions.length == 0) {
                    // wrap in try/catch since the delete could result in an error if the condition does not exist
                    try {
                        delete option.conditions[condition.id];
                    } catch (e) {
                        console.log(e);
                    }
                }
                this.addOptionMixin(option); // update the option in the allOptions state in survey store
            }
            //check if newValue is spreadable and exists
            if (typeof newValue === "object") {
                option = { ...newValue };
                // grab the subConditions array of the selected option
                subConditions =
                    option.conditions[condition.id]?.subConditions ?? [];
                /**
                 * push the 'conditionOptionIndex' in the 'subConditions' and assign it to the option
                 */
                subConditions.push(conditionOptionIndex);
                option.conditions[condition.id] = {
                    subConditions: Array.from(new Set(subConditions)), // get only the unique values from the subConditions array since there can be duplicates
                };
                this.addOptionMixin(option); // update the option in the allOptions state in survey store
            }
        },
        /**
         * saves the surveyJson to local storage
         */
        saveSurveyJsonToLocalStorage() {
            const surveyJson = {
                id: this.$route.params.id ?? this.surveyId,
                name: "Survey 1",
                steps: this.steps,
                cart: this.cart,
                stylesConfiguration: {
                    ...this.stylesConfiguration,
                },
            };
            localStorage.setItem("surveyJson", JSON.stringify(surveyJson));
        },
        async refresh() {
            this.questions = [];
            this.steps = [];
            this.$store
                .dispatch("surveys/show", {
                    id: this.$route.params.id,
                    public: !this.isPublic,
                })
                .then((res) => {
                    this.name = res?.data?.name ?? "";
                    this.cart.addProductsManually =
                        res?.data?.isManualProducts == 1;
                    this.cart.minimizeCart = res?.data?.minimizeCart == 1;
                    this.cart.minimizeSteps = res?.data?.minimizeSteps == 1;
                    this.minimizeCart = res?.data?.minimizeCart == 1;
                    this.minimizeSteps = res?.data?.minimizeSteps == 1;
                    this.stylesConfiguration = res?.data?.stylesConfiguration;
                    let questionsAndChapters =
                        res?.data?.questionsAndChapters ?? [];
                    questionsAndChapters.forEach((questionOrChapter) => {
                        if (questionOrChapter.questions !== undefined) {
                            questionOrChapter.questions.forEach((question) => {
                                question.configuration.groups.forEach(
                                    (group) => {
                                        group.options.forEach((option) => {
                                            this.addOptionMixin(option);
                                        });
                                    }
                                );
                                question.configuration.conditions.forEach(
                                    (condition) => {
                                        this.addConditionMixin(condition);
                                    }
                                );
                            });
                        } else {
                            questionOrChapter.configuration.groups.forEach(
                                (group) => {
                                    group.options.forEach((option) => {
                                        this.addOptionMixin(option);
                                    });
                                }
                            );
                            questionOrChapter.configuration.conditions.forEach(
                                (condition) => {
                                    this.addConditionMixin(condition);
                                }
                            );
                        }
                    });
                    questionsAndChapters.forEach((questionOrChapter) => {
                        if (questionOrChapter.questions !== undefined) {
                            let questionStep = {
                                id: uuidv4(),
                                type: "chapter",
                                value: {
                                    id: questionOrChapter.chapterId,
                                    title: questionOrChapter.chapterTitle,
                                    questions: questionOrChapter.questions,
                                    chapterOrder: questionOrChapter.order,
                                },
                            };
                            questionStep.value.questions.forEach(
                                (question, index) => {
                                    let modifiedQuestion = {
                                        ...question,
                                    };
                                    modifiedQuestion?.configuration?.groups?.forEach(
                                        (group) => {
                                            if (group?.options) {
                                                group?.options?.forEach(
                                                    (option) => {
                                                        this.allOptions[
                                                            option.id
                                                        ].value = "";
                                                        this.allOptions[
                                                            option.id
                                                        ].conditions = {};
                                                        this.allOptions[
                                                            option.id
                                                        ].products =
                                                            this.allOptions[
                                                                option.id
                                                            ].products.map(
                                                                (product) => {
                                                                    return {
                                                                        ...(this.surveyProducts?.find(
                                                                            (
                                                                                pr
                                                                            ) =>
                                                                                pr.id ==
                                                                                product.id
                                                                        ) ??
                                                                            {}),
                                                                        quantity:
                                                                            product.quantity,
                                                                    };
                                                                }
                                                            );
                                                    }
                                                );
                                            }
                                        }
                                    );
                                    if (
                                        modifiedQuestion?.configuration
                                            ?.conditions
                                    )
                                        modifiedQuestion?.configuration?.conditions?.forEach(
                                            (condition) => {
                                                condition.products =
                                                    condition.products.map(
                                                        (product) => {
                                                            return {
                                                                ...(this.surveyProducts?.find(
                                                                    (pr) =>
                                                                        pr.id ==
                                                                        product.id
                                                                ) ?? {}),
                                                                quantity:
                                                                    product.quantity,
                                                            };
                                                        }
                                                    );
                                                condition.options.forEach(
                                                    (option) => {
                                                        option.option =
                                                            this.allOptions[
                                                                option.option
                                                            ];
                                                    }
                                                );
                                                this.addConditionMixin(
                                                    condition
                                                );
                                            }
                                        );
                                    modifiedQuestion.configuration.groups =
                                        modifiedQuestion.configuration.groups.map(
                                            (group) => {
                                                return {
                                                    ...group,
                                                    options: group.options.map(
                                                        (op) => op.id
                                                    ),
                                                };
                                            }
                                        );
                                    questionStep.value.questions[index] = {
                                        id: uuidv4(),
                                        type: "question",
                                        value: modifiedQuestion,
                                    };
                                }
                            );
                            this.questions = [
                                ...this.questions,
                                ...questionStep.value.questions,
                            ];
                            this.steps.push({ ...questionStep });
                        } else {
                            let modifiedQuestion = {
                                ...questionOrChapter,
                            };
                            modifiedQuestion?.configuration?.groups?.forEach(
                                (group) => {
                                    if (group?.options)
                                        group?.options?.forEach((option) => {
                                            this.allOptions[option.id].value =
                                                "";
                                            this.allOptions[
                                                option.id
                                            ].conditions = {};
                                            this.allOptions[
                                                option.id
                                            ].products = this.allOptions[
                                                option.id
                                            ].products.map((product) => {
                                                return {
                                                    ...(this.surveyProducts?.find(
                                                        (pr) =>
                                                            pr.id == product.id
                                                    ) ?? {}),
                                                    quantity: product.quantity,
                                                };
                                            });
                                        });
                                }
                            );
                            if (modifiedQuestion?.configuration?.conditions)
                                modifiedQuestion?.configuration?.conditions?.forEach(
                                    (condition) => {
                                        condition.products =
                                            condition.products.map(
                                                (product) => {
                                                    return {
                                                        ...(this.surveyProducts?.find(
                                                            (pr) =>
                                                                pr.id ==
                                                                product.id
                                                        ) ?? {}),
                                                        quantity:
                                                            product.quantity,
                                                    };
                                                }
                                            );
                                        condition.options.forEach((option) => {
                                            option.option =
                                                this.allOptions[option.option];
                                        });
                                        this.addConditionMixin(condition);
                                    }
                                );
                            modifiedQuestion.configuration.groups =
                                modifiedQuestion.configuration.groups.map(
                                    (group) => {
                                        return {
                                            ...group,
                                            options: group.options.map(
                                                (op) => op.id
                                            ),
                                        };
                                    }
                                );
                            this.questions = [
                                ...this.questions,
                                { ...modifiedQuestion },
                            ];
                            this.steps.push({
                                id: uuidv4(),
                                type: "question",
                                value: modifiedQuestion,
                            });
                        }
                    });
                    for (let key in this.allConditions) {
                        const condition = this.allConditions[key];
                        condition.options.forEach((option, index) => {
                            this.addConditionToSelectedOption(
                                {
                                    oldValue: "",
                                    newValue: option.option,
                                },
                                condition,
                                index
                            );
                        });
                    }
                    this.selectedQuestion =
                        this.questions?.[0]?.value ??
                        this.questions?.[0] ??
                        null;
                })
                .catch((e) => {
                    console.log(e);
                });
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

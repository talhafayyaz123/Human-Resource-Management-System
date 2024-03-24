<template>
    <div
        :data-division="sectionDivision()"
        :style="
            (layout.customCss ?? '') +
            'grid-template-' +
            layoutType() +
            's: ' +
            sectionDivision()
        "
        class="grid gap-5"
    >
        <Sections
            v-for="(section, index) in layout.children"
            :key="'section-' + index"
            :layout="section"
            :name="name"
            :stepsParent="steps"
            :selectedQuestionParent="selectedQuestion"
            :selectedChapterParent="selectedChapter"
            :cartParent="cart"
            :validatorParent="validator"
            :stylesConfigurationParent="stylesConfiguration"
            :questionsParent="questions"
            :manualProducts="manualProducts"
            @manualProducts="$emit('manualProducts', $event)"
            :selectedInputs="selectedInputs"
            @selectedInputs="$emit('selectedInputs', $event)"
            :minimizeSteps="minimizeSteps"
            :minimizeCart="minimizeCart"
            @minimizeCart="$emit('minimizeCart', $event)"
            @stepsParent="
                steps = $event;
                $emit('stepsParent', steps);
            "
            @selectedQuestionParent="
                selectedQuestion = $event;
                $emit('selectedQuestionParent', selectedQuestion);
            "
            @selectedChapterParent="
                selectedChapter = $event;
                $emit('selectedChapterParent', selectedChapter);
            "
            @cartParent="
                cart = $event;
                $emit('cartParent', cart);
            "
            @validatorParent="
                validator = $event;
                $emit('validatorParent', validator);
            "
            @stylesConfigurationParent="
                stylesConfiguration = $event;
                $emit('stylesConfigurationParent', stylesConfiguration);
            "
            @questionsParent="
                questions = $event;
                $emit('questionsParent', questions);
            "
        />
        <div v-if="layout.contains">
            <div
                v-if="layout.contains === 'steps'"
                :style="`color: ${stylesConfiguration.steps?.cardTextColor}; background: ${stylesConfiguration.steps?.cardBgColor}; min-height: 100%`"
                class="step-card"
            >
                <Steps
                    :name="name"
                    :minimizeStepsParent="minimizeSteps"
                    :stepsParent="steps"
                    :selectedQuestionParent="selectedQuestion"
                    :selectedChapterParent="selectedChapter"
                    :questionsParent="questions"
                    :stylesConfigurationParent="stylesConfiguration"
                    :validatorParent="validator"
                    @stepsParent="
                        steps = $event;
                        $emit('stepsParent', steps);
                    "
                    @selectedQuestionParent="
                        selectedQuestion = $event;
                        $emit('selectedQuestionParent', selectedQuestion);
                    "
                    @selectedChapterParent="
                        selectedChapter = $event;
                        $emit('selectedChapterParent', selectedChapter);
                    "
                    @questionsParent="
                        questions = $event;
                        $emit('questionsParent', questions);
                    "
                    @stylesConfigurationParent="
                        stylesConfiguration = $event;
                        $emit('stylesConfigurationParent', stylesConfiguration);
                    "
                    @validatorParent="
                        validator = $event;
                        $emit('validatorParent', validator);
                    "
                />
            </div>
            <div
                v-if="layout.contains === 'question'"
                :style="`color: ${stylesConfiguration.questionDetails?.cardTextColor}; background: ${stylesConfiguration.questionDetails?.cardBgColor}; min-height: 100%`"
                class="question-card"
            >
                <Question
                    @selectedInputs="$emit('selectedInputs', $event)"
                    :manualProducts="manualProducts"
                    :selectedQuestionParent="selectedQuestion"
                    :questionsParent="questions"
                    :validatorParent="validator"
                    :cartParent="cart"
                    @selectedQuestionParent="
                        selectedQuestion = $event;
                        $emit('selectedQuestionParent', selectedQuestion);
                    "
                    @questionsParent="
                        questions = $event;
                        $emit('questionsParent', questions);
                    "
                    @validatorParent="
                        validator = $event;
                        $emit('validatorParent', validator);
                    "
                    @cartParent="
                        cart = $event;
                        $emit('cartParent', cart);
                    "
                />
            </div>
            <div
                v-if="layout.contains === 'details'"
                class="detail-card"
                :style="`min-height: 500px; color: ${stylesConfiguration.productDetails?.cardTextColor}; background: ${stylesConfiguration.productDetails?.cardBgColor}; min-height: 100%`"
            >
                <Details
                    :selectedQuestionParent="selectedQuestion"
                    :stylesConfigurationParent="stylesConfiguration"
                    @selectedQuestionParent="
                        selectedQuestion = $event;
                        $emit('selectedQuestionParent', selectedQuestion);
                    "
                />
            </div>
            <div
                v-if="layout.contains === 'cart' && !minimizeCart"
                :style="`color: ${stylesConfiguration.cart?.cardTextColor}; background: ${stylesConfiguration.cart?.cardBgColor}; min-height: 100%`"
                class="cart-card"
            >
                <Cart
                    @minimizeCart="$emit('minimizeCart', $event)"
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
        </div>
    </div>
</template>

<script>
import Steps from "./Steps.vue";
import Question from "./Question.vue";
import Details from "./Details.vue";
import Cart from "./Cart.vue";

export default {
    name: "Sections",
    components: {
        Steps,
        Question,
        Details,
        Cart,
    },
    props: [
        "minimizeSteps",
        "minimizeCart",
        "layout",
        "stepsParent",
        "cartParent",
        "stylesConfigurationParent",
        "selectedQuestionParent",
        "questionsParent",
        "validatorParent",
        "selectedChapterParent",
        "manualProducts",
        "selectedInputs",
        "name",
    ],
    emits: [
        "minimizeCart",
        "stepsParent",
        "cartParent",
        "stylesConfigurationParent",
        "questionsParent",
        "validatorParent",
        "selectedQuestionParent",
        "selectedChapterParent",
    ],
    watch: {
        cartParent(val) {
            this.cart = val;
        },
        stepsParent(val) {
            this.steps = val;
        },
        selectedQuestionParent(val) {
            this.selectedQuestion = val;
        },
        stylesConfigurationParent(val) {
            this.stylesConfiguration = val;
        },
        validatorParent(val) {
            this.validator = val;
        },
        questionsParent(val) {
            this.questions = val;
        },
        selectedChapterParent(val) {
            this.selectedChapter = val;
        },
    },
    mounted() {
        this.steps = this.stepsParent;
        this.cart = this.cartParent;
        this.stylesConfiguration = this.stylesConfigurationParent;
        this.questions = this.questionsParent;
        this.validator = this.validatorParent;
        this.selectedQuestion = this.selectedQuestionParent;
        this.selectedChapter = this.selectedChapterParent;
    },
    data() {
        return {
            selectedChapter: null,
            steps: [],
            cart: [],
            stylesConfiguration: {},
            selectedQuestion: null,
            questions: [],
            validator: {},
        };
    },
    methods: {
        layoutType() {
            let type = this.layout.type;
            this.layout.children.forEach((layout) => {
                type = layout.type;
            });
            return type;
        },
        sectionDivision() {
            let sectionDivision = "";
            for (let i = 0; i < this.layout.children.length; i++) {
                sectionDivision += `${this.layout.children[i].value}${
                    this.layout.children[i + 1] ? " " : ""
                }`;
            }
            return sectionDivision;
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

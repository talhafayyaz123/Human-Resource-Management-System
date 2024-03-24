<template>
    <div
        v-for="(inputBlock, index) in numberInputs"
        :key="`input-block-${index}`"
        class="flex items-center"
    >
        <div class="flex items-center p-2" v-if="inputBlock.type === 'single'">
            <div class="flex items-center" style="width: fit-content">
                <p>(&nbsp;</p>
                <div class="autocomplete">
                    <input
                        type="text"
                        class="border self-center w-3rem"
                        @input="onChange(inputBlock.value)"
                        v-model="inputBlock.value"
                        @keyup.down="onArrowDown"
                        @keyup.up="onArrowUp"
                        @keyup.enter="onEnter"
                    />
                    <ul
                        id="autocomplete-results"
                        v-show="isOpen"
                        class="autocomplete-results"
                    >
                        <li class="loading" v-if="isLoading">
                            Loading results...
                        </li>
                        <li
                            v-else
                            v-for="(result, i) in results"
                            :key="i"
                            @click="selectValue(inputBlock, result)"
                            class="autocomplete-result"
                            :class="{ 'is-active': i === arrowCounter }"
                        >
                            {{ result.title }}
                        </li>
                    </ul>
                </div>
                <NumberInput
                    :items="items"
                    :numberInputs="inputBlock.parenthesis"
                />
                <button
                    @click="addBlockInParenthesis(index)"
                    class="bg-gray-500 px-2 text-white ml-2 rounded self-center"
                >
                    +
                </button>
                <p>&nbsp;)</p>
            </div>
        </div>
        <div
            class="flex items-center p-3 relative"
            v-else-if="inputBlock.type === 'block'"
        >
            <font-awesome-icon
                @click="removeBlock(index)"
                class="cursor-pointer cross top-4"
                icon="fa-solid fa-xmark"
            />
            <select
                v-model="inputBlock.operator"
                class="border self-center w-3rem"
                name=""
                id=""
            >
                <option value="+">+</option>
                <option value="-">-</option>
                <option value="*">*</option>
                <option value="/">/</option>
                <option value="%">%</option>
                <option value="**">^</option>
            </select>
            <div class="flex ml-2 items-center" style="width: fit-content">
                <p>(&nbsp;</p>
                <div class="autocomplete">
                    <input
                        type="text"
                        class="border self-center w-3rem"
                        @input="onChange(inputBlock.value)"
                        v-model="inputBlock.value"
                        @keyup.down="onArrowDown"
                        @keyup.up="onArrowUp"
                        @keyup.enter="onEnter"
                    />
                    <ul
                        id="autocomplete-results"
                        v-show="isOpen"
                        class="autocomplete-results"
                    >
                        <li class="loading" v-if="isLoading">
                            Loading results...
                        </li>
                        <li
                            v-else
                            v-for="(result, i) in results"
                            :key="i"
                            @click="selectValue(inputBlock, result)"
                            class="autocomplete-result"
                            :class="{ 'is-active': i === arrowCounter }"
                        >
                            {{ result.title }}
                        </li>
                    </ul>
                </div>
                <NumberInput
                    :type="'block'"
                    :numberInputs="inputBlock.parenthesis"
                />
                <button
                    @click="addBlockInParenthesis(index)"
                    class="bg-gray-500 px-2 text-white ml-2 rounded self-center"
                >
                    +
                </button>
                <p>&nbsp;)</p>
            </div>
        </div>
    </div>
    <button
        v-if="type === 'single'"
        @click="addBlock"
        class="bg-gray-500 px-2 text-white ml-2 rounded self-center"
    >
        +
    </button>
    <div class="flex">
        <p v-if="type === 'single'" class="self-center ml-2">
            = {{ isNaN(result) ? 0 : result }}
        </p>
    </div>
</template>

<script>
import allOptionsMixin from "@/Mixins/allOptionsMixin";

export default {
    mixins: [allOptionsMixin],
    name: "NumberInput",
    data() {
        return {
            isOpen: false,
            results: [],
            search: "",
            isLoading: false,
            arrowCounter: 0,
        };
    },
    mounted() {
        document.addEventListener("click", this.handleClickOutside);
    },
    unmounted() {
        document.removeEventListener("click", this.handleClickOutside);
    },
    components: {},
    props: {
        questions: Array,
        numberInputs: Array,
        type: String,
        items: {
            type: Array,
            required: false,
            default: () => [],
        },
    },
    computed: {
        result() {
            let result = 0;
            this.numberInputs.forEach((input) => {
                if (input.operator) {
                    this.javascriptExecuter(
                        `result = result ${
                            input.operator
                        } ${+this.calculateResult(input)}`
                    );
                } else {
                    result = +this.calculateResult(input);
                }
            });
            return result;
        },
    },
    methods: {
        selectValue(inputBlock, result) {
            inputBlock.value = result.title;
            inputBlock.id = result.uuid;
        },
        onChange(value) {
            // Let's warn the parent that a change was made
            this.$emit("input", value);

            // Is the data given by an outside ajax request?
            if (this.isAsync) {
                this.isLoading = true;
            } else {
                // Let's search our flat array
                this.filterResults(value);
                this.isOpen = true;
            }
        },

        filterResults(value) {
            // first uncapitalize all the things
            this.results = this.items
                .filter((item) => {
                    return (
                        this.allOptions[item].title
                            .toLowerCase()
                            .indexOf(value.toLowerCase()) > -1
                    );
                })
                .map((item) => this.allOptions[item]);
        },
        setResult(result) {
            this.search = result.title;
            this.isOpen = false;
        },
        onArrowDown(evt) {
            if (this.arrowCounter < this.results.length) {
                this.arrowCounter = this.arrowCounter + 1;
            }
        },
        onArrowUp() {
            if (this.arrowCounter > 0) {
                this.arrowCounter = this.arrowCounter - 1;
            }
        },
        onEnter() {
            this.search = this.results[this.arrowCounter];
            this.isOpen = false;
            this.arrowCounter = -1;
        },
        handleClickOutside(evt) {
            if (!this.$el.contains(evt.target)) {
                this.isOpen = false;
                this.arrowCounter = -1;
            }
        },
        calculateResult(numberInput) {
            let result = 0;
            if (!numberInput.parenthesis.length) {
                let foundOption = {};
                for (let i = 0; i < this.questions.length; i++) {
                    const question = this.questions[i];
                    foundOption =
                        (
                            question?.value ?? question
                        ).configuration.options.find(
                            (op) => this.allOptions[op].uuid === numberInput.id
                        ) ?? {};
                    if (Object.keys(foundOption).length > 0) {
                        break;
                    }
                }
                result = +(foundOption?.value ?? numberInput.value);
            } else {
                let foundOption = {};
                for (let i = 0; i < this.questions.length; i++) {
                    const question = this.questions[i];
                    foundOption =
                        (
                            question?.value ?? question
                        ).configuration.options.find(
                            (op) => this.allOptions[op].uuid === numberInput.id
                        ) ?? {};
                    if (Object.keys(foundOption).length > 0) {
                        break;
                    }
                }
                result = +(foundOption?.value ?? numberInput.value);
                numberInput.parenthesis.forEach((input) => {
                    // result = this.calculateResult(input);
                    this.javascriptExecuter(
                        `result = result ${
                            input.operator
                        } ${this.calculateResult(input)}`
                    );
                });
            }
            return result;
        },
        removeBlock(index) {
            this.numberInputs.splice(index, 1);
        },
        addBlockInParenthesis(index) {
            this.numberInputs[index].parenthesis.push({
                type: "block",
                operator: "+",
                value: "",
                parenthesis: [],
            });
        },
        addBlock() {
            this.numberInputs.push({
                type: "block",
                operator: "+",
                value: "",
                parenthesis: [],
            });
        },
    },
};
</script>

<style scoped>
.cross {
    position: absolute;
    right: 10px;
}

.top-4 {
    top: 0;
    height: 0.7rem;
}

.w-3rem {
    width: 3rem !important;
}
.autocomplete {
    position: relative;
    width: auto;
}

.autocomplete-results {
    padding: 0;
    margin: 0;
    border: 1px solid #eeeeee;
    height: 120px;
    overflow: auto;
    width: 100%;
}

.autocomplete-result {
    list-style: none;
    text-align: left;
    padding: 4px 2px;
    cursor: pointer;
}

.autocomplete-result.is-active,
.autocomplete-result:hover {
    background-color: #4aae9b;
    color: white;
}
</style>

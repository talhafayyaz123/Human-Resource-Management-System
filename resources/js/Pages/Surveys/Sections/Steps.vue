<template>
    <div
        class="step-card-header"
        :style="
            'color: ' +
            stylesConfiguration.steps?.cardHeaderTextColor +
            '; background: ' +
            stylesConfiguration.steps?.cardHeaderBgColor
        "
    >
        <h3
            :style="'color: ' + stylesConfiguration.steps?.cardHeaderTextColor"
            class=""
        >
            {{ $t("Steps") }}
        </h3>
    </div>
    <div class="step-card-body">
        <draggable
            class="steps-container"
            :disabled="$route.name === 'SurveysShow'"
            :clone="cloneQuestion"
            :list="steps"
            @change="onQuestionChange"
            group="questions"
            item-key="id"
            :style="`${
                minimizeStepsParent
                    ? `max-height: ${
                          $route.name === 'SurveysShow' ? '20' : '10'
                      }rem;`
                    : ''
            } overflow: auto`"
        >
            <template #item="{ element: step, index: stepIndex }">
                <div
                    :id="'step-' + step?.value?.id"
                    class="cursor-pointer mb-3"
                >
                    <div
                        :class="[
                            'relative',
                            selectedQuestion?.id == step?.value?.id
                                ? 'font-bold'
                                : '',
                        ]"
                        v-if="step.type === 'question'"
                        @click="
                            selectedQuestion = step.value;
                            $emit('selectedQuestionParent', selectedQuestion);
                        "
                    >
                        <div class="form-group step-question">
                            <div class="form-control flex items-center justify-between" v-if="toEdit !== step.id">
                                <p>{{ step.value.title }}</p>
                                <div class="">
                                    <font-awesome-icon
                                        v-if="$route.name !== 'SurveysShow'"
                                        @click="editQuestion(stepIndex)"
                                        class="cursor-pointer mr-1"
                                        icon="fa-solid fa-pencil"
                                    />
                                    <font-awesome-icon
                                        v-if="$route.name !== 'SurveysShow'"
                                        @click="removeQuestion(stepIndex)"
                                        class="cursor-pointer"
                                        icon="fa-solid fa-xmark"
                                    />
                                </div>
                            </div>
                            <input
                                v-else
                                @keypress.enter="updateQuestion(stepIndex)"
                                v-model="nameTemp"
                                class="form-control"
                                type="text"
                            />
                        </div>
                    </div>
                    <div class="chapter-list relative" v-else-if="step.type === 'chapter'">
                        <div class="flex items-center justify-between" v-if="toEdit !== step.id">
                            <h1 class="title">{{ step.value.title }}</h1>
                            <div class="">
                                <font-awesome-icon
                                v-if="$route.name !== 'SurveysShow'"
                                @click="editQuestion(stepIndex)"
                                class="cursor-pointer mr-2"
                                icon="fa-solid fa-pencil"
                            />
                            <font-awesome-icon
                                v-if="$route.name !== 'SurveysShow'"
                                @click="removeQuestion(stepIndex)"
                                class="cursor-pointer"
                                icon="fa-solid fa-xmark"
                            />
                            </div>
                        </div>
                        <div class="form-group" v-else>
                            <input
                            
                            @keypress.enter="updateQuestion(stepIndex)"
                            v-model="nameTemp"
                            class="edit-input form-control"
                            type="text"
                        />
                        </div>
                        
                        <ul class="chapter">
                            <draggable
                                :disabled="$route.name === 'SurveysShow'"
                                @change="
                                    onChapterQuestionChange(
                                        $event,
                                        step,
                                        stepIndex
                                    )
                                "
                                :clone="cloneChapterQuestion"
                                :list="step.value.questions ?? []"
                                group="questions"
                                item-key="id"
                            >
                                <template
                                    #item="{
                                        element: question,
                                        index: questionIndex,
                                    }"
                                >
                                    <li
                                        @click="
                                            selectedQuestion = question.value;
                                            $emit(
                                                'selectedQuestionParent',
                                                selectedQuestion
                                            );
                                        "
                                        :class="[
                                            'relative',
                                            selectedQuestion?.id ==
                                            question?.value?.id
                                                ? 'font-bold'
                                                : '',
                                        ]"
                                    >
                                        <div
                                            :id="'step-' + question?.value?.id"
                                            v-if="
                                                toEdit !== question?.value?.id
                                            "
                                            class="form-group"
                                        >
                                        <div class="form-control flex items-center justify-between">
                                            <span
                                                >{{ question?.value?.title }}
                                            </span>
                                            <div>
                                                <font-awesome-icon
                                                v-if="
                                                    $route.name !==
                                                    'SurveysShow'
                                                "
                                                @click="
                                                    editQuestion(
                                                        stepIndex,
                                                        questionIndex
                                                    )
                                                "
                                                class="cursor-pointer mr-2"
                                                icon="fa-solid fa-pencil"
                                            />
                                            <font-awesome-icon
                                                v-if="
                                                    $route.name !==
                                                    'SurveysShow'
                                                "
                                                @click="
                                                    removeQuestion(
                                                        stepIndex,
                                                        questionIndex
                                                    )
                                                "
                                                class="cursor-pointer"
                                                icon="fa-solid fa-xmark"
                                            />
                                            </div>
                                        </div>
                                            
                                            
                                        </div>
                                        <div class="form-group" v-else>
                                            <input
                                            
                                            @keypress.enter="
                                                updateQuestion(
                                                    stepIndex,
                                                    questionIndex
                                                )
                                            "
                                            v-model="nameTemp"
                                            class="edit-input form-control"
                                            type="text"
                                        />
                                        </div>
                                    </li>
                                </template>
                            </draggable>
                            <li
                                v-if="$route.name !== 'SurveysShow'"
                                class="form-group"
                            >
                                <input
                                    ref="chapterQuestionInput"
                                    class="form-control"
                                    @keypress.enter="
                                        addQuestionToChapter(step.value, $event)
                                    "
                                    label=""
                                    :placeholder="$t('Enter Question Title')"
                                    type="text"
                                />
                            </li>
                        </ul>
                    </div>
                </div>
            </template>
        </draggable>
        <div class="step-no-ques" v-if="!steps.length">
            <p class="">
                {{ $t("No Questions/Chapters") }}
            </p>
        </div>

        <div
            class="step-ques-input"
            v-if="$route.name !== 'SurveysShow'"
        >
            <div class="form-group mb-1">
                <text-input
                    v-model="title"
                    label=""
                    :placeholder="$t('Question')"
                    type="text"
                />
            </div>
            <h3 class="card-title">{{$t("Enter Title")}}</h3>
            <div class="grid grid-cols-2 gap-2 mt-2 mb-2">
                <div class="checkbox-group">
                    <input
                        class="checkbox-input"
                        @input="selectType('question')"
                        name="type"
                        id="question"
                        type="radio"
                        :checked="type === 'question'"
                    />
                    <label class="checkbox-label" for="question">{{
                        $t("Question")
                    }}</label>
                </div>
                <div class="checkbox-group">
                    <input
                        class="checkbox-input"
                        @input="selectType('chapter')"
                        name="type"
                        id="chapter"
                        type="radio"
                        :checked="type === 'chapter'"
                    />
                    <label class="checkbox-label" for="chapter">
                        {{ $t("Chapter") }}
                    </label>
                </div>
            </div>
            <div class="grid grid-cols-1 mt-3">
                <button
                    type="button"
                    class="secondary-btn"
                    @click="
                        type === 'question' ? createQuestion() : createChapter()
                    "
                >
                    {{ $t("Create") }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import draggable from "vuedraggable";
import TextInput from "@/Components/TextInput.vue";
import { v4 as uuidv4 } from "uuid";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters("surveys", {
            surveyId: "id",
        }),
    },
    props: [
        "minimizeStepsParent",
        "stepsParent",
        "selectedQuestionParent",
        "selectedChapterParent",
        "questionsParent",
        "stylesConfigurationParent",
        "validatorParent",
        "name",
    ],
    emits: [
        "stepsParent",
        "selectedQuestionParent",
        "selectedChapterParent",
        "questionsParent",
        "stylesConfigurationParent",
        "validatorParent",
    ],
    mounted() {
        document.body.addEventListener("click", (e) => {
            if (
                this.toEdit &&
                !e.target.classList.contains("edit-input") &&
                !e.target.classList.contains("fa-pencil") &&
                !(e.target.nodeName == "path" || e.target.nodeName == "svg")
            ) {
                this.toEdit = "";
                this.nameTemp = "";
            }
        });
        this.steps = this.stepsParent;
        this.selectedQuestion = this.selectedQuestionParent;
        this.selectedChapter = this.selectedChapterParent;
        this.questions = this.questionsParent;
        this.stylesConfiguration = this.stylesConfigurationParent;
        this.validator = this.validatorParent;
    },
    data() {
        return {
            toEdit: "",
            nameTemp: "",
            steps: [],
            selectedQuestion: null,
            selectedChapter: null,
            questions: [],
            stylesConfiguration: {},
            validator: {},
            title: "",
            type: "question",
        };
    },
    components: {
        draggable,
        TextInput,
    },
    watch: {
        stylesConfigurationParent(val) {
            this.stylesConfiguration = val;
        },
        selectedQuestionParent(val) {
            this.selectedQuestion = val;
            this.scrollQuestionIntoView(val?.id);
        },
        questionsParent(val) {
            this.questions = val;
        },
        stepsParent(val) {
            this.steps = val;
        },
    },
    methods: {
        scrollQuestionIntoView(id) {
            this.$nextTick(() => {
                document
                    .querySelector(".steps-container")
                    ?.querySelector(`#step-${id}`)
                    ?.scrollIntoView({
                        behavior: "smooth",
                        block: "end",
                        inline: "nearest",
                    });
            });
        },
        async updateQuestion(index, questionIndex) {
            if (this.nameTemp.length) {
                let question = {};
                if (questionIndex !== undefined) {
                    this.steps[index].value.questions[
                        questionIndex
                    ].value.title = this.nameTemp;
                    question = this.steps[index].value.questions[questionIndex];
                } else {
                    this.steps[index].value.title = this.nameTemp;
                    question = this.steps[index].value;
                }
                if (question.value) {
                    const questionIndex = this.questions.findIndex(
                        (q) => q.id == question?.value?.id
                    );
                    this.questions[questionIndex] = question.value;
                } else {
                    const questionIndex = this.questions.findIndex(
                        (q) => q.id == question?.id
                    );
                    this.questions[questionIndex] = question;
                }
                this.$emit("questionsParent", this.questions);
                this.toEdit = "";
                this.nameTemp = "";
                try {
                    await this.$store.dispatch(
                        `${
                            questionIndex !== undefined
                                ? "questions"
                                : this.steps[index].type === "chapter"
                                ? "chapters"
                                : "questions"
                        }/update`,
                        {
                            id:
                                questionIndex !== undefined
                                    ? this.steps[index].value.questions[
                                          questionIndex
                                      ]?.value?.id
                                    : this.steps[index].value?.id,
                            data: {
                                ...(questionIndex !== undefined
                                    ? this.steps[index].value.questions[
                                          questionIndex
                                      ]?.value
                                    : this.steps[index].value),
                                surveyId: this.surveyId,
                            },
                        }
                    );
                } catch (e) {
                    console.log(e);
                }
            } else {
                this.toEdit = "";
                this.nameTemp = "";
            }
        },
        editQuestion(index, questionIndex) {
            if (questionIndex !== undefined) {
                this.toEdit =
                    this.steps[index].value.questions[questionIndex].value.id;
                this.nameTemp =
                    this.steps[index].value.questions[
                        questionIndex
                    ].value.title;
            } else {
                this.toEdit = this.steps[index].id;
                this.nameTemp = this.steps[index].value.title;
            }
        },
        async onQuestionChange(event) {
            if (event.added) {
                try {
                    await this.$store.dispatch("questions/move", {
                        questionId: event.added.element.value.id,
                        chapterId: null,
                    });
                } catch (e) {}
            } else if (event.moved) {
                try {
                    if (event.moved.newIndex == 0) {
                        await this.$store.dispatch(
                            `${
                                event.moved.element.type === "question"
                                    ? "questions"
                                    : "chapters"
                            }/changeOrder`,
                            {
                                id: event.moved.element.value.id,
                                lowerOrder:
                                    this.steps[event.moved.newIndex + 1]?.value
                                        ?.order ??
                                    this.steps[event.moved.newIndex + 1]?.value
                                        ?.chapterOrder,
                            }
                        );
                    } else if (this.steps[event.moved.newIndex + 1]) {
                        await this.$store.dispatch(
                            `${
                                event.moved.element.type === "question"
                                    ? "questions"
                                    : "chapters"
                            }/changeOrder`,
                            {
                                id: event.moved.element.value.id,
                                lowerOrder:
                                    this.steps[event.moved.newIndex - 1]?.value
                                        ?.order ??
                                    this.steps[event.moved.newIndex - 1]?.value
                                        ?.chapterOrder,
                                upperOrder:
                                    this.steps[event.moved.newIndex + 1]?.value
                                        ?.order ??
                                    this.steps[event.moved.newIndex + 1]?.value
                                        ?.chapterOrder,
                            }
                        );
                    } else {
                        await this.$store.dispatch(
                            `${
                                event.moved.element.type === "question"
                                    ? "questions"
                                    : "chapters"
                            }/changeOrder`,
                            {
                                id: event.moved.element.value.id,
                                upperOrder:
                                    this.steps[event.moved.newIndex - 1]?.value
                                        ?.order ??
                                    this.steps[event.moved.newIndex - 1]?.value
                                        ?.chapterOrder,
                            }
                        );
                    }
                    if (this.$route.name === "SurveysEdit")
                        this.$emitter.emit("refresh", true);
                } catch (e) {
                    console.log(e);
                }
            }
        },
        async onChapterQuestionChange(event, step, index) {
            if (event.added) {
                try {
                    await this.$store.dispatch("questions/move", {
                        questionId: event.added.element.value.id,
                        chapterId: step.value.id,
                    });
                } catch (e) {}
            } else if (event.moved) {
                try {
                    if (event.moved.newIndex == 0) {
                        await this.$store.dispatch("questions/changeOrder", {
                            id: event.moved.element.value.id,
                            lowerOrder:
                                this.steps[index]?.value?.questions[
                                    event.moved.newIndex + 1
                                ]?.value?.order ??
                                this.steps[index]?.value?.questions[
                                    event.moved.newIndex + 1
                                ]?.value?.chapterOrder,
                        });
                    } else if (this.steps[event.moved.newIndex + 1]) {
                        await this.$store.dispatch("questions/changeOrder", {
                            id: event.moved.element.value.id,
                            lowerOrder:
                                this.steps[index]?.value?.questions[
                                    event.moved.newIndex - 1
                                ]?.value?.order ??
                                this.steps[index]?.value?.questions[
                                    event.moved.newIndex - 1
                                ]?.value?.chapterOrder,
                            upperOrder:
                                this.steps[index]?.value?.questions[
                                    event.moved.newIndex + 1
                                ]?.value?.order ??
                                this.steps[event.moved.newIndex + 1]?.value
                                    ?.chapterOrder,
                        });
                    } else {
                        await this.$store.dispatch("questions/changeOrder", {
                            id: event.moved.element.value.id,
                            upperOrder:
                                this.steps[index]?.value?.questions[
                                    event.moved.newIndex - 1
                                ]?.value?.order ??
                                this.steps[index]?.value?.questions[
                                    event.moved.newIndex - 1
                                ]?.value?.chapterOrder,
                        });
                    }
                    if (this.$route.name === "SurveysEdit")
                        this.$emitter.emit("refresh", true);
                } catch (e) {
                    console.log(e);
                }
            }
        },
        cloneChapterQuestion(params) {
            return params;
        },
        cloneQuestion(params) {
            return params;
        },
        async removeQuestion(index, questionIndex) {
            if (typeof questionIndex === "number") {
                const chapter = this.steps[index];
                const deletedItem = chapter.value.questions.splice(
                    questionIndex,
                    1
                )?.[0];
                try {
                    await this.$store.dispatch(
                        "questions/destroy",
                        deletedItem?.value?.id ?? deletedItem?.id
                    );
                } catch (e) {}
                this.filterQuestionsAfterDelete(deletedItem.id);
            } else {
                const deletedItem = this.steps.splice(index, 1)?.[0];
                try {
                    await this.$store.dispatch(
                        `${
                            deletedItem.type === "chapter"
                                ? "chapters"
                                : "questions"
                        }/destroy`,
                        deletedItem?.value?.id
                    );
                } catch (e) {}
                this.$emit("stepsParent", this.steps);
                if (deletedItem.type === "question") {
                    this.filterQuestionsAfterDelete(deletedItem.value.id);
                } else {
                    const questions = deletedItem.value.questions;
                    questions.forEach((question) => {
                        this.filterQuestionsAfterDelete(question.id);
                    });
                }
            }
        },
        filterQuestionsAfterDelete(deletedQuestionId) {
            this.questions = this.questions.filter(
                (question) => question.id != deletedQuestionId
            );
            this.$emit("questionsParent", this.questions);
            if (this.selectedQuestion?.id == deletedQuestionId) {
                this.selectedQuestion = null;
                this.$emit("selectedQuestionParent", this.selectedQuestion);
            }
        },
        async addQuestionToChapter(chapter, e) {
            const question = {
                id: uuidv4(),
                title: e.target.value,
                text: "",
                description: "",
                chapter: null,
                chapterId: chapter.id,
                surveyId: this.$route.params.id ?? this.surveyId,
                productDetails: "",
                configuration: {
                    type: "single-select",
                    conditions: [],
                    groups: [],
                },
                upperOrder:
                    chapter?.questions?.[chapter?.questions?.length - 1]?.value
                        .order,
                next: "",
                back: "",
            };
            try {
                const response = await this.$store.dispatch(
                    "questions/create",
                    {
                        ...question,
                    }
                );
                question.id = response?.data?.id ?? question.id;
                this.$store.commit(
                    "surveys/id",
                    response?.data?.surveyId ?? null
                );
            } catch (e) {}
            this.scrollQuestionIntoView(question?.id);
            this.questions.push(question);
            this.$emit("questionsParent", this.questions);
            chapter.questions.push({
                id: uuidv4(),
                type: "question",
                value: question,
            });
            this.$refs.chapterQuestionInput.value = "";
        },
        selectType(type) {
            this.type = type;
        },
        async createQuestion() {
            if (this.title) {
                const question = {
                    id: uuidv4(),
                    title: this.title,
                    text: "",
                    description: "",
                    chapter: null,
                    chapterId: null,
                    surveyId: this.$route.params.id ?? this.surveyId,
                    productDetails: "",
                    configuration: {
                        type: "single-select",
                        groups: [],
                        conditions: [],
                    },
                    upperOrder:
                        this.steps?.[this.questions.length - 1]?.value?.order ??
                        this.steps?.[this.questions.length - 1]?.value
                            ?.chapterOrder,
                    next: "",
                    back: "",
                };
                this.validator[question.id] = {
                    text: {
                        required: true,
                        error: false,
                        message: "Text is a required field",
                    },
                    description: {
                        required: true,
                        error: false,
                        message: "Description is a required field",
                    },
                    value: {
                        required: true,
                        error: false,
                        message: "Value is a required field",
                    },
                };
                this.$emit("validatorParent", this.validator);
                if (!this.name && !this.surveyId) {
                    this.$store.commit("errors", {
                        name: this.$t("Name is a required field"),
                    });
                    return;
                }
                try {
                    const response = await this.$store.dispatch(
                        "questions/create",
                        {
                            ...question,
                            name: this.name,
                        }
                    );
                    question.id = response?.data?.id ?? question.id;
                    question.surveyId = response?.data?.surveyId;
                    if (!this.surveyId) {
                        this.$nextTick(async () => {
                            await this.$store.dispatch(
                                "surveys/saveStylesConfigurations",
                                {
                                    ...this.stylesConfiguration,
                                    surveyId: response?.data?.surveyId,
                                }
                            );
                        });
                    }
                    question.order = response?.data?.questionOrder;
                    this.scrollQuestionIntoView(question?.id);
                    this.$store.commit(
                        "surveys/id",
                        response?.data?.surveyId ?? null
                    );
                } catch (e) {}
                this.questions.push(question);
                this.$emit("questionsParent", this.questions);
                this.steps.push({
                    id: uuidv4(),
                    type: "question",
                    value: question,
                });
                this.$emit("stepsParent", this.steps);
                this.title = "";
                this.selectedQuestion = question;
                this.$emit("selectedQuestionParent", this.selectedQuestion);
            }
        },
        async createChapter() {
            if (this.title) {
                const chapter = {
                    id: uuidv4(),
                    title: this.title,
                    questions: [],
                    surveyId: this.surveyId ?? this.$route.params.id,
                    upperOrder:
                        this.steps?.[this.steps.length - 1]?.value?.order ??
                        this.steps?.[this.steps.length - 1]?.value
                            ?.chapterOrder,
                };
                if (!this.name && !this.surveyId) {
                    this.$store.commit("errors", {
                        name: this.$t("Name is a required field"),
                    });
                    return;
                }
                try {
                    const response = await this.$store.dispatch(
                        "chapters/create",
                        {
                            ...chapter,
                            name: this.name,
                        }
                    );
                    chapter.id = response?.data?.id ?? chapter.id;
                    chapter.surveyId = response?.data?.surveyId;
                    if (!this.surveyId) {
                        this.$nextTick(async () => {
                            await this.$store.dispatch(
                                "surveys/saveStylesConfigurations",
                                {
                                    ...this.stylesConfiguration,
                                    surveyId: response?.data?.surveyId,
                                }
                            );
                        });
                    }
                    chapter.chapterOrder = response?.data?.chapterOrder;
                    this.$store.commit(
                        "surveys/id",
                        response?.data?.surveyId ?? null
                    );
                } catch (e) {}
                this.steps.push({
                    id: uuidv4(),
                    type: "chapter",
                    value: chapter,
                });
                this.$emit("stepsParent", this.steps);
                this.title = "";
                this.selectedChapter = chapter;
                this.$emit("selectedChapterParent", this.selectedChapter);
            }
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

.question:hover > .pencil {
    display: block;
}

.chapter:hover > .pencil {
    display: block;
}

ul {
    margin-left: 20px;
}

// .chapter li {
//     list-style-type: none;
//     margin: 10px 0 10px 10px;
//     position: relative;
// }

// .chapter .question:before {
//     content: "";
//     position: absolute;
//     top: -13px;
//     left: -20px;
//     border-left: 1px solid #ddd;
//     border-bottom: 1px solid #ddd;
//     width: 20px;
//     height: 27px;
// }

// .chapter .question-title-input:before {
//     content: "";
//     position: absolute;
//     top: -20px;
//     left: -20px;
//     border-left: 1px solid #ddd;
//     border-bottom: 1px solid #ddd;
//     width: 20px;
//     height: 40px;
// }

// .chapter li:after {
//     position: absolute;
//     content: "";
//     top: 5px;
//     left: -20px;
//     border-left: 1px solid #ddd;
//     width: 20px;
//     height: 100%;
// }
// .chapter li:last-child:after {
//     display: none;
// }
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

.pencil {
    color: black !important;
    position: absolute;
    right: 30px;
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

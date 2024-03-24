<template>
    <div :class="$attrs.class">
        <label v-if="label" class="form-label input-label" :for="id"
            ><span v-if="required" style="color: red">*</span>&nbsp;{{
                label
            }}:</label
        >
        <input
            :id="id"
            ref="input"
            v-bind="{ ...$attrs, class: null }"
            :readonly="isReadonly"
            class="form-input form-control" 
            :class="{ error: error }"
            :type="type"
            @input="filterMilestones"
            :value="displayValue"
            @click="isDisplay = !isDisplay"
        />
        <div v-if="error" class="form-error">{{ $t(error) ?? "" }}</div>
        <div
            :class="[
                'wrapper-class',
                pStore === 'milestone' ? 'w-200 bg-white relative' : '',
            ]"
        >
            <div
                v-if="isDisplay"
                :class="[
                    'select-box',
                    pStore === 'milestone' ? 'absolute bg-white' : '',
                    selectType === 'product' ? 'h-[500px] w-full overflow-auto' : '',
                ]"
            >
                <transition name="slided">
                    <ul class="select-list">
                        <li
                            v-for="option in duplicateOptions"
                            :key="option.id"
                            data-row="1"
                            @click="selectedValue(option)"
                        >
                            {{ option.name }}
                        </li>
                        <br />
                        <hr />
                        <input
                            @keypress.enter.prevent="store"
                            v-if="pStore !== 'milestone'"
                            v-model="form.name"
                            :id="id"
                            ref="input"
                            v-bind="{ ...$attrs, class: null }"
                            class="form-input"
                            :class="{ error: error }"
                            :type="type"
                            :placeholder="`Enter ${pStore} name`"
                        />
                        <div
                            v-if="pStore == 'milestone'"
                            class="flex -mb-8 -mr-6 p-1"
                        >
                            <text-input
                                v-model="form.name"
                                :error="errors.name"
                                class="pb-8 pr-6 lg:w-1/2"
                                label="Name"
                            />
                            <div class="form-group">
                                <select-input
                                    v-model="form.status"
                                    :error="errors.status"
                                    label="Status"
                                    class="pb-8 pr-6 lg:w-1/2"
                                >
                                    <option value="new">{{ $t("New") }}</option>
                                    <option value="done">{{ $t("Done") }}</option>
                                    <option value="in-work">
                                        {{ $t("In Work") }}
                                    </option>
                                    <option value="in-review">
                                        {{ $t("In Review") }}
                                    </option>
                                    <option value="blocked">
                                        {{ $t("Blocked") }}
                                    </option>
                                </select-input>
                            </div>
                        </div>
                        <div
                            v-if="pStore == 'milestone'"
                            class="flex -mb-8 -mr-6 p-1"
                        >
                            <DateInput
                                v-model="form.plannedStartDate"
                                class="pb-8 pr-6 lg:w-1/2"
                                label="Planned Start Date"
                                :error="errors.plannedStartDate"
                            />
                            <DateInput
                                v-model="form.plannedFinishedDate"
                                class="pb-8 pr-6 lg:w-1/2"
                                label="Planned Finished Date"
                                :error="errors.plannedFinishedDate"
                            />
                        </div>
                        <div
                            v-if="pStore == 'milestone'"
                            class="-mb-8 -mr-6 p-1"
                        >
                            <QuillTextEditor
                                class="pb-8 pr-6"
                                :content="form.description"
                                :error="errors.description"
                                @delta="updateDescription($event)"
                                label="Description"
                            />
                        </div>
                        <br />
                        <div class="max-w-3xl flex">
                            <loading-button
                                :loading="isLoading"
                                style="cursor: pointer"
                                class="secondary-btn"
                                @click="store"
                                >Create {{ pStore }}</loading-button
                            >
                        </div>
                        <br />
                    </ul>
                </transition>
            </div>
        </div>
    </div>
</template>

<script>
import { v4 as uuid } from "uuid";
import LoadingButton from "./LoadingButton.vue";
import QuillTextEditor from "./QuillTextEditor.vue";
import TextInput from "./TextInput.vue";
import DateInput from "./DateInput.vue";
import SelectInput from "./SelectInput.vue";
import { mapGetters } from "vuex";

import axios from "axios";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
    },
    components: {
        DateInput,
        LoadingButton,
        QuillTextEditor,
        TextInput,
        SelectInput,
    },
    inheritAttrs: false,
    props: {
        required: { type: Boolean, required: false },
        id: {
            type: String,
            default() {
                return `text-input-${uuid()}`;
            },
        },
        type: {
            type: String,
            default: "text",
        },
        isReadonly: {
            type: Boolean,
            default: true,
        },
        error: String,
        label: String,
        pStore: {
            type: String,
            default: "group",
        },
        options: Object,
        selectValue: Object,
        modelValue: Object,
        selectType: {
            type: String,
            default: "",
        }
    },
    data() {
        return {
            form: {
                name: "",
                redirectBack: true,
                status: "",
                description: "",
                plannedStartDate: "",
                plannedFinishedDate: "",
            },
            displayValue: "",
            isDisplay: false,
            duplicateOptions: this.options,
        };
    },
    watch: {
        options(newValue) {
            this.duplicateOptions = newValue;
            console.log(this.duplicateOptions);
        },
    },
    emits: ["update:modelValue"],
    methods: {
        updateDescription(event){  
            this.form.description=event;
        },

        filterMilestones($event) {
            let searchTerm = $event.target.value;
            if (searchTerm) {
                this.duplicateOptions = this.options.filter((option) =>
                    option.name.includes(searchTerm)
                );
            } else {
                this.duplicateOptions = this.options;
            }
        },
        focus() {
            this.$refs.input.focus();
        },
        select() {
            this.$refs.input.select();
        },
        setSelectionRange(start, end) {
            this.$refs.input.setSelectionRange(start, end);
        },
        selectedValue(option) {
            this.$emit("update:modelValue", option);
            this.displayValue = option.name;
            this.isDisplay = false;
        },
        async store() {
            try {
                if (this.pStore === "group") {
                    this.$store.commit("isLoading", true);
                    await this.$store.dispatch("groups/create", {
                        ...this.form,
                    });
                    await this.$store.dispatch("groups/list");
                } else if (this.pStore === "milestone") {
                    await this.$store.dispatch("milestones/create", {
                        ...this.form,
                        milestoneId: "something",
                        projectId: this.$route.query.id,
                    });
                    this.$emit("refresh", true);
                    this.form = {
                        name: "",
                        redirectBack: true,
                        status: "",
                        description: "",
                        plannedStartDate: "",
                        plannedFinishedDate: "",
                    };
                } else {
                    this.$store.commit("isLoading", true);
                    await this.$store.dispatch("versions/create", {
                        ...this.form,
                    });
                    await this.$store.dispatch("versions/list");
                }
                this.form.name = "";
            } catch (e) {}
        },
        close(e) {
            if (!this.$el.contains(e.target)) {
                this.isDisplay = false;
            }
        },
    },
    mounted() {
        if (this.modelValue) {
            this.displayValue = this.modelValue?.name;
        }
        if (this.selectValue) {
            this.$emit("update:modelValue", this.selectValue);
            this.displayValue =
                this.selectValue?.[0]?.name ?? this.selectValue.name;
        }
        document.addEventListener("click", this.close);
    },
    beforeDestroy() {
        document.removeEventListener("click", this.close);
    },
};
</script>

<style scoped>
.w-200 {
    min-width: 200%;
}
</style>

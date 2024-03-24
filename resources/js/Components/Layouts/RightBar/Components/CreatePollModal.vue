<template>
    <Modal
        :title="(modalType == 'add' ? 'Create' : 'Edit') + ' Poll'"
        v-if="togglePollModal"
        @toggleModal="cancel"
        :classSize="'modal-sm'"
    >
        <template #body>
            <div class="grid items-center grid-cols-2 gap-6">
                <div class="w-full col-span-2">
                    <div class="form-group">
                        <div>
                            <label class="form-label input-label"
                                ><span class="text-red-500">*&nbsp;</span
                                >{{ $t("Question") }}:</label
                            >
                            <textarea
                                v-model="form.question"
                                rows="5"
                                class="form-control form-textarea" 
                            ></textarea>
                        </div>
                    </div>
                </div>
                <div
                    class="w-full col-span-2"
                    v-for="(option, index) in this.form.options"
                    :key="index"
                >
                    <div class="">
                        <div
                            class="grid items-center grid-cols-1 gap-4"
                            v-if="index < 2"
                        >
                            <div class="form-group">
                                <label
                                    class="input-label"
                                    style="border-radius: 10px"
                                    ><span class="text-red-500">*&nbsp;</span
                                    >{{ `Option ${index + 1}` }}</label
                                >
                                <input
                                    class="form-control"
                                    v-model="this.form.options[index]"
                                />
                            </div>
                        </div>
                        <div class="grid items-center grid-cols-1 gap-4" v-else>
                            <div class="form-group">
                                <label
                                class="input-label"
                                    style="border-radius: 10px"
                                    >{{ "Option " + (index + 1) }}</label
                                >
                                <input
                                class="form-control"
                                    v-model="this.form.options[index]"
                                />
                            </div>
                            <div class="">
                                <span
                                    class="cursor-pointer"
                                    @click="deleteOption(index)"
                                    ><icon name="DeleteIcon"
                                /></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full col-span-2">
                    <div class="form-group">
                        <button @click="handleAddOption" class="secondary-btn">
                            Add Option
                        </button>
                    </div>
                </div>
                <div class="w-full col-span-2">
                    <div class="form-group">
                        <label class="form-label input-label"
                            ><span class="text-red-500">*&nbsp;</span
                            >{{ $t("Due Date") }}:</label
                        >
                        <datepicker class="form-control"
                            :clearable="false"
                            :enable-time-picker="true"
                            auto-apply
                            :close-on-auto-apply="false"
                            :disabled-dates="disabledDates"
                            v-model="form.dueDate"
                            :class="errors.dueDate ? 'error' : ''"
                        />
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            
            <button
                @click="cancel"
                type="button"
                class="primary-btn mr-3"
            >
                {{ $t("Cancel") }}
            </button>
            <loading-button
                @click="createPoll"
                class="secondary-btn"
                :loading="isLoading"
                :disabled="
                    !this.form.question ||
                    this.disableCreateButtonCheck(this.form.options) ||
                    !this.form.dueDate
                "
            >
                {{ modalType == "add" ? "Create Poll" : "Save Changes" }}
            </loading-button>
        </template>
    </Modal>
</template>

<script>
import Modal from "@/Components/EditModal.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TextInput from "@/Components/TextInput.vue";
import TextAreaInput from "@/Components/TextareaInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import Icon from "../../../Icon.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters(["isLoading", "errors"]),
    },
    props: {
        poll: {
            type: Object,
            default: null,
        },
        togglePollModal: {
            type: Boolean,
            default: false,
        },
        moduleId: {
            type: String,
            default: "",
        },
        moduleName: {
            type: String,
            default: "",
        },
        modalType: {
            type: String,
            default: "add",
        },
    },
    watch: {
        togglePollModal(val) {
            if (!val) {
                this.resetForm();
            }
        },
    },
    components: {
        Modal,
        LoadingButton,
        SelectInput,
        TextInput,
        MultiSelectInput,
        TextAreaInput,
        Icon,
    },
    data() {
        const defaultDueDate = new Date();
        defaultDueDate.setHours(defaultDueDate.getHours() + 24);
        return {
            isOptionInputFieldVisible: false,
            answerInput: "",
            form: {
                question: "",
                options: ["", ""],
                dueDate: defaultDueDate,
            },
        };
    },
    methods: {
        disableCreateButtonCheck(options) {
            for (let i = 0; i < options.length; i++) {
                if (options[i]?.length === 0) {
                    return true;
                }
            }
            return false;
        },
        createPoll() {
            this.$emit("save", { form: this.form, id: this.moduleId });

            this.resetForm();
        },
        handleAddOption() {
            this.isOptionInputFieldVisible = true;
            this.form.options.push("");
            // this.answerInput = "";
        },

        deleteOption(index) {
            this.form.options.splice(index, 1);
        },

        resetForm() {
            const defaultDueDate = new Date();
            defaultDueDate.setHours(defaultDueDate.getHours() + 24);
            this.form = {
                question: "",
                options: ["", ""],
                dueDate: defaultDueDate,
            };
            this.answerInput = "";
        },

        cancel() {
            this.resetForm();
            this.$emit("close");
            this.$store.commit("flashMessage", {
                show: false,
                message: "",
                type: "",
                link: "",
            });
            this.$store.commit("errors", {});
        },
        disabledDates(date) {
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            return date < today;
        },
    },
    mounted() {
        if (
            this.poll &&
            (this.poll.question !== null ||
                this.poll.dueDate !== null ||
                this.poll.options.length !== 0) &&
            JSON.stringify(this.poll) !== "{}"
        ) {
            console.log("poll is mounted");
            console.log(this.poll);
            this.form = {
                dueDate: this.poll.dueDate,
                question: this.poll.question,
                options: this.poll.options?.map((option) =>
                    option.text ? option.text : option
                ),
            };
        }
    },
};
</script>

<style scoped>
@import url("//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css");

:deep(.form-input) {
    min-height: 44px;
}

:deep(.vue3-icon-picker) {
    font-size: 2rem;
}

::-webkit-scrollbar {
    width: 2px;
}

.options-contanier {
    display: flex;
    flex-direction: column;
    padding: 5px 0;
    margin-bottom: 5px;
}

button:disabled,
button[disabled] {
    cursor: not-allowed;
    background-color: #cccccc;
    color: #666666;
}
</style>

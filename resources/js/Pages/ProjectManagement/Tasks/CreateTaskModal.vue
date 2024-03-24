<template>
    <Modal
        :title="(modalType == 'add' ? 'Add' : 'Edit') + ' Task'"
        v-if="toggleTasksModal"
        @toggleModal="cancel"
        :classSize="'modal-lg'"
    >
        <template #body>
            <div class="grid items-center grid-cols-2 gap-6">
                <div class="w-full">
                    <div class="form-group">
                        <div class="">
                            <label class="input-label"
                                ><span class="text-red-500">*&nbsp;</span
                                >{{ $t("Icon:") }}</label
                            >
                            <vue3-icon-picker v-model="form.icon" />
                            <p class="form-error">
                                {{ $t(errors.icon ?? "") }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <select-input
                            :required="true"
                            v-model="form.color"
                            :key="form.color"
                            :error="errors.color"
                            :label="$t('Color')"
                            class=""
                        >
                            <option value="#fdefe0">{{ $t("Red") }}</option>
                            <option value="#dff1e2">{{ $t("Green") }}</option>
                            <option value="rgb(0 194 253 / 15%)">
                                {{ $t("Sky") }}
                            </option>
                        </select-input>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            :required="true"
                            v-model="form.headline"
                            :error="errors.headline"
                            :label="$t('Headline')"
                            class=""
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <select-input
                            :required="true"
                            v-model="form.priority"
                            :error="errors.priority"
                            :key="form.priority"
                            :label="$t('Priority')"
                            class=""
                        >
                            <option value="low">{{ $t("Low") }}</option>
                            <option value="normal">{{ $t("Normal") }}</option>
                            <option value="middle">{{ $t("Middle") }}</option>
                            <option value="high">{{ $t("High") }}</option>
                            <option value="critical">
                                {{ $t("Critical") }}
                            </option>
                        </select-input>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <div class="">
                            <label class="input-label"
                                ><span class="text-red-500">*&nbsp;</span
                                >{{ $t("Due Date") }}:</label
                            >
                            <datepicker
                                class="form-control"
                                text-input
                                :format="format"
                                :clearable="false"
                                :enable-time-picker="true"
                                auto-apply
                                :close-on-auto-apply="false"
                                v-model="form.dueDate"
                                :class="errors.dueDate ? 'error' : ''"
                            />
                            <div v-if="errors?.dueDate" class="form-error">
                                {{ errors.dueDate }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full">
                    <div class="form-group">
                        <select-input
                            :required="true"
                            v-model="form.taskType"
                            :key="form.taskType"
                            :error="errors.taskType"
                            :label="$t('Task Type')"
                            class=""
                        >
                            <option value="personal">
                                {{ $t("Personal") }}
                            </option>
                            <option value="group">{{ $t("Group") }}</option>
                        </select-input>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            :showLabels="false"
                            :required="true"
                            v-model="form.assignees"
                            :options="users"
                            :key="form.assignees"
                            :multiple="form.taskType === 'group' ? true : false"
                            :textLabel="
                                form.taskType === 'group'
                                    ? $t('Group')
                                    : $t('Personal')
                            "
                            label="first_name"
                            trackBy="id"
                            class=""
                            :search-param-name="'search_string'"
                            :customLabel="customLabel"
                            :customSearchInOptions="false"
                            :query="{ type: 'employee' }"
                            moduleName="auth"
                        >
                            <template #beforeList>
                                <div
                                    class="grid p-2 gap-2"
                                    style="grid-template-columns: 25% 25% 50%"
                                >
                                    <p class="font-bold">
                                        {{ $t("First Name") }}
                                    </p>
                                    <p class="font-bold">
                                        {{ $t("Last Name") }}
                                    </p>
                                    <p class="font-bold">
                                        {{ $t("Email") }}
                                    </p>
                                </div>
                                <hr />
                            </template>
                            <template #singleLabel="{ props }">
                                <p>
                                    {{ props.option.first_name }}
                                    {{ props.option.last_name }}
                                </p>
                            </template>
                            <template #option="{ props }">
                                <div
                                    class="grid"
                                    style="grid-template-columns: 25% 25% 50%"
                                >
                                    <p class="overflow-text-users-table">
                                        {{ props.option.first_name }}
                                    </p>
                                    <p class="overflow-text-users-table">
                                        {{ props.option.last_name }}
                                    </p>
                                    <p class="overflow-text-users-table">
                                        {{ props.option.email }}
                                    </p>
                                </div>
                            </template>
                        </MultiSelectInput>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <select-input
                            :required="true"
                            v-model="form.timeTrackerType"
                            :error="errors.timeTrackerType"
                            :key="form.timeTrackerType"
                            :label="$t('Time Tracker Type')"
                            class=""
                        >
                            <option value="government">
                                {{ $t("Government") }}
                            </option>
                            <option value="company">{{ $t("Company") }}</option>
                        </select-input>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <select-input
                            :required="true"
                            v-model="form.contentType"
                            :error="errors.contentType"
                            :key="form.contentType"
                            :label="$t('Content Type')"
                            class=""
                        >
                            <option value="text">{{ $t("Text") }}</option>
                            <option value="checkboxes">
                                {{ $t("Checkboxes") }}
                            </option>
                        </select-input>
                    </div>
                </div>
                <div class="w-full col-span-2">
                    <div class="form-group" v-if="form.contentType === 'text'">
                        <QuillTextEditor
                            class="pb-8 pr-6 w-full lg:w-full"
                            :content-type="'html'"
                            :content="form.text"
                            :required="true"
                            :error="errors.text"
                            @delta="form.text = $event"
                            :saveUploadedImagesToStorage="true"
                            :label="$t('Text')"
                            height="20rem"
                        />
                    </div>
                    <div v-else class="pb-8 pr-6 lg:w-full">
                        <h3 class="card-title text-left">
                            {{ $t("Checkboxes")
                            }}<span class="text-red-500">&nbsp;*</span>
                        </h3>
                        <div
                            v-for="(checkbox, index) in form.checkboxes"
                            :key="'checkbox-' + index"
                            class="flex flex-wrap mt-3"
                        >
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="checkbox.value"
                                    :error="errors.value"
                                    :label="$t('Value')"
                                    class=""
                                />
                            </div>
                            <button
                                class="ml-2"
                                @click="form.checkboxes.splice(index, 1)"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-trash-can"
                                />
                            </button>
                        </div>
                        <button
                            @click="
                                form.checkboxes.push({
                                    value: '',
                                })
                            "
                            class="secondary-btn mt-2"
                        >
                            {{ $t("Add Checkbox") }}
                        </button>
                    </div>
                </div>
                <div class="w-full col-span-2">
                    <div class="form-group">
                        <Comments
                            :showQuillParent="showQuill"
                            :taskId="task?.id"
                            v-if="modalType === 'edit' && task?.id"
                        />
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <button @click="cancel" type="button" class="primary-btn mr-3">
                {{ $t("Cancel") }}
            </button>
            <loading-button
                @click="createOrUpdate"
                :loading="isLoading"
                class="secondary-btn"
            >
                {{ modalType == "add" ? "Add" : "Edit" }}
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
import IconPicker from "@/Components/IconPicker.vue";
import Comments from "./Comments.vue";
import Vue3IconPicker from "vue3-universal-icon-picker";
import { mapGetters } from "vuex";
import QuillTextEditor from "@/Components/QuillTextEditor.vue";

export default {
    emits: ["toggleTasksModal", "createOrUpdateTask", "refresh"],
    computed: {
        ...mapGetters(["isLoading", "errors"]),
        ...mapGetters("auth", ["users", "user", "userProfilePictures"]),
        ...mapGetters("taskStatuses", ["statuses"]),
        ...mapGetters("taskBoards", ["selectedBoard"]),
        format() {
            if (this.$store.state.language === "de") {
                return "dd.MM.yyyy, HH:mm";
            } else {
                return "MM/dd/yyyy, HH:mm";
            }
        },
    },
    props: {
        task: {
            type: Object,
            default: null,
        },
        toggleTasksModal: {
            type: Boolean,
            default: false,
        },
        modalType: {
            type: String,
            default: "add",
        },
        status: {
            type: String,
        },
        upperOrder: {
            type: Number,
            default: null,
        },
        scrollToComment: {
            type: Boolean,
            default: false,
        },
    },
    watch: {
        toggleTasksModal(val) {
            if (!val) {
                this.resetForm();
            }
        },
        task(val) {
            if (typeof val === "object") {
                this.form = { ...val };
            }
        },
        "form.contentType"(val) {
            if (val === "text") {
                this.form.checkboxes.splice(0, this.form.checkboxes.length);
                return;
            }
            this.form.text = "";
        },
        "form.taskType"(val) {
            if (val === "personal" && this.modalType === "add") {
                this.form.assignees = this?.user;
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
        IconPicker,
        Vue3IconPicker,
        QuillTextEditor,
        Comments,
    },
    data() {
        return {
            showQuill: false,
            files: {
                files: [],
            },
            form: {
                icon: "",
                color: "",
                headline: "",
                priority: "",
                dueDate: new Date(),
                taskType: "personal",
                contentType: "text",
                text: "",
                assignees: "",
                checkboxes: [],
                status: this.status ?? this.statuses?.[0],
                timeTrackerType: "",
            },
            search: "",
        };
    },
    mounted() {
        if (this.scrollToComment) {
            this.showQuill = true;
            this.$nextTick(() => {
                document
                    .querySelector("#task-comments-" + this.task?.id)
                    .scrollIntoView({
                        behavior: "smooth",
                        block: "nearest",
                        inline: "nearest",
                    });
            });
        }
        this.resetForm();
        this.form.icon = this.selectedBoard?.standardIcon ?? "";
        this.form.color = this.selectedBoard?.standardColor ?? "";
        this.form.priority = this.selectedBoard?.standardPriority ?? "";
        this.form.timeTrackerType =
            this.selectedBoard?.standardTimeTracker ?? "";
        if (typeof this.task === "object" && !!this.task) {
            this.form = { ...this.task };
        }
        if (this.form.taskType === "personal" && this.modalType === "add") {
            this.form.assignees = this?.user;
        }
    },
    methods: {
        async createOrUpdate() {
            this.$store.commit("isLoading", true);
            try {
                let content = [];
                if (this.form.contentType === "text") {
                    content = [
                        {
                            type: this.form.contentType,
                            value: this.form.text,
                            isChecked: false,
                        },
                    ];
                } else {
                    content = this.form.checkboxes.map((checkbox) => {
                        return {
                            type: this.form.contentType,
                            value: checkbox.value,
                            isChecked: checkbox.isChecked ?? false,
                        };
                    });
                }
                const payload = {
                    ...this.form,
                    dueDate: this.form.dueDate.toLocaleString(),
                    // dueDate: this.formattedDate(this.form.dueDate),
                    content: content,
                    assignees:
                        this.form.taskType === "personal"
                            ? // ? [{ userId: this.user.id }]
                              [{ userId: this.form.assignees.id }]
                            : this.form.assignees instanceof Array
                            ? this.form.assignees.map((assignee) => ({
                                  userId: assignee.id,
                              }))
                            : [],
                    boardAssignees: (
                        this.selectedBoard?.shareableUsers ?? []
                    ).map((userId) => ({ userId: userId })),
                    userId:
                        this.form.taskType === "personal"
                            ? this.form.assignees.id
                            : this.user.id,
                    statusId: this.status?.id ?? this.statuses?.[0]?.id,
                    upperOrder: this.upperOrder,
                };
                await this.$store.dispatch(
                    `myTasks/${this.modalType === "add" ? "create" : "update"}`,
                    this.modalType === "add"
                        ? payload
                        : {
                              id: this.task.id,
                              data: payload,
                          }
                );
                this.$emit("refresh", true);
                this.$emit("toggleTasksModal", false);
                this.resetForm();
            } catch (e) {
                console.error(e);
            }
        },
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
        /**
         * resets the form to default
         */
        resetForm() {
            this.form = {
                icon: "",
                color: "",
                headline: "",
                priority: "",
                dueDate: new Date(),
                taskType: "personal",
                contentType: "text",
                text: "",
                assignees: "",
                checkboxes: [],
                status: this.status ?? this.statuses?.[0],
                timeTrackerType: "",
            };
        },
        /**
         * resets the form when the modal is toggled off
         * resets the errors
         * resets any flash messages
         */
        cancel() {
            this.resetForm();
            this.$emit("toggleTasksModal", false);
            this.$store.commit("flashMessage", {
                show: false,
                message: "",
                type: "",
                link: "",
            });
            this.$store.commit("errors", {});
        },
        formattedDate(param) {
            const date = new Date(param);
            const day = date.getDate().toString().padStart(2, "0");
            const month = (date.getMonth() + 1).toString().padStart(2, "0"); // Month starts from 0
            const year = date.getFullYear();
            let hour = date.getHours();
            const minute = date.getMinutes().toString().padStart(2, "0");
            const second = date.getSeconds().toString().padStart(2, "0");
            const meridiem = hour >= 12 ? "PM" : "AM";
            hour = hour % 12 || 12;

            const formatted = `${month}/${day}/${year}, ${hour}:${minute}:${second} ${meridiem}`;
            return formatted;
        },
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

:deep(.ql-toolbar) {
    display: block !important;
}
:deep(.ql-container) {
    border: 1px solid #747a80 !important;
}
</style>

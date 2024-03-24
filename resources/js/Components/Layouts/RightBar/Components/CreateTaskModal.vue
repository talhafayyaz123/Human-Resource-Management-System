<template>
    <Modal
        :title="(modalType == 'add' ? 'Add' : 'Edit') + ' Task'"
        v-if="toggleTasksModal"
        @toggleModal="cancel"
        :classSize="'modal-md'"
    >
        <template #body>
            <div class="grid items-center grid-cols-2 gap-6">
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
                        <label class="input-label"
                            ><span class="text-red-500">*&nbsp;</span
                            >{{ $t("Due Date") }}:</label
                        >
                        <datepicker
                            class="form-control"
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
                <div class="w-full">
                    <div class="form-group">
                        <TextAreaInput
                            required="true"
                            v-if="form.contentType === 'text'"
                            v-model="form.text"
                            :error="errors.text"
                            :label="$t('Text')"
                            class=""
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
import Vue3IconPicker from "vue3-universal-icon-picker";
import { mapGetters } from "vuex";

export default {
    emits: ["toggleTasksModal", "createOrUpdateTask", "refresh"],
    computed: {
        ...mapGetters(["isLoading", "errors"]),
        ...mapGetters("auth", ["users", "user", "userProfilePictures"]),
        ...mapGetters("taskStatuses", ["statuses"]),
        ...mapGetters("taskBoards", ["selectedBoard"]),
        // get entire user objects from 'userProfilePictures' state filtered by board shareable users
        shareableUsers() {
            return (this.selectedBoard?.shareableUsers ?? [])
                .map((userId) => this.userProfilePictures[userId])
                .filter((user) => !!user);
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
        status: {
            type: String,
        },
        upperOrder: {
            type: Number,
            default: null,
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
    },
    data() {
        return {
            form: {
                icon: "far fa-newspaper",
                color: "#dff1e2",
                headline: "",
                priority: "normal",
                dueDate: new Date(),
                taskType: "personal",
                contentType: "text",
                text: "",
                assignees: "",
                checkboxes: [],
                status: this.status ?? this.statuses?.[0],
                timeTrackerType: "",
                taskImages: [],
            },
        };
    },
    mounted() {
        this.resetForm();
        if (typeof this.task === "object" && !!this.task) {
            this.form = { ...this.task };
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
                    content: content,
                    assignees:
                        this.form.taskType === "personal"
                            ? [{ userId: this.user.id }]
                            : this.form.assignees instanceof Array
                            ? this.form.assignees.map((assignee) => ({
                                  userId: assignee.id,
                              }))
                            : [],
                    userId: this.user.id,
                    statusId: 1,
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
                await this.$store.dispatch(
                    `myTasks/${this.modalType === "add" ? "create" : "update"}`,
                    this.modalType === "add"
                        ? payload
                        : {
                              id: this.task.id,
                              data: payload,
                          }
                );
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("assets/createFeed", {
                    moduleId: this.moduleId,
                    modelName: this.moduleName,
                    text: "+" + this.form.headline,
                    isVote: 0,
                });
                this.$emit("refresh");
                this.$emit("toggleTasksModal", false);
                this.resetForm();
            } catch (e) {}
        },
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
        /**
         * resets the form to default
         */
        resetForm() {
            this.form = {
                icon: "far fa-newspaper",
                color: "#dff1e2",
                headline: "",
                priority: "normal",
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
</style>

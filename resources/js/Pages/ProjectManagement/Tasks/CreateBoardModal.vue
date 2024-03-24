<template>
    <Modal
        :title="(modalType == 'add' ? 'Add' : 'Edit') + ' Board'"
        v-if="toggleBoardModal"
        @toggleModal="cancel"
        :classSize="'modal-lg'"
    >
        <template #body>
            <div class="grid items-center grid-cols-2 gap-6 mb-3">
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            :required="true"
                            v-model="form.name"
                            :error="errors.name"
                            :label="$t('Name')"
                            class=""
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <select-input
                            v-if="board?.isDefault == 0 || modalType === 'add'"
                            :required="true"
                            v-model="form.color"
                            :key="form.color"
                            :error="errors.color"
                            :label="$t('Color')"
                            class=""
                        >
                            <option value="#fdefe0">{{ $t("Red") }}</option>
                            <option value="#dff1e2">{{ $t("Green") }}</option>
                        </select-input>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            v-if="board?.isDefault == 0 || modalType === 'add'"
                            :showLabels="false"
                            v-model="form.users"
                            :options="users"
                            :key="form.users"
                            :multiple="true"
                            :textLabel="$t('Share With')"
                            label="first_name"
                            trackBy="id"
                            class=""
                            moduleName="auth"
                            :search-param-name="'search_string'"
                            :customLabel="customLabel"
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
                        <template v-if="modalType === 'add'">
                            <div class="checkbox-group">
                                <input
                                    v-model="form.useDefaultStatuses"
                                    :checked="form.useDefaultStatuses"
                                    class="checkbox-input"
                                    type="checkbox"
                                    id="use-default-statuses"
                                />
                                <label
                                    class="checkbox-label"
                                    for="use-default-statuses"
                                    >{{ $t("Use Default Statuses") }}</label
                                >
                            </div>
                            <div v-if="!form.useDefaultStatuses" class="w-full">
                                <p class="font-bold py-2">
                                    {{ $t("Custom Statuses") }}:
                                </p>
                                <CustomStatuses
                                    @statuses="boardStatuses = $event"
                                    ref="custom-statuses"
                                />
                            </div>
                        </template>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <NumberInput
                            :showPrefix="false"
                            v-model="form.escalationHours"
                            :key="form.escalationHours"
                            :label="$t('Escalation Hours')"
                            class=""
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            :showLabels="false"
                            v-model="form.escalationManager"
                            :options="users"
                            :key="form.escalationManager"
                            :multiple="false"
                            :textLabel="$t('Escalation Manager')"
                            label="first_name"
                            trackBy="id"
                            class=""
                            moduleName="auth"
                            :search-param-name="'search_string'"
                            :customLabel="customLabel"
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
            </div>
            <h3 class="card-title text-left">
                {{ $t("Notification Settings") }}
            </h3>
            <div class="grid items-center grid-cols-3 gap-6 mb-3 pt-3">
                <div class="w-full">
                    <div class="checkbox-group">
                        <input
                            v-model="form.uponTaskCreation"
                            :checked="form.uponTaskCreation"
                            class="checkbox-input"
                            type="checkbox"
                            id="uponTaskCreation"
                        />
                        <label class="checkbox-label" for="uponTaskCreation">{{
                            $t("Upon task creation")
                        }}</label>
                    </div>
                </div>
                <div class="w-full">
                    <div class="checkbox-group">
                        <input
                            v-model="form.whenTaskIsAssigned"
                            :checked="form.whenTaskIsAssigned"
                            class="checkbox-input"
                            type="checkbox"
                            id="whenTaskIsAssigned"
                        />
                        <label
                            class="checkbox-label"
                            for="whenTaskIsAssigned"
                            >{{ $t("When task is assigned") }}</label
                        >
                    </div>
                </div>
                <div class="w-full">
                    <div class="checkbox-group">
                        <input
                            v-model="form.inEscalationMode"
                            :checked="form.inEscalationMode"
                            class="checkbox-input"
                            type="checkbox"
                            id="inEscalationMode"
                        />
                        <label class="checkbox-label" for="inEscalationMode">{{
                            $t("In escalation Mode")
                        }}</label>
                    </div>
                </div>
            </div>
            <h3 class="card-title text-left">{{ $t("Task Flow") }}</h3>
            <div class="grid items-center grid-cols-1 gap-6 mb-3">
                <div class="w-full">
                    <TaskFlow
                        ref="task-flow"
                        :modalType="modalType"
                        :boardStatuses="boardStatuses"
                        :key="board.id"
                        :board-id="board?.id"
                    />
                </div>
            </div>
            <h3 class="card-title text-left">
                {{ $t("Standard Task Defination") }}:
            </h3>
            <StandardTaskDefination
                :board="form"
                ref="standard-task-defination"
                class="mt-2"
            />
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
import NumberInput from "@/Components/NumberInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import TaskFlow from "./TaskFlow.vue";
import StandardTaskDefination from "./StandardTaskDefination.vue";
import CustomStatuses from "./CustomStatuses.vue";
import { v4 as uuid } from "uuid";
import { mapGetters } from "vuex";

const defaultStatuses = [
    { id: uuid(), name: "To do", color: "black", isDoneStatus: false },
    { id: uuid(), name: "In progress", color: "blue", isDoneStatus: false },
    { id: uuid(), name: "Done", color: "green", isDoneStatus: true },
    { id: uuid(), name: "Blocked", color: "red", isDoneStatus: false },
];

export default {
    emits: ["toggleBoardModal", "createOrUpdateBoard", "refresh"],
    computed: {
        ...mapGetters(["isLoading", "errors"]),
        ...mapGetters("auth", ["users", "user"]),
        ...mapGetters("taskStatuses", ["statuses"]),
    },
    props: {
        board: {
            type: Object,
            default: () => ({
                color: "",
                name: "",
                users: [],
                useDefaultStatuses: true,
                escalationHours: 24,
                escalationManager: "",
                uponTaskCreation: false,
                whenTaskIsAssigned: false,
                inEscalationMode: false,
                statuses: [],
            }),
        },
        toggleBoardModal: {
            type: Boolean,
            default: false,
        },
        modalType: {
            type: String,
            default: "add",
        },
    },
    watch: {
        "form.useDefaultStatuses"(val) {
            if (!!val) {
                this.boardStatuses = defaultStatuses;
                this.form.statuses.splice(0, this.form.statuses.length);
                return;
            }
            this.boardStatuses = [];
        },
        toggleBoardModal(val) {
            if (!val) {
                this.resetForm();
            }
        },
        board(val) {
            if (typeof val === "object") {
                this.form = { ...val };
            }
        },
    },
    components: {
        Modal,
        LoadingButton,
        SelectInput,
        TextInput,
        MultiSelectInput,
        NumberInput,
        TaskFlow,
        CustomStatuses,
        StandardTaskDefination,
    },
    data() {
        return {
            boardStatuses: defaultStatuses,
            form: {
                color: "",
                name: "",
                users: [],
                useDefaultStatuses: true,
                escalationHours: 24,
                escalationManager: "",
                uponTaskCreation: false,
                whenTaskIsAssigned: false,
                inEscalationMode: false,
                statuses: [],
                standardIcon: "",
                standardColor: "",
                standardPriority: "",
                standardTimeTracker: "",
            },
        };
    },
    mounted() {
        if (typeof this.board === "object") {
            this.form = { ...this.board };
        }
    },
    methods: {
        async createOrUpdate() {
            // perform form validation for custom statuses
            if (!this.form.useDefaultStatuses) {
                let customStatuses =
                    this.$refs?.["custom-statuses"]?.statuses ?? [];
                for (let i = 0; i < customStatuses.length; i++) {
                    if (!customStatuses[i].name || !customStatuses[i].color) {
                        let errors = {};
                        if (!customStatuses[i].name)
                            errors[`customStatuses[${i}].name`] =
                                "Name is required";
                        if (!customStatuses[i].color)
                            errors[`customStatuses[${i}].color`] =
                                "Color is required";
                        this.$store.commit("errors", errors);
                        return;
                    }
                }
            }
            this.$store.commit("isLoading", true);
            this.$store.commit("errors", {});
            try {
                let statusTransitions = [];
                let statusLinks =
                    this.$refs?.["task-flow"]?.linksByStatus ?? {};
                for (let key in statusLinks) {
                    statusTransitions = [
                        ...statusTransitions,
                        ...statusLinks[key].map((link) => {
                            return {
                                fromStatusId: key,
                                toStatusId: link.id,
                            };
                        }),
                    ];
                }
                const payload = {
                    name: this.form.name,
                    userId: this.user.id,
                    menuColor: this.form.color,
                    headColor: this.form.color,
                    shareableUsers: Array.from(
                        Array.from(
                            new Set([
                                ...this.form.users.map((user) => user.id),
                                this.user.id,
                            ])
                        ).map((userId) => {
                            return {
                                userId: userId,
                            };
                        })
                    ),
                    statuses: this.boardStatuses,
                    useDefaultStatuses: this.form.useDefaultStatuses,
                    escalationHours: this.form.escalationHours,
                    escalationManager: this.form.escalationManager?.id,
                    isEscalationModeNotify: this.form.inEscalationMode ? 1 : 0,
                    isTaskCreationNotify: this.form.uponTaskCreation ? 1 : 0,
                    isTaskAssignedNotify: this.form.whenTaskIsAssigned ? 1 : 0,
                    standardIcon:
                        this.$refs?.["standard-task-defination"]?.form?.icon ??
                        "",
                    standardColor:
                        this.$refs?.["standard-task-defination"]?.form?.color ??
                        "",
                    standardPriority:
                        this.$refs?.["standard-task-defination"]?.form
                            ?.priority ?? "",
                    standardTimeTracker:
                        this.$refs?.["standard-task-defination"]?.form
                            ?.timeTrackerType ?? "",
                    statusTransitions: statusTransitions,
                };
                await this.$store.dispatch(
                    `taskBoards/${
                        this.modalType === "add" ? "create" : "update"
                    }`,
                    this.modalType === "add"
                        ? payload
                        : {
                              id: this.board.id,
                              data: payload,
                          }
                );
                this.$emit("refresh", true);
                this.$emit("toggleBoardModal", false);
            } catch (e) {
                console.log(e);
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
                color: "",
                name: "",
                users: [],
                useDefaultStatuses: true,
                escalationHours: 24,
                escalationManager: "",
                uponTaskCreation: false,
                whenTaskIsAssigned: false,
                inEscalationMode: false,
                statuses: [],
            };
        },
        /**
         * resets the form when the modal is toggled off
         * resets the errors
         * resets any flash messages
         */
        cancel() {
            this.resetForm();
            this.$emit("toggleBoardModal", false);
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

<style scoped></style>

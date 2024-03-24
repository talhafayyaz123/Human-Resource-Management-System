<template>
    <Modal
        :title="$t('Assign To Other Board')"
        v-if="toggleAssignBoardModal"
        @toggleModal="cancel"
        :classSize="'modal-md'"
    >
        <template #body>
            <div class="grid items-center grid-cols-2 gap-6">
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            class="pb-8 pr-6 lg:w-1/3"
                            v-model="board"
                            :key="board"
                            :multiple="false"
                            @update:model-value="fetchStatuses"
                            :options="filteredBoards"
                            :textLabel="$t('Board')"
                            label="name"
                            trackBy="id"
                            :error="errors.board"
                            :required="true"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            class="pb-8 pr-6 lg:w-1/3"
                            v-model="status"
                            :key="status"
                            :multiple="false"
                            :options="statuses"
                            :textLabel="$t('Status')"
                            label="name"
                            trackBy="id"
                            :error="errors.status"
                            :required="true"
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
                @click="assignToBoard"
                :loading="isLoading"
                class="secondary-btn"
            >
                {{ $t("Assign") }}
            </loading-button>
            
        </template>
    </Modal>
</template>

<script>
import Modal from "@/Components/EditModal.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";

export default {
    emits: ["toggleAssignBoardModal"],
    computed: {
        ...mapGetters(["isLoading", "errors"]),
        ...mapGetters("taskBoards", ["boards", "selectedBoard"]),
        // filter boards without the currently selected board
        filteredBoards() {
            return this.boards.filter(
                (board) => board.id != this.selectedBoard?.id
            );
        },
    },
    data() {
        return {
            board: "",
            status: "",
            statuses: [],
        };
    },
    props: {
        assignedBoard: {
            type: Number,
            required: true,
        },
        toggleAssignBoardModal: {
            type: Boolean,
            default: false,
        },
        task: {
            type: Object,
            default: () => ({}),
        },
    },
    components: {
        Modal,
        LoadingButton,
        MultiSelectInput,
    },
    methods: {
        // fetch statuses for the selected 'board'
        async fetchStatuses() {
            try {
                await this.$nextTick();
                const response = await this.$store.dispatch(
                    "taskStatuses/list",
                    {
                        taskBoardId: this.board?.id,
                        setStore: false,
                    }
                );
                this.statuses = response?.data?.data ?? [];
            } catch (e) {
                console.log(e);
            }
        },
        // update the task to assign it to another board
        async assignToBoard() {
            try {
                // both board and status must be selected, so perform validations
                if (!this.board || !this.status) {
                    this.$store.commit("errors", {
                        board: !this.board
                            ? this.$t("Kindly select a board to continue")
                            : "",
                        status: !this.status
                            ? this.$t("Kindly select a status to continue")
                            : "",
                    });
                    return;
                }
                // if not all the assignees of the task have access to other board then show a validation message
                if (
                    !this.task.assignees.every((assignee) =>
                        this.board.shareableUsers.includes(assignee.userId)
                    )
                ) {
                    this.$store.commit("flashMessage", {
                        show: true,
                        message: this.$t(
                            "One or more assignees do not have access to moving board"
                        ),
                        type: "error",
                        link: "",
                    });
                    return;
                }
                // reset errors
                this.$store.commit("errors", {});
                // set loader
                this.$store.commit("isLoading", true);
                // update task
                await this.$store.dispatch("myTasks/update", {
                    id: this.task?.id,
                    data: {
                        ...this.task,
                        assignees: this.task.assignees.map((assignee) => ({
                            userId: assignee.userId,
                        })),
                        statusId: this.status?.id,
                    },
                });
                // hide modal
                this.$emit("toggleAssignBoardModal", false);
                // refresh task listing
                this.$emit("refresh", true);
            } catch (e) {}
        },
        cancel() {
            this.$emit("toggleAssignBoardModal", false);
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

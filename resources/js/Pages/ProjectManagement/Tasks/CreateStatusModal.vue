<template>
    <Modal
        :title="$t(`${modalType === 'add' ? 'Add' : 'Edit'} Status`)"
        v-if="toggleCreateStatusModal"
        @toggleModal="cancel"
        :classSize="'modal-md'"
    >
        <template #body>
            <div class="grid items-center grid-cols-2 gap-6">
                <div class="w-full">
                    <div class="form-group">
                        <TextInput
                            :required="true"
                            :label="$t('Name')"
                            v-model="form.name"
                            :error="errors?.name"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <SelectInput
                            :required="true"
                            :label="$t('Color')"
                            v-model="form.color"
                            :key="form.color"
                            :error="errors?.color"
                        >
                            <option value="red">{{ $t("Red") }}</option>
                            <option value="green">{{ $t("Green") }}</option>
                            <option value="blue">{{ $t("Blue") }}</option>
                            <option value="black">{{ $t("Black") }}</option>
                        </SelectInput>
                    </div>
                </div>
                <div class="w-full flex items-center">
                    <input
                        class="mr-1"
                        id="is-done-status"
                        type="checkbox"
                        v-model="form.isDoneStatus"
                    />
                    <label for="is-done-status">{{
                        $t("Is Done Status")
                    }}</label>
                </div>
            </div>
        </template>
        <template #footer>
            <button @click="cancel" type="button" class="primary-btn mr-3">
                {{ $t("Cancel") }}
            </button>
            <loading-button
                @click="save"
                :loading="isLoading"
                class="secondary-btn"
            >
                {{ $t(modalType === "add" ? "Add" : "Edit") }}
            </loading-button>
        </template>
    </Modal>
</template>

<script>
import Modal from "@/Components/EditModal.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { mapGetters } from "vuex";

export default {
    emits: ["toggleCreateStatusModal", "refresh"],
    computed: {
        ...mapGetters(["isLoading", "errors"]),
        ...mapGetters("taskBoards", ["selectedBoard"]),
        ...mapGetters("taskStatuses", ["statuses"]),
    },
    data() {
        return {
            form: {
                name: "",
                color: "",
                isDoneStatus: false,
            },
        };
    },
    props: {
        modalType: {
            type: String,
            default: "add",
        },
        toggleCreateStatusModal: {
            type: Boolean,
            default: false,
        },
        status: {
            type: Object,
            default: null,
        },
    },
    components: {
        Modal,
        LoadingButton,
        TextInput,
        SelectInput,
    },
    mounted() {
        if (typeof this.status === "object") {
            this.form = { ...this.status };
        }
    },
    watch: {
        status(val) {
            if (typeof val === "object") {
                this.form = { ...val };
            }
        },
    },
    methods: {
        async save() {
            this.$store.commit("isLoading", true);
            try {
                const payload = {
                    ...this.form,
                    taskBoardId: this.selectedBoard?.id,
                    upperOrder:
                        this.statuses?.[this.statuses?.length - 1]?.order ??
                        null,
                };
                await this.$store.dispatch(
                    `taskStatuses/${
                        this.modalType === "add" ? "create" : "update"
                    }`,
                    this.modalType === "add"
                        ? payload
                        : {
                              id: this.status.id,
                              data: payload,
                          }
                );
                this.$emit("refresh", true);
                this.$emit("toggleCreateStatusModal", false);
                this.form = {
                    name: "",
                    color: "",
                };
            } catch (e) {}
        },
        cancel() {
            this.$emit("toggleCreateStatusModal", false);
            this.$store.commit("flashMessage", {
                show: false,
                message: "",
                type: "",
                link: "",
            });
            this.$store.commit("errors", {});
            this.form = {
                name: "",
                color: "",
            };
        },
    },
};
</script>

<style scoped></style>

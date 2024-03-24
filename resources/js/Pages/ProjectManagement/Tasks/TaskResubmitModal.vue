<template>
    <Modal
        :title="$t('Resubmit Task')"
        v-if="toggleTaskResubmitModal"
        @toggleModal="cancel"
        :classSize="'modal-sm'"
    >
        <template #body>
            <div class="grid items-center grid-cols-1 gap-6">
                <div class="w-full">
                    <div class="form-group">
                        <label class="input-label"
                            ><span class="text-red-500">*&nbsp;</span
                            >{{ $t("Resubmit Date") }}:</label
                        >
                        <datepicker class="form-control"
                            :clearable="false"
                            :enable-time-picker="true"
                            auto-apply
                            :close-on-auto-apply="false"
                            v-model="resubmitDate"
                            :class="errors.resubmitDate ? 'error' : ''"
                        />
                        <div v-if="errors?.dueDate" class="form-error">
                            {{ errors.resubmitDate }}
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <button @click="cancel" type="button" class="primary-btn mr-3">
                {{ $t("Cancel") }}
            </button>
            <loading-button
                @click="resubmit"
                :loading="isLoading"
                class="secondary-btn"
            >
                {{ $t("Resubmit") }}
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
    emits: ["toggleTaskResubmitModal"],
    computed: {
        ...mapGetters(["isLoading", "errors"]),
    },
    data() {
        return {
            resubmitDate: new Date(),
        };
    },
    props: {
        toggleTaskResubmitModal: {
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
    },
    methods: {
        // update the task to assign it to another board
        async resubmit() {
            try {
                // set loader
                this.$store.commit("isLoading", true);
                // update task
                await this.$store.dispatch("myTasks/resubmit", {
                    taskId: this.task.id,
                    resubmitDate: this.resubmitDate.toLocaleString(),
                });
                // hide modal
                this.$emit("toggleTaskResubmitModal", false);
                // refresh task listing
                this.$emit("refresh", true);
                this.resubmitDate = new Date();
            } catch (e) {}
        },
        cancel() {
            this.$emit("toggleTaskResubmitModal", false);
            this.$store.commit("flashMessage", {
                show: false,
                message: "",
                type: "",
                link: "",
            });
            this.$store.commit("errors", {});
            this.resubmitDate = new Date();
        },
    },
};
</script>

<style scoped></style>

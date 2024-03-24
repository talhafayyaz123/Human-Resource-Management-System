<template>
    <Modal
        :title="$t('Delete Task')"
        v-if="toggleDeleteModal"
        @toggleModal="cancel"
        :classSize="'modal-sm'"
    >
        <template #body>
            <p class="px-8">
                {{ $t("Are you sure you want to delete this task?") }}
            </p>
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
                @click="deleteTask"
                :loading="isLoading"
                class="delete-btn"
            >
                {{ $t("Delete") }}
            </loading-button>
            
        </template>
    </Modal>
</template>

<script>
import Modal from "@/Components/EditModal.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import { mapGetters } from "vuex";

export default {
    emits: ["toggleDeleteModal", "refresh"],
    computed: {
        ...mapGetters(["isLoading", "errors"]),
    },
    props: {
        taskId: {
            type: Number,
            required: true,
        },
        toggleDeleteModal: {
            type: Boolean,
            default: false,
        },
    },
    components: {
        Modal,
        LoadingButton,
    },
    methods: {
        async deleteTask() {
            this.$store.commit("isLoading", true);
            try {
                await this.$store.dispatch("myTasks/destroy", this.taskId);
                this.$emit("refresh", true);
                this.$emit("toggleDeleteModal", false);
            } catch (e) {}
        },
        cancel() {
            this.$emit("toggleDeleteModal", false);
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

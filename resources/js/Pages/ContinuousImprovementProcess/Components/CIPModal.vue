<template>
    <Modal
        :title="$t('Update CIP')"
        v-if="toggleModal"
        @toggleModal="$emit('cancel', true)"
        :classSize="'modal-md'"
    >
        <template #body>
            <CIPForm ref="cipForm" :action-form="actionForm" />
        </template>
        <template #footer>
            <button
                @click="$emit('cancel', true)"
                type="button"
                class="primary-btn mr-3"
            >
                Cancel
            </button>
            <loading-button
                :loading="isLoading"
                style="cursor: pointer"
                class="secondary-btn"
                @click="update()"
                >{{ $t("Update CIP") }}</loading-button
            >
            
        </template>
    </Modal>
</template>

<script>
import Modal from "@/Components/EditModal.vue";
import CIPForm from "./CIPForm.vue";
import { mapGetters } from "vuex";

export default {
    name: "CIPModal",
    components: {
        Modal,
        CIPForm,
    },
    computed: {
        ...mapGetters(["isLoading"]),
    },
    props: {
        toggleModal: {
            type: Boolean,
            default: false,
        },
        actionForm: {
            type: Object,
            default: {
                processNumber: "",
                title: "",
                date: new Date(),
                description: "",
                suggestedSolution: "",
                affectedGroup: "",
                files: [],
                userId: "",
            },
        },
    },
    methods: {
        /**
         * hits the CIP update API
         */
        async update() {
            try {
                const form = this.$refs.cipForm.form;
                this.$store.commit("isLoading", true);
                const formData = new FormData();
                formData.append("processNumber", form.processNumber);
                formData.append("title", form.title);
                formData.append("date", form.date.toLocaleDateString());
                formData.append("description", form.description);
                formData.append("suggestedSolution", form.suggestedSolution);
                formData.append("affectedGroup", form.affectedGroup?.id);
                form.files.forEach((file, index) => {
                    formData.append(`files[${index}]`, file);
                });
                await this.$store.dispatch(
                    "continuousImprovementProcess/update",
                    {
                        id: this.actionForm.id,
                        data: formData,
                    }
                );
                //reset the form
                this.cancel();
            } catch (e) {
                console.log(e);
            }
        },
    },
};
</script>

<template>
    <Modal
        :title="$t('Edit Level')"
        v-if="toggleModal"
        @toggleModal="$emit('toggleModal', $event)"
        width="50%"
    >
        <template #body>
            <div class="flex flex-col items-center">
                <div class="grid items-center grid-cols-4 gap-6">
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="editSlaForm.name"
                                :error="errors.name"
                                :label="$t('Name')"
                                :required="true"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <number-input
                                :error="errors.factor"
                                v-model="editSlaForm.factor"
                                :required="true"
                                :showPrefix="false"
                                :label="$t('Factor')"
                            />
                        </div>
                    </div>
                </div>
                <div class="grid items-center grid-cols-4 gap-6 my-5">
                    <div class="self-center">
                        <p>{{ $t("Priority: Urgent") }}</p>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                :error="errors.reactionTimeUrgent"
                                :required="true"
                                :min="0"
                                @keypress="limitToPositiveNumbers"
                                v-model="editSlaForm.reactionTimeUrgent"
                                :type="`number`"
                                :label="$t('Reaction Time')"
                            />
                        </div>
                    </div>
                </div>
                <div class="grid items-center grid-cols-4 gap-6 my-5">
                    <div class="self-center">
                        <p>{{ $t("Priority: High") }}</p>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                :error="errors.reactionTimeHigh"
                                :required="true"
                                :min="0"
                                @keypress="limitToPositiveNumbers"
                                v-model="editSlaForm.reactionTimeHigh"
                                :type="`number`"
                                :label="$t('Reaction Time')"
                            />
                        </div>
                    </div>
                </div>
                <div class="grid items-center grid-cols-4 gap-6 my-5">
                    <div class="self-center">
                        <p>{{ $t("Priority: Low") }}</p>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                :error="errors.reactionTimeLow"
                                :required="true"
                                :min="0"
                                @keypress="limitToPositiveNumbers"
                                v-model="editSlaForm.reactionTimeLow"
                                :type="`number`"
                                :label="$t('Reaction Time')"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <button
                @click="$emit('edit', editSlaForm)"
                type="button"
                class="secondary-btn"
            >
                {{ $t("Update") }}
            </button>
            <button
                @click="$emit('toggleModal', false)"
                type="button"
                class="primary-btn mr-3"
            >
                {{ $t("Cancel") }}
            </button>
        </template>
    </Modal>
</template>

<script>
import Modal from "@/Components/EditModal.vue";
import TextInput from "@/Components/TextInput.vue";
import NumberInput from "@/Components/NumberInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { mapGetters } from "vuex";

export default {
    emits: ["toggleModal", "edit"],
    computed: {
        ...mapGetters(["errors"]),
    },
    props: {
        form: { type: Object, default: () => ({}) },
        toggleModal: { type: Boolean, default: false },
    },
    components: {
        Modal,
        TextInput,
        NumberInput,
        SelectInput,
    },
    data() {
        return {
            editSlaForm: {},
        };
    },
    mounted() {
        this.editSlaForm = { ...this.form };
    },
    watch: {
        form: {
            handler: function () {
                this.editSlaForm = { ...this.form };
            },
            deep: true,
        },
    },
};
</script>

<template>
    <Modal
        title="License Key"
        width="50%"
        v-if="toggleModal"
        @toggleModal="$emit('toggleModal', $event)"
    >
        <template #body>
            <div class="flex flex-wrap -mr-6 px-8 py-1">
                <TextInput
                    :modelValue="licenseKey"
                    :key="licenseKey"
                    :isReadonly="true"
                    class="w-full pr-2"
                />
                <button
                    @click="copyText()"
                    class="px-3 py-1 my-2 docsHeroColorBlue rounded"
                >
                    {{ $t("Copy") }}
                </button>
            </div>
        </template>
        <template #footer>
            <button
                @click="$emit('toggleModal', false)"
                type="button"
                class="primary-btn mr-3"
            >
                Close
            </button>
        </template>
    </Modal>
</template>

<script>
import Modal from "@/Components/EditModal.vue";
import TextInput from "@/Components/TextInput.vue";

export default {
    props: {
        toggleModal: {
            type: Boolean,
            default: false,
        },
        licenseKey: {
            type: String,
            default: "",
        },
    },
    components: {
        Modal,
        TextInput,
    },
    methods: {
        copyText(type) {
            // Copy the text inside the text field
            navigator.clipboard.writeText(this.licenseKey);

            // Alert the copied text
            alert("License key copied successfully!");
        },
    },
};
</script>

<style scoped></style>

<template>
    <Modal
        title="API Key"
        width="50%"
        v-if="toggleModal"
        @toggleModal="$emit('toggleModal', $event)"
    >
        <template #warning>
            <p class="pl-4 ml-4 text-red-500">
                {{
                    $t(
                        "copy and save client ID and client secret somewhere as they will be lost as soon as you leave this page"
                    )
                }}
            </p>
        </template>
        <template #body>
            <div class="flex flex-wrap -mr-6 px-8 py-1">
                <div class="form-group w-full">
                    <TextInput
                        :modelValue="clientID"
                        :key="clientID"
                        :isReadonly="true"
                        :label="$t('Client ID')"
                        class="w-full pr-2"
                    />
                    <button
                        @click="copyText('clientID')"
                        class="px-3 py-1 my-2 docsHeroColorBlue rounded"
                    >
                        {{ $t("Copy") }}
                    </button>
                </div>
                <div class="form-group w-full mt-4">
                    <TextInput
                        :modelValue="clientSecret"
                        :key="clientSecret"
                        :isReadonly="true"
                        :label="$t('Client Secret')"
                        class="w-full pr-2"
                    />
                    <button
                        @click="copyText('clientSecret')"
                        class="px-3 py-1 my-2 docsHeroColorBlue rounded"
                    >
                        {{ $t("Copy") }}
                    </button>
                </div>
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
        clientSecret: {
            type: String,
            default: "",
        },
        clientID: {
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
            navigator.clipboard.writeText(
                type === "clientID" ? this.clientID : this.clientSecret
            );

            // Alert the copied text
            alert(
                (type === "clientID" ? "Client ID" : "Client Secret") +
                    " " +
                    "copied successfully!"
            );
        },
    },
};
</script>

<style scoped></style>

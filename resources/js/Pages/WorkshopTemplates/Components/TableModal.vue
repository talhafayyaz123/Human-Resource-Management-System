<template>
    <Modal
        @toggleModal="$emit('cancel', true)"
        :title="actionType"
        v-if="toggle"
    >
        <template #body>
            <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                <text-input
                    v-for="(field, index) in form"
                    :key="'field' + index"
                    v-model="field.value"
                    class="pb-8 pr-6 w-full lg:w-1/3"
                    :label="$t(field.displayName)"
                    placeholder=" "
                    :floatingLabel="true"
                />
            </div>
        </template>
        <template #footer>
            <button
                type="button"
                class="cursor-pointer secondary-btn"
                @click="
                    actionType === 'Add'
                        ? $emit('save', form)
                        : $emit('update', form)
                "
            >
                {{ $t(actionType) }}
            </button>
            <button
                @click="$emit('cancel', true)"
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

export default {
    emits: ["save", "update", "cancel"],
    props: {
        // can be 'Add' and 'Edit'
        actionType: {
            type: String,
            default: "Add",
        },
        // toggles the modal
        toggle: {
            type: Boolean,
            default: false,
        },
        // the currently selected widget
        widget: {
            type: Object,
            default: () => ({}),
        },
        record: {
            type: Array,
            default: () => [],
        },
    },
    data() {
        return {
            form: [], // contains the fields along with values entered by the user from the inputs above
        };
    },
    mounted() {
        // use try/catch because JSON parsing can fail
        try {
            /**
             * if the actionType is set to 'Edit' and the record, which contains the record to be edited is set then set 'form' to the
             * value of the 'record' prop
             */
            if (this.actionType === "Edit" && this.record instanceof Array) {
                // remove reactivity by stringifying and parsing
                this.form = JSON.parse(JSON.stringify(this.record));
                return; // we do not need to execute the code below in 'Edit' mode
            }
            /**
             * create a form which is an array of objects
             * it will have all the keys and values as a the widget 'fields' array
             * we just add an additional value attribute, which will hold the value entered by the user
             * The form will display all the form inputs in the form above
             * The displayName is used as the label for the input
             */
            for (let i in this.widget.configuration.fields) {
                const formField = {
                    ...this.widget.configuration.fields[i],
                    value: "",
                };
                this.form = [...this.form, formField];
            }
        } catch (e) {
            console.log(e);
        }
    },
    components: {
        Modal,
        TextInput,
    },
};
</script>

<style scoped></style>

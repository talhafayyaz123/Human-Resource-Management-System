<template>
    <div class="card mt-3">
        <div class="card-header flex justify-between items-center">
            <h3 class="card-title">{{ $t("Add Contract Fields") }}</h3>
            <!-- :class="[contractFields.length == 0 ? 'pt-8' : '']"  -->
            <div class="flex justify-end my-3">
                <button
                    @click.prevent="
                        contractFields.push({
                            type: 'text',
                            key: '',
                        })
                    "
                    class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 border-b-4 border-blue-700 hover:border-blue-500 rounded"
                >
                    <font-awesome-icon ref="icon" icon="fa-plus" />
                    {{ $t("Add") }}
                </button>
            </div>
        </div>
        <div class="card-body">
            <div
                v-for="(contractField, index) in contractFields"
                :key="index"
                class=""
            >
                <div class="grid items-center grid-cols-3 gap-6 my-5">
                    <div class="w-full">
                        <div class="form-group">
                            <select-input
                                v-model="contractField.type"
                                :label="$t('Type')"
                                :error="errors[`contractField.${index}.type`]"
                            >
                                <option value="text">{{ $t("Text") }}</option>
                                <option value="number">{{ $t("Number") }}</option>
                                <option value="date">{{ $t("Date") }}</option>
                                <option value="time">{{ $t("Time") }}</option>
                                <option value="customer">{{ $t("Customer") }}</option>
                                <option value="invoice">{{ $t("Invoice") }}</option>
                                <option value="offer">{{ $t("Offer") }}</option>
                                <option value="offer-confirmation">
                                    {{ $t("Offer Confirmation") }}
                                </option>
                                <option value="performance-record">
                                    {{ $t("Performance record") }}
                                </option>
                                <option value="products">{{ $t("Products") }}</option>
                            </select-input>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="contractField.key"
                                :error="errors[`contractField.${index}.key`]"
                                :label="$t('Key')"
                            ></text-input>
                        </div>
                    </div>
                    <div class="flex justify-start">
                        <button @click.prevent="contractFields.splice(index, 1)" style="color: red">
                            <font-awesome-icon ref="icon" icon="fa-regular fa-trash-can" />
                        </button>
                    </div>
                </div>
                
            </div>
            
        </div>
    </div>
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters(["errors"]),
    },
    props: {
        // contractFields from parent component
        contractFieldsParent: {
            type: Array,
            default: () => [],
        },
    },
    components: {
        TextInput,
        SelectInput,
    },
    watch: {
        // if the parent contractFields change then set it to the component's contractFields
        contractFieldsParent() {
            this.contractFields = this.contractFieldsParent;
        },
    },
    mounted() {
        // set the parent contractFields to the component's contractFields
        this.contractFields = this.contractFieldsParent;
    },
    data() {
        return {
            contractFields: [],
        };
    },
};
</script>

<style scoped></style>

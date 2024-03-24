<template>
    <div class="card px-7 pb-7 pt-8 relative">
        <div
            v-for="(item, index) in billData"
            :key="index"
            class="grid items-center grid-cols-2 gap-6"
        >
            
            <div class="w-full">
                <div class="form-group">
                    <MultiSelectInput
                        :required="true"
                        :textLabel="$t('Invoice Type')"
                        v-model="item.invoiceTypeId"
                        :key="item.invoiceTypeId"
                        :options="invoiceTypes.data"
                        :multiple="false"
                        label="name"
                        trackBy="id"
                        moduleName="invoiceTypes"
                        :isDisabled="show"
                    />
                </div>
            </div>
            <div class="w-full">
                <div class="form-group">
                    <text-input
                        v-model="item.location"
                        :error="errors?.location"
                        :label="$t('Location')"
                        :isReadonly="show"
                    />
                </div>
            </div>
            <div class="w-full">
                <div class="form-group">
                    <label class="input-label"
                        ><span class="text-red-500">*</span>&nbsp;{{
                            $t("From")
                        }}:</label
                    >
                    <datepicker
                        class="form-control"
                        :disabled="show"
                        :clearable="false"
                        :enable-time-picker="false"
                        auto-apply
                        :close-on-auto-apply="false"
                        v-model="item.fromDate"
                        :class="errors.fromDate ? 'error' : ''"
                    />
                    <div v-if="errors?.fromDate" class="form-error">
                        {{ errors.fromDate }}
                    </div>
                </div>
            </div>
            <div class="w-full">
                <div class="form-group">
                    <label class="input-label"
                        ><span class="text-red-500">*</span>&nbsp;{{
                            $t("To")
                        }}:</label
                    >
                    <datepicker
                        class="form-control"
                        :disabled="show"
                        :clearable="false"
                        :enable-time-picker="false"
                        auto-apply
                        :close-on-auto-apply="false"
                        v-model="item.toDate"
                        :class="errors.toDate ? 'error' : ''"
                    />
                    <div v-if="errors?.toDate" class="form-error">
                        {{ errors.toDate }}
                    </div>
                </div>
            </div>
            <div class="w-full col-span-2">
                <div class="form-group">
                    <text-area-input
                        v-model="item.description"
                        :error="errors.description"
                        :label="$t('Description')"
                        :isReadonly="show"
                    />
                </div>
            </div>
            <div class="w-full col-span-2">
                <div class="form-group">
                    <file-inputs :isReadonly="show" :productFiles="item" />
                </div>
            </div>
            <div class="absolute right-3 top-2" v-if="!show">
                <button
                    role="button"
                    type="button"
                    @click="deleteBill(index)"
                    class="text-red-500"
                >
                    <font-awesome-icon
                        ref="icon"
                        icon="fa-regular fa-trash-can"
                    />
                </button>
            </div>
        </div>
    </div>
    <div v-if="!show" class="flex justify-between mt-3">
        <button
            role="button"
            type="button"
            @click="
                billData.push({
                    location: '',
                    description: '',
                    fromDate: '',
                    toDate: '',
                    invoiceTypeId: '',
                    files: [],
                })
            "
            class="secondary-btn gap-2"
        >
            <font-awesome-icon icon="fa-solid fa-plus" />
            <span>{{ $t("Add bill") }}</span>
        </button>
        <loading-button
            class="secondary-btn"
            @click="save"
            :loading="isLoading"
            >{{ $t("Save & Proceed") }}</loading-button
        >
    </div>
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import TextAreaInput from "@/Components/TextareaInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import FileInputs from "@/Components/FileInputs.vue";
import { mapGetters } from "vuex";

export default {
    props: {
        show: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        ...mapGetters("travelExpenseInvoiceType", ["invoiceTypes"]),
        ...mapGetters(["isLoading", "errors"]),
    },
    components: {
        FileInputs,
        TextInput,
        TextAreaInput,
        SelectInput,
        LoadingButton,
        MultiSelectInput,
    },
    data() {
        return {
            billData: [
                {
                    location: "",
                    description: "",
                    fromDate: "",
                    toDate: "",
                    invoiceTypeId: "",
                    files: [],
                },
            ],
        };
    },
    methods: {
        /**
         * hit the bill delete API
         * @param {index} bill index to be deleted
         */
        async deleteBill(index) {
            if (!!this.billData[index]?.id) {
                try {
                    await this.$store.dispatch(
                        "travelExpenseBills/destroy",
                        this.billData[index]?.id
                    );
                    this.refresh();
                } catch (e) {
                    console.log(e);
                }
            } else {
                this.billData[index].splice(index, 1);
            }
        },
        /**
         * get bill listing
         */
        async refresh() {
            try {
                const response = await this.$store.dispatch(
                    "travelExpenseBills/list",
                    {
                        travelExpenseId: this.$route.params.id,
                    }
                );
                this.billData = (response?.data ?? []).map((bill) => {
                    return {
                        ...bill,
                        fromDate: new Date(bill.fromDate),
                        toDate: new Date(bill.toDate),
                        invoiceTypeId: bill.invoiceType ?? "",
                        files: bill.attachments ?? [],
                    };
                }) ?? [
                    {
                        location: "",
                        description: "",
                        fromDate: "",
                        toDate: "",
                        invoiceTypeId: "",
                        files: [],
                    },
                ];
                this.$store.commit("travelExpenseBills/bills", this.billData);
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * save bill data
         */
        async save() {
            this.$store.commit("isLoading", true);
            try {
                for (let i = 0; i < this.billData.length; i++) {
                    const bill = this.billData[i];
                    const formData = new FormData();
                    const fromDate = bill.fromDate.toISOString();
                    const toDate = bill.toDate.toISOString();
                    formData.set("travelExpenseId", this.$route.params.id);
                    formData.set("invoiceTypeId", bill.invoiceTypeId?.id);
                    formData.set("location", bill.location);
                    formData.set(
                        "fromDate",
                        typeof fromDate === "string"
                            ? fromDate.slice(0, 10)
                            : ""
                    );
                    formData.set(
                        "toDate",
                        typeof toDate === "string" ? toDate.slice(0, 10) : ""
                    );
                    formData.set("description", bill.description);
                    bill.files.forEach((file, index) => {
                        if (file.id) {
                            formData.set(`attachments[${index}][id]`, file.id);
                            formData.set(
                                `attachments[${index}][name]`,
                                file.name
                            );
                            formData.set(
                                `attachments[${index}][size]`,
                                file.size
                            );
                            formData.set(
                                `attachments[${index}][storage_name]`,
                                file.storage_name
                            );
                        } else {
                            formData.set(`attachments[${index}]`, file);
                        }
                    });
                    await this.$store.dispatch(
                        `travelExpenseBills/${bill.id ? "update" : "create"}`,
                        bill.id
                            ? {
                                  id: bill.id,
                                  data: formData,
                              }
                            : formData
                    );
                }
            } catch (e) {
                console.log(e);
            }
        },
    },
    async mounted() {
        try {
            this.refresh();
            // fetch the invoiceTypes list
            if (!this.invoiceTypes?.data?.length) {
                this.$store.dispatch("travelExpenseInvoiceType/list");
            }
        } catch (e) {
            console.log(e);
        }
    },
};
</script>

<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Fill Invoice Reminder Level") }}</h3>
                </div>
                <div class="card-body">
                    <div class="w-full my-5">
                        <div class="form-group">
                            <select-input
                                v-model="form.levelName"
                                :error="errors.levelName"
                                :label="`Level Name`"
                                :required="true"
                            >
                                <option
                                    v-for="status in [
                                        'warning level 1',
                                        'warning level 2',
                                        'warning level 3',
                                    ]"
                                    :key="status"
                                    :value="status"
                                >
                                    {{ status }}
                                </option>
                            </select-input>
                        </div>
                    </div>
                    <div class="w-full my-5">
                        <div class="form-group">
                            <text-input
                                :required="true"
                                v-model="form.name"
                                :error="errors.name"
                                :label="$t('Name')"
                            />
                        </div>
                    </div>
                    <div class="w-full my-5">
                        <div class="form-group">
                            <MultiSelectInput
                                v-model="form.documentTemplateId"
                                :options="documentServices?.data"
                                :multiple="false"
                                :textLabel="$t('Document Template')"
                                label="name"
                                :trackBy="'id'"
                                :required="true"
                                :moduleName="'documentService'"
                                :searchParamName="`search_string`"
                                :taggable="true"
                                @asyncSearch="documentSearch"
                            />
                        </div>
                    </div>
                    <div class="w-full my-5">
                        <div class="form-group">
                            <text-input
                                :type="`number`"
                                :required="true"
                                v-model="form.periodDays"
                                :error="errors.periodDays"
                                :label="$t('Period Days')"
                            />
                        </div>
                    </div>
                    <div class="w-full my-5">
                        <div class="form-group">
                            <number-input
                                v-model="form.reminderFee"
                                :error="errors.reminderFee"
                                type="number"
                                :label="`Reminder Fee`"
                                placeholder=" "
                            ></number-input>
                        </div>
                    </div>
                    <div class="w-full my-5">
                        <div class="form-group">
                            <textarea-input
                                v-model="form.startText"
                                :error="errors.startText"
                                :label="$t('Start Text')"
                            ></textarea-input>
                        </div>
                    </div>
                    <div class="w-full my-5">
                        <div class="form-group">
                            <textarea-input
                                v-model="form.endText"
                                :error="errors.endText"
                                :label="$t('End Text')"
                            ></textarea-input>
                        </div>
                    </div>
                    <div class="w-full my-5">
                        <div class="form-group">
                            <textarea-input
                                v-model="form.mailText"
                                :error="errors.mailText"
                                :label="$t('Mail Text')"
                            ></textarea-input>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link to="/invoice-reminders" class="primary-btn mr-3">
                    <span class="mr-1">
                        <CustomIcon name="cancelIcon" />
                    </span>
                    <span>{{ $t("Cancel") }}</span>
                </router-link>
                <loading-button
                    v-if="$can(`${$route.meta.permission}.create`)"
                    :loading="isLoading"
                    class="secondary-btn"
                >
                    <span class="mr-1">
                        <CustomIcon name="saveIcon" />
                    </span>
                    {{ $t("Save and Proceed") }}
                </loading-button>
            </div>
        </form>
    </div>
</template>

<script>
import LoadingButton from "../../Components/LoadingButton.vue";
import TextInput from "../../Components/TextInput.vue";
import { mapGetters } from "vuex";
import TextareaInput from "../../Components/TextareaInput.vue";
import NumberInput from "../../Components/NumberInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("documentService", ["documentServices", "count"]),
    },
    components: {
        LoadingButton,
        TextInput,
        TextareaInput,
        NumberInput,
        SelectInput,
        MultiSelectInput,
        PageHeader,
    },
    async mounted() {
        await this.$store.dispatch("documentService/list", {
            limit_start: 0,
            limit_count: 25,
        });
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Invoice Reminder",
                    to: "/invoice-reminders",
                },
                {
                    text: this.$t("Create"),
                    active: true,
                },
            ],
            form: {
                levelName: "",
                periodDays: "",
                reminderFee: "",
                startText: "",
                endText: "",
                mailText: "",
                name: "",
                documentTemplateId: "",
            },
        };
    },
    methods: {
        async store() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("invoicereminder/create", {
                    ...this.form,
                    documentTemplateId: this.form.documentTemplateId?.id,
                });
                await this.$store.dispatch("invoicereminder/list");
                this.$router.push("/invoice-reminders");
            } catch (e) {}
        },
        async documentSearch(data) {
            this.documentServices = data?.data;
        },
    },
};
</script>

<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Fill Terms of payment Details") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="w-full mb-5">
                        <div class="form-group">
                            <text-input
                                :required="true"
                                v-model="form.name"
                                :error="errors.name"
                                :label="$t('Name')"
                            />
                        </div>
                    </div>
                    <div class="grid grid-cols-[1fr,10fr,10fr] items-center">
                        <p class="self-center ml-2 mt-5">1.</p>
                        <div class="flex flex-wrap self-center">
                            <text-input
                                type="number"
                                :required="true"
                                v-model="form.percentage1"
                                :error="errors.percentage1"
                                class="pb-8 pr-6 w-full"
                                :label="$t('Percentage (%)')"
                            />
                        </div>
                        <div class="flex flex-wrap">
                            <text-input
                                type="number"
                                :required="true"
                                v-model="form.noOfDays1"
                                :error="errors.noOfDays1"
                                class="pb-8 pr-6 w-full"
                                :label="$t('No. of Days')"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-[1fr,10fr,10fr] items-center">
                        <p class="self-center ml-2 mt-5">2.</p>
                        <div class="flex flex-wrap self-center">
                            <text-input
                                type="number"
                                v-model="form.percentage2"
                                :error="errors.percentage2"
                                class="pb-8 pr-6 w-full"
                                :label="$t('Percentage (%)')"
                            />
                        </div>
                        <div class="flex flex-wrap">
                            <text-input
                                type="number"
                                v-model="form.noOfDays2"
                                :error="errors.noOfDays2"
                                class="pb-8 pr-6 w-full"
                                :label="$t('No. of Days')"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-[1fr,10fr,10fr] items-center">
                        <p class="self-center ml-2 mt-5">3.</p>
                        <div class="flex flex-wrap self-center">
                            <text-input
                                type="number"
                                v-model="form.percentage3"
                                :error="errors.percentage3"
                                class="pb-8 pr-6 w-full"
                                :label="$t('Percentage (%)')"
                            />
                        </div>
                        <div class="flex flex-wrap">
                            <text-input
                                type="number"
                                v-model="form.noOfDays3"
                                :error="errors.noOfDays3"
                                class="pb-8 pr-6 w-full"
                                :label="$t('No. of Days')"
                            />
                        </div>
                    </div>

                    <div class="w-full my-5">
                        <div class="form-group">
                            <TextareaInput
                                v-model="form.offerText"
                                :error="errors.offerText"
                                :label="$t('Text On Offers')"
                            />
                        </div>
                    </div>

                    <div class="w-full my-5">
                        <div class="form-group">
                            <TextareaInput
                                v-model="form.orderText"
                                :error="errors.orderText"
                                :label="$t('Text On Offer Confirmation')"
                            />
                        </div>
                    </div>

                    <div class="w-full my-5">
                        <div class="form-group">
                            <TextareaInput
                                v-model="form.invoiceText"
                                :error="errors.invoiceText"
                                :label="$t('Text On Invoices')"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link to="/terms-of-payment" class="primary-btn mr-3">
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
import TextInput from "../../Components/TextInput.vue";
import TextareaInput from "../../Components/TextareaInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
    },
    components: {
        TextareaInput,
        LoadingButton,
        TextInput,
        PageHeader,
    },
    remember: "form",
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Terms Of Payment",
                    to: "/terms-of-payment",
                },
                {
                    text: this.$t("Create"),
                    active: true,
                },
            ],
            form: {
                percentage1: "",
                noOfDays1: "",
                percentage2: "",
                noOfDays2: "",
                percentage3: "",
                noOfDays3: "",
                offerText: "",
                orderText: "",
                invoiceText: "",
                name: "",
            },
        };
    },
    mounted() {},
    methods: {
        async store() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("termsOfPayment/create", {
                    ...this.form,
                });
                await this.$store.dispatch("termsOfPayment/list");
                this.$router.push("/terms-of-payment");
            } catch (e) {}
        },
    },
};
</script>

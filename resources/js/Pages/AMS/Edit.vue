<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Fill Application Management Service") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.customerId"
                                    :key="form.customerId"
                                    :isDisabled="true"
                                    :required="true"
                                    :options="companies.data"
                                    :multiple="false"
                                    textLabel="Customer"
                                    label="companyName"
                                    trackBy="id"
                                    :error="errors.companyId"
                                    moduleName="companies"
                                    class=""
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <NumberInput
                                    v-model="form.serviceHoursYear"
                                    :key="form.serviceHoursYear"
                                    :required="true"
                                    :label="$t('Service Hours Year')"
                                    :error="errors.serviceHoursYear"
                                    class=""
                                    :showPrefix="false"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <NumberInput
                                    v-model="form.remainingHours"
                                    :key="form.remainingHours"
                                    :required="true"
                                    :label="$t('Remaining Hours')"
                                    :error="errors.remainingHours"
                                    class=""
                                    :showPrefix="false"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <NumberInput
                                    v-model="form.hourlyRate"
                                    :key="form.hourlyRate"
                                    :required="true"
                                    :label="$t('Hourly Rate')"
                                    :error="errors.hourlyRate"
                                    class=""
                                    :showPrefix="false"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <NumberInput
                                    v-model="form.monthlyAmount"
                                    :key="form.monthlyAmount"
                                    :required="true"
                                    :label="$t('Monthly Amount')"
                                    :error="errors.monthlyAmount"
                                    class=""
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-if="isApiCalled"
                                    :required="true"
                                    :textLabel="'Software'"
                                    :key="form.softwareId"
                                    class=""
                                    :trackBy="'id'"
                                    :label="'name'"
                                    :multiple="false"
                                    v-model="form.softwareId"
                                    moduleName="softwares"
                                    :search-param-name="'search'"
                                    :options="softwares?.data"
                                    :error="errors.softwareId"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="w-full mt-6">
                        <div class="flex items-center">
                            <p class="font-medium">SLA SERVICE TIME :</p>
                            <p class="mx-2 font-normal">
                                {{
                                    form?.attachments?.slaServiceTime?.name ??
                                    " - "
                                }}
                            </p>
                        </div>

                        <div class="flex items-center">
                            <p class="font-medium">SLA Level :</p>
                            <p class="mx-2 font-normal">
                                {{ form?.attachments?.slaLevel?.name ?? " - " }}
                            </p>
                        </div>
                    </div>
                    <div
                        class="w-1/4 mt-4"
                        v-if="
                            form?.attachments?.products &&
                            form?.attachments?.products?.length > 0
                        "
                    >
                        <p class="font-medium">List Of Products :</p>
                        <div
                            class="flex items-center font-medium justify-between"
                        >
                            <p>Name</p>
                            <p>Quantity</p>
                        </div>
                        <div
                            v-for="(products, index) in form?.attachments
                                ?.products"
                            :key="index"
                            class="flex items-center justify-between font-normal"
                        >
                            <p>
                                {{ products?.productName ?? "-" }}
                            </p>
                            <p>
                                {{ products?.quantity ?? "-" }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link
                    :to="`/ams?page=${returnPage}`"
                    class="primary-btn mr-3"
                >
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
                        <CustomIcon name="updateIcon" />
                    </span>
                    {{ $t("Update") }}
                </loading-button>
            </div>
        </form>
    </div>
</template>

<script>
import LoadingButton from "../../Components/LoadingButton.vue";
import NumberInput from "../../Components/NumberInput.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("companies", ["companies"]),
        ...mapGetters("softwares", ["softwares"]),
    },
    components: {
        LoadingButton,
        NumberInput,
        MultiSelectInput,
        PageHeader,
    },
    data() {
        return {
            returnPage: "",
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Support"),
                    to: "/ams",
                },
                {
                    text: this.$t("Application Management Services"),
                    to: "/ams?page=" + this.$route.query.page,
                },
                {
                    text: "Edit",
                    active: true,
                },
            ],
            form: {
                customerId: "",
                serviceHoursYear: 0,
                remainingHours: 0,
                hourlyRate: 0,
                monthlyAmount: 0,
                softwareId: "",
                attachments: "",
            },
            isApiCalled: false,
        };
    },
    async mounted() {
        if (this.$route.query.page) {
            this.returnPage = this.$route.query.page;
        }
        try {
            this.$store.commit("showLoader", true);
            await this.$store.dispatch("softwares/list");
            const response = await this.$store.dispatch(
                "ams/show",
                this.$route.params.id
            );
            document.title = "Edit Ams " + response?.data?.data?.amsNumber;
            this.form = {
                customerId: response?.data?.data?.customerId ?? "",
                serviceHoursYear: response?.data?.data?.serviceHoursYear ?? 0,
                remainingHours: response?.data?.data?.remainingHours ?? 0,
                hourlyRate: response?.data?.data?.hourlyRate ?? 0,
                monthlyAmount: response?.data?.data?.monthlyAmount ?? 0,
                softwareId: this.softwares?.data?.find((software) => {
                    return software.id == response?.data?.data?.softwareId;
                }),
                attachments: {
                    products: response?.data?.data?.attachment?.products,
                    slaLevel: response?.data?.data?.attachment?.slaLevelId,
                    slaServiceTime:
                        response?.data?.data?.attachment?.slaServiceTimeId,
                },
            };
            if (!this.companies?.data?.length) {
                await this.$store.dispatch("companies/list");
            }
            this.isApiCalled = true;
        } catch (e) {
            console.log(e);
        } finally {
            this.$store.commit("showLoader", false);
        }
    },
    methods: {
        async update() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("ams/update", {
                    id: this.$route.params.id,
                    data: {
                        ...this.form,
                        customerId: this.form?.customerId?.id,
                        softwareId: this.form?.softwareId?.id,
                    },
                });
                await this.$store.dispatch("ams/list");
                this.$router.push("/ams?page=" + this.returnPage);
            } catch (e) {
                console.log(e);
            }
        },
    },
};
</script>

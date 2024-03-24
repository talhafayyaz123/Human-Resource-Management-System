<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="store">
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
                                    :required="true"
                                    :textLabel="'Software'"
                                    class=""
                                    :trackBy="'id'"
                                    :label="'name'"
                                    :multiple="false"
                                    v-model="form.softwareId"
                                    moduleName="softwares"
                                    :search-param-name="'search'"
                                    :options="softwares?.data ?? []"
                                    :error="errors.softwareId"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link to="/ams" class="primary-btn mr-3">
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
                    to: "/ams",
                },
                {
                    text: "Create",
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
            },
        };
    },
    async mounted() {
        try {
            if (!this.companies?.data?.length) {
                await this.$store.dispatch("companies/list");
            }
            await this.$store.dispatch("softwares/list");
        } catch (e) {
            console.log(e);
        }
    },
    methods: {
        async store() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("ams/create", {
                    ...this.form,
                    customerId: this.form?.customerId?.id,
                    softwareId: this.form?.softwareId?.id,
                });
                await this.$store.dispatch("ams/list");
                this.$router.push("/ams");
            } catch (e) {}
        },
    },
};
</script>

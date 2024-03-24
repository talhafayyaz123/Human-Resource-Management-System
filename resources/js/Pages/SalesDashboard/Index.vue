<template>
    <PageHeader :items="breadcrumbItems" />
    <div class="card">
        <div class="card-body">
            <div class="grid items-center grid-cols-2 gap-6">
                <div class="w-full">
                    <div class="form-group">
                        <label class="mr-2" for="yearly">{{
                            $t("Yearly")
                        }}</label>
                        <input
                            id="yearly"
                            name="chart-type"
                            type="radio"
                            v-model="filterBy"
                            value="yearly"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <label class="mr-2" for="monthly">{{
                            $t("Monthly")
                        }}</label>
                        <input
                            id="monthly"
                            name="chart-type"
                            type="radio"
                            v-model="filterBy"
                            value="monthly"
                        />
                    </div>
                </div>
                <div class="w-full" v-if="filterBy === 'monthly'">
                    <div class="form-group">
                        <label class="input-label"
                            ><span class="text-red-500">*&nbsp;</span
                            >{{ $t("Month") }}:</label
                        >
                        <datepicker
                            class="form-control"
                            :clearable="false"
                            :locale="globalLanguage"
                            :enable-time-picker="false"
                            auto-apply
                            :close-on-auto-apply="false"
                            v-model="month"
                            month-picker
                        />
                    </div>
                </div>
                <div class="w-full" v-else-if="filterBy === 'yearly'">
                    <div class="form-group">
                        <text-input
                            :required="true"
                            v-model="year"
                            :label="$t('Year')"
                            type="number"
                            :min="2000"
                            :max="new Date().getFullYear()"
                        >
                        </text-input>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            v-model="companyId"
                            :key="companyId"
                            :required="true"
                            :options="companies.data"
                            :multiple="false"
                            :textLabel="$t('Customer')"
                            label="companyName"
                            trackBy="id"
                            moduleName="companies"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- offers/offerconfirmation graph -->
    <OffersStatistics
        :filterBy="filterBy"
        :month="month"
        :year="year"
        :companyId="companyId?.id ?? ''"
    />

    <!-- stacked charts -->
    <StackedProportions
        :filterBy="filterBy"
        :month="month"
        :year="year"
        :companyId="companyId?.id ?? ''"
    />

    <!-- products graph -->
    <ProductProportions
        :filterBy="filterBy"
        :month="month"
        :year="year"
        :companyId="companyId?.id ?? ''"
    />

    <!-- leads graph -->
    <LeadsChannelStatistics :filterBy="filterBy" :month="month" :year="year" />
</template>

<script>
import OffersStatistics from "./Components/OffersStatistics.vue";
import ProductProportions from "./Components/ProductProportions/Index.vue";
import LeadsChannelStatistics from "./Components/LeadsChannelStatistics.vue";
import StackedProportions from "./Components/StackedProportions/Index.vue";
import TextInput from "../../Components/TextInput.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    computed: {
        ...mapGetters("companies", ["companies"]),
    },
    components: {
        OffersStatistics,
        ProductProportions,
        LeadsChannelStatistics,
        StackedProportions,
        TextInput,
        MultiSelectInput,
        PageHeader,
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Sales",
                    to: "/sales-dashboard",
                },
                {
                    text: "Dashboard",
                    active: true,
                },
            ],
            filterBy: "yearly",
            // the currently selected date
            month: {
                month: new Date().getMonth(),
                year: new Date().getFullYear(),
            },
            year: new Date().getFullYear(),
            companyId: "",
        };
    },
    async mounted() {
        try {
            if (!this.companies?.data?.length) {
                this.$store.commit("showLoader", true);
                await this.$store.dispatch("companies/list");
            }
        } catch (e) {
            console.log(e);
        } finally {
            this.$store.commit("showLoader", false);
        }
    },
};
</script>

<style scoped></style>

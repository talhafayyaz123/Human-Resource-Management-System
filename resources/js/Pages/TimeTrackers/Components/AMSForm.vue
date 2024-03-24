<template>
    <!-- Which "company" does the current record belong to -->
    <div class="grid items-center grid-cols-2 gap-6">
        <div class="w-full">
            <div class="form-group">
                <MultiSelectInput
                    :required="true"
                    v-if="$can('company.list')"
                    
                    :textLabel="$t('Customer')"
                    v-model="company"
                    :options="companyOptions"
                    :multiple="false"
                    label="companyName"
                    trackBy="id"
                    moduleName="companies"
                    :key="company?.id"
                    @update:model-value="fetchCompanyAMS"
                    :error="$t(modalErrors.company)"
                    @asyncSearch="companyOptions = companies?.data"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <MultiSelectInput
                    :required="true"
                    
                    :textLabel="$t('AMS')"
                    v-model="ams"
                    :key="ams"
                    :options="amsList"
                    :multiple="false"
                    label="amsNumber"
                    trackBy="id"
                    @update:model-value="setTotalRemainingHours"
                    :isDisabled="(amsList?.length ?? 0) == 1"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <!-- Time spent on the entry -->
                <number-input
                    :roundNearQuarter="true"
                    :forceLeftBound="true"
                    :showPrefix="false"
                    v-model="accountedTime"
                    :min="0"
                    
                    :required="true"
                    :label="$t('Accounted Time')"
                    :error="$t(modalErrors.accountedTime)"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <!-- Total Remaining Hours of the selected customer from AMS -->
                <number-input
                    :roundNearQuarter="true"
                    :forceLeftBound="true"
                    :showPrefix="false"
                    v-model="totalRemainingHours"
                    :isReadonly="true"
                    :min="0"
                    
                    :label="$t('Total Remaining Hours')"
                    :error="$t(modalErrors.totalRemainingHours)"
                />
            </div>
        </div>
        <div class="w-full col-span-2">
            <div class="form-group">
                <!-- Description of the given type -->
                <text-area-input
                    :required="true"
                    v-model="description"
                    :rows="3"
                    :error="$t(modalErrors.description)"
                    
                    :label="$t('Description')"
                />
            </div>
        </div>
        <div class="w-full col-span-2">
            <div class="form-group">
                <text-area-input
                    :required="false"
                    v-model="internalNote"
                    :rows="3"
                    :label="$t('Internal Note')"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="checkbox-group">
                <!-- Is the time tracking for free? -->
                    <input
                        id="goodwill"
                        v-model="isGoodwill"
                        :type="`checkbox`"
                        class="checkbox-input"
                    />
                    <label class="checkbox-label" :for="'goodwill'">
                        {{ $t("Is Goodwill") }}?
                    </label>
            </div>
        </div>
    </div>
</template>

<script>
import NumberInput from "@/Components/NumberInput.vue";
import TextAreaInput from "@/Components/TextareaInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";

export default {
    name: "AMSForm",
    props: [
        "modalErrors",
        "actionType",
        "filterCompany",
        "timeTrackerCompanyEditData",
    ],
    components: {
        NumberInput,
        TextAreaInput,
        MultiSelectInput,
    },
    computed: {
        ...mapGetters("companies", ["companies"]),
    },
    data() {
        return {
            company: "",
            companyOptions: [],
            accountedTime: "0.00",
            totalRemainingHours: "0.00",
            description: "",
            internalNote: "",
            isGoodwill: false,
            ams: null,
            amsList: [],
        };
    },
    mounted() {
        this.getCompanies();

        if (this.actionType === "edit") {
            this.description = this.timeTrackerCompanyEditData.description;
            this.internalNote = this.timeTrackerCompanyEditData.internalNote;
            this.accountedTime = this.timeTrackerCompanyEditData.time;
            this.isGoodwill =
                this.timeTrackerCompanyEditData.isGoodwill == 1 ? true : false;
            this.company = this.timeTrackerCompanyEditData.company;
            if (this.company) {
                this.fetchCompanyAMS();
            }
        }
    },
    methods: {
        async setTotalRemainingHours() {
            await this.$nextTick();
            this.totalRemainingHours = this.ams?.remainingHours ?? 0;
        },
        /**
         * fetches the AMS record using show API for the selected company and sets the 'totalRemainingHours' to the remainingHours from
         * the response
         */
        async fetchCompanyAMS() {
            try {
                const response = await this.$store.dispatch(
                    "ams/getByCustomer",
                    this.company?.id
                );
                this.amsList = response?.data?.data ?? [];
                if (
                    this.actionType === "edit" &&
                    this.timeTrackerCompanyEditData.moduleId
                ) {
                    this.ams =
                        this.amsList.find(
                            (ams) =>
                                ams.id ==
                                this.timeTrackerCompanyEditData.moduleId
                        ) ?? null;
                }
                if (this.actionType === "add" && this.amsList?.length == 1) {
                    this.ams = this.amsList[0];
                }
                this.totalRemainingHours = this.ams?.remainingHours ?? 0;
            } catch (e) {
                console.log(e);
            }
        },
        async getCompanies() {
            await this.$store.dispatch("companies/list", {
                perPage: 25,
                page: 1,
            });
            if (this.filterCompany.id !== "all") {
                this.companyOptions = this.companies?.data.filter(
                    (item) => item.id === this.filterCompany.id
                );
                this.company = this.filterCompany;
            } else {
                this.companyOptions = this.companies?.data;
            }
        },
    },
};
</script>

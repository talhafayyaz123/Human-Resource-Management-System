<template>
    <div class="grid items-center grid-cols-2 gap-6">
        <div class="w-full">
            <div class="form-group">
                <number-input
                    :maximumFractionDigits="2"
                    v-model="jobForm.targetValueYear"
                    @update:model-value="calculateTargetValue('month')"
                    class=""
                    :label="$t('Target value | year (PT)')"
                    type="number"
                    placeholder=" "
                    :error="errors.targetValueYear"
                    :showPrefix="false"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <number-input
                    :maximumFractionDigits="2"
                    v-model="jobForm.targetValueMonth"
                    @update:model-value="calculateTargetValue('year')"
                    class=""
                    :label="$t('Target value | month (PT)')"
                    type="number"
                    placeholder=" "
                    :error="errors.targetValueMonth"
                    :showPrefix="false"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <number-input
                    :maximumFractionDigits="2"
                    v-model="targetValueYearHours"
                    @update:model-value="calculateTargetValue('year-hours')"
                    class=""
                    :label="$t('Target value | Year (hours)')"
                    type="number"
                    placeholder=" "
                    :error="errors.targetValueMonthHours"
                    :showPrefix="false"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <number-input
                    :maximumFractionDigits="2"
                    v-model="targetValueMonthHours"
                    @update:model-value="calculateTargetValue('month-hours')"
                    class=""
                    :label="$t('Target value | month (hours)')"
                    type="number"
                    placeholder=" "
                    :error="errors.targetValueMonth"
                    :showPrefix="false"
                />
            </div>
        </div>
    </div>
</template>

<script>
import NumberInput from "@/Components/NumberInput.vue";

export default {
    inject: ["isLoaded"],
    components: {
        NumberInput,
    },
    props: ["jobForm", "errors"],
    data() {
        return {
            targetValueYearHours: 0,
            targetValueMonthHours: 0,
        };
    },
    mounted() {
        this.calculateTargetValueHours(); //compute the hours for target value
    },
    watch: {
        "jobForm.targetValueYear"() {
            /**
             * compute the hours for target value when targetValueYear year changes.
             * note that we only add watcher for year and not month, that is because when month value changes the year value is recalcuted
             * and vice versa
             */
            this.calculateTargetValueHours();
        },
    },
    methods: {
        /**
         * calculates the target value hours for year and month
         */
        calculateTargetValueHours() {
            this.targetValueYearHours = this.jobForm.targetValueYear * 8;
            this.targetValueMonthHours = this.targetValueYearHours / 12;
        },
        /**
         * Auto calculates the targetValueMonth or targetValueYear based on 'field'
         * @param {field} can be 'month' or 'year', determines what value of which variable should be calculated
         */
        calculateTargetValue(field) {
            if (field === "month") {
                this.jobForm.targetValueMonth =
                    (this.jobForm.targetValueYear ?? 0) / 12; // divide by 12 to calucate the month value
            } else if (field === "year") {
                this.jobForm.targetValueYear =
                    (this.jobForm.targetValueMonth ?? 0) * 12; // multiple by 12 to calculate the year value
            } else if (field === "year-hours") {
                this.jobForm.targetValueYear = this.targetValueYearHours / 8; // calculate the targetValueYear
                this.calculateTargetValue("month"); // since the year value has changed we recalculate the month value
            } else if (field === "month-hours") {
                this.targetValueYearHours = this.targetValueMonthHours * 12; // calculate the targetValueYearHours
                this.jobForm.targetValueYear = this.targetValueYearHours / 8; // calculate the targetValueYear
                this.calculateTargetValue("month"); // since the year value has changed we recalculate the month value
            }
        },
    },
};
</script>

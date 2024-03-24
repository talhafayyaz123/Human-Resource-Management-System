<template>
    <div class="table-responsive">
        <table class="doc-table">
            <tr class="text-left font-bold">
                <th class="">{{ $t("Active") }}</th>
                <th class="">{{ $t("Date") }}</th>
                <th class="">{{ $t("From") }}</th>
                <th class="">{{ $t("Untill") }}</th>
                <th class="">{{ $t("Breakfast") }}</th>
                <th class="">{{ $t("Lunch") }}</th>
                <th class="">{{ $t("Dinner") }}</th>
                <th class="">{{ $t("Expense Rate") }}</th>
            </tr>
            <tr v-for="(item, index) in daySpecificationData" :key="index">
                <td class="">
                    <input
                        :disabled="show"
                        v-model="item.isActive"
                        type="checkbox"
                        class="border-gray-300 rounded h-5 w-5"
                    />
                </td>
                <td class="">
                    {{ $dateFormatter(item.date, globalLanguage) }}
                </td>
                <td class="">
                    {{ item.fromTime }}
                </td>
                <td class="">
                    {{ item.toTime }}
                </td>
                <td class="">
                    <input
                        :disabled="show"
                        v-model="item.breakfast"
                        type="checkbox"
                        class="border-gray-300 rounded h-5 w-5"
                    />
                </td>
                <td class="">
                    <input
                        :disabled="show"
                        v-model="item.lunch"
                        type="checkbox"
                        class="border-gray-300 rounded h-5 w-5"
                    />
                </td>
                <td class="">
                    <input
                        :disabled="show"
                        v-model="item.dinner"
                        type="checkbox"
                        class="border-gray-300 rounded h-5 w-5"
                    />
                </td>
                <td class="">
                    <number-input
                        :isReadonly="show"
                        v-model="item.expenseRate"
                        showPrefix="true"
                        class="lg:w-1/2 pr-2"
                        :error="errors.expenseRate ?? ''"
                    />
                </td>
            </tr>
        </table>
    </div>
    <div v-if="!show" class="mt-3 flex flex-row-reverse">
        <loading-button @click="save" :loading="isLoading" class="secondary-btn">{{
            $t("Save day specification")
        }}</loading-button>
    </div>
</template>

<script>
import LoadingButton from "@/Components/LoadingButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import NumberInput from "@/Components/NumberInput.vue";
import { mapGetters } from "vuex";

export default {
    props: {
        show: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        ...mapGetters(["isLoading", "errors"]),
    },
    components: {
        TextInput,
        SelectInput,
        LoadingButton,
        NumberInput,
    },
    data() {
        return {
            daySpecificationData: [],
        };
    },
    watch: {
        daySpecificationData: {
            handler: function () {
                this.calculateExpenseRate();
            },
            deep: true,
        },
    },
    async mounted() {
        this.refresh();
    },
    methods: {
        calculateExpenseRate() {
            this.daySpecificationData.forEach((item, index) => {
                let expensesRate = 0;
                if ((item?.country ?? "").toLowerCase() === "germany") {
                    let absenceTime = this.diffInHours(
                        item.fromTime,
                        item.toTime
                    );
                    // if one day trip and absence time is greater than 8 then add 14
                    if (
                        absenceTime > 8 &&
                        this.daySpecificationData.length == 1
                    ) {
                        expensesRate += 14;
                    }
                    // add 14 for arrival and departure days
                    if (
                        index == 0 ||
                        this.daySpecificationData.length - 1 == index
                    ) {
                        expensesRate += 14;
                    }
                    // if absence time is 24 hours than add 28
                    if (absenceTime == 24) {
                        expensesRate += 28;
                    }
                }
                const originalExpensesRate = expensesRate;
                // if breakfast is checked then ad 5.6
                if (item.breakfast) {
                    expensesRate -= originalExpensesRate * 0.2;
                }
                // if lunch is checked then ad 11.2
                if (item.lunch) {
                    expensesRate -= originalExpensesRate * 0.4;
                }
                // if dinner is checked then ad 11.2
                if (item.dinner) {
                    expensesRate -= originalExpensesRate * 0.4;
                }
                item.expenseRate = (
                    expensesRate < 0 ? 0 : expensesRate
                ).toFixed(2);
            });
        },
        /**
         * calculates hours difference between two time strings
         * @param {timeString1} time string 1
         * @param {timeString2} time string 2
         */
        diffInHours(timeString1, timeString2) {
            // Create Date objects from the time strings
            var date1 = new Date();
            const fromTokens = (timeString1 ?? "").split(":"); // get the hour and minute but spliting with ":"
            // set the hour and minute
            date1.setHours(fromTokens?.[0] ?? 0);
            date1.setMinutes(fromTokens?.[1] ?? 0);
            date1.setSeconds(fromTokens?.[2] ?? 0);

            var date2 = new Date();
            const toTokens = (timeString2 ?? "").split(":"); // get the hour and minute but spliting with ":"
            // set the hour and minute
            date2.setHours(toTokens?.[0] ?? 0);
            date2.setMinutes(toTokens?.[1] ?? 0);
            date2.setSeconds(toTokens?.[2] ?? 0);

            // Calculate the time difference in milliseconds
            var timeDifference = date2 - date1;

            // Convert milliseconds to hours
            return Math.round(timeDifference / (1000 * 60 * 60));
        },
        /**
         * save day specifications
         */
        async save() {
            this.$store.commit("isLoading", true);
            try {
                await this.$store.dispatch(
                    "travelExpenseDaySpecifications/update",
                    {
                        travelExpenseId: this.$route.params.id,
                        data: this.daySpecificationData,
                    }
                );
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * get day specifications listing
         */
        async refresh() {
            const response = await this.$store.dispatch(
                "travelExpenseDaySpecifications/list",
                {
                    travelExpenseId: this.$route.params.id,
                }
            );
            this.daySpecificationData = response?.data?.data ?? [];
            this.$store.commit(
                "travelExpenseDaySpecifications/daySpecifications",
                this.daySpecificationData
            );
        },
    },
};
</script>

<style scoped>
.compact-cell {
    width: 7%;
}
.table-layout-auto {
    table-layout: auto;
}
</style>

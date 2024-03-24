<template>
    <div class="grid gap-2 grid-cols-[1fr,1fr]">
        <div>
            <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                <div class="flex pr-6 w-full">
                    <p class="pb-8 pr-6 self-center">{{ $t("Day") }}</p>
                    <SelectInput
                        class="pb-8 pr-6 self-center w-1/4"
                        v-model="dayOfMonth"
                        :key="dayOfMonth"
                    >
                        <option
                            v-for="day in dayOfMonthsData"
                            :key="'day-' + day"
                            :value="day"
                        >
                            {{ day }}
                        </option>
                    </SelectInput>
                    <p class="pb-8 pr-6 self-center">{{ $t("of") }}</p>
                    <MultiSelectInput
                        @update:model-value="removeAllExceptDefault"
                        v-model="month"
                        :key="month"
                        :options="months"
                        :multiple="true"
                        :trackBy="'value'"
                        class="pb-8 pr-6 self-center"
                        :label="'name'"
                    >
                    </MultiSelectInput>
                </div>
                <div class="flex w-1/2">
                    <p class="pb-8 pr-6 w:1/4 self-center">{{ $t("at") }}</p>
                    <SelectInput
                        class="pb-8 pr-1 w-1/2"
                        v-model="hour"
                        :key="hour"
                        :label="$t('Hour')"
                    >
                        <option
                            v-for="hour in hoursData"
                            :key="'hour-' + hour"
                            :value="(hour < 10 ? '0' : '') + hour"
                        >
                            {{ (hour < 10 ? "0" : "") + hour }}
                        </option>
                    </SelectInput>
                    <p class="pb-8 pr-1 w:1/4 self-center">:</p>
                    <SelectInput
                        class="pb-8 pr-1 w-full lg:w-1/2"
                        v-model="minute"
                        :key="minute"
                        :label="$t('Minute')"
                    >
                        <option
                            v-for="minute in minutesData"
                            :key="'minute-' + minute"
                            :value="(minute < 10 ? '0' : '') + minute"
                        >
                            {{ (minute < 10 ? "0" : "") + minute }}
                        </option>
                    </SelectInput>
                    <p class="pb-8 pr-1 w:1/4 self-center">:</p>
                    <SelectInput
                        class="pb-8 pr-1 w-full lg:w-1/2"
                        v-model="second"
                        :key="second"
                        :label="$t('Seconds')"
                    >
                        <option
                            v-for="second in secondsData"
                            :key="'second-' + second"
                            :value="(second < 10 ? '0' : '') + second"
                        >
                            {{ (second < 10 ? "0" : "") + second }}
                        </option>
                    </SelectInput>
                </div>
            </div>
            <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                <TextInput
                    :label="$t('Reset Pattern')"
                    :modelValue="resetPattern"
                    @change="onChange"
                    class="pb-8 pr-6 w-full lg:w-1/3"
                ></TextInput>
            </div>
        </div>
        <div class="-mb-8 -mr-6 px-8 pb-5">
            <p class="font-bold pb-5">{{ $t("Next 5 scheduled dates") }}:</p>
            <p
                v-for="(date, index) in nextSchedulesDates"
                :key="'schedules-date-' + date"
            >
                <span class="font-bold">{{ index + 1 + "." }}</span> {{ date }}
            </p>
        </div>
    </div>
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import { watch, ref, onMounted, computed } from "vue";
import { parse } from "@datasert/cronjs-parser";
import { wTrans } from "laravel-vue-i18n";
import * as cronjsMatcher from "@datasert/cronjs-matcher";

export default {
    name: "CronExpressionMaker",
    props: {
        // the reset_pattern retrieved from the show rule API
        formResetPattern: {
            type: String,
            default: () => "* * * * * ?",
        },
    },

    setup(props) {
        const hasError = ref(false);
        const second = ref("00");
        const minute = ref("00");
        const hour = ref("12");
        const dayOfMonth = ref(1);
        const month = ref([
            {
                name: "January",
                value: "01",
            },
        ]);
        const resetPattern = ref("* * * * * ?");
        const dayOfWeek = "?";
        const secondsData = [...Array(61).keys()];
        const minutesData = [...Array(61).keys()];
        const hoursData = [...Array(24).keys()];
        const dayOfMonthsData = [
            ...Array.from({ length: 31 }, (_, i) => i + 1),
        ];

        const defaultMonthsData = [
            {
                name: wTrans("Janurary"),
                value: "01",
            },
            {
                name: wTrans("February"),
                value: "02",
            },
            {
                name: wTrans("March"),
                value: "03",
            },
            {
                name: wTrans("April"),
                value: "04",
            },
            {
                name: wTrans("May"),
                value: "05",
            },
            {
                name: wTrans("June"),
                value: "06",
            },
            {
                name: wTrans("July"),
                value: "07",
            },
            {
                name: wTrans("August"),
                value: "08",
            },
            {
                name: wTrans("September"),
                value: "09",
            },
            {
                name: wTrans("October"),
                value: 10,
            },
            {
                name: wTrans("November"),
                value: 11,
            },
            {
                name: wTrans("December"),
                value: 12,
            },
        ];

        const months = ref(defaultMonthsData);

        // removes any other month value which is not default
        // this is needed because we only want either the default month values or the custom values that were entered
        // in syncFormResetPatternAndResetPattern
        const removeAllExceptDefault = () => {
            setTimeout(() => {
                if (
                    month.value.length > 1 &&
                    month.value.some((month) => month.value === "*")
                ) {
                    month.value = month.value.filter((item) =>
                        defaultMonthsData.some(
                            (defaultMonth) => defaultMonth.value == item.value
                        )
                    );
                }
            }, 1);
        };

        const onChange = (event) => {
            resetPattern.value = event.target.value;
        };

        // calculates the next 5 scheduled date strings
        const nextSchedulesDates = computed(() => {
            let schedulesDates = [];
            try {
                schedulesDates = cronjsMatcher.getFutureMatches(
                    resetPattern.value,
                    {
                        hasSeconds: true,
                        startAt: new Date().toISOString(),
                    }
                );
            } catch (e) {
                console.log(e);
            }
            return schedulesDates;
        });

        // creates a crontab expression based on the second, minute, dayOfMonth, month and dayOfWeek
        const calculateResetPattern = () => {
            // since we are using double digit number strings we must convert them to integer and also check for NaN
            let expression = `${
                isNaN(+second.value) ? second.value : +second.value
            } ${isNaN(+minute.value) ? minute.value : +minute.value} ${
                isNaN(+hour.value) ? hour.value : +hour.value
            } ${
                isNaN(+dayOfMonth.value) ? dayOfMonth.value : +dayOfMonth.value
            } `;
            // month will be an array og objects so we fetch the value from it and join it with "," if values are mutliple

            let months = month.value;

            if (months.length > 1) {
                months = months.filter((month) => month.value != "*");
            }

            months = months.map((month) =>
                isNaN(+month.value) ? month.value : +month.value
            );
            expression += months.join(",");
            expression += " " + dayOfWeek;
            try {
                parse(expression, { hasSeconds: true });
                hasError.value = false;
            } catch (e) {
                hasError.value = true;
                alert(e.message);
            }
            resetPattern.value = expression;
        };

        // set the correct values for second, minute, hour, dayOfMonth and month based on the crontab expression string received
        // from the formResetPattern prop
        const syncFormResetPatternAndResetPattern = () => {
            if (!!props.formResetPattern) {
                const tokens = props.formResetPattern.split(" ");
                second.value = tokens[0] ?? second.value;
                second.value =
                    second.value < 10 ? "0" + second.value : second.value;
                minute.value = tokens[1] ?? minute.value;
                minute.value =
                    minute.value < 10 ? "0" + minute.value : minute.value;
                hour.value = tokens[2] ?? hour.value;
                hour.value = hour.value < 10 ? "0" + hour.value : hour.value;
                dayOfMonth.value = tokens[3] ?? dayOfMonth.value;
                // check if the 4th token which represents month contains digits and commas only
                // if yes then split them by "," and map the month value on the months array
                if (/^[\d,]+$/.test(tokens[4])) {
                    month.value = tokens[4].split(",").map((month) => {
                        return {
                            ...months.value.find((mon) => +mon.value == month),
                        };
                    });
                } else {
                    // if the month value in token[4] is something other then digits or comma separated digits
                    // then add the create an object for the patterns and add that to months array and set the month to
                    // the newly created object
                    // we do this because we cannot map months from patterns other than comma separated digits
                    if (
                        !months.value.includes(
                            (month) => month.value == tokens[4]
                        ) &&
                        tokens[4]
                    ) {
                        months.value = [
                            ...months.value,
                            { name: 'Every Month', value: tokens[4] },
                        ];
                    }
                    month.value = [{ name: 'Every Month', value: tokens[4] }];
                }
            }
        };

        // watch for change in second, minute, hour, dayOfMonth and month and then call 'calculateResetPattern'
        watch([second, minute, hour, dayOfMonth, month], () => {
            calculateResetPattern();
        });

        // watch for change in formResetPattern to compute the reset patterns from the string
        watch(
            () => props.formResetPattern,
            () => syncFormResetPatternAndResetPattern()
        );

        watch(resetPattern, (val) => {
            try {
                parse(val, { hasSeconds: true });
                hasError.value = false;
            } catch (e) {
                hasError.value = true;
                alert(e.message);
            }
        });

        onMounted(() => {
            // compute 'calculateResetPattern' and 'syncFormResetPatternAndResetPattern' on mount
            calculateResetPattern();
            syncFormResetPatternAndResetPattern();
        });

        return {
            second,
            minute,
            hour,
            dayOfMonth,
            month,
            resetPattern,
            secondsData,
            minutesData,
            hoursData,
            dayOfMonthsData,
            months,
            nextSchedulesDates,
            removeAllExceptDefault,
            onChange,
            hasError,
        };
    },
    components: {
        TextInput,
        SelectInput,
        MultiSelectInput,
    },
};
</script>

<style scoped></style>

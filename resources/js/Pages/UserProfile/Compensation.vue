<template>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $t("Compensation") }}</h3>
        </div>
        <div class="card-body">
            <div class="grid items-center grid-cols-2 gap-6">
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            v-model="data.compensationType"
                            :error="errors.compensationType"
                            class=""
                            :label="$t('Compensation Type')"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <number-input
                            @inputEvent="calculateGrossHourlySalary"
                            v-model="data.grossMonthlySalary"
                            :error="errors.grossMonthlySalary"
                            class=""
                            :label="$t('Gross Monthly Salary')"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <number-input
                            v-model="data.grossHourlySalary"
                            @inputEvent="calculateGrossMonthlySalary"
                            :error="errors.grossHourlySalary"
                            class=""
                            :label="$t('Gross Hourly Salary')"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">{{ $t("Bonus") }}</h3>
        </div>
        <div class="card-body">
            <div v-for="(bonus, index) in data.bonus" :key="index" class="">
                <h3 class="card-title">{{ $t("Level") }} {{ index }}</h3>
                <div class="grid items-center grid-cols-5 gap-4 mt-5 mb-3">
                    <div class="w-full col-span-3">
                        <div class="form-group">
                            <select-input
                                v-model="bonus.targetType"
                                :error="errors.targetType"
                                label="Target Type"
                                class=""
                            >
                                <option value="consulting_individual_value">
                                    {{
                                        $t("Consulting Individual Target Value")
                                    }}
                                </option>
                                <option value="consulting_team_value">
                                    {{ $t("Consulting Team Target Value") }}
                                </option>
                                <option value="sale_individual_value">
                                    {{ $t("Sales Individual Target Value") }}
                                </option>
                                <option value="sale_team_value">
                                    {{ $t("Sales Team Target Value") }}
                                </option>
                                <option value="service_individual_value">
                                    {{ $t("Service Individual Target Value") }}
                                </option>
                                <option value="service_team_value">
                                    {{ $t("Service Team Target Value") }}
                                </option>
                            </select-input>
                        </div>
                    </div>
                    <div
                        class="w-full col-span-2"
                        v-if="
                            bonus.targetType === `consulting_individual_value`
                        "
                    >
                        <div class="grid items-center grid-cols-3 gap-3">
                            <div class="w-full">
                                <div class="form-group">
                                    <number-input
                                        class=""
                                        :label="$t('Half Year (h)')"
                                        :showPrefix="false"
                                        v-model="bonus.halfYear"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <number-input
                                        class=""
                                        :label="$t('Year (h)')"
                                        v-model="bonus.year"
                                        :showPrefix="false"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <number-input
                                        class=""
                                        :label="$t('Bonus Faktor')"
                                        v-model="bonus.bonusFaktor"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        v-else-if="
                            bonus.targetType == 'service_individual_value' ||
                            bonus.targetType == 'service_team_value'
                        "
                        class="w-full col-span-2"
                    >
                        <div class="grid items-center grid-cols-3 gap-3">
                            <div
                                class="w-full"
                                v-if="bonus.goal == 'cross_selling_year'"
                            >
                                <div class="form-group">
                                    <number-input
                                        class=""
                                        :label="$t('€')"
                                        v-model="bonus.percentNumber"
                                    />
                                </div>
                            </div>
                            <div class="w-full" v-else>
                                <div class="form-group">
                                    <text-input
                                        :type="`number`"
                                        v-model="bonus.percentNumber"
                                        :error="errors.percentNumber"
                                        class=""
                                        :label="$t('Percent')"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <select-input
                                        v-model="bonus.goal"
                                        :error="errors.goal"
                                        label="Goal"
                                        class=""
                                    >
                                        <option value="ticket_solution_rate">
                                            {{ $t("Ticket Solution Rate") }}
                                        </option>
                                        <option
                                            value="customer_satisfaction_rate"
                                        >
                                            {{
                                                $t(
                                                    "Customer Satisfaction score"
                                                )
                                            }}
                                        </option>
                                        <option value="cross_selling_year">
                                            Cross Selling Sales/Year
                                        </option>
                                    </select-input>
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <number-input
                                        class=""
                                        :label="$t('Bonus Faktor')"
                                        v-model="bonus.bonusFaktor"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="w-full col-span-2">
                        <div class="form-group">
                            <text-input
                                v-model="bonus.targetValue"
                                :error="errors.targetValue"
                                class=""
                                :label="$t('Target Value')"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">{{ $t("Pension plan") }}</h3>
        </div>
        <div class="card-body">
            <div class="grid items-center grid-cols-2 gap-6">
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            v-model="data.contractNumber"
                            :error="errors.contractNumber"
                            class=""
                            :label="$t('Contract Number')"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            v-model="data.insuranceCompany"
                            :error="errors.insuranceCompany"
                            class=""
                            :label="$t('Insurance Company')"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            v-model="data.amountMonthly"
                            :error="errors.amountMonthly"
                            class=""
                            :label="$t('Amount Monthly')"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">{{ $t("Illness Leaves") }}</h3>
        </div>
        <div class="card-body">
            <div class="grid items-center grid-cols-2 gap-6">
                <div class="w-full">
                    <div class="form-group">
                        <DateInput
                            v-model="form.startDate"
                            :required="true"
                            @update:model-value="calculateDaysOffAtATime"
                            class=""
                            :label="$t('Start Date')"
                            :error="errors.startDate"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <DateInput
                            @update:model-value="calculateDaysOffAtATime"
                            v-model="form.endDate"
                            :required="true"
                            class=""
                            :label="$t('End Date')"
                            :error="errors.endDate"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            v-model="form.requiredVacationDays"
                            :error="errors.requiredVacationDays"
                            type="number"
                            :min="1"
                            :max="differenceInDays"
                            class=""
                            :label="$t('Days')"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="checkbox-group">
                        <input
                            class="checkbox-input"
                            type="checkbox"
                            v-model="addNotes"
                        />
                        <label for="" class="checkbox-label">{{
                            $t("Add Notes")
                        }}</label>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <TextareaInput
                            v-if="addNotes"
                            v-model="form.notes"
                            :error="errors.notes"
                            class=""
                            :label="$t('Notes')"
                        />
                    </div>
                </div>
            </div>
            <div class="flex items-center mb-3">
                <button
                    @click.prevent="createIllnessLeave"
                    class="secondary-btn"
                >
                    {{ $t("Create") }}
                </button>
            </div>
            <div class="table-responsive">
                <table class="doc-table">
                    <tr class="text-left">
                        <th class="">{{ $t("Start Date") }}</th>
                        <th class="">{{ $t("End Date") }}</th>
                        <th class="">{{ $t("Days") }}</th>
                        <th class="">{{ $t("Notes") }}</th>
                        <th class="">{{ $t("Action") }}</th>
                    </tr>
                    <tr
                        v-for="leave in illnessLeaves?.data ?? []"
                        :key="leave.id"
                        class="hover:bg-gray-100 focus-within:bg-gray-100"
                    >
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{
                                    $dateFormatter(
                                        leave.startDate,
                                        globalLanguage
                                    )
                                }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{
                                    $dateFormatter(
                                        leave.endDate,
                                        globalLanguage
                                    )
                                }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ leave.days }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500 input-box-length"
                            >
                                {{ leave.notes }}
                            </a>
                        </td>

                        <td class="">
                            <div data-v-a756b9e3="" class="flex gap-2">
                                 <a @click='editIllnessLeaveModel(leave)' class="cursor-pointer">
                                    <svg data-v-a756b9e3="" class="svg-inline--fa fa-pen-to-square" aria-hidden="true" focusable="false" data-prefix="far" data-icon="pen-to-square" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path class="" fill="currentColor" d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152V424c0 48.6 39.4 88 88 88H360c48.6 0 88-39.4 88-88V312c0-13.3-10.7-24-24-24s-24 10.7-24 24V424c0 22.1-17.9 40-40 40H88c-22.1 0-40-17.9-40-40V152c0-22.1 17.9-40 40-40H200c13.3 0 24-10.7 24-24s-10.7-24-24-24H88z"></path></svg>
                                    </a>
                                    
                                    <a  @click.stop="deleteIllnessLeave(leave.id)">
                                        <svg data-v-a756b9e3="" class="svg-inline--fa fa-trash-can" aria-hidden="true" focusable="false" data-prefix="far" data-icon="trash-can" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path class="" fill="currentColor" d="M170.5 51.6L151.5 80h145l-19-28.4c-1.5-2.2-4-3.6-6.7-3.6H177.1c-2.7 0-5.2 1.3-6.7 3.6zm147-26.6L354.2 80H368h48 8c13.3 0 24 10.7 24 24s-10.7 24-24 24h-8V432c0 44.2-35.8 80-80 80H112c-44.2 0-80-35.8-80-80V128H24c-13.3 0-24-10.7-24-24S10.7 80 24 80h8H80 93.8l36.7-55.1C140.9 9.4 158.4 0 177.1 0h93.7c18.7 0 36.2 9.4 46.6 24.9zM80 128V432c0 17.7 14.3 32 32 32H336c17.7 0 32-14.3 32-32V128H80zm80 64V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16z">

                                        </path></svg>
                                    </a>
                                </div>
                        </td>
                    </tr>
                    <tr v-if="(illnessLeaves?.data?.length ?? 0) === 0">
                        <td class=" " colspan="4">
                            {{ $t("No illness leaves found") }}.
                        </td>
                    </tr>
                </table>
            </div>
            <div class="flex items-center justify-center mt-4">
                <pagination
                    :limit="10"
                    align="center"
                    :data="illnessLeaves ?? []"
                    @pagination-change-page="changePageOrSearch"
                ></pagination>
            </div>
        </div>
    </div>
    <div class="flex items-center justify-end mt-4">
        <!-- <div @click="backButton()" class="primary-btn cursor-pointer me-3">
                <span class="mr-1">
                    <CustomIcon name="backIcon" />
                </span>
                <span>{{ $t("Back") }}</span>
            </div> -->
        <loading-button
            :loading="isLoading"
            type="submit"
            class="secondary-btn"
        >
            <span class="mr-1">
                <CustomIcon name="nextIcon" />
            </span>
            {{ $t("Next") }}
        </loading-button>
    </div>
    <Modal
        :title="$t(`Edit Illness Leave`)"
        v-if="isIllnessLeaveToogleModel"
        @isIllnessLeaveToogleModel="isIllnessLeaveToogleModel = $event"
        width="50%"
    >
    
        <template #body>
            <div class="card-body">
                <div class="grid items-center grid-cols-2 gap-6">
                <div class="w-full">
                    <div class="form-group">
                        <DateInput
                            v-model="illnessLeave.startDate"
                            :required="true"
                            @update:model-value="calculateDaysOffAtATime"
                            class=""
                            :label="$t('Start Date')"
                            :error="errors.startDate"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <DateInput
                            @update:model-value="calculateDaysOffAtATime"
                            v-model="illnessLeave.endDate"
                            :required="true"
                            class=""
                            :label="$t('End Date')"
                            :error="errors.endDate"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            v-model="illnessLeave.days"
                            :error="errors.days"
                            type="number"
                            :min="1"
                            :max="differenceInDays"
                            class=""
                            :label="$t('Days')"
                        />
                    </div>
                </div>
            
                <div class="w-full">
                    <div class="form-group">
                        <TextareaInput
                            v-model="illnessLeave.notes"
                            :error="errors.notes"
                            class=""
                            :label="$t('Notes')"
                        />
                    </div>
                </div>
            </div>
             
                
            </div>
        </template>
        <template #footer>
            <button @click="updateIllnessLeave" type="button" class="secondary-btn">
               Save
            </button>
            <button
                @click="isIllnessLeaveToogleModel = false"
                type="button"
                class="primary-btn mr-3"
            >
                Cancel
            </button>
        </template>
    </Modal>
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import NumberInput from "@/Components/NumberInput.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import DateInput from "@/Components/DateInput.vue";
import Pagination from "laravel-vue-pagination";
import LoadingButton from "@/Components/LoadingButton.vue";
import { mapGetters } from "vuex";
import SelectInput from "../../Components/SelectInput.vue";
import Modal from "@/Components/EditModal.vue";

export default {
    computed: {
        ...mapGetters(["isLoading"]),
    },
    inject: ["monthlyWorkingHours", "requesterId"],
    components: {
        LoadingButton,
        TextInput,
        DateInput,
        TextareaInput,
        NumberInput,
        Pagination,
        SelectInput,
        Modal
    },
    props: ["data", "errors"],
    data() {
        return {
            differenceInDays: 0,
            isIllnessLeaveToogleModel:false,
            page: 1,
            addNotes: false,
            form: {
                startDate: "",
                endDate: "",
                requiredVacationDays: 0,
                notes: "",
            },
            illnessLeaves: {
                data: [],
                links: [],
            },
            illnessLeave:''
        };
    },
    unmounted() {
        this.$store.commit("errors", {});
    },
    mounted() {
        this.refresh();
    },
    methods: {
        editIllnessLeaveModel(leave){
            
            this.illnessLeave=Object.assign({}, leave);
            this.isIllnessLeaveToogleModel=true;
       
        },
        refresh() {
            this.$store
                .dispatch("vacationRequest/getIllnessLeaves", {
                    userId: this.requesterId,
                })
                .then((res) => {
                    this.illnessLeaves = res?.data ?? this.illnessLeaves;
                });
        },
        async updateIllnessLeave(){

            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("vacationRequest/updateIllnessLeave", {
                    id: this.illnessLeave.id,
                    data: {
                        ...this.illnessLeave,
                    },
                });
                this.refresh();
                this.isIllnessLeaveToogleModel=false;
                
            } catch (e) {
                console.log(e);
            }
        },
       async deleteIllnessLeave(id){
       
        this.$swal({
        title: this.$t("Do you want to delete this record?"),
        text: this.$t("You can't revert your action"),
        type: "warning",
        showCancelButton: true,
        confirmButtonText: this.$t("Yes delete it!"),
        cancelButtonText: this.$t("No"),
        showCloseButton: true,
      }).then(async (result) => {
        if (result.isConfirmed === true) {
        try {
            await this.$store.dispatch("vacationRequest/deleteIllnessLeave", id);
            this.refresh();
        } catch (e) {}
    }
      }); 

        },
        calculateYears(event, bonus) {
            bonus.halfYear = (event.target.value * 6).toFixed(2);
            bonus.year = (event.target.value * 12).toFixed(2);
            bonus.month = event.target.value;
        },
        async changePageOrSearch(page = 1) {
            this.page = page;
            this.$store
                .dispatch("vacationRequest/getIllnessLeaves", {
                    page: this.page,
                    userId: this.requesterId,
                })
                .then((res) => {
                    this.illnessLeaves = res?.data ?? this.illnessLeaves;
                });
        },
        async calculateDaysOffAtATime() {
            await this.$nextTick();
            var date1 = new Date(this.form.startDate);
            var date2 = new Date(this.form.endDate);

            let weekEndDaysCount = 0;

            if (date1 && date2) {
                let startDate = date1;
                let endDate = date2;
                if (startDate.getDay() == 0 || startDate.getDay() == 6) {
                    weekEndDaysCount += 1;
                    startDate = startDate.addDays(1);
                    if (this.matchDates(startDate, endDate)) {
                        if (
                            startDate.getDay() == 0 ||
                            startDate.getDay() == 6
                        ) {
                            weekEndDaysCount += 1;
                        }
                    }
                }
                if (startDate <= endDate) {
                    let wentInsideTheLoop = false;
                    while (!this.matchDates(startDate, endDate)) {
                        wentInsideTheLoop = true;
                        if (
                            startDate.getDay() == 0 ||
                            startDate.getDay() == 6
                        ) {
                            weekEndDaysCount += 1;
                        }
                        startDate = startDate.addDays(1);
                    }
                    if (
                        this.matchDates(startDate, endDate) &&
                        wentInsideTheLoop
                    ) {
                        if (
                            startDate.getDay() == 0 ||
                            startDate.getDay() == 6
                        ) {
                            weekEndDaysCount += 1;
                        }
                    }
                }
            }

            // To calculate the time difference of two dates
            var differenceInTime = date2.getTime() - date1.getTime();

            this.differenceInDays = 0;
            this.differenceInDays = differenceInTime / (1000 * 3600 * 24);
            this.differenceInDays += 1; // since we are calculating the difference between start and end date, there is always a difference of 1, this offsets that
            this.differenceInDays -= weekEndDaysCount;
            this.form.requiredVacationDays =
                isNaN(this.differenceInDays) || this.differenceInDays < 0
                    ? 0
                    : this.differenceInDays.toFixed(0);
        },
        matchDates(date1, date2) {
            return (
                date1.getDate() == date2.getDate() &&
                date1.getMonth() == date2.getMonth() &&
                date1.getFullYear() == date2.getFullYear()
            );
        },
        async createIllnessLeave() {
            let response = null;
            try {
                response = await this.$store.dispatch(
                    "vacationRequest/createIllnessLeave",
                    {
                        ...this.form,
                        userId: this.requesterId,
                    }
                );
                this.form = {
                    startDate: "",
                    endDate: "",
                    requiredVacationDays: 0,
                    notes: "",
                };
                this.refresh();
            } catch (e) {}
            // send mail
            if (response?.data?.id)
                this.$store
                    .dispatch("vacationRequest/sendMail", response?.data?.id)
                    .catch((e) => console.log(e));
        },
        async calculateGrossHourlySalary() {
            try {
                await this.$nextTick();
                if (!this.monthlyWorkingHours) {
                    this.errors["grossHourlySalary"] =
                        "Please add working hours to calculate the rates";
                    return;
                }
                this.data.grossHourlySalary =
                    +this.data.grossMonthlySalary / +this.monthlyWorkingHours;
            } catch (e) {
                console.log(e);
            }
        },
        async calculateGrossMonthlySalary() {
            try {
                await this.$nextTick();
                if (!this.monthlyWorkingHours) {
                    this.errors["grossMonthlySalary"] =
                        "Please add working hours to calculate the rates";
                    return;
                }
                this.data.grossMonthlySalary =
                    +this.data.grossHourlySalary * +this.monthlyWorkingHours;
            } catch (e) {
                console.log(e);
            }
        },
    },
};
</script>

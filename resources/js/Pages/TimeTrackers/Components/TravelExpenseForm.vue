<template>
    <!-- Display company listing -->
    <div class="grid items-center grid-cols-2 gap-6">
        <div class="w-full">
            <div class="form-group">
                <MultiSelectInput
                    :isDisabled="actionType === 'edit'"
                    :required="true"
                    
                    :textLabel="$t('Customer')"
                    v-if="$can('company.list')"
                    :error="modalErrors.company"
                    @update:model-value="getTravelExpenses"
                    v-model="company"
                    :options="companyOptions"
                    :multiple="false"
                    label="companyName"
                    trackBy="id"
                    moduleName="companies"
                    :key="company?.id"
                />
            </div>
        </div>
        <div class="w-full col-span-2">
            <div class="form-group">
                <!-- Display comment listing -->
                <div v-if="actionType === 'add'" class="pr-6 w-full lg:w-full">
                    <div
                        class="table-responsive"
                    >
                        <table class="doc-table">
                            <thead>
                                <tr>
                                    <th
                                        style="width: 8%"
                                        class="text-left"
                                    >
                                        {{ $t("Select") }}
                                    </th>
                                    <th
                                        style="width: 12%"
                                        class="text-left"
                                    >
                                        {{ $t("Travel Req. No.") }}
                                    </th>
                                    <th
                                        style="width: 15%"
                                        class="text-left"
                                    >
                                        {{ $t("From Date") }}
                                    </th>
                                    <th
                                        style="width: 15%"
                                        class="text-left"
                                    >
                                        {{ $t("To Date") }}
                                    </th>
                                    <th
                                        style="width: 10%"
                                        class="text-left"
                                    >
                                        {{ $t("Arrival Travel Time") }}
                                    </th>
                                    <th
                                        style="width: 10%"
                                        class="text-left"
                                    >
                                        {{ $t("Departure Travel Time") }}
                                    </th>
                                    <th
                                        style="width: 20%"
                                        class="text-left"
                                    >
                                        {{ $t("Description") }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td
                                        v-if="travelExpenses?.length > 0"
                                        class="border-b border-gray-200"
                                        colspan="3"
                                    >
                                        <label class="inline-flex items-center">
                                            <input
                                                type="checkbox"
                                                class="form-checkbox h-5 w-5 text-blue-600"
                                                @change="
                                                    checkAllTravelExpenses(
                                                        $event
                                                    )
                                                "
                                            />
                                            <span class="ml-2">{{
                                                $t("Check all")
                                            }}</span>
                                        </label>
                                    </td>
                                </tr>
                                <tr
                                    :key="index"
                                    v-for="(data, index) in travelExpenses ??
                                    []"
                                >
                                    <td
                                        class="border-b border-gray-200"
                                    >
                                        <label class="inline-flex items-center">
                                            <input
                                                @change="
                                                    toggleTravelExpense(
                                                        $event,
                                                        index
                                                    )
                                                "
                                                type="checkbox"
                                                class="travel-expense-checkbox h-5 w-5 text-blue-600"
                                            />
                                        </label>
                                    </td>
                                    <td
                                        class="border-b border-gray-200"
                                    >
                                        {{ data.travelNumber }}
                                    </td>
                                    <td
                                        class="border-b border-gray-200"
                                    >
                                        {{
                                            $dateFormatter(
                                                data.fromDate,
                                                globalLanguage
                                            )
                                        }}
                                    </td>
                                    <td
                                        class="border-b border-gray-200"
                                    >
                                        {{
                                            $dateFormatter(
                                                data.toDate,
                                                globalLanguage
                                            )
                                        }}
                                    </td>
                                    <td
                                        class="border-b border-gray-200"
                                    >
                                        {{
                                            $formatter(
                                                data.fromDiscrepancy,
                                                globalLanguage,
                                                "EUR",
                                                true
                                            )
                                        }}
                                    </td>
                                    <td
                                        class="border-b border-gray-200"
                                    >
                                        {{
                                            $formatter(
                                                data.toDiscrepancy,
                                                globalLanguage,
                                                "EUR",
                                                true
                                            )
                                        }}
                                    </td>
                                    <td
                                        class="border-b border-gray-200"
                                    >
                                        {{ data.description }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div
                            v-if="this.modalErrors?.selectedTravelExpenses"
                            class="form-error"
                        >
                            {{ this.modalErrors.selectedTravelExpenses }}
                        </div>
                        <div
                            v-if="this.errors?.customerEndTime"
                            class="p-3 form-error"
                        >
                            {{ this.errors.customerEndTime }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full col-span-2" v-if="actionType === 'edit'">
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
        <div class="w-full" v-if="actionType === 'edit'">
            <div class="form-group">
                <!-- Time spent on the given task, if edit and not 'new entry' accounted time needs to be disabled -->
                <number-input
                    
                    :isReadonly="true"
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
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import NumberInput from "@/Components/NumberInput.vue";
import TextAreaInput from "@/Components/TextareaInput.vue";
import { mapGetters } from "vuex";

export default {
    props: [
        "filterCompany",
        "modalErrors",
        "userId",
        "actionType",
        "date",
        "timeTrackerCompanyEditData",
    ],
    components: {
        MultiSelectInput,
        NumberInput,
        TextAreaInput,
    },
    computed: {
        ...mapGetters("companies", ["companies"]),
    },
    data() {
        return {
            moduleId: "",
            description: "",
            accountedTime: "0.00",
            company: "",
            companyOptions: [],
            displayCommentListing: false,
            recordId: "",
            ticket: "",
            ticketOptions: [],
            internalNote: "",
            isGoodwill: false,
            travelExpenses: [],
            selectedTravelExpenses: [],
            timeTrackerCompanyData: [],
        };
    },
    async mounted() {
        // get companies listing
        this.getCompanies();
        if (this.actionType === "edit") {
            this.moduleId = this.timeTrackerCompanyEditData.moduleId;
            this.description = this.timeTrackerCompanyEditData.description;
            this.internalNote = this.timeTrackerCompanyEditData.internalNote;
            this.accountedTime = this.timeTrackerCompanyEditData.time;
            this.isGoodwill =
                this.timeTrackerCompanyEditData.isGoodwill == 1 ? true : false;
            this.company = this.timeTrackerCompanyEditData.company;
        }
    },
    methods: {
        /**
         * get companies listing
         */
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
        /**
         * selects and adds all travel expense entries to selectedTravelExpenses
         * @param {event} input event
         */
        checkAllTravelExpenses(event) {
            this.selectedTravelExpenses = event.target.checked
                ? this.travelExpenses ?? []
                : [];

            const checkboxes = document.querySelectorAll(
                ".travel-expense-checkbox"
            );
            checkboxes.forEach((checkbox) => {
                checkbox.checked = event.target.checked;
            });
        },
        /**
         * adds the checked travel expense entry to selectedTravelExpenses
         * @param {event} input event
         * @param {index} index of the travel expense entry
         */
        toggleTravelExpense(event, index) {
            if (event.target.checked) {
                this.selectedTravelExpenses.push(this.travelExpenses?.[index]);
            } else {
                const indexToRemove = this.selectedTravelExpenses.indexOf(
                    this.travelExpenses?.[index]
                );
                if (indexToRemove > -1) {
                    // create a new array that excludes the element to remove
                    this.selectedTravelExpenses = [
                        ...this.selectedTravelExpenses.slice(0, indexToRemove),
                        ...this.selectedTravelExpenses.slice(indexToRemove + 1),
                    ];
                }
            }
        },
        /**
         * gets the travel expenses listing
         */
        async getTravelExpenses() {
            await this.$nextTick();
            try {
                const response = await this.$store.dispatch(
                    "travelExpense/list",
                    {
                        companyId: this.company?.id,
                        approved: true,
                        date: this.date,
                    }
                );
                this.travelExpenses = response?.data?.data ?? [];
            } catch (e) {
                console.log(e);
            }
        },
    },
};
</script>

<style scoped>
.table-layout-fixed {
    table-layout: fixed;
}
</style>

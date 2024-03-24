<template>
    <Modal
        :title="$t('Government')"
        v-if="toggleGovernmentModal"
        @toggleModal="cancelGovernment"
        :classSize="'modal-md'"
    >
        <template #body>
            <div class="grid items-center grid-cols-2 gap-6">
                <div class="w-full">
                    <div class="form-group">
                        <!-- Define the type: ticket/task -->
                        <select-input
                            v-model="timeTrackerGovernmentTemp.type"
                            :key="timeTrackerGovernmentTemp.type"
                            class=""
                            :label="$t('Type')"
                            :required="true"
                            :error="modalErrors.governmentType"
                            @update:model-value="typeSelected"
                        >
                            <option value="task">
                                {{ $t("Task") }}
                            </option>
                            <option value="break">
                                {{ $t("Break") }}
                            </option>
                            <option value="vacation">
                                {{ $t("Vacation") }}
                            </option>
                            <option value="illness">
                                {{ $t("Illness") }}
                            </option>
                            <option value="internal">
                                {{ $t("Internal") }}
                            </option>
                            <option value="training">
                                {{ $t("Training") }}
                            </option>
                            <option value="take-from-overdue">
                                {{ $t("Take from overdue") }}
                            </option>
                            <option value="sales-presales">
                                {{ $t("Sales/Presales") }}
                            </option>
                            <option value="meeting">
                                {{ $t("Meeting") }}
                            </option>
                            <option value="public-holiday">
                                {{ $t("Public Holiday") }}
                            </option>
                            <option
                                v-if="
                                    actionType === 'edit' &&
                                    (timeTrackerGovernmentTemp.type === '' ||
                                        timeTrackerGovernmentTemp.type ===
                                            'newEntry')
                                "
                                value="newEntry"
                            >
                                {{ $t("New Entry") }}
                            </option>
                            <option
                                v-if="
                                    actionType === 'edit' &&
                                    (timeTrackerGovernmentTemp.type === '' ||
                                        timeTrackerGovernmentTemp.type ===
                                            'ticket')
                                "
                                value="ticket"
                            >
                                {{ $t("Ticket") }}
                            </option>
                            <option
                                v-if="
                                    actionType === 'edit' &&
                                    (timeTrackerGovernmentTemp.type === '' ||
                                        timeTrackerGovernmentTemp.type ===
                                            'ams')
                                "
                                value="ams"
                            >
                                {{ $t("AMS") }}
                            </option>
                            <option
                                v-if="
                                    actionType === 'edit' &&
                                    (timeTrackerGovernmentTemp.type === '' ||
                                        timeTrackerGovernmentTemp.type ===
                                            'travel-expense')
                                "
                                value="travel-expense"
                            >
                                {{ $t("Travel Expense") }}
                            </option>
                        </select-input>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <!-- Display company listing if selected type is "sales/presales" -->
                        <select-input
                            v-if="
                                timeTrackerGovernmentTemp.type ===
                                'sales-presales'
                            "
                            v-model="customerType"
                            class=""
                            :label="$t('Customer Type')"
                            :required="true"
                            :error="modalErrors.customerType"
                            @update:model-value="customerTypeChanged"
                        >
                            <option value="lead">
                                {{ $t("Lead") }}
                            </option>
                            <option value="customer">
                                {{ $t("Customer") }}
                            </option>
                        </select-input>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <!-- Which customer to associate with -->
                        <MultiSelectInput
                            v-if="
                                timeTrackerGovernmentTemp.type ===
                                    'sales-presales' &&
                                customerType &&
                                $can('company.list')
                            "
                            v-model="timeTrackerGovernmentTemp.customerId"
                            :key="timeTrackerGovernmentTemp.customerId"
                            :required="true"
                            class=""
                            :textLabel="$t('Receiver')"
                            :placeholder="$t('Customer')"
                            :options="customerOptions"
                            :multiple="false"
                            label="companyName"
                            trackBy="id"
                            moduleName="companies"
                            :error="errors.customerId"
                            :query="{ customerType: customerType }"
                            :countStore="
                                customerType === 'lead' ? 'leadCount' : 'count'
                            "
                            @asyncSearch="companiesSearch"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <!-- Description of the given type -->
                        <text-area-input
                            v-model="timeTrackerGovernmentTemp.description"
                            :required="true"
                            rows="1"
                            class=""
                            :label="$t('Description')"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <!-- Time spent on the given task -->
                        <text-input
                            @input="startTimeChanged"
                            v-model="timeTrackerGovernmentTemp.startTime"
                            :type="`time`"
                            class=""
                            :required="true"
                            :label="$t('Start Time')"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <number-input
                            :roundNearQuarter="true"
                            :forceLeftBound="true"
                            :showPrefix="false"
                            v-model="timeTrackerGovernmentTemp.hours"
                            :min="0"
                            class=""
                            :required="true"
                            :label="$t('Hours Taken')"
                            @update:model-value="hoursChanged"
                            :errors="modalErrors.governmentHours ?? ''"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            @input="endTimeChanged"
                            v-model="timeTrackerGovernmentTemp.endTime"
                            :type="`time`"
                            class=""
                            :label="$t('End Time')"
                            :error="errors.governmentEndTime"
                        />
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <button
                @click="cancelGovernment"
                type="button"
                class="primary-btn mr-3"
            >
                {{ $t("Cancel") }}
            </button>
            <button
                type="button"
                class="secondary-btn"
                @click="savetimeTrackerGovernment"
            >
                {{ actionType === "add" ? $t("Create") : $t("Save") }}
            </button>
            
        </template>
    </Modal>
</template>

<script>
import Modal from "@/Components/EditModal.vue";
import TextInput from "@/Components/TextInput.vue";
import NumberInput from "@/Components/NumberInput.vue";
import TextAreaInput from "@/Components/TextareaInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";

export default {
    emits: ["toggleGovernmentModal"],
    data() {
        return {
            timeTrackerGovernment: [],
            date: new Date().toISOString().slice(0, 10),
            customerOptions: [],
            customerType: "",
            actionType: "add",
            timeTrackerGovernmentTemp: {
                hours: 0.0,
            },
            modalErrors: {
                projectId: "",
                taskId: "",
                ticketId: "",
                description: "",
                accountedTime: "",
                company: "",
                selectedComments: "",
                moduleId: "",
                totalRemainingHours: "",
                customerType: "",
                selectedTravelExpenses: "",
                ams: "",
                projectId: "",
            },
        };
    },
    computed: {
        ...mapGetters(["errors"]),
        ...mapGetters("auth", ["user"]),
    },
    props: {
        toggleGovernmentModal: {
            type: Boolean,
            default: false,
        },
    },
    components: {
        Modal,
        TextInput,
        NumberInput,
        TextAreaInput,
        SelectInput,
        MultiSelectInput,
    },
    methods: {
        /**
         * hides government modal
         */
        cancelGovernment() {
            this.$emit("toggleGovernmentModal", false);
            this.timeTrackerGovernmentTemp = {
                hours: 0.0,
            };
        },
        /**
         * calculates hours when the startDate/endDate is changed
         * @param {*} value
         */
        async hoursChanged(value) {
            await this.$nextTick();
            this.timeTrackerGovernmentTemp.hours = parseFloat(value).toFixed(2);
            this.timeTrackerGovernmentTemp.endTime = (() => {
                let tokens = (
                    this.timeTrackerGovernmentTemp?.startTime ?? ""
                ).split(":");
                let endTime = "";
                let splitValues = value.toString().split(".");
                let beforeFloatingPoint = splitValues?.[0] ?? "0";
                let afterFloatingPoint = (splitValues?.[1] ?? "0").slice(0, 2);
                let sumHours = +tokens[0] + +beforeFloatingPoint;
                let sumMinutes =
                    +tokens[1] +
                    ([25, 50, 75].includes(+afterFloatingPoint)
                        ? this.quarterEnums[+afterFloatingPoint]
                        : +afterFloatingPoint);
                if (sumMinutes >= 60) {
                    sumMinutes = sumMinutes % 60;
                    sumHours += 1;
                }
                if (sumHours > 24) {
                    endTime = `${sumHours % 24 < 10 ? "0" : ""}${
                        sumHours % 24
                    }:${sumMinutes < 10 ? "0" : ""}${sumMinutes}`;
                } else {
                    endTime = `${sumHours < 10 ? "0" : ""}${sumHours}:${
                        sumMinutes < 10 ? "0" : ""
                    }${sumMinutes}`;
                }
                return endTime;
            })();
        },
        /**
         * sets company options on async search
         * @param {data} data to be set
         */
        companiesSearch(data) {
            this.customerOptions = data?.data;
        },
        /**
         * changes company options when the customerType is changed
         * @param {item} lead/customer
         */
        async customerTypeChanged(item) {
            if (this.timeTrackerGovernmentTemp.type != "sales-presales") {
                this.customerOptions = [];
                return;
            }
            let response = await this.$store.dispatch("companies/list", {
                perPage: 25,
                page: 1,
                customerType: item,
            });
            this.customerOptions = response?.data?.data;
        },
        /**
         * sets time tracker government description automatically if the selected type is "vacation" or "public-holiday"
         * @param {event} input event
         */
        async typeSelected(event) {
            await this.$nextTick();
            const typeEnum = {
                vacation: "Vacation",
                "public-holiday": "Public Holiday",
            };
            if (!!typeEnum[event]) {
                this.timeTrackerGovernmentTemp.description = typeEnum[event];
            } else {
                this.timeTrackerGovernmentTemp.description = "";
            }
            this.modalErrors.governmentType = "";
        },
        async validateAccountingDate() {
            //Check if user profile exists; check if accounting date is set and defined correctly
            const data = {
                date: this.date,
                userId: this.user.id,
            };
            try {
                await this.$store.dispatch(
                    "timeTrackers/validateUserProfile",
                    data
                );
            } catch (err) {
                return false;
            }
            return true;
        },
        validateStartEndTime(startTime, endTime, accountedTime) {
            // Get the number of hours left in the day from the start time
            const startHour = parseInt(startTime?.split(":")?.[0]) || 0; // extract the hour value from the start time string, default to 0 if not present
            const startMinute = parseInt(startTime?.split(":")?.[1]) || 0; // extract the minute value from the start time string, default to 0 if not present
            const diff = 24 - (startHour + startMinute / 60); // calculate the number of hours left in the day

            // Check if the accounted time exceeds the number of hours left in the day
            if (accountedTime > diff) {
                return false;
            }

            // Check if start and end times are present
            if (!startTime || !endTime) {
                return false;
            }

            // Parse start and end times into Date objects
            const start = new Date(`2000-01-01T${startTime}`); // set the date to a fixed value to simplify the code
            const end = new Date(`2000-01-01T${endTime}`);

            // Check if the end time crosses over to the next day
            if (end.getHours() === 0 && end.getMinutes() > 0) {
                return false;
            }

            // Check if the end time is after the start time
            return end > start;
        },
        async fetchTrackerData() {
            return new Promise(async (resolve, reject) => {
                try {
                    const response = await this.$store.dispatch(
                        "timeTrackers/date",
                        {
                            date: this.date,
                            company: "",
                            userId: this.user.id,
                            auth_user_roles: this.user.roles,
                            auth_user_id: this.user.id,
                        }
                    );
                    this.timeTrackerGovernment =
                        response?.data?.timeTrackerGovernment;
                    resolve();
                } catch (e) {
                    reject(e);
                }
            });
        },
        async createTimeTrackerData(payload) {
            await this.fetchTrackerData();
            // if a vacation entry has been logged on this day then show a warning to the user
            if (
                this.timeTrackerGovernment?.some(
                    (item) => item.type === "vacation"
                )
            ) {
                this.$store.commit("flashMessage", {
                    show: true,
                    message: this.$t("You have a vacation on this day."),
                    type: "warning",
                    link: "",
                });
                await new Promise((resolve) =>
                    setTimeout(() => {
                        resolve();
                    }, 2000)
                );
            }
            return new Promise((resolve, reject) => {
                this.$store.commit("isLoading", true);
                this.$store
                    .dispatch("timeTrackers/save", payload)
                    .then((res) => {
                        this.$store.commit("isLoading", false);
                        resolve();
                    })
                    .catch((err) => {
                        this.$store.commit("isLoading", false);
                        reject(err);
                    });
            });
        },
        /**
         * saves time tracker government data
         */
        async savetimeTrackerGovernment() {
            if (!(await this.validateAccountingDate())) {
                this.cancelGovernment();
                return;
            }
            if (!this.timeTrackerGovernmentTemp.type) {
                this.modalErrors.governmentType = "Please enter a type";
                return;
            }
            if (!this.timeTrackerGovernmentTemp.hours) {
                this.modalErrors.governmentHours = "Please enter hours";
                return;
            }
            if (
                !this.validateStartEndTime(
                    this.timeTrackerGovernmentTemp?.startTime,
                    this.timeTrackerGovernmentTemp?.endTime,
                    this.timeTrackerGovernmentTemp?.hours
                )
            ) {
                this.errors.governmentEndTime =
                    "End time cannot be before start time or beyond the current day.";
                return;
            } else {
                this.errors.governmentEndTime = "";
                const payload = {
                    date: this.date,
                    companyId: "",
                    timeTrackerGovernment: {
                        ...this.timeTrackerGovernmentTemp,
                        customerId:
                            this.timeTrackerGovernmentTemp?.customerId?.id,
                    },
                    timeTrackerCompany: null,
                    userId: this.user.id,
                };
                this.createTimeTrackerData(payload);
            }
            this.cancelGovernment();
        },
    },
};
</script>

<style scoped></style>

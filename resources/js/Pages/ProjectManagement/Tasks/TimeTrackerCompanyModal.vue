<template>
    <Modal
        :title="$t('Customer')"
        v-if="toggleCompanyModal"
        @toggleModal="cancelCompany"
        :classSize="'modal-md'"
    >
        <template #body>
            <div class="grid items-center grid-cols-2 gap-6">
                <div class="w-full">
                    <div class="form-group">
                        <select-input
                            :required="true"
                            :isReadOnly="actionType === 'edit'"
                            v-model="moduleType"
                            class=""
                            :label="$t('Type')"
                        >
                            <option v-if="$can('task.list')" value="task">
                                {{ $t("Task") }}
                            </option>
                            <option v-if="$can('ticket.list')" value="ticket">
                                {{ $t("Ticket") }}
                            </option>
                            <option
                                v-if="
                                    $can('application-management-services.list')
                                "
                                value="ams"
                            >
                                {{ $t("AMS") }}
                            </option>
                            <option
                                v-if="$can('travel-expenses.list')"
                                value="travel-expense"
                            >
                                {{ $t("Travel Expense") }}
                            </option>
                            <option value="newEntry">
                                {{ $t("New Entry") }}
                            </option>
                        </select-input>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <task-form
                            v-if="moduleType === 'task'"
                            :filterCompany="filterCompany"
                            :modalErrors="modalErrors"
                            :actionType="actionType"
                            :timeTrackerCompanyEditData="timeTrackerCompanyTemp"
                            ref="taskFormComponent"
                        >
                        </task-form>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <ticket-form
                            v-if="moduleType === 'ticket'"
                            :filterCompany="filterCompany"
                            :modalErrors="modalErrors"
                            :actionType="actionType"
                            :date="date"
                            :timeTrackerCompanyEditData="timeTrackerCompanyTemp"
                            :userId="this.user.id"
                            ref="ticketFormComponent"
                        >
                        </ticket-form>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <ams-form
                            v-if="moduleType === 'ams'"
                            :filterCompany="filterCompany"
                            :modalErrors="modalErrors"
                            :actionType="actionType"
                            :timeTrackerCompanyEditData="timeTrackerCompanyTemp"
                            ref="amsFormComponent"
                        >
                        </ams-form>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <travel-expense-form
                            v-if="moduleType === 'travel-expense'"
                            :filterCompany="filterCompany"
                            :modalErrors="modalErrors"
                            :actionType="actionType"
                            :date="date"
                            :timeTrackerCompanyEditData="timeTrackerCompanyTemp"
                            ref="travelExpenseFormComponent"
                        >
                        </travel-expense-form>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <new-entry-form
                            v-if="moduleType === 'newEntry'"
                            :modalErrors="modalErrors"
                            :actionType="actionType"
                            :filterCompany="filterCompany"
                            :timeTrackerCompanyEditData="timeTrackerCompanyTemp"
                            ref="newEntryFormComponent"
                        >
                        </new-entry-form>
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            
            <button
                @click="cancelCompany"
                type="button"
                class="primary-btn mr-3"
            >
                {{ $t("Cancel") }}
            </button>
            <loading-button
                :loading="isLoading"
                type="button"
                class="secondary-btn"
                @click="savetimeTrackerCompany"
                v-if="moduleType != '' && isCompanyFormShow()"
            >
                {{ actionType === "add" ? $t("Create") : $t("Save") }}
            </loading-button>
        </template>
    </Modal>
</template>

<script>
import Modal from "@/Components/EditModal.vue";
import SelectInput from "@/Components/SelectInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import TicketForm from "@/Pages/TimeTrackers/Components/TicketForm.vue";
import TaskForm from "@/Pages/TimeTrackers/Components/TaskForm.vue";
import AMSForm from "@/Pages/TimeTrackers/Components/AMSForm.vue";
import TravelExpenseForm from "@/Pages/TimeTrackers/Components/TravelExpenseForm.vue";
import NewEntryForm from "@/Pages/TimeTrackers/Components/NewEntryForm.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters("auth", ["user"]),
        ...mapGetters(["isLoading"]),
        modalHasErrors() {
            return (
                this.modalErrors.projectId != "" ||
                this.modalErrors.taskId != "" ||
                this.modalErrors.ticketId != "" ||
                this.modalErrors.description != "" ||
                this.modalErrors.accountedTime != "" ||
                this.modalErrors.company != "" ||
                this.modalErrors.moduleId != "" ||
                this.modalErrors.selectedComments != "" ||
                this.modalErrors.totalRemainingHours != "" ||
                this.modalErrors.selectedTravelExpenses != "" ||
                this.modalErrors.projectId != ""
            );
        },
    },
    emits: ["toggleCompanyModal"],
    props: {
        toggleCompanyModal: {
            type: Boolean,
            default: false,
        },
    },
    mounted() {
        this.fetchTrackerData();
    },
    components: {
        SelectInput,
        TicketForm,
        TaskForm,
        NewEntryForm,
        TravelExpenseForm,
        "ams-form": AMSForm,
        Modal,
        LoadingButton,
    },
    data() {
        return {
            startTime: "08:00:00",
            userId: "",
            date: new Date().toISOString().slice(0, 10),
            timeTrackerCompanyTemp: {
                selectedComments: [],
                time: 0,
            },
            timeTrackerGovernment: [],
            timeTrackerCompany: [],
            filterCompany: {
                id: "all",
                companyName: "All",
            },
            actionType: "add",
            moduleType: "",
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
    methods: {
        validateTasks(item) {
            this.modalErrors.projectId =
                item.projectId == "" ? "Please select project" : "";
            this.modalErrors.taskId =
                item.moduleId == "" ? "Please select task number" : "";
            this.modalErrors.accountedTime =
                !item.isGoodwill && (item.hours == "" || item.hours == "0.00")
                    ? "Please define time"
                    : "";
            this.modalErrors.company =
                item.companyId == "" ? "Customer not selected" : "";
            this.modalErrors.description =
                item.description == "" ? "Description not defined" : "";
        },
        /**
         * hides company modal
         */
        cancelCompany() {
            this.$emit("toggleCompanyModal", false);
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
        validateTickets(item) {
            this.modalErrors.company =
                item.companyId == "" ? "Customer not selected" : "";
            if (this.actionType === "add") {
                this.modalErrors.ticketId =
                    item.ticket == "" ? "Please select ticket number" : "";
                this.modalErrors.selectedComments =
                    item.selectedComments == "" ? "No comments selected" : "";
            } else {
                this.modalErrors.moduleId =
                    item.moduleId == "" ? "Invalid module defined" : "";
            }
        },
        async updateTimeTrackerCompany(timeTrackerCompany) {
            //Inititalize payload
            let payload = {
                id: this.timeTrackerCompanyTemp.id,
                type: this.moduleType,
                ...timeTrackerCompany,
            };
            this.$store.commit("isLoading", true);
            await this.$store
                .dispatch("timeTrackers/updateCompany", payload)
                .then((res) => {
                    this.fetchTrackerData();
                    this.$store.commit("isLoading", false);
                })
                .catch((err) => {
                    this.$store.commit("isLoading", false);
                });
        },
        handleTicketData(startTime) {
            let refTicketComp = this.$refs.ticketFormComponent;

            //Validate data
            this.validateTickets({
                moduleId: refTicketComp.moduleId ?? "",
                ticket: refTicketComp.ticket?.id ?? "",
                companyId: refTicketComp.company?.id ?? "",
                selectedComments: refTicketComp.selectedComments,
            });

            if (this.modalHasErrors !== false) {
                return false;
            }

            if (this.actionType === "edit") {
                //Module id is id of comment not the ticket
                let timeTrackerCompany = {
                    moduleId: refTicketComp.moduleId,
                    description: refTicketComp.description,
                    internalNote: refTicketComp.internalNote,
                    hours: refTicketComp.accountedTime,
                    isGoodwill: refTicketComp.isGoodwill ?? false,
                    companyId: refTicketComp.company?.id ?? "",
                    amsId: refTicketComp?.ams?.id ?? "",
                };
                this.updateTimeTrackerCompany(timeTrackerCompany);
                this.cancelCompany();
                return;
            }

            //Loop selected comments to create an individual record
            refTicketComp.selectedComments.forEach((comment) => {
                let endTime = this.getEndTime(startTime, comment.time);
                let governmentHours = this.diff(startTime, endTime);
                let companyHours = comment.time;
                let isError = !this.validateStartEndTime(
                    startTime,
                    endTime,
                    comment.time
                );

                if (endTime === "Invalid Date") {
                    endTime = "";
                    governmentHours = 0;
                } else if (isError) {
                    endTime = startTime;
                    governmentHours = 0;
                }

                //Check if validation return errors
                if (this.modalHasErrors !== false) {
                    return false;
                }

                let description =
                    "Ticket " + refTicketComp.ticket.ticketNumber + "";
                description += "Description: " + comment.text;

                let timeTrackerCompany = {
                    type: this.moduleType,
                    moduleId: comment.id,
                    description: description,
                    internalNote: refTicketComp.internalNote,
                    hours: companyHours,
                    companyId: refTicketComp.company?.id ?? "",
                    isGoodwill: refTicketComp.isGoodwill ?? false,
                    amsId: refTicketComp?.ams?.id ?? "",
                };
                let timeTrackerGovernment = {
                    type: this.moduleType,
                    description: description,
                    internalNote: refTicketComp.internalNote,
                    startTime: startTime,
                    endTime: endTime,
                    hours: governmentHours,
                    commentId: comment?.id,
                };

                //Inititalize payload
                let payload = {
                    date: this.date,
                    userId: this.user.id,
                    timeTrackerCompany: timeTrackerCompany,
                    timeTrackerGovernment: timeTrackerGovernment,
                };

                //Store data
                this.createTimeTrackerData(payload);

                //Push data in existing dataset
                this.timeTrackerCompany.push(timeTrackerCompany);
                this.timeTrackerGovernment.push(timeTrackerGovernment);
            });

            //Reset modal errors
            Object.keys(this.modalErrors).forEach((key) => {
                this.modalErrors[key] = "";
            });
            //Reset data
            this.cancelCompany();
        },
        /**
         * validates travel expense and sets errors on 'modalErrors'
         * @params {item} the travel expense entry
         */
        validateTravelExpense(item) {
            this.modalErrors.company =
                item.companyId == "" ? "Customer not selected" : "";
            if (this.actionType === "add") {
                this.modalErrors.selectedTravelExpenses = !item
                    .selectedTravelExpenses?.length
                    ? "No travel expenses selected"
                    : "";
            } else {
                this.modalErrors.moduleId =
                    item.moduleId == "" ? "Invalid module defined" : "";
            }
        },
        getEndTime(startTime, hours) {
            //Get end time
            const startDateTime = new Date(`2000-01-01T${startTime}`);
            const duration = parseFloat(String(hours).replace(",", "."));
            const getTime = new Date(
                startDateTime.getTime() + duration * 60 * 60 * 1000
            );

            // Format the end time as a string in the format "hh:mm"
            return getTime.toLocaleTimeString("en-US", {
                hour12: false,
                hour: "2-digit",
                minute: "2-digit",
            });
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
        matchDates(date1, date2) {
            return (
                date1.getUTCDate() == date2.getUTCDate() &&
                date1.getUTCMonth() == date2.getUTCMonth() &&
                date1.getUTCFullYear() == date2.getUTCFullYear()
            );
        },
        /**
         * performs error handling and creates the travel expense company entries
         * @param {startTime} main start time
         */
        async handleTravelExpenseData(startTime) {
            let refTravelExpenseComp = this.$refs.travelExpenseFormComponent;

            //Validate data
            this.validateTravelExpense({
                moduleId: refTravelExpenseComp.moduleId ?? "",
                companyId: refTravelExpenseComp.company?.id ?? "",
                selectedTravelExpenses:
                    refTravelExpenseComp.selectedTravelExpenses,
            });

            if (this.modalHasErrors !== false) {
                return false;
            }

            if (this.actionType === "edit") {
                //Module id is id of comment not the ticket
                let timeTrackerCompany = {
                    moduleId: refTravelExpenseComp.moduleId,
                    description: refTravelExpenseComp.description,
                    internalNote: refTravelExpenseComp.internalNote,
                    hours: refTravelExpenseComp.accountedTime,
                    isGoodwill: refTravelExpenseComp.isGoodwill ?? false,
                    companyId: refTravelExpenseComp.company?.id ?? "",
                };
                this.updateTimeTrackerCompany(timeTrackerCompany);
                this.cancelCompany();
                return;
            }

            //Loop selected travel expenses to create an individual record
            for (
                let i = 0;
                i < (refTravelExpenseComp?.selectedTravelExpenses ?? []).length;
                i++
            ) {
                let travelExpense =
                    refTravelExpenseComp?.selectedTravelExpenses?.[i] ?? {};
                let time = +(travelExpense?.fromDiscrepancy ?? 0);
                let description = `Arrival`;
                let endTime = this.getEndTime(startTime, time);
                let governmentHours = this.diff(startTime, endTime);
                let companyHours = time;
                let isError = !this.validateStartEndTime(
                    startTime,
                    endTime,
                    time
                );

                if (endTime === "Invalid Date") {
                    endTime = "";
                    governmentHours = 0;
                } else if (isError) {
                    endTime = startTime;
                    governmentHours = 0;
                }

                //Check if validation return errors
                if (this.modalHasErrors !== false) {
                    return false;
                }

                let timeTrackerCompany = {
                    type: this.moduleType,
                    moduleId: travelExpense.id,
                    description: description,
                    internalNote: refTravelExpenseComp.internalNote,
                    hours: companyHours,
                    companyId: refTravelExpenseComp.company?.id ?? "",
                    isGoodwill: refTravelExpenseComp.isGoodwill ?? false,
                };
                let timeTrackerGovernment = {
                    type: this.moduleType,
                    description: description,
                    internalNote: refTravelExpenseComp.internalNote,
                    startTime: startTime,
                    endTime: endTime,
                    hours: governmentHours,
                    commentId: refTravelExpenseComp?.id,
                };

                //Inititalize payload
                let payload = {
                    date: this.date,
                    userId: this.user.id,
                    timeTrackerCompany: timeTrackerCompany,
                    timeTrackerGovernment: timeTrackerGovernment,
                };

                //Store data
                await this.createTimeTrackerData(payload);

                //Push data in existing dataset
                this.timeTrackerCompany.push(timeTrackerCompany);
                this.timeTrackerGovernment.push(timeTrackerGovernment);

                // if the fromDate and toDate are same then we must create another entry
                if (
                    this.matchDates(
                        new Date(travelExpense.fromDate),
                        new Date(travelExpense.toDate)
                    )
                ) {
                    //Set start time as end time of last government entry if data exists
                    let newStartTime = this.timeTrackerGovernment.length
                        ? this.timeTrackerGovernment[
                              this.timeTrackerGovernment.length - 1
                          ]?.endTime
                        : this.startTime;
                    let time = +(travelExpense?.toDiscrepancy ?? 0);
                    let description = `Departure`;
                    let endTime = this.getEndTime(newStartTime, time);
                    let governmentHours = this.diff(newStartTime, endTime);
                    let companyHours = time;
                    let isError = !this.validateStartEndTime(
                        newStartTime,
                        endTime,
                        time
                    );

                    if (endTime === "Invalid Date") {
                        endTime = "";
                        governmentHours = 0;
                    } else if (isError) {
                        endTime = startTime;
                        governmentHours = 0;
                    }

                    //Check if validation return errors
                    if (this.modalHasErrors !== false) {
                        return false;
                    }

                    let timeTrackerCompany = {
                        type: this.moduleType,
                        moduleId: travelExpense.id,
                        description: description,
                        internalNote: refTravelExpenseComp.internalNote,
                        hours: companyHours,
                        companyId: refTravelExpenseComp.company?.id ?? "",
                        isGoodwill: refTravelExpenseComp.isGoodwill ?? false,
                    };
                    let timeTrackerGovernment = {
                        type: this.moduleType,
                        description: description,
                        internalNote: refTravelExpenseComp.internalNote,
                        startTime: newStartTime,
                        endTime: endTime,
                        hours: governmentHours,
                        commentId: refTravelExpenseComp?.id,
                    };

                    //Inititalize payload
                    let payload = {
                        date: this.date,
                        userId: this.user.id,
                        timeTrackerCompany: timeTrackerCompany,
                        timeTrackerGovernment: timeTrackerGovernment,
                    };

                    //Store data
                    await this.createTimeTrackerData(payload);

                    //Push data in existing dataset
                    this.timeTrackerCompany.push(timeTrackerCompany);
                    this.timeTrackerGovernment.push(timeTrackerGovernment);
                }
            }

            //Reset modal errors
            Object.keys(this.modalErrors).forEach((key) => {
                this.modalErrors[key] = "";
            });
            //Reset data
            this.cancelCompany();
        },
        diff(startTime, endTime) {
            let start = startTime;
            let end = endTime;

            start = start?.split(":") ?? "";
            end = end?.split(":") ?? "";
            var startDate = new Date(0, 0, 0, start[0], start[1], 0);
            var endDate = new Date(0, 0, 0, end[0], end[1], 0);
            var diff = endDate.getTime() - startDate.getTime();
            var hours = diff / 1000 / 60 / 60;
            diff -= hours * 1000 * 60 * 60;
            return isNaN(hours) ? 0 : hours;
        },
        validateNewEntry(item) {
            this.modalErrors.accountedTime =
                item.hours == "" ? "Please define time" : "";
            this.modalErrors.company =
                item.companyId == "" ? "Customer not selected" : "";
            this.modalErrors.projectId = !item.projectId
                ? "Project not selected"
                : "";
            this.modalErrors.description =
                item.description == "" ? "Description not defined" : "";
        },
        /**
         * validates the ams entry to be added and adds the errors to modalErrors if any
         * @param {item} the ams entry to be validated
         */
        validateAMS(item) {
            this.modalErrors.accountedTime =
                !item.isGoodwill && (item.hours == "" || item.hours == "0.00")
                    ? "Please define time"
                    : "";
            this.modalErrors.company =
                item.companyId == "" ? "Customer not selected" : "";
            this.modalErrors.description =
                item.description == "" ? "Description not defined" : "";
        },
        /**
         * saves time tracker company data
         */
        async savetimeTrackerCompany() {
            // reset modal errors
            this.modalErrors = {
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
            };
            if (!(await this.validateAccountingDate())) {
                this.cancelCompany();
                return;
            }

            //Set start time as end time of last government entry if data exists
            let startTime = this.timeTrackerGovernment.length
                ? this.timeTrackerGovernment[
                      this.timeTrackerGovernment.length - 1
                  ]?.endTime
                : this.startTime;
            let endTime = "";

            if (this.moduleType === "ticket") {
                this.handleTicketData(startTime);
                return;
            }

            if (this.moduleType === "travel-expense") {
                this.handleTravelExpenseData(startTime);
                return;
            }

            //Get time tracker company data
            let timeTrackerCompany = {};
            if (this.moduleType === "task") {
                timeTrackerCompany = {
                    moduleId: this.$refs.taskFormComponent.task.id ?? "",
                    description: this.$refs.taskFormComponent.description,
                    internalNote: this.$refs.taskFormComponent.internalNote,
                    hours: this.$refs.taskFormComponent.accountedTime,
                    isGoodwill:
                        this.$refs.taskFormComponent.isGoodwill ?? false,
                    companyId: this.$refs.taskFormComponent.company?.id ?? "",
                };
                this.validateTasks({
                    ...timeTrackerCompany,
                    projectId: this.$refs.taskFormComponent.project.id ?? "",
                });

                endTime = this.getEndTime(startTime, timeTrackerCompany.hours);
            } else if (this.moduleType === "newEntry") {
                timeTrackerCompany = {
                    moduleId: "",
                    description: this.$refs.newEntryFormComponent.description,
                    internalNote: this.$refs.newEntryFormComponent.internalNote,
                    hours: this.$refs.newEntryFormComponent.accountedTime,
                    isGoodwill:
                        this.$refs.newEntryFormComponent.isGoodwill ?? false,
                    companyId:
                        this.$refs.newEntryFormComponent.company?.id ?? "",
                    projectId:
                        this.$refs.newEntryFormComponent.project?.id ?? "",
                };
                endTime = this.getEndTime(startTime, timeTrackerCompany.hours);
                this.validateNewEntry(timeTrackerCompany);
            } else if (this.moduleType === "ams") {
                timeTrackerCompany = {
                    moduleId: this.$refs.amsFormComponent.ams?.id ?? "",
                    description: this.$refs.amsFormComponent.description,
                    internalNote: this.$refs.amsFormComponent.internalNote,
                    hours: this.$refs.amsFormComponent.accountedTime,
                    isGoodwill: this.$refs.amsFormComponent.isGoodwill ?? false,
                    companyId: this.$refs.amsFormComponent.company?.id ?? "",
                    totalRemainingHours:
                        this.$refs.amsFormComponent.totalRemainingHours ?? "",
                };
                endTime = this.getEndTime(startTime, timeTrackerCompany.hours);
                this.validateAMS(timeTrackerCompany);
            }

            //Check if validation return errors
            if (this.modalHasErrors !== false) {
                return;
            }
            //Inititalize payload
            let payload = {
                date: this.date,
                userId: this.user.id,
            };

            //Parse time tracker company to save payload
            payload.timeTrackerCompany = {
                type: this.moduleType,
                ...timeTrackerCompany,
            };

            //Parse time tracker government to save payload
            payload.timeTrackerGovernment = {
                type: this.moduleType,
                description: timeTrackerCompany.description,
                internalNote: timeTrackerCompany.internalNote,
                startTime: startTime,
                endTime: endTime,
                hours: timeTrackerCompany.hours,
            };

            //If time crosses 24 hours, set end time to 0 and hours to 0
            if (
                !this.validateStartEndTime(
                    startTime,
                    endTime,
                    timeTrackerCompany.hours
                )
            ) {
                const startHour = parseInt(startTime?.split(":")?.[0]) || 0; // extract the hour value from the start time string, default to 0 if not present
                const startMinute = parseInt(startTime?.split(":")?.[1]) || 0; // extract the minute value from the start time string, default to 0 if not present
                const diff = 24 - (startHour + startMinute / 60); // calculate the number of hours left in the day
                endTime = this.getEndTime(startTime, diff); // get the end time
                payload.timeTrackerGovernment.endTime = endTime;
            }

            //Save "task" & "new entry" data
            if (this.actionType === "add") {
                this.createTimeTrackerData(payload);
            } else if (this.actionType === "edit") {
                this.updateTimeTrackerCompany(timeTrackerCompany);
            }

            //Reset modal errors
            Object.keys(this.modalErrors).forEach((key) => {
                this.modalErrors[key] = "";
            });

            //Reset data
            this.cancelCompany();
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
                    this.timeTrackerGovernment = this.timeTrackerGovernment.map(
                        (trackerData) => {
                            return {
                                ...trackerData,
                                hours: this.diff(
                                    trackerData.startTime,
                                    trackerData.endTime
                                ),
                            };
                        }
                    );
                    this.timeTrackerCompany =
                        response?.data?.timeTrackerCompany;
                    let timeTrackerCompany = [];
                    // map the time tracker company data
                    for (let i = 0; i < this.timeTrackerCompany.length; i++) {
                        const trackerData = this.timeTrackerCompany[i];
                        // find the company from the companies listing
                        let company = this.companies?.data?.find(
                            (company) => company?.id == trackerData.company
                        );
                        // if the compnay is not found then fetch individually using the show API
                        if (!company) {
                            const response = await this.$store.dispatch(
                                "companies/show",
                                trackerData.company
                            );
                            company = response?.data?.modelData ?? {};
                        }
                        trackerData.company = company;
                        // map the moduleId
                        trackerData.moduleId =
                            trackerData.moduleType === "ticket"
                                ? this.tickets?.data?.find(
                                      (ticket) =>
                                          ticket.id == trackerData.moduleId
                                  ) ?? trackerData.moduleId
                                : this.tasks?.data?.find(
                                      (task) => task.id == trackerData.moduleId
                                  ) ?? trackerData.moduleId;
                        timeTrackerCompany[i] = trackerData;
                    }
                    // set the mapped timeTrackerCompany to timeTrackerCompany state
                    this.timeTrackerCompany = [...timeTrackerCompany];
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
                        this.fetchTrackerData();
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
         * returns true/false if the correct/wrong moduleType is selected
         */
        isCompanyFormShow() {
            if (this.moduleType == "ticket" && this.tickets != "") {
                return true;
            } else if (this.moduleType == "task" && this.tasks != "") {
                return true;
            } else if (
                this.moduleType == "travel-expense" &&
                this.travelExpenses != ""
            ) {
                return true;
            } else if (this.moduleType == "newEntry") {
                return true;
            } else if (this.moduleType == "ams") {
                return true;
            } else {
                return false;
            }
        },
    },
};
</script>

<style scoped></style>

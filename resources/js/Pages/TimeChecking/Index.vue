<template>
    <PageHeader :items="breadcrumbItems" />
    <div class="card my-5">
        <div class="card-body">
            <div class="grid items-center grid-cols-4 gap-6 my-5">
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            v-model="form.userId"
                            :key="form.userId"
                            :error="errors['userId']"
                            :options="userProfiles?.data ?? []"
                            :multiple="false"
                            :text-label="$t('Person')"
                            label="employee"
                            trackBy="userId"
                            moduleName="userProfile"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            v-model="form.companyId"
                            :key="form.companyId"
                            :textLabel="$t('Company')"
                            :multiple="false"
                            :options="companies?.data ?? []"
                            label="companyName"
                            trackBy="id"
                            moduleName="companies"
                            :error="errors.companyId"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <SelectInput
                            :label="$t('Type')"
                            v-model="form.type"
                            :key="form.type"
                            :error="errors.type"
                        >
                            <option value="">{{ $t("All") }}</option>
                            <option value="task">{{ $t("Task") }}</option>
                            <option value="ticket">{{ $t("Ticket") }}</option>
                            <option value="ams">{{ $t("AMS") }}</option>
                            <option value="newEntry">{{ $t("New Entry") }}</option>
                        </SelectInput>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <SelectInput
                            :label="$t('Status')"
                            v-model="form.status"
                            :key="form.status"
                            :error="errors.status"
                        >
                            <option value="">{{ $t("All") }}</option>
                            <option :value="1">{{ $t("Accepted") }}</option>
                            <option :value="0">{{ $t("Rejected") }}</option>
                            <option :value="2">{{ $t("To Do") }}</option>
                        </SelectInput>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap">
                <div
                    style="border-right: 1px solid gray"
                    class="flex justify-center items-center flex-col pb-8 pr-6 w-full lg:w-1/3"
                >
                    <h1 class="font-medium text-5xl">
                        {{
                            $formatter(
                                timeChecking?.totalTime ?? 0,
                                globalLanguage,
                                "EUR",
                                true, // not a currency
                                2, // minimum fraction digits
                                2 // maximum fraction digits
                            )
                        }}
                        {{ $t("hours") }}
                    </h1>
                    <p class="mt-2">{{ $t("Total Time") }}</p>
                </div>
                <div
                    class="flex justify-center items-center flex-col pb-8 pr-6 w-full lg:w-1/3"
                >
                    <h1 class="font-medium text-5xl">
                        {{
                            $formatter(
                                timeChecking?.kulanzTime ?? 0,
                                globalLanguage,
                                "EUR",
                                true, // not a currency
                                2, // minimum fraction digits
                                2 // maximum fraction digits
                            )
                        }}
                        {{ $t("hours") }}
                    </h1>
                    <p class="mt-2">{{ $t("Kulanz Time") }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="w-full doc-table">
            <tr class="text-left font-bold">
                <th
                    @click="sort('date', 'timeTrackerCompanies')"
                    class="pb-4 pt-6 px-6 compact-cell cursor-pointer"
                >
                    {{ $t("Date") }}
                    <font-awesome-icon
                        v-if="form.sortBy === 'date'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
                <th
                    class="pb-4 pt-6 px-6 compact-cell cursor-pointer"
                    @click="sort('module_type', 'timeTrackerCompanies')"
                >
                    {{ $t("Type") }}
                    <font-awesome-icon
                        v-if="form.sortBy === 'module_type'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
                <th
                    class="pb-4 pt-6 px-6 compact-cell cursor-pointer"
                    @click="sort('Company.companyName', 'timeTrackerCompanies')"
                >
                    {{ $t("Company") }}
                    <font-awesome-icon
                        v-if="form.sortBy === 'Company.companyName'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
                <th
                    @click="
                        sort('userProfileData.firstName', 'timeTrackerCompanies')
                    "
                    class="pb-4 pt-6 px-6"
                >
                    {{ $t("Person/Reference") }}
                    <font-awesome-icon
                        v-if="form.sortBy === 'userProfileData.firstName'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
                <th
                    class="pb-4 pt-6 px-6 cursor-pointer text-center text-hover"
                    @click="sort('description', 'timeTrackerCompanies')"
                >
                    {{ $t("Activity Description") }}
                    <font-awesome-icon
                        v-if="form.sortBy === 'description'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
                <th
                    class="pb-4 pt-6 px-6 compact-cell cursor-pointer"
                    @click="sort('time', 'timeTrackerCompanies')"
                >
                    <p>{{ $t("Quantity") }} / {{ $t("Unit") }}</p>
                    <font-awesome-icon
                        v-if="form.sortBy === 'time'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
                <th
                    @click="sort('isGoodwill', 'timeTrackerCompanies')"
                    class="pb-4 pt-6 px-6 compact-cell cursor-pointer"
                >
                    {{ $t("Goodwill") }}
                    <font-awesome-icon
                        v-if="form.sortBy === 'isGoodwill'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
                <th class="pb-4 pt-6 px-6 compact-cell">{{ $t("Action") }}</th>
            </tr>
            <tr v-for="(entry, index) in timeChecking?.data" :key="entry.id">
                <td
                    :style="`border-left: ${
                        entry.status === null ? '0' : '5'
                    }px solid ${entry.status == 0 ? 'red' : 'green'}`"
                    class="border-t"
                >
                    <a
                        href="javascript:void(0)"
                        class="flex items-center px-6 py-4 focus:text-indigo-500"
                    >
                        {{ $dateFormatter(entry.date, globalLanguage) }}
                    </a>
                </td>
                <td class="border-t">
                    <a
                        href="javascript:void(0)"
                        class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >{{ entry.type }}</a
                    >
                </td>
                <td class="border-t">
                    <a
                        href="javascript:void(0)"
                        class="flex items-center px-6 text-hover-100 py-4 focus:text-indigo-500"
                        >{{ entry.companyName }}</a
                    >
                </td>
                <td class="border-t">
                    <a
                        href="javascript:void(0)"
                        class="flex  items-center px-6 py-4 focus:text-indigo-500"
                        >{{ entry.userName }}</a
                    >
                </td>
                <td class="border-t description-cell">
                    <a
                        href="javascript:void(0)"
                        class="flex text-hover items-center px-6 py-4 focus:text-indigo-500"
                        >
                        <span v-html="entry.description"/>
                    </a
                    >
                </td>
                <td class="border-t">
                    <a
                        href="javascript:void(0)"
                        class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >{{
                            $formatter(entry.quantity, globalLanguage, "EUR", true)
                        }}
                        {{ $t("Hours") }}</a
                    >
                </td>
                <td class="border-t">
                    <a
                        href="javascript:void(0)"
                        class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >{{ $t(entry.isGoodwill == 1 ? "Yes" : "No") }}</a
                    >
                </td>
                <td class="border-t">
                    <a
                        v-if="$can(`${$route.meta.permission}.update`)"
                        href="javascript:void(0)"
                        class="flex items-center px-6 py-4 focus:text-indigo-500"
                    >
                        <button @click="editCompanyModal(index)" class="px-2">
                            <font-awesome-icon icon="fa-regular fa-pen-to-square"/>
                        </button>
                        <font-awesome-icon
                            v-if="entry.status === null || entry.status == 0"
                            class="cursor-pointer text-sm text-green-600 mr-2"
                            @click="updateTimeCheckingStatus(entry.id, 1)"
                            :icon="['fa-solid', 'fa-check']"
                        />
                        <font-awesome-icon
                            v-if="entry.status === null || entry.status == 1"
                            @click="updateTimeCheckingStatus(entry.id, 0)"
                            class="cursor-pointer text-sm text-red-600 mr-2"
                            :icon="['fa-solid', 'fa-xmark']"
                        />
                    </a>
                </td>
            </tr>
            <tr v-if="(timeChecking?.data?.length ?? 0) == 0">
                <p class="ml-8">{{ $t("No time checking record found") }}</p>
            </tr>
        </table>
    </div>
    <div style="margin-top: 3rem" class="flex justify-center">
        <pagination
            :limit="10"
            align="center"
            :data="timeChecking"
            @pagination-change-page="changePageOrSearch"
        ></pagination>
        <br />
        <br />
    </div>
    <Modal
        :title="$t('Customer')"
        v-if="toggleEditCompanyModal"
        @toggleModal="cancelCompany"
    >
        <template #body>
            <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                <!-- Define the module type (linked with respective module): ticket/task -->

                <div class="w-full">
                    <select-input
                        :required="true"
                        :isReadOnly="actionType === 'edit'"
                        v-model="moduleType"
                        :key="moduleType"
                        class="pb-8 pr-6 w-1/3"
                        :label="$t('Type')"
                    >
                        <option v-if="$can('task.list')" value="task">
                            {{ $t("Task") }}
                        </option>
                        <option v-if="$can('ticket.list')" value="ticket">
                            {{ $t("Ticket") }}
                        </option>
                        <option
                            v-if="$can('application-management-services.list')"
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
                <task-form
                    v-if="moduleType === 'task'"
                    :filterCompany="filterCompany"
                    :modalErrors="modalErrors"
                    :actionType="actionType"
                    :timeTrackerCompanyEditData="timeTrackerCompanyTemp"
                    ref="taskFormComponent"
                >
                </task-form>

                <ticket-form
                    v-if="moduleType === 'ticket'"
                    :filterCompany="filterCompany"
                    :modalErrors="modalErrors"
                    :actionType="actionType"
                    :date="date"
                    :timeTrackerCompanyEditData="timeTrackerCompanyTemp"
                    :userId="
                        this.userId?.userId ? this.userId?.userId : this.user.id
                    "
                    ref="ticketFormComponent"
                >
                </ticket-form>

                <ams-form
                    v-if="moduleType === 'ams'"
                    :filterCompany="filterCompany"
                    :modalErrors="modalErrors"
                    :actionType="actionType"
                    :timeTrackerCompanyEditData="timeTrackerCompanyTemp"
                    ref="amsFormComponent"
                >
                </ams-form>

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
        </template>
        <template #footer>
            <button
                type="button"
                class="secondary-btn"
                @click="savetimeTrackerCompany"
                v-if="moduleType != '' && isCompanyFormShow()"
            >
                {{ actionType === "add" ? $t("Create") : $t("Save") }}
            </button>
            <button
                @click="cancelCompany"
                type="button"
                class="primary-btn mr-3"
            >
                {{ $t("Cancel") }}
            </button>
        </template>
    </Modal>
</template>

<script>
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import Pagination from "laravel-vue-pagination";
import SelectInput from "@/Components/SelectInput.vue";
import TicketForm from "@/Pages/TimeTrackers/Components/TicketForm.vue";
import TaskForm from "@/Pages/TimeTrackers/Components/TaskForm.vue";
import AMSForm from "@/Pages/TimeTrackers/Components/AMSForm.vue";
import TravelExpenseForm from "@/Pages/TimeTrackers/Components/TravelExpenseForm.vue";
import NewEntryForm from "@/Pages/TimeTrackers/Components/NewEntryForm.vue";
import Modal from "@/Components/EditModal.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    components: {
        Pagination,
        MultiSelectInput,
        SelectInput,
        TicketForm,
        TaskForm,
        NewEntryForm,
        TravelExpenseForm,
        "ams-form": AMSForm,
        Modal,
        PageHeader
    },
    computed: {
        ...mapGetters(["errors"]),
        ...mapGetters("companies", ["companies"]),
        ...mapGetters("userProfile", ["userProfiles"]),
        ...mapGetters("timeChecking", ["timeChecking"]),
        ...mapGetters("auth", ["user"]),
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
                this.modalErrors.selectedTravelExpenses != ""
            );
        },
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Project Management"),
                    to: "/time-checking",
                },
                {
                    text: "Time Checking",
                    active: true,
                },
            ],
            userId: "",
            filterCompany: {
                id: "all",
                companyName: "All",
            },
            actionType: "edit",
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
            },
            timeTrackerCompanyTemp: {
                selectedComments: [],
                time: 0,
            },
            moduleType: "",
            toggleEditCompanyModal: false,
            form: {
                page: 1,
                companyId: "",
                userId: "",
                type: "",
                status: "",
                sortBy:
                    localStorage.getItem("sort_timeChecking_column") ?? "date",
                sortOrder:
                    localStorage.getItem("sort_timeChecking_order") ?? "asc",
            },
        };
    },
    async mounted() {
        try {
            await this.$store.dispatch("timeChecking/list");
            await this.$store.dispatch("companies/list");
            await this.$store.dispatch("userProfile/list");
        } catch (e) {
            console.log(e);
        }
    },
    watch: {
        form: {
            handler: async function () {
                await this.$store.dispatch("timeChecking/list", {
                    companyId: this.form.companyId?.id,
                    userId: this.form.userId?.userId,
                    status: this.form.status,
                    type: this.form.type,
                    sortBy: this.form.sortBy,
                    sortOrder: this.form.sortOrder,
                    page: this.form.page,
                });
            },
            deep: true,
        },
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
         * validates the ams entry to be added and adds the errors to modalErrors if any
         * @param {item} the ams entry to be validated
         */
        validateAMS(item) {
            this.modalErrors.accountedTime =
                !item.isGoodwill && (item.hours == "" || item.hours == "0.00")
                    ? "Please define time"
                    : "";
            // if modalErrors for accountedTime has been set than check for the 'totalRemainingHours' validation
            if (!this.modalErrors.accountedTime) {
                this.modalErrors.accountedTime =
                    parseFloat(item.hours) >
                    parseFloat(item.totalRemainingHours)
                        ? "Accounted time cannot be greater than total remaining hours"
                        : "";
            }
            this.modalErrors.company =
                item.companyId == "" ? "Customer not selected" : "";
            this.modalErrors.description =
                item.description == "" ? "Description not defined" : "";
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
        validateNewEntry(item) {
            this.modalErrors.accountedTime =
                item.hours == "" ? "Please define time" : "";
            this.modalErrors.company =
                item.companyId == "" ? "Customer not selected" : "";
            this.modalErrors.description =
                item.description == "" ? "Description not defined" : "";
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
        async savetimeTrackerCompany() {
            //Get time tracker company data
            let timeTrackerCompany = {};
            if (this.moduleType === "ticket") {
                timeTrackerCompany = {
                    moduleId: this.$refs.ticketFormComponent.moduleId,
                    description: this.$refs.ticketFormComponent.description,
                    internalNote: this.$refs.ticketFormComponent.internalNote,
                    hours: this.$refs.ticketFormComponent.accountedTime,
                    isGoodwill:
                        this.$refs.ticketFormComponent.isGoodwill ?? false,
                    companyId: this.$refs.ticketFormComponent.company.id ?? "",
                };
                //Validate data
                this.validateTickets({
                    moduleId: this.$refs.ticketFormComponent.moduleId ?? "",
                    ticket: this.$refs.ticketFormComponent.ticket.id ?? "",
                    companyId: this.$refs.ticketFormComponent.company.id ?? "",
                    selectedComments:
                        this.$refs.ticketFormComponent.selectedComments,
                });
            } else if (this.moduleType === "travel-expense") {
                timeTrackerCompany = {
                    moduleId: this.$refs.travelExpenseFormComponent.moduleId,
                    description:
                        this.$refs.travelExpenseFormComponent.description,
                    internalNote:
                        this.$refs.travelExpenseFormComponent.internalNote,
                    hours: this.$refs.travelExpenseFormComponent.accountedTime,
                    isGoodwill:
                        this.$refs.travelExpenseFormComponent.isGoodwill ??
                        false,
                    companyId:
                        this.$refs.travelExpenseFormComponent.company.id ?? "",
                };
                //Validate data
                this.validateTravelExpense({
                    moduleId:
                        this.$refs.travelExpenseFormComponent.moduleId ?? "",
                    companyId:
                        this.$refs.travelExpenseFormComponent.company.id ?? "",
                    selectedTravelExpenses:
                        this.$refs.travelExpenseFormComponent
                            .selectedTravelExpenses,
                });
            } else if (this.moduleType === "task") {
                timeTrackerCompany = {
                    moduleId: this.$refs.taskFormComponent.task ?? "",
                    description: this.$refs.taskFormComponent.description,
                    internalNote: this.$refs.taskFormComponent.internalNote,
                    hours: this.$refs.taskFormComponent.accountedTime,
                    isGoodwill:
                        this.$refs.taskFormComponent.isGoodwill ?? false,
                    companyId: this.$refs.taskFormComponent.company.id ?? "",
                };
                this.validateTasks({
                    ...timeTrackerCompany,
                    projectId: this.$refs.taskFormComponent.project.id ?? "",
                });
            } else if (this.moduleType === "newEntry") {
                timeTrackerCompany = {
                    moduleId: "",
                    moduleType: 'newEntry',
                    description: this.$refs.newEntryFormComponent.description,
                    internalNote: this.$refs.newEntryFormComponent.internalNote,
                    hours: this.$refs.newEntryFormComponent.accountedTime,
                    isGoodwill:
                        this.$refs.newEntryFormComponent.isGoodwill ?? false,
                    companyId:
                        this.$refs.newEntryFormComponent.company.id ?? "",
                    projectId: this.$refs.newEntryFormComponent.project?.id ?? ""
                };
                this.validateNewEntry(timeTrackerCompany);
            } else if (this.moduleType === "ams") {
                timeTrackerCompany = {
                    moduleId: this.$refs.amsFormComponent.ams?.id ?? "",
                    description: this.$refs.amsFormComponent.description,
                    internalNote: this.$refs.amsFormComponent.internalNote,
                    hours: this.$refs.amsFormComponent.accountedTime,
                    isGoodwill: this.$refs.amsFormComponent.isGoodwill ?? false,
                    companyId: this.$refs.amsFormComponent.company.id ?? "",
                    totalRemainingHours:
                        this.$refs.amsFormComponent.totalRemainingHours ?? "",
                };
                this.validateAMS(timeTrackerCompany);
            }

            //Check if validation return errors
            if (this.modalHasErrors !== false) {
                return;
            }

            //Save "task" & "new entry" data
            this.updateTimeTrackerCompany(timeTrackerCompany);

            //Reset modal errors
            Object.keys(this.modalErrors).forEach((key) => {
                this.modalErrors[key] = "";
            });

            //Reset data
            this.cancelCompany();
        },
        async updateTimeTrackerCompany(timeTrackerCompany) {
            //Inititalize payload
            let payload = {
                id: this.timeTrackerCompanyTemp.id,
                type: this.moduleType,
                ...timeTrackerCompany,
            };
            await this.$store.dispatch("timeTrackers/updateCompany", payload);
            await this.$store.dispatch("timeChecking/list", {
                companyId: this.form.companyId?.id,
                userId: this.form.userId?.userId,
                status: this.form.status,
                type: this.form.type,
                sortBy: this.form.sortBy,
                sortOrder: this.form.sortOrder,
                page: this.form.page,
            });
        },
        editCompanyModal(index) {
            this.timeTrackerCompanyTemp =
                this.timeChecking?.data?.[index] ?? {};
            this.timeTrackerCompanyTemp = {
                ...this.timeTrackerCompanyTemp,
                moduleType: this.timeTrackerCompanyTemp?.type,
                time: this.timeTrackerCompanyTemp?.quantity,
            };
            this.actionType = "edit";
            this.moduleType = this.timeChecking?.data?.[index]?.type ?? "";
            this.toggleEditCompanyModal = true;
        },
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
        cancelCompany() {
            this.toggleEditCompanyModal = false;
            this.actionType = "add";
            this.moduleType = "";
        },
        async changePageOrSearch(page = 1) {
            this.form.page = page;
            await this.$store.dispatch("timeChecking/list", {
                companyId: this.form.companyId?.id,
                userId: this.form.userId?.userId,
                type: this.form.type,
                status: this.form.status,
                sortBy: this.form.sortBy,
                sortOrder: this.form.sortOrder,
                page: this.form.page,
            });
        },
        async updateTimeCheckingStatus(id, status) {
            try {
                await this.$store.dispatch("timeChecking/update", {
                    id: id,
                    data: {
                        status: status,
                    },
                });
                await this.$store.dispatch("timeChecking/list", {
                    companyId: this.form.companyId?.id,
                    userId: this.form.userId?.userId,
                    status: this.form.status,
                    type: this.form.type,
                    sortBy: this.form.sortBy,
                    sortOrder: this.form.sortOrder,
                    page: this.form.page,
                });
            } catch (e) {
                console.log(e);
            }
        },
    },
};
</script>

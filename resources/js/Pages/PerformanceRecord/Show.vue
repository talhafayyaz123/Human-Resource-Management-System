<template>
    <h1 class="header mb-8 text-3xl font-bold">
        <router-link
            class="secondary-color"
            :to="`/performance-records?page=${returnPage}`"
            >{{ $t("Performance Records") }}</router-link
        >
        <span class="secondary-color font-medium">/</span>
        <span class="text-color">{{ $t("Edit") }}</span>
    </h1>

    <h6 class="text-xl font-normal leading-normal mt-8 mb-2 text-cyan-800">
        {{ $t("Configuration") }}
    </h6>
    <div class="w-full bg-white rounded-md shadow mb-8 p-4">
        <div
            class="grid gap-2"
            style="grid-template-columns: repeat(auto-fit, minmax(200px, auto))"
        >
            <TextInput
                :isReadonly="true"
                v-model="form.performanceNumber"
                :label="$t('LN Number')"
            />
            <TextInput
                :isReadonly="true"
                v-model="form.customerNumber"
                :label="$t('Customer Number')"
            />
            <MultiSelectInput
                :key="form.company"
                :isDisabled="true"
                :required="true"
                v-model="form.company"
                :options="companies"
                :multiple="false"
                :text-label="$t('Customer')"
                label="companyName"
                trackBy="id"
                moduleName="companies"
            />
            <MultiSelectInput
                :key="form.project"
                v-model="form.project"
                :options="projects?.data ?? []"
                :multiple="false"
                :text-label="$t('Project Reference')"
                label="name"
                trackBy="id"
                moduleName="projects"
                :query="{ isInternalProject: false }"
            />
            <MultiSelectInput
                :error="errors.advisorId"
                :key="form.advisor"
                v-model="form.advisor"
                :options="users ?? []"
                :multiple="false"
                :text-label="$t('Advisor')"
                :customLabel="customLabel"
                trackBy="id"
                moduleName="auth"
                :search-param-name="'search_string'"
                :query="{ limit_start: 0, limit_count: 100, type: 'employee' }"
            />
            <DateInput
                :isReadonly="true"
                v-model="form.createDate"
                :label="$t('Create Date')"
            />
            <DateInput
                v-model="form.startDate"
                :required="true"
                :label="$t('Start Date')"
            />
            <DateInput
                v-model="form.endDate"
                :required="true"
                :label="$t('End Date')"
            />
            <SelectInput
                :error="errors.status"
                v-model="form.status"
                :required="true"
                :key="form.status"
                :label="$t('Status')"
            >
                <option value="open">{{ $t("Open") }}</option>
                <option value="approved">{{ $t("Approved") }}</option>
                <option value="done">{{ $t("Done") }}</option>
            </SelectInput>
            <DateInput
                :isReadonly="true"
                v-if="form.isEdited"
                v-model="form.updatedAt"
                :label="$t('Updated At')"
            />
            <MultiSelectInput
                v-if="form.isEdited"
                :error="errors.isEdited"
                :key="form.isEdited"
                v-model="form.isEdited"
                :options="users ?? []"
                :multiple="false"
                :text-label="$t('Updated By')"
                :customLabel="customLabel"
                trackBy="id"
                moduleName="auth"
                :search-param-name="'search_string'"
                :query="{ limit_start: 0, limit_count: 100, type: 'employee' }"
            />
            <div class="flex items-center">
                <input
                    class="checkbox-input"
                    v-model="form.isApprovedByCustomer"
                    type="checkbox"
                />
                <label class="ml-1 checkbox-label" for="">{{
                    $t("Approved By Customer")
                }}</label>
            </div>
        </div>
        <loading-button
            :loading="isLoading"
            style="cursor: pointer"
            class="secondary-btn mt-5"
            @click="updatePerformanceRecord"
            >{{ $t("Save") }}</loading-button
        >
    </div>

    <h6 class="text-xl font-normal leading-normal mb-2 text-cyan-800">
        {{ $t("Company Details") }}
    </h6>
    <div class="w-1/4 bg-white rounded-md shadow mb-8 p-4">
        <p>
            {{ form.company?.companyName }}
            <br />
            {{ form.company?.address }}
            <br />
            {{ form.company?.code }}&nbsp;{{ form.company?.city }}
            <br />
            {{ form.company?.state }}
            <br />
            {{ form.company?.country }}
        </p>
    </div>
    <div class="relative inline-block">
        <div class="form-group min-w-[200px] w-full">
            <select-input @change="sortedEntries" v-model="sortBy" class="">
                <option value="entry">{{ $t("Sort by Entry Order") }}</option>
                <option value="date">{{ $t("Sort by Date") }}</option>
            </select-input>
        </div>
        <div
            class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none"
        >
            <svg class="fill-current h-4 w-4 text-gray-500" viewBox="0 0 20 20">
                <path d="M10 12l-6-6 1.41-1.41L10 9.18l4.59-4.59L16 6l-6 6z" />
            </svg>
        </div>
    </div>
    <button
        class="px-3 py-2 docsHeroColorBlue rounded float-right"
        @click="toggleModal = true"
    >
        {{ $t("Add") }} +
    </button>

    <!-- Tasks Accordion -->
    <TasksAccordion
        v-if="form.tasks && form.tasks.length > 0"
        :tasks="form.tasks"
        :open-accordions="openAccordions"
        @toggle-accordion="toggleAccordion"
        @edit-entry="editEntry"
    />

    <!-- Tickets Accordion -->
    <TicketsAccordion
        v-if="form.ticketComments && form.ticketComments.length > 0"
        :ticketComments="form.ticketComments"
        :open-accordions="openAccordions"
        @toggle-accordion="toggleAccordion"
        @edit-entry="editEntry"
    />

    <!-- AMS Accordion -->
    <AMSAccordion
        v-if="form.ams && form.ams.length > 0"
        :ams="form.ams"
        :open-accordions="openAccordions"
        @toggle-accordion="toggleAccordion"
        @edit-entry="editEntry"
    />

    <!-- New Entry Accordion -->
    <NewEntryAccordion
        v-if="form.newEntry && form.newEntry.length > 0"
        :new-entry="form.newEntry"
        :open-accordions="openAccordions"
        @toggle-accordion="toggleAccordion"
        @edit-entry="editEntry"
    />

    <table class="w-full whitespace-nowrap mt-2">
        <tr v-if="totalHours != 0">
            <td colspan="5"></td>
            <td
                colspan="1"
                style="grid-template-columns: 1fr 1fr"
                class="grid mt-4 justify-around font-bold bg-gray-300"
            >
                <p style="justify-self: center">
                    {{ replace(totalHours) }}
                </p>
                <p>{{ $t("Hours") }}</p>
            </td>
            <td colspan="1"></td>
        </tr>
        <tr v-if="totalGoodwillHours != 0">
            <td colspan="5"></td>
            <td
                colspan="1"
                style="grid-template-columns: 1fr 1fr"
                class="grid my-1 justify-around font-bold bg-gray-300"
            >
                <p style="justify-self: center">
                    {{ replace(totalGoodwillHours) }}
                </p>
                <p>{{ $t("Goodwill") }}</p>
            </td>
            <td colspan="1"></td>
        </tr>
    </table>
    <EntriesModal
        :toggleModal="toggleModal"
        :actionType="actionType"
        @cancel="cancel"
        @refresh="refresh"
        :record="recordToBeEdited"
        :company="form.company || {}"
    />
</template>

<script>
import SelectInput from "@/Components/SelectInput.vue";
import TextInput from "@/Components/TextInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import DateInput from "@/Components/DateInput.vue";
import EntriesModal from "./EntriesModal.vue";
import { mapGetters } from "vuex";
import draggable from "vuedraggable";
import TasksAccordion from "./Accordion/TasksAccordion.vue";
import TicketsAccordion from "./Accordion/TicketsAccordion.vue";
import AMSAccordion from "./Accordion/AMSAccordion.vue";
import NewEntryAccordion from "./Accordion/NewEntryAccordion.vue";

//Sample data set

export default {
    data() {
        return {
            openAccordions: [],
            modelData: {},
            recordToBeEdited: {},
            toggleModal: false,
            actionType: "Add",
            companies: [],
            returnPage: "",
            form: {
                id: "",
                performanceNumber: "",
                customerNumber: "",
                company: "",
                project: "",
                advisor: "",
                createDate: "",
                startDate: "",
                endDate: "",
                timeTrackerRecords: [],
                updatedAt: "",
                isEdited: 0,
                editedUserId: "",
                tasks: null,
                ticketComments: null,
                ams: null,
                newEntry: null,
                status: "open",
                isApprovedByCustomer: false,
            },
            totalHours: 0,
            totalGoodwillHours: 0,
            sortBy: "entry",
        };
    },
    computed: {
        ...mapGetters(["isLoading", "errors"]),
        ...mapGetters("projects", ["projects"]),
        ...mapGetters("auth", ["user", "users"]),
    },
    components: {
        EntriesModal,
        TextInput,
        MultiSelectInput,
        SelectInput,
        DateInput,
        LoadingButton,
        draggable,
        TasksAccordion,
        TicketsAccordion,
        NewEntryAccordion,
        AMSAccordion,
    },
    unmounted() {
        this.$store.commit("projects/projects", {
            data: [],
            links: [],
        });
    },
    async mounted() {
        if (this.$route.query.page) {
            this.returnPage = this.$route.query.page;
        }

        try {
            this.$store.commit("showLoader", true);
            if (!this.users?.length)
                await this.$store.dispatch("auth/list", {
                    limit_start: 0,
                    limit_count: 100,
                    type: "employee",
                });
            await this.refresh();
            this.$store
                .dispatch("companies/list", {
                    page: 1,
                    perPage: 25,
                })
                .then(async (res) => {
                    this.companies = res?.data?.data ?? [];
                    this.form.company =
                        this.companies.find(
                            (company) => company.id == this.modelData?.companyId
                        ) ?? "";
                    if (this.modelData?.companyId && !this.form.company) {
                        const response = await this.$store.dispatch(
                            "companies/show",
                            this.modelData?.companyId
                        );
                        this.form.company = response?.data?.modelData;
                    }
                })
                .catch((e) => console.log(e));
            await this.$store.dispatch("projects/list", {
                perPage: 100,
                page: 1,
                isInternalProject: false,
            });
            this.form.advisor = this.users.find(
                (user) => user.id == this.modelData?.advisor
            );
            this.form.project = this.projects.data.find(
                (project) => project.id == this.modelData?.projectId
            );
        } catch (e) {
            console.log(e);
        } finally {
            this.$store.commit("showLoader", false);
        }
    },
    methods: {
        toggleAccordion(key) {
            const index = this.openAccordions.indexOf(key);
            if (index === -1) {
                this.openAccordions.push(key);
            } else {
                this.openAccordions.splice(index, 1);
            }
        },
        sortedEntries() {
            if (this.sortBy === "entry") {
                this.form.newEntry = this.form.newEntry.sort(
                    (a, b) => a.order - b.order
                );
                this.form.ams = this.form.ams.sort((a, b) => a.order - b.order);
                this.form.ticketComments = this.form.ticketComments.sort(
                    (a, b) => a.order - b.order
                );
                this.sortTasksByOrder(this.form.tasks);
            } else {
                this.form.newEntry = this.form.newEntry.sort(
                    (a, b) => new Date(a.date) - new Date(b.date)
                );
                this.form.ams = this.form.ams.sort(
                    (a, b) => new Date(a.date) - new Date(b.date)
                );
                this.form.ticketComments = this.form.ticketComments.sort(
                    (a, b) => new Date(a.date) - new Date(b.date)
                );
                this.sortTasksByDate(this.form.tasks);
            }
        },
        sortTasksByOrder(tasks) {
            tasks.forEach((task) => {
                if (task.milestones) {
                    task.milestones.forEach((milestone) => {
                        if (milestone.tasks) {
                            milestone.tasks.sort((a, b) => a.order - b.order);
                        }
                    });
                }
            });
        },
        sortTasksByDate(tasks) {
            tasks.forEach((task) => {
                if (task.milestones) {
                    task.milestones.forEach((milestone) => {
                        if (milestone.tasks) {
                            milestone.tasks.sort(
                                (a, b) => new Date(a.date) - new Date(b.date)
                            );
                        }
                    });
                }
            });
        },
        /**
         * returns a custom label value for the selected option in multiselect component
         * @param {props} the properties of the options
         */
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
        /**
         * fetches the performance record data
         */
        async refresh() {
            return new Promise(async (resolve, reject) => {
                try {
                    const response = await this.$store.dispatch(
                        "performanceRecords/show",
                        {
                            id: this.$route.query.id,
                            startDate: this.$route.query.startDate,
                            endDate: this.$route.query.endDate,
                            customerId: this.$route.query.customerId,
                        }
                    );
                    this.modelData = response?.data;
                    document.title =
                        "Edit Performance " +
                            this.modelData?.performanceNumber ?? "";
                    this.form.performanceNumber =
                        this.modelData?.performanceNumber;
                    this.form.createDate = this.modelData?.createdDate;
                    this.form.customerNumber = this.modelData?.companyNumber;
                    this.form.startDate = this.modelData?.startDate;
                    this.form.endDate = this.modelData?.endDate;
                    this.form.id = this.modelData?.id;
                    this.form.updatedAt = this.modelData?.updatedAt;
                    this.form.editedUserId = this.modelData?.editedUserId;
                    this.form.status = this.modelData?.status ?? "open";
                    this.form.isApprovedByCustomer =
                        !!this.modelData?.isApprovedByCustomer ?? false;
                    this.form.tasks = this.modelData?.tasks;
                    this.form.ticketComments = this.modelData?.ticketComments;
                    this.form.ams = this.modelData?.ams;
                    this.form.newEntry = this.modelData?.newEntry;

                    this.totalHours = this.modelData?.totalHours;
                    this.totalGoodwillHours =
                        this.modelData?.totalGoodwillHours;

                    await this.$nextTick();
                    this.form.editedUserId = this.users.find(
                        (user) => user.id == this.modelData?.editedUserId
                    );
                    resolve();
                } catch (e) {
                    console.log(e);
                    reject(e);
                }
            });
        },
        /**
         * hides and the modal and resets modal props
         */
        cancel() {
            this.toggleModal = false;
            this.actionType = "Add";
            this.recordToBeEdited = {};
            this.$store.commit("errors", {});
        },
        /**
         * sets 'recordToBeEdited' to the intended record and toggles the modal
         * @param {record} record to be edited
         */
        editEntry(record) {
            this.recordToBeEdited = { ...record };
            this.recordToBeEdited.userId = this.users.find(
                (user) => user.id == record.userId
            );
            this.actionType = "Edit";
            this.toggleModal = true;
        },
        replace(string) {
            try {
                return this.globalLanguage === "de"
                    ? parseFloat(string).toFixed(2).replaceAll(".", ",")
                    : parseFloat(string).toFixed(2);
            } catch (e) {
                return string;
            }
        },
        async updatePerformanceRecord() {
            await this.$nextTick();
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("performanceRecords/update", {
                    id: this.form.id,
                    data: {
                        projectId: this.form.project?.id,
                        startDate: this.form.startDate,
                        endDate: this.form.endDate,
                        advisorId: this.form.advisor?.id,
                        editedUserId: this.user?.id,
                        status: this.form.status ?? "open",
                        isApprovedByCustomer: this.form.isApprovedByCustomer,
                    },
                });

                await this.refresh();

                const templateId = await this.$store.dispatch(
                    "globalSettings/getTemplateByName",
                    "perfRecordTemplateId"
                );
                if (templateId != "") {
                    const responseUser = await this.$store.dispatch(
                        "auth/show",
                        {
                            id: this.form.advisor.id,
                        }
                    );
                    const templatePayload = {
                        ...this.form,
                        id: templateId,
                        userFirstName: responseUser?.data?.first_name,
                        userLastName: responseUser?.data?.last_name,
                        userPhone: responseUser?.data?.phone,
                        output: "pdf",
                        updatedTime: Date.now(),
                    };
                    await this.$store.dispatch(
                        "documentService/processTemplate",
                        {
                            data: templatePayload,
                            filename:
                                "performance-records" +
                                this.form.performanceNumber +
                                ".pdf",
                        }
                    );
                }
            } catch (e) {
                console.log(e);
            }
        },
    },
};
</script>

<style scoped>
:deep(.form-label) {
    font-weight: bold;
}
</style>

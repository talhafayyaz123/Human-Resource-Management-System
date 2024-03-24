<template>
    <PageHeader :items="breadcrumbItems" />

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $t("Configuration") }}</h3>
        </div>
        <div class="card-body">
            <div class="grid items-center grid-cols-2 gap-6">
                <div class="w-full" v-if="form.customerNumber">
                    <div class="form-group">
                        <TextInput
                            :isReadonly="true"
                            v-model="form.customerNumber"
                            :label="$t('Customer Number')"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            :error="errors.companyId"
                            @update:model-value="setCustomerNumber"
                            :key="form.company"
                            v-model="form.company"
                            :options="companies"
                            :multiple="false"
                            :text-label="$t('Customer')"
                            label="companyName"
                            trackBy="id"
                            :required="true"
                            moduleName="companies"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            :error="errors.projectId"
                            :key="form.project"
                            v-model="form.project"
                            :options="projects?.data ?? []"
                            :multiple="false"
                            :text-label="$t('Project Reference')"
                            label="name"
                            trackBy="id"
                            moduleName="projects"
                            :query="{
                                isInternalProject: false,
                            }"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
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
                            :query="{
                                limit_start: 0,
                                limit_count: 100,
                                type: 'employee',
                            }"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <DateInput
                            :key="form.startDate"
                            :error="errors.startDate"
                            v-model="form.startDate"
                            :required="true"
                            :label="$t('Start Date')"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <DateInput
                            :error="errors.endDate"
                            v-model="form.endDate"
                            :required="true"
                            :label="$t('End Date')"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <SelectInput
                            :error="errors.status"
                            v-model="form.status"
                            :required="true"
                            :key="form.status"
                            :label="$t('Status')"
                        >
                            <option value="open">{{ $t("Open") }}</option>
                            <option value="approved">
                                {{ $t("Approved") }}
                            </option>
                            <option value="done">{{ $t("Done") }}</option>
                        </SelectInput>
                    </div>
                </div>
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
        </div>
    </div>
    <div class="card mt-3" v-if="form.company">
        <div class="card-header">
            <h3 class="card-title">{{ $t("Company Details") }}</h3>
        </div>
        <div class="card-body">
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
    </div>
    <div class="flex items-center justify-end mt-4">
        <loading-button
            @click="importFile"
            :loading="isLoading"
            class="secondary-btn"
        >
            <span class="mr-1">
                <CustomIcon name="saveIcon" />
            </span>
            {{ $t("Import") }}
        </loading-button>
    </div>
    <div class="flex items-center justify-end mt-4">
        <input @input="uploadFile" ref="fileInput" class="hidden" type="file" />

        <button class="secondary-btn" @click="toggleModal = true">
            <div class="mr-1">
                <CustomIcon name="addIcon" />
            </div>
            {{ $t("Add") }}
        </button>
    </div>

    <div class="table-responsive mt-2">
        <table class="doc-table">
            <thead>
                <tr class="text-left font-bold">
                    <th class="">{{ $t("Date") }}</th>
                    <th class="">
                        {{ $t("Person/Reference") }}
                    </th>
                    <th class="">
                        {{ $t("Activity Description") }}
                    </th>
                    <th
                        style="grid-template-columns: 1fr 1fr"
                        class="grid flex justify-between"
                    >
                        <p style="justify-self: center">{{ $t("Quantity") }}</p>
                        <p>{{ $t("Unit") }}</p>
                    </th>
                    <th class="">{{ $t("Goodwill") }}</th>
                    <th class="">{{ $t("Action") }}</th>
                </tr>
            </thead>
            <tbody>
                <draggable
                    :list="form.timeTrackerRecords"
                    group="timeTrackerRecords"
                    item-key="id"
                    tag="tbody"
                >
                    <template #item="{ element: record, index }">
                        <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                            <td style="vertical-align: top" class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                >
                                    {{
                                        $dateFormatter(
                                            record?.date,
                                            globalLanguage
                                        )
                                    }}
                                </a>
                            </td>
                            <td
                                style="vertical-align: top"
                                class="border-t pt-4"
                            >
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 focus:text-indigo-500"
                                >
                                    {{ record?.userId?.first_name }}
                                    {{ record?.userId?.last_name }}
                                </a>
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 focus:text-indigo-500 capitalize"
                                >
                                    {{
                                        record?.type ===
                                        String.raw`App\Models\TicketComment`
                                            ? "Ticket"
                                            : record?.type ===
                                              String.raw`App\Models\Task`
                                            ? "Task"
                                            : ""
                                    }}{{ record?.ticketNumber }}
                                </a>
                            </td>
                            <td style="vertical-align: top" class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    style="
                                        white-space: pre-wrap;
                                        text-align: justify;
                                    "
                                    class="flex items-center px-6 py-4 focus:text-indigo-500 description-box-length pl-2"
                                >
                                    {{ record?.description }}
                                </a>
                            </td>
                            <td
                                style="
                                    grid-template-columns: 1fr 1fr;
                                    vertical-align: top;
                                "
                                class="grid justify-around border-t py-4"
                            >
                                <p style="justify-self: center">
                                    {{ replace(record?.quantity) }}
                                </p>
                                <p>{{ $t("Hours") }}</p>
                            </td>
                            <td style="vertical-align: top" class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4"
                                    tabindex="-1"
                                >
                                    {{
                                        record?.isGoodwill === 0 ? "No" : "Yes"
                                    }}
                                </a>
                            </td>
                            <td style="vertical-align: top" class="border-t">
                                <button
                                    class="pl-5 pt-5"
                                    @click="editEntry(record, index)"
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-pen-to-square"
                                    />
                                </button>
                                <button
                                    class="pl-5 pt-5"
                                    @click="removeEntry(index)"
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-trash-can"
                                    />
                                </button>
                            </td>
                        </tr>
                    </template>
                </draggable>
                <tr v-if="(form.timeTrackerRecords ?? []).length > 0">
                    <td class=""></td>
                    <td class=""></td>
                    <td class=""></td>
                    <td
                        style="grid-template-columns: 1fr 1fr"
                        class="grid mt-4 justify-around font-bold bg-gray-300"
                    >
                        <p style="justify-self: center">
                            {{ replace(totalHours) }}
                        </p>
                        <p>{{ $t("Hours") }}</p>
                    </td>
                    <td class=""></td>
                    <td class=""></td>
                </tr>
                <tr v-if="(form.timeTrackerRecords ?? []).length > 0">
                    <td class=""></td>
                    <td class=""></td>
                    <td class=""></td>
                    <td
                        style="grid-template-columns: 1fr 1fr"
                        class="grid my-1 justify-around font-bold bg-gray-300"
                    >
                        <p style="justify-self: center">
                            {{ replace(totalGoodwillHours) }}
                        </p>
                        <p>{{ $t("Goodwill") }}</p>
                    </td>
                    <td class=""></td>
                    <td class=""></td>
                </tr>
                <tr
                    class="flex justify-center"
                    v-if="(form.timeTrackerRecords ?? []).length == 0"
                >
                    <td>{{ $t("No Entries Exist") }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="flex items-center justify-end mt-4">
        <router-link to="/performance-records" class="primary-btn me-3">
            <span class="mr-1">
                <CustomIcon name="cancelIcon" />
            </span>
            <span>{{ $t("Cancel") }}</span>
        </router-link>
        <loading-button
            @click="createPerformanceRecord"
            :loading="isLoading"
            class="secondary-btn"
        >
            <span class="mr-1">
                <CustomIcon name="saveIcon" />
            </span>
            {{ $t("Save and Proceed") }}
        </loading-button>
    </div>
    <EntriesModal
        :isCreate="true"
        :toggleModal="toggleModal"
        :actionType="actionType"
        @cancel="cancel"
        @updateEntry="updateEntry"
        @addEntry="addEntry"
        :record="recordToBeEdited"
    />
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import DateInput from "@/Components/DateInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import EntriesModal from "./EntriesModal.vue";
import { mapGetters } from "vuex";
import draggable from "vuedraggable";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    computed: {
        ...mapGetters(["isLoading", "errors"]),
        ...mapGetters("projects", ["projects"]),
        ...mapGetters("auth", ["user", "users"]),
        totalHours() {
            let total = 0;
            (this.form.timeTrackerRecords ?? []).forEach((record) => {
                if (record.isGoodwill == 0) total += +record.quantity;
            });
            return total;
        },
        totalGoodwillHours() {
            let total = 0;
            (this.form.timeTrackerRecords ?? []).forEach((record) => {
                if (record.isGoodwill == 1) total += +record.quantity;
            });
            return total;
        },
    },
    components: {
        EntriesModal,
        TextInput,
        MultiSelectInput,
        DateInput,
        LoadingButton,
        SelectInput,
        draggable,
        PageHeader,
    },
    unmounted() {
        this.$store.commit("projects/projects", {
            data: [],
            links: [],
        });
    },
    async mounted() {
        this.form.startDate = this.$route.query.startDate;
        this.form.endDate = this.$route.query.endDate;
        try {
            if (!this.users?.length)
                await this.$store.dispatch("auth/list", {
                    limit_start: 0,
                    limit_count: 100,
                    type: "employee",
                });
            this.$store
                .dispatch("companies/list", {
                    page: 1,
                    perPage: 25,
                })
                .then((res) => {
                    this.companies = res?.data?.data ?? [];
                    this.form.company =
                        this.companies.find(
                            (company) => company.id == this.modelData?.companyId
                        ) ?? "";
                });
            await this.$store.dispatch("projects/list", {
                perPage: 25,
                page: 1,
                isInternalProject: false,
            });
            this.form.advisor = this.users.find(
                (user) => user.id == this.user?.id
            );
        } catch (e) {}
    },
    methods: {
        /**
         * returns a custom label value for the selected option in multiselect component
         * @param {props} the properties of the options
         */
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
        /**
         * triggers the import API in performance record
         */
        async uploadFile(event) {
            this.$store.commit("isLoading", true);
            try {
                const file = event?.target?.files?.[0] ?? null;
                const formData = new FormData();
                formData.set("csv", file);
                const response = await this.$store.dispatch(
                    "performanceRecords/import",
                    formData
                );
                const timeTrackerRecords = response?.data?.data ?? [];
                (timeTrackerRecords instanceof Array
                    ? timeTrackerRecords
                    : []
                ).forEach((record) => {
                    const user =
                        this.users?.find(
                            (user) =>
                                `${user?.first_name ?? ""} ${
                                    user?.last_name ?? ""
                                }` === record.advisor
                        ) ?? {};
                    const modifiedRecord = {
                        createdDate: record.date
                            ? new Date(record.date).toISOString().slice(0, 10)
                            : "",
                        date: record.date
                            ? new Date(record.date).toISOString().slice(0, 10)
                            : "",
                        description: record.activityDescription,
                        isGoodwill: 0,
                        quantity: record.time,
                        taskNumber: record.taskNumber,
                        time: record.time,
                        userId: user,
                    };
                    this.form.timeTrackerRecords = [
                        ...this.form.timeTrackerRecords,
                        { ...modifiedRecord },
                    ];
                });
            } catch (e) {
                console.log(e);
            } finally {
                this.$store.commit("isLoading", false);
            }
        },
        /**
         * clicks on the hidden input file and open the file input dialog box
         */
        importFile() {
            this.$refs.fileInput.click();
        },
        /**
         * updates an entry to the 'timeTrackerRecords' array in 'form'
         * @param {entry} the entry to be updated
         */
        updateEntry(entry) {
            try {
                this.form.timeTrackerRecords[this.recordToBeEditedIndex] = {
                    ...entry,
                };
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * removes the selected entry from the 'timeTrackerRecords' in 'form'
         * @param {index} index of the entry to be removed
         */
        removeEntry(index) {
            this.form.timeTrackerRecords.splice(index, 1);
        },
        /**
         * adds an entry to the 'timeTrackerRecords' array in 'form'
         * @param {entry} the entry to be added
         */
        addEntry(entry) {
            try {
                if (typeof entry === "object") {
                    this.form.timeTrackerRecords = [
                        ...this.form.timeTrackerRecords,
                        { ...entry },
                    ];
                }
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * sets the customerNumber in form when the customer/company is selected
         */
        async setCustomerNumber() {
            await this.$nextTick();
            this.form.customerNumber = this.form.company?.companyNumber ?? "";
        },
        /**
         * hides and the modal and resets modal props
         */
        cancel() {
            this.toggleModal = false;
            this.actionType = "Add";
            this.recordToBeEdited = {};
            this.recordToBeEditedIndex = "";
            this.$store.commit("errors", {});
        },
        /**
         * sets 'recordToBeEdited' to the intended record and toggles the modal
         * @param {record} record to be edited
         * @param {index} index of the record to be edited in the timrTrackerRecords array
         */
        editEntry(record, index) {
            this.recordToBeEdited = { ...record };
            this.recordToBeEditedIndex = index;
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
        async createPerformanceRecord() {
            await this.$nextTick();
            let wasCreateSuccessfull = false; // keeps track if the create API was successfull, if yes then redirect to the performance record index page, even if the APIs below fail
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch(
                    "performanceRecords/createManually",
                    {
                        projectId: this.form.project?.id,
                        startDate: this.form.startDate,
                        endDate: this.form.endDate,
                        advisorId: this.form.advisor?.id,
                        companyId: this.form.company?.id,
                        entries: this.form.timeTrackerRecords,
                        status: this.form.status ?? "open",
                        isApprovedByCustomer: this.form.isApprovedByCustomer,
                    }
                );
                wasCreateSuccessfull = true;
            } catch (e) {
                console.log(e);
            }
            try {
                this.$store.commit("isLoading", true);
                const templateId = await this.$store.dispatch(
                    "globalSettings/getTemplateByName",
                    "perfRecordTemplateId"
                );
                if (templateId != "") {
                    this.$store.commit("isLoading", true);
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
                        newEntry: this.form.timeTrackerRecords,
                        ams: [],
                        tasks: [],
                        ticketComments: [],
                    };
                    this.$store.commit("isLoading", true);
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
            } catch (e) {}
            // if the create API was successfull then redirect to the index page
            if (wasCreateSuccessfull) {
                this.$router.push("/performance-records");
            }
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
                    to: "/performance-records",
                },
                {
                    text: this.$t("Performance Records"),
                    to: "/performance-records",
                },
                {
                    text: "Create",
                    active: true,
                },
            ],
            modelData: {},
            recordToBeEdited: {},
            recordToBeEditedIndex: "",
            toggleModal: false,
            actionType: "Add",
            companies: [],
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
                status: "open",
                isApprovedByCustomer: false,
            },
        };
    },
};
</script>

<style scoped>
:deep(.form-label) {
    font-weight: bold;
}
</style>

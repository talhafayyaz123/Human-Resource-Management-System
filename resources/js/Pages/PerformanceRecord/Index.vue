<template>
    <div>
        <PageHeader
            :key="isLoading"
            :items="breadcrumbItems"
            :optionalItems="optionalItems"
            :isLoading="isLoading"
            @csvBtn1="create"
        />
        <div>
            <div
                class="grid gap-2 mr-2 mb-5"
                style="
                    grid-template-columns: repeat(
                        auto-fill,
                        minmax(200px, 1fr)
                    );
                "
            >
                <!-- <loading-button
                    :loading="isLoading"
                    v-if="$can(`${$route.meta.permission}.create`)"
                    class="secondary-btn self-end flex justify-center"
                    @click="create"
                >
                    <span>{{ $t("Create Performance Record") }}</span>
                </loading-button>
                <loading-button
                    :loading="isLoading"
                    v-if="$can(`${$route.meta.permission}.create`)"
                    class="secondary-btn self-end flex justify-center"
                    @click="
                        $router.push(
                            `/performance-records/create?startDate=${startDate}&endDate=${endDate}`
                        )
                    "
                >
                    <span>{{ $t("Create Manually") }}</span>
                </loading-button> -->
                <!-- create invoices for all the selected performance records -->
                <loading-button
                    :disabled="!isAnyChecked"
                    class="primary-btn"
                    :loading="processingAll"
                    @click="sendAllSelected"
                >
                    {{ $t("Create Selected Invoices") }}
                </loading-button>
            </div>
            <div class="flex justify-between">
                <div class="flex w-full">
                    <DateInput
                        @input="refresh"
                        v-model="startDate"
                        class="lg:w-1/5"
                        :label="$t('Start Date')"
                    />
                    <DateInput
                        @input="refresh"
                        v-model="endDate"
                        class="ml-8 lg:w-1/5"
                        :label="$t('End Date')"
                    />
                    <MultiSelectInput
                        v-model="form.company"
                        :options="companies"
                        :multiple="false"
                        :textLabel="$t('Customer')"
                        label="companyName"
                        trackBy="id"
                        class="ml-8 lg:w-1/5"
                        moduleName="companies"
                        @asyncSearch="companiesSearch"
                    />
                    <select-input
                        v-model="form.status"
                        class="ml-8 lg:w-1/5"
                        :key="form.status"
                        :label="$t('Status')"
                    >
                        <option value="open">{{ $t("Open") }}</option>
                        <option value="approved">{{ $t("Approved") }}</option>
                        <option value="done">{{ $t("Done") }}</option>
                    </select-input>
                </div>
                <search-filter
                    :isFilter="false"
                    v-model="form.search"
                    class="mr-4 w-full max-w-md self-end"
                    @reset="reset"
                >
                </search-filter>
            </div>
        </div>
        <p v-if="hasError" class="text-red-500 mt-3">
            {{
                $t(
                    "Please add a start date and an end date to create performance records"
                )
            }}
        </p>
        <div class="table-responsive mt-4">
            <table class="doc-table">
                <thead>
                    <tr class="text-left">
                        <th
                            @click="
                                sort('performanceNumber', 'performanceRecords')
                            "
                            class="cursor-pointer"
                            style="width: 165px"
                        >
                            <!-- checks all the performance record that have checkboxes -->
                            <div class="flex items-center">
                                <input
                                    :checked="areAllChecked"
                                    @click.stop="selectAll"
                                    class="mr-2"
                                    type="checkbox"
                                />
                                {{ $t("LN Number", "performanceRecords")
                                }}<font-awesome-icon
                                    v-if="form.sortBy === 'performanceNumber'"
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        form.sortOrder === 'asc' ? 'up' : 'down'
                                    }`"
                                />
                            </div>
                        </th>
                        <th
                            @click="
                                sort(
                                    'Company.companyNumber',
                                    'performanceRecords'
                                )
                            "
                            class="cursor-pointer"
                        >
                            <div class="flex items-center" style="width: 130px">
                                {{ $t("Customer Number") }}
                                <font-awesome-icon
                                    v-if="
                                        form.sortBy === 'Company.companyNumber'
                                    "
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        form.sortOrder === 'asc' ? 'up' : 'down'
                                    }`"
                                />
                            </div>
                        </th>
                        <th
                            @click="sort('Company.companyName', 'invoices')"
                            class="cursor-pointer"
                        >
                            <div class="flex items-center" style="width: 250px">
                                {{ $t("Customer")
                                }}<font-awesome-icon
                                    v-if="form.sortBy === 'Company.companyName'"
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        form.sortOrder === 'asc' ? 'up' : 'down'
                                    }`"
                                />
                            </div>
                        </th>
                        <th
                            @click="
                                sort('startDate-endDate', 'performanceRecords')
                            "
                            class="cursor-pointer"
                        >
                            {{ $t("Performance Period")
                            }}<font-awesome-icon
                                v-if="form.sortBy === 'startDate-endDate'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('totalHours', 'performanceRecords')"
                            class="cursor-pointer"
                        >
                            {{ $t("Hours")
                            }}<font-awesome-icon
                                v-if="form.sortBy === 'totalHours'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('goodWillHours', 'performanceRecords')"
                            class="cursor-pointer"
                        >
                            {{ $t("Goodwill")
                            }}<font-awesome-icon
                                v-if="form.sortBy === 'goodWillHours'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th>
                            {{ $t("Invoice") }}
                        </th>
                        <th
                            @click="sort('createdAt', 'performanceRecords')"
                            class="cursor-pointer"
                        >
                            {{ $t("Create Date")
                            }}<font-awesome-icon
                                v-if="form.sortBy === 'createdAt'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('status', 'performanceRecords')"
                            class="cursor-pointer"
                        >
                            {{ $t("Status")
                            }}<font-awesome-icon
                                v-if="form.sortBy === 'status'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('updatedAt', 'performanceRecords')"
                            class="cursor-pointer"
                        >
                            {{ $t("Updated At") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'updatedAt'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('editedUserId', 'performanceRecords')"
                            class="cursor-pointer"
                        >
                            {{ $t("Updated By")
                            }}<font-awesome-icon
                                v-if="form.sortBy === 'editedUserId'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th>
                            {{ $t("Generate Document") }}
                        </th>
                        <th>{{ $t("Actions") }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="record in performanceRecords?.data ?? []"
                        :key="record.id"
                        class="hover:bg-gray-100 focus-within:bg-gray-100"
                        @click.stop.right="
                            (e) => {
                                e.preventDefault();
                                let route = $router.resolve(
                                    `/performance-records/show?id=${record.id}&startDate=${record.startDate}&endDate=${record.endDate}&customerId=${record.companyId}&page=${page}`
                                );
                                window.open(route.href, '_blank');
                            }
                        "
                    >
                        <td class="">
                            <div class="flex items-center">
                                <font-awesome-icon
                                    v-if="record.isRejected"
                                    style="left: 20px"
                                    icon="fa fa-exclamation-triangle"
                                    class="text-red-500 mr-2"
                                    aria-hidden="true"
                                ></font-awesome-icon>
                                <!-- selects the performance record for invoice creation -->
                                <input
                                    v-if="record.showCheckbox"
                                    @click.stop=""
                                    v-model="
                                        selectedPerformanceRecords[record.id]
                                    "
                                    :checked="
                                        !!selectedPerformanceRecords[record.id]
                                    "
                                    type="checkbox"
                                />
                                <a class="ml-2">
                                    {{ record.performanceNumber }}
                                </a>
                            </div>
                        </td>
                        <td class="">
                            <a
                                class="flex items-center cursor-pointer focus:text-indigo-500"
                            >
                                {{ record.companyNumber }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                style="width: 250px"
                                class="flex items-center cursor-pointer text-center text-hover focus:text-indigo-500"
                            >
                                {{ record.company }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                class="flex items-center cursor-pointer focus:text-indigo-500"
                            >
                                {{
                                    $dateFormatter(
                                        record.startDate,
                                        globalLanguage
                                    )
                                }}
                                -
                                {{
                                    $dateFormatter(
                                        record.endDate,
                                        globalLanguage
                                    )
                                }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                class="flex items-center cursor-pointer"
                                tabindex="-1"
                            >
                                {{ record.totalHours }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                class="flex items-center cursor-pointer"
                                tabindex="-1"
                            >
                                {{ record.goodWillHours }}
                            </a>
                        </td>
                        <td class="">
                            <div>
                                <font-awesome-icon
                                    v-if="
                                        record.invoice &&
                                        (record.invoice?.status === 'draft' ||
                                            record.invoice?.status === 'sent')
                                    "
                                    :title="'Show Invoice'"
                                    @click.stop="
                                        $router.push(
                                            `/invoices/${record?.invoice.id}/edit?page=${page}`
                                        )
                                    "
                                    class="cursor-pointer px-1"
                                    icon="fa-solid fa-eye"
                                />
                                <font-awesome-icon
                                    v-else-if="
                                        record.invoice &&
                                        (record.invoice?.status ===
                                            'approved' ||
                                            record.invoice?.status === 'paid')
                                    "
                                    @click.stop="
                                        $router.push(
                                            `/invoices/${record?.invoice.id}?page=${page}`
                                        )
                                    "
                                    :class="`text-green-700`"
                                    class="w-5 h-5 cursor-pointer"
                                    icon="fa-solid fa-file-circle-check"
                                />
                                <font-awesome-icon
                                    v-else
                                    :title="
                                        record.invoice?.id
                                            ? 'Show Invoice'
                                            : !record.invoice?.id &&
                                              invoiceIconColorRed(record)
                                                  ?.length > 0
                                            ? invoiceIconColorRed(record)
                                            : 'Create Invoice'
                                    "
                                    class="cursor-pointer px-1"
                                    @click.stop="
                                        createInvoiceFromPerformanceRecord(
                                            record
                                        )
                                    "
                                    :class="
                                        !record.invoice?.id &&
                                        invoiceIconColorRed(record)?.length > 0
                                            ? `text-red-500`
                                            : ``
                                    "
                                    :icon="[
                                        'fa-solid',
                                        record.invoice?.id
                                            ? 'fa-eye'
                                            : 'fa-print',
                                    ]"
                                />
                            </div>
                        </td>
                        <td class="">
                            <a
                                class="flex items-center cursor-pointer"
                                tabindex="-1"
                            >
                                {{
                                    $dateFormatter(
                                        record.createdDate,
                                        globalLanguage
                                    )
                                }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                class="flex items-center cursor-pointer input-box-length"
                                tabindex="-1"
                            >
                                {{ record.status }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                class="flex items-center cursor-pointer input-box-length"
                                tabindex="-1"
                            >
                                {{
                                    $dateFormatter(
                                        record.updatedAt,
                                        globalLanguage
                                    )
                                }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                class="flex items-center cursor-pointer"
                                tabindex="-1"
                            >
                                {{
                                    userProfiles?.data?.find(
                                        (user) =>
                                            user.userId == record.editedUserId
                                    )?.employee ?? ""
                                }}
                            </a>
                        </td>
                        <td class="">
                            <font-awesome-icon
                                :title="$t('Generate Document')"
                                class="cursor-pointer px-1"
                                @click.stop="
                                    generateProcessTemplate(
                                        record,
                                        'generate',
                                        'pdf'
                                    )
                                "
                                icon="fa-solid fa-file-word"
                            />
                            <button @click.stop="download(record.id)">
                                <font-awesome-icon
                                    icon="fa-solid fa-file-csv px-1"
                                />
                            </button>
                        </td>
                        <td class="">
                            <router-link
                                v-if="
                                    $can(`${$route.meta.permission}.show`) &&
                                    !record.invoice?.id
                                "
                                class="px-1"
                                :to="`/performance-records/show?id=${record.id}&startDate=${record.startDate}&endDate=${record.endDate}&customerId=${record.companyId}&page=${page}`"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-pen-to-square"
                                />
                            </router-link>
                            <button
                                v-if="
                                    $can(`${$route.meta.permission}.delete`) &&
                                    !record?.invoice?.id
                                "
                                @click.stop="destroy(record.id)"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-trash-can px-1"
                                />
                            </button>
                        </td>
                    </tr>
                    <tr v-if="(performanceRecords?.data?.length ?? 0) === 0">
                        <td class="" colspan="4">
                            {{ $t("No performance records found") }}.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="performanceRecords ?? []"
                @pagination-change-page="changePageOrSearch"
            >
            </pagination>
            <br />
            <br />
        </div>
    </div>
    <Modal
        :title="$t(message)"
        v-if="togglePromptModal"
        @toggleModal="togglePromptModal = false"
        width="50%"
    >
        <template #body>
            <div class="px-8 py-2">
                <p>
                    {{
                        $t(
                            "Missing information on customer. Go to customer and edit to continue."
                        )
                    }}
                </p>
            </div>
        </template>
        <template #footer>
            <loading-button
                style="cursor: pointer"
                class="secondary-btn mx-2"
                @click="
                    togglePromptModal = false;
                    message = '';
                    $router.push(
                        `/companies/${selectedPerformanceRecord?.companyId}/edit?page=${page}`
                    );
                "
            >
                {{ $t("Go to Customer") }}
            </loading-button>
            <button
                @click="
                    togglePromptModal = false;
                    selectedPerformanceRecord = {};
                "
                type="button"
                class="primary-btn mr-3"
            >
                {{ $t("No") }}
            </button>
        </template>
    </Modal>
</template>

<script>
import Icon from "../../Components/Icon.vue";
import Pagination from "laravel-vue-pagination";

import SearchFilter from "../../Components/SearchFilter.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import DateInput from "../../Components/DateInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import Modal from "../../Components/EditModal.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { debounce } from "../../utils/debounce";
export default {
    computed: {
        ...mapGetters(["isLoading"]),
        ...mapGetters("auth", ["user"]),
        ...mapGetters("performanceRecords", ["performanceRecords"]),
        ...mapGetters("userProfile", ["userProfiles"]),
        // returns true if any performance record in the selectedPerformanceRecords object has true value
        isAnyChecked() {
            return Object.values(this.selectedPerformanceRecords).includes(
                true
            );
        },
        // returns true if all the performance records in the selectedPerformanceRecords object have true values
        areAllChecked() {
            return (
                Object.values(this.selectedPerformanceRecords).length > 0 &&
                Object.values(this.selectedPerformanceRecords).length ===
                    Object.values(this.selectedPerformanceRecords).filter(
                        (item) => !!item
                    ).length
            );
        },
    },
    components: {
        LoadingButton,
        MultiSelectInput,
        Icon,
        Pagination,
        SearchFilter,
        SelectInput,
        DateInput,
        Modal,
        PageHeader,
    },
    props: {
        filters: Object,
        can: Object,
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
                    active: true,
                },
            ],
            optionalItems: {
                csvBtn1Show: true,
                csvBtn1Text: this.$t("Create Performance Record"),
                isBtn2Show: true,
                btn2Text: this.$t("Create Manually"),
                btn2Path: "/performance-records/create",
                isLoading: this.isLoading

            },
            window,
            selectedPerformanceRecord: {},
            togglePromptModal: false,
            message: "",
            selectedPerformanceRecords: {},
            processingAll: false,
            page: 1,
            hasError: false,
            companies: [],
            startDate: "",
            endDate: "",
            form: {
                search: "",
                company: "",
                status: "",
                sortBy:
                    localStorage.getItem("sort_performanceRecords_column") ??
                    "performanceNumber",
                sortOrder:
                    localStorage.getItem("sort_performanceRecords_order") ??
                    "asc",
            },
            responseData: [],
            templateId: "",
        };
    },
    watch: {
        "form.search"(...val) {
            this.debouncedFetch(...val);
        },
        "form.company"(...val) {
            this.debouncedFetch(...val);
        },
        "form.status"(...val) {
            this.debouncedFetch(...val);
        },
        "form.sortBy"(...val) {
            this.debouncedFetch(...val);
        },
        "form.sortOrder"(...val) {
            this.debouncedFetch(...val);
        },
    },
    created() {
        this.debouncedFetch = debounce(async (newValue, oldValue) => {
            try {
                await this.$store.dispatch("performanceRecords/list", {
                    ...this.form,
                    company: this.form.company?.id,
                });
            } catch (e) {}
        }, 300);
    },
    async mounted() {
        try {
            var isPginate=false;
        if(this.$route.query.page){
            this.page=this.$route.query.page;
            this.$router.replace({'query': null});
            isPginate=true;
        }
            if (!this.userProfiles?.data?.length)
                await this.$store.dispatch("userProfile/list");
            await this.refresh(isPginate);
        } catch (e) {
            console.log(e);
        }
    },
    methods: {
        /**
         * routes to the invoice create page if icon is not red
         * if the icon is red than it opens a modal
         * @param {record} selected performance record
         */
        createInvoiceFromPerformanceRecord(record) {
            if (this.invoiceIconColorRed(record)?.length > 0) {
                this.message = this.invoiceIconColorRed(record);
                this.selectedPerformanceRecord = record;
                this.togglePromptModal = true;
                return;
            }
            this.$router.push(
                record.invoice?.id
                    ? `/invoices/${record?.invoice.id}/edit?page${page}`
                    : `/invoices/create?startDate=${record.startDate}&endDate=${record.endDate}&performanceRecord=${record.id}`
            );
        },
        /**
         * sets error message based on if certain properties are set on performance record
         * @param {record} the performance record entry
         */
        invoiceIconColorRed(record) {
            let message = "";
            if (!record.isDefaultServiceProductSet) {
                message = "Default service product is not set";
            } else if (!record.isPaymentPeriodSet) {
                message = "Payment period is not set";
            } else if (!record.isTermsOfPaymentSet) {
                message = "Terms of payment is not set";
            }
            return message;
        },
        /**
         * hits the invoice create API for all the selected performance records
         **/
        async sendAllSelected() {
            try {
                this.processingAll = true;
                // get the performance record ids of all the checked performance records
                let selectedPerformanceRecords = [];
                for (let key in this.selectedPerformanceRecords) {
                    if (!!this.selectedPerformanceRecords[key]) {
                        selectedPerformanceRecords.push(key);
                    }
                }
                await this.$store.dispatch(
                    "performanceRecords/createInvoices",
                    {
                        performanceRecordIds: selectedPerformanceRecords,
                    }
                );
                this.selectedPerformanceRecords = {}; // reset the selected performance records
            } catch (e) {
                console.log(e);
            } finally {
                this.processingAll = false; // stop the loading button
                await this.refresh(true); // refetch the listing
            }
        },
        /**
         * checks all performance records that have checboxes next to them
         * @param {event} imput event
         */
        selectAll(event) {
            (this.performanceRecords?.data ?? []).forEach(
                (performanceRecord) => {
                    if (performanceRecord.showCheckbox)
                        this.selectedPerformanceRecords[performanceRecord.id] =
                            event.target.checked;
                }
            );
        },
        async generateProcessTemplate(item, type, documentType) {
            let userData = {};
            if (type == "bulk") {
                userData = this.user;
            } else {
                const responseUser = await this.$store.dispatch("auth/show", {
                    id: item.advisorId,
                });
                userData = responseUser?.data;
            }
            let perfResponse = await this.$store.dispatch(
                "performanceRecords/show",
                {
                    id: item.id,
                    startDate: item.startDate,
                    endDate: item.endDate,
                    customerId: item.companyId,
                }
            );
            //Create template payload and process template
            if (this.templateId != "" && perfResponse != "") {
                const templatePayload = {
                    ...perfResponse?.data,
                    id: this.templateId,
                    userFirstName: userData.first_name,
                    userLastName: userData.last_name,
                    userPhone: userData.phone,
                    output: documentType,
                    updatedTime: Date.now(),
                };
                try {
                    const performanceBlob = await this.$store.dispatch(
                        "documentService/processTemplate",
                        {
                            data: templatePayload,
                            filename:
                                "performance-records-" +
                                item.performanceNumber +
                                `.${documentType}`,
                            documentType: documentType,
                        }
                    );
                    if (performanceBlob instanceof Blob) {
                        // convert blob to base64
                        const base64 = await this.convertBlobToBase64(
                            performanceBlob
                        );
                        // send the elo request
                        const response = await this.$store.dispatch(
                            "performanceRecords/showEloRequestForPerformanceRecord",
                            {
                                id: item.id,
                                base64:
                                    (base64 ?? "").split(";base64,")?.[1] ?? "",
                                startDate: item.startDate,
                                endDate: item.endDate,
                                customerId: item.companyId,
                            }
                        );
                    }
                } catch (error) {
                    console.error("An error occurred:", error);
                }
            }
        },
        async create() {
            if (!this.startDate || !this.endDate) {
                this.hasError = true;
                return;
            }
            this.$store.commit("isLoading", true);
            try {
                const response = await this.$store.dispatch(
                    "performanceRecords/create",
                    {
                        startDate: this.startDate,
                        endDate: this.endDate,
                        advisor: this.user.id,
                        company: this.form.company?.id ?? null,
                    }
                );

                await this.refresh();

                this.responseData = response?.data?.data ?? {};

                for (let index = 0; index < this.responseData.length; index++) {
                    let item = this.responseData[index];
                    await this.generateProcessTemplate(item, "bulk");
                }
                await this.refresh();
            } catch (e) {}
            this.$store.commit("isLoading", false)
        },
        async refresh(bypass) {
            this.hasError = false;
            try {
                if (!this.performanceRecord?.data?.length || bypass) {
                    await this.$store.dispatch("performanceRecords/list", {
                        ...this.form,
                        company: this.form.company?.id,
                        page: this.page,
                    });
                }
                this.$store
                    .dispatch("companies/list", {
                        page: 1,
                        perPage: 25,
                    })
                    .then((res) => {
                        this.companies = res?.data?.data ?? [];
                    });
                if (this.templateId == "") {
                    this.templateId = await this.$store.dispatch(
                        "globalSettings/getTemplateByName",
                        "perfRecordTemplateId"
                    );
                }
            } catch (e) {}
        },
        companiesSearch(data) {
            this.companies = data?.data;
        },
        async destroy(id) {
            this.$swal({
                title: this.$t("Do you want to delete this record?"),
                text: this.$t("You can't revert your action"),
                type: "warning",
                showCancelButton: true,
                confirmButtonText: this.$t("Yes delete it!"),
                cancelButtonText: this.$t("No"),
                showCloseButton: true,
                showLoaderOnConfirm: true,
            }).then(async (result) => {
                if (result.isConfirmed === true) {
                    await this.$store.dispatch(
                        "performanceRecords/destroy",
                        id
                    );
                    this.refresh(true);
                }
            });
        },
        async download(id) {
            try {
                await this.$store.dispatch("performanceRecords/download", id);
            } catch (e) {}
        },
        async changePageOrSearch(page = 1) {
            this.page = page;
            const response = await this.$store.dispatch(
                "performanceRecords/list",
                {
                    page: this.page,
                    ...this.form,
                    company: this.form.company?.id,
                }
            );
            this.performanceRecords = response?.data ?? {
                data: [],
                links: [],
            };
        },
        reset() {
            this.form = this.mapValues(this.form, () => null);
        },
    },
};
</script>

<style scoped>
:deep(.page-item.active .page-link) {
    background-color: #2957a4;
    border-color: #2957a4;
}

:deep(.page-link) {
    color: #2957a4;
}

.text-clip {
    max-width: 30ch;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    display: block;
}

.text-clip:hover {
    text-overflow: clip;
    white-space: normal;
    word-break: break-all;
}
</style>

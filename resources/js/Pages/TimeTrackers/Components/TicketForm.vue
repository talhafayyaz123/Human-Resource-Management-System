<template>
    <div class="grid items-center grid-cols-2 gap-6">
        <div class="w-full">
            <div class="form-group">
                <MultiSelectInput
                    v-if="actionType === 'add' && $can('ticket.list')"
                    :required="true"
                    :options="tickets?.data"
                    class=""
                    :multiple="false"
                    :textLabel="$t('Ticket Number')"
                    label="ticketNumber"
                    trackBy="id"
                    moduleName="tickets"
                    @update:modelValue="getTicketComments"
                    v-model="ticket"
                    :error="modalErrors.ticketId"
                    :query="{
                        isArchived: false,
                        sortBy: 'created_at',
                        sortOrder: 'desc',
                        unaccountedTickets: true,
                    }"
                    @asyncSearch="searchCompanies()"
                >
                    <template #singleLabel="{ props }">
                        <p>
                            {{ $t("Ticket") }}
                            {{ props.option.ticketNumber }}
                        </p>
                    </template>
                    <template #option="{ props }">
                        <p>
                            {{ $t("Ticket") }}
                            {{ props.option.ticketNumber }}
                        </p>
                    </template>
                </MultiSelectInput>
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <div v-if="actionType === 'add'" class="">
                    <label class="input-label">{{ $t("Date") }}:</label>
                    <datepicker
                        :clearable="true"
                        :enable-time-picker="false"
                        auto-apply
                        :close-on-auto-apply="false"
                        v-model="selectedDate"
                        @update:model-value="getTicketComments(ticket)"
                        class="form-control"
                    />
                </div>
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <!-- Display comment listing -->
                <div class="pr-6 w-full lg:w-full">
                    <div
                        v-if="displayCommentListing"
                        class="bg-white overflow-hidden shadow-md rounded-lg mb-8"
                    >
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left">
                                        {{ $t("Select") }}
                                    </th>
                                    <th class="px-4 py-2 text-left">
                                        {{ $t("Description") }}
                                    </th>
                                    <th class="px-4 py-2 text-left">
                                        {{ $t("Accounted Time") }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    :key="index"
                                    v-for="(data, index) in ticketComments"
                                >
                                    <td
                                        class="px-4 py-2 border-b border-gray-200"
                                    >
                                        <label class="inline-flex items-center">
                                            <input
                                                @change="
                                                    toggleComment($event, index)
                                                "
                                                type="checkbox"
                                                :checked="
                                                    selectedComments.some(
                                                        (comment) =>
                                                            comment.id ==
                                                            data.id
                                                    )
                                                "
                                                class="comment-checkbox h-5 w-5 text-blue-600"
                                            />
                                        </label>
                                    </td>
                                    <td
                                        class="px-4 py-2 border-b border-gray-200"
                                    >
                                        <span
                                            v-if="data?.text"
                                            v-html="data?.text"
                                        />
                                        <span v-else>{{ $t("N/A") }}</span>
                                    </td>
                                    <td
                                        class="px-4 py-2 border-b border-gray-200"
                                    >
                                        {{ Number(data?.time).toFixed(2) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div
                            v-if="this.modalErrors?.selectedComments"
                            class="form-error"
                        >
                            {{ this.modalErrors.selectedComments }}
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
        <div class="w-full">
            <div class="form-group">
                <!-- Description of the given type -->
                <text-area-input
                    v-if="actionType === 'edit'"
                    :required="true"
                    v-model="description"
                    :rows="3"
                    :error="$t(modalErrors.description)"
                    class=""
                    :label="$t('Description')"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <!-- Display company listing -->
                <MultiSelectInput
                    :isDisabled="actionType === 'edit'"
                    :required="true"
                    class=""
                    :textLabel="$t('Customer')"
                    v-if="$can('company.list')"
                    :error="modalErrors.company"
                    v-model="company"
                    :options="companyOptions"
                    :multiple="false"
                    label="companyName"
                    trackBy="id"
                    moduleName="companies"
                    :key="company?.id"
                    @asyncSearch="companyOptions = companies?.data"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <MultiSelectInput
                    class=""
                    :textLabel="$t('AMS')"
                    v-model="ams"
                    :key="ams"
                    :options="amsList"
                    :multiple="false"
                    label="amsNumber"
                    trackBy="id"
                    @update:model-value="setTotalRemainingHours"
                    :isDisabled="
                        (amsList?.length ?? 0) == 1 || actionType === 'edit'
                    "
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <!-- Time spent on the given task, if edit and not 'new entry' accounted time needs to be disabled -->
                <number-input
                    :isReadonly="true"
                    :roundNearQuarter="true"
                    :forceLeftBound="true"
                    :showPrefix="false"
                    v-model="accountedTime"
                    :min="0"
                    class=""
                    :required="true"
                    :label="$t('Accounted Time')"
                    :error="$t(modalErrors.accountedTime)"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <!-- Total Remaining Hours of the selected customer from AMS -->
                <number-input
                    :roundNearQuarter="true"
                    :forceLeftBound="true"
                    :showPrefix="false"
                    v-model="totalRemainingHours"
                    :isReadonly="true"
                    :min="0"
                    class=""
                    :label="$t('Total Remaining Hours')"
                    :error="$t(modalErrors.totalRemainingHours)"
                />
            </div>
        </div>
        <div class="w-full col-span-2">
            <div class="form-group">
                <text-area-input
                    :required="false"
                    v-model="internalNote"
                    :rows="3"
                    class=""
                    :label="$t('Internal Note')"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="checkbox-group">
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
        ...mapGetters("tickets", ["tickets"]),
    },
    data() {
        return {
            totalRemainingHours: 0,
            ams: null,
            amsList: [],
            selectedDate: "",
            moduleId: "",
            description: "",
            accountedTime: "0.00",
            company: "",
            companyOptions: [],
            displayCommentListing: false,
            recordId: "",
            ticket: "",
            internalNote: "",
            isGoodwill: false,
            selectedComments: [],
            timeTrackerCompanyData: [],
        };
    },
    watch: {
        selectedComments: {
            handler: function () {
                this.accountedTime = this.selectedComments.reduce(
                    (acc, curr) => {
                        return acc + +(curr?.time ?? 0);
                    },
                    0
                );
            },
            deep: true,
        },
    },
    async mounted() {
        this.selectedDate = this.date;
        await this.getTickets();
        await this.getCompanies();

        if (this.actionType === "edit") {
            this.moduleId = this.timeTrackerCompanyEditData.moduleId;
            this.getTicketByComment();
            this.description = this.timeTrackerCompanyEditData.description;
            this.internalNote = this.timeTrackerCompanyEditData.internalNote;
            this.accountedTime = this.timeTrackerCompanyEditData.time;
            this.isGoodwill =
                this.timeTrackerCompanyEditData.isGoodwill == 1 ? true : false;
            this.company = this.timeTrackerCompanyEditData.company;
            this.fetchCompanyAMS();
        }
    },
    methods: {
        async setTotalRemainingHours() {
            await this.$nextTick();
            this.totalRemainingHours = this.ams?.remainingHours ?? 0;
        },
        checkAllComments(event) {
            this.selectedComments = event.target.checked
                ? this.ticketComments
                : [];

            const checkboxes = document.querySelectorAll(".comment-checkbox");
            checkboxes.forEach((checkbox) => {
                checkbox.checked = event.target.checked;
            });
        },
        toggleComment(event, index) {
            if (event.target.checked) {
                this.selectedComments.splice(0, this.selectedComments.length);
                this.selectedComments.push(this.ticketComments[index]);
            } else {
                const indexToRemove = this.selectedComments.indexOf(
                    this.ticketComments[index]
                );
                if (indexToRemove > -1) {
                    // create a new array that excludes the element to remove
                    this.selectedComments = [
                        ...this.selectedComments.slice(0, indexToRemove),
                        ...this.selectedComments.slice(indexToRemove + 1),
                    ];
                }
            }
        },
        async getTickets() {
            try {
                let payload = {
                    perPage: 25,
                    page: 1,
                    isArchived: false,
                    sortBy: "created_at",
                    sortOrder: "desc",
                    unaccountedTickets: true,
                };
                if (this.filterCompany.id !== "all") {
                    payload.companyId = this.filterCompany.id;
                }
                await this.$store.dispatch("tickets/list", payload);
            } catch (e) {
                console.log(e);
            }
        },
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
         * fetches the AMS record using show API for the selected company and sets the 'totalRemainingHours' to the remainingHours from
         * the response
         */
        async fetchCompanyAMS() {
            try {
                const response = await this.$store.dispatch(
                    "ams/getByCustomer",
                    this.company?.id
                );
                this.amsList = response?.data?.data ?? [];
                if (
                    this.actionType === "edit" &&
                    this.timeTrackerCompanyEditData?.additionalInfo?.amsId
                ) {
                    this.ams =
                        this.amsList.find(
                            (ams) =>
                                ams.id ==
                                this.timeTrackerCompanyEditData?.additionalInfo
                                    ?.amsId
                        ) ?? null;
                }
                if (this.actionType === "add" && this.amsList?.length == 1) {
                    this.ams = this.amsList[0];
                }
                this.totalRemainingHours = this.ams?.remainingHours ?? 0;
            } catch (e) {
                console.log(e);
            }
        },
        async getTicketByComment() {
            let response = await this.$store.dispatch(
                "tickets/getTicketByComment",
                this.moduleId
            );
            this.ticket = response?.data?.ticket;
        },
        async getTicketComments(event) {
            this.displayCommentListing = false;
            this.company = this.companyOptions.find(
                (company) => company.id == event.companyId
            );
            let response = await this.$store.dispatch(
                "tickets/getTicketComments",
                {
                    date: this.selectedDate,
                    objectId: event.id,
                    userId: this.userId,
                }
            );
            this.ticketComments = response?.data?.data;
            this.displayCommentListing = true;
            this.fetchCompanyAMS();
            this.$nextTick;
        },
    },
};
</script>

<template>
    <div class="grid items-center grid-cols-3 gap-6">
        <div class="w-full">
            <div class="form-group">
                <text-input v-model="form.search" :label="$t('Search Term')" />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <MultiSelectInput
                    v-model="form.status"
                    :options="statuses"
                    :multiple="false"
                    :textLabel="$t('Status')"
                    label="label"
                    :trackBy="'id'"
                    :custom-label="customStatusLabel"
                    :error="errors.status"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <MultiSelectInput
                    :textLabel="$t('Type Of Absence')"
                    v-model="form.leaveType"
                    :options="typesOfAbsence"
                    :custom-label="customStatusLabel"
                    :multiple="false"
                    label="label"
                    :trackBy="'id'"
                    :error="errors.leaveType"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <DateInput v-model="form.startDate" :label="$t('Date From')" />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <DateInput v-model="form.endDate" :label="$t('Date To')" />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <MultiSelectInput
                    v-if="$hasRole('admin')"
                    :show-labels="false"
                    v-model="selectedUser"
                    :key="selectedUser"
                    :options="users?.data ?? []"
                    :multiple="false"
                    :textLabel="$t('User')"
                    :label="$t('firstName')"
                    @update:model-value="refresh()"
                    :customLabel="customLabel"
                    trackBy="id"
                    moduleName="userProfile"
                    :showLoadMoreText="true"
                >
                    <template #beforeList>
                        <div
                            class="grid p-2 gap-2"
                            style="grid-template-columns: 25% 25% 50%"
                        >
                            <p class="font-bold">
                                {{ $t("First Name") }}
                            </p>
                            <p class="font-bold">
                                {{ $t("Last Name") }}
                            </p>
                            <p class="font-bold">{{ $t("Email") }}</p>
                        </div>
                        <hr />
                    </template>
                    <template #singleLabel="{ props }">
                        {{ props.option.firstName }}
                        {{ props.option.lastName }}
                    </template>
                    <template #option="{ props }">
                        <div
                            class="grid gap-2"
                            style="grid-template-columns: 25% 25% 50%"
                        >
                            <p class="overflow-text-users-table">
                                {{ props.option.firstName }}
                            </p>
                            <p class="overflow-text-users-table">
                                {{ props.option.lastName }}
                            </p>
                            <p class="overflow-text-users-table">
                                {{ props.option.email }}
                            </p>
                        </div>
                    </template>
                </MultiSelectInput>
            </div>
        </div>
        <div class="w-full col-span-3">
            <div class="flex justify-end">
                <button @click="refresh()" class="secondary-btn">
                    {{ $t("Search") }}
                </button>
            </div>
        </div>
    </div>
    <div class="table-responsive mt-5">
        <table class="doc-table">
            <tr class="text-left font-bold">
                <th
                    class="cursor-pointer"
                    @click="sort('startDate', 'employeeVacations')"
                >
                    {{ $t("Start Date") }}
                    <font-awesome-icon
                        v-if="form.sortBy === 'startDate'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
                <th
                    class="cursor-pointer"
                    @click="sort('endDate', 'employeeVacations')"
                >
                    {{ $t("End Date") }}
                    <font-awesome-icon
                        v-if="form.sortBy === 'endDate'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
                <th
                    class="cursor-pointer"
                    @click="sort('requiredVacationDays', 'employeeVacations')"
                >
                    {{ $t("Required Vacation Days") }}
                    <font-awesome-icon
                        v-if="form.sortBy === 'requiredVacationDays'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
                <th
                    class="cursor-pointer"
                    @click="sort('isSpecialLeave', 'employeeVacations')"
                >
                    {{ $t("Leave Type") }}
                    <font-awesome-icon
                        v-if="form.sortBy === 'isSpecialLeave'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
                <th
                    class="cursor-pointer"
                    @click="sort('isPaid', 'employeeVacations')"
                >
                    {{ $t("Type") }}
                    <font-awesome-icon
                        v-if="form.sortBy === 'isPaid'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
                <th
                    class="cursor-pointer"
                    @click="sort('specialLeaveComments', 'employeeVacations')"
                >
                    {{ $t("Remarks") }}
                    <font-awesome-icon
                        v-if="form.sortBy === 'specialLeaveComments'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
                <th class="">{{ $t("Status") }}</th>
                <th class="">{{ $t("Action") }}</th>
            </tr>
            <tr
                v-for="request in vacationRequests?.data ?? []"
                :key="request.id"
                class="hover:bg-gray-100 focus-within:bg-gray-100"
            >
                <td class="">
                    <a
                        href="javascript:void(0)"
                        class="flex items-center cursor-pointer focus:text-indigo-500"
                    >
                        {{ $dateFormatter(request.startDate, globalLanguage) }}
                    </a>
                </td>
                <td class="">
                    <a
                        href="javascript:void(0)"
                        class="flex items-center focus:text-indigo-500"
                    >
                        {{ $dateFormatter(request.endDate, globalLanguage) }}
                    </a>
                </td>
                <td class="">
                    <a
                        href="javascript:void(0)"
                        class="flex items-center focus:text-indigo-500"
                    >
                        {{ request.requiredVacationDays }}
                    </a>
                </td>
                <td class="">
                    <a
                        href="javascript:void(0)"
                        class="flex items-center focus:text-indigo-500"
                    >
                        {{
                            request.isSpecialLeave == 1
                                ? $t("Special")
                                : request.leaveType === "illness"
                                ? $t(request.leaveType)
                                : $t("Vacation")
                        }}
                    </a>
                </td>
                <td class="">
                    <a
                        href="javascript:void(0)"
                        class="flex items-center focus:text-indigo-500"
                    >
                        {{
                            request.leaveType === "illness"
                                ? ""
                                : request.isPaid
                                ? "Paid"
                                : "Unpaid"
                        }}
                    </a>
                </td>
                <td class="">
                    <a
                        href="javascript:void(0)"
                        class="capitalize flex items-center focus:text-indigo-500 input-box-length"
                    >
                        {{ request.remarks }}
                    </a>
                </td>
                <td class="">
                    <a
                        href="javascript:void(0)"
                        class="capitalize flex items-center focus:text-indigo-500"
                    >
                        {{
                            request.leaveType === "illness"
                                ? $t("illness")
                                : $t(request.status)
                        }}
                    </a>
                </td>
                <td class="">
                    <div class="flex gap-3">
                        <button @click="viewVacationDetails(request.id)">
                            <font-awesome-icon
                                icon="fa-solid fa-eye"
                            />
                        </button>
                        <button
                            v-if="request.status !== 'approved'"
                            @click="editVacationDetails(request.id)"
                        >
                            <font-awesome-icon
                                icon="fa-solid fa-pencil"
                            />
                        </button>
                        <button
                            v-if="request.status !== 'approved'"
                            @click="deleteVacation(request.id)"
                        >
                            <font-awesome-icon
                                icon="fa-solid fa-trash"
                            />
                        </button>

                        <button
                            v-if="request.status !== 'cancelled' "
                            @click="cancelVacation(request.id)"
                        >
                        <i class="fa fa-times" aria-hidden="true"></i>
                        </button>

                    </div>
                </td>
            </tr>
            <tr v-if="(vacationRequests?.data?.length ?? 0) === 0">
                <td class=" " colspan="4">
                    {{ $t("No vacation requests found.") }}
                </td>
            </tr>
        </table>
    </div>
    <div style="margin-top: 3rem" class="flex justify-center">
        <pagination
            :limit="10"
            align="center"
            :data="vacationRequests"
            @pagination-change-page="changePageOrSearch"
        ></pagination>
        <br />
        <br />
    </div>

    <!-- vacation details/show -->
    <VacationDetails
        v-if="toggleVacationModal"
        :modalData="modalData"
        @toggleVacationModal="toggleVacationModal = $event"
    />

    <EditModal
        :toggleVacationEditModal="toggleVacationEditModal"
        @toggleVacationEditModal="toggleVacationEditModal = $event"
        @refresh="refresh()"
        :modalData="modalData"
    ></EditModal>

    <CancelVacation
        v-if="selectedVacationId"
        :toggleCancelVacationModal="toggleCancelVacationModal"
        :selectedVacationId="selectedVacationId"
        @toggleCancelVacationModal="toggleCancelVacationModal = $event"

        @refresh="refresh()"
        :modalData="modalData"
    ></CancelVacation>

</template>

<script>
import EditModal from "./EditModal.vue";
import CancelVacation from "./CancelVacation.vue";
import TextInput from "../../Components/TextInput.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import DateInput from "../../Components/DateInput.vue";
import Pagination from "laravel-vue-pagination";
import Modal from "../../Components/EditModal.vue";
import VacationDetails from "./VacationDetails.vue";
import { mapGetters } from "vuex";
import userProfilePicturesMixin from "@/Mixins/userProfilePicturesMixin";

export default {
    mixins: [userProfilePicturesMixin],
    computed: {
        ...mapGetters(["errors"]),
        ...mapGetters("vacationRequest", ["vacationRequests"]),
        ...mapGetters("auth", ["user"]),
        ...mapGetters("userProfile", {
            users: "userProfiles",
        }),
        // filter the user listing without the currently logged in user or selectedUser
        usersFilteredWithoutCurrentUser() {
            return (
                this.users?.data?.filter(
                    (user) =>
                        user.userId !=
                        (this.selectedUser?.userId ?? this.user?.id)
                ) ?? this.users?.data
            );
        },
    },
    components: {
        EditModal,
        TextInput,
        MultiSelectInput,
        DateInput,
        Pagination,
        Modal,
        VacationDetails,
        CancelVacation
    },
    unmounted() {
        // reset the selectedUser on unmount
        this.selectedUser = null;
    },
    mounted() {
        // auto select the currently logged in user
        this.selectedUser =
            this.users?.data?.find((user) => user.userId == this.user?.id) ??
            null;
        this.refresh();
    },
    data() {
        return {
            toggleCancelVacationModal:false,
            selectedVacationId:'',
            selectedUser: null, // the user selected from user dropdown by an admin user
            page: 1,
            toggleVacationEditModal: false,
            statuses: [
                {
                    id: 1,
                    value: "approved",
                    label: "Approved",
                },
                {
                    id: 2,
                    value: "pending",
                    label: "Pending",
                },
                {
                    id: 3,
                    value: "rejected",
                    label: "Rejected",
                },
            ],
            typesOfAbsence: [
                {
                    id: 1,
                    value: "whole",
                    label: "Whole",
                },
                {
                    id: 2,
                    value: "half",
                    label: "Half",
                },
            ],
            toggleVacationModal: false,
            vacationTypes: [],
            form: {
                leaveType: null,
                status: null,
                starDate: null,
                endDate: null,
                search: "",
                sortBy:
                    localStorage.getItem("sort_employeeVacations_column") ??
                    "startDate",
                sortOrder:
                    localStorage.getItem("sort_employeeVacations_order") ??
                    "asc",
            },
            orderForm: {},
            modalData: {},
        };
    },
    watch: {
        "form.sortOrder": function () {
            this.refresh();
        },
    },
    methods: {
        /**
         * formats the shown name in the users dropdown
         * @param {props} data of the selected options
         */
        customLabel(props) {
            return `${props?.firstName ?? ""} ${props?.lastName ?? ""}`;
        },
        cancelVacation(id){
         this.selectedVacationId=id;
         this.toggleCancelVacationModal=true;
        },
        /** deletes the vacation request
         * @param {id} the id of the vacation request to be deleted
         */
        async deleteVacation(id) {
            try {
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
                            "vacationRequest/destroy",
                            id
                        );
                        this.refresh();
                        this.$emitter.emit("search", true); // refreshes the calendar data in vacation calendar
                    }
                });
            } catch (e) {
                console.log(e);
            }
        },
        customStatusLabel(props) {
            return this.$t(props?.label);
        },
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("vacationRequest/list", {
                page: this.page,
                search: this.form.search,
            });
        },
        async editVacationDetails(vacationId) {
            this.toggleVacationEditModal = true;
            const response = await this.$store.dispatch(
                "vacationRequest/show",
                vacationId
            );
            this.modalData = response?.data?.data ?? {};
            this.modalData = {
                ...this.modalData,
                userId: this.selectedUser?.userId ?? this.user?.id, // set the userId to selectedUser or the currently logged in user
            };
            this.getUserProfilePictures(
                this.modalData?.replacements ?? [],
                "id"
            );
        },
        async viewVacationDetails(vacationId) {
            this.toggleVacationModal = true;
            const response = await this.$store.dispatch(
                "vacationRequest/show",
                vacationId
            );
            this.modalData = response?.data?.data ?? {};
            this.getUserProfilePictures(
                this.modalData?.approvers ?? [],
                "userId"
            );
        },
        async refresh() {
            await this.$nextTick();
            try {
                this.$store.commit("showLoader", true);
                await this.$store.dispatch("vacationRequest/list", {
                    page: 1,
                    perPage: 25,
                    ...this.form,
                    leaveType: this.form.leaveType?.value,
                    status: this.form.status?.value,
                    userId: this.selectedUser?.userId ?? this.user?.id, // filter by selectedUser or logged in user
                });
            } catch (e) {
                console.log(e);
            } finally {
                this.$store.commit("showLoader", false);
            }
        },
    },
};
</script>

<template>
    <div>
        <div class="flex flex-wrap">
          
            <MultiSelectInput
                v-model="form.status"
                :options="statuses"
                :multiple="true"
                textLabel="Status"
                label="label"
                :trackBy="'id'"
                class="mb-5 w-1/3 px-2"
                :error="errors.status"
            />
            <div v-if="$hasRole('admin')" class="mb-5 w-1/3 px-2 relative">
                <MultiSelectInput
                    :show-labels="false"
                    v-model="selectedUser"
                    :options="users?.data ?? []"
                    :multiple="false"
                    :textLabel="$t('User')"
                    :label="$t('firstName')"
                    :key="selectedUser"
                    @update:model-value="
                        isAll = false;
                        refresh();
                    "
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
                <div style="top: 0; right: 8px" class="absolute">
                    <label class="mr-2" for="all">{{ $t("All") }}</label>
                    <input
                        :checked="isAll"
                        @input="selectAll"
                        id="all"
                        type="checkbox"
                    />
                </div>
            </div>
        </div>
        <div class="bg-white rounded-md shadow overflow-x-auto">
            <table class="w-full">
                <tr class="text-left font-bold">
                    <th
                        class="p-2 cursor-pointer"
                        @click="sort('requester', 'requestVacations')"
                    >
                        {{ $t("Requester") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'requester'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('startDate', 'requestVacations')"
                        class="p-2 cursor-pointer"
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
                        @click="sort('endDate', 'requestVacations')"
                        class="p-2 cursor-pointer"
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
                        @click="
                            sort('requiredVacationDays', 'requestVacations')
                        "
                        class="pb-4 pt-6 px-6 cursor-pointer"
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
                        @click="
                            sort('overtimeTaken', 'requestVacations')
                        "
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Overtime Taken") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'overtimeTaken'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('leaveType', 'requestVacations')"
                        class="p-2 cursor-pointer"
                    >
                        {{ $t("Leave Type") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'leaveType'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th class="p-2 cursor-pointer">
                        {{ $t("Type") }}
                    </th>
                    <th
                        class="p-2 cursor-pointer"
                        @click="sort('replacements', 'requestVacations')"
                    >
                        {{ $t("Replacements") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'replacements'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('status', 'requestVacations')"
                        class="p-2 cursor-pointer"
                    >
                        {{ $t("Status") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'status'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th class="p-2">{{ $t("Actions") }}</th>
                </tr>
                <tr
                    v-for="(item, index) in requestsForApproval.data"
                    :key="item.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t p-2">
                        <span
                            v-if="item.requester == ' '"
                            style="color: red"
                            class="cursor-pointer"
                            :title="'No profile exist'"
                        >
                            <font-awesome-icon
                                icon="fa-solid fa-exclamation-triangle"
                                class="tooltip"
                            />&nbsp;</span
                        >
                        <span v-else>
                            {{ item.requester }}
                        </span>
                    </td>
                    <td class="border-t p-2">
                        {{ item.startDate }}
                    </td>
                    <td class="border-t p-2">
                        {{ item.endDate }}
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ item.requiredVacationDays }}
                        </a>
                    </td>
                    <td class="border-t p-2">
                        {{ item.overtimeTaken }}
                    </td>
                    <td class="border-t p-2">
                        {{ item.leaveType }}
                    </td>
                    <td class="border-t p-2">
                        {{ item.isPaid ? "Paid" : "Unpaid" }}
                    </td>
                    <td class="border-t p-2">
                        <p class="input-box-length">{{ item.replacements }}</p>
                    </td>
                    <td class="w-px border-t p-2">
                        <p
                            class="capitalize"
                            v-if="
                                item.status === 'approved' ||
                                item.status === 'rejected'
                            "
                        >
                            {{ item.status }}
                        </p>
                        <button
                            @click="openModal(item, index)"
                            v-else
                            class="docsHeroColorBlue py-2 px-3 rounded"
                        >
                            Change Status
                        </button>
                    </td>
                    <td class="border-t">
                        <div class="flex items-center gap-2">
                            <button
                                @click="
                                    viewVacationDetails(item.employeeVacationId)
                                "
                            >
                                <font-awesome-icon
                                    icon="fa-solid fa-eye py-4"
                                />
                            </button>

                            <button
                                v-if="$can(`${$route.meta.permission}.delete`)"
                                @click="deleteVacationDetails(item.id)"
                            >
                                <font-awesome-icon
                                    icon="fa-solid fa-trash py-4"
                                />
                            </button>
                        </div>
                    </td>
                </tr>
                <tr v-if="(requestsForApproval?.data?.length ?? 0) === 0">
                    <td class="px-6 py-4 border-t" colspan="4">
                        {{ $t("No Requests found") }}.
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="requestsForApproval"
                @pagination-change-page="changePageOrSearch"
            ></pagination>
            <br />
            <br />
        </div>
    </div>
    <Modal
        title="Update Status"
        v-if="toggleModal"
        @toggleModal="toggleModal = $event"
        width="50%"
    >
        <template #body>
            <div class="flex flex-col ml-6 mr-6 border mb-3 p-3">
                <SelectInput
                    :error="errors.status"
                    class="w-1/2"
                    label="Status"
                    :required="true"
                    v-model="selectedItem.status"
                >
                    <option value="approved">
                        {{ $t("Approved") }}
                    </option>
                    <option value="rejected">
                        {{ $t("Rejected") }}
                    </option>
                    <option value="pending">{{ $t("Pending") }}</option>
                </SelectInput>
                <TextareaInput
                    :error="errors.remarks"
                    class="mt-2"
                    label="Remarks"
                    v-model="selectedItem.remarks"
                />
            </div>
        </template>
        <template #footer>
            <button @click="changeStatus()" type="button" class="secondary-btn">
                {{ $t("Update") }}
            </button>
            <button
                @click="toggleModal = false"
                type="button"
                class="primary-btn mr-3"
            >
                {{ $t("Cancel") }}
            </button>
        </template>
    </Modal>

    <!-- vacation details/show -->
    <VacationDetails
        v-if="toggleVacationModal"
        :modalData="modalData"
        @toggleVacationModal="toggleVacationModal = $event"
    />
</template>

<script>
import Pagination from "laravel-vue-pagination";
import SelectInput from "../../Components/SelectInput.vue";
import TextareaInput from "../../Components/TextareaInput.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import Modal from "../../Components/EditModal.vue";
import VacationDetails from "./VacationDetails.vue";
import { mapGetters } from "vuex";
import userProfilePicturesMixin from "@/Mixins/userProfilePicturesMixin";

export default {
    mixins: [userProfilePicturesMixin],
    computed: {
        ...mapGetters("vacationRequest", ["requestsForApproval"]),
        ...mapGetters(["errors"]),
        ...mapGetters("auth", ["user"]),
        ...mapGetters("userProfile", {
            users: "userProfiles",
        }),
    },
    components: {
        TextareaInput,
        Modal,
        SelectInput,
        Pagination,
        MultiSelectInput,
        VacationDetails,
    },
    props: {
        filters: Object,
        can: Object,
    },
    data() {
        return {
            modalData: {}, // contains vacation details
            toggleVacationModal: false, // is used to toggle vacation details modal
            isAll: false, // checks if all the approver listing should be fetched regardless of selected user
            selectedUser: null, // the user selected by admin for which he would like to view the approval requests
            page: 1,
            selectedItem: null,
            toggleModal: false,
            form: {
                status: [
                    {
                        id: 2,
                        value: "pending",
                        label: "Pending",
                    },
                ],
                sortBy:
                    localStorage.getItem("sort_requestVacations_column") ??
                    "startDate",
                sortOrder:
                    localStorage.getItem("sort_requestVacations_order") ??
                    "asc",
            },
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
        };
    },
    watch: {
        "form.sortOrder": function () {
            this.refresh();
        },
        "form.status": function () {
            this.refresh();
        },
    },
    mounted() {
        // select the logged in user by default
        this.selectedUser =
            this.users?.data?.find((user) => user.userId == this.user?.id) ??
            null;
        this.refresh();
    },
    unmounted() {
        // reset the selected user on unmount
        this.selectedUser = null;
    },
    methods: {
        /**
         * fetches and sets the vacation details to 'modalData'
         * toggles vacation details modal
         * @param {vacationId} vacation id for which the details is to be fetched
         */
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

        /**
         * Delete the vacation approves and detail
         * @param {id} approvers id
         */
        async deleteVacationDetails(id) {
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
                            "vacationRequest/approvalDestroy",
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
        /**
         * set 'isAll' to true/false and resets the selectedUser
         * refreshes the requests for approval listing
         * @param {event} input event
         */
        selectAll(event) {
            this.selectedUser = null;
            this.isAll = event.target.checked;
            this.refresh();
        },
        /**
         * formats the shown name in the users dropdown
         * @param {props} data of the selected options
         */
        customLabel(props) {
            return `${props?.firstName ?? ""} ${props?.lastName ?? ""}`;
        },
        openModal(item, index) {
            this.selectedItem = { ...item };
            this.selectedIndex = index;
            this.toggleModal = true;
        },
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("vacationRequest/requestsForApproval", {
                page: this.page,
                search: this.form.search,
                userId: this.selectedUser?.userId ?? this.user?.id, // approval requests filtered by selected user or logged in user
            });
        },
        async changeStatus() {
            await this.$nextTick();
            const payload = {
                id: this.selectedItem.id,
                status: this.selectedItem.status,
                remarks: this.selectedItem.remarks,
            };
            try {
                await this.$store.dispatch(
                    "vacationRequest/setApprovalStatus",
                    payload
                );
                this.toggleModal = false;
                this.selectedItem = null;
                this.errors = {};
                this.refresh();
            } catch (e) {}
        },
        async refresh() {
            try {
                this.$store.commit("showLoader", true);
                await this.$store.dispatch(
                    "vacationRequest/requestsForApproval",
                    {
                        ...this.form,
                        status: this.form.status.map((status) => status.value),
                        userId: this.isAll //if isAll is true then fetch all the records
                            ? null
                            : this.selectedUser?.userId ?? this.user?.id, // approval requests filtered by selected user or logged in user
                    }
                );
            } catch (e) {
            } finally {
                this.$store.commit("showLoader", false);
            }
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
.table-layout-fixed {
    table-layout: fixed;
}
</style>

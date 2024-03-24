<template>
    <Modal
        :title="$t('Vacation details')"
        width="50%"
        @toggleModal="$emit('toggleVacationModal', $event)"
    >
        <template #body>
            <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                <!--      replacements
                  specialLeaveComments -->
                <div class="pb-8 pr-6 w-full lg:w-1/3">
                    <label class="form-label">{{ $t("Leave Type") }}:</label>
                    <p class="capitalize">
                        {{
                            this.modalData.isSpeacialLeave
                                ? $t("Special")
                                : this.modalData.leaveType === "illness"
                                ? $t(this.modalData.leaveType)
                                : $t("Vacation")
                        }}
                    </p>
                </div>
                <div class="pb-8 pr-6 w-full lg:w-1/3">
                    <label class="form-label">{{ $t("Start Date") }}:</label>
                    <p class="capitalize">
                        {{
                            $dateFormatter(modalData.startDate, globalLanguage)
                        }}
                    </p>
                </div>
                <div
                    v-if="this.modalData.endDate"
                    class="pb-8 pr-6 w-full lg:w-1/3"
                >
                    <label class="form-label">{{ $t("End Date") }}:</label>
                    <p class="capitalize">
                        {{
                            $dateFormatter(
                                this.modalData.endDate,
                                globalLanguage
                            )
                        }}
                    </p>
                </div>
                <div
                    v-if="this.modalData.startTime"
                    class="pb-8 pr-6 w-full lg:w-1/3"
                >
                    <label class="form-label">{{ $t("Start Time") }}:</label>
                    <p class="capitalize">
                        {{ this.modalData.startTime ?? "-" }}
                    </p>
                </div>
                <div
                    v-if="this.modalData.endTime"
                    class="pb-8 pr-6 w-full lg:w-1/3"
                >
                    <label class="form-label">{{ $t("End Time") }}:</label>
                    <p class="capitalize">
                        {{ this.modalData.endTime ?? "-" }}
                    </p>
                </div>
                <div class="pb-8 pr-6 w-full lg:w-1/3">
                    <label class="form-label">{{ $t("Replacements") }}:</label>
                    <p
                        v-for="replacement in this.modalData?.replacements ??
                        []"
                        :key="'replacement-' + replacement?.id"
                        class="capitalize"
                    >
                        {{ replacement.name }}
                    </p>
                </div>
                <div
                    v-if="this.modalData.specialLeaveType"
                    class="pb-8 pr-6 w-full lg:w-1/3"
                >
                    <label class="form-label"
                        >{{ $t("Special Leave Type") }}:</label
                    >
                    <p class="capitalize">
                        {{ this.modalData.specialLeaveType ?? "-" }}
                    </p>
                </div>
                <div class="pb-8 pr-6 w-full lg:w-1/3">
                    <label class="form-label"
                        >{{ $t("Special Leave Comments") }}:</label
                    >
                    <p>{{ this.modalData.specialLeaveComments ?? "-" }}</p>
                </div>

                <div
                    v-if="this.modalData.requiredVacationDays"
                    class="pb-8 pr-6 w-full lg:w-1/3"
                >
                    <label class="form-label">{{ $t("Required Vacation Days") }}:</label>
                    <p class="capitalize">
                        {{ this.modalData.requiredVacationDays ?? "-" }}
                    </p>
                </div>

                <div
                    v-if="this.modalData.overtimeTaken"
                    class="pb-8 pr-6 w-full lg:w-1/3"
                >
                    <label class="form-label">{{ $t("Taken From Overtime Hours") }}:</label>
                    <p class="capitalize">
                        {{ this.modalData.overtimeTaken ?? "-" }}
                    </p>
                </div>


                <div class="pb-8 pr-6 w-full">
                    <label class="form-label">{{ $t("Approvers") }}:</label>
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                >
                                    Name
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                >
                                    Email
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                >
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(
                                    approver, index
                                ) in modalData?.approvers ?? []"
                                :key="'approver-' + index"
                            >
                                <td
                                    class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                >
                                    <div
                                        style="display: flex !important"
                                        class="items-center"
                                    >
                                        <div
                                            class="flex justify-center items-center"
                                        >
                                            <div
                                                class="flex justify-center items-center relative"
                                                :style="{
                                                    backgroundImage:
                                                        'url(' +
                                                        (userProfilePictures?.[
                                                            approver?.userId
                                                        ]?.profile_image ?? '') +
                                                        ')',
                                                }"
                                                style="
                                                    background-position: center
                                                        center;
                                                    background-repeat: no-repeat;
                                                    background-size: cover;
                                                    min-height: 40px;
                                                    min-width: 40px;
                                                    border-radius: 50%;
                                                    background-color: #ed7d31;
                                                    color: white;
                                                    overflow: hidden;
                                                "
                                            >
                                                <p
                                                    style="font-size: 0.9rem"
                                                    v-if="
                                                        approver?.name &&
                                                        !userProfilePictures?.[
                                                            approver?.userId
                                                        ]
                                                    "
                                                >
                                                    {{
                                                        getInitials(
                                                            approver.name
                                                        )
                                                    }}
                                                </p>
                                            </div>
                                        </div>
                                        <span
                                            style="text-transform: capitalize"
                                            class="hidden md:inline ml-2"
                                            >{{ approver.name }}
                                        </span>
                                    </div>
                                </td>
                                <td
                                    class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                >
                                    {{ approver.email }}
                                </td>
                                <td
                                    class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                                >
                                    {{ approver.status }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </template>
        <template #footer>
            <button
                type="button"
                class="secondary-btn"
                @click="$emit('toggleVacationModal', false)"
            >
                <span class="flex">{{ $t("Close") }}</span>
            </button>
        </template>
    </Modal>
</template>

<script>
import Modal from "@/Components/EditModal.vue";
import userProfilePicturesMixin from "@/Mixins/userProfilePicturesMixin";
import { mapGetters } from "vuex";

export default {
    mixins: [userProfilePicturesMixin],
    emits: ["toggleVacationModal"],
    props: {
        // vacation details
        modalData: {
            type: Object,
            default: () => ({}),
        },
    },
    components: {
        Modal,
    },
    computed: {
        ...mapGetters("auth", ["userProfilePictures"]),
    },
    methods: {
        /**
         * Retrieves the initials from a string based on whitespace
         * @param {name} the string to fetch the initials from
         */
        getInitials(name) {
            const tokens = name?.split(" ");
            if (tokens)
                return `${tokens?.[0]?.[0] ?? ""}${
                    tokens?.[1]?.[0] ?? ""
                }`.toUpperCase();
            else return "";
        },
    },
};
</script>

<style scoped></style>

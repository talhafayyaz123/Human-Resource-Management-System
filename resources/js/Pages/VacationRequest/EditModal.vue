<template>
    <Modal
        :title="$t('Edit Vacation details')"
        :classSize="'modal-md'"
        v-if="toggleVacationEditModal"
        @toggleModal="$emit('toggleVacationEditModal', false)"
    >
        <template #body>
            <div class="grid items-center grid-cols-2 gap-6">
                <div class="w-full">
                    <div class="form-group">
                        <input
                            :checked="form.leaveType === 'whole'"
                            v-model="form.leaveType"
                            name="leaveType"
                            id="full-day"
                            type="radio"
                            value="whole"
                        />
                        <label class="ml-2" for="full-day">{{
                            $t("Full Day")
                        }}</label>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <input
                            :checked="form.leaveType === 'half'"
                            v-model="form.leaveType"
                            name="leaveType"
                            id="half-day"
                            type="radio"
                            value="half"
                        />
                        <label class="ml-2" for="half-day">{{
                            $t("Half Day")
                        }}</label>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <label class="input-label"
                            ><span class="text-red-500">*&nbsp;</span
                            >{{ $t("Start Date") }}:</label
                        >
                        <datepicker
                            class="form-control"
                            :clearable="false"
                            :enable-time-picker="false"
                            auto-apply
                            :close-on-auto-apply="false"
                            v-model="form.startDate"
                            :class="errors.startDate ? 'error' : ''"
                            @update:model-value="calculateDaysOffAtATime"
                        />
                        <div v-if="errors?.endDate" class="form-error">
                            {{ errors.startDate }}
                        </div>
                    </div>
                </div>
                <div class="w-full" v-if="form.leaveType === 'whole'">
                    <div class="form-group">
                        <label class="input-label"
                            ><span class="text-red-500">*&nbsp;</span
                            >{{ $t("End Date") }}:</label
                        >
                        <datepicker
                            class="form-control"
                            :clearable="false"
                            :enable-time-picker="false"
                            auto-apply
                            :close-on-auto-apply="false"
                            v-model="form.endDate"
                            :class="errors.endDate ? 'error' : ''"
                            @update:model-value="calculateDaysOffAtATime"
                            :disabled="form.isSpecialLeave == 1"
                        />
                        <div v-if="errors?.endDate" class="form-error">
                            {{ errors.endDate }}
                        </div>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            :show-labels="false"
                            :required="true"
                            v-model="form.replacements"
                            :key="form.replacements"
                            :options="usersFilteredWithoutCurrentUser"
                            :multiple="true"
                            :textLabel="$t('Stand-In')"
                            :label="$t('firstName')"
                            :customLabel="customLabel"
                            trackBy="id"
                            moduleName="userProfile"
                            :showLoadMoreText="true"
                            :error="errors.replacements"
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
                        <div
                            v-for="(replacement, index) in form.replacements"
                            :key="'replacement-' + replacement.id"
                            class="flex justify-between items-center"
                        >
                            <div
                                class="flex items-center mt-3 mr-1 group-hover:text-orange-600 focus:text-orange-600 whitespace-nowrap"
                            >
                                <div class="flex justify-center items-center">
                                    <div
                                        class="flex justify-center items-center relative"
                                        :style="{
                                            backgroundImage:
                                                'url(' +
                                                (userProfilePictures?.[
                                                    replacement?.userId
                                                ]?.profile_image ?? '') +
                                                ')',
                                        }"
                                        style="
                                            background-position: center center;
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
                                                replacement?.firstName &&
                                                !userProfilePictures?.[
                                                    replacement?.userId
                                                ]
                                            "
                                        >
                                            {{
                                                getInitials(
                                                    (replacement?.firstName ??
                                                        "") +
                                                        " " +
                                                        (replacement?.lastName ??
                                                            "")
                                                )
                                            }}
                                        </p>
                                    </div>
                                </div>
                                <span
                                    style="text-transform: capitalize"
                                    class="hidden md:inline ml-2"
                                    >&nbsp;{{
                                        replacement?.firstName ?? ""
                                    }}&nbsp;{{ replacement?.lastName ?? "" }}
                                    <p class="ml-1 text-gray-500">
                                        {{ replacement?.departments ?? "-" }}
                                    </p>
                                </span>
                            </div>
                            <font-awesome-icon
                                icon="trash"
                                @click="removeReplacement(index)"
                                class="text-gray-500 cursor-pointer"
                            />
                        </div>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <label for="">{{ $t("Special Leave") }}:</label>
                        <div class="flex">
                            <div class="flex">
                                <input
                                    :checked="form.isSpecialLeave == 1"
                                    v-model="form.isSpecialLeave"
                                    name="special-leave"
                                    id="yes"
                                    type="radio"
                                    value="1"
                                />
                                <label class="ml-2" for="yes">{{
                                    $t("Yes")
                                }}</label>
                            </div>
                            <div class="flex ml-5">
                                <input
                                    @input="form.specialLeaveType = ''"
                                    :checked="form.isSpecialLeave == 0"
                                    v-model="form.isSpecialLeave"
                                    name="special-leave"
                                    id="no"
                                    type="radio"
                                    value="0"
                                />
                                <label class="ml-2" for="no">{{
                                    $t("No")
                                }}</label>
                            </div>
                        </div>
                        <p v-if="errors['isSpecialLeave']" class="text-red-500">
                            {{ errors["isSpecialLeave"] }}
                        </p>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <select-input
                            v-if="form.isSpecialLeave == 1"
                            :required="true"
                            :label="$t('Special Leave Type')"
                            v-model="form.specialLeaveType"
                            :error="errors.specialLeaveType"
                            @update:model-value="specialLeaveSelected"
                        >
                            <option value="injury">{{ $t("Injury") }}</option>
                            <option value="death-of-relative">
                                {{ $t("Death of a close relative (3 Days)") }}
                            </option>
                            <option value="own-wedding">
                                {{ $t("(own) wedding (1 Day)") }}
                            </option>
                            <option value="birth-of-child">
                                {{ $t("Birth of one's own child (1 Day)") }}
                            </option>
                        </select-input>
                    </div>
                </div>
                <div class="w-full col-span-2">
                    <div class="form-group">
                        <textarea-input
                            v-model="form.specialLeaveComments"
                            :label="$t('Comments')"
                        ></textarea-input>
                    </div>
                </div>
                <div class="w-full col-span-2">
                    <div
                        class="mt-3 bg-gray-100 grid grid-cols-[1fr,1fr,1fr] gap-2 p-2"
                    >
                        <div
                            class="flex flex-col justify-center items-center"
                            style="border-right: 1px solid black"
                        >
                            <p>{{ $t("Days Off At A Time") }}</p>
                            <p class="text-gray-500 font-bold">
                                {{ daysOffAtATime }} {{ $t("Day(s)") }}
                            </p>
                        </div>
                        <div
                            class="flex flex-col justify-center items-center"
                            style="border-right: 1px solid black"
                        >
                            <p>{{ $t("Required Vacation Days") }}</p>
                            <div class="flex">
                                <input
                                    @change="changeDaysOffAtAtime"
                                    style="max-width: 3rem"
                                    type="number"
                                    :min="form.leaveType === 'whole' ? 1 : 0.5"
                                    :max="differenceInDays"
                                    v-model="requiredVacationDays"
                                    :disabled="
                                        form.leaveType === 'half' ||
                                        form.isSpecialLeave == 1
                                    "
                                />
                                <p class="text-gray-500 font-bold ml-2">
                                    {{ $t("Day(s)") }}
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-col justify-center items-center">
                            <p>
                                {{ $t("Remaining Holidays") }} ({{
                                    currentYear
                                }})
                            </p>
                            <p class="text-gray-500 font-bold">
                                {{ remainingHolidays }} {{ $t("Day(s)") }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <button
                type="button"
                class="primary-btn mr-3"
                @click="$emit('toggleVacationEditModal', false)"
            >
                <span class="flex">Close</span>
            </button>
            <loading-button
                :loading="isLoading"
                @click="edit"
                class="secondary-btn"
                type="button"
            >
                {{ $t("Edit") }}
            </loading-button>
        </template>
    </Modal>
</template>

<script>
import DateInput from "../../Components/DateInput.vue";
import TextInput from "../../Components/TextInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import TextareaInput from "../../Components/TextareaInput.vue";
import Modal from "@/Components/EditModal.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import { mapGetters } from "vuex";
import userProfilePicturesMixin from "@/Mixins/userProfilePicturesMixin";

function addDays(date, days) {
    const d = new Date(date.valueOf());
    d.setDate(d.getDate() + days);
    return d;
}
Date.prototype.addDays = function (days) {
    return addDays(this, days);
};

export default {
    mixins: [userProfilePicturesMixin],
    emits: ["toggleVacationEditModal", "refresh", "search"],
    data() {
        return {
            daysOffAtATime: 0,
            requiredVacationDays: 0,
            form: {},
            profileData: {},
            expiryMonth: 0,
        };
    },
    async mounted() {
        try {
            if (!this.users.length)
                await this.$store.dispatch("userProfile/list", {
                    page: 1,
                    perPage: 25,
                });
            this.fetchProfileData();
        } catch (e) {
            console.log(e);
        }
    },
    computed: {
        ...mapGetters(["isLoading", "errors"]),
        ...mapGetters("auth", ["user", "userProfilePictures"]),
        ...mapGetters("userProfile", {
            users: "userProfiles",
        }),
        /**
         * calculates the remaining holidays
         */
        remainingHolidays() {
            // if special leave is selected then the remaining holidays will remain the same
            if (this.form.isSpecialLeave && this.form.specialLeaveType) {
                return +(this.profileData?.remainingLeaves ?? 0);
            }
            let totalHolidays =
                +(this.profileData?.remainingLeaves ?? 0) -
                +this.daysOffAtATime;
            if (this.expiryShouldCount) {
                totalHolidays +=
                    this.profileData?.totalHolidaysTakenPrevious ?? 0;
            }
            if (totalHolidays < 0) {
                this.additionalLeaves = totalHolidays;
                return 0;
            }
            return totalHolidays.toFixed(2);
        },
        currentYear() {
            return new Date().getFullYear();
        },
        usersFilteredWithoutCurrentUser() {
            return (
                this.users?.data?.filter(
                    (user) => user.userId != this.user?.id
                ) ?? this.users?.data
            );
        },
    },
    props: {
        toggleVacationEditModal: {
            type: Boolean,
            default: false,
        },
        modalData: {
            type: Object,
            default: () => ({}),
        },
    },
    methods: {
        /**
         * upon 'specialLeaveType' selection the end date must be set based on the selected special leave type
         */
        specialLeaveSelected() {
            if (this.form.specialLeaveType === "death-of-relative") {
                this.form.endDate = this.form.startDate.addDays(2);
                this.daysOffAtATime = this.requiredVacationDays = 3;
            } else if (this.form.specialLeaveType === "own-wedding") {
                this.form.endDate = this.form.startDate.addDays(0);
                this.daysOffAtATime = this.requiredVacationDays = 1;
            } else if (this.form.specialLeaveType === "birth-of-child") {
                this.form.endDate = this.form.startDate.addDays(0);
                this.daysOffAtATime = this.requiredVacationDays = 1;
            }
        },
        /**
         * checks if two days are the same
         * @param {date1} the first date
         * @param {date2} the second date
         */
        matchDates(date1, date2) {
            return (
                date1.getDate() == date2.getDate() &&
                date1.getMonth() == date2.getMonth() &&
                date1.getFullYear() == date2.getFullYear()
            );
        },
        /**
         * fetches the user profile data that is used to calculate the remaining days etc.
         */
        async fetchProfileData() {
            this.$store.dispatch("vacationRequest/profileData").then((res) => {
                this.profileData = { ...(res?.data ?? {}) };
            });
            const response = await this.$store.dispatch("globalSettings/list");
            this.expiryMonth = response?.data?.expiryMonth ?? 0;
        },
        /**
         * calculates the daysOffAtATime, requiredVacationDays
         */
        async calculateDaysOffAtATime() {
            await this.$nextTick();
            var date1 = new Date(this.form.startDate);
            var date2 = new Date(
                this.form.leaveType === "half"
                    ? this.form.startDate
                    : this.form.endDate
            );

            let weekEndDaysCount = 0;

            if (date1 && date2) {
                let startDate = date1;
                let endDate = date2;
                if (startDate.getDay() == 0 || startDate.getDay() == 6) {
                    weekEndDaysCount += 1;
                    startDate = startDate.addDays(1);
                    if (this.matchDates(startDate, endDate)) {
                        if (
                            startDate.getDay() == 0 ||
                            startDate.getDay() == 6
                        ) {
                            weekEndDaysCount += 1;
                        }
                    }
                }
                if (startDate <= endDate) {
                    let wentInsideTheLoop = false;
                    while (!this.matchDates(startDate, endDate)) {
                        wentInsideTheLoop = true;
                        if (
                            startDate.getDay() == 0 ||
                            startDate.getDay() == 6
                        ) {
                            weekEndDaysCount += 1;
                        }
                        startDate = startDate.addDays(1);
                    }
                    if (
                        this.matchDates(startDate, endDate) &&
                        wentInsideTheLoop
                    ) {
                        if (
                            startDate.getDay() == 0 ||
                            startDate.getDay() == 6
                        ) {
                            weekEndDaysCount += 1;
                        }
                    }
                }
            }

            if (this.form.leaveType === "half") {
                date1.setHours(
                    this.form.startTime?.split(":")?.[0] ?? "00",
                    this.form.startTime?.split(":")?.[1] ?? "00",
                    "00"
                );
                date2.setHours(
                    this.form.endTime?.split(":")?.[0] ?? "00",
                    this.form.endTime?.split(":")?.[1] ?? "00",
                    "00"
                );
            }

            // To calculate the time difference of two dates
            var differenceInTime = date2.getTime() - date1.getTime();

            const workDay = this.jobData?.workHours?.find((day) => {
                return day.days?.[0] === this.weekDays[date1.getDay()];
            });

            // To calculate the no. of days between two dates
            this.differenceInDays = 0;
            if (this.form.leaveType === "whole") {
                this.differenceInDays = differenceInTime / (1000 * 3600 * 24);
                this.differenceInDays += 1; // since we are calculating the difference between start and end date, there is always a difference of 1, this offsets that
            } else {
                this.differenceInDays = 0.5;
            }
            this.differenceInDays -= weekEndDaysCount;
            this.daysOffAtATime =
                isNaN(this.differenceInDays) || this.differenceInDays < 0
                    ? 0
                    : this.differenceInDays.toFixed(2);
            this.requiredVacationDays = this.daysOffAtATime;
            // if the leave is special then recalculate the requiredVacationDays, daysOffAtTheTime
            if (this.form.isSpecialLeave && this.form.specialLeaveType) {
                this.specialLeaveSelected();
            }
        },
        async changeDaysOffAtAtime(event) {
            await this.$nextTick();
            // if the value being entered in less than 1 or greater than the differenceInDays then set the daysOffAtATime and
            // requiredVacationDays to differenceInDays
            if (
                event.target.value < 1 ||
                event.target.value > this.differenceInDays
            ) {
                this.daysOffAtATime = this.requiredVacationDays =
                    this.differenceInDays;
                return;
            }
            this.daysOffAtATime = this.requiredVacationDays =
                event.target.value;
        },
        /**
         * create initials from a string based on space and returns it
         * @param {name} the string we need to get initials from
         */
        getInitials(name) {
            const tokens = name?.split(" ");
            if (tokens)
                return `${tokens?.[0]?.[0] ?? ""}${
                    tokens?.[1]?.[0] ?? ""
                }`.toUpperCase();
            else return "";
        },
        /**
         * triggers the update API for the vacation request
         */
        async edit() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("vacationRequest/update", {
                    id: this.form.id,
                    data: {
                        ...this.form,
                        endDate:
                            this.form.leaveType === "half"
                                ? this.form.startDate
                                : this.form.endDate,
                        replacements: this.form.replacements?.map(
                            (replacement) => replacement.id
                        ),
                        requiredVacationDays: this.requiredVacationDays,
                    },
                });
                this.$emit("refresh", true);
                this.$emit("toggleVacationEditModal", false);
                this.$emitter.emit("search", true); // refreshes the calendar data in vacation calendar
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * modifies the selected label in the multiselect dropdown
         * @param {props} information regarding options in the multiselect dropdown
         */
        customLabel(props) {
            return `${props?.firstName ?? ""} ${props?.lastName ?? ""}`;
        },
        /**
         * removes the selected replacement from the replacements array
         * @param {index} index of the replacement to be removed in the array
         */
        removeReplacement(index) {
            this.form.replacements.splice(index, 1);
        },
    },
    watch: {
        "form.leaveType"(val) {
            this.daysOffAtATime = 0;
            this.requiredVacationDays = 0;
            this.calculateDaysOffAtATime(); // recalculate when leaveType is changed
        },
        modalData: {
            handler: function () {
                try {
                    if (typeof this.modalData === "object") {
                        this.form = { ...this.modalData };
                        this.form.startDate = new Date(this.form.startDate);
                        this.form.endDate = new Date(this.form.endDate);
                    }
                    let replacements = [];
                    this.form.replacements?.forEach((replacement) => {
                        const foundReplacement = this.users?.data?.find(
                            (user) => user.userId == replacement.id
                        );
                        if (foundReplacement) {
                            replacements = [
                                ...replacements,
                                { ...foundReplacement },
                            ];
                        }
                        this.form.replacements = replacements;
                        this.calculateDaysOffAtATime();
                        // set the requiredVacationDays, daysOffAtATime to the values fetched from the modalData
                        setTimeout(() => {
                            this.requiredVacationDays = this.daysOffAtATime =
                                this.form.requiredVacationDays;
                        }, 100);
                    });
                } catch (e) {
                    console.log(e);
                }
            },
            deep: true,
        },
    },
    components: {
        Modal,
        LoadingButton,
        TextInput,
        DateInput,
        SelectInput,
        MultiSelectInput,
        TextareaInput,
    },
};
</script>

<style scoped></style>

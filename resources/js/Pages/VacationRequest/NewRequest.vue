<template>
    <div class="grid items-center grid-cols-2 gap-6 mb-3">
        <div class="w-full">
            <div class="form-group">
                <MultiSelectInput
                    v-if="$hasRole('admin')"
                    :show-labels="false"
                    v-model="selectedUser"
                    :key="selectedUser"
                    :options="users?.data ?? []"
                    @update:model-value="
                        fetchVacationDataByUser();
                        fetchUsers();
                    "
                    :multiple="false"
                    :textLabel="$t('User')"
                    :label="$t('firstName')"
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
    </div>

    <div class="grid grid-cols-[3fr,2fr] gap-3">
        <div class="grid grid-rows-[3fr,1fr] gap-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{
                            globalLanguage === "de"
                                ? "Urlaubsantrag"
                                : $t("Application Data")
                        }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <div class="flex">
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
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <div class="flex">
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
                                    @update:model-value="
                                        calculateDaysOffAtATime
                                    "
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
                                    @update:model-value="
                                        calculateDaysOffAtATime
                                    "
                                    :disabled="form.isSpecialLeave == 1"
                                />
                                <div v-if="errors?.endDate" class="form-error">
                                    {{ errors.endDate }}
                                </div>
                            </div>
                        </div>
                        <div class="w-full col-span-2" v-if="overtime > 0">
                            <div class="grid items-center grid-cols-2 gap-6">
                                <div class="w-full">
                                    <div class="checkbox-group">
                                        <input
                                            type="checkbox"
                                            v-model="takeFromOvertime"
                                            class="checkbox-input"
                                            id="overtime"
                                        />
                                        <label
                                            for="overtime"
                                            class="checkbox-label"
                                            >{{
                                                $t("Take From Overtime")
                                            }}</label
                                        >
                                    </div>
                                </div>
                                <div class="w-full" v-if="takeFromOvertime">
                                    <div class="form-group">
                                        <label
                                            >{{
                                                $t("Available Overtime Hours")
                                            }}:&nbsp;{{
                                                $formatter(
                                                    overtime,
                                                    globalLanguage,
                                                    "EUR",
                                                    true,
                                                    2,
                                                    2
                                                )
                                            }}&nbsp;h</label
                                        >
                                        <br />
                                        <label
                                            >{{
                                                $t("Available Overtime Days")
                                            }}:&nbsp;{{
                                                $formatter(
                                                    availableOvertimeDays,
                                                    globalLanguage,
                                                    "EUR",
                                                    true,
                                                    2,
                                                    2
                                                )
                                            }}&nbsp;{{ $t("days(s)") }}</label
                                        >
                                        <div class="flex">
                                            <TextInput
                                                :modelValue="takenFromOvertime"
                                                @update:model-value="
                                                    updateTakenFromOvertime(
                                                        $event
                                                    )
                                                "
                                                :key="takenFromOvertime"
                                                :error="
                                                    errors.takenFromOvertime
                                                "
                                                type="number"
                                                :min="0"
                                                :max="availableOvertimeDays"
                                            />
                                            <label class="self-center">{{
                                                $t("day(s)")
                                            }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    :show-labels="false"
                                    :required="true"
                                    v-model="form.replacements"
                                    :options="usersFilteredWithoutCurrentUser"
                                    :multiple="true"
                                    :textLabel="$t('Stand-In')"
                                    :label="$t('firstName')"
                                    :customLabel="customLabel"
                                    trackBy="id"
                                    moduleName="userProfile"
                                    :showLoadMoreText="true"
                                    :error="errors.replacements"
                                    @update:model-value="fetchProfilePictures"
                                >
                                    <template #beforeList>
                                        <div
                                            class="grid p-2 gap-2"
                                            style="
                                                grid-template-columns: 25% 25% 50%;
                                            "
                                        >
                                            <p class="font-bold">
                                                {{ $t("First Name") }}
                                            </p>
                                            <p class="font-bold">
                                                {{ $t("Last Name") }}
                                            </p>
                                            <p class="font-bold">
                                                {{ $t("Email") }}
                                            </p>
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
                                            style="
                                                grid-template-columns: 25% 25% 50%;
                                            "
                                        >
                                            <p
                                                class="overflow-text-users-table"
                                            >
                                                {{ props.option.firstName }}
                                            </p>
                                            <p
                                                class="overflow-text-users-table"
                                            >
                                                {{ props.option.lastName }}
                                            </p>
                                            <p
                                                class="overflow-text-users-table"
                                            >
                                                {{ props.option.email }}
                                            </p>
                                        </div>
                                    </template>
                                </MultiSelectInput>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <div
                                    v-for="(
                                        replacement, index
                                    ) in form.replacements"
                                    :key="'replacement-' + replacement.id"
                                    class="flex justify-between items-center"
                                >
                                    <div
                                        class="flex items-center mt-3 mr-1 group-hover:text-orange-600 focus:text-orange-600 whitespace-nowrap"
                                    >
                                        <div
                                            class="flex justify-center items-center"
                                        >
                                            <div
                                                class="flex justify-center items-center relative"
                                                :style="{
                                                    backgroundImage:
                                                        'url(' +
                                                        userProfilePictures?.[
                                                            replacement?.userId
                                                        ]?.profile_image +
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
                                            }}&nbsp;{{
                                                replacement?.lastName ?? ""
                                            }}
                                            <p class="ml-1 text-gray-500">
                                                {{
                                                    replacement?.departments ??
                                                    "-"
                                                }}
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
                                <p
                                    v-if="errors['isSpecialLeave']"
                                    class="text-red-500"
                                >
                                    {{ errors["isSpecialLeave"] }}
                                </p>
                            </div>
                        </div>
                        <div
                            class="w-full col-span-2"
                            v-if="form.isSpecialLeave == 1"
                        >
                            <div class="form-group">
                                <select-input
                                    :required="true"
                                    :label="$t('Special Leave Type')"
                                    v-model="form.specialLeaveType"
                                    :error="errors.specialLeaveType"
                                    @update:model-value="specialLeaveSelected"
                                >
                                    <option value="injury">
                                        {{ $t("Injury") }}
                                    </option>
                                    <option value="death-of-relative">
                                        {{
                                            $t(
                                                "Death of a close relative (3 Days)"
                                            )
                                        }}
                                    </option>
                                    <option value="own-wedding">
                                        {{ $t("(own) wedding (1 Day)") }}
                                    </option>
                                    <option value="birth-of-child">
                                        {{
                                            $t(
                                                "Birth of one's own child (1 Day)"
                                            )
                                        }}
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
                        <div class="w-full">
                            <div class="form-group">
                                <loading-button
                                    :loading="isLoading"
                                    :disabled="doesErrorExist"
                                    @click="create"
                                    class="secondary-btn"
                                    type="button"
                                >
                                    {{ $t("Apply For Leave") }}
                                </loading-button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
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
                                    :disabled="
                                        form.leaveType === 'half' ||
                                        form.isSpecialLeave == 1
                                    "
                                    v-model="requiredVacationDays"
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
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Application Manager (one must agree)") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div
                        v-for="user in teamLeads"
                        :key="'user-' + user.id"
                        class="flex justify-between items-center mt-5 mr-1 group-hover:text-orange-600 focus:text-orange-600 whitespace-nowrap"
                    >
                        <div class="flex items-center">
                            <div class="flex justify-center items-center">
                                <div
                                    class="flex justify-center items-center relative"
                                    :style="{
                                        backgroundImage:
                                            'url(' +
                                            (userProfilePictures?.[
                                                user?.user_id
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
                                            user?.first_name &&
                                            !userProfilePictures?.[
                                                user?.user_id
                                            ]
                                        "
                                    >
                                        {{
                                            getInitials(
                                                (user?.first_name ?? "") +
                                                    " " +
                                                    (user?.last_name ?? "")
                                            )
                                        }}
                                    </p>
                                </div>
                            </div>
                            <span
                                style="text-transform: capitalize"
                                class="hidden md:inline ml-2"
                                >&nbsp;{{ user?.first_name ?? "" }}&nbsp;{{
                                    user?.last_name ?? ""
                                }}
                                <p class="ml-1 text-gray-500 capitalize">
                                    {{ user?.department }}
                                </p>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $t("My Profile") }}</h3>
            </div>
            <div class="card-body">
                <div class="flex justify-center items-center">
                    <div
                        class="flex justify-center items-center relative"
                        :style="{
                            backgroundImage:
                                'url(' + selectedUserProfilePic + ')',
                        }"
                        style="
                            background-position: center center;
                            background-repeat: no-repeat;
                            background-size: cover;
                            min-height: 150px;
                            min-width: 150px;
                            border-radius: 50%;
                            background-color: #ed7d31;
                            color: white;
                            overflow: hidden;
                            margin-left: 4rem;
                        "
                    >
                        <p
                            style="font-size: 1.5rem"
                            v-if="
                                (selectedUser?.firstName ?? user?.first_name) &&
                                !selectedUserProfilePic
                            "
                        >
                            {{
                                getInitials(
                                    (selectedUser?.firstName ??
                                        user?.first_name ??
                                        "") +
                                        " " +
                                        (selectedUser?.lastName ??
                                            user?.last_name ??
                                            "")
                                )
                            }}
                        </p>
                    </div>
                    <div
                        id="piechart"
                        class="ml-4"
                        style="width: 300px; height: 300px"
                    ></div>
                </div>
                <div class="flex flex-col justify-center items-center mt-2">
                    <p class="text-gray-500" style="font-size: 1.2rem">
                        {{
                            (selectedUser?.firstName ??
                                user?.first_name ??
                                "") +
                            " " +
                            (selectedUser?.lastName ?? user?.last_name ?? "")
                        }}
                    </p>
                    <p>{{ jobData?.locationId?.name ?? "" }}</p>
                    <p>{{ jobData?.departmentId?.name ?? "" }}</p>
                    <p>
                        {{ $t("Personalnr:") }}
                        {{ jobData?.personalNumber ?? "" }}
                    </p>
                </div>
                <div class="mt-3">
                    <div class="flex items-center justify-between">
                        <h4 class="card-title">
                            {{ $t("Remaining Holidays:") }}
                        </h4>
                        <p class="">
                            {{ profileData?.totalWorkedHours ?? 0 }} Hr(s)
                        </p>
                    </div>
                    <div class="flex items-center justify-between">
                        <h4 class="card-title">
                            {{ $t("Planned Holidays:") }}
                        </h4>
                        <p>
                            {{ remainingHolidays }}
                            {{ $t("Day(s)") }}.
                        </p>
                    </div>
                    <div class="flex items-center justify-between">
                        <h4 class="card-title">{{ $t("Taken From It:") }}</h4>
                        <p>
                            {{ +this.profileData.pending }}
                            {{ $t("Day(s)") }}.
                        </p>
                    </div>
                    <div class="flex items-center justify-between">
                        <h4 class="card-title">{{ $t("Already Expired:") }}</h4>
                        <p>
                            {{ profileData?.totalHolidaysTakenCurrent ?? 0 }}
                            {{ $t("Day(s)") }}.
                        </p>
                    </div>
                    <div class="flex items-center justify-between">
                        <h4 class="card-title">
                            {{ $t("Expires on") + " " + dateOfExpiry + ":" }}
                        </h4>
                        <p>
                            {{ profileData.expiredLeaves ?? 0 }}
                            {{ $t("Day(s)") }}.
                        </p>
                    </div>
                    <div class="flex items-center justify-between">
                        <h4 class="card-title">
                            {{ $t("Holidays") + " " + currentYear + ":" }}
                        </h4>
                        <p>
                            {{ profileData?.previousYearRemainingLeaves ?? 0 }}
                            {{ $t("Day(s)") }}
                        </p>
                    </div>
                    <div class="flex items-center justify-between">
                        <h4 class="card-title">
                            {{ $t("Additional Leaves:") }}
                        </h4>
                        <p>
                            {{ profileData?.totalAnnualLeaves ?? 0 }}
                            {{ $t("Day(s)") }}.
                        </p>
                    </div>
                    <div class="flex items-center justify-between">
                        <h4 class="card-title">
                            {{ $t("Vacation Entered:") }}
                        </h4>
                        <p>
                            {{ profileData?.currentAdditionalLeaves ?? 0 }}
                            {{ $t("Day(s)") }}.
                        </p>
                    </div>
                    <div class="flex items-center justify-between">
                        <h4 class="card-title">
                            {{ $t("Of Which Requested:") }}
                        </h4>
                        <p>0,0 {{ $t("Day(s)") }}.</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <h4 class="card-title">
                            {{ $t("Released From It:") }}
                        </h4>
                        <p>0,0 {{ $t("Day(s)") }}.</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <h4 class="card-title">{{ $t("Started From It:") }}</h4>
                        <p>0,0 {{ $t("Day(s)") }}.</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <h4 class="card-title">
                            {{
                                $t("Remaining Hoildays") +
                                " " +
                                currentYear +
                                ":"
                            }}
                        </h4>
                        <p>
                            {{ profileData.currentYearRemainingLeaves ?? 0 }}
                            {{ $t("Day(s)") }}.
                        </p>
                    </div>
                    <div class="flex items-center justify-between">
                        <h4 class="card-title">
                            {{ $t("Illness") + " " + currentYear + ":" }}
                        </h4>
                        <p>
                            {{ profileData?.illness ?? 0 }} {{ $t("Day(s)") }}.
                        </p>
                    </div>
                    <div class="flex items-center justify-between">
                        <h4 class="card-title">
                            {{ $t("Special Leave" + " " + currentYear + ":") }}
                        </h4>
                        <p>
                            {{ profileData?.totalSpecialLeave ?? 0 }}
                            {{ $t("Day(s)") }}.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import TextInput from "../../Components/TextInput.vue";
import NumberInput from "../../Components/NumberInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import TextareaInput from "../../Components/TextareaInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import userProfilePicturesMixin from "@/Mixins/userProfilePicturesMixin.js";
import { getHolidays, isHoliday, isSpecificHoliday } from "feiertagejs";

import { mapGetters } from "vuex";

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
    emits: ["isApprover"],
    components: {
        LoadingButton,
        TextInput,
        SelectInput,
        MultiSelectInput,
        TextareaInput,
        NumberInput,
    },
    computed: {
        currentYear() {
            return new Date().getFullYear();
        },
        expiryDate() {
            return `01-${
                this.expiryMonth < 10
                    ? "0" + this.expiryMonth
                    : this.expiryMonth
            }-${new Date().getFullYear()}`;
        },
        doesErrorExist() {
            return this.errors["endTime"]?.length;
        },
        ...mapGetters("auth", {
            user: "user",
            userProfilePictures: "userProfilePictures",
        }),
        ...mapGetters("userProfile", {
            users: "userProfiles",
            authUserProfile: "authUserProfile",
        }),
        ...mapGetters(["errors", "isLoading"]),
        expiryShouldCount() {
            const currentMonth = new Date().getMonth() + 1;
            let expiryShouldCount = false;
            if (currentMonth < this.expiryMonth) {
                expiryShouldCount = true;
            }
            return expiryShouldCount;
        },
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
        // filter the users dropdown options without the selectedUser or logged in user
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
    watch: {
        "form.endDate"() {
            this.calculateOvertimeDays();
        },
        "form.startDate"(val) {
            if (val > this.form.endDate) {
                this.form.endDate = val;
            }
        },
        async "user.id"() {
            if (!this.user?.id) {
                return;
            }
            const response = await this.$store.dispatch(
                "userProfile/showJobByUserBasic",
                this.user?.id
            );
            this.jobData = response?.data?.data ?? {};
            this.$emit("isApprover", this.jobData?.isApprover);
            // auto select the logged in user
            this.selectedUser =
                this.users?.data?.find(
                    (user) => user.userId == this.user?.id
                ) ?? null;
        },
        "form.leaveType"(val) {
            this.daysOffAtATime = 0;
            this.requiredVacationDays = 0;
            this.calculateDaysOffAtATime(); // recalculate when leaveType is changed
        },
        takeFromOvertime() {
            this.calculateOvertimeDays();
        },
        async selectedUser(val) {
            try {
                if (!!val) {
                    this.getOvertime();
                    // also when selected user change then recalculate
                    this.daysOffAtATime = 0;
                    this.requiredVacationDays = 0;
                    this.calculateDaysOffAtATime();
                }
            } catch (e) {
                console.log(e);
            }
        },
    },
    unmounted() {
        // reset the selectedUser on unmount
        this.selectedUser = null;
    },
    async mounted() {
        try {
            this.$store.commit("showLoader", true);
            // calculate on requiredVacationDays, daysOffAtATime on mount
            this.calculateDaysOffAtATime();
            if (!this.users.length) this.fetchUsers();
            if (this.user?.id) {
                const response = await this.$store.dispatch(
                    "userProfile/showJobByUserBasic",
                    this.user?.id
                );
                this.jobData = response?.data?.data ?? {};
                this.jobData.teams = this.jobData.teams
                    ? this.jobData.teams
                    : [];
                // auto select the logged in user
                this.selectedUser =
                    this.users?.data?.find(
                        (user) => user.userId == this.user?.id
                    ) ?? null;
            }
            await this.fetchProfileData();
            this.$emit("isApprover", this.jobData?.isApprover);
            this.teamLeads = this.jobData?.getImmediateSupervisor;
            this.getUserProfilePictures(
                this.teamLeads ? this.teamLeads : [],
                "user_id"
            );
            const response = await this.$store.dispatch("globalSettings/list");
            this.expiryMonth = response?.data?.expiryMonth ?? 0;
            this.dateOfExpiry = response?.data?.expiryDate ?? 0;
        } catch (e) {
        } finally {
            this.$store.commit("showLoader", false);
        }
    },
    methods: {
        /**
         * hits the timeTrackers overtime API to get the overtime based on the current date
         */
        async getOvertime() {
            try {
                const response = await this.$store.dispatch(
                    "timeTrackers/overtime",
                    {
                        userId: this.selectedUser?.userId,
                        date: new Date().toLocaleDateString(),
                    }
                );
                this.overtime = response?.data?.overtime ?? 0;
                this.calculateOvertimeDays();
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * get user profiles listing
         */
        async fetchUsers() {
            try {
                await this.$store.dispatch("userProfile/list", {
                    page: 1,
                    perPage: 25,
                });
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * updates 'takenFromOvertime' and calculates 'requiredVacationDays' and 'daysOffAtATime' based on the value of 'takenFromOvertime'
         * @param {event} updated value
         */
        updateTakenFromOvertime(event) {
            this.previousTakenFromOvertime = this.takenFromOvertime;
            this.takenFromOvertime = event;
            if (this.previousTakenFromOvertime > this.takenFromOvertime) {
                this.requiredVacationDays =
                    +this.requiredVacationDays +
                    (+this.previousTakenFromOvertime - +this.takenFromOvertime);
                this.daysOffAtATime =
                    +this.daysOffAtATime +
                    (+this.previousTakenFromOvertime - +this.takenFromOvertime);
            } else if (
                this.previousTakenFromOvertime < this.takenFromOvertime
            ) {
                this.requiredVacationDays =
                    +this.requiredVacationDays -
                    (+this.takenFromOvertime - +this.previousTakenFromOvertime);
                this.daysOffAtATime =
                    +this.daysOffAtATime -
                    (+this.takenFromOvertime - +this.previousTakenFromOvertime);
            }
        },
        addDays(date, days) {
            const d = new Date(date.valueOf());
            d.setDate(d.getDate() + days);
            return d;
        },
        matchDates(date1, date2) {
            return (
                date1.getDate() == date2.getDate() &&
                date1.getMonth() == date2.getMonth() &&
                date1.getFullYear() == date2.getFullYear()
            );
        },
        calculateOvertimeDays() {
            if (
                this.takeFromOvertime &&
                this.form.startDate &&
                this.form.endDate
            ) {
                this.availableOvertimeDays = 0;
                let overtime = this.overtime;
                let startDate = this.form.startDate;
                while (
                    !this.matchDates(startDate, this.form.endDate) &&
                    startDate < this.form.endDate
                ) {
                    const workDay =
                        this.authUserProfile?.jobData?.workHours?.find(
                            (day) => {
                                return (
                                    day.days?.[0] ===
                                    this.weekDays[startDate.getDay()]
                                );
                            }
                        );
                    if (+overtime >= +(workDay?.numOfHours ?? 0) && !!workDay) {
                        this.availableOvertimeDays += 1;
                        overtime -= +(workDay?.numOfHours ?? 0);
                    }
                    startDate = startDate.addDays(1);
                }
                const workDay = this.authUserProfile?.jobData?.workHours?.find(
                    (day) => {
                        return (
                            day.days?.[0] === this.weekDays[startDate.getDay()]
                        );
                    }
                );
                if (+overtime >= +(workDay?.numOfHours ?? 0) && !!workDay) {
                    this.availableOvertimeDays += 1;
                    overtime -= +(workDay?.numOfHours ?? 0);
                }
                // available holidays
                let totalHolidays = +(this.profileData?.remainingLeaves ?? 0);
                if (this.expiryShouldCount) {
                    totalHolidays +=
                        this.profileData?.totalHolidaysTakenPrevious ?? 0;
                }
                // if the requiredVacationDays are are less than the total available holidays then automatically set the 'takenFromOvertime'
                if (totalHolidays < this.requiredVacationDays) {
                    let requiredDaysOffset =
                        +this.requiredVacationDays - +totalHolidays;
                    this.takenFromOvertime =
                        +requiredDaysOffset <= +this.availableOvertimeDays
                            ? +requiredDaysOffset
                            : +requiredDaysOffset - +this.availableOvertimeDays;
                    this.requiredVacationDays =
                        +this.requiredVacationDays -
                        (+this.takenFromOvertime -
                            +this.previousTakenFromOvertime);
                    this.daysOffAtATime =
                        +this.daysOffAtATime -
                        (+this.takenFromOvertime -
                            +this.previousTakenFromOvertime);
                }
            }
        },

        calculateDaysExcludingPublicHolidays() {
            if (this.form.startDate && this.form.endDate) {
                function getDates(startDate, stopDate) {
                    // get all dates range, including start and endDate
                    var dateArray = new Array();
                    var currentDate = startDate;
                    while (currentDate < stopDate.addDays(1)) {
                        dateArray.push(new Date(currentDate));
                        currentDate = currentDate.addDays(1);
                    }
                    return dateArray;
                }
                const datesRange = getDates(
                    this.form.startDate,
                    this.form.endDate
                );
                // console.log("dates range", datesRange);
                const year = new Date().getFullYear(); // pass the current year
                const holidaysInYear = getHolidays("2024", "BUND"); // get public holidays
                // console.log("public holiday", holidaysInYear);
                function getFinalDates() {
                    // remove public holidays from array.
                    const tempDates = [...datesRange];
                    for (let i = 0; i < datesRange.length; i++) {
                        for (let j = 0; j < holidaysInYear.length; j++) {
                            let date1 = datesRange[i].setHours(0, 0, 0, 0);
                            let date2 = holidaysInYear[j].date.setHours(
                                0,
                                0,
                                0,
                                0
                            );
                            if (date1 === date2) {
                                tempDates.splice(i, 1);
                            }
                        }
                    }
                    return tempDates;
                }
                // console.log("FINAL DATES", getFinalDates());
                return getFinalDates().length;
            }
        },
        async fetchProfilePictures() {
            await this.$nextTick();
            try {
                this.getUserProfilePictures(this.form.replacements, "userId");
            } catch (e) {
                console.log(e);
            }
        },
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
         * triggered everytime a user is selected from the user dropdown
         * fetch the data of the selectedUser and set the user details, vacation summary, approvers, profilePicture etc.
         */
        async fetchVacationDataByUser() {
            await this.$nextTick();
            try {
                this.$store.commit("showLoader", true);
                // fetch the jobData related info of user
                let response = await this.$store.dispatch(
                    "userProfile/showJobByUserBasic",
                    this.selectedUser?.userId
                        ? this.selectedUser?.userId
                        : this.user?.id
                );
                this.jobData = response?.data?.data ?? {};
                // fetch the stats or summary details about vacations
                await this.fetchProfileData();
                // set the approvers or application managers
                this.teamLeads = this.jobData?.getImmediateSupervisor;
                // fetch and set the profile picture of the selected user
                if (this.selectedUser?.userId)
                    this.$store
                        .dispatch("auth/show", {
                            id: this.selectedUser?.userId,
                        })
                        .then((res) => {
                            this.selectedUserProfilePic =
                                res?.data?.profile_image ?? null;
                        });
            } catch (e) {
                console.log(e);
            }
        },
        fetchProfileData() {
            return new Promise((resolve, reject) => {
                this.$store
                    .dispatch("vacationRequest/profileData", {
                        userId: this.selectedUser?.userId, // get the profile data filtered by selected user
                    })
                    .then((res) => {
                        this.profileData = { ...(res?.data ?? {}) };
                        resolve();
                    })
                    .catch((err) => reject(err))
                    .finally(() => {
                        window.google.charts.load("current", {
                            packages: ["corechart"],
                        });
                        window.google.charts.setOnLoadCallback(this.drawChart);
                    });
                // fetch and set the profile picture of the selected user
                if (this.selectedUser?.userId){
                    this.$store
                        .dispatch("auth/show", {
                            id: this.selectedUser?.userId,
                        })
                        .then((res) => {
                            this.selectedUserProfilePic =
                                res?.data?.profile_image ?? null;
                        }).catch((e) => {

                        }).finally((e) => {
                            this.$store.commit("showLoader", false);
                        });
                }
                else{
                    this.selectedUserProfilePic = ""
                    this.$store.commit("showLoader", false);
                }
            });
        },
        drawChart() {
            const remaining = +this.remainingHolidays;
            var data = window.google.visualization.arrayToDataTable([
                ["Task", "Hours per Day"],
                ["Remaining", remaining < 0 ? 0 : remaining],
                ["Planned", +this.profileData.pending],
                ["Consumed", +this.profileData.approved],
            ]);

            var options = {
                title: "",
                chartArea: { width: "50%", height: "50%" },
                legend: {
                    position: "top",
                    maxLines: 3,
                },
                colors: ["green", "orange", "red"],
            };

            var chart = new window.google.visualization.PieChart(
                document.getElementById("piechart")
            );

            chart.draw(data, options);
        },
        matchDates(date1, date2) {
            return (
                date1.getDate() == date2.getDate() &&
                date1.getMonth() == date2.getMonth() &&
                date1.getFullYear() == date2.getFullYear()
            );
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
                if (this.selectedUser?.country === "Germany") {
                    this.differenceInDays =
                        this.calculateDaysExcludingPublicHolidays();
                } else {
                    this.differenceInDays =
                        differenceInTime / (1000 * 3600 * 24);
                    this.differenceInDays += 1; // since we are calculating the difference between start and end date, there is always a difference of 1, this offsets that
                }
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
        customLabel(props) {
            return `${props?.firstName ?? ""} ${props?.lastName ?? ""}`;
        },
        removeReplacement(index) {
            this.form.replacements.splice(index, 1);
        },
        async create() {
            this.$store.commit("isLoading", true);
            let response = null;
            try {
                const endDate = this.form.endDate;
                endDate.setHours(23);
                endDate.setMinutes(59);
                endDate.setSeconds(59);
                response = await this.$store.dispatch(
                    "vacationRequest/create",
                    {
                        ...this.form,
                        userId: this.selectedUser?.userId ?? this.user.id, // create the vacation request for the selected user or currently logged in user
                        endDate:
                            this.form.leaveType === "half"
                                ? this.form.startDate
                                : endDate,
                        replacements: this.form.replacements?.map(
                            (replacement) => replacement.id
                        ),
                        requiredVacationDays: this.requiredVacationDays,
                        takenFromOvertime: +this.takenFromOvertime,
                    }
                );
                await this.fetchProfileData();
                this.$store.commit("errors", {});
                this.form.replacements.splice(0, this.form.replacements.length);
                this.form = {
                    leaveType: "whole",
                    startDate: new Date(),
                    endDate: "",
                    startTime: "",
                    endTime: "",
                    replacements: [],
                    isSpecialLeave: 0,
                    specialLeaveType: "",
                    specialLeaveComments: "",
                };
                this.previousTakenFromOvertime = 0;
                this.takenFromOvertime = 0;
                this.takeFromOvertime = false;
                this.overtime = 0;
                this.availableOvertimeDays = 0;
                this.getOvertime();
                this.$store.dispatch("vacationRequest/list", {
                    page: 1,
                    perPage: 25,
                });
                this.$emitter.emit("search", true);
            } catch (e) {}
            // send mail
            if (response?.data?.id)
                this.$store
                    .dispatch("vacationRequest/sendMail", response?.data?.id)
                    .catch((e) => console.log(e));
        },
        getInitials(name) {
            const tokens = name?.split(" ");
            if (tokens)
                return `${tokens?.[0]?.[0] ?? ""}${
                    tokens?.[1]?.[0] ?? ""
                }`.toUpperCase();
            else return "";
        },
    },
    data() {
        return {
            previousTakenFromOvertime: 0,
            takenFromOvertime: 0,
            takeFromOvertime: false,
            overtime: 0,
            availableOvertimeDays: 0,
            selectedUserProfilePic: null, // user profile picture url of the selectedUser
            selectedUser: null, // the user selected by an admin for which he would like to create/view vacation requests
            differenceInDays: 0, // difference between the startDate and endDate
            additionalLeaves: 0,
            expiryMonth: 0,
            dateOfExpiry: 0,
            profileData: {},
            weekDays: {
                0: "sun",
                1: "mon",
                2: "tue",
                3: "wed",
                4: "thu",
                5: "fri",
                6: "sat",
            },
            daysOffAtATime: 0,
            requiredVacationDays: 0,
            teamLeads: [],
            jobData: {},
            form: {
                leaveType: "whole",
                startDate: new Date(),
                endDate: "",
                startTime: "",
                endTime: "",
                replacements: [],
                isSpecialLeave: false,
                specialLeaveType: "",
                specialLeaveComments: "",
            },
        };
    },
};
</script>

<style scoped>
.piechart {
    display: block;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background-image: conic-gradient(
        rgb(136, 201, 251) 70deg,
        rgb(152, 255, 168) 0 235deg,
        rgb(204, 204, 204) 0
    );
}

body,
.piechart {
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>

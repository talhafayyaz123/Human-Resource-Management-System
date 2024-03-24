<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <div class="flex items-center justify-center tab-header mb-6">
            <button
                class="tab-btn mr-3"
                :class="{ active: activeTab === 'tab1' }"
                @click="activeTab = 'tab1'"
            >
                {{ $t("Time Tracker") }}
            </button>
            <button
                class="tab-btn"
                :class="{ active: activeTab === 'tab2' }"
                @click="activeTab = 'tab2'"
            >
                {{ $t("Overview") }}
            </button>
        </div>
        <div class="tab-content">
            <div v-show="activeTab === 'tab1'">
                <div class="grid grid-cols-[1fr,1fr,1fr,1fr] gap-7">
                    <div>
                        <p style="font-weight: bold">
                            {{ $t("Time Account") }}
                        </p>
                        <div class="grid grid-cols-[1fr,1fr,1fr] gap-2 mt-2">
                            <div style="font-size: 0.8rem">
                                <span class="flex">
                                    <p
                                        style="
                                            font-weight: bold;
                                            width: max-content;
                                        "
                                    >
                                        {{ $t("Total Worked Hours") }}&nbsp;
                                    </p>
                                </span>
                                <span class="flex items-end">
                                    <p
                                        :class="[
                                            this.totalWorkedHours < 0
                                                ? 'text-red-600'
                                                : 'text-green-600',
                                        ]"
                                        style="
                                            font-size: 1.2rem;
                                            font-weight: bold;
                                        "
                                    >
                                        {{
                                            numberFormatter(
                                                this.totalWorkedHours || 0,
                                                2,
                                                2
                                            )
                                        }}&nbsp;
                                    </p>
                                    <p class="text-gray-500">
                                        {{ $t("Hours") }}
                                    </p>
                                </span>
                            </div>
                            <div style="font-size: 0.8rem">
                                <span class="flex">
                                    <p
                                        style="
                                            font-weight: bold;
                                            width: max-content;
                                        "
                                    >
                                        {{ $t("Expected Worked Hours") }}&nbsp;
                                    </p>
                                </span>
                                <span class="flex items-end">
                                    <p
                                        :class="[
                                            this.expectedWorkedHours < 0
                                                ? 'text-red-600'
                                                : 'text-green-600',
                                        ]"
                                        style="
                                            font-size: 1.2rem;
                                            font-weight: bold;
                                        "
                                    >
                                        {{
                                            numberFormatter(
                                                this.expectedWorkedHours || 0,
                                                2,
                                                2
                                            )
                                        }}&nbsp;
                                    </p>
                                    <p class="text-gray-500">
                                        {{ $t("Hours") }}
                                    </p>
                                </span>
                            </div>
                            <!-- 
                            <div style="font-size: 0.8rem">
                                <span class="flex">
                                    <p
                                        style="
                                            font-weight: bold;
                                            width: max-content;
                                        "
                                    >
                                        {{ $t("Old") }}&nbsp;
                                    </p>
                                </span>
                                <span class="flex items-end">
                                    <p
                                        :class="[
                                            this.oldWorkedHours < 0
                                                ? 'text-red-600'
                                                : 'text-green-600',
                                        ]"
                                        style="
                                            font-size: 1.2rem;
                                            font-weight: bold;
                                        "
                                    >
                                        {{
                                            numberFormatter(
                                                this.oldWorkedHours || 0,
                                                2,
                                                2
                                            )
                                        }}&nbsp;
                                    </p>
                                    <p class="text-gray-500">
                                        {{ $t("Hours") }}
                                    </p>
                                </span>
                            </div>
                            <div style="font-size: 0.8rem">
                                <span class="flex">
                                    <p
                                        style="
                                            font-weight: bold;
                                            width: max-content;
                                        "
                                    >
                                        {{ $t("This Month") }}&nbsp;
                                    </p>
                                    <p
                                        style="width: max-content"
                                        class="text-gray-500"
                                    >
                                        ({{
                                            $dateFormatter(
                                                date,
                                                globalLanguage
                                            )
                                        }})
                                    </p>
                                </span>
                                <span class="flex items-end">
                                    <p
                                        :class="[
                                            this.nowWorkedHours < 0
                                                ? 'text-red-600'
                                                : 'text-green-600',
                                        ]"
                                        style="
                                            font-size: 1.2rem;
                                            font-weight: bold;
                                        "
                                    >
                                        {{
                                            numberFormatter(
                                                this.nowWorkedHours || 0,
                                                2,
                                                2
                                            )
                                        }}
                                        &nbsp;
                                    </p>
                                    <p class="text-gray-500">
                                        {{ $t("Hours") }}
                                    </p>
                                </span>
                            </div>
                            <div style="font-size: 0.8rem">
                                <span class="flex">
                                    <p style="font-weight: bold">Total&nbsp;</p>
                                    <p
                                        style="width: max-content"
                                        class="text-gray-500"
                                    >
                                        ({{
                                            $dateFormatter(
                                                date,
                                                globalLanguage
                                            )
                                        }})
                                    </p>
                                </span>
                                <span class="flex items-end">
                                    <p
                                        :class="[
                                            parseFloat(this.oldWorkedHours) +
                                                parseFloat(
                                                    this.nowWorkedHours
                                                ) <
                                            0
                                                ? 'text-red-600'
                                                : 'text-green-600',
                                        ]"
                                        style="
                                            font-size: 1.2rem;
                                            font-weight: bold;
                                        "
                                    >
                                        {{
                                            numberFormatter(
                                                parseFloat(
                                                    this.oldWorkedHours
                                                ) +
                                                    parseFloat(
                                                        this.nowWorkedHours
                                                    ) || 0,
                                                2,
                                                2
                                            )
                                        }}&nbsp;
                                    </p>
                                    <p class="text-gray-500">
                                        {{ $t("Hours") }}
                                    </p>
                                </span>
                            </div>
                             -->
                        </div>
                        <div class="mt-2">
                            <p class="text-sm font-bold mr-5">
                                {{ $t("Overtime") }}
                            </p>
                            <span class="flex items-end">
                                <p
                                    :class="
                                        overtime >= 0
                                            ? 'text-green-600'
                                            : 'text-red-600'
                                    "
                                    style="font-size: 1.2rem; font-weight: bold"
                                >
                                    {{ numberFormatter(overtime || 0, 2, 2) }}
                                    &nbsp;
                                </p>
                                <p class="text-gray-500 text-xs">
                                    {{ $t("Hours") }}
                                </p>
                            </span>
                        </div>
                        <div class="mt-2">
                            <loading-button
                                @click="exportTimeTrackerData"
                                class="btn-green mt-4"
                            >
                                <font-awesome-icon
                                    class="cursor-pointer mr-3"
                                    color="white"
                                    icon="fa-solid fa-print"
                                />{{ $t("Export Time Tracker Data") }}
                            </loading-button>
                        </div>
                    </div>
                    <div>
                        <p style="font-weight: bold">
                            {{ $t("Holiday Account") }}
                        </p>
                        <div class="grid grid-cols-[1fr,1fr,1fr] gap-2 mt-2">
                            <div style="font-size: 0.8rem">
                                <p style="font-weight: bold">
                                    {{ $t("Consumed") }}
                                </p>
                                <span class="flex items-center">
                                    <p
                                        style="
                                            font-size: 1.2rem;
                                            font-weight: bold;
                                        "
                                    >
                                        {{ this.consumedHolidays || 0 }}&nbsp;
                                    </p>
                                    <p class="text-gray-500">
                                        {{ $t("Day(s)") }}
                                    </p>
                                </span>
                            </div>
                            <div style="font-size: 0.8rem">
                                <p style="font-weight: bold">
                                    {{ $t("Planned") }}
                                </p>
                                <span class="flex items-center">
                                    <p
                                        style="
                                            font-size: 1.2rem;
                                            font-weight: bold;
                                        "
                                    >
                                        {{ this.plannedHolidays || 0 }}&nbsp;
                                    </p>
                                    <p class="text-gray-500">
                                        {{ $t("Days(s)") }}
                                    </p>
                                </span>
                            </div>
                            <div style="font-size: 0.8rem">
                                <p style="font-weight: bold">
                                    {{ $t("Remaining") }}
                                </p>
                                <span class="flex items-center">
                                    <p
                                        style="
                                            font-size: 1.2rem;
                                            font-weight: bold;
                                        "
                                    >
                                        {{
                                            (
                                                parseFloat(
                                                    this.totalAnnualLeaves
                                                ) -
                                                    parseFloat(
                                                        this.consumedHolidays
                                                    ) || 0
                                            ).toFixed(2)
                                        }}&nbsp;
                                    </p>
                                    <p class="text-gray-500">
                                        {{ $t("Days(s)") }}
                                    </p>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div>
                            <p style="font-weight: bold">
                                {{ $t("Working Hours") }}
                            </p>
                            <div class="grid grid-cols-[1fr,1fr,1fr] gap-2">
                                <div class="">
                                    <p
                                        style="
                                            font-size: 0.8rem;
                                            font-weight: bold;
                                        "
                                        class="text-center text-gray-500"
                                    >
                                        {{ $t("Day") }}
                                    </p>
                                    <div class="base-timer">
                                        <svg
                                            class="base-timer__svg"
                                            viewBox="0 0 100 100"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <circle
                                                class="base-timer__path-remaining"
                                                cx="50"
                                                cy="50"
                                                r="35"
                                            ></circle>
                                            <circle
                                                v-if="
                                                    workingDaysHours &&
                                                    hoursSpecifiedOnTheDay
                                                "
                                                class="base-timer__path-elapsed"
                                                cx="50"
                                                cy="50"
                                                r="35"
                                                :style="{
                                                    stroke: circleColor(
                                                        getHoursWorkedDecimal(
                                                            workingDaysHours
                                                        ),
                                                        hoursSpecifiedOnTheDay
                                                    ),
                                                    strokeDashoffset:
                                                        dashOffset(
                                                            getHoursWorkedDecimal(
                                                                workingDaysHours
                                                            ),
                                                            hoursSpecifiedOnTheDay
                                                        ),
                                                    strokeDasharray:
                                                        dashArray(),
                                                }"
                                            ></circle>
                                        </svg>
                                        <span
                                            id="base-timer-label"
                                            class="base-timer__label flex flex-col"
                                        >
                                            <p>{{ this.workingDaysHours }}</p>
                                            <p
                                                class="text-gray-500"
                                                style="
                                                    font-size: 0.8rem;
                                                    font-weight: bold;
                                                "
                                            ></p>
                                        </span>
                                    </div>
                                </div>
                                <div class="">
                                    <p
                                        style="
                                            font-size: 0.8rem;
                                            font-weight: bold;
                                        "
                                        class="text-center text-gray-500"
                                    >
                                        {{ $t("Week") }}
                                    </p>
                                    <div class="base-timer">
                                        <svg
                                            class="base-timer__svg"
                                            viewBox="0 0 100 100"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <circle
                                                class="base-timer__path-remaining"
                                                cx="50"
                                                cy="50"
                                                r="35"
                                            ></circle>
                                            <circle
                                                v-if="
                                                    workingWeeksHours &&
                                                    hoursSpecificOnTheWeek
                                                "
                                                class="base-timer__path-elapsed"
                                                cx="50"
                                                cy="50"
                                                r="35"
                                                :style="{
                                                    stroke: circleColor(
                                                        getHoursWorkedDecimal(
                                                            workingWeeksHours
                                                        ),
                                                        hoursSpecificOnTheWeek
                                                    ),
                                                    strokeDashoffset:
                                                        dashOffset(
                                                            getHoursWorkedDecimal(
                                                                workingWeeksHours
                                                            ),
                                                            hoursSpecificOnTheWeek
                                                        ),
                                                    strokeDasharray:
                                                        dashArray(),
                                                }"
                                            ></circle>
                                        </svg>
                                        <span
                                            id="base-timer-label"
                                            class="base-timer__label flex flex-col"
                                        >
                                            <p>{{ this.workingWeeksHours }}</p>
                                            <p
                                                class="text-gray-500"
                                                style="
                                                    font-size: 0.8rem;
                                                    font-weight: bold;
                                                "
                                            ></p>
                                        </span>
                                    </div>
                                </div>
                                <div class="">
                                    <p
                                        style="
                                            font-size: 0.8rem;
                                            font-weight: bold;
                                        "
                                        class="text-center text-gray-500"
                                    >
                                        {{ $t("Month") }}
                                    </p>
                                    <div class="base-timer">
                                        <svg
                                            class="base-timer__svg"
                                            viewBox="0 0 100 100"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <circle
                                                class="base-timer__path-remaining"
                                                cx="50"
                                                cy="50"
                                                r="35"
                                            ></circle>
                                            <circle
                                                v-if="
                                                    workingMonthsHours &&
                                                    hoursSpecificOnTheMonth
                                                "
                                                class="base-timer__path-elapsed"
                                                cx="50"
                                                cy="50"
                                                r="35"
                                                :style="{
                                                    stroke: circleColor(
                                                        getHoursWorkedDecimal(
                                                            workingMonthsHours
                                                        ),
                                                        hoursSpecificOnTheMonth
                                                    ),
                                                    strokeDashoffset:
                                                        dashOffset(
                                                            getHoursWorkedDecimal(
                                                                workingMonthsHours
                                                            ),
                                                            hoursSpecificOnTheMonth
                                                        ),
                                                    strokeDasharray:
                                                        dashArray(),
                                                }"
                                            ></circle>
                                        </svg>
                                        <span
                                            id="base-timer-label"
                                            class="base-timer__label flex flex-col"
                                        >
                                            <p>{{ this.workingMonthsHours }}</p>
                                            <p
                                                class="text-gray-500"
                                                style="
                                                    font-size: 0.8rem;
                                                    font-weight: bold;
                                                "
                                            ></p>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <p style="font-weight: bold">{{ $t("Invoice") }}</p>
                            <div class="grid grid-cols-[1fr,1fr,1fr] gap-2">
                                <div class="">
                                    <p
                                        style="
                                            font-size: 0.8rem;
                                            font-weight: bold;
                                        "
                                        class="text-center text-gray-500"
                                    >
                                        {{ $t("Day") }}
                                    </p>
                                    <div class="base-timer">
                                        <svg
                                            class="base-timer__svg"
                                            viewBox="0 0 100 100"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <g class="base-timer__circle">
                                                <circle
                                                    class="base-timer__path-elapsed"
                                                    cx="50"
                                                    cy="50"
                                                    r="35"
                                                ></circle>
                                                <circle
                                                    v-if="
                                                        getIfWorkedHoursNotZero(
                                                            workingInvoiceDaysHours
                                                        ) &&
                                                        hoursSpecifiedOnTheDay
                                                    "
                                                    class="base-timer__path-elapsed"
                                                    cx="50"
                                                    cy="50"
                                                    r="35"
                                                    :style="{
                                                        stroke: circleColor(
                                                            getHoursWorkedDecimal(
                                                                workingInvoiceDaysHours
                                                            ),
                                                            hoursSpecifiedOnTheDay
                                                        ),
                                                        strokeDashoffset:
                                                            dashOffset(
                                                                getHoursWorkedDecimal(
                                                                    workingInvoiceDaysHours
                                                                ),
                                                                hoursSpecifiedOnTheDay
                                                            ),
                                                        strokeDasharray:
                                                            dashArray(),
                                                    }"
                                                ></circle>
                                            </g>
                                        </svg>
                                        <span
                                            id="base-timer-label"
                                            class="base-timer__label flex flex-col"
                                        >
                                            <p>
                                                {{
                                                    this.workingInvoiceDaysHours
                                                }}
                                            </p>
                                            <p
                                                class="text-gray-500"
                                                style="
                                                    font-size: 0.8rem;
                                                    font-weight: bold;
                                                "
                                            ></p>
                                        </span>
                                    </div>
                                </div>
                                <div class="">
                                    <p
                                        style="
                                            font-size: 0.8rem;
                                            font-weight: bold;
                                        "
                                        class="text-center text-gray-500"
                                    >
                                        {{ $t("Week") }}
                                    </p>
                                    <div class="base-timer">
                                        <svg
                                            class="base-timer__svg"
                                            viewBox="0 0 100 100"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <g class="base-timer__circle">
                                                <circle
                                                    class="base-timer__path-elapsed"
                                                    cx="50"
                                                    cy="50"
                                                    r="35"
                                                ></circle>
                                                <circle
                                                    v-if="
                                                        getIfWorkedHoursNotZero(
                                                            workingInvoiceWeeksHours
                                                        ) &&
                                                        hoursSpecificOnTheWeek
                                                    "
                                                    class="base-timer__path-elapsed"
                                                    cx="50"
                                                    cy="50"
                                                    r="35"
                                                    :style="{
                                                        stroke: circleColor(
                                                            getHoursWorkedDecimal(
                                                                workingInvoiceWeeksHours
                                                            ),
                                                            hoursSpecificOnTheWeek
                                                        ),
                                                        strokeDashoffset:
                                                            dashOffset(
                                                                getHoursWorkedDecimal(
                                                                    workingInvoiceWeeksHours
                                                                ),
                                                                hoursSpecificOnTheWeek
                                                            ),
                                                        strokeDasharray:
                                                            dashArray(),
                                                    }"
                                                ></circle>
                                            </g>
                                        </svg>
                                        <span
                                            id="base-timer-label"
                                            class="base-timer__label flex flex-col"
                                        >
                                            <p>
                                                {{
                                                    this
                                                        .workingInvoiceWeeksHours
                                                }}
                                            </p>
                                            <p
                                                class="text-gray-500"
                                                style="
                                                    font-size: 0.8rem;
                                                    font-weight: bold;
                                                "
                                            ></p>
                                        </span>
                                    </div>
                                </div>
                                <div class="">
                                    <p
                                        style="
                                            font-size: 0.8rem;
                                            font-weight: bold;
                                        "
                                        class="text-center text-gray-500"
                                    >
                                        {{ $t("Month") }}
                                    </p>
                                    <div class="base-timer">
                                        <svg
                                            class="base-timer__svg"
                                            viewBox="0 0 100 100"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <g class="base-timer__circle">
                                                <circle
                                                    class="base-timer__path-elapsed"
                                                    cx="50"
                                                    cy="50"
                                                    r="35"
                                                ></circle>
                                                <circle
                                                    v-if="
                                                        getIfWorkedHoursNotZero(
                                                            workingInvoiceMonthHours
                                                        ) &&
                                                        hoursSpecificOnTheMonth
                                                    "
                                                    class="base-timer__path-elapsed"
                                                    cx="50"
                                                    cy="50"
                                                    r="35"
                                                    :style="{
                                                        stroke: circleColor(
                                                            getHoursWorkedDecimal(
                                                                workingInvoiceMonthHours
                                                            ),
                                                            hoursSpecificOnTheMonth
                                                        ),
                                                        strokeDashoffset:
                                                            dashOffset(
                                                                getHoursWorkedDecimal(
                                                                    workingInvoiceMonthHours
                                                                ),
                                                                hoursSpecificOnTheMonth
                                                            ),
                                                        strokeDasharray:
                                                            dashArray(),
                                                    }"
                                                ></circle>
                                            </g>
                                        </svg>
                                        <span
                                            id="base-timer-label"
                                            class="base-timer__label flex flex-col"
                                        >
                                            <p>
                                                {{
                                                    this
                                                        .workingInvoiceMonthHours
                                                }}
                                            </p>
                                            <p
                                                class="text-gray-500"
                                                style="
                                                    font-size: 0.8rem;
                                                    font-weight: bold;
                                                "
                                            ></p>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <TargetValueMonthGraph
                            :targetValueMonth="targetValue"
                        />
                        <div class="relative">
                            <GaugeChart
                                :targetValueAbsoluteMonth="targetValueAbsolute"
                                :targetValueMonth="targetValue"
                            />
                        </div>
                    </div>
                </div>
                <div>
                    <div
                        class="shadow overflow m-4 p-4"
                        style="margin-top: 4rem"
                    >
                        <div class="flex">
                            <!-- Date at which the time is logged for -->
                            <DateInput
                                :label="$t('Date')"
                                :max="
                                    $hasRole('admin')
                                        ? null
                                        : new Date()
                                              .addDays(3)
                                              .toLocaleDateString('fr-ca', {
                                                  timeZone: 'Europe/Berlin',
                                              })
                                "
                                v-model="date"
                                :required="true"
                                @change="getTrackerData"
                                :setCurrentDate="true"
                                class="pr-6 w-full lg:w-1/3"
                            />
                            <!-- user?.roles?.includes('admin') -->
                            <MultiSelectInput
                                v-if="$can('company.list')"
                                class="pr-6 w-full lg:w-1/3"
                                :textLabel="$t('Company')"
                                :required="true"
                                v-model="filterCompany"
                                :options="companies.data"
                                @update:modelValue="getTrackerData"
                                :multiple="false"
                                label="companyName"
                                trackBy="id"
                                moduleName="companies"
                            />
                            <MultiSelectInput
                                v-if="$can('user.list') && shouldShow"
                                class="pr-6 w-full lg:w-1/3"
                                v-model="userId"
                                :key="userId"
                                :showLabels="false"
                                :required="true"
                                :error="errors['userId']"
                                :options="userProfiles?.data ?? []"
                                :multiple="false"
                                :text-label="$t('User')"
                                label="firstName"
                                trackBy="userId"
                                moduleName="userProfile"
                                @update:modelValue="getTrackerData"
                                :search-param-name="'search'"
                                :query="{
                                    isConstrained: true,
                                }"
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
                                        <p class="overflow-text-users-table">
                                            {{ props.option.firstName }}
                                        </p>
                                        <p
                                            style="width: 10ch"
                                            class="overflow-text-users-table"
                                        >
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
                    <div v-show="isTrackerFormDisplay">
                        <text-input
                            v-model="startTime"
                            @change="starTimeChanged"
                            :type="`time`"
                            class="pb-8 pl-4 pr-6 w-full lg:w-1/3"
                            :label="$t('Start Time')"
                        />
                        <div class="flex gap-2">
                            <div
                                id="government-section"
                                class="w-1/3 shadow p-2 overflow-auto"
                            >
                                <!-- //Government Form -->
                                <h6
                                    class="text-xl font-normal leading-normal mt-0 mb-2 text-cyan-800"
                                >
                                    {{ $t("Legal time reporting") }}
                                </h6>
                                <table
                                    v-if="timeTrackerGovernment.length > 0"
                                    class="table-auto w-full mb-2"
                                >
                                    <thead>
                                        <tr
                                            style="
                                                border-bottom: 1px solid
                                                    lightgrey;
                                            "
                                        >
                                            <th
                                                class="text-left"
                                                style="width: 30px"
                                            >
                                                #
                                            </th>
                                            <th class="text-left">
                                                {{ $t("Type") }}
                                            </th>
                                            <th class="text-left">
                                                {{ $t("Description") }}
                                            </th>
                                            <th class="text-left">
                                                {{ $t("Start Time") }}
                                            </th>
                                            <th class="text-left">
                                                {{ $t("End Time") }}
                                            </th>
                                            <th class="text-left">
                                                {{ $t("Hours") }}
                                            </th>
                                            <th class="text-left">
                                                {{ $t("Action") }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(
                                                item, index
                                            ) in timeTrackerGovernment"
                                            :key="'government-' + index"
                                            class="text-center pb-2"
                                            style="
                                                border-bottom: 1px solid
                                                    lightgrey;
                                            "
                                        >
                                            <td
                                                class="text-left"
                                                :class="
                                                    item.hours == 0
                                                        ? 'text-red-500'
                                                        : ''
                                                "
                                                style="vertical-align: top"
                                            >
                                                {{ index + 1 }}
                                            </td>
                                            <td
                                                class="text-left"
                                                :class="
                                                    item.hours == 0
                                                        ? 'text-red-500'
                                                        : ''
                                                "
                                                style="vertical-align: top"
                                            >
                                                {{
                                                    moduleTypeEnums?.[item.type]
                                                }}
                                            </td>
                                            <td
                                                class="pl-2 description-box-length text-left"
                                                :class="
                                                    item.hours == 0
                                                        ? 'text-red-500'
                                                        : ''
                                                "
                                                style="
                                                    white-space: pre-wrap;
                                                    text-align: justify;
                                                    vertical-align: top;
                                                "
                                            >
                                                <span
                                                    v-if="item.description"
                                                    v-html="item.description"
                                                ></span>
                                                <span v-else>
                                                    {{ $t("N/A") }}
                                                </span>
                                            </td>
                                            <td
                                                class="text-left"
                                                :class="
                                                    item.hours == 0
                                                        ? 'text-red-500'
                                                        : ''
                                                "
                                                style="vertical-align: top"
                                            >
                                                {{
                                                    (
                                                        item?.startTime ?? ""
                                                    ).slice(0, 5)
                                                }}
                                            </td>
                                            <td
                                                class="text-left"
                                                style="vertical-align: top"
                                                :class="
                                                    item.hours == 0
                                                        ? 'text-red-500'
                                                        : ''
                                                "
                                            >
                                                {{
                                                    (item?.endTime ?? "").slice(
                                                        0,
                                                        5
                                                    )
                                                }}
                                            </td>
                                            <td
                                                class="text-left"
                                                style="vertical-align: top"
                                                :class="
                                                    item.hours == 0
                                                        ? 'text-red-500'
                                                        : ''
                                                "
                                            >
                                                {{
                                                    numberFormatter(
                                                        diff(
                                                            item.startTime,
                                                            item.endTime
                                                        ),
                                                        2,
                                                        2
                                                    ) ?? $t("N/A")
                                                }}
                                            </td>
                                            <td
                                                v-if="
                                                    !restrictEditingForNonAdmins
                                                "
                                                style="vertical-align: top"
                                                class="flex text-left"
                                                :class="
                                                    item.hours == 0
                                                        ? 'text-red-500'
                                                        : ''
                                                "
                                            >
                                                <button
                                                    v-if="
                                                        item.status !== 'pr' &&
                                                        item.status !== 'billed'
                                                    "
                                                    @click="
                                                        editGovernmentModal(
                                                            index
                                                        )
                                                    "
                                                    class="px-1"
                                                >
                                                    <font-awesome-icon
                                                        icon="fa-regular fa-pen-to-square"
                                                    ></font-awesome-icon>
                                                </button>
                                                <button
                                                    v-if="
                                                        item.status !== 'pr' &&
                                                        item.status !== 'billed'
                                                    "
                                                    @click="
                                                        removeRecord(
                                                            index,
                                                            'government'
                                                        )
                                                    "
                                                >
                                                    <font-awesome-icon
                                                        icon="fa-regular fa-trash-can"
                                                    ></font-awesome-icon>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div v-else class="text-center w-full m-8">
                                    <i>{{ $t("No time track found") }}</i>
                                </div>

                                <loading-button
                                    v-if="!restrictEditingForNonAdmins"
                                    @click="logNewTimeGovernment"
                                    class="btn-green float-right mt-4"
                                >
                                    <font-awesome-icon
                                        class="cursor-pointer mr-3"
                                        color="white"
                                        icon="fa-solid fa-plus"
                                    />{{
                                        $t(
                                            "Log new time for legal time reporting"
                                        )
                                    }}
                                </loading-button>
                            </div>
                            <div
                                id="company-section"
                                class="w-2/3 shadow p-2 overflow-auto"
                            >
                                <!-- //Company Form -->
                                <h6
                                    class="text-xl font-normal leading-normal mt-0 mb-2 text-cyan-800"
                                >
                                    {{ $t("Customer") }}
                                </h6>
                                <table
                                    v-if="timeTrackerCompany.length > 0"
                                    class="table-auto w-full mb-2"
                                >
                                    <thead>
                                        <tr
                                            style="
                                                border-bottom: 1px solid
                                                    lightgrey;
                                            "
                                        >
                                            <th>#</th>
                                            <th>{{ $t("Type") }}</th>
                                            <th>{{ $t("Object") }}</th>
                                            <th>{{ $t("Description") }}</th>
                                            <th>{{ $t("Time") }}</th>
                                            <th>{{ $t("Customer") }}</th>
                                            <th>{{ $t("Is Goodwill?") }}</th>
                                            <th>{{ $t("Action") }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(
                                                item, index
                                            ) in timeTrackerCompany"
                                            :key="'company-' + index"
                                            class="text-center"
                                            style="
                                                border-bottom: 1px solid
                                                    lightgrey;
                                            "
                                        >
                                            <td
                                                :class="
                                                    item?.previousTime >= 16
                                                        ? 'text-red-500'
                                                        : ''
                                                "
                                                style="vertical-align: top"
                                            >
                                                {{ index + 1 }}
                                            </td>
                                            <td
                                                :class="
                                                    item?.previousTime >= 16
                                                        ? 'text-red-500'
                                                        : ''
                                                "
                                                style="vertical-align: top"
                                            >
                                                {{
                                                    moduleTypeEnums?.[
                                                        item.moduleType
                                                    ]
                                                }}
                                            </td>
                                            <td
                                                :class="
                                                    item?.previousTime >= 16
                                                        ? 'text-red-500'
                                                        : ''
                                                "
                                                style="vertical-align: top"
                                            >
                                                {{
                                                    item.moduleType === "ticket"
                                                        ? item.additionalInfo
                                                              ?.ticketNumber ??
                                                          $t("N/A")
                                                        : item.moduleType ===
                                                          "travel-expense"
                                                        ? item.additionalInfo
                                                              ?.travelNumber ??
                                                          $t("N/A")
                                                        : item.moduleId?.id ??
                                                          $t("N/A")
                                                }}
                                            </td>
                                            <td
                                                :class="
                                                    item?.previousTime >= 16
                                                        ? 'text-red-500'
                                                        : ''
                                                "
                                                class="pl-2 description-box-length"
                                                style="
                                                    white-space: pre-wrap;
                                                    text-align: justify;
                                                    vertical-align: top;
                                                "
                                            >
                                                <span
                                                    v-if="item.description"
                                                    v-html="item.description"
                                                ></span>
                                                <span v-else>
                                                    {{ $t("N/A") }}
                                                </span>
                                            </td>
                                            <td
                                                :class="
                                                    item?.previousTime >= 16
                                                        ? 'text-red-500'
                                                        : ''
                                                "
                                                style="vertical-align: top"
                                            >
                                                {{
                                                    numberFormatter(
                                                        item.time,
                                                        2,
                                                        2
                                                    ) ?? $t("N/A")
                                                }}
                                            </td>
                                            <td
                                                :class="
                                                    item?.previousTime >= 16
                                                        ? 'text-red-500'
                                                        : ''
                                                "
                                                style="vertical-align: top"
                                            >
                                                {{
                                                    item.company?.companyName ??
                                                    $t("N/A")
                                                }}
                                            </td>
                                            <td
                                                :class="
                                                    item?.previousTime >= 16
                                                        ? 'text-red-500'
                                                        : ''
                                                "
                                                style="vertical-align: top"
                                            >
                                                {{
                                                    item.isGoodwill
                                                        ? $t("Yes")
                                                        : $t("No")
                                                }}
                                            </td>
                                            <td
                                                v-if="
                                                    !restrictEditingForNonAdmins
                                                "
                                                :class="
                                                    item?.previousTime >= 16
                                                        ? 'text-red-500'
                                                        : ''
                                                "
                                            >
                                                <button
                                                    v-if="
                                                        item.status !== 'pr' &&
                                                        item.status !== 'billed'
                                                    "
                                                    @click="
                                                        editCompanyModal(index)
                                                    "
                                                    class="px-1"
                                                >
                                                    <font-awesome-icon
                                                        icon="fa-regular fa-pen-to-square"
                                                    ></font-awesome-icon>
                                                </button>
                                                <button
                                                    v-if="
                                                        item.status !== 'pr' &&
                                                        item.status !== 'billed'
                                                    "
                                                    @click="
                                                        removeRecord(
                                                            index,
                                                            'company'
                                                        )
                                                    "
                                                >
                                                    <font-awesome-icon
                                                        icon="fa-regular fa-trash-can"
                                                    ></font-awesome-icon>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div v-else class="text-center w-full m-8">
                                    <i>{{ $t("No time track found") }}</i>
                                </div>

                                <loading-button
                                    v-if="!restrictEditingForNonAdmins"
                                    @click="this.toggleEditCompanyModal = true"
                                    class="btn-green float-right mt-4"
                                >
                                    <font-awesome-icon
                                        class="cursor-pointer mr-3"
                                        color="white"
                                        icon="fa-solid fa-plus"
                                    />{{ $t("Log new time for customer") }}
                                </loading-button>
                            </div>
                        </div>
                        <div
                            class="flex justify-between"
                            style="width: calc(45% - 2.5px)"
                        >
                            <p class="mt-4 mb-4">
                                {{ $t("Todays working hours yet") }}:
                                <span
                                    >{{
                                        numberFormatter(totalWorkingHours, 2, 2)
                                    }}
                                    {{ $t("hr(s)") }}</span
                                >
                            </p>
                            <p class="mt-4 mb-4">
                                {{ $t("Overtime:") }}
                                <span class="text-green-500">
                                    {{
                                        numberFormatter(calculateOvertime, 2, 2)
                                    }}
                                    {{ $t("hr(s)") }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <EditModal
                        :title="$t('Government')"
                        v-if="toggleEditGovernmentModal"
                        @toggleModal="cancelGovernment"
                        :classSize="'modal-md'"
                    >
                        <template #body>
                            <div class="grid items-center grid-cols-2 gap-6">
                                <div class="w-full">
                                    <div class="form-group">
                                        <!-- Define the type: ticket/task -->
                                        <select-input
                                            v-model="
                                                timeTrackerGovernmentTemp.type
                                            "
                                            :key="
                                                timeTrackerGovernmentTemp.type
                                            "
                                            :label="$t('Type')"
                                            :required="true"
                                            :error="modalErrors.governmentType"
                                            @update:model-value="typeSelected"
                                        >
                                            <option value="task">
                                                {{ $t("Task") }}
                                            </option>
                                            <option value="break">
                                                {{ $t("Break") }}
                                            </option>
                                            <option value="vacation">
                                                {{ $t("Vacation") }}
                                            </option>
                                            <option value="illness">
                                                {{ $t("Illness") }}
                                            </option>
                                            <option value="internal">
                                                {{ $t("Internal") }}
                                            </option>
                                            <option value="training">
                                                {{ $t("Training") }}
                                            </option>
                                            <option value="take-from-overdue">
                                                {{ $t("Take from overdue") }}
                                            </option>
                                            <option value="sales-presales">
                                                {{ $t("Sales/Presales") }}
                                            </option>
                                            <option value="meeting">
                                                {{ $t("Meeting") }}
                                            </option>
                                            <option value="public-holiday">
                                                {{ $t("Public Holiday") }}
                                            </option>
                                            <option
                                                v-if="
                                                    actionType === 'edit' &&
                                                    (timeTrackerGovernmentTemp.type ===
                                                        '' ||
                                                        timeTrackerGovernmentTemp.type ===
                                                            'newEntry')
                                                "
                                                value="newEntry"
                                            >
                                                {{ $t("New Entry") }}
                                            </option>
                                            <option
                                                v-if="
                                                    actionType === 'edit' &&
                                                    (timeTrackerGovernmentTemp.type ===
                                                        '' ||
                                                        timeTrackerGovernmentTemp.type ===
                                                            'ticket')
                                                "
                                                value="ticket"
                                            >
                                                {{ $t("Ticket") }}
                                            </option>
                                            <option
                                                v-if="
                                                    actionType === 'edit' &&
                                                    (timeTrackerGovernmentTemp.type ===
                                                        '' ||
                                                        timeTrackerGovernmentTemp.type ===
                                                            'ams')
                                                "
                                                value="ams"
                                            >
                                                {{ $t("AMS") }}
                                            </option>
                                            <option
                                                v-if="
                                                    actionType === 'edit' &&
                                                    (timeTrackerGovernmentTemp.type ===
                                                        '' ||
                                                        timeTrackerGovernmentTemp.type ===
                                                            'travel-expense')
                                                "
                                                value="travel-expense"
                                            >
                                                {{ $t("Travel Expense") }}
                                            </option>
                                        </select-input>
                                    </div>
                                </div>
                                <div
                                    class="w-full"
                                    v-if="
                                        timeTrackerGovernmentTemp.type ===
                                        'sales-presales'
                                    "
                                >
                                    <div class="form-group">
                                        <!-- Display company listing if selected type is "sales/presales" -->
                                        <select-input
                                            v-model="customerType"
                                            class="pb-8 pr-6 w-1/2"
                                            :label="$t('Customer Type')"
                                            :required="true"
                                            :error="modalErrors.customerType"
                                            @update:model-value="
                                                customerTypeChanged
                                            "
                                        >
                                            <option value="lead">
                                                {{ $t("Lead") }}
                                            </option>
                                            <option value="customer">
                                                {{ $t("Customer") }}
                                            </option>
                                        </select-input>
                                    </div>
                                </div>
                                <div
                                    class="w-full"
                                    v-if="
                                        timeTrackerGovernmentTemp.type ===
                                            'sales-presales' &&
                                        customerType &&
                                        $can('company.list')
                                    "
                                >
                                    <div class="form-group">
                                        <!-- Which customer to associate with -->
                                        <MultiSelectInput
                                            v-model="
                                                timeTrackerGovernmentTemp.customerId
                                            "
                                            :key="
                                                timeTrackerGovernmentTemp.customerId
                                            "
                                            :required="true"
                                            :textLabel="$t('Receiver')"
                                            :placeholder="$t('Customer')"
                                            :options="customerOptions"
                                            :multiple="false"
                                            label="companyName"
                                            trackBy="id"
                                            moduleName="companies"
                                            :error="errors.customerId"
                                            :query="{
                                                customerType: customerType,
                                            }"
                                            :countStore="
                                                customerType === 'lead'
                                                    ? 'leadCount'
                                                    : 'count'
                                            "
                                            @asyncSearch="companiesSearch"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <!-- Description of the given type -->
                                        <text-area-input
                                            v-model="
                                                timeTrackerGovernmentTemp.description
                                            "
                                            :required="true"
                                            rows="1"
                                            :label="$t('Description')"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <!-- Time spent on the given task -->
                                        <text-input
                                            @input="startTimeChanged"
                                            v-model="
                                                timeTrackerGovernmentTemp.startTime
                                            "
                                            :type="`time`"
                                            :required="true"
                                            :label="$t('Start Time')"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <number-input
                                            :roundNearQuarter="true"
                                            :forceLeftBound="true"
                                            :showPrefix="false"
                                            v-model="
                                                timeTrackerGovernmentTemp.hours
                                            "
                                            :min="0"
                                            :required="true"
                                            :label="$t('Hours Taken')"
                                            @update:model-value="hoursChanged"
                                            :errors="
                                                modalErrors.governmentHours ??
                                                ''
                                            "
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            @input="endTimeChanged"
                                            v-model="
                                                timeTrackerGovernmentTemp.endTime
                                            "
                                            :type="`time`"
                                            :label="$t('End Time')"
                                            :error="errors.governmentEndTime"
                                        />
                                    </div>
                                </div>
                            </div>
                        </template>
                        <template #footer>
                            <button
                                @click="cancelGovernment"
                                type="button"
                                class="primary-btn mr-3"
                            >
                                {{ $t("Cancel") }}
                            </button>
                            <button
                                type="button"
                                class="secondary-btn"
                                @click="savetimeTrackerGovernment"
                            >
                                {{
                                    actionType === "add"
                                        ? $t("Create")
                                        : $t("Save")
                                }}
                            </button>
                        </template>
                    </EditModal>

                    <EditModal
                        :title="$t('Customer')"
                        v-if="toggleEditCompanyModal"
                        @toggleModal="cancelCompany"
                        :classSize="'modal-md'"
                    >
                        <template #body>
                            <div class="grid items-center grid-cols-2 gap-6">
                                <div class="w-full col-span-2">
                                    <div class="form-group">
                                        <select-input
                                            :required="true"
                                            :isReadOnly="actionType === 'edit'"
                                            v-model="moduleType"
                                            class=""
                                            :label="$t('Type')"
                                        >
                                            <option
                                                v-if="$can('task.list')"
                                                value="task"
                                            >
                                                {{ $t("Task") }}
                                            </option>
                                            <option
                                                v-if="$can('ticket.list')"
                                                value="ticket"
                                            >
                                                {{ $t("Ticket") }}
                                            </option>
                                            <option
                                                v-if="
                                                    $can(
                                                        'application-management-services.list'
                                                    )
                                                "
                                                value="ams"
                                            >
                                                {{ $t("AMS") }}
                                            </option>
                                            <option
                                                v-if="
                                                    $can('travel-expenses.list')
                                                "
                                                value="travel-expense"
                                            >
                                                {{ $t("Travel Expense") }}
                                            </option>
                                            <option value="newEntry">
                                                {{ $t("New Entry") }}
                                            </option>
                                        </select-input>
                                    </div>
                                </div>
                                <div
                                    class="w-full col-span-2"
                                    v-if="moduleType === 'task'"
                                >
                                    <div class="form-group">
                                        <task-form
                                            :filterCompany="filterCompany"
                                            :modalErrors="modalErrors"
                                            :actionType="actionType"
                                            :timeTrackerCompanyEditData="
                                                timeTrackerCompanyTemp
                                            "
                                            ref="taskFormComponent"
                                        >
                                        </task-form>
                                    </div>
                                </div>
                                <div
                                    class="w-full col-span-2"
                                    v-if="moduleType === 'ticket'"
                                >
                                    <div class="form-group">
                                        <ticket-form
                                            :filterCompany="filterCompany"
                                            :modalErrors="modalErrors"
                                            :actionType="actionType"
                                            :date="date"
                                            :timeTrackerCompanyEditData="
                                                timeTrackerCompanyTemp
                                            "
                                            :userId="
                                                this.userId?.userId
                                                    ? this.userId?.userId
                                                    : this.user.id
                                            "
                                            ref="ticketFormComponent"
                                        >
                                        </ticket-form>
                                    </div>
                                </div>
                                <div
                                    class="w-full col-span-2"
                                    v-if="moduleType === 'ams'"
                                >
                                    <div class="form-group">
                                        <ams-form
                                            :filterCompany="filterCompany"
                                            :modalErrors="modalErrors"
                                            :actionType="actionType"
                                            :timeTrackerCompanyEditData="
                                                timeTrackerCompanyTemp
                                            "
                                            ref="amsFormComponent"
                                        >
                                        </ams-form>
                                    </div>
                                </div>
                                <div
                                    class="w-full col-span-2"
                                    v-if="moduleType === 'travel-expense'"
                                >
                                    <div class="form-group">
                                        <travel-expense-form
                                            :filterCompany="filterCompany"
                                            :modalErrors="modalErrors"
                                            :actionType="actionType"
                                            :date="date"
                                            :timeTrackerCompanyEditData="
                                                timeTrackerCompanyTemp
                                            "
                                            ref="travelExpenseFormComponent"
                                        >
                                        </travel-expense-form>
                                    </div>
                                </div>
                                <div
                                    class="w-full col-span-2"
                                    v-if="moduleType === 'newEntry'"
                                >
                                    <div class="form-group">
                                        <new-entry-form
                                            :modalErrors="modalErrors"
                                            :actionType="actionType"
                                            :filterCompany="filterCompany"
                                            :timeTrackerCompanyEditData="
                                                timeTrackerCompanyTemp
                                            "
                                            ref="newEntryFormComponent"
                                        >
                                        </new-entry-form>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <template #footer>
                            <button
                                @click="cancelCompany"
                                type="button"
                                class="primary-btn mr-3"
                            >
                                {{ $t("Cancel") }}
                            </button>
                            <button
                                type="button"
                                class="secondary-btn"
                                @click="savetimeTrackerCompany"
                                v-if="moduleType != '' && isCompanyFormShow()"
                            >
                                {{
                                    actionType === "add"
                                        ? $t("Create")
                                        : $t("Save")
                                }}
                            </button>
                        </template>
                    </EditModal>
                </div>
            </div>
            <div v-show="activeTab === 'tab2'">
                <div class="shadow overflow m-4 p-4" style="margin-top: 4rem">
                    <div class="flex">
                        <div class="w-full lg:w-1/2 px-2">
                            <label class="">{{ $t("Month") }}</label>
                            <datepicker
                                :clearable="false"
                                :locale="globalLanguage"
                                :enable-time-picker="false"
                                auto-apply
                                :close-on-auto-apply="false"
                                v-model="month"
                                month-picker
                                @update:model-value="showMonth"
                            />
                        </div>
                        <div class="w-full lg:w-1/2 px-2">
                            <MultiSelectInput
                                v-if="$can('user.list') && shouldShow"
                                class="3"
                                v-model="userId"
                                :key="userId"
                                :showLabels="false"
                                :required="true"
                                :error="errors['userId']"
                                :options="userProfiles?.data ?? []"
                                :multiple="false"
                                :text-label="$t('User')"
                                label="firstName"
                                trackBy="userId"
                                moduleName="userProfile"
                                @update:modelValue="fetchOverviewRecord"
                                :search-param-name="'search'"
                                :query="{
                                    isConstrained: true,
                                }"
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
                                        <p class="overflow-text-users-table">
                                            {{ props.option.firstName }}
                                        </p>
                                        <p
                                            style="width: 10ch"
                                            class="overflow-text-users-table"
                                        >
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
                    <div class="w-full mt-5 flex items-center px-2">
                        <Calendar
                            class="time-tracker-calender"
                            :attributes="attributes"
                            v-model="selectedDate"
                            disable-page-swipe
                            is-expanded
                            style="width: 100%"
                            :min-Date="minDate"
                            :max-Date="maxDate"
                            :key="month"
                            :locale="globalLanguage"
                        >
                            <template v-slot:day-content="{ day, attributes }">
                                <div
                                    class="flex flex-col h-full z-10 overflow-hidden"
                                    @click="getOverViewReporting(day)"
                                >
                                    <span
                                        class="day-label text-sm text-gray-900"
                                        >{{ day.day }}</span
                                    >
                                    <div
                                        class="flex-grow overflow-y-auto overflow-x-auto"
                                    >
                                        <p
                                            v-for="attr in attributes"
                                            :key="attr.key"
                                            style="font-size: large !important"
                                            class="leading-tight rounded-sm p-1 mt-0 mb-1 cursor-pointer"
                                            :class="attr.customData?.class"
                                        >
                                            {{ attr.customData?.title }}
                                        </p>
                                    </div>
                                </div>
                            </template>
                        </Calendar>
                    </div>
                </div>
            </div>
            <modal
                :toggleCalenderModal="toggleCalenderModal"
                :overViewReportingData="overViewReportingData"
                @toggleCalenderModal="toggleCalenderModal = $event"
            />
        </div>
    </div>
</template>

<script>
import LoadingButton from "@/Components/LoadingButton.vue";
import EditModal from "@/Components/EditModal.vue";
import DateInput from "@/Components/DateInput.vue";
import TextInput from "@/Components/TextInput.vue";
import NumberInput from "@/Components/NumberInput.vue";
import TextAreaInput from "@/Components/TextareaInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";
import Split from "split.js";

import TicketForm from "./Components/TicketForm.vue";
import TaskForm from "./Components/TaskForm.vue";
import AMSForm from "./Components/AMSForm.vue";
import TravelExpenseForm from "./Components/TravelExpenseForm.vue";
import NewEntryForm from "./Components/NewEntryForm.vue";
import TargetValueMonthGraph from "./Components/TargetValueMonthGraph.vue";
import TargetValueAbsoluteMonthGraph from "./Components/TargetValueAbsoluteMonthGraph.vue";
import GaugeChart from "./Components/GaugeChart.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import modal from "./Components/Modal.vue";
import { Calendar } from "v-calendar";
import "v-calendar/style.css";

/**
 * Takes the number of days and a date and adds number of specified days to the date and return it
 * @params {days} the number of days to add
 * @params {date} a optional date, if the user wants to specify a date to add days to. If no date is specified, the current date is taken
 */
Date.prototype.addDays = (days, date = new Date()) => {
    const d = new Date(date.valueOf());
    d.setDate(d.getDate() + days);
    return d;
};
/**
 * Takes the number of days and a date and subtracts number of specified days to the date and return it
 * @params {days} the number of days to subtract
 * @params {date} a optional date, if the user wants to specify a date to subtract days from. If no date is specified, the current date is taken
 */
Date.prototype.subtractDays = (days, date = new Date()) => {
    const d = new Date(date.valueOf());
    d.setDate(d.getDate() - days);
    return d;
};

export default {
    computed: {
        ...mapGetters(["language"]),
        ...mapGetters("tasks", ["tasks"]),
        ...mapGetters("travelExpense", ["travelExpenses"]),
        ...mapGetters("companies", {
            companies: "companies",
        }),
        ...mapGetters("auth", ["users", "user"]),
        ...mapGetters("userProfile", ["userProfiles"]),
        // returns true if the auth user is not an admin and the time tracker date is less than (current date - 14 days)
        restrictEditingForNonAdmins() {
            let minDate = new Date().subtractDays(14);
            minDate.setHours(0);
            minDate.setMinutes(0);
            minDate.setSeconds(0);
            return this.$hasRole("admin")
                ? false
                : new Date(this.date) < minDate;
        },
        totalWorkingHours() {
            let totalWorkingHours = 0;
            this.totalVacationWorkingHours = 0; // Reset the value before calculating
            this.timeTrackerGovernment.forEach((timeTracker) => {
                if (
                    timeTracker.type !== "break" &&
                    timeTracker.type !== "illness"
                ) {
                    totalWorkingHours += +this.diff(
                        timeTracker.startTime,
                        timeTracker.endTime
                    );
                    if (timeTracker.type === "vacation") {
                        this.totalVacationWorkingHours += +this.diff(
                            timeTracker.startTime,
                            timeTracker.endTime
                        );
                    }
                }
            });
            return totalWorkingHours;
        },
        calculateOvertime() {
            const totalDeductionHours =
                this.totalVacationWorkingHours + this.hoursSpecifiedOnTheDay;
            if (this.totalWorkingHours > totalDeductionHours) {
                return this.totalWorkingHours - totalDeductionHours;
            }
            return 0;
        },
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
                this.modalErrors.selectedTravelExpenses != "" ||
                this.modalErrors.projectId != ""
            );
        },
        lastDay() {
            return new Date(this.month.year, this.month.month + 1, 0).getDate();
        },
        maxDate() {
            return new Date(
                `${this.month.year}-${this.month.month + 1}-${this.lastDay}`
            );
        },
        minDate() {
            return new Date(`${this.month.year}-${this.month.month + 1}-01`);
        },
        selectedDate() {
            return new Date(`${this.month.year}-${this.month.month + 1}-1`);
        },
    },
    components: {
        GaugeChart,
        LoadingButton,
        EditModal,
        DateInput,
        TextInput,
        TextAreaInput,
        SelectInput,
        MultiSelectInput,
        NumberInput,
        TicketForm,
        TaskForm,
        NewEntryForm,
        TravelExpenseForm,
        "ams-form": AMSForm,
        TargetValueAbsoluteMonthGraph,
        TargetValueMonthGraph,
        Calendar,
        modal,
        PageHeader,
    },
    data() {
        return {
            overtime: 0,
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Time Tracker",
                    active: true,
                },
            ],
            showDate: new Date(),
            attributes: [],
            overViewReportingData: [],
            masks: {
                weekdays: "WWW",
            },
            tickets: {
                data: [],
                links: [],
            },
            activeTab: "tab1",
            moduleTypeEnums: {
                task: this.$t("Task"),
                ticket: this.$t("Ticket"),
                break: this.$t("Break"),
                vacation: this.$t("Vacation"),
                illness: this.$t("Illness"),
                internal: this.$t("Internal"),
                training: this.$t("Training"),
                "take-from-overdue": this.$t("Take from overdue"),
                "sales-presales": this.$t("Sales/Presales"),
                meeting: this.$t("Meeting"),
                newEntry: this.$t("New Entry"),
                "public-holiday": this.$t("Public Holiday"),
                ams: "ams",
                "travel-expense": this.$t("Travel Expense"),
            },
            quarterEnums: {
                25: 15,
                50: 30,
                75: 45,
            },
            shouldShow: true,
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
                ams: "",
            },
            targetValue: 0,
            targetValueAbsolute: [],
            oldWorkedHours: "",
            nowWorkedHours: "",

            totalWorkedHours: "", //Sum of all worked prior selected date
            expectedWorkedHours: "", //Sum of all expected hours as per employee job data prior to selected date

            consumedHolidays: "",
            plannedHolidays: "",
            totalAnnualLeaves: "",
            workingDaysHours: "",
            workingMonthsHours: "",
            workingWeeksHours: "",
            workingInvoiceDaysHours: "",
            workingInvoiceWeeksHours: "",
            workingInvoiceMonthHours: "",
            monthlyOvertime: 0, //monthly overtime
            split: null,
            startTime: "08:00:00",
            actionType: "add",
            selectedGovermentTimeIndex: "",
            hoursSpecifiedOnTheDay: "",
            hoursSpecificOnTheWeek: "",
            hoursSpecificOnTheMonth: "",
            filterCompany: {
                id: "all",
                companyName: "All",
            },
            toggleFilter: false,
            isTrackerFormDisplay: false,
            date: "",
            month: {
                month: new Date().getMonth(),
                year: new Date().getFullYear(),
            },
            userId: "",
            overviewUserId: "",
            errors: {
                governmentEndTime: "",
                customerEndTime: "",
            },
            form: {
                saveProcessing: false,
                generateProcessing: false,
            },
            //Government modal
            toggleEditGovernmentModal: false,
            timeTrackerGovernmentTemp: {
                hours: 0.0,
            },
            timeTrackerGovernment: [],

            //Company modal
            toggleEditCompanyModal: false,
            moduleType: "",
            timeTrackerCompanyTemp: {
                selectedComments: [],
                time: 0,
            },
            timeTrackerCompany: [],
            //ticket comments
            ticketComments: [],

            totalVacationWorkingHours: 0,
            customerType: "",
            customerOptions: [],
            toggleCalenderModal: false,
        };
    },
    unmounted() {
        // reset the userProfiles state since we don't wanna show filtered userProfiles in other modules
        this.$store.commit("userProfile/userProfiles", {
            data: [],
            links: [],
        });
        // reset the projects state since we don't wanna show filtered projects in other modules
        this.$store.commit("projects/projects", {
            data: [],
            links: [],
        });
        // reset the tickets state since we don't wanna show filtered tickets in other modules
        this.$store.commit("tickets/tickets", {
            data: [],
            links: [],
        });
    },
    async mounted() {
        this.$store.commit("showLoader", true);
        // fetch the userProfiles
        await this.$store.dispatch("userProfile/list", {
            isConstrained: true,
        });

        await this.$store.dispatch("auth/list", {
            limit_start: 0,
            limit_count: 25,
            type: "employee",
        });

        // if date is present in query params then set date from query params
        if (this.$route.query.date) {
            this.date = this.$route.query.date;
        }

        // if userId is set in query params then find the userProfile for the query param userId and set the userId state
        // else set the logged in user
        if (this.$route.query.userId) {
            const user = await this.$store.dispatch(
                "userProfile/showProfileByUserId",
                this.$route.query.userId
            );
            this.userId = user?.data?.data ?? this.userId;
        } else {
            // find the user profile object from the listing
            this.userId = this.userProfiles?.data?.find(
                (user) => user.userId == this.user.id
            );
        }

        // if userId is not found in the listing then fetch individually using the 'showProfileByUserId' API
        if (!this.userId) {
            const userProfile = await this.$store.dispatch(
                "userProfile/showProfileByUserId",
                this.user.id
            );
            this.userId = userProfile?.data?.data ?? "";
        }

        await this.$store.dispatch("companies/list", {
            perPage: 25,
            page: 1,
        });

        const response = await this.$store.dispatch("tickets/list", {
            perPage: 25,
            page: 1,
            isArchived: false,
        });

        this.tickets = response?.data ?? this.tickets;
        this.$store.commit("showLoader", false);
        await this.$store.dispatch("tasks/list", { perPage: 10 });
        await this.getTrackerData();

        // if id is present in query params then find the index of the id in timeTrackerCompany array
        // and call the editCompanyModal method on the found index to open up the edit modal
        if (this.$route.query.id) {
            const index = this.timeTrackerCompany.findIndex(
                (entry) => entry.id == this.$route.query.id
            );
            if (index > -1) {
                this.editCompanyModal(index);
            }
        }
        this.shouldShow = false;
        await this.$nextTick();
        this.shouldShow = true;
        this.split = Split(["#government-section", "#company-section"], {
            sizes: [45, 55],
            gutterSize: 5,
        });
    },
    watch: {
        userId(newVal, oldVal) {
            this.fetchOverviewRecord();
        },
    },
    methods: {
        async getOverViewReporting(day) {
            const response = await this.$store.dispatch(
                "timeTrackers/overViewReporting",
                {
                    date: day.id,
                    userId: this.userId?.userId,
                }
            );
            this.overViewReportingData = response?.data?.data;
            this.toggleCalenderModal = true;
        },

        calenderModalClose() {
            this.toggleCalenderModal = false;
        },
        showMonth(date) {
            this.fetchOverviewRecord();
        },
        matchDates(date1, date2) {
            return (
                date1.getUTCDate() == date2.getUTCDate() &&
                date1.getUTCMonth() == date2.getUTCMonth() &&
                date1.getUTCFullYear() == date2.getUTCFullYear()
            );
        },
        /**
         * sets time tracker government description automatically if the selected type is "vacation" or "public-holiday"
         * @param {event} input event
         */
        async typeSelected(event) {
            await this.$nextTick();
            const typeEnum = {
                vacation: "Vacation",
                "public-holiday": "Public Holiday",
            };
            if (!!typeEnum[event]) {
                this.timeTrackerGovernmentTemp.description = typeEnum[event];
            } else {
                this.timeTrackerGovernmentTemp.description = "";
            }
            this.modalErrors.governmentType = "";
        },
        companiesSearch(data) {
            this.customerOptions = data?.data;
        },
        async customerTypeChanged(item) {
            if (this.timeTrackerGovernmentTemp.type != "sales-presales") {
                this.customerOptions = [];
                return;
            }
            let response = await this.$store.dispatch("companies/list", {
                perPage: 25,
                page: 1,
                customerType: item,
            });
            this.customerOptions = response?.data?.data;
        },
        getIfWorkedHoursNotZero(value) {
            const [hours, minutes] = value.split(" ");
            return parseInt(hours) > 0 || parseInt(minutes) > 0;
        },
        getHoursWorkedDecimal(value) {
            // assuming the hours and minutes are in a string format
            const timeString = value;

            // split the string into hours and minutes
            const [hoursStr, minutesStr] = timeString.split(" ");

            // convert the hours and minutes to numbers
            const hours = parseInt(hoursStr);
            const minutes = parseInt(minutesStr);
            const totalHours = hours + minutes / 60;

            // calculate the total hours worked as a decimal value
            return totalHours;
        },
        percentage(hours, totalHours) {
            if (totalHours <= hours) return 100;
            return Math.floor((hours / totalHours) * 100);
        },
        circleColor(hours, totalHours) {
            if (this.percentage(hours, totalHours) <= 50) {
                return "red";
            } else if (
                this.percentage(hours, totalHours) > 50 &&
                this.percentage(hours, totalHours) <= 99
            ) {
                return "orange";
            } else {
                return "green";
            }
        },
        dashArray() {
            return 2 * Math.PI * 35;
        },
        dashOffset(hours, totalHours) {
            return (
                this.dashArray() *
                (1 - this.percentage(hours, totalHours) / 100)
            );
        },
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
        async hoursChanged(value) {
            await this.$nextTick();
            this.timeTrackerGovernmentTemp.hours = parseFloat(value).toFixed(2);
            this.timeTrackerGovernmentTemp.endTime = (() => {
                let tokens = (
                    this.timeTrackerGovernmentTemp?.startTime ?? ""
                ).split(":");
                let endTime = "";
                let splitValues = value.toString().split(".");
                let beforeFloatingPoint = splitValues?.[0] ?? "0";
                let afterFloatingPoint = (splitValues?.[1] ?? "0").slice(0, 2);
                let sumHours = +tokens[0] + +beforeFloatingPoint;
                let sumMinutes =
                    +tokens[1] +
                    ([25, 50, 75].includes(+afterFloatingPoint)
                        ? this.quarterEnums[+afterFloatingPoint]
                        : +afterFloatingPoint);
                if (sumMinutes >= 60) {
                    sumMinutes = sumMinutes % 60;
                    sumHours += 1;
                }
                if (sumHours > 24) {
                    endTime = `${sumHours % 24 < 10 ? "0" : ""}${
                        sumHours % 24
                    }:${sumMinutes < 10 ? "0" : ""}${sumMinutes}`;
                } else {
                    endTime = `${sumHours < 10 ? "0" : ""}${sumHours}:${
                        sumMinutes < 10 ? "0" : ""
                    }${sumMinutes}`;
                }
                return endTime;
            })();
        },
        setFreeTimeSlot() {
            // Find the first "free time slot" in timeTrackerGovernment
            let freeStartTime = null;
            let freeEndTime = null;
            if (this.timeTrackerGovernment.length > 0) {
                for (
                    let i = 0;
                    i < this.timeTrackerGovernment.length - 1;
                    i++
                ) {
                    const current = this.timeTrackerGovernment[i];
                    const next = this.timeTrackerGovernment[i + 1];
                    const currentEndTime = current.endTime;
                    const nextStartTime = next.startTime;
                    const timeDiff = this.diff(currentEndTime, nextStartTime);
                    if (timeDiff > 0) {
                        freeStartTime = currentEndTime;
                        freeEndTime = nextStartTime;
                        this.timeTrackerGovernmentTemp.hours = timeDiff;
                        break;
                    }
                }
            }
            // If no "free time slot" was found, use the last end time in the array or this.startTime
            if (!freeStartTime) {
                freeStartTime =
                    this.timeTrackerGovernment.length > 0
                        ? this.timeTrackerGovernment[
                              this.timeTrackerGovernment.length - 1
                          ].endTime
                        : this.startTime;
            }

            // Update the timeTrackerGovernmentTemp data variable
            this.timeTrackerGovernmentTemp.startTime = freeStartTime;
            this.timeTrackerGovernmentTemp.endTime = freeEndTime;
        },
        logNewTimeGovernment() {
            this.setFreeTimeSlot();
            this.toggleEditGovernmentModal = true;
        },
        startTimeChanged() {
            this.hoursChanged(this.timeTrackerGovernmentTemp.hours);
            if (this.selectedGovermentTimeIndex == 0) {
                this.startTime = this.timeTrackerGovernmentTemp.startTime;
            }
        },
        endTimeChanged() {
            this.timeTrackerGovernmentTemp.hours = this.diff(
                this.timeTrackerGovernmentTemp.startTime,
                this.timeTrackerGovernmentTemp.endTime
            );
        },
        starTimeChanged(event) {
            if (this.timeTrackerGovernment.length) {
                let timeTrackerData = this.timeTrackerGovernment[0];
                let timeDifference = +this.diff(
                    timeTrackerData.startTime,
                    timeTrackerData.endTime
                );
                this.timeTrackerGovernment[0] = {
                    ...timeTrackerData,
                    startTime: event.target.value,
                    endTime: (() => {
                        let tokens = event.target.value.split(":");
                        let sumHours = +tokens[0] + timeDifference;
                        let endTime = "";
                        if (sumHours > 24) {
                            endTime = `${sumHours % 24 < 10 ? "0" : ""}${
                                sumHours % 24
                            }:${tokens[1]}`;
                        } else {
                            endTime = `${sumHours}:${tokens[1]}`;
                        }
                        return endTime;
                    })(),
                };
            }
        },
        diff(startTime, endTime) {
            let start = startTime;
            let end = endTime;

            start = start?.split(":") ?? "";
            end = end?.split(":") ?? "";
            var startDate = new Date(0, 0, 0, start[0], start[1], 0);
            var endDate = new Date(0, 0, 0, end[0], end[1], 0);
            var diff = endDate.getTime() - startDate.getTime();
            var hours = diff / 1000 / 60 / 60;
            diff -= hours * 1000 * 60 * 60;
            return isNaN(hours) ? 0 : hours;
        },
        getTrackerData() {
            return new Promise(async (resolve, reject) => {
                try {
                    this.toggleFilter = true;
                    await this.fetchTrackerData();
                    this.toggleFilter = false;
                    this.isTrackerFormDisplay = true;
                    resolve();
                } catch (e) {
                    reject(e);
                }
            });
        },
        async fetchTaskData() {
            await this.$store.dispatch("tasks/byDate", {
                date: this.date,
                companyId:
                    this.filterCompany?.id === "all"
                        ? ""
                        : this.filterCompany?.id,
                userId: this.userId?.userId,
            });
        },
        async fetchTicketsData() {
            await this.$store.dispatch("tickets/articlesByDate", {
                date: this.date,
                companyId:
                    this.filterCompany?.id === "all"
                        ? ""
                        : this.filterCompany?.id,
                userId: this.userId?.userId,
            });
        },
        async fetchTrackerData() {
            return new Promise(async (resolve, reject) => {
                try {
                    const response = await this.$store.dispatch(
                        "timeTrackers/date",
                        {
                            date: this.date,
                            company:
                                this.filterCompany?.id === "all"
                                    ? ""
                                    : this.filterCompany?.id,
                            userId: this.userId?.userId,
                            auth_user_roles: this.user.roles,
                            auth_user_id: this.user.id,
                        }
                    );
                    this.targetValue = +(
                        response?.data?.targetValueData?.targetValue ?? 0
                    );
                    this.targetValueAbsolute =
                        response?.data?.targetValueData?.targetValueAbsolute ??
                        [];
                    this.timeTrackerGovernment =
                        response?.data?.timeTrackerGovernment;

                    /**
                     * Developer note: Following dataset added on temporary basis,
                     * the entire calculation will be re-evaluated
                     */
                    this.totalWorkedHours = response?.data?.totalWorkedHours;
                    this.expectedWorkedHours =
                        response?.data?.expectedWorkedHours;
                    this.overtime = response?.data?.overtime;

                    this.oldWorkedHours = response?.data?.oldWorkedHours;
                    this.nowWorkedHours = response?.data?.tillNowWorkedHours;
                    this.hoursSpecifiedOnTheDay =
                        response?.data?.hoursSpecifiedOnTheDay;
                    this.hoursSpecificOnTheWeek =
                        response?.data?.hoursSpecificOnTheWeek;
                    this.hoursSpecificOnTheMonth =
                        response?.data?.hoursSpecificOnTheMonth;
                    this.consumedHolidays =
                        response?.data?.totalAnnualLeavesAvailed;
                    this.monthlyOvertime = response?.data?.currentMonthOvertime; //set monthly overtime
                    this.plannedHolidays =
                        response?.data?.totalMonthlyAnnualLeavesPlanned;
                    this.totalAnnualLeaves = response?.data?.totalAnnualLeaves;
                    this.workingDaysHours =
                        response?.data?.governmentCurrentDayWorkedHours;
                    this.workingWeeksHours =
                        response?.data?.governmentCurrentWeekWorkedHours;
                    this.workingMonthsHours =
                        response?.data?.governmentCurrentMonthWorkedHours;
                    this.workingInvoiceDaysHours =
                        response?.data?.invoiceCurrentDayWorkedHours;
                    this.workingInvoiceWeeksHours =
                        response?.data?.invoiceCurrentWeekWorkedHours;
                    this.workingInvoiceMonthHours =
                        response?.data?.invoiceCurrentMonthWorkedHours;
                    this.startTime =
                        this.timeTrackerGovernment?.[0]?.startTime ??
                        "08:00:00";
                    this.timeTrackerCompany =
                        response?.data?.timeTrackerCompany;
                    this.timeTrackerGovernment = this.timeTrackerGovernment.map(
                        (trackerData) => {
                            return {
                                ...trackerData,
                                hours: this.diff(
                                    trackerData.startTime,
                                    trackerData.endTime
                                ),
                            };
                        }
                    );
                    let timeTrackerCompany = [];
                    // map the time tracker company data
                    for (let i = 0; i < this.timeTrackerCompany.length; i++) {
                        const trackerData = this.timeTrackerCompany[i];
                        // find the company from the companies listing
                        let company = this.companies?.data?.find(
                            (company) => company?.id == trackerData.company
                        );
                        // if the compnay is not found then fetch individually using the show API
                        if (!company) {
                            const response = await this.$store.dispatch(
                                "companies/show",
                                trackerData.company
                            );
                            company = response?.data?.modelData ?? {};
                        }
                        trackerData.company = company;
                        // map the moduleId
                        trackerData.moduleId =
                            trackerData.moduleType === "ticket"
                                ? this.tickets?.data?.find(
                                      (ticket) =>
                                          ticket.id == trackerData.moduleId
                                  ) ?? trackerData.moduleId
                                : trackerData.moduleType === "task"
                                ? this.tasks?.data?.find(
                                      (task) => task.id == trackerData.moduleId
                                  ) ?? trackerData.moduleId
                                : trackerData.moduleId;
                        timeTrackerCompany[i] = trackerData;
                    }
                    // set the mapped timeTrackerCompany to timeTrackerCompany state
                    this.timeTrackerCompany = [...timeTrackerCompany];
                    resolve();
                } catch (e) {
                    reject(e);
                }
            });
        },
        async fetchOverviewRecord() {
            const response = await this.$store.dispatch(
                "timeTrackers/overView",
                {
                    fromDate: this.minDate,
                    toDate: this.maxDate,
                    userId: this.userId?.userId,
                }
            );
            this.attributes = [];
            response?.data?.data.forEach((item, index) => {
                this.attributes.push({
                    key: ++index,
                    customData: {
                        title: item.hours + "h",
                        class: "overviewCalender_" + item.class + "",
                    },
                    dates: new Date(item.date),
                });
            });
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
            this.modalErrors.projectId = !item.projectId
                ? "Project not selected"
                : "";
            this.modalErrors.description =
                item.description == "" ? "Description not defined" : "";
        },
        getEndTime(startTime, hours) {
            //Get end time
            const startDateTime = new Date(`2000-01-01T${startTime}`);
            const duration = parseFloat(String(hours).replace(",", "."));
            const getTime = new Date(
                startDateTime.getTime() + duration * 60 * 60 * 1000
            );

            // Format the end time as a string in the format "hh:mm"
            return getTime.toLocaleTimeString("en-US", {
                hour12: false,
                hour: "2-digit",
                minute: "2-digit",
            });
        },
        async savetimeTrackerCompany() {
            // reset modal errors
            this.modalErrors = {
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
            };
            if (!(await this.validateAccountingDate())) {
                this.cancelCompany();
                return;
            }

            //Set start time as end time of last government entry if data exists
            let startTime = this.timeTrackerGovernment.length
                ? this.timeTrackerGovernment[
                      this.timeTrackerGovernment.length - 1
                  ]?.endTime
                : this.startTime;
            let endTime = "";

            if (this.moduleType === "ticket") {
                this.handleTicketData(startTime);
                return;
            }

            if (this.moduleType === "travel-expense") {
                this.handleTravelExpenseData(startTime);
                return;
            }

            //Get time tracker company data
            let timeTrackerCompany = {};
            if (this.moduleType === "task") {
                timeTrackerCompany = {
                    moduleId: this.$refs.taskFormComponent.task.id ?? "",
                    description: this.$refs.taskFormComponent.description,
                    internalNote: this.$refs.taskFormComponent.internalNote,
                    hours: this.$refs.taskFormComponent.accountedTime,
                    isGoodwill:
                        this.$refs.taskFormComponent.isGoodwill ?? false,
                    companyId: this.$refs.taskFormComponent.company?.id ?? "",
                };
                this.validateTasks({
                    ...timeTrackerCompany,
                    projectId: this.$refs.taskFormComponent.project.id ?? "",
                });

                endTime = this.getEndTime(startTime, timeTrackerCompany.hours);
            } else if (this.moduleType === "newEntry") {
                timeTrackerCompany = {
                    moduleId: "",
                    description: this.$refs.newEntryFormComponent.description,
                    internalNote: this.$refs.newEntryFormComponent.internalNote,
                    hours: this.$refs.newEntryFormComponent.accountedTime,
                    isGoodwill:
                        this.$refs.newEntryFormComponent.isGoodwill ?? false,
                    companyId:
                        this.$refs.newEntryFormComponent.company?.id ?? "",
                    projectId:
                        this.$refs.newEntryFormComponent.project?.id ?? "",
                };
                endTime = this.getEndTime(startTime, timeTrackerCompany.hours);
                this.validateNewEntry(timeTrackerCompany);
            } else if (this.moduleType === "ams") {
                timeTrackerCompany = {
                    moduleId: this.$refs.amsFormComponent.ams?.id ?? "",
                    description: this.$refs.amsFormComponent.description,
                    internalNote: this.$refs.amsFormComponent.internalNote,
                    hours: this.$refs.amsFormComponent.accountedTime,
                    isGoodwill: this.$refs.amsFormComponent.isGoodwill ?? false,
                    companyId: this.$refs.amsFormComponent.company?.id ?? "",
                    totalRemainingHours:
                        this.$refs.amsFormComponent.totalRemainingHours ?? "",
                    isAmsBillable: false,
                };
                endTime = this.getEndTime(startTime, timeTrackerCompany.hours);
                this.validateAMS(timeTrackerCompany);
                if (
                    +timeTrackerCompany.totalRemainingHours == 0 &&
                    +timeTrackerCompany.hours > 0
                ) {
                    timeTrackerCompany.isAmsBillable = true;
                } else if (
                    +timeTrackerCompany.totalRemainingHours -
                        +timeTrackerCompany.hours >=
                    0
                ) {
                    timeTrackerCompany.isAmsBillable = false;
                } else if (
                    +timeTrackerCompany.totalRemainingHours >= 0 &&
                    +timeTrackerCompany.totalRemainingHours -
                        +timeTrackerCompany.hours <
                        0
                ) {
                    const secondEntry = { ...timeTrackerCompany };
                    secondEntry.hours = Math.abs(
                        +timeTrackerCompany.totalRemainingHours -
                            +timeTrackerCompany.hours
                    );
                    secondEntry.startTime = endTime;
                    secondEntry.endTime = this.getEndTime(
                        secondEntry.startTime,
                        secondEntry.hours
                    );
                    secondEntry.isAmsBillable = true;
                    timeTrackerCompany.isAmsBillable = false;
                    timeTrackerCompany.hours =
                        +timeTrackerCompany.hours +
                        (+timeTrackerCompany.totalRemainingHours -
                            +timeTrackerCompany.hours);
                    endTime = this.getEndTime(
                        startTime,
                        timeTrackerCompany.hours
                    );
                    //Check if validation return errors
                    if (this.modalHasErrors !== false) {
                        return;
                    }
                    //Inititalize payload
                    let payload = {
                        date: this.date,
                        userId: this.userId?.userId
                            ? this.userId?.userId
                            : this.user.id,
                    };
                    /*let payloadForExtraRecord = {
                      date: this.date,
                      userId: this.userId?.id ? this.userId?.id : this.user.id,
                  }; */
                    //Parse time tracker company to save payload

                    payload.timeTrackerCompany = {
                        type: this.moduleType,
                        ...secondEntry,
                    };

                    //Parse time tracker government to save payload
                    payload.timeTrackerGovernment = {
                        type: this.moduleType,
                        description: secondEntry.description,
                        internalNote: secondEntry.internalNote,
                        startTime: secondEntry.startTime,
                        endTime: secondEntry.endTime,
                        hours: secondEntry.hours,
                    };

                    //If time crosses 24 hours, set end time to 0 and hours to 0
                    if (
                        !this.validateStartEndTime(
                            secondEntry.startTime,
                            secondEntry.endTime,
                            secondEntry.hours
                        )
                    ) {
                        const startHour =
                            parseInt(secondEntry.startTime?.split(":")?.[0]) ||
                            0; // extract the hour value from the start time string, default to 0 if not present
                        const startMinute =
                            parseInt(secondEntry.startTime?.split(":")?.[1]) ||
                            0; // extract the minute value from the start time string, default to 0 if not present
                        const diff = 24 - (startHour + startMinute / 60); // calculate the number of hours left in the day
                        let secondEndTime = this.getEndTime(
                            secondEntry.startTime,
                            diff
                        ); // get the end time
                        payload.timeTrackerGovernment.endTime = secondEndTime;
                    }
                    //Save "task" & "new entry" data
                    this.createTimeTrackerData(payload);
                }
            }

            //Check if validation return errors
            if (this.modalHasErrors !== false) {
                return;
            }
            //Inititalize payload
            let payload = {
                date: this.date,
                userId: this.userId?.userId
                    ? this.userId?.userId
                    : this.user.id,
            };
            /*let payloadForExtraRecord = {
                      date: this.date,
                      userId: this.userId?.id ? this.userId?.id : this.user.id,
                  }; */
            //Parse time tracker company to save payload

            payload.timeTrackerCompany = {
                type: this.moduleType,
                ...timeTrackerCompany,
            };

            //Parse time tracker government to save payload
            payload.timeTrackerGovernment = {
                type: this.moduleType,
                description: timeTrackerCompany.description,
                internalNote: timeTrackerCompany.internalNote,
                startTime: startTime,
                endTime: endTime,
                hours: timeTrackerCompany.hours,
            };

            //If time crosses 24 hours, set end time to 0 and hours to 0
            if (
                !this.validateStartEndTime(
                    startTime,
                    endTime,
                    timeTrackerCompany.hours
                )
            ) {
                const startHour = parseInt(startTime?.split(":")?.[0]) || 0; // extract the hour value from the start time string, default to 0 if not present
                const startMinute = parseInt(startTime?.split(":")?.[1]) || 0; // extract the minute value from the start time string, default to 0 if not present
                const diff = 24 - (startHour + startMinute / 60); // calculate the number of hours left in the day
                endTime = this.getEndTime(startTime, diff); // get the end time
                payload.timeTrackerGovernment.endTime = endTime;
            }

            //Save "task" & "new entry" data
            if (this.actionType === "add") {
                this.createTimeTrackerData(payload);
                /*   if (
                            payloadForExtraRecord.hasOwnProperty(
                                "timeTrackerGovernment"
                            )
                        ) {
                            this.createTimeTrackerData(payloadForExtraRecord);
                        } */
            } else if (this.actionType === "edit") {
                this.updateTimeTrackerCompany(timeTrackerCompany);
            }

            //Reset modal errors
            Object.keys(this.modalErrors).forEach((key) => {
                this.modalErrors[key] = "";
            });

            //Reset data
            this.cancelCompany();
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
        /**
         * performs error handling and creates the travel expense company entries
         * @param {startTime} main start time
         */
        async handleTravelExpenseData(startTime) {
            let refTravelExpenseComp = this.$refs.travelExpenseFormComponent;

            //Validate data
            this.validateTravelExpense({
                moduleId: refTravelExpenseComp.moduleId ?? "",
                companyId: refTravelExpenseComp.company?.id ?? "",
                selectedTravelExpenses:
                    refTravelExpenseComp.selectedTravelExpenses,
            });

            if (this.modalHasErrors !== false) {
                return false;
            }

            if (this.actionType === "edit") {
                //Module id is id of comment not the ticket
                let timeTrackerCompany = {
                    moduleId: refTravelExpenseComp.moduleId,
                    description: refTravelExpenseComp.description,
                    internalNote: refTravelExpenseComp.internalNote,
                    hours: refTravelExpenseComp.accountedTime,
                    isGoodwill: refTravelExpenseComp.isGoodwill ?? false,
                    companyId: refTravelExpenseComp.company?.id ?? "",
                };
                this.updateTimeTrackerCompany(timeTrackerCompany);
                this.cancelCompany();
                return;
            }

            //Loop selected travel expenses to create an individual record
            for (
                let i = 0;
                i < (refTravelExpenseComp?.selectedTravelExpenses ?? []).length;
                i++
            ) {
                let travelExpense =
                    refTravelExpenseComp?.selectedTravelExpenses?.[i] ?? {};
                let time = +(travelExpense?.fromDiscrepancy ?? 0);
                let description = `Arrival`;
                let endTime = this.getEndTime(startTime, time);
                let governmentHours = this.diff(startTime, endTime);
                let companyHours = time;
                let isError = !this.validateStartEndTime(
                    startTime,
                    endTime,
                    time
                );

                if (endTime === "Invalid Date") {
                    endTime = "";
                    governmentHours = 0;
                } else if (isError) {
                    endTime = startTime;
                    governmentHours = 0;
                }

                //Check if validation return errors
                if (this.modalHasErrors !== false) {
                    return false;
                }

                let timeTrackerCompany = {
                    type: this.moduleType,
                    moduleId: travelExpense.id,
                    description: description,
                    internalNote: refTravelExpenseComp.internalNote,
                    hours: companyHours,
                    companyId: refTravelExpenseComp.company?.id ?? "",
                    isGoodwill: refTravelExpenseComp.isGoodwill ?? false,
                };
                let timeTrackerGovernment = {
                    type: this.moduleType,
                    description: description,
                    internalNote: refTravelExpenseComp.internalNote,
                    startTime: startTime,
                    endTime: endTime,
                    hours: governmentHours,
                    commentId: refTravelExpenseComp?.id,
                };

                //Inititalize payload
                let payload = {
                    date: this.date,
                    userId: this.userId?.userId
                        ? this.userId?.userId
                        : this.user.id,
                    timeTrackerCompany: timeTrackerCompany,
                    timeTrackerGovernment: timeTrackerGovernment,
                };

                //Store data
                await this.createTimeTrackerData(payload);

                //Push data in existing dataset
                this.timeTrackerCompany.push(timeTrackerCompany);
                this.timeTrackerGovernment.push(timeTrackerGovernment);

                // if the fromDate and toDate are same then we must create another entry
                if (
                    this.matchDates(
                        new Date(travelExpense.fromDate),
                        new Date(travelExpense.toDate)
                    )
                ) {
                    //Set start time as end time of last government entry if data exists
                    let newStartTime = this.timeTrackerGovernment.length
                        ? this.timeTrackerGovernment[
                              this.timeTrackerGovernment.length - 1
                          ]?.endTime
                        : this.startTime;
                    let time = +(travelExpense?.toDiscrepancy ?? 0);
                    let description = `Departure`;
                    let endTime = this.getEndTime(newStartTime, time);
                    let governmentHours = this.diff(newStartTime, endTime);
                    let companyHours = time;
                    let isError = !this.validateStartEndTime(
                        newStartTime,
                        endTime,
                        time
                    );

                    if (endTime === "Invalid Date") {
                        endTime = "";
                        governmentHours = 0;
                    } else if (isError) {
                        endTime = startTime;
                        governmentHours = 0;
                    }

                    //Check if validation return errors
                    if (this.modalHasErrors !== false) {
                        return false;
                    }

                    let timeTrackerCompany = {
                        type: this.moduleType,
                        moduleId: travelExpense.id,
                        description: description,
                        internalNote: refTravelExpenseComp.internalNote,
                        hours: companyHours,
                        companyId: refTravelExpenseComp.company?.id ?? "",
                        isGoodwill: refTravelExpenseComp.isGoodwill ?? false,
                    };
                    let timeTrackerGovernment = {
                        type: this.moduleType,
                        description: description,
                        internalNote: refTravelExpenseComp.internalNote,
                        startTime: newStartTime,
                        endTime: endTime,
                        hours: governmentHours,
                        commentId: refTravelExpenseComp?.id,
                    };

                    //Inititalize payload
                    let payload = {
                        date: this.date,
                        userId: this.userId?.userId
                            ? this.userId?.userId
                            : this.user.id,
                        timeTrackerCompany: timeTrackerCompany,
                        timeTrackerGovernment: timeTrackerGovernment,
                    };

                    //Store data
                    await this.createTimeTrackerData(payload);

                    //Push data in existing dataset
                    this.timeTrackerCompany.push(timeTrackerCompany);
                    this.timeTrackerGovernment.push(timeTrackerGovernment);
                }
            }

            //Reset modal errors
            Object.keys(this.modalErrors).forEach((key) => {
                this.modalErrors[key] = "";
            });
            //Reset data
            this.cancelCompany();
        },
        handleTicketData(startTime) {
            let refTicketComp = this.$refs.ticketFormComponent;

            //Validate data
            this.validateTickets({
                moduleId: refTicketComp.moduleId ?? "",
                ticket: refTicketComp.ticket?.id ?? "",
                companyId: refTicketComp.company?.id ?? "",
                selectedComments: refTicketComp.selectedComments,
            });

            if (this.modalHasErrors !== false) {
                return false;
            }

            if (this.actionType === "edit") {
                //Module id is id of comment not the ticket
                let timeTrackerCompany = {
                    moduleId: refTicketComp.moduleId,
                    description: refTicketComp.description,
                    internalNote: refTicketComp.internalNote,
                    hours: refTicketComp.accountedTime,
                    isGoodwill: refTicketComp.isGoodwill ?? false,
                    companyId: refTicketComp.company?.id ?? "",
                    amsId: refTicketComp?.ams?.id ?? "",
                    totalRemainingHours:
                        refTicketComp?.totalRemainingHours ?? 0,
                    isAmsBillable: false,
                };
                this.updateTimeTrackerCompany(timeTrackerCompany);
                this.cancelCompany();
                return;
            }

            //Loop selected comments to create an individual record
            refTicketComp.selectedComments.forEach((comment) => {
                let endTime = this.getEndTime(startTime, comment.time);
                let governmentHours = this.diff(startTime, endTime);
                let companyHours = comment.time;
                let isError = !this.validateStartEndTime(
                    startTime,
                    endTime,
                    comment.time
                );

                if (endTime === "Invalid Date") {
                    endTime = "";
                    governmentHours = 0;
                } else if (isError) {
                    endTime = startTime;
                    governmentHours = 0;
                }

                //Check if validation return errors
                if (this.modalHasErrors !== false) {
                    return false;
                }

                let description =
                    "Ticket " + refTicketComp.ticket.ticketNumber + "";
                description +=
                    "Description: " + this.shortenedString(comment.text);
                let timeTrackerCompany = {
                    type: this.moduleType,
                    moduleId: comment.id,
                    description: description,
                    internalNote: refTicketComp.internalNote,
                    hours: companyHours,
                    companyId: refTicketComp.company?.id ?? "",
                    isGoodwill: refTicketComp.isGoodwill ?? false,
                    amsId: refTicketComp?.ams?.id ?? "",
                    totalRemainingHours:
                        refTicketComp?.totalRemainingHours ?? 0,
                    isAmsBillable: false,
                };
                let timeTrackerGovernment = {
                    type: this.moduleType,
                    description: description,
                    internalNote: refTicketComp.internalNote,
                    startTime: startTime,
                    endTime: endTime,
                    hours: governmentHours,
                    commentId: comment?.id,
                };

                if (
                    +timeTrackerCompany.totalRemainingHours == 0 &&
                    +timeTrackerCompany.hours > 0
                ) {
                    timeTrackerCompany.isAmsBillable = true;
                } else if (
                    +timeTrackerCompany.totalRemainingHours -
                        +timeTrackerCompany.hours >=
                    0
                ) {
                    timeTrackerCompany.isAmsBillable = false;
                } else if (
                    +timeTrackerCompany.totalRemainingHours >= 0 &&
                    +timeTrackerCompany.totalRemainingHours -
                        +timeTrackerCompany.hours <
                        0
                ) {
                    const secondEntry = { ...timeTrackerCompany };
                    secondEntry.hours = Math.abs(
                        +timeTrackerCompany.totalRemainingHours -
                            +timeTrackerCompany.hours
                    );
                    secondEntry.startTime = endTime;
                    secondEntry.endTime = this.getEndTime(
                        secondEntry.startTime,
                        secondEntry.hours
                    );
                    secondEntry.isAmsBillable = true;
                    timeTrackerCompany.isAmsBillable = false;
                    timeTrackerCompany.hours =
                        +timeTrackerCompany.hours +
                        (+timeTrackerCompany.totalRemainingHours -
                            +timeTrackerCompany.hours);
                    endTime = this.getEndTime(
                        startTime,
                        timeTrackerCompany.hours
                    );
                    timeTrackerGovernment.endTime = endTime;
                    timeTrackerGovernment.hours = timeTrackerCompany.hours;
                    //Check if validation return errors
                    if (this.modalHasErrors !== false) {
                        return;
                    }
                    //Inititalize payload
                    let payload = {
                        date: this.date,
                        userId: this.userId?.userId
                            ? this.userId?.userId
                            : this.user.id,
                    };
                    /*let payloadForExtraRecord = {
                      date: this.date,
                      userId: this.userId?.id ? this.userId?.id : this.user.id,
                  }; */
                    //Parse time tracker company to save payload

                    payload.timeTrackerCompany = {
                        type: this.moduleType,
                        ...secondEntry,
                    };

                    //Parse time tracker government to save payload
                    payload.timeTrackerGovernment = {
                        type: this.moduleType,
                        description: secondEntry.description,
                        internalNote: secondEntry.internalNote,
                        startTime: secondEntry.startTime,
                        endTime: secondEntry.endTime,
                        hours: secondEntry.hours,
                    };

                    //If time crosses 24 hours, set end time to 0 and hours to 0
                    if (
                        !this.validateStartEndTime(
                            secondEntry.startTime,
                            secondEntry.endTime,
                            secondEntry.hours
                        )
                    ) {
                        const startHour =
                            parseInt(secondEntry.startTime?.split(":")?.[0]) ||
                            0; // extract the hour value from the start time string, default to 0 if not present
                        const startMinute =
                            parseInt(secondEntry.startTime?.split(":")?.[1]) ||
                            0; // extract the minute value from the start time string, default to 0 if not present
                        const diff = 24 - (startHour + startMinute / 60); // calculate the number of hours left in the day
                        let secondEndTime = this.getEndTime(
                            secondEntry.startTime,
                            diff
                        ); // get the end time
                        payload.timeTrackerGovernment.endTime = secondEndTime;
                    }
                    //Save "task" & "new entry" data
                    this.createTimeTrackerData(payload);
                }

                //Inititalize payload
                let payload = {
                    date: this.date,
                    userId: this.userId?.userId
                        ? this.userId?.userId
                        : this.user.id,
                    timeTrackerCompany: timeTrackerCompany,
                    timeTrackerGovernment: timeTrackerGovernment,
                };

                //Store data
                this.createTimeTrackerData(payload);

                //Push data in existing dataset
                this.timeTrackerCompany.push(timeTrackerCompany);
                this.timeTrackerGovernment.push(timeTrackerGovernment);
            });

            //Reset modal errors
            Object.keys(this.modalErrors).forEach((key) => {
                this.modalErrors[key] = "";
            });
            //Reset data
            this.cancelCompany();
        },
        async createTimeTrackerData(payload) {
            // if a vacation entry has been logged on this day then show a warning to the user
            if (
                this.timeTrackerGovernment?.some(
                    (item) => item.type === "vacation"
                )
            ) {
                this.$store.commit("flashMessage", {
                    show: true,
                    message: this.$t("You have a vacation on this day."),
                    type: "warning",
                    link: "",
                });
                await new Promise((resolve) =>
                    setTimeout(() => {
                        resolve();
                    }, 2000)
                );
            }
            return new Promise((resolve, reject) => {
                this.form.saveProcessing = true;
                this.$store
                    .dispatch("timeTrackers/save", payload)
                    .then((res) => {
                        this.getTrackerData();
                        this.form.saveProcessing = false;
                        resolve();
                    })
                    .catch((err) => {
                        this.form.saveProcessing = false;
                        reject(err);
                    });
            });
        },
        async updateTimeTrackerCompany(timeTrackerCompany) {
            //Inititalize payload
            let payload = {
                id: this.timeTrackerCompanyTemp.id,
                type: this.moduleType,
                ...timeTrackerCompany,
            };
            this.form.saveProcessing = true;
            await this.$store
                .dispatch("timeTrackers/updateCompany", payload)
                .then((res) => {
                    this.getTrackerData();
                    this.form.saveProcessing = false;
                })
                .catch((err) => {
                    this.form.saveProcessing = false;
                });
        },
        editCompanyModal(index) {
            this.timeTrackerCompanyTemp = this.timeTrackerCompany[index];
            this.actionType = "edit";
            this.moduleType = this.timeTrackerCompany[index].moduleType;
            this.toggleEditCompanyModal = true;
        },
        async removeRecord(index, type) {
            try {
                if (type === "government") {
                    await this.$store.dispatch(
                        "timeTrackers/removeTimeTrackerGovernment",
                        this.timeTrackerGovernment[index]?.id
                    );
                    const comment = this.timeTrackerGovernment[index];
                    if (comment?.commentId ?? null) {
                        await this.$store.dispatch(
                            "timeTrackers/removeComment",
                            {
                                commentId: comment?.commentId,
                            }
                        );
                    }
                } else if (type === "company") {
                    await this.$store.dispatch(
                        "timeTrackers/removeTimeTrackerCompany",
                        this.timeTrackerCompany[index]?.id
                    );
                }
            } catch (e) {
                console.log(e);
            } finally {
                this.getTrackerData();
            }
        },
        cancelGovernment() {
            // refetch the tracker data
            this.fetchTrackerData();
            this.timeTrackerGovernmentTemp = [];
            this.toggleEditGovernmentModal = false;
            this.actionType = "add";
            this.selectedGovermentTimeIndex = "";
            //Reset modal errors
            Object.keys(this.modalErrors).forEach((key) => {
                this.modalErrors[key] = "";
            });
        },
        validateStartEndTime(startTime, endTime, accountedTime) {
            // Get the number of hours left in the day from the start time
            const startHour = parseInt(startTime?.split(":")?.[0]) || 0; // extract the hour value from the start time string, default to 0 if not present
            const startMinute = parseInt(startTime?.split(":")?.[1]) || 0; // extract the minute value from the start time string, default to 0 if not present
            const diff = 24 - (startHour + startMinute / 60); // calculate the number of hours left in the day

            // Check if the accounted time exceeds the number of hours left in the day
            if (accountedTime > diff) {
                return false;
            }

            // Check if start and end times are present
            if (!startTime || !endTime) {
                return false;
            }

            // Parse start and end times into Date objects
            const start = new Date(`2000-01-01T${startTime}`); // set the date to a fixed value to simplify the code
            const end = new Date(`2000-01-01T${endTime}`);

            // Check if the end time crosses over to the next day
            if (end.getHours() === 0 && end.getMinutes() > 0) {
                return false;
            }

            // Check if the end time is after the start time
            return end > start;
        },
        async savetimeTrackerGovernment() {
            if (this.actionType === "add") {
                if (!(await this.validateAccountingDate())) {
                    this.cancelGovernment();
                    return;
                }
                if (!this.timeTrackerGovernmentTemp.type) {
                    this.modalErrors.governmentType = "Please enter a type";
                    return;
                }
                if (!this.timeTrackerGovernmentTemp.hours) {
                    this.modalErrors.governmentHours = "Please enter hours";
                    return;
                }
                if (
                    !this.validateStartEndTime(
                        this.timeTrackerGovernmentTemp?.startTime,
                        this.timeTrackerGovernmentTemp?.endTime,
                        this.timeTrackerGovernmentTemp?.hours
                    )
                ) {
                    this.errors.governmentEndTime =
                        "End time cannot be before start time or beyond the current day.";
                    return;
                } else {
                    this.errors.governmentEndTime = "";
                    const payload = {
                        date: this.date,
                        companyId:
                            this.filterCompany?.id === "all"
                                ? ""
                                : this.filterCompany?.id,
                        timeTrackerGovernment: {
                            ...this.timeTrackerGovernmentTemp,
                            customerId:
                                this.timeTrackerGovernmentTemp?.customerId?.id,
                        },
                        timeTrackerCompany: null,
                        userId: this.userId?.userId
                            ? this.userId?.userId
                            : this.user.id,
                    };
                    this.createTimeTrackerData(payload);
                }
            } else {
                if (
                    !this.validateStartEndTime(
                        this.timeTrackerGovernmentTemp?.startTime,
                        this.timeTrackerGovernmentTemp?.endTime,
                        this.timeTrackerGovernmentTemp?.hours
                    )
                ) {
                    this.errors.governmentEndTime =
                        "End time cannot be before start time or beyond the current day.";
                    return;
                } else {
                    try {
                        await this.$store.dispatch(
                            "timeTrackers/updateGovernment",
                            {
                                id: this.timeTrackerGovernmentTemp.id,
                                data: {
                                    ...this.timeTrackerGovernmentTemp,
                                    date: this.date,
                                    userId: this.userId?.userId
                                        ? this.userId?.userId
                                        : this.user.id,
                                },
                            }
                        );
                    } catch (e) {}
                    this.getTrackerData();
                }
            }
            this.cancelGovernment();
        },
        async editGovernmentModal(index) {
            this.timeTrackerGovernmentTemp = this.timeTrackerGovernment[index];
            if (this.timeTrackerGovernmentTemp.type === "sales-presales") {
                this.customerType = this.timeTrackerGovernmentTemp.customerType;
                let response = await this.$store.dispatch("companies/list", {
                    perPage: 25,
                    page: 1,
                    customerType: this.customerType,
                });
                this.customerOptions = response?.data?.data;
                const selectedCustomer = this.customerOptions.find(
                    (option) =>
                        option.id === this.timeTrackerGovernmentTemp.customerId
                );
                if (selectedCustomer) {
                    this.timeTrackerGovernmentTemp.customerId =
                        selectedCustomer;
                }
            }
            this.selectedGovermentTimeIndex = index;
            this.actionType = "edit";
            this.toggleEditGovernmentModal = true;
        },
        async validateAccountingDate() {
            //Check if user profile exists; check if accounting date is set and defined correctly
            const data = {
                date: this.date,
                userId: this.userId?.userId
                    ? this.userId?.userId
                    : this.user.id,
            };
            try {
                await this.$store.dispatch(
                    "timeTrackers/validateUserProfile",
                    data
                );
            } catch (err) {
                return false;
            }
            return true;
        },

        shortenedString(text) {
            // Remove HTML tags from the original string
            const plainText = this.stripHtmlTags(text);
            if (plainText.length <= 64) {
                return plainText;
            } else {
                // If the string is longer than 60 characters, append "..."
                return plainText.slice(0, 60) + "...";
            }
        },
        stripHtmlTags(html) {
            // Create a temporary div element to parse and sanitize HTML
            const doc = new DOMParser().parseFromString(html, "text/html");
            return doc.body.textContent || "";
        },

        async exportTimeTrackerData() {
            const response = await this.$store.dispatch(
                "timeTrackers/exportTimeTrackerData",
                {
                    date: this.date,
                    company:
                        this.filterCompany?.id === "all"
                            ? ""
                            : this.filterCompany?.id,
                    userId: this.userId?.userId,
                    auth_user_roles: this.user.roles,
                    auth_user_id: this.user.id,
                }
            );
        },
    },
};
</script>

<style lang="scss">
.overviewCalender_red {
    color: red !important;
    font-size: 15px !important;
}

.overviewCalender_green {
    color: green !important;
    font-size: 15px !important;
}

.month-picker-input {
    width: 100% !important;
}

.month-picker__container {
    z-index: 99;

    .month-picker__year {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }
}
.vc-pane {
    background: #2957a4;
}
.vc-day {
    text-align: center;
}
.vc-header {
    height: 60px;
    margin: 0;
    button {
        color: #fff !important;
    }
}
.time-tracker-calender {
    .vc-weeks {
        padding: 0;
        background: #fff;
        .vc-weekdays {
            border: 1px solid #ccc;
            .vc-weekday {
                border: 1px solid #ccc;
                background: #dedede;
                color: #000;
                height: 60px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 20px;
            }
        }
        .vc-week {
            .vc-day {
                height: 80px;
                border: 1px solid #dedede;
                border-bottom: 1px solid #ccc;
                border-left: 1px solid #ccc;
                border-right: 1px solid #ccc;
                padding: 10px;
                .day-label {
                    text-align: right;
                    font-size: 24px;
                }
            }
        }
    }
}
.time-tracker-calender.vc-monthly .is-not-in-month * {
    opacity: 1;
    color: #ccc;
}
</style>

<style scoped>
.month-picker-input-container {
    width: 100% !important;
    min-width: auto !important;
}

:deep(.gutter) {
    background: #f8f8f8 no-repeat;
    background-position: 50%;
}

:deep(.gutter.gutter-horizontal) {
    background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAeCAYAAADkftS9AAAAIklEQVQoU2M4c+bMfxAGAgYYmwGrIIiDjrELjpo5aiZeMwF+yNnOs5KSvgAAAABJRU5ErkJggg==");
    cursor: col-resize;
    max-height: auto;
}

.base-timer__path-elapsed {
    stroke-width: 10px;
    fill: none;
    transform-origin: center;
    transform: rotate(-90deg);
    transition: stroke-dashoffset 1s linear;
}

.base-timer__path-remaining {
    stroke-width: 5px;
    fill: none;
    transform-origin: center;
    transform: rotate(-90deg);
    stroke: transparent;
    stroke: grey;
}

.base-timer {
    position: relative;
    width: 100px;
    height: 100px;
}

.base-timer__svg {
    transform: scaleX(-1);
}

.base-timer__circle {
    fill: none;
    stroke: none;
}

.base-timer__path-elapsed {
    stroke-width: 5px;
    stroke: grey;
}

.base-timer__label {
    position: absolute;
    width: 100px;
    height: 100px;
    top: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}
</style>

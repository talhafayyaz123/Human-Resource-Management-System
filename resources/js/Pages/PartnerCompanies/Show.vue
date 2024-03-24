<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <div class="tab-header">
            <ul>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        @click="activeTab = 'company'"
                        :class="
                            activeTab === 'company'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("Customer") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        @click="activeTab = 'licenses'"
                        :class="
                            activeTab === 'licenses'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("Licenses") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        @click="activeTab = 'systems'"
                        :class="
                            activeTab === 'systems'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("Systems") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        @click="activeTab = 'offers'"
                        :class="
                            activeTab === 'offers'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("Offers") }}
                    </a>
                </li>
                <li class="nav-item"></li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        @click="activeTab = 'orderConfirmation'"
                        :class="
                            activeTab === 'orderConfirmation'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("Order Confirmation") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        @click="activeTab = 'invoices'"
                        :class="
                            activeTab === 'invoices'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("Invoices") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        @click="activeTab = 'reports'"
                        :class="
                            activeTab === 'reports'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("Reports") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        @click="activeTab = 'projects'"
                        :class="
                            activeTab === 'projects'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("Projects") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        @click="activeTab = 'tickets'"
                        :class="
                            activeTab === 'tickets'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("Tickets") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        @click="activeTab = 'contracts'"
                        :class="
                            activeTab === 'contracts'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("Contracts") }}
                    </a>
                </li>
            </ul>
        </div>

        <div id="myTabContent">
            <div
                v-if="activeTab === 'company'"
                class="p-4"
                id="profile"
                role="tabpanel"
                aria-labelledby="profile-tab"
            >
                <form>
                    <div class="w-full flex justify-end mb-2">
                        <router-link
                            class="secondary-btn"
                            :to="
                                '/partner/companies/' +
                                company.id +
                                '/edit?page=' +
                                returnPage
                            "
                        >
                            <span>{{ $t("Edit") }}</span>
                        </router-link>
                    </div>
                    <div class="card my-5">
                        <div class="card-body">
                            <div class="flex flex-wrap">
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Company Number") }}:</label
                                    >
                                    <p>{{ form.companyNumber }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Name") }}:</label
                                    >
                                    <p>{{ form.companyName }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Type") }}:</label
                                    >
                                    <p>{{ form.type }}</p>
                                </div>

                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("URL") }}:</label
                                    >
                                    <a
                                        class="hover:text-blue-500"
                                        :href="
                                            form.url
                                                ? customerLink(form.url)
                                                : '#'
                                        "
                                        :target="
                                            customerLink(form.url)
                                                ? '_blank'
                                                : null
                                        "
                                    >
                                        {{ form.url }}</a
                                    >
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Address First") }}:</label
                                    >
                                    <p>{{ form.addressFirst }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Address Second") }}:</label
                                    >
                                    <p>{{ form.addressSecond }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("City") }}:</label
                                    >
                                    <p>{{ form.city }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("ZIP") }}:</label
                                    >
                                    <p>{{ form.zip }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Country") }}:</label
                                    >
                                    <p>{{ form.countryName }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("State") }}:</label
                                    >
                                    <p>{{ form.state }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("VAT ID") }}:</label
                                    >
                                    <p>{{ form.vatId }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Phone") }}:</label
                                    >
                                    <p>{{ form.phone }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Fax") }}:</label
                                    >
                                    <p>{{ form.fax }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{
                                            $t("Customer Portal Onboarding")
                                        }}:</label
                                    >
                                    <p>
                                        {{
                                            $t(form.isCustomerPortalOnboarding)
                                        }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card my-5">
                        <div class="card-header">
                            <h3 class="card-title">
                                {{ $t("Payment Details") }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="pb-8 pr-6 w-full lg:w-1/4">
                                <label class="form-label font-bold"
                                    >{{ $t("Terms Of Payment") }}:</label
                                >
                                <p>{{ form.termsOfPayment?.name }}</p>
                            </div>
                            <div class="pb-8 pr-6 w-full lg:w-1/4">
                                <label class="form-label font-bold"
                                    >{{ $t("Invoice Email Address") }}:</label
                                >
                                <p>{{ form.invoiceEmail }}</p>
                            </div>
                            <div class="w-full">
                                <div class="flex justify-between">
                                    <h6
                                        class="font-normal leading-normal mt-0 mb-2 text-cyan-800"
                                    >
                                        {{ $t("Bank Details") }}:
                                    </h6>
                                </div>
                                <div
                                    v-if="form.bankDetails?.length > 0"
                                    class="w-full margin-bottom-3rem card shadow rounded p-5"
                                >
                                    <div
                                        v-for="(
                                            bankDetail, index
                                        ) in form.bankDetails"
                                        :key="index"
                                        class="flex flex-wrap -mb-8 -mr-6"
                                    >
                                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                                            <label class="form-label font-bold"
                                                >{{ $t("Bank Name") }}:</label
                                            >
                                            <p>{{ bankDetail.bankName }}</p>
                                        </div>
                                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                                            <label class="form-label font-bold"
                                                >{{ $t("Swift/Bic") }}:</label
                                            >
                                            <p>{{ bankDetail.swift }}</p>
                                        </div>
                                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                                            <label class="form-label font-bold"
                                                >{{ $t("IBAN") }}:</label
                                            >
                                            <p>{{ bankDetail.iban }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card my-5">
                        <div class="card-header">
                            <h3 class="card-title">
                                {{ $t("Default Service Details") }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="flex flex-wrap">
                                <div class="pb-8 pr-6 w-full lg:w-1/2">
                                    <label class="form-label font-bold"
                                        >{{
                                            $t("Default Service Product")
                                        }}:</label
                                    >
                                    <p>
                                        {{ form.defaultServiceProduct?.name }}
                                    </p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/2">
                                    <label class="form-label font-bold"
                                        >{{ $t("Hourly Rate") }}:</label
                                    >
                                    <p>{{ form.hourlyRate }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/2">
                                    <label class="form-label font-bold"
                                        >{{ $t("Discount") }}:</label
                                    >
                                    <p>{{ form.discount }}%</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/2">
                                    <label class="form-label font-bold"
                                        >{{
                                            $t("Default Service Hourly Rate")
                                        }}:</label
                                    >
                                    <p>
                                        {{ form.defaultServiceHourlyRate }}
                                    </p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/2">
                                    <label class="form-label font-bold"
                                        >{{
                                            $t("Default Service Daily Rate")
                                        }}:</label
                                    >
                                    <p>
                                        {{ form.defaultServiceDailyRate }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Location listing -->
                <div class="table-responsive my-5">
                    <table class="w-full doc-table">
                        <tr class="text-left font-bold">
                            <th class="pb-4 pt-6 px-6">
                                {{ $t("Address Line 1") }}
                            </th>
                            <th class="pb-4 pt-6 px-6">
                                {{ $t("Address Line 2") }}
                            </th>
                            <th class="pb-4 pt-6 px-6">{{ $t("City") }}</th>
                            <th class="pb-4 pt-6 px-6">
                                {{ $t("Country") }}
                            </th>
                            <th class="pb-4 pt-6 px-6">
                                {{ $t("State") }}
                            </th>
                            <th class="pb-4 pt-6 px-6 text-center">
                                {{ $t("Actions") }}
                            </th>
                        </tr>
                        <tr
                            v-for="(location, index) in locations"
                            :key="'location-' + index"
                            class="hover:bg-gray-100 focus-within:bg-gray-100"
                        >
                            <td class="border-t">
                                <p class="mt-3 ml-3">
                                    {{ location.addressFirst }}
                                </p>
                            </td>
                            <td class="border-t">
                                <p class="mt-3 ml-3">
                                    {{ location.addressSecond }}
                                </p>
                            </td>
                            <td class="border-t">
                                <p class="mt-3 ml-3">
                                    {{ location.city }}
                                </p>
                            </td>
                            <td class="border-t">
                                <p class="mt-3 ml-3">
                                    {{ location.countryName }}
                                </p>
                            </td>
                            <td class="border-t">
                                <p class="mt-3 ml-3">
                                    {{ location.state }}
                                </p>
                            </td>
                            <td
                                v-if="location.isHeadQuarters == 0"
                                class="w-px border-t text-center"
                            >
                                <button
                                    class="mr-2"
                                    @click.prevent="
                                        openEditLocationModal(index)
                                    "
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-pen-to-square"
                                    />
                                </button>
                                <button
                                    @click.prevent="removeLocation(location.id)"
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-trash-can"
                                    />
                                </button>
                            </td>
                        </tr>
                        <tr v-if="locations.length === 0">
                            <td class="px-6 py-4 border-t" colspan="4">
                                {{ $t("No Locations found.") }}
                            </td>
                        </tr>
                    </table>
                </div>
                <pagination
                    :limit="10"
                    align="center"
                    :data="locations"
                ></pagination>

                <!-- Employee listing -->
                <div class="table-responsive my-5">
                    <table class="w-full doc-table">
                        <tr class="text-left font-bold">
                            <th class="pb-4 pt-6 px-6">
                                {{ $t("Name") }}
                            </th>
                            <th class="pb-4 pt-6 px-6">
                                {{ $t("Position") }}
                            </th>
                            <th class="pb-4 pt-6 px-6">
                                {{ $t("Mail") }}
                            </th>
                            <th class="pb-4 pt-6 px-6">
                                {{ $t("Phone") }}
                            </th>
                            <th class="pb-4 pt-6 px-6">
                                {{ $t("Mobile") }}
                            </th>
                            <th class="pb-4 pt-6 px-6 text-center">
                                {{ $t("Actions") }}
                            </th>
                        </tr>
                        <tr
                            v-for="(employee, index) in employees"
                            :key="'employee-' + index"
                            class="hover:bg-gray-100 focus-within:bg-gray-100"
                        >
                            <td class="border-t">
                                <p class="mt-3 ml-3">
                                    {{ employee.first_name }}
                                    {{ employee.last_name }}
                                </p>
                            </td>
                            <td class="border-t">
                                <p class="mt-3 ml-3">
                                    {{ employee?.position }}
                                </p>
                            </td>
                            <td class="border-t">
                                <p class="mt-3 ml-3">
                                    {{ employee.email }}
                                </p>
                            </td>
                            <td class="border-t">
                                <p class="mt-3 ml-3">
                                    {{ employee?.phone }}
                                </p>
                            </td>
                            <td class="border-t">
                                <p class="mt-3 ml-3">
                                    {{ employee?.mobile }}
                                </p>
                            </td>
                            <td class="border-t text-center">
                                <button
                                    class="mr-2"
                                    @click.prevent="
                                        openEditEmployeeModal(index)
                                    "
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-pen-to-square"
                                    />
                                </button>
                                <button
                                    @click.prevent="removeEmployee(employee.id)"
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-trash-can"
                                    />
                                </button>
                            </td>
                        </tr>
                        <tr v-if="employees.length === 0">
                            <td
                                class="px-6 py-4 border-t text-center"
                                colspan="4"
                            >
                                {{ $t("No Employees found.") }}
                            </td>
                        </tr>
                    </table>
                </div>
                <div style="margin-top: 3rem" class="flex justify-center">
                    <custom-pagination
                        align="center"
                        :count="count"
                        :perPage="perPage"
                        :start="start"
                        :length="employees.length"
                        :currentPage="currentPage"
                        @paginationInfo="
                            fetchEmployees($event.start, $event.end)
                        "
                    ></custom-pagination>
                </div>
            </div>
            <div
                v-if="activeTab === 'licenses'"
                class="p-4"
                id="licenses"
                role="tabpanel"
                aria-labelledby="licenses-tab"
            >
                <Licenses :isShow="true" :companyId="$route.params.id" />
            </div>
            <div
                v-if="activeTab === 'systems'"
                class="p-4"
                id="profile"
                role="tabpanel"
                aria-labelledby="profile-tab"
            >
                <div class="card my-5">
                    <div class="card-header">
                        <h3 class="card-title">
                            {{ $t("On Premise Systems") }}
                        </h3>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="w-full doc-table">
                            <tr class="text-left font-bold">
                                <th
                                    @click="
                                        sort(
                                            'systemNumber',
                                            'premise',
                                            'onPremise'
                                        )
                                    "
                                    class="cursor-pointer"
                                >
                                    {{ $t("System Number")
                                    }}<font-awesome-icon
                                        v-if="
                                            onPremise.sortBy === 'systemNumber'
                                        "
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            onPremise.sortOrder === 'asc'
                                                ? 'up'
                                                : 'down'
                                        }`"
                                    />
                                </th>
                                <th
                                    @click="
                                        sort(
                                            'systemName',
                                            'premise',
                                            'onPremise'
                                        )
                                    "
                                    class="cursor-pointer"
                                >
                                    {{ $t("System Name")
                                    }}<font-awesome-icon
                                        v-if="onPremise.sortBy === 'systemName'"
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            onPremise.sortOrder === 'asc'
                                                ? 'up'
                                                : 'down'
                                        }`"
                                    />
                                </th>
                                <th
                                    @click="
                                        sort(
                                            'Company.companyName',
                                            'premise',
                                            'onPremise'
                                        )
                                    "
                                    class="cursor-pointer"
                                >
                                    {{ $t("Customer")
                                    }}<font-awesome-icon
                                        v-if="
                                            onPremise.sortBy ===
                                            'Company.companyName'
                                        "
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            onPremise.sortOrder === 'asc'
                                                ? 'up'
                                                : 'down'
                                        }`"
                                    />
                                </th>
                                <th
                                    @click="
                                        sort('serverIp', 'premise', 'onPremise')
                                    "
                                    class="cursor-pointer"
                                >
                                    {{ $t("Server IP")
                                    }}<font-awesome-icon
                                        v-if="onPremise.sortBy === 'serverIp'"
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            onPremise.sortOrder === 'asc'
                                                ? 'up'
                                                : 'down'
                                        }`"
                                    />
                                </th>
                                <th
                                    @click="
                                        sort(
                                            'instanceType',
                                            'premise',
                                            'onPremise'
                                        )
                                    "
                                    class="cursor-pointer"
                                >
                                    {{ $t("instance Type")
                                    }}<font-awesome-icon
                                        v-if="
                                            onPremise.sortBy === 'instanceType'
                                        "
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            onPremise.sortOrder === 'asc'
                                                ? 'up'
                                                : 'down'
                                        }`"
                                    />
                                </th>
                                <th
                                    @click="
                                        sort(
                                            'operatingSystem.name',
                                            'premise',
                                            'onPremise'
                                        )
                                    "
                                    class="cursor-pointer"
                                >
                                    {{ $t("Operating System")
                                    }}<font-awesome-icon
                                        v-if="
                                            onPremise.sortBy ===
                                            'operatingSystem.name'
                                        "
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            onPremise.sortOrder === 'asc'
                                                ? 'up'
                                                : 'down'
                                        }`"
                                    />
                                </th>
                                <th class="pb-4 pt-6 px-6">
                                    {{ $t("Action") }}
                                </th>
                            </tr>
                            <tr
                                v-for="system in premiseSystems"
                                :key="system.id"
                                class="hover:bg-gray-100 focus-within:bg-gray-100"
                            >
                                <td class="border-t">
                                    <router-link
                                        :to="`/systems/on-premise/${system.id}/edit`"
                                    >
                                        <a
                                            href="javascript:void(0)"
                                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                                        >
                                            {{ system.systemNumber }}
                                        </a>
                                    </router-link>
                                </td>
                                <td class="border-t">
                                    <router-link
                                        :to="`/systems/on-premise/${system.id}/edit`"
                                    >
                                        <a
                                            href="javascript:void(0)"
                                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                                        >
                                            {{ system?.systemName }}
                                        </a>
                                    </router-link>
                                </td>
                                <td class="border-t">
                                    <router-link
                                        :to="`/systems/on-premise/${system.id}/edit`"
                                    >
                                        <a
                                            href="javascript:void(0)"
                                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                                        >
                                            {{ system.customer }}
                                        </a>
                                    </router-link>
                                </td>
                                <td class="border-t">
                                    <router-link
                                        :to="`/systems/on-premise/${system.id}/edit`"
                                    >
                                        <a
                                            href="javascript:void(0)"
                                            class="flex items-center px-6 py-4"
                                            tabindex="-1"
                                        >
                                            {{ system.serverIp }}
                                        </a>
                                    </router-link>
                                </td>
                                <td class="border-t">
                                    <router-link
                                        :to="`/systems/on-premise/${system.id}/edit`"
                                    >
                                        <a
                                            href="javascript:void(0)"
                                            class="flex items-center px-6 py-4"
                                            tabindex="-1"
                                        >
                                            {{ system.instanceType }}
                                        </a>
                                    </router-link>
                                </td>
                                <td class="border-t">
                                    <router-link
                                        :to="`/systems/on-premise/${system.id}/edit`"
                                    >
                                        <a
                                            href="javascript:void(0)"
                                            class="flex items-center px-6 py-4"
                                            tabindex="-1"
                                        >
                                            {{ system.operatingSystem }}
                                        </a>
                                    </router-link>
                                </td>
                                <td class="w-px border-t">
                                    <router-link
                                        :to="`/systems/on-premise/${system.id}/edit`"
                                    >
                                        <font-awesome-icon
                                            icon="fa-regular fa-pen-to-square"
                                        />
                                    </router-link>
                                </td>
                            </tr>
                            <tr
                                class="flex justify-center py-4"
                                v-if="premiseSystems.length === 0"
                            >
                                <p class="text-center">
                                    {{ $t("On Premise Systems not added") }},
                                </p>
                                <router-link
                                    class="link"
                                    :to="'/systems/on-premise/create'"
                                    >&nbsp;{{ $t("create here") }}</router-link
                                >
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="card my-5">
                    <div class="card-header">
                        <h3 class="card-title">{{ $t("Cloud Systems") }}</h3>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="w-full doc-table">
                            <tr class="text-left font-bold">
                                <th
                                    @click="sortByCloud('systemNumber')"
                                    class="pb-4 pt-6 px-6 cursor-pointer"
                                >
                                    {{ $t("System Number") }}
                                    <font-awesome-icon
                                        v-if="sortFieldCloud === 'systemNumber'"
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            sortOrderCloud === 1 ? 'up' : 'down'
                                        }`"
                                    />
                                </th>
                                <th
                                    @click="sortByCloud('serverIp')"
                                    class="pb-4 pt-6 px-6 cursor-pointer"
                                >
                                    {{ $t("Host") }}
                                    <font-awesome-icon
                                        v-if="sortFieldCloud === 'serverIp'"
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            sortOrderCloud === 1 ? 'up' : 'down'
                                        }`"
                                    />
                                </th>
                                <th
                                    @click="sortByCloud('tennantName')"
                                    class="pb-4 pt-6 px-6 cursor-pointer"
                                >
                                    {{ $t("Tenant Name") }}
                                    <font-awesome-icon
                                        v-if="sortFieldCloud === 'tennantName'"
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            sortOrderCloud === 1 ? 'up' : 'down'
                                        }`"
                                    />
                                </th>
                                <th
                                    @click="sortByCloud('subType')"
                                    class="pb-4 pt-6 px-6 cursor-pointer"
                                >
                                    {{ $t("Sub Type") }}
                                    <font-awesome-icon
                                        v-if="sortFieldCloud === 'subType'"
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            sortOrderCloud === 1 ? 'up' : 'down'
                                        }`"
                                    />
                                </th>
                                <th
                                    @click="sortByCloud('instanceType')"
                                    class="pb-4 pt-6 px-6 cursor-pointer"
                                >
                                    {{ $t("Instance Type") }}
                                    <font-awesome-icon
                                        v-if="sortFieldCloud === 'instanceType'"
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            sortOrderCloud === 1 ? 'up' : 'down'
                                        }`"
                                    />
                                </th>
                                <th class="pb-4 pt-6 px-6">
                                    {{ $t("Action") }}
                                </th>
                            </tr>
                            <template
                                v-for="system in sortedCloud"
                                :key="system.id"
                            >
                                <tr
                                    @click="
                                        system.isDropdown = !system.isDropdown
                                    "
                                    class=""
                                >
                                    <td class="border-t">
                                        <router-link
                                            :to="`/systems/cloud/${system.id}/edit`"
                                        >
                                            <a
                                                href="javascript:void(0)"
                                                class="flex items-center px-6 py-4 focus:text-indigo-500"
                                            >
                                                {{ system.systemNumber }}
                                            </a>
                                        </router-link>
                                    </td>

                                    <td class="border-t">
                                        <router-link
                                            :to="`/systems/cloud/${system.id}/edit`"
                                        >
                                            <a
                                                href="javascript:void(0)"
                                                class="flex items-center px-6 py-4"
                                                tabindex="-1"
                                            >
                                                {{ system.serverIp }}
                                            </a>
                                        </router-link>
                                    </td>
                                    <td class="border-t">
                                        <router-link
                                            :to="`/systems/cloud/${system.id}/edit`"
                                        >
                                            <a
                                                href="javascript:void(0)"
                                                class="flex items-center px-6 py-4"
                                                tabindex="-1"
                                            >
                                                {{ system.tennantName }}
                                            </a>
                                        </router-link>
                                    </td>
                                    <td class="border-t">
                                        <router-link
                                            :to="`/systems/cloud/${system.id}/edit`"
                                        >
                                            <a
                                                href="javascript:void(0)"
                                                class="flex items-center px-6 py-4"
                                                tabindex="-1"
                                            >
                                                {{ system.subType }}
                                            </a>
                                        </router-link>
                                    </td>
                                    <td class="border-t">
                                        <router-link
                                            :to="`/systems/cloud/${system.id}/edit`"
                                        >
                                            <a
                                                href="javascript:void(0)"
                                                class="flex items-center px-6 py-4"
                                                tabindex="-1"
                                            >
                                                {{ system.instanceType }}
                                            </a>
                                        </router-link>
                                    </td>
                                    <td class="w-px border-t">
                                        <router-link
                                            :to="`/systems/cloud/${system.id}/edit`"
                                        >
                                            <font-awesome-icon
                                                icon="fa-regular fa-pen-to-square"
                                            ></font-awesome-icon>
                                        </router-link>
                                    </td>
                                    <!-- other columns -->
                                </tr>
                                <!-- nested table -->
                            </template>
                            <tr
                                class="flex px-6 py-2"
                                v-if="cloudSystems.length === 0"
                            >
                                <p class="text-center">
                                    {{ $t("Cloud Systems not added") }},
                                </p>
                                <router-link
                                    class="link"
                                    :to="'/systems/cloud/create'"
                                    >&nbsp;{{ $t("create here") }}</router-link
                                >
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            {{ $t("Hosting Systems") }}
                        </h3>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="w-full doc-table">
                            <tr class="text-left font-bold">
                                <th
                                    @click="sortByHosting('systemNumber')"
                                    class="pb-4 pt-6 px-6 cursor-pointer"
                                >
                                    {{ $t("System Number") }}
                                    <font-awesome-icon
                                        v-if="
                                            sortFieldHosting === 'systemNumber'
                                        "
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            sortOrderHosting === 1
                                                ? 'up'
                                                : 'down'
                                        }`"
                                    />
                                </th>
                                <th
                                    @click="sortByHosting('customer')"
                                    class="pb-4 pt-6 px-6 cursor-pointer"
                                >
                                    {{ $t("Customer") }}
                                    <font-awesome-icon
                                        v-if="sortFieldHosting === 'customer'"
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            sortOrderHosting === 1
                                                ? 'up'
                                                : 'down'
                                        }`"
                                    />
                                </th>
                                <th
                                    @click="sortByHosting('virtualMachine')"
                                    class="pb-4 pt-6 px-6 cursor-pointer"
                                >
                                    {{ $t("Virtual Machine Name") }}
                                    <font-awesome-icon
                                        v-if="
                                            sortFieldHosting ===
                                            'virtualMachine'
                                        "
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            sortOrderHosting === 1
                                                ? 'up'
                                                : 'down'
                                        }`"
                                    />
                                </th>
                                <th
                                    @click="sortByHosting('instanceType')"
                                    class="pb-4 pt-6 px-6 cursor-pointer"
                                >
                                    {{ $t("Instance Type") }}
                                    <font-awesome-icon
                                        v-if="
                                            sortFieldHosting === 'instanceType'
                                        "
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            sortOrderHosting === 1
                                                ? 'up'
                                                : 'down'
                                        }`"
                                    />
                                </th>
                                <th
                                    @click="sortByHosting('operatingSystem')"
                                    class="pb-4 pt-6 px-6 cursor-pointer"
                                >
                                    {{ $t("Operating System") }}
                                    <font-awesome-icon
                                        v-if="
                                            sortFieldHosting ===
                                            'operatingSystem'
                                        "
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            sortOrderHosting === 1
                                                ? 'up'
                                                : 'down'
                                        }`"
                                    />
                                </th>
                                <th class="pb-4 pt-6 px-6">
                                    {{ $t("Action") }}
                                </th>
                            </tr>
                            <tr
                                v-for="system in sortedHosting"
                                :key="system.id"
                                class="hover:bg-gray-100 focus-within:bg-gray-100"
                            >
                                <td class="border-t">
                                    <router-link
                                        :to="`/systems/hosting/${system.id}/edit`"
                                    >
                                        <a
                                            href="javascript:void(0)"
                                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                                        >
                                            {{ system.systemNumber }}
                                        </a>
                                    </router-link>
                                </td>
                                <td class="border-t">
                                    <router-link
                                        :to="`/systems/hosting/${system.id}/edit`"
                                    >
                                        <a
                                            href="javascript:void(0)"
                                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                                        >
                                            {{ system.customer }}
                                        </a>
                                    </router-link>
                                </td>
                                <td class="border-t">
                                    <router-link
                                        :to="`/systems/hosting/${system.id}/edit`"
                                    >
                                        <a
                                            href="javascript:void(0)"
                                            class="flex items-center px-6 py-4"
                                            tabindex="-1"
                                        >
                                            {{ system.virtualMachine }}
                                        </a>
                                    </router-link>
                                </td>
                                <td class="border-t">
                                    <router-link
                                        :to="`/systems/hosting/${system.id}/edit`"
                                    >
                                        <a
                                            href="javascript:void(0)"
                                            class="flex items-center px-6 py-4"
                                            tabindex="-1"
                                        >
                                            {{ system.instanceType }}
                                        </a>
                                    </router-link>
                                </td>
                                <td class="border-t">
                                    <router-link
                                        :to="`/systems/hosting/${system.id}/edit`"
                                    >
                                        <a
                                            href="javascript:void(0)"
                                            class="flex items-center px-6 py-4"
                                            tabindex="-1"
                                        >
                                            {{ system.operatingSystem }}
                                        </a>
                                    </router-link>
                                </td>
                                <td class="border-t">
                                    <router-link
                                        :to="`/systems/hosting/${system.id}/edit`"
                                    >
                                        <font-awesome-icon
                                            icon="fa-regular fa-pen-to-square"
                                        />
                                    </router-link>
                                </td>
                            </tr>
                            <tr
                                class="flex px-6 py-2"
                                v-if="hostingSystems.length === 0"
                            >
                                <p class="text-center">
                                    {{ $t("Hoting Systems not added") }},
                                </p>
                                <router-link
                                    class="link"
                                    :to="'/systems/hosting/create'"
                                    >&nbsp;{{ $t("create here") }}</router-link
                                >
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div
                v-if="activeTab === 'offers'"
                class="p-4"
                id="profile"
                role="tabpanel"
                aria-labelledby="profile-tab"
            >
                <div class="">
                    <div class="table-responsive">
                        <table class="w-full doc-table">
                            <tr class="text-left font-bold">
                                <th
                                    @click="
                                        sort(
                                            'saleOfferNumber',
                                            'offers',
                                            'offer'
                                        )
                                    "
                                    class="cursor-pointer"
                                >
                                    {{ $t("Offer Number")
                                    }}<font-awesome-icon
                                        v-if="
                                            offer.sortBy === 'saleOfferNumber'
                                        "
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            offer.sortOrder === 'asc'
                                                ? 'up'
                                                : 'down'
                                        }`"
                                    />
                                </th>
                                <th
                                    @click="
                                        sort(
                                            'Company.companyName',
                                            'offers',
                                            'offer'
                                        )
                                    "
                                    class="cursor-pointer"
                                >
                                    {{ $t("Company")
                                    }}<font-awesome-icon
                                        v-if="
                                            offer.sortBy ===
                                            'Company.companyName'
                                        "
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            offer.sortOrder === 'asc'
                                                ? 'up'
                                                : 'down'
                                        }`"
                                    />
                                </th>
                                <th
                                    @click="
                                        sort(
                                            'TermsOfPayment.name',
                                            'offers',
                                            'offer'
                                        )
                                    "
                                    class="cursor-pointer"
                                >
                                    {{ $t("Terms")
                                    }}<font-awesome-icon
                                        v-if="
                                            offer.sortBy ===
                                            'TermsOfPayment.name'
                                        "
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            offer.sortOrder === 'asc'
                                                ? 'up'
                                                : 'down'
                                        }`"
                                    />
                                </th>
                                <th
                                    @click="
                                        sort('receiverType', 'offers', 'offer')
                                    "
                                    class="cursor-pointer"
                                >
                                    {{ $t("Receiver Type")
                                    }}<font-awesome-icon
                                        v-if="offer.sortBy === 'receiverType'"
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            offer.sortOrder === 'asc'
                                                ? 'up'
                                                : 'down'
                                        }`"
                                    />
                                </th>
                                <th
                                    @click="sort('type', 'offers', 'offer')"
                                    class="cursor-pointer"
                                >
                                    {{ $t("Type")
                                    }}<font-awesome-icon
                                        v-if="offer.sortBy === 'type'"
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            offer.sortOrder === 'asc'
                                                ? 'up'
                                                : 'down'
                                        }`"
                                    />
                                </th>
                                <th
                                    @click="
                                        sort('Project.name', 'offers', 'offer')
                                    "
                                    class="cursor-pointer"
                                >
                                    {{ $t("Project")
                                    }}<font-awesome-icon
                                        v-if="offer.sortBy === 'Project.name'"
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            offer.sortOrder === 'asc'
                                                ? 'up'
                                                : 'down'
                                        }`"
                                    />
                                </th>
                                <th class="pb-4 pt-6 px-6">
                                    {{ $t("Action") }}
                                </th>
                            </tr>
                            <tr
                                v-for="order in offers"
                                :key="order.id"
                                class="hover:bg-gray-100 focus-within:bg-gray-100"
                                @click="$router.push(`/offers/${order.id}`)"
                            >
                                <td class="border-t">
                                    <a
                                        href="javascript:void(0)"
                                        class="flex items-center px-6 py-4 focus:text-indigo-500"
                                    >
                                        {{ order.offerNumber }}
                                    </a>
                                </td>
                                <td class="border-t">
                                    <a
                                        href="javascript:void(0)"
                                        class="flex items-center px-6 py-4 focus:text-indigo-500"
                                    >
                                        {{ order.company }}
                                    </a>
                                </td>
                                <td class="border-t">
                                    <a
                                        href="javascript:void(0)"
                                        class="flex items-center px-6 py-4 focus:text-indigo-500"
                                    >
                                        {{ order.terms }}
                                    </a>
                                </td>
                                <td class="border-t">
                                    <a
                                        href="javascript:void(0)"
                                        class="flex items-center px-6 py-4"
                                        tabindex="-1"
                                    >
                                        {{ order.receiverType }}
                                    </a>
                                </td>
                                <td class="border-t">
                                    <a
                                        href="javascript:void(0)"
                                        class="flex items-center px-6 py-4"
                                        tabindex="-1"
                                    >
                                        {{ order.type }}
                                    </a>
                                </td>
                                <td class="border-t">
                                    <a
                                        href="javascript:void(0)"
                                        class="flex items-center px-6 py-4"
                                        tabindex="-1"
                                    >
                                        {{ order.project }}
                                    </a>
                                </td>
                                <td class="w-px border-t text-center">
                                    <div class="flex justify-around">
                                        <router-link
                                            @click.stop=""
                                            :to="`/offers/${order.id}`"
                                        >
                                            <font-awesome-icon
                                                icon="fa-solid fa-eye"
                                            />
                                        </router-link>
                                        <router-link
                                            @click.stop=""
                                            class="px-4"
                                            :to="`/offers/${order.id}/edit`"
                                        >
                                            <font-awesome-icon
                                                icon="fa-regular fa-pen-to-square"
                                            />
                                        </router-link>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="mt-4" v-if="!offers.length">
                        <div class="flex justify-center">
                            <p class="text-center">
                                {{ $t("offers not added") }},
                            </p>
                            <router-link class="link" :to="'/offers/create'"
                                >&nbsp;{{ $t("create here") }}</router-link
                            >
                        </div>
                    </div>
                </div>
            </div>
            <div
                v-if="activeTab === 'orderConfirmation'"
                class="p-4"
                id="profile"
                role="tabpanel"
                aria-labelledby="profile-tab"
            >
                <div class="table-responsive">
                    <table class="w-full doc-table">
                        <tr class="text-left font-bold">
                            <th
                                @click="
                                    sort('saleOfferNumber', 'offers', 'order')
                                "
                                class="cursor-pointer"
                            >
                                {{ $t("Offer Number")
                                }}<font-awesome-icon
                                    v-if="order.sortBy === 'saleOfferNumber'"
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        order.sortOrder === 'asc'
                                            ? 'up'
                                            : 'down'
                                    }`"
                                />
                            </th>
                            <th
                                @click="
                                    sort(
                                        'Company.companyName',
                                        'offers',
                                        'order'
                                    )
                                "
                                class="cursor-pointer"
                            >
                                {{ $t("Company")
                                }}<font-awesome-icon
                                    v-if="
                                        order.sortBy === 'Company.companyName'
                                    "
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        order.sortOrder === 'asc'
                                            ? 'up'
                                            : 'down'
                                    }`"
                                />
                            </th>
                            <th
                                @click="
                                    sort(
                                        'TermsOfPayment.name',
                                        'offers',
                                        'order'
                                    )
                                "
                                class="cursor-pointer"
                            >
                                {{ $t("Terms")
                                }}<font-awesome-icon
                                    v-if="
                                        order.sortBy === 'TermsOfPayment.name'
                                    "
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        order.sortOrder === 'asc'
                                            ? 'up'
                                            : 'down'
                                    }`"
                                />
                            </th>
                            <th
                                @click="sort('receiverType', 'offers', 'order')"
                                class="cursor-pointer"
                            >
                                {{ $t("Receiver Type")
                                }}<font-awesome-icon
                                    v-if="order.sortBy === 'receiverType'"
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        order.sortOrder === 'asc'
                                            ? 'up'
                                            : 'down'
                                    }`"
                                />
                            </th>
                            <th
                                @click="sort('type', 'offers', 'order')"
                                class="cursor-pointer"
                            >
                                {{ $t("Type")
                                }}<font-awesome-icon
                                    v-if="order.sortBy === 'type'"
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        order.sortOrder === 'asc'
                                            ? 'up'
                                            : 'down'
                                    }`"
                                />
                            </th>
                            <th
                                @click="sort('Project.name', 'offers', 'order')"
                                class="cursor-pointer"
                            >
                                {{ $t("Project")
                                }}<font-awesome-icon
                                    v-if="order.sortBy === 'Project.name'"
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        order.sortOrder === 'asc'
                                            ? 'up'
                                            : 'down'
                                    }`"
                                />
                            </th>
                            <th class="pb-4 pt-6 px-6">{{ $t("Action") }}</th>
                        </tr>
                        <tr
                            v-for="order in orderConfirmation"
                            :key="order.id"
                            class="hover:bg-gray-100 focus-within:bg-gray-100"
                            @click="
                                $router.push(`/order-confirmation/${order.id}`)
                            "
                        >
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                >
                                    {{ order.offerNumber }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                >
                                    {{ order.company }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                >
                                    {{ order.terms }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4"
                                    tabindex="-1"
                                >
                                    {{ order.receiverType }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4"
                                    tabindex="-1"
                                >
                                    {{ order.type }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4"
                                    tabindex="-1"
                                >
                                    {{ order.project }}
                                </a>
                            </td>
                            <td class="w-px border-t text-center">
                                <div class="flex justify-around">
                                    <router-link
                                        @click.stop=""
                                        :to="`/order-confirmation/${order.id}`"
                                    >
                                        <font-awesome-icon
                                            icon="fa-solid fa-eye"
                                        />
                                    </router-link>
                                    <router-link
                                        @click.stop=""
                                        class="px-4"
                                        :to="`/order-confirmation/${order.id}/edit`"
                                    >
                                        <font-awesome-icon
                                            icon="fa-regular fa-pen-to-square"
                                        />
                                    </router-link>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="mt-4" v-if="!orderConfirmation.length">
                    <div class="flex justify-center">
                        <p class="text-center">
                            {{ $t("Order Confirmation not added") }},
                        </p>
                        <router-link
                            class="link"
                            :to="'/order-confirmation/create'"
                            >&nbsp;{{ $t("create here") }}</router-link
                        >
                    </div>
                </div>
            </div>
            <div
                v-if="activeTab === 'invoices'"
                class="p-4"
                id="profile"
                role="tabpanel"
                aria-labelledby="profile-tab"
            >
                <div class="table-responsive">
                    <table class="w-full doc-table">
                        <tr class="text-left font-bold">
                            <th
                                @click="
                                    sort('invoiceNumber', 'invoices', 'invoice')
                                "
                                class="cursor-pointer"
                            >
                                {{ $t("Invoice number")
                                }}<font-awesome-icon
                                    v-if="invoice.sortBy === 'invoiceNumber'"
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        invoice.sortOrder === 'asc'
                                            ? 'up'
                                            : 'down'
                                    }`"
                                />
                            </th>
                            <th
                                @click="
                                    sort(
                                        'Company.companyName',
                                        'invoices',
                                        'invoice'
                                    )
                                "
                                class="cursor-pointer"
                            >
                                {{ $t("Customer")
                                }}<font-awesome-icon
                                    v-if="
                                        invoice.sortBy === 'Company.companyName'
                                    "
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        invoice.sortOrder === 'asc'
                                            ? 'up'
                                            : 'down'
                                    }`"
                                />
                            </th>
                            <th
                                @click="
                                    sort(
                                        'customNotesField',
                                        'invoices',
                                        'invoice'
                                    )
                                "
                                class="cursor-pointer"
                            >
                                {{ $t("Notes")
                                }}<font-awesome-icon
                                    v-if="invoice.sortBy === 'customNotesField'"
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        invoice.sortOrder === 'asc'
                                            ? 'up'
                                            : 'down'
                                    }`"
                                />
                            </th>
                            <th
                                @click="
                                    sort('invoiceType', 'invoices', 'invoice')
                                "
                                class="cursor-pointer"
                            >
                                {{ $t("Invoice type")
                                }}<font-awesome-icon
                                    v-if="invoice.sortBy === 'invoiceType'"
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        invoice.sortOrder === 'asc'
                                            ? 'up'
                                            : 'down'
                                    }`"
                                />
                            </th>

                            <th
                                @click="
                                    sort('invoicePeriod', 'invoices', 'invoice')
                                "
                                class="cursor-pointer"
                            >
                                {{ $t("Invoice period")
                                }}<font-awesome-icon
                                    v-if="invoice.sortBy === 'invoicePeriod'"
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        invoice.sortOrder === 'asc'
                                            ? 'up'
                                            : 'down'
                                    }`"
                                />
                            </th>
                            <th
                                @click="sort('dueDate', 'invoices', 'invoice')"
                                class="cursor-pointer"
                            >
                                {{ $t("Due Date")
                                }}<font-awesome-icon
                                    v-if="invoice.sortBy === 'dueDate'"
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        invoice.sortOrder === 'asc'
                                            ? 'up'
                                            : 'down'
                                    }`"
                                />
                            </th>
                            <th class="pb-4 pt-6 px-6">{{ $t("Action") }}</th>
                        </tr>
                        <tr
                            v-for="invoice in invoices"
                            :key="invoice.id"
                            class="hover:bg-gray-100 focus-within:bg-gray-100"
                            @click="$router.push(`/invoices/${invoice.id}`)"
                        >
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                >
                                    {{ invoice.invoiceNumber }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                >
                                    {{ invoice.company }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                >
                                    {{ invoice.notes }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4"
                                    tabindex="-1"
                                >
                                    {{ invoice.invoiceType }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4"
                                    tabindex="-1"
                                >
                                    {{ invoice.invoicePeriod }}
                                    {{ $t("Months") }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4"
                                    tabindex="-1"
                                >
                                    {{
                                        $dateFormatter(
                                            invoice.dueDate,
                                            globalLanguage
                                        )
                                    }}
                                </a>
                            </td>
                            <td class="w-px border-t text-center">
                                <div class="flex justify-around">
                                    <router-link
                                        @click.stop=""
                                        :to="`/invoices/${invoice.id}`"
                                    >
                                        <font-awesome-icon
                                            icon="fa-solid fa-eye"
                                        />
                                    </router-link>
                                    <router-link
                                        @click.stop=""
                                        class="px-4"
                                        :to="`/invoices/${invoice.id}/edit`"
                                    >
                                        <font-awesome-icon
                                            icon="fa-regular fa-pen-to-square"
                                        />
                                    </router-link>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="mt-4" v-if="!invoices.length">
                    <div class="flex justify-center">
                        <p class="text-center">
                            {{ $t("Invoices not added") }},
                        </p>
                        <router-link class="link" :to="'/invoices/create'"
                            >&nbsp;{{ $t("create here") }}</router-link
                        >
                    </div>
                </div>
            </div>
            <div
                v-if="activeTab === 'reports'"
                class="p-4"
                id="profile"
                role="tabpanel"
                aria-labelledby="profile-tab"
            >
                <div class="table-responsive">
                    <table class="w-full doc-table">
                        <tr class="text-left font-bold">
                            <th
                                @click="
                                    sort(
                                        'subject',
                                        'contactReports',
                                        'contactReport'
                                    )
                                "
                                class="cursor-pointer"
                            >
                                {{ $t("Subject")
                                }}<font-awesome-icon
                                    v-if="contactReport.sortBy === 'subject'"
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        contactReport.sortOrder === 'asc'
                                            ? 'up'
                                            : 'down'
                                    }`"
                                />
                            </th>
                            <th
                                @click="
                                    sort(
                                        'type',
                                        'contactReports',
                                        'contactReport'
                                    )
                                "
                                class="cursor-pointer"
                            >
                                {{ $t("Type")
                                }}<font-awesome-icon
                                    v-if="contactReport.sortBy === 'type'"
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        contactReport.sortOrder === 'asc'
                                            ? 'up'
                                            : 'down'
                                    }`"
                                />
                            </th>
                            <th
                                @click="
                                    sort(
                                        'Company.companyName',
                                        'contactReports',
                                        'contactReport'
                                    )
                                "
                                class="cursor-pointer"
                            >
                                {{ $t("Company")
                                }}<font-awesome-icon
                                    v-if="
                                        contactReport.sortBy ===
                                        'Company.companyName'
                                    "
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        contactReport.sortOrder === 'asc'
                                            ? 'up'
                                            : 'down'
                                    }`"
                                />
                            </th>
                            <th
                                @click="
                                    sort(
                                        'priority',
                                        'contactReports',
                                        'contactReport'
                                    )
                                "
                                class="cursor-pointer"
                            >
                                {{ $t("Priority")
                                }}<font-awesome-icon
                                    v-if="contactReport.sortBy === 'priority'"
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        contactReport.sortOrder === 'asc'
                                            ? 'up'
                                            : 'down'
                                    }`"
                                />
                            </th>
                            <th
                                @click="
                                    sort(
                                        'contactType',
                                        'contactReports',
                                        'contactReport'
                                    )
                                "
                                class="cursor-pointer"
                            >
                                {{ $t("Contact Type")
                                }}<font-awesome-icon
                                    v-if="
                                        contactReport.sortBy === 'contactType'
                                    "
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        contactReport.sortOrder === 'asc'
                                            ? 'up'
                                            : 'down'
                                    }`"
                                />
                            </th>

                            <th
                                @click="
                                    sort(
                                        'initiatedBy',
                                        'contactReports',
                                        'contactReport'
                                    )
                                "
                                class="cursor-pointer"
                            >
                                {{ $t("Initiated By")
                                }}<font-awesome-icon
                                    v-if="
                                        contactReport.sortBy === 'initiatedBy'
                                    "
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        contactReport.sortOrder === 'asc'
                                            ? 'up'
                                            : 'down'
                                    }`"
                                />
                            </th>
                            <th class="pb-4 pt-6 px-6">{{ $t("Actions") }}</th>
                        </tr>
                        <tr
                            v-for="report in reports ?? []"
                            :key="report.id"
                            class="hover:bg-gray-100 focus-within:bg-gray-100"
                        >
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                >
                                    {{ report.subject }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4"
                                    tabindex="-1"
                                >
                                    {{ report.type }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4"
                                    tabindex="-1"
                                >
                                    {{ report.company }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4"
                                    tabindex="-1"
                                >
                                    {{ report.priority }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4"
                                    tabindex="-1"
                                >
                                    {{ report.contactType }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4"
                                    tabindex="-1"
                                >
                                    {{ report.initiatedBy }}
                                </a>
                            </td>
                            <td class="w-px border-t text-center">
                                <router-link
                                    class="px-1"
                                    :to="`/contact-reports/${report.id}/edit`"
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-pen-to-square"
                                    />
                                </router-link>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="mt-4" v-if="!reports.length">
                    <div class="flex justify-center">
                        <p class="text-center">
                            {{ $t("Reports not added") }},
                        </p>
                        <router-link
                            class="link"
                            :to="'/contact-reports/create'"
                            >&nbsp;{{ $t("create here") }}</router-link
                        >
                    </div>
                </div>
            </div>
            <div
                v-if="activeTab === 'projects'"
                class="p-4"
                id="profile"
                role="tabpanel"
                aria-labelledby="profile-tab"
            >
                <div class="table-responsive">
                    <table class="w-full doc-table">
                        <tr class="text-left font-bold">
                            <th
                                @click="sort('name', 'project', 'project')"
                                class="cursor-pointer"
                            >
                                {{ $t("Name")
                                }}<font-awesome-icon
                                    v-if="project.sortBy === 'name'"
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        project.sortOrder === 'asc'
                                            ? 'up'
                                            : 'down'
                                    }`"
                                />
                            </th>
                            <th
                                @click="sort('status', 'project', 'project')"
                                class="cursor-pointer"
                            >
                                {{ $t("Status")
                                }}<font-awesome-icon
                                    v-if="project.sortBy === 'status'"
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        project.sortOrder === 'asc'
                                            ? 'up'
                                            : 'down'
                                    }`"
                                />
                            </th>
                            <th
                                @click="
                                    sort(
                                        'plannedStartDate',
                                        'project',
                                        'project'
                                    )
                                "
                                class="cursor-pointer"
                            >
                                {{ $t("Planned Start Date")
                                }}<font-awesome-icon
                                    v-if="project.sortBy === 'plannedStartDate'"
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        project.sortOrder === 'asc'
                                            ? 'up'
                                            : 'down'
                                    }`"
                                />
                            </th>
                            <th
                                @click="
                                    sort(
                                        'plannedFinishedDate',
                                        'project',
                                        'project'
                                    )
                                "
                                class="cursor-pointer"
                            >
                                {{ $t("Planned Finish Date")
                                }}<font-awesome-icon
                                    v-if="
                                        project.sortBy === 'plannedFinishedDate'
                                    "
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        project.sortOrder === 'asc'
                                            ? 'up'
                                            : 'down'
                                    }`"
                                />
                            </th>

                            <th
                                @click="
                                    sort('estimatedTime', 'project', 'project')
                                "
                                class="cursor-pointer"
                            >
                                {{ $t("Estimated time")
                                }}<font-awesome-icon
                                    v-if="project.sortBy === 'estimatedTime'"
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        project.sortOrder === 'asc'
                                            ? 'up'
                                            : 'down'
                                    }`"
                                />
                            </th>
                            <th
                                @click="
                                    sort('neededTime', 'project', 'project')
                                "
                                class="cursor-pointer"
                            >
                                {{ $t("Needed time")
                                }}<font-awesome-icon
                                    v-if="project.sortBy === 'neededTime'"
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        project.sortOrder === 'asc'
                                            ? 'up'
                                            : 'down'
                                    }`"
                                />
                            </th>
                            <th class="pb-4 pt-6 px-6">{{ $t("Actions") }}</th>
                        </tr>
                        <tr
                            v-for="project in projects ?? []"
                            :key="project.id"
                            class="hover:bg-gray-100 focus-within:bg-gray-100"
                            @click="
                                $router.push(
                                    `/project-management?id=${project.id}`
                                )
                            "
                        >
                            <td class="border-t">
                                <router-link
                                    :is="
                                        $can('project-management')
                                            ? 'router-link'
                                            : 'span'
                                    "
                                    :to="`/project-management?id=${project.id}`"
                                    class="flex items-center px-6 py-4 focus:text-indigo-500 link overflow-text-project"
                                >
                                    {{ project.name }}
                                </router-link>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4"
                                    tabindex="-1"
                                >
                                    {{ project.status }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4"
                                    tabindex="-1"
                                >
                                    {{ $dateFormatter(project.plannedStartDate, globalLanguage) }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4"
                                    tabindex="-1"
                                >
                                    {{ $dateFormatter(project.plannedFinishedDate, globalLanguage) }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4"
                                    tabindex="-1"
                                >
                                    {{ project.estimatedTime }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4"
                                    tabindex="-1"
                                >
                                    {{ project.neededTime }}
                                </a>
                            </td>
                            <td class="w-px border-t text-center">
                                <router-link
                                    class="px-4"
                                    :to="`/project-management/projects/${project.id}/edit`"
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-pen-to-square"
                                    />
                                </router-link>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="mt-4" v-if="!projects.length">
                    <div class="flex justify-center">
                        <p class="text-center">
                            {{ $t("Projects not added") }},
                        </p>
                        <router-link
                            class="link"
                            :to="'/project-management/projects/create'"
                            >&nbsp;{{ $t("create here") }}</router-link
                        >
                    </div>
                </div>
            </div>
            <div
                v-if="activeTab === 'tickets'"
                class="p-4"
                id="profile"
                role="tabpanel"
                aria-labelledby="profile-tab"
            >
                <div class="table-responsive">
                    <table class="w-full doc-table">
                        <tr class="text-left font-bold">
                            <th
                                @click="
                                    sort('ticketNumber', 'ticket', 'ticket')
                                "
                                class="cursor-pointer"
                            >
                                {{ $t("Ticket Number")
                                }}<font-awesome-icon
                                    v-if="ticket.sortBy === 'ticketNumber'"
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        ticket.sortOrder === 'asc'
                                            ? 'up'
                                            : 'down'
                                    }`"
                                />
                            </th>
                            <th
                                @click="sort('title', 'ticket', 'ticket')"
                                class="cursor-pointer"
                            >
                                {{ $t("Title")
                                }}<font-awesome-icon
                                    v-if="ticket.sortBy === 'title'"
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        ticket.sortOrder === 'asc'
                                            ? 'up'
                                            : 'down'
                                    }`"
                                />
                            </th>
                            <th
                                @click="sort('state', 'ticket', 'ticket')"
                                class="cursor-pointer"
                            >
                                {{ $t("Status")
                                }}<font-awesome-icon
                                    v-if="ticket.sortBy === 'state'"
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        ticket.sortOrder === 'asc'
                                            ? 'up'
                                            : 'down'
                                    }`"
                                />
                            </th>
                            <th
                                @click="sort('priority', 'ticket', 'ticket')"
                                class="cursor-pointer"
                            >
                                {{ $t("Priority")
                                }}<font-awesome-icon
                                    v-if="ticket.sortBy === 'priority'"
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        ticket.sortOrder === 'asc'
                                            ? 'up'
                                            : 'down'
                                    }`"
                                />
                            </th>
                            <th class="pb-4 pt-6 px-6">{{ $t("Action") }}</th>
                        </tr>
                        <tr
                            v-for="item in tickets"
                            :key="item.id"
                            class="hover:bg-gray-100 focus-within:bg-gray-100"
                            @click="$router.push(`/tickets/${item.id}`)"
                        >
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                >
                                    {{ item.ticketNumber }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                >
                                    {{ item.title }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                >
                                    {{ item.state }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                >
                                    {{ item.priority }}
                                </a>
                            </td>
                            <td class="w-px border-t">
                                <router-link
                                    v-if="
                                        $can(`${$route.meta.permission}.edit`)
                                    "
                                    class="px-1"
                                    :to="`/tickets/${item.id}`"
                                >
                                    <font-awesome-icon icon="fa-solid fa-eye" />
                                </router-link>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="mt-4" v-if="!tickets.length">
                    <div class="flex justify-center">
                        <p class="text-center">
                            {{ $t("Tickets not added") }},
                        </p>
                        <router-link class="link" to="/tickets/create"
                            >&nbsp;{{ $t("create here") }}</router-link
                        >
                    </div>
                </div>
            </div>
            <div
                v-if="activeTab === 'contracts'"
                class="p-4"
                id="profile"
                role="tabpanel"
                aria-labelledby="profile-tab"
            >
                <div class="table-responsive">
                    <table class="w-full doc-table">
                        <tr class="text-left font-bold">
                            <th
                                @click="
                                    sort(
                                        'contractNumber',
                                        'outbounded',
                                        'contract'
                                    )
                                "
                                class="cursor-pointer"
                            >
                                {{ $t("Contract Number")
                                }}<font-awesome-icon
                                    v-if="contract.sortBy === 'contractNumber'"
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        contract.sortOrder === 'asc'
                                            ? 'up'
                                            : 'down'
                                    }`"
                                />
                            </th>
                            <th
                                class="cursor-pointer"
                                @click="
                                    sort(
                                        'Company.companyName',
                                        'outbounded',
                                        'contract'
                                    )
                                "
                            >
                                {{ $t("Customer") }}
                                <font-awesome-icon
                                    v-if="
                                        contract.sortBy ===
                                        'Company.companyName'
                                    "
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        contract.sortOrder === 'asc'
                                            ? 'up'
                                            : 'down'
                                    }`"
                                />
                            </th>
                            <th
                                class="cursor-pointer"
                                @click="
                                    sort(
                                        'ContractType.name',
                                        'outbounded',
                                        'contract'
                                    )
                                "
                            >
                                {{ $t("Contract Type") }}
                                <font-awesome-icon
                                    v-if="
                                        contract.sortBy === 'ContractType.name'
                                    "
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        contract.sortOrder === 'asc'
                                            ? 'up'
                                            : 'down'
                                    }`"
                                />
                            </th>
                            <th
                                class="cursor-pointer"
                                @click="
                                    sort('createdAt', 'outbounded', 'contract')
                                "
                            >
                                {{ $t("Create Date") }}
                                <font-awesome-icon
                                    v-if="contract.sortBy === 'createdAt'"
                                    class="cursor-pointer ml-2"
                                    :icon="`fa-solid fa-sort-${
                                        contract.sortOrder === 'asc'
                                            ? 'up'
                                            : 'down'
                                    }`"
                                />
                            </th>
                            <th class="pb-4 pt-6 px-6">{{ $t("Action") }}</th>
                        </tr>
                        <tbody>
                            <template
                                v-for="contract in contractTree"
                                :key="contract.id"
                            >
                                <tr>
                                    <td class="border-t">
                                        <div
                                            @click="
                                                contract.expanded =
                                                    !contract.expanded
                                            "
                                            class="flex items-center cursor-pointer"
                                        >
                                            <font-awesome-icon
                                                class="text-xs text-gray-600 mr-2"
                                                :icon="[
                                                    'fa-solid',
                                                    contract.expanded
                                                        ? 'fa-chevron-down'
                                                        : 'fa-chevron-right',
                                                ]"
                                            />
                                            {{ contract.contractNumber }}
                                        </div>
                                    </td>
                                    <td class="border-t">
                                        <a
                                            href="javascript:void(0)"
                                            class="flex items-center"
                                        >
                                            {{ contract.customer?.companyName }}
                                        </a>
                                    </td>
                                    <td class="border-t">
                                        <a
                                            href="javascript:void(0)"
                                            class="flex items-center"
                                        >
                                            {{ contract.contractType?.name }}
                                        </a>
                                    </td>
                                    <td class="border-t">
                                        <a
                                            href="javascript:void(0)"
                                            class="flex items-center"
                                        >
                                            {{
                                                $dateFormatter(
                                                    contract.createdAt,
                                                    globalLanguage
                                                )
                                            }}
                                        </a>
                                    </td>

                                    <td class="w-px">
                                        <router-link
                                            v-if="
                                                $can(
                                                    `${$route.meta.permission}.show`
                                                )
                                            "
                                            class="mx-2"
                                            :to="`/outbounded-contracts/${contract.id}`"
                                        >
                                            <font-awesome-icon
                                                icon="fa-solid fa-eye"
                                            />
                                        </router-link>
                                    </td>
                                    <td class="w-px"></td>
                                </tr>
                                <!-- show the children of contracts when expanded is set to true -->
                                <template
                                    v-if="contract.expanded"
                                    v-for="software in contract.expanded
                                        ? contract.children
                                        : []"
                                    :key="software.id"
                                >
                                    <tr>
                                        <td class="">
                                            <div
                                                class="flex items-center cursor-pointer"
                                                v-if="!!contract.id"
                                                @click="
                                                    software.expanded =
                                                        !software.expanded
                                                "
                                            >
                                                <font-awesome-icon
                                                    class="text-xs text-gray-600 mr-2"
                                                    :icon="[
                                                        'fa-solid',
                                                        software.expanded
                                                            ? 'fa-chevron-down'
                                                            : 'fa-chevron-right',
                                                    ]"
                                                />
                                                {{ software.name }}
                                            </div>
                                        </td>
                                        <td class="border-t">
                                            <a
                                                href="javascript:void(0)"
                                                class="flex items-center px-4 py-4"
                                            >
                                            </a>
                                        </td>
                                        <td class="border-t">
                                            <a
                                                href="javascript:void(0)"
                                                class="flex items-center px-4 py-4"
                                            >
                                            </a>
                                        </td>
                                        <td class="border-t">
                                            <a
                                                href="javascript:void(0)"
                                                class="flex items-center px-4 py-4"
                                            >
                                            </a>
                                        </td>
                                        <td class="border-t">
                                            <a
                                                href="javascript:void(0)"
                                                class="flex items-center px-4 py-4"
                                            >
                                            </a>
                                        </td>
                                        <td class="w-px border-t">
                                            <a
                                                href="javascript:void(0)"
                                                class="flex items-center px-4 py-4"
                                            >
                                            </a>
                                        </td>
                                    </tr>
                                    <tr
                                        v-if="software?.expanded"
                                        class="text-left font-bold"
                                    >
                                        <th class="pb-4 px-4">
                                            {{ $t("Contract Number") }}
                                        </th>
                                        <th class="pb-4 px-4">
                                            {{ $t("Contract Category") }}
                                        </th>
                                        <th class="pb-4 px-4">
                                            {{ $t("Price") }}/{{ $t("Month") }}
                                        </th>
                                        <th class="pb-4 px-4">
                                            {{ $t("Price") }}/{{ $t("Year") }}
                                        </th>
                                        <th class="pb-4 px-4">
                                            {{ $t("Create Date") }}
                                        </th>
                                        <th class="pb-4 px-4">
                                            {{ $t("Sign Date") }}
                                        </th>
                                    </tr>
                                    <!-- show the contract fields when the software expanded is set to true -->
                                    <tr
                                        v-if="software?.expanded"
                                        v-for="attachment in software?.expanded
                                            ? software?.children ?? []
                                            : []"
                                        :key="attachment.id"
                                    >
                                        <td class="border-t pl-8">
                                            <a
                                                href="javascript:void(0)"
                                                class="flex items-center px-4 py-4 pl-6"
                                            >
                                                {{
                                                    attachment.attachmentNumber
                                                }}
                                            </a>
                                        </td>
                                        <td class="border-t">
                                            <a
                                                href="javascript:void(0)"
                                                class="flex items-center px-4 py-4"
                                            >
                                                {{
                                                    attachment.contractCategory
                                                }}
                                            </a>
                                        </td>
                                        <td class="border-t">
                                            <a
                                                v-if="
                                                    !!attachment?.pricePerMonth
                                                "
                                                href="javascript:void(0)"
                                                class="flex items-center px-4 py-4"
                                            >
                                                {{
                                                    $formatter(
                                                        (
                                                            attachment?.pricePerMonth ??
                                                            0
                                                        ).toFixed(2),
                                                        globalLanguage
                                                    )
                                                }}
                                            </a>
                                        </td>
                                        <td class="border-t">
                                            <a
                                                v-if="
                                                    !!attachment?.pricePerMonth
                                                "
                                                href="javascript:void(0)"
                                                class="flex items-center px-4 py-4"
                                            >
                                                {{
                                                    $formatter(
                                                        (
                                                            (attachment?.pricePerMonth ??
                                                                0) * 12
                                                        ).toFixed(2),
                                                        globalLanguage
                                                    )
                                                }}
                                            </a>
                                        </td>
                                        <td class="border-t">
                                            <a
                                                href="javascript:void(0)"
                                                class="flex items-center px-4 py-4"
                                            >
                                                {{
                                                    $dateFormatter(
                                                        attachment.createdAt,
                                                        globalLanguage
                                                    )
                                                }}
                                            </a>
                                        </td>
                                        <td class="w-px border-t">
                                            <a
                                                href="javascript:void(0)"
                                                class="flex items-center px-4 py-4"
                                            >
                                                {{
                                                    $dateFormatter(
                                                        attachment.signedDate,
                                                        globalLanguage
                                                    )
                                                }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr v-if="software?.expanded">
                                        <td class="border-t pl-8">
                                            <a
                                                href="javascript:void(0)"
                                                class="flex items-center px-4 py-4 pl-6 font-bold"
                                            >
                                                {{ $t("Summary") }}:
                                            </a>
                                        </td>
                                        <td class="border-t">
                                            <a
                                                href="javascript:void(0)"
                                                class="flex items-center px-4 py-4"
                                            >
                                            </a>
                                        </td>
                                        <td class="border-t">
                                            <a
                                                href="javascript:void(0)"
                                                class="flex items-center px-4 py-4 font-bold"
                                            >
                                                {{
                                                    $formatter(
                                                        getTotalPricePerMonth(
                                                            software?.children ??
                                                                []
                                                        ).toFixed(2),
                                                        globalLanguage
                                                    )
                                                }}
                                            </a>
                                        </td>
                                        <td class="border-t">
                                            <a
                                                href="javascript:void(0)"
                                                class="flex items-center px-4 py-4 font-bold"
                                            >
                                                {{
                                                    $formatter(
                                                        getTotalPricePerYear(
                                                            software?.children ??
                                                                []
                                                        ).toFixed(2),
                                                        globalLanguage
                                                    )
                                                }}
                                            </a>
                                        </td>
                                        <td class="border-t">
                                            <a
                                                href="javascript:void(0)"
                                                class="flex items-center px-4 py-4"
                                            >
                                            </a>
                                        </td>
                                        <td class="w-px border-t">
                                            <a
                                                href="javascript:void(0)"
                                                class="flex items-center px-4 py-4"
                                            >
                                            </a>
                                        </td>
                                    </tr>
                                </template>
                            </template>
                            <tr v-if="(contracts?.length ?? 0) === 0">
                                <td
                                    class="px-4 py-4 border-t text-center"
                                    colspan="4"
                                >
                                    {{ $t("No outbounded contracts found") }}.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- <div class="mt-4" v-if="!tickets.length">
                    <div class="flex justify-center">
                        <p class="text-center">
                            {{ $t("Tickets not added") }},
                        </p>
                        <router-link class="link" to="/tickets/create"
                            >&nbsp;{{ $t("create here") }}</router-link
                        >
                    </div>
                </div> -->
            </div>
        </div>
    </div>
    <EditModal
        :classSize="'modal-md'"
        :title="$t('Edit Location')"
        v-if="toggleLocationModal"
    >
        <template #body>
            <div class="grid items-center grid-cols-2 gap-6">
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            :required="true"
                            v-model="locationTempData.addressFirst"
                            class=""
                            :label="$t('Address Line 1')"
                            placeholder=" "
                            :floatingLabel="true"
                            :error="errors.addressFirst"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            v-model="locationTempData.addressSecond"
                            class=""
                            :label="$t('Address Line 2')"
                            placeholder=" "
                            :floatingLabel="true"
                            :error="errors.addressSecond"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            :required="true"
                            v-model="locationTempData.city"
                            class=""
                            :label="$t('City')"
                            placeholder=" "
                            :floatingLabel="true"
                            :error="errors.city"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            :required="true"
                            v-model="locationTempData.zip"
                            class=""
                            :label="$t('Zip')"
                            placeholder=" "
                            :floatingLabel="true"
                            :error="errors.zip"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            :required="true"
                            v-model="locationTempData.country"
                            class=""
                            :label="$t('Country')"
                            placeholder=" "
                            :floatingLabel="true"
                            :error="errors.country"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            :required="true"
                            v-model="locationTempData.state"
                            class=""
                            :label="$t('State')"
                            placeholder=" "
                            :floatingLabel="true"
                            :error="errors.state"
                        />
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <button @click="onCancel" type="button" class="primary-btn mr-3">
                {{ $t("Cancel") }}
            </button>
            <loading-button
                :loading="isLoading"
                type="button"
                class="secondary-btn"
                @click="saveLocation"
            >
                {{ $t("Save") }}
            </loading-button>
        </template>
    </EditModal>
    <EditModal :classSize="'modal-md'" :title="$t('Edit Employee')" v-if="toggleEmployeeModal">
        <template #body>
            <div class="grid items-center grid-cols-2 gap-6">
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            v-model="employeeTempData.title"
                            class=""
                            :label="$t('Title')"
                            placeholder=" "
                            :floatingLabel="true"
                            :error="errors.title"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            v-model="employeeTempData.first_name"
                            class=""
                            :label="$t('First Name')"
                            placeholder=" "
                            :floatingLabel="true"
                            :error="errors.first_name"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            v-model="employeeTempData.last_name"
                            class=""
                            :label="$t('Last Name')"
                            placeholder=" "
                            :floatingLabel="true"
                            :error="errors.last_name"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            :isReadonly="
                                typeof employeeTempData?.id === 'string'
                            "
                            :required="true"
                            v-model="employeeTempData.email"
                            class=""
                            :label="$t('Email')"
                            placeholder=" "
                            :floatingLabel="true"
                            :error="errors.email"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            v-model="employeeTempData.mobile"
                            class=""
                            :label="$t('Mobile')"
                            placeholder=" "
                            :floatingLabel="true"
                            :error="errors.mobile"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            v-model="employeeTempData.phone"
                            class=""
                            :label="$t('Phone Number')"
                            placeholder=" "
                            :floatingLabel="true"
                            :error="errors.phone"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            v-model="employeeTempData.fax"
                            class=""
                            :label="$t('Fax')"
                            placeholder=" "
                            :floatingLabel="true"
                            :error="errors.fax"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            v-model="employeeTempData.position"
                            class=""
                            :label="$t('Position')"
                            placeholder=" "
                            :floatingLabel="true"
                            :error="errors.position"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            v-model="employeeTempData.department"
                            class=""
                            :label="$t('Department')"
                            placeholder=" "
                            :floatingLabel="true"
                            :error="errors.department"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <select-input
                            v-model="employeeTempData.location_id"
                            class=""
                            :floatingLabel="true"
                            :error="errors.location_id"
                            :label="$t('Location')"
                        >
                            <option
                                v-for="location in locations ?? []"
                                :key="'location' + location.id"
                                :value="location.id"
                            >
                                {{ location.addressFirst }},
                                {{ location.addressSecond }},
                                {{ location.city }}, {{ location.state }},
                                {{ location.zip }},
                                {{ location.countryName }}
                            </option>
                        </select-input>
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <button @click="onCancel" type="button" class="primary-btn mr-3">
                {{ $t("Cancel") }}
            </button>
            <loading-button
                :loading="isLoading"
                type="button"
                class="secondary-btn"
                @click="saveEmployee"
            >
                {{ $t("Save") }}
            </loading-button>
            
        </template>
    </EditModal>
</template>

<script>
import TextInput from "../../Components/TextInput.vue";
import SaleOffer from "../../Components/Offer.vue";
import SelectInput from "../../Components/SelectInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import EditModal from "../../Components/EditModal.vue";
import Pagination from "laravel-vue-pagination";
import CustomPagination from "../../Components/Pagination.vue";
import Licenses from "./Licenses.vue";
import { mapGetters } from "vuex";
import { computed } from "vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("termsOfPayment", {
            termsOfPayment: "termsOfPayment",
        }),
        ...mapGetters("companyEmployees", {
            employees: "users",
            count: "count",
        }),
        sortedHosting() {
            return this.hostingSystems.slice().sort((a, b) => {
                const fieldA = a[this.sortFieldHosting];
                const fieldB = b[this.sortFieldHosting];

                if (
                    String("000000000000" + fieldA).slice(-12) <
                    String("000000000000" + fieldB).slice(-12)
                ) {
                    return -1 * this.sortOrderHosting;
                } else if (
                    String("000000000000" + fieldA).slice(-12) >
                    String("000000000000" + fieldB).slice(-12)
                ) {
                    return 1 * this.sortOrderHosting;
                } else {
                    return 0;
                }
            });
        },
        sortedCloud() {
            return this.cloudSystems.slice().sort((a, b) => {
                const fieldA = a[this.sortFieldCloud];
                const fieldB = b[this.sortFieldCloud];

                if (
                    String("000000000000" + fieldA).slice(-12) <
                    String("000000000000" + fieldB).slice(-12)
                ) {
                    return -1 * this.sortOrderCloud;
                } else if (
                    String("000000000000" + fieldA).slice(-12) >
                    String("000000000000" + fieldB).slice(-12)
                ) {
                    return 1 * this.sortOrderCloud;
                } else {
                    return 0;
                }
            });
        },
    },
    async mounted() {
        this.refresh();
        if (this.$route.query.page) {
            this.returnPage = this.$route.query.page;
        }
    },
    components: {
        SaleOffer,
        LoadingButton,
        SelectInput,
        TextInput,
        Pagination,
        CustomPagination,
        EditModal,
        Licenses,
        PageHeader,
    },
    watch: {
        contracts(val) {
            this.contractTree = val; //set the contractTree array on change
            this.groupContracts();
        },
        "onPremise.sortBy"(...val) {
            // Handle the change in the type property
            this.onPremiseDetails();
        },
        "onPremise.sortOrder"(...val) {
            // Handle the change in the type property
            this.onPremiseDetails();
        },
        "ticket.sortBy"(...val) {
            // Handle the change in the type property
            this.ticketDetails();
        },
        "ticket.sortOrder"(...val) {
            // Handle the change in the type property
            this.ticketDetails();
        },
        "project.sortBy"(...val) {
            // Handle the change in the type property
            this.projectDetails();
        },
        "project.sortOrder"(...val) {
            // Handle the change in the type property
            this.projectDetails();
        },
        "contract.sortBy"(...val) {
            // Handle the change in the type property
            this.contractDetails();
        },
        "contract.sortOrder"(...val) {
            // Handle the change in the type property
            this.contractDetails();
        },
        "invoice.sortBy"(...val) {
            // Handle the change in the type property
            this.invoiceDetails();
        },
        "invoice.sortOrder"(...val) {
            // Handle the change in the type property
            this.invoiceDetails();
        },
        "contactReport.sortBy"(...val) {
            // Handle the change in the type property
            this.contactReportDetails();
        },
        "contactReport.sortOrder"(...val) {
            // Handle the change in the type property
            this.contactReportDetails();
        },
        "offer.sortBy"(...val) {
            // Handle the change in the type property
            this.offerDetails();
        },
        "offer.sortOrder"(...val) {
            // Handle the change in the type property
            this.offerDetails();
        },
        "order.sortBy"(...val) {
            // Handle the change in the type property
            this.orderDetails();
        },
        "order.sortOrder"(...val) {
            // Handle the change in the type property
            this.orderDetails();
        },
    },
    data() {
        return {
            sortFieldCloud: "",
            sortOrderCloud: 1, // 1 for ascending, -1 for descending
            sortFieldHosting: "",
            sortOrderHosting: 1, // 1 for ascending, -1 for descending
            onPremise: {
                sortBy: "",
                sortOrder: "",
            },
            ticket: {
                sortBy: "",
                sortOrder: "",
            },
            project: {
                sortBy: "",
                sortOrder: "",
            },
            contract: {
                sortBy: "",
                sortOrder: "",
            },
            invoice: {
                sortBy: "",
                sortOrder: "",
            },
            contactReport: {
                sortBy: "",
                sortOrder: "",
            },
            offer: {
                sortBy: "",
                sortOrder: "",
            },
            order: {
                sortBy: "",
                sortOrder: "",
            },
            services: [],
            returnPage: "",
            currentPage: 1,
            start: 0,
            perPage: 25,
            employeeName: {},
            locations: [],
            premiseSystems: [],
            cloudSystems: [],
            hostingSystems: [],
            offers: [],
            orderConfirmation: [],
            invoices: [],
            projects: [],
            reports: [],
            tickets: [],
            contracts: [],
            contractTree: [], // keeps the modifled true structured outbounded contracts

            company: {},
            // activeClasses:
            //     "cursor-pointer inline-flex items-center justify-center w-1/2 py-3 font-medium leading-none tracking-wider text-indigo-500 bg-gray-100 border-b-2 border-indigo-500 rounded-t sm:px-6 sm:w-auto sm:justify-start title-font",
            // inactiveClasses:
            //     "cursor-pointer inline-flex items-center justify-center w-1/2 py-3 font-medium leading-none tracking-wider border-b-2 border-gray-200 sm:px-6 sm:w-auto sm:justify-start title-font hover:text-gray-900",
            activeTab: "company",
            activeClasses: "active",
            inactiveClasses: "inactive",
            form: {
                companyName: "",
                companyNumber: "",
                vatId: "",
                url: "",
                phone: "",
                fax: "",
                type: "",
                status: "",
                termsOfPayment: "",
                invoiceEmail: "",
                addressFirst: "",
                addressSecond: "",
                city: "",
                zip: "",
                state: "",
                country: "",
                defaultServiceDailyRate: "",
                defaultServiceHourlyRate: "",
                defaultServiceProduct: "",
                discount: 0,
                hourlyRate: 0,
                isCustomerPortalOnboarding: "No",
            },
            locationTempData: {}, //used for v-model in location edit modal
            toggleLocationModal: false,
            employeeTempData: {}, //used for v-model in employee edit modal
            toggleEmployeeModal: false,
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Partner Management"),
                    to: "/partner-management/companies",
                },
                {
                    text: this.$t("Customer"),
                    to: "/partner-management/companies?page=" + this.$route.query.page,
                },
                {
                    text: "Show",
                    active: true,
                },
            ],
        };
    },
    methods: {
        sortByCloud(field) {
            if (this.sortFieldCloud === field) {
                // If already sorting by this field, reverse the order
                this.sortOrderCloud *= -1;
            } else {
                // If sorting by a new field, set the field and default to ascending order
                this.sortFieldCloud = field;
                this.sortOrderCloud = 1;
            }
        },
        sortByHosting(field) {
            if (this.sortFieldHosting === field) {
                // If already sorting by this field, reverse the order
                this.sortOrderHosting *= -1;
            } else {
                // If sorting by a new field, set the field and default to ascending order
                this.sortFieldHosting = field;
                this.sortOrderHosting = 1;
            }
        },
        async onPremiseDetails() {
            const systemsResponse = await this.$store.dispatch(
                "companies/systemsByCompany",
                {
                    id: this.$route.params.id,
                    sortBy: this.onPremise.sortBy,
                    sortOrder: this.onPremise.sortOrder,
                }
            );
            this.premiseSystems = systemsResponse?.data?.premise ?? [];
        },
        async invoiceDetails() {
            const invoicesResponse = await this.$store.dispatch(
                "companies/invoicesByCompany",
                {
                    id: this.$route.params.id,
                    sortBy: this.invoice.sortBy,
                    sortOrder: this.invoice.sortOrder,
                }
            );
            this.invoices = invoicesResponse?.data?.data ?? [];
        },
        async ticketDetails() {
            const ticketResponse = await this.$store.dispatch(
                "companies/ticketsByCompany",
                {
                    id: this.$route.params.id,
                    sortBy: this.ticket.sortBy,
                    sortOrder: this.ticket.sortOrder,
                }
            );
            this.tickets = ticketResponse?.data?.data ?? [];
        },
        async contactReportDetails() {
            const reportsResponse = await this.$store.dispatch(
                "companies/reportsByCompany",
                {
                    id: this.$route.params.id,
                    sortBy: this.contactReport.sortBy,
                    sortOrder: this.contactReport.sortOrder,
                }
            );
            this.reports = reportsResponse?.data?.data ?? [];
        },
        async projectDetails() {
            const projectsResponse = await this.$store.dispatch(
                "companies/projectsByCompany",
                {
                    id: this.$route.params.id,
                    sortBy: this.project.sortBy,
                    sortOrder: this.project.sortOrder,
                }
            );
            this.projects = projectsResponse?.data?.data ?? [];
        },
        async offerDetails() {
            const offersResponse = await this.$store.dispatch(
                "offers/getOffersByCustomer",
                {
                    id: this.$route.params.id,
                    sortBy: this.offer.sortBy,
                    sortOrder: this.offer.sortOrder,
                }
            );
            this.offers = offersResponse?.data?.data ?? [];
        },
        async orderDetails() {
            const orderConfirmResponse = await this.$store.dispatch(
                "orderConfirmation/getOrderConfirmationByCustomer",
                {
                    id: this.$route.params.id,
                    offerType: "order",
                    sortBy: this.order.sortBy,
                    sortOrder: this.order.sortOrder,
                }
            );
            this.orderConfirmation = orderConfirmResponse?.data?.data ?? [];
        },
        async contractDetails() {
            const contractsResponse = await this.$store.dispatch(
                "companies/contractsByCompany",
                {
                    id: this.$route.params.id,
                    sortBy: this.contract.sortBy,
                    sortOrder: this.contract.sortOrder,
                }
            );
            this.contracts = contractsResponse?.data?.data ?? [];
        },
        customerLink(incommingUrl) {
            if (incommingUrl) {
                let url = "";
                url = incommingUrl.trim();
                if (!/^https?:\/\//i.test(url)) {
                    url = `https://${url}`;
                }
                return url;
            }
            return incommingUrl;
        },
        groupContracts() {
            // map the outboundedContracts to group the contract fields by attachment software
            const contracts = this.contractTree.map((contract) => {
                const softwares = [];
                // loop through the attachments
                contract.attachments.forEach((attachment) => {
                    // add software to softwares object if not already present
                    if (
                        !softwares.some(
                            (software) => software.id === attachment.software.id
                        )
                    ) {
                        softwares.push({
                            ...attachment.software,
                            expanded: false,
                            children: [],
                        });
                    }
                    const softwareIndex = softwares.findIndex(
                        (software) => software.id === attachment.software.id
                    );
                    // map the contract fields based on software
                    softwares[softwareIndex].children = [
                        ...softwares[softwareIndex].children,
                        {
                            ...attachment,
                            contractCategory: `Attachment ${attachment.name}`,
                            expanded: false,
                        },
                    ];
                });
                return {
                    ...contract,
                    expanded: false,
                    children: softwares,
                };
            });
            this.contractTree = [...contracts]; // set the contractTree
        },
        getTotalPricePerYear(attachments) {
            return attachments.reduce((acc, curr) => {
                return acc + +(curr?.pricePerMonth ?? 0) * 12;
            }, 0);
        },
        /**
         * calculates sum of price per month of all the attachments
         * @param {attachments} attachments
         */
        getTotalPricePerMonth(attachments) {
            return attachments.reduce((acc, curr) => {
                return acc + +curr.pricePerMonth;
            }, 0);
        },
        async refresh() {
            this.$store.commit("showLoader", true);
            try {
                const response = await this.$store.dispatch(
                    "companies/showPartnerCompany",
                    this.$route.params.id
                );
                this.company = response?.data?.modelData ?? {};
                const productResponse = await this.$store.dispatch(
                    "products/productsWithQuantity",
                    {
                        perPage: 25,
                        page: 1,
                        type: "service",
                    }
                );
                this.services = productResponse?.data?.products ?? [];
                await this.$store.dispatch("termsOfPayment/list", {
                    perPage: 25,
                    page: 1,
                });
                await this.$store.dispatch("companyEmployees/list", {
                    limit_start: this.start,
                    limit_count: this.perPage,
                    type: "customer",
                    company_id: this.$route.params.id,
                });
                const systemsResponse = await this.$store.dispatch(
                    "companies/systemsByCompany",
                    {
                        id: this.$route.params.id,
                    }
                );
                const invoicesResponse = await this.$store.dispatch(
                    "companies/invoicesByCompany",
                    {
                        id: this.$route.params.id,
                    }
                );
                const reportsResponse = await this.$store.dispatch(
                    "companies/reportsByCompany",
                    {
                        id: this.$route.params.id,
                    }
                );
                const projectsResponse = await this.$store.dispatch(
                    "companies/projectsByCompany",
                    {
                        id: this.$route.params.id,
                    }
                );
                const ticketResponse = await this.$store.dispatch(
                    "companies/ticketsByCompany",
                    {
                        id: this.$route.params.id,
                    }
                );
                const contractsResponse = await this.$store.dispatch(
                    "companies/contractsByCompany",
                    {
                        id: this.$route.params.id,
                    }
                );
                const offersResponse = await this.$store.dispatch(
                    "offers/getOffersByCustomer",
                    {
                        id: this.$route.params.id,
                    }
                );
                const orderConfirmResponse = await this.$store.dispatch(
                    "orderConfirmation/getOrderConfirmationByCustomer",
                    {
                        id: this.$route.params.id,
                        offerType: "order",
                    }
                );
                await this.fetchLocations();
                await this.fetchEmployees(this.start, this.perPage);
                this.premiseSystems = systemsResponse?.data?.premise ?? [];
                this.cloudSystems = systemsResponse?.data?.cloud ?? [];
                this.hostingSystems = systemsResponse?.data?.hosting ?? [];
                this.reports = reportsResponse?.data?.data ?? [];
                this.invoices = invoicesResponse?.data?.data ?? [];
                this.projects = projectsResponse?.data?.data ?? [];
                this.offers = offersResponse?.data?.data ?? [];
                this.orderConfirmation = orderConfirmResponse?.data?.data ?? [];
                this.tickets = ticketResponse?.data?.data ?? [];
                this.contracts = contractsResponse?.data?.data ?? [];

                let companyFormData = {
                    id: this.company.id,
                    companyName: this.company.companyName,
                    companyNumber: this.company.companyNumber,
                    vatId: this.company.vatId,
                    url: this.company.url,
                    phone: this.company.phone,
                    fax: this.company.fax,
                    type: this.company.type,
                    termsOfPayment: "",
                    invoiceEmail: this.company.invoiceEmail,
                    bankDetails: this.company.bankDetails,
                    defaultServiceProduct: this.company.defaultServiceProduct,
                    defaultServiceHourlyRate:
                        this.company.defaultServiceHourlyRate,
                    defaultServiceDailyRate:
                        this.company.defaultServiceDailyRate,
                    discount: this.company.discount,
                    isCustomerPortalOnboarding: !!this.company
                        .isCustomerPortalOnboarding
                        ? "Yes"
                        : "No",
                };
                companyFormData = {
                    ...companyFormData,
                    ...(this.locations.find((location) => {
                        return location.isHeadQuarters == 1;
                    }) ?? {}),
                };
                this.form = { ...companyFormData };
                this.form.defaultServiceProduct = this.services.data.find(
                    (service) =>
                        service.id == this.company.defaultServiceProduct
                );

                this.form.hourlyRate =
                    this.form.defaultServiceProduct?.hourlyRate ?? 0;
                this.form.termsOfPayment =
                    this.termsOfPayment.data.find(
                        (terms) => terms.id === this.company.termsOfPayment
                    ) ?? {};
            } catch (e) {
            } finally {
                this.$store.commit("showLoader", false);
            }
        },
        async fetchEmployees(start, end) {
            return new Promise(async (resolve, reject) => {
                try {
                    await this.$store.dispatch("companyEmployees/list", {
                        limit_start: start,
                        limit_count: end,
                        type: "customer",
                        company_id: this.$route.params.id,
                    });
                    resolve();
                } catch (e) {
                    reject(e);
                }
            });
        },
        async fetchLocations() {
            return new Promise(async (resolve, reject) => {
                try {
                    const response = await this.$store.dispatch(
                        "companies/locationsList",
                        this.$route.params.id
                    );
                    this.locations = response?.data?.locations ?? [];
                    resolve();
                } catch (e) {
                    reject(e);
                }
            });
        },
        openEditEmployeeModal(index) {
            this.employeeTempData = { ...this.employees[index] };
            this.toggleEmployeeModal = true;
        },
        openEditLocationModal(index) {
            this.locationTempData = { ...this.locations[index] };
            this.toggleLocationModal = true;
        },
        onCancel() {
            this.toggleLocationModal = false;
            this.toggleEmployeeModal = false;
        },
        async saveLocation() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("companies/locationsUpdate", {
                    id: this.locationTempData.id,
                    data: {
                        ...this.locationTempData,
                        companyId: this.$route.params.id,
                    },
                });
                this.fetchLocations();
                this.resetModalData();
            } catch (e) {}
        },
        async saveEmployee() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("auth/update", {
                    id: this.employeeTempData.id,
                    ...this.employeeTempData,
                    types: ["customer"],
                });
                this.fetchEmployees(this.start, this.perPage);
                this.resetModalData();
            } catch (e) {}
        },
        resetModalData() {
            this.employeeTempData = {};
            this.toggleEmployeeModal = false;
            this.locationTempData = {};
            this.toggleLocationModal = false;
        },
        async removeLocation(id) {
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
                    await this.$store.dispatch("skills/destroy", id);
                    await this.refresh(true);
                    await this.$store.dispatch("companies/locationsDelete", id);
                    this.fetchLocations();
                }
            });
        },
        async removeEmployee(id) {
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
                    await this.$store.dispatch("auth/destroy", {
                        id: id,
                    });
                    this.fetchEmployees(this.start, this.perPage);
                }
            });
        },
    },
};
</script>

<style scoped>
.table-layout-fixed {
    table-layout: fixed;
}
.link {
    text-decoration: underline;
    color: blue;
}
.employee-list {
    list-style-type: circle;
    margin-left: 2rem;
}
</style>

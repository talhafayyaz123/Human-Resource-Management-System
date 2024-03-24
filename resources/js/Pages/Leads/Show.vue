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
                        {{ $t("Lead") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        @click="activeTab = 'locations'"
                        :class="
                            activeTab === 'locations'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("Locations") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        @click="activeTab = 'employees'"
                        :class="
                            activeTab === 'employees'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("Employees") }}
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
                        @click="activeTab = 'contact-reports'"
                        :class="
                            activeTab === 'contact-reports'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("Contact Reports") }}
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
                            :to="'/leads/' + company.id + '/edit?page='+returnPage"
                        >
                            <span>{{ $t("Edit") }}</span>
                        </router-link>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $t("Supplier Detail") }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="flex flex-wrap">
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Lead Number") }}:</label
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
                                            form.url ? customerLink(form.url) : '#'
                                        "
                                        :target="
                                            customerLink(form.url) ? '_blank' : null
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
                                        >{{ $t("Status") }}:</label
                                    >
                                    <p>{{ form.status.name ?? "-" }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Notes") }}:</label
                                    >
                                    <p>{{ form.notes }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Size") }}:</label
                                    >
                                    <p>{{ form.size }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Order Probability") }}:</label
                                    >
                                    <p>{{ form.orderProbability }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card my-5">
                        <div class="card-header">
                            <h3 class="card-title">{{ $t("Add Assignees") }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="grid items-center grid-cols-2 gap-6" v-if="form.assignees.length > 0">
                                <div class="w-full">
                                    <div class="form-group">
                                        <MultiSelectInput
                                            v-model="form.assignees"
                                            :multiple="true"
                                            :isDisabled="true"
                                            :textLabel="$t('Assignees')"
                                            :customLabel="customLabel"
                                            label="first_name"
                                            :trackBy="'id'"
                                            :options="users"
                                            moduleName="auth"
                                            :search-param-name="'search_string'"
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
                                                <p>
                                                    {{ props.option.first_name }}
                                                    {{ props.option.last_name }}
                                                </p>
                                            </template>
                                            <template #option="{ props }">
                                                <div
                                                    class="grid"
                                                    style="
                                                        grid-template-columns: 25% 25% 50%;
                                                    "
                                                >
                                                    <p class="overflow-text-users-table">
                                                        {{ props.option.first_name }}
                                                    </p>
                                                    <p class="overflow-text-users-table">
                                                        {{ props.option.last_name }}
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
                            <div v-else class="flex flex-wrap">
                                {{ $t("No Assignee Exists") }}
                            </div>
                        </div>
                    </div>
                    <div class="card my-5">
                        <div class="card-header">
                            <h3 class="card-title">{{ $t("Add Contact Report Sources") }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="grid items-center grid-cols-2 gap-6" v-if="form.contactSources">
                                <div class="w-full">
                                    <div class="form-group">
                                        <MultiSelectInput
                                            :isDisabled="true"
                                            :textLabel="`Contact Report Source`"
                                            v-model="form.contactSources"
                                            :options="contactSources?.data"
                                            :multiple="true"
                                            label="name"
                                            trackBy="id"
                                            moduleName="sources"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card my-5">
                        <div class="card-header">
                            <h3 class="card-title">{{ $t("Payment Details") }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="flex flex-wrap">
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
                            </div>
                        </div>
                    </div>
                    <div class="card my-5" v-if="form.bankDetails?.length > 0">
                        <div class="card-header">
                            <h3 class="card-title">{{ $t("Bank Details") }}</h3>
                        </div>
                        <div class="card-body">
                            <div
                                v-for="(bankDetail, index) in form.bankDetails"
                                :key="index"
                                class="flex flex-wrap"
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
                </form>
            </div>
            <div
                v-else-if="activeTab === 'locations'"
                class="p-4"
                id="settings"
                role="tabpanel"
                aria-labelledby="settings-tab"
            >
                <form
                    v-for="(location, index) in locations ?? []"
                    :key="'location-' + index"
                >
                    <div class="card my-5">
                        <div class="card-header flex justify-between">
                            <h3 class="card-title">{{ $t("Location") }} {{ index + 1 }}</h3>
                            <router-link
                                class="px-4 self-center"
                                :to="
                                    location.isHeadQuarters
                                        ? `/leads/${company.id}/edit?page=${returnPage}`
                                        : `/leads/${company.id}/edit?tab=locations&action=edit&id=${location.id}&page=${returnPage}`
                                "
                            >
                                <font-awesome-icon icon="fa-solid fa-eye"/>
                            </router-link>
                        </div>
                        <div class="card-body">
                            <div class="flex flex-wrap">
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Address First") }}:</label
                                    >
                                    <p>{{ location.addressFirst }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Address Second") }}:</label
                                    >
                                    <p>{{ location.addressSecond }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("City") }}:</label
                                    >
                                    <p>{{ location.city }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("ZIP") }}:</label
                                    >
                                    <p>{{ location.zip }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Country") }}:</label
                                    >
                                    <p>{{ location.countryName }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("State") }}:</label
                                    >
                                    <p>{{ location.state }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div
                v-else-if="activeTab === 'employees'"
                class="p-4"
                id="dashboard"
                role="tabpanel"
                aria-labelledby="dashboard-tab"
            >
                <form
                    v-for="(employee, index) in employees ?? []"
                    :key="'employee-' + index"
                >
                    <div class="card">
                        <div class="card-header flex justify-between">
                            <h3 class="card-title">{{ $t("Employee") }} {{ index + 1 }}</h3>
                            <router-link
                                class="px-4 self-center"
                                :to="`/leads/${company.id}/edit?tab=employees&action=edit&id=${employee.id}&page=${returnPage}`"
                            >
                                <font-awesome-icon icon="fa-solid fa-eye"/>
                            </router-link>
                        </div>
                        <div class="card-body">
                            <div class="flex flex-wrap">
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Title") }}:</label
                                    >
                                    <p>{{ employee.title }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("First Name") }}:</label
                                    >
                                    <p>{{ employee.first_name }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Last Name") }}:</label
                                    >
                                    <p>{{ employee.last_name }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Email") }}:</label
                                    >
                                    <p>{{ employee.email }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Mobile") }}:</label
                                    >
                                    <p>{{ employee.mobile }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Fax") }}:</label
                                    >
                                    <p>{{ employee.fax }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Phone") }}:</label
                                    >
                                    <p>{{ employee.phone }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Position") }}:</label
                                    >
                                    <p>{{ employee.position }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Department") }}:</label
                                    >
                                    <p>{{ employee.department }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Location") }}:</label
                                    >
                                    <p>
                                        {{
                                            getLocationString(employee.location_id)
                                        }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div v-if="!employees.length">
                    <div class="flex justify-center">
                        <p class="text-center">
                            {{ $t("Employees not added") }},
                        </p>
                        <router-link
                            class="link"
                            :to="
                                '/leads/' +
                                company.id +
                                '/edit?tab=employees&action=add&page='+returnPage
                            "
                            >&nbsp;{{ $t("create here") }}</router-link
                        >
                    </div>
                </div>
            </div>
            <div
                v-if="activeTab === 'systems'"
                class="p-4"
                id="profile"
                role="tabpanel"
                aria-labelledby="profile-tab"
            >
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $t("On Premise Systems") }}</h3>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="w-full doc-table">
                            <tr class="text-left font-bold">
                                <th
                                    @click="sort('systemNumber', 'premise', 'onPremise')"
                                    class="cursor-pointer"
                                >
                                    {{ $t("System Number")
                                    }}<font-awesome-icon
                                        v-if="onPremise.sortBy === 'systemNumber'"
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            onPremise.sortOrder === 'asc' ? 'up' : 'down'
                                        }`"
                                    />
                                </th>
                                <th
                                    @click="sort('systemName', 'premise', 'onPremise')"
                                    class="cursor-pointer"
                                >
                                    {{ $t("System Name")
                                    }}<font-awesome-icon
                                        v-if="onPremise.sortBy === 'systemName'"
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            onPremise.sortOrder === 'asc' ? 'up' : 'down'
                                        }`"
                                    />
                                </th>
                                <th
                                    @click="sort('Company.companyName', 'premise', 'onPremise')"
                                    class="cursor-pointer"
                                >
                                    {{ $t("Customer")
                                    }}<font-awesome-icon
                                        v-if="onPremise.sortBy === 'Company.companyName'"
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            onPremise.sortOrder === 'asc' ? 'up' : 'down'
                                        }`"
                                    />
                                </th>
                                <th
                                    @click="sort('serverIp', 'premise', 'onPremise')"
                                    class="cursor-pointer"
                                >
                                    {{ $t("Server IP")
                                    }}<font-awesome-icon
                                        v-if="onPremise.sortBy === 'serverIp'"
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            onPremise.sortOrder === 'asc' ? 'up' : 'down'
                                        }`"
                                    />
                                </th>
                                <th
                                    @click="sort('instanceType', 'premise', 'onPremise')"
                                    class="cursor-pointer"
                                >
                                    {{ $t("instance Type")
                                    }}<font-awesome-icon
                                        v-if="onPremise.sortBy === 'instanceType'"
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            onPremise.sortOrder === 'asc' ? 'up' : 'down'
                                        }`"
                                    />
                                </th>
                                <th
                                    @click="sort('operatingSystem.name', 'premise', 'onPremise')"
                                    class="cursor-pointer"
                                >
                                    {{ $t("Operating System")
                                    }}<font-awesome-icon
                                        v-if="onPremise.sortBy === 'operatingSystem.name'"
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            onPremise.sortOrder === 'asc' ? 'up' : 'down'
                                        }`"
                                    />
                                </th>
                                <th class="pb-4 pt-6 px-6">{{ $t("Action") }}</th>
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
                                        <font-awesome-icon icon="fa-regular fa-pen-to-square"/>
                                    </router-link>
                                </td>
                            </tr>
                            <tr
                                class="flex justify-center px-4 py-4"
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
                                <th class="pb-4 pt-6 px-6">{{ $t("Action") }}</th>
                            </tr>
                            <template
                                v-for="system in sortedCloud"
                                :key="system.id"
                            >
                                <tr
                                    @click="system.isDropdown = !system.isDropdown"
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
                <div class="card my-5">
                    <div class="card-header">
                        <h3 class="card-title">{{ $t("Hosting Systems") }}</h3>
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
                                        v-if="sortFieldHosting === 'systemNumber'"
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            sortOrderHosting === 1 ? 'up' : 'down'
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
                                            sortOrderHosting === 1 ? 'up' : 'down'
                                        }`"
                                    />
                                </th>
                                <th
                                    @click="sortByHosting('serverIp')"
                                    class="pb-4 pt-6 px-6 cursor-pointer"
                                >
                                    {{ $t("Host") }}
                                    <font-awesome-icon
                                        v-if="sortFieldHosting === 'serverIp'"
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            sortOrderHosting === 1 ? 'up' : 'down'
                                        }`"
                                    />
                                </th>
                                <th
                                    @click="sortByHosting('virtualMachine')"
                                    class="pb-4 pt-6 px-6 cursor-pointer"
                                >
                                    {{ $t("Virtual Machine Name") }}
                                    <font-awesome-icon
                                        v-if="sortFieldHosting === 'virtualMachine'"
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            sortOrderHosting === 1 ? 'up' : 'down'
                                        }`"
                                    />
                                </th>
                                <th
                                    @click="sortByHosting('instanceType')"
                                    class="pb-4 pt-6 px-6 cursor-pointer"
                                >
                                    {{ $t("Instance Type") }}
                                    <font-awesome-icon
                                        v-if="sortFieldHosting === 'instanceType'"
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            sortOrderHosting === 1 ? 'up' : 'down'
                                        }`"
                                    />
                                </th>
                                <th
                                    @click="sortByHosting('operatingSystem')"
                                    class="pb-4 pt-6 px-6 cursor-pointer"
                                >
                                    {{ $t("Operating System") }}
                                    <font-awesome-icon
                                        v-if="sortFieldHosting === 'operatingSystem'"
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            sortOrderHosting === 1 ? 'up' : 'down'
                                        }`"
                                    />
                                </th>
                                <th class="pb-4 pt-6 px-6">{{ $t("Action") }}</th>
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
                                            {{ system.serverIp }}
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
                                        <font-awesome-icon icon="fa-regular fa-pen-to-square"/>
                                    </router-link>
                                </td>
                            </tr>
                            <tr
                                class="flex px-6 py-2"
                                v-if="hostingSystems.length === 0"
                            >
                                <p class="text-center">
                                    {{ $t("Hosting Systems not added") }},
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
                v-if="activeTab === 'contact-reports'"
                class="p-4"
                id="profile"
                role="tabpanel"
                aria-labelledby="profile-tab"
            >
                <ContactReports />
            </div>
        </div>
    </div>
</template>

<script>
import SaleOffer from "../../Components/Offer.vue";
import TextInput from "../../Components/TextInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import EditModal from "../../Components/EditModal.vue";
import Pagination from "../../Components/Pagination.vue";
import ContactReports from "./Components/ContactReports.vue";
import { mapGetters } from "vuex";
import Quill from "quill";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";

export default {
    computed: {
        ...mapGetters("termsOfPayment", {
            termsOfPayment: "termsOfPayment",
        }),
        ...mapGetters("companyEmployees", {
            employees: "users",
            count: "count",
        }),
        ...mapGetters("auth", {
            users: "users",
        }),
        ...mapGetters("contactSource", ["contactSources"]),
        sortedHosting() {
            return this.hostingSystems.slice().sort((a, b) => {
                const fieldA = a[this.sortFieldHosting];
                const fieldB = b[this.sortFieldHosting];

                if ( String("000000000000" + fieldA).slice(-12)  <  String("000000000000" + fieldB).slice(-12) ) {
                    return -1 * this.sortOrderHosting;
                } else if (String("000000000000" + fieldA).slice(-12)  >  String("000000000000" + fieldB).slice(-12) ) {
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

                if ( String("000000000000" + fieldA).slice(-12)  <  String("000000000000" + fieldB).slice(-12) ) {
                    return -1 * this.sortOrderCloud;
                } else if (String("000000000000" + fieldA).slice(-12)  >  String("000000000000" + fieldB).slice(-12) ) {
                    return 1 * this.sortOrderCloud;
                } else {
                    return 0;
                }


            });
        },
    },
    mounted() {
        // if the activeTab query param is set, check if the param is valid, if yes then set to activeTab
        if (
            ["company", "locations", "employees", "contact-reports"].includes(
                this.$route.query.activeTab
            )
        ) {
            this.activeTab = this.$route.query.activeTab;
        }
        this.refresh();
    },
    components: {
        SaleOffer,
        LoadingButton,
        SelectInput,
        TextInput,
        Pagination,
        EditModal,
        MultiSelectInput,
        ContactReports,
        PageHeader
    },
    data() {
        return {
            sortFieldCloud: "",
            sortOrderCloud: 1, // 1 for ascending, -1 for descending
            sortFieldHosting: "",
            sortOrderHosting: 1, // 1 for ascending, -1 for descending
            
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Sales",
                    to: "/leads",
                },
                {
                    text: "Leads",
                    to: "/leads?page="+ this.$route.query.page,
                },
                {
                    text: this.$t("Show"),
                    active: true,
                },
            ],
            onPremise: {
                sortBy: "",
                sortOrder: "",
            },
            currentPage: 1,
            returnPage:'',
            start: 0,
            perPage: 25,
            employeeName: {},
            locations: [],
            company: {},
            activeClasses: "active",
            inactiveClasses: "inactive",
            activeTab: "company",
            form: {
                companyName: "",
                companyNumber: "",
                vatId: "",
                url: "",
                phone: "",
                fax: "",
                type: "",
                termsOfPayment: "",
                invoiceEmail: "",
                addressFirst: "",
                addressSecond: "",
                city: "",
                zip: "",
                state: "",
                country: "",
                assignees: [],
                status: "open",
                notes: "",
                contactSources: "",
            },
            premiseSystems: [],
            cloudSystems: [],
            hostingSystems: [],
        };
    },
    watch: {
        'onPremise.sortBy'(...val) {
            // Handle the change in the type property
            this.onPremiseDetails()

        },
        'onPremise.sortOrder'(...val) {
            // Handle the change in the type property
            this.onPremiseDetails()
        }
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
                "companies/systemsByCompany", {
                    id: this.$route.params.id,
                    sortBy: this.onPremise.sortBy,
                    sortOrder: this.onPremise.sortOrder
                }
            );
            this.premiseSystems = systemsResponse?.data?.premise ?? []; 
        },
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
        async refresh() {
            if(this.$route.query.page){
             this.returnPage=this.$route.query.page; 
            }
            try {
                await this.$store.dispatch("auth/list", {
                    limit_start: 0,
                    limit_count: 25,
                    type: "employee",
                });
                const response = await this.$store.dispatch(
                    "companies/show",
                    this.$route.params.id
                );
                this.company = response?.data?.modelData ?? {};
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
                const locationsResponse = await this.$store.dispatch(
                    "companies/locationsList",
                    this.$route.params.id
                );
                const systemsResponse = await this.$store.dispatch(
                    "companies/systemsByCompany", {
                        id: this.$route.params.id
                    }
                );
                await this.$store.dispatch("contactSource/list", {
                    page: this.page,
                    search: this.form.search,
                });
                this.premiseSystems = systemsResponse?.data?.premise ?? [];
                this.cloudSystems = systemsResponse?.data?.cloud ?? [];
                this.hostingSystems = systemsResponse?.data?.hosting ?? [];
                this.locations = locationsResponse?.data?.locations ?? [];
                let companyFormData = {
                    id: this.company.id,
                    companyNumber: this.company.companyNumber,
                    companyName: this.company.companyName,
                    vatId: this.company.vatId,
                    url: this.company.url,
                    phone: this.company.phone,
                    fax: this.company.fax,
                    type: this.company.type,
                    termsOfPayment: "",
                    invoiceEmail: this.company.invoiceEmail,
                    bankDetails: this.company.bankDetails,
                    status: this.company.status,
                    notes: this.company.notes,
                    size: this.company.size,
                    orderProbability: this.company.orderProbability,
                    assignees:
                        this.users?.filter((user) =>
                            this.company?.assignees?.some(
                                (assignee) => assignee.id == user.id
                            )
                        ) ?? [],
                    contactSources: this.company.contactSources,
                };
                companyFormData = {
                    ...companyFormData,
                    ...(this.locations.find((location) => {
                        return location.isHeadQuarters == 1;
                    }) ?? {}),
                };
                this.form = { ...companyFormData };
                this.form.termsOfPayment =
                    this.termsOfPayment.data.find(
                        (terms) => terms.id === this.company.termsOfPayment
                    ) ?? {};
                for (let i = 0; i < this.projects.length; i++) {
                    const response = await this.$store.dispatch("auth/show", {
                        id: this.projects[i].employee,
                    });
                    this.employeeName[this.projects[i].id] =
                        response?.data?.first_name;
                }
            } catch (e) {}
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
        htmlFromText(text) {
            try {
                let article = document.createElement("article");
                let quill = new Quill(article, { readOny: true });
                quill.setContents(JSON.parse(text));
                return quill.root.innerHTML;
            } catch (err) {
                return "";
            }
        },
        getLocationString(id) {
            const location = this.locations.find(
                (location) => location.id == id
            );
            return `${location?.addressFirst ?? ""}${
                location?.addressSecond ? "," : ""
            } ${location?.addressSecond ?? ""}${location?.city ? "," : ""} ${
                location?.city ?? ""
            }${location?.zip ? "," : ""} ${location?.zip ?? ""}${
                location?.state ? "," : ""
            } ${location?.state ?? ""}${location?.country ? "," : ""} ${
                location?.country ?? ""
            }`;
        },
        onCancelEmployeeEdit() {
            this.toggleEditEmployeeModal = false;
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

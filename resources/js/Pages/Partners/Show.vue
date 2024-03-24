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
                        {{ $t("Partner") }}
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
                        @click="activeTab = 'customers'"
                        :class="
                            activeTab === 'customers'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("Customers") }}
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
                                '/partners/' +
                                company.id +
                                '/edit?page=' +
                                returnPage
                            "
                        >
                            <span>{{ $t("Edit") }}</span>
                        </router-link>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                {{ $t("Fill Personal Details") }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="flex flex-wrap">
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Partner Number") }}:</label
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
                                        >{{ $t("Partner Type") }}:</label
                                    >
                                    <p
                                        v-if="
                                            form.partnerType ==
                                            'reseller_partner'
                                        "
                                    >
                                        {{ $t("Reseller Partner") }}
                                    </p>
                                    <p
                                        v-if="
                                            form.partnerType ==
                                            'business_partner'
                                        "
                                    >
                                        {{ $t("Solution Partner") }}
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
                            <div class="flex flex-wrap">
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Terms Of Payment") }}:</label
                                    >
                                    <p>{{ form.termsOfPayment?.name }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{
                                            $t("Invoice Email Address")
                                        }}:</label
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
                                            <div
                                                class="pb-8 pr-6 w-full lg:w-1/4"
                                            >
                                                <label
                                                    class="form-label font-bold"
                                                    >{{
                                                        $t("Bank Name")
                                                    }}:</label
                                                >
                                                <p>{{ bankDetail.bankName }}</p>
                                            </div>
                                            <div
                                                class="pb-8 pr-6 w-full lg:w-1/4"
                                            >
                                                <label
                                                    class="form-label font-bold"
                                                    >{{
                                                        $t("Swift/Bic")
                                                    }}:</label
                                                >
                                                <p>{{ bankDetail.swift }}</p>
                                            </div>
                                            <div
                                                class="pb-8 pr-6 w-full lg:w-1/4"
                                            >
                                                <label
                                                    class="form-label font-bold"
                                                    >{{ $t("IBAN") }}:</label
                                                >
                                                <p>{{ bankDetail.iban }}</p>
                                            </div>
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
                            <td class="w-px border-t">
                                <p class="mt-3 ml-3">
                                    {{ employee.email }}
                                </p>
                            </td>
                            <td class="w-px border-t">
                                <p class="mt-3 ml-3">
                                    {{ employee?.phone }}
                                </p>
                            </td>
                            <td class="w-px border-t">
                                <p class="mt-3 ml-3">
                                    {{ employee?.mobile }}
                                </p>
                            </td>
                            <td class="w-px border-t text-center">
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
                            <td class="px-6 py-4 border-t" colspan="4">
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
                v-if="activeTab === 'offers'"
                class="p-4"
                id="profile"
                role="tabpanel"
                aria-labelledby="profile-tab"
            >
                <form
                    v-for="(offer, index) in offers ?? []"
                    :key="'offer-' + index"
                >
                    <div class="card">
                        <div class="card-body">
                            <div class="flex justify-end p-2">
                                <router-link
                                    class="px-4 self-center"
                                    :to="`/offers/${offer.id}/edit`"
                                >
                                    <font-awesome-icon icon="fa-solid fa-eye" />
                                </router-link>
                            </div>
                            <div class="flex flex-wrap">
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Offer Number") }}:</label
                                    >
                                    <p>{{ offer.offerNumber }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Terms") }}:</label
                                    >
                                    <p>{{ offer.terms }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Receiver Type") }}:</label
                                    >
                                    <p>{{ offer.receiverType }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Project") }}:</label
                                    >
                                    <p>{{ offer.project }}</p>
                                </div>
                                <div class="pb-8 pr-6 w-full lg:w-1/4">
                                    <label class="form-label font-bold"
                                        >{{ $t("Offer Type") }}:</label
                                    >
                                    <p>{{ offer.type }}</p>
                                </div>
                            </div>
                            <SaleOffer
                                v-if="offer"
                                :key="offer.id"
                                :offer="offer"
                            />
                        </div>
                    </div>
                </form>
                <div v-if="!offers.length">
                    <div class="flex justify-center">
                        <p class="text-center">{{ $t("Offers not added") }},</p>
                        <router-link class="link" :to="'/offers/create'"
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
                                <router-link
                                    @click.stop=""
                                    :to="`/invoices/${invoice.id}`"
                                >
                                    <font-awesome-icon icon="fa-solid fa-eye" />
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
            <div v-else-if="activeTab === 'customers'">
                <div class="table-responsive">
                    <table class="doc-table">
                        <thead>
                            <tr class="text-left font-bold">
                                <th
                                    @click="
                                        sort(
                                            'companyNumber',
                                            'customers',
                                            'company'
                                        )
                                    "
                                    class="cursor-pointer"
                                    style="width: 140px"
                                >
                                    {{ $t("Customer Number")
                                    }}<font-awesome-icon
                                        v-if="
                                            company.sortBy === 'companyNumber'
                                        "
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            company.sortOrder === 'asc'
                                                ? 'up'
                                                : 'down'
                                        }`"
                                    />
                                </th>
                                <th
                                    style="width: 240px"
                                    @click="
                                        sort(
                                            'companyName',
                                            'customers',
                                            'company'
                                        )
                                    "
                                    class="cursor-pointer"
                                >
                                    {{ $t("Customer Name")
                                    }}<font-awesome-icon
                                        v-if="company.sortBy === 'companyName'"
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            company.sortOrder === 'asc'
                                                ? 'up'
                                                : 'down'
                                        }`"
                                    />
                                </th>
                                <th
                                    style="width: 140px"
                                    @click="
                                        sort(
                                            'customerType',
                                            'customers',
                                            'company'
                                        )
                                    "
                                    class="cursor-pointer"
                                >
                                    {{ $t("Type")
                                    }}<font-awesome-icon
                                        v-if="company.sortBy === 'customerType'"
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            company.sortOrder === 'asc'
                                                ? 'up'
                                                : 'down'
                                        }`"
                                    />
                                </th>
                                <th
                                    style="width: 500px"
                                    @click="sort('url', 'customers', 'company')"
                                    class="cursor-pointer"
                                >
                                    {{ $t("URL")
                                    }}<font-awesome-icon
                                        v-if="company.sortBy === 'url'"
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            company.sortOrder === 'asc'
                                                ? 'up'
                                                : 'down'
                                        }`"
                                    />
                                </th>
                                <th
                                    @click="
                                        sort('vatId', 'customers', 'company')
                                    "
                                    class="cursor-pointer"
                                >
                                    {{ $t("VAT")
                                    }}<font-awesome-icon
                                        v-if="company.sortBy === 'vatId'"
                                        class="cursor-pointer ml-2"
                                        :icon="`fa-solid fa-sort-${
                                            company.sortOrder === 'asc'
                                                ? 'up'
                                                : 'down'
                                        }`"
                                    />
                                </th>
                                <th class="">{{ $t("Actions") }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="customer in partnerCustomers?.data ?? []"
                                :key="customer.id"
                                class="hover:bg-gray-100 focus-within:bg-gray-100"
                                @click.stop.right="
                                    (e) => {
                                        e.preventDefault();
                                        let route = $router.resolve(
                                            '/companies/' + customer.id
                                        );
                                        window.open(route.href, '_blank');
                                    }
                                "
                            >
                                <td class="">
                                    <a class="flex items-center cursor-pointer">
                                        {{ customer.companyNumber }}
                                    </a>
                                </td>
                                <td class="">
                                    <a class="flex cursor-pointer items-center">
                                        {{ customer.companyName }}
                                    </a>
                                </td>
                                <td class="">
                                    <a
                                        class="flex items-center cursor-pointer"
                                        tabindex="-1"
                                    >
                                        {{ customer.type }}
                                    </a>
                                </td>
                                <td class="">
                                    <a class="cursor-pointer" tabindex="-1">
                                        <a
                                            :href="
                                                customer.url
                                                    ? customerLink(customer.url)
                                                    : '#'
                                            "
                                            :target="
                                                customerLink(customer.url)
                                                    ? '_blank'
                                                    : null
                                            "
                                            class="flex items-center cursor-pointer hover:text-blue-500 transition duration-200 ease-in-out"
                                            tabindex="-1"
                                            @click.stop
                                        >
                                            {{ customer.url }}
                                        </a>
                                    </a>
                                </td>

                                <td class="">
                                    <a
                                        class="flex cursor-pointer items-center"
                                        tabindex="-1"
                                    >
                                        {{ customer.vatId }}
                                    </a>
                                </td>
                                <td class="w-px text-center">
                                    <div class="flex items-center gap-3">
                                        <router-link
                                            v-if="
                                                $can(
                                                    `${$route.meta.permission}.show`
                                                )
                                            "
                                            :to="`/companies/${customer.id}?page=${page}`"
                                        >
                                            <font-awesome-icon
                                                icon="fa-solid fa-eye"
                                            />
                                        </router-link>
                                        <a
                                            v-if="
                                                $can(
                                                    `${$route.meta.permission}.edit`
                                                )
                                            "
                                            class="cursor-pointer"
                                            @click.stop="
                                                $router.push(
                                                    `/companies/${customer.id}/edit?page=${page}`
                                                )
                                            "
                                        >
                                            <font-awesome-icon
                                                icon="fa-regular fa-pen-to-square"
                                            />
                                        </a>
                                        <button
                                            v-if="
                                                $can(
                                                    `${$route.meta.permission}.delete`
                                                )
                                            "
                                            @click.stop="destroy(customer.id)"
                                        >
                                            <font-awesome-icon
                                                icon="fa-regular fa-trash-can"
                                            />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr
                                v-if="
                                    (partnerCustomers?.data?.length ?? 0) === 0
                                "
                            >
                                <td class="" colspan="4">
                                    {{ $t("No companies found.") }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div style="margin-top: 3rem" class="flex justify-center">
                    <pagination
                        :limit="10"
                        align="center"
                        :data="partnerCustomers"
                        @pagination-change-page="changePageOrSearch"
                    ></pagination>
                    <br />
                    <br />
                </div>
            </div>
        </div>
    </div>
    <EditModal :title="$t('Edit Location')" v-if="toggleLocationModal">
        <template #body>
            <div class="grid items-center grid-cols-2 gap-6">
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            :required="true"
                            v-model="locationTempData.addressFirst"
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
    <EditModal :title="$t('Edit Employee')" v-if="toggleEmployeeModal">
        <template #body>
            <div class="grid items-center grid-cols-2 gap-6">
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            v-model="employeeTempData.title"
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
import { mapGetters } from "vuex";
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
    },
    async mounted() {
        this.refresh();
        if (this.$route.query.page) {
            this.returnPage = this.$route.query.page;
        }
        let customerResponses = await this.$store.dispatch(
            "companies/partnerCompanies",
            {
                id: this.$route.params.id,
                page: 1,
            }
        );
        this.partnerCustomers = customerResponses?.data?.data;
    },
    components: {
        SaleOffer,
        LoadingButton,
        SelectInput,
        TextInput,
        Pagination,
        CustomPagination,
        EditModal,
        PageHeader,
    },
    data() {
        return {
            services: [],
            returnPage: "",
            currentPage: 1,
            start: 0,
            perPage: 25,
            employeeName: {},
            locations: [],
            invoice: {
                sortBy: "",
                sortOrder: "",
            },
            company: {
                sortBy: "",
                sortOrder: "",
            },
            premiseSystems: [],
            cloudSystems: [],
            hostingSystems: [],
            offers: [],
            invoices: [],
            projects: [],
            reports: [],
            tickets: [],
            company: {},
            partnerCustomers: [],
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
                partnerType: "",
                city: "",
                zip: "",
                state: "",
                country: "",
                defaultServiceDailyRate: "",
                defaultServiceHourlyRate: "",
                defaultServiceProduct: "",
                discount: 0,
                hourlyRate: 0,
            },
            locationTempData: {}, //used for v-model in location edit modal
            toggleLocationModal: false,
            employeeTempData: {}, //used for v-model in employee edit modal
            toggleEmployeeModal: false,
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Partners",
                    to: `/partners?page=${this.returnPage}`,
                },
                {
                    text: this.$t("Show"),
                    active: true,
                },
            ],
        };
    },
    watch: {
        "invoice.sortBy"(...val) {
            // Handle the change in the type property
            this.invoiceDetails();
        },
        "invoice.sortOrder"(...val) {
            // Handle the change in the type property
            this.invoiceDetails();
        },
        "company.sortBy"(...val) {
            // Handle the change in the type property
            this.companyDetails();
        },
        "company.sortOrder"(...val) {
            // Handle the change in the type property
            this.companyDetails();
        },
    },
    methods: {
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
        async companyDetails() {
            let customerResponses = await this.$store.dispatch(
                "companies/partnerCompanies",
                {
                    id: this.$route.params.id,
                    sortBy: this.company.sortBy,
                    sortOrder: this.company.sortOrder,
                }
            );
            this.partnerCustomers = customerResponses?.data?.data;
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
        async refresh() {
            this.$store.commit("showLoader", true);
            try {
                const response = await this.$store.dispatch(
                    "companies/show",
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
                    type: "partner",
                    company_id: this.$route.params.id,
                });
                const invoicesResponse = await this.$store.dispatch(
                    "companies/invoicesByCompany",
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
                await this.fetchLocations();
                await this.fetchEmployees(this.start, this.perPage);
                this.invoices = invoicesResponse?.data?.data ?? [];
                this.offers = offersResponse?.data?.data ?? [];
                let companyFormData = {
                    id: this.company.id,
                    companyName: this.company.companyName,
                    companyNumber: this.company.companyNumber,
                    vatId: this.company.vatId,
                    url: this.company.url,
                    phone: this.company.phone,
                    fax: this.company.fax,
                    type: this.company.type,
                    partnerType: this.company.partnerType,
                    termsOfPayment: "",
                    invoiceEmail: this.company.invoiceEmail,
                    bankDetails: this.company.bankDetails,
                    defaultServiceProduct: this.company.defaultServiceProduct,
                    defaultServiceHourlyRate:
                        this.company.defaultServiceHourlyRate,
                    defaultServiceDailyRate:
                        this.company.defaultServiceDailyRate,
                    discount: this.company.discount,
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
                console.log(e);
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
                        type: "partner",
                        company_id: this.$route.params.id,
                    });
                    resolve();
                } catch (e) {
                    reject(e);
                }
            });
        },
        async changePageOrSearch(page = 1) {
            this.page = page;
            let customerResponses = await this.$store.dispatch(
                "companies/partnerCompanies",
                {
                    id: this.$route.params.id,
                    page: page,
                }
            );
            this.partnerCustomers = customerResponses?.data?.data;
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
                    types: ["partner"],
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
                    try {
                        await this.$store.dispatch(
                            "companies/locationsDelete",
                            id
                        );
                        this.fetchLocations();
                    } catch (e) {}
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
                    try {
                        await this.$store.dispatch("auth/destroy", {
                            id: id,
                        });
                        this.fetchEmployees(this.start, this.perPage);
                    } catch (e) {}
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

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
                <form @submit.prevent="">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                {{ $t("Fill Personal Details") }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="grid items-center grid-cols-2 gap-6">
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            :required="true"
                                            v-model="form.companyName"
                                            :error="errors.companyName"
                                            class=""
                                            :label="$t('Name')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <select-input
                                            :key="form.type"
                                            v-model="form.type"
                                            :error="errors.type"
                                            class=""
                                            :label="$t('Type')"
                                        >
                                            <option value="">
                                                {{ $t("--Select--") }}
                                            </option>
                                            <option value="premise">
                                                {{ $t("OnPremise") }}
                                            </option>
                                            <option value="private">
                                                {{ $t("Cloud (Private)") }}
                                            </option>
                                            <option value="public">
                                                {{ $t("Cloud (Public)") }}
                                            </option>
                                            <option value="hosting">
                                                {{ $t("Hosting") }}
                                            </option>
                                            <option value="other">
                                                {{ $t("Other") }}
                                            </option>
                                        </select-input>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <select-input
                                            :required="true"
                                            v-if="form.customerType"
                                            v-model="form.customerType"
                                            :error="errors.customerType"
                                            class=""
                                            :floatingLabel="true"
                                            :label="$t('Type')"
                                        >
                                            <option value="customer">
                                                {{ $t("Customer") }}
                                            </option>
                                            <option value="lead">
                                                {{ $t("Lead") }}
                                            </option>
                                        </select-input>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            :required="true"
                                            v-model="form.url"
                                            :error="errors.url"
                                            class=""
                                            :label="$t('URL')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            :required="true"
                                            v-model="form.addressFirst"
                                            :error="errors.addressFirst"
                                            class=""
                                            :label="$t('Address Line 1')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            v-model="form.addressSecond"
                                            :error="errors.addressSecond"
                                            class=""
                                            :label="$t('Address Line 2')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            :required="true"
                                            v-model="form.city"
                                            :error="errors.city"
                                            class=""
                                            :label="$t('City')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            :required="true"
                                            v-model="form.zip"
                                            :error="errors.zip"
                                            class=""
                                            :label="$t('Zip')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <select-input
                                            :required="true"
                                            :key="form.country"
                                            v-model="form.country"
                                            :error="errors.country"
                                            class=""
                                            :floatingLabel="true"
                                            :label="$t('Country')"
                                        >
                                            <option
                                                v-for="(
                                                    country, key
                                                ) in countries"
                                                :value="country.id"
                                                :key="key"
                                            >
                                                {{ country.name }}
                                            </option>
                                        </select-input>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            v-model="form.state"
                                            :error="errors.state"
                                            class=""
                                            :label="$t('State')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            v-model="form.vatId"
                                            :error="errors.vatId"
                                            class=""
                                            type="text"
                                            :label="$t('VAT ID')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            v-model="form.phone"
                                            :error="errors.phone"
                                            class=""
                                            :label="$t('Phone')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            v-model="form.fax"
                                            :error="errors.fax"
                                            class=""
                                            :label="$t('Fax')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <MultiSelectInput
                                            class=""
                                            v-model="form.partner"
                                            :textLabel="$t('Partner')"
                                            :error="errors['partner']"
                                            :key="form.partner"
                                            :options="partners?.data ?? []"
                                            :multiple="false"
                                            :required="true"
                                            label="companyName"
                                            trackBy="id"
                                            moduleName="partner"
                                            :query="{ customerType: 'partner' }"
                                            :countStore="'partnerCount'"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <select-input
                                            :key="form.status"
                                            :required="true"
                                            v-model="form.status"
                                            :error="errors.status"
                                            class=""
                                            :label="$t('Status')"
                                        >
                                            <option value="active">
                                                {{ $t("Active") }}
                                            </option>
                                            <option value="archived">
                                                {{ $t("Archived") }}
                                            </option>
                                        </select-input>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="checkbox-group">
                                        <input
                                            v-model="
                                                form.isCustomerPortalOnboarding
                                            "
                                            :checked="
                                                form.isCustomerPortalOnboarding
                                            "
                                            class="checkbox-input"
                                            type="checkbox"
                                            id="customer-portal-onboarding"
                                        />
                                        <label
                                            class="checkbox-label"
                                            for="customer-portal-onboarding"
                                            >{{
                                                $t("Customer Portal Onboarding")
                                            }}</label
                                        >
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">
                                {{ $t("Payment Details") }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="grid items-center grid-cols-4 gap-6">
                                <div class="w-full col-span-2">
                                    <div class="form-group">
                                        <MultiSelectInput
                                            v-model="form.termsOfPayment"
                                            :textLabel="$t('Terms Of Payment')"
                                            :error="errors['termsOfPayment']"
                                            :key="form.termsOfPayment"
                                            :options="termsOfPayment.data"
                                            :multiple="false"
                                            label="name"
                                            trackBy="id"
                                            moduleName="termsOfPayment"
                                        ></MultiSelectInput>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <input
                                            v-if="form.termsOfPayment"
                                            style="
                                                width: -webkit-fill-available;
                                                align-self: baseline;
                                            "
                                            class="form-control"
                                            readonly
                                            type="text"
                                            :value="
                                                form.termsOfPayment
                                                    ?.paymentTerms
                                            "
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            v-model="form.invoiceEmail"
                                            :error="errors.invoiceEmail"
                                            class=""
                                            :label="$t('Invoice Email Address')"
                                            placeholder=" "
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <MultiSelectInput
                                            v-model="form.defaultPaymentPeriod"
                                            :key="form.defaultPaymentPeriod"
                                            :error="errors.defaultPaymentPeriod"
                                            :required="true"
                                            class=""
                                            :textLabel="
                                                $t('Default Payment Period')
                                            "
                                            :options="periods.data"
                                            :multiple="false"
                                            label="name"
                                            trackBy="id"
                                            moduleName="periods"
                                        ></MultiSelectInput>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="checkbox-group">
                                        <input
                                            v-model="form.mergePdfsOnDefault"
                                            :checked="form.mergePdfsOnDefault"
                                            class="checkbox-input"
                                            type="checkbox"
                                            id="merge-pdfs-on-default"
                                        />
                                        <label
                                            class="checkbox-label"
                                            for="merge-pdfs-on-default"
                                            >{{
                                                $t("Merge PDFs on default")
                                            }}</label
                                        >
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="checkbox-group">
                                        <input
                                            class="checkbox-input"
                                            v-model="form.applyReverseChange"
                                            :checked="form.applyReverseChange"
                                            type="checkbox"
                                            id="apply-reverse-change"
                                        />
                                        <label
                                            class="checkbox-label"
                                            for="apply-reverse-change"
                                            >{{
                                                $t("Apply Reverse Charge")
                                            }}</label
                                        >
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            v-model="form.externalOrderNumber"
                                            :error="errors.externalOrderNumber"
                                            class=""
                                            :label="$t('External Order Number')"
                                            placeholder=" "
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-header">
                            <h3 class="card-title">{{ $t("Bank Details") }}</h3>
                        </div>
                        <div class="card-body">
                            <div
                                v-for="(bankDetails, index) in form.bankDetails"
                                :key="index"
                                class="card mb-3 p-3 pt-8 relative"
                            >
                                <div
                                    role="button"
                                    class="absolute top-2 right-2 z-10"
                                    @click="form.bankDetails.splice(index, 1)"
                                >
                                    <CustomIcon name="DeleteIcon" />
                                </div>

                                <div
                                    class="grid items-center grid-cols-3 gap-6"
                                >
                                    <div class="w-full">
                                        <div class="form-group">
                                            <text-input
                                                :required="true"
                                                v-model="bankDetails.bankName"
                                                class=""
                                                :error="
                                                    errors[
                                                        `bankDetails.${index}.bankName`
                                                    ]
                                                "
                                                :label="$t('Bank Name')"
                                                placeholder=" "
                                                :floatingLabel="true"
                                            />
                                        </div>
                                    </div>
                                    <div class="w-full">
                                        <div class="form-group">
                                            <text-input
                                                :required="true"
                                                v-model="bankDetails.swift"
                                                class=""
                                                :label="$t('BIC/SWIFT')"
                                                placeholder=" "
                                                :error="
                                                    errors[
                                                        `bankDetails.${index}.swift`
                                                    ]
                                                "
                                                :floatingLabel="true"
                                            />
                                        </div>
                                    </div>
                                    <div class="w-full">
                                        <div class="form-group">
                                            <text-input
                                                :required="true"
                                                v-model="bankDetails.iban"
                                                :error="
                                                    errors[
                                                        `bankDetails.${index}.iban`
                                                    ]
                                                "
                                                class=""
                                                :label="$t('IBAN')"
                                                placeholder=" "
                                                :floatingLabel="true"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="flex items-center flex-wrap -mr-6 pt-4"
                                :class="[
                                    form.bankDetails &&
                                    form.bankDetails.length === 0
                                        ? 'p-4'
                                        : 'p-8',
                                ]"
                            >
                                <span
                                    :class="[
                                        form.bankDetails &&
                                        form.bankDetails.length === 0
                                            ? ''
                                            : 'mb-2',
                                    ]"
                                    class="flex items-center cursor-pointer"
                                >
                                    <svg
                                        class="fill-current text-blue-600 h-5 w-5"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            d="M11 9h4v2h-4v4H9v-4H5V9h4V5h2v4zm-1 11a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16z"
                                        />
                                    </svg>
                                    <span
                                        @click="addBankDetails"
                                        class="ml-2 font-medium text-blue-600"
                                        >{{ $t("Add bank accounts") }}</span
                                    >
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">
                                {{ $t("Default Service Details") }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="grid items-center grid-cols-2 gap-6">
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            class=""
                                            :isReadonly="true"
                                            :isButton="true"
                                            :label="
                                                $t('Default Service Product')
                                            "
                                            :placeholder="
                                                form.defaultServiceProduct
                                                    ?.name ??
                                                $t(
                                                    'Select Default Service Product'
                                                )
                                            "
                                            @click="serviceToggle = true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <number-input
                                            v-model="form.hourlyRate"
                                            class=""
                                            :label="$t('Hourly Rate')"
                                            :is-readonly="true"
                                            type="number"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <number-input
                                            @update:model-value="
                                                discountChanged
                                            "
                                            v-model="form.discount"
                                            class=""
                                            :label="$t('Discount')"
                                            :showPrefix="false"
                                            :maximum-fraction-digits="2"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <number-input
                                            v-model="
                                                form.defaultServiceHourlyRate
                                            "
                                            @update:model-value="
                                                calculateDailyRate
                                            "
                                            class=""
                                            :label="
                                                $t(
                                                    'Default Service Hourly Rate'
                                                )
                                            "
                                            type="number"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <number-input
                                            v-model="
                                                form.defaultServiceDailyRate
                                            "
                                            :isReadonly="true"
                                            class=""
                                            :label="
                                                $t('Default Service Daily Rate')
                                            "
                                            type="number"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <select-price-list
                        v-if="isPriceListToggle"
                        @cancelled="isPriceListToggle = false"
                        @selected="getSelectedPriceItems"
                        :selectedItems="form.priceLists"
                    ></select-price-list>
                    <div class="mt-4">
                        <div class="flex justify-between align-center mb-4">
                            <h3 class="card-title">
                                {{ $t("Edit Price List") }}
                            </h3>
                            <button
                                type="button"
                                @click="isPriceListToggle = true"
                                class="secondary-btn"
                            >
                                <p
                                    class="text-sm font-medium leading-none text-white"
                                >
                                    {{ $t("Add Default Price List") }}
                                </p>
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="doc-table">
                                <tr class="text-left font-bold">
                                    <th class="">
                                        {{ $t("Name") }}
                                    </th>
                                    <th class="">
                                        {{ $t("Status") }}
                                    </th>
                                    <th class="">
                                        {{ $t("Is Default") }}
                                    </th>
                                    <th class="">
                                        {{ $t("Delete") }}
                                    </th>
                                </tr>
                                <tr
                                    v-for="(
                                        priceList, index
                                    ) in form.priceLists"
                                    :key="index"
                                >
                                    <td class="">
                                        <p class="pt-3 pb-3 px-6">
                                            {{ priceList.name }}
                                        </p>
                                    </td>
                                    <td class="">
                                        <p class="pt-3 pb-3 px-6">
                                            {{ priceList.status }}
                                        </p>
                                    </td>
                                    <td class="">
                                        <p class="pt-3 pb-3 px-6">
                                            {{
                                                $t(
                                                    priceList.isDefault == 1
                                                        ? "Yes"
                                                        : "No"
                                                )
                                            }}
                                        </p>
                                    </td>
                                    <td class="">
                                        <button
                                            class="pt-3 pb-3 px-6"
                                            @click.prevent="
                                                form.priceLists.splice(index, 1)
                                            "
                                        >
                                            <font-awesome-icon
                                                icon="fa-regular fa-trash-can"
                                            />
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </form>

                <!-- Location details -->
                <div class="w-full flex flex-row-reverse pt-4 mb-4">
                    <loading-button
                        :loading="isLoading"
                        class="secondary-btn"
                        @click.prevent="openAddLocationModal()"
                        >{{ $t("Add Location") }}</loading-button
                    >
                </div>
                <div class="table-responsive margin-bottom-3rem">
                    <table class="doc-table">
                        <tr class="text-left font-bold">
                            <th class="">
                                {{ $t("Address Line 1") }}
                            </th>
                            <th class="">
                                {{ $t("Address Line 2") }}
                            </th>
                            <th class="">{{ $t("City") }}</th>
                            <th class="">{{ $t("Country") }}</th>
                            <th class="">{{ $t("State") }}</th>
                            <th class="text-center">
                                {{ $t("Actions") }}
                            </th>
                        </tr>
                        <tr
                            v-for="(location, index) in locations.data"
                            :key="'location-' + index"
                            class="hover:bg-gray-100 focus-within:bg-gray-100"
                        >
                            <td class="">
                                <p class="">
                                    {{ location.addressFirst }}
                                </p>
                            </td>
                            <td class="">
                                <p class="">
                                    {{ location.addressSecond }}
                                </p>
                            </td>
                            <td class="">
                                <p class="">
                                    {{ location.city }}
                                </p>
                            </td>
                            <td class="">
                                <p class="">
                                    {{ location.countryName }}
                                </p>
                            </td>
                            <td class="">
                                <p class="">
                                    {{ location.state }}
                                </p>
                            </td>
                            <td
                                v-if="location.isHeadQuarters == 0"
                                class="w-px text-center"
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
                        <tr v-if="locations.data.length === 0">
                            <td class="px-6 py-4" colspan="4">
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

                <!-- Employee Details -->
                <div class="w-full flex flex-row-reverse mb-4">
                    <loading-button
                        :loading="isLoading"
                        class="secondary-btn"
                        @click.prevent="openAddEmployeeModal()"
                        >{{ $t("Add Employee") }}</loading-button
                    >
                </div>
                <div class="table-responsive">
                    <table class="doc-table">
                        <tr class="text-left font-bold">
                            <th class="">
                                {{ $t("Name") }}
                            </th>
                            <th class="">
                                {{ $t("Position") }}
                            </th>
                            <th class="">
                                {{ $t("Mail") }}
                            </th>
                            <th class="">
                                {{ $t("Phone") }}
                            </th>
                            <th class="">
                                {{ $t("Mobile") }}
                            </th>
                            <th class="text-center">
                                {{ $t("Actions") }}
                            </th>
                        </tr>
                        <tr
                            v-for="(employee, index) in employees"
                            :key="'employee-' + index"
                            class="hover:bg-gray-100 focus-within:bg-gray-100"
                        >
                            <td class="">
                                <p class="">
                                    {{ employee.first_name }}
                                    {{ employee.last_name }}
                                </p>
                            </td>
                            <td class="">
                                <p class="">
                                    {{ employee?.position }}
                                </p>
                            </td>
                            <td class="">
                                <p class="">
                                    {{ employee.email }}
                                </p>
                            </td>
                            <td class="">
                                <p class="">
                                    {{ employee?.phone }}
                                </p>
                            </td>
                            <td class="">
                                <p class="">
                                    {{ employee?.mobile }}
                                </p>
                            </td>
                            <td class="w-px text-center">
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
                            <td class="px-6 py-4" colspan="4">
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
                    <br />
                    <br />
                </div>
            </div>
            <div
                v-if="activeTab === 'licenses'"
                id="licenses"
                role="tabpanel"
                aria-labelledby="licenses-tab"
            >
                <Licenses :companyId="$route.params.id" />
            </div>
        </div>
        <br />
        <div :class="`flex items-center justify-end`">
            <button
                @click="
                    $router.push('/partner-management/companies?page=' + this.$route.query.page)
                "
                class="primary-btn mr-3"
            >
                <div class="mr-1">
                    <CustomIcon name="cancelIcon" />
                </div>
                {{ $t("Cancel") }}
            </button>
            <loading-button
                @click="next"
                :loading="isLoading"
                class="secondary-btn"
            >
                <div class="mr-1">
                    <CustomIcon name="updateIcon" />
                </div>
                {{ $t("Update") }}
            </loading-button>
        </div>
        <EditModal
            :title="
                employeeModalAction === 'edit'
                    ? $t('Edit Employee')
                    : $t('Add Employee')
            "
            v-if="toggleEmployeeModal"
        >
            <template #body>
                <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                    <MultiSelectInput
                        @update:model-value="employeeSelected"
                        :showLabels="false"
                        v-model="employeeFormTemp"
                        :key="employeeFormTemp"
                        :options="users"
                        :multiple="false"
                        :textLabel="$t('Select An Existing Employee')"
                        label="first_name"
                        trackBy="id"
                        class="w-1/2"
                        moduleName="auth"
                        :query="{ type: null }"
                        :search-param-name="'search_string'"
                        :customLabel="customLabel"
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
                                style="grid-template-columns: 25% 25% 50%"
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
                <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                    <text-input
                        v-model="employeeTempData.title"
                        class=""
                        :label="$t('Title')"
                        placeholder=" "
                        :floatingLabel="true"
                        :error="errors.title"
                    />
                    <text-input
                        v-model="employeeTempData.first_name"
                        class=""
                        :label="$t('First Name')"
                        placeholder=" "
                        :floatingLabel="true"
                        :error="errors.first_name"
                    />
                    <text-input
                        v-model="employeeTempData.last_name"
                        class=""
                        :label="$t('Last Name')"
                        placeholder=" "
                        :floatingLabel="true"
                        :error="errors.last_name"
                    />
                    <text-input
                        :isReadonly="typeof employeeTempData?.id === 'string'"
                        :required="true"
                        v-model="employeeTempData.email"
                        class=""
                        :label="$t('Email')"
                        placeholder=" "
                        :floatingLabel="true"
                        :error="errors.email"
                    />
                    <text-input
                        v-model="employeeTempData.mobile"
                        class=""
                        :label="$t('Mobile')"
                        placeholder=" "
                        :floatingLabel="true"
                        :error="errors.mobile"
                    />
                    <text-input
                        v-model="employeeTempData.phone"
                        class=""
                        :label="$t('Phone Number')"
                        placeholder=" "
                        :floatingLabel="true"
                        :error="errors.phone"
                    />
                    <text-input
                        v-model="employeeTempData.fax"
                        class=""
                        :label="$t('Fax')"
                        placeholder=" "
                        :floatingLabel="true"
                        :error="errors.fax"
                    />
                    <text-input
                        v-model="employeeTempData.position"
                        class=""
                        :label="$t('Position')"
                        placeholder=" "
                        :floatingLabel="true"
                        :error="errors.position"
                    />
                    <text-input
                        v-model="employeeTempData.department"
                        class=""
                        :label="$t('Department')"
                        placeholder=" "
                        :floatingLabel="true"
                        :error="errors.department"
                    />
                    <div class="form-group">
                        <select-input
                            v-model="employeeTempData.location_id"
                            class=""
                            :floatingLabel="true"
                            :error="errors.location_id"
                            :label="$t('Location')"
                        >
                            <option
                                v-for="location in locations?.data ?? []"
                                :key="'location' + location.id"
                                :value="location.id"
                            >
                                {{ location.addressFirst }},
                                {{ location.addressSecond }}, {{ location.city }},
                                {{ location.state }}, {{ location.zip }},
                                {{ location.countryName }}
                            </option>
                        </select-input>
                    </div>
                </div>
            </template>
            <template #footer>
                <loading-button
                    :loading="isLoading"
                    type="button"
                    class="secondary-btn"
                    @click="saveEmployee"
                >
                    {{ $t("Save") }}
                </loading-button>
                <button
                    @click="onCancelEmployeeEdit"
                    type="button"
                    class="primary-btn mr-3"
                >
                    {{ $t("Cancel") }}
                </button>
            </template>
        </EditModal>
        <EditModal
            :title="
                locationModalAction === 'edit'
                    ? $t('Edit Location')
                    : $t('Add Location')
            "
            v-if="toggleLocationModal"
        >
            <template #body>
                <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                    <text-input
                        :required="true"
                        v-model="locationTempData.addressFirst"
                        class=""
                        :label="$t('Address Line 1')"
                        placeholder=" "
                        :floatingLabel="true"
                        :error="errors.addressFirst"
                    />
                    <text-input
                        v-model="locationTempData.addressSecond"
                        class=""
                        :label="$t('Address Line 2')"
                        placeholder=" "
                        :floatingLabel="true"
                        :error="errors.addressSecond"
                    />
                    <text-input
                        :required="true"
                        v-model="locationTempData.city"
                        class=""
                        :label="$t('City')"
                        placeholder=" "
                        :floatingLabel="true"
                        :error="errors.city"
                    />
                    <text-input
                        :required="true"
                        v-model="locationTempData.zip"
                        class=""
                        :label="$t('Zip')"
                        placeholder=" "
                        :floatingLabel="true"
                        :error="errors.zip"
                    />
                    <div class="form-group">
                        <select-input
                            :required="true"
                            v-model="locationTempData.country"
                            :error="errors.country"
                            :floatingLabel="true"
                            :label="$t('Country')"
                        >
                            <option
                                v-for="(country, key) in countries"
                                :value="country.id"
                                :key="key"
                            >
                                {{ country.name }}
                            </option>
                        </select-input>
                    </div>
                    <text-input
                        v-model="locationTempData.state"
                        class=""
                        :label="$t('State')"
                        placeholder=" "
                        :floatingLabel="true"
                        :error="errors.state"
                    />
                </div>
            </template>
            <template #footer>
                <loading-button
                    :loading="isLoading"
                    type="button"
                    class="secondary-btn"
                    @click="saveLocation"
                >
                    {{ $t("Save") }}
                </loading-button>
                <button
                    @click="onCancel"
                    type="button"
                    class="primary-btn mr-3"
                >
                    {{ $t("Cancel") }}
                </button>
            </template>
        </EditModal>
    </div>
    <select-service-modal
        v-if="serviceToggle"
        @selected="addService"
        @cancelled="hideModal"
        :single-select="true"
        :selectedItems="getSelectedItems"
        :products="services"
    ></select-service-modal>
</template>

<script>
import TextInput from "../../Components/TextInput.vue";
import NumberInput from "../../Components/NumberInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import EditModal from "../../Components/EditModal.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";
import Pagination from "laravel-vue-pagination";
import CustomPagination from "../../Components/Pagination.vue";
import SelectServiceModal from "../../Components/SelectServiceModal.vue";
import Licenses from "./Licenses.vue";
import SelectPriceList from "../../Components/SelectPriceList.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("auth", ["user", "users"]),
        ...mapGetters("companies", ["partners"]),
        ...mapGetters("termsOfPayment", {
            termsOfPayment: "termsOfPayment",
        }),
        ...mapGetters("periods", ["periods"]),
        ...mapGetters("companyEmployees", {
            employees: "users",
            count: "count",
        }),
        getSelectedItems() {
            return this.form.defaultServiceProduct
                ? [this.form.defaultServiceProduct]
                : [];
        },
    },
    watch: {
        async "form.type"(val) {
            if (val == null) {
                this.form.type = "";
            }
        },
        serviceToggle(newVal) {
            if (newVal) {
                document.body.classList.add("modal-layout");
            } else {
                document.body.classList.remove("modal-layout");
            }
        },
    },
    components: {
        LoadingButton,
        SelectInput,
        TextInput,
        Pagination,
        EditModal,
        MultiSelectInput,
        CustomPagination,
        NumberInput,
        SelectServiceModal,
        Licenses,
        SelectPriceList,
        PageHeader,
    },
    async mounted() {
        try {
            const response = await this.$store.dispatch(
                "products/productsWithQuantity",
                {
                    perPage: 25,
                    page: 1,
                    type: "service",
                }
            );
            this.services = response?.data?.products ?? [];
            if (!this.period?.data?.length) {
                await this.$store.dispatch("periods/list");
            }
            await this.$store.dispatch("companies/list", {
                perPage: 25,
                page: 1,
                customerType: "partner",
            });

            if (this.user) {
                this.form.partner = this.partners?.data?.find(
                    (partner) => partner.id === this.user.tenant_id
                );
                if (!this.form.partner && this.user.tenant_id) {
                    this.$store
                        .dispatch("companies/showPartnerCompany", this.user.tenant_id)
                        .then((res) => {
                            this.form.partner = res?.data?.modelData ?? null;
                        });
                }
            }

            this.refresh();
        } catch (e) {}
    },
    data() {
        return {
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
                    text: "Edit",
                    active: true,
                },
            ],
            isPriceListToggle: false,
            services: [],
            employeeFormTemp: null,
            service: [],
            serviceToggle: false,
            shouldShow: true,
            currentPage: 1,
            start: 0,
            perPage: 25,
            company: {},
            locationTempData: {}, //used for v-model in location edit modal
            employeeTempData: {}, //used for v-model in employee edit modal
            employeeModalAction: "",
            toggleLocationModal: false,
            toggleEmployeeModal: false,
            locationModalAction: "",
            countries: [],
            activeClasses: "active",
            inactiveClasses: "inactive",
            activeTab: "company",
            locations: {
                data: [],
                links: [],
            },
            form: {
                companyName: "",
                companyNumber: "0",
                vatId: "",
                phone: "",
                url: "",
                fax: "",
                partner: "",
                status: "",
                type: "",
                customerType: "",
                addressFirst: "",
                addressSecond: "",
                city: "",
                zip: "",
                state: "",
                country: "",
                termsOfPayment: "",
                invoiceEmail: "",
                bankDetails: [],
                defaultServiceProduct: "",
                defaultServiceHourlyRate: "",
                defaultServiceDailyRate: "",
                hourlyRate: "",
                discount: 0,
                priceLists: [],
                defaultPaymentPeriod: "",
                mergePdfsOnDefault: false,
                applyReverseChange: false,
                externalOrderNumber: "",
                isCustomerPortalOnboarding: false,
            },
            employeeForm: {
                company_id: "",
                title: "",
                first_name: "",
                last_name: "",
                email: "",
                mobile: "",
                phone: "",
                fax: "",
                position: "",
                department: "",
                location_id: "",
                password: "",
            },
            locationForm: {
                companyId: "",
                addressFirst: "",
                addressSecond: "",
                city: "",
                zip: "",
                state: "",
                country: "",
            },
        };
    },
    methods: {
        hideModal(){
            this.serviceToggle = false;
        },
        /**
         * recalculates the default service hourly rate when the discount is changed
         */
        async discountChanged() {
            await this.$nextTick();
            this.form.defaultServiceHourlyRate =
                (this.form.defaultServiceHourlyRate ?? 0) -
                ((this.form.defaultServiceHourlyRate ?? 0) *
                    (this.form.discount ?? 0)) /
                    100;
            this.calculateDailyRate(); // recalculate the default service daily rate since it depends on default service hourly rate
        },
        /**
         * triggered when employee is selected from employee dropdown in employees tab
         * copies the data from 'employeeFormTemp' to 'employeeForm'
         * if employee dropdown is cleared then resets the 'employeeForm'
         */
        async employeeSelected() {
            await this.$nextTick();
            if (this.employeeFormTemp)
                this.employeeTempData = { ...this.employeeFormTemp };
            else {
                this.employeeTempData = {};
            }
        },
        getSelectedPriceItems(items) {
            this.form.priceLists = items;
            this.isPriceListToggle = false;
        },
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
        addService(services) {
            if (services.length) {
                this.form.defaultServiceProduct = services?.[0] ?? "";
                if (this.form.defaultServiceProduct) {
                    this.form.defaultServiceDailyRate =
                        this.form.defaultServiceProduct?.dailyRate;
                    this.form.defaultServiceHourlyRate =
                        this.form.defaultServiceProduct?.hourlyRate;
                    this.form.discount = 0;
                }
            }
            this.serviceToggle = false;
        },
        calculateDailyRate() {
            this.$nextTick(() => {
                if (
                    this.form.defaultServiceProduct?.paymentDetailsType ===
                    "pauschal"
                )
                    return;
                try {
                    this.form.defaultServiceDailyRate =
                        +this.form.defaultServiceHourlyRate * 8;
                } catch (e) {
                    console.log(e);
                }
            });
        },
        async serviceProductAdded() {
            await this.$nextTick();
            if (this.form.defaultServiceProduct) {
                this.form.defaultServiceDailyRate =
                    this.form.defaultServiceProduct?.dailyRate;
                this.form.defaultServiceHourlyRate =
                    this.form.defaultServiceProduct?.hourlyRate;
            }
        },
        deleteBankDetails(index) {
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
                    this.form.bankDetails.splice(index, 1);
                }
            });
        },
        addBankDetails() {
            this.form.bankDetails.push({
                bankName: "",
                swift: "",
                iban: "",
            });
        },
        async refresh() {
            this.$store.commit("showLoader", true);
            try {
                this.shouldShow = false;
                const countries = await this.$store.dispatch(
                    "countries/getAllCountries"
                );
                this.countries = countries?.data?.data;
                const response = await this.$store.dispatch(
                    "companies/showPartnerCompany",
                    this.$route.params.id
                );
                this.company = response?.data?.modelData ?? {};

                document.title = "Edit Company " + this.company.companyNumber;
                await this.$store.dispatch("termsOfPayment/list", {
                    perPage: 25,
                    page: 1,
                });
                await this.$store.dispatch("companyEmployees/list", {
                    limit_start: 0,
                    limit_count: 25,
                    type: "customer",
                    company_id: this.$route.params.id,
                });
                const locationsResponse = await this.$store.dispatch(
                    "companies/locationsList",
                    this.$route.params.id
                );
                this.locations.data = locationsResponse?.data?.locations ?? [];
                let companyFormData = { ...this.company };
                companyFormData = {
                    ...companyFormData,
                    partner: companyFormData.partner,
                    ...(this.locations.data.find((location) => {
                        return location.isHeadQuarters == 1;
                    }) ?? {}),
                };
                this.form = {
                    ...companyFormData,
                    defaultServiceHourlyRate:
                        companyFormData.defaultServiceHourlyRate ?? "",
                    defaultServiceDailyRate:
                        companyFormData.defaultServiceDailyRate ?? "",
                    discount: companyFormData.discount ?? 0,
                    defaultPaymentPeriod:
                        companyFormData?.defaultPaymentPeriod ?? "",
                    mergePdfsOnDefault:
                        companyFormData?.mergePdfsOnDefault == 1,
                    applyReverseChange:
                        companyFormData?.applyReverseChange == 1,
                    isCustomerPortalOnboarding:
                        companyFormData?.isCustomerPortalOnboarding == 1,
                };
                this.form.defaultServiceProduct = this.services.data.find(
                    (service) =>
                        service.id == companyFormData.defaultServiceProduct
                );
                // if a service is not found from the 'serviceProducts' array then call the show API to get the service individually
                if (
                    !this.form.defaultServiceProduct &&
                    companyFormData.defaultServiceProduct != null
                ) {
                    try {
                        const serviceResponse = await this.$store.dispatch(
                            "products/show",
                            companyFormData.defaultServiceProduct
                        );
                        this.form.defaultServiceProduct =
                            serviceResponse?.data?.pProducts;
                    } catch (e) {
                        console.log(e);
                    }
                }
                this.form.hourlyRate =
                    this.form.defaultServiceProduct?.hourlyRate ?? 0;
                this.shouldShow = true;
                this.form.termsOfPayment =
                    this.termsOfPayment.data.find(
                        (terms) => terms.id === this.company.termsOfPayment
                    ) ?? null;
                this.activeTab = this.$route.query.tab ?? "company";
            } catch (e) {
            } finally {
                this.$store.commit("showLoader", false);
            }
        },
        onCancelEmployeeEdit() {
            this.toggleEmployeeModal = false;
        },
        async openAddEmployeeModal() {
            // fetch the auth listing
            try {
                await this.$store.dispatch("auth/list", {
                    limit_start: 0,
                    limit_count: 25,
                });
            } catch (e) {
                console.log(e);
            }
            this.employeeTempData = {};
            this.toggleEmployeeModal = true;
            this.employeeModalAction = "add";
        },
        openEditEmployeeModal(index) {
            this.employeeModalAction = "edit";
            this.employeeTempData = { ...this.employees[index] };
            this.toggleEmployeeModal = true;
        },
        openAddLocationModal() {
            this.locationTempData = {};
            this.toggleLocationModal = true;
            this.locationModalAction = "add";
        },
        openEditLocationModal(index) {
            this.locationModalAction = "edit";
            this.locationTempData = { ...this.locations.data[index] };
            this.toggleLocationModal = true;
        },
        onCancel() {
            this.toggleLocationModal = false;
        },
        async fetchEmployees(start, end) {
            try {
                await this.$store.dispatch("companyEmployees/list", {
                    limit_start: start,
                    limit_count: end,
                    type: "customer",
                    company_id: this.$route.params.id,
                });
            } catch (e) {}
        },
        async fetchLocations() {
            try {
                const response = await this.$store.dispatch(
                    "companies/locationsList",
                    this.$route.params.id
                );
                this.locations.data = response?.data?.locations ?? [];
            } catch (e) {}
        },
        async next() {
            this.$store.commit("isLoading", true);
            
            if (this.$route.params.id) {
            
                try {
                    await this.$store.dispatch("companies/updatePartnerCompany", {
                        id: this.$route.params.id,
                        data: {
                            ...this.form,
                            partner: this.form.partner?.id,
                            termsOfPayment: this.form?.termsOfPayment?.id,
                            location_id: this.locations.data?.[0].id,
                            defaultServiceProduct:
                                this.form?.defaultServiceProduct?.id,
                            defaultPaymentPeriod:
                                this.form.defaultPaymentPeriod?.id ?? "",
                        },
                    });
                    //  this.sendEloRequest("updateCustomer");
                } catch (e) {
                console.log(e)
                    return;
                }
            } else {
                try {
                    const response = await this.$store.dispatch(
                        "companies/create",
                        {
                            ...this.form,
                            partner: this.form.partner?.id,
                            termsOfPayment: this.form?.termsOfPayment?.id,
                            defaultPaymentPeriod:
                                this.form.defaultPaymentPeriod?.id ?? "",
                        }
                    );
                    this.fetchLocations();
                    //  this.sendEloRequest("createCustomer");
                } catch (e) {
                    return;
                }
            }
            if (this.$route.query.page) {
                this.$router.push("/partner-management/companies?page=" + this.$route.query.page);
            } else {
                this.$router.push("/partner-management/companies");
            }
        },
        async sendEloRequest(moduleAction) {
            return new Promise(async (resolve, reject) => {
                try {
                    //Define logic to retrieve elo configuration
                    let content = {
                        ...this.form,
                        moduleAction: moduleAction,
                    };
                    if (moduleAction === "createCustomerEmployee") {
                        content.employeeData = { ...this.employeeForm };
                    } else if (moduleAction === "updateCustomerEmployee") {
                        content.employeeData = { ...this.employeeEditTemp };
                    }
                    await this.$store.dispatch(
                        "globalSettings/sendEloApiRequest",
                        {
                            content: content,
                        }
                    );
                    resolve();
                } catch (e) {
                    reject(e);
                }
            });
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
                    await this.$store.dispatch("companies/locationsDelete", id);
                    this.fetchLocations();
                }
            });
        },
        resetLocationData() {
            this.locationTempData = {};
            this.toggleLocationModal = false;
            this.locationForm = {
                companyId: "",
                addressFirst: "",
                addressSecond: "",
                city: "",
                zip: "",
                state: "",
                country: "",
            };
            this.fetchLocations();
        },
        async addLocation() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("companies/locationsCreate", {
                    ...this.locationForm,
                    companyId: this.$route.params.id,
                });
                this.resetLocationData();
            } catch (e) {}
        },
        async saveLocation() {
            if (this.locationModalAction === "add") {
                this.locationForm = { ...this.locationTempData };
                this.addLocation();
                return;
            }
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("companies/locationsUpdate", {
                    id: this.locationTempData.id,
                    data: {
                        ...this.locationTempData,
                        companyId: this.$route.params.id,
                    },
                });
                this.resetLocationData();
            } catch (e) {}
        },
        resetEmployeeData() {
            this.employeeFormTemp = null;
            this.employeeTempData = {};
            this.toggleEmployeeModal = false;
            this.employeeForm = {
                company_id: "",
                title: "",
                first_name: "",
                last_name: "",
                password: "",
                email: "",
                mobile: "",
                phone: "",
                fax: "",
                partner: "",
                position: "",
                department: "",
                location_id: "",
            };
            this.fetchEmployees(this.start, this.perPage);
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
        async addEmployee() {
            try {
                this.$store.commit("isLoading", true);
                if (this.employeeForm?.id) {
                    await this.$store.dispatch("auth/update", {
                        id: this.employeeForm.id,
                        title: this.employeeForm.title,
                        mobile: this.employeeForm.mobile,
                        phone: this.employeeForm.phone,
                        fax: this.employeeForm.fax,
                        partner: this.employeeForm.partner,
                        position: this.employeeForm.position,
                        department: this.employeeForm.department,
                        location_id: this.employeeForm.location_id,
                        types: [
                            ...Object.keys(this.employeeForm.types).filter(
                                (type) => this.employeeForm.types[type] == 1
                            ),
                            "customer",
                        ],
                        set_company_id: this.$route.params.id,
                    });
                } else {
                    await this.$store.dispatch("auth/create", {
                        ...this.employeeForm,
                        types: ["customer"],
                        mail: this.employeeForm.email,
                        set_company_id: this.$route.params.id,
                    });
                }
                await this.sendEloRequest("createCustomerEmployee");
                this.resetEmployeeData();
            } catch (e) {}
        },
        async saveEmployee() {
            if (this.employeeModalAction === "add") {
                this.employeeForm = { ...this.employeeTempData };
                this.addEmployee();
                return;
            }
            try {
                this.$store.commit("isLoading", true);
                const payload = {
                    id: this.employeeTempData.id,
                    ...this.employeeTempData,
                    types: ["customer"],
                    set_company_id: this.$route.params.id,
                };
                delete payload["company_id"];
                await this.$store.dispatch("auth/update", payload);
                this.fetchEmployees(this.start, this.perPage);
                await this.sendEloRequest("updateCustomerEmployee");
            } catch (e) {
            } finally {
                this.employeeTempData = {};
                this.toggleEmployeeModal = false;
            }
        },
    },
};
</script>

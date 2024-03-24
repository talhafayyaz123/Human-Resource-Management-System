<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <div class="tab-header">
            <ul class="">
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
                        @click="company_id && (activeTab = 'locations')"
                        :class="
                            activeTab === 'locations'
                                ? activeClasses
                                : inactiveClasses +
                                  ' ' +
                                  (company_id ? '' : 'disabled')
                        "
                    >
                        {{ $t("Locations") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        @click="company_id && (activeTab = 'employees')"
                        :class="
                            activeTab === 'employees'
                                ? activeClasses
                                : inactiveClasses +
                                  ' ' +
                                  (company_id ? '' : 'disabled')
                        "
                    >
                        {{ $t("Employees") }}
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
                                            :label="$t('Company Name')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <select-input
                                            v-model="form.type"
                                            :error="errors.type"
                                            :label="$t('Type')"
                                            :floatingLabel="true"
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
                                            v-model="form.customerType"
                                            :error="errors.customerType"
                                            :floatingLabel="true"
                                            :disabled="true"
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
                                            v-model="form.country"
                                            :key="form.country"
                                            :error="errors.country"
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
                                            :label="$t('State')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            :required="true"
                                            v-model="form.vatId"
                                            :error="errors.vatId"
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
                                            :label="$t('Fax')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <MultiSelectInput
                                            :textLabel="$t('Status')"
                                            :required="true"
                                            :error="errors?.status ?? ''"
                                            v-model="form.status"
                                            :options="leadStatus?.data"
                                            :multiple="false"
                                            label="name"
                                            trackBy="id"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            v-model="form.notes"
                                            :error="errors.notes"
                                            :label="$t('Notes')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <select-input
                                            :required="true"
                                            v-model="form.size"
                                            :error="errors.size"
                                            :floatingLabel="true"
                                            :label="$t('Size')"
                                        >
                                            <option value="tiny">
                                                {{ $t("Tiny") }}
                                            </option>
                                            <option value="small">
                                                {{ $t("Small") }}
                                            </option>
                                            <option value="medium">
                                                {{ $t("Medium") }}
                                            </option>
                                            <option value="big">
                                                {{ $t("Big") }}
                                            </option>
                                            <option value="corporate">
                                                {{ $t("Corporate") }}
                                            </option>
                                        </select-input>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            v-model="form.orderProbability"
                                            :error="errors.orderProbability"
                                            :label="$t('Order Probability')"
                                            placeholder=" "
                                            type="number"
                                            :min="0"
                                            :max="100"
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <label class="form-label"
                                            >{{ $t("Expiring Date") }}:</label
                                        >
                                        <datepicker
                                            :clearable="false"
                                            :enable-time-picker="false"
                                            auto-apply
                                            :close-on-auto-apply="false"
                                            style="
                                                border: 1px solid lightgray;
                                                padding: 0.5rem;
                                                border-radius: 5px;
                                                width: 95%;
                                            "
                                            v-model="form.expiryDate"
                                            :class="
                                                errors.expiryDate ? 'error' : ''
                                            "
                                        />
                                        <div
                                            v-if="errors?.expiryDate"
                                            class="form-error"
                                        >
                                            {{ errors.expiryDate }}
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <MultiSelectInput
                                            v-model="form.partner"
                                            :textLabel="$t('Partner')"
                                            :error="errors['partner']"
                                            :key="form.partner"
                                            :required="true"
                                            :options="partners?.data ?? []"
                                            :multiple="false"
                                            label="companyName"
                                            trackBy="id"
                                            moduleName="partner"
                                            :query="{ customerType: 'partner' }"
                                            :countStore="'partnerCount'"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">
                                {{ $t("Add Assignees") }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="grid items-center grid-cols-2 gap-6">
                                <div class="w-full">
                                    <div class="form-group">
                                        <MultiSelectInput
                                            :required="true"
                                            :key="form.assignees"
                                            v-model="form.assignees"
                                            :multiple="true"
                                            :textLabel="$t('Assignees')"
                                            :error="errors.assignees"
                                            :customLabel="customLabel"
                                            label="first_name"
                                            :trackBy="'id'"
                                            :options="employees"
                                            moduleName="auth"
                                            :search-param-name="'search_string'"
                                            :action="'employees'"
                                            :countStore="'employeesCount'"
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
                                                    {{
                                                        props.option.first_name
                                                    }}
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
                                                    <p
                                                        class="overflow-text-users-table"
                                                    >
                                                        {{
                                                            props.option
                                                                .first_name
                                                        }}
                                                    </p>
                                                    <p
                                                        class="overflow-text-users-table"
                                                    >
                                                        {{
                                                            props.option
                                                                .last_name
                                                        }}
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
                            </div>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">
                                {{ $t("Add Contact Report Sources") }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="grid items-center grid-cols-2 gap-6">
                                <div class="w-full">
                                    <div class="form-group">
                                        <MultiSelectInput
                                            :textLabel="
                                                $t('Contact Report Source')
                                            "
                                            v-model="form.contactSources"
                                            :options="contactSources?.data"
                                            :multiple="true"
                                            label="name"
                                            trackBy="id"
                                            moduleName="sources"
                                            :error="errors.contactSources"
                                        />
                                    </div>
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
                            <div class="grid items-center grid-cols-2 gap-6">
                                <div class="w-full">
                                    <div class="form-group">
                                        <div
                                            :class="[
                                                'grid',
                                                `grid-cols-[${
                                                    form.termsOfPayment
                                                        ? '5fr,1fr'
                                                        : '100%'
                                                }]`,
                                                'gap-2',
                                            ]"
                                        >
                                            <MultiSelectInput
                                                v-model="form.termsOfPayment"
                                                :textLabel="
                                                    $t('Terms Of Payment')
                                                "
                                                :error="
                                                    errors['termsOfPayment']
                                                "
                                                :key="form.termsOfPayment"
                                                :options="termsOfPayment.data"
                                                :multiple="false"
                                                label="name"
                                                trackBy="id"
                                                moduleName="termsOfPayment"
                                            ></MultiSelectInput>
                                            <input
                                                v-if="form.termsOfPayment"
                                                style="
                                                    width: -webkit-fill-available;
                                                    align-self: baseline;
                                                "
                                                class="py-2 mt-8 pl-1 border rounded mr-2"
                                                readonly
                                                type="text"
                                                :value="
                                                    form.termsOfPayment
                                                        ?.paymentTerms
                                                "
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            v-model="form.invoiceEmail"
                                            :error="errors.invoiceEmail"
                                            :label="$t('Invoice Email Address')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <MultiSelectInput
                                            v-model="form.defaultPaymentPeriod"
                                            :key="form.defaultPaymentPeriod"
                                            :error="errors.defaultPaymentPeriod"
                                            :required="false"
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
                            </div>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">{{ $t("Bank Details") }}</h3>
                        </div>
                        <div class="card-body">
                            <div
                                v-for="(bankDetails, index) in form.bankDetails"
                                :key="index"
                                class=""
                            >
                                <div
                                    role="button"
                                    class="text-xs font-medium text-orange-500 underline pt-2 mr-6 text-right my-3"
                                    @click="form.bankDetails.splice(index, 1)"
                                >
                                    {{ $t("Delete") }}
                                </div>

                                <div
                                    class="grid items-center grid-cols-2 gap-6"
                                >
                                    <div class="w-full">
                                        <div class="form-group">
                                            <text-input
                                                :required="true"
                                                v-model="bankDetails.bankName"
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
                </form>
            </div>
            <div
                v-else-if="activeTab === 'locations'"
                class="p-4"
                id="settings"
                role="tabpanel"
                aria-labelledby="settings-tab"
            >
                <form @submit.prevent="addLocation">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                {{ $t("Add Location Details") }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="grid items-center grid-cols-2 gap-6">
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            :required="true"
                                            v-model="locationForm.addressFirst"
                                            :error="errors.addressFirst"
                                            :label="$t('Address Line 1')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            v-model="locationForm.addressSecond"
                                            :error="errors.addressSecond"
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
                                            v-model="locationForm.city"
                                            :error="errors.city"
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
                                            v-model="locationForm.zip"
                                            :error="errors.zip"
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
                                            v-model="locationForm.country"
                                            :error="errors.country"
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
                                            v-model="locationForm.state"
                                            :error="errors.state"
                                            :label="$t('State')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-end mt-3">
                        <loading-button
                            :loading="isLoading"
                            class="secondary-btn"
                            >{{ $t("Add Location") }}</loading-button
                        >
                    </div>
                </form>
                <div class="table-responsive mt-3">
                    <table class="doc-table">
                        <tr class="text-left font-bold">
                            <th class="pb-4 pt-6 px-6">
                                {{ $t("Address Line 1") }}
                            </th>
                            <th class="pb-4 pt-6 px-6">
                                {{ $t("Address Line 2") }}
                            </th>
                            <th class="pb-4 pt-6 px-6">{{ $t("City") }}</th>
                            <th class="pb-4 pt-6 px-6">{{ $t("Country") }}</th>
                            <th class="pb-4 pt-6 px-6">{{ $t("State") }}</th>
                            <th class="pb-4 pt-6 px-6 text-center">
                                {{ $t("Actions") }}
                            </th>
                        </tr>
                        <tr
                            v-for="(location, index) in locations.data"
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
                        <tr v-if="locations.data.length === 0">
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
            </div>
            <div
                v-else-if="activeTab === 'employees'"
                class="p-4"
                id="dashboard"
                role="tabpanel"
                aria-labelledby="dashboard-tab"
            >
                <form @submit.prevent="addEmployee">
                    <div class="card">
                        <div
                            class="card-header flex justify-between items-center"
                        >
                            <h3 class="card-title">
                                {{ $t("Add Employee Details") }}
                            </h3>
                            <div class="w-1/4">
                                <div class="form-group">
                                    <MultiSelectInput
                                        @update:model-value="employeeSelected"
                                        :showLabels="false"
                                        v-model="employeeFormTemp"
                                        :key="employeeFormTemp"
                                        :options="users"
                                        :multiple="false"
                                        :textLabel="
                                            $t('Select An Existing Employee')
                                        "
                                        label="first_name"
                                        trackBy="id"
                                        moduleName="auth"
                                        :query="{ type: null }"
                                        :search-param-name="'search_string'"
                                        :customLabel="customLabel"
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
                                                <p
                                                    class="overflow-text-users-table"
                                                >
                                                    {{
                                                        props.option.first_name
                                                    }}
                                                </p>
                                                <p
                                                    class="overflow-text-users-table"
                                                >
                                                    {{ props.option.last_name }}
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
                        </div>
                        <div class="card-body">
                            <div class="grid items-center grid-cols-2 gap-6">
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            v-model="employeeForm.title"
                                            :error="errors.title"
                                            :label="$t('Title')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            :isReadonly="
                                                typeof employeeForm?.id ===
                                                'string'
                                            "
                                            v-model="employeeForm.first_name"
                                            :error="errors.first_name"
                                            :label="$t('First Name')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            :isReadonly="
                                                typeof employeeForm?.id ===
                                                'string'
                                            "
                                            v-model="employeeForm.last_name"
                                            :error="errors.last_name"
                                            :label="$t('Last Name')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            :isReadonly="
                                                typeof employeeForm?.id ===
                                                'string'
                                            "
                                            :required="true"
                                            v-model="employeeForm.email"
                                            :error="errors.email"
                                            :label="$t('Email')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            v-model="employeeForm.mobile"
                                            :error="errors.mobile"
                                            :label="$t('Mobile')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            v-model="employeeForm.phone"
                                            :error="errors.phone"
                                            :label="$t('Phone Number')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            v-model="employeeForm.fax"
                                            :error="errors.fax"
                                            :label="$t('Fax')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            v-model="employeeForm.position"
                                            :error="errors.position"
                                            :label="$t('Position')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            v-model="employeeForm.department"
                                            :error="errors.department"
                                            :label="$t('Department')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <select-input
                                            v-model="employeeForm.location_id"
                                            :error="errors.location_id"
                                            :floatingLabel="true"
                                            :label="$t('Location')"
                                        >
                                            <option
                                                v-for="location in locations?.data ??
                                                []"
                                                :key="'location' + location.id"
                                                :value="location.id"
                                            >
                                                {{ location.addressFirst }},
                                                {{ location.addressSecond }},
                                                {{ location.city }},
                                                {{ location.state }},
                                                {{ location.zip }},
                                                {{ location?.countryName }}
                                            </option>
                                        </select-input>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 flex items-center justify-end">
                        <loading-button
                            :loading="isLoading"
                            class="btn-green"
                            >{{ $t("Add Employee") }}</loading-button
                        >
                    </div>
                </form>
                <div class="table-responsive mt-3">
                    <table class="doc-table">
                        <tr class="text-left font-bold">
                            <th class="pb-4 pt-6 px-6">
                                {{ $t("Name") }}
                            </th>
                            <th class="pb-4 pt-6 px-6">
                                {{ $t("Email") }}
                            </th>
                            <th class="pb-4 pt-6 px-6 text-center">
                                {{ $t("Actions") }}
                            </th>
                        </tr>
                        <tr
                            v-for="(employee, index) in companyEmployees"
                            :key="'employee-' + index"
                            class="hover:bg-gray-100 focus-within:bg-gray-100"
                        >
                            <td class="border-t">
                                <p class="mt-3 ml-3">
                                    {{ employee.first_name }}
                                    {{ employee.last_name }}
                                </p>
                            </td>
                            <td class="w-px border-t">
                                <p class="mt-3 ml-3">
                                    {{ employee.email }}
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
                        <tr v-if="companyEmployees.length === 0">
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
                        :length="companyEmployees.length"
                        :currentPage="currentPage"
                        @paginationInfo="
                            fetchEmployees($event.start, $event.end)
                        "
                    ></custom-pagination>
                    <br />
                    <br />
                </div>
            </div>
        </div>
        <br />
        <div :class="`flex items-center justify-end`">
            <button
                v-if="activeTab === 'company'"
                @click="$router.push('/leads')"
                class="primary-btn mr-3"
            >
                {{ $t("Cancel") }}
            </button>
            <loading-button
                v-else
                @click="back"
                :loading="isLoading"
                class="primary-btn mr-3"
                >{{ $t("Back") }}
            </loading-button>
            <loading-button
                @click="next"
                :loading="isLoading"
                class="secondary-btn"
                >{{
                    activeTab === "employees"
                        ? "Finish"
                        : activeTab === "company"
                        ? $t("Save and Proceed")
                        : $t("Next")
                }}
            </loading-button>
        </div>
        <EditModal :title="'Edit Employee'" v-if="toggleEditEmployeeModal">
            <template #body>
                <div class="grid items-center grid-cols-2 gap-6 px-8 py-5">
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="employeeEditTemp.title"
                                :label="$t('Title')"
                                placeholder=" "
                                :floatingLabel="true"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="employeeEditTemp.first_name"
                                :label="$t('First Name')"
                                placeholder=" "
                                :floatingLabel="true"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="employeeEditTemp.last_name"
                                :label="$t('Last Name')"
                                placeholder=" "
                                :floatingLabel="true"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="employeeEditTemp.mobile"
                                :label="$t('Mobile')"
                                placeholder=" "
                                :floatingLabel="true"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="employeeEditTemp.phone"
                                :label="$t('Phone Number')"
                                placeholder=" "
                                :floatingLabel="true"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="employeeEditTemp.fax"
                                :label="$t('Fax')"
                                placeholder=" "
                                :floatingLabel="true"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="employeeEditTemp.position"
                                :label="$t('Position')"
                                placeholder=" "
                                :floatingLabel="true"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="employeeEditTemp.department"
                                :label="$t('Department')"
                                placeholder=" "
                                :floatingLabel="true"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <select-input
                                v-if="employeeForm.location_id"
                                v-model="employeeEditTemp.location_id"
                                :floatingLabel="true"
                                :label="$t('Location')"
                            >
                                <option
                                    v-for="location in locations?.data ?? []"
                                    :key="'location' + location.id"
                                    :value="location.id"
                                >
                                    {{ location.addressFirst }},
                                    {{ location.addressSecond }},
                                    {{ location.city }}, {{ location.state }},
                                    {{ location.zip }},
                                    {{ location?.countryName }}
                                </option>
                            </select-input>
                        </div>
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
                    {{ $t("Edit") }}
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
        <EditModal :title="$t('Edit Location')" v-if="toggleEditLocationModal">
            <template #body>
                <div class="grid items-center grid-cols-2 gap-6 px-8 py-5">
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="locationEditTemp.addressFirst"
                                :label="$t('Address Line 1')"
                                placeholder=" "
                                :floatingLabel="true"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="locationEditTemp.addressSecond"
                                :label="$t('Address Line 2')"
                                placeholder=" "
                                :floatingLabel="true"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="locationEditTemp.city"
                                :label="$t('City')"
                                placeholder=" "
                                :floatingLabel="true"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="locationEditTemp.zip"
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
                                v-model="locationEditTemp.country"
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
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="locationEditTemp.state"
                                :label="$t('State')"
                                placeholder=" "
                                :floatingLabel="true"
                            />
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <loading-button
                    :loading="isLoading"
                    type="button"
                    class="secondary-btn"
                    @click="saveLocation"
                >
                    {{ $t("Edit") }}
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
</template>

<script>
import TextInput from "../../Components/TextInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import EditModal from "../../Components/EditModal.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import CustomPagination from "../../Components/Pagination.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    async mounted() {
        const countries = await this.$store.dispatch(
            "countries/getAllCountries"
        );
        this.countries = countries?.data?.data;
        await this.$store.dispatch("termsOfPayment/list", {
            perPage: 25,
            page: 1,
        });
        await this.$store.dispatch("leadStatus/list", {
            limit_start: 0,
            limit_count: 25,
        });
        await this.$store.dispatch("auth/employees", {
            limit_start: 0,
            limit_count: 25,
        });
        await this.$store.dispatch("auth/list", {
            limit_start: 0,
            limit_count: 25,
        });
        await this.$store.dispatch("contactSource/list", {
            page: this.page,
            search: this.form.search,
        });
        if (!this.period?.data?.length) {
            await this.$store.dispatch("periods/list");
        }
        this.form.assignees =
            this.users.filter((user) => user.id == this.user.id) ?? [];

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
                    .dispatch("companies/show", this.user.tenant_id)
                    .then((res) => {
                        this.form.partner = res?.data?.modelData ?? null;
                    });
            }
        }
    },
    unmounted() {
        this.$store.commit("companyEmployees/users", []);
        this.$store.commit("companyEmployees/count", 0);
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("auth", ["user"]),
        ...mapGetters("companies", ["partners"]),
        ...mapGetters("periods", ["periods"]),
        ...mapGetters("companyEmployees", {
            companyEmployees: "users",
            count: "count",
        }),
        ...mapGetters("termsOfPayment", {
            termsOfPayment: "termsOfPayment",
        }),
        ...mapGetters("auth", {
            user: "user",
            users: "users",
            employees: "employees",
        }),
        ...mapGetters("leadStatus", ["leadStatus"]),
        ...mapGetters("contactSource", ["contactSources"]),
    },
    components: {
        CustomPagination,
        LoadingButton,
        SelectInput,
        TextInput,
        EditModal,
        MultiSelectInput,
        PageHeader,
    },
    data() {
        return {
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
                    to: "/leads",
                },
                {
                    text: this.$t("Create"),
                    active: true,
                },
            ],
            employeeFormTemp: null,
            currentPage: 1,
            start: 0,
            perPage: 25,
            company_id: "",
            locationEditTemp: {}, //used for v-model in location edit modal
            employeeEditTemp: {}, //used for v-model in employee edit modal
            toggleEditLocationModal: false,
            toggleEditEmployeeModal: false,
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
                url: "",
                fax: "",
                partner: "",
                type: "",
                phone: "",
                customerType: "lead",
                addressFirst: "",
                addressSecond: "",
                city: "",
                zip: "",
                state: "",
                country: "",
                termsOfPayment: "",
                invoiceEmail: "",
                bankDetails: [],
                assignees: [],
                expiryDate: "",
                status: {},
                notes: "",
                size: "",
                orderProbability: 0,
                contactSources: "",
                defaultPaymentPeriod: "",
            },
            employeeForm: {
                title: "",
                companyId: "",
                first_name: "",
                last_name: "",
                email: "",
                mobile: "",
                phone: "",
                fax: "",
                position: "",
                department: "",
                location: "",
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
        /**
         * triggered when employee is selected from employee dropdown in employees tab
         * copies the data from 'employeeFormTemp' to 'employeeForm'
         * if employee dropdown is cleared then resets the 'employeeForm'
         */
        async employeeSelected() {
            await this.$nextTick();
            if (this.employeeFormTemp)
                this.employeeForm = { ...this.employeeFormTemp };
            else {
                this.employeeForm = {
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
                };
            }
        },
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
        addBankDetails() {
            this.form.bankDetails.push({
                bankName: "",
                swift: "",
                iban: "",
            });
        },
        onCancelEmployeeEdit() {
            this.toggleEditEmployeeModal = false;
        },
        openEditEmployeeModal(index) {
            this.employeeEditTemp = { ...this.companyEmployees[index] };
            this.toggleEditEmployeeModal = true;
        },
        openEditLocationModal(index) {
            this.locationEditTemp = { ...this.locations.data[index] };
            this.toggleEditLocationModal = true;
        },
        onCancel() {
            this.toggleEditLocationModal = false;
        },
        async fetchEmployees(start, end) {
            try {
                await this.$store.dispatch("companyEmployees/list", {
                    limit_start: start,
                    limit_count: end,
                    type: "customer",
                    company_id: this.company_id,
                });
            } catch (e) {}
        },
        async fetchLocations() {
            try {
                const response = await this.$store.dispatch(
                    "companies/locationsList",
                    this.company_id
                );
                this.locations.data = response?.data?.locations ?? [];
            } catch (e) {}
        },
        back() {
            if (this.activeTab === "locations") {
                this.activeTab = "company";
            } else if (this.activeTab === "employees") {
                this.activeTab = "locations";
            }
        },
        async next() {
            if (this.activeTab === "company") {
                this.$store.commit("isLoading", true);
                if (this.company_id) {
                    try {
                        await this.$store.dispatch("companies/update", {
                            id: this.company_id,
                            data: {
                                ...this.form,
                                partner: this.form.partner?.id,
                                statusId: this.form?.status?.id,
                                termsOfPayment: this.form?.termsOfPayment?.id,
                                location_id: this.locations.data?.[0].id,
                            },
                        });
                        this.activeTab = "locations";
                        this.fetchLocations();
                        //this.sendEloRequest("updateLead");
                    } catch (e) {}
                } else {
                    try {
                        const response = await this.$store.dispatch(
                            "companies/create",
                            {
                                ...this.form,
                                partner: this.form.partner?.id,
                                defaultPaymentPeriod:
                                    this.form.defaultPaymentPeriod?.id ?? "",
                                statusId: this.form?.status?.id,
                                termsOfPayment: this.form?.termsOfPayment?.id,
                                assignees: this.form.assignees.map(
                                    (assignee) => assignee.id
                                ),
                            }
                        );
                        this.company_id = response.data.company_id;
                        this.form.companyNumber = response.data.company_number;
                        this.activeTab = "locations";
                        this.fetchLocations();
                        //this.sendEloRequest("createLead");
                    } catch (e) {}
                }
            } else if (this.activeTab === "locations") {
                this.activeTab = "employees";
            } else if (this.activeTab === "employees") {
                window.location.replace("/leads");
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
                    if (moduleAction === "createLeadEmployee") {
                        content.employeeData = { ...this.employeeForm };
                    } else if (moduleAction === "updateLeadEmployee") {
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
        async addLocation() {
            this.$store.commit("isLoading", true);

            try {
                await this.$store.dispatch("companies/locationsCreate", {
                    ...this.locationForm,
                    companyId: this.company_id,
                });
                this.fetchLocations();
            } catch (e) {}
            this.locationForm = {
                companyId: "",
                addressFirst: "",
                addressSecond: "",
                city: "",
                zip: "",
                state: "",
                country: "",
            };
        },
        async saveLocation() {
            this.$store.commit("isLoading", true);

            try {
                await this.$store.dispatch("companies/locationsUpdate", {
                    id: this.locationEditTemp.id,
                    data: {
                        ...this.locationEditTemp,
                        companyId: this.company_id,
                    },
                });
                this.fetchLocations();
            } catch (e) {}
            this.locationEditTemp = {};
            this.toggleEditLocationModal = false;
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
            this.$store.commit("isLoading", true);

            try {
                if (this.employeeForm?.id) {
                    await this.$store.dispatch("auth/update", {
                        id: this.employeeForm.id,
                        title: this.employeeForm.title,
                        mobile: this.employeeForm.mobile,
                        phone: this.employeeForm.phone,
                        fax: this.employeeForm.fax,
                        position: this.employeeForm.position,
                        department: this.employeeForm.department,
                        location_id: this.employeeForm.location_id,
                        types: [
                            ...Object.keys(this.employeeForm.types).filter(
                                (type) => this.employeeForm.types[type] == 1
                            ),
                            "customer",
                        ],
                        set_company_id: this.company_id,
                    });
                } else {
                    await this.$store.dispatch("auth/create", {
                        ...this.employeeForm,
                        types: ["customer"],
                        mail: this.employeeForm.email,
                        set_company_id: this.company_id,
                    });
                }
                this.employeeFormTemp = null;
                this.fetchEmployees(this.start, this.perPage);
                await this.sendEloRequest("createLeadEmployee");
            } catch (e) {}
            this.employeeForm = {
                title: "",
                companyId: "",
                first_name: "",
                last_name: "",
                email: "",
                mobile: "",
                phone: "",
                fax: "",
                position: "",
                department: "",
                location: "",
            };
        },
        async saveEmployee() {
            this.$store.commit("isLoading", true);

            try {
                const payload = {
                    id: this.employeeEditTemp.id,
                    ...this.employeeEditTemp,
                    types: ["customer"],
                    set_company_id: this.company_id,
                };
                delete payload["company_id"];
                await this.$store.dispatch("auth/update", payload);
                this.fetchEmployees(this.start, this.perPage);
                await this.sendEloRequest("updateLeadEmployee");
            } catch (e) {}
            this.employeeEditTemp = {};
            this.toggleEditEmployeeModal = false;
        },
    },
};
</script>

<style scoped>
.table-layout-fixed {
    table-layout: fixed;
}

.disabled {
    color: lightgray;
    cursor: not-allowed;
}
:deep(.v3dp__clearable) {
    font-size: 1.2rem;
    left: -25px;
}
:deep(.v3dp__clearable i) {
    font-style: normal;
}
</style>

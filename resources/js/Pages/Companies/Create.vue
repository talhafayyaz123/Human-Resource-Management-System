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
                        {{ $t("Customer") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        @click="company_id && (activeTab = 'licenses')"
                        :class="
                            activeTab === 'licenses'
                                ? activeClasses
                                : inactiveClasses +
                                  ' ' +
                                  (company_id ? '' : 'disabled')
                        "
                    >
                        {{ $t("Licenses") }}
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
                                            v-model="form.customerType"
                                            :error="errors.customerType"
                                            :disabled="true"
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
                                            v-model="form.country"
                                            :key="form.country"
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
                                            :options="partners?.data"
                                            :required="true"
                                            :multiple="false"
                                            label="companyName"
                                            trackBy="id"
                                            moduleName="companies"
                                            :query="{ customerType: 'partner' }"
                                            :countStore="'partnerCount'"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <select-input
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
                                    <div class="form-group">
                                        <label for="" class="input-label"></label>
                                        <input type="file" ref="file"  @change="handleFileChange" class="form-control">
                                    </div>
                                </div>
                                <div class="col-span-2" v-if="!!base64Image">
                                    <img  :src="'data:image\/png;base64,'+base64Image?.base64" style="width: 155px !important; height: 140px !important; margin-right: 10px">
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
                                <div class="w-full" v-if="form.termsOfPayment">
                                    <div class="form-group">
                                        <input
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
                                            v-model="form.applyReverseChange"
                                            :checked="form.applyReverseChange"
                                            class="checkbox-input"
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
                            <h3 class="card-title">
                                {{ $t("Bank Details") }}
                            </h3>
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

                    <div class="card my-5">
                        <div class="card-header flex justify-between">
                            <h3 class="card-title">
                                {{ $t("Add Default Price List") }}
                            </h3>
                            <button
                                type="button"
                                @click="isPriceListToggle = true"
                                class="secondary-btn"
                            >
                                {{ $t("Add Price List") }}
                            </button>
                        </div>
                        <div class="card-body">
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
                                        <td class="border-t">
                                            <p class="pt-3 pb-3 px-6">
                                                {{ priceList.name }}
                                            </p>
                                        </td>
                                        <td class="border-t">
                                            <p class="pt-3 pb-3 px-6">
                                                {{ priceList.status }}
                                            </p>
                                        </td>
                                        <td class="border-t">
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
                                        <td class="border-t">
                                            <button
                                                class="pt-3 pb-3 px-6"
                                                @click.prevent="
                                                    form.priceLists.splice(
                                                        index,
                                                        1
                                                    )
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
                    </div>
                </form>
            </div>

            <div
                v-if="activeTab === 'licenses'"
                id="licenses"
                role="tabpanel"
                aria-labelledby="licenses-tab"
            >
                <Licenses :companyId="company_id" />
            </div>
            <div
                v-else-if="activeTab === 'locations'"
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
                                            v-model="locationForm.addressFirst"
                                            :required="true"
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
                                            v-model="locationForm.addressSecond"
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
                                            v-model="locationForm.city"
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
                                            v-model="locationForm.zip"
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
                                            v-model="locationForm.country"
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
                                            v-model="locationForm.state"
                                            :error="errors.state"
                                            class=""
                                            :label="$t('State')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center justify-end">
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
                            <td class="border-t">
                                <p class="">
                                    {{ location.addressFirst }}
                                </p>
                            </td>
                            <td class="border-t">
                                <p class="">
                                    {{ location.addressSecond }}
                                </p>
                            </td>
                            <td class="border-t">
                                <p class="">
                                    {{ location.city }}
                                </p>
                            </td>
                            <td class="border-t">
                                <p class="">
                                    {{ location.countryName }}
                                </p>
                            </td>
                            <td class="border-t">
                                <p class="">
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
                id="dashboard"
                role="tabpanel"
                aria-labelledby="dashboard-tab"
            >
                <form @submit.prevent="addEmployee">
                    <div class="card">
                        <div
                            class="card-header flex items-center justify-between"
                        >
                            <h3 class="card-title">
                                {{ $t("Add Employee Details") }}
                            </h3>
                            <div class="form-group w-1/3">
                                <MultiSelectInput
                                    @update:model-value="employeeSelected"
                                    :showLabels="false"
                                    v-model="employeeFormTemp"
                                    :options="users"
                                    :key="employeeFormTemp"
                                    :multiple="false"
                                    :textLabel="
                                        $t('Select An Existing Employee')
                                    "
                                    label="first_name"
                                    trackBy="id"
                                    class=""
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
                                                {{ props.option.first_name }}
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
                        <div class="card-body">
                            <div class="grid items-center grid-cols-2 gap-6">
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            v-model="employeeForm.title"
                                            :error="errors.title"
                                            class=""
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
                                            class=""
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
                                            class=""
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
                                            class=""
                                            :label="$t('Email')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            v-if="!employeeForm?.id"
                                            v-model="employeeForm.password"
                                            :error="errors.password"
                                            :key="inputType"
                                            :label="$t('Password')"
                                            :type="inputType"
                                            :show-password="showPassword"
                                            @child-event="handleChildEvent"

                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            v-model="employeeForm.mobile"
                                            :error="errors.mobile"
                                            class=""
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
                                            class=""
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
                                            class=""
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
                                            class=""
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
                                            class=""
                                            :label="$t('Department')"
                                            placeholder=" "
                                            :floatingLabel="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <select-input
                                            :key="employeeForm.location_id"
                                            v-model="employeeForm.location_id"
                                            :error="errors.location_id"
                                            class=""
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
                                                {{ location.countryName }}
                                            </option>
                                        </select-input>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-end mt-4">
                        <loading-button
                            :loading="isLoading"
                            class="secondary-btn"
                            >{{ $t("Add Employee") }}</loading-button
                        >
                    </div>
                </form>
                <div class="mt-3 table-responsive">
                    <table class="doc-table">
                        <tr class="text-left font-bold">
                            <th class="">
                                {{ $t("Name") }}
                            </th>
                            <th class="">
                                {{ $t("Email") }}
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
                            <td class="border-t">
                                <p class="">
                                    {{ employee.first_name }}
                                    {{ employee.last_name }}
                                </p>
                            </td>
                            <td class="w-px border-t">
                                <p class="">
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
        </div>
        <div :class="`flex items-center justify-end mt-5`">
            <button
                v-if="activeTab === 'company'"
                @click="$router.push('/companies')"
                class="primary-btn gap-2 mr-3"
            >
                <div class="">
                    <CustomIcon name="cancelIcon" />
                </div>
                {{ $t("Cancel") }}
            </button>
            <loading-button
                v-else
                @click="back"
                :loading="isLoading"
                class="primary-btn gap-2 mr-3"
            >
            <CustomIcon name="backIcon"/>
                {{ $t("Back") }}
            </loading-button>
            <button
                v-if="activeTab === 'licenses' || activeTab === 'locations'"
                @click="next"
                class="primary-btn gap-2 mr-3"
            >
                {{ $t("Skip") }}
            </button>
            <loading-button
                @click="next"
                :loading="isLoading"
                class="secondary-btn gap-2"
            >
                <div class="">
                    <CustomIcon name="saveIcon" />
                </div>
                {{
                    activeTab === "employees"
                        ? $t("Save and Proceed")
                        : activeTab === "company"
                        ? $t("Save and Proceed")
                        : $t("Next")
                }}
            </loading-button>
        </div>
        <EditModal
            :classSize="'modal-md'"
            :title="'Edit Employee'"
            v-if="toggleEditEmployeeModal"
        >
            <template #body>
                <div class="grid items-center grid-cols-2 gap-6">
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="employeeEditTemp.title"
                                class=""
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
                                class=""
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
                                class=""
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
                                class=""
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
                                class=""
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
                                class=""
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
                                class=""
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
                                class=""
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
                                class=""
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
                                    {{ location.countryName }}
                                </option>
                            </select-input>
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <button
                    @click="onCancelEmployeeEdit"
                    type="button"
                    class="primary-btn mr-3"
                >
                    {{ $t("Cancel") }}
                </button>
                <loading-button
                    :loading="isLoading"
                    type="button"
                    class="secondary-btn"
                    @click="saveEmployee"
                >
                    {{ $t("Edit") }}
                </loading-button>
            </template>
        </EditModal>
        <EditModal
            :classSize="'modal-md'"
            :title="$t('Edit Location')"
            v-if="toggleEditLocationModal"
        >
            <template #body>
                <div class="grid items-center grid-cols-2 gap-6">
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="locationEditTemp.addressFirst"
                                class=""
                                label="Address Line 1"
                                placeholder=" "
                                :floatingLabel="true"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="locationEditTemp.addressSecond"
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
                                v-model="locationEditTemp.city"
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
                                v-model="locationEditTemp.zip"
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
                                v-model="locationEditTemp.country"
                                :error="errors.country"
                                class=""
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
                                class=""
                                :label="$t('State')"
                                placeholder=" "
                                :floatingLabel="true"
                            />
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <button
                    @click="onCancel"
                    type="button"
                    class="primary-btn mr-3"
                >
                    {{ $t("Cancel") }}
                </button>
                <loading-button
                    :loading="isLoading"
                    type="button"
                    class="secondary-btn"
                    @click="saveLocation"
                >
                    {{ $t("Edit") }}
                </loading-button>
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
import CustomPagination from "../../Components/Pagination.vue";
import SelectServiceModal from "../../Components/SelectServiceModal.vue";
import Licenses from "./Licenses.vue";
import { mapGetters } from "vuex";
import SelectPriceList from "../../Components/SelectPriceList.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    async mounted() {
        const countries = await this.$store.dispatch(
            "countries/getAllCountries"
        );
        this.countries = countries?.data?.data;
        const defaultCountry = this.countries?.find(
            (country) => country.isDefault === true
        );
        this.form.country = defaultCountry?.id;
        this.isApiCalled = true;
        await this.$store.dispatch("termsOfPayment/list", {
            perPage: 25,
            page: 1,
        });
        const response = await this.$store.dispatch(
            "products/productsWithQuantity",
            {
                perPage: 25,
                page: 1,
                type: "service",
            }
        );
        this.services = response?.data?.products ?? [];
        if (!this.users.length) {
            await this.$store.dispatch("auth/list", {
                limit_start: 0,
                limit_count: 25,
            });
        }
        if (!this.slaLevels?.length) {
            await this.$store.dispatch("slaLevels/list");
        }
        if (!this.slaServiceTimes?.length) {
            await this.$store.dispatch("slaServiceTimes/list");
        }
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
                    .dispatch("companies/show", this.user.tenant_id)
                    .then((res) => {
                        this.form.partner = res?.data?.modelData ?? null;
                    });
            }
        }
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("companies", ["partners"]),
        ...mapGetters("auth", ["user", "users"]),
        ...mapGetters("periods", ["periods"]),
        ...mapGetters("companyEmployees", {
            employees: "users",
            count: "count",
        }),
        ...mapGetters("termsOfPayment", {
            termsOfPayment: "termsOfPayment",
        }),
        ...mapGetters("slaLevels", ["slaLevels"]),
        ...mapGetters("slaServiceTimes", ["slaServiceTimes"]),
        getSelectedItems() {
            return this.form.defaultServiceProduct
                ? [this.form.defaultServiceProduct]
                : [];
        },
    },
    components: {
        SelectServiceModal,
        CustomPagination,
        LoadingButton,
        SelectInput,
        TextInput,
        EditModal,
        MultiSelectInput,
        NumberInput,
        Licenses,
        SelectPriceList,
        PageHeader,
    },
    data() {
        return {
            showPassword: false,
            inputType: "password",
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Master Data",
                    to: "/companies",
                },
                {
                    text: this.$t("Customer"),
                    to: "/companies",
                },
                {
                    text: this.$t("Create"),
                    active: true,
                },
            ],
            isPriceListToggle: false,
            services: [],
            isApiCalled: false,
            employeeFormTemp: null,
            service: [], // even tough it is an array, it will always get a single service
            serviceToggle: false,
            currentPage: 1,
            start: 0,
            perPage: 25,
            company_id: "",
            countries: [],
            locationEditTemp: {}, //used for v-model in location edit modal
            employeeEditTemp: {}, //used for v-model in employee edit modal
            toggleEditLocationModal: false,
            toggleEditEmployeeModal: false,
            activeClasses: "active",
            inactiveClasses: "in-active",
            activeTab: "company",
            locations: {
                data: [],
                links: [],
            },
            form: {
                companyName: "",
                companyNumber: "0",
                externalOrderNumber: "",
                vatId: "",
                url: "",
                fax: "",
                status: "active",
                partner: "",
                type: "",
                phone: "",
                customerType: "customer",
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
                discount: 0,
                hourlyRate: 0,
                slaLevel: "",
                slaServiceTime: "",
                priceLists: [],
                defaultPaymentPeriod: "",
                mergePdfsOnDefault: false,
                applyReverseChange: false,
                isCustomerPortalOnboarding: false,
            },
            employeeForm: {
                companyId: "",
                title: "",
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
            selectedFile: null,
            base64Image: null
        };
    },
    watch:{
        serviceToggle(newVal) {
            if (newVal) {
                document.body.classList.add("modal-layout");
            } else {
                document.body.classList.remove("modal-layout");
            }
        },
    },
    methods: {
        handleFileChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.selectedFile = file;
                this.convertToBase64(file);
            }
        },
        convertToBase64(file) {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = (e) => {
                const requiredData = e.target.result.split(",");
                this.base64Image = {
                    name: file.name,
                    size: file.size,
                    base64: requiredData[1],
                };
            };
            reader.readAsDataURL(file);
        },
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

        getSelectedPriceItems(items) {
            this.form.priceLists = items;
            this.isPriceListToggle = false;
        },
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
                    partner: "",
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
        addService(services) {
            if (services.length) {
                this.form.defaultServiceProduct = services?.[0] ?? "";
                if (this.form.defaultServiceProduct) {
                    this.form.defaultServiceDailyRate =
                        this.form.defaultServiceProduct?.dailyRate;
                    this.form.defaultServiceHourlyRate =
                        this.form.defaultServiceProduct?.hourlyRate;
                    this.form.hourlyRate =
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
            this.employeeEditTemp = { ...this.employees[index] };
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
                                termsOfPayment: this.form?.termsOfPayment?.id,
                                location_id: this.locations.data?.[0].id,
                                defaultServiceProduct:
                                    this.form?.defaultServiceProduct?.id,
                                slaLevel: this.form.slaLevel?.id ?? "",
                                slaServiceTime:
                                    this.form.slaServiceTime?.id ?? "",
                            },
                        });
                        this.activeTab = "licenses";
                        this.fetchLocations();
                        // this.sendEloRequest("updateCustomer");
                    } catch (e) {}
                } else {
                    try {
                        const response = await this.$store.dispatch(
                            "companies/create",
                            {
                                ...this.form,
                                attachment: this.base64Image,
                                partner: this.form.partner?.id,
                                termsOfPayment: this.form?.termsOfPayment?.id,
                                defaultServiceProduct:
                                    this.form?.defaultServiceProduct?.id,
                                slaLevel: this.form.slaLevel?.id ?? "",
                                slaServiceTime:
                                    this.form.slaServiceTime?.id ?? "",
                                defaultPaymentPeriod:
                                    this.form.defaultPaymentPeriod?.id ?? "",
                            }
                        );
                        this.company_id = response.data.company_id;
                        this.form.companyNumber = response.data.company_number;
                        this.activeTab = "licenses";
                        this.fetchLocations();
                        //  this.sendEloRequest("createCustomer");
                    } catch (e) {}
                }
            } else if (this.activeTab === "licenses") {
                this.activeTab = "locations";
            } else if (this.activeTab === "locations") {
                this.activeTab = "employees";
            } else if (this.activeTab === "employees") {
                window.location.replace("/companies");
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
        async addLocation() {
            try {
                this.$store.commit("isLoading", true);
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
            try {
                this.$store.commit("isLoading", true);
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
                        set_company_id: this.company_id,
                    });
                } else {
                    await this.$store.dispatch("auth/create", {
                        ...this.employeeForm,
                        types: ["customer"],
                        set_company_id: this.company_id,
                        mail: this.employeeForm.email,
                    });
                }
                this.employeeFormTemp = null;
                this.fetchEmployees(this.start, this.perPage);
                await this.sendEloRequest("createCustomerEmployee");
            } catch (e) {}
            this.employeeForm = {
                companyId: "",
                title: "",
                first_name: "",
                last_name: "",
                email: "",
                mobile: "",
                phone: "",
                fax: "",
                partner: "",
                position: "",
                department: "",
                location: "",
            };
        },
        async saveEmployee() {
            try {
                this.$store.commit("isLoading", true);
                const payload = {
                    id: this.employeeEditTemp.id,
                    ...this.employeeEditTemp,
                    types: ["customer"],
                    set_company_id: this.company_id,
                };
                delete payload["company_id"];
                await this.$store.dispatch("auth/update", payload);
                this.fetchEmployees(this.start, this.perPage);
                await this.sendEloRequest("updateCustomerEmployee");
            } catch (e) {}
            this.employeeEditTemp = {};
            this.toggleEditEmployeeModal = false;
        },
        handleChildEvent() {
            this.showPassword = !this.showPassword;
            this.inputType = this.showPassword ? "text" : "password";
        },
    },
};
</script>

<style scoped>
.disabled {
    color: lightgray;
    cursor: not-allowed;
}
</style>

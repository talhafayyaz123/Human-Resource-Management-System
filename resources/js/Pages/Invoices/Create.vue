<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $t("Configuration") }}</h3>
            </div>
            <div class="card-body">
                <div class="grid items-center grid-cols-3 gap-6">
                    <div class="w-full">
                        <div class="form-group">
                            <select-input
                                :required="true"
                                :label="$t('Category')"
                                v-model="form.category"
                            >
                                <option value="license">
                                    {{ $t("Software License") }}
                                </option>
                                <option value="maintenance">
                                    {{ $t("Software Maintenance") }}
                                </option>
                                <option value="service">
                                    {{ $t("Service") }}
                                </option>
                                <option value="ams">
                                    {{ $t("Application Management Service") }}
                                </option>
                                <option value="hosting">
                                    {{ $t("Hosting") }}
                                </option>
                                <option value="cloud-software">
                                    {{ $t("Cloud") }}
                                </option>
                            </select-input>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <select-input
                                :key="form.invoiceType"
                                :required="true"
                                :isReadOnly="$route.query.performanceRecord"
                                v-model="form.invoiceType"
                                :label="$t('Invoice Type')"
                                :error="errors.invoiceType"
                            >
                                <option
                                    v-for="type in invoiceTypes"
                                    :key="type"
                                    :value="type"
                                >
                                    {{ type }}
                                </option>
                            </select-input>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <label class="form-label"
                                ><span style="color: red">*</span>&nbsp;{{
                                    $t("Due Date")
                                }}:</label
                            >
                            <datepicker
                                :style="dropdownStyles"
                                v-model="form.dueDate"
                                :clearable="false"
                                :enable-time-picker="false"
                                auto-apply
                                :close-on-auto-apply="false"
                                :class="errors.dueDate ? 'error' : ''"
                            />
                            <div v-if="errors?.dueDate" class="form-error">
                                {{ errors.dueDate }}
                            </div>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <select-input
                                :required="true"
                                v-model="form.receiverType"
                                :key="form.receiverType"
                                @update:model-value="form.contactPerson = ''"
                                :error="errors.receiverType"
                                :label="$t('Receiver Type')"
                            >
                                <option value="customer">
                                    {{ $t("Customer") }}
                                </option>
                                <option value="partner">
                                    {{ $t("Partner") }}
                                </option>
                            </select-input>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <MultiSelectInput
                                :isDisabled="form.performanceRecord?.id"
                                @open="fetchCustomers"
                                @update:model-value="
                                    fetchSystemsAndCompanyEmployees
                                "
                                v-model="form.customers"
                                :key="form.customers"
                                :required="true"
                                :textLabel="
                                    $t(
                                        form.receiverType === 'customer'
                                            ? 'Customer'
                                            : 'Partner'
                                    )
                                "
                                :placeholder="$t('Select Receiver')"
                                :options="
                                    form.receiverType === 'customer'
                                        ? companies.data
                                        : partners.data
                                "
                                :multiple="false"
                                label="companyName"
                                trackBy="id"
                                moduleName="companies"
                                :query="{ customerType: form.receiverType }"
                                :countStore="
                                    form.receiverType === 'partner'
                                        ? 'partnerCount'
                                        : 'count'
                                "
                                :error="errors.customers"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <MultiSelectInput
                                :customLabel="customLabel"
                                v-model="form.contactPerson"
                                :key="form.contactPerson"
                                :textLabel="$t('Contact Person')"
                                :options="users"
                                :multiple="false"
                                trackBy="id"
                                moduleName="companyEmployees"
                                :error="errors.contactPerson"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <select-input
                                :label="$t('System')"
                                v-model="form.systems"
                                :key="form.systems"
                                :textLabel="$t('System')"
                                :error="errors.systems"
                            >
                                <option
                                    v-for="system in systems"
                                    :key="system.id"
                                    :value="system"
                                >
                                    {{ system.systemNumber }}
                                </option>
                            </select-input>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <div
                                v-if="
                                    [
                                        'license',
                                        'service',
                                        'maintenance',
                                        'ams',
                                        'hosting',
                                        'cloud-software',
                                    ].includes(form.category)
                                "
                            >
                                <label class="form-label"
                                    ><span style="color: red">*</span>&nbsp;{{
                                        $t("Start Date")
                                    }}:</label
                                >
                                <datepicker
                                    :min-date="
                                        new Date(
                                            `${new Date().getFullYear()}-01-01`
                                        )
                                    "
                                    :style="dropdownStyles"
                                    :clearable="false"
                                    :enable-time-picker="false"
                                    auto-apply
                                    :close-on-auto-apply="false"
                                    v-model="form.startDate"
                                    :class="errors.startDate ? 'error' : ''"
                                />
                                <div
                                    v-if="errors?.startDate"
                                    class="form-error"
                                >
                                    {{ errors.startDate }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <div
                                v-if="
                                    [
                                        'license',
                                        'service',
                                        'maintenance',
                                        'ams',
                                        'hosting',
                                        'cloud-software',
                                    ].includes(form.category)
                                "
                            >
                                <label class="form-label"
                                    ><span style="color: red">*</span>&nbsp;{{
                                        $t("End Date")
                                    }}:</label
                                >
                                <datepicker
                                    :disabled="form.category === 'maintenance'"
                                    :clearable="false"
                                    :enable-time-picker="false"
                                    auto-apply
                                    :close-on-auto-apply="false"
                                    :style="dropdownStyles"
                                    v-model="form.endDate"
                                    :class="errors.endDate ? 'error' : ''"
                                />
                                <div v-if="errors?.endDate" class="form-error">
                                    {{ errors.endDate }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <div
                                v-if="
                                    [
                                        'license',
                                        'service',
                                        'maintenance',
                                        'ams',
                                        'hosting',
                                        'cloud-software',
                                    ].includes(form.category)
                                "
                            >
                                <label class="form-label"
                                    >{{ $t("Invoice Date") }}:</label
                                >
                                <datepicker
                                    :style="dropdownStyles"
                                    :clearable="false"
                                    :enable-time-picker="false"
                                    auto-apply
                                    :close-on-auto-apply="false"
                                    v-model="form.draftStatusChangeDate"
                                    :class="
                                        errors.draftStatusChangeDate
                                            ? 'error'
                                            : ''
                                    "
                                />
                                <div
                                    v-if="errors?.draftStatusChangeDate"
                                    class="form-error"
                                >
                                    {{ errors.draftStatusChangeDate }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="form.externalOrderNumber"
                                :error="errors.externalOrderNumber"
                                :label="$t('External Order Number')"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <select-input
                                :key="form.recreateAfter"
                                :required="true"
                                v-model="form.recreateAfter"
                                :label="$t('Recreate After')"
                                :error="errors.recreateAfter"
                            >
                                <option value=""></option>
                                <option value="2W">2 Weeks</option>
                                <option value="1M">1 Month</option>
                                <option value="3M">3 Months</option>
                                <option value="6M">6 Months</option>
                                <option value="12M">12 Months</option>
                            </select-input>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <MultiSelectInput
                                v-if="form.customers?.id"
                                v-model="form.projectId"
                                :textLabel="$t('Project Reference')"
                                :options="projects ?? []"
                                :multiple="false"
                                label="name"
                                trackBy="id"
                                moduleName="projects"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <MultiSelectInput
                                v-if="
                                    showPerformanceRecord &&
                                    form.category === 'service'
                                "
                                @update:model-value="performanceRecordAdded"
                                :isDisabled="$route.query.performanceRecord"
                                :textLabel="$t('Performance Record')"
                                v-model="form.performanceRecord"
                                :options="performanceRecords?.data ?? []"
                                :multiple="false"
                                label="performanceNumber"
                                trackBy="id"
                                moduleName="performanceRecord"
                            />
                        </div>
                    </div>
                    <div class="w-full" v-if="form.category === 'service'">
                        <div class="form-group">
                            <p>{{ $t("Grouped By") }}:</p>
                            <div class="mt-2">
                                <div class="flex">
                                    <input
                                        name="projects-grouped-by"
                                        id="nothing"
                                        v-model="form.groupedBy"
                                        value="nothing"
                                        type="radio"
                                    />
                                    <label class="ml-1" for="">{{
                                        $t("Nothing")
                                    }}</label>
                                </div>
                                <div class="flex">
                                    <input
                                        name="projects-grouped-by"
                                        id="category"
                                        v-model="form.groupedBy"
                                        value="category"
                                        type="radio"
                                    />
                                    <label class="ml-1" for="">{{
                                        $t("Category")
                                    }}</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-full">
                        <div class="form-group">
                            <!-- Project Reference -->
                            <MultiSelectInput
                               v-if="form.invoiceType==='invoice-storno' "
                                v-model="referenceInvoiceId"
                                :key="referenceInvoiceId"
                                :textLabel="$t('Reference Invoices')"
                                :options="referenceInvoices ?? []"
                                :multiple="false"
                                label="invoice_number"
                                trackBy="id"
                                moduleName="referenceInvoices"
                                
                            />
                        </div>
                    </div>

                    <div class="w-full">
                        <div class="form-group">
                            <input
                                v-model="form.applyReverseChange"
                                :checked="form.applyReverseChange"
                                @change="recompute"
                                class="mr-2"
                                type="checkbox"
                                id="apply-reverse-change"
                            />
                            <label for="apply-reverse-change">{{
                                $t("Apply reverse charge")
                            }}</label>
                        </div>
                    </div>
                    <div class="w-full" v-if="form.category === 'maintenance'">
                        <div class="form-group">
                            <label
                                class="self-center"
                                for="firstYearMaintenance"
                                >{{ $t("First Year Maintenance") }}</label
                            >
                            <input
                                onclick="return false;"
                                class="ml-5 self-center mt-1"
                                id="firstYearMaintenance"
                                type="checkbox"
                                :checked="firstYearMaintenance"
                            />
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap">
                    <!-- <div
                      v-if="module === 'invoiceTemplates'"
                      class="pr-6 w-full lg:w-1/3 mb-8"
                  >
                      <label class="form-label"
                          ><span style="color: red">*</span>&nbsp;{{
                              $t("Next Create Date")
                          }}:</label
                      >
                      <datepicker
                          :style="dropdownStyles"
                          :clearable="false"
                          :enable-time-picker="false"
                          auto-apply
                          :close-on-auto-apply="false"
                          v-model="form.nextCreateDate"
                          :class="errors.nextCreateDate ? 'error' : ''"
                      />
                      <div v-if="errors?.nextCreateDate" class="form-error">
                          {{ errors.nextCreateDate }}
                      </div>
                  </div> -->
                </div>
            </div>
        </div>

        <form @submit.prevent="store">
            <div class="flex justify-between mb-8">
                <h6 v-if="form.customers?.companyName">
                    {{ form.customers?.companyName }}
                    <br />
                    <span v-if="form.contactPerson"
                        >{{ form.contactPerson?.first_name }}
                        {{ form.contactPerson?.last_name }}
                        <br />
                    </span>
                    {{ form.customers?.address }}
                    <br />
                    {{ form.customers?.code }} &nbsp; {{ form.customers?.city }}
                    <br />
                    {{ form.customers?.country }}
                    <br />
                </h6>
            </div>

            <div class="card my-5">
                <div class="card-header">
                    <h3 class="card-title flex justify-between">
                        <h1 class="secondary-color text-2xl">
                            Draft {{ invoiceTypeEnums[this.form.invoiceType] }}
                        </h1>
                        <h6>
                            {{
                                $dateFormatter(
                                    formatDateLite(new Date()),
                                    globalLanguage
                                )
                            }}
                        </h6>
                    </h3>
                </div>
                <div class="card-body">
                    <h1 class="secondary-color text-2xl capitalize mb-8">
                        {{ categoryDateString }}
                    </h1>
                    <div
                        class="grid gap-2"
                        style="grid-template-columns: repeat(auto-fit, 500px)"
                    >
                        <div class="mb-8" style="color: #2957a4">
                            <div
                                class="grid gap-2"
                                style="
                                    grid-template-columns: repeat(
                                        auto-fit,
                                        200px
                                    );
                                "
                            >
                                <b>{{ $t("Customer Nr.") }}</b>
                                <p>
                                    {{ form?.customers?.companyNumber ?? "-" }}
                                </p>
                            </div>
                            <div
                                class="grid gap-2"
                                style="
                                    grid-template-columns: repeat(
                                        auto-fit,
                                        200px
                                    );
                                "
                            >
                                <b>{{ $t("Project Nr.") }}</b>
                                <p>{{ form?.projectId?.projectId ?? "-" }}</p>
                            </div>
                            <div
                                class="grid gap-2"
                                style="
                                    grid-template-columns: repeat(
                                        auto-fit,
                                        200px
                                    );
                                "
                            >
                                <b>{{ $t("Invoice Nr.") }}</b>
                                <p>
                                    Draft
                                    {{
                                        invoiceTypeEnums[this.form.invoiceType]
                                    }}
                                </p>
                            </div>
                        </div>
                        <div class="mb-8" style="color: #2957a4">
                            <div
                                v-if="user"
                                class="grid gap-2"
                                style="
                                    grid-template-columns: repeat(
                                        auto-fit,
                                        200px
                                    );
                                "
                            >
                                <b>{{ $t("Created By") }}:</b>
                                <p>
                                    {{ user?.first_name }} {{ user?.last_name }}
                                </p>
                            </div>
                            <div
                                v-if="form?.dueDate"
                                class="grid gap-2"
                                style="
                                    grid-template-columns: repeat(
                                        auto-fit,
                                        200px
                                    );
                                "
                            >
                                <b>{{ $t("Due Date") }}:</b>
                                <p>
                                    {{
                                        $dateFormatter(
                                            formatDateLite(
                                                new Date(form?.dueDate)
                                            ),
                                            globalLanguage
                                        )
                                    }}
                                </p>
                            </div>
                            <div
                                class="grid gap-2"
                                style="
                                    grid-template-columns: repeat(
                                        auto-fit,
                                        200px
                                    );
                                "
                            >
                                <b>{{ $t("System number") }}:</b>
                                <p>{{ form.systems?.systemNumber ?? "-" }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card my-5">
                <div class="flex flex-wrap py-5">
                    <div class="sm:px-6 w-full">
                        <div style="padding-top: 0" class="bg-white py-4">
                            <div
                                style="text-align: end"
                                class="system-customer-error"
                            >
                                <div
                                    v-if="errors?.customers"
                                    class="form-error"
                                >
                                    {{ errors.customers }}
                                </div>
                                <div v-if="errors?.systems" class="form-error">
                                    {{ errors.systems }}
                                </div>
                            </div>
                            <div class="sm:flex items-center justify-between">
                                <button
                                    type="button"
                                    @click="toggleProductsModal()"
                                    class="mt-4 sm:mt-0 inline-flex items-start justify-start px-6 py-3 docsHeroColorBlue rounded"
                                >
                                    <p
                                        class="text-sm font-medium leading-none text-white"
                                    >
                                        {{ $t("Add Products") }}
                                    </p>
                                </button>
                            </div>

                            <div
                                v-if="form.category === 'license'"
                                class="mt-7 overflow-x-auto"
                            >
                                <table
                                    class="w-full whitespace-nowrap doc-table"
                                    style="table-layout: auto"
                                >
                                    <thead>
                                        <tr>
                                            <th>
                                                {{ $t("POS") }}
                                            </th>
                                            <th>
                                                {{ $t("Article number") }}
                                            </th>
                                            <th>
                                                {{ $t("Product name") }}
                                            </th>
                                            <th>
                                                {{ $t("Quantity") }}
                                            </th>
                                            <th>
                                                {{ $t("Product price") }}
                                            </th>
                                            <th>{{ $t("Discount") }}(%)</th>
                                            <th>
                                                {{ $t("Netto total") }}
                                            </th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <template
                                            v-for="(
                                                product, index
                                            ) in softwareLicences"
                                            :key="index"
                                            :tabindex="index"
                                        >
                                            <tr
                                                class="focus:outline-none h-16 border border-gray-100 rounded"
                                            >
                                                <td>
                                                    {{ index + 1 }}
                                                </td>
                                                <td>
                                                    <div>
                                                        <input
                                                            class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:border-purple-500"
                                                            type="text"
                                                            :style="
                                                                inputWidth(
                                                                    index,
                                                                    'articleNumber',
                                                                    'softwareLicences'
                                                                )
                                                            "
                                                            :class="{
                                                                Perror: errors[
                                                                    `products.${index}.articleNumber`
                                                                ],
                                                            }"
                                                            v-model="
                                                                product.articleNumber
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="table-align">
                                                        <input
                                                            v-if="
                                                                product.isProductNameEdit
                                                            "
                                                            class="appearance-none border-2 border-gray-200 rounded input-width py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="text"
                                                            name="productName"
                                                            v-model="
                                                                product.name
                                                            "
                                                            :class="{
                                                                Perror: errors[
                                                                    `products.${index}.productName`
                                                                ],
                                                            }"
                                                        />

                                                        <p v-else>
                                                            {{ product.name }}
                                                        </p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input
                                                            class="appearance-none border-2 border-gray-200 rounded input-width py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            @input="
                                                                calculateProductValue(
                                                                    index,
                                                                    'softwareLicences',
                                                                    true,
                                                                    $event
                                                                )
                                                            "
                                                            name="quantity"
                                                            :class="{
                                                                Perror: errors[
                                                                    `products.${index}.quantity`
                                                                ],
                                                            }"
                                                            v-model="
                                                                product.quantity
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="md:w-2/3">
                                                        <number-input
                                                            class="appearance-none border border-2 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="text"
                                                            v-model="
                                                                product.salePrice
                                                            "
                                                            :allowNegative="
                                                                true
                                                            "
                                                            @inputEvent="
                                                                calculateProductValue(
                                                                    index,
                                                                    'softwareLicences',
                                                                    true,
                                                                    $event
                                                                )
                                                            "
                                                            name="salePrice"
                                                            :class="{
                                                                Perror: errors[
                                                                    `products.${index}.salePrice`
                                                                ],
                                                            }"
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input
                                                            class="appearance-none border-2 border-gray-200 rounded input-width py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="text"
                                                            @input="
                                                                calculateProductValue(
                                                                    index,
                                                                    'softwareLicences',
                                                                    true,
                                                                    $event
                                                                )
                                                            "
                                                            name="discount"
                                                            v-model="
                                                                product.discount
                                                            "
                                                            :class="{
                                                                Perror: errors[
                                                                    `products.${index}.discount`
                                                                ],
                                                            }"
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <number-input
                                                            class="appearance-none rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="text"
                                                            @inputEvent="
                                                                calculateProductValue(
                                                                    index,
                                                                    'softwareLicences',
                                                                    true,
                                                                    $event
                                                                )
                                                            "
                                                            :allowNegative="
                                                                true
                                                            "
                                                            :isReadonly="false"
                                                            name="priceWithoutTax"
                                                            v-model="
                                                                product.priceWithoutTax
                                                            "
                                                            :class="{
                                                                Perror: errors[
                                                                    `products.${index}.discount`
                                                                ],
                                                            }"
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <font-awesome-icon
                                                        @click="
                                                            removeOption(index)
                                                        "
                                                        class="cursor-pointer cross"
                                                        icon="fa-solid fa-xmark"
                                                    />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="pl-6" colspan="8">
                                                    <div
                                                        @click="
                                                            toggleAdditionalDescriptionDropdowns(
                                                                product.productInvoiceId,
                                                                index,
                                                                additionalDescriptionToggled[
                                                                    product
                                                                        .productInvoiceId
                                                                ]
                                                                    ? 'remove'
                                                                    : 'add',
                                                                'softwareLicences'
                                                            )
                                                        "
                                                        class="add-additional-btn"
                                                    >
                                                        <font-awesome-icon
                                                            :class="`cursor-pointer cross mr-2 self-center text-${
                                                                additionalDescriptionToggled[
                                                                    product
                                                                        .productInvoiceId
                                                                ]
                                                                    ? 'red'
                                                                    : 'blue'
                                                            }-500`"
                                                            :icon="`fa-solid fa-circle-${
                                                                additionalDescriptionToggled[
                                                                    product
                                                                        .productInvoiceId
                                                                ]
                                                                    ? 'minus'
                                                                    : 'plus'
                                                            }`"
                                                        />
                                                        <p
                                                            :class="`text-sm text-${
                                                                additionalDescriptionToggled[
                                                                    product
                                                                        .productInvoiceId
                                                                ]
                                                                    ? 'red'
                                                                    : 'blue'
                                                            }-500`"
                                                        >
                                                            {{
                                                                $t(
                                                                    `${
                                                                        additionalDescriptionToggled[
                                                                            product
                                                                                .productInvoiceId
                                                                        ]
                                                                            ? "Remove"
                                                                            : "Add"
                                                                    } Additional Description`
                                                                )
                                                            }}
                                                        </p>
                                                    </div>
                                                    <div
                                                        v-if="
                                                            additionalDescriptionToggled[
                                                                product
                                                                    .productInvoiceId
                                                            ]
                                                        "
                                                    >
                                                        <TextAreaInput
                                                            style="
                                                                overflow: visible !important;
                                                            "
                                                            v-model="
                                                                product.additionalDescription
                                                            "
                                                            class="pb-1 pr-6 w-full"
                                                            :label="
                                                                $t(
                                                                    'Additional Description'
                                                                )
                                                            "
                                                            :error="
                                                                errors[
                                                                    'additionalDescription'
                                                                ]
                                                            "
                                                        ></TextAreaInput>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                                <br />
                            </div>
                            <div
                                v-if="
                                    form.category === 'license' &&
                                    Object.keys(softwareLicences).length > 0
                                "
                                style="
                                    flex-direction: column;
                                    align-items: end;
                                    justify-content: flex-end;
                                "
                            >
                                <div>
                                    <div
                                        class="grid gap-2 justify-end"
                                        style="
                                            grid-template-columns: repeat(
                                                auto-fit,
                                                100px
                                            );
                                        "
                                    >
                                        <p>{{ $t("Netto") }}:</p>
                                        <p
                                            :class="[
                                                globalLanguage === 'de'
                                                    ? 'text-end'
                                                    : '',
                                            ]"
                                        >
                                            {{
                                                $formatter(
                                                    totalAmountWithoutTaxLicences,
                                                    globalLanguage
                                                )
                                            }}
                                        </p>
                                    </div>
                                    <div
                                        class="grid gap-2 mt-1 justify-end"
                                        style="
                                            grid-template-columns: repeat(
                                                auto-fit,
                                                100px
                                            );
                                        "
                                        v-for="(
                                            item, index
                                        ) in softwareLicencesTax"
                                        :key="index"
                                    >
                                        <h1>
                                            {{ item.percent }}% {{ $t("Tax") }}:
                                        </h1>
                                        <h1
                                            :class="[
                                                globalLanguage === 'de'
                                                    ? 'text-end'
                                                    : '',
                                            ]"
                                        >
                                            {{
                                                $formatter(
                                                    item.amount.toFixed(2),
                                                    globalLanguage
                                                )
                                            }}
                                        </h1>
                                    </div>
                                    <div
                                        class="grid gap-2 mt-2 justify-end font-bold bg-gray-300"
                                        style="
                                            grid-template-columns: repeat(
                                                auto-fit,
                                                100px
                                            );
                                            background-image: -webkit-linear-gradient(
                                                left,
                                                white,
                                                white 81%,
                                                transparent 70%,
                                                transparent 100%
                                            );
                                        "
                                    >
                                        <p>{{ $t("Total") }}:</p>
                                        <p
                                            :class="[
                                                globalLanguage === 'de'
                                                    ? 'text-end'
                                                    : '',
                                            ]"
                                        >
                                            {{
                                                $formatter(
                                                    totalAmountLicences,
                                                    globalLanguage
                                                )
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div
                                v-else-if="form.category === 'maintenance'"
                                class="mt-7 overflow-x-auto"
                            >
                                <table
                                    class="w-full whitespace-nowrap doc-table"
                                    style="table-layout: auto"
                                >
                                    <thead>
                                        <tr>
                                            <th>
                                                {{ $t("POS") }}
                                            </th>
                                            <th>
                                                {{ $t("Article number") }}
                                            </th>
                                            <th>
                                                {{ $t("Product name") }}
                                            </th>
                                            <th>
                                                {{ $t("Quantity") }}
                                            </th>
                                            <th>
                                                {{ $t("Price") }}
                                            </th>
                                            <th>
                                                {{ $t("Product Price") }}
                                            </th>
                                            <th>
                                                {{ $t("Maintenance rate(%)") }}
                                            </th>
                                            <th>
                                                {{ $t("Netto total") }}
                                            </th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <template
                                            v-for="(
                                                product, index
                                            ) in softwareMaintenance"
                                            :key="index"
                                            :tabindex="index"
                                        >
                                            <tr
                                                class="focus:outline-none h-16 border border-gray-100 rounded"
                                            >
                                                <td>
                                                    {{ index + 1 }}
                                                </td>
                                                <td>
                                                    <div>
                                                        <input
                                                            class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:border-purple-500"
                                                            type="text"
                                                            :style="
                                                                inputWidth(
                                                                    index,
                                                                    'articleNumber',
                                                                    'softwareMaintenance'
                                                                )
                                                            "
                                                            :class="{
                                                                Perror: errors[
                                                                    `products.${index}.articleNumber`
                                                                ],
                                                            }"
                                                            v-model="
                                                                product.articleNumber
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="table-align">
                                                        <input
                                                            v-if="
                                                                product.isProductNameEdit
                                                            "
                                                            class="appearance-none border-2 border-gray-200 rounded input-width py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="text"
                                                            name="productName"
                                                            v-model="
                                                                product.name
                                                            "
                                                            :class="{
                                                                Perror: errors[
                                                                    `products.${index}.productName`
                                                                ],
                                                            }"
                                                        />

                                                        <p v-else>
                                                            {{ product.name }}
                                                        </p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input
                                                            class="appearance-none border-2 border-gray-200 rounded input-width py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            @input="
                                                                calculateProductValue(
                                                                    index,
                                                                    'softwareMaintenance',
                                                                    true,
                                                                    $event
                                                                )
                                                            "
                                                            name="quantity"
                                                            :class="{
                                                                Perror: errors[
                                                                    `products.${index}.quantity`
                                                                ],
                                                            }"
                                                            v-model="
                                                                product.quantity
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <number-input
                                                            @inputEvent="
                                                                salePriceChangedMaintenance(
                                                                    index
                                                                )
                                                            "
                                                            :allowNegative="
                                                                true
                                                            "
                                                            class="appearance-none rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            name="price"
                                                            :class="{
                                                                Perror: errors[
                                                                    `products.${index}.price`
                                                                ],
                                                            }"
                                                            v-model="
                                                                product.baseMaintenancePrice
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="md:w-2/3">
                                                        <number-input
                                                            @update:model-value="
                                                                totalSalePriceChangedMaintenance(
                                                                    index
                                                                )
                                                            "
                                                            :allowNegative="
                                                                true
                                                            "
                                                            class="appearance-none rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            name="totalSalePriceAdjustedForQuantity"
                                                            v-model="
                                                                product.totalSalePriceAdjustedForQuantity
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input
                                                            class="appearance-none border-2 border-gray-200 rounded input-width py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="text"
                                                            v-model="
                                                                product.maintenanceRate
                                                            "
                                                            @input="
                                                                calculateProductValue(
                                                                    index,
                                                                    'softwareMaintenance',
                                                                    true,
                                                                    $event
                                                                )
                                                            "
                                                            name="maintenanceRate"
                                                            :class="{
                                                                Perror: errors[
                                                                    `products.${index}.salePrice`
                                                                ],
                                                            }"
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <number-input
                                                            class=""
                                                            type="text"
                                                            v-model="
                                                                product.maintenancePrice
                                                            "
                                                            :allowNegative="
                                                                true
                                                            "
                                                            @inputEvent="
                                                                calculateProductValue(
                                                                    index,
                                                                    'softwareMaintenance',
                                                                    true,
                                                                    $event
                                                                )
                                                            "
                                                            name="maintenancePrice"
                                                            :class="{
                                                                Perror: errors[
                                                                    `products.${index}.salePrice`
                                                                ],
                                                            }"
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <font-awesome-icon
                                                        @click="
                                                            removeOption(index)
                                                        "
                                                        class="cursor-pointer cross"
                                                        icon="fa-solid fa-xmark"
                                                    />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="pl-6" colspan="9">
                                                    <div
                                                        @click="
                                                            toggleAdditionalDescriptionDropdowns(
                                                                product.productInvoiceId,
                                                                index,
                                                                additionalDescriptionToggled[
                                                                    product
                                                                        .productInvoiceId
                                                                ]
                                                                    ? 'remove'
                                                                    : 'add',
                                                                'softwareMaintenance'
                                                            )
                                                        "
                                                        class="add-additional-btn"
                                                    >
                                                        <font-awesome-icon
                                                            :class="`cursor-pointer cross mr-2 self-center text-${
                                                                additionalDescriptionToggled[
                                                                    product
                                                                        .productInvoiceId
                                                                ]
                                                                    ? 'red'
                                                                    : 'blue'
                                                            }-500`"
                                                            :icon="`fa-solid fa-circle-${
                                                                additionalDescriptionToggled[
                                                                    product
                                                                        .productInvoiceId
                                                                ]
                                                                    ? 'minus'
                                                                    : 'plus'
                                                            }`"
                                                        />
                                                        <p
                                                            :class="`text-sm text-${
                                                                additionalDescriptionToggled[
                                                                    product
                                                                        .productInvoiceId
                                                                ]
                                                                    ? 'red'
                                                                    : 'blue'
                                                            }-500`"
                                                        >
                                                            {{
                                                                $t(
                                                                    `${
                                                                        additionalDescriptionToggled[
                                                                            product
                                                                                .productInvoiceId
                                                                        ]
                                                                            ? "Remove"
                                                                            : "Add"
                                                                    } Additional Description`
                                                                )
                                                            }}
                                                        </p>
                                                    </div>
                                                    <div
                                                        v-if="
                                                            additionalDescriptionToggled[
                                                                product
                                                                    .productInvoiceId
                                                            ]
                                                        "
                                                    >
                                                        <TextAreaInput
                                                            style="
                                                                overflow: visible !important;
                                                            "
                                                            v-model="
                                                                product.additionalDescription
                                                            "
                                                            class="pb-1 pr-6 w-full"
                                                            :label="
                                                                $t(
                                                                    'Additional Description'
                                                                )
                                                            "
                                                            :error="
                                                                errors[
                                                                    'additionalDescription'
                                                                ]
                                                            "
                                                        ></TextAreaInput>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                                <br />
                                <div
                                    v-if="
                                        Object.keys(softwareMaintenance)
                                            .length > 0
                                    "
                                    style="
                                        flex-direction: column;
                                        align-items: end;
                                        justify-content: flex-end;
                                    "
                                >
                                    <div>
                                        <div v-if="firstYearMaintenance">
                                            <div
                                                class="grid gap-2 justify-end"
                                                style="
                                                    grid-template-columns: repeat(
                                                        auto-fit,
                                                        100px
                                                    );
                                                "
                                            >
                                                <p>{{ $t("Subtotal") }}:</p>
                                                <p
                                                    :class="[
                                                        globalLanguage === 'de'
                                                            ? 'text-end'
                                                            : '',
                                                    ]"
                                                >
                                                    {{
                                                        $formatter(
                                                            totalMaintenancePrice,
                                                            globalLanguage,
                                                            "EUR",
                                                            false,
                                                            2,
                                                            2
                                                        )
                                                    }}
                                                </p>
                                            </div>
                                            <div
                                                class="grid gap-2 justify-end mb-5"
                                                style="
                                                    grid-template-columns: 215px 100px;
                                                "
                                            >
                                                <p>
                                                    {{
                                                        $t("Duration Discount")
                                                    }}
                                                    ({{
                                                        12 -
                                                        getMonthDifference(
                                                            form.startDate,
                                                            form.endDate
                                                        ) +
                                                        "/12"
                                                    }}):
                                                </p>
                                                <p
                                                    :class="[
                                                        globalLanguage === 'de'
                                                            ? 'text-end'
                                                            : '',
                                                    ]"
                                                >
                                                    {{
                                                        $formatter(
                                                            totalMaintenancePrice -
                                                                totalAmountWithoutTaxMaintenance,
                                                            globalLanguage,
                                                            "EUR",
                                                            false,
                                                            2,
                                                            2
                                                        )
                                                    }}
                                                </p>
                                            </div>
                                        </div>
                                        <div
                                            class="grid gap-2 justify-end"
                                            style="
                                                grid-template-columns: repeat(
                                                    auto-fit,
                                                    100px
                                                );
                                            "
                                        >
                                            <p>{{ $t("Netto") }}:</p>
                                            <p
                                                :class="[
                                                    globalLanguage === 'de'
                                                        ? 'text-end'
                                                        : '',
                                                ]"
                                            >
                                                {{
                                                    $formatter(
                                                        totalAmountWithoutTaxMaintenance,
                                                        globalLanguage
                                                    )
                                                }}
                                            </p>
                                        </div>
                                        <div
                                            class="grid gap-2 mt-1 justify-end"
                                            style="
                                                grid-template-columns: repeat(
                                                    auto-fit,
                                                    100px
                                                );
                                            "
                                            v-for="(
                                                item, index
                                            ) in softwareMaintenanceTax"
                                            :key="index"
                                        >
                                            <h1>
                                                {{ item.percent }}%
                                                {{ $t("Tax") }}:
                                            </h1>
                                            <h1
                                                :class="[
                                                    globalLanguage === 'de'
                                                        ? 'text-end'
                                                        : '',
                                                ]"
                                            >
                                                {{
                                                    $formatter(
                                                        item.amount.toFixed(2),
                                                        globalLanguage
                                                    )
                                                }}
                                            </h1>
                                        </div>
                                        <div
                                            class="grid gap-2 mt-2 justify-end font-bold bg-gray-300"
                                            style="
                                                grid-template-columns: repeat(
                                                    auto-fit,
                                                    100px
                                                );
                                                background-image: -webkit-linear-gradient(
                                                    left,
                                                    white,
                                                    white 81%,
                                                    transparent 70%,
                                                    transparent 100%
                                                );
                                            "
                                        >
                                            <p>{{ $t("Total") }}:</p>
                                            <p
                                                :class="[
                                                    globalLanguage === 'de'
                                                        ? 'text-end'
                                                        : '',
                                                ]"
                                            >
                                                {{
                                                    $formatter(
                                                        totalAmountMaintenance,
                                                        globalLanguage
                                                    )
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                v-else-if="
                                    form.category === 'service' &&
                                    form.groupedBy === 'nothing'
                                "
                                class="mt-7 overflow-x-auto"
                            >
                                <table
                                    class="w-full whitespace-nowrap doc-table"
                                    style="table-layout: auto"
                                >
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>
                                                {{ $t("Pos") }}
                                            </th>
                                            <th>
                                                {{ $t("Article Nr.") }}
                                            </th>
                                            <th>
                                                {{ $t("Name") }}
                                            </th>
                                            <th>
                                                {{
                                                    $t("Quantity") +
                                                    "/" +
                                                    $t("Unit")
                                                }}
                                            </th>
                                            <th>
                                                {{ $t("Hourly Rate") }}
                                            </th>
                                            <th>{{ $t("Discount") }}(%)</th>
                                            <th v-if="!form.applyReverseChange">
                                                {{ $t("Tax") }}(%)
                                            </th>
                                            <th v-if="!form.applyReverseChange">
                                                {{ $t("Tax Rate") }}
                                            </th>
                                            <th>
                                                {{ $t("Netto Total") }}
                                            </th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <template
                                            v-for="(service, index) in services"
                                            :key="'services-' + index"
                                            :tabindex="index"
                                        >
                                            <tr
                                                class="focus:outline-none h-16 border border-gray-100 rounded"
                                            >
                                                <td>
                                                    <input
                                                        class="relative z-10"
                                                        type="checkbox"
                                                        @input="
                                                            removeAllChildren(
                                                                index,
                                                                'service',
                                                                $event
                                                            )
                                                        "
                                                        v-model="
                                                            groupToggler[
                                                                'service'
                                                            ][service.id]
                                                        "
                                                    />
                                                </td>
                                                <td>
                                                    <div class="md:w-2/3">
                                                        <p>{{ index + 1 }}</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <p>
                                                            {{
                                                                service?.articleNumber
                                                            }}
                                                        </p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="table-align">
                                                        <input
                                                            v-if="
                                                                service.isProductNameEdit
                                                            "
                                                            class="appearance-none border-2 border-gray-200 rounded input-width py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="text"
                                                            name="productName"
                                                            v-model="
                                                                service.name
                                                            "
                                                            :class="{
                                                                Perror: errors[
                                                                    `products.${index}.productName`
                                                                ],
                                                            }"
                                                        />

                                                        <p v-else>
                                                            {{ service?.name }}
                                                        </p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div style="display: flex">
                                                        <input
                                                            style="
                                                                min-width: 5ch;
                                                                max-width: 8ch;
                                                            "
                                                            @input="
                                                                serviceQuantityChanged(
                                                                    $event,
                                                                    index
                                                                )
                                                            "
                                                            min="0"
                                                            max="9999"
                                                            class="appearance-none input-width border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                service.quantity
                                                            "
                                                            name="quantity"
                                                        />
                                                        <p
                                                            class="self-center ml-2"
                                                        >
                                                            {{
                                                                service?.unit
                                                                    ?.name
                                                            }}
                                                        </p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="flex">
                                                        <number-input
                                                            @inputEvent="
                                                                serviceQuantityChanged(
                                                                    $event,
                                                                    index
                                                                )
                                                            "
                                                            class="w-full appearance-none input-width rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            v-model="
                                                                service.hourlyRate
                                                            "
                                                            name="hourlyRate"
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="flex">
                                                        <input
                                                            style="
                                                                min-width: 8ch;
                                                                max-width: 8ch;
                                                            "
                                                            @input="
                                                                serviceQuantityChanged(
                                                                    $event,
                                                                    index
                                                                )
                                                            "
                                                            class="appearance-none border-2 border-gray-200 rounded py-2 input-width px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                service.discount
                                                            "
                                                            name="discount"
                                                        />
                                                    </div>
                                                </td>
                                                <td
                                                    v-if="
                                                        !form.applyReverseChange
                                                    "
                                                >
                                                    <div class="flex">
                                                        <input
                                                            style="
                                                                min-width: 8ch;
                                                                max-width: 8ch;
                                                            "
                                                            @input="
                                                                serviceQuantityChanged(
                                                                    $event,
                                                                    index
                                                                )
                                                            "
                                                            class="w-full appearance-none input-width border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                service.tax
                                                            "
                                                            name="tax"
                                                        />
                                                    </div>
                                                </td>
                                                <td
                                                    v-if="
                                                        !form.applyReverseChange
                                                    "
                                                >
                                                    <div class="flex">
                                                        <number-input
                                                            @inputEvent="
                                                                serviceTaxRateChanged(
                                                                    $event,
                                                                    index
                                                                )
                                                            "
                                                            class="input-width appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                service.taxRate
                                                            "
                                                            name="taxRate"
                                                            :allowNegative="
                                                                true
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="flex">
                                                        <number-input
                                                            @inputEvent="
                                                                serviceNettoTotalChanged(
                                                                    $event,
                                                                    index
                                                                )
                                                            "
                                                            class="input-width appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                service.nettoTotal
                                                            "
                                                            name="nettoTotal"
                                                            :allowNegative="
                                                                true
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <font-awesome-icon
                                                        @click="
                                                            removeOption(index)
                                                        "
                                                        class="cursor-pointer cross"
                                                        icon="fa-solid fa-xmark"
                                                    />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="pl-6" colspan="11">
                                                    <div
                                                        @click="
                                                            toggleAdditionalDescriptionDropdowns(
                                                                service.productInvoiceId,
                                                                index,
                                                                additionalDescriptionToggled[
                                                                    service
                                                                        .productInvoiceId
                                                                ]
                                                                    ? 'remove'
                                                                    : 'add',
                                                                'services'
                                                            )
                                                        "
                                                        class="add-additional-btn"
                                                    >
                                                        <font-awesome-icon
                                                            :class="`cursor-pointer cross mr-2 self-center text-${
                                                                additionalDescriptionToggled[
                                                                    service
                                                                        .productInvoiceId
                                                                ]
                                                                    ? 'red'
                                                                    : 'blue'
                                                            }-500`"
                                                            :icon="`fa-solid fa-circle-${
                                                                additionalDescriptionToggled[
                                                                    service
                                                                        .productInvoiceId
                                                                ]
                                                                    ? 'minus'
                                                                    : 'plus'
                                                            }`"
                                                        />
                                                        <p
                                                            :class="`text-sm text-${
                                                                additionalDescriptionToggled[
                                                                    service
                                                                        .productInvoiceId
                                                                ]
                                                                    ? 'red'
                                                                    : 'blue'
                                                            }-500`"
                                                        >
                                                            {{
                                                                $t(
                                                                    `${
                                                                        additionalDescriptionToggled[
                                                                            service
                                                                                .productInvoiceId
                                                                        ]
                                                                            ? "Remove"
                                                                            : "Add"
                                                                    } Additional Description`
                                                                )
                                                            }}
                                                        </p>
                                                    </div>
                                                    <div
                                                        v-if="
                                                            additionalDescriptionToggled[
                                                                service
                                                                    .productInvoiceId
                                                            ]
                                                        "
                                                    >
                                                        <TextAreaInput
                                                            style="
                                                                overflow: visible !important;
                                                            "
                                                            v-model="
                                                                service.additionalDescription
                                                            "
                                                            class="pb-1 pr-6 w-full"
                                                            :label="
                                                                $t(
                                                                    'Additional Description'
                                                                )
                                                            "
                                                            :error="
                                                                errors[
                                                                    'additionalDescription'
                                                                ]
                                                            "
                                                        ></TextAreaInput>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr
                                                v-if="
                                                    groupToggler['service'][
                                                        service.id
                                                    ]
                                                "
                                                class="bg-gray-100"
                                            >
                                                <th></th>
                                                <th>
                                                    {{ $t("Pos") }}
                                                </th>
                                                <th>
                                                    {{ $t("Article Nr.") }}
                                                </th>
                                                <th>
                                                    {{ $t("Name") }}
                                                </th>
                                                <th>
                                                    {{
                                                        $t("Quantity") +
                                                        "/" +
                                                        $t("Unit")
                                                    }}
                                                </th>
                                                <th>
                                                    {{
                                                        $t("Single Hourly Rate")
                                                    }}
                                                </th>
                                                <th>
                                                    {{ $t("Total Rate") }}
                                                </th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            <tr
                                                v-for="(
                                                    child, childIndex
                                                ) in service.children"
                                                :tabindex="childIndex"
                                                :key="childIndex"
                                                class="focus:outline-none h-16 border border-gray-100 rounded"
                                            >
                                                <td></td>
                                                <td>
                                                    <div>
                                                        <p>
                                                            {{ index + 1 }}.{{
                                                                childIndex + 1
                                                            }}
                                                        </p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <p>
                                                            {{
                                                                child?.articleNumber
                                                            }}
                                                        </p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="table-align">
                                                        <input
                                                            v-if="
                                                                child.isProductNameEdit
                                                            "
                                                            class="appearance-none border-2 border-gray-200 rounded input-width py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="text"
                                                            name="productName"
                                                            v-model="child.name"
                                                            :class="{
                                                                Perror: errors[
                                                                    `products.${index}.productName`
                                                                ],
                                                            }"
                                                        />

                                                        <p v-else>
                                                            {{ child?.name }}
                                                        </p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div style="display: flex">
                                                        <input
                                                            @input="
                                                                serviceQuantityChanged(
                                                                    $event,
                                                                    index,
                                                                    childIndex
                                                                )
                                                            "
                                                            min="0"
                                                            max="9999"
                                                            @keypress="
                                                                limitToPositiveNumbers
                                                            "
                                                            class="appearance-none w-16 input-width border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                child.quantity
                                                            "
                                                            name="quantity"
                                                        />
                                                        <p
                                                            class="self-center ml-2"
                                                        >
                                                            {{
                                                                child?.unit
                                                                    ?.name
                                                            }}
                                                        </p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="flex">
                                                        <number-input
                                                            @inputEvent="
                                                                serviceQuantityChanged(
                                                                    $event,
                                                                    index,
                                                                    childIndex
                                                                )
                                                            "
                                                            class="input-width appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                child.hourlyRate
                                                            "
                                                            name="hourlyRate"
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="flex">
                                                        <number-input
                                                            @inputEvent="
                                                                serviceQuantityChanged(
                                                                    $event,
                                                                    index,
                                                                    childIndex
                                                                )
                                                            "
                                                            :isReadonly="false"
                                                            class="appearance-none input-width w-24 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                child.totalRate
                                                            "
                                                            name="totalRate"
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <font-awesome-icon
                                                        @click="
                                                            removeChildOption(
                                                                index,
                                                                childIndex,
                                                                'service'
                                                            )
                                                        "
                                                        class="cursor-pointer cross"
                                                        icon="fa-solid fa-xmark"
                                                    />
                                                </td>
                                                <td></td>
                                                <td></td>

                                                <td></td>
                                            </tr>
                                            <tr
                                                v-if="
                                                    groupToggler['service'][
                                                        service.id
                                                    ]
                                                "
                                            >
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>
                                                    <div
                                                        @click="
                                                            toggleServiceChildren(
                                                                index
                                                            )
                                                        "
                                                        style="
                                                            display: flex !important;
                                                        "
                                                        class="mt-2 cursor-pointer text-blue-500"
                                                    >
                                                        <font-awesome-icon
                                                            class="cursor-pointer cross mr-2 self-center"
                                                            icon="fa-solid fa-circle-plus"
                                                        />
                                                        <p class="text-sm">
                                                            {{
                                                                $t(
                                                                    "Add Child Product"
                                                                )
                                                            }}
                                                        </p>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                                <br />
                                <div
                                    v-if="Object.keys(services).length > 0"
                                    style="
                                        flex-direction: column;
                                        align-items: end;
                                        justify-content: flex-end;
                                    "
                                >
                                    <div>
                                        <div
                                            class="grid gap-2 justify-end"
                                            style="
                                                grid-template-columns: repeat(
                                                    auto-fit,
                                                    100px
                                                );
                                            "
                                        >
                                            <p>{{ $t("Netto") }}:</p>
                                            <p
                                                :class="[
                                                    globalLanguage === 'de'
                                                        ? 'text-end'
                                                        : '',
                                                ]"
                                            >
                                                {{
                                                    $formatter(
                                                        computeNetto,
                                                        globalLanguage
                                                    )
                                                }}
                                            </p>
                                        </div>
                                        <div
                                            class="grid gap-2 mt-1 justify-end"
                                            style="
                                                grid-template-columns: repeat(
                                                    auto-fit,
                                                    100px
                                                );
                                            "
                                            v-for="(value, key) in computeMwst"
                                            :key="key"
                                        >
                                            <h1>{{ key }}% {{ $t("Tax") }}:</h1>
                                            <h1
                                                :class="[
                                                    globalLanguage === 'de'
                                                        ? 'text-end'
                                                        : '',
                                                ]"
                                            >
                                                {{
                                                    $formatter(
                                                        value,
                                                        globalLanguage
                                                    )
                                                }}
                                            </h1>
                                        </div>
                                        <div
                                            class="grid gap-2 mt-2 justify-end font-bold bg-gray-300"
                                            style="
                                                grid-template-columns: repeat(
                                                    auto-fit,
                                                    100px
                                                );
                                                background-image: -webkit-linear-gradient(
                                                    left,
                                                    white,
                                                    white 81%,
                                                    transparent 70%,
                                                    transparent 100%
                                                );
                                            "
                                        >
                                            <p>{{ $t("Total") }}:</p>
                                            <p
                                                :class="[
                                                    globalLanguage === 'de'
                                                        ? 'text-end'
                                                        : '',
                                                ]"
                                            >
                                                {{
                                                    $formatter(
                                                        computeSummary,
                                                        globalLanguage
                                                    )
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                v-else-if="
                                    form.category === 'service' &&
                                    form.groupedBy === 'category'
                                "
                                class="mt-7 overflow-x-auto"
                            >
                                <table
                                    class="w-full whitespace-nowrap doc-table"
                                    style="table-layout: auto"
                                >
                                    <thead>
                                        <tr>
                                            <th>
                                                {{ $t("Pos") }}
                                            </th>
                                            <th>
                                                {{ $t("Article Nr.") }}
                                            </th>
                                            <th>
                                                {{ $t("Name") }}
                                            </th>
                                            <th>
                                                {{
                                                    $t("Quantity") +
                                                    "/" +
                                                    $t("Unit")
                                                }}
                                            </th>
                                            <th>
                                                {{ $t("Hourly Rate") }}
                                            </th>
                                            <th>{{ $t("Discount") }}(%)</th>
                                            <th v-if="!form.applyReverseChange">
                                                {{ $t("Tax") }}(%)
                                            </th>
                                            <th v-if="!form.applyReverseChange">
                                                {{ $t("Tax Rate") }}
                                            </th>
                                            <th>
                                                {{ $t("Netto Total") }}
                                            </th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <template
                                            v-for="(
                                                category, index
                                            ) in categories"
                                            :key="'category-' + index"
                                            :tabindex="index"
                                        >
                                            <tr
                                                class="focus:outline-none h-16 border border-gray-100 rounded"
                                            >
                                                <td>
                                                    <div>
                                                        <p>{{ index + 1 }}</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p
                                                        v-for="product in category.products"
                                                        :key="
                                                            'product-' +
                                                            product.id
                                                        "
                                                    >
                                                        {{
                                                            product.articleNumber
                                                        }}
                                                    </p>
                                                </td>
                                                <td>
                                                    <div>
                                                        <p class="font-bold">
                                                            {{ category?.name }}
                                                        </p>
                                                        <li
                                                            class="ml-2 table-align"
                                                            v-for="(
                                                                product, index
                                                            ) in category.products"
                                                            :key="
                                                                'service-child-' +
                                                                index
                                                            "
                                                        >
                                                            <input
                                                                v-if="
                                                                    product.isProductNameEdit
                                                                "
                                                                class="appearance-none border-2 border-gray-200 rounded input-width py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                                type="text"
                                                                name="productName"
                                                                v-model="
                                                                    product.name
                                                                "
                                                                :class="{
                                                                    Perror: errors[
                                                                        `products.${index}.productName`
                                                                    ],
                                                                }"
                                                            />

                                                            <p v-else>
                                                                {{
                                                                    product?.name
                                                                }}
                                                            </p>
                                                        </li>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div style="display: flex">
                                                        <input
                                                            style="
                                                                min-width: 5ch;
                                                                max-width: 10ch;
                                                            "
                                                            @input="
                                                                serviceQuantityChangedCategory(
                                                                    $event,
                                                                    index
                                                                )
                                                            "
                                                            class="appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                category.quantity
                                                            "
                                                            name="quantity"
                                                        />
                                                        <p
                                                            class="self-center ml-2"
                                                        >
                                                            {{
                                                                category?.unit
                                                                    ?.name
                                                            }}
                                                        </p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="flex">
                                                        <number-input
                                                            @inputEvent="
                                                                serviceQuantityChangedCategory(
                                                                    $event,
                                                                    index
                                                                )
                                                            "
                                                            class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                category.hourlyRate
                                                            "
                                                            name="hourlyRate"
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="flex">
                                                        <input
                                                            style="
                                                                min-width: 8ch;
                                                                max-width: 8ch;
                                                            "
                                                            @input="
                                                                serviceQuantityChangedCategory(
                                                                    $event,
                                                                    index
                                                                )
                                                            "
                                                            class="w-full appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                category.discount
                                                            "
                                                            name="discount"
                                                        />
                                                    </div>
                                                </td>
                                                <td
                                                    v-if="
                                                        !form.applyReverseChange
                                                    "
                                                >
                                                    <div class="flex">
                                                        <input
                                                            style="
                                                                min-width: 8ch;
                                                                max-width: 8ch;
                                                            "
                                                            @input="
                                                                serviceQuantityChangedCategory(
                                                                    $event,
                                                                    index
                                                                )
                                                            "
                                                            class="w-full appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                category.tax
                                                            "
                                                            name="tax"
                                                        />
                                                    </div>
                                                </td>
                                                <td
                                                    v-if="
                                                        !form.applyReverseChange
                                                    "
                                                >
                                                    <div class="flex">
                                                        <number-input
                                                            @inputEvent="
                                                                serviceTaxRateChangedCategory(
                                                                    $event,
                                                                    index
                                                                )
                                                            "
                                                            class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                category.taxRate
                                                            "
                                                            name="taxRate"
                                                            :allowNegative="
                                                                true
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="flex">
                                                        <number-input
                                                            @inputEvent="
                                                                serviceNettoTotalChangedCategory(
                                                                    $event,
                                                                    index
                                                                )
                                                            "
                                                            class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                category.nettoTotal
                                                            "
                                                            name="nettoTotal"
                                                            :allowNegative="
                                                                true
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <font-awesome-icon
                                                        @click="
                                                            removeOption(index)
                                                        "
                                                        class="cursor-pointer cross"
                                                        icon="fa-solid fa-xmark"
                                                    />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="pl-6" colspan="10">
                                                    <div
                                                        @click="
                                                            toggleAdditionalDescriptionDropdowns(
                                                                category.productInvoiceId,
                                                                index,
                                                                additionalDescriptionToggled[
                                                                    category
                                                                        .productInvoiceId
                                                                ]
                                                                    ? 'remove'
                                                                    : 'add',
                                                                'categories'
                                                            )
                                                        "
                                                        class="add-additional-btn"
                                                    >
                                                        <font-awesome-icon
                                                            :class="`cursor-pointer cross mr-2 self-center text-${
                                                                additionalDescriptionToggled[
                                                                    category
                                                                        .productInvoiceId
                                                                ]
                                                                    ? 'red'
                                                                    : 'blue'
                                                            }-500`"
                                                            :icon="`fa-solid fa-circle-${
                                                                additionalDescriptionToggled[
                                                                    category
                                                                        .productInvoiceId
                                                                ]
                                                                    ? 'minus'
                                                                    : 'plus'
                                                            }`"
                                                        />
                                                        <p
                                                            :class="`text-sm text-${
                                                                additionalDescriptionToggled[
                                                                    category
                                                                        .productInvoiceId
                                                                ]
                                                                    ? 'red'
                                                                    : 'blue'
                                                            }-500`"
                                                        >
                                                            {{
                                                                $t(
                                                                    `${
                                                                        additionalDescriptionToggled[
                                                                            category
                                                                                .productInvoiceId
                                                                        ]
                                                                            ? "Remove"
                                                                            : "Add"
                                                                    } Additional Description`
                                                                )
                                                            }}
                                                        </p>
                                                    </div>
                                                    <div
                                                        v-if="
                                                            additionalDescriptionToggled[
                                                                category
                                                                    .productInvoiceId
                                                            ]
                                                        "
                                                    >
                                                        <TextAreaInput
                                                            style="
                                                                overflow: visible !important;
                                                            "
                                                            v-model="
                                                                category.additionalDescription
                                                            "
                                                            class="pb-1 pr-6 w-full"
                                                            :label="
                                                                $t(
                                                                    'Additional Description'
                                                                )
                                                            "
                                                            :error="
                                                                errors[
                                                                    'additionalDescription'
                                                                ]
                                                            "
                                                        ></TextAreaInput>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                                <br />
                                <div
                                    v-if="Object.keys(categories).length > 0"
                                    style="
                                        flex-direction: column;
                                        align-items: end;
                                        justify-content: flex-end;
                                    "
                                >
                                    <div>
                                        <div
                                            class="grid gap-2 justify-end"
                                            style="
                                                grid-template-columns: repeat(
                                                    auto-fit,
                                                    100px
                                                );
                                            "
                                        >
                                            <p>{{ $t("Netto") }}:</p>
                                            <p
                                                :class="[
                                                    globalLanguage === 'de'
                                                        ? 'text-end'
                                                        : '',
                                                ]"
                                            >
                                                {{
                                                    $formatter(
                                                        computeNettoCategory,
                                                        globalLanguage
                                                    )
                                                }}
                                            </p>
                                        </div>
                                        <div
                                            class="grid gap-2 mt-1 justify-end"
                                            style="
                                                grid-template-columns: repeat(
                                                    auto-fit,
                                                    100px
                                                );
                                            "
                                            v-for="(
                                                value, key
                                            ) in computeMwstCategory"
                                            :key="key"
                                        >
                                            <h1>{{ key }}% {{ $t("Tax") }}:</h1>
                                            <h1
                                                :class="[
                                                    globalLanguage === 'de'
                                                        ? 'text-end'
                                                        : '',
                                                ]"
                                            >
                                                {{
                                                    $formatter(
                                                        value,
                                                        globalLanguage
                                                    )
                                                }}
                                            </h1>
                                        </div>
                                        <div
                                            class="grid gap-2 mt-2 justify-end font-bold bg-gray-300"
                                            style="
                                                grid-template-columns: repeat(
                                                    auto-fit,
                                                    100px
                                                );
                                                background-image: -webkit-linear-gradient(
                                                    left,
                                                    white,
                                                    white 81%,
                                                    transparent 70%,
                                                    transparent 100%
                                                );
                                            "
                                        >
                                            <p>{{ $t("Total") }}:</p>
                                            <p
                                                :class="[
                                                    globalLanguage === 'de'
                                                        ? 'text-end'
                                                        : '',
                                                ]"
                                            >
                                                {{
                                                    $formatter(
                                                        computeSummaryCategory,
                                                        globalLanguage
                                                    )
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                v-if="form.category === 'ams'"
                                class="mt-7 overflow-x-auto"
                            >
                                <table
                                    class="w-full whitespace-nowrap doc-table"
                                    style="table-layout: auto"
                                >
                                    <thead>
                                        <tr>
                                            <th>
                                                {{ $t("POS") }}
                                            </th>
                                            <th>
                                                {{ $t("Article Nr.") }}
                                            </th>
                                            <th>
                                                {{ $t("Name") }}
                                            </th>
                                            <th>
                                                {{ $t("Software") }}
                                            </th>
                                            <th>
                                                {{ $t("Quantity") }}/{{
                                                    $t("Unit")
                                                }}
                                            </th>
                                            <th>
                                                {{ $t("Hourly Rate") }}
                                            </th>
                                            <th>
                                                {{ $t("Discount") }}
                                            </th>
                                            <th>
                                                {{ $t("Period") }}
                                            </th>
                                            <th>
                                                {{ $t("Netto Total") }}
                                            </th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <template
                                            v-for="(product, index) in ams"
                                            :key="index"
                                            :tabindex="index"
                                        >
                                            <tr
                                                class="focus:outline-none h-16 border border-gray-100 rounded"
                                            >
                                                <td>
                                                    <div>
                                                        {{ index + 1 }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        {{
                                                            product.articleNumber
                                                        }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="table-align">
                                                        <input
                                                            v-if="
                                                                product.isProductNameEdit
                                                            "
                                                            class="appearance-none border-2 border-gray-200 rounded input-width py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="text"
                                                            name="productName"
                                                            v-model="
                                                                product.name
                                                            "
                                                            :class="{
                                                                Perror: errors[
                                                                    `products.${index}.productName`
                                                                ],
                                                            }"
                                                        />

                                                        <p v-else>
                                                            {{ product.name }}
                                                        </p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        {{
                                                            product
                                                                .productSoftware
                                                                ?.name
                                                        }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div style="display: flex">
                                                        <input
                                                            @input="
                                                                changedQuantityAMS(
                                                                    index,
                                                                    $event
                                                                )
                                                            "
                                                            class="appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                product.quantity
                                                            "
                                                            name="quantity"
                                                        />
                                                        <p
                                                            class="self-center ml-2"
                                                        >
                                                            {{
                                                                product?.unit
                                                                    ?.name
                                                            }}
                                                        </p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <number-input
                                                            @inputEvent="
                                                                changedQuantityAMS(
                                                                    index,
                                                                    $event
                                                                )
                                                            "
                                                            class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                product.hourlyRate
                                                            "
                                                            name="hourlyRate"
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input
                                                            @input="
                                                                changedQuantityAMS(
                                                                    index,
                                                                    $event
                                                                )
                                                            "
                                                            class="appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                product.discount
                                                            "
                                                            name="discount"
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <select-input
                                                            class="input-width"
                                                            v-model="
                                                                product.paymentPeriod
                                                            "
                                                        >
                                                            <option
                                                                v-for="period in periods.data"
                                                                :key="
                                                                    'period-' +
                                                                    period.id
                                                                "
                                                                :value="
                                                                    period.id
                                                                "
                                                            >
                                                                {{
                                                                    period.name
                                                                }}
                                                            </option>
                                                        </select-input>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <number-input
                                                            @inputEvent="
                                                                amsNettoTotalChanged(
                                                                    event,
                                                                    index
                                                                )
                                                            "
                                                            class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                product.nettoTotal
                                                            "
                                                            name="nettoTotal"
                                                            :allowNegative="
                                                                true
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <font-awesome-icon
                                                        @click="
                                                            removeOption(index)
                                                        "
                                                        class="cursor-pointer cross"
                                                        icon="fa-solid fa-xmark"
                                                    />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="pl-6" colspan="10">
                                                    <div
                                                        @click="
                                                            toggleAdditionalDescriptionDropdowns(
                                                                product.productInvoiceId,
                                                                index,
                                                                additionalDescriptionToggled[
                                                                    product
                                                                        .productInvoiceId
                                                                ]
                                                                    ? 'remove'
                                                                    : 'add',
                                                                'ams'
                                                            )
                                                        "
                                                        class="add-additional-btn"
                                                    >
                                                        <font-awesome-icon
                                                            :class="`cursor-pointer cross mr-2 self-center text-${
                                                                additionalDescriptionToggled[
                                                                    product
                                                                        .productInvoiceId
                                                                ]
                                                                    ? 'red'
                                                                    : 'blue'
                                                            }-500`"
                                                            :icon="`fa-solid fa-circle-${
                                                                additionalDescriptionToggled[
                                                                    product
                                                                        .productInvoiceId
                                                                ]
                                                                    ? 'minus'
                                                                    : 'plus'
                                                            }`"
                                                        />
                                                        <p
                                                            :class="`text-sm text-${
                                                                additionalDescriptionToggled[
                                                                    product
                                                                        .productInvoiceId
                                                                ]
                                                                    ? 'red'
                                                                    : 'blue'
                                                            }-500`"
                                                        >
                                                            {{
                                                                $t(
                                                                    `${
                                                                        additionalDescriptionToggled[
                                                                            product
                                                                                .productInvoiceId
                                                                        ]
                                                                            ? "Remove"
                                                                            : "Add"
                                                                    } Additional Description`
                                                                )
                                                            }}
                                                        </p>
                                                    </div>
                                                    <div
                                                        v-if="
                                                            additionalDescriptionToggled[
                                                                product
                                                                    .productInvoiceId
                                                            ]
                                                        "
                                                    >
                                                        <TextAreaInput
                                                            style="
                                                                overflow: visible !important;
                                                            "
                                                            v-model="
                                                                product.additionalDescription
                                                            "
                                                            class="pb-1 pr-6 w-full"
                                                            :label="
                                                                $t(
                                                                    'Additional Description'
                                                                )
                                                            "
                                                            :error="
                                                                errors[
                                                                    'additionalDescription'
                                                                ]
                                                            "
                                                        ></TextAreaInput>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                                <br />
                            </div>
                            <div
                                v-if="
                                    form.category === 'ams' &&
                                    Object.keys(ams).length > 0
                                "
                                style="
                                    flex-direction: column;
                                    align-items: end;
                                    justify-content: flex-end;
                                "
                            >
                                <div>
                                    <div
                                        class="grid gap-2 justify-end"
                                        style="
                                            grid-template-columns: repeat(
                                                auto-fit,
                                                100px
                                            );
                                        "
                                    >
                                        <p>{{ $t("Netto") }}:</p>
                                        <p
                                            :class="[
                                                globalLanguage === 'de'
                                                    ? 'text-end'
                                                    : '',
                                            ]"
                                        >
                                            {{
                                                $formatter(
                                                    computeNettoAms,
                                                    globalLanguage
                                                )
                                            }}
                                        </p>
                                    </div>
                                    <div
                                        class="grid gap-2 mt-1 justify-end"
                                        style="
                                            grid-template-columns: repeat(
                                                auto-fit,
                                                100px
                                            );
                                        "
                                        v-for="(value, key) in computeMwstAms"
                                        :key="key"
                                    >
                                        <h1>{{ key }}% {{ $t("Tax") }}:</h1>
                                        <h1
                                            :class="[
                                                globalLanguage === 'de'
                                                    ? 'text-end'
                                                    : '',
                                            ]"
                                        >
                                            {{
                                                $formatter(
                                                    value,
                                                    globalLanguage
                                                )
                                            }}
                                        </h1>
                                    </div>
                                    <div
                                        class="grid gap-2 mt-2 justify-end font-bold bg-gray-300"
                                        style="
                                            grid-template-columns: repeat(
                                                auto-fit,
                                                100px
                                            );
                                            background-image: -webkit-linear-gradient(
                                                left,
                                                white,
                                                white 81%,
                                                transparent 70%,
                                                transparent 100%
                                            );
                                        "
                                    >
                                        <p>{{ $t("Total") }}:</p>
                                        <p
                                            :class="[
                                                globalLanguage === 'de'
                                                    ? 'text-end'
                                                    : '',
                                            ]"
                                        >
                                            {{
                                                $formatter(
                                                    computeSummaryAms,
                                                    globalLanguage
                                                )
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div
                                v-if="form.category === 'hosting'"
                                class="mt-7 overflow-x-auto"
                            >
                                <table
                                    class="w-full whitespace-nowrap doc-table"
                                    style="table-layout: auto"
                                >
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>
                                                {{ $t("POS") }}
                                            </th>
                                            <th>
                                                {{ $t("Article Nr.") }}
                                            </th>
                                            <th>
                                                {{ $t("Name") }}
                                            </th>
                                            <th>
                                                {{ $t("Software") }}
                                            </th>
                                            <th>
                                                {{ $t("Quantity") }}
                                            </th>
                                            <th>
                                                {{ $t("Price Per Period") }}
                                            </th>
                                            <th>
                                                {{ $t("Discount") }}
                                            </th>
                                            <th>
                                                {{ $t("Period") }}
                                            </th>
                                            <th>
                                                {{ $t("Netto Total") }}
                                            </th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <template
                                            v-for="(product, index) in hosting"
                                            :key="index"
                                            :tabindex="index"
                                        >
                                            <tr
                                                class="focus:outline-none h-16 border border-gray-100 rounded"
                                            >
                                                <td>
                                                    <input
                                                        class="relative z-10"
                                                        type="checkbox"
                                                        @input="
                                                            removeAllChildren(
                                                                index,
                                                                'hosting',
                                                                $event
                                                            )
                                                        "
                                                        v-model="
                                                            groupToggler[
                                                                'hosting'
                                                            ][product.id]
                                                        "
                                                    />
                                                </td>
                                                <td>
                                                    <div>
                                                        {{ index + 1 }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        {{
                                                            product.articleNumber
                                                        }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="table-align">
                                                        <input
                                                            v-if="
                                                                product.isProductNameEdit
                                                            "
                                                            class="appearance-none border-2 border-gray-200 rounded input-width py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="text"
                                                            name="productName"
                                                            v-model="
                                                                product.name
                                                            "
                                                            :class="{
                                                                Perror: errors[
                                                                    `products.${index}.productName`
                                                                ],
                                                            }"
                                                        />

                                                        <p v-else>
                                                            {{ product.name }}
                                                        </p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        {{
                                                            product
                                                                .productSoftware
                                                                ?.name
                                                        }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input
                                                            @input="
                                                                changedQuantityHosting(
                                                                    index,
                                                                    $event
                                                                )
                                                            "
                                                            style="
                                                                max-width: 8ch;
                                                                min-width: 8ch;
                                                            "
                                                            class="appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                product.quantity
                                                            "
                                                            name="quantity"
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <number-input
                                                            @inputEvent="
                                                                changedQuantityHosting(
                                                                    index,
                                                                    $event
                                                                )
                                                            "
                                                            class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                product.pricePerPeriod
                                                            "
                                                            name="pricePerPeriod"
                                                            :allowNegative="
                                                                true
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input
                                                            @input="
                                                                changedQuantityHosting(
                                                                    index,
                                                                    $event
                                                                )
                                                            "
                                                            class="input-width appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                product.discount
                                                            "
                                                            name="discount"
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <select-input
                                                            class="input-width"
                                                            :key="
                                                                product.paymentPeriod
                                                            "
                                                            v-model="
                                                                product.paymentPeriod
                                                            "
                                                        >
                                                            <option
                                                                v-for="period in periods.data"
                                                                :key="
                                                                    'period-' +
                                                                    period.id
                                                                "
                                                                :value="
                                                                    period.id
                                                                "
                                                            >
                                                                {{
                                                                    period.name
                                                                }}
                                                            </option>
                                                        </select-input>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <number-input
                                                            @inputEvent="
                                                                hostingNettoTotalChanged(
                                                                    event,
                                                                    index
                                                                )
                                                            "
                                                            class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                product.nettoTotal
                                                            "
                                                            name="nettoTotal"
                                                            :allowNegative="
                                                                true
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <font-awesome-icon
                                                        @click="
                                                            removeOption(index)
                                                        "
                                                        class="cursor-pointer cross"
                                                        icon="fa-solid fa-xmark"
                                                    />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="pl-6" colspan="10">
                                                    <div
                                                        @click="
                                                            toggleAdditionalDescriptionDropdowns(
                                                                product.productInvoiceId,
                                                                index,
                                                                additionalDescriptionToggled[
                                                                    product
                                                                        .productInvoiceId
                                                                ]
                                                                    ? 'remove'
                                                                    : 'add',
                                                                'hosting'
                                                            )
                                                        "
                                                        class="add-additional-btn"
                                                    >
                                                        <font-awesome-icon
                                                            :class="`cursor-pointer cross mr-2 self-center text-${
                                                                additionalDescriptionToggled[
                                                                    product
                                                                        .productInvoiceId
                                                                ]
                                                                    ? 'red'
                                                                    : 'blue'
                                                            }-500`"
                                                            :icon="`fa-solid fa-circle-${
                                                                additionalDescriptionToggled[
                                                                    product
                                                                        .productInvoiceId
                                                                ]
                                                                    ? 'minus'
                                                                    : 'plus'
                                                            }`"
                                                        />
                                                        <p
                                                            :class="`text-sm text-${
                                                                additionalDescriptionToggled[
                                                                    product
                                                                        .productInvoiceId
                                                                ]
                                                                    ? 'red'
                                                                    : 'blue'
                                                            }-500`"
                                                        >
                                                            {{
                                                                $t(
                                                                    `${
                                                                        additionalDescriptionToggled[
                                                                            product
                                                                                .productInvoiceId
                                                                        ]
                                                                            ? "Remove"
                                                                            : "Add"
                                                                    } Additional Description`
                                                                )
                                                            }}
                                                        </p>
                                                    </div>
                                                    <div
                                                        v-if="
                                                            additionalDescriptionToggled[
                                                                product
                                                                    .productInvoiceId
                                                            ]
                                                        "
                                                    >
                                                        <TextAreaInput
                                                            style="
                                                                overflow: visible !important;
                                                            "
                                                            v-model="
                                                                product.additionalDescription
                                                            "
                                                            class="pb-1 pr-6 w-full"
                                                            :label="
                                                                $t(
                                                                    'Additional Description'
                                                                )
                                                            "
                                                            :error="
                                                                errors[
                                                                    'additionalDescription'
                                                                ]
                                                            "
                                                        ></TextAreaInput>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr
                                                v-if="
                                                    groupToggler['hosting'][
                                                        product.id
                                                    ]
                                                "
                                                class="bg-gray-100"
                                            >
                                                <th></th>
                                                <th>
                                                    {{ $t("POS") }}
                                                </th>
                                                <th>
                                                    {{ $t("Article Nr.") }}
                                                </th>
                                                <th>
                                                    {{ $t("Name") }}
                                                </th>
                                                <th>
                                                    {{ $t("Software") }}
                                                </th>
                                                <th>
                                                    {{ $t("Quantity") }}
                                                </th>
                                                <th>
                                                    {{
                                                        $t(
                                                            "Single Price Per Period"
                                                        )
                                                    }}
                                                </th>
                                                <th>
                                                    {{
                                                        $t("Total Price Period")
                                                    }}
                                                </th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>

                                            <tr
                                                v-for="(
                                                    childProduct, childIndex
                                                ) in product.children"
                                                :key="childIndex"
                                                :tabindex="childIndex"
                                                class="focus:outline-none h-16 border border-gray-100 rounded"
                                            >
                                                <td></td>

                                                <td>
                                                    <div class="">
                                                        {{ index + 1 }}.{{
                                                            childIndex + 1
                                                        }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="">
                                                        {{
                                                            childProduct.articleNumber
                                                        }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="table-align">
                                                        <input
                                                            v-if="
                                                                childProduct.isProductNameEdit
                                                            "
                                                            class="appearance-none border-2 border-gray-200 rounded input-width py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="text"
                                                            name="productName"
                                                            v-model="
                                                                childProduct.name
                                                            "
                                                            :class="{
                                                                Perror: errors[
                                                                    `products.${index}.productName`
                                                                ],
                                                            }"
                                                        />

                                                        <p v-else>
                                                            {{
                                                                childProduct.name
                                                            }}
                                                        </p>
                                                        <!--                                                        <input-->
                                                        <!--                                                            :title="-->
                                                        <!--                                                                childProduct.name-->
                                                        <!--                                                            "-->
                                                        <!--                                                            class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:border-purple-500"-->
                                                        <!--                                                            type="text"-->
                                                        <!--                                                            :class="{-->
                                                        <!--                                                                Perror: errors[-->
                                                        <!--                                                                    `products.${index}.name`-->
                                                        <!--                                                                ],-->
                                                        <!--                                                            }"-->
                                                        <!--                                                            readonly-->
                                                        <!--                                                            v-model="-->
                                                        <!--                                                                childProduct.name-->
                                                        <!--                                                            "-->
                                                        <!--                                                        />-->
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="">
                                                        {{
                                                            childProduct
                                                                .productSoftware
                                                                ?.name
                                                        }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="flex">
                                                        <input
                                                            @input="
                                                                changedQuantityHosting(
                                                                    index,
                                                                    $event,
                                                                    childIndex
                                                                )
                                                            "
                                                            class="w-full appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                childProduct.quantity
                                                            "
                                                            :min="0"
                                                            @keypress="
                                                                limitToPositiveNumbers
                                                            "
                                                            name="quantity"
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="">
                                                        <number-input
                                                            @inputEvent="
                                                                changedQuantityHosting(
                                                                    index,
                                                                    $event,
                                                                    childIndex
                                                                )
                                                            "
                                                            class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                childProduct.pricePerPeriod
                                                            "
                                                            name="pricePerPeriod"
                                                            :allowNegative="
                                                                true
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <number-input
                                                        @inputEvent="
                                                            changedQuantityHosting(
                                                                index,
                                                                $event,
                                                                childIndex
                                                            )
                                                        "
                                                        :isReadonly="false"
                                                        class="w-full appearance-none rounded text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="
                                                            childProduct.totalPricePeriod
                                                        "
                                                        name="pricePerPeriod"
                                                        :allowNegative="true"
                                                    />
                                                </td>
                                                <td>
                                                    <font-awesome-icon
                                                        @click="
                                                            removeChildOption(
                                                                index,
                                                                childIndex,
                                                                'hosting'
                                                            )
                                                        "
                                                        class="cursor-pointer cross"
                                                        icon="fa-solid fa-xmark"
                                                    />
                                                </td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr
                                                v-if="
                                                    groupToggler['hosting'][
                                                        product.id
                                                    ]
                                                "
                                            >
                                                <td colspan="10">
                                                    <div
                                                        @click="
                                                            toggleHostingChildren(
                                                                index
                                                            )
                                                        "
                                                        style="
                                                            display: flex !important;
                                                        "
                                                        class="mt-2 cursor-pointer text-blue-500"
                                                    >
                                                        <font-awesome-icon
                                                            class="cursor-pointer cross mr-2 self-center"
                                                            icon="fa-solid fa-circle-plus"
                                                        />
                                                        <p class="text-sm">
                                                            {{
                                                                $t(
                                                                    "Add Child Product"
                                                                )
                                                            }}
                                                        </p>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                                <br />
                            </div>
                            <div
                                v-if="
                                    form.category === 'hosting' &&
                                    Object.keys(hosting).length > 0
                                "
                                style="
                                    flex-direction: column;
                                    align-items: end;
                                    justify-content: flex-end;
                                "
                            >
                                <div>
                                    <div
                                        class="grid gap-2 justify-end"
                                        style="
                                            grid-template-columns: repeat(
                                                auto-fit,
                                                100px
                                            );
                                        "
                                    >
                                        <p>{{ $t("Netto") }}:</p>
                                        <p
                                            :class="[
                                                globalLanguage === 'de'
                                                    ? 'text-end'
                                                    : '',
                                            ]"
                                        >
                                            {{
                                                $formatter(
                                                    computeNettoHosting,
                                                    globalLanguage
                                                )
                                            }}
                                        </p>
                                    </div>
                                    <div
                                        class="grid gap-2 mt-1 justify-end"
                                        style="
                                            grid-template-columns: repeat(
                                                auto-fit,
                                                100px
                                            );
                                        "
                                        v-for="(
                                            value, key
                                        ) in computeMwstHosting"
                                        :key="key"
                                    >
                                        <h1>{{ key }}% {{ $t("Tax") }}:</h1>
                                        <h1
                                            :class="[
                                                globalLanguage === 'de'
                                                    ? 'text-end'
                                                    : '',
                                            ]"
                                        >
                                            {{
                                                $formatter(
                                                    value,
                                                    globalLanguage
                                                )
                                            }}
                                        </h1>
                                    </div>
                                    <div
                                        class="grid gap-2 mt-2 justify-end font-bold bg-gray-300"
                                        style="
                                            grid-template-columns: repeat(
                                                auto-fit,
                                                100px
                                            );
                                            background-image: -webkit-linear-gradient(
                                                left,
                                                white,
                                                white 81%,
                                                transparent 70%,
                                                transparent 100%
                                            );
                                        "
                                    >
                                        <p>{{ $t("Total") }}:</p>
                                        <p
                                            :class="[
                                                globalLanguage === 'de'
                                                    ? 'text-end'
                                                    : '',
                                            ]"
                                        >
                                            {{
                                                $formatter(
                                                    computeSummaryHosting,
                                                    globalLanguage
                                                )
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div
                                v-if="form.category === 'cloud-software'"
                                class="mt-7 overflow-x-auto"
                            >
                                <table
                                    class="w-full whitespace-nowrap doc-table"
                                    style="table-layout: auto"
                                >
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>
                                                {{ $t("POS") }}
                                            </th>
                                            <th>
                                                {{ $t("Article Nr.") }}
                                            </th>
                                            <th>
                                                {{ $t("Name") }}
                                            </th>
                                            <th>
                                                {{ $t("Software") }}
                                            </th>
                                            <th>
                                                {{ $t("Quantity") }}
                                            </th>
                                            <th>
                                                {{ $t("Duration") }}
                                            </th>
                                            <th>
                                                {{ $t("Product Price") }}
                                            </th>
                                            <th>
                                                {{ $t("Discount") }}
                                            </th>
                                            <th>
                                                {{ $t("Period") }}
                                            </th>
                                            <th>
                                                {{ $t("Netto Total") }}
                                            </th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <template
                                            v-for="(product, index) in cloud"
                                            :key="index"
                                            :tabindex="index"
                                        >
                                            <tr
                                                class="focus:outline-none h-16 border border-gray-100 rounded"
                                            >
                                                <td>
                                                    <input
                                                        class="relative z-10"
                                                        type="checkbox"
                                                        @input="
                                                            removeAllChildren(
                                                                index,
                                                                'cloud',
                                                                $event
                                                            )
                                                        "
                                                        v-model="
                                                            groupToggler[
                                                                'cloud'
                                                            ][product.id]
                                                        "
                                                    />
                                                </td>
                                                <td>
                                                    <div>
                                                        {{ index + 1 }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        {{
                                                            product.articleNumber
                                                        }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="table-align">
                                                        <input
                                                            v-if="
                                                                product.isProductNameEdit
                                                            "
                                                            class="appearance-none border-2 border-gray-200 rounded input-width py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="text"
                                                            name="productName"
                                                            v-model="
                                                                product.name
                                                            "
                                                            :class="{
                                                                Perror: errors[
                                                                    `products.${index}.productName`
                                                                ],
                                                            }"
                                                        />

                                                        <p v-else>
                                                            {{ product.name }}
                                                        </p>
                                                    </div>
                                                    <!--                                                    <div class="md:w-2/3">-->
                                                    <!--                                                        <input-->
                                                    <!--                                                            :title="-->
                                                    <!--                                                                product.name-->
                                                    <!--                                                            "-->
                                                    <!--                                                            style="-->
                                                    <!--                                                                max-width: 10ch;-->
                                                    <!--                                                                min-width: 10ch;-->
                                                    <!--                                                            "-->
                                                    <!--                                                            type="text"-->
                                                    <!--                                                            :class="{-->
                                                    <!--                                                                Perror: errors[-->
                                                    <!--                                                                    `products.${index}.name`-->
                                                    <!--                                                                ],-->
                                                    <!--                                                            }"-->
                                                    <!--                                                            readonly-->
                                                    <!--                                                            v-model="-->
                                                    <!--                                                                product.name-->
                                                    <!--                                                            "-->
                                                    <!--                                                        />-->
                                                    <!--                                                    </div>-->
                                                </td>
                                                <td>
                                                    <div>
                                                        {{
                                                            product
                                                                .productSoftware
                                                                ?.name
                                                        }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="flex">
                                                        <input
                                                            @input="
                                                                changedQuantityCloud(
                                                                    index,
                                                                    $event
                                                                )
                                                            "
                                                            class="h appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                product.quantity
                                                            "
                                                            name="quantity"
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div
                                                        v-if="
                                                            product.type ===
                                                            'cloud-software'
                                                        "
                                                        class="flex"
                                                    >
                                                        <input
                                                            @input="
                                                                changedQuantityCloud(
                                                                    index,
                                                                    $event
                                                                )
                                                            "
                                                            class="input-width appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                product.duration
                                                            "
                                                            name="duration"
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <number-input
                                                            @inputEvent="
                                                                changedQuantityCloud(
                                                                    index,
                                                                    $event
                                                                )
                                                            "
                                                            class="w-full appearance-none border border-2 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                product.salePrice
                                                            "
                                                            name="salePrice"
                                                            :allowNegative="
                                                                true
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input
                                                            @input="
                                                                changedQuantityCloud(
                                                                    index,
                                                                    $event
                                                                )
                                                            "
                                                            class="input-width appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                product.discount
                                                            "
                                                            name="discount"
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <select-input
                                                            class="input-width"
                                                            :key="
                                                                product.paymentPeriod
                                                            "
                                                            v-model="
                                                                product.paymentPeriod
                                                            "
                                                        >
                                                            <option
                                                                v-for="period in periods.data"
                                                                :key="
                                                                    'period-' +
                                                                    period.id
                                                                "
                                                                :value="
                                                                    period.id
                                                                "
                                                            >
                                                                {{
                                                                    period.name
                                                                }}
                                                            </option>
                                                        </select-input>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <number-input
                                                            @inputEvent="
                                                                cloudNettoTotalChanged(
                                                                    event,
                                                                    index
                                                                )
                                                            "
                                                            class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                product.nettoTotal
                                                            "
                                                            name="nettoTotal"
                                                            :allowNegative="
                                                                true
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <font-awesome-icon
                                                        @click="
                                                            removeOption(index)
                                                        "
                                                        class="cursor-pointer cross"
                                                        icon="fa-solid fa-xmark"
                                                    />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="pl-6" colspan="10">
                                                    <div
                                                        @click="
                                                            toggleAdditionalDescriptionDropdowns(
                                                                product.productInvoiceId,
                                                                index,
                                                                additionalDescriptionToggled[
                                                                    product
                                                                        .productInvoiceId
                                                                ]
                                                                    ? 'remove'
                                                                    : 'add',
                                                                'clouds'
                                                            )
                                                        "
                                                        class="add-additional-btn"
                                                    >
                                                        <font-awesome-icon
                                                            :class="`cursor-pointer cross mr-2 self-center text-${
                                                                additionalDescriptionToggled[
                                                                    product
                                                                        .productInvoiceId
                                                                ]
                                                                    ? 'red'
                                                                    : 'blue'
                                                            }-500`"
                                                            :icon="`fa-solid fa-circle-${
                                                                additionalDescriptionToggled[
                                                                    product
                                                                        .productInvoiceId
                                                                ]
                                                                    ? 'minus'
                                                                    : 'plus'
                                                            }`"
                                                        />
                                                        <p
                                                            :class="`text-sm text-${
                                                                additionalDescriptionToggled[
                                                                    product
                                                                        .productInvoiceId
                                                                ]
                                                                    ? 'red'
                                                                    : 'blue'
                                                            }-500`"
                                                        >
                                                            {{
                                                                $t(
                                                                    `${
                                                                        additionalDescriptionToggled[
                                                                            product
                                                                                .productInvoiceId
                                                                        ]
                                                                            ? "Remove"
                                                                            : "Add"
                                                                    } Additional Description`
                                                                )
                                                            }}
                                                        </p>
                                                    </div>
                                                    <div
                                                        v-if="
                                                            additionalDescriptionToggled[
                                                                product
                                                                    .productInvoiceId
                                                            ]
                                                        "
                                                    >
                                                        <TextAreaInput
                                                            style="
                                                                overflow: visible !important;
                                                            "
                                                            v-model="
                                                                product.additionalDescription
                                                            "
                                                            class="pb-1 pr-6 w-full"
                                                            :label="
                                                                $t(
                                                                    'Additional Description'
                                                                )
                                                            "
                                                            :error="
                                                                errors[
                                                                    'additionalDescription'
                                                                ]
                                                            "
                                                        ></TextAreaInput>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr
                                                v-if="
                                                    groupToggler['cloud'][
                                                        product.id
                                                    ]
                                                "
                                                class="bg-gray-100"
                                            >
                                                <th></th>
                                                <th>
                                                    {{ $t("POS") }}
                                                </th>
                                                <th>
                                                    {{ $t("Article Nr.") }}
                                                </th>
                                                <th>
                                                    {{ $t("Name") }}
                                                </th>
                                                <th>
                                                    {{ $t("Software") }}
                                                </th>
                                                <th>
                                                    {{ $t("Quantity") }}
                                                </th>
                                                <th>
                                                    {{ $t("Duration") }}
                                                </th>
                                                <th>
                                                    {{ $t("Product Price") }}
                                                </th>
                                                <th>
                                                    {{
                                                        $t(
                                                            "Total Product Price"
                                                        )
                                                    }}
                                                </th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            <tr
                                                v-for="(
                                                    child, childIndex
                                                ) in product.children"
                                                :key="childIndex"
                                                :tabindex="childIndex"
                                                class="focus:outline-none h-16 border border-gray-100 rounded"
                                            >
                                                <td></td>
                                                <td>
                                                    <div class="">
                                                        {{ index + 1 }}.{{
                                                            childIndex + 1
                                                        }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="">
                                                        {{
                                                            child.articleNumber
                                                        }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="table-align">
                                                        <input
                                                            v-if="
                                                                child.isProductNameEdit
                                                            "
                                                            class="appearance-none border-2 border-gray-200 rounded input-width py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="text"
                                                            name="productName"
                                                            v-model="child.name"
                                                            :class="{
                                                                Perror: errors[
                                                                    `products.${childIndex}.productName`
                                                                ],
                                                            }"
                                                        />

                                                        <p v-else>
                                                            {{ child.name }}
                                                        </p>
                                                        <!--                                                        <input-->
                                                        <!--                                                            :title="child.name"-->
                                                        <!--                                                            class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:border-purple-500"-->
                                                        <!--                                                            type="text"-->
                                                        <!--                                                            :class="{-->
                                                        <!--                                                                Perror: errors[-->
                                                        <!--                                                                    `products.${index}.name`-->
                                                        <!--                                                                ],-->
                                                        <!--                                                            }"-->
                                                        <!--                                                            readonly-->
                                                        <!--                                                            v-model="child.name"-->
                                                        <!--                                                        />-->
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="">
                                                        {{
                                                            child
                                                                .productSoftware
                                                                ?.name
                                                        }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="flex">
                                                        <input
                                                            @input="
                                                                changedQuantityCloud(
                                                                    index,
                                                                    $event,
                                                                    childIndex
                                                                )
                                                            "
                                                            class="appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                child.quantity
                                                            "
                                                            name="quantity"
                                                            :min="0"
                                                            @keypress="
                                                                limitToPositiveNumbers
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="flex">
                                                        <input
                                                            @input="
                                                                changedQuantityCloud(
                                                                    index,
                                                                    $event,
                                                                    childIndex
                                                                )
                                                            "
                                                            class="appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            :min="0"
                                                            @keypress="
                                                                limitToPositiveNumbers
                                                            "
                                                            v-model="
                                                                child.duration
                                                            "
                                                            name="duration"
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="">
                                                        <number-input
                                                            @inputEvent="
                                                                changedQuantityCloud(
                                                                    index,
                                                                    $event,
                                                                    childIndex
                                                                )
                                                            "
                                                            class="w-full appearance-none border border-2 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                child.salePrice
                                                            "
                                                            name="salePrice"
                                                            :allowNegative="
                                                                true
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="">
                                                        <number-input
                                                            @inputEvent="
                                                                changedQuantityCloud(
                                                                    index,
                                                                    $event,
                                                                    childIndex
                                                                )
                                                            "
                                                            :isReadonly="false"
                                                            class="w-full border border-2 appearance-none rounded text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                child.totalPricePeriod
                                                            "
                                                            name="salePrice"
                                                            :allowNegative="
                                                                true
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <font-awesome-icon
                                                        @click="
                                                            removeChildOption(
                                                                index,
                                                                childIndex,
                                                                'cloud'
                                                            )
                                                        "
                                                        class="cursor-pointer cross"
                                                        icon="fa-solid fa-xmark"
                                                    />
                                                </td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr
                                                v-if="
                                                    groupToggler['cloud'][
                                                        product.id
                                                    ]
                                                "
                                            >
                                                <td colspan="10">
                                                    <div
                                                        @click="
                                                            toggleCloudChildren(
                                                                index
                                                            )
                                                        "
                                                        style="
                                                            display: flex !important;
                                                        "
                                                        class="mt-2 cursor-pointer text-blue-500"
                                                    >
                                                        <font-awesome-icon
                                                            class="cursor-pointer cross mr-2 self-center"
                                                            icon="fa-solid fa-circle-plus"
                                                        />
                                                        <p class="text-sm">
                                                            {{
                                                                $t(
                                                                    "Add Child Product"
                                                                )
                                                            }}
                                                        </p>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                                <br />
                            </div>
                            <div
                                v-if="
                                    form.category === 'cloud-software' &&
                                    Object.keys(cloud).length > 0
                                "
                                style="
                                    flex-direction: column;
                                    align-items: end;
                                    justify-content: flex-end;
                                "
                            >
                                <div>
                                    <div
                                        class="grid gap-2 justify-end"
                                        style="
                                            grid-template-columns: repeat(
                                                auto-fit,
                                                100px
                                            );
                                        "
                                    >
                                        <p>{{ $t("Netto") }}:</p>
                                        <p
                                            :class="[
                                                globalLanguage === 'de'
                                                    ? 'text-end'
                                                    : '',
                                            ]"
                                        >
                                            {{
                                                $formatter(
                                                    computeNettoCloud,
                                                    globalLanguage
                                                )
                                            }}
                                        </p>
                                    </div>
                                    <div
                                        class="grid gap-2 mt-1 justify-end"
                                        style="
                                            grid-template-columns: repeat(
                                                auto-fit,
                                                100px
                                            );
                                        "
                                        v-for="(value, key) in computeMwstCloud"
                                        :key="key"
                                    >
                                        <h1>{{ key }}% {{ $t("Tax") }}:</h1>
                                        <h1
                                            :class="[
                                                globalLanguage === 'de'
                                                    ? 'text-end'
                                                    : '',
                                            ]"
                                        >
                                            {{
                                                $formatter(
                                                    value,
                                                    globalLanguage
                                                )
                                            }}
                                        </h1>
                                    </div>
                                    <div
                                        class="grid gap-2 mt-2 justify-end font-bold bg-gray-300"
                                        style="
                                            grid-template-columns: repeat(
                                                auto-fit,
                                                100px
                                            );
                                            background-image: -webkit-linear-gradient(
                                                left,
                                                white,
                                                white 81%,
                                                transparent 70%,
                                                transparent 100%
                                            );
                                        "
                                    >
                                        <p>{{ $t("Total") }}:</p>
                                        <p
                                            :class="[
                                                globalLanguage === 'de'
                                                    ? 'text-end'
                                                    : '',
                                            ]"
                                        >
                                            {{
                                                $formatter(
                                                    computeSummaryCloud,
                                                    globalLanguage
                                                )
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div v-if="errors?.products" class="form-error">
                                {{ errors.products }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Fill Invoice Details") }}</h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.invoicePeriod"
                                    :key="form.invoicePeriod"
                                    :error="errors.invoicePeriod"
                                    :required="true"
                                    :textLabel="$t('Payment Period')"
                                    :options="periods.data"
                                    :multiple="false"
                                    label="name"
                                    trackBy="id"
                                    moduleName="periods"
                                ></MultiSelectInput>
                            </div>
                        </div>
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
                                        :required="true"
                                        v-model="form.termsOfPayment"
                                        :key="form.termsOfPayment"
                                        :error="errors.termsOfPayment"
                                        :multiple="false"
                                        trackBy="id"
                                        label="name"
                                        :options="termsOfPayment.data"
                                        moduleName="termsOfPayment"
                                        :textLabel="$t('Terms Of Payment')"
                                        @update:model-value="
                                            updatedTermsOfPayment
                                        "
                                    ></MultiSelectInput>
                                    <input
                                        v-if="form.termsOfPayment"
                                        style="
                                            width: -webkit-fill-available;
                                            align-self: end;
                                        "
                                        class="py-2 pl-1 border rounded mr-2"
                                        readonly
                                        type="text"
                                        :value="
                                            form.termsOfPayment?.paymentTerms
                                        "
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-area-input
                                    v-model="form.customNotesFields"
                                    :error="errors.customNotesFields"
                                    :label="$t('Custom Notes Field')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-area-input
                                    v-model="form.paymentTerms"
                                    :label="$t('Payment Terms')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="max-w-6xl flex flex-row-reverse">
                <loading-button
                    :loading="isLoading"
                    @click="store"
                    class="secondary-btn"
                    >{{ $t("Create Invoices") }}</loading-button
                >
            </div> -->
            <div class="flex items-center justify-end mt-4">
                <loading-button
                    :loading="isLoading"
                    @click="store"
                    class="secondary-btn"
                >
                    <span class="mr-1">
                        <CustomIcon name="saveIcon" />
                    </span>
                    {{ $t("Save and Proceed") }}
                </loading-button>
            </div>
        </form>
    </div>
    <select-product-modal
        v-if="productToggle"
        :productType="productType"
        @selected="addProducts"
        :fromOffers="true"
        @cancelled="hideProductModal"
        :selectedItems="[]"
        :products="
            productType === 'software'
                ? softwareProducts
                : productType === 'hosting'
                ? hostingProducts
                : cloudSoftwareProducts
        "
        :selectedProductType="selectedProductType"
        :defaultCategory="form.category"
    ></select-product-modal>
    <select-service-modal
        v-if="serviceToggle"
        @selected="addServices"
        @cancelled="hideModal"
        :selectedItems="
            addServiceChildren ? services[serviceIndex].children : []
        "
        :products="serviceProducts"
        :defaultCategory="form.category"
    ></select-service-modal>
    <select-ams-modal
        v-if="amsToggle"
        @selected="addAMS"
        @cancelled="amsToggle = false"
        :selectedItems="[]"
        :products="amsProducts"
    ></select-ams-modal>
</template>

<script>
import SelectServiceModal from "../../Components/SelectServiceModal.vue";
import SelectAmsModal from "../../Components/SelectAmsModal.vue";
import TextInput from "../../Components/TextInput.vue";
import NumberInput from "../../Components/NumberInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import DateInput from "../../Components/DateInput.vue";
import TextAreaInput from "../../Components/TextareaInput.vue";
import Icon from "../../Components/Icon.vue";
import SelectProductModal from "../../Components/SelectProductModal.vue";
import SelectCustomerModal from "../../Components/SelectCustomerModal.vue";
import SelectSystemModal from "../../Components/SelectSystemModal.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import eloRequestMixin from "./Mixins/eloRequestMixin.js";
import diffInMonthsMixin from "@/Mixins/diffInMonthsMixin";

import { mapGetters } from "vuex";
import { v4 as uuid } from "uuid";
import PageHeader from "@/Components/Layouts/Page-header.vue";

export default {
    // props: {
    //     module: {
    //         type: String,
    //         default: "invoices",
    //     },
    // },

    mixins: [diffInMonthsMixin, eloRequestMixin],
    components: {
        NumberInput,
        MultiSelectInput,
        SelectServiceModal,
        SelectAmsModal,
        LoadingButton,
        SelectInput,
        TextInput,
        TextAreaInput,
        Icon,
        SelectProductModal,
        SelectCustomerModal,
        SelectSystemModal,
        DateInput,
        PageHeader,
    },
    data() {
        return {
            referenceInvoiceId:0,
            referenceInvoices: [],
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Finance"),
                    to: "/invoices",
                },
                {
                    text: this.$t("Invoices"),
                    to: "/invoices",
                },
                {
                    text: "Create",
                    active: true,
                },
            ],
            serviceIndex: null, // the index in the service array to which the children must be appended
            addServiceChildren: false, // when true sets the selectedItems prop on SelectProductModal to the children array of service
            cloudIndex: null, // the index in the cloud array to which the children must be appended
            addCloudChildren: false, // when true sets the selectedItems prop on SelectProductModal to the children array of cloud
            hostingIndex: null, // the index in the hosting array to which the children must be appended
            addHostingChildren: false, // when true sets the selectedItems prop on SelectProductModal to the children array of hosting
            // is used to toggle child addition divs in service, hosting, cloud when the group checkbox is checked/unchecked
            groupToggler: {
                service: {},
                hosting: {},
                cloud: {},
            },
            additionalDescriptionToggled: {},
            productTypeEnums: {
                software: "software",
                service: "service",
                ams: "ams",
                hosting: "hosting",
                "cloud-software": "cloudSoftware",
            },
            productType: "software",
            performanceRecords: {
                data: [],
                links: [],
            },
            showPerformanceRecord: true,
            invoiceTypeEnums: {
                invoice: "Invoice",
                "invoice-correction": "Invoice Correction",
                "invoice-storno": "Invoice Storno",
            },
            hosting: [], //holds the selected product of hosting type
            hostingProducts: [], // all the hosting products that are loaded in the products modal, sent as prop to select-products-modal
            cloud: [], //holds the selected product of cloud type
            cloudSoftwareProducts: [], // all the cloud products that are loaded in the products modal, sent as prop to select-products-modal
            categories: [],
            projects: [],
            globalPeriod: "",
            show: true,
            selectedProductType: "softwareLicences",
            softwareLicences: [],
            softwareLicencesTax: [],
            services: [],
            ams: [],
            serviceToggle: false,
            hostingToggle: false,
            cloudToggle: false,
            amsToggle: false,
            disabled: false,
            invoiceTypes: [],
            softwareProducts: [],
            customers: {
                data: [],
                links: [],
            },
            form: {
                paymentTerms: "",
                performanceRecord: "",
                receiverType: "customer",
                groupedBy: "nothing",
                category: this.$route.query.performanceRecord
                    ? "service"
                    : "license",
                invoiceType: "",
                dueDate: new Date(),
                startDate: new Date(),
                endDate: new Date(`${new Date().getFullYear()}-12-31`),
                invoicePeriod: "",
                paymentPeriod: "",
                termsOfPayment: "",
                customNotesFields: "",
                // ...(this.module === "invoiceTemplates" && {
                //     nextCreateDate: "",
                // }),
                projectId: "",
                customers: {},
                products: [],
                systems: {},
                contactPerson: "",
                draftStatusChangeDate: new Date(),
                applyReverseChange: false,
                recreateAfter: "",
            },
            invoiceTax: [],
            systems: [],
            productToggle: false,
            softwareMaintenance: [],
            softwareMaintenanceTax: [],
            serviceProducts: [],
            amsProducts: [],
        };
    },
    async mounted() {
        const response = await this.$store.dispatch("invoices/fetchData");
        this.invoiceTypes = response?.data?.invoiceTypes ?? [];
        this.softwareProducts = response?.data?.products ?? {
            data: [],
            links: [],
        };
        this.customers = response?.data?.customers ?? [];

        const invoices = await this.$store.dispatch(
                        "invoices/referenceInvoices",
                        this.$route.params.id
                );

        this.referenceInvoices=invoices?.data?.invoices;

        this.$store
            .dispatch("products/productsWithQuantity", {
                type: ["service", "pauschal"],
            })
            .then((res) => {
                this.serviceProducts = res?.data?.products ?? {
                    data: [],
                    links: [],
                };
            });
        this.$store
            .dispatch("products/productsWithQuantity", {
                type: ["ams", "pauschal"],
            })
            .then((res) => {
                this.amsProducts = res?.data?.products ?? {
                    data: [],
                    links: [],
                };
            });
        await this.$store.dispatch("termsOfPayment/list", {
            perPage: 25,
            page: 1,
        });
        await this.$store.dispatch("periods/list");
        await this.$store.dispatch("units/list");
        const performanceRecordResponse = await this.$store.dispatch(
            "performanceRecords/list",
            {
                showUnapproved: true,
                selectedIds: [this.$route.query.performanceRecord],
            }
        );
        this.performanceRecords = performanceRecordResponse?.data ?? {
            data: [],
            links: [],
        };
        if (this.$route.query.performanceRecord) {
            this.showPerformanceRecord = false;
            this.form.performanceRecord =
                this.performanceRecords.data.find(
                    (record) => record.id == this.$route.query.performanceRecord
                ) ?? "";
            this.form.invoiceType = "invoice";
            let service = this.serviceProducts?.data?.find(
                (service) =>
                    service.id ==
                    this.form.performanceRecord?.defaultServiceProduct
            );
            // if a service is not found from the 'serviceProducts' array then call the show API to get the service individually
            if (!service) {
                try {
                    if (this.form.performanceRecord?.defaultServiceProduct) {
                        const serviceResponse = await this.$store.dispatch(
                            "products/show",
                            this.form.performanceRecord?.defaultServiceProduct
                        );
                        service = serviceResponse?.data?.pProducts;
                    }
                } catch (e) {
                    console.log(e);
                }
            }
            if (service) {
                service.hourlyRate =
                    this.form.performanceRecord?.defaultServiceHourlyRate;
                service.dailyRate =
                    this.form.performanceRecord?.defaultServiceDailyRate;
                service.quantity =
                    this.form.performanceRecord?.totalHours ?? service.quantity;
                service.discount =
                    this.form.performanceRecord?.discount ?? service.discount;
                this.addServices([service]);
            }

            if (this.$route.query.startDate && this.$route.query.endDate) {
                this.form.startDate = new Date(
                    this.$route.query.startDate ?? this.form.startDate
                );
                this.form.endDate = new Date(
                    this.$route.query.endDate ?? this.form.endDate
                );
            }
            this.form.invoicePeriod =
                this.periods.data?.find((period) => period.isDefault === 1) ??
                this.form.invoicePeriod;
            await this.$nextTick();
            this.showPerformanceRecord = true;
        }
    },
    computed: {
        ...mapGetters("companyEmployees", ["users"]),
        ...mapGetters("auth", ["user"]),
        ...mapGetters("companies", ["companies", "partners"]),
        ...mapGetters("units", ["units"]),
        ...mapGetters("periods", ["periods"]),
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("termsOfPayment", {
            termsOfPayment: "termsOfPayment",
        }),
        // calculates the total maintenance price across software maintenance products
        totalMaintenancePrice() {
            return (
                this.softwareMaintenance?.reduce((sum, current) => {
                    return sum + (+current?.maintenancePrice ?? 0);
                }, 0) ?? 0
            );
        },
        /**
         * calculates the difference in months between the start date and end date set when category in set to
         * 'maintenance'
         * @returns true if the difference is less then equal to 11
         */
        firstYearMaintenance() {
            return (
                this.getMonthDifference(
                    this.form.startDate,
                    this.form.endDate
                ) <= 11
            );
        },
        computeSummaryCloud() {
            let sum = +this.computeNettoCloud;
            if (!this.form.applyReverseChange) {
                for (let key in this.computeMwstCloud) {
                    sum += +this.computeMwstCloud[key];
                }
            }
            return sum.toFixed(2);
        },
        computeNettoCloud() {
            return this.cloud.reduce((accumulator, currentValue) => {
                return +accumulator + +currentValue.nettoTotal;
            }, 0);
        },
        computeMwstCloud() {
            const mwst = {};
            this.cloud.forEach((product) => {
                mwst[product.tax] = this.form.applyReverseChange
                    ? 0
                    : (+(mwst?.[product.tax] ?? 0) + +product.taxRate).toFixed(
                          2
                      );
            });
            return mwst;
        },
        computeSummaryHosting() {
            let sum = +this.computeNettoHosting;
            if (!this.form.applyReverseChange) {
                for (let key in this.computeMwstHosting) {
                    sum += +this.computeMwstHosting[key];
                }
            }
            return sum.toFixed(2);
        },
        computeNettoHosting() {
            return this.hosting.reduce((accumulator, currentValue) => {
                return +accumulator + +currentValue.nettoTotal;
            }, 0);
        },
        computeMwstHosting() {
            const mwst = {};
            this.hosting.forEach((product) => {
                mwst[product.tax] = this.form.applyReverseChange
                    ? 0
                    : (+(mwst?.[product.tax] ?? 0) + +product.taxRate).toFixed(
                          2
                      );
            });
            return mwst;
        },
        computeMwstCategory() {
            const mwst = {};
            this.categories.forEach((category) => {
                mwst[category.tax] = this.form.applyReverseChange
                    ? 0
                    : (
                          +(mwst?.[category.tax] ?? 0) + +category.taxRate
                      ).toFixed(2);
            });
            return mwst;
        },
        computeNettoCategory() {
            return this.categories.reduce((accumulator, currentValue) => {
                return +accumulator + +currentValue.nettoTotal;
            }, 0);
        },
        computeSummaryCategory() {
            let sum = +this.computeNettoCategory;
            if (!this.form.applyReverseChange) {
                for (let key in this.computeMwstCategory) {
                    sum += +this.computeMwstCategory[key];
                }
            }
            return sum.toFixed(2);
        },
        categoryDateString() {
            let categoryDateString = "";
            const categoryEnum = {
                license: "Software Licenses",
                maintenance: "Software Maintenance",
                service: "Services",
                ams: "Application Management Service",
                hosting: "Hosting",
                "cloud-software": "Cloud",
            };
            if (this.form.category === "license") {
                return categoryEnum[this.form.category];
            }
            categoryDateString = `${
                categoryEnum[this.form.category]
            } ${this.$dateFormatter(
                this.formatDateLite(this.form.startDate),
                this.globalLanguage
            )} - ${this.$dateFormatter(
                this.formatDateLite(this.form.endDate),
                this.globalLanguage
            )}`;
            return categoryDateString;
        },
        computeSummaryAms() {
            let sum = +this.computeNettoAms;
            if (!this.form.applyReverseChange) {
                for (let key in this.computeMwstAms) {
                    sum += +this.computeMwstAms[key];
                }
            }
            return sum.toFixed(2);
        },
        computeNettoAms() {
            return this.ams.reduce((accumulator, currentValue) => {
                return +accumulator + +currentValue.nettoTotal;
            }, 0);
        },
        computeMwstAms() {
            const mwst = {};
            this.ams.forEach((product) => {
                mwst[product.tax] = this.form.applyReverseChange
                    ? 0
                    : (+(mwst?.[product.tax] ?? 0) + +product.taxRate).toFixed(
                          2
                      );
            });
            return mwst;
        },
        computeSummary() {
            let sum = +this.computeNetto;
            if (!this.form.applyReverseChange) {
                for (let key in this.computeMwst) {
                    sum += +this.computeMwst[key];
                }
            }
            return sum.toFixed(2);
        },
        computeMwst() {
            const mwst = {};
            this.services.forEach((service) => {
                mwst[service.tax] = this.form.applyReverseChange
                    ? 0
                    : (+(mwst?.[service.tax] ?? 0) + +service.taxRate).toFixed(
                          2
                      );
            });
            return mwst;
        },
        computeNetto() {
            return this.services.reduce((accumulator, currentValue) => {
                return +accumulator + +currentValue.nettoTotal;
            }, 0);
        },
        totalAmountMaintenance() {
            let sum = this.softwareMaintenance.reduce((accumulator, object) => {
                return (
                    parseFloat(accumulator) +
                    parseFloat(object.maintenancePrice)
                );
            }, 0);

            sum = this.calculateAdjustedSoftwareMaintenanceTotal(sum);

            if (!this.form.applyReverseChange) {
                sum = parseFloat(
                    sum +
                        parseFloat(
                            this.softwareMaintenanceTax?.[0]?.["amount"] ?? 0
                        )
                );
            }
            return sum ? sum.toFixed(2) : 0;
        },
        totalAmountWithoutTaxMaintenance() {
            let sum = this.softwareMaintenance.reduce((accumulator, object) => {
                return (
                    parseFloat(accumulator) +
                    parseFloat(object.maintenancePrice)
                );
            }, 0);

            sum = this.calculateAdjustedSoftwareMaintenanceTotal(sum);

            return sum ? sum.toFixed(2) : 0;
        },
        totalAmountWithoutTaxLicences() {
            const sum = this.softwareLicences.reduce((accumulator, object) => {
                return (
                    parseFloat(accumulator) + parseFloat(object.priceWithoutTax)
                );
            }, 0);

            return sum ? sum.toFixed(2) : 0;
        },
        dropdownStyles() {
            return {
                "--elem-hover-bg-color": "#312E81",
                "--elem-selected-bg-color": "#312E81",
            };
        },
        totalAmountLicences() {
            const sum = this.softwareLicences.reduce((accumulator, object) => {
                return parseFloat(accumulator) + parseFloat(object.totalPrice);
            }, 0);
            return sum ? sum.toFixed(2) : 0;
        },
    },
    watch: {
        "form.draftStatusChangeDate"(val) {
            this.form.dueDate = val;
            this.addTermsOfPaymentDaysToDueDate();
        },
        "form.startDate"() {
            if (this.form.category === "maintenance")
                this.recalculateSoftwareMaintenanceTax(); // recalculate softwareMaintenanceTax
        },
        "form.endDate"() {
            if (this.form.category === "maintenance")
                this.recalculateSoftwareMaintenanceTax(); // recalculate softwareMaintenanceTax
        },
        async "form.performanceRecord"() {
            await this.$nextTick();
            if (this.form.performanceRecord?.id) {
                await this.fetchCustomers();
                this.form.customers = this.companies?.data.find(
                    (company) =>
                        company.id == this.form.performanceRecord?.companyId
                );
                // if customer is not found in the customers array then use the show API to fetch individually
                if (!this.form.customers) {
                    const response = await this.$store.dispatch(
                        "companies/show",
                        this.form.performanceRecord?.companyId
                    );
                    this.form.customers = response?.data?.modelData ?? {};
                }
                this.addTermsOfPayment(this.form.customers);
                await this.fetchSystemsAndCompanyEmployees();
            }
        },
        "form.groupedBy"(val) {
            if (val === "category") {
                this.computeServicesByCategory();
            }
        },
        "form.category"(newVal, oldVal) {
            if (oldVal === "license" || oldVal === "maintenance") {
                this.softwareLicences.splice(0, this.softwareLicences.length);
                this.softwareMaintenance.splice(
                    0,
                    this.softwareMaintenance.length
                );
            } else if (oldVal === "service") {
                this.services.splice(0, this.services.length);
            } else if (oldVal === "ams") {
                this.ams.splice(0, this.ams.length);
            }
            // if the category is set to 'service', then automatically set the invoicePeriod to the period with name 'einmalig'
            if (newVal === "service") {
                this.form.invoicePeriod =
                    this.periods.data?.find(
                        (period) => period.name === "einmalig"
                    ) ?? this.form.invoicePeriod;
            }
        },
        serviceToggle(newVal) {
            if (newVal) {
                document.body.classList.add("modal-layout");
            } else {
                document.body.classList.remove("modal-layout");
            }
        },
        productToggle(newVal) {
            if (newVal) {
                document.body.classList.add("modal-layout");
            } else {
                document.body.classList.remove("modal-layout");
            }
        },
    },
    methods: {
        hideModal() {
            this.serviceToggle = false;
        },
        hideProductModal() {
            this.productToggle = false;
        },
        /**
         * triggered when sale price in the software maintenance table is changed
         * auto calculates and updates the maintenancePrice
         * @param {index} index of the modified product from software maintenance products
         */
        async salePriceChangedMaintenance(index) {
            await this.$nextTick();
            const product = this.softwareMaintenance[index];
            const salePrice =
                (+product.baseMaintenancePrice * 100) /
                +product.maintenanceRate;
            product.totalSalePriceAdjustedForQuantity =
                +salePrice * +product.quantity;
            const maintenancePrice =
                (+product.totalSalePriceAdjustedForQuantity *
                    +product.maintenanceRate) /
                100;
            this.softwareMaintenance[index] = {
                ...product,
                salePrice: salePrice,
                maintenancePrice: maintenancePrice,
            };
            this.recalculateSoftwareMaintenanceTax();
        },
        recompute() {
            const products =
                this.form.category === "license"
                    ? [...this.softwareLicences]
                    : [...this.softwareMaintenance];
            products.forEach((product, index) => {
                this.calculateProductValue(
                    index,
                    this.form.category === "license"
                        ? "softwareLicences"
                        : "softwareMaintenance"
                );
            });
        },
        /**
         * removes the child product from the corresponding type array
         * @param {index} index of the parent array
         * @param {childIndex} index of the child product
         * @param {type} the type of parent array
         */
        removeChildOption(index, childIndex, type) {
            if (type === "hosting") {
                this.hosting[index].children.splice(childIndex, 1);
            } else if (type === "cloud") {
                this.cloud[index].children.splice(childIndex, 1);
            } else if (type === "service") {
                this.services[index].children.splice(childIndex, 1);
            }
        },
        /**
         * open the SelectProductModal for service after setting the serviceIndex and addServiceChildren
         * @param {index} index of the service array to which the child products will be added to
         */
        toggleServiceChildren(index) {
            this.serviceIndex = index;
            this.addServiceChildren = true;
            this.serviceToggle = true;
        },
        /**
         * open the SelectProductModal for cloud after setting the cloudIndex and addCloudChildren
         * @param {index} index of the cloud array to which the child products will be added to
         */
        toggleCloudChildren(index) {
            this.cloudIndex = index;
            this.addCloudChildren = true;
            this.toggleProductsModal("cloud-software");
        },
        /**
         * open the SelectProductModal for hosting after setting the hostingIndex and addHostingChildren
         * @param {index} index of the hosting array to which the child products will be added to
         */
        toggleHostingChildren(index) {
            this.hostingIndex = index;
            this.addHostingChildren = true;
            this.toggleProductsModal("hosting");
        },
        /**
         * removes all the children from the corresponding type array
         * @param {index} index of the parent array
         * @param {type} type of the parent array
         * @param {event} input event
         */
        async removeAllChildren(index, type, event) {
            await this.$nextTick();
            if (!event.target.checked) {
                if (type === "hosting") {
                    this.hosting[index].children.splice(
                        0,
                        this.hosting[index].children.length
                    );
                } else if (type === "cloud") {
                    this.cloud[index].children.splice(
                        0,
                        this.cloud[index].children.length
                    );
                } else if (type === "service") {
                    this.services[index].children.splice(
                        0,
                        this.services[index].children.length
                    );
                }
            }
        },
        /**
         * sets the additionalDescriptionToggled object value based on the index and type
         * @param {id} id of the product for which to toggle additional information dropdown
         * @param {index} index of the product
         * @param {type} possible values (add, remove)
         * @param {category} the category for which to toggle the additional description
         */
        toggleAdditionalDescriptionDropdowns(id, index, type, category) {
            this.additionalDescriptionToggled[id] = type === "add";
            // if removed set the additional description values of the product back to default
            if (type === "remove") {
                if (category === "softwareLicences")
                    this.softwareLicences[index]["additionalDescription"] = "";
                else if (category === "softwareMaintenance")
                    this.softwareMaintenance[index]["additionalDescription"] =
                        "";
                else if (category === "services")
                    this.services[index]["additionalDescription"] = "";
                else if (category === "categories")
                    this.categories[index]["additionalDescription"] = "";
                else if (category === "ams")
                    this.ams[index]["additionalDescription"] = "";
                else if (category === "hosting")
                    this.hosting[index]["additionalDescription"] = "";
                else if (category === "cloud")
                    this.cloud[index]["additionalDescription"] = "";
            }
        },
        /**
         * formats the option label in multiselect input
         * @param {props} the options received from the multiselect input
         * @returns the formated label
         */
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
        /**
         * triggered when a company is selected
         * fetches the systems, company employees, projects, terms of payment related to the selected company
         */
        fetchSystemsAndCompanyEmployees() {
            return new Promise(async (resolve, reject) => {
                await this.$nextTick();
                try {
                    this.form.externalOrderNumber =
                        this.form.customers?.externalOrderNumber ?? "";
                    this.form.applyReverseChange =
                        this.form.customers?.applyReverseChange == 1;
                    this.form.contactPerson = ""; // reset the contact person when the customer changes
                    await this.$store.dispatch("companyEmployees/list", {
                        limit_start: 0,
                        limit_count: 25,
                        type: this.form.receiverType,
                        company_id: this.form.customers?.id,
                    });
                    this.$store
                        .dispatch(
                            "systems/search",
                            this.form.customers?.id ?? ""
                        )
                        .then((res) => {
                            this.systems = res?.data?.data ?? [];
                        });
                    this.form.projectId = null;
                    this.addTermsOfPayment(this.form.customers);
                    const response = await this.$store.dispatch(
                        "projects/list",
                        {
                            page: 1,
                            perPage: 25,
                            companyId: this.form.customers?.id,
                        }
                    );
                    this.projects = response?.data?.data ?? [];
                    resolve();
                } catch (e) {
                    console.log(e);
                    reject(e);
                }
            });
        },
        /**
         * fetches companies when the multiselect input is opened
         */
        async fetchCustomers() {
            return new Promise(async (resolve, reject) => {
                try {
                    if (
                        !this.companies?.data?.length &&
                        this.form.receiverType === "customer"
                    )
                        await this.$store.dispatch("companies/list", {
                            perPage: 25,
                            page: 1,
                        });
                    else if (
                        !this.partners?.data?.length &&
                        this.form.receiverType === "partner"
                    )
                        await this.$store.dispatch("companies/list", {
                            perPage: 25,
                            page: 1,
                            customerType: "partner",
                        });
                    resolve();
                } catch (e) {
                    console.log(e);
                    reject(e);
                }
            });
        },
        /**
         * recalculates softwareMaintenanceTax
         */
        recalculateSoftwareMaintenanceTax() {
            this.softwareMaintenanceTax = this.calculateTax(
                "softwareMaintenance"
            );
        },
        /**
         * calculates the softwareMaintenanceTotal adjusted by the difference in months between start and end date.
         * 'firstYearMaintenance' must be less then 12 for this
         * @param {softwareMaintenanceTotal} the sum of all the maintenance price of software maintenance products
         * @returns sum after adjustment
         */
        calculateAdjustedSoftwareMaintenanceTotal(softwareMaintenanceTotal) {
            let sum = softwareMaintenanceTotal;
            if (this.firstYearMaintenance) {
                const diffInMonths = this.getMonthDifference(
                    this.form.startDate,
                    this.form.endDate
                );
                sum = sum * (+diffInMonths / 12);
            }
            return sum;
        },
        /**
         * triggered when sale price in the software maintenance table is changed
         * auto calculates and updates the maintenancePrice
         * @param {index} index of the modified product from software maintenance products
         */
        async totalSalePriceChangedMaintenance(index) {
            await this.$nextTick();
            const product = this.softwareMaintenance[index];
            const salePrice =
                +product.totalSalePriceAdjustedForQuantity / +product.quantity;
            const maintenancePrice =
                (+product.totalSalePriceAdjustedForQuantity *
                    +product.maintenanceRate) /
                100;
            product.baseMaintenancePrice =
                (+salePrice * +product.maintenanceRate) / 100;
            this.softwareMaintenance[index] = {
                ...product,
                salePrice: salePrice,
                maintenancePrice: maintenancePrice,
            };
        },
        /**
         * sets the productType and toggles the products modal
         * calls the products API to load the relevant products based on type if not already loaded
         */
        async toggleProductsModal() {
            let type = "";
            if (
                this.form.category === "license" ||
                this.form.category === "maintenance"
            ) {
                type = "software";
            } else {
                type = this.form.category;
            }
            try {
                // checks if the products have already been loaded
                const response = await this.$store.dispatch(
                    "products/productsWithQuantity",
                    {
                        type: [type, "pauschal"],
                        companyId: this.form.customers?.id, // filter products by the default price list of this company
                    }
                );
                // sets the products from the API response
                this[`${this.productTypeEnums[type]}Products`] = response?.data
                    ?.products ?? {
                    data: [],
                    links: [],
                };
                this.productType = type;
                if (
                    this.form.category === "license" ||
                    this.form.category === "maintenance" ||
                    this.form.category === "hosting" ||
                    this.form.category === "cloud-software"
                ) {
                    this.productToggle = true; // toggles the select-products-modal
                } else if (this.form.category === "service") {
                    this.serviceToggle = true; // toggles the select-service-modal
                } else if (this.form.category === "ams") {
                    this.amsToggle = true; // toggles the select-ams-modal
                }
            } catch (e) {
                console.log(e);
            }
        },
        async performanceRecordAdded() {
            await this.$nextTick();
            if (this.form.performanceRecord) {
                const service = this.serviceProducts?.data?.find(
                    (service) =>
                        service.id ==
                        this.form.performanceRecord?.defaultServiceProduct
                );
                if (service) {
                    service.hourlyRate =
                        this.form.performanceRecord?.defaultServiceHourlyRate;
                    service.dailyRate =
                        this.form.performanceRecord?.defaultServiceDailyRate;
                    service.quantity =
                        this.form.performanceRecord?.quantity ??
                        service.quantity;
                    service.discount =
                        this.form.performanceRecord?.discount ??
                        service.discount ??
                        0;
                    this.addServices([service]);
                }
                this.form.startDate = new Date(
                    this.form.performanceRecord?.startDate ??
                        this.form.startDate
                );
                this.form.endDate = new Date(
                    this.form.performanceRecord?.endDate ?? this.form.endDate
                );
            }
        },
        /**
         * when the termsOfPayment is selected, adds the noOfDays1 from termsOfPayment to
         * dueDate
         */
        async addTermsOfPaymentDaysToDueDate() {
            const noOfDays = this.form?.termsOfPayment?.noOfDays1 ?? 0;
            const newDate = new Date();
            // Add the specified number of days to the date
            newDate.setDate(newDate.getDate() + noOfDays);
            await this.$nextTick();
            this.form.dueDate = newDate;
        },
        async updatedTermsOfPayment() {
            this.addPaymentTerms();
            this.addTermsOfPaymentDaysToDueDate();
        },
        async addPaymentTerms() {
            await this.$nextTick();
            this.form.paymentTerms = this.form?.termsOfPayment?.invoiceText;
        },
        async hostingNettoTotalChanged(event, index) {
            await this.$nextTick();
            const product = this.hosting[index];
            const pricePerPeriod =
                (100 * product.nettoTotal) /
                (100 * product.quantity - product.discount * product.quantity);
            const taxRate = (+product.nettoTotal * +product.tax) / 100;
            product.taxRate = taxRate.toFixed(2);
            product.pricePerPeriod = pricePerPeriod.toFixed(2);
            this.hosting[index] = { ...product };
        },
        async cloudNettoTotalChanged(event, index) {
            await this.$nextTick();
            const product = this.cloud[index];
            const salePrice =
                (100 * product.nettoTotal) /
                (100 * product.quantity - product.discount * product.quantity);
            const taxRate = (+product.nettoTotal * +product.tax) / 100;
            product.taxRate = taxRate.toFixed(2);
            product.salePrice = salePrice.toFixed(2);
            this.cloud[index] = { ...product };
        },
        async changedQuantityHosting(index, event, childIndex) {
            await this.$nextTick();
            const product =
                typeof childIndex === "number"
                    ? this.hosting[index].children[childIndex]
                    : this.hosting[index];
            const totalWithoutDiscount =
                +product.pricePerPeriod * +product.quantity;
            const discountAmount =
                (+totalWithoutDiscount * +product.discount) / 100;
            const nettoTotal = +totalWithoutDiscount - +discountAmount;
            const taxRate = (+nettoTotal * +product.tax) / 100;
            product.nettoTotal = nettoTotal.toFixed(2);
            product.taxRate = taxRate.toFixed(2);
            if (typeof childIndex === "number") {
                product.totalPricePeriod = totalWithoutDiscount;
                this.hosting[index].children[childIndex] = { ...product };
                this.hosting[index].pricePerPeriod = 0;
                this.hosting[index].children.forEach((child) => {
                    this.hosting[index].pricePerPeriod +=
                        child.totalPricePeriod;
                });
                const totalWithoutDiscountInner =
                    +this.hosting[index].pricePerPeriod *
                    +this.hosting[index].quantity;
                const discountAmountInner =
                    (+totalWithoutDiscountInner *
                        +this.hosting[index].discount) /
                    100;
                const nettoTotalInner =
                    +totalWithoutDiscountInner - +discountAmountInner;
                const taxRateInner = (+nettoTotal * +product.tax) / 100;
                this.hosting[index].nettoTotal = nettoTotalInner.toFixed(2);
                this.hosting[index].taxRate = taxRateInner.toFixed(2);
            } else {
                this.hosting[index] = { ...product };
            }
        },
        async changedQuantityCloud(index, event, childIndex) {
            await this.$nextTick();
            const product =
                typeof childIndex === "number"
                    ? this.cloud[index].children[childIndex]
                    : this.cloud[index];
            if (!product.duration) product.duration = 1;
            const totalWithoutDiscount =
                +product.salePrice *
                +(product.duration ?? 1) *
                +product.quantity;
            const discountAmount =
                (+totalWithoutDiscount * +product.discount) / 100;
            const nettoTotal = +totalWithoutDiscount - +discountAmount;
            const taxRate = (+nettoTotal * +product.tax) / 100;
            product.nettoTotal = nettoTotal.toFixed(2);
            product.taxRate = taxRate.toFixed(2);
            if (typeof childIndex === "number") {
                product.totalPricePeriod = totalWithoutDiscount;
                this.cloud[index].children[childIndex] = { ...product };
                this.cloud[index].salePrice = 0;
                this.cloud[index].children.forEach((child) => {
                    this.cloud[index].salePrice += child.totalPricePeriod;
                });
                const totalWithoutDiscountInner =
                    +this.cloud[index].salePrice *
                    +(this.cloud[index].duration ?? 1) *
                    +this.cloud[index].quantity;
                const discountAmountInner =
                    (+totalWithoutDiscountInner * +this.cloud[index].discount) /
                    100;
                const nettoTotalInner =
                    +totalWithoutDiscountInner - +discountAmountInner;
                const taxRateInner =
                    (+nettoTotalInner * +this.cloud[index].tax) / 100;
                this.cloud[index].nettoTotal = nettoTotalInner.toFixed(2);
                this.cloud[index].taxRate = taxRateInner.toFixed(2);
            } else {
                this.cloud[index] = { ...product };
            }
        },
        async serviceQuantityChangedCategory(event, index) {
            await this.$nextTick();
            const category = this.categories[index];
            const totalWithoutDiscount =
                +category.quantity * +category.hourlyRate;
            const discountAmount =
                (+totalWithoutDiscount * +category.discount) / 100;
            const nettoTotal = +totalWithoutDiscount - +discountAmount;
            const taxRate = (+nettoTotal * +category.tax) / 100;
            category.nettoTotal = nettoTotal.toFixed(2);
            category.taxRate = taxRate.toFixed(2);
            category.bruttoTotal = (+nettoTotal + +taxRate).toFixed(2);
            this.categories[index] = { ...category };
        },
        async serviceNettoTotalChangedCategory(event, index) {
            await this.$nextTick();
            const category = this.categories[index];
            const hourlyRate =
                (100 * category.nettoTotal) /
                (100 * category.quantity -
                    category.discount * category.quantity);
            const taxRate = (+category.nettoTotal * +category.tax) / 100;
            category.taxRate = taxRate.toFixed(2);
            category.hourlyRate = hourlyRate.toFixed(2);
            category.bruttoTotal = (+category.nettoTotal + +taxRate).toFixed(2);
            this.categories[index] = { ...category };
        },
        async serviceTaxRateChangedCategory(event, index) {
            await this.$nextTick();
            const category = this.categories[index];
            category.nettoTotal = (
                (100 * category.taxRate) /
                category.tax
            ).toFixed(2);
            const hourlyRate =
                (100 * category.nettoTotal) /
                (100 * category.quantity -
                    category.discount * category.quantity);
            category.hourlyRate = hourlyRate.toFixed(2);
            category.bruttoTotal = (
                +category.nettoTotal + +category.taxRate
            ).toFixed(2);
            this.categories[index] = { ...category };
        },
        unitForCategory(category) {
            let units = Array.from(
                new Set(category.products.map((product) => product?.unit))
            );
            return units.length > 1
                ? this.units?.data?.find?.(
                      (unit) => unit.id == category.defaultUnit
                  ) ?? units?.[0]
                : units?.[0];
        },
        calculateCategoryQuantity(products) {
            return products.reduce(
                (accumulator, product) => +accumulator + +product.quantity,
                0
            );
        },
        computeServicesByCategory() {
            this.categories = Array.from(
                new Set(this.services.map((service) => service?.category?.id))
            ).map((categoryId) => {
                const category =
                    this.services.find(
                        (service) => service?.category?.id === categoryId
                    )?.category ?? {};
                const products = this.services.filter(
                    (service) => service.category.id == category.id
                );
                const quantity = this.calculateCategoryQuantity(products);
                const unit = this.unitForCategory({
                    ...category,
                    products,
                });
                const hourlyRate = products.reduce((accumulator, current) => {
                    return +current.hourlyRate > accumulator
                        ? +current.hourlyRate
                        : accumulator;
                }, 0);
                const totalWithoutDiscount = +hourlyRate * +quantity;
                const discount = products.reduce((accumulator, current) => {
                    return +current.discount > accumulator
                        ? +current.discount
                        : accumulator;
                }, 0);
                const discountAmount =
                    (+totalWithoutDiscount * +discount) / 100;
                const nettoTotal = +totalWithoutDiscount - +discountAmount;
                const tax = products.reduce((accumulator, current) => {
                    return +current.tax > accumulator
                        ? +current.tax
                        : accumulator;
                }, 0);
                const taxRate = (+nettoTotal * +tax) / 100;
                return {
                    ...category,
                    productInvoiceId: uuid(), //uuid is required because we need a unique identifier for additionalDescriptionToggled since multiple products with the same id can be added
                    additionalDescription:
                        category?.additionalDescription ?? "",
                    products: products,
                    unit: unit,
                    quantity: quantity,
                    hourlyRate: hourlyRate,
                    discount: discount,
                    nettoTotal: nettoTotal,
                    tax: tax,
                    taxRate: taxRate,
                    bruttoTotal: +nettoTotal + +taxRate,
                };
            });
        },
        removeOption(index) {
            if (
                this.form.category === "license" ||
                this.form.category === "maintenance"
            ) {
                this.softwareLicences.splice(index, 1);
                this.softwareMaintenance.splice(index, 1);
            } else if (this.form.category === "service") {
                this.services.splice(index, 1);
            } else if (this.form.category === "ams") {
                this.ams.splice(index, 1);
            } else if (this.form.category === "hosting") {
                this.hosting.splice(index, 1);
            } else if (this.form.category === "cloud-software") {
                this.cloud.splice(index, 1);
            }
        },
        async amsNettoTotalChanged(event, index) {
            await this.$nextTick();
            const product = this.ams[index];
            const hourlyRate =
                (100 * product.nettoTotal) /
                (100 * product.quantity - product.discount * product.quantity);
            const taxRate = (+product.nettoTotal * +product.tax) / 100;
            product.taxRate = taxRate.toFixed(2);
            product.hourlyRate = hourlyRate.toFixed(2);
            this.ams[index] = { ...product };
        },
        async amsTaxRateChanged(event, index) {
            await this.$nextTick();
            const product = this.ams[index];
            product.nettoTotal = (
                (100 * product.taxRate) /
                product.tax
            ).toFixed(2);
            const hourlyRate =
                (100 * product.nettoTotal) /
                (100 * product.quantity - product.discount * product.quantity);
            product.hourlyRate = hourlyRate.toFixed(2);
            this.ams[index] = { ...product };
        },
        async changedQuantityAMS(index, event) {
            await this.$nextTick();
            const product = this.ams[index];
            const totalWithoutDiscount =
                +product.quantity * +product.hourlyRate;
            const discountAmount =
                (+totalWithoutDiscount * +product.discount) / 100;
            const nettoTotal = +totalWithoutDiscount - +discountAmount;
            const taxRate = (+nettoTotal * +product.tax) / 100;
            product.nettoTotal = nettoTotal.toFixed(2);
            product.taxRate = taxRate.toFixed(2);
            this.ams[index] = { ...product };
        },
        async resetProductsUponUncheck(event, type) {
            await this.$nextTick();
            if (!event.target.checked) {
                if (type === "softwareLicenses") {
                    this.softwareLicenses.splice(
                        0,
                        this.softwareLicenses.length
                    );
                } else if (type === "softwareMaintenance") {
                    this.softwareMaintenance.splice(
                        0,
                        this.softwareMaintenance.length
                    );
                } else if (type === "services") {
                    this.services.splice(0, this.services.length);
                }
            }
        },
        async serviceQuantityChanged(event, index, childIndex) {
            await this.$nextTick();
            const service =
                typeof childIndex === "number"
                    ? this.services[index].children[childIndex]
                    : this.services[index];
            const totalWithoutDiscount =
                +service.quantity * +service.hourlyRate;
            const discountAmount =
                (+totalWithoutDiscount * +service.discount) / 100;
            const nettoTotal = +totalWithoutDiscount - +discountAmount;
            const taxRate = (+nettoTotal * +service.tax) / 100;
            service.nettoTotal = nettoTotal.toFixed(2);
            service.taxRate = taxRate.toFixed(2);
            service.bruttoTotal = (+nettoTotal + +taxRate).toFixed(2);
            if (typeof childIndex === "number") {
                service.totalRate = totalWithoutDiscount;
                this.services[index].children[childIndex] = { ...service };
                this.services[index].hourlyRate = 0;
                this.services[index].children.forEach((child) => {
                    this.services[index].hourlyRate += child.totalRate;
                });
                const totalWithoutDiscountInner =
                    +this.services[index].quantity *
                    +this.services[index].hourlyRate;
                const discountAmountInner =
                    (+totalWithoutDiscountInner *
                        +this.services[index].discount) /
                    100;
                const nettoTotalInner =
                    +totalWithoutDiscountInner - +discountAmountInner;
                const taxRateInner =
                    (+nettoTotalInner * +this.services[index].tax) / 100;
                this.services[index].nettoTotal = nettoTotalInner;
                this.services[index].taxRate = taxRateInner;
            } else {
                this.services[index] = { ...service };
            }
        },
        async serviceNettoTotalChanged(event, index) {
            await this.$nextTick();
            const service = this.services[index];
            const hourlyRate =
                (100 * service.nettoTotal) /
                (100 * service.quantity - service.discount * service.quantity);
            const taxRate = (+service.nettoTotal * +service.tax) / 100;
            service.taxRate = taxRate.toFixed(2);
            service.hourlyRate = hourlyRate.toFixed(2);
            service.bruttoTotal = (+service.nettoTotal + +taxRate).toFixed(2);
            this.services[index] = { ...service };
        },
        async serviceTaxRateChanged(event, index) {
            await this.$nextTick();
            const service = this.services[index];
            service.nettoTotal = (
                (100 * service.taxRate) /
                service.tax
            ).toFixed(2);
            const hourlyRate =
                (100 * service.nettoTotal) /
                (100 * service.quantity - service.discount * service.quantity);
            service.hourlyRate = hourlyRate.toFixed(2);
            service.bruttoTotal = (
                +service.nettoTotal + +service.taxRate
            ).toFixed(2);
            this.services[index] = { ...service };
        },
        async addAMS(products) {
            this.amsToggle = false;
            this.show = false;
            this.globalPeriod = products?.[0]?.paymentPeriod?.id ?? "";
            await this.$nextTick();
            this.show = true;
            this.ams = [
                ...this.ams,
                ...products.map((product) => {
                    const modifiedProduct = { ...product };
                    if (modifiedProduct.type === "pauschal") {
                        modifiedProduct.hourlyRate = product.salePrice;
                    }
                    const totalWithoutDiscount =
                        +modifiedProduct.hourlyRate * +modifiedProduct.quantity;
                    const discountAmount =
                        (+totalWithoutDiscount * +modifiedProduct.discount) /
                        100;
                    const nettoTotal = +totalWithoutDiscount - +discountAmount;
                    const taxRate = (+nettoTotal * +modifiedProduct.tax) / 100;
                    return {
                        ...modifiedProduct,
                        productInvoiceId: uuid(), //uuid is required because we need a unique identifier for additionalDescriptionToggled since multiple products with the same id can be added
                        discount: modifiedProduct.discount ?? 0,
                        paymentPeriod: modifiedProduct?.paymentPeriod?.id,
                        nettoTotal: nettoTotal,
                        taxRate: taxRate,
                        additionalDescription:
                            modifiedProduct?.additionalDescription ?? "",
                    };
                }),
            ];
        },
        addServices(products) {
            const modifiedProducts = [
                ...products.map((product) => {
                    const modifiedProduct = { ...product };
                    if (modifiedProduct.type === "pauschal") {
                        modifiedProduct.hourlyRate = product.salePrice;
                    }
                    const totalWithoutDiscount =
                        +modifiedProduct.quantity * +modifiedProduct.hourlyRate;
                    const discountAmount =
                        (+totalWithoutDiscount * +modifiedProduct.discount) /
                        100;
                    const nettoTotal = +totalWithoutDiscount - +discountAmount;
                    const taxRate = (+nettoTotal * +modifiedProduct.tax) / 100;
                    return {
                        ...modifiedProduct,
                        productInvoiceId: uuid(), //uuid is required because we need a unique identifier for additionalDescriptionToggled since multiple products with the same id can be added
                        discount: modifiedProduct.discount ?? 0,
                        tax: +modifiedProduct.tax,
                        nettoTotal: nettoTotal,
                        taxRate: taxRate,
                        bruttoTotal: +nettoTotal + +taxRate,
                        additionalDescription:
                            modifiedProduct?.additionalDescription ?? "",
                        children: modifiedProduct?.children ?? [],
                        totalRate:
                            +product.quantity *
                            +(product.type === "pauschal"
                                ? product.salePrice
                                : product.hourlyRate),
                    };
                }),
            ];
            // if the products added are children then do the calculations
            if (
                this.addServiceChildren &&
                typeof this.serviceIndex === "number"
            ) {
                this.services[this.serviceIndex].hourlyRate = 0;
                this.services[this.serviceIndex].children = [
                    ...(this.services?.[this.serviceIndex]?.children ?? []),
                    ...modifiedProducts,
                ];
                this.services[this.serviceIndex].hourlyRate = this.services[
                    this.serviceIndex
                ].children.reduce((acc, curr) => {
                    return acc + +curr.quantity * +curr.hourlyRate;
                }, 0);
                const totalWithoutDiscountInner =
                    +this.services[this.serviceIndex].quantity *
                    +this.services[this.serviceIndex].hourlyRate;
                const discountAmountInner =
                    (+totalWithoutDiscountInner *
                        +this.services[this.serviceIndex].discount) /
                    100;
                const nettoTotalInner =
                    +totalWithoutDiscountInner - +discountAmountInner;
                const taxRateInner =
                    (+nettoTotalInner * +this.services[this.serviceIndex].tax) /
                    100;
                this.services[this.serviceIndex].nettoTotal = nettoTotalInner;
                this.services[this.serviceIndex].nettoTotal = taxRateInner;
                this.serviceIndex = null;
                this.addServiceChildren = false;
            } else {
                this.services = [...this.services, ...modifiedProducts];
            }
            this.computeServicesByCategory();
            this.serviceToggle = false;
        },
        inputWidth(index, productName = "name", type) {
            const width =
                type === "softwareLicences"
                    ? this.softwareLicences[index][productName]?.length
                    : this.softwareMaintenance[index][productName]?.length;
            return { width: parseFloat(width) + 4 + "ch" };
        },
        /**
         * adds terms of payment based on the customer
         * @param {customer} the customer from which the terms of payment is selected
         */
        addTermsOfPayment(customer) {
            this.form.termsOfPayment =
                this.termsOfPayment?.data?.find(
                    (terms) => terms.id == customer?.termsOfPayment
                ) ?? "";
            this.updatedTermsOfPayment();
        },
        addProducts(products) {
            if (this.productType === "hosting") {
                const modifiedProducts = [
                    ...products.map((product) => {
                        const modifiedProduct = { ...product };
                        if (modifiedProduct.type === "pauschal") {
                            modifiedProduct.pricePerPeriod = product.salePrice;
                        }
                        const totalWithoutDiscount =
                            +modifiedProduct.quantity *
                            +modifiedProduct.pricePerPeriod;
                        const discountAmount =
                            (+totalWithoutDiscount *
                                +modifiedProduct.discount) /
                            100;
                        const nettoTotal =
                            +totalWithoutDiscount - +discountAmount;
                        const taxRate =
                            (+nettoTotal * +modifiedProduct.tax) / 100;
                        return {
                            ...modifiedProduct,
                            productInvoiceId: uuid(), //uuid is required because we need a unique identifier for additionalDescriptionToggled since multiple products with the same id can be added
                            discount: modifiedProduct.discount ?? 0,
                            paymentPeriod: modifiedProduct?.paymentPeriod?.id,
                            nettoTotal: nettoTotal,
                            taxRate: taxRate,
                            additionalDescription:
                                modifiedProduct?.additionalDescription ?? "",
                            children: modifiedProduct?.children ?? [],
                            totalPricePeriod:
                                +modifiedProduct.quantity *
                                +modifiedProduct.pricePerPeriod,
                        };
                    }),
                ];
                // if the products added are children then do the calculations
                if (
                    this.addHostingChildren &&
                    typeof this.hostingIndex === "number"
                ) {
                    this.hosting[this.hostingIndex].pricePerPeriod = 0;
                    this.hosting[this.hostingIndex].children = [
                        ...(this.hosting?.[this.hostingIndex]?.children ?? []),
                        ...modifiedProducts,
                    ];
                    this.hosting[this.hostingIndex].pricePerPeriod =
                        this.hosting[this.hostingIndex].children.reduce(
                            (acc, curr) => {
                                return (
                                    acc + +curr.quantity * +curr.pricePerPeriod
                                );
                            },
                            0
                        );
                    const totalWithoutDiscountInner =
                        +this.hosting[this.hostingIndex].quantity *
                        +this.hosting[this.hostingIndex].pricePerPeriod;
                    const discountAmountInner =
                        (+totalWithoutDiscountInner *
                            +this.hosting[this.hostingIndex].discount) /
                        100;
                    const nettoTotalInner =
                        +totalWithoutDiscountInner - +discountAmountInner;
                    const taxRateInner =
                        (+nettoTotalInner *
                            +this.hosting[this.hostingIndex].tax) /
                        100;
                    this.hosting[this.hostingIndex].nettoTotal =
                        nettoTotalInner;
                    this.hosting[this.hostingIndex].taxRate = taxRateInner;
                    this.hostingIndex = null;
                    this.addHostingChildren = false;
                } else {
                    this.hosting = [...this.hosting, ...modifiedProducts];
                }
            } else if (this.productType === "cloud-software") {
                const modifiedProducts = [
                    ...products.map((product) => {
                        const totalWithoutDiscount =
                            +product.quantity * +product.salePrice;
                        const discountAmount =
                            (+totalWithoutDiscount * +product.discount) / 100;
                        const nettoTotal =
                            +totalWithoutDiscount - +discountAmount;
                        const taxRate = (+nettoTotal * +product.tax) / 100;
                        return {
                            ...product,
                            productInvoiceId: uuid(), //uuid is required because we need a unique identifier for additionalDescriptionToggled since multiple products with the same id can be added
                            discount: product.discount ?? 0,
                            paymentPeriod: product?.paymentPeriod?.id,
                            nettoTotal: nettoTotal,
                            taxRate: taxRate,
                            duration: 1,
                            additionalDescription:
                                product?.additionalDescription ?? "",
                            children: product?.children ?? [],
                            totalPricePeriod:
                                +product.quantity *
                                +(product.duration ?? 1) *
                                +product.salePrice,
                        };
                    }),
                ];
                // if the products added are children then do the calculations
                if (
                    this.addCloudChildren &&
                    typeof this.cloudIndex === "number"
                ) {
                    this.cloud[this.cloudIndex].salePrice = 0;
                    this.cloud[this.cloudIndex].children = [
                        ...(this.cloud?.[this.cloudIndex]?.children ?? []),
                        ...modifiedProducts,
                    ];
                    this.cloud[this.cloudIndex].salePrice = this.cloud[
                        this.cloudIndex
                    ].children.reduce((acc, curr) => {
                        return (
                            acc +
                            +curr.quantity *
                                +(curr.duration ?? 1) *
                                +curr.salePrice
                        );
                    }, 0);
                    const totalWithoutDiscountInner =
                        +this.cloud[this.cloudIndex].quantity *
                        +(this.cloud[this.cloudIndex].duration ?? 1) *
                        +this.cloud[this.cloudIndex].salePrice;
                    const discountAmountInner =
                        (+totalWithoutDiscountInner *
                            +this.cloud[this.cloudIndex].discount) /
                        100;
                    const nettoTotalInner =
                        +totalWithoutDiscountInner - +discountAmountInner;
                    const taxRateInner =
                        (+nettoTotalInner * +this.cloud[this.cloudIndex].tax) /
                        100;
                    this.cloud[this.cloudIndex].nettoTotal = nettoTotalInner;
                    this.cloud[this.cloudIndex].taxRate = taxRateInner;
                    this.cloudIndex = null;
                    this.addCloudChildren = false;
                } else {
                    this.cloud = [...this.cloud, ...modifiedProducts];
                }
            } else if (this.productType === "software") {
                this.softwareLicences = [
                    ...this.softwareLicences,
                    ...(products?.map((product) => {
                        return {
                            ...product,
                            productInvoiceId: uuid(), //uuid is required because we need a unique identifier for additionalDescriptionToggled since multiple products with the same id can be added
                            discount: product.discount ?? 0,
                            additionalDescription:
                                product?.additionalDescription ?? "",
                        };
                    }) ?? []),
                ];
                this.softwareLicences.forEach((item, index) => {
                    this.calculateProductValue(index, "softwareLicences");
                });
                this.softwareLicencesTax = this.calculateTax(
                    "softwareLicences"
                )?.map((product) => {
                    return {
                        ...product,
                        totalSalePriceAdjustedForQuantity:
                            +product.salePrice * +product.quantity,
                    };
                });
                this.softwareMaintenance = [
                    ...this.softwareMaintenance,
                    ...JSON.parse(JSON.stringify(products))?.map((product) => {
                        return {
                            ...product,
                            maintenanceRate: product?.maintenanceRate ?? 0,
                            productInvoiceId: uuid(), //uuid is required because we need a unique identifier for additionalDescriptionToggled since multiple products with the same id can be added
                            baseMaintenancePrice:
                                (+product.salePrice *
                                    +product.maintenanceRate) /
                                100,
                            discount: product.discount ?? 0,
                            totalSalePriceAdjustedForQuantity:
                                +product.salePrice * +product.quantity,
                            additionalDescription:
                                product?.additionalDescription ?? "",
                        };
                    }),
                ];
                this.softwareMaintenance.forEach((item, index) => {
                    this.calculateProductValue(index, "softwareMaintenance");
                });
                this.softwareMaintenanceTax = this.calculateTax(
                    "softwareMaintenance"
                );
            }
            this.productToggle = false;
        },
        calculateProductValue(index, type, fieldChanged, event) {
            let products =
                type === "softwareLicences"
                    ? this.softwareLicences
                    : this.softwareMaintenance;
            products[index].totalPrice =
                products[index].salePrice * products[index].quantity;
            products[index].totalSalePriceAdjustedForQuantity =
                products[index].salePrice * products[index].quantity;
            if (products[index]?.discount) {
                products[index].totalPrice =
                    products[index].totalPrice *
                    (1 - products[index]?.discount / 100);
            }
            if (type === "softwareLicences") {
                if (
                    !fieldChanged ||
                    event?.target?.name === "salePrice" ||
                    event?.target?.name === "quantity" ||
                    event?.target?.name === "discount"
                )
                    products[index].priceWithoutTax =
                        products[index].totalPrice.toFixed(2);
                else {
                    this.softwareLicences[index].salePrice = (
                        this.softwareLicences[index].priceWithoutTax /
                        (1 - this.softwareLicences[index].discount / 100)
                    ).toFixed(2);
                }
            } else {
                if (
                    event?.target?.name !== "maintenancePrice" &&
                    event?.target?.name !== "priceWithoutTax"
                ) {
                    this.softwareMaintenance[index].maintenancePrice = (
                        (this.softwareMaintenance[index]
                            .totalSalePriceAdjustedForQuantity *
                            this.softwareMaintenance[index].maintenanceRate) /
                        100
                    ).toFixed(2);
                    products[index].baseMaintenancePrice =
                        (+products[index].salePrice *
                            +products[index].maintenanceRate) /
                        100;
                } else if (event?.target?.name !== "maintenancePrice") {
                    products[index].maintenancePrice =
                        parseFloat(
                            products[index].totalSalePriceAdjustedForQuantity
                        ).toFixed(2) * products[index].quantity;
                } else if (event?.target?.name === "maintenancePrice") {
                    products[index].totalSalePriceAdjustedForQuantity =
                        (100 * +products[index].maintenancePrice) /
                        +products[index].maintenanceRate;
                    products[index].salePrice =
                        +products[index].totalSalePriceAdjustedForQuantity /
                        +products[index].quantity;
                    products[index].baseMaintenancePrice =
                        (+products[index].salePrice *
                            +products[index].maintenanceRate) /
                        100;
                }
            }
            if (products[index]?.tax && !this.form.applyReverseChange) {
                products[index].totalPrice =
                    products[index].totalPrice *
                    (products[index]?.tax / 100 + 1);
            }

            products[index].totalPrice = products[index].totalPrice.toFixed(2);

            if (type === "softwareLicences")
                this.softwareLicencesTax = this.calculateTax(type);
            else this.softwareMaintenanceTax = this.calculateTax(type);
            if (fieldChanged) {
                if (type === "softwareLicences") {
                    this.softwareMaintenance[index].quantity =
                        this.softwareLicences[index].quantity;
                    this.softwareMaintenance[index].salePrice =
                        this.softwareLicences[index].salePrice;
                    this.softwareMaintenance[index].maintenancePrice = (
                        (this.softwareLicences[index]
                            .totalSalePriceAdjustedForQuantity *
                            this.softwareLicences[index].maintenanceRate) /
                        100
                    ).toFixed(2);
                    this.$nextTick(() => {
                        this.calculateProductValue(
                            index,
                            "softwareMaintenance"
                        );
                    });
                } else {
                    this.softwareLicences[index].quantity =
                        this.softwareMaintenance[index].quantity;
                    if (event.target.name === "maintenanceRate") {
                        this.softwareMaintenance[index].maintenancePrice = (
                            (this.softwareMaintenance[index]
                                .totalSalePriceAdjustedForQuantity *
                                this.softwareMaintenance[index]
                                    .maintenanceRate) /
                            100
                        ).toFixed(2);
                    } else
                        this.softwareLicences[index].maintenancePrice =
                            this.softwareMaintenance[index].maintenancePrice;
                    this.$nextTick(() => {
                        this.calculateProductValue(index, "softwareLicences");
                    });
                }
            }
        },
        calculateTax(type) {
            const products =
                type === "softwareLicences"
                    ? this.softwareLicences
                    : this.softwareMaintenance;

            const groupedProducts = products.reduce((result, product) => {
                const tax = product.tax;
                if (!result[tax]) {
                    result[tax] = [];
                }
                result[tax].push(product);
                return result;
            }, {});

            const result = Object.entries(groupedProducts).map(
                ([tax, product]) => ({
                    percent: tax,
                    amount:
                        type === "softwareLicences"
                            ? product.reduce(
                                  (sum, p) =>
                                      sum +
                                      (parseFloat(p.totalPrice) || 0) -
                                      (parseFloat(p.priceWithoutTax) || 0),
                                  0
                              )
                            : (product.reduce(
                                  (sum, p) =>
                                      sum + parseFloat(p.maintenancePrice),
                                  0
                              ) *
                                  tax) /
                              100,
                })
            );

            return result;
        },
        async store() {
            await this.$nextTick();
            if (this.disabled) return;
            this.disabled = true;
            let products = [];
            let categories = []; // if the category is 'service' and groupedBy is 'category' then map the categories to this array to send along the payload
            if (this.form.category === "license") {
                this.form.totalAmount = this.totalAmountLicences;
                this.form.totalAmountWithoutTax =
                    this.totalAmountWithoutTaxLicences;
                this.form.totalTaxAmount = this.$formatter(
                    this.softwareLicencesTax?.[0]?.amount?.toFixed(2),
                    this.globalLanguage
                );
                products = this.softwareLicences;
            } else if (this.form.category === "maintenance") {
                this.form.totalAmount = this.totalAmountMaintenance;
                this.form.totalAmountWithoutTax =
                    this.totalAmountWithoutTaxMaintenance;
                this.form.totalTaxAmount = this.$formatter(
                    this.softwareMaintenanceTax?.[0]?.amount?.toFixed(2),
                    this.globalLanguage
                );
                products = this.softwareMaintenance.map((product) => {
                    return {
                        ...product,
                        priceWithoutTax: product.maintenancePrice,
                    };
                });
            } else if (this.form.category === "service") {
                this.form.totalAmount =
                    this.form.groupedBy === "category"
                        ? this.computeSummaryCategory
                        : this.computeSummary;
                this.form.totalAmountWithoutTax =
                    this.form.groupedBy === "category"
                        ? this.computeNettoCategory
                        : this.computeNetto;
                this.form.totalTaxAmount = this.$formatter(
                    Object.values(
                        this.form.groupedBy === "category"
                            ? this.computeMwstCategory
                            : this.computeMwst
                    )?.[0],
                    this.globalLanguage
                );
                products = this.services.map((service) => {
                    return {
                        ...service,
                        totalPrice: this.computeSummary,
                        priceWithoutTax: this.computeNetto,
                        additionalDescription:
                            service?.additionalDescription ?? "",
                        children: service.children.map((child) => {
                            return {
                                ...child,
                                totapPrice: child.totalRate,
                                productId: child?.productId ?? child?.id,
                            };
                        }),
                    };
                });
                if (this.form.groupedBy === "category") {
                    categories = this.categories.map((category) => {
                        return {
                            id: category.id,
                            quantity: category.quantity,
                            hourlyRate: category.hourlyRate,
                            discount: category.discount,
                            tax: category.tax,
                            taxRate: category.taxRate,
                            nettoTotal: category.nettoTotal,
                            additionalDescription:
                                category?.additionalDescription ?? "",
                        };
                    });
                }
            } else if (this.form.category === "ams") {
                this.form.totalAmount = this.computeSummaryAms;
                this.form.totalAmountWithoutTax = this.computeNettoAms;
                this.form.totalTaxAmount = this.$formatter(
                    Object.values(this.computeMwstAms)?.[0],
                    this.globalLanguage
                );
                products = this.ams.map((service) => {
                    return {
                        ...service,
                        totalPrice: this.computeSummaryAms,
                        priceWithoutTax: this.computeNettoAms,
                        quantity: service.quantity,
                    };
                });
            } else if (this.form.category === "hosting") {
                this.form.totalAmount = this.computeSummaryHosting;
                this.form.totalAmountWithoutTax = this.computeNettoHosting;
                this.form.totalTaxAmount = this.$formatter(
                    Object.values(this.computeMwstHosting)?.[0],
                    this.globalLanguage
                );
                products = this.hosting.map((product) => {
                    return {
                        ...product,
                        totalPrice: this.computeSummaryHosting,
                        priceWithoutTax: this.computeNettoHosting,
                        salePrice: product.pricePerPeriod,
                        pricePerPeriod: product.pricePerPeriod,
                        quantity: product.quantity,
                        children: (product.children ?? []).map((child) => {
                            return {
                                ...child,
                                totalPrice: child.totalPricePeriod,
                                salePrice: child.pricePerPeriod,
                                productId: child?.productId ?? child?.id,
                            };
                        }),
                    };
                });
            } else if (this.form.category === "cloud-software") {
                this.form.totalAmount = this.computeSummaryCloud;
                this.form.totalAmountWithoutTax = this.computeNettoCloud;
                this.form.totalTaxAmount = this.$formatter(
                    Object.values(this.computeMwstCloud)?.[0],
                    this.globalLanguage
                );
                products = this.cloud.map((product) => {
                    return {
                        ...product,
                        totalPrice: this.computeSummaryCloud,
                        priceWithoutTax: this.computeNettoCloud,
                        quantity: product.quantity,
                        children: (product.children ?? []).map((child) => {
                            return {
                                ...child,
                                totalPrice: child.totalPricePeriod,
                                productId: child?.productId ?? child?.id,
                            };
                        }),
                    };
                });
            }
            try {
                let templatePayload = "invoiceTemplateId";
                //Get invoice type
                if (this.form.invoiceType == "invoice-correction") {
                    templatePayload = "invoiceCorrectionTemplateId";
                } else if (this.form.invoiceType == "invoice-storno") {
                    templatePayload = "invoiceStornoTemplateId";
                }

                this.$store.commit("isLoading", true);
                const response = await this.$store.dispatch("invoices/create", {
                    ...this.form,
                    startDate: this.form.startDate?.toLocaleDateString(),
                    endDate: this.form.endDate?.toLocaleDateString(),
                    dueDate: this.form.dueDate?.toLocaleDateString(),
                    draftStatusChangeDate: this.form.draftStatusChangeDate
                        ? this.form.draftStatusChangeDate?.toLocaleDateString()
                        : null,
                    categories: categories,
                    products: products,
                    userId: this.user.id,
                    projectId: this.form.projectId?.id ?? "",
                    performanceRecord: this.form.performanceRecord?.id ?? "",
                    termsOfPayment: this.form?.termsOfPayment?.id,
                    invoicePeriod: this.form?.invoicePeriod,
                    contactPerson: this.form?.contactPerson?.id,
                     isInvoiceCancel:1,
                     invoiceId:this.referenceInvoiceId?.id,
                    ...(this.form?.nextCreateDate && {
                        nextCreateDate: this.formatDateLite(
                            this.form?.nextCreateDate
                        ),
                    }),
                });
                await this.sendEloRequest(response?.data?.id, "createInvoice");
                await this.$store.dispatch(
                    "invoices/list"
                    // TODO: need discussion
                    // {
                    //     offerType: this.module,
                    // }
                );
                this.$router.push("/invoices");
            } catch (e) {
                console.log(e);
                this.disabled = false;
            }
        },
    },
};
</script>
<style scoped lang="scss">
.table-align {
    width: 250px !important;
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
    &:hover {
        text-overflow: inherit;
        white-space: wrap;
    }
}
.input-width {
    width: 100px !important;
}
</style>

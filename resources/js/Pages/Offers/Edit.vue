<template>
    <PageHeader :items="breadcrumbItems" />

    <div class="w-full my-5">
        <div class="form-group">
            <!-- only shows in the order confirmation module -->
            <MultiSelectInput
                v-if="module === 'order'"
                :show-labels="false"
                @update:model-value="offerSelected"
                :isDisabled="module === 'order' && form.orderStatus !== 'draft'"
                v-model="selectedOffer"
                :key="selectedOffer"
                :options="offers.data"
                :multiple="false"
                :textLabel="$t('Offer')"
                label="offerNumber"
                trackBy="id"
                moduleName="offers"
                :showLoadMoreText="true"
                :error="errors.selectedOffer"
            >
                <template #beforeList>
                    <div
                        class="grid p-2 gap-2"
                        style="grid-template-columns: 33.33% 33.33% 33.33%"
                    >
                        <p class="font-bold">{{ $t("Offer Number") }}</p>
                        <p class="font-bold">
                            {{ $t("Customer") + "/" + $t("Lead") }}
                        </p>
                        <p class="font-bold">{{ $t("Netto Total") }}</p>
                    </div>
                    <hr />
                </template>
                <template #singleLabel="{ props }">
                    {{ props.option.offerNumber }}
                </template>
                <template #option="{ props }">
                    <div
                        class="grid gap-2"
                        style="grid-template-columns: 33.33% 33.33% 33.33%"
                    >
                        <p class="overflow-text-users-table location-length">
                            {{ props.option.offerNumber }}
                        </p>
                        <p class="overflow-text-users-table location-length">
                            {{ props.option.company }}
                        </p>
                        <p class="overflow-text-users-table location-length">
                            {{
                                $formatter(
                                    props.option.totalNetto,
                                    globalLanguage
                                )
                            }}
                        </p>
                    </div>
                </template>
            </MultiSelectInput>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $t("Configuration") }}</h3>
        </div>
        <div class="card-body">
            <div class="grid items-center grid-cols-4 gap-6">
                <div class="w-full">
                    <div class="form-group">
                        <select-input
                            :required="true"
                            :key="form.receiverType"
                            :is-read-only="
                                module === 'order' &&
                                form.orderStatus !== 'draft'
                            "
                            v-model="form.receiverType"
                            @update:model-value="form.contactPerson = ''"
                            :error="errors.receiverType"
                            :label="$t('Receiver Type')"
                        >
                            <option value="customer">
                                {{ $t("Customer") }}
                            </option>
                            <option value="lead">{{ $t("Lead") }}</option>
                            <option value="partner">{{ $t("Partner") }}</option>
                        </select-input>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            v-model="form.company"
                            :key="form.company"
                            :required="true"
                            :isDisabled="
                                module === 'order' &&
                                form.orderStatus !== 'draft'
                            "
                            :textLabel="$t('Receiver')"
                            :placeholder="$t('Select Receiver')"
                            @update:model-value="
                                fetchCompanyLeadEmployees(true);
                                fetchProjectsByCustomer();
                            "
                            :options="
                                form.receiverType === 'lead'
                                    ? leads.data
                                    : form.receiverType === 'partner'
                                    ? partners.daya
                                    : companies.data
                            "
                            :multiple="false"
                            label="companyName"
                            trackBy="id"
                            moduleName="companies"
                            :error="errors.companyId"
                            :query="{ customerType: form.receiverType }"
                            :countStore="
                                form.receiverType === 'lead'
                                    ? 'leadCount'
                                    : form.receiverType === 'partner'
                                    ? 'partnerCount'
                                    : 'count'
                            "
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            :customLabel="customLabel"
                            v-model="form.contactPerson"
                            :isDisabled="
                                module === 'order' &&
                                form.orderStatus !== 'draft'
                            "
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
                        <MultiSelectInput
                            :customLabel="customLabel"
                            v-model="form.createdByShow"
                            :key="form.createdByShow"
                            :textLabel="$t('Created By')"
                            :options="users"
                            :multiple="false"
                            trackBy="id"
                            moduleName="companyEmployees"
                            :error="errors.createdByShow"
                            :isDisabled="true"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            :required="true"
                            :isDisabled="
                                module === 'order' &&
                                form.orderStatus !== 'draft'
                            "
                            v-model="form.term"
                            :key="form.term"
                            :error="errors.termId"
                            :multiple="false"
                            trackBy="id"
                            label="name"
                            :options="termsOfPayment.data"
                            moduleName="termsOfPayment"
                            :textLabel="$t('Terms Of Payment')"
                            @update:model-value="addPaymentTerms"
                        ></MultiSelectInput>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            v-model="form.project"
                            :key="form.project"
                            :isDisabled="
                                module === 'order' &&
                                form.orderStatus !== 'draft'
                            "
                            :textLabel="$t('Project')"
                            :placeholder="$t('Select Receiver')"
                            :options="projects.data"
                            :multiple="false"
                            label="name"
                            trackBy="id"
                            moduleName="projects"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <select-input
                            :required="true"
                            v-model="form.type"
                            :error="errors.type"
                            :is-read-only="
                                module === 'order' &&
                                form.orderStatus !== 'draft'
                            "
                            :label="$t('Offer Type')"
                        >
                            <option value="budget">
                                {{ $t("Price Indication") }}
                            </option>
                            <option value="offer">{{ $t("Offer") }}</option>
                        </select-input>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <select-input
                            v-if="form.type === 'budget'"
                            v-model="form.template"
                            :error="errors.template"
                            :is-read-only="
                                module === 'order' &&
                                form.orderStatus !== 'draft'
                            "
                            :label="$t('Template')"
                        >
                            <option value="empty">{{ $t("Empty") }}</option>
                            <option value="project">{{ $t("Project") }}</option>
                        </select-input>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <select-input
                            :is-read-only="
                                module === 'order' &&
                                form.orderStatus !== 'draft'
                            "
                            v-if="form.type === 'budget' && shouldShow"
                            v-model="form.productsGroupedBy"
                            :error="errors.productsGroupedBy"
                            :label="$t('Grouped By')"
                        >
                            <option value="nothing">{{ $t("Nothing") }}</option>
                            <option value="category">
                                {{ $t("Category") }}
                            </option>
                        </select-input>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-area-input
                            :isReadonly="
                                module === 'order' &&
                                form.orderStatus !== 'draft'
                            "
                            v-model="form.paymentTerms"
                            :label="$t('Payment Terms')"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <label class="form-label"
                            ><span style="color: red">*</span>&nbsp;{{
                                $t("Expiry Date")
                            }}:</label
                        >
                        <datepicker
                            :clearable="false"
                            :enable-time-picker="false"
                            auto-apply
                            :close-on-auto-apply="false"
                            :disabled="
                                module === 'order' &&
                                form.orderStatus !== 'draft'
                            "
                            :style="dropdownStyles"
                            v-model="form.expiryDate"
                            :class="errors.expiryDate ? 'error' : ''"
                        />
                        <div v-if="errors?.expiryDate" class="form-error">
                            {{ errors.expiryDate }}
                        </div>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <select-input
                            v-if="module === 'offer'"
                            v-model="form.offerStatus"
                            :key="form.offerStatus"
                            :error="errors.offerStatus"
                            :label="$t('Status')"
                        >
                            <option value="entwurf">{{ $t("Entwurf") }}</option>
                            <option value="versendet">
                                {{ $t("Versendet") }}
                            </option>
                            <option value="beauftragt">
                                {{ $t("Beauftragt") }}
                            </option>
                            <option value="abgelehnt">
                                {{ $t("Abgelehnt") }}
                            </option>
                        </select-input>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <p>{{ $t("Components") }}:</p>
                        <div class="mt-2">
                            <div class="flex">
                                <input
                                    @input="
                                        resetProductsUponUncheck(
                                            $event,
                                            'softwareLicenses'
                                        );
                                        softwareMaintenanceToggle =
                                            !softwareMaintenanceToggle;
                                    "
                                    v-model="softwareLicencesToggle"
                                    type="checkbox"
                                />
                                <label class="ml-1" for="">{{
                                    $t("Software Licences")
                                }}</label>
                            </div>
                            <div class="flex">
                                <input
                                    @input="
                                        resetProductsUponUncheck(
                                            $event,
                                            'softwareMaintenance'
                                        );
                                        softwareLicencesToggle =
                                            !softwareLicencesToggle;
                                    "
                                    v-model="softwareMaintenanceToggle"
                                    type="checkbox"
                                />
                                <label class="ml-1" for="">{{
                                    $t("Software Maintenance")
                                }}</label>
                            </div>
                            <div class="flex">
                                <input
                                    @input="
                                        resetProductsUponUncheck(
                                            $event,
                                            'services'
                                        )
                                    "
                                    v-model="servicesToggle"
                                    type="checkbox"
                                />
                                <label class="ml-1" for="">{{
                                    $t("Services")
                                }}</label>
                            </div>
                            <div class="flex">
                                <input
                                    @input="
                                        fetchSLA();
                                        resetProductsUponUncheck(
                                            $event,
                                            'application'
                                        );
                                    "
                                    v-model="applicationManagementServiceToggle"
                                    type="checkbox"
                                />
                                <label class="ml-1" for="">{{
                                    $t("Application Management Service")
                                }}</label>
                            </div>
                            <div class="flex">
                                <input
                                    @input="
                                        resetProductsUponUncheck(
                                            $event,
                                            'hosting'
                                        )
                                    "
                                    v-model="hostingToggle"
                                    type="checkbox"
                                />
                                <label class="ml-1" for="">{{
                                    $t("Hosting")
                                }}</label>
                            </div>
                            <div class="flex">
                                <input
                                    @input="
                                        resetProductsUponUncheck(
                                            $event,
                                            'cloud-software'
                                        )
                                    "
                                    v-model="cloudToggle"
                                    type="checkbox"
                                />
                                <label class="ml-1" for="">{{
                                    $t("Cloud")
                                }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <select-input
                            v-if="module === 'order'"
                            v-model="form.orderStatus"
                            :key="form.orderStatus"
                            :error="errors.orderStatus"
                            :label="$t('Confirmation Status')"
                        >
                            <option value="draft">{{ $t("Draft") }}</option>
                            <option value="approved">
                                {{ $t("Approved") }}
                            </option>
                            <option value="sent">{{ $t("Sent") }}</option>
                            <option value="done">{{ $t("Done") }}</option>
                        </select-input>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <label class="mr-5" for="remove-from-statistics"
                            >{{ $t("Remove From Statistics") }}:</label
                        >
                        <input
                            id="remove-from-statistics"
                            type="checkbox"
                            :checked="form.removeFromStatistics"
                            v-model="form.removeFromStatistics"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <label class="mr-5" for="is-hide-customer"
                            >{{ $t("Hide from customer") }}:</label
                        >
                        <input
                            id="is-hide-customer"
                            type="checkbox"
                            :checked="isVisibleForCustomer"
                            v-model="isVisibleForCustomer"
                        />
                    </div>
                </div>
                <div class="w-full" v-if="module === 'order'">
                    <div class="form-group">
                        <label class="form-label"
                            >{{ $t("Planned Start Date") }}:</label
                        >
                        <datepicker
                            :style="dropdownStyles"
                            :clearable="false"
                            :enable-time-picker="false"
                            auto-apply
                            :close-on-auto-apply="false"
                            v-model="form.plannedStartDate"
                            :class="errors.plannedStartDate ? 'error' : ''"
                        />
                        <div v-if="errors?.plannedStartDate" class="form-error">
                            {{ errors.plannedStartDate }}
                        </div>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <TextInput
                            v-model="form.externalOrderNumber"
                            :error="errors.externalOrderNumber"
                            :label="$t('External Order Number')"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <TextInput
                            v-model="form.identifier"
                            :error="errors.identifier"
                            :label="$t('Identifier')"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="flex">
            <div class="w-1/2">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Cover Letter Text") }}</h3>
                </div>
                <div class="card-body">
                    <div class="w-full">
                        <div class="form-group">
                            <text-area-input
                                :isReadonly="
                                    module === 'order' &&
                                    form.orderStatus === 'sent'
                                "
                                v-model="form.coverLetterText"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-1/2">
                <div class="card-header">
                    <h3 class="card-title">
                        {{
                            $t(
                                `${
                                    this.module === "offer"
                                        ? "Offer Description Text"
                                        : "Offer Confirmation Description Text"
                                }`
                            )
                        }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="w-full">
                        <div class="form-group">
                            <text-area-input
                                :isReadonly="
                                    module === 'order' &&
                                    form.orderStatus === 'sent'
                                "
                                v-model="form.offerDescriptionText"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">{{ $t("Company Details") }}</h3>
        </div>
        <div class="card-body">
            <div class="">
                <span>
                    {{ form.company?.companyName }}
                    <div v-if="form.contactPerson">
                        {{ form.contactPerson?.first_name }}
                        {{ form.contactPerson?.last_name }}
                        <br />
                    </div>
                    <br v-else />
                    {{ form.company?.address }}
                    <br />
                    {{ form.company?.code }}&nbsp;{{ form.company?.city }}
                    <br />
                    {{ form.company?.state }}
                    <br />
                    {{ form.company?.country }}
                </span>
            </div>
        </div>
    </div>

    <div class="card my-5">
        <div class="card-header">
            <h3 class="card-title flex justify-between">
                <h1 class="secondary-color text-2xl">{{ form.offerNumber }}</h1>
                <h6>
                    {{
                        $dateFormatter(
                            formatDateLite(this.form.updatedAt),
                            globalLanguage
                        )
                    }}
                </h6>
            </h3>
        </div>
        <div class="card-body">
            <div
                class="grid gap-2"
                style="grid-template-columns: repeat(auto-fit, 500px)"
            >
                <div class="mb-8" style="color: #2957a4">
                    <div
                        class="grid gap-2"
                        style="grid-template-columns: repeat(auto-fit, 200px)"
                    >
                        <b>{{ $t("Customer Nr.") }}</b>
                        <p>{{ form?.company?.companyNumber ?? "-" }}</p>
                    </div>
                    <div
                        class="grid gap-2"
                        style="grid-template-columns: repeat(auto-fit, 200px)"
                    >
                        <b>{{ $t("Project Nr.") }}</b>
                        <p>{{ form?.project?.projectId ?? "-" }}</p>
                    </div>
                    <div
                        class="grid gap-2"
                        style="grid-template-columns: repeat(auto-fit, 200px)"
                    >
                        <b>{{ $t("Offer Nr.") }}</b>
                        <p>{{ form.offerNumber }}</p>
                    </div>
                </div>
                <div class="mb-8" style="color: #2957a4">
                    <div
                        v-if="user"
                        class="grid gap-2"
                        style="grid-template-columns: repeat(auto-fit, 200px)"
                    >
                        <b>{{ $t("Created By") }}:</b>
                        <p>{{ user?.first_name }} {{ user?.last_name }}</p>
                    </div>
                    <div
                        v-if="form?.expiryDate"
                        class="grid gap-2"
                        style="grid-template-columns: repeat(auto-fit, 200px)"
                    >
                        <b>{{ $t("Expiry Date") }}:</b>
                        <p>
                            {{
                                $dateFormatter(
                                    formatDateLite(new Date(form?.expiryDate)),
                                    globalLanguage
                                )
                            }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="grid-template-rows: repeat(auto-fill, auto)" class="grid gap-3">
        <div v-if="softwareLicencesToggle">
            <div class="tabs">
                <div class="relative tab">
                    <input
                        class="tab-checkbox"
                        type="checkbox"
                        id="software-license-accordion"
                        :checked="true"
                    />
                    <label
                        style="background-color: #2957a4"
                        class="rounded tab-label styles-configurator-tab-label flex justify-between p-2 text-white"
                        for="software-license-accordion"
                    >
                        <p class="self-center">{{ $t("Software licences") }}</p>
                        <button
                            type="button"
                            style="background-color: #ed7d31"
                            @click="
                                toggleProductsModal('software');
                                selectedProductType = 'softwareLicences';
                                optionalSoftwareProducts = false;
                            "
                            class="mt-4 sm:mt-0 inline-flex items-start justify-start px-6 py-3 docsHeroColorBlue rounded"
                        >
                            <p
                                class="text-sm font-medium leading-none text-white"
                            >
                                {{ $t("Add Products") }}
                            </p>
                        </button>
                    </label>
                    <div class="tab-content border p-2 overflow-auto">
                        <div class="mt-7 overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th
                                            style="width: 10%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Article number") }}
                                        </th>
                                        <th
                                            style="width: 34%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Product name") }}
                                        </th>
                                        <th
                                            style="width: 8%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Quantity") }}
                                        </th>
                                        <th
                                            style="width: 20%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Product price") }}
                                        </th>
                                        <th
                                            style="width: 8%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Discount") }}(%)
                                        </th>
                                        <th
                                            style="width: 240px"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Netto total") }}
                                        </th>
                                        <th
                                            style="width: 2%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                        ></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <template
                                        v-for="(
                                            product, index
                                        ) in softwareLicences"
                                        :key="index"
                                    >
                                        <tr
                                            class="focus:outline-none h-16 border border-gray-100 rounded"
                                        >
                                            <td class="pl-5 align-top py-2">
                                                <div class="">
                                                    <input
                                                        class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:border-purple-500"
                                                        type="text"
                                                        :class="{
                                                            Perror: errors[
                                                                `products.${index}.articleNumber`
                                                            ],
                                                        }"
                                                        readonly
                                                        v-model="
                                                            product.articleNumber
                                                        "
                                                    />
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="table-align">
                                                    <input
                                                        v-if="
                                                            product.isProductNameEdit
                                                        "
                                                        class="appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="text"
                                                        name="name"
                                                        v-model="product.name"
                                                        :class="{
                                                            Perror: errors[
                                                                `products.${index}.name`
                                                            ],
                                                        }"
                                                    />
                                                    <span v-else>{{
                                                        product.name
                                                    }}</span>
                                                </div>
                                                <!--                                                <div class="">-->
                                                <!--                                                    <input-->
                                                <!--                                                        class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:border-purple-500"-->
                                                <!--                                                        type="text"-->
                                                <!--                                                        :class="{-->
                                                <!--                                                            Perror: errors[-->
                                                <!--                                                                `products.${index}.name`-->
                                                <!--                                                            ],-->
                                                <!--                                                        }"-->
                                                <!--                                                        readonly-->
                                                <!--                                                        v-model="product.name"-->
                                                <!--                                                    />-->
                                                <!--                                                </div>-->
                                                <div
                                                    @click="
                                                        toggleAdditionalDescriptionDropdowns(
                                                            product.id,
                                                            index,
                                                            additionalDescriptionToggled[
                                                                'softwareLicences'
                                                            ][product.id]
                                                                ? 'remove'
                                                                : 'add',
                                                            'softwareLicences'
                                                        )
                                                    "
                                                    class="flex mt-2 cursor-pointer"
                                                >
                                                    <font-awesome-icon
                                                        :class="`cursor-pointer cross mr-2 self-center text-${
                                                            additionalDescriptionToggled[
                                                                'softwareLicences'
                                                            ][product.id]
                                                                ? 'red'
                                                                : 'blue'
                                                        }-500`"
                                                        :icon="`fa-solid fa-circle-${
                                                            additionalDescriptionToggled[
                                                                'softwareLicences'
                                                            ][product.id]
                                                                ? 'minus'
                                                                : 'plus'
                                                        }`"
                                                    />
                                                    <p
                                                        :class="`text-sm text-${
                                                            additionalDescriptionToggled[
                                                                'softwareLicences'
                                                            ][product.id]
                                                                ? 'red'
                                                                : 'blue'
                                                        }-500`"
                                                    >
                                                        {{
                                                            $t(
                                                                `${
                                                                    additionalDescriptionToggled[
                                                                        "softwareLicences"
                                                                    ][
                                                                        product
                                                                            .id
                                                                    ]
                                                                        ? "Remove"
                                                                        : "Add"
                                                                } Additional Description`
                                                            )
                                                        }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="">
                                                    <number-input
                                                        class="input-width appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        @inputEvent="
                                                            calculateProductValue(
                                                                index,
                                                                'softwareLicences',
                                                                true,
                                                                $event
                                                            )
                                                        "
                                                        :showPrefix="false"
                                                        :min="0"
                                                        @keypress="
                                                            limitToPositiveNumbers
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
                                            <td class="pl-5 align-top">
                                                <div class="">
                                                    <number-input
                                                        class="appearance-none rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="text"
                                                        v-model="
                                                            product.salePrice
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
                                                        :allowNegative="true"
                                                    />
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="">
                                                    <input
                                                        class="input-width appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
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
                                            <td
                                                class="pl-5 text-center align-top"
                                            >
                                                <div class="">
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
                                                        :allowNegative="true"
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
                                            <td class="text-center">
                                                <font-awesome-icon
                                                    @click="
                                                        removeOption(
                                                            index,
                                                            'license'
                                                        )
                                                    "
                                                    class="cursor-pointer cross"
                                                    icon="fa-solid fa-xmark"
                                                />
                                            </td>
                                        </tr>
                                        <tr
                                            v-if="
                                                additionalDescriptionToggled[
                                                    'softwareLicences'
                                                ][product.id]
                                            "
                                        >
                                            <td></td>
                                            <td class="pl-6">
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
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                            <br />
                            <div
                                v-if="Object.keys(softwareLicences).length > 0"
                                style="
                                    flex-direction: column;
                                    align-items: end;
                                    justify-content: end;
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
                            <div class="flex justify-between mt-5">
                                <div class="flex">
                                    <label
                                        for="optional-license-products"
                                        class="self-center"
                                        >{{ $t("Optional Products") }}</label
                                    >
                                    <input
                                        class="ml-2"
                                        type="checkbox"
                                        @input="discardOptionalSoftwareProducts"
                                        v-model="optionalSoftwareProductsToggle"
                                        id="optional-license-products"
                                    />
                                </div>
                                <button
                                    v-if="optionalSoftwareProductsToggle"
                                    type="button"
                                    style="background-color: #ed7d31"
                                    @click="
                                        optionalSoftwareProducts = true;
                                        toggleProductsModal('software');
                                    "
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
                                v-if="optionalSoftwareProductsToggle"
                                class="mt-7 overflow-x-auto"
                            >
                                <table class="w-full">
                                    <thead>
                                        <tr>
                                            <th
                                                style="width: 10%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Article number") }}
                                            </th>
                                            <th
                                                style="width: 34%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Product name") }}
                                            </th>
                                            <th
                                                style="width: 8%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Quantity") }}
                                            </th>
                                            <th
                                                style="width: 20%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Product price") }}
                                            </th>
                                            <th
                                                style="width: 8%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Discount") }}(%)
                                            </th>
                                            <th
                                                style="width: 240px"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Netto total") }}
                                            </th>
                                            <th
                                                style="width: 2%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            ></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <template
                                            v-for="(
                                                product, index
                                            ) in optionalSoftwareLicenses"
                                            :key="index"
                                            :tabindex="index"
                                        >
                                            <tr
                                                class="focus:outline-none h-16 border border-gray-100 rounded"
                                            >
                                                <td class="pl-5">
                                                    <div class="">
                                                        <input
                                                            class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:border-purple-500"
                                                            type="text"
                                                            :class="{
                                                                Perror: errors[
                                                                    `products.${index}.articleNumber`
                                                                ],
                                                            }"
                                                            readonly
                                                            v-model="
                                                                product.articleNumber
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td class="pl-5">
                                                    <div class="table-align">
                                                        <input
                                                            v-if="
                                                                product.isProductNameEdit
                                                            "
                                                            class="appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="text"
                                                            name="name"
                                                            v-model="
                                                                product.name
                                                            "
                                                            :class="{
                                                                Perror: errors[
                                                                    `products.${index}.name`
                                                                ],
                                                            }"
                                                        />
                                                        <span v-else>{{
                                                            product.name
                                                        }}</span>
                                                    </div>
                                                    <!--                                                    <div class="">-->
                                                    <!--                                                        <input-->
                                                    <!--                                                            class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:border-purple-500"-->
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
                                                <td class="pl-5">
                                                    <div class="">
                                                        <number-input
                                                            class="input-width appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            @inputEvent="
                                                                calculateProductValue(
                                                                    index,
                                                                    'optionalSoftwareLicenses',
                                                                    true,
                                                                    $event
                                                                )
                                                            "
                                                            :showPrefix="false"
                                                            :min="0"
                                                            @keypress="
                                                                limitToPositiveNumbers
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
                                                <td class="pl-5">
                                                    <div class="">
                                                        <number-input
                                                            class="appearance-none roundedpy-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="text"
                                                            v-model="
                                                                product.salePrice
                                                            "
                                                            @inputEvent="
                                                                calculateProductValue(
                                                                    index,
                                                                    'optionalSoftwareLicenses',
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
                                                            :allowNegative="
                                                                true
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td class="pl-5">
                                                    <div class="">
                                                        <input
                                                            class="input-width appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="text"
                                                            @input="
                                                                calculateProductValue(
                                                                    index,
                                                                    'optionalSoftwareLicenses',
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
                                                <td class="pl-5 text-center">
                                                    <div class="">
                                                        <number-input
                                                            class="appearance-none rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="text"
                                                            @inputEvent="
                                                                calculateProductValue(
                                                                    index,
                                                                    'optionalSoftwareLicenses',
                                                                    true,
                                                                    $event
                                                                )
                                                            "
                                                            :allowNegative="
                                                                true
                                                            "
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
                                                <td class="text-center">
                                                    <font-awesome-icon
                                                        @click="
                                                            removeOption(
                                                                index,
                                                                'optionalLicense'
                                                            )
                                                        "
                                                        class="cursor-pointer cross"
                                                        icon="fa-solid fa-xmark"
                                                    />
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                                <br />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="softwareMaintenanceToggle">
            <div class="tabs">
                <div class="relative tab">
                    <input
                        class="tab-checkbox"
                        type="checkbox"
                        id="software-maintenance-accordion"
                        :checked="true"
                    />
                    <label
                        style="background-color: #2957a4"
                        class="rounded tab-label styles-configurator-tab-label flex justify-between p-2 text-white"
                        for="software-maintenance-accordion"
                    >
                        <p class="self-center">
                            {{ $t("Software Maintenance") }}
                        </p>
                        <button
                            disabled
                            style="background-color: lightgrey"
                            type="button"
                            @click="
                                productToggle = true;
                                selectedProductType = 'softwareMaintenance';
                            "
                            class="mt-4 sm:mt-0 inline-flex items-start justify-start px-6 py-3 docsHeroColorBlue rounded"
                        >
                            <p
                                class="text-sm font-medium leading-none text-white"
                            >
                                {{ $t("Add Products") }}
                            </p>
                        </button>
                    </label>
                    <div class="tab-content border p-2 overflow-auto">
                        <div class="mt-7 overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th
                                            style="width: 10%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Article number") }}
                                        </th>
                                        <th
                                            style="width: 34%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Product name") }}
                                        </th>
                                        <th
                                            style="width: 8%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Quantity") }}
                                        </th>
                                        <th
                                            style="width: 20%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Product Price") }}
                                        </th>
                                        <th
                                            style="width: 8%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Maintenance rate(%)") }}
                                        </th>
                                        <th
                                            style="width: 240px"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Netto total") }}
                                        </th>
                                        <th
                                            style="width: 2%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                        ></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <template
                                        v-for="(
                                            product, index
                                        ) in softwareMaintenance"
                                        :key="index"
                                    >
                                        <tr
                                            class="focus:outline-none h-16 border border-gray-100 rounded"
                                        >
                                            <td class="pl-5 align-top py-2">
                                                <div class="">
                                                    <input
                                                        class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:border-purple-500"
                                                        type="text"
                                                        :class="{
                                                            Perror: errors[
                                                                `products.${index}.articleNumber`
                                                            ],
                                                        }"
                                                        readonly
                                                        v-model="
                                                            product.articleNumber
                                                        "
                                                    />
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="table-align">
                                                    <input
                                                        v-if="
                                                            product.isProductNameEdit
                                                        "
                                                        class="appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="text"
                                                        name="name"
                                                        v-model="product.name"
                                                        :class="{
                                                            Perror: errors[
                                                                `products.${index}.name`
                                                            ],
                                                        }"
                                                    />
                                                    <span v-else>{{
                                                        product.name
                                                    }}</span>
                                                </div>
                                                <!--                                                <div class="">-->
                                                <!--                                                    <input-->
                                                <!--                                                        class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:border-purple-500"-->
                                                <!--                                                        type="text"-->
                                                <!--                                                        :class="{-->
                                                <!--                                                            Perror: errors[-->
                                                <!--                                                                `products.${index}.name`-->
                                                <!--                                                            ],-->
                                                <!--                                                        }"-->
                                                <!--                                                        readonly-->
                                                <!--                                                        v-model="product.name"-->
                                                <!--                                                    />-->
                                                <!--                                                </div>-->
                                                <div
                                                    @click="
                                                        toggleAdditionalDescriptionDropdowns(
                                                            product.id,
                                                            index,
                                                            additionalDescriptionToggled[
                                                                'softwareMaintenance'
                                                            ][product.id]
                                                                ? 'remove'
                                                                : 'add',
                                                            'softwareMaintenance'
                                                        )
                                                    "
                                                    class="flex mt-2 cursor-pointer"
                                                >
                                                    <font-awesome-icon
                                                        :class="`cursor-pointer cross mr-2 self-center text-${
                                                            additionalDescriptionToggled[
                                                                'softwareMaintenance'
                                                            ][product.id]
                                                                ? 'red'
                                                                : 'blue'
                                                        }-500`"
                                                        :icon="`fa-solid fa-circle-${
                                                            additionalDescriptionToggled[
                                                                'softwareMaintenance'
                                                            ][product.id]
                                                                ? 'minus'
                                                                : 'plus'
                                                        }`"
                                                    />
                                                    <p
                                                        :class="`text-sm text-${
                                                            additionalDescriptionToggled[
                                                                'softwareMaintenance'
                                                            ][product.id]
                                                                ? 'red'
                                                                : 'blue'
                                                        }-500`"
                                                    >
                                                        {{
                                                            $t(
                                                                `${
                                                                    additionalDescriptionToggled[
                                                                        "softwareMaintenance"
                                                                    ][
                                                                        product
                                                                            .id
                                                                    ]
                                                                        ? "Remove"
                                                                        : "Add"
                                                                } Additional Description`
                                                            )
                                                        }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="">
                                                    <number-input
                                                        class="input-width appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        @inputEvent="
                                                            calculateProductValue(
                                                                index,
                                                                'softwareMaintenance',
                                                                true,
                                                                $event
                                                            )
                                                        "
                                                        :showPrefix="false"
                                                        :min="0"
                                                        @keypress="
                                                            limitToPositiveNumbers
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
                                            <td class="pl-5 align-top">
                                                <div class="">
                                                    <number-input
                                                        class="appearance-none rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        name="totalSalePriceAdjustedForQuantity"
                                                        @inputEvent="
                                                            calculateProductValue(
                                                                index,
                                                                'softwareMaintenance',
                                                                true,
                                                                $event
                                                            )
                                                        "
                                                        v-model="
                                                            product.totalSalePriceAdjustedForQuantity
                                                        "
                                                        :allowNegative="true"
                                                    />
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="">
                                                    <input
                                                        class="appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
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
                                            <td
                                                class="pl-5 text-center align-top"
                                            >
                                                <div class="">
                                                    <number-input
                                                        class="appearance-none rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="text"
                                                        v-model="
                                                            product.maintenancePrice
                                                        "
                                                        :allowNegative="true"
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
                                            <td class="text-center">
                                                <font-awesome-icon
                                                    @click="
                                                        removeOption(
                                                            index,
                                                            'maintenance'
                                                        )
                                                    "
                                                    class="cursor-pointer cross"
                                                    icon="fa-solid fa-xmark"
                                                />
                                            </td>
                                        </tr>
                                        <tr
                                            v-if="
                                                additionalDescriptionToggled[
                                                    'softwareMaintenance'
                                                ][product.id]
                                            "
                                        >
                                            <td></td>
                                            <td class="pl-6">
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
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                            <br />
                            <div
                                v-if="
                                    Object.keys(softwareMaintenance).length > 0
                                "
                                style="
                                    flex-direction: column;
                                    align-items: end;
                                    justify-content: end;
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
                                                    totalAmountMaintenance,
                                                    globalLanguage
                                                )
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-between mt-5">
                                <div class="flex">
                                    <label
                                        for="optional-license-products"
                                        class="self-center"
                                        >{{ $t("Optional Products") }}</label
                                    >
                                    <input
                                        class="ml-2"
                                        type="checkbox"
                                        @input="discardOptionalSoftwareProducts"
                                        v-model="optionalSoftwareProductsToggle"
                                        id="optional-license-products"
                                    />
                                </div>
                                <button
                                    disabled
                                    style="background-color: lightgrey"
                                    v-if="optionalSoftwareProductsToggle"
                                    type="button"
                                    @click="
                                        optionalSoftwareProducts = true;
                                        toggleProductsModal('software');
                                    "
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
                                v-if="optionalSoftwareProductsToggle"
                                class="mt-7 overflow-x-auto"
                            >
                                <table class="w-full">
                                    <thead>
                                        <tr>
                                            <th
                                                style="width: 10%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Article number") }}
                                            </th>
                                            <th
                                                style="width: 34%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Product name") }}
                                            </th>
                                            <th
                                                style="width: 8%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Quantity") }}
                                            </th>
                                            <th
                                                style="width: 20%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Product Price") }}
                                            </th>
                                            <th
                                                style="width: 8%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Maintenance rate(%)") }}
                                            </th>
                                            <th
                                                style="width: 240px"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Netto total") }}
                                            </th>
                                            <th
                                                style="width: 2%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            ></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <template
                                            v-for="(
                                                product, index
                                            ) in optionalSoftwareMaintenance"
                                            :key="index"
                                            :tabindex="index"
                                        >
                                            <tr
                                                class="focus:outline-none h-16 border border-gray-100 rounded"
                                            >
                                                <td class="pl-5">
                                                    <div class="">
                                                        <input
                                                            class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:border-purple-500"
                                                            type="text"
                                                            :class="{
                                                                Perror: errors[
                                                                    `products.${index}.articleNumber`
                                                                ],
                                                            }"
                                                            readonly
                                                            v-model="
                                                                product.articleNumber
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td class="pl-5">
                                                    <div class="table-align">
                                                        <input
                                                            v-if="
                                                                product.isProductNameEdit
                                                            "
                                                            class="appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="text"
                                                            name="name"
                                                            v-model="
                                                                product.name
                                                            "
                                                            :class="{
                                                                Perror: errors[
                                                                    `products.${index}.name`
                                                                ],
                                                            }"
                                                        />
                                                        <span v-else>{{
                                                            product.name
                                                        }}</span>
                                                    </div>
                                                    <!--                                                    <div class="">-->
                                                    <!--                                                        <input-->
                                                    <!--                                                            class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:border-purple-500"-->
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
                                                <td class="pl-5">
                                                    <div class="">
                                                        <number-input
                                                            class="input-width appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            @inputEvent="
                                                                calculateProductValue(
                                                                    index,
                                                                    'optionalSoftwareMaintenance',
                                                                    true,
                                                                    $event
                                                                )
                                                            "
                                                            :min="0"
                                                            @keypress="
                                                                limitToPositiveNumbers
                                                            "
                                                            :showPrefix="false"
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
                                                <td class="pl-5">
                                                    <div class="">
                                                        <number-input
                                                            class="appearance-none rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            name="totalSalePriceAdjustedForQuantity"
                                                            @inputEvent="
                                                                calculateProductValue(
                                                                    index,
                                                                    'optionalSoftwareMaintenance',
                                                                    true,
                                                                    $event
                                                                )
                                                            "
                                                            v-model="
                                                                product.totalSalePriceAdjustedForQuantity
                                                            "
                                                            :allowNegative="
                                                                true
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td class="pl-5">
                                                    <div class="">
                                                        <input
                                                            class="appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="text"
                                                            v-model="
                                                                product.maintenanceRate
                                                            "
                                                            @input="
                                                                calculateProductValue(
                                                                    index,
                                                                    'optionalSoftwareMaintenance',
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
                                                <td class="pl-5 text-center">
                                                    <div class="">
                                                        <number-input
                                                            class="appearance-none rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
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
                                                                    'optionalSoftwareMaintenance',
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
                                                <td class="text-center">
                                                    <font-awesome-icon
                                                        @click="
                                                            removeOption(
                                                                index,
                                                                'optionalMaintenance'
                                                            )
                                                        "
                                                        class="cursor-pointer cross"
                                                        icon="fa-solid fa-xmark"
                                                    />
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                                <br />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="servicesToggle">
            <div class="tabs">
                <div class="relative tab">
                    <input
                        class="tab-checkbox"
                        type="checkbox"
                        id="services-accordion"
                        :checked="true"
                    />
                    <label
                        style="background-color: #2957a4"
                        class="rounded tab-label styles-configurator-tab-label flex justify-between p-2 text-white"
                        for="services-accordion"
                    >
                        <p class="self-center">{{ $t("Services") }}</p>
                        <button
                            style="background-color: #ed7d31"
                            type="button"
                            @click="
                                optionalServicesProducts = false;
                                addServiceChildren = false;
                                toggleProductsModal('service');
                            "
                            class="mt-4 sm:mt-0 inline-flex items-start justify-start px-6 py-3 docsHeroColorBlue rounded"
                        >
                            <p
                                class="text-sm font-medium leading-none text-white"
                            >
                                {{ $t("Add Products") }}
                            </p>
                        </button>
                    </label>
                    <div class="tab-content border p-2 overflow-auto">
                        <div
                            v-if="form.productsGroupedBy === 'nothing'"
                            class="mt-7 overflow-x-auto"
                        >
                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th
                                            style="width: 2%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        ></th>
                                        <th
                                            style="width: 4%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Pos") }}
                                        </th>
                                        <th
                                            style="width: 9%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Article Nr.") }}
                                        </th>
                                        <th
                                            style="width: 22%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Name") }}
                                        </th>
                                        <th
                                            style="width: 13%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{
                                                $t("Quantity") +
                                                "/" +
                                                $t("Unit")
                                            }}
                                        </th>
                                        <th
                                            style="width: 10%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Hourly Rate") }}
                                        </th>
                                        <th
                                            style="width: 9%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Discount") }}(%)
                                        </th>
                                        <th
                                            style="width: 8%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Tax") }}(%)
                                        </th>
                                        <th
                                            style="width: 12%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Tax Rate") }}
                                        </th>
                                        <th
                                            style="width: 240px"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Netto Total") }}
                                        </th>
                                        <th
                                            style="width: 2%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                        ></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <template
                                        v-for="(service, index) in services"
                                        :key="'services-' + index"
                                    >
                                        <tr
                                            :tabindex="index"
                                            class="focus:outline-none h-16 border border-gray-100 rounded"
                                        >
                                            <td class="pl-5 align-top py-4">
                                                <input
                                                    class="relative z-10"
                                                    type="checkbox"
                                                    v-model="
                                                        groupToggler['service'][
                                                            service.id
                                                        ]
                                                    "
                                                />
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="">
                                                    <p>{{ index + 1 }}</p>
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div>
                                                    <p>
                                                        {{
                                                            service?.articleNumber
                                                        }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div>
                                                    <input
                                                        v-if="
                                                            service.isProductNameEdit
                                                        "
                                                        class="appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="text"
                                                        name="name"
                                                        v-model="service.name"
                                                        :class="{
                                                            Perror: errors[
                                                                `products.${index}.name`
                                                            ],
                                                        }"
                                                    />
                                                    <span v-else>{{
                                                        service.name
                                                    }}</span>
                                                </div>
                                                <div
                                                    @click="
                                                        toggleAdditionalDescriptionDropdowns(
                                                            service.id,
                                                            index,
                                                            additionalDescriptionToggled[
                                                                'services'
                                                            ][service.id]
                                                                ? 'remove'
                                                                : 'add',
                                                            'services'
                                                        )
                                                    "
                                                    class="flex mt-2 cursor-pointer"
                                                >
                                                    <font-awesome-icon
                                                        :class="`cursor-pointer cross mr-2 self-center text-${
                                                            additionalDescriptionToggled[
                                                                'services'
                                                            ][service.id]
                                                                ? 'red'
                                                                : 'blue'
                                                        }-500`"
                                                        :icon="`fa-solid fa-circle-${
                                                            additionalDescriptionToggled[
                                                                'services'
                                                            ][service.id]
                                                                ? 'minus'
                                                                : 'plus'
                                                        }`"
                                                    />
                                                    <p
                                                        :class="`text-sm text-${
                                                            additionalDescriptionToggled[
                                                                'services'
                                                            ][service.id]
                                                                ? 'red'
                                                                : 'blue'
                                                        }-500`"
                                                    >
                                                        {{
                                                            $t(
                                                                `${
                                                                    additionalDescriptionToggled[
                                                                        "services"
                                                                    ][
                                                                        service
                                                                            .id
                                                                    ]
                                                                        ? "Remove"
                                                                        : "Add"
                                                                } Additional Description`
                                                            )
                                                        }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div style="display: flex">
                                                    <number-input
                                                        @inputEvent="
                                                            serviceQuantityChanged(
                                                                $event,
                                                                index,
                                                                false
                                                            )
                                                        "
                                                        :showPrefix="false"
                                                        min="0"
                                                        @keypress="
                                                            limitToPositiveNumbers
                                                        "
                                                        max="9999"
                                                        class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="
                                                            service.quantity
                                                        "
                                                        name="quantity"
                                                    />
                                                    <p class="self-center ml-2">
                                                        {{
                                                            service?.unit?.name
                                                        }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top">
                                                <div class="flex">
                                                    <number-input
                                                        @inputEvent="
                                                            serviceQuantityChanged(
                                                                $event,
                                                                index,
                                                                false
                                                            )
                                                        "
                                                        class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="
                                                            service.hourlyRate
                                                        "
                                                        name="hourlyRate"
                                                    />
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="flex">
                                                    <input
                                                        @input="
                                                            serviceQuantityChanged(
                                                                $event,
                                                                index,
                                                                false
                                                            )
                                                        "
                                                        class="appearance-none w-24 border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="
                                                            service.discount
                                                        "
                                                        name="discount"
                                                    />
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="flex">
                                                    <input
                                                        @input="
                                                            serviceQuantityChanged(
                                                                $event,
                                                                index,
                                                                false
                                                            )
                                                        "
                                                        class="w-full appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="service.tax"
                                                        name="tax"
                                                    />
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top">
                                                <div class="flex">
                                                    <number-input
                                                        @inputEvent="
                                                            serviceTaxRateChanged(
                                                                $event,
                                                                index,
                                                                false
                                                            )
                                                        "
                                                        class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="
                                                            service.taxRate
                                                        "
                                                        name="taxRate"
                                                        :allowNegative="true"
                                                    />
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top">
                                                <div class="flex" style="width: 125px">
                                                    <number-input
                                                        @inputEvent="
                                                            serviceNettoTotalChanged(
                                                                $event,
                                                                index,
                                                                false
                                                            )
                                                        "
                                                        class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="
                                                            service.nettoTotal
                                                        "
                                                        name="nettoTotal"
                                                        :allowNegative="true"
                                                    />
                                                </div>
                                            </td>

                                            <td class="text-center">
                                                <font-awesome-icon
                                                    @click="
                                                        removeOption(
                                                            index,
                                                            'service'
                                                        )
                                                    "
                                                    class="cursor-pointer cross"
                                                    icon="fa-solid fa-xmark"
                                                />
                                            </td>
                                        </tr>
                                        <tr
                                            v-if="
                                                additionalDescriptionToggled[
                                                    'services'
                                                ][service.id]
                                            "
                                        >
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="pl-6">
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
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
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
                                            class="bg-gray-100"
                                        >
                                            <th
                                                style="width: 2%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            ></th>
                                            <th
                                                style="width: 4%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Pos") }}
                                            </th>
                                            <th
                                                style="width: 9%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Article Nr.") }}
                                            </th>
                                            <th
                                                style="width: 22%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Name") }}
                                            </th>
                                            <th
                                                style="width: 13%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{
                                                    $t("Quantity") +
                                                    "/" +
                                                    $t("Unit")
                                                }}
                                            </th>
                                            <th
                                                style="width: 10%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Single Hourly Rate") }}
                                            </th>
                                            <th
                                                style="width: 9%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Total Rate") }}
                                            </th>
                                            <th
                                                style="width: 8%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            ></th>
                                            <th
                                                style="width: 12%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            ></th>
                                            <th
                                                style="width: 13%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            ></th>
                                            <th
                                                style="width: 2%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            ></th>
                                        </tr>
                                        <tr
                                            v-for="(
                                                child, childIndex
                                            ) in service.children"
                                            :tabindex="childIndex"
                                            class="focus:outline-none h-16 border border-gray-100 rounded"
                                        >
                                            <td class="pl-5"></td>
                                            <td class="pl-5">
                                                <div class="">
                                                    <p>
                                                        {{ index + 1 }}.{{
                                                            childIndex + 1
                                                        }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div>
                                                    <p>
                                                        {{
                                                            child?.articleNumber
                                                        }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div>
                                                    <input
                                                        v-if="
                                                            child.isProductNameEdit
                                                        "
                                                        class="appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="text"
                                                        name="name"
                                                        v-model="child.name"
                                                        :class="{
                                                            Perror: errors[
                                                                `products.${index}.name`
                                                            ],
                                                        }"
                                                    />
                                                    <span v-else>{{
                                                        child.name
                                                    }}</span>
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div style="display: flex">
                                                    <number-input
                                                        @inputEvent="
                                                            serviceQuantityChanged(
                                                                $event,
                                                                index,
                                                                false,
                                                                childIndex
                                                            )
                                                        "
                                                        :showPrefix="false"
                                                        min="0"
                                                        max="9999"
                                                        @keypress="
                                                            limitToPositiveNumbers
                                                        "
                                                        class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="child.quantity"
                                                        name="quantity"
                                                    />
                                                    <p class="self-center ml-2">
                                                        {{ child?.unit?.name }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div class="flex">
                                                    <number-input
                                                        @inputEvent="
                                                            serviceQuantityChanged(
                                                                $event,
                                                                index,
                                                                false,
                                                                childIndex
                                                            )
                                                        "
                                                        class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="
                                                            child.hourlyRate
                                                        "
                                                        name="hourlyRate"
                                                    />
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div class="flex">
                                                    <number-input
                                                        @inputEvent="
                                                            serviceQuantityChanged(
                                                                $event,
                                                                index,
                                                                false,
                                                                childIndex
                                                            )
                                                        "
                                                        :isReadonly="true"
                                                        class="appearance-none w-24 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="
                                                            child.totalRate
                                                        "
                                                        name="totalRate"
                                                    />
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <font-awesome-icon
                                                    @click="
                                                        removeOption(
                                                            index,
                                                            'service'
                                                        )
                                                    "
                                                    class="cursor-pointer cross"
                                                    icon="fa-solid fa-xmark"
                                                />
                                            </td>
                                            <td class="pl-5"></td>
                                            <td class="pl-5"></td>

                                            <td class="text-center"></td>
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
                                    justify-content: end;
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
                                        :key="index"
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
                            <div class="flex justify-between mt-5">
                                <div class="flex">
                                    <label
                                        for="optional-ams-products"
                                        class="self-center"
                                        >{{ $t("Optional Products") }}</label
                                    >
                                    <input
                                        class="ml-2"
                                        type="checkbox"
                                        @input="discardOptionalServiceProducts"
                                        v-model="optionalServicesProductsToggle"
                                        id="optional-ams-products"
                                    />
                                </div>
                                <button
                                    v-if="optionalServicesProductsToggle"
                                    type="button"
                                    style="background-color: #ed7d31"
                                    @click="
                                        optionalServicesProducts = true;
                                        toggleProductsModal('service');
                                    "
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
                                v-if="optionalServicesProductsToggle"
                                class="mt-7 overflow-x-auto"
                            >
                                <table class="w-full">
                                    <thead>
                                        <tr>
                                            <th
                                                style="width: 4%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Pos") }}
                                            </th>
                                            <th
                                                style="width: 9%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Article Nr.") }}
                                            </th>
                                            <th
                                                style="width: 22%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Name") }}
                                            </th>
                                            <th
                                                style="width: 13%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{
                                                    $t("Quantity") +
                                                    "/" +
                                                    $t("Unit")
                                                }}
                                            </th>
                                            <th
                                                style="width: 10%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Hourly Rate") }}
                                            </th>
                                            <th
                                                style="width: 9%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Discount") }}(%)
                                            </th>
                                            <th
                                                style="width: 8%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Tax") }}(%)
                                            </th>
                                            <th
                                                style="width: 12%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Tax Rate") }}
                                            </th>
                                            <th
                                                style="width: 240px"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Netto Total") }}
                                            </th>
                                            <th
                                                style="width: 2%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            ></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr
                                            v-for="(
                                                service, index
                                            ) in optionalServices"
                                            :key="'services-' + index"
                                            :tabindex="index"
                                            class="focus:outline-none h-16 border border-gray-100 rounded"
                                        >
                                            <td class="pl-5">
                                                <div class="">
                                                    <p>{{ index + 1 }}</p>
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div>
                                                    <p>
                                                        {{
                                                            service?.articleNumber
                                                        }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div>
                                                    <input
                                                        v-if="
                                                            service.isProductNameEdit
                                                        "
                                                        class="appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="text"
                                                        name="name"
                                                        v-model="service.name"
                                                        :class="{
                                                            Perror: errors[
                                                                `products.${index}.name`
                                                            ],
                                                        }"
                                                    />
                                                    <span v-else>{{
                                                        service.name
                                                    }}</span>
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div style="display: flex">
                                                    <number-input
                                                        @inputEvent="
                                                            serviceQuantityChanged(
                                                                $event,
                                                                index,
                                                                true
                                                            )
                                                        "
                                                        :showPrefix="false"
                                                        min="0"
                                                        max="9999"
                                                        @keypress="
                                                            limitToPositiveNumbers
                                                        "
                                                        class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="
                                                            service.quantity
                                                        "
                                                        name="quantity"
                                                    />
                                                    <p class="self-center ml-2">
                                                        {{
                                                            service?.unit?.name
                                                        }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div class="flex">
                                                    <number-input
                                                        @inputEvent="
                                                            serviceQuantityChanged(
                                                                $event,
                                                                index,
                                                                true
                                                            )
                                                        "
                                                        class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="
                                                            service.hourlyRate
                                                        "
                                                        name="hourlyRate"
                                                    />
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div class="flex">
                                                    <input
                                                        @input="
                                                            serviceQuantityChanged(
                                                                $event,
                                                                index,
                                                                true
                                                            )
                                                        "
                                                        class="appearance-none w-24 border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="
                                                            service.discount
                                                        "
                                                        name="discount"
                                                    />
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div class="flex">
                                                    <input
                                                        @input="
                                                            serviceQuantityChanged(
                                                                $event,
                                                                index,
                                                                true
                                                            )
                                                        "
                                                        class="w-full appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="service.tax"
                                                        name="tax"
                                                    />
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div class="flex">
                                                    <number-input
                                                        @inputEvent="
                                                            serviceTaxRateChanged(
                                                                $event,
                                                                index,
                                                                true
                                                            )
                                                        "
                                                        class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="
                                                            service.taxRate
                                                        "
                                                        name="taxRate"
                                                        :allowNegative="true"
                                                    />
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div class="flex" style="width: 125px">
                                                    <number-input
                                                        @inputEvent="
                                                            serviceNettoTotalChanged(
                                                                $event,
                                                                index,
                                                                true
                                                            )
                                                        "
                                                        class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="
                                                            service.nettoTotal
                                                        "
                                                        name="nettoTotal"
                                                        :allowNegative="true"
                                                    />
                                                </div>
                                            </td>

                                            <td class="text-center">
                                                <font-awesome-icon
                                                    @click="
                                                        removeOption(
                                                            index,
                                                            'optionalService'
                                                        )
                                                    "
                                                    class="cursor-pointer cross"
                                                    icon="fa-solid fa-xmark"
                                                />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <br />
                            </div>
                        </div>
                        <div
                            v-else-if="form.productsGroupedBy === 'category'"
                            class="mt-7 overflow-x-auto"
                        >
                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th
                                            style="width: 4%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Pos") }}
                                        </th>
                                        <th
                                            style="width: 9%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Article Nr.") }}
                                        </th>
                                        <th
                                            style="width: 22%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Name") }}
                                        </th>
                                        <th
                                            style="width: 9%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{
                                                $t("Quantity") +
                                                "/" +
                                                $t("Unit")
                                            }}
                                        </th>
                                        <th
                                            style="width: 10%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Hourly Rate") }}
                                        </th>
                                        <th
                                            style="width: 13%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Discount") }}(%)
                                        </th>
                                        <th
                                            style="width: 8%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Tax") }}(%)
                                        </th>
                                        <th
                                            style="width: 12%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Tax Rate") }}
                                        </th>
                                        <th
                                            style="width: 240px"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Netto Total") }}
                                        </th>
                                        <th
                                            style="width: 2%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                        ></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <template
                                        v-for="(category, index) in categories"
                                        :key="'category-' + index"
                                        :tabindex="index"
                                    >
                                        <tr
                                            class="focus:outline-none h-16 border border-gray-100 rounded"
                                        >
                                            <td class="pl-5">
                                                <div class="">
                                                    <p>{{ index + 1 }}</p>
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <p class="mb-6"></p>
                                                <p
                                                    v-for="product in category.products"
                                                    :key="
                                                        'product-' + product.id
                                                    "
                                                >
                                                    {{ product.articleNumber }}
                                                </p>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div>
                                                    <p class="font-bold">
                                                        {{ category?.name }}
                                                    </p>
                                                    <li
                                                        class="ml-2"
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
                                                            class="appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="text"
                                                            name="name"
                                                            v-model="
                                                                product.name
                                                            "
                                                            :class="{
                                                                Perror: errors[
                                                                    `products.${index}.name`
                                                                ],
                                                            }"
                                                        />
                                                        <span v-else>{{
                                                            product.name
                                                        }}</span>
                                                    </li>
                                                    <div
                                                        @click="
                                                            toggleAdditionalDescriptionDropdowns(
                                                                category.id,
                                                                index,
                                                                additionalDescriptionToggled[
                                                                    'categories'
                                                                ][category.id]
                                                                    ? 'remove'
                                                                    : 'add',
                                                                'categories'
                                                            )
                                                        "
                                                        class="flex mt-2 cursor-pointer"
                                                    >
                                                        <font-awesome-icon
                                                            :class="`cursor-pointer cross mr-2 self-center text-${
                                                                additionalDescriptionToggled[
                                                                    'categories'
                                                                ][category.id]
                                                                    ? 'red'
                                                                    : 'blue'
                                                            }-500`"
                                                            :icon="`fa-solid fa-circle-${
                                                                additionalDescriptionToggled[
                                                                    'categories'
                                                                ][category.id]
                                                                    ? 'minus'
                                                                    : 'plus'
                                                            }`"
                                                        />
                                                        <p
                                                            :class="`text-sm text-${
                                                                additionalDescriptionToggled[
                                                                    'categories'
                                                                ][category.id]
                                                                    ? 'red'
                                                                    : 'blue'
                                                            }-500`"
                                                        >
                                                            {{
                                                                $t(
                                                                    `${
                                                                        additionalDescriptionToggled[
                                                                            "categories"
                                                                        ][
                                                                            category
                                                                                .id
                                                                        ]
                                                                            ? "Remove"
                                                                            : "Add"
                                                                    } Additional Description`
                                                                )
                                                            }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="flex">
                                                    <number-input
                                                        @inputEvent="
                                                            serviceQuantityChangedCategory(
                                                                $event,
                                                                index,
                                                                false
                                                            )
                                                        "
                                                        :showPrefix="false"
                                                        class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        :min="0"
                                                        @keypress="
                                                            limitToPositiveNumbers
                                                        "
                                                        v-model="
                                                            category.quantity
                                                        "
                                                        name="quantity"
                                                    />
                                                    <p class="self-center ml-2">
                                                        {{
                                                            category?.unit?.name
                                                        }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="flex">
                                                    <number-input
                                                        @inputEvent="
                                                            serviceQuantityChangedCategory(
                                                                $event,
                                                                index,
                                                                false
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
                                            <td class="pl-5 align-top py-2">
                                                <div class="flex">
                                                    <input
                                                        @input="
                                                            serviceQuantityChangedCategory(
                                                                $event,
                                                                index,
                                                                false
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
                                            <td class="pl-5 align-top py-2">
                                                <div class="flex">
                                                    <input
                                                        @input="
                                                            serviceQuantityChangedCategory(
                                                                $event,
                                                                index,
                                                                false
                                                            )
                                                        "
                                                        class="w-full appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="category.tax"
                                                        name="tax"
                                                    />
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="flex">
                                                    <number-input
                                                        @inputEvent="
                                                            serviceTaxRateChangedCategory(
                                                                $event,
                                                                index,
                                                                false
                                                            )
                                                        "
                                                        class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="
                                                            category.taxRate
                                                        "
                                                        name="taxRate"
                                                        :allowNegative="true"
                                                    />
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="flex" style="width: 125px">
                                                    <number-input
                                                        @inputEvent="
                                                            serviceNettoTotalChangedCategory(
                                                                $event,
                                                                index,
                                                                false
                                                            )
                                                        "
                                                        class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="
                                                            category.nettoTotal
                                                        "
                                                        name="nettoTotal"
                                                        :allowNegative="true"
                                                    />
                                                </div>
                                            </td>

                                            <td class="text-center">
                                                <font-awesome-icon
                                                    @click="
                                                        removeOption(
                                                            index,
                                                            'categories'
                                                        )
                                                    "
                                                    class="cursor-pointer cross"
                                                    icon="fa-solid fa-xmark"
                                                />
                                            </td>
                                        </tr>
                                        <tr
                                            v-if="
                                                additionalDescriptionToggled[
                                                    'categories'
                                                ][category.id]
                                            "
                                        >
                                            <td></td>
                                            <td></td>
                                            <td class="pl-6">
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
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
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
                                    justify-content: end;
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
                                        :key="index"
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
                            <div class="flex justify-between mt-5">
                                <div class="flex">
                                    <label
                                        for="optional-ams-products"
                                        class="self-center"
                                        >{{ $t("Optional Products") }}</label
                                    >
                                    <input
                                        class="ml-2"
                                        type="checkbox"
                                        @input="discardOptionalServiceProducts"
                                        v-model="optionalServicesProductsToggle"
                                        id="optional-ams-products"
                                    />
                                </div>
                                <button
                                    v-if="optionalServicesProductsToggle"
                                    type="button"
                                    style="background-color: #ed7d31"
                                    @click="
                                        optionalServicesProducts = true;
                                        toggleProductsModal('service');
                                    "
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
                                v-if="optionalServicesProductsToggle"
                                class="mt-7 overflow-x-auto"
                            >
                                <table class="w-full">
                                    <thead>
                                        <tr>
                                            <th
                                                style="width: 4%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Pos") }}
                                            </th>
                                            <th
                                                style="width: 9%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Article Nr.") }}
                                            </th>
                                            <th
                                                style="width: 22%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Name") }}
                                            </th>
                                            <th
                                                style="width: 9%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{
                                                    $t("Quantity") +
                                                    "/" +
                                                    $t("Unit")
                                                }}
                                            </th>
                                            <th
                                                style="width: 10%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Hourly Rate") }}
                                            </th>
                                            <th
                                                style="width: 13%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Discount") }}(%)
                                            </th>
                                            <th
                                                style="width: 8%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Tax") }}(%)
                                            </th>
                                            <th
                                                style="width: 12%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Tax Rate") }}
                                            </th>
                                            <th
                                                style="width: 240px"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Netto Total") }}
                                            </th>
                                            <th
                                                style="width: 2%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            ></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr
                                            v-for="(
                                                category, index
                                            ) in optionalCategories"
                                            :key="'category-' + index"
                                            :tabindex="index"
                                            class="focus:outline-none h-16 border border-gray-100 rounded"
                                        >
                                            <td class="pl-5">
                                                <div class="">
                                                    <p>{{ index + 1 }}</p>
                                                </div>
                                            </td>
                                            <td class="pl-5 pt-2">
                                                <p class="mb-6"></p>
                                                <p
                                                    v-for="product in category.products"
                                                    :key="
                                                        'product-' + product.id
                                                    "
                                                >
                                                    {{ product.articleNumber }}
                                                </p>
                                            </td>
                                            <td class="pl-5 pt-2">
                                                <div>
                                                    <p class="font-bold">
                                                        {{ category?.name }}
                                                    </p>
                                                    <li
                                                        class="ml-2"
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
                                                            class="appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="text"
                                                            name="name"
                                                            v-model="
                                                                product.name
                                                            "
                                                            :class="{
                                                                Perror: errors[
                                                                    `products.${index}.name`
                                                                ],
                                                            }"
                                                        />
                                                        <span v-else>{{
                                                            product.name
                                                        }}</span>
                                                    </li>
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div class="flex">
                                                    <number-input
                                                        @inputEvent="
                                                            serviceQuantityChangedCategory(
                                                                $event,
                                                                index,
                                                                true
                                                            )
                                                        "
                                                        class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="
                                                            category.quantity
                                                        "
                                                        :showPrefix="false"
                                                        :min="0"
                                                        @keypress="
                                                            limitToPositiveNumbers
                                                        "
                                                        name="quantity"
                                                    />
                                                    <p class="self-center ml-2">
                                                        {{
                                                            category?.unit?.name
                                                        }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div class="flex">
                                                    <number-input
                                                        @inputEvent="
                                                            serviceQuantityChangedCategory(
                                                                $event,
                                                                index,
                                                                true
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
                                            <td class="pl-5">
                                                <div class="flex">
                                                    <input
                                                        @input="
                                                            serviceQuantityChangedCategory(
                                                                $event,
                                                                index,
                                                                true
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
                                            <td class="pl-5">
                                                <div class="flex">
                                                    <input
                                                        @input="
                                                            serviceQuantityChangedCategory(
                                                                $event,
                                                                index,
                                                                true
                                                            )
                                                        "
                                                        class="w-full appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="category.tax"
                                                        name="tax"
                                                    />
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div class="flex">
                                                    <number-input
                                                        @inputEvent="
                                                            serviceTaxRateChangedCategory(
                                                                $event,
                                                                index,
                                                                true
                                                            )
                                                        "
                                                        class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="
                                                            category.taxRate
                                                        "
                                                        name="taxRate"
                                                        :allowNegative="true"
                                                    />
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div class="flex" style="width: 125px">
                                                    <number-input
                                                        @inputEvent="
                                                            serviceNettoTotalChangedCategory(
                                                                $event,
                                                                index,
                                                                true
                                                            )
                                                        "
                                                        class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="
                                                            category.nettoTotal
                                                        "
                                                        name="nettoTotal"
                                                        :allowNegative="true"
                                                    />
                                                </div>
                                            </td>

                                            <td class="text-center">
                                                <font-awesome-icon
                                                    @click="
                                                        removeOption(
                                                            index,
                                                            'categories'
                                                        )
                                                    "
                                                    class="cursor-pointer cross"
                                                    icon="fa-solid fa-xmark"
                                                />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <br />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="applicationManagementServiceToggle">
            <div class="tabs">
                <div class="relative tab">
                    <input
                        class="tab-checkbox"
                        type="checkbox"
                        id="ams-accordion"
                        :checked="true"
                    />
                    <label
                        style="background-color: #2957a4"
                        class="rounded tab-label styles-configurator-tab-label flex justify-between p-2 text-white"
                        for="ams-accordion"
                    >
                        <p class="self-center">
                            {{ $t("Application Management Service") }}
                        </p>
                        <div
                            class="flex"
                            style="
                                align-self: center;
                                justify-self: center;
                                font-weight: normal;
                                color: black;
                            "
                        >
                            <label for="" class="mr-2 self-center text-white"
                                >{{ $t("Payment Period") }}:</label
                            >
                            <select-input
                                v-if="show"
                                @update:model-value="
                                    updatePeriodOfAllProducts('ams')
                                "
                                v-model="globalPeriodAMS"
                                :key="globalPeriodAMS"
                            >
                                <option
                                    v-for="period in periods.data"
                                    :key="'period-' + period.id"
                                    :value="period.id"
                                >
                                    {{ period.name }}
                                </option>
                            </select-input>
                        </div>
                        <button
                            type="button"
                            style="background-color: #ed7d31"
                            @click="
                                optionalAmsProducts = false;
                                toggleProductsModal('ams');
                            "
                            class="mt-4 sm:mt-0 inline-flex items-start justify-start px-6 py-3 docsHeroColorBlue rounded"
                        >
                            <p
                                class="text-sm font-medium leading-none text-white"
                            >
                                {{ $t("Add Products") }}
                            </p>
                        </button>
                    </label>
                    <div class="tab-content border p-2 overflow-auto">
                        <div class="mt-7 overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th
                                            style="width: 4%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("POS") }}
                                        </th>
                                        <th
                                            style="width: 9%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Article Nr.") }}
                                        </th>
                                        <th
                                            style="width: 22%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Name") }}
                                        </th>
                                        <th
                                            style="width: 9%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Software") }}
                                        </th>
                                        <th
                                            style="width: 10%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Quantity") }}/{{
                                                $t("Unit")
                                            }}
                                        </th>
                                        <th
                                            style="width: 13%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Hourly Rate") }}
                                        </th>
                                        <th
                                            style="width: 8%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Discount") }}
                                        </th>
                                        <th
                                            style="width: 12%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Period") }}
                                        </th>
                                        <th
                                            style="width: 240px"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Netto Total") }}
                                        </th>
                                        <th
                                            style="width: 2%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                        ></th>
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
                                            <td class="pl-5 align-top py-2">
                                                <div class="">
                                                    {{ index + 1 }}
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="">
                                                    {{ product.articleNumber }}
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="">
                                                    <div class="table-align">
                                                        <input
                                                            v-if="
                                                                product.isProductNameEdit
                                                            "
                                                            class="appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="text"
                                                            name="name"
                                                            v-model="
                                                                product.name
                                                            "
                                                            :class="{
                                                                Perror: errors[
                                                                    `products.${index}.name`
                                                                ],
                                                            }"
                                                        />
                                                        <span v-else>{{
                                                            product.name
                                                        }}</span>
                                                    </div>
                                                    <!--                                                    <input-->
                                                    <!--                                                        :title="product.name"-->
                                                    <!--                                                        class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:border-purple-500"-->
                                                    <!--                                                        type="text"-->
                                                    <!--                                                        :class="{-->
                                                    <!--                                                            Perror: errors[-->
                                                    <!--                                                                `products.${index}.name`-->
                                                    <!--                                                            ],-->
                                                    <!--                                                        }"-->
                                                    <!--                                                        readonly-->
                                                    <!--                                                        v-model="product.name"-->
                                                    <!--                                                    />-->
                                                    <div
                                                        @click="
                                                            toggleAdditionalDescriptionDropdowns(
                                                                product.id,
                                                                index,
                                                                additionalDescriptionToggled[
                                                                    'ams'
                                                                ][product.id]
                                                                    ? 'remove'
                                                                    : 'add',
                                                                'ams'
                                                            )
                                                        "
                                                        class="flex mt-2 cursor-pointer"
                                                    >
                                                        <font-awesome-icon
                                                            :class="`cursor-pointer cross mr-2 self-center text-${
                                                                additionalDescriptionToggled[
                                                                    'ams'
                                                                ][product.id]
                                                                    ? 'red'
                                                                    : 'blue'
                                                            }-500`"
                                                            :icon="`fa-solid fa-circle-${
                                                                additionalDescriptionToggled[
                                                                    'ams'
                                                                ][product.id]
                                                                    ? 'minus'
                                                                    : 'plus'
                                                            }`"
                                                        />
                                                        <p
                                                            :class="`text-sm text-${
                                                                additionalDescriptionToggled[
                                                                    'ams'
                                                                ][product.id]
                                                                    ? 'red'
                                                                    : 'blue'
                                                            }-500`"
                                                        >
                                                            {{
                                                                $t(
                                                                    `${
                                                                        additionalDescriptionToggled[
                                                                            "ams"
                                                                        ][
                                                                            product
                                                                                .id
                                                                        ]
                                                                            ? "Remove"
                                                                            : "Add"
                                                                    } Additional Description`
                                                                )
                                                            }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div
                                                    @click="
                                                        toggleSLADropdowns(
                                                            product.id,
                                                            index,
                                                            slaToggled[
                                                                product.id
                                                            ]
                                                                ? 'remove'
                                                                : 'add'
                                                        )
                                                    "
                                                    class="flex mt-2 cursor-pointer"
                                                >
                                                    <font-awesome-icon
                                                        :class="`cursor-pointer cross mr-2 self-center text-${
                                                            slaToggled[
                                                                product.id
                                                            ]
                                                                ? 'red'
                                                                : 'blue'
                                                        }-500`"
                                                        :icon="`fa-solid fa-circle-${
                                                            slaToggled[
                                                                product.id
                                                            ]
                                                                ? 'minus'
                                                                : 'plus'
                                                        }`"
                                                    />
                                                    <p
                                                        :class="`text-sm text-${
                                                            slaToggled[
                                                                product.id
                                                            ]
                                                                ? 'red'
                                                                : 'blue'
                                                        }-500`"
                                                    >
                                                        {{
                                                            $t(
                                                                `${
                                                                    slaToggled[
                                                                        product
                                                                            .id
                                                                    ]
                                                                        ? "Remove"
                                                                        : "Add"
                                                                } SLA`
                                                            )
                                                        }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="">
                                                    {{
                                                        product.productSoftware
                                                            ?.name
                                                    }}
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="flex">
                                                    <number-input
                                                        @inputEvent="
                                                            changedQuantityAMS(
                                                                index,
                                                                $event,
                                                                false
                                                            )
                                                        "
                                                        class="input-width appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="
                                                            product.quantity
                                                        "
                                                        :showPrefix="false"
                                                        name="quantity"
                                                        :min="0"
                                                        @keypress="
                                                            limitToPositiveNumbers
                                                        "
                                                    />
                                                    <p class="self-center ml-2">
                                                        {{
                                                            product?.unit?.name
                                                        }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td
                                                class="pl-5 text-center align-top"
                                            >
                                                <div class="">
                                                    <number-input
                                                        @inputEvent="
                                                            changedQuantityAMS(
                                                                index,
                                                                $event,
                                                                false
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
                                            <td
                                                class="pl-5 text-center align-top py-2"
                                            >
                                                <div class="">
                                                    <input
                                                        @input="
                                                            changedQuantityAMS(
                                                                index,
                                                                $event,
                                                                false
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
                                            <td
                                                class="pl-5 text-center align-top py-2"
                                            >
                                                <div class="">
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
                                                            :value="period.id"
                                                        >
                                                            {{ period.name }}
                                                        </option>
                                                    </select-input>
                                                </div>
                                            </td>
                                            <td
                                                class="pl-5 text-center align-top"
                                            >
                                                <div class="" style="width: 125px">
                                                    <number-input
                                                        @inputEvent="
                                                            amsNettoTotalChanged(
                                                                event,
                                                                index,
                                                                false
                                                            )
                                                        "
                                                        class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="
                                                            product.nettoTotal
                                                        "
                                                        name="nettoTotal"
                                                        :allowNegative="true"
                                                    />
                                                </div>
                                            </td>

                                            <td
                                                class="text-center align-top py-2"
                                            >
                                                <font-awesome-icon
                                                    @click="
                                                        removeOption(
                                                            index,
                                                            'ams'
                                                        )
                                                    "
                                                    class="cursor-pointer cross"
                                                    icon="fa-solid fa-xmark"
                                                />
                                            </td>
                                        </tr>
                                        <tr
                                            v-if="
                                                additionalDescriptionToggled[
                                                    'ams'
                                                ][product.id]
                                            "
                                        >
                                            <td></td>
                                            <td></td>
                                            <td class="pl-6">
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
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr v-if="slaToggled[product.id]">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr v-if="slaToggled[product.id]">
                                            <td></td>
                                            <td></td>
                                            <td>
                                                <MultiSelectInput
                                                    style="
                                                        overflow: visible !important;
                                                    "
                                                    v-model="
                                                        product.slaServiceTimeId
                                                    "
                                                    class="pb-1 pr-6 w-full"
                                                    :textLabel="
                                                        $t('SLA Service Time')
                                                    "
                                                    :error="
                                                        errors['slaServiceTime']
                                                    "
                                                    :key="
                                                        product.slaServiceTimeId
                                                    "
                                                    :options="slaServiceTimes"
                                                    :multiple="false"
                                                    label="name"
                                                    trackBy="id"
                                                ></MultiSelectInput>
                                            </td>
                                            <td>
                                                <div
                                                    style="
                                                        overflow: visible !important;
                                                    "
                                                    v-if="
                                                        product.slaServiceTimeId
                                                    "
                                                    class="p-3 text-blue-500"
                                                >
                                                    <p>
                                                        {{
                                                            product
                                                                .slaServiceTimeId
                                                                ?.name
                                                        }}
                                                        on
                                                        {{
                                                            daysString(
                                                                product
                                                                    .slaServiceTimeId
                                                                    ?.days
                                                            )
                                                        }}
                                                        from
                                                        {{
                                                            product
                                                                .slaServiceTimeId
                                                                ?.from
                                                        }}
                                                        to
                                                        {{
                                                            product
                                                                .slaServiceTimeId
                                                                ?.to
                                                        }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr v-if="slaToggled[product.id]">
                                            <td></td>
                                            <td></td>
                                            <td>
                                                <MultiSelectInput
                                                    style="
                                                        overflow: visible !important;
                                                    "
                                                    v-model="product.slaLevelId"
                                                    class="pb-1 pr-6 w-full"
                                                    :textLabel="$t('SLA Level')"
                                                    :error="
                                                        errors['slaLevelId']
                                                    "
                                                    :key="product.slaLevelId"
                                                    :options="
                                                        slaLevels?.data ?? []
                                                    "
                                                    :multiple="false"
                                                    label="name"
                                                    trackBy="id"
                                                    moduleName="slaLevel"
                                                ></MultiSelectInput>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                            <br />
                        </div>
                        <div
                            v-if="Object.keys(ams).length > 0"
                            style="
                                flex-direction: column;
                                align-items: end;
                                justify-content: end;
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
                                    :key="index"
                                >
                                    <h1>{{ key }}% {{ $t("Tax") }}:</h1>
                                    <h1
                                        :class="[
                                            globalLanguage === 'de'
                                                ? 'text-end'
                                                : '',
                                        ]"
                                    >
                                        {{ $formatter(value, globalLanguage) }}
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
                        <div class="flex justify-between mt-5">
                            <div class="flex">
                                <label
                                    for="optional-ams-products"
                                    class="self-center"
                                    >{{ $t("Optional Products") }}</label
                                >
                                <input
                                    class="ml-2"
                                    type="checkbox"
                                    @input="discardOptionalAMSProducts"
                                    v-model="optionalAMSProductsToggle"
                                    id="optional-ams-products"
                                />
                            </div>
                            <button
                                v-if="optionalAMSProductsToggle"
                                type="button"
                                style="background-color: #ed7d31"
                                @click="
                                    optionalAmsProducts = true;
                                    toggleProductsModal('ams');
                                "
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
                            v-if="optionalAMSProductsToggle"
                            class="mt-7 overflow-x-auto"
                        >
                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th
                                            style="width: 4%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("POS") }}
                                        </th>
                                        <th
                                            style="width: 9%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Article Nr.") }}
                                        </th>
                                        <th
                                            style="width: 22%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Name") }}
                                        </th>
                                        <th
                                            style="width: 9%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Software") }}
                                        </th>
                                        <th
                                            style="width: 10%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Quantity") }}/{{
                                                $t("Unit")
                                            }}
                                        </th>
                                        <th
                                            style="width: 13%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Hourly Rate") }}
                                        </th>
                                        <th
                                            style="width: 8%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Discount") }}
                                        </th>
                                        <th
                                            style="width: 12%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Period") }}
                                        </th>
                                        <th
                                            style="width: 240px"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Netto Total") }}
                                        </th>
                                        <th
                                            style="width: 2%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                        ></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <template
                                        v-for="(product, index) in optionalAms"
                                        :key="index"
                                        :tabindex="index"
                                    >
                                        <tr
                                            class="focus:outline-none h-16 border border-gray-100 rounded"
                                        >
                                            <td class="pl-5 align-top py-2">
                                                <div class="">
                                                    {{ index + 1 }}
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="">
                                                    {{ product.articleNumber }}
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="table-align">
                                                    <input
                                                        v-if="
                                                            product.isProductNameEdit
                                                        "
                                                        class="appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="text"
                                                        name="name"
                                                        v-model="product.name"
                                                        :class="{
                                                            Perror: errors[
                                                                `products.${index}.name`
                                                            ],
                                                        }"
                                                    />
                                                    <span v-else>{{
                                                        product.name
                                                    }}</span>
                                                </div>
                                                <!--                                                <div class="">-->
                                                <!--                                                    <input-->
                                                <!--                                                        :title="product.name"-->
                                                <!--                                                        class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:border-purple-500"-->
                                                <!--                                                        type="text"-->
                                                <!--                                                        :class="{-->
                                                <!--                                                            Perror: errors[-->
                                                <!--                                                                `products.${index}.name`-->
                                                <!--                                                            ],-->
                                                <!--                                                        }"-->
                                                <!--                                                        readonly-->
                                                <!--                                                        v-model="product.name"-->
                                                <!--                                                    />-->
                                                <!--                                                </div>-->
                                                <div
                                                    @click="
                                                        toggleSLADropdowns(
                                                            product.id,
                                                            index,
                                                            slaToggled[
                                                                product.id
                                                            ]
                                                                ? 'remove'
                                                                : 'add'
                                                        )
                                                    "
                                                    class="flex mt-2 cursor-pointer"
                                                >
                                                    <font-awesome-icon
                                                        :class="`cursor-pointer cross mr-2 self-center text-${
                                                            slaToggled[
                                                                product.id
                                                            ]
                                                                ? 'red'
                                                                : 'blue'
                                                        }-500`"
                                                        :icon="`fa-solid fa-circle-${
                                                            slaToggled[
                                                                product.id
                                                            ]
                                                                ? 'minus'
                                                                : 'plus'
                                                        }`"
                                                    />
                                                    <p
                                                        :class="`text-sm text-${
                                                            slaToggled[
                                                                product.id
                                                            ]
                                                                ? 'red'
                                                                : 'blue'
                                                        }-500`"
                                                    >
                                                        {{
                                                            $t(
                                                                `${
                                                                    slaToggled[
                                                                        product
                                                                            .id
                                                                    ]
                                                                        ? "Remove"
                                                                        : "Add"
                                                                } SLA`
                                                            )
                                                        }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="">
                                                    {{
                                                        product.productSoftware
                                                            ?.name
                                                    }}
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="flex">
                                                    <number-input
                                                        @inputEvent="
                                                            changedQuantityAMS(
                                                                index,
                                                                $event,
                                                                true
                                                            )
                                                        "
                                                        class="input-width appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="
                                                            product.quantity
                                                        "
                                                        :showPrefix="false"
                                                        :min="0"
                                                        @keypress="
                                                            limitToPositiveNumbers
                                                        "
                                                        name="quantity"
                                                    />
                                                    <p class="self-center ml-2">
                                                        {{
                                                            product?.unit?.name
                                                        }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td
                                                class="pl-5 text-center align-top"
                                            >
                                                <div class="">
                                                    <number-input
                                                        @inputEvent="
                                                            changedQuantityAMS(
                                                                index,
                                                                $event,
                                                                true
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
                                            <td
                                                class="pl-5 text-center align-top py-2"
                                            >
                                                <div class="">
                                                    <input
                                                        @input="
                                                            changedQuantityAMS(
                                                                index,
                                                                $event,
                                                                true
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
                                            <td
                                                class="pl-5 text-center align-top py-2"
                                            >
                                                <div class="">
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
                                                            :value="period.id"
                                                        >
                                                            {{ period.name }}
                                                        </option>
                                                    </select-input>
                                                </div>
                                            </td>
                                            <td
                                                class="pl-5 text-center align-top"
                                            >
                                                <div class="" style="width: 125px">
                                                    <number-input
                                                        @inputEvent="
                                                            amsNettoTotalChanged(
                                                                event,
                                                                index,
                                                                true
                                                            )
                                                        "
                                                        class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="
                                                            product.nettoTotal
                                                        "
                                                        name="nettoTotal"
                                                        :allowNegative="true"
                                                    />
                                                </div>
                                            </td>

                                            <td
                                                class="text-center align-top py-2"
                                            >
                                                <font-awesome-icon
                                                    @click="
                                                        removeOption(
                                                            index,
                                                            'optionalAms'
                                                        )
                                                    "
                                                    class="cursor-pointer cross"
                                                    icon="fa-solid fa-xmark"
                                                />
                                            </td>
                                        </tr>
                                        <tr v-if="slaToggled[product.id]">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr v-if="slaToggled[product.id]">
                                            <td></td>
                                            <td></td>
                                            <td>
                                                <MultiSelectInput
                                                    style="
                                                        overflow: visible !important;
                                                    "
                                                    v-model="
                                                        product.slaServiceTimeId
                                                    "
                                                    class="pb-1 pr-6 w-full"
                                                    :textLabel="
                                                        $t('SLA Service Time')
                                                    "
                                                    :error="
                                                        errors['slaServiceTime']
                                                    "
                                                    :key="
                                                        product.slaServiceTimeId
                                                    "
                                                    :options="slaServiceTimes"
                                                    :multiple="false"
                                                    label="name"
                                                    trackBy="id"
                                                ></MultiSelectInput>
                                            </td>
                                            <td>
                                                <div
                                                    style="
                                                        overflow: visible !important;
                                                    "
                                                    v-if="
                                                        product.slaServiceTimeId
                                                    "
                                                    class="p-3 text-blue-500"
                                                >
                                                    <p>
                                                        {{
                                                            product
                                                                .slaServiceTimeId
                                                                ?.name
                                                        }}
                                                        on
                                                        {{
                                                            daysString(
                                                                product
                                                                    .slaServiceTimeId
                                                                    ?.days
                                                            )
                                                        }}
                                                        from
                                                        {{
                                                            product
                                                                .slaServiceTimeId
                                                                ?.from
                                                        }}
                                                        to
                                                        {{
                                                            product
                                                                .slaServiceTimeId
                                                                ?.to
                                                        }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr v-if="slaToggled[product.id]">
                                            <td></td>
                                            <td></td>
                                            <td>
                                                <MultiSelectInput
                                                    style="
                                                        overflow: visible !important;
                                                    "
                                                    v-model="product.slaLevelId"
                                                    class="pb-1 pr-6 w-full"
                                                    :textLabel="$t('SLA Level')"
                                                    :error="
                                                        errors['slaLevelId']
                                                    "
                                                    :key="product.slaLevelId"
                                                    :options="
                                                        slaLevels?.data ?? []
                                                    "
                                                    :multiple="false"
                                                    label="name"
                                                    trackBy="id"
                                                    moduleName="slaLevel"
                                                ></MultiSelectInput>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                            <br />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="hostingToggle">
            <div class="tabs">
                <div class="relative tab">
                    <input
                        class="tab-checkbox"
                        type="checkbox"
                        id="hosting-accordion"
                        :checked="true"
                    />
                    <label
                        style="background-color: #2957a4"
                        class="rounded tab-label styles-configurator-tab-label flex justify-between p-2 text-white"
                        for="hosting-accordion"
                    >
                        <p class="self-center">
                            {{ $t("Hosting") }}
                        </p>
                        <div
                            class="flex"
                            style="
                                align-self: center;
                                justify-self: center;
                                font-weight: normal;
                                color: black;
                            "
                        >
                            <label for="" class="mr-2 self-center text-white"
                                >{{ $t("Payment Period") }}:</label
                            >
                            <select-input
                                @update:model-value="
                                    updatePeriodOfAllProducts('hosting')
                                "
                                v-if="show"
                                :key="globalPeriodHosting"
                                v-model="globalPeriodHosting"
                            >
                                <option
                                    v-for="period in periods.data"
                                    :key="'period-' + period.id"
                                    :value="period.id"
                                >
                                    {{ period.name }}
                                </option>
                            </select-input>
                        </div>
                        <button
                            type="button"
                            @click="
                                optionalHostingProducts = false;
                                toggleProductsModal('hosting');
                            "
                            style="background-color: #ed7d31"
                            class="mt-4 sm:mt-0 inline-flex items-start justify-start px-6 py-3 docsHeroColorBlue rounded"
                        >
                            <p
                                class="text-sm font-medium leading-none text-white"
                            >
                                {{ $t("Add Products") }}
                            </p>
                        </button>
                    </label>
                    <div class="tab-content border p-2 overflow-auto">
                        <div class="mt-7 overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th
                                            style="width: 2%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        ></th>
                                        <th
                                            style="width: 4%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("POS") }}
                                        </th>
                                        <th
                                            style="width: 9%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Article Nr.") }}
                                        </th>
                                        <th
                                            style="width: 22%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Name") }}
                                        </th>
                                        <th
                                            style="width: 9%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Software") }}
                                        </th>
                                        <th
                                            style="width: 10%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Quantity") }}
                                        </th>
                                        <th
                                            style="width: 13%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Price Per Period") }}
                                        </th>
                                        <th
                                            style="width: 8%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Discount") }}
                                        </th>
                                        <th
                                            style="width: 12%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Period") }}
                                        </th>
                                        <th
                                            style="width: 240px"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Netto Total") }}
                                        </th>
                                        <th
                                            style="width: 2%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                        ></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <template
                                        v-for="(product, index) in hosting"
                                        :key="index"
                                    >
                                        <tr
                                            :tabindex="index"
                                            class="focus:outline-none h-16 border border-gray-100 rounded"
                                        >
                                            <td class="pl-5 align-top py-3">
                                                <input
                                                    type="checkbox"
                                                    class="relative z-10"
                                                    @input="
                                                        removeAllChildren(
                                                            index,
                                                            'hosting',
                                                            $event
                                                        )
                                                    "
                                                    v-model="
                                                        groupToggler['hosting'][
                                                            product.id
                                                        ]
                                                    "
                                                />
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="">
                                                    {{ index + 1 }}
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="">
                                                    {{ product.articleNumber }}
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="table-align">
                                                    <input
                                                        v-if="
                                                            product.isProductNameEdit
                                                        "
                                                        class="appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="text"
                                                        name="name"
                                                        v-model="product.name"
                                                        :class="{
                                                            Perror: errors[
                                                                `products.${index}.name`
                                                            ],
                                                        }"
                                                    />
                                                    <span v-else>{{
                                                        product.name
                                                    }}</span>
                                                </div>
                                                <div
                                                    @click="
                                                        toggleAdditionalDescriptionDropdowns(
                                                            product.id,
                                                            index,
                                                            additionalDescriptionToggled[
                                                                'hosting'
                                                            ][product.id]
                                                                ? 'remove'
                                                                : 'add',
                                                            'hosting'
                                                        )
                                                    "
                                                    class="flex mt-2 cursor-pointer"
                                                >
                                                    <font-awesome-icon
                                                        :class="`cursor-pointer cross mr-2 self-center text-${
                                                            additionalDescriptionToggled[
                                                                'hosting'
                                                            ][product.id]
                                                                ? 'red'
                                                                : 'blue'
                                                        }-500`"
                                                        :icon="`fa-solid fa-circle-${
                                                            additionalDescriptionToggled[
                                                                'hosting'
                                                            ][product.id]
                                                                ? 'minus'
                                                                : 'plus'
                                                        }`"
                                                    />
                                                    <p
                                                        :class="`text-sm text-${
                                                            additionalDescriptionToggled[
                                                                'hosting'
                                                            ][product.id]
                                                                ? 'red'
                                                                : 'blue'
                                                        }-500`"
                                                    >
                                                        {{
                                                            $t(
                                                                `${
                                                                    additionalDescriptionToggled[
                                                                        "hosting"
                                                                    ][
                                                                        product
                                                                            .id
                                                                    ]
                                                                        ? "Remove"
                                                                        : "Add"
                                                                } Additional Description`
                                                            )
                                                        }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="">
                                                    {{
                                                        product.productSoftware
                                                            ?.name
                                                    }}
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="flex">
                                                    <number-input
                                                        @inputEvent="
                                                            changedQuantityHosting(
                                                                index,
                                                                $event,
                                                                false
                                                            )
                                                        "
                                                        class="input-width appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="
                                                            product.quantity
                                                        "
                                                        :showPrefix="false"
                                                        :min="0"
                                                        @keypress="
                                                            limitToPositiveNumbers
                                                        "
                                                        name="quantity"
                                                    />
                                                </div>
                                            </td>
                                            <td
                                                class="pl-5 text-center align-top"
                                            >
                                                <div class="">
                                                    <number-input
                                                        @inputEvent="
                                                            changedQuantityHosting(
                                                                index,
                                                                $event,
                                                                false
                                                            )
                                                        "
                                                        class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="
                                                            product.pricePerPeriod
                                                        "
                                                        name="pricePerPeriod"
                                                        :allowNegative="true"
                                                    />
                                                </div>
                                            </td>
                                            <td
                                                class="pl-5 text-center align-top py-2"
                                            >
                                                <div class="">
                                                    <input
                                                        @input="
                                                            changedQuantityHosting(
                                                                index,
                                                                $event,
                                                                false
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
                                            <td
                                                class="pl-5 text-center align-top py-2"
                                            >
                                                <div class="">
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
                                                            :value="period.id"
                                                        >
                                                            {{ period.name }}
                                                        </option>
                                                    </select-input>
                                                </div>
                                            </td>
                                            <td
                                                class="pl-5 text-center align-top"
                                            >
                                                <div class="" style="width: 125px">
                                                    <number-input
                                                        @inputEvent="
                                                            hostingNettoTotalChanged(
                                                                event,
                                                                index,
                                                                false
                                                            )
                                                        "
                                                        class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="
                                                            product.nettoTotal
                                                        "
                                                        name="nettoTotal"
                                                        :allowNegative="true"
                                                    />
                                                </div>
                                            </td>

                                            <td class="text-center">
                                                <font-awesome-icon
                                                    @click="
                                                        removeOption(
                                                            index,
                                                            'hosting'
                                                        )
                                                    "
                                                    class="cursor-pointer cross"
                                                    icon="fa-solid fa-xmark"
                                                />
                                            </td>
                                        </tr>
                                        <tr
                                            v-if="
                                                additionalDescriptionToggled[
                                                    'hosting'
                                                ][product.id]
                                            "
                                        >
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="pl-6">
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
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr
                                            v-if="
                                                groupToggler['hosting'][
                                                    product.id
                                                ]
                                            "
                                            class="bg-gray-100"
                                        >
                                            <th
                                                style="width: 2%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            ></th>
                                            <th
                                                style="width: 4%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("POS") }}
                                            </th>
                                            <th
                                                style="width: 9%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Article Nr.") }}
                                            </th>
                                            <th
                                                style="width: 22%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Name") }}
                                            </th>
                                            <th
                                                style="width: 9%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Software") }}
                                            </th>
                                            <th
                                                style="width: 10%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Quantity") }}
                                            </th>
                                            <th
                                                style="width: 13%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{
                                                    $t(
                                                        "Single Price Per Period"
                                                    )
                                                }}
                                            </th>
                                            <th
                                                style="width: 8%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Total Price Period") }}
                                            </th>
                                            <th
                                                style="width: 12%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            ></th>
                                            <th
                                                style="width: 13%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            ></th>
                                            <th
                                                style="width: 2%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            ></th>
                                        </tr>

                                        <tr
                                            v-for="(
                                                childProduct, childIndex
                                            ) in product.children"
                                            :key="childIndex"
                                            :tabindex="childIndex"
                                            class="focus:outline-none h-16 border border-gray-100 rounded"
                                        >
                                            <td class="pl-5 text-center"></td>

                                            <td class="pl-5">
                                                <div class="">
                                                    {{ index + 1 }}.{{
                                                        childIndex + 1
                                                    }}
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div class="">
                                                    {{
                                                        childProduct.articleNumber
                                                    }}
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div class="">
                                                    <input
                                                        v-if="
                                                            childProduct.isProductNameEdit
                                                        "
                                                        class="appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="text"
                                                        name="name"
                                                        v-model="
                                                            childProduct.name
                                                        "
                                                        :class="{
                                                            Perror: errors[
                                                                `products.${index}.name`
                                                            ],
                                                        }"
                                                    />
                                                    <span v-else>{{
                                                        childProduct.name
                                                    }}</span>
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div class="">
                                                    {{
                                                        childProduct
                                                            .productSoftware
                                                            ?.name
                                                    }}
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div class="flex">
                                                    <number-input
                                                        @inputEvent="
                                                            changedQuantityHosting(
                                                                index,
                                                                $event,
                                                                false,
                                                                childIndex
                                                            )
                                                        "
                                                        class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="
                                                            childProduct.quantity
                                                        "
                                                        :showPrefix="false"
                                                        :min="0"
                                                        @keypress="
                                                            limitToPositiveNumbers
                                                        "
                                                        name="quantity"
                                                    />
                                                </div>
                                            </td>
                                            <td class="pl-5 text-center">
                                                <div class="">
                                                    <number-input
                                                        @inputEvent="
                                                            changedQuantityHosting(
                                                                index,
                                                                $event,
                                                                false,
                                                                childIndex
                                                            )
                                                        "
                                                        class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="
                                                            childProduct.pricePerPeriod
                                                        "
                                                        name="pricePerPeriod"
                                                        :allowNegative="true"
                                                    />
                                                </div>
                                            </td>
                                            <td class="pl-5 text-center">
                                                <number-input
                                                    @inputEvent="
                                                        changedQuantityHosting(
                                                            index,
                                                            $event,
                                                            false,
                                                            childIndex
                                                        )
                                                    "
                                                    :isReadonly="true"
                                                    class="w-full appearance-none rounded text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                    type="number"
                                                    v-model="
                                                        childProduct.totalPricePeriod
                                                    "
                                                    name="pricePerPeriod"
                                                    :allowNegative="true"
                                                />
                                            </td>
                                            <td class="pl-5 text-center">
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
                                            <td class="pl-5 text-center"></td>
                                            <td class="pl-5 text-center"></td>
                                        </tr>
                                        <tr
                                            v-if="
                                                groupToggler['hosting'][
                                                    product.id
                                                ]
                                            "
                                        >
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>
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
                            v-if="Object.keys(hosting).length > 0"
                            style="
                                flex-direction: column;
                                align-items: end;
                                justify-content: end;
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
                                    v-for="(value, key) in computeMwstHosting"
                                    :key="index"
                                >
                                    <h1>{{ key }}% {{ $t("Tax") }}:</h1>
                                    <h1
                                        :class="[
                                            globalLanguage === 'de'
                                                ? 'text-end'
                                                : '',
                                        ]"
                                    >
                                        {{ $formatter(value, globalLanguage) }}
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
                        <div class="flex justify-between mt-5">
                            <div class="flex">
                                <label
                                    for="optional-ams-products"
                                    class="self-center"
                                    >{{ $t("Optional Products") }}</label
                                >
                                <input
                                    class="ml-2"
                                    type="checkbox"
                                    @input="discardOptionalHostingProducts"
                                    v-model="optionalHostingProductsToggle"
                                    id="optional-ams-products"
                                />
                            </div>
                            <button
                                v-if="optionalHostingProductsToggle"
                                type="button"
                                style="background-color: #ed7d31"
                                @click="
                                    optionalHostingProducts = true;
                                    toggleProductsModal('hosting');
                                "
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
                            v-if="optionalHostingProductsToggle"
                            class="mt-7 overflow-x-auto"
                        >
                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th
                                            style="width: 4%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("POS") }}
                                        </th>
                                        <th
                                            style="width: 9%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Article Nr.") }}
                                        </th>
                                        <th
                                            style="width: 22%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Name") }}
                                        </th>
                                        <th
                                            style="width: 9%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Software") }}
                                        </th>
                                        <th
                                            style="width: 10%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Quantity") }}
                                        </th>
                                        <th
                                            style="width: 13%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Price Per Period") }}
                                        </th>
                                        <th
                                            style="width: 8%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Discount") }}
                                        </th>
                                        <th
                                            style="width: 12%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Period") }}
                                        </th>
                                        <th
                                            style="width: 240px"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Netto Total") }}
                                        </th>
                                        <th
                                            style="width: 2%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                        ></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr
                                        v-for="(
                                            product, index
                                        ) in optionalHosting"
                                        :key="index"
                                        :tabindex="index"
                                        class="focus:outline-none h-16 border border-gray-100 rounded"
                                    >
                                        <td class="pl-5">
                                            <div class="">
                                                {{ index + 1 }}
                                            </div>
                                        </td>
                                        <td class="pl-5">
                                            <div class="">
                                                {{ product.articleNumber }}
                                            </div>
                                        </td>
                                        <td class="pl-5">
                                            <div class="table-align">
                                                <input
                                                    v-if="
                                                        product.isProductNameEdit
                                                    "
                                                    class="appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                    type="text"
                                                    name="name"
                                                    v-model="product.name"
                                                    :class="{
                                                        Perror: errors[
                                                            `products.${index}.name`
                                                        ],
                                                    }"
                                                />
                                                <span v-else>{{
                                                    product.name
                                                }}</span>
                                            </div>
                                            <!--                                            <div class="">-->
                                            <!--                                                <input-->
                                            <!--                                                    :title="product.name"-->
                                            <!--                                                    class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:border-purple-500"-->
                                            <!--                                                    type="text"-->
                                            <!--                                                    :class="{-->
                                            <!--                                                        Perror: errors[-->
                                            <!--                                                            `products.${index}.name`-->
                                            <!--                                                        ],-->
                                            <!--                                                    }"-->
                                            <!--                                                    readonly-->
                                            <!--                                                    v-model="product.name"-->
                                            <!--                                                />-->
                                            <!--                                            </div>-->
                                        </td>
                                        <td class="pl-5">
                                            <div class="">
                                                {{
                                                    product.productSoftware
                                                        ?.name
                                                }}
                                            </div>
                                        </td>
                                        <td class="pl-5">
                                            <div class="flex">
                                                <number-input
                                                    @inputEvent="
                                                        changedQuantityHosting(
                                                            index,
                                                            $event,
                                                            true
                                                        )
                                                    "
                                                    class="input-width appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                    type="number"
                                                    :showPrefix="false"
                                                    v-model="product.quantity"
                                                    :min="0"
                                                    @keypress="
                                                        limitToPositiveNumbers
                                                    "
                                                    name="quantity"
                                                />
                                            </div>
                                        </td>
                                        <td class="pl-5 text-center">
                                            <div class="">
                                                <number-input
                                                    @inputEvent="
                                                        changedQuantityHosting(
                                                            index,
                                                            $event,
                                                            true
                                                        )
                                                    "
                                                    class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                    type="number"
                                                    v-model="
                                                        product.pricePerPeriod
                                                    "
                                                    name="pricePerPeriod"
                                                    :allowNegative="true"
                                                />
                                            </div>
                                        </td>
                                        <td class="pl-5 text-center">
                                            <div class="">
                                                <input
                                                    @input="
                                                        changedQuantityHosting(
                                                            index,
                                                            $event,
                                                            true
                                                        )
                                                    "
                                                    class="input-width appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                    type="number"
                                                    v-model="product.discount"
                                                    name="discount"
                                                />
                                            </div>
                                        </td>
                                        <td class="pl-5 text-center">
                                            <div class="">
                                                <select-input
                                                    class="input-width"
                                                    :key="product.paymentPeriod"
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
                                                        :value="period.id"
                                                    >
                                                        {{ period.name }}
                                                    </option>
                                                </select-input>
                                            </div>
                                        </td>
                                        <td class="pl-5 text-center">
                                            <div class="" style="width: 125px">
                                                <number-input
                                                    @inputEvent="
                                                        hostingNettoTotalChanged(
                                                            event,
                                                            index,
                                                            true
                                                        )
                                                    "
                                                    class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                    type="number"
                                                    v-model="product.nettoTotal"
                                                    name="nettoTotal"
                                                    :allowNegative="true"
                                                />
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <font-awesome-icon
                                                @click="
                                                    removeOption(
                                                        index,
                                                        'optionalHosting'
                                                    )
                                                "
                                                class="cursor-pointer cross"
                                                icon="fa-solid fa-xmark"
                                            />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <br />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="cloudToggle">
            <div class="tabs">
                <div class="relative tab">
                    <input
                        class="tab-checkbox"
                        type="checkbox"
                        id="cloud-accordion"
                        :checked="true"
                    />
                    <label
                        style="background-color: #2957a4"
                        class="rounded tab-label styles-configurator-tab-label flex justify-between p-2 text-white"
                        for="cloud-accordion"
                    >
                        <p class="self-center">
                            {{ $t("Cloud") }}
                        </p>
                        <div
                            class="flex"
                            style="
                                align-self: center;
                                justify-self: center;
                                font-weight: normal;
                                color: black;
                            "
                        >
                            <label for="" class="mr-2 self-center text-white"
                                >{{ $t("Payment Period") }}:</label
                            >
                            <select-input
                                v-if="show"
                                @update:model-value="
                                    updatePeriodOfAllProducts('cloud')
                                "
                                :key="globalPeriodCloud"
                                v-model="globalPeriodCloud"
                            >
                                <option
                                    v-for="period in periods.data"
                                    :key="'period-' + period.id"
                                    :value="period.id"
                                >
                                    {{ period.name }}
                                </option>
                            </select-input>
                        </div>
                        <button
                            type="button"
                            @click="
                                optionalCloudProducts = false;
                                addCloudChildren = false;
                                toggleProductsModal('cloud-software');
                            "
                            style="background-color: #ed7d31"
                            class="mt-4 sm:mt-0 inline-flex items-start justify-start px-6 py-3 docsHeroColorBlue rounded"
                        >
                            <p
                                class="text-sm font-medium leading-none text-white"
                            >
                                {{ $t("Add Products") }}
                            </p>
                        </button>
                    </label>
                    <div class="tab-content border p-2 overflow-auto">
                        <div class="mt-7 overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th
                                            style="width: 2%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        ></th>
                                        <th
                                            style="width: 4%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("POS") }}
                                        </th>
                                        <th
                                            style="width: 9%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Article Nr.") }}
                                        </th>
                                        <th
                                            style="width: 22%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Name") }}
                                        </th>
                                        <th
                                            style="width: 9%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Software") }}
                                        </th>
                                        <th
                                            style="width: 10%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Quantity") }}
                                        </th>
                                        <th
                                            style="width: 10%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Duration") }}
                                        </th>
                                        <th
                                            style="width: 13%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Product Price") }}
                                        </th>
                                        <th
                                            style="width: 8%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Discount") }}
                                        </th>
                                        <th
                                            style="width: 12%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Period") }}
                                        </th>
                                        <th
                                            style="width: 240px"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Netto Total") }}
                                        </th>
                                        <th
                                            style="width: 2%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                        ></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <template
                                        v-for="(product, index) in cloud"
                                        :key="index"
                                    >
                                        <tr
                                            :tabindex="index"
                                            class="focus:outline-none h-16 border border-gray-100 rounded"
                                        >
                                            <td class="pl-5 align-top py-3">
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
                                                        groupToggler['cloud'][
                                                            product.id
                                                        ]
                                                    "
                                                />
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="">
                                                    {{ index + 1 }}
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="">
                                                    {{ product.articleNumber }}
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="table-align">
                                                    <input
                                                        v-if="
                                                            product.isProductNameEdit
                                                        "
                                                        class="appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="text"
                                                        name="name"
                                                        v-model="product.name"
                                                        :class="{
                                                            Perror: errors[
                                                                `products.${index}.name`
                                                            ],
                                                        }"
                                                    />
                                                    <span v-else>{{
                                                        product.name
                                                    }}</span>
                                                </div>
                                                <div
                                                    @click="
                                                        toggleAdditionalDescriptionDropdowns(
                                                            product.id,
                                                            index,
                                                            additionalDescriptionToggled[
                                                                'cloud'
                                                            ][product.id]
                                                                ? 'remove'
                                                                : 'add',
                                                            'cloud'
                                                        )
                                                    "
                                                    class="flex mt-2 cursor-pointer"
                                                >
                                                    <font-awesome-icon
                                                        :class="`cursor-pointer cross mr-2 self-center text-${
                                                            additionalDescriptionToggled[
                                                                'cloud'
                                                            ][product.id]
                                                                ? 'red'
                                                                : 'blue'
                                                        }-500`"
                                                        :icon="`fa-solid fa-circle-${
                                                            additionalDescriptionToggled[
                                                                'cloud'
                                                            ][product.id]
                                                                ? 'minus'
                                                                : 'plus'
                                                        }`"
                                                    />
                                                    <p
                                                        :class="`text-sm text-${
                                                            additionalDescriptionToggled[
                                                                'cloud'
                                                            ][product.id]
                                                                ? 'red'
                                                                : 'blue'
                                                        }-500`"
                                                    >
                                                        {{
                                                            $t(
                                                                `${
                                                                    additionalDescriptionToggled[
                                                                        "cloud"
                                                                    ][
                                                                        product
                                                                            .id
                                                                    ]
                                                                        ? "Remove"
                                                                        : "Add"
                                                                } Additional Description`
                                                            )
                                                        }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="">
                                                    {{
                                                        product.productSoftware
                                                            ?.name
                                                    }}
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="flex">
                                                    <number-input
                                                        @inputEvent="
                                                            changedQuantityCloud(
                                                                index,
                                                                $event,
                                                                false
                                                            )
                                                        "
                                                        class="input-width appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        :showPrefix="false"
                                                        v-model="
                                                            product.quantity
                                                        "
                                                        :min="0"
                                                        @keypress="
                                                            limitToPositiveNumbers
                                                        "
                                                        name="quantity"
                                                    />
                                                </div>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="flex">
                                                    <input
                                                        @input="
                                                            changedQuantityCloud(
                                                                index,
                                                                $event,
                                                                false
                                                            )
                                                        "
                                                        :min="0"
                                                        @keypress="
                                                            limitToPositiveNumbers
                                                        "
                                                        class="w-full appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="
                                                            product.duration
                                                        "
                                                        name="duration"
                                                    />
                                                </div>
                                            </td>
                                            <td
                                                class="pl-5 text-center align-top"
                                            >
                                                <div class="">
                                                    <number-input
                                                        @inputEvent="
                                                            changedQuantityCloud(
                                                                index,
                                                                $event,
                                                                false
                                                            )
                                                        "
                                                        class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="
                                                            product.salePrice
                                                        "
                                                        name="salePrice"
                                                        :allowNegative="true"
                                                    />
                                                </div>
                                            </td>
                                            <td
                                                class="pl-5 text-center align-top py-2"
                                            >
                                                <div class="">
                                                    <input
                                                        @input="
                                                            changedQuantityCloud(
                                                                index,
                                                                $event,
                                                                false
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
                                            <td
                                                class="pl-5 text-center align-top py-2"
                                            >
                                                <div class="form-group">
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
                                                            :value="period.id"
                                                        >
                                                            {{ period.name }}
                                                        </option>
                                                    </select-input>
                                                </div>
                                            </td>
                                            <td
                                                class="pl-5 text-center align-top"
                                            >
                                                <div class="" style="width: 125px">
                                                    <number-input
                                                        @inputEvent="
                                                            cloudNettoTotalChanged(
                                                                event,
                                                                index,
                                                                false
                                                            )
                                                        "
                                                        class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="
                                                            product.nettoTotal
                                                        "
                                                        name="nettoTotal"
                                                        :allowNegative="true"
                                                    />
                                                </div>
                                            </td>

                                            <td class="text-center">
                                                <font-awesome-icon
                                                    @click="
                                                        removeOption(
                                                            index,
                                                            'cloud'
                                                        )
                                                    "
                                                    class="cursor-pointer cross"
                                                    icon="fa-solid fa-xmark"
                                                />
                                            </td>
                                        </tr>
                                        <tr
                                            v-if="
                                                additionalDescriptionToggled[
                                                    'cloud'
                                                ][product.id]
                                            "
                                        >
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="pl-6">
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
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr
                                            v-if="
                                                groupToggler['cloud'][
                                                    product.id
                                                ]
                                            "
                                            class="bg-gray-100"
                                        >
                                            <th
                                                style="width: 2%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            ></th>
                                            <th
                                                style="width: 4%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("POS") }}
                                            </th>
                                            <th
                                                style="width: 9%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Article Nr.") }}
                                            </th>
                                            <th
                                                style="width: 22%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Name") }}
                                            </th>
                                            <th
                                                style="width: 9%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Software") }}
                                            </th>
                                            <th
                                                style="width: 10%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Quantity") }}
                                            </th>
                                            <th
                                                style="width: 10%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Duration") }}
                                            </th>
                                            <th
                                                style="width: 13%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Product Price") }}
                                            </th>
                                            <th
                                                style="width: 8%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            >
                                                {{ $t("Total Product Price") }}
                                            </th>
                                            <th
                                                style="width: 12%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            ></th>
                                            <th
                                                style="width: 13%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                            ></th>
                                            <th
                                                style="width: 2%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            ></th>
                                        </tr>
                                        <tr
                                            v-for="(
                                                child, childIndex
                                            ) in product.children"
                                            :tabindex="childIndex"
                                            class="focus:outline-none h-16 border border-gray-100 rounded"
                                        >
                                            <td class="pl-5"></td>
                                            <td class="pl-5">
                                                <div class="">
                                                    {{ index + 1 }}.{{
                                                        childIndex + 1
                                                    }}
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div class="">
                                                    {{ child.articleNumber }}
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div class="">
                                                    <input
                                                        v-if="
                                                            child.isProductNameEdit
                                                        "
                                                        class="appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="text"
                                                        name="name"
                                                        v-model="child.name"
                                                        :class="{
                                                            Perror: errors[
                                                                `products.${index}.name`
                                                            ],
                                                        }"
                                                    />
                                                    <span v-else>{{
                                                        child.name
                                                    }}</span>
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div class="">
                                                    {{
                                                        child.productSoftware
                                                            ?.name
                                                    }}
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div class="flex">
                                                    <number-input
                                                        @inputEvent="
                                                            changedQuantityCloud(
                                                                index,
                                                                $event,
                                                                false,
                                                                childIndex
                                                            )
                                                        "
                                                        class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        :showPrefix="false"
                                                        v-model="child.quantity"
                                                        name="quantity"
                                                        :min="0"
                                                        @keypress="
                                                            limitToPositiveNumbers
                                                        "
                                                    />
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <div class="flex">
                                                    <input
                                                        @input="
                                                            changedQuantityCloud(
                                                                index,
                                                                $event,
                                                                false,
                                                                childIndex
                                                            )
                                                        "
                                                        class="w-full appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        :min="0"
                                                        @keypress="
                                                            limitToPositiveNumbers
                                                        "
                                                        v-model="child.duration"
                                                        name="duration"
                                                    />
                                                </div>
                                            </td>
                                            <td class="pl-5 text-center">
                                                <div class="">
                                                    <number-input
                                                        @inputEvent="
                                                            changedQuantityCloud(
                                                                index,
                                                                $event,
                                                                false,
                                                                childIndex
                                                            )
                                                        "
                                                        class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="
                                                            child.salePrice
                                                        "
                                                        name="salePrice"
                                                        :allowNegative="true"
                                                    />
                                                </div>
                                            </td>
                                            <td class="pl-5 text-center">
                                                <div class="">
                                                    <number-input
                                                        @inputEvent="
                                                            changedQuantityCloud(
                                                                index,
                                                                $event,
                                                                false,
                                                                childIndex
                                                            )
                                                        "
                                                        :isReadonly="true"
                                                        class="w-full appearance-none rounded text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                        type="number"
                                                        v-model="
                                                            child.totalPricePeriod
                                                        "
                                                        name="salePrice"
                                                        :allowNegative="true"
                                                    />
                                                </div>
                                            </td>
                                            <td class="pl-5 text-center">
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
                                            <td class="pl-5 text-center"></td>
                                            <td class="text-center"></td>
                                        </tr>
                                        <tr
                                            v-if="
                                                groupToggler['cloud'][
                                                    product.id
                                                ]
                                            "
                                        >
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>
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
                            v-if="Object.keys(cloud).length > 0"
                            style="
                                flex-direction: column;
                                align-items: end;
                                justify-content: end;
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
                                    :key="index"
                                >
                                    <h1>{{ key }}% {{ $t("Tax") }}:</h1>
                                    <h1
                                        :class="[
                                            globalLanguage === 'de'
                                                ? 'text-end'
                                                : '',
                                        ]"
                                    >
                                        {{ $formatter(value, globalLanguage) }}
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
                        <div class="flex justify-between mt-5">
                            <div class="flex">
                                <label
                                    for="optional-ams-products"
                                    class="self-center"
                                    >{{ $t("Optional Products") }}</label
                                >
                                <input
                                    class="ml-2"
                                    type="checkbox"
                                    @input="discardOptionalCloudProducts"
                                    v-model="optionalCloudProductsToggle"
                                    id="optional-ams-products"
                                />
                            </div>
                            <button
                                v-if="optionalCloudProductsToggle"
                                type="button"
                                style="background-color: #ed7d31"
                                @click="
                                    optionalCloudProducts = true;
                                    toggleProductsModal('cloud-software');
                                "
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
                            v-if="optionalCloudProductsToggle"
                            class="mt-7 overflow-x-auto"
                        >
                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th
                                            style="width: 4%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("POS") }}
                                        </th>
                                        <th
                                            style="width: 9%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Article Nr.") }}
                                        </th>
                                        <th
                                            style="width: 22%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Name") }}
                                        </th>
                                        <th
                                            style="width: 9%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Software") }}
                                        </th>
                                        <th
                                            style="width: 10%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Quantity") }}
                                        </th>
                                        <th
                                            style="width: 10%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Duration") }}
                                        </th>
                                        <th
                                            style="width: 13%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Product Price") }}
                                        </th>
                                        <th
                                            style="width: 8%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Discount") }}
                                        </th>
                                        <th
                                            style="width: 12%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Period") }}
                                        </th>
                                        <th
                                            style="width: 240px"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Netto Total") }}
                                        </th>
                                        <th
                                            style="width: 2%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                        ></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr
                                        v-for="(
                                            product, index
                                        ) in optionalCloud"
                                        :key="index"
                                        :tabindex="index"
                                        class="focus:outline-none h-16 border border-gray-100 rounded"
                                    >
                                        <td class="pl-5">
                                            <div class="">
                                                {{ index + 1 }}
                                            </div>
                                        </td>
                                        <td class="pl-5">
                                            <div class="">
                                                {{ product.articleNumber }}
                                            </div>
                                        </td>
                                        <td class="pl-5">
                                            <div class="table-align">
                                                <input
                                                    v-if="
                                                        product.isProductNameEdit
                                                    "
                                                    class="appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                    type="text"
                                                    name="name"
                                                    v-model="product.name"
                                                    :class="{
                                                        Perror: errors[
                                                            `products.${index}.name`
                                                        ],
                                                    }"
                                                />
                                                <span v-else>{{
                                                    product.name
                                                }}</span>
                                            </div>
                                            <!--                                            <div class="">-->
                                            <!--                                                <input-->
                                            <!--                                                    :title="product.name"-->
                                            <!--                                                    class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:border-purple-500"-->
                                            <!--                                                    type="text"-->
                                            <!--                                                    :class="{-->
                                            <!--                                                        Perror: errors[-->
                                            <!--                                                            `products.${index}.name`-->
                                            <!--                                                        ],-->
                                            <!--                                                    }"-->
                                            <!--                                                    readonly-->
                                            <!--                                                    v-model="product.name"-->
                                            <!--                                                />-->
                                            <!--                                            </div>-->
                                        </td>
                                        <td class="pl-5">
                                            <div class="">
                                                {{
                                                    product.productSoftware
                                                        ?.name
                                                }}
                                            </div>
                                        </td>
                                        <td class="pl-5">
                                            <div class="flex">
                                                <number-input
                                                    @inputEvent="
                                                        changedQuantityCloud(
                                                            index,
                                                            $event,
                                                            true
                                                        )
                                                    "
                                                    class="input-width appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                    type="number"
                                                    v-model="product.quantity"
                                                    :showLabel="false"
                                                    name="quantity"
                                                    :min="0"
                                                    @keypress="
                                                        limitToPositiveNumbers
                                                    "
                                                />
                                            </div>
                                        </td>
                                        <td class="pl-5">
                                            <div class="flex">
                                                <input
                                                    @input="
                                                        changedQuantityCloud(
                                                            index,
                                                            $event,
                                                            true
                                                        )
                                                    "
                                                    class="w-full appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                    type="number"
                                                    v-model="product.duration"
                                                    name="duration"
                                                    :min="0"
                                                    @keypress="
                                                        limitToPositiveNumbers
                                                    "
                                                />
                                            </div>
                                        </td>
                                        <td class="pl-5 text-center">
                                            <div class="">
                                                <number-input
                                                    @inputEvent="
                                                        changedQuantityCloud(
                                                            index,
                                                            $event,
                                                            true
                                                        )
                                                    "
                                                    class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                    type="number"
                                                    v-model="product.salePrice"
                                                    name="salePrice"
                                                    :allowNegative="true"
                                                />
                                            </div>
                                        </td>
                                        <td class="pl-5 text-center">
                                            <div class="">
                                                <input
                                                    @input="
                                                        changedQuantityCloud(
                                                            index,
                                                            $event,
                                                            true
                                                        )
                                                    "
                                                    class="input-width appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                    type="number"
                                                    v-model="product.discount"
                                                    name="discount"
                                                />
                                            </div>
                                        </td>
                                        <td class="pl-5 text-center">
                                            <div class="">
                                                <select-input
                                                    class="input-width"
                                                    :key="product.paymentPeriod"
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
                                                        :value="period.id"
                                                    >
                                                        {{ period.name }}
                                                    </option>
                                                </select-input>
                                            </div>
                                        </td>
                                        <td class="pl-5 text-center">
                                            <div class="" style="width: 125px">
                                                <number-input
                                                    @inputEvent="
                                                        cloudNettoTotalChanged(
                                                            event,
                                                            index,
                                                            true
                                                        )
                                                    "
                                                    class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                    type="number"
                                                    v-model="product.nettoTotal"
                                                    name="nettoTotal"
                                                    :allowNegative="true"
                                                />
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <font-awesome-icon
                                                @click="
                                                    removeOption(
                                                        index,
                                                        'optionalCloud'
                                                    )
                                                "
                                                class="cursor-pointer cross"
                                                icon="fa-solid fa-xmark"
                                            />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <br />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-6xl flex flex-row-reverse justify-right">
            <loading-button
                v-if="
                    module == 'order' && modelData?.orderStatus === 'approved'
                "
                :loading="isLoading"
                @click="createInvoices"
                class="btn-green"
                >{{ $t("Create Invoices") }}</loading-button
            >

            <loading-button
                v-if="module == 'offer'"
                :loading="isLoading"
                @click="update('copy')"
                class="btn-green"
                >{{ $t("Copy Offer") }}</loading-button
            >

            <loading-button
                :loading="isLoading"
                @click="update('update')"
                class="btn-green mr-3"
                >{{ $t("Update") }}</loading-button
            >

            <loading-button
                @click.prevent="update('generate', 'pdf')"
                :loading="isLoading"
                class="secondary-btn mr-3"
                >{{ $t("Generate PDF") }}</loading-button
            >

            <loading-button
                @click.prevent="update('generate', 'docx')"
                :loading="isLoading"
                class="secondary-btn mr-3"
                >{{ $t("Generate Docx") }}</loading-button
            >
        </div>
    </div>
    <select-product-modal
        v-if="productToggle"
        :productType="productType"
        @selected="addProducts"
        :fromOffers="true"
        :fromOffersEdit="true"
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
    ></select-product-modal>
    <select-service-modal
        v-if="serviceToggle"
        :fromOffers="true"
        :fromOffersEdit="true"
        @selected="addServices"
        @cancelled="hideModal"
        :selectedItems="[]"
        :products="serviceProducts"
    ></select-service-modal>
    <select-ams-modal
        v-if="amsToggle"
        @selected="addAMS"
        :fromOffers="true"
        :fromOffersEdit="true"
        @cancelled="amsToggle = false"
        :selectedItems="[]"
        :products="amsProducts"
    ></select-ams-modal>
</template>

<script>
import TextInput from "../../Components/TextInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import NumberInput from "../../Components/NumberInput.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import SelectProductModal from "../../Components/SelectProductModal.vue";
import SelectServiceModal from "../../Components/SelectServiceModal.vue";
import SelectAmsModal from "../../Components/SelectAmsModal.vue";
import TextAreaInput from "../../Components/TextareaInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import { mapGetters } from "vuex";
import offerMixin from "../../Mixins/offerMixin.js";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    mixins: [offerMixin],
    props: {
        module: {
            type: String,
            default: "offer",
        },
    },
    data() {
        return {
            isVisibleForCustomer: false,
            returnPage: "",
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Sales",
                    to: "/offers",
                },
                {
                    text: "Offers",
                    to: "/offers?page=" + this.$route.query.page,
                },
                {
                    text: "Edit",
                    active: true,
                },
            ],
            additionalDescriptionToggled: {
                softwareLicences: {},
                softwareMaintenance: {},
                services: {},
                categories: {},
                ams: {},
                hosting: {},
                cloud: {},
            },
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
            optionalServicesProducts: false, // keeps track of which array to add services products to when selected from modal
            optionalServicesProductsToggle: false, // toggles the optional products table in services
            optionalServices: [], // keeps the optional services products
            optionalCategories: [], // keeps the optional categories products
            optionalAmsProducts: false, // keeps track of which array to add ams products to when selected from modal
            optionalAMSProductsToggle: false, // toggles the optional products table in AMS
            optionalAms: [], // keeps the optional AMS products
            optionalHostingProducts: false, // keeps track of which array to add hosting products to when selected from modal
            optionalHostingProductsToggle: false, // toggles the optional products table in hosting
            optionalHosting: [], // keeps the optional AMS products
            optionalCloudProducts: false, // keeps track of which array to add cloud products to when selected from modal
            optionalCloudProductsToggle: false, // toggles the optional products table in cloud
            optionalCloud: [], // keeps the optional cloud products
            optionalSoftwareProducts: false, // keeps track of which array to add license/maintenance products to when selected from modal
            optionalSoftwareProductsToggle: false, // toggles the optional products table in license
            optionalSoftwareLicenses: [], // keeps the optional license products
            optionalSoftwareMaintenance: [], // keeps the optional maintenance products
            slaLevelEnums: {
                0: "Urgent",
                1: "High",
                2: "Middle",
            },
            slaToggled: {}, //keeps track of all the products for which to the SLA dropdowns in AMS
            selectedOffer: "",
            modelData: {},
            componentIdsMappedToProductIds: {
                softwareLicenses: {},
                softwareMaintenance: {},
                cloud: {},
                hosting: {},
                ams: {},
                services: {},
            },
            optionalComponentIdsMappedToProductIds: {
                softwareLicenses: {},
                softwareMaintenance: {},
                cloud: {},
                hosting: {},
                ams: {},
                services: {},
            },
            productTypeEnums: {
                software: "software",
                service: "service",
                ams: "ams",
                hosting: "hosting",
                "cloud-software": "cloudSoftware",
            },
            cloud: [], //holds the selected product of cloud type
            cloudSoftwareProducts: [], // all the cloud products that are loaded in the products modal, sent as prop to select-products-modal
            productType: "software",
            hosting: [], //holds the selected product of hosting type
            hostingProducts: [], // all the hosting products that are loaded in the products modal, sent as prop to select-products-modal
            hostingToggle: false,
            cloudToggle: false,
            user: "",
            shouldShow: true,
            amsToggle: false,
            show: true,
            globalPeriodAMS: "",
            globalPeriodHosting: "",
            globalPeriodCloud: "",
            ams: [],
            categories: [],
            form: {
                offerNumber: "",
                createdBy: "",
                createdAt: "",
                paymentTerms: "",
                expiryDate: new Date(),
                term: "",
                company: null,
                project: null,
                type: "budget",
                receiverType: "customer",
                template: "empty",
                productsGroupedBy: "nothing",
                coverLetterText: "",
                offerDescriptionText: "",
                contactPerson: "",
                orderStatus: "draft",
                offerStatus: "entwurf",
                updatedAt: new Date(),
                removeFromStatistics: false,
                plannedStartDate: new Date(),
                externalOrderNumber: "",
                createdByShow: "",
                identifier: "",
                isVisibleForCustomer: false,
            },
            selectedProductType: "softwareLicences",
            softwareProducts: [],
            serviceProducts: [],
            serviceToggle: false,
            productToggle: false,
            softwareLicences: [],
            softwareLicencesToggle: false,
            softwareMaintenance: [],
            softwareMaintenanceToggle: false,
            applicationManagementServiceToggle: false,
            hostingCloudToggle: false,
            softwareLicencesTax: [],
            softwareMaintenanceTax: [],
            servicesToggle: false,
            services: [],
            servicesTax: [],
            createdByUsers: [],
        };
    },
    watch: {
        "form.productsGroupedBy"(val) {
            if (val === "category") {
                this.computeServicesByCategory();
            }
        },
        "form.receiverType"() {
            this.shouldShow = false;
            this.form.company = "";
            this.$nextTick(() => {
                this.shouldShow = true;
            });
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
    async mounted() {
        if (this.$route.query.page) {
            this.returnPage = this.$route.query.page;
        }
        try {
            this.$store.commit("showLoader", true);
            await this.fetchOfferData(); // get the offer show data
            // if the module is of type "order" then we need to fetch the offers listing to show in the dropdown at the top
            if (this.module === "order") {
                await this.$store.dispatch("offers/list");
            }
            await this.$store.dispatch("companies/list", {
                perPage: 25,
                page: 1,
                selectedIds: [this.modelData?.company],
            });
            this.$store.dispatch("companies/list", {
                perPage: 25,
                page: 1,
                customerType: "lead",
                selectedIds: [this.modelData?.company],
            });
            this.$store.dispatch("companies/list", {
                perPage: 25,
                page: 1,
                customerType: "partner",
                selectedIds: [this.modelData?.company],
            });
            await this.$store.dispatch("projects/list", {
                perPage: 25,
                page: 1,
            });
            await this.$store.dispatch("termsOfPayment/list", {
                perPage: 25,
                page: 1,
            });
            await this.$store.dispatch("periods/list");
            await this.$store.dispatch("units/list");
            const userResponse = await this.$store.dispatch("auth/list", {
                limit_start: 0,
                limit_count: 25,
                type: "employee",
            });
            this.createdByUsers = userResponse?.data?.data;
            await this.setData();
            this.form.createdByShow = this.createdByUsers.find((user) => {
                return user.id === this.form.createdBy;
            });
        } catch (e) {
            console.log(e);
        } finally {
            this.$store.commit("showLoader", false);
        }
    },
    computed: {
        ...mapGetters("auth", {
            authUser: "user",
        }),
        ...mapGetters("companyEmployees", ["users"]),
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("units", ["units"]),
        ...mapGetters("periods", ["periods"]),
        ...mapGetters(["errors"]),
        ...mapGetters("offers", ["offers"]),
        ...mapGetters("companies", ["companies", "leads", "partners"]),
        ...mapGetters("projects", ["projects"]),
        ...mapGetters("slaLevel", ["slaLevels"]),
        ...mapGetters("slaServiceTimes", ["slaServiceTimes"]),
        ...mapGetters("termsOfPayment", {
            termsOfPayment: "termsOfPayment",
        }),
        // based on the variables returns the array that will be passed as selectedItems prop value in SelectProductModal
        selectedItems() {
            if (this.addCloudChildren) {
                return this.cloud?.[this.cloudIndex]?.children ?? [];
            } else if (this.addHostingChildren) {
                return this.hosting?.[this.hostingIndex]?.children ?? [];
            } else {
                if (this.optionalSoftwareProducts) {
                    return this.optionalSoftwareLicenses;
                } else {
                    if (this.productType === "software") {
                        if (this.selectedProductType === "softwareLicences") {
                            return this.softwareLicences;
                        } else {
                            return this.softwareMaintenance;
                        }
                    } else {
                        if (this.productType === "hosting") {
                            if (this.optionalHostingProducts) {
                                return this.optionalHosting;
                            } else {
                                return this.hosting;
                            }
                        } else {
                            if (this.optionalCloudProducts) {
                                return this.optionalCloud;
                            } else {
                                return this.cloud;
                            }
                        }
                    }
                }
            }
        },
        computeSummaryCloud() {
            let sum = +this.computeNettoCloud;
            for (let key in this.computeMwstCloud) {
                sum += +this.computeMwstCloud[key];
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
                mwst[product.tax] = (
                    +(mwst?.[product.tax] ?? 0) + +product.taxRate
                ).toFixed(2);
            });
            return mwst;
        },
        computeSummaryHosting() {
            let sum = +this.computeNettoHosting;
            for (let key in this.computeMwstHosting) {
                sum += +this.computeMwstHosting[key];
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
                mwst[product.tax] = (
                    +(mwst?.[product.tax] ?? 0) + +product.taxRate
                ).toFixed(2);
            });
            return mwst;
        },
        computeSummaryAms() {
            let sum = +this.computeNettoAms;
            for (let key in this.computeMwstAms) {
                sum += +this.computeMwstAms[key];
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
                mwst[product.tax] = (
                    +(mwst?.[product.tax] ?? 0) + +product.taxRate
                ).toFixed(2);
            });
            return mwst;
        },
        computeMwst() {
            const mwst = {};
            this.services.forEach((service) => {
                mwst[service.tax] = (
                    +(mwst?.[service.tax] ?? 0) + +service.taxRate
                ).toFixed(2);
            });
            return mwst;
        },
        computeMwstCategory() {
            const mwst = {};
            this.categories.forEach((category) => {
                mwst[category.tax] = (
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
        computeNetto() {
            return this.services.reduce((accumulator, currentValue) => {
                return +accumulator + +currentValue.nettoTotal;
            }, 0);
        },
        computeSummaryCategory() {
            let sum = +this.computeNettoCategory;
            for (let key in this.computeMwstCategory) {
                sum += +this.computeMwstCategory[key];
            }
            return sum.toFixed(2);
        },
        computeSummary() {
            let sum = +this.computeNetto;
            for (let key in this.computeMwst) {
                sum += +this.computeMwst[key];
            }
            return sum.toFixed(2);
        },
        totalAmountLicences() {
            const sum = this.softwareLicences.reduce((accumulator, object) => {
                return parseFloat(accumulator) + parseFloat(object.totalPrice);
            }, 0);
            return sum ? sum.toFixed(2) : 0;
        },
        totalAmountWithoutTaxServices() {
            const sum = this.services.reduce((accumulator, object) => {
                return (
                    parseFloat(accumulator) + parseFloat(object.priceWithoutTax)
                );
            }, 0);

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
        totalAmountMaintenance() {
            let sum = this.softwareMaintenance.reduce((accumulator, object) => {
                return (
                    parseFloat(accumulator) +
                    parseFloat(object.maintenancePrice)
                );
            }, 0);
            sum = parseFloat(
                sum +
                    parseFloat(
                        this.softwareMaintenanceTax?.[0]?.["amount"] ?? 0
                    )
            );
            return sum ? sum.toFixed(2) : 0;
        },
        totalAmountWithoutTaxMaintenance() {
            const sum = this.softwareMaintenance.reduce(
                (accumulator, object) => {
                    return (
                        parseFloat(accumulator) +
                        parseFloat(object.maintenancePrice)
                    );
                },
                0
            );

            return sum ? sum.toFixed(2) : 0;
        },
    },
    methods: {
        hideModal(){
            this.serviceToggle = false;
        },
        hideProductModal(){
            this.productToggle = false;
        },
        async createInvoices() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch(
                    "orderConfirmation/createInvoices",
                    this.$route.params.id
                );
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * sets the additionalDescriptionToggled object value based on the index and type
         * @param {id} id of the product for which to toggle additional information dropdown
         * @param {index} index of the product
         * @param {type} possible values (add, remove)
         * @param {component} the component of the product for which 'additionalDescription' is being toggled
         */
        toggleAdditionalDescriptionDropdowns(id, index, type, component) {
            this.additionalDescriptionToggled[component][id] = type === "add";
            // if removed set the additional description values of the product back to default
            if (type === "remove") {
                if (component === "softwareLicences")
                    this.softwareLicences[index]["additionalDescription"] = "";
                else if (component === "softwareMaintenance")
                    this.softwareMaintenance[index]["additionalDescription"] =
                        "";
                else if (component === "services")
                    this.services[index]["additionalDescription"] = "";
                else if (component === "categories")
                    this.categories[index]["additionalDescription"] = "";
                else if (component === "ams")
                    this.ams[index]["additionalDescription"] = "";
                else if (component === "hosting")
                    this.hosting[index]["additionalDescription"] = "";
                else if (component === "cloud")
                    this.cloud[index]["additionalDescription"] = "";
            }
        },
        /**
         * fetch projects based on customerId
         */
        async fetchProjectsByCustomer() {
            try {
                this.form.project = "";
                await this.$store.dispatch("projects/list", {
                    companyId: this.form.company?.id,
                });
            } catch (e) {
                console.log(e);
            }
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
         * discards the products in optionalServices, optionalCategories array when the optionalServicesProductsToggle is unchecked
         * @param {event} the input event
         */
        async discardOptionalServiceProducts(event) {
            await this.$nextTick();
            if (!event.target.checked) {
                this.optionalServices.splice(0, this.optionalServices.length);
                this.optionalCategories.splice(
                    0,
                    this.optionalCategories.length
                );
            }
        },
        /**
         * discards the products in optionalSoftwareLicenses, optionalSoftwareMaintenance array when the optionalSoftwareProductsToggle is unchecked
         * @param {event} the input event
         */
        async discardOptionalSoftwareProducts(event) {
            await this.$nextTick();
            if (!event.target.checked) {
                this.optionalSoftwareLicenses.splice(
                    0,
                    this.optionalSoftwareLicenses.length
                );
                this.optionalSoftwareMaintenance.splice(
                    0,
                    this.optionalSoftwareMaintenance.length
                );
            }
        },
        /**
         * discards the products in optionalHosting array when the optionalHostingProductsToggle is unchecked
         * @param {event} the input event
         */
        async discardOptionalHostingProducts(event) {
            await this.$nextTick();
            if (!event.target.checked) {
                this.optionalHosting.splice(0, this.optionalHosting.length);
            }
        },
        /**
         * discards the products in optionalAms array when the optionalAmsProductsToggle is unchecked
         * @param {event} the input event
         */
        async discardOptionalAMSProducts(event) {
            await this.$nextTick();
            if (!event.target.checked) {
                this.optionalAms.splice(0, this.optionalAms.length);
            }
        },
        /**
         * discards the products in optionalCloud array when the optionalCloudProductsToggle is unchecked
         * @param {event} the input event
         */
        async discardOptionalCloudProducts(event) {
            await this.$nextTick();
            if (!event.target.checked) {
                this.optionalCloud.splice(0, this.optionalCloud.length);
            }
        },
        /**
         * converts the days array to string separated by commas
         * @param {days} the days array
         */
        daysString(days) {
            return days?.map((day) => day.name).join(", ") ?? "";
        },
        /**
         * sets the slaToggled object value based on the index and type
         * @param {id} id of the product for which to toggle SLA dropdowns
         * @param {index} index of the product
         * @param {type} possible values (add, remove)
         */
        toggleSLADropdowns(id, index, type) {
            this.slaToggled[id] = type === "add";
            // if removed set the SLA values of the product back to default
            if (type === "remove") {
                this.ams[index]["slaLevelId"] = "";
                this.ams[index]["slaServiceTimeId"] = "";
            }
        },
        /**
         * calls the SLA infraturcture, SLA service times, SLA levels list APIs when AMS is checked
         */
        async fetchSLA() {
            if (!this.slaLevels?.data?.length) {
                await this.$store.dispatch("slaLevel/list");
            }
            if (!this.slaServiceTimes.length) {
                await this.$store.dispatch("slaServiceTimes/list");
            }
        },
        /**
         * when the offer is selected, the offer data is fetched using the offers show API
         * we set 'modelData' to the data received from the response
         * then we call the setData method from the offerMixin which sets the data in the form and the components
         * in the correct format
         */
        async offerSelected() {
            await this.$nextTick();
            if (!this.selectedOffer) return;
            this.$store.commit("showLoader", true);
            try {
                const response = await this.$store.dispatch(
                    "offers/show",
                    this.selectedOffer?.id
                );

                this.modelData = response?.data?.data ?? {};
                await this.setData();
            } catch (e) {
            } finally {
                this.$store.commit("showLoader", false);
            }
        },
        /**
         * removed the products from the relevant component array based on type
         * @param {index} index of the product to be removed
         * @param {type} type of component, licenses, maintenance etc
         */
        removeOption(index, type) {
            if (type === "license" || type === "maintenance") {
                this.softwareLicences.splice(index, 1);
                this.softwareMaintenance.splice(index, 1);
            } else if (type === "service") {
                this.services.splice(index, 1);
            } else if (type === "categories") {
                this.categories.splice(index, 1);
            } else if (type === "ams") {
                this.ams.splice(index, 1);
            } else if (type === "hosting") {
                this.hosting.splice(index, 1);
            } else if (type === "cloud") {
                this.cloud.splice(index, 1);
            } else if (
                type === "optionalLicense" ||
                type === "optionalMaintenance"
            ) {
                this.optionalSoftwareLicenses.splice(index, 1);
                this.optionalSoftwareMaintenance.splice(index, 1);
            } else if (type === "optionalAms") {
                this.optionalAms.splice(index, 1);
            } else if (type === "optionalHosting") {
                this.optionalHosting.splice(index, 1);
            } else if (type === "optionalCloud") {
                this.optionalCloud.splice(index, 1);
            } else if (type === "optionalService") {
                this.optionalServices.splice(index, 1);
            } else if (type === "optionalCategories") {
                this.optionalCategories.splice(index, 1);
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
         * fetches the company employees related to the selected company
         */
        async fetchCompanyLeadEmployees(update = false) {
            await this.$nextTick();
            return new Promise(async (resolve, reject) => {
                try {
                    if (update) {
                        this.form.externalOrderNumber =
                            this.form.company?.externalOrderNumber ?? "";
                    }
                    this.form.contactPerson = ""; // reset the contact person when the customer changes
                    await this.$store.dispatch("companyEmployees/list", {
                        limit_start: 0,
                        limit_count: 25,
                        type: this.form.receiverType,
                        company_id: this.form.company?.id,
                    });
                    resolve();
                } catch (e) {
                    console.log(e);
                    reject(e);
                }
            });
        },
        /**
         * triggers the offers show API
         * sets the modelData from the data received from the API
         * @returns {Promise}
         */
        async fetchOfferData() {
            return new Promise(async (resolve, reject) => {
                try {
                    const response = await this.$store.dispatch(
                        `${
                            this.module === "offer"
                                ? "offers"
                                : "orderConfirmation"
                        }/show`,
                        this.$route.params.id
                    );
                    this.modelData = response?.data?.data ?? {};
                    this.isVisibleForCustomer =
                        response?.data?.data?.isVisibleForCustomer;
                    console.log(this.isVisibleForCustomer);
                    console.log(response?.data?.data?.isVisibleForCustomer);

                    document.title =
                        this.module === "offer"
                            ? "Edit Offer " + this.modelData.offerNumber
                            : "Edit Offer Confirmation " +
                              this.modelData.offerNumber;
                    if (
                        this.modelData?.offerType === "order" &&
                        this.modelData?.parentOffer
                    ) {
                        this.selectedOffer = this.modelData?.parentOffer ?? "";
                    }
                    resolve();
                } catch (e) {
                    reject(e);
                }
            });
        },
        /**
         * changes the payment period field of all the products of the selected 'type' to the global period
         * @param {type} product type e.g ams, hosting, cloud
         */
        async updatePeriodOfAllProducts(type) {
            await this.$nextTick();
            if (type === "ams") {
                // update all ams products' payment period to global period
                this.ams = this.ams.map((product) => {
                    return {
                        ...product,
                        paymentPeriod: this.globalPeriodAMS,
                    };
                });
            } else if (type === "cloud") {
                // update all cloud products' payment period to global period
                this.cloud = this.cloud.map((product) => {
                    return {
                        ...product,
                        paymentPeriod: this.globalPeriodCloud,
                    };
                });
            } else if (type === "hosting") {
                // update all hosting products' payment period to global period
                this.hosting = this.hosting.map((product) => {
                    return {
                        ...product,
                        paymentPeriod: this.globalPeriodHosting,
                    };
                });
            }
        },
        async cloudNettoTotalChanged(event, index, optional = false) {
            await this.$nextTick();
            const product = !optional
                ? this.cloud[index]
                : this.optionalCloud[index];
            const salePrice =
                (100 * product.nettoTotal) /
                (100 * product.quantity - product.discount * product.quantity);
            const taxRate = (+product.nettoTotal * +product.tax) / 100;
            product.taxRate = taxRate.toFixed(2);
            product.salePrice = salePrice.toFixed(2);
            if (!optional) this.cloud[index] = { ...product };
            else this.optionalCloud[index] = { ...product };
        },
        async changedQuantityCloud(index, event, optional = false, childIndex) {
            await this.$nextTick();
            const product = !optional
                ? typeof childIndex === "number"
                    ? this.cloud[index].children[childIndex]
                    : this.cloud[index]
                : this.optionalCloud[index];
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
                if (!optional) this.cloud[index] = { ...product };
                else this.optionalCloud[index] = { ...product };
            }
        },
        async hostingNettoTotalChanged(event, index, optional = false) {
            await this.$nextTick();
            const product = !optional
                ? this.hosting[index]
                : this.optionalHosting[index];
            const pricePerPeriod =
                (100 * product.nettoTotal) /
                (100 * product.quantity - product.discount * product.quantity);
            const taxRate = (+product.nettoTotal * +product.tax) / 100;
            product.taxRate = taxRate.toFixed(2);
            product.pricePerPeriod = pricePerPeriod.toFixed(2);
            if (!optional) {
                this.hosting[index] = { ...product };
            } else {
                this.optionalHosting[index] = { ...product };
            }
        },
        async changedQuantityHosting(
            index,
            event,
            optional = false,
            childIndex
        ) {
            await this.$nextTick();
            const product = !optional
                ? typeof childIndex === "number"
                    ? this.hosting[index].children[childIndex]
                    : this.hosting[index]
                : this.optionalHosting[index];
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
                if (!optional) {
                    this.hosting[index] = { ...product };
                } else {
                    this.optionalHosting[index] = { ...product };
                }
            }
        },
        /**
         * sets the productType and toggles the products modal
         * calls the products API to load the relevant products based on type if not already loaded
         * @param {type} type of products that need to be selected e.g software, service, ams, hosting etc.
         */
        async toggleProductsModal(type) {
            try {
                const response = await this.$store.dispatch(
                    "products/productsWithQuantity",
                    {
                        type: [type, "pauschal"],
                        companyId: this.form.company?.id,
                    }
                );
                // sets the products from the API response
                this[`${this.productTypeEnums[type]}Products`] = response?.data
                    ?.products ?? {
                    data: [],
                    links: [],
                };
                this.productType = type; // sets the product type so that we can make changes to the select-products-modal based on the type
                if (type === "ams") {
                    this.amsToggle = true; // toggle the ams modal
                } else if (type === "service") {
                    this.serviceToggle = true; // toggle the services modal
                } else this.productToggle = true; // toggles the select-products-modal
            } catch (e) {
                console.log(e);
            }
        },
        async amsNettoTotalChanged(event, index, optional = false) {
            await this.$nextTick();
            const product = !optional
                ? this.ams[index]
                : this.optionalAms[index];
            const hourlyRate =
                (100 * product.nettoTotal) /
                (100 * product.quantity - product.discount * product.quantity);
            const taxRate = (+product.nettoTotal * +product.tax) / 100;
            product.taxRate = taxRate.toFixed(2);
            product.hourlyRate = hourlyRate.toFixed(2);
            if (!optional) {
                this.ams[index] = { ...product };
            } else {
                this.optionalAms[index] = { ...product };
            }
        },
        async changedQuantityAMS(index, event, optional = false) {
            await this.$nextTick();
            const product = !optional
                ? this.ams[index]
                : this.optionalAms[index];
            const totalWithoutDiscount =
                +product.quantity * +product.hourlyRate;
            const discountAmount =
                (+totalWithoutDiscount * +product.discount) / 100;
            const nettoTotal = +totalWithoutDiscount - +discountAmount;
            const taxRate = (+nettoTotal * +product.tax) / 100;
            product.nettoTotal = nettoTotal.toFixed(2);
            product.taxRate = taxRate.toFixed(2);
            if (!optional) {
                this.ams[index] = { ...product };
            } else {
                this.optionalAms[index] = { ...product };
            }
        },
        async addAMS(products) {
            this.amsToggle = false;
            this.show = false;
            this.globalPeriodAMS = products?.[0]?.paymentPeriod?.id ?? "";
            await this.$nextTick();
            this.show = true;
            const modifiedProducts = products.map((product) => {
                const modifiedProduct = { ...product };
                if (modifiedProduct.type === "pauschal") {
                    modifiedProduct.hourlyRate = product.salePrice;
                    modifiedProduct.quantity = product.quanity;
                }
                const totalWithoutDiscount =
                    +modifiedProduct.hourlyRate * +modifiedProduct.quantity;
                const discountAmount =
                    (+totalWithoutDiscount * +modifiedProduct.discount) / 100;
                const nettoTotal = +totalWithoutDiscount - +discountAmount;
                const taxRate = (+nettoTotal * +modifiedProduct.tax) / 100;
                return {
                    ...modifiedProduct,
                    paymentPeriod: modifiedProduct?.paymentPeriod?.id,
                    nettoTotal: nettoTotal,
                    taxRate: taxRate,
                    slaServiceTimeId: modifiedProduct?.slaServiceTimeId ?? "",
                    slaLevelId: modifiedProduct?.slaLevelId ?? "",
                    additionalDescription:
                        modifiedProduct?.additionalDescription ?? "",
                };
            });
            // if optionalAmsProducts is true then add the selected products to optionalAms
            if (this.optionalAmsProducts) {
                this.optionalAms = [...this.optionalAms, ...modifiedProducts];
            } else {
                this.ams = [...this.ams, ...modifiedProducts];
            }
        },
        async resetProductsUponUncheck(event, type) {
            await this.$nextTick();
            if (!event.target.checked) {
                if (
                    type === "softwareLicenses" ||
                    type === "softwareMaintenance"
                ) {
                    this.softwareLicences.splice(
                        0,
                        this.softwareLicences.length
                    );
                    this.optionalSoftwareLicenses.splice(
                        0,
                        this.optionalSoftwareLicenses.length
                    );
                    this.softwareMaintenance.splice(
                        0,
                        this.softwareMaintenance.length
                    );
                    this.optionalSoftwareMaintenance.splice(
                        0,
                        this.optionalSoftwareMaintenance.length
                    );
                } else if (type === "services") {
                    this.services.splice(0, this.services.length);
                    this.optionalServices.splice(
                        0,
                        this.optionalServices.length
                    );
                    this.categories.splice(0, this.categories.length);
                    this.optionalCategories.splice(
                        0,
                        this.optionalCategories.length
                    );
                } else if (type === "application") {
                    this.ams.splice(0, this.ams.length);
                    this.optionalAms.splice(0, this.optionalAms.length);
                } else if (type === "hosting") {
                    this.hosting.splice(0, this.hosting.length);
                    this.optionalHosting.splice(0, this.optionalHosting.length);
                } else if (type === "cloud-software") {
                    this.cloud.splice(0, this.cloud.length);
                    this.optionalCloud.splice(0, this.optionalCloud.length);
                }
            }
        },
        /**
         * creates categories from the services
         * @param {services} the services to be categorized
         */
        createCategories(services) {
            return Array.from(
                new Set(services.map((service) => service.category.id))
            ).map((categoryId) => {
                const category =
                    services.find(
                        (service) => service.category.id === categoryId
                    )?.category ?? {};
                const products = services.filter(
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
                    products: products,
                    unit: unit,
                    quantity: quantity,
                    hourlyRate: hourlyRate,
                    discount: discount,
                    nettoTotal: nettoTotal,
                    tax: tax,
                    taxRate: taxRate,
                    bruttoTotal: +nettoTotal + +taxRate,
                    additionalDescription:
                        category?.additionalDescription ?? "",
                };
            });
        },
        computeServicesByCategory() {
            this.categories = this.createCategories(this.services);
            this.optionalCategories = this.createCategories(
                this.optionalServices
            );
        },
        async serviceQuantityChangedCategory(event, index, optional = false) {
            await this.$nextTick();
            const category = !optional
                ? this.categories[index]
                : this.optionalCategories[index];
            const totalWithoutDiscount =
                +category.quantity * +category.hourlyRate;
            const discountAmount =
                (+totalWithoutDiscount * +category.discount) / 100;
            const nettoTotal = +totalWithoutDiscount - +discountAmount;
            const taxRate = (+nettoTotal * +category.tax) / 100;
            category.nettoTotal = nettoTotal.toFixed(2);
            category.taxRate = taxRate.toFixed(2);
            category.bruttoTotal = (+nettoTotal + +taxRate).toFixed(2);
            if (!optional) {
                this.categories[index] = { ...category };
            } else {
                this.optionalCategories[index] = { ...category };
            }
        },
        async serviceNettoTotalChangedCategory(event, index, optional = false) {
            await this.$nextTick();
            const category = !optional
                ? this.categories[index]
                : this.optionalCategories[index];
            const hourlyRate =
                (100 * category.nettoTotal) /
                (100 * category.quantity -
                    category.discount * category.quantity);
            const taxRate = (+category.nettoTotal * +category.tax) / 100;
            category.taxRate = taxRate.toFixed(2);
            category.hourlyRate = hourlyRate.toFixed(2);
            category.bruttoTotal = (+category.nettoTotal + +taxRate).toFixed(2);
            if (!optional) {
                this.categories[index] = { ...category };
            } else {
                this.optionalCategories[index] = { ...category };
            }
        },
        async serviceTaxRateChangedCategory(event, index, optional = false) {
            await this.$nextTick();
            const category = !optional
                ? this.categories[index]
                : this.optionalCategories[index];
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
            if (!optional) {
                this.categories[index] = { ...category };
            } else {
                this.optionalCategories[index] = { ...category };
            }
        },
        async serviceQuantityChanged(
            event,
            index,
            optional = false,
            childIndex
        ) {
            await this.$nextTick();
            const service = !optional
                ? typeof childIndex === "number"
                    ? this.services[index].children[childIndex]
                    : this.services[index]
                : this.optionalServices[index];
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
                if (!optional) {
                    this.services[index] = { ...service };
                } else {
                    this.optionalServices[index] = { ...service };
                }
            }
        },
        async serviceNettoTotalChanged(event, index, optional = false) {
            await this.$nextTick();
            const service = !optional
                ? this.services[index]
                : this.optionalServices[index];
            const hourlyRate =
                (100 * service.nettoTotal) /
                (100 * service.quantity - service.discount * service.quantity);
            const taxRate = (+service.nettoTotal * +service.tax) / 100;
            service.taxRate = taxRate.toFixed(2);
            service.hourlyRate = hourlyRate.toFixed(2);
            service.bruttoTotal = (+service.nettoTotal + +taxRate).toFixed(2);
            if (!optional) {
                this.services[index] = { ...service };
            } else {
                this.optionalServices[index] = { ...service };
            }
        },
        async serviceTaxRateChanged(event, index, optional = false) {
            await this.$nextTick();
            const service = !optional
                ? this.services[index]
                : this.optionalServices[index];
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
            if (!optional) {
                this.services[index] = { ...service };
            } else {
                this.optionalServices[index] = { ...service };
            }
        },
        unitForCategory(category) {
            let units = Array.from(
                new Set(category.products.map((product) => product.unit))
            );
            return units.length > 1
                ? this.units?.data?.find?.(
                      (unit) => unit.id == category.default_unit
                  ) ?? units?.[0]
                : units?.[0];
        },
        calculateCategoryQuantity(products) {
            return products.reduce(
                (accumulator, product) => +accumulator + +product.quantity,
                0
            );
        },
        async update(buttonType, documentType, byPass = false) {
            const payload = {
                type: this.form.type,
                offerType: this.module,
                orderStatus: this.form.orderStatus,
                offerStatus: this.form.offerStatus,
                companyId: this.form.company?.id,
                projectId: this.form.project?.id,
                termId: this.form.term?.id,
                receiverType: this.form.receiverType,
                template: this.form.template,
                groupedBy: this.form.productsGroupedBy,
                expiryDate: this.form.expiryDate,
                paymentTerms: this.form.paymentTerms,
                createdBy:
                    buttonType === "copy"
                        ? this.authUser.id
                        : this.form.createdBy,
                coverLetterText: this.form.coverLetterText,
                offerDescriptionText: this.form.offerDescriptionText,
                contactPerson: this.form.contactPerson?.id ?? "",
                removeFromStatistics: this.form.removeFromStatistics,
                plannedStartDate: this.form.plannedStartDate,
                externalOrderNumber: this.form.externalOrderNumber,
                identifier: this.form.identifier,
                isVisibleForCustomer: this.isVisibleForCustomer,
                types: [],
                optionalTypes: [],
            };
            if (this.selectedOffer && this.module === "order") {
                payload["offerId"] = this.selectedOffer?.id ?? "";
            }
            payload.types = [
                ...this.ams.map((product) => {
                    return {
                        id: buttonType === "copy" ? null : product.componentId, // the original component id used for updating
                        type: "application",
                        additionalDescription:
                            product?.additionalDescription ?? "",
                        productId: product?.productId ?? product?.id,
                        salePrice: product.salePrice,
                        discount: product.discount,
                        quantity: product.quantity,
                        tax: product.tax,
                        hourlyRate: product.hourlyRate,
                        paymentPeriod:
                            this.periods?.data?.find(
                                (period) => period.id === product.paymentPeriod
                            ) ?? product.paymentPeriod,
                        productFullDetails: {
                            ...product,
                            slaServiceTimeId: product?.slaServiceTimeId?.id,
                            slaLevelId: product?.slaLevelId?.id ?? "",
                        },
                    };
                }),
                ...this.hosting.map((product) => {
                    return {
                        id: buttonType === "copy" ? null : product.componentId, // the original component id used for updating
                        type: "hosting",
                        additionalDescription:
                            product?.additionalDescription ?? "",
                        productId: product?.productId ?? product?.id,
                        discount: product.discount,
                        quantity: product.quantity,
                        tax: product.tax,
                        pricePerPeriod: product.pricePerPeriod,
                        paymentPeriod:
                            this.periods?.data?.find(
                                (period) => period.id === product.paymentPeriod
                            ) ?? product.paymentPeriod,
                        productFullDetails: { ...product },
                        children: (product.children ?? []).map((child) => {
                            return {
                                ...child,
                                id: buttonType === "copy" ? null : child.id,
                                productId: child?.productId ?? child?.id,
                            };
                        }),
                    };
                }),
                ...this.cloud.map((product) => {
                    return {
                        id: buttonType === "copy" ? null : product.componentId, // the original component id used for updating
                        type: "cloud",
                        additionalDescription:
                            product?.additionalDescription ?? "",
                        productId: product?.productId ?? product?.id,
                        discount: product.discount,
                        quantity: product.quantity,
                        tax: product.tax,
                        salePrice: product.salePrice,
                        paymentPeriod:
                            this.periods?.data?.find(
                                (period) => period.id === product.paymentPeriod
                            ) ?? product.paymentPeriod,
                        duration: product.duration,
                        productFullDetails: { ...product },
                        children: (product.children ?? []).map((child) => {
                            return {
                                ...child,
                                id: buttonType === "copy" ? null : child?.id,
                                productId: child?.productId ?? child?.id,
                            };
                        }),
                    };
                }),
                ...this.softwareLicences.map((product) => {
                    return {
                        id: buttonType === "copy" ? null : product.componentId, // the original component id used for updating
                        type: "license",
                        additionalDescription:
                            product?.additionalDescription ?? "",
                        productId: product?.productId ?? product?.id,
                        salePrice: product.salePrice,
                        discount: product.discount,
                        quantity: product.quantity,
                        tax: product.tax,
                        maintenanceRate: product.maintenanceRate,
                        productFullDetails: { ...product },
                    };
                }),
                ...this.softwareMaintenance.map((product) => {
                    return {
                        id: buttonType === "copy" ? null : product.componentId, // the original component id used for updating
                        additionalDescription:
                            product?.additionalDescription ?? "",
                        type: "maintenance",
                        productId: product?.productId ?? product?.id,
                        salePrice: product.salePrice,
                        discount: product.discount,
                        quantity: product.quantity,
                        tax: product.tax,
                        maintenanceRate: product.maintenanceRate,
                        productFullDetails: { ...product },
                    };
                }),
            ];
            payload.optionalTypes = [
                ...this.optionalAms.map((product) => {
                    return {
                        id:
                            buttonType === "copy"
                                ? null
                                : this.optionalComponentIdsMappedToProductIds[
                                      "ams"
                                  ][product?.productId ?? product?.id],
                        type: "application",
                        productId: product?.productId ?? product?.id,
                        salePrice: product.salePrice,
                        discount: product.discount,
                        quantity: product.quantity,
                        tax: product.tax,
                        hourlyRate: product.hourlyRate,
                        paymentPeriod:
                            this.periods?.data?.find(
                                (period) => period.id === product.paymentPeriod
                            ) ?? product.paymentPeriod,
                        productFullDetails: {
                            ...product,
                            slaServiceTimeId: product?.slaServiceTimeId?.id,
                            slaLevelId: product?.slaLevelId?.id ?? "",
                        },
                    };
                }),
                ...this.optionalCloud.map((product) => {
                    return {
                        id:
                            buttonType === "copy"
                                ? null
                                : this.optionalComponentIdsMappedToProductIds[
                                      "cloud"
                                  ][product?.productId ?? product?.id],
                        type: "cloud",
                        productId: product?.productId ?? product?.id,
                        discount: product.discount,
                        quantity: product.quantity,
                        tax: product.tax,
                        salePrice: product.salePrice,
                        paymentPeriod:
                            this.periods?.data?.find(
                                (period) => period.id === product.paymentPeriod
                            ) ?? product.paymentPeriod,
                        duration: product.duration,
                        productFullDetails: { ...product },
                    };
                }),
                ...this.optionalHosting.map((product) => {
                    return {
                        id:
                            buttonType === "copy"
                                ? null
                                : this.optionalComponentIdsMappedToProductIds[
                                      "hosting"
                                  ][product?.productId ?? product?.id],
                        type: "hosting",
                        productId: product?.productId ?? product?.id,
                        discount: product.discount,
                        quantity: product.quantity,
                        tax: product.tax,
                        pricePerPeriod: product.pricePerPeriod,
                        paymentPeriod:
                            this.periods?.data?.find(
                                (period) => period.id === product.paymentPeriod
                            ) ?? product.paymentPeriod,
                        productFullDetails: { ...product },
                    };
                }),
                ...this.optionalSoftwareLicenses.map((product) => {
                    return {
                        type: "license",
                        productId: product?.productId ?? product?.id,
                        salePrice: product.salePrice,
                        discount: product.discount,
                        quantity: product.quantity,
                        tax: product.tax,
                        maintenanceRate: product.maintenanceRate,
                        productFullDetails: { ...product },
                    };
                }),
                ...this.optionalSoftwareMaintenance.map((product) => {
                    return {
                        type: "maintenance",
                        productId: product?.productId ?? product?.id,
                        salePrice: product.salePrice,
                        discount: product.discount,
                        quantity: product.quantity,
                        tax: product.tax,
                        maintenanceRate: product.maintenanceRate,
                        productFullDetails: { ...product },
                    };
                }),
            ];
            if (this.form.productsGroupedBy === "nothing") {
                this.services.forEach((service) => {
                    payload.types.push({
                        ...service,
                        id:
                            buttonType === "copy"
                                ? null
                                : this.componentIdsMappedToProductIds[
                                      "services"
                                  ][service.id],
                        additionalDescription:
                            service?.additionalDescription ?? "",
                        productId: service?.productId ?? service.id,
                        type: "service",
                        children: service.children.map((child) => {
                            return {
                                ...child,
                                id: buttonType === "copy" ? null : child.id,
                                productId: child?.productId ?? child.id,
                            };
                        }),
                    });
                });
                this.optionalServices.forEach((service) => {
                    payload.optionalTypes.push({
                        ...service,
                        id:
                            buttonType === "copy"
                                ? null
                                : this.optionalComponentIdsMappedToProductIds[
                                      "services"
                                  ][service.id],
                        productId: service?.productId ?? service.id,
                        type: "service",
                    });
                });
            } else {
                this.categories.forEach((category) => {
                    payload.types.push({
                        ...category,
                        type: "service",
                        id:
                            buttonType === "copy"
                                ? null
                                : this.componentIdsMappedToProductIds[
                                      "services"
                                  ][
                                      category.products?.[0]?.product?.id ??
                                          category.products?.[0]?.id
                                  ],
                        additionalDescription:
                            category?.additionalDescription ?? "",
                        productFullDetails: category.products,
                        productId:
                            category.products?.[0]?.product?.id ??
                            category.products?.[0]?.id,
                        productCategoryId: category.id,
                        products: category.products.map(
                            (product) => product?.product?.id ?? product.id
                        ),
                    });
                });
                this.optionalCategories.forEach((category) => {
                    payload.optionalTypes.push({
                        ...category,
                        type: "service",
                        id:
                            buttonType === "copy"
                                ? null
                                : this.optionalComponentIdsMappedToProductIds[
                                      "services"
                                  ][
                                      category.products?.[0]?.product?.id ??
                                          category.products?.[0]?.id
                                  ],
                        productFullDetails: category.products,
                        productId:
                            category.products?.[0]?.product?.id ??
                            category.products?.[0]?.id,
                        productCategoryId: category.id,
                        products: category.products.map(
                            (product) => product?.product?.id ?? product.id
                        ),
                    });
                });
            }

            if (buttonType === "update") {
                try {
                    this.$store.commit("isLoading", true);
                    await this.$store.dispatch(
                        `${
                            this.module === "offer"
                                ? "offers"
                                : "orderConfirmation"
                        }/update`,
                        {
                            id: this.$route.params.id,
                            data: payload,
                        }
                    );
                    if (!byPass) {
                        this.$store.dispatch(
                            `${
                                this.module === "offer"
                                    ? "offers"
                                    : "orderConfirmation"
                            }/list`,
                            {
                                offerType: this.module,
                            }
                        );
                        this.$router.push(
                            `/${
                                this.module === "offer"
                                    ? "offers?page=" + this.returnPage
                                    : "order-confirmation?page=" +
                                      this.returnPage
                            }`
                        );
                    }
                } catch (e) {
                    console.log(e);
                }
            } else if (buttonType === "generate") {
                this.generateProcessTemplate(payload, documentType);
            } else if (buttonType === "copy") {
                try {
                    this.$store.commit("isLoading", true);
                    await this.$store.dispatch("offers/create", {
                        ...payload,
                        offerStatus: "entwurf",
                    });
                    this.$store.dispatch("offers/list", {
                        offerType: this.module,
                    });
                    this.$router.push("/offers?page=" + this.returnPage);
                } catch (e) {
                    console.log(e);
                }
            }
        },
        async generateProcessTemplate(payload, documentType) {
            this.$store.commit("isLoading", true);
            //Get template id by name
            const templateId = await this.$store.dispatch(
                "globalSettings/getTemplateByName",
                this.module === "offer"
                    ? "offerTemplateId"
                    : "orderConfirmationTemplateId"
            );

            //Create template payload and process template
            if (templateId != "") {
                const templatePayload = {
                    ...payload,
                    types: payload.types.map((type) => {
                        // if groupedBy is set to 'category' then map the 'productFullDetails' array onto 'products'
                        if (
                            type.type === "service" &&
                            payload.groupedBy === "category"
                        ) {
                            return {
                                ...type,
                                products: [...type.productFullDetails],
                            };
                        } else {
                            return type;
                        }
                    }),
                    id: templateId,
                    userFirstName: this.user.first_name,
                    userLastName: this.user.last_name,
                    userEmail: this.user.email,
                    userPhone: this.user.mobile,
                    company: this.form.company,
                    offerNumber: this.form.offerNumber,
                    output: documentType,
                    contactPerson:
                        (this.form.contactPerson?.first_name ?? "") +
                        " " +
                        (this.form.contactPerson?.last_name ?? ""),
                    updatedTime: Date.now(),
                    offer: this.selectedOffer,
                };

                this.$store.commit("isLoading", true);
                const response = await this.$store.dispatch(
                    "documentService/processTemplate",
                    {
                        data: templatePayload,
                        filename:
                            // if the module is "offer" then the file name starts with 'offers', otherwise it starts with 'offer-confirmation'
                            `${
                                this.module === "offer"
                                    ? "offers"
                                    : "offer-confirmation"
                            }-` +
                            this.form.offerNumber +
                            `.${documentType}`,
                        documentType: documentType,
                    }
                );
                // check if the response of the process template is of type Blob
                if (response instanceof Blob) {
                    // convert blob to base64
                    const base64 = await this.convertBlobToBase64(response);
                    const eloRequestDataResponse = await this.$store.dispatch(
                        "offers/eloRequestData",
                        this.$route.params.id
                    );
                    // send the elo request
                    await this.$store.dispatch(
                        "globalSettings/sendEloApiRequest",
                        {
                            content: {
                                moduleAction: `updatePDF${
                                    this.module === "offer"
                                        ? "Offer"
                                        : "OfferConfirmation"
                                }`,
                                data: {
                                    ...(eloRequestDataResponse?.data?.data ??
                                        {}),
                                    base64:
                                        (base64 ?? "").split(";base64,")?.[1] ??
                                        "",
                                },
                            },
                        }
                    );
                }
                await this.update("update", "", true);
            }
        },
        addServices(products) {
            const modifiedProducts = products.map((product) => {
                const totalWithoutDiscount =
                    +product.quantity *
                    +(product.type === "pauschal"
                        ? product.salePrice
                        : product.hourlyRate);
                const discountAmount =
                    (+totalWithoutDiscount * +product.discount) / 100;
                const nettoTotal = +totalWithoutDiscount - +discountAmount;
                const taxRate = (+nettoTotal * +product.tax) / 100;
                return {
                    ...product,
                    tax: +product.tax,
                    hourlyRate:
                        product.type === "pauschal"
                            ? product.salePrice
                            : product.hourlyRate,
                    nettoTotal: nettoTotal,
                    taxRate: taxRate,
                    bruttoTotal: +nettoTotal + +taxRate,
                    children: product.children ?? [],
                    additionalDescription: product?.additionalDescription ?? "",
                    totalRate:
                        +product.quantity *
                        +(product.type === "pauschal"
                            ? product.salePrice
                            : product.hourlyRate),
                };
            });
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
                this.services[this.serviceIndex].taxRate = taxRateInner;
                this.serviceIndex = null;
                this.addServiceChildren = false;
            } else {
                if (this.optionalServicesProducts) {
                    this.optionalServices = [
                        ...this.optionalServices,
                        ...modifiedProducts,
                    ];
                } else {
                    this.services = [...this.services, ...modifiedProducts];
                }
            }
            this.computeServicesByCategory();
            this.serviceToggle = false;
        },
        addProducts(products) {
            if (this.productType === "hosting") {
                this.globalPeriodHosting =
                    products?.[0]?.paymentPeriod?.id ?? "";
                const modifiedProducts = products.map((product) => {
                    const modifiedProduct = { ...product };
                    if (modifiedProduct.type === "pauschal") {
                        modifiedProduct.pricePerPeriod = product.salePrice;
                    }
                    const totalWithoutDiscount =
                        +modifiedProduct.quantity *
                        +modifiedProduct.pricePerPeriod;
                    const discountAmount =
                        (+totalWithoutDiscount * +modifiedProduct.discount) /
                        100;
                    const nettoTotal = +totalWithoutDiscount - +discountAmount;
                    const taxRate = (+nettoTotal * +modifiedProduct.tax) / 100;
                    return {
                        ...modifiedProduct,
                        paymentPeriod: modifiedProduct?.paymentPeriod?.id,
                        nettoTotal: nettoTotal,
                        taxRate: taxRate,
                        children: modifiedProduct.children ?? [],
                        additionalDescription:
                            modifiedProduct?.additionalDescription ?? "",
                        totalPricePeriod:
                            +modifiedProduct.quantity *
                            +modifiedProduct.pricePerPeriod,
                    };
                });
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
                    if (this.optionalHostingProducts) {
                        this.optionalHosting = [
                            ...this.optionalHosting,
                            ...modifiedProducts,
                        ];
                    } else {
                        this.hosting = [...this.hosting, ...modifiedProducts];
                    }
                }
            } else if (this.productType === "cloud-software") {
                this.globalPeriodCloud = products?.[0]?.paymentPeriod?.id ?? "";
                const modifiedProducts = products.map((product) => {
                    const totalWithoutDiscount =
                        +product.quantity *
                        +(product.duration ?? 1) *
                        +product.salePrice;
                    const discountAmount =
                        (+totalWithoutDiscount * +product.discount) / 100;
                    const nettoTotal = +totalWithoutDiscount - +discountAmount;
                    const taxRate = (+nettoTotal * +product.tax) / 100;
                    return {
                        ...product,
                        paymentPeriod: product?.paymentPeriod?.id,
                        nettoTotal: nettoTotal,
                        duration: +(product.duration ?? 1),
                        taxRate: taxRate,
                        children: product.children ?? [],
                        additionalDescription:
                            product?.additionalDescription ?? "",
                        totalPricePeriod:
                            +product.quantity *
                            +(product.duration ?? 1) *
                            +product.salePrice,
                    };
                });
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
                    if (this.optionalCloudProducts) {
                        this.optionalCloud = [
                            ...this.optionalCloud,
                            ...modifiedProducts,
                        ];
                    } else {
                        this.cloud = [...this.cloud, ...modifiedProducts];
                    }
                }
            } else if (this.productType === "software") {
                if (this.optionalSoftwareProducts) {
                    this.optionalSoftwareLicenses = [
                        ...this.optionalSoftwareLicenses,
                        ...products,
                    ];
                    this.optionalSoftwareLicenses.forEach((item, index) => {
                        this.calculateProductValue(
                            index,
                            "optionalSoftwareLicenses"
                        );
                    });
                    this.optionalSoftwareMaintenance = [
                        ...this.optionalSoftwareMaintenance,
                        ...JSON.parse(JSON.stringify(products))?.map(
                            (product) => {
                                return {
                                    ...product,
                                    maintenanceRate:
                                        product?.maintenanceRate ?? 0,
                                    totalSalePriceAdjustedForQuantity:
                                        +product.salePrice * +product.quantity,
                                };
                            }
                        ),
                    ];
                    this.optionalSoftwareMaintenance.forEach((item, index) => {
                        this.calculateProductValue(
                            index,
                            "optionalSoftwareMaintenance"
                        );
                    });
                } else {
                    this.softwareLicences = [
                        ...this.softwareLicences,
                        ...products,
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
                            additionalDescription:
                                product?.additionalDescription ?? "",
                        };
                    });
                    this.softwareMaintenance = [
                        ...this.softwareMaintenance,
                        ...JSON.parse(JSON.stringify(products))?.map(
                            (product) => {
                                return {
                                    ...product,
                                    maintenanceRate:
                                        product?.maintenanceRate ?? 0,
                                    totalSalePriceAdjustedForQuantity:
                                        +product.salePrice * +product.quantity,
                                    additionalDescription:
                                        product?.additionalDescription ?? "",
                                };
                            }
                        ),
                    ];
                    this.softwareMaintenance.forEach((item, index) => {
                        this.calculateProductValue(
                            index,
                            "softwareMaintenance"
                        );
                    });
                    this.softwareMaintenanceTax = this.calculateTax(
                        "softwareMaintenance"
                    );
                }
            }
            this.productToggle = false;
        },
        calculateProductValue(index, type, fieldChanged, event) {
            let products = [];

            if (type === "softwareLicences") {
                products = this.softwareLicences;
            } else if (type === "softwareMaintenance") {
                products = this.softwareMaintenance;
            } else if (type === "optionalSoftwareMaintenance") {
                products = this.optionalSoftwareMaintenance;
            } else if (type === "optionalSoftwareLicenses") {
                products = this.optionalSoftwareLicenses;
            }
            // if the totalSalePriceAdjustedForQuantity in softwareMaintenance is modified than update the original salePrice, since totalSalePriceAdjustedForQuantity is dependant on salePrice
            if (event?.target?.name === "totalSalePriceAdjustedForQuantity") {
                products[index].salePrice =
                    +products[index].totalSalePriceAdjustedForQuantity /
                    products[index].quantity;
            }
            products[index].totalPrice =
                products[index].salePrice * products[index].quantity;
            products[index].totalSalePriceAdjustedForQuantity =
                products[index].salePrice * products[index].quantity;
            if (products[index]?.discount) {
                products[index].totalPrice =
                    products[index].totalPrice *
                    (1 - products[index]?.discount / 100);
            }
            if (
                type === "softwareLicences" ||
                type === "optionalSoftwareLicenses"
            ) {
                if (
                    !fieldChanged ||
                    event?.target?.name === "salePrice" ||
                    event?.target?.name === "quantity" ||
                    event?.target?.name === "discount"
                ) {
                    products[index].priceWithoutTax =
                        products[index].totalPrice.toFixed(2);
                } else {
                    this[
                        type === "softwareLicences"
                            ? "softwareLicences"
                            : "optionalSoftwareLicenses"
                    ][index].salePrice = (
                        this[
                            type === "softwareLicences"
                                ? "softwareLicences"
                                : "optionalSoftwareLicenses"
                        ][index].priceWithoutTax /
                        (1 -
                            this[
                                type === "softwareLicences"
                                    ? "softwareLicences"
                                    : "optionalSoftwareLicenses"
                            ][index].discount /
                                100) /
                        this[
                            type === "softwareLicences"
                                ? "softwareLicences"
                                : "optionalSoftwareLicenses"
                        ][index].quantity
                    ).toFixed(2);
                }
            } else {
                if (
                    event?.target?.name !== "maintenancePrice" &&
                    event?.target?.name !== "priceWithoutTax"
                ) {
                    this[
                        type === "softwareMaintenance"
                            ? "softwareMaintenance"
                            : "optionalSoftwareMaintenance"
                    ][index].maintenancePrice = (
                        (this[
                            type === "softwareMaintenance"
                                ? "softwareMaintenance"
                                : "optionalSoftwareMaintenance"
                        ][index].totalSalePriceAdjustedForQuantity *
                            this[
                                type === "softwareMaintenance"
                                    ? "softwareMaintenance"
                                    : "optionalSoftwareMaintenance"
                            ][index].maintenanceRate) /
                        100
                    ).toFixed(2);
                } else if (event?.target?.name !== "maintenancePrice") {
                    products[index].maintenancePrice =
                        parseFloat(
                            products[index].totalSalePriceAdjustedForQuantity
                        ).toFixed(2) * products[index].quantity;
                }
                // if maintenancePrice is changed
                else if (event?.target?.name === "maintenancePrice") {
                    // calculate the totalSalePriceAdjustedForQuantity for softwareMaintenance based on the changed maintenancePrice
                    this[
                        type === "softwareMaintenance"
                            ? "softwareMaintenance"
                            : "optionalSoftwareMaintenance"
                    ][index].totalSalePriceAdjustedForQuantity = (
                        (+this[
                            type === "softwareMaintenance"
                                ? "softwareMaintenance"
                                : "optionalSoftwareMaintenance"
                        ][index].maintenancePrice *
                            100) /
                        +this[
                            type === "softwareMaintenance"
                                ? "softwareMaintenance"
                                : "optionalSoftwareMaintenance"
                        ][index].maintenanceRate
                    ).toFixed(2);
                }
                this.$nextTick(() => {
                    products[index].priceWithoutTax =
                        (parseFloat(products[index].maintenancePrice).toFixed(
                            2
                        ) *
                            +products[index].quantity *
                            products[index].maintenanceRate) /
                        100;
                });
            }
            if (products[index]?.tax) {
                products[index].totalPrice =
                    products[index].totalPrice *
                    (products[index]?.tax / 100 + 1);
            }

            products[index].totalPrice = products[index].totalPrice.toFixed(2);

            if (type === "softwareLicences")
                this.softwareLicencesTax = this.calculateTax(type);
            else this.softwareMaintenanceTax = this.calculateTax(type);
            if (fieldChanged) {
                if (
                    type === "softwareLicences" ||
                    type === "optionalSoftwareLicenses"
                ) {
                    this[
                        type === "softwareLicences"
                            ? "softwareMaintenance"
                            : "optionalSoftwareMaintenance"
                    ][index].quantity =
                        this[
                            type === "softwareLicences"
                                ? "softwareLicences"
                                : "optionalSoftwareLicenses"
                        ][index].quantity;
                    this[
                        type === "softwareLicences"
                            ? "softwareMaintenance"
                            : "optionalSoftwareMaintenance"
                    ][index].salePrice =
                        this[
                            type === "softwareLicences"
                                ? "softwareLicences"
                                : "optionalSoftwareLicenses"
                        ][index].salePrice;
                    this[
                        type === "softwareLicences"
                            ? "softwareMaintenance"
                            : "optionalSoftwareMaintenance"
                    ][index].maintenancePrice = (
                        (this[
                            type === "softwareLicences"
                                ? "softwareLicences"
                                : "optionalSoftwareLicenses"
                        ][index].totalSalePriceAdjustedForQuantity *
                            this[
                                type === "softwareLicences"
                                    ? "softwareLicences"
                                    : "optionalSoftwareLicenses"
                            ][index].maintenanceRate) /
                        100
                    ).toFixed(2);
                    this.$nextTick(() => {
                        this.calculateProductValue(
                            index,
                            "softwareMaintenance"
                        );
                    });
                } else {
                    if (event.target.name === "maintenanceRate") {
                        this[
                            type === "softwareMaintenance"
                                ? "softwareMaintenance"
                                : "optionalSoftwareMaintenance"
                        ][index].maintenancePrice = (
                            (this[
                                type === "softwareMaintenance"
                                    ? "softwareMaintenance"
                                    : "optionalSoftwareMaintenance"
                            ][index].totalSalePriceAdjustedForQuantity *
                                this[
                                    type === "softwareMaintenance"
                                        ? "softwareMaintenance"
                                        : "optionalSoftwareMaintenance"
                                ][index].maintenanceRate) /
                            100
                        ).toFixed(2);
                    }
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
    },
    components: {
        PageHeader,
        SelectAmsModal,
        SelectInput,
        SelectProductModal,
        SelectServiceModal,
        MultiSelectInput,
        TextAreaInput,
        NumberInput,
        LoadingButton,
        TextInput,
    },
};
</script>

<style lang="scss" scoped>
li {
    list-style: none;
}
.tabs {
    overflow: hidden;
}

.tab {
    width: 100%;
    color: black;
    overflow: hidden;
    &-label {
        display: flex;
        justify-content: space-between;
        cursor: pointer;
    }
    &-content {
        display: none;
        max-height: 0;
        color: black;
        transition: all 0.35s;
    }
    &-close {
        display: flex;
        justify-content: flex-end;
        font-size: 0.75em;
        cursor: pointer;
        &:hover {
        }
    }
}
.styles-configurator-tab-label::after {
    margin-top: 0px !important;
}

// :checked
input:checked {
    ~ .tab-content {
        display: block;
        max-height: 100vh;
    }
}

.inner-accordion-label::after {
    transform: rotate(90deg);
    transform-origin: center;
}

input[class="tab-checkbox"] {
    position: absolute;
    opacity: 0;
    z-index: -1;
}

.inner-accordion {
    display: none;
}
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

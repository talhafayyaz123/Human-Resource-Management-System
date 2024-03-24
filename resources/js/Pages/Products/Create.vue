<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />


        <div class="tab-header">
            <ul class="">
                <li class="nav-item">
                    <a
                        class="nav-link"
                        :class="
                            activeTab === 'products'
                                ? activeClasses
                                : inactiveClasses
                        "
                        @click="activeTab = `products`"
                    >
                        {{ $t("Products") }}
                    </a>
                </li>
            <!--     <li
                    class="nav-item"
                    v-if="
                        form.paymentDetailsType === 'software' ||
                        form.paymentDetailsType === 'cloud-software'
                    "
                >
                    <a
                        @click="activeTab = `dependencies`"
                        class="nav-link"
                        :class="
                            activeTab === 'dependencies'
                                ? activeClasses
                                : inactiveClasses +
                                  ' ' +
                                  (form?.versions?.length ? '' : 'disabled')
                        "
                    >
                        {{ $t("Dependencies") }}
                    </a>
                </li> -->
            <!--     <li
                    class="nav-item"
                    v-if="
                        form.paymentDetailsType === 'software' ||
                        form.paymentDetailsType === 'cloud-software' ||
                        form.paymentDetailsType === 'hosting'
                    "
                >
                    <a
                        class="nav-link"
                        :class="
                            activeTab === 'install'
                                ? activeClasses
                                : inactiveClasses +
                                  ' ' +
                                  (form?.versions?.length ? '' : 'disabled')
                        "
                        @click="activeTab = `install`"
                    >
                        {{ $t("Install Routines") }}
                    </a>
                </li> -->
              <!--   <li
                    class="nav-item"
                    v-if="
                        form.paymentDetailsType === 'software' ||
                        form.paymentDetailsType === 'cloud-software' ||
                        form.paymentDetailsType === 'hosting'
                    "
                >
                    <a
                        @click="activeTab = `uninstall`"
                        class="nav-link"
                        :class="
                            activeTab === 'uninstall'
                                ? activeClasses
                                : inactiveClasses +
                                  ' ' +
                                  (form?.versions?.length ? '' : 'disabled')
                        "
                    >
                        {{ $t("Uninstall Routines") }}
                    </a>
                </li> -->
                <li class="nav-item" v-if="form.paymentDetailsType !== 'ams'">
                    <a
                        @click="activeTab = `childrens`"
                        class="nav-link"
                        :class="
                            activeTab === 'childrens'
                                ? activeClasses
                                : inactiveClasses +
                                  ' ' +
                                  (form?.versions?.length ||
                                  form.paymentDetailsType === 'service' ||
                                  form.paymentDetailsType === 'pauschal' ||
                                  form.paymentDetailsType === 'traveling'
                                      ? ''
                                      : 'disabled')
                        "
                    >
                        {{ $t("Childrens") }}
                    </a>
                </li>
              <!--   <li
                    class="nav-item"
                    v-if="
                        form.paymentDetailsType === 'software' ||
                        form.paymentDetailsType === 'cloud-software' ||
                        form.paymentDetailsType === 'hosting'
                    "
                >
                    <a
                        @click="activeTab = `parameters`"
                        class="nav-link"
                        :class="
                            activeTab === 'parameters'
                                ? activeClasses
                                : inactiveClasses +
                                  ' ' +
                                  (form?.versions?.length ? '' : 'disabled')
                        "
                    >
                        {{ $t("Parameters") }}
                    </a>
                </li> -->
                <li
                    class="nav-item"
                    v-if="
                        form.paymentDetailsType === 'software' ||
                        form.paymentDetailsType === 'cloud-software' ||
                        form.paymentDetailsType === 'hosting'
                    "
                >
                    <a
                        @click="activeTab = `services`"
                        class="nav-link"
                        :class="
                            activeTab === 'services'
                                ? activeClasses
                                : inactiveClasses +
                                  ' ' +
                                  (form?.versions?.length ? '' : 'disabled')
                        "
                    >
                        {{ $t("Additional Services") }}
                    </a>
                </li>
                <li
                    class="nav-item"
                    v-if="
                        form.paymentDetailsType === 'software' ||
                        form.paymentDetailsType === 'cloud-software' ||
                        form.paymentDetailsType === 'hosting'
                    "
                >
                    <a
                        @click="activeTab = `files`"
                        class="nav-link"
                        :class="
                            activeTab === 'files'
                                ? activeClasses
                                : inactiveClasses +
                                  ' ' +
                                  (form?.versions?.length ? '' : 'disabled')
                        "
                    >
                        {{ $t("Files") }}
                    </a>
                </li>
            </ul>
        </div>

        <div id="myTabContent">
            <div
                v-if="activeTab === 'products'"
                id="profile"
                role="tabpanel"
                aria-labelledby="product-tab"
            >
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">{{ $t("Product Type") }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="w-full">
                            <div
                                class="grid gap-2 py-4 px-8"
                                style="
                                    grid-template-columns: repeat(
                                        auto-fit,
                                        minmax(100px, 1fr)
                                    );
                                "
                            >
                                <div class="flex">
                                    <label for="software">{{
                                        $t("Software")
                                    }}</label>
                                    <input
                                        @input="paymentDetailsTypeChanged"
                                        v-model="form.paymentDetailsType"
                                        class="ml-5 mt-1"
                                        name="payment_details_type"
                                        id="software"
                                        type="radio"
                                        value="software"
                                    />
                                </div>
                                <div class="flex">
                                    <label for="software">{{
                                        $t("Service")
                                    }}</label>
                                    <input
                                        @input="paymentDetailsTypeChanged"
                                        v-model="form.paymentDetailsType"
                                        class="ml-5 mt-1"
                                        name="payment_details_type"
                                        id="service"
                                        type="radio"
                                        value="service"
                                    />
                                </div>
                                <div class="flex">
                                    <label for="software">{{
                                        $t("Pauschal")
                                    }}</label>
                                    <input
                                        @input="paymentDetailsTypeChanged"
                                        v-model="form.paymentDetailsType"
                                        class="ml-5 mt-1"
                                        name="payment_details_type"
                                        id="pauschal"
                                        type="radio"
                                        value="pauschal"
                                    />
                                </div>
                                <div class="flex">
                                    <label for="hosting">{{
                                        $t("Hosting")
                                    }}</label>
                                    <input
                                        @input="paymentDetailsTypeChanged"
                                        v-model="form.paymentDetailsType"
                                        class="ml-5 mt-1"
                                        name="payment_details_type"
                                        id="hosting"
                                        type="radio"
                                        value="hosting"
                                    />
                                </div>
                                <div class="flex">
                                    <label for="software">{{
                                        $t("AMS")
                                    }}</label>
                                    <input
                                        @input="paymentDetailsTypeChanged"
                                        v-model="form.paymentDetailsType"
                                        class="ml-5 mt-1"
                                        name="payment_details_type"
                                        id="ams"
                                        type="radio"
                                        value="ams"
                                    />
                                </div>
                                <div class="flex">
                                    <label for="cloud-software">{{
                                        $t("Cloud Software")
                                    }}</label>
                                    <input
                                        @input="paymentDetailsTypeChanged"
                                        v-model="form.paymentDetailsType"
                                        class="ml-5 mt-1"
                                        name="payment_details_type"
                                        id="cloud-software"
                                        type="radio"
                                        value="cloud-software"
                                    />
                                </div>
                                <div class="flex">
                                    <label for="traveling">{{
                                        $t("Traveling")
                                    }}</label>
                                    <input
                                        @input="paymentDetailsTypeChanged"
                                        v-model="form.paymentDetailsType"
                                        class="ml-5 mt-1"
                                        name="payment_details_type"
                                        id="traveling"
                                        type="radio"
                                        value="traveling"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">{{ $t("Products Details") }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="">
                            <div class="grid grid-cols-2 gap-6">
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            v-model="form.name"
                                            :error="errors.name"
                                            :required="true"
                                            class=""
                                            :label="$t('Name')"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group checkbox-group">
                                        <input
                                            type="checkbox"
                                            id="productEditableCheckbox"
                                            class="checkbox-input"
                                            v-model="form.isProductNameEdit"
                                            true-value="true"
                                            false-value="false"
                                        />
                                        <label
                                            for="productEditableCheckbox"
                                            class="checkbox-label"
                                            >Product Name Editable</label
                                        >
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <select-input
                                            v-model="form.status"
                                            :required="true"
                                            :error="errors.status"
                                            class=""
                                            :label="$t('Status')"
                                        >
                                            <option value="1">
                                                {{ $t("Active") }}
                                            </option>
                                            <option value="0">
                                                {{ $t("Deactive") }}
                                            </option>
                                        </select-input>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <select-link-input
                                            v-if="
                                                form.paymentDetailsType !==
                                                'traveling'
                                            "
                                            :options="groups"
                                            :error="errors.productGroup"
                                            v-model="form.productGroup"
                                            :label="$t('Product Group')"
                                            :required="
                                                form.paymentDetailsType !==
                                                'cloud-software'
                                            "
                                            :selectType="'product'"
                                        ></select-link-input>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <MultiSelect
                                            v-if="
                                                form.paymentDetailsType !==
                                                'traveling'
                                            "
                                            class=""
                                            :error="errors.productCategoryId"
                                            v-model="form.productCategoryId"
                                            :options="productCategories.data"
                                            :multiple="false"
                                            :required="
                                                form.paymentDetailsType !==
                                                    'software' &&
                                                form.paymentDetailsType !==
                                                    'cloud-software'
                                            "
                                            :textLabel="$t('Product Category')"
                                            label="name"
                                            :trackBy="'id'"
                                            :moduleName="'productCategory'"
                                            :taggable="true"
                                            :query="{
                                                productType:
                                                    form.paymentDetailsType,
                                            }"
                                        />
                                    </div>
                                </div>
                                <!-- <div class="w-full">
                                    <div class="form-group">
                                        <MultiSelect
                                            v-if="
                                                form.paymentDetailsType ===
                                                    'software' ||
                                                form.paymentDetailsType ===
                                                    'cloud-software' ||
                                                form.paymentDetailsType ===
                                                    'hosting'
                                            "
                                            class=""
                                            :error="errors.versions"
                                            v-model="form.versions"
                                            :options="form.versions"
                                            :multiple="true"
                                            :textLabel="$t('Versions')"
                                            :required="true"
                                            label="name"
                                            :trackBy="'name'"
                                            :taggable="true"
                                            :tagPlaceholder="
                                                $t('Add a new version')
                                            "
                                            @tagAdded="addNewVersion"
                                        />
                                    </div>
                                </div> -->
                                <div class="w-full">
                                    <div class="form-group">
                                        <MultiSelect
                                            class=""
                                            :error="errors.productSoftware"
                                            v-model="form.productSoftware"
                                            :options="softwares.data"
                                            :multiple="false"
                                            :required="
                                                form.paymentDetailsType !==
                                                'hosting'
                                            "
                                            :textLabel="$t('Software')"
                                            label="name"
                                            :trackBy="'id'"
                                            :moduleName="'softwares'"
                                            v-if="
                                                form.paymentDetailsType !==
                                                'traveling'
                                            "
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <MultiSelect
                                            v-if="
                                                form.paymentDetailsType ===
                                                    'software' ||
                                                form.paymentDetailsType ===
                                                    'cloud-software'
                                            "
                                            class=""
                                            :error="errors.eloVersion"
                                            v-model="form.eloVersion"
                                            :key="form.eloVersion"
                                            :options="versions"
                                            :multiple="false"
                                            :required="
                                                form.paymentDetailsType !==
                                                'cloud-software'
                                            "
                                            :textLabel="$t('Software Version')"
                                            label="name"
                                            :trackBy="'id'"
                                            :moduleName="'version'"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            v-if="
                                                form.paymentDetailsType !==
                                                'traveling'
                                            "
                                            v-model="form.discount"
                                            :error="errors.discount"
                                            class=""
                                            :label="$t('Discount (%)')"
                                            :type="`number`"
                                            :min="0"
                                            @keypress="limitToPositiveNumbers"
                                            @update:modelValue="
                                                calculateProfitSale
                                            "
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            v-if="
                                                form.paymentDetailsType !==
                                                'traveling'
                                            "
                                            v-model="form.tax"
                                            :error="errors.tax"
                                            :required="true"
                                            class=""
                                            :type="`number`"
                                            :label="$t('Tax (%)')"
                                            :min="0"
                                            @keypress="limitToPositiveNumbers"
                                        />
                                    </div>
                                </div>

                                <div class="w-full">
                                    <div class="form-group">
                                        <MultiSelect
                                            v-if="
                                                form.paymentDetailsType !==
                                                'traveling'
                                            "
                                            class=""
                                            :error="errors.rules"
                                            v-model="form.rules"
                                            :options="rules"
                                            :multiple="true"
                                            :textLabel="$t('Rules')"
                                            label="rule_name"
                                            :trackBy="'id'"
                                            :moduleName="'rules'"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <MultiSelect
                                            class=""
                                            :error="errors.productPrice"
                                            v-model="form.productPrice"
                                            :options="
                                                form.paymentDetailsType ===
                                                'traveling'
                                                    ? productPrices?.data ?? []
                                                    : productPriceList
                                            "
                                            :multiple="false"
                                            :required="
                                                form.paymentDetailsType !==
                                                'hosting'
                                            "
                                            :textLabel="$t('Price List')"
                                            label="name"
                                            :trackBy="'id'"
                                            :moduleName="'productprice'"
                                            :key="form.productPrice?.id"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <MultiSelect
                                            class=""
                                            :error="errors.infrastructureSetting"
                                            v-model="form.infrastructureSetting"
                                            :options="hosts?.data"
                                            :multiple="false"
                                            :textLabel="$t('Infrastructure Settings')"
                                            label="name"
                                            :trackBy="'id'"
                                            :moduleName="'infrastructure_settings'"
                                        />
                                    </div>
                                </div>
                                <div class="w-full col-span-2">
                                    <div class="form-group">
                                        <text-area-input
                                            v-if="
                                                form.paymentDetailsType !==
                                                'traveling'
                                            "
                                            v-model="form.description"
                                            :error="errors.description"
                                            class=""
                                            :label="$t('Description')"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $t("Payment Details") }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="">
                            <div
                                v-if="
                                    form.paymentDetailsType === 'software' ||
                                    form.paymentDetailsType === 'cloud-software'
                                "
                                class="grid items-center grid-cols-2 gap-6"
                            >
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            v-model="form.manufacturerNumber"
                                            :required="true"
                                            :error="errors.manufacturerNumber"
                                            class=""
                                            :label="
                                                $t(
                                                    'Manufacturer article number'
                                                )
                                            "
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            v-model="form.executionNumber"
                                            :required="true"
                                            :error="errors.executionNumber"
                                            class=""
                                            :label="$t('Execution number')"
                                            :min="0"
                                            @keypress="limitToPositiveNumbers"
                                            :type="`number`"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <MultiSelect
                                            v-model="form.unit"
                                            :error="errors.unit"
                                            :key="form.unit"
                                            :required="true"
                                            class=""
                                            :textLabel="$t('Unit')"
                                            :options="units.data"
                                            moduleName="units"
                                            :multiple="false"
                                            label="name"
                                            :trackBy="'id'"
                                        ></MultiSelect>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <select-input
                                            v-model="form.recurringPayment"
                                            :error="errors.recurringPayment"
                                            :key="form.recurringPayment"
                                            :required="true"
                                            class=""
                                            :label="$t('Recurring payment')"
                                        >
                                            <option value="1">
                                                {{ $t("Yes") }}
                                            </option>
                                            <option value="0">
                                                {{ $t("No") }}
                                            </option>
                                        </select-input>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <MultiSelect
                                            v-model="form.paymentPeriod"
                                            :error="errors.paymentPeriod"
                                            :key="form.paymentPeriod"
                                            :required="true"
                                            class=""
                                            :textLabel="$t('Payment Period')"
                                            :options="periods.data"
                                            :multiple="false"
                                            label="name"
                                            trackBy="id"
                                            moduleName="periods"
                                        ></MultiSelect>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <number-input
                                            v-model="form.listingPrice"
                                            :required="true"
                                            :error="errors.listingPrice"
                                            class=""
                                            :label="$t('Listing price')"
                                            @update:modelValue="
                                                calculateProfitSale
                                            "
                                            :allowNegative="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            v-model="form.manufacturerDiscount"
                                            :required="true"
                                            :error="errors.manufacturerDiscount"
                                            class=""
                                            :label="
                                                $t('Manufacturer Discount (%)')
                                            "
                                            :min="0"
                                            @keypress="limitToPositiveNumbers"
                                            :type="`number`"
                                            @update:modelValue="
                                                calculateProfitSale
                                            "
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <number-input
                                            v-model="form.salePrice"
                                            :required="true"
                                            :error="errors.salePrice"
                                            class=""
                                            :label="$t('Sale Price')"
                                            @update:modelValue="calculateProfit"
                                            :allowNegative="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <number-input
                                            v-model="form.profit"
                                            :required="true"
                                            :error="errors.profit"
                                            class=""
                                            :label="$t('Profit')"
                                            @update:modelValue="
                                                calculateSalePrice
                                            "
                                            :allowNegative="true"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <number-input
                                            v-if="
                                                form.paymentDetailsType !==
                                                'cloud-software'
                                            "
                                            v-model="form.maintanencePrice"
                                            :required="true"
                                            :error="errors.maintanencePrice"
                                            class=""
                                            :label="
                                                $t('Software maintenance price')
                                            "
                                            @update:modelValue="
                                                calculateMaintanenceRate
                                            "
                                            :allowNegative="false"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            v-if="
                                                form.paymentDetailsType !==
                                                'cloud-software'
                                            "
                                            v-model="form.maintanenceRate"
                                            :required="true"
                                            :error="errors.maintanenceRate"
                                            class=""
                                            :label="
                                                $t(
                                                    'Software maintenance rate (%)'
                                                )
                                            "
                                            :type="`number`"
                                            @update:modelValue="
                                                calculateMaintanencePrice
                                            "
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group"></div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group"></div>
                                </div>
                            </div>
                            <div
                                v-if="form.paymentDetailsType === 'hosting'"
                                class="grid items-center grid-cols-2 gap-6"
                            >
                                <div class="w-full">
                                    <div class="form-group">
                                        <MultiSelect
                                            v-model="form.paymentPeriod"
                                            :error="errors.paymentPeriod"
                                            :key="form.paymentPeriod"
                                            :required="true"
                                            class=""
                                            :textLabel="$t('Payment Period')"
                                            :options="periods.data"
                                            :multiple="false"
                                            label="name"
                                            trackBy="id"
                                            moduleName="periods"
                                        ></MultiSelect>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <number-input
                                            v-model="form.pricePerPeriod"
                                            :error="errors.pricePerPeriod"
                                            class=""
                                            :label="$t('Price Per Period')"
                                            :allowNegative="true"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div
                                v-else-if="
                                    form.paymentDetailsType === 'service' ||
                                    form.paymentDetailsType === 'pauschal'
                                "
                                class="grid items-center grid-cols-2 gap-6"
                            >
                                <div class="w-full">
                                    <div class="form-group">
                                        <number-input
                                            v-if="
                                                form.paymentDetailsType ===
                                                'service'
                                            "
                                            @update:modelValue="
                                                calculateDailyRate
                                            "
                                            v-model="form.hourlyRate"
                                            :required="true"
                                            :error="errors.hourlyRate"
                                            class="pb-8 pr-6 w-full lg:w-1/4"
                                            :label="$t('Hourly Rate')"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            type="number"
                                            @input="calculateSalePriceService"
                                            :error="errors.quantity"
                                            :required="true"
                                            v-model="form.quantity"
                                            class="pb-8 pr-6 w-full lg:w-1/4"
                                            :label="$t('Quantity')"
                                        ></text-input>
                                    </div>
                                </div>

                                <div class="w-full">
                                    <div class="form-group">
                                        <MultiSelect
                                            v-model="form.unit"
                                            :error="errors.unit"
                                            :required="true"
                                            class="pb-8 pr-6 w-full lg:w-1/4"
                                            :textLabel="$t('Unit')"
                                            :options="units.data"
                                            moduleName="units"
                                            :multiple="false"
                                            label="name"
                                            :trackBy="'id'"
                                        ></MultiSelect>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <number-input
                                            v-model="form.salePrice"
                                            :required="true"
                                            :error="errors.salePrice"
                                            class="pb-8 pr-6 w-full lg:w-1/4"
                                            :label="$t('Sale Price')"
                                            :allowNegative="true"
                                            @update:modelValue="
                                                calculateHourlyRate
                                            "
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <number-input
                                            v-if="
                                                form.paymentDetailsType ===
                                                'service'
                                            "
                                            :isReadonly="true"
                                            v-model="form.dailyRate"
                                            :required="true"
                                            :error="errors.dailyRate"
                                            class="pb-8 pr-6 w-full lg:w-1/4"
                                            :label="$t('Daily Rate')"
                                            :allowNegative="true"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div v-else-if="form.paymentDetailsType === 'ams'">
                                <div
                                    class="grid items-center grid-cols-2 gap-6"
                                >
                                    <div class="w-full">
                                        <div class="form-group">
                                            <label for="contingent">{{
                                                $t("Contingent")
                                            }}</label>
                                            <input
                                                class="ml-5 mt-1"
                                                name="contingent"
                                                id="contingent"
                                                type="radio"
                                                value="contingent"
                                                v-model="form.amsType"
                                            />
                                        </div>
                                    </div>
                                    <div class="w-full">
                                        <div class="form-group">
                                            <label for="fix_price">{{
                                                $t("Fix Price")
                                            }}</label>
                                            <input
                                                class="ml-5 mt-1"
                                                name="fix_price"
                                                id="fix_price"
                                                type="radio"
                                                value="fix_price"
                                                v-model="form.amsType"
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div
                                    v-if="form.amsType === `contingent`"
                                    class="grid items-center grid-cols-2 gap-6"
                                >
                                    <div class="w-full">
                                        <div class="form-group">
                                            <number-input
                                                @update:modelValue="
                                                    calculateDailyRate
                                                "
                                                v-model="form.hourlyRate"
                                                :required="true"
                                                :error="errors.hourlyRate"
                                                class="pb-8 pr-6 w-full lg:w-1/3"
                                                :label="$t('Hourly Rate')"
                                            />
                                        </div>
                                    </div>
                                    <div class="w-full">
                                        <div class="form-group">
                                            <number-input
                                                :isReadonly="true"
                                                v-model="form.dailyRate"
                                                :required="true"
                                                :error="errors.dailyRate"
                                                class="pb-8 pr-6 w-full lg:w-1/3"
                                                :label="$t('Daily Rate')"
                                                :allowNegative="true"
                                            />
                                        </div>
                                    </div>
                                    <div class="w-full">
                                        <div class="form-group">
                                            <MultiSelect
                                                v-model="form.unit"
                                                :error="errors.unit"
                                                :required="true"
                                                class="pb-8 pr-6 w-full lg:w-1/3"
                                                :textLabel="$t('Unit')"
                                                :options="units.data"
                                                moduleName="units"
                                                :multiple="false"
                                                label="name"
                                                :trackBy="'id'"
                                            ></MultiSelect>
                                        </div>
                                    </div>
                                    <div class="w-full">
                                        <div class="form-group">
                                            <MultiSelect
                                                v-model="form.paymentPeriod"
                                                :error="errors.paymentPeriod"
                                                :required="true"
                                                class=""
                                                :textLabel="
                                                    $t('Payment Period')
                                                "
                                                :multiple="false"
                                                :options="periods.data"
                                                label="name"
                                                trackBy="id"
                                            ></MultiSelect>
                                        </div>
                                    </div>

                                    <!-- <number-input
                                :showPrefix="false"
                                @update:model-value="calculateServiceDays"
                                v-model="form.serviceHours"
                                :required="true"
                                :error="errors.serviceHours"
                                class="pb-8 pr-6 w-full lg:w-1/4"
                                :label="$t('Service Hours')"
                            />
                               <number-input
                                :showPrefix="false"
                                @update:model-value="calculateSalePriceAMS"
                                v-model="form.serviceDays"
                                :required="true"
                                :error="errors.serviceDays"
                                class="pb-8 pr-6 w-full lg:w-1/4"
                                :label="$t('Service Days')"
                            />
                              <number-input
                                v-model="form.salePrice"
                                :required="true"
                                :error="errors.salePrice"
                                class="pb-8 pr-6 w-full lg:w-1/4"
                                :label="$t('Sale Price')"
                                :allowNegative="true"
                            /> -->
                                </div>
                                <div
                                    v-if="form.amsType === `fix_price`"
                                    class="flex flex-wrap -mb-8 -mr-6 p-8"
                                >
                                    <number-input
                                        v-model="form.fixedPrice"
                                        :required="true"
                                        :error="errors.fixedPrice"
                                        class=""
                                        :label="$t('Fixed Price')"
                                        :allowNegative="true"
                                    />
                                    <MultiSelect
                                        v-model="form.unit"
                                        :error="errors.unit"
                                        :required="true"
                                        class=""
                                        :textLabel="$t('Unit')"
                                        :options="units.data"
                                        moduleName="units"
                                        :multiple="false"
                                        label="name"
                                        :trackBy="'id'"
                                    ></MultiSelect>
                                </div>
                            </div>
                            <div
                                v-else-if="
                                    form.paymentDetailsType === 'traveling'
                                "
                                class="flex flex-wrap -mb-8 -mr-6 p-8"
                            >
                                <number-input
                                    v-model="form.salePrice"
                                    :required="true"
                                    :error="errors.salePrice"
                                    class=""
                                    :label="$t('Sale Price')"
                                    @update:modelValue="calculateProfit"
                                    :allowNegative="true"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end mt-4">
                    <router-link to="/products" class="primary-btn me-3">
                        <span class="mr-1">
                            <CustomIcon name="cancelIcon" />
                        </span>
                        <span>{{ $t("Cancel") }}</span>
                    </router-link>
                    <loading-button
                        @click="next()"
                        :loading="isLoading"
                        class="secondary-btn"
                    >
                        <span class="mr-1">
                            <CustomIcon name="saveIcon" />
                        </span>
                        {{
                            form.paymentDetailsType === "ams"
                                ? productId
                                    ? $t("Update")
                                    : $t("Create")
                                : $t("Save & Proceed")
                        }}
                    </loading-button>
                </div>
            </div>
            <div
                v-else-if="activeTab === 'dependencies'"
                id="profile"
                role="tabpanel"
                aria-labelledby="dependencies-tab"
            >
                <product-dependencies
                    :dependencies="dependencies"
                    :form="dependentProductsForm"
                    @options="options = $event"
                    @valueChanged="valueChanged('dependencies', $event)"
                    :versions="form.versions"
                    @selectedVersion="selectedVersionChanged"
                    :selectedVersion="selectedVersion"
                    @back="
                        (data) => {
                            activeTab = data;
                        }
                    "
                    @dependencies="next()"
                ></product-dependencies>
            </div>
            <div
                v-else-if="activeTab === 'install'"
                id="profile"
                role="tabpanel"
                aria-labelledby="install-tab"
            >
                <install-routines
                    :installRoutines="installRoutines"
                    @back="
                        (data) => {
                            activeTab = data;
                        }
                    "
                    @valueChanged="valueChanged('install', $event)"
                    :versions="form.versions"
                    @selectedVersion="selectedVersionChanged"
                    :selectedVersion="selectedVersion"
                    @install="next()"
                ></install-routines>
            </div>
            <div
                v-else-if="activeTab === 'uninstall'"
                id="profile"
                role="tabpanel"
                aria-labelledby="uninstall-tab"
            >
                <uninstall-routines
                    :uninstallRoutines="uninstallRoutines"
                    @back="
                        (data) => {
                            activeTab = data;
                        }
                    "
                    :versions="form.versions"
                    @selectedVersion="selectedVersionChanged"
                    :selectedVersion="selectedVersion"
                    @valueChanged="valueChanged('uninstall', $event)"
                    @uninstall="next()"
                ></uninstall-routines>
            </div>
            <div
                v-else-if="activeTab === 'childrens'"
                id="profile"
                role="tabpanel"
                aria-labelledby="children-tab"
            >
                <product-childrens
                    :dependencies="dependencies"
                    :servicesChildrens="servicesChildrens"
                    :products="products"
                    @options="options = $event"
                    :productChildrens="productChildrens"
                    @valueChanged="valueChanged('children', $event)"
                    :versions="form.versions"
                    @selectedVersion="selectedVersionChanged"
                    :selectedVersion="selectedVersion"
                    :paymentDetailsType="form.paymentDetailsType"
                    @back="
                        (data) => {
                            activeTab = data;
                        }
                    "
                    @childrens="next()"
                    :update="typeof productId === 'number'"
                ></product-childrens>
            </div>
            <div
                v-else-if="activeTab === 'parameters'"
                id="profile"
                role="tabpanel"
                aria-labelledby="files-tab"
            >
                <set-parameters
                    @parameters="next()"
                    :productParameters="productParameters"
                    @valueChanged="valueChanged('parameters', $event)"
                    :versions="form.versions"
                    @selectedVersion="selectedVersionChanged"
                    :selectedVersion="selectedVersion"
                    @back="
                        (data) => {
                            activeTab = data;
                        }
                    "
                ></set-parameters>
            </div>
            <div
                v-else-if="activeTab === 'services'"
                id="profile"
                role="tabpanel"
                aria-labelledby="additional-services-tab"
            >
                <additional-services
                    :versions="form.versions"
                    @selectedVersion="selectedVersionChanged"
                    :productServices="productServices"
                    :selectedVersion="selectedVersion"
                    @valueChanged="valueChanged('services', $event)"
                    @services="next()"
                    @back="
                        (data) => {
                            activeTab = data;
                        }
                    "
                ></additional-services>
            </div>
            <div
                v-else-if="activeTab === 'files'"
                id="profile"
                role="tabpanel"
                aria-labelledby="files-tab"
            >
                <product-files
                    @back="
                        (data) => {
                            activeTab = data;
                        }
                    "
                    :versions="form.versions"
                    @selectedVersion="selectedVersionChanged"
                    :selectedVersion="selectedVersion"
                    @valueChanged="valueChanged('files', $event)"
                    @files="next"
                    :productFiles="productFiles"
                    :update="typeof productId === 'number'"
                ></product-files>
            </div>
        </div>
    </div>
</template>

<script>
import TextInput from "../../Components/TextInput.vue";
import NumberInput from "../../Components/NumberInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import TextAreaInput from "../../Components/TextareaInput.vue";
import PriceInput from "../../Components/PriceInput.vue";
import SelectLinkInput from "../../Components/SelectLinkInput.vue";
import MultiSelect from "../../Components/MultiSelectInput.vue";
import { BigNumber } from "bignumber.js";
import InstallRoutines from "./InstallRoutines.vue";
import UninstallRoutines from "./UninstallRoutines.vue";
import ProductDependencies from "./ProductDependencies.vue";
import ProductFiles from "./ProductFiles.vue";
import ProductChildrens from "./ProductChildrens.vue";
import SetParameters from "./SetParameters.vue";
import { mapGetters } from "vuex";
import AdditionalServices from "./AdditionalServices.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    components: {
        NumberInput,
        MultiSelect,
        LoadingButton,
        SelectInput,
        TextInput,
        TextAreaInput,
        PriceInput,
        SelectLinkInput,
        InstallRoutines,
        UninstallRoutines,
        ProductDependencies,
        ProductFiles,
        ProductChildrens,
        SetParameters,
        AdditionalServices,
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
                    text: "Master Data",
                    to: "/products",
                },
                {
                    text: "Products",
                    to: "/products",
                },
                {
                    text: this.$t("Create"),
                    active: true,
                },
            ],
            productId: null,
            leafNodes: {},
            leafNodesServices: {},
            options: [],
            selectedVersion: null,
            groups: [],
            versions: [],
            products: [],
            dependencies: [],
            servicesChildrens: [],
            productPriceList: [],
            form: {
                rules: [],
                fixedPrice: 0,
                productPrice: "",
                isProductNameEdit: false,
                pricePerPeriod: 0,
                productSoftware: "",
                paymentPeriod: "",
                serviceDays: "",
                serviceHours: "",
                quantity: "",
                dailyRate: "",
                hourlyRate: "",
                name: "",
                unit: "",
                paymentDetailsType: "software",
                manufacturerDiscount: "",
                status: "",
                phone: "",
                description: "",
                listingPrice: "",
                discount: "0",
                salePrice: "",
                profit: "",
                tax: "",
                executionNumber: "",
                maintanenceRate: "",
                maintanencePrice: "",
                premiseRoutine: "",
                publicRoutine: "",
                privateRoutine: "",
                productGroup: "",
                eloVersion: "",
                articleNumber: "",
                recurringPayment: "",
                manufacturerNumber: "",
                dependentProducts: "",
                productCategoryId: "",
                productSoftwareChildrens: [],
                productServiceChildrens: [],
                versions: [
                    {name:1}
                ],
                amsType: "contingent",
                infrastructureSetting:{}
            },
            dependentProductsForm: {
                dependentProducts: null,
            },
            installRoutines: {
                windowsPremiseRoutine: "",
                windowsPrivateRoutine: "",
                windowsPublicRoutine: "",
                linuxPremiseRoutine: "",
                linuxPrivateRoutine: "",
                linuxPublicRoutine: "",
                macPremiseRoutine: "",
                macPrivateRoutine: "",
                macPublicRoutine: "",
            },
            uninstallRoutines: {
                windowsUninstallPremiseRoutine: "",
                windowsUninstallPrivateRoutine: "",
                windowsUninstallPublicRoutine: "",
                linuxUninstallPremiseRoutine: "",
                linuxUninstallPrivateRoutine: "",
                linuxUninstallPublicRoutine: "",
                macUninstallPremiseRoutine: "",
                macUninstallPrivateRoutine: "",
                macUninstallPublicRoutine: "",
            },
            productChildrens: {
                productChildrens: null,
                versionServiceChildrens: null,
            },
            productParameters: {
                parameters: [
                    {
                        type: "text",
                        key: "",
                        file: null,
                        value: "",
                    },
                ],
            },
            productServices: {
                installations: [],
                configurations: [],
            },
            productFiles: {
                files: [],
            },
            activeTab: "products",
            activeClasses: "active",
            inactiveClasses: "inactive",
        };
    },
    watch: {
        async "form.paymentDetailsType"(val) {
            try {
                await this.$store.dispatch("productCategory/list", {
                    productType: val,
                });
            } catch (e) {
                console.log(e);
            }
        },
        async "form.productSoftware"(val) {
            if (val.id) {
                const response = await this.$store.dispatch(
                    "productprice/getAllProductsRelatedToSoftware",
                    val.id
                );
                this.productPriceList = response?.data?.data;
                this.form.productPrice = this.productPriceList.find(
                    (data) => data.isDefault === 1
                );
            }
            this.versions = [];
            await this.$nextTick(() => {
                this.form.eloVersion = ""; // Clear eloVersion after the next rendering cycle
            });
            if (val != null) {
                const response = await this.$store.dispatch(
                    "versions/getVersions",
                    val.id
                );
                this.versions = response?.data ?? [];
            }
        },
    },
    remember: "form",
    computed: {
        ...mapGetters("rules", ["rules", "count"]),
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("productCategory", {
            productCategories: "data",
        }),
        ...mapGetters("units", ["units"]),
        ...mapGetters("softwares", ["softwares"]),
        ...mapGetters("periods", ["periods"]),
        ...mapGetters("productprice", ["productPrices"]),
        ...mapGetters("eloBusinessSolutions", ["hosts"])
    },
    async mounted() {
        try {
            const response = await this.$store.dispatch("products/fetchData");
            this.groups = response?.data?.groups ?? [];
            // this.versions = response?.data?.versions ?? [];
            this.products = response?.data?.products ?? [];
            this.dependencies = response?.data?.dependencies ?? [];
            this.servicesChildrens = response?.data?.servicesChildrens ?? [];
            this.getLeafNodes(this.dependencies);
            this.getLeafNodesServices(this.servicesChildrens);
            this.$store.dispatch("productCategory/list", {
                productType: "software",
            });
            await this.$store.dispatch("units/list");
            await this.$store.dispatch("softwares/list");
            await this.$store.dispatch("periods/list");
            await this.$store.dispatch("rules/list");
            await this.$store.dispatch("eloBusinessSolutions/list");
        } catch (e) {
            console.log(e);
        }
    },
    methods: {
        async calculateServiceDays() {
            await this.$nextTick();
            this.form.serviceDays = +this.form.serviceHours / 8;
            this.calculateSalePriceAMS();
        },
        async calculateSalePriceAMS() {
            await this.$nextTick();
            this.form.serviceHours = +this.form.serviceDays * 8;
            this.form.salePrice = this.form.dailyRate * this.form.serviceDays;
        },
        calculateHourlyRate() {
            this.$nextTick(() => {
                if (this.form.paymentDetailsType === "pauschal") return;
                try {
                    this.form.hourlyRate =
                        +this.form.salePrice /
                        (this.form.quantity ? +this.form.quantity : 1);
                    if (!this.form.quantity) {
                        this.form.quantity = 1;
                    }
                    this.form.dailyRate = +this.form.hourlyRate * 8;
                } catch (e) {
                    console.log(e);
                }
            });
        },
        calculateDailyRate() {
            this.$nextTick(() => {
                if (this.form.paymentDetailsType === "pauschal") return;
                try {
                    this.form.dailyRate = +this.form.hourlyRate * 8;
                    if (this.form.paymentDetailsType === "ams") {
                        this.form.salePrice =
                            this.form.dailyRate * this.form.serviceDays;
                        return;
                    }
                    this.form.salePrice =
                        +this.form.hourlyRate *
                        (this.form.quantity ? +this.form.quantity : 0);
                } catch (e) {
                    console.log(e);
                }
            });
        },
        calculateSalePriceService() {
            this.$nextTick(() => {
                if (this.form.paymentDetailsType === "pauschal") return;
                try {
                    this.form.salePrice =
                        (this.form.hourlyRate ? +this.form.hourlyRate : 0) *
                        (this.form.quantity ? +this.form.quantity : 0);
                } catch (e) {
                    console.log(e);
                }
            });
        },
        async paymentDetailsTypeChanged(event) {
            if (
                event.target.value === "software" ||
                event.target.value === "cloud-software"
            ) {
                this.form.hourlyRate = "";
                this.form.dailyRate = "";
                this.form.unit = "";
                this.form.quantity = "";
                this.form.salePrice = "";
                if (event.target.value === "cloud-software") {
                    this.form.recurringPayment = 1;
                    this.form.unit =
                        this.units?.data?.find(
                            (unit) => unit?.name?.toLowerCase() === "menge"
                        ) ?? "";
                    this.form.paymentPeriod =
                        this.periods?.data?.find(
                            (period) =>
                                period?.name?.toLowerCase() === "monatlich"
                        ) ?? "";
                }
            } else if (event.target.value === "hosting") {
                this.form.pricePerPeriod = 0;
                this.form.paymentPeriod = "";
            } else if (
                event.target.value === "service" ||
                event.target.value === "pauschal"
            ) {
                this.form.manufacturerDiscount = "";
                this.form.listingPrice = "";
                this.form.paymentPeriod = "";
                this.form.salePrice = "";
                this.form.profit = "";
                this.form.executionNumber = "";
                this.form.maintanenceRate = "";
                this.form.maintanencePrice = "";
                this.form.eloVersion = "";
                this.form.recurringPayment = "";
                this.form.manufacturerNumber = "";
                this.form.versions = [];
                this.form.unit = "";
            }
            // if the paymentDetailsType is set to traveling then get the productprice listing and set the productPrice whose 'isDefault'
            // is set to 1 to the form.productPrice
            else if (event.target.value === "traveling") {
                try {
                    await this.$store.dispatch("productprice/list", {
                        status: "active",
                    });
                    this.form.productPrice = this.productPrices?.data.find(
                        (data) => data.isDefault === 1
                    );
                } catch (e) {
                    console.log(e);
                }
            }
            this.dependentProductsForm = {
                dependentProducts: null,
            };
            this.installRoutines = {
                windowsPremiseRoutine: "",
                windowsPrivateRoutine: "",
                windowsPublicRoutine: "",
                linuxPremiseRoutine: "",
                linuxPrivateRoutine: "",
                linuxPublicRoutine: "",
                macPremiseRoutine: "",
                macPrivateRoutine: "",
                macPublicRoutine: "",
            };
            this.uninstallRoutines = {
                windowsUninstallPremiseRoutine: "",
                windowsUninstallPrivateRoutine: "",
                windowsUninstallPublicRoutine: "",
                linuxUninstallPremiseRoutine: "",
                linuxUninstallPrivateRoutine: "",
                linuxUninstallPublicRoutine: "",
                macUninstallPremiseRoutine: "",
                macUninstallPrivateRoutine: "",
                macUninstallPublicRoutine: "",
            };
            this.productChildrens = {
                productChildrens: null,
                versionServiceChildrens: null,
            };
            this.productParameters = {
                parameters: [
                    {
                        type: "text",
                        key: "",
                        file: null,
                        value: "",
                    },
                ],
            };
            this.productServices = {
                installations: [],
                configurations: [],
            };
            this.productFiles = {
                files: [],
            };
        },
        getLeafNodes(dependencies) {
            for (let key in dependencies) {
                if (dependencies[key].id) {
                    this.leafNodes[dependencies[key].id] = dependencies[key];
                } else {
                    this.getLeafNodes(dependencies[key]);
                }
            }
        },
        getLeafNodesServices(services) {
            for (let key in services) {
                if (services[key].articleNumber) {
                    this.leafNodesServices[services[key].articleNumber] =
                        services[key];
                } else {
                    this.getLeafNodesServices(services[key]);
                }
            }
        },
        selectedVersionChanged(event) {
            this.selectedVersion = event;
            const version = this.form.versions.find(
                (version) => version.name === event.name
            );
            this.dependentProductsForm = {
                dependentProducts:
                    version.dependentProducts?.map(
                        (product) => product.nodeId
                    ) ?? null,
            };
            this.installRoutines = {
                windowsPremiseRoutine: version?.windowsPremiseRoutine,
                windowsPrivateRoutine: version?.windowsPrivateRoutine,
                windowsPublicRoutine: version?.windowsPublicRoutine,
                linuxPremiseRoutine: version?.linuxPremiseRoutine,
                linuxPrivateRoutine: version?.linuxPrivateRoutine,
                linuxPublicRoutine: version?.linuxPublicRoutine,
                macPremiseRoutine: version?.macPremiseRoutine,
                macPrivateRoutine: version?.macPrivateRoutine,
                macPublicRoutine: version?.macPublicRoutine,
            };
            this.uninstallRoutines = {
                windowsUninstallPremiseRoutine:
                    version?.windowsUninstallPremiseRoutine ?? "",
                windowsUninstallPrivateRoutine:
                    version?.windowsUninstallPrivateRoutine ?? "",
                windowsUninstallPublicRoutine:
                    version?.windowsUninstallPublicRoutine ?? "",
                linuxUninstallPremiseRoutine:
                    version?.linuxUninstallPremiseRoutine ?? "",
                linuxUninstallPrivateRoutine:
                    version?.linuxUninstallPrivateRoutine ?? "",
                linuxUninstallPublicRoutine:
                    version?.linuxUninstallPublicRoutine ?? "",
                macUninstallPremiseRoutine:
                    version?.macUninstallPremiseRoutine ?? "",
                macUninstallPrivateRoutine:
                    version?.macUninstallPrivateRoutine ?? "",
                macUninstallPublicRoutine:
                    version?.macUninstallPublicRoutine ?? "",
            };
            this.productChildrens = {
                productChildrens:
                    version.productChildrens?.map(
                        (product) => product.nodeId
                    ) ?? null,
                versionServiceChildrens:
                    version.versionServiceChildrens?.map(
                        (product) => product.nodeId
                    ) ?? null,
            };
            this.productParameters = {
                parameters: version.parameters ?? [
                    {
                        type: "text",
                        key: "",
                        file: null,
                        value: "",
                    },
                ],
            };
            this.productServices = {
                installations: version.installations ?? [],
                configurations: version.configurations ?? [],
            };
            this.productFiles = {
                files: version.files,
            };
        },
        async next() {
            try {
                if (this.activeTab === "products") {
                    if(this.form?.versions.length==0){
                        this.form.versions=[{name:1}];
                    }
                   /*  if (
                        !this.form?.versions?.length &&
                        (this.form.paymentDetailsType === "software" ||
                            this.form.paymentDetailsType === "cloud-software" ||
                            this.form.paymentDetailsType === "hosting")
                    ) {
                        this.$store.commit("flashMessage", {
                            show: true,
                            message: "Add a version to proceed",
                            type: "error",
                        });
                        return;
                    } */
                    await this.save();
                    if (this.form.paymentDetailsType === "ams") {
                        this.$router.push("/products");
                    }
                    /* this.activeTab =
                        this.form.paymentDetailsType === "software" ||
                         this.form.paymentDetailsType === "cloud-software"
                            ? "dependencies"
                            : this.form.paymentDetailsType === "hosting"
                            ? "install"
                            : "childrens";  */
                            this.activeTab ="childrens";
                         ////   alert(this.form.paymentDetailsType );
                          //  alert(this.activeTab);
                } else if (this.activeTab === "dependencies") {
                    await this.save();
                    this.activeTab = "install";
                } else if (this.activeTab === "install") {
                    await this.save();
                    this.activeTab = "uninstall";
                } else if (this.activeTab === "uninstall") {
                    await this.save();
                    this.activeTab = "childrens";
                } else if (this.activeTab === "childrens") {
                  //  alert('childrens');
                    await this.save();
                    if (
                        this.form.paymentDetailsType === "service" ||
                        this.form.paymentDetailsType === "pauschal" ||
                        this.form.paymentDetailsType === "traveling"
                    ) {
                        this.$router.push("/products");
                    }
                    this.activeTab = "services";
                  //  alert(this.activeTab);
                } else if (this.activeTab === "parameters") {
                    await this.save();
                    this.activeTab = "services";
                } else if (this.activeTab === "services") {
                    await this.save();
                    this.activeTab = "files";
                } else if (this.activeTab === "files") {
                    await this.save();
                    this.$router.push("/products");
                }
            } catch (e) {
                console.log(e);
            }
        },
        valueChanged(form, data) {
            const selectedVersionIndex = this.form.versions.findIndex(
                (version) => version.name === this.selectedVersion.name
            );
            if (form === "dependencies") {
                this.dependentForm = data;
                const dependentForm = { ...this.dependentForm };
                const dependentProducts = [];
                data.dependentProducts?.forEach((treeProductId) => {
                    dependentProducts.push(this.leafNodes[treeProductId]);
                });
                dependentForm.dependentProducts = dependentProducts.map(
                    (product) => {
                        return {
                            id: product.productId,
                            versionId: product.versionId,
                            nodeId: product.id,
                        };
                    }
                );
                this.form.versions[selectedVersionIndex] = Object.assign(
                    this.form.versions[selectedVersionIndex],
                    dependentForm
                );
            } else if (form === "install") {
                this.installRoutines = data;
                this.form.versions[selectedVersionIndex] = Object.assign(
                    this.form.versions[selectedVersionIndex],
                    this.installRoutines
                );
            } else if (form === "uninstall") {
                this.uninstallRoutines = data;
                this.form.versions[selectedVersionIndex] = Object.assign(
                    this.form.versions[selectedVersionIndex],
                    this.uninstallRoutines
                );
            } else if (form === "children") {
                this.productChildrens = data;
                const productChildrens = { ...this.productChildrens };
                const children = [];
                const services = [];
                data.productChildrens?.forEach((treeProductId) => {
                    children.push(this.leafNodes[treeProductId]);
                });
                data.versionServiceChildrens?.forEach((treeProductId) => {
                    services.push(this.leafNodesServices[treeProductId]);
                });
                productChildrens.productChildrens = children?.map((product) => {
                    return {
                        id: product.productId,
                        versionId: product.versionId,
                        nodeId: product.id,
                    };
                });
                productChildrens.versionServiceChildrens = services?.map(
                    (product) => {
                        return {
                            id: product.productId,
                            nodeId: product.articleNumber,
                        };
                    }
                );
                if (
                    this.form.paymentDetailsType === "software" ||
                    this.form.paymentDetailsType === "cloud-software" ||
                    this.form.paymentDetailsType === "hosting"
                ) {
                    this.form.versions[selectedVersionIndex] = Object.assign(
                        this.form.versions[selectedVersionIndex],
                        productChildrens
                    );
                } else {
                    this.form.productSoftwareChildrens =
                        productChildrens.productChildrens;
                    this.form.productServiceChildrens =
                        productChildrens.versionServiceChildrens;
                }
            } else if (form === "parameters") {
                this.productParameters = data;
                this.form.versions[selectedVersionIndex] = Object.assign(
                    this.form.versions[selectedVersionIndex],
                    this.productParameters
                );
            } else if (form === "services") {
                this.productServices = data;
                this.form.versions[selectedVersionIndex] = Object.assign(
                    this.form.versions[selectedVersionIndex],
                    this.productServices
                );
            } else if (form === "files") {
                this.productFiles = data;
                this.form.versions[selectedVersionIndex] = Object.assign(
                    this.form.versions[selectedVersionIndex],
                    this.productFiles
                );
            }
        },
        addNewVersion(tag) {
            this.form.versions.push({
                name: tag,
                dependentProducts: null,
                windowsPremiseRoutine: "",
                windowsPrivateRoutine: "",
                windowsPublicRoutine: "",
                linuxPremiseRoutine: "",
                linuxPrivateRoutine: "",
                linuxPublicRoutine: "",
                macPremiseRoutine: "",
                macPrivateRoutine: "",
                macPublicRoutine: "",
                windowsUninstallPremiseRoutine: "",
                windowsUninstallPrivateRoutine: "",
                windowsUninstallPublicRoutine: "",
                linuxUninstallPremiseRoutine: "",
                linuxUninstallPrivateRoutine: "",
                linuxUninstallPublicRoutine: "",
                macUninstallPremiseRoutine: "",
                macUninstallPrivateRoutine: "",
                macUninstallPublicRoutine: "",
                productChildrens: null,
                parameters: [
                    {
                        type: "text",
                        key: "",
                        file: null,
                        value: "",
                    },
                ],
                files: [],
            });
            if (!this.selectedVersion) {
                this.selectedVersion = { name: tag };
            }
        },
        calculateProfitSale() {
            /** calculating profit */
            let salePrice = BigNumber(+this.form.salePrice);
            let listingPrice = BigNumber(+this.form.listingPrice);
            let discount = BigNumber(+this.form.manufacturerDiscount);
            let percentageDiscount = discount.div(100);
            let totalDiscount = BigNumber(1 - percentageDiscount);

            let listingPriceAndDiscount =
                listingPrice.multipliedBy(totalDiscount);
            let profit = salePrice.minus(listingPriceAndDiscount);

            this.form.profit = profit.toFixed(profit.isInteger() ? 0 : 2);
            /** */

            /** calculating the sale price */
            let totalSalePrice = profit.plus(listingPriceAndDiscount);
            this.form.salePrice = totalSalePrice.toFixed(
                totalSalePrice.isInteger() ? 0 : 2
            );
            /** */
        },
        calculateProfit() {
            let salePrice = new BigNumber(+this.form.salePrice);
            let listingPrice = new BigNumber(+this.form.listingPrice);
            let discount = new BigNumber(+this.form.manufacturerDiscount);
            let percentageDiscount = discount.div(100);
            let totalDiscount = new BigNumber(1 - percentageDiscount);
            let listingPriceAndDiscount =
                listingPrice.multipliedBy(totalDiscount);

            /** calculating profit */
            let profit = salePrice.minus(listingPriceAndDiscount);
            this.form.profit = profit.toFixed(profit.isInteger() ? 0 : 2);
            /** */
        },
        calculateSalePrice() {
            let profit = new BigNumber(+this.form.profit);
            let listingPrice = new BigNumber(+this.form.listingPrice);
            let discount = new BigNumber(+this.form.manufacturerDiscount);
            let percentageDiscount = discount.div(100);
            let totalDiscount = new BigNumber(1 - percentageDiscount);
            let listingPriceAndDiscount =
                listingPrice.multipliedBy(totalDiscount);

            /** calculating the sale price */
            let salePrice = profit.plus(listingPriceAndDiscount);
            this.form.salePrice = salePrice.toFixed(
                salePrice.isInteger() ? 0 : 2
            );
            /** */
        },
        calculateMaintanenceRate() {
            if (this.form.listingPrice) {
                let listingPrice = new BigNumber(+this.form.listingPrice);
                let maintanencePrice = new BigNumber(
                    +this.form.maintanencePrice
                );
                let percentListingPrice = listingPrice.multipliedBy(100);
                let maintanenceRate = maintanencePrice.div(percentListingPrice);
                this.form.maintanenceRate = maintanenceRate.toFixed(
                    maintanenceRate.isInteger() ? 0 : 2
                );
            }
        },
        calculateMaintanencePrice() {
            let listingPrice = new BigNumber(+this.form.listingPrice);
            let maintanenceRate = new BigNumber(+this.form.maintanenceRate);
            let percentMaintanenceRate = maintanenceRate.div(100);
            let maintanencePrice =
                percentMaintanenceRate.multipliedBy(listingPrice);
            this.form.maintanencePrice = maintanencePrice.toFixed(
                maintanencePrice.isInteger() ? 0 : 2
            );
        },
        parameters(data) {
            data.post("/products/parameters/save", {
                onFinish: () => {
                    if (Object.keys(this.errors).length > 0) {
                        data.errors = this.errors;
                    } else {
                        this.activeTab = "files";
                    }
                },
            });
        },
        async save() {
            return new Promise(async (resolve, reject) => {
                try {
                
                    this.$store.commit("isLoading", true);
                    let response = null;
                    const isSoftware =
                        this.form.paymentDetailsType === "software" ||
                        this.form.paymentDetailsType === "cloud-software" ||
                        this.form.paymentDetailsType === "hosting";
                    const data = {
                        ...this.form,
                        productCategoryId: this.form.productCategoryId?.id,
                        productSoftware: this.form.productSoftware?.id,
                        productPrice: this.form.productPrice?.id,
                        unit: this.form.unit?.id,
                        paymentPeriod: this.form.paymentPeriod?.id,
                        productChildrens: isSoftware
                            ? []
                            : this.productChildrens.productChildrens,
                    };
                    // if productId exists then trigger the update products API
                    const payload = this.productId
                        ? {
                              id: this.productId,
                              data: {
                                  ...data,
                              },
                          }
                        : {
                              ...data,
                          };
                         
                    response = await this.$store.dispatch(
                        `products/${this.productId ? "update" : "create"}`,
                        {
                            ...payload,
                        }
                    );
                    if (!this.productId)
                        this.productId = response?.data?.id ?? null; // if productId has been set already then do not set it again
                    // if type is software then proceed
                    if (isSoftware) {
                        const versionsResponse = response?.data?.data ?? [];
                        const versions = [...this.form.versions];
                        const formData = new FormData();
                        let filesLength = 0;
                        versions.forEach((version, index) => {
                            formData.append(
                                `versions[${index}][id]`,
                                versionsResponse.find(
                                    (versionResponse) =>
                                        versionResponse.name === version.name
                                )?.id ?? ""
                            );
                            version.files.forEach((file, i) => {
                                filesLength++;
                                formData.append(
                                    `versions[${index}][files][${i}]`,
                                    file
                                );
                            });
                           
                        });
                        if (filesLength > 0)
                            await this.$store.dispatch(
                                "products/uploadFiles",
                                formData
                            );
                    }
                   
                    resolve(true);
                } catch (e) {
                    console.log(e);
                    this.activeTab = "products";
                    reject(e);
                }
            });
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

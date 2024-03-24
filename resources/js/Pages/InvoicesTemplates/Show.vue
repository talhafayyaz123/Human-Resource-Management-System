<template>
    <div>

        <PageHeader :items="breadcrumbItems" />

        <div class="card my-3">
            <div class="card-header">
              <h3 class="card-title">{{ $t("Configuration") }}</h3>
            </div>
            <div class="card-body">
                <div class="flex flex-wrap">
                    <div class="pb-8 pr-6 w-full lg:w-1/3">
                        <label class="form-label font-bold"
                            >{{ $t("Category") }}:</label
                        >
                        <p>{{ form.category }}</p>
                    </div>
                    <div class="pb-8 pr-6 w-full lg:w-1/3">
                        <label class="form-label font-bold"
                            >{{ $t("Customer") }}:</label
                        >
                        <p>
                            {{ form.customers?.companyName }}
                        </p>
                    </div>
                    <div
                        v-if="form.contactPerson"
                        class="pb-8 pr-6 w-full lg:w-1/3"
                    >
                        <label class="form-label font-bold"
                            >{{ $t("Contact Person") }}:</label
                        >
                        <p>
                            {{
                                form.contactPerson?.first_name +
                                " " +
                                form.contactPerson?.last_name
                            }}
                        </p>
                    </div>
                    <div
                        v-if="form.contactPerson"
                        class="pb-8 pr-6 w-full lg:w-1/3"
                    >
                        <label class="form-label font-bold"
                            >{{ $t("System") }}:</label
                        >
                        <p>
                            {{ form.systems?.systemNumber }}
                        </p>
                    </div>
                    <div class="pr-6 w-full lg:w-1/3 mb-8">
                        <label class="form-label font-bold"
                            >{{ $t("Invoice Status") }}:</label
                        >
                        <p>
                            {{ form?.invoiceStatus ? $t(form.invoiceStatus) : "-" }}
                        </p>
                    </div>
                    <div class="pr-6 w-full lg:w-1/3 mb-8">
                        <label
                            v-if="form.customers?.id"
                            class="form-label font-bold"
                            >{{ $t("Project Reference") }}:</label
                        >
                        <p>
                            {{ form.projectId?.name ?? "-" }}
                        </p>
                    </div>
                    <div class="pr-6 w-full lg:w-1/3 mb-8">
                        <label
                            v-if="
                                showPerformanceRecord && form.category === 'service'
                            "
                            class="form-label font-bold"
                            >{{ $t("Performance Record") }}:</label
                        >
                        <p
                            @click="
                                $router.push(
                                    `/performance-records/show?id=${form.performanceRecord?.id}&startDate=${form.performanceRecord?.startDate}&endDate=${form.performanceRecord?.endDate}&customerId=${form.performanceRecord?.companyId}`
                                )
                            "
                            style="
                                text-decoration: underline;
                                color: blue;
                                cursor: pointer;
                            "
                        >
                            {{ form.performanceRecord?.performanceNumber }}
                        </p>
                    </div>
                    <div
                        v-if="form.category === 'service'"
                        class="pr-6 w-full lg:w-1/3 mb-8"
                    >
                        <label class="form-label font-bold"
                            >{{ $t("Grouped By") }}:</label
                        >
                        <p>
                            {{ $t(form.groupedBy) }}
                        </p>
                    </div>
                    <div class="flex items-center pb-8 pr-6 pl-6 w-full lg:w-1/3">
                        <input
                            readonly
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
                    <div v-if="form.category === 'maintenance'" class="flex">
                        <label class="self-center" for="firstYearMaintenance">{{
                            $t("First Year Maintenance")
                        }}</label>
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
        </div>

        <form @submit.prevent="store">
            <div class="card my-5" v-if="form.customers?.companyName">
                <div class="flex justify-between p-5">
                    <h6 >
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
            </div>
            <div class="card my-5">
                <div class="card-header">
                    <h3 class="card-title">
                        <h1 class="secondary-color text-2xl capitalize mb-8">
                            {{ categoryDateString }}
                        </h1>
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
                                <p>{{ form?.customers?.companyNumber ?? "-" }}</p>
                            </div>
                            <div
                                class="grid gap-2"
                                style="grid-template-columns: repeat(auto-fit, 200px)"
                            >
                                <b>{{ $t("Project Nr.") }}</b>
                                <p>
                                    {{
                                        form?.projectId?.projectId ??
                                        form?.projectId?.project_id ??
                                        "-"
                                    }}
                                </p>
                            </div>
                        </div>
                        <div class="mb-8" style="color: #2957a4">
                            <div
                                class="grid gap-2"
                                style="grid-template-columns: repeat(auto-fit, 200px)"
                            >
                                <b>{{ $t("Created By") }}:</b>
                                <p>{{ user?.first_name }} {{ user?.last_name }}</p>
                            </div>
                            <div
                                class="grid gap-2"
                                style="grid-template-columns: repeat(auto-fit, 200px)"
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
                                    :disabled="invoiceStaticStatus != 'draft'"
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
                                <table class="w-full whitespace-nowrap">
                                    <thead>
                                        <tr>
                                            <th
                                                style="width: 3%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("POS") }}
                                            </th>
                                            <th
                                                style="width: 12%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Article number") }}
                                            </th>
                                            <th
                                                style="width: 25%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Product name") }}
                                            </th>
                                            <th
                                                style="width: 13%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Quantity") }}
                                            </th>
                                            <th
                                                style="width: 16%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Product price") }}
                                            </th>
                                            <th
                                                style="width: 13%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Discount") }}(%)
                                            </th>
                                            <th
                                                style="width: 16%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
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
                                            :tabindex="index"
                                        >
                                            <tr
                                                class="focus:outline-none h-16 border border-gray-100 rounded"
                                            >
                                                <td class="pl-5 align-top py-2">
                                                    {{ index + 1 }}
                                                </td>
                                                <td class="pl-5 align-top py-2">
                                                    <div class="md:w-2/3">
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
                                                            readonly
                                                            v-model="
                                                                product.articleNumber
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td class="pl-5 align-top py-2">
                                                    <div class="md:w-2/3">
                                                        <input
                                                            class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:border-purple-500"
                                                            type="text"
                                                            :style="
                                                                inputWidth(
                                                                    index,
                                                                    null,
                                                                    'softwareLicences'
                                                                )
                                                            "
                                                            :class="{
                                                                Perror: errors[
                                                                    `products.${index}.name`
                                                                ],
                                                            }"
                                                            readonly
                                                            v-model="
                                                                product.name
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td class="pl-5 align-top py-2">
                                                    <div class="md:w-2/3">
                                                        <input
                                                            class="appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
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
                                                            readonly
                                                            v-model="
                                                                product.quantity
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td class="pl-5 align-top">
                                                    <div>
                                                        <number-input
                                                            :maximumFractionDigits="
                                                                2
                                                            "
                                                            :allowNegative="
                                                                true
                                                            "
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
                                                            :isReadonly="true"
                                                        />
                                                    </div>
                                                </td>
                                                <td class="pl-5 align-top py-2">
                                                    <div class="md:w-2/3">
                                                        <input
                                                            class="appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
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
                                                            readonly
                                                        />
                                                    </div>
                                                </td>
                                                <td
                                                    class="pl-5 text-center align-top"
                                                >
                                                    <div>
                                                        <number-input
                                                            :maximumFractionDigits="
                                                                2
                                                            "
                                                            :allowNegative="
                                                                true
                                                            "
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
                                                            name="priceWithoutTax"
                                                            v-model="
                                                                product.priceWithoutTax
                                                            "
                                                            :class="{
                                                                Perror: errors[
                                                                    `products.${index}.discount`
                                                                ],
                                                            }"
                                                            :isReadonly="true"
                                                        />
                                                    </div>
                                                </td>
                                                <td class="text-center"></td>
                                            </tr>
                                            <tr
                                                v-if="
                                                    additionalDescriptionToggled[
                                                        product.id
                                                    ]
                                                "
                                            >
                                                <td></td>
                                                <td></td>
                                                <td class="pl-6">
                                                    <TextAreaInput
                                                        style="
                                                            overflow: visible !important;
                                                        "
                                                        :isReadonly="true"
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
                                        </template>
                                    </tbody>
                                </table>
                                <br />
                                <div
                                    v-if="
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
                                                        totalAmountLicences,
                                                        globalLanguage
                                                    )
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                v-else-if="form.category === 'maintenance'"
                                class="mt-7 overflow-x-auto"
                            >
                                <table class="w-full whitespace-nowrap">
                                    <thead>
                                        <tr>
                                            <th
                                                style="width: 3%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("POS") }}
                                            </th>
                                            <th
                                                style="width: 15%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Article number") }}
                                            </th>
                                            <th
                                                style="width: 20%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Product name") }}
                                            </th>
                                            <th
                                                style="width: 10%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Quantity") }}
                                            </th>
                                            <th
                                                style="width: 15%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Price") }}
                                            </th>
                                            <th
                                                style="width: 15%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Product Price") }}
                                            </th>
                                            <th
                                                style="width: 15%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Maintenance rate(%)") }}
                                            </th>
                                            <th
                                                style="width: 15%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
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
                                            :tabindex="index"
                                        >
                                            <tr
                                                class="focus:outline-none h-16 border border-gray-100 rounded"
                                            >
                                                <td class="pl-5 align-top py-2">
                                                    {{ index + 1 }}
                                                </td>
                                                <td class="pl-5 align-top">
                                                    <div class="md:w-2/3">
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
                                                            readonly
                                                            v-model="
                                                                product.articleNumber
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td class="pl-5 align-top py-2">
                                                    <div class="md:w-2/3">
                                                        <input
                                                            class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:border-purple-500"
                                                            type="text"
                                                            :style="
                                                                inputWidth(
                                                                    index,
                                                                    null,
                                                                    'softwareMaintenance'
                                                                )
                                                            "
                                                            :class="{
                                                                Perror: errors[
                                                                    `products.${index}.name`
                                                                ],
                                                            }"
                                                            readonly
                                                            v-model="
                                                                product.name
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td class="pl-5 align-top py-2">
                                                    <div class="md:w-2/3">
                                                        <input
                                                            class="appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
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
                                                            readonly
                                                        />
                                                    </div>
                                                </td>
                                                <td class="pl-5 align-top">
                                                    <div class="md:w-2/3">
                                                        <number-input
                                                            :isReadonly="true"
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
                                                            :allowNegative="
                                                                true
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td class="pl-5 align-top">
                                                    <div class="md:w-2/3">
                                                        <number-input
                                                            :maximumFractionDigits="
                                                                2
                                                            "
                                                            @update:model-value="
                                                                totalSalePriceChangedMaintenance(
                                                                    index
                                                                )
                                                            "
                                                            class="appearance-none rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            name="totalSalePriceAdjustedForQuantity"
                                                            :isReadonly="true"
                                                            v-model="
                                                                product.totalSalePriceAdjustedForQuantity
                                                            "
                                                            :allowNegative="
                                                                true
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td class="pl-5 align-top py-2">
                                                    <div class="md:w-2/3">
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
                                                            readonly
                                                        />
                                                    </div>
                                                </td>
                                                <td
                                                    class="pl-5 text-center align-top"
                                                >
                                                    <div>
                                                        <number-input
                                                            :maximumFractionDigits="
                                                                2
                                                            "
                                                            class="appearance-none rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="text"
                                                            v-model="
                                                                product.maintenancePrice
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
                                                            :allowNegative="
                                                                true
                                                            "
                                                            :class="{
                                                                Perror: errors[
                                                                    `products.${index}.salePrice`
                                                                ],
                                                            }"
                                                            :isReadonly="true"
                                                        />
                                                    </div>
                                                </td>
                                                <td class="text-center"></td>
                                            </tr>
                                            <tr
                                                v-if="
                                                    additionalDescriptionToggled[
                                                        product.id
                                                    ]
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
                                                        :isReadonly="true"
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
                                                    <!-- ({{
                                                        12 -
                                                        getMonthDifference(
                                                            form.startDate,
                                                            form.endDate
                                                        ) +
                                                        "/12"
                                                    }}): -->
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
                                <table class="w-full whitespace-nowrap">
                                    <thead>
                                        <tr>
                                            <th
                                                style="width: 3%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Pos") }}
                                            </th>
                                            <th
                                                style="width: 7%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Article Nr.") }}
                                            </th>
                                            <th
                                                style="width: 20%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Name") }}
                                            </th>
                                            <th
                                                style="width: 12%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{
                                                    $t("Quantity") +
                                                    "/" +
                                                    $t("Unit")
                                                }}
                                            </th>
                                            <th
                                                style="width: 10%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Hourly Rate") }}
                                            </th>
                                            <th
                                                style="width: 10%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Discount") }}(%)
                                            </th>
                                            <th
                                                v-if="!form.applyReverseChange"
                                                style="width: 10%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Tax") }}(%)
                                            </th>
                                            <th
                                                v-if="!form.applyReverseChange"
                                                style="width: 13%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Tax Rate") }}
                                            </th>
                                            <th
                                                style="width: 13%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
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
                                            :tabindex="index"
                                        >
                                            <tr
                                                class="focus:outline-none h-16 border border-gray-100 rounded"
                                            >
                                                <td class="pl-5 align-top py-2">
                                                    <div class="md:w-2/3">
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
                                                        <p>
                                                            {{ service?.name }}
                                                        </p>
                                                    </div>
                                                </td>
                                                <td class="pl-5 align-top py-2">
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
                                                            readonly
                                                            class="appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
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
                                                <td class="pl-5 align-top">
                                                    <div class="flex">
                                                        <number-input
                                                            @inputEvent="
                                                                serviceQuantityChanged(
                                                                    $event,
                                                                    index
                                                                )
                                                            "
                                                            class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            v-model="
                                                                service.hourlyRate
                                                            "
                                                            :isReadonly="true"
                                                            name="hourlyRate"
                                                        />
                                                    </div>
                                                </td>
                                                <td class="pl-5 align-top py-2">
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
                                                            class="appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                service.discount
                                                            "
                                                            name="discount"
                                                            readonly
                                                        />
                                                    </div>
                                                </td>
                                                <td
                                                    v-if="
                                                        !form.applyReverseChange
                                                    "
                                                    class="pl-5 align-top py-2"
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
                                                            class="w-full appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                service.tax
                                                            "
                                                            readonly
                                                            name="tax"
                                                        />
                                                    </div>
                                                </td>
                                                <td
                                                    v-if="
                                                        !form.applyReverseChange
                                                    "
                                                    class="pl-5 align-top"
                                                >
                                                    <div class="flex">
                                                        <number-input
                                                            @inputEvent="
                                                                serviceTaxRateChanged(
                                                                    $event,
                                                                    index
                                                                )
                                                            "
                                                            class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                service.taxRate
                                                            "
                                                            name="taxRate"
                                                            :allowNegative="
                                                                true
                                                            "
                                                            :isReadonly="true"
                                                        />
                                                    </div>
                                                </td>
                                                <td class="pl-5 align-top">
                                                    <div class="flex">
                                                        <number-input
                                                            @inputEvent="
                                                                serviceNettoTotalChanged(
                                                                    $event,
                                                                    index
                                                                )
                                                            "
                                                            class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                service.nettoTotal
                                                            "
                                                            name="nettoTotal"
                                                            :allowNegative="
                                                                true
                                                            "
                                                            :isReadonly="true"
                                                        />
                                                    </div>
                                                </td>
                                                <td class="text-center"></td>
                                            </tr>
                                            <tr
                                                v-if="
                                                    additionalDescriptionToggled[
                                                        service.id
                                                    ]
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
                                                            service.additionalDescription
                                                        "
                                                        class="pb-1 pr-6 w-full"
                                                        :isReadonly="true"
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
                                <table class="w-full whitespace-nowrap">
                                    <thead>
                                        <tr>
                                            <th
                                                style="width: 3%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Pos") }}
                                            </th>
                                            <th
                                                style="width: 7%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Article Nr.") }}
                                            </th>
                                            <th
                                                style="width: 20%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Name") }}
                                            </th>
                                            <th
                                                style="width: 12%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{
                                                    $t("Quantity") +
                                                    "/" +
                                                    $t("Unit")
                                                }}
                                            </th>
                                            <th
                                                style="width: 10%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Hourly Rate") }}
                                            </th>
                                            <th
                                                style="width: 10%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Discount") }}(%)
                                            </th>
                                            <th
                                                v-if="!form.applyReverseChange"
                                                style="width: 10%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Tax") }}(%)
                                            </th>
                                            <th
                                                v-if="!form.applyReverseChange"
                                                style="width: 13%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Tax Rate") }}
                                            </th>
                                            <th
                                                style="width: 13%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Netto Total") }}
                                            </th>
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
                                                <td class="pl-5 align-top py-2">
                                                    <div class="md:w-2/3">
                                                        <p>{{ index + 1 }}</p>
                                                    </div>
                                                </td>
                                                <td class="pl-5 align-top py-2">
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
                                                <td
                                                    class="pl-5 align-top py-2 product-grouped-category"
                                                >
                                                    <p class="font-bold">
                                                        {{
                                                            category?.name ??
                                                            category?.categoryName ??
                                                            ""
                                                        }}
                                                    </p>
                                                    <li
                                                        class="ml-2"
                                                        v-for="(
                                                            product,
                                                            productIndex
                                                        ) in category.products"
                                                        :key="
                                                            'service-child-' +
                                                            productIndex
                                                        "
                                                    >
                                                        {{ product?.name }}
                                                    </li>
                                                </td>
                                                <td class="pl-5 align-top py-2">
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
                                                            readonly
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
                                                <td class="pl-5 align-top">
                                                    <div class="flex">
                                                        <number-input
                                                            :maximumFractionDigits="
                                                                2
                                                            "
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
                                                            :isReadonly="true"
                                                        />
                                                    </div>
                                                </td>
                                                <td class="pl-5 align-top py-2">
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
                                                            readonly
                                                        />
                                                    </div>
                                                </td>
                                                <td
                                                    v-if="
                                                        !form.applyReverseChange
                                                    "
                                                    class="pl-5 align-top py-2"
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
                                                            readonly
                                                        />
                                                    </div>
                                                </td>
                                                <td
                                                    v-if="
                                                        !form.applyReverseChange
                                                    "
                                                    class="pl-5 align-top"
                                                >
                                                    <div class="flex">
                                                        <number-input
                                                            :maximumFractionDigits="
                                                                2
                                                            "
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
                                                            :isReadonly="true"
                                                            :allowNegative="
                                                                true
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td class="pl-5 align-top">
                                                    <div class="flex">
                                                        <number-input
                                                            :maximumFractionDigits="
                                                                2
                                                            "
                                                            @inputEvent="
                                                                serviceNettoTotalChangedCategory(
                                                                    $event,
                                                                    index
                                                                )
                                                            "
                                                            class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            v-model="
                                                                category.nettoTotal
                                                            "
                                                            name="nettoTotal"
                                                            :isReadonly="true"
                                                            :allowNegative="
                                                                true
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr
                                                v-if="
                                                    additionalDescriptionToggled[
                                                        category.id
                                                    ]
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
                                                        :isReadonly="true"
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
                                <table class="w-full whitespace-nowrap">
                                    <thead>
                                        <tr>
                                            <th
                                                style="width: 3%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("POS") }}
                                            </th>
                                            <th
                                                style="width: 10%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Article Nr.") }}
                                            </th>
                                            <th
                                                style="width: 20%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Name") }}
                                            </th>
                                            <th
                                                style="width: 8%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Software") }}
                                            </th>
                                            <th
                                                style="width: 12%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Quantity") }}/{{
                                                    $t("Unit")
                                                }}
                                            </th>
                                            <th
                                                style="width: 13%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Hourly Rate") }}
                                            </th>
                                            <th
                                                style="width: 6%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Discount") }}
                                            </th>
                                            <th
                                                style="width: 13%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Period") }}
                                            </th>
                                            <th
                                                style="width: 13%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
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
                                                    <div>
                                                        {{ index + 1 }}
                                                    </div>
                                                </td>
                                                <td class="pl-5 align-top py-2">
                                                    <div>
                                                        {{
                                                            product.articleNumber
                                                        }}
                                                    </div>
                                                </td>
                                                <td class="pl-5 align-top py-2">
                                                    <div>
                                                        <input
                                                            :title="
                                                                product.name
                                                            "
                                                            type="text"
                                                            :class="{
                                                                Perror: errors[
                                                                    `products.${index}.name`
                                                                ],
                                                            }"
                                                            readonly
                                                            v-model="
                                                                product.name
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td class="pl-5 align-top py-2">
                                                    <div>
                                                        {{
                                                            product
                                                                .productSoftware
                                                                ?.name
                                                        }}
                                                    </div>
                                                </td>
                                                <td class="pl-5 align-top py-2">
                                                    <div style="display: flex">
                                                        <input
                                                            @input="
                                                                changedQuantityAMS(
                                                                    index,
                                                                    $event
                                                                )
                                                            "
                                                            class="w-full appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                product.quantity
                                                            "
                                                            name="quantity"
                                                            readonly
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
                                                <td
                                                    class="pl-5 text-center align-top py-2"
                                                >
                                                    <div>
                                                        <input
                                                            @input="
                                                                changedQuantityAMS(
                                                                    index,
                                                                    $event
                                                                )
                                                            "
                                                            class="w-full appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                product.hourlyRate
                                                            "
                                                            name="hourlyRate"
                                                            readonly
                                                        />
                                                    </div>
                                                </td>
                                                <td
                                                    class="pl-5 text-center align-top py-2"
                                                >
                                                    <div>
                                                        <input
                                                            @input="
                                                                changedQuantityAMS(
                                                                    index,
                                                                    $event
                                                                )
                                                            "
                                                            class="w-full appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                product.discount
                                                            "
                                                            name="discount"
                                                            readonly
                                                        />
                                                    </div>
                                                </td>
                                                <td
                                                    class="pl-5 text-center align-top py-2"
                                                >
                                                    <div>
                                                        <select-input
                                                            v-if="
                                                                product.paymentPeriod
                                                            "
                                                            v-model="
                                                                product.paymentPeriod
                                                            "
                                                            :key="
                                                                product.paymentPeriod
                                                            "
                                                            :disabled="true"
                                                        >
                                                            <option
                                                                v-for="period in periods.data"
                                                                :key="
                                                                    'period-' +
                                                                    period.id
                                                                "
                                                                :value="period"
                                                            >
                                                                {{
                                                                    period.name
                                                                }}
                                                            </option>
                                                        </select-input>
                                                    </div>
                                                </td>
                                                <td
                                                    class="pl-5 text-center align-top"
                                                >
                                                    <div>
                                                        <number-input
                                                            :maximumFractionDigits="
                                                                2
                                                            "
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
                                                            :isReadonly="true"
                                                            :allowNegative="
                                                                true
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td class="text-center"></td>
                                            </tr>
                                            <tr
                                                v-if="
                                                    additionalDescriptionToggled[
                                                        product.id
                                                    ]
                                                "
                                            >
                                                <td></td>
                                                <td></td>
                                                <td class="pl-6">
                                                    <TextAreaInput
                                                        style="
                                                            overflow: visible !important;
                                                        "
                                                        :isReadonly="true"
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
                                        </template>
                                    </tbody>
                                </table>
                                <br />
                                <div
                                    v-if="Object.keys(ams).length > 0"
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
                                            v-for="(
                                                value, key
                                            ) in computeMwstAms"
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
                            </div>
                            <div
                                v-if="form.category === 'hosting'"
                                class="mt-7 overflow-x-auto"
                            >
                                <table class="w-full whitespace-nowrap">
                                    <thead>
                                        <tr>
                                            <th
                                                style="width: 3%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("POS") }}
                                            </th>
                                            <th
                                                style="width: 10%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Article Nr.") }}
                                            </th>
                                            <th
                                                style="width: 20%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Name") }}
                                            </th>
                                            <th
                                                style="width: 10%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Software") }}
                                            </th>
                                            <th
                                                style="width: 8%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Quantity") }}
                                            </th>
                                            <th
                                                style="width: 13%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Price Per Period") }}
                                            </th>
                                            <th
                                                style="width: 8%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Discount") }}
                                            </th>
                                            <th
                                                style="width: 13%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Period") }}
                                            </th>
                                            <th
                                                style="width: 13%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
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
                                            :tabindex="index"
                                        >
                                            <tr
                                                class="focus:outline-none h-16 border border-gray-100 rounded"
                                            >
                                                <td class="pl-5 align-top py-2">
                                                    <div>
                                                        {{ index + 1 }}
                                                    </div>
                                                </td>
                                                <td class="pl-5 align-top py-2">
                                                    <div>
                                                        {{
                                                            product.articleNumber
                                                        }}
                                                    </div>
                                                </td>
                                                <td class="pl-5 align-top py-2">
                                                    <div>
                                                        <input
                                                            :title="
                                                                product.name
                                                            "
                                                            type="text"
                                                            :class="{
                                                                Perror: errors[
                                                                    `products.${index}.name`
                                                                ],
                                                            }"
                                                            readonly
                                                            v-model="
                                                                product.name
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td class="pl-5 align-top py-2">
                                                    <div>
                                                        {{
                                                            product
                                                                .productSoftware
                                                                ?.name
                                                        }}
                                                    </div>
                                                </td>
                                                <td class="pl-5 align-top py-2">
                                                    <div>
                                                        <input
                                                            @input="
                                                                changedQuantityHosting(
                                                                    index,
                                                                    $event
                                                                )
                                                            "
                                                            readonly
                                                            class="w-full appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                product.quantity
                                                            "
                                                            name="quantity"
                                                        />
                                                    </div>
                                                </td>
                                                <td
                                                    class="pl-5 text-center align-top"
                                                >
                                                    <div>
                                                        <number-input
                                                            :maximumFractionDigits="
                                                                2
                                                            "
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
                                                            :isReadonly="true"
                                                            name="pricePerPeriod"
                                                            :allowNegative="
                                                                true
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td
                                                    class="pl-5 text-center align-top py-2"
                                                >
                                                    <div>
                                                        <input
                                                            @input="
                                                                changedQuantityHosting(
                                                                    index,
                                                                    $event
                                                                )
                                                            "
                                                            class="w-full appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                product.discount
                                                            "
                                                            readonly
                                                            name="discount"
                                                        />
                                                    </div>
                                                </td>
                                                <td
                                                    class="pl-5 text-center align-top py-2"
                                                >
                                                    <div>
                                                        <select-input
                                                            :key="
                                                                product.paymentPeriod
                                                            "
                                                            v-model="
                                                                product.paymentPeriod
                                                            "
                                                            :isDisabled="true"
                                                        >
                                                            <option
                                                                v-for="period in periods.data"
                                                                :key="
                                                                    'period-' +
                                                                    period.id
                                                                "
                                                                :value="period"
                                                            >
                                                                {{
                                                                    period.name
                                                                }}
                                                            </option>
                                                        </select-input>
                                                    </div>
                                                </td>
                                                <td
                                                    class="pl-5 text-center align-top"
                                                >
                                                    <div>
                                                        <number-input
                                                            :maximumFractionDigits="
                                                                2
                                                            "
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
                                                            :allowNegative="
                                                                true
                                                            "
                                                            name="nettoTotal"
                                                            :isReadonly="true"
                                                        />
                                                    </div>
                                                </td>
                                                <td class="text-center"></td>
                                            </tr>
                                            <tr
                                                v-if="
                                                    additionalDescriptionToggled[
                                                        product.id
                                                    ]
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
                                                        :isReadonly="true"
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
                                    v-if="Object.keys(hosting).length > 0"
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
                                                        computeSummaryHosting,
                                                        globalLanguage
                                                    )
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                v-if="form.category === 'cloud-software'"
                                class="mt-7 overflow-x-auto"
                            >
                                <table class="w-full whitespace-nowrap">
                                    <thead>
                                        <tr>
                                            <th
                                                style="width: 3%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("POS") }}
                                            </th>
                                            <th
                                                style="width: 10%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Article Nr.") }}
                                            </th>
                                            <th
                                                style="width: 20%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Name") }}
                                            </th>
                                            <th
                                                style="width: 6%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Software") }}
                                            </th>
                                            <th
                                                style="width: 6%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Quantity") }}
                                            </th>
                                            <th
                                                style="width: 6%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Duration") }}
                                            </th>
                                            <th
                                                style="width: 13%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Product Price") }}
                                            </th>
                                            <th
                                                style="width: 8%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Discount") }}
                                            </th>
                                            <th
                                                style="width: 13%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                            >
                                                {{ $t("Period") }}
                                            </th>
                                            <th
                                                style="width: 13%"
                                                class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
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
                                            :tabindex="index"
                                        >
                                            <tr
                                                class="focus:outline-none h-16 border border-gray-100 rounded"
                                            >
                                                <td class="pl-5 align-top py-2">
                                                    <div>
                                                        {{ index + 1 }}
                                                    </div>
                                                </td>
                                                <td class="pl-5 align-top py-2">
                                                    <div>
                                                        {{
                                                            product.articleNumber
                                                        }}
                                                    </div>
                                                </td>
                                                <td class="pl-5 align-top py-2">
                                                    <div>
                                                        <input
                                                            :title="
                                                                product.name
                                                            "
                                                            type="text"
                                                            :class="{
                                                                Perror: errors[
                                                                    `products.${index}.name`
                                                                ],
                                                            }"
                                                            readonly
                                                            v-model="
                                                                product.name
                                                            "
                                                        />
                                                    </div>
                                                </td>
                                                <td class="pl-5 align-top py-2">
                                                    <div>
                                                        {{
                                                            product
                                                                .productSoftware
                                                                ?.name
                                                        }}
                                                    </div>
                                                </td>
                                                <td class="pl-5">
                                                    <div>
                                                        <input
                                                            @input="
                                                                changedQuantityCloud(
                                                                    index,
                                                                    $event
                                                                )
                                                            "
                                                            class="w-full appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                product.quantity
                                                            "
                                                            readonly
                                                            name="quantity"
                                                        />
                                                    </div>
                                                </td>
                                                <td class="pl-5 align-top py-2">
                                                    <div
                                                        v-if="
                                                            product.type ===
                                                            'cloud-software'
                                                        "
                                                    >
                                                        <input
                                                            @input="
                                                                changedQuantityCloud(
                                                                    index,
                                                                    $event
                                                                )
                                                            "
                                                            class="w-full appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                product.duration
                                                            "
                                                            readonly
                                                            name="duration"
                                                        />
                                                    </div>
                                                </td>
                                                <td
                                                    class="pl-5 text-center align-top"
                                                >
                                                    <div>
                                                        <number-input
                                                            :maximumFractionDigits="
                                                                2
                                                            "
                                                            @inputEvent="
                                                                changedQuantityCloud(
                                                                    index,
                                                                    $event
                                                                )
                                                            "
                                                            class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                product.salePrice
                                                            "
                                                            :allowNegative="
                                                                true
                                                            "
                                                            name="salePrice"
                                                            :isReadonly="true"
                                                        />
                                                    </div>
                                                </td>
                                                <td
                                                    class="pl-5 text-center align-top py-2"
                                                >
                                                    <div>
                                                        <input
                                                            @input="
                                                                changedQuantityCloud(
                                                                    index,
                                                                    $event
                                                                )
                                                            "
                                                            class="w-full appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                                            type="number"
                                                            v-model="
                                                                product.discount
                                                            "
                                                            readonly
                                                            name="discount"
                                                        />
                                                    </div>
                                                </td>
                                                <td
                                                    class="pl-5 text-center align-top py-2"
                                                >
                                                    <div>
                                                        <select-input
                                                            :key="
                                                                product.paymentPeriod
                                                            "
                                                            v-model="
                                                                product.paymentPeriod
                                                            "
                                                            :isDisabled="true"
                                                        >
                                                            <option
                                                                v-for="period in periods.data"
                                                                :key="
                                                                    'period-' +
                                                                    period.id
                                                                "
                                                                :value="period"
                                                            >
                                                                {{
                                                                    period.name
                                                                }}
                                                            </option>
                                                        </select-input>
                                                    </div>
                                                </td>
                                                <td
                                                    class="pl-5 text-center align-top"
                                                >
                                                    <div>
                                                        <number-input
                                                            :maximumFractionDigits="
                                                                2
                                                            "
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
                                                            :allowNegative="
                                                                true
                                                            "
                                                            :isReadonly="true"
                                                            name="nettoTotal"
                                                        />
                                                    </div>
                                                </td>
                                                <td class="text-center"></td>
                                            </tr>
                                            <tr
                                                v-if="
                                                    additionalDescriptionToggled[
                                                        product.id
                                                    ]
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
                                                        :isReadonly="true"
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
                                    v-if="Object.keys(cloud).length > 0"
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
                                            v-for="(
                                                value, key
                                            ) in computeMwstCloud"
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
                                                        computeSummaryCloud,
                                                        globalLanguage
                                                    )
                                                }}
                                            </p>
                                        </div>
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

            <div class="card my-5">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Invoice Details") }}</h3>
                </div>
                <div class="card-body">
                    <div class="flex flex-wrap">
                        <div class="pr-6 w-full lg:w-1/3 mb-8">
                            <label class="form-label font-bold"
                                >{{ $t("Payment Period") }}:</label
                            >
                            <p>
                                {{
                                    periods.data?.find(
                                        (period) => period.id == form.invoicePeriod
                                    )?.name ?? ""
                                }}
                            </p>
                        </div>
    
                        <div class="pr-6 w-full lg:w-1/3 mb-8">
                            <label class="form-label font-bold"
                                >{{ $t("Terms Of Payment") }}:</label
                            >
                            <p>
                                {{ form.termsOfPayment?.name }} |
                                {{ form.termsOfPayment?.paymentTerms }}
                            </p>
                        </div>
    
                        <div class="pr-6 w-full lg:w-1/3 mb-8">
                            <label class="form-label font-bold"
                                >{{ $t("Custom Notes Field") }}:</label
                            >
                            <p>
                                {{ form.customNotesFields }}
                            </p>
                        </div>
    
                        <div class="pr-6 w-full lg:w-1/3 mb-8">
                            <label class="form-label font-bold"
                                >{{ $t("Payment Terms") }}:</label
                            >
                            <p>
                                {{ form.paymentTerms }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
</template>

<script>
import Icon from "../../Components/Icon.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import NumberInput from "../../Components/NumberInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import diffInMonthsMixin from "@/Mixins/diffInMonthsMixin";
import TextAreaInput from "../../Components/TextareaInput.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";

export default {
    mixins: [diffInMonthsMixin],
    components: {
        Icon,
        LoadingButton,
        SelectInput,
        NumberInput,
        TextAreaInput,
        PageHeader
    },
    props: {
        isCorrection: { default: false, type: Boolean },
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Invoices Template",
                    to: "/invoices-templates?page="+this.$route.query.page,
                },
                {
                    text: "Show",
                    active: true,
                },
            ],
            moduleHistory: [],
            invoiceTemplateEnums: {
                invoice: "invoiceTemplateId",
                "invoice-correction": "invoiceCorrectionTemplateId",
                "invoice-storno": "invoiceStornoTemplateId",
            },
            additionalDescriptionToggled: {},
            productTypeEnums: {
                software: "software",
                service: "service",
                ams: "ams",
                hosting: "hosting",
                "cloud-software": "cloudSoftware",
            },
            hosting: [], //holds the selected product of hosting type
            hostingProducts: [], // all the hosting products that are loaded in the products modal, sent as prop to select-products-modal
            cloud: [], //holds the selected product of cloud type
            cloudSoftwareProducts: [], // all the cloud products that are loaded in the products modal, sent as prop to select-products-modal
            showPerformanceRecord: true,
            invoiceTypeEnums: {
                invoice: "Invoice",
                "invoice-correction": "Invoice Correction",
                "invoice-storno": "Invoice Storno",
            },
            categories: [],
            projects: [],
            user: null,
            customers: [],
            products: { data: [], links: [] },
            systems: { data: [], links: [] },
            invoiceStatuses: [],
            invoiceTypes: [],
            pInvoices: {},
            selectedProductType: "softwareLicences",
            softwareLicences: [],
            softwareLicencesTax: [],
            softwareMaintenance: [],
            softwareMaintenanceTax: [],
            services: [],
            ams: [],
            serviceToggle: false,
            amsToggle: false,
            form: {
                performanceRecord: "",
                paymentTerms: "",
                category: "license",
                invoicePeriod: "",
                paymentPeriod: "",
                termsOfPayment: "",
                customNotesFields: "",
                invoiceStatus: "",
                projectId: "",
                startDateExpression: "",
                endDateExpression: "",
                createDateExpression: "",
                customers: {},
                products: [],
                systems: {},
                groupedBy: "nothing",
                contactPerson: "",
                draftStatusChangeDate: "",
                applyReverseChange: false,
            },
            productToggle: false,
            customerToggle: false,
            systemToggle: false,
            isInstall: false,
            isInstallation: true,
            commands: [],
        };
    },
    async mounted() {
        this.$store.commit("showLoader", true);
        await this.$store.dispatch("termsOfPayment/list", {
            perPage: 25,
            page: 1,
        });
        this.$store
            .dispatch("products/productsWithQuantity", {
                type: "service",
            })
            .then((res) => {
                this.serviceProducts = res?.data?.products ?? {
                    data: [],
                    links: [],
                };
            });
        this.$store
            .dispatch("products/productsWithQuantity", {
                type: "ams",
            })
            .then((res) => {
                this.amsProducts = res?.data?.products ?? {
                    data: [],
                    links: [],
                };
            });
        await this.$store.dispatch("periods/list");
        await this.$store.dispatch("units/list");
        this.refresh();
    },
    computed: {
        ...mapGetters("units", ["units"]),
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("periods", ["periods"]),
        ...mapGetters("performanceRecords", ["performanceRecords"]),
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
        // firstYearMaintenance() {
        //     return (
        //         this.getMonthDifference(
        //             this.form.startDate,
        //             this.form.endDate
        //         ) <= 11
        //     );
        // },
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
                this.formatDateLite(
                    (() => {
                        if (
                            [
                                "maintenance",
                                "ams",
                                "hosting",
                                "cloud-software",
                            ].includes(this.form.category)
                        ) {
                            // return this.form.startDate;
                            return;
                        } else if (this.form.category === "service") {
                            return this.form.performanceRecord?.startDate ?? "";
                        }
                    })()
                ),
                this.globalLanguage
            )} - ${this.$dateFormatter(
                this.formatDateLite(
                    (() => {
                        if (
                            [
                                "maintenance",
                                "ams",
                                "hosting",
                                "cloud-software",
                            ].includes(this.form.category)
                        ) {
                            return this.form.endDate;
                        } else if (this.form.category === "service") {
                            return this.form.performanceRecord?.endDate ?? "";
                        }
                    })()
                ),
                this.globalLanguage
            )}`;
            return categoryDateString;
        },
        dropdownStyles() {
            return {
                "--elem-hover-bg-color": "#312E81",
                "--elem-selected-bg-color": "#312E81",
            };
        },
        customerData() {
            return this.form.customers;
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
        },
        customerData(value) {
            this.$store
                .dispatch("systems/search", value?.id ?? "")
                .then((res) => {
                    this.systems = res?.data;
                });
        },
    },
    methods: {
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
            // if (this.firstYearMaintenance) {
            //     const diffInMonths = this.getMonthDifference(
            //         this.form.startDate,
            //         this.form.endDate
            //     );
            //     sum = sum * (+diffInMonths / 12);
            // }
            return sum;
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
                    this.addServices([service]);
                }
            }
        },
        async addPaymentTerms() {
            await this.$nextTick();
            this.form.paymentTerms = this.form?.termsOfPayment?.invoiceText;
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
                new Set(category.products.map((product) => product.unit))
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
                new Set(this.services.map((service) => service.category.id))
            ).map((categoryId) => {
                const category =
                    this.services.find(
                        (service) => service.category.id === categoryId
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
            }
        },
        async refresh() {
            try {
                const response = await this.$store.dispatch(
                    "invoiceTemplates/show",
                    this.$route.params.id
                );
                this.pInvoices = response?.data?.pInvoices ?? {};
                this.moduleHistory = response?.data?.moduleHistory ?? [];
                this.customers = response?.data?.customers ?? this.customers;
                this.systems = response?.data?.systems ?? this.systems;
                this.products = response?.data?.products ?? this.products;
                this.invoiceTypes =
                    response?.data?.invoiceTypes ?? this.invoiceTypes;
                this.invoiceStatuses =
                    response?.data?.invoiceStatuses ?? this.invoiceStatuses;
                const responseUser = await this.$store.dispatch("auth/show", {
                    id: this.pInvoices?.userId,
                });
                this.user = responseUser?.data;
                this.form = {
                    category: this.pInvoices?.category,
                    applyReverseChange: this.pInvoices?.applyReverseChange,
                    groupedBy: this.pInvoices?.groupedBy ?? "nothing",
                    userId: this.pInvoices?.userId,
                    userFirstName: responseUser?.data?.first_name,
                    userLastName: responseUser?.data?.last_name,
                    userEmail: responseUser?.data?.email,
                    userPhone: responseUser?.data?.mobile,
                    // invoiceNumber: this.pInvoices?.invoiceNumber,
                    paymentTerms: this.pInvoices?.paymentTerms ?? "",
                    invoiceStatus: this.pInvoices?.invoiceStatus,

                    startDateExpression: this.pInvoices?.startDateExpression,
                    endDateExpression: this.pInvoices?.endDateExpression,
                    createDateExpression: this.pInvoices?.createDateExpression,

                    invoicePeriod: this.pInvoices?.invoicePeriod,

                    termsOfPayment:
                        this.termsOfPayment?.data?.find(
                            (terms) =>
                                terms.id == this.pInvoices?.termsOfPayment
                        ) ?? "",
                    customNotesFields: this.pInvoices?.notes,
                    customers: this.pInvoices?.customer,
                    systems: this.pInvoices?.systems ?? {},
                    projectId: this.pInvoices?.project ?? {},
                    contactPerson: "",
                    draftStatusChangeDate: this.pInvoices?.draftStatusChangeDate
                        ? new Date(
                              typeof this.pInvoices?.draftStatusChangeDate ===
                              "string"
                                  ? this.pInvoices?.draftStatusChangeDate
                                        .replace(/-/g, "\/")
                                        .replace(/T.+/, "")
                                  : ""
                          )
                        : null,
                };
                // fetch the contactPerson from the show API
                if (this.pInvoices?.contactPerson)
                    this.$store
                        .dispatch("auth/show", {
                            id: this.pInvoices?.contactPerson,
                        })
                        .then((res) => {
                            this.form.contactPerson = res?.data ?? "";
                        });
                const payload = {
                    // startDate: this.pInvoices.startDate,
                    // endDate: this.pInvoices.endDate,
                    selectedIds: [this.pInvoices.performanceRecord],
                };
                if (!this.pInvoices.performanceRecord) {
                    payload["showUnapproved"] = true;
                }
                await this.$store.dispatch("performanceRecords/list", payload);
                this.showPerformanceRecord = false;
                this.form.performanceRecord =
                    this.performanceRecords.data.find(
                        (record) =>
                            record.id == this.pInvoices.performanceRecord
                    ) ?? "";
                await this.$nextTick();
                this.showPerformanceRecord = true;
                this.$store
                    .dispatch("projects/list", {
                        page: 1,
                        perPage: 25,
                        companyId: this.form.customers?.id,
                    })
                    .then((res) => {
                        this.projects = res?.data?.data ?? [];
                    });
                this.$nextTick(() => {
                    if (
                        this.pInvoices?.category === "license" ||
                        this.pInvoices?.category === "maintenance"
                    ) {
                        this.softwareLicences = (
                            response?.data?.pInvoices?.products ?? []
                        )?.map((product) => {
                            this.additionalDescriptionToggled[product.id] = (
                                product?.additionalDescription ?? ""
                            ).length;
                            return {
                                ...product,
                                totalSalePriceAdjustedForQuantity:
                                    +product.salePrice * +product.quantity,
                                additionalDescription:
                                    product?.additionalDescription ?? "",
                            };
                        });
                        this.softwareLicencesTax =
                            this.calculateTax("softwareLicences");
                        this.softwareMaintenance = (
                            response?.data?.pInvoices?.products ?? []
                        )?.map((product) => {
                            this.additionalDescriptionToggled[product.id] = (
                                product?.additionalDescription ?? ""
                            ).length;
                            return {
                                ...product,
                                maintenancePrice:
                                    (+product.salePrice *
                                        +product.quantity *
                                        +product.maintenanceRate) /
                                    100,
                                baseMaintenancePrice:
                                    (+product.salePrice *
                                        +product.maintenanceRate) /
                                    100,
                                totalSalePriceAdjustedForQuantity:
                                    +product.salePrice * +product.quantity,
                                additionalDescription:
                                    product?.additionalDescription ?? "",
                            };
                        });
                        this.softwareMaintenanceTax = this.calculateTax(
                            "softwareMaintenance"
                        );
                    } else if (this.pInvoices?.category === "service") {
                        let products = [];
                        if (this.pInvoices?.groupedBy === "category") {
                            this.categories = this.pInvoices.categories.map(
                                (category) => {
                                    const categoryProducts = [
                                        ...category.products,
                                    ];
                                    const foundCategory = {
                                        ...(categoryProducts.find(
                                            (service) =>
                                                service.category.id ===
                                                category.categoryId
                                        )?.category ?? {}),
                                        products: categoryProducts,
                                    };
                                    products = [
                                        ...products,
                                        ...categoryProducts,
                                    ];
                                    const unit = this.unitForCategory({
                                        ...foundCategory,
                                        categoryProducts,
                                    });
                                    this.additionalDescriptionToggled[
                                        category.categoryId
                                    ] = (
                                        category?.additionalDescription ?? ""
                                    ).length;
                                    return {
                                        ...category,
                                        id: category.categoryId,
                                        products: category.products,
                                        unit: unit,
                                        quantity: category.quantity,
                                        hourlyRate: category.hourlyRate,
                                        discount: category.discount,
                                        nettoTotal: category.nettoTotal,
                                        tax: category.tax,
                                        taxRate: category.taxRate,
                                        bruttoTotal:
                                            +category.nettoTotal +
                                            +category.taxRate,
                                        additionalDescription:
                                            category?.additionalDescription ??
                                            "",
                                    };
                                }
                            );
                        } else {
                            products = [
                                ...products,
                                ...(this.pInvoices?.products ?? []),
                            ];
                        }
                        this.services = products.map((product) => {
                            const totalWithoutDiscount =
                                +product.quantity * +product.hourlyRate;
                            const discountAmount =
                                (+totalWithoutDiscount * +product.discount) /
                                100;
                            const nettoTotal =
                                +totalWithoutDiscount - +discountAmount;
                            const taxRate = (+nettoTotal * +product.tax) / 100;
                            this.additionalDescriptionToggled[product.id] = (
                                product?.additionalDescription ?? ""
                            ).length;
                            return {
                                ...product,
                                tax: +product.tax,
                                nettoTotal: nettoTotal,
                                taxRate: taxRate,
                                bruttoTotal: +nettoTotal + +taxRate,
                                additionalDescription:
                                    product?.additionalDescription ?? "",
                            };
                        });
                    } else if (this.pInvoices?.category === "ams") {
                        this.ams = (this.pInvoices?.products ?? []).map(
                            (product) => {
                                const totalWithoutDiscount =
                                    +product.hourlyRate * +product.quantity;
                                const discountAmount =
                                    (+totalWithoutDiscount *
                                        +product.discount) /
                                    100;
                                const nettoTotal =
                                    +totalWithoutDiscount - +discountAmount;
                                const taxRate =
                                    (+nettoTotal * +product.tax) / 100;
                                this.additionalDescriptionToggled[product.id] =
                                    (
                                        product?.additionalDescription ?? ""
                                    ).length;
                                return {
                                    ...product,
                                    paymentPeriod: product?.paymentPeriod,
                                    nettoTotal: nettoTotal,
                                    taxRate: taxRate,
                                    additionalDescription:
                                        product?.additionalDescription ?? "",
                                };
                            }
                        );
                    } else if (this.pInvoices?.category === "hosting") {
                        this.hosting = (this.pInvoices?.products ?? []).map(
                            (product) => {
                                const totalWithoutDiscount =
                                    +product.quantity * +product.pricePerPeriod;
                                const discountAmount =
                                    (+totalWithoutDiscount *
                                        +product.discount) /
                                    100;
                                const nettoTotal =
                                    +totalWithoutDiscount - +discountAmount;
                                const taxRate =
                                    (+nettoTotal * +product.tax) / 100;
                                this.additionalDescriptionToggled[product.id] =
                                    (
                                        product?.additionalDescription ?? ""
                                    ).length;
                                return {
                                    ...product,
                                    paymentPeriod: product?.paymentPeriod,
                                    nettoTotal: nettoTotal,
                                    taxRate: taxRate,
                                    additionalDescription:
                                        product?.additionalDescription ?? "",
                                };
                            }
                        );
                    } else if (this.pInvoices?.category === "cloud-software") {
                        this.cloud = (this.pInvoices?.products ?? []).map(
                            (product) => {
                                const totalWithoutDiscount =
                                    +product.quantity *
                                    (+product.salePrice *
                                        (+product.duration ?? 1));
                                const discountAmount =
                                    (+totalWithoutDiscount *
                                        +product.discount) /
                                    100;
                                const nettoTotal =
                                    +totalWithoutDiscount - +discountAmount;
                                const taxRate =
                                    (+nettoTotal * +product.tax) / 100;
                                this.additionalDescriptionToggled[product.id] =
                                    (
                                        product?.additionalDescription ?? ""
                                    ).length;
                                return {
                                    ...product,
                                    paymentPeriod: product?.paymentPeriod,
                                    nettoTotal: nettoTotal,
                                    taxRate: taxRate,
                                    additionalDescription:
                                        product?.additionalDescription ?? "",
                                };
                            }
                        );
                    }
                });
            } catch (e) {
                console.log(e);
            }
            finally {
                this.$store.commit("showLoader", false);
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
        async serviceQuantityChanged(event, index) {
            await this.$nextTick();
            const service = this.services[index];
            const totalWithoutDiscount =
                +service.quantity * +service.hourlyRate;
            const discountAmount =
                (+totalWithoutDiscount * +service.discount) / 100;
            const nettoTotal = +totalWithoutDiscount - +discountAmount;
            const taxRate = (+nettoTotal * +service.tax) / 100;
            service.nettoTotal = nettoTotal.toFixed(2);
            service.taxRate = taxRate.toFixed(2);
            service.bruttoTotal = (+nettoTotal + +taxRate).toFixed(2);
            this.services[index] = { ...service };
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
        toggleModal() {
            if (
                this.form.category === "license" ||
                this.form.category === "maintenance"
            ) {
                this.productToggle = true;
            } else if (this.form.category === "service") {
                this.serviceToggle = true;
            } else if (this.form.category === "ams") {
                this.amsToggle = true;
            }
        },
        async addAMS(products) {
            this.amsToggle = false;
            this.globalPeriod = products?.[0]?.paymentPeriod?.id ?? "";
            await this.$nextTick();
            this.ams = products.map((product) => {
                const totalWithoutDiscount =
                    +product.hourlyRate * +product.quantity;
                const discountAmount =
                    (+totalWithoutDiscount * +product.discount) / 100;
                const nettoTotal = +totalWithoutDiscount - +discountAmount;
                const taxRate = (+nettoTotal * +product.tax) / 100;
                return {
                    ...product,
                    paymentPeriod: product?.paymentPeriod?.id,
                    nettoTotal: nettoTotal,
                    taxRate: taxRate,
                };
            });
        },
        addServices(products) {
            this.services = products.map((product) => {
                const totalWithoutDiscount =
                    +product.quantity * +product.hourlyRate;
                const discountAmount =
                    (+totalWithoutDiscount * +product.discount) / 100;
                const nettoTotal = +totalWithoutDiscount - +discountAmount;
                const taxRate = (+nettoTotal * +product.tax) / 100;
                return {
                    ...product,
                    tax: +product.tax,
                    nettoTotal: nettoTotal,
                    taxRate: taxRate,
                    bruttoTotal: +nettoTotal + +taxRate,
                };
            });
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
        addSystems(system) {
            this.form.systems = system;
            this.systemToggle = false;
        },
        async addCustomer(customer) {
            this.form.customers = {};
            await this.$nextTick();
            this.form.projectId = null;
            this.form.customers = customer;
            this.form.termsOfPayment =
                this.termsOfPayment?.data?.find(
                    (terms) => terms.id == customer?.termsOfPayment
                ) ?? "";
            this.customerToggle = false;
            await this.$nextTick();
            const response = await this.$store.dispatch("projects/list", {
                page: 1,
                perPage: 25,
                companyId: customer?.id,
            });
            this.projects = response?.data?.data ?? [];
        },
        addProducts(products) {
            this.softwareLicences = products;
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
            this.softwareMaintenance = JSON.parse(
                JSON.stringify(products)
            )?.map((product) => {
                return {
                    ...product,
                    totalSalePriceAdjustedForQuantity:
                        +product.salePrice * +product.quantity,
                };
            });
            this.softwareMaintenance.forEach((item, index) => {
                this.calculateProductValue(index, "softwareMaintenance");
            });
            this.softwareMaintenanceTax = this.calculateTax(
                "softwareMaintenance"
            );
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
                } else if (event?.target?.name !== "maintenancePrice") {
                    products[index].maintenancePrice =
                        parseFloat(
                            products[index].totalSalePriceAdjustedForQuantity
                        ).toFixed(2) * products[index].quantity;
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
        async store() {
            if (this.isCorrection) {
                try {
                    this.$store.commit("isLoading", true);
                    await this.$store.dispatch("invoices/create", {
                        ...this.form,
                    });
                    await this.$store.dispatch("invoices/list");
                    this.$router.push("/invoices");
                } catch (e) {}
            } else {
                try {
                    let products = [];
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
                            this.softwareMaintenanceTax?.[0]?.amount?.toFixed(
                                2
                            ),
                            this.globalLanguage
                        );
                        products = this.softwareMaintenance;
                    } else if (this.form.category === "service") {
                        this.form.totalAmount = this.computeSummary;
                        this.form.totalAmountWithoutTax = this.computeNetto;
                        this.form.totalTaxAmount = this.$formatter(
                            Object.values(this.computeMwst)?.[0],
                            this.globalLanguage
                        );
                        products = this.services.map((service) => {
                            return {
                                ...service,
                                totalPrice: this.computeSummary,
                                priceWithoutTax: this.computeNetto,
                            };
                        });
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
                    }
                    this.$store.commit("isLoading", true);
                    await this.$store.dispatch("invoices/update", {
                        id: this.pInvoices?.id,
                        data: {
                            ...this.form,
                            // dueDate: this.formatDateLite(this.form.dueDate),
                            products: products,
                            userId: this.user?.user_id ?? "",
                            projectId: this.form.projectId?.id ?? "",
                            termsOfPayment: this.form?.termsOfPayment?.id,
                        },
                    });
                    await this.$store.dispatch("invoices/list");
                    this.$router.push("/invoices");
                } catch (e) {
                    console.log(e);
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
};
</script>

<style></style>

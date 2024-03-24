<template>
    <div class="mb-5">
        <div v-if="!readonly || softwareLicences.length" class="mt-5">
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
                        <p class="self-center">
                            {{ $t("Software licences") }}
                        </p>
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
                                            style="width: 34%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Product Group") }}
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
                                            style="width: 20%"
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
                                            <td class="border-t">
                                                <a
                                                    href="javascript:void(0)"
                                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                                >
                                                    <input
                                                        v-if="!readonly"
                                                        v-model="
                                                            selectedProducts
                                                        "
                                                        type="checkbox"
                                                        class="border-gray-300 rounded h-4 w-4 self-center"
                                                        :value="product"
                                                    />
                                                    &nbsp;<span
                                                        class="self-center"
                                                        >{{
                                                            product.articleNumber
                                                        }}</span
                                                    >
                                                </a>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="">
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
                                            <td class="border-t">
                                                <a
                                                    href="javascript:void(0)"
                                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                                >
                                                    <span class="self-center">{{
                                                        product.productGroup
                                                    }}</span>
                                                </a>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="">
                                                    <number-input
                                                        class="appearance-none rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
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
                                                    v-if="!readonly"
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="!readonly || softwareMaintenance.length" class="mt-5">
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
                                            style="width: 34%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Product Group") }}
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
                                            style="width: 20%"
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
                                            <td class="border-t">
                                                <a
                                                    href="javascript:void(0)"
                                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                                >
                                                    <input
                                                        v-if="!readonly"
                                                        v-model="
                                                            selectedProducts
                                                        "
                                                        type="checkbox"
                                                        class="border-gray-300 rounded h-4 w-4 self-center"
                                                        :value="product"
                                                    />
                                                    &nbsp;<span
                                                        class="self-center"
                                                        >{{
                                                            product.articleNumber
                                                        }}</span
                                                    >
                                                </a>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="">
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
                                            <td class="border-t">
                                                <a
                                                    href="javascript:void(0)"
                                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                                >
                                                    <span class="self-center">{{
                                                        product.productGroup
                                                    }}</span>
                                                </a>
                                            </td>
                                            <td class="pl-5 align-top py-2">
                                                <div class="">
                                                    <number-input
                                                        class="appearance-none rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
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
                                                    v-if="!readonly"
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="!readonly || cloud.length" class="mt-5">
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
                                            style="width: 34%"
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                                        >
                                            {{ $t("Product Group") }}
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
                                            style="width: 13%"
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
                                            <td
                                                class="pl-5 align-top py-3 flex"
                                            >
                                                <input
                                                    v-if="!readonly"
                                                    v-model="selectedProducts"
                                                    type="checkbox"
                                                    class="border-gray-300 rounded h-4 w-4 self-center mr-2"
                                                    :value="product"
                                                />
                                                <input
                                                    v-if="!readonly"
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
                                                <div class="">
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
                                            <td class="border-t">
                                                <a
                                                    href="javascript:void(0)"
                                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                                >
                                                    <span class="self-center">{{
                                                        product.productGroup
                                                    }}</span>
                                                </a>
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
                                                        class="w-full appearance-none rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
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
                                                        class="w-full appearance-none border-2 border-gray-200 rounded py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
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
                                                <div class="">
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
                                                    v-if="!readonly"
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
                                            :key="childIndex"
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
                                                        :title="child.name"
                                                        class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:border-purple-500"
                                                        type="text"
                                                        :class="{
                                                            Perror: errors[
                                                                `products.${index}.name`
                                                            ],
                                                        }"
                                                        readonly
                                                        v-model="child.name"
                                                    />
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import NumberInput from "./NumberInput.vue";
import TextInput from "./TextInput.vue";
import TextAreaInput from "./TextareaInput.vue";
import SelectInput from "./SelectInput.vue";
import { mapGetters } from "vuex";

export default {
    props: {
        additionalDescriptionToggled: {
            type: Object,
            default: () => ({}),
        },
        softwareLicences: {
            type: Array,
            default: [],
        },
        softwareMaintenance: {
            type: Array,
            default: [],
        },
        cloud: {
            type: Array,
            default: [],
        },
        readonly: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            groupToggler: {
                cloud: {},
            },
            selectedProducts: [],
            softwareLicencesTax: [],
            softwareMaintenanceTax: [],
        };
    },
    components: {
        NumberInput,
        TextInput,
        SelectInput,
        TextAreaInput,
    },
    watch: {
        softwareLicences() {
            this.setProducts();
        },
        softwareMaintenance() {
            this.setProducts();
        },
    },
    mounted() {
        this.setProducts();
        if (!this.periods?.data?.length) {
            this.$store.dispatch("periods/list");
        }
    },
    computed: {
        ...mapGetters(["errors"]),
        ...mapGetters("periods", ["periods"]),
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
        totalAmountLicences() {
            const sum = this.softwareLicences.reduce((accumulator, object) => {
                return parseFloat(accumulator) + parseFloat(object.totalPrice);
            }, 0);
            return sum ? sum.toFixed(2) : 0;
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
        totalAmountWithoutTaxLicences() {
            const sum = this.softwareLicences.reduce((accumulator, object) => {
                return (
                    parseFloat(accumulator) + parseFloat(object.priceWithoutTax)
                );
            }, 0);

            return sum ? sum.toFixed(2) : 0;
        },
    },
    methods: {
        setProducts() {
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
            this.softwareMaintenance.forEach((item, index) => {
                this.calculateProductValue(index, "softwareMaintenance");
            });
            this.softwareMaintenanceTax = this.calculateTax(
                "softwareMaintenance"
            );
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
         * removes the child product from the corresponding type array
         * @param {index} index of the parent array
         * @param {childIndex} index of the child product
         * @param {type} the type of parent array
         */
        removeChildOption(index, childIndex, type) {
            if (type === "cloud") {
                this.cloud[index].children.splice(childIndex, 1);
            }
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
        /**
         * removes all the children from the corresponding type array
         * @param {index} index of the parent array
         * @param {type} type of the parent array
         * @param {event} input event
         */
        async removeAllChildren(index, type, event) {
            await this.$nextTick();
            if (!event.target.checked) {
                if (type === "cloud") {
                    this.cloud[index].children.splice(
                        0,
                        this.cloud[index].children.length
                    );
                }
            }
        },
        removeOption(index) {
            if (
                this.form.category === "license" ||
                this.form.category === "maintenance"
            ) {
                this.softwareLicences.splice(index, 1);
                this.softwareMaintenance.splice(index, 1);
            } else if (this.form.category === "cloud-software") {
                this.cloud.splice(index, 1);
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
                else if (category === "cloud")
                    this.cloud[index]["additionalDescription"] = "";
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
    },
};
</script>

<style lang="scss" scoped>
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
</style>

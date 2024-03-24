<template>
    <div class="table-responsive my-5">
        <table class="w-full doc-table">
            <thead>
                <tr>
                    <th
                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                    >
                        {{ $t("Component") }}
                    </th>
                    <th
                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                    >
                        {{ $t("Netto") }}
                    </th>
                    <th
                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                    >
                        {{ $t("Tax") }}
                    </th>
                    <th
                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                    >
                        {{ $t("Total") }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="softwareLicencesToggle">
                    <td
                        class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                    >
                        {{ $t("Software Licences") }}
                    </td>
                    <td
                        class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                    >
                        {{
                            $formatter(
                                totalAmountWithoutTaxLicences,
                                globalLanguage,
                                "EUR"
                            )
                        }}
                    </td>
                    <td
                        class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                    >
                        <div
                            style="display: flex"
                            v-for="(item, index) in softwareLicencesTax"
                            :key="index"
                        >
                            <h1>
                                {{ $t("MwSt.") }}
                                {{ item.percent }}%:
                            </h1>
                            &nbsp;
                            <h1 class="ml-2">
                                {{
                                    $formatter(
                                        item.amount.toFixed(2),
                                        globalLanguage,
                                        "EUR"
                                    )
                                }}
                            </h1>
                        </div>
                    </td>
                    <td
                        class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                    >
                        {{
                            $formatter(
                                totalAmountLicences,
                                globalLanguage,
                                "EUR"
                            )
                        }}
                    </td>
                </tr>
                <tr v-if="softwareMaintenanceToggle">
                    <td
                        class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                    >
                        Software Maintenance
                    </td>
                    <td
                        class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                    >
                        {{
                            $formatter(
                                totalAmountWithoutTaxMaintenance,
                                globalLanguage,
                                "EUR"
                            )
                        }}
                    </td>
                    <td
                        class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                    >
                        <div
                            style="display: flex"
                            v-for="(
                                item, index
                            ) in softwareMaintenanceTax"
                            :key="index"
                        >
                            <h1>
                                {{ $t("MwSt.") }}
                                {{ item.percent }}%:
                            </h1>
                            &nbsp;
                            <h1 class="ml-2">
                                {{
                                    $formatter(
                                        item.amount.toFixed(2),
                                        globalLanguage,
                                        "EUR"
                                    )
                                }}
                            </h1>
                        </div>
                    </td>
                    <td
                        class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                    >
                        {{
                            $formatter(
                                totalAmountMaintenance,
                                globalLanguage,
                                "EUR"
                            )
                        }}
                    </td>
                </tr>
                <tr
                    v-if="
                        servicesToggle &&
                        form.productsGroupedBy === 'category'
                    "
                >
                    <td
                        class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                    >
                        Services
                    </td>
                    <td
                        class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                    >
                        {{
                            $formatter(
                                computeNetto,
                                globalLanguage,
                                "EUR"
                            )
                        }}
                    </td>
                    <td
                        class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                    >
                        <div
                            style="display: flex"
                            v-for="(value, key, index) in computeMwst"
                            :key="index"
                        >
                            <h1>
                                {{ $t("MwSt.") }}
                                {{ key }}%:
                            </h1>
                            &nbsp;
                            <h1 class="ml-2">
                                {{
                                    $formatter(
                                        value,
                                        globalLanguage,
                                        "EUR"
                                    )
                                }}
                            </h1>
                        </div>
                    </td>
                    <td
                        class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                    >
                        {{
                            $formatter(
                                computeSummary,
                                globalLanguage,
                                "EUR"
                            )
                        }}
                    </td>
                </tr>
                <tr
                    v-if="
                        servicesToggle &&
                        form.productsGroupedBy === 'nothing'
                    "
                >
                    <td
                        class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                    >
                        Services
                    </td>
                    <td
                        class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                    >
                        {{
                            $formatter(
                                computeNettoCategory,
                                globalLanguage,
                                "EUR"
                            )
                        }}
                    </td>
                    <td
                        class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                    >
                        <div
                            style="display: flex"
                            v-for="(
                                value, key, index
                            ) in computeMwstCategory"
                            :key="index"
                        >
                            <h1>
                                {{ $t("MwSt.") }}
                                {{ key }}%:
                            </h1>
                            &nbsp;
                            <h1 class="ml-2">
                                {{
                                    $formatter(
                                        computeSummary,
                                        globalLanguage,
                                        "EUR"
                                    )
                                }}
                            </h1>
                        </div>
                    </td>
                    <td
                        class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                    >
                        {{
                            $formatter(
                                computeSummaryCategory,
                                globalLanguage,
                                "EUR"
                            )
                        }}
                    </td>
                </tr>
                <tr v-if="applicationManagementServiceToggle">
                    <td
                        class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                    >
                        {{ $t("Application Management Service") }}
                    </td>
                    <td
                        class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                    >
                        {{
                            $formatter(
                                computeNettoAms,
                                globalLanguage,
                                "EUR"
                            )
                        }}
                    </td>
                    <td
                        class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                    >
                        <div
                            style="display: flex"
                            v-for="(
                                value, key, index
                            ) in computeMwstAms"
                            :key="index"
                        >
                            <h1>
                                {{ $t("MwSt.") }}
                                {{ key }}%:
                            </h1>
                            &nbsp;
                            <h1 class="ml-2">
                                {{
                                    $formatter(
                                        value,
                                        globalLanguage,
                                        "EUR"
                                    )
                                }}
                            </h1>
                        </div>
                    </td>
                    <td
                        class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                    >
                        {{
                            $formatter(
                                computeSummaryAms,
                                globalLanguage,
                                "EUR"
                            )
                        }}
                    </td>
                </tr>
                <tr v-if="hostingToggle">
                    <td
                        class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                    >
                        {{ $t("Hosting") }}
                    </td>
                    <td
                        class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                    >
                        {{
                            $formatter(
                                computeNettoHosting,
                                globalLanguage,
                                "EUR"
                            )
                        }}
                    </td>
                    <td
                        class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                    >
                        <div
                            style="display: flex"
                            v-for="(
                                value, key, index
                            ) in computeMwstHosting"
                            :key="index"
                        >
                            <h1>
                                {{ $t("MwSt.") }}
                                {{ key }}%:
                            </h1>
                            &nbsp;
                            <h1 class="ml-2">
                                {{
                                    $formatter(
                                        value,
                                        globalLanguage,
                                        "EUR"
                                    )
                                }}
                            </h1>
                        </div>
                    </td>
                    <td
                        class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                    >
                        {{
                            $formatter(
                                computeSummaryHosting,
                                globalLanguage,
                                "EUR"
                            )
                        }}
                    </td>
                </tr>
                <tr v-if="cloudToggle">
                    <td
                        class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                    >
                        {{ $t("Cloud") }}
                    </td>
                    <td
                        class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                    >
                        {{
                            $formatter(
                                computeNettoCloud,
                                globalLanguage,
                                "EUR"
                            )
                        }}
                    </td>
                    <td
                        class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                    >
                        <div
                            style="display: flex"
                            v-for="(
                                value, key, index
                            ) in computeMwstCloud"
                            :key="index"
                        >
                            <h1>
                                {{ $t("MwSt.") }}
                                {{ key }}%:
                            </h1>
                            &nbsp;
                            <h1 class="ml-2">
                                {{
                                    $formatter(
                                        value,
                                        globalLanguage,
                                        "EUR"
                                    )
                                }}
                            </h1>
                        </div>
                    </td>
                    <td
                        class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                    >
                        {{
                            $formatter(
                                computeSummaryCloud,
                                globalLanguage,
                                "EUR"
                            )
                        }}
                    </td>
                </tr>
                <tr>
                    <td
                        class="px-5 py-5 border-b border-gray-200 bg-white text-sm font-bold"
                    >
                        Total
                    </td>
                    <td
                        class="px-5 py-5 border-b border-gray-200 bg-white text-sm font-bold"
                    >
                        {{
                            $formatter(
                                totalNettoTotals,
                                globalLanguage,
                                "EUR"
                            )
                        }}
                    </td>
                    <td
                        class="px-5 py-5 border-b border-gray-200 bg-white text-sm font-bold"
                    >
                        {{
                            $formatter(totalMwst, globalLanguage, "EUR")
                        }}
                    </td>
                    <td
                        class="px-5 py-5 border-b border-gray-200 bg-white text-sm font-bold"
                    >
                        {{
                            $formatter(
                                totalSummary,
                                globalLanguage,
                                "EUR"
                            )
                        }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: {
        offer: {
            type: Object,
            default: () => ({}),
        },
    },
    data() {
        return {
            hostingToggle: false,
            cloudToggle: false,
            amsToggle: false,
            ams: [],
            hosting: [],
            cloud: [],
            categories: [],
            form: {
                term: "",
                company: null,
                project: null,
                type: "budget",
                receiverType: "customer",
                template: "empty",
                productsGroupedBy: "nothing",
            },
            selectedProductType: "softwareLicences",
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
        };
    },
    async mounted() {
        this.refresh();
    },
    computed: {
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
        totalSummary() {
            return (
                +this.totalAmountLicences +
                +this.totalAmountMaintenance +
                (this.form.productsGroupedBy === "nothing"
                    ? +this.computeSummary
                    : +this.computeSummaryCategory) +
                +this.computeSummaryAms +
                +this.computeSummaryHosting +
                +this.computeSummaryCloud
            ).toFixed(2);
        },
        totalNettoTotals() {
            return (
                +this.totalAmountWithoutTaxLicences +
                +this.totalAmountWithoutTaxMaintenance +
                (this.form.productsGroupedBy === "nothing"
                    ? +this.computeNetto
                    : +this.computeNettoCategory) +
                +this.computeNettoAms +
                +this.computeNettoHosting +
                +this.computeNettoCloud
            );
        },
        totalMwst() {
            let mwstSoftwareLicense = 0;
            for (let key in this.softwareLicencesTax) {
                mwstSoftwareLicense += (+this.softwareLicencesTax[key]
                    .amount).toFixed(2);
            }
            let mwstSoftwareMaintenance = 0;
            for (let key in this.softwareMaintenanceTax) {
                mwstSoftwareMaintenance += (+this.softwareMaintenanceTax[key]
                    .amount).toFixed(2);
            }
            let mwstServiceNothing = 0;
            for (let key in this.computeMwst) {
                mwstServiceNothing += (+this.computeMwst[key]).toFixed(2);
            }
            let mwstServiceCategory = 0;
            for (let key in this.computeMwstCategory) {
                mwstServiceCategory += (+this.computeMwstCategory[key]).toFixed(
                    2
                );
            }
            let mwstAms = 0;
            for (let key in this.computeMwstAms) {
                mwstAms += (+this.computeMwstAms[key]).toFixed(2);
            }
            let mwstHosting = 0;
            for (let key in this.computeMwstHosting) {
                mwstHosting += (+this.computeMwstHosting[key]).toFixed(2);
            }
            let mwstCloud = 0;
            for (let key in this.computeMwstCloud) {
                mwstCloud += (+this.computeMwstCloud[key]).toFixed(2);
            }
            return (
                +mwstSoftwareLicense +
                +mwstSoftwareMaintenance +
                (this.form.productsGroupedBy === "nothing"
                    ? +mwstServiceNothing
                    : +mwstServiceCategory) +
                +mwstAms +
                +mwstHosting +
                +mwstCloud
            ).toFixed(2);
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
        async refresh() {
            try {
                const components = this.offer.components;
                components.forEach((component) => {
                    if (component.type === "license") {
                        this.softwareLicences = [
                            ...this.softwareLicences,
                            {
                                ...component,
                                productId: component.product.id,
                                articleNumber: component.product.articleNumber,
                                name: component.product.name,
                                salePrice: component.salePrice,
                                maintenanceRate: component.maintenanceRate,
                            },
                        ];
                        this.softwareLicencesToggle = true;
                    } else if (component.type === "maintenance") {
                        this.softwareMaintenance = [
                            ...this.softwareMaintenance,
                            {
                                ...component,
                                productId: component.product.id,
                                articleNumber: component.product.articleNumber,
                                name: component.product.name,
                                salePrice: component.salePrice,
                                maintenanceRate: component.maintenanceRate,
                                totalSalePriceAdjustedForQuantity:
                                    +component.salePrice * +component.quantity,
                            },
                        ];
                        this.softwareMaintenanceToggle = true;
                    } else if (component.type === "service") {
                        if (this.form.productsGroupedBy == "nothing") {
                            const totalWithoutDiscount =
                                +component.quantity * +component.hourlyRate;
                            const discountAmount =
                                (+totalWithoutDiscount * +component.discount) /
                                100;
                            const nettoTotal =
                                +totalWithoutDiscount - +discountAmount;
                            const taxRate =
                                (+nettoTotal * +component.tax) / 100;
                            this.services = [
                                ...this.services,
                                {
                                    ...component,
                                    category: component.product.category,
                                    productId: component.product.id,
                                    articleNumber:
                                        component.product.articleNumber,
                                    name: component.product.name,
                                    salePrice: component.salePrice,
                                    tax: +component.tax,
                                    nettoTotal: nettoTotal,
                                    taxRate: taxRate,
                                    bruttoTotal: +nettoTotal + +taxRate,
                                },
                            ];
                            this.computeServicesByCategory();
                        } else {
                            const category = component.productCategory;
                            const products = component.products;
                            this.services = [
                                ...this.services,
                                ...products.map((product) => {
                                    const totalWithoutDiscount =
                                        +product.quantity * +product.hourlyRate;
                                    const discountAmount =
                                        (+totalWithoutDiscount *
                                            +product.discount) /
                                        100;
                                    const nettoTotal =
                                        +totalWithoutDiscount - +discountAmount;
                                    const taxRate =
                                        (+nettoTotal * +product.tax) / 100;
                                    return {
                                        ...product,
                                        tax: +product.tax,
                                        nettoTotal: nettoTotal,
                                        taxRate: taxRate,
                                        bruttoTotal: +nettoTotal + +taxRate,
                                    };
                                }),
                            ];
                            const quantity = component.quantity;
                            const hourlyRate = component.hourlyRate;
                            const totalWithoutDiscount =
                                +hourlyRate * +quantity;
                            const discount = component.discount;
                            const discountAmount =
                                (+totalWithoutDiscount * +discount) / 100;
                            const nettoTotal =
                                +totalWithoutDiscount - +discountAmount;
                            const tax = component.tax;
                            const taxRate = (+nettoTotal * +tax) / 100;
                            this.categories.push({
                                ...category,
                                products: products,
                                quantity: quantity,
                                hourlyRate: hourlyRate,
                                discount: discount,
                                nettoTotal: nettoTotal,
                                tax: tax,
                                taxRate: taxRate,
                                bruttoTotal: +nettoTotal + +taxRate,
                            });
                        }
                        this.servicesToggle = true;
                    } else if (component.type === "application") {
                        const totalWithoutDiscount =
                            +component.quantity * +component.hourlyRate;
                        const discountAmount =
                            (+totalWithoutDiscount * +component.discount) / 100;
                        const nettoTotal =
                            +totalWithoutDiscount - +discountAmount;
                        const taxRate = (+nettoTotal * +component.tax) / 100;
                        this.ams = [
                            ...this.ams,
                            {
                                ...component,
                                productId: component.product.id,
                                articleNumber: component.product.articleNumber,
                                productSoftware:
                                    component.product.productSoftware,
                                name: component.product.name,
                                salePrice: component.salePrice,
                                hourlyRate: component.hourlyRate,
                                paymentPeriod: component.paymentPeriod,
                                serviceHours: component.quantity,
                                tax: +component.tax,
                                nettoTotal: nettoTotal,
                                taxRate: taxRate,
                            },
                        ];
                        this.applicationManagementServiceToggle = true;
                    } else if (component.type === "hosting") {
                        const totalWithoutDiscount =
                            +component.quantity * +component.pricePerPeriod;
                        const discountAmount =
                            (+totalWithoutDiscount * +component.discount) / 100;
                        const nettoTotal =
                            +totalWithoutDiscount - +discountAmount;
                        const taxRate = (+nettoTotal * +component.tax) / 100;
                        this.hosting = [
                            ...this.hosting,
                            {
                                ...component,
                                productId: component.product.id,
                                articleNumber: component.product.articleNumber,
                                productSoftware:
                                    component.product.productSoftware,
                                name: component.product.name,
                                unit: component.product.unit,
                                pricePerPeriod: component.pricePerPeriod,
                                quantity: component.quantity,
                                paymentPeriod: component.paymentPeriod,
                                tax: +component.tax,
                                nettoTotal: nettoTotal,
                                taxRate: taxRate,
                            },
                        ];
                        this.hostingToggle = true;
                    } else if (component.type === "cloud") {
                        const totalWithoutDiscount =
                            +component.quantity *
                            (+component.salePrice * (+component.duration ?? 1));
                        const discountAmount =
                            (+totalWithoutDiscount * +component.discount) / 100;
                        const nettoTotal =
                            +totalWithoutDiscount - +discountAmount;
                        const taxRate = (+nettoTotal * +component.tax) / 100;
                        this.cloud = [
                            ...this.cloud,
                            {
                                ...component,
                                productId: component.product.id,
                                articleNumber: component.product.articleNumber,
                                productSoftware:
                                    component.product.productSoftware,
                                name: component.product.name,
                                unit: component.product.unit,
                                salePrice: component.salePrice,
                                quantity: component.quantity,
                                paymentPeriod: component.paymentPeriod,
                                tax: +component.tax,
                                nettoTotal: nettoTotal,
                                taxRate: taxRate,
                            },
                        ];
                        this.cloudToggle = true;
                    }
                });
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
            } catch (e) {
                console.log(e);
            }
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
        calculateCategoryQuantity(products) {
            return products.reduce(
                (accumulator, product) => +accumulator + +product.quantity,
                0
            );
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
                    this.softwareLicences[index].salePrice = (
                        (this.softwareLicences[index].maintenancePrice * 100) /
                        this.softwareLicences[index].maintenanceRate
                    ).toFixed(2);
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
};
</script>

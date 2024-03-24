<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"></h3>
            </div>
            <div class="card-body">
                <div class="grid items-center grid-cols-2 gap-6">
                    <div class="w-full">
                        <div class="form-group">
                            <TextInput
                                :isReadonly="true"
                                :modelValue="fuelReceipts.licenceNumber"
                                :label="$t('Licence Number')"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <TextInput
                                :isReadonly="true"
                                :modelValue="fuelReceipts.vim"
                                :label="$t('VIM')"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <TextInput
                                :isReadonly="true"
                                :modelValue="fuelReceipts.model"
                                :label="$t('Model')"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive mt-3">
            <table class="doc-table">
                <thead>
                    <tr class="text-left">
                        <th class="">
                            {{ $t("Date") }}
                        </th>
                        <th class="">
                            {{ $t("Mileage") }}
                        </th>
                        <th class="">
                            {{ $t("Product") }}
                        </th>
                        <th class="">
                            {{ $t("Quantity") }}
                        </th>
                        <th class="">
                            <p>{{ $t("Unit") }}</p>
                        </th>
                        <th class="">
                            <p>{{ $t("Price") }} / {{ $t("Unit") }}</p>
                        </th>
                        <th class="">
                            <p>
                                {{
                                    globalLanguage === "de"
                                        ? `Verbrauch`
                                        : `Consume / 100KM`
                                }}
                            </p>
                        </th>
                        <th class="">
                            <p>
                                {{
                                    globalLanguage === "de"
                                        ? `Kosten`
                                        : `Euro / 100KM`
                                }}
                            </p>
                        </th>
                        <th class="">
                            {{ $t("Total Netto") }}
                        </th>
                        <th class="">
                            {{ $t("Tax") }}
                        </th>
                        <th class="">
                            {{ $t("Total Brutto") }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="receipt in fuelReceipts.fuelReceipts"
                        :key="receipt.id"
                    >
                        <!-- Ticket content here -->
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{
                                    $dateFormatter(
                                        receipt.deliveryDate,
                                        globalLanguage
                                    )
                                }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                                >{{
                                    $formatter(
                                        receipt.actualMileage,
                                        globalLanguage,
                                        "EUR"
                                    ).replace(/€/g, "")
                                }}</a
                            >
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                                >{{ receipt.product }}</a
                            >
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{
                                    $formatter(
                                        receipt.quantity,
                                        globalLanguage,
                                        "EUR"
                                    ).replace(/€/g, "")
                                }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                                >{{ receipt.unit }}</a
                            >
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                                >{{
                                    $formatter(
                                        receipt.pricePerUnit,
                                        globalLanguage,
                                        "EUR"
                                    )
                                }}</a
                            >
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{
                                    $formatter(
                                        receipt.consumePerHundredKilometer,
                                        globalLanguage,
                                        "EUR"
                                    ).replace(/€/g, "")
                                }}
                                LTR
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{
                                    $formatter(
                                        receipt.euroPerHundredKilometer,
                                        globalLanguage,
                                        "EUR"
                                    )
                                }}
                            </a>
                        </td>

                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                                >{{
                                    $formatter(
                                        receipt.totalNetto,
                                        globalLanguage,
                                        "EUR"
                                    )
                                }}</a
                            >
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{
                                    $formatter(
                                        receipt.tax,
                                        globalLanguage,
                                        "EUR"
                                    )
                                }}</a
                            >
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                                >{{
                                    $formatter(
                                        receipt.totalBrutto,
                                        globalLanguage,
                                        "EUR"
                                    )
                                }}</a
                            >
                        </td>
                    </tr>
                    <tr class="font-bold bg-gray-300">
                        <td colspan="3"></td>
                        <td colspan="1" class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                <p>
                                    {{
                                        $formatter(
                                            fuelReceipts.totalQuantity,
                                            globalLanguage,
                                            "EUR"
                                        ).replace(/€/g, "")
                                    }}
                                </p>
                            </a>
                        </td>
                        <td colspan="1" class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                <p>LTR</p>
                            </a>
                        </td>
                        <td colspan="3" class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                            </a>
                        </td>
                        <td colspan="1" class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                                >{{
                                    $formatter(
                                        fuelReceipts.totalNetto,
                                        globalLanguage,
                                        "EUR"
                                    )
                                }}
                            </a>
                        </td>
                        <td colspan="1" class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{
                                    $formatter(
                                        fuelReceipts.tax,
                                        globalLanguage,
                                        "EUR"
                                    )
                                }}
                            </a>
                        </td>
                        <td colspan="1" class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{
                                    $formatter(
                                        fuelReceipts.totalBrutto,
                                        globalLanguage,
                                        "EUR"
                                    )
                                }}
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import TextInput from "@/Components/TextInput.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Fuel Receipts",
                    to: `/fuel-monitoring`,
                },
                {
                    text: "Show",
                    active: true,
                },
            ],
            driver: "",
            fuelReceipts: [],
        };
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("auth", {
            users: "users",
        }),
    },
    async mounted() {
        this.$store.commit("showLoader", true);
        await this.$store.dispatch("auth/list", {
            limit_start: 0,
            limit_count: 25,
            type: "employee",
        });
        const response = await this.$store.dispatch(
            "fuelReceipts/show",
            this.$route.params.id
        );
        this.fuelReceipts = response?.data;
        this.$store.commit("showLoader", false);
    },
    components: { TextInput, MultiSelectInput, PageHeader },
};
</script>

<style></style>

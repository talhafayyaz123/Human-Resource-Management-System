<template>
    <div>
        <div class="">
            <h3 class="card-title">
                {{ $t("Old Vehicles") }}
            </h3>
            <div class="table-responsive mt-2">
                <table class="doc-table">
                    <tr class="text-left">
                        <th class="">
                            {{ $t("Licence Number") }}
                        </th>
                        <th class="">{{ $t("Model") }}</th>
                        <th class="">{{ $t("Brand") }}</th>
                        <th class="">{{ $t("Color") }}</th>
                    </tr>
                    <tr
                        v-for="car in previouslyOwnedCars"
                        :key="car.id"
                        class="hover:bg-gray-100 focus-within:bg-gray-100"
                    >
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center"
                            >
                                {{ car.licenceNumber }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center"
                                >{{ car.model }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center"
                                >{{ car.brand }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center"
                                >{{ car.color }}
                            </a>
                        </td>
                    </tr>
                    <tr v-if="(previouslyOwnedCars?.length ?? 0) === 0">
                        <td class="" colspan="4">{{ $t("No Cars found") }}.</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="mt-4">
            <h3 class="card-title">
                {{ $t("Assigned Vehicles") }}
            </h3>
            <div class="table-responsive mt-2">
                <table class="doc-table">
                    <tr class="text-left">
                        <th class="">
                            {{ $t("Licence Number") }}
                        </th>
                        <th class="">{{ $t("Model") }}</th>
                        <th class="">{{ $t("Brand") }}</th>
                        <th class="">{{ $t("Color") }}</th>
                    </tr>
                    <tr
                        v-for="car in ownedCars"
                        :key="car.id"
                        class="hover:bg-gray-100 focus-within:bg-gray-100"
                        @click.stop="$router.push(`/fleet-cars/${car.id}/edit`)"
                        style="cursor: pointer"
                    >
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center"
                            >
                                {{ car.licenceNumber }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center"
                                >{{ car.model }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center"
                                >{{ car.brand }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center"
                                >{{ car.color }}
                            </a>
                        </td>
                    </tr>
                    <tr v-if="(ownedCars?.length ?? 0) === 0">
                        <td class="" colspan="4">{{ $t("No Cars found") }}.</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="mt-4">
            <h3 class="card-title">
                {{ $t("Asset Articles") }}
            </h3>
            <div class="table-responsive mt-2">
                <table class="doc-table">
                    <tr class="text-left">
                        <th class="">{{ $t("Asset Name") }}</th>
                        <th class="">{{ $t("Model") }}</th>
                        <th class="">{{ $t("Serial No") }}</th>
                        <th class="">{{ $t("Status") }}</th>
                        <th class="">
                            {{ $t("Storage Location") }}
                        </th>
                    </tr>
                    <tr
                        v-for="asset in assetArticles"
                        :key="asset.id"
                        class="hover:bg-gray-100 focus-within:bg-gray-100"
                        style="cursor: pointer"
                    >
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center"
                            >
                                {{ asset.assetName }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center"
                            >
                                {{ asset.model }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center"
                            >
                                {{ asset.serialNo }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center"
                            >
                                {{ asset.status }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center"
                            >
                                {{ asset.storageLocation }}
                            </a>
                        </td>
                    </tr>
                    <tr v-if="(assetArticles?.length ?? 0) === 0">
                        <td class="" colspan="5">
                            {{ $t("No Asset Articles") }}.
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="flex items-center justify-end mt-4">
            <!-- <div @click="backButton()" class="primary-btn cursor-pointer me-3">
                <span class="mr-1">
                    <CustomIcon name="backIcon" />
                </span>
                <span>{{ $t("Back") }}</span>
            </div> -->
            <loading-button @click="$emit('next', true)" class="secondary-btn">
                <span class="mr-1">
                    <CustomIcon name="nextIcon" />
                </span>
                {{ $t("Next") }}
            </loading-button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        userId: String,
    },
    data() {
        return {
            previouslyOwnedCars: [],
            ownedCars: [],
            assetArticles: [],
        };
    },
    async mounted() {
        console.log(this.userId);
        const response = await this.$store.dispatch(
            "assetList/getEmployeeAsset",
            {
                userId: this.userId,
            }
        );
        this.previouslyOwnedCars = response?.data?.previouslyOwnedCars ?? [];
        this.ownedCars = response?.data?.ownedCars ?? [];
        this.assetArticles = response?.data?.assetArticles ?? [];
    },
};
</script>

<style></style>

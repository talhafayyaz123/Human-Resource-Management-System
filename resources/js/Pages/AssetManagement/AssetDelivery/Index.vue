<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <div class="flex items-center justify-end mb-6">
            <search-filter
                :isFilter="false"
                v-model="form.search"
                class="mr-4 w-full max-w-md"
                @reset="reset"
            >
            </search-filter>
        </div>
        <div class="flex items-center justify-end mb-6"></div>
        <div class="table-responsive">
            <table class="w-full doc-table">
                <tr class="text-left font-bold">
                    <th
                        @click="sort('assetId')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Asset Id") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'assetId'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('model')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Model") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'model'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('isDeliveryChecked')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Delivery Checked") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'isDeliveryChecked'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('storageLocationId')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Storage Location") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'storageLocationId'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Actions") }}</th>

                    <!-- <th class="pb-4 pt-6 px-6">{{ $t("Asset Id") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Model") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Delivery Checked") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Storage Location") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Actions") }}</th> -->
                </tr>
                <tr v-for="asset in assetDeliveries?.data" :key="asset.id">
                    <td class="pb-4 pt-6 px-6 border-t">
                        {{ asset.assetId }}
                    </td>
                    <td class="pb-4 pt-6 px-6 border-t">
                        {{ asset.model }}
                    </td>
                    <td class="pb-4 pt-6 px-6 border-t">
                        {{
                            asset.isDeliveryChecked === 1 ? $t("Yes") : $t("No")
                        }}
                    </td>
                    <td class="pb-4 pt-6 px-6 border-t">
                        <div
                            v-for="(stor, storInd) in storages.data"
                            :key="storInd"
                        >
                            <p v-if="stor.id == asset.storageLocationId">
                                {{ stor.storageLocation }}
                            </p>
                        </div>
                    </td>
                    <td class="pb-4 pt-6 px-2 border-t">
                        <router-link
                            v-if="$can(`${$route.meta.permission}.edit`)"
                            class="px-2"
                            :to="`/asset-delivery/${asset.id}/edit`"
                        >
                            <font-awesome-icon
                                icon="fa-regular fa-pen-to-square"
                            />
                        </router-link>

                        <button
                            v-if="$can(`${$route.meta.permission}.delete`)"
                            @click="destroy(asset.id)"
                        >
                            <font-awesome-icon icon="fa-regular fa-trash-can" />
                        </button>
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="assetDeliveries"
                @pagination-change-page="changePageOrSearch"
            ></pagination>
            <br />
            <br />
        </div>
    </div>
</template>

<script>
import Pagination from "laravel-vue-pagination";
import SearchFilter from "@/Components/SearchFilter.vue";
import { mapGetters } from "vuex";
import { debounce } from "@/utils/debounce";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    components: {
        Pagination,
        SearchFilter,
        PageHeader,
    },
    props: {
        filters: Object,
        can: Object,
    },

    computed: {
        ...mapGetters("assetDelivery", ["assetDeliveries"]),
        ...mapGetters("storageLocations", ["storages"]),
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Asset Management"),
                    to: "/asset-delivery",
                },
                {
                    text: this.$t("Asset Delivery"),
                    active: true,
                }
            ],
            window,
            page: 1,
            form: {
                search: "",
                perPage: 10,
                sortBy: "assetId",
                sortOrder: "",
            },
        };
    },
    watch: {
        "form.search"(...val) {
            this.debouncedFetch(...val);
        },
        "form.sortBy"(...val) {
            this.debouncedFetch(...val);
        },
        "form.sortOrder"(...val) {
            this.debouncedFetch(...val);
        },
    },
    created() {
        this.debouncedFetch = debounce(async (newValue, oldValue) => {
            try {
                await this.$store.dispatch("assetDelivery/list", this.form);
            } catch (e) {
                console.log(e);
            }
        }, 300);
    },
    async mounted() {
        await this.$store.dispatch("assetDelivery/list", {
            page: this.page,
            perPage: this.form.perPage,
        });
        await this.$store.dispatch("storageLocations/list");
    },
    methods: {
        reset() {
            this.form.search = "";
        },
        async destroy(id) {
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
                    await this.$store.dispatch("assetDelivery/destroy", id);
                    await this.$store.dispatch("assetDelivery/list");
                }
            });
        },
        async changePageOrSearch(page = 1) {
            this.page = page;

            await this.$store.dispatch("assetDelivery/list", {
                page: this.page,
                ...this.form,
            });
        },
    },
};
</script>

<style scoped></style>

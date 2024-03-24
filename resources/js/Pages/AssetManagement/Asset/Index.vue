<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />
        <div class="flex items-center justify-end mb-6">
            <search-filter
                :isFilter="false"
                v-model="form.search"
                class="mr-4 w-full max-w-md"
                @reset="reset"
            >
            </search-filter>
        </div>
        <div class="table-responsive">
            <table class="w-full doc-table">
                <tr class="text-left font-bold">
                    <th
                        @click="sort('assetName')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Name") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'assetName'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('assetNumber')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Asset Number") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'assetNumber'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('assetType')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Asset Type") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'assetType'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('priceNetto')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Price Netto") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'priceNetto'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Actions") }}</th>
                </tr>
                <tr
                    @click.stop.right="
                        (e) => {
                            e.preventDefault();
                            let route = $router.resolve(
                                `assets/${asset.id}/edit?page=${page}`
                            );
                            window.open(route.href, '_blank');
                        }
                    "
                    class="cursor-pointer"
                    v-for="asset in assets?.data"
                    :key="asset.id"
                >
                    <td class="pb-4 pt-6 px-6 border-t">
                        {{ asset.assetName }}
                    </td>
                    <td class="pb-4 pt-6 px-6 border-t">
                        {{ asset.assetNumber }}
                    </td>
                    <td class="pb-4 pt-6 px-6 border-t">
                        {{ asset.assetType }}
                    </td>
                    <td class="pb-4 pt-6 px-6 border-t">
                        {{ $formatter(asset.priceNetto, globalLanguage) }}
                    </td>
                    <td class="pb-4 pt-6 px-6 border-t">
                        <router-link
                            v-if="$can(`${$route.meta.permission}.edit`)"
                            class="px-2"
                            :to="`assets/${asset.id}/edit?page=${page}`"
                        >
                            <font-awesome-icon
                                icon="fa-regular fa-pen-to-square"
                            />
                        </router-link>
                        <button
                            @click="destroy(asset.id)"
                            v-if="$can(`${$route.meta.permission}.delete`)"
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
                :data="assets"
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

import PageHeader from "@/Components/Layouts/Page-header.vue";
import { debounce } from "@/utils/debounce";
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
        ...mapGetters("assets", ["assets"]),
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
                    to: "/assets",
                },
                {
                    text: this.$t("Assets"),
                    active: true,
                }
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Asset"),
                btn2Path: "/asset-create",
            },
            window,
            page: 1,
            form: {
                search: "",
                sortBy: "assetName",
                sortOrder: "asc",
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
                await this.$store.dispatch("assets/list", this.form);
            } catch (e) {
                console.log(e);
            }
        }, 300);
    },
    async mounted() {
        if (this.$route.query.page) {
            this.page = this.$route.query.page;
            this.$router.replace({ query: null });
        }
        await this.$store.dispatch("assets/list", {page:this.page,...this.pickBy(this.form)});
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
                    await this.$store.dispatch("assets/destroy", id);
                    await this.$store.dispatch("assets/list", {
                        page: this.page,
                    });
                }
            });
        },
        async changePageOrSearch(page = 1) {
            this.page = page;

            await this.$store.dispatch("assets/list", {
                page: this.page,
                ...this.form,
            });
        },
    },
};
</script>

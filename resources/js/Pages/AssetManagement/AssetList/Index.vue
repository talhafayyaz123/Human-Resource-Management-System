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
                        class="pb-4 pt-6 px-6 cursor-pointer"
                        @click="sort('UserProfileData.firstName')"
                    >
                        {{ $t("Name") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'UserProfileData.firstName'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        class="pb-4 pt-6 px-6 cursor-pointer"
                        @click="sort('assetNumber')"
                    >
                        {{ $t("Asset List Number") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'assetNumber'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Personal Number") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Location") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Actions") }}</th>
                </tr>
                <tr
                    v-for="asset in assetLists?.data"
                    @click.stop.right="
                        (e) => {
                            e.preventDefault();
                            let route = $router.resolve(
                                `asset-list/${asset.id}/edit?page=${page}`
                            );
                            window.open(route.href, '_blank');
                        }
                    "
                    class="cursor-pointer"
                    :key="asset.id"
                >
                    <td class="pb-4 pt-6 px-6 border-t">
                        {{ asset.name }}
                    </td>
                    <td class="pb-4 pt-6 px-6 border-t">
                        {{ asset.assetNumber }}
                    </td>
                    <td class="pb-4 pt-6 px-6 border-t">
                        {{ asset.personalNumber }}
                    </td>
                    <td class="pb-4 pt-6 px-6 border-t">
                        {{ asset.location }}
                    </td>
                    <td class="pb-4 pt-6 px-2 border-t">
                        <router-link
                            v-if="$can(`${$route.meta.permission}.edit`)"
                            class="px-2"
                            :to="`/asset-list/${asset.id}/edit?page=${page}`"
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
                :data="assetLists"
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
        ...mapGetters("assetList", ["assetLists"]),
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
                    to: "/asset-lists",
                },
                {
                    text: this.$t("Asset Lists"),
                    active: true,
                }
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Asset List"),
                btn2Path: "/asset-list/create",
            },
            window,
            page: 1,
            form: {
                search: "",
                perPage: 10,
                sortBy: "",
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
                await this.$store.dispatch("assetList/list", this.form);
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
        await this.$store.dispatch("assetList/list", { page: this.page });
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
                    await this.$store.dispatch("assetList/destroy", id);
                    await this.$store.dispatch("assetList/list");
                }
            });
        },
        async changePageOrSearch(page = 1) {
            this.page = page;

            await this.$store.dispatch("assetList/list", {
                page: this.page,
                ...this.form,
            });
        },
    },
};
</script>

<style scoped></style>

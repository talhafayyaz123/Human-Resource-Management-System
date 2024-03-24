<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

        <div class="flex items-center justify-end mb-6">
            <search-filter
                :isFilter="false"
                v-model="form.search"
                @reset="reset"
                class="mr-4 w-full max-w-md"
            >
            </search-filter>
        </div>
        <div class="table-responsive">
            <table class="w-full doc-table">
                <tr class="text-left font-bold">
                    <th
                        @click="sort('name')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Name") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'name'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('iso')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Iso") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'iso'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('region')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Region") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'region'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('subRegion')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Sub-Region") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'subRegion'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Actions") }}</th>
                </tr>
                <tr v-for="country in countries?.data" :key="country.id"
                    @contextmenu.stop.prevent="
                        (e) => {
                            e.preventDefault();
                            let route = $router.resolve(`/countries/${country.id}/edit?page=${page}`);
                            window.open(route.href, '_blank');
                        }
                    "
                >
                    <td class="pb-4 pt-6 px-6">{{ country.name }}</td>
                    <td class="pb-4 pt-6 px-6">{{ country.iso }}</td>
                    <td class="pb-4 pt-6 px-6">{{ country.region }}</td>
                    <td class="pb-4 pt-6 px-6">{{ country.subregion }}</td>
                    <td class="pb-4 pt-6 px-6">
                        <router-link
                            v-if="$can(`${$route.meta.permission}.edit`)"
                            class="mr-2"
                            :to="`/countries/${country.id}/edit?page=${page}`"
                        >
                            <font-awesome-icon
                                icon="fa-regular fa-pen-to-square"
                            />
                        </router-link>
                        <button
                            @click="destroy(country.id)"
                            v-if="$can(`${$route.meta.permission}.delete`)"
                        >
                            <font-awesome-icon icon="fa-regular fa-trash-can" />
                        </button>
                    </td>
                </tr>
            </table>
        </div>
        <div class="flex justify-center my-5">
            <pagination
                :limit="10"
                align="center"
                :data="countries"
                @pagination-change-page="changePageOrSearch"
            >
            </pagination>
        </div>
    </div>
</template>

<script>
import Pagination from "laravel-vue-pagination";
import SearchFilter from "../../Components/SearchFilter.vue";
import { mapGetters } from "vuex";
import { debounce } from "@/utils/debounce"

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
     data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Countries",
                    active: true,
                },
            ],
            optionalItems: {
                isBtnShow: true,
                btnText: "Import Countries",
                path: "/import/countries",
                isBtn2Show: true,
                btn2Text: this.$t("Create Countries"),
                btn2Path: "/countries/create",
            },
            page: 1,
            form: {
                search: "",
                perPage: 25,
                sortBy: "",
                sortOrder: "",
            },
            window
        };
    },
    async created() {
        this.debouncedFetch = debounce(async (newValue, oldValue)=>{
            try {
                await this.$store.dispatch(
                    "countries/list",
                    this.pickBy(this.form)
                );
            } catch (e) {}
        }, 300);
    },
    watch: {
        'form.search'(...val) {
            // Handle the change in the search property
            this.debouncedFetch(...val);
        },
        'form.sortBy'(...val) {
            // Handle the change in the type property
            this.debouncedFetch(...val);
        },
        'form.sortOrder'(...val) {
            // Handle the change in the type property
            this.debouncedFetch(...val);
        },
    },
    computed: {
        ...mapGetters("countries", ["countries"]),
    },
   
    async mounted() {
        let isPaginate = false;
        if (this.$route.query.page) {
            this.page = this.$route.query.page;
            this.$router.replace({ query: null });
            isPaginate = true;
        }
        this.refresh(isPaginate);
        // await this.$store.dispatch("countries/list", this.pickBy(this.form));
    },
    methods: {
        async refresh(bypass) {
            if (!this.countries.data.length || bypass)
                await this.$store.dispatch("countries/list", {
                    page: this.page,
                    ...this.form,
                });
        },
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
                    try {
                        await this.$store.dispatch("countries/destroy", id);
                        await this.$store.dispatch("countries/list");
                    } catch (e) {}
                }
            });
        },
        async changePageOrSearch(page = 1) {
            this.page = page;

            await this.$store.dispatch("countries/list", {
                page: this.page,
                ...this.form,
            });
        },
    },
};
</script>

<style scoped></style>

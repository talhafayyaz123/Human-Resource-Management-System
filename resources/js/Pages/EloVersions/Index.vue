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
                        @click="sort('type')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Type") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'type'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('ProductSoftware.name')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Software") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'ProductSoftware.name'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('createdAt')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Created At") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'createdAt'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Actions") }}</th>
                </tr>
                <tr
                    v-for="version in versions.data"
                    :key="version.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                    @contextmenu.stop.prevent="
                        (e) => {
                            e.preventDefault();
                            let route = $router.resolve(`/versions/${version.id}/edit?page=${page}`);
                            window.open(route.href, '_blank');
                        }
                    "
                >
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ version.name }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{
                                options.find((o) => o.value === version.type)?.[
                                    "displayValue"
                                ] ?? ""
                            }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ version.software }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4"
                            tabindex="-1"
                        >
                            {{
                                $dateFormatter(
                                    version.createdAt,
                                    globalLanguage
                                )
                            }}
                        </a>
                    </td>
                    <td class="border-t">
                        <div class="flex items-center px-6 py-4">
                            <router-link
                                v-if="$can(`${$route.meta.permission}.edit`)"
                                class="mr-2"
                                :to="`/versions/${version.id}/edit?page=${page}`"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-pen-to-square"
                                />
                            </router-link>
                            <button
                                v-if="$can(`${$route.meta.permission}.delete`)"
                                @click.stop="destroy(version.id)"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-trash-can"
                                />
                            </button>
                        </div>
                    </td>
                </tr>
                <tr v-if="versions.data.length === 0">
                    <td class="px-6 py-4 border-t text-center" colspan="4">
                        {{ $t("No versions found") }}.
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                align="center"
                :data="versions"
                @pagination-change-page="changePageOrSearch"
            ></pagination>
            <br />
            <br />
        </div>
    </div>
</template>

<script>
import Icon from "../../Components/Icon.vue";
import Pagination from "laravel-vue-pagination";

import SearchFilter from "../../Components/SearchFilter.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";
import { debounce } from "@/utils/debounce"
export default {
    computed: {
        ...mapGetters("versions", ["versions"]),
    },
    components: {
        Icon,
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
                    text: "Software Version",
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Version"),
                btn2Path: "/versions/create",
            },
            page: 1,
            form: {
                search: "",
                sortBy: "id",
                sortOrder: "asc",
            },
            options: [
                {
                    value: "lts",
                    displayValue: "LTS (Long Term Support)",
                },
                {
                    value: "fr",
                    displayValue: "FR (Feature Release)",
                },
                {
                    value: "hf",
                    displayValue: "HF (Hot Fix)",
                },
                {
                    value: "mr",
                    displayValue: "MR (Master Release)",
                },
            ],
            window
        };
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
    async created() {
        this.debouncedFetch = debounce(async (newValue, oldValue)=>{
            try {
                await this.$store.dispatch(
                    "versions/list",
                    this.pickBy(this.form)
                );
            } catch (e) {}
        }, 300);
    },
    mounted() {
        let isPaginate = false;
        if (this.$route.query.page) {
            this.page = this.$route.query.page;
            this.$router.replace({ query: null });
            isPaginate = true;
        }
        this.refresh(isPaginate);
    },
    methods: {
        async refresh(bypass) {
            if (!this.versions.data.length || bypass)
                await this.$store.dispatch("versions/list", {
                    page: this.page,
                });
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
                        await this.$store.dispatch("versions/destroy", id);
                        await this.refresh(true);
                    } catch (e) {}
                }
            });
        },
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("versions/list", {
                page: this.page,
                search: this.form.search,
            });
        },
        reset() {
            this.form = this.mapValues(this.form, () => null);
        },
    },
};
</script>

<style scoped>
:deep(.page-item.active .page-link) {
    background-color: #2957a4;
    border-color: #2957a4;
}
:deep(.page-link) {
    color: #2957a4;
}
</style>

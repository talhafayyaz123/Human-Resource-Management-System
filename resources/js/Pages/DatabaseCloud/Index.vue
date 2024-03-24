<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

        <div class="flex items-center justify-end mb-6"></div>
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
                        @click="sort('subType')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Sub Type") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'subType'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('instanceType')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Instance Type") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'instanceType'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('databaseType')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Database Type") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'databaseType'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Actions") }}</th>
                </tr>
                <tr
                    v-for="databaseCloud in databaseClouds.data"
                    :key="databaseCloud.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                    @contextmenu.stop.prevent="
                        (e) => {
                            e.preventDefault();
                            let route = $router.resolve(`/database-cloud/${databaseCloud.id}/edit`);
                            window.open(route.href, '_blank');
                        }
                    "
                >
                    <td class="border-t">
                        <router-link
                            :to="`/database-cloud/${databaseCloud.id}/edit`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ databaseCloud.name }}
                        </router-link>
                    </td>
                    <td class="border-t">
                        <router-link
                            :to="`/database-cloud/${databaseCloud.id}/edit`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ databaseCloud.subType }}
                        </router-link>
                    </td>
                    <td class="border-t">
                        <router-link
                            :to="`/database-cloud/${databaseCloud.id}/edit`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ databaseCloud.instanceType }}
                        </router-link>
                    </td>
                    <td class="border-t">
                        <router-link
                            :to="`/database-cloud/${databaseCloud.id}/edit`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ databaseCloud.databaseType }}
                        </router-link>
                    </td>
                    <td class="w-px border-t">
                        <router-link
                            v-if="$can(`${$route.meta.permission}.edit`)"
                            class="px-1 mr-2"
                            :to="`/database-cloud/${databaseCloud.id}/edit`"
                        >
                            <font-awesome-icon
                                icon="fa-regular fa-pen-to-square"
                            />
                        </router-link>
                        <button
                            v-if="$can(`${$route.meta.permission}.delete`)"
                            @click="destroy(databaseCloud.id)"
                        >
                            <font-awesome-icon icon="fa-regular fa-trash-can" />
                        </button>
                    </td>
                </tr>
                <tr v-if="databaseClouds.data.length === 0">
                    <td class="px-6 py-4 border-t" colspan="4">
                        {{ $t("No database clouds found") }}.
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                align="center"
                :data="databaseClouds"
                @pagination-change-page="changePageOrSearch"
            ></pagination>
            <br />
            <br />
        </div>
    </div>
</template>

<script>
import Pagination from "laravel-vue-pagination";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";
import { debounce } from "@/utils/debounce"

export default {
    data() {
        return {
            page: 1,
        };
    },
    computed: {
        ...mapGetters("databaseCloud", ["databaseClouds"]),
    },
    components: {
        Pagination,
        PageHeader,
    },
    async created() {
        this.debouncedFetch = debounce(async (newValue, oldValue)=>{
            try {
                await this.$store.dispatch(
                    "databaseCloud/list",
                    this.pickBy(this.form)
                );
            } catch (e) {}
        }, 300);
    },
    data() {
        return {
            form: {
                search: "",
                sortBy: "",
                sortOrder: "",
            },
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Database Cloud",
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Databse Cloud"),
                btn2Path: "/database-cloud/create",
            },
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
    mounted() {
        this.fetchData();
    },
    methods: {
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("databaseCloud/list", {
                page: this.page,
            });
        },
        async fetchData() {
            await this.$store.dispatch("databaseCloud/list");
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
                        await this.$store.dispatch("databaseCloud/destroy", id);
                        this.fetchData();
                    } catch (e) {}
                }
            });
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

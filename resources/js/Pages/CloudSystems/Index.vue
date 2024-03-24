<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

        <div class="flex items-center justify-end mb-6">
            <search-filter
                v-model="form.search"
                class="mr-4 w-full max-w-md"
                @reset="reset"
            >
                <div class="form-group mt-[20px]">
                <select-input v-model="form.type" :label="$t('Instance type')">
                    <option value="development">
                        {{ $t("Development System") }}
                    </option>
                    <option value="test">{{ $t("Test System") }}</option>
                    <option value="productive">
                        {{ $t("Productive System") }}
                    </option>
                </select-input>
                </div>
            </search-filter>
        </div>
        <div class="bg-white rounded-md shadow overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <tr class="text-left font-bold">
                    <th
                        @click="sort('systemNumber')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("System Number") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'systemNumber'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('systemName')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Name") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'systemName'"
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

                    <th class="pb-4 pt-6 px-6">{{ $t("Action") }}</th>
                </tr>
                <template
                    v-for="system in systemCloud?.data"
                    :key="system.id"
                    @contextmenu.stop.prevent="
                        (e) => {
                            e.preventDefault();
                            let route = $router.resolve(
                                `/system-cloud/${system.id}/edit`
                            );
                            window.open(route.href, '_blank');
                        }
                    "
                >
                    <tr class="">
                        <td class="border-t">
                            <router-link :to="`system-cloud/${system.id}/edit`">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                >
                                    {{ system.systemNumber }}
                                </a>
                            </router-link>
                        </td>

                        <td class="border-t">
                            <router-link :to="`system-cloud/${system.id}/edit`">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4"
                                    tabindex="-1"
                                >
                                    {{ system.systemName }}
                                </a>
                            </router-link>
                        </td>
                        <td class="border-t">
                            <router-link :to="`system-cloud/${system.id}/edit`">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4"
                                    tabindex="-1"
                                >
                                    {{ system.subType }}
                                </a>
                            </router-link>
                        </td>
                        <td class="border-t">
                            <router-link :to="`system-cloud/${system.id}/edit`">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4"
                                    tabindex="-1"
                                >
                                    {{ system.instanceType }}
                                </a>
                            </router-link>
                        </td>
                        <td class="w-px border-t">
                            <router-link
                                class="px-4"
                                :to="`system-cloud/${system.id}/edit`"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-pen-to-square"
                                />
                            </router-link>

                            <button @click.stop="destroy(system.id)">
                                <font-awesome-icon
                                    icon="fa-regular fa-trash-can"
                                />
                            </button>
                        </td>
                        <!-- other columns -->
                    </tr>
                    <!-- nested table -->
                </template>
                <tr v-if="systemCloud?.data.length === 0">
                    <td class="px-6 py-4 border-t" colspan="4">
                        {{ $t("No systems found") }}.
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="systemCloud"
                @pagination-change-page="changePageOrSearch"
            ></pagination>
            <br />
            <br />
        </div>
    </div>
</template>

<script>
import Icon from "../../Components/Icon.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import SelectInput from "@/Components/SelectInput.vue";
import Pagination from "laravel-vue-pagination";
import { mapGetters } from "vuex";
import SearchFilter from "../../Components/SearchFilter.vue";
import { debounce } from "@/utils/debounce";
export default {
    components: {
        Icon,
        Pagination,
        SearchFilter,
        PageHeader,
        SelectInput
    },
    computed: {
        ...mapGetters("systemCloud", ["systemCloud"]),
    },
    mounted() {
        this.refresh();
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
                    text: this.$t("Systems"),
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create System"),
                btn2Path: "/system-cloud/create",
            },
            page: 1,
            form: {
                search: "",
                type: "",
                sortBy: "",
                sortOrder: "",
            },
            collapsed: {},
            window,
        };
    },
    watch: {
        "form.search"(...val) {
            // Handle the change in the search property
            this.debouncedFetch(...val);
        },
        "form.type"(...val) {
            // Handle the change in the type property
            this.debouncedFetch(...val);
        },
        "form.sortBy"(...val) {
            // Handle the change in the type property
            this.debouncedFetch(...val);
        },
        "form.sortOrder"(...val) {
            // Handle the change in the type property
            this.debouncedFetch(...val);
        },
    },
    async created() {
        this.debouncedFetch = debounce(async (newValue, oldValue) => {
            try {
                await this.$store.dispatch("systemCloud/list", {
                    instanceType: this.form.type,
                    search: this.form.search,
                    sortOrder: this.form.sortOrder,
                    sortBy: this.form.sortBy,
                });
            } catch (e) {}
        }, 300);
    },
    methods: {
        toggleCollapse(systemId) {
            this.collapsed[systemId] = !this.collapsed[systemId];
        },
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("systemCloud/list", {
                page: page,
                search: this.form.search,
            });
        },
        async refresh(bypass) {
            await this.$store.dispatch("systemCloud/list");
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
                        await this.$store.dispatch("systemCloud/destroy", id);
                        this.refresh(true);
                    } catch (e) {}
                }
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

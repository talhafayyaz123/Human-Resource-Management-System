<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

        <div class="flex items-center justify-end mb-6">
            <!-- <router-link
                v-if="$can(`${$route.meta.permission}.create`)"
                class="secondary-btn"
                to="/systems/cloud/create"
            >
                <span>{{ $t("Create System") }}</span>
            </router-link> -->
            <search-filter
                v-model="form.search"
                class="mr-4 w-full max-w-md"
                @reset="reset"
            >
                <div class="form-group mt-1">
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
        <div class="table-responsive">
            <table class="w-full doc-table">
                <tr class="text-left font-bold">
                    <th
                        @click="sort('systemNumber')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("System Number")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'systemNumber'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('SystemTenant.name')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Tennant Name")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'SystemTenant.name'"
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
                        {{ $t("Sub Type")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'subType'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('url')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Url")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'url'"
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
                        {{ $t("Instance Type")
                        }}<font-awesome-icon
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
                    v-for="system in systemCloudForTenant"
                    :key="system.id"
                >
                    <tr
                        @contextmenu.stop.prevent="
                            (e) => {
                                e.preventDefault();
                                let route = $router.resolve(`/systems/cloud/${system.id}/edit`);
                                window.open(route.href, '_blank');
                            }
                        "
                        class=""
                    >
                        <td class="border-t">
                            <a
                                class="flex items-center cursor-pointer px-6 py-4 focus:text-indigo-500"
                            >
                                {{ system.systemNumber }}
                            </a>
                        </td>

                        <td class="border-t">
                            <a
                                class="flex items-center cursor-pointer px-6 py-4"
                                tabindex="-1"
                            >
                                {{ system.tenantName }}
                            </a>
                        </td>
                        <td class="border-t">
                            <a
                                class="flex items-center cursor-pointer px-6 py-4"
                                tabindex="-1"
                            >
                                {{ system.subType }}
                            </a>
                        </td>
                        <td class="border-t">
                            <a
                                :href="system.url ? system.url : '#'"
                                :target="system.url ? '_blank' : null"
                                class="flex items-center cursor-pointer px-6 py-4 cursor-pointer hover:text-blue-500 transition duration-200 ease-in-out"
                                v-if="system.url"
                                tabindex="-1"
                                @click.stop
                            >
                                {{ system.url }}
                            </a>
                            <span
                                v-else
                                class="flex items-center cursor-pointer px-6 py-4"
                                >N/A</span
                            >
                        </td>
                        <td class="border-t">
                            <a
                                class="flex items-center cursor-pointer px-6 py-4"
                                tabindex="-1"
                            >
                                {{ system.instanceType }}
                            </a>
                        </td>
                        <td class="w-px border-t">
                            <div class="flex items-center gap-x-2">
                                <router-link
                                    @click.stop=""
                                    :to="`/systems/cloud/${system.id}/edit?page=${page}`"
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-pen-to-square"
                                    />
                                </router-link>
                                <button
                                    v-if="
                                        $can(`${$route.meta.permission}.delete`)
                                    "
                                    @click.stop="destroy(system.id)"
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-trash-can"
                                    />
                                </button>
                            </div>
                        </td>
                        <!-- other columns -->
                    </tr>
                    <!-- nested table -->
                </template>
                <tr v-if="systemCloudForTenant.length === 0">
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
                :data="systemCloudForTenant"
                @pagination-change-page="changePageOrSearch"
            ></pagination>
            <br />
            <br />
        </div>
    </div>
</template>

<script>
import Icon from "@/Components/Icon.vue";

import Pagination from "laravel-vue-pagination";
import { mapGetters } from "vuex";
import SearchFilter from "@/Components/SearchFilter.vue";
import Terminal from "../../Invoices/Terminal.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { debounce } from "@/utils/debounce"
import SelectInput from "@/Components/SelectInput.vue";
export default {
    components: {
        Icon,
        Pagination,
        SearchFilter,
        Terminal,
        PageHeader,
        SelectInput
    },
    computed: {
        ...mapGetters("systemCloud", ["systemCloudForTenant"]),
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
                    text: "Master Data",
                    to: "/systems/cloud",
                },
                {
                    text: "Systems",
                    to: "/systems/cloud",
                },
                {
                    text: "Cloud",
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create System"),
                btn2Path: "/systems/cloud/create",
            },
            page: 1,
            form: {
                search: "",
                type: "",
                sortBy: "systemNumber",
                sortOrder: "asc",
            },
            collapsed: {},
            
            window
        };
    },

    watch: {
        'form.search'(...val) {
            this.debouncedFetch(...val);
        },
        'form.type'(...val) {
            this.debouncedFetch(...val);
        },
        'form.sortBy'(...val) {
            this.debouncedFetch(...val);
        },
        'form.sortOrder'(...val) {
            this.debouncedFetch(...val);
        },
    },
    created() {
        this.debouncedFetch = debounce(async (newValue, oldValue)=>{
            try {
                await this.$store.dispatch("systemCloud/listCloud", {
                    ...this.form,
                    instanceType: this.form.type,
                    search: this.form.search,
                });
            } catch (e) {}
        }, 300);
    },
    methods: {
        toggleCollapse(systemId) {
            this.collapsed[systemId] = !this.collapsed[systemId];
        },
        async changePageOrSearch(page = 1) {
            (this.page = page),
                await this.$store.dispatch("systemCloud/listCloud", {
                    page: this.page,
                    search: this.form.search,
                });
        },
        async refresh(bypass) {
            if(this.$route.query.page){
            this.page=this.$route.query.page;
            this.$router.replace({'query': null});
          }
            await this.$store.dispatch("systemCloud/listCloud",{
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
                     await this.$store.dispatch("systemCloud/deleteTenant", id);
                    this.refresh(true);
                }
            });
        },
        reset() {
            this.form = this.mapValues(this.form, () => null);
        },
        async download(id) {
            try {
                await this.$store.dispatch("systems/download", id);
            } catch (e) {}
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

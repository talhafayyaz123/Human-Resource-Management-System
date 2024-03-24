<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

        <div class="flex items-center justify-end mb-6">
            <search-filter
                v-model="form.search"
                class="mr-4 w-full max-w-md"
                @reset="reset"
            >
                <div class="form-group">
                    <select-input
                        v-model="form.instanceType"
                        :label="$t('Instance Type')"
                    >
                        <option value="database">
                            {{ $t("Database System") }}
                        </option>
                        <option value="development">
                            {{ $t("Development System") }}
                        </option>
                        <option value="test">{{ $t("Test System") }}</option>
                        <option value="productive">
                            {{ $t("Productive System") }}
                        </option>
                    </select-input>
                </div>
                <div class="form-group">
                    <MultiSelectInput
                        class="mt-4 w-full"
                        v-model="form.customerId"
                        :options="companies"
                        :multiple="false"
                        :textLabel="$t('Customer')"
                        label="companyName"
                        :trackBy="'id'"
                        :moduleName="'Companies'"
                        :taggable="true"
                    />
                </div>
            </search-filter>
        </div>
        <div class="table-responsive">
            <table class="w-full doc-table">
                <thead>
                    <tr class="text-left font-bold">
                        <th
                            @click="sort('systemNumber', 'premise')"
                            class="cursor-pointer"
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
                            @click="sort('systemName', 'premise')"
                            class="cursor-pointer"
                        >
                            {{ $t("System Name")
                            }}<font-awesome-icon
                                v-if="form.sortBy === 'systemName'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('customer', 'premise')"
                            class="cursor-pointer"
                        >
                            {{ $t("Customer")
                            }}<font-awesome-icon
                                v-if="form.sortBy === 'customer'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('serverIp', 'premise')"
                            class="cursor-pointer"
                        >
                            {{ $t("Server IP")
                            }}<font-awesome-icon
                                v-if="form.sortBy === 'serverIp'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('instanceType', 'premise')"
                            class="cursor-pointer"
                        >
                            {{ $t("instance Type")
                            }}<font-awesome-icon
                                v-if="form.sortBy === 'instanceType'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('operatingSystem', 'premise')"
                            class="cursor-pointer"
                        >
                            {{ $t("Operating System")
                            }}<font-awesome-icon
                                v-if="form.sortBy === 'operatingSystem'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('software', 'premise')"
                            class="cursor-pointer"
                        >
                            {{ $t("Software")
                            }}<font-awesome-icon
                                v-if="form.sortBy === 'software'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th class="pb-4 pt-6 px-6">{{ $t("Action") }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="system in premiseSystems?.data"
                        :key="system.id"
                        class="hover:bg-gray-100 focus-within:bg-gray-100"
                        @click.stop.right="
                            (e) => {
                                e.preventDefault();
                                let route = $router.resolve(
                                    `/systems/on-premise/${system.id}/edit`
                                );
                                window.open(route.href, '_blank');
                            }
                        "
                    >
                        <td class="">
                            <a
                                class="flex items-center cursor-pointer focus:text-indigo-500"
                            >
                                {{ system.systemNumber }}
                            </a>
                        </td>
                        <td class="">
                            <a>
                                <a
                                    class="flex items-center cursor-pointer focus:text-indigo-500"
                                >
                                    {{ system?.systemName }}
                                </a>
                            </a>
                        </td>
                        <td class="">
                            <a v-if="$can(`${$route.meta.permission}.edit`)">
                                <a
                                    class="flex items-center cursor-pointer focus:text-indigo-500"
                                >
                                    {{ system.customer }}
                                </a>
                            </a>
                        </td>
                        <td class="">
                            <a>
                                <a
                                    class="flex items-center cursor-pointer"
                                    tabindex="-1"
                                >
                                    {{ system.serverIp }}
                                </a>
                            </a>
                        </td>
                        <td class="">
                            <a>
                                <a
                                    class="flex items-center cursor-pointer"
                                    tabindex="-1"
                                >
                                    {{ $t(system.instanceType) }}
                                </a>
                            </a>
                        </td>
                        <td class="">
                            <a>
                                <a
                                    class="flex items-center cursor-pointer"
                                    tabindex="-1"
                                >
                                    {{ system.operatingSystem }}
                                </a>
                            </a>
                        </td>
                        <td class="">
                            <a
                                class="flex items-center cursor-pointer"
                                tabindex="-1"
                            >
                                {{ system.software }}
                            </a>
                        </td>
                        <td class="w-px">
                            <a
                                class="cursor-pointer px-1 mr-2"
                                @click.stop="
                                    $router.push(
                                        `/systems/on-premise/${system.id}/edit?page=${page}`
                                    )
                                "
                                v-if="$can(`${$route.meta.permission}.edit`)"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-pen-to-square"
                                />
                            </a>
                            <button
                                v-if="$can(`${$route.meta.permission}.delete`)"
                                @click.stop="destroy(system.id)"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-trash-can px-2"
                                />
                            </button>
                            <a
                                v-if="
                                    system.operatingSystem == 'windows' &&
                                    system.type == 'premise' &&
                                    $can(`${$route.meta.permission}.download`)
                                "
                                class="px-1"
                                @click.stop="download(system.id)"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-arrow-alt-circle-down"
                                />
                            </a>
                        </td>
                    </tr>
                    <tr v-if="premiseSystems.data.length === 0">
                        <td class="" colspan="4">
                            {{ $t("No systems found") }}.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            
            <pagination
                :limit="10"
                align="center"
                :data="premiseSystems"
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
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { debounce } from "@/utils/debounce";
import SelectInput from "@/Components/SelectInput.vue";

export default {
    components: {
        Icon,
        Pagination,
        SearchFilter,
        MultiSelectInput,
        PageHeader,
        SelectInput
    },
    computed: {
        ...mapGetters("systems", ["premiseSystems"]),
    },
    mounted() {
        var isPaginate=false;
        if(this.$route.query.page){
            this.page=this.$route.query.page;
            this.$router.replace({'query': null});
            isPaginate=true;
          }
        this.refresh(isPaginate);
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
                    to: "/systems/on-premise",
                },
                {
                    text: this.$t("Systems"),
                    to: "/systems/on-premise",
                },
                {
                    text: "On Premise",
                    active: true,
                },
            ],
            optionalItems: {
                isBtnShow: true,
                btnText: this.$t("Generate Install"),
                path: "/systems/on-premise",
                isBtn2Show: true,
                btn2Text: this.$t("Create System"),
                btn2Path: "/systems/on-premise/create",
            },
            page: 1,
            companies: [],
            window,
            form: {
                search: "",
                instanceType: "",
                customerId: "",
                sortBy:
                    localStorage.getItem("sort_premise_column") ??
                    "systemNumber",
                sortOrder: localStorage.getItem("sort_premise_order") ?? "asc",
            },
        };
    },
    watch: {
        "form.search"(...val) {
            this.debouncedFetch(...val);
        },
        "form.instanceType"(...val) {
            this.debouncedFetch(...val);
        },
        "form.customerId"(...val) {
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
                await this.$store.dispatch("systems/list", {
                    ...this.form,
                    type: "premise",
                    instanceType: this.form.instanceType,
                    search: this.form.search,
                    customerId: this.form.customerId["id"] ?? null,
                });
            } catch (e) {}
        }, 300);
    },
    methods: {
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("systems/list", {
                page: this.page,
                search: this.form.search,
                type: "premise",
            });
        },
        async refresh(byPass) {
            
            const response = await this.$store.dispatch("companies/list", {
                perPage: 25,
                page: 1,
            });
            this.companies = response?.data?.data ?? [];
            
            if (!this.premiseSystems.data.length || byPass)
                await this.$store.dispatch("systems/list", {
                    type: "premise",
                    page: this.page,
                    ...this.form,
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
                     await this.$store.dispatch("systems/destroy", id);
                    await this.$store.dispatch("systems/list", {
                        type: "premise",
                        page: this.page,
                        ...this.form,
                    });
                }
            });
        },
        reset() {
            this.form = {
                search: "",
                instanceType: "",
                customerId: "",
                sortBy:
                    localStorage.getItem("sort_premise_column") ??
                    "systemNumber",
                sortOrder: localStorage.getItem("sort_premise_order") ?? "asc",
            };
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

<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

        <div class="flex items-center justify-end mb-6">
            <search-filter
                v-model="form.search"
                class="mr-4 w-full max-w-md"
                @reset="reset"
            >
                <div class="form-group mt-1">
                    <select-input
                        v-model="form.instanceType"
                        :label="$t('Instance type')"
                    >
                        <option value="development">
                            {{ $t("Development System") }}
                        </option>
                        <option value="test">{{ $t("Test System") }}</option>
                        <option value="productive">
                            {{ $t("Productive System") }}
                        </option>
                    </select-input>
                </div>
                <div class="form-group mt-[20px]">
                <MultiSelectInput
                    v-model="form.customerId"
                    :options="companies"
                    :multiple="false"
                    :textLabel="$t('Customer')"
                    label="companyName"
                    :trackBy="'id'"
                    :moduleName="'companies'"
                    :taggable="true"
                    @asyncSearch="companiesSearch"
                />
                </div>
            </search-filter>
        </div>
        <div class="table-responsive">
            <table class="w-full doc-table">
                <tr class="text-left font-bold">
                    <th
                        @click="sort('systemNumber', 'hosting')"
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
                        @click="sort('customer', 'hosting')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
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
                        @click="sort('hostingType', 'hosting')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Hosting Type")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'hostingType'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('serverIp', 'hosting')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Host")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'serverIp'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('virtualMachine', 'hosting')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("System Name")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'virtualMachine'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('instanceType', 'hosting')"
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
                    <th
                        @click="sort('operatingSystem', 'hosting')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
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
                        @click="sort('software', 'hosting')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
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
                <tr
                    v-for="system in hostingSystems.data"
                    :key="system.id"
                    @click.stop.right="
                        (e) => {
                            e.preventDefault();
                            let route = $router.resolve(
                                `/systems/hosting/${system.id}/edit?page=${page}`
                            );
                            window.open(route.href, '_blank');
                        }
                    "
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
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
                            class="flex items-center cursor-pointer px-6 py-4 focus:text-indigo-500"
                        >
                            {{ system.customer }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex align-items-center items-center px-6 py-4 focus:text-indigo-500 capitalize"
                        >
                            {{ system.hostingType }}
                            <font-awesome-icon
                                v-if="system.hostingType === 'docker'"
                                title="Install Docker"
                                icon="fa-regular fa-arrow-alt-circle-down"
                                class="pl-3 pt-1"
                            ></font-awesome-icon>
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
                            tabindex="-1"
                        >
                            {{ system.serverIp }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
                            tabindex="-1"
                        >
                            {{ system.virtualMachine }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
                            tabindex="-1"
                        >
                            {{ system.instanceType }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
                            tabindex="-1"
                        >
                            {{ system.operatingSystem }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
                            tabindex="-1"
                        >
                            {{ system.software }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="cursor-pointer px-1 mr-2"
                            @click.stop="
                                $router.push(
                                    `/systems/hosting/${system.id}/edit?page=${page}`
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
                                $can(`${$route.meta.permission}.download`)
                            "
                            @click.stop="download(system.id)"
                        >
                            <font-awesome-icon
                                icon="fa-regular fa-arrow-alt-circle-down"
                            />
                        </a>
                    </td>
                </tr>
                <tr v-if="hostingSystems.data.length === 0">
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
                :data="hostingSystems"
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
import { debounce } from "@/utils/debounce"
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
        ...mapGetters("systems", ["hostingSystems"]),
    },
    mounted() { 
       var isPaginate=false;
        if(this.$route.query.page){
            this.page=this.$route.query.page;
            this.$router.replace({'query': null});
            isPaginate=true;
        }

        this.refresh( isPaginate);
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
                    to: "/systems/hosting",
                },
                {
                    text: this.$t("Systems"),
                    to: "/systems/hosting",
                },
                {
                    text: "Hosting",
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create System"),
                btn2Path: "/systems/hosting/create",
            },
            page: 1,
            window,
            companies: [],
            form: {
                search: "",
                instanceType: "",
                customerId: "",
                sortBy:
                    localStorage.getItem("sort_hosting_column") ??
                    "systemNumber",
                sortOrder: localStorage.getItem("sort_hosting_order") ?? "asc",
            },
        };
    },
    watch: {
        'form.search'(...val) {
            this.debouncedFetch(...val);
        },
        'form.instanceType'(...val) {
            this.debouncedFetch(...val);
        },
        'form.customerId'(...val) {
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
            
                await this.$store.dispatch("systems/list", {
                    ...this.form,
                    type: "hosting",
                    instanceType: this.form.instanceType,
                    search: this.form.search,
                    customerId: this.form.customerId["id"] ?? null,
                });
            } catch (e) {}
        }, 300);
    },
    methods: {
        companiesSearch(data) {
            this.companies = data?.data;
        },
        async changePageOrSearch(page = 1) {
            this.page = page;
        
            await this.$store.dispatch("systems/list", {
                page: this.page,
                search: this.form.search,
                type: "hosting",
            });
        },
        async refresh(bypass) {
           
            const response = await this.$store.dispatch("companies/list", {
                perPage: 25,
                page: 1,
            });
            this.companies = response?.data?.data ?? [];
          
            if (!this.hostingSystems.data.length || bypass){
        
                await this.$store.dispatch("systems/list", {
                    type: "hosting",
                    page: this.page,
                    ...this.form,
                });
            }
               
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

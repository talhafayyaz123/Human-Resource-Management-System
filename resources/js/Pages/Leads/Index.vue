<template>
    <div>
        <PageHeader
            :items="breadcrumbItems"
            :optionalItems="optionalItems"
            @csvBtn1="downloadCSV"
            @csvBtn2="downloadLatestCsv"
        />

        <div class="flex items-center justify-end mb-6">
            <search-filter
                :isFilter="true"
                v-model="form.search"
                class="mr-4 w-full max-w-md"
                @reset="reset"
            >
                <select-input :label="$t('Status')" v-model="form.leadStatus">
                    <option value="">All</option>
                    <option
                        v-for="(status, key) in leadStatus?.data"
                        :key="key"
                        :value="status.id"
                    >
                        {{ status.name }}
                    </option>
                </select-input>
            </search-filter>
        </div>
        <div class="table-responsive">
            <table class="doc-table">
                <thead>
                    <tr class="text-left">
                        <th class="cursor-pointer"></th>
                        <th
                            @click="sort('companyNumber', 'leads')"
                            class="cursor-pointer"
                        >
                            {{ $t("Lead Number")
                            }}<font-awesome-icon
                                v-if="form.sortBy === 'companyNumber'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('companyName', 'leads')"
                            class="cursor-pointer"
                        >
                            {{ $t("Lead Name")
                            }}<font-awesome-icon
                                v-if="form.sortBy === 'companyName'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('type', 'leads')"
                            class="cursor-pointer"
                        >
                            {{ $t("Type")
                            }}<font-awesome-icon
                                v-if="form.sortBy === 'type'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th class="cursor-pointer">
                            {{ $t("Source") }}
                        </th>
                        <th
                            @click="sort('url', 'leads')"
                            class="cursor-pointer"
                        >
                            {{ $t("URL")
                            }}<font-awesome-icon
                                v-if="form.sortBy === 'url'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('expiryDt', 'leads')"
                            class="cursor-pointer"
                        >
                            {{ $t("Expiring Date")
                            }}<font-awesome-icon
                                v-if="form.sortBy === 'expiryDt'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th class="pb-4 pt-6 px-6">{{ $t("Actions") }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="customer in leads?.data ?? []"
                        :key="customer.id"
                        class="hover:bg-gray-100 focus-within:bg-gray-100"
                        @contextmenu.stop.prevent="
                            (e) => {
                                e.preventDefault();
                                let route = $router.resolve(
                                    `/leads/${customer.id}/edit?page=${page}`
                                );
                                window.open(route.href, '_blank');
                            }
                        "
                    >
                        <td class="">
                            <div
                                v-if="customer.expiryDate"
                                class="flex flex-wrap bg-red-500 lead-tag"
                            >
                                {{ $t("DEMO") }}
                            </div>
                        </td>
                        <td class="">
                            {{ customer.companyNumber }}

                        </td>
                        <td class="">
                            {{ customer.companyName }}
                        </td>
                        <td class="">
                            {{ customer.type }}
                        </td>
                        <td class="">
                            <div class="flex items-center">
                                <span
                                    v-for="source in customer?.contactReportSources"
                                    :key="source.id"
                                    class="tag"
                                >
                                    {{ source.name }}
                                </span>
                            </div>
                        </td>
                        <td class="">
                            <a
                                :href="
                                        customer.url
                                            ? customerLink(customer.url)
                                            : '#'
                                    "
                                :target="
                                        customerLink(customer.url)
                                            ? '_blank'
                                            : null
                                    "
                                class="flex items-center cursor-pointer hover:text-blue-500 transition duration-200 ease-in-out"
                                tabindex="-1"
                                @click.stop
                            >
                                {{ customer.url }}
                            </a>
                        </td>
                        <td class="">
                            {{
                                $dateFormatter(
                                    customer.expiryDate,
                                    globalLanguage
                                )
                            }}
                        </td>
                        <td class="w-px  text-center">
                            <div class="flex items-center gap-x-2">
                                <router-link
                                    v-if="
                                        $can(`${$route.meta.permission}.show`)
                                    "
                                    :to="`/leads/${customer.id}?page=${page}`"
                                >
                                    <font-awesome-icon icon="fa-solid fa-eye" />
                                </router-link>
                                <router-link
                                    v-if="
                                        $can(`${$route.meta.permission}.edit`)
                                    "
                                    :to="`/leads/${customer.id}/edit?page=${page}`"
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-pen-to-square"
                                    />
                                </router-link>
                                <button
                                    v-if="
                                        $can(`${$route.meta.permission}.delete`)
                                    "
                                    @click="destroy(customer.id)"
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-trash-can"
                                    />
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="(leads?.data?.length ?? 0) === 0">
                        <td class="" colspan="4">
                            {{ $t("No leads found.") }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="leads"
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
import { mapGetters } from "vuex";
import SearchFilter from "../../Components/SearchFilter.vue";
import SelectInput from "../../Components/SelectInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { debounce } from "../../utils/debounce";
export default {
    computed: {
        ...mapGetters("companies", ["leads"]),
        ...mapGetters("leadStatus", ["leadStatus"]),
    },
    mounted() {
        this.refresh();
    },
    components: {
        Icon,
        Pagination,
        SearchFilter,
        SelectInput,
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
                    text: "Sales",
                    to: "/leads",
                },
                {
                    text: "Leads",
                    active: true,
                },
            ],
            optionalItems: {
                csvBtn1Show: true,
                csvBtn1Text: "Export CSV",
                csvBtn2Show: true,
                csvBtn2Text: "Export Latest CSV",
                isBtn2Show: true,
                btn2Text: "Create Lead",
                btn2Path: "/leads/create",
            },
            page: 1,
            form: {
                search: "",
                sortBy:
                    localStorage.getItem("sort_leads_column") ??
                    "companyNumber",
                sortOrder: localStorage.getItem("sort_leads_order") ?? "asc",
                customerType: "lead",
                leadStatus: "",
            },
            window,
        };
    },
    watch: {
        "form.search"(...val) {
            this.debouncedFetch(...val);
        },
        "form.customerType"(...val) {
            this.debouncedFetch(...val);
        },
        "form.leadStatus"(...val) {
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
                await this.$store.dispatch(
                    "companies/list",
                    this.pickBy(this.form)
                );
            } catch (e) {}
        }, 300);
    },
    methods: {
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("companies/list", {
                page: this.page,
                search: this.form.search,
                customerType: "lead",
                ...this.form,
            });
        },
        async refresh() {
            if (this.$route.query.page) {
                this.page = this.$route.query.page;
                this.$router.replace({ query: null });
            }
            await this.$store.dispatch("companies/list", {
                customerType: "lead",
                ...this.form,
                page: this.page,
            });
            await this.$store.dispatch("leadStatus/list", {
                limit_start: 0,
                limit_count: 25,
            });
        },
        reset() {
            this.form = this.mapValues(this.form, (value, key) =>
                key === "customerType" ? value : null
            );
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
                    await this.$store.dispatch("companies/destroy", id);
                    this.refresh();
                }
            });
        },
        async downloadCSV() {
            try {
                await this.$store.dispatch("companies/download", {
                    type: "lead",
                });
            } catch (e) {}
        },
        async downloadLatestCsv() {
            try {
                await this.$store.dispatch("companies/downloadLatestCsv", {
                    type: "lead",
                });
            } catch (e) {}
        },
        customerLink(incommingUrl) {
            if (incommingUrl) {
                let url = "";
                url = incommingUrl.trim();
                if (!/^https?:\/\//i.test(url)) {
                    url = `https://${url}`;
                }
                return url;
            }
            return incommingUrl;
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
.lead-tag {
    padding: 6px 12px;
    margin-top: 12px;
    border-radius: 16px;
    color: #fff;
    font-size: 14px;
    font-weight: 500;
}
</style>

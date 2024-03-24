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
                <MultiSelectInput
                    v-model="form.company"
                    :options="companies"
                    :multiple="false"
                    :textLabel="`${$t('Company')}/${$t('Lead')}`"
                    label="companyName"
                    trackBy="id"
                    moduleName="companies"
                    @asyncSearch="companiesSearch"
                />
            </search-filter>
        </div>
        <div class="table-responsive">
            <table class="w-full doc-table">
                <tr class="text-left font-bold">
                    <th
                        @click="sort('offerRequestNumber', 'offerRequests')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Offer Request Number")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'offerRequestNumber'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('Company.companyName', 'offerRequests')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Customer") + "/" + $t("Lead")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'Company.companyName'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('requestStatus', 'offerRequests')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Status")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'requestStatus'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('receiverType', 'offerRequests')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Receiver Type")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'receiverType'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Actions") }}</th>
                </tr>
                <tr
                    v-for="offer in offerRequests?.data ?? []"
                    :key="offer.id"
                    :style="`background-color: ${
                        offer.offerStatus === 'beauftragt'
                            ? 'rgba(105, 255, 152, 0.3)'
                            : offer.offerStatus === 'abgelehnt'
                            ? 'rgba(255, 84, 41, 0.3)'
                            : ''
                    }`"
                    @click.stop.right="
                        (e) => {
                            e.preventDefault();
                            let route = $router.resolve(
                                `/offer-requests/${offer.id}/edit?page=${page}`
                            );
                            window.open(route.href, '_blank');
                        }
                    "
                >
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4 focus:text-indigo-500"
                        >
                            {{ offer.offerRequestNumber }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4 focus:text-indigo-500"
                        >
                            {{ offer.receiver?.companyName }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4 focus:text-indigo-500 capitalize"
                        >
                            {{ offer.requestStatus }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4 capitalize"
                            tabindex="-1"
                        >
                            {{ offer.receiverType }}
                        </a>
                    </td>
                    <td @click.stop class="w-px border-t">
                        <div class="flex items-center gap-x-2">
                            <router-link
                                @click.stop=""
                                v-if="$can(`${$route.meta.permission}.show`)"
                                :to="`/offer-requests/${offer.id}?page=${page}`"
                            >
                                <font-awesome-icon icon="fa-solid fa-eye" />
                            </router-link>
                            <router-link
                                click.stop=""
                                v-if="$can(`${$route.meta.permission}.edit`)"
                                :to="`/offer-requests/${offer.id}/edit?page=${page}`"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-pen-to-square"
                                />
                            </router-link>
                            <button
                                v-if="$can(`${$route.meta.permission}.delete`)"
                                @click="destroy(offer.id)"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-trash-can"
                                />
                            </button>
                        </div>
                    </td>
                </tr>
                <tr v-if="(offerRequests?.data?.length ?? 0) === 0">
                    <td class="px-6 py-4 border-t" colspan="4">
                        {{ $t("No offer requests found") }}.
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="offerRequests ?? []"
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
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { debounce } from "../../utils/debounce";
export default {
    computed: {
        ...mapGetters("offerRequests", ["offerRequests"]),
    },
    components: {
        MultiSelectInput,
        Icon,
        Pagination,
        SearchFilter,
        SelectInput,
        PageHeader,
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
                    to: "/offer-requests",
                },
                {
                    text: "Offer Requests",
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Offer Request"),
                btn2Path: "/offer-requests/create",
            },
            window,
            page: 1,
            form: {
                search: "",
                company: "",
                sortBy:
                    localStorage.getItem("sort_offerRequests_column") ??
                    "offerRequestNumber",
                sortOrder:
                    localStorage.getItem("sort_offerRequests_order") ?? "asc",
            },
            companies: [],
        };
    },
    watch: {
        "form.search"(...val) {
            this.debouncedFetch(...val);
        },
        "form.company"(...val) {
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
                await this.$store.dispatch("offerRequests/list", {
                    ...this.form,
                    company: this.form.company?.id,
                });
            } catch (e) {}
        }, 300);
    },
    async mounted() {
        await this.refresh();
    },
    methods: {
        async refresh() {
            if(this.$route.query.page){
            this.page=this.$route.query.page;
            this.$router.replace({'query': null});
            }
            try {
                await this.$store.dispatch("offerRequests/list", {
                    ...this.form,
                    page: this.page,
                });
                this.$store
                    .dispatch("companies/list", {
                        page: 1,
                        perPage: 25,
                    })
                    .then((res) => {
                        this.companies = res?.data?.data ?? [];
                    });
            } catch (e) {}
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
                    await this.$store.dispatch("offerRequests/destroy", id);
                    this.refresh(true);
                }
            });
        },
        companiesSearch(data) {
            this.companies = data?.data;
        },
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("offerRequests/list", {
                page: this.page,
                search: this.form.search,
                sortBy: this.form.sortBy,
                sortOrder: this.form.sortOrder,
                ...this.form,
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

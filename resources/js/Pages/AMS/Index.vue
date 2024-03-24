<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

        <div class="table-responsive">
            <table class="doc-table">
                <thead>
                    <tr class="text-left">
                        <th @click="sort('AmsNumber')" class="cursor-pointer">
                            {{ $t("AMS Number") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'AmsNumber'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('Company.companyName')"
                            class="cursor-pointer"
                        >
                            {{ $t("Customer") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'Company.companyName'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('serviceHoursYear')"
                            class="cursor-pointer"
                        >
                            {{ $t("Service Hours Year") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'serviceHoursYear'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('remainingHours')"
                            class="cursor-pointer"
                        >
                            {{ $t("Remaining Hours") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'remainingHours'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th @click="sort('hourlyRate')" class="cursor-pointer">
                            {{ $t("Hourly Rate") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'hourlyRate'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('monthlyAmount')"
                            class="cursor-pointer"
                        >
                            {{ $t("Monthly Amount") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'monthlyAmount'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th class="">{{ $t("Action") }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="status in ams?.data"
                        :key="status.id"
                        class="hover:bg-gray-100 focus-within:bg-gray-100"
                        @contextmenu.stop.prevent="
                            (e) => {
                                e.preventDefault();
                                let route = $router.resolve(
                                    `/ams/${status.id}/edit?page=${page}`
                                );
                                window.open(route.href, '_blank');
                            }
                        "
                    >
                        <td class="">
                            <a
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ status.amsNumber }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ status.customerId?.companyName ?? "-" }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ status.serviceHoursYear }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ status.remainingHours }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ status.hourlyRate }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{
                                    $formatter(
                                        status.monthlyAmount,
                                        globalLanguage
                                    )
                                }}
                            </a>
                        </td>
                        <td class="w-px ">
                            <div class="flex items-center gap-2">
                                <router-link
                                    v-if="
                                        $can(`${$route.meta.permission}.list`)
                                    "
                                    class=""
                                    :to="`/ams/${status.id}/show?page=${page}`"
                                >
                                    <font-awesome-icon icon="fa-solid fa-eye" />
                                </router-link>
                                <router-link
                                    v-if="
                                        $can(`${$route.meta.permission}.edit`)
                                    "
                                    class=""
                                    :to="`/ams/${status.id}/edit?page=${page}`"
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-pen-to-square"
                                    />
                                </router-link>
                                <button
                                    class=""
                                    v-if="
                                        $can(`${$route.meta.permission}.delete`)
                                    "
                                    @click.stop="destroy(status.id)"
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-trash-can"
                                    />
                                </button>

                                
                            </div>
                        </td>
                    </tr>
                    <tr v-if="ams?.data.length === 0">
                        <td class="text-center" colspan="4">
                            {{ $t("No ams found") }}.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="ams"
                @pagination-change-page="changePageOrSearch"
            ></pagination>
            <br />
            <br />
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import Pagination from "laravel-vue-pagination";
import { debounce } from "../../utils/debounce";

import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    computed: {
        ...mapGetters("ams", ["ams"]),
    },
    components: {
        Pagination,
        PageHeader,
    },
    mounted() {
        this.refresh();
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Support"),
                    to: "/ams",
                },
                {
                    text: this.$t("Application Management Services"),
                    active:true
                }
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create AMS"),
                btn2Path: "/ams/create",
            },
            page: 1,
            form: {
                sortBy:
                    localStorage.getItem("sort_project_protocols_column") ??
                    "name",
                sortOrder:
                    localStorage.getItem("sort_project_protocols_order") ??
                    "asc",
            },
            window,
        };
    },
    watch: {
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
                await this.$store.dispatch("ams/list", {
                    ...this.form,
                });
            } catch (e) {}
        }, 300);
    },
    methods: {
        async refresh() {
            
        if(this.$route.query.page){
            this.page=this.$route.query.page;
            this.$router.replace({'query': null});

        }
            
            await this.$store.dispatch("ams/list", {
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
                    await this.$store.dispatch("ams/destroy", id);
                    this.refresh();
                }
            });
        },
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("ams/list", {
                page: this.page,
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

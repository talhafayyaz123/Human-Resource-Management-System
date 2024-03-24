<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

        <div class="table-responsive">
            <table class="doc-table">
                <thead>
                    <tr class="text-left">
                        <th @click="sort('itemNumber', 'requests')" class="">
                            {{ $t("Request Number") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'itemNumber'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th @click="sort('title', 'requests')" class="">
                            {{ $t("Product Title") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'title'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('shortDescription', 'requests')"
                            class=""
                        >
                            {{ $t("Short Description") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'shortDescription'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th @click="sort('createdAt', 'requests')" class="">
                            {{ $t("Creation Date") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'createdAt'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th @click="sort('sellPrice', 'requests')" class="">
                            {{ $t("UVP Sale Price") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'sellPrice'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th @click="sort('status', 'requests')" class="">
                            {{ $t("status") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'status'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th class="">{{ $t("Actions") }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="requ in requests ?? []"
                        :key="requ.id"
                        class="hover:bg-gray-100 focus-within:bg-gray-100"
                        @contextmenu.stop.prevent="
                            (e) => {
                                e.preventDefault();
                                let route = $router.resolve(
                                    `/requests/${requ.id}/edit`
                                );
                                window.open(route.href, '_blank');
                            }
                        "
                    >
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ requ.itemNumber }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ requ.productTitle }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                <p v-html="requ.shortDescription"></p>
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ $dateFormatter(requ.Date, globalLanguage) }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ requ.sellPrice }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ requ.status }}
                            </a>
                        </td>

                        <td class="w-px text-center">
                            <!-- <router-link
                            v-if="$can(`${$route.meta.permission}.show`)"
                            :to="`/requests/${requ.id}`"
                        >
                            <font-awesome-icon icon="fa-solid fa-eye"/>
                        </router-link> -->
                            <router-link
                                v-if="$can(`${$route.meta.permission}.edit`)"
                                class="mr-2"
                                :to="`/requests/${requ.id}/edit`"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-pen-to-square"
                                />
                            </router-link>
                            <button
                                v-if="$can(`${$route.meta.permission}.delete`)"
                                @click="destroy(requ.id)"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-trash-can"
                                />
                            </button>
                        </td>
                    </tr>
                    <tr v-if="(requests?.length ?? 0) === 0">
                        <td class="text-center" colspan="4">
                            {{ $t("No Requests found") }}.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :count="count"
                :perPage="perPage"
                :start="start"
                :length="requests.length"
                :currentPage="currentPage"
                @paginationInfo="refresh(true, $event.start, $event.end)"
            ></pagination>
            <br />
            <br />
        </div>
    </div>
</template>

<script>
import Icon from "../../Components/Icon.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import Pagination from "../../Components/Pagination.vue";

import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    computed: {
        ...mapGetters("requests", ["requests", "count"]),
    },
    components: {
        Icon,
        Pagination,
        MultiSelectInput,
        SelectInput,
        PageHeader,
    },
    props: {
        filters: Object,
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Requests",
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Request"),
                btn2Path: "/requests/create",
            },
            form: {
                sortBy:
                    localStorage.getItem("sort_requests_column") ??
                    "itemNumber",
                sortOrder: localStorage.getItem("sort_requests_order") ?? "asc",
            },

            page: 1,
            currentPage: 1,
            start: 0,
            perPage: 5,
            window,
        };
    },
    async mounted() {
        this.refresh(true, this.start, this.perPage);
    },
    watch: {
        form: {
            deep: true,
            handler: function () {
                this.throttle(async () => {
                    try {
                        await this.$store.dispatch("requests/list", {
                            ...this.form,
                            limit_start: this.start,
                            limit_count: this.perPage,
                        });
                    } catch (e) {}
                }, 150)();
            },
        },
    },
    methods: {
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("requests/list", {
                page: this.page,
            });
        },
        async refresh(bypass, start, end) {
            try {
                if (!this.requests.length || bypass) {
                    await this.$store.dispatch(
                        "requests/list",
                        this.pickBy({
                            ...this.form,
                            limit_start: start,
                            limit_count: end,
                        })
                    );
                }
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
                    await this.$store.dispatch("requests/destroy", id);
                    this.refresh(true);
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

.roles > :only-child {
    overflow: inherit !important;
    text-overflow: clip !important;
    white-space: normal !important;
    word-break: break-all !important;
}
</style>

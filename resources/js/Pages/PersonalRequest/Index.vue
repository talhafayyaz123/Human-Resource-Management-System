<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />
        <!-- <h1 class="header mb-8 text-3xl font-bold secondary-color">
            {{ $t("Personal Requests") }}
        </h1> -->
        <div class="flex items-center justify-end mb-6">
            <!-- <router-link
                v-if="$can(`${$route.meta.permission}.create`)"
                class="secondary-btn"
                to="/personal-requests/create"
            >
                <span>{{ $t("Create") }}</span>
                <span class="hidden md:inline"
                    >&nbsp;{{ $t("Personal Request") }}</span
                >
            </router-link> -->
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
                    <th class="pb-4 pt-6 px-6">
                        {{ $t("Requester") }}
                    </th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Department") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Team") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Job") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Location") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Contract Type") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Start Date") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Status") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Action") }}</th>
                </tr>
                <tr
                    v-for="request in requests?.data ?? []"
                    :key="request.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t">
                        <router-link
                            :to="`/personal-requests/${request.id}/edit`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ request.requester?.employee ?? "" }}
                        </router-link>
                    </td>
                    <td class="border-t">
                        <router-link
                            :to="`/personal-requests/${request.id}/edit`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ request.department?.name ?? "" }}
                        </router-link>
                    </td>
                    <td class="border-t">
                        <router-link
                            :to="`/personal-requests/${request.id}/edit`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ request.team?.name ?? "" }}
                        </router-link>
                    </td>
                    <td class="border-t">
                        <router-link
                            :to="`/personal-requests/${request.id}/edit`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ request.job?.jobName ?? "" }}
                        </router-link>
                    </td>
                    <td class="border-t">
                        <router-link
                            :to="`/personal-requests/${request.id}/edit`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ request.location?.name ?? "" }}
                        </router-link>
                    </td>
                    <td class="border-t">
                        <router-link
                            :to="`/personal-requests/${request.id}/edit`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ request.contractType?.name ?? "" }}
                        </router-link>
                    </td>
                    <td class="border-t">
                        <router-link
                            :to="`/personal-requests/${request.id}/edit`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{
                                $dateFormatter(
                                    request.startDate,
                                    globalLanguage
                                )
                            }}
                        </router-link>
                    </td>
                    <td class="border-t">
                        <router-link
                            :to="`/personal-requests/${request.id}/edit`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500 capitalize"
                        >
                            {{ request.status }}
                        </router-link>
                    </td>
                    <td class="w-px border-t">
                        <router-link
                            v-if="$can(`${$route.meta.permission}.edit`)"
                            class="px-1 mr-2"
                            :to="`/personal-requests/${request.id}/edit`"
                        >
                            <font-awesome-icon
                                icon="fa-regular fa-pen-to-square"
                            />
                        </router-link>
                        <button
                            v-if="
                                $can(`${$route.meta.permission}.delete`) &&
                                request.status !== 'approved'
                            "
                            @click="destroy(request.id)"
                        >
                            <font-awesome-icon icon="fa-regular fa-trash-can" />
                        </button>
                    </td>
                </tr>
                <tr v-if="(requests?.data?.length ?? 0) === 0">
                    <td class="px-6 py-4 border-t text-center" colspan="4">
                        {{ $t("No processes found") }}.
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="requests"
                @pagination-change-page="changePageOrSearch"
            ></pagination>
            <br />
            <br />
        </div>
    </div>
</template>

<script>
import Pagination from "laravel-vue-pagination";
import { mapGetters } from "vuex";
import SearchFilter from "@/Components/SearchFilter.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";

export default {
    computed: {
        ...mapGetters("personalRequests", ["requests"]),
    },
    components: {
        Pagination,
        SearchFilter,
        PageHeader
    },
    data() {
        return {
            page: 1,
            form: {
                search: "",
            },
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: this.$t("Personal Requests"),
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Personal Request"),
                btn2Path: "/personal-requests/create",
            },
        };
    },
    watch: {
        form: {
            deep: true,
            handler: function () {
                this.throttle(async () => {
                    try {
                        await this.$store.dispatch(
                            "personalRequests/list",
                            this.pickBy(this.form)
                        );
                    } catch (e) {}
                }, 150)();
            },
        },
    },
    mounted() {
        this.refresh();
    },
    methods: {
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("personalRequests/list", {
                page: this.page,
                search: this.form.search,
            });
        },
        async refresh(bypass) {
            try {
                if (!this.data?.data?.length || bypass)
                    await this.$store.dispatch("personalRequests/list");
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
                    try {
                        await this.$store.dispatch("personalRequests/destroy", id);
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

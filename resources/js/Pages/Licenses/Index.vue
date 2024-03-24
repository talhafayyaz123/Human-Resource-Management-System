<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

        <div class="table-responsive">
            <table class="doc-table">
                <thead>
                    <tr class="text-left">
                        <th class="">{{ $t("Name") }}</th>
                        <th class="">{{ $t("Company") }}</th>
                        <th class="">{{ $t("Tenant") }}</th>
                        <th class="">{{ $t("Creation Date") }}</th>
                        <th class="">{{ $t("Actions") }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="license in licenses ?? []"
                        :key="license.id"
                        class="hover:bg-gray-100 focus-within:bg-gray-100"
                        @contextmenu.stop.prevent="
                            (e) => {
                                e.preventDefault();
                                let route = $router.resolve(
                                    `/licenses/${license.id}/edit`
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
                                {{ license.name }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ license.company }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ license.tenant }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{
                                    $dateFormatter(
                                        license.creationDate,
                                        globalLanguage
                                    )
                                }}
                            </a>
                        </td>
                        <td class="w-px text-center">
                            <div class="flex item-center">
                                <router-link
                                    v-if="
                                        $can(`${$route.meta.permission}.show`)
                                    "
                                    :to="`/licenses/${license.id}`"
                                >
                                    <font-awesome-icon icon="fa-solid fa-eye" />
                                </router-link>
                                <router-link
                                    v-if="
                                        $can(`${$route.meta.permission}.edit`)
                                    "
                                    class="mx-2"
                                    :to="`/licenses/${license.id}/edit`"
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-pen-to-square"
                                    />
                                </router-link>
                                <button
                                    v-if="
                                        $can(`${$route.meta.permission}.delete`)
                                    "
                                    @click="destroy(license.id)"
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-trash-can"
                                    />
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="(licenses?.length ?? 0) === 0">
                        <td class=" " colspan="4">
                            {{ $t("No Licenses found") }}.
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
                :length="licenses.length"
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
        ...mapGetters("licensingLicenses", ["licenses", "count"]),
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
                    text: "License Generator",
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create License Generator"),
                btn2Path: "/licenses/create",
            },
            page: 1,
            currentPage: 1,
            start: 0,
            perPage: 25,
            window,
        };
    },
    async mounted() {
        this.refresh(true, this.start, this.perPage);
        try {
            await this.$store.dispatch("licensingLicenses/list");
        } catch (e) {}
    },
    methods: {
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("licensingLicenses/list", {
                page: this.page,
            });
        },
        async refresh(bypass, start, end) {
            try {
                if (!this.licenses.length || bypass) {
                    await this.$store.dispatch("licensingLicenses/list", {
                        limit_start: start,
                        limit_count: end,
                    });
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
                    await this.$store.dispatch("licensingLicenses/destroy", {
                        id: id,
                    });
                    this.refresh(true, this.start, this.perPage);
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

<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

        <div class="flex items-center justify-end mb-6">
            <search-filter
                :isFilter="true"
                v-model="form.search"
                class="mr-4 w-full max-w-md"
                @reset="reset"
            >
                <MultiSelectInput
                    v-model="form.companyId"
                    :options="companies"
                    :multiple="false"
                    textLabel="Company"
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
                    <th class="">{{ $t("ID") }}</th>
                    <th class="">{{ $t("Name") }}</th>
                    <th class="">
                        {{ $t("Documents Generated") }}
                    </th>
                    <th class="">{{ $t("Company") }}</th>
                    <th class="">{{ $t("Creation Date") }}</th>
                    <th class="">{{ $t("Action") }}</th>
                </tr>
                <tr
                    v-for="documentService in documentServices?.data ?? []"
                    :key="documentService.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                    @contextmenu.stop.prevent="
                        (e) => {
                            e.preventDefault();
                            let route = $router.resolve(
                                `/document-service/${documentService.id}/edit`
                            );
                            window.open(route.href, '_blank');
                        }
                    "
                >
                    <td class="">
                        <button class="flex items-center">
                            {{ documentService.id }}
                        </button>
                    </td>
                    <td class="">
                        <button
                            @click="
                                downloadFile(
                                    documentService.id,
                                    documentService.name
                                )
                            "
                            class="flex items-center"
                        >
                            {{ documentService.name }}
                        </button>
                    </td>
                    <td class="">
                        <a href="javascript:void(0)" class="flex items-center">
                            {{ documentService.generated_document_count }}
                        </a>
                    </td>
                    <td class="">
                        <a href="javascript:void(0)" class="flex items-center">
                            {{ companyNames[documentService.company_id] ?? "" }}
                        </a>
                    </td>
                    <td class="">
                        <a href="javascript:void(0)" class="flex items-center">
                            {{
                                $dateFormatter(
                                    new Date(
                                        (documentService?.creation_date ?? 0) *
                                            1000
                                    )
                                        ?.toISOString()
                                        ?.slice(0, 10),
                                    globalLanguage
                                )
                            }}
                        </a>
                    </td>
                    <td class="flex">
                        <router-link
                            class="mx-1"
                            @click.stop=""
                            v-if="$can(`${$route.meta.permission}.show`)"
                            :to="`/document-service/${documentService.id}`"
                        >
                            <font-awesome-icon icon="fa-solid fa-eye" />
                        </router-link>
                        <router-link
                            v-if="$can(`${$route.meta.permission}.edit`)"
                            class="mx-2"
                            :to="`/document-service/${documentService.id}/edit`"
                        >
                            <font-awesome-icon
                                icon="fa-regular fa-pen-to-square"
                            />
                        </router-link>
                        <button
                            v-if="$can(`${$route.meta.permission}.delete`)"
                            class="flex items-center px-1"
                            tabindex="-1"
                            method="delete"
                            as="button"
                            @click="destroy(documentService.id)"
                        >
                            <icon name="trash" class="mr-2 w-4 h-4 fill-red" />
                        </button>
                    </td>
                </tr>
                <tr v-if="(documentServices?.data?.length ?? 0) === 0">
                    <td class="text-center" colspan="4">
                        {{ $t("No templates found") }}.
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :count="count"
                :perPage="perPage"
                :start="start"
                :length="documentServices.data?.length"
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

import SearchFilter from "../../Components/SearchFilter.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import Pagination from "../../Components/Pagination.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters("documentService", ["documentServices", "count"]),
    },
    components: {
        Icon,
        Pagination,
        SearchFilter,
        MultiSelectInput,
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
                    text: "Document Service",
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Template List"),
                btn2Path: "/document-service/create",
            },
            companyNames: {},
            currentPage: 1,
            start: 0,
            perPage: 25,
            form: {
                search: "",
                companyId: "",
            },
            companies: [],
            window,
        };
    },
    watch: {
        documentServices: {
            handler: async function (val) {
                for (let i = 0; i < val?.data?.length ?? 0; i++) {
                    if (this.companyNames[val.data[i].company_id]) {
                        continue;
                    }
                    let company = this.companies?.data?.find(
                        (company) => company.id == val.data[i].company_id
                    );
                    if (company) {
                        this.companyNames[val.data[i].company_id] =
                            company.companyName;
                    } else if (val.data[i].company_id) {
                        try {
                            const response = await this.$store.dispatch(
                                "companies/show",
                                val.data[i].company_id
                            );
                            this.companyNames[val.data[i].company_id] =
                                response?.data?.modelData?.companyName ?? "";
                        } catch (e) {
                            console.log(e);
                        }
                    }
                }
            },
            deep: true,
        },
        "form.companyId"(val) {
            this.refresh(true, this.start, this.perPage);
        },
        "form.search"(val) {
            this.refresh(true, this.start, this.perPage);
        },
        // form: {
        //     deep: true,
        //     handler: function () {
        //         this.throttle(async () => {
        //             try {
        //                 alert('hello2')
        //                 await this.$store.dispatch("documentService/list", {
        //                     limit_start: this.start,
        //                     limit_count: this.perPage,
        //                     search_string: this.form.search,
        //                     company_id: this.form.companyId?.id,
        //                 });
        //             } catch (e) {
        //                 console.log(e);
        //             }
        //         }, 150)();
        //     },
        // },
    },
    mounted() {
        this.refresh(false, this.start, this.perPage);
        this.$store
            .dispatch("companies/list", {
                page: 1,
                perPage: 25,
            })
            .then((res) => {
                this.companies = res?.data?.data ?? [];
            });
    },
    methods: {
        async downloadFile(id, name) {
            return;
            /**   await this.$store.dispatch("documentService/download", {
                  id: id,
                  name: name,
              });*/
        },
        async changePageOrSearch(page = 1) {
            await this.$store.dispatch("documentService/list", {
                limit_start: 0,
                limit_count: 100,
            });
        },
        companiesSearch(data) {
            this.companies = data?.data;
        },
        async refresh(bypass, start, end) {
            await this.$nextTick();
            await this.$store.dispatch("documentService/list", {
                limit_start: start,
                limit_count: end,
                company_id: this.form.companyId?.id,
                search_string: this.form.search,
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
                    try {
                        await this.$store.dispatch("documentService/destroy", {
                            id: id,
                        });
                        this.refresh(true, this.start, this.perPage);
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

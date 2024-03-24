<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

        <div class="table-responsive">
            <table class="w-full doc-table">
                <thead>
                    <tr class="text-left font-bold">
                        <th
                            @click="sort('templateName')"
                            class="cursor-pointer"
                        >
                            {{ $t("Template Name") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'templateName'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th class="pb-4 pt-6 px-6">
                            {{ $t("Assigned Products") }}
                        </th>
                        <th
                            @click="sort('templateVersion')"
                            class="cursor-pointer"
                        >
                            {{ $t("Template Version") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'templateVersion'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('status')"
                            class="cursor-pointer"
                        >
                            {{ $t("status") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'status'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th class="cursor-pointer">
                            {{ $t("Action") }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="template in modifiedWorkshopTemplates?.data ??
                        []"
                        :key="template.id"
                        class="hover:bg-gray-100 focus-within:bg-gray-100"
                        @contextmenu.stop.prevent="
                            (e) => {
                                e.preventDefault();
                                let route = $router.resolve(
                                    `/workshop-templates/${template.id}/edit?page=${page}`
                                );
                                window.open(route.href, '_blank');
                            }
                        "
                    >
                        <td class="">
                            {{ template.templateName }}
                        </td>
                        <td class="">
                            <div
                                class="grid gap-2 grid-rows-1 focus:text-indigo-500"
                            >
                                <div
                                    class="flex"
                                    v-for="product in template.assignedProducts"
                                    :key="'product-' + product.id"
                                >
                                    <p class="mr-8">
                                        {{ product.articleNumber }}
                                    </p>
                                    <p>{{ product.name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="">
                            <div
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ template.templateVersion }}
                            </div>
                        </td>
                        <td class="">
                            <div
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ $t(template.status) }}
                            </div>
                        </td>
                        <td class="w-px text-center">
                            <div class="flex items-center gap-2">
                                <router-link
                                    v-if="template.status === 'stable'"
                                    :to="`/workshop-templates/${template.id}?page=${page}`"
                                >
                                    <font-awesome-icon icon="fa-solid fa-eye" />
                                </router-link>
                                <router-link
                                    v-if="
                                        template.id === 999 ||
                                        template.id === 1000
                                    "
                                    :to="`/workshop-templates/${template.id}/edit-temp?page=${page}`"
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-pen-to-square"
                                    />
                                </router-link>
                                <router-link
                                    v-else
                                    :to="`/workshop-templates/${template.id}/edit?page=${page}`"
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-pen-to-square"
                                    />
                                </router-link>
                                <button @click="destroy(template.id)">
                                    <font-awesome-icon
                                        icon="fa-regular fa-trash-can"
                                    />
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr
                        v-if="
                            (modifiedWorkshopTemplates?.data?.length ?? 0) === 0
                        "
                    >
                        <td class="" colspan="4">
                            {{ $t("No workshop templates found") }}.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="modifiedWorkshopTemplates"
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

import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    name: "WorkshopTemplates",
    mounted() {
        this.refresh();
    },

    computed: {
        ...mapGetters("workshopTemplates", ["workshopTemplates"]),
        modifiedWorkshopTemplates() {
            return {
                ...this.workshopTemplates,
                data: [
                    ...this.workshopTemplates.data,
                    {
                        id: 999,
                        templateName: "SAP",
                        assignedProducts: [
                            {
                                articleNumber: "SW-1121",
                                id: 139,
                                manufacturerNumber: "MSAP-SLSAP-INT",
                                name: "ELO Smart Link for SAP® ERP - Basis",
                                productCategory: null,
                                productSoftware: "ELO",
                                profit: "3885",
                                salePrice: "12950",
                                status: "active",
                                type: "software",
                            },
                            {
                                articleNumber: "SW-1123",
                                id: 141,
                                manufacturerNumber: "MSAP-CONP-INT",
                                name: "ELO Connectivity Pack for SAP® ERP - Basis",
                                productCategory: null,
                                productSoftware: "ELO",
                                profit: "2985",
                                salePrice: "9950",
                                status: "active",
                                type: "software",
                            },
                            {
                                articleNumber: "SW-1125",
                                id: 143,
                                manufacturerNumber: "MSAP-TOOL-INT",
                                name: "ELO Toolbox for SAP® ERP - Basis",
                                productCategory: null,
                                productSoftware: "ELO",
                                profit: "750",
                                salePrice: "2500",
                                status: "active",
                                type: "software",
                            },
                        ],
                        templateVersion: "1.0",
                        status: "draft",
                    },
                    {
                        id: 1000,
                        templateName: "ABAS",
                        assignedProducts: [],
                        templateVersion: "",
                        status: "draft",
                    },
                ],
            };
        },
    },
    components: {
        Pagination,
        PageHeader,
    },

    data() {
        return {
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Consulting"),
                    to: "/workshop-templates",
                },
                {
                    text: this.$t("Workshop Templates"),
                    active:true
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Workshop Template"),
                btn2Path: "/workshop-templates/create",
            },
            page: 1,
            form: {
                sortBy:
                    localStorage.getItem("sort_workshop_templates_column") ??
                    "templateName",
                sortOrder:
                    localStorage.getItem("sort_workshop_templates_order") ??
                    "asc",
            },
            window,
        };
    },
    methods: {
        async refresh() {
            if (this.$route.query.page) {
                this.page = this.$route.query.page;
                this.$router.replace({ query: null });
            }
            await this.$store.dispatch(
                "workshopTemplates/list",
                this.pickBy(this.form),
                {
                    perPage: 25,
                    page: this.page,
                }
            );
        },
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("workshopTemplates/list", {
                perPage: 25,
                page: this.page,
                sortBy: this.form.sortBy,
                sortOrder: this.form.sortOrder,
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
                    await this.$store.dispatch("workshopTemplates/destroy", id);
                    this.refresh();
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
</style>

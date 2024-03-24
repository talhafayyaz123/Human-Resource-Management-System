<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

        <div class="table-responsive">
            <table class="doc-table">
                <thead>
                    <tr class="text-left">
                        <th
                            @click="sort('date')"
                            class=" cursor-pointer"
                        >
                            {{ $t("Date") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'date'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('Company.companyName')"
                            class=" cursor-pointer"
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
                            @click="sort('ProtocolType.name')"
                            class=" cursor-pointer"
                        >
                            {{ $t("Protocol Type") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'ProtocolType.name'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('ProjectStatus.name')"
                            class=" cursor-pointer"
                        >
                            {{ $t("Project Status") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'ProjectStatus.name'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('Project.name')"
                            class=" cursor-pointer"
                        >
                            {{ $t("Project") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'Project.name'"
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
                        v-for="protocol in projectProtocols?.data"
                        :key="protocol.id"
                        class="hover:bg-gray-100 focus-within:bg-gray-100"
                        @contextmenu.stop.prevent="
                            (e) => {
                                e.preventDefault();
                                let route = $router.resolve(
                                    `/project-protocols/${protocol.id}/edit?page=${page}`
                                );
                                window.open(route.href, '_blank');
                            }
                        "
                    >
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center ocus:text-indigo-500"
                            >
                                {{ protocol?.date }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center ocus:text-indigo-500"
                            >
                                {{ protocol?.customer?.companyName ?? "-" }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center ocus:text-indigo-500"
                            >
                                {{ protocol?.protocolType?.name ?? "-" }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center ocus:text-indigo-500"
                            >
                                {{ protocol?.projectStatus?.name ?? "-" }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center ocus:text-indigo-500"
                            >
                                {{ protocol?.project?.projectNumber ?? "-" }}
                            </a>
                        </td>
                        <td class="w-px ">
                            <router-link
                                v-if="$can(`${$route.meta.permission}.edit`)"
                                class="px-2"
                                :to="`/project-protocols/${protocol.id}/edit?page=${page}`"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-pen-to-square"
                                />
                            </router-link>
                            <button
                                v-if="$can(`${$route.meta.permission}.delete`)"
                                @click.stop="destroy(protocol.id)"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-trash-can"
                                />
                            </button>
                        </td>
                    </tr>
                    <tr v-if="projectProtocols?.data.length === 0">
                        <td class="text-center" colspan="4">
                            {{ $t("No project protocols found") }}.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="projectProtocols"
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

import PageHeader from "@/Components/Layouts/Page-header.vue";
import { debounce } from "../../utils/debounce";
export default {
    computed: {
        ...mapGetters("projectProtocols", ["projectProtocols"]),
    },
    components: {
        Pagination,
        PageHeader,
    },
    mounted() {
        var isPginate=false;
        if(this.$route.query.page){
            this.page=this.$route.query.page;
            this.$router.replace({'query': null});
            isPginate=true;
        }
        this.refresh(isPginate);
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Project Management"),
                    to: "/project-protocols",
                },
                {
                    text: this.$t("Project Protocols"),
                    active:true
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Project Protocols"),
                btn2Path: "/project-protocols/create",
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
                await this.$store.dispatch("projectProtocols/list", {
                    ...this.form,
                });
            } catch (e) {}
        }, 300);
    },
    methods: {
        async refresh(bypass) {
            if (!this.projectProtocols?.data.length || bypass)
                await this.$store.dispatch("projectProtocols/list", {
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
                    await this.$store.dispatch("projectProtocols/destroy", id);
                    this.refresh(true);
                }
            });
        },
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("projectProtocols/list", {
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

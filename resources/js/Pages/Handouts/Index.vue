<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

        <div class="table-responsive">
            <table class="w-full doc-table">
                <thead>
                    <tr class="text-left">
                        <th
                            @click="sort('articleNumber')"
                            class="cursor-pointer"
                        >
                            {{ $t("Template Name") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'articleNumber'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th @click="sort('name')" class="cursor-pointer">
                            {{ $t("Assigned Products") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'name'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('productCategoryId')"
                            class="cursor-pointer"
                        >
                            {{ $t("Template Version") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'productCategoryId'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('paymentDetailsType')"
                            class="cursor-pointer"
                        >
                            {{ $t("Status") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'paymentDetailsType'"
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
                        v-for="handout in handouts?.data ?? []"
                        :key="handout.id"
                        class="hover:bg-gray-100 focus-within:bg-gray-100"
                        @contextmenu.stop.prevent="
                            (e) => {
                                e.preventDefault();
                                let route = $router.resolve(
                                    `/handouts/${handout.id}/edit?page=${page}`
                                );
                                window.open(route.href, '_blank');
                            }
                        "
                    >
                        <td class="border-t">
                            <div
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ handout.templateName }}
                            </div>
                        </td>
                        <td class="border-t">
                            <div
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ handout.assignedProducts }}
                            </div>
                        </td>
                        <td class="border-t">
                            <div
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ handout.templateVersions }}
                            </div>
                        </td>
                        <td class="border-t">
                            <div
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ handout.status }}
                            </div>
                        </td>
                        <td class="w-px border-t text-center">
                            <router-link
                                class="px-4"
                                :to="`/handouts/${handout.id}/edit?page=${page}`"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-pen-to-square"
                                />
                            </router-link>
                            <button @click="destroy(handout.id)">
                                <font-awesome-icon
                                    icon="fa-regular fa-trash-can"
                                />
                            </button>
                        </td>
                    </tr>
                    <tr v-if="(handouts?.data?.length ?? 0) === 0">
                        <td class="text-center" colspan="4">
                            {{ $t("No handouts found") }}.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="handouts"
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
    mounted() {
        this.refresh();
    },
    computed: {
        ...mapGetters("handouts", ["handouts"]),
    },
    components: {
        Pagination,
        PageHeader,
    },
    data() {
        return {
            page: 1,
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Consulting"),
                    to: "/handouts",
                },
                {
                    text: this.$t("Handouts"),
                    active:true
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Handouts"),
                btn2Path: "/handouts/create",
            },
            form: {},
            window,
        };
    },
    methods: {
        async refresh() {
            if(this.$route.query.page){
             this.page=this.$route.query.page;
             this.$router.replace({'query': null});
        }
            await this.$store.dispatch("handouts/list", {
                page: this.page
            });
        },
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("handouts/list", {
                page: this.page
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
                    await this.$store.dispatch("handouts/destroy", id);
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

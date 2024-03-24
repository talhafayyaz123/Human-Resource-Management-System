<template>
    <div>
        <!-- TODO: IMPLEMENT LATER -->
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

        <div class="flex items-center justify-end mb-6"></div>
        <div class="table-responsive">
            <table class="doc-table">
                <thead>
                    <tr class="text-left font-bold">
                        <th @click="sort('name')" class="cursor-pointer">
                            {{ $t("Name")
                            }}<font-awesome-icon
                                v-if="form.sortBy === 'name'"
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
                        v-for="contractType in modifiedContractTypes?.data"
                        :key="contractType.id"
                        class="hover:bg-gray-100 focus-within:bg-gray-100"
                        @click="
                            $router.push(
                                `/contract-types/${contractType.id}/edit`
                            )
                        "
                    >
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ contractType.name }}
                            </a>
                        </td>
                        <td class="">
                            <router-link
                                v-if="$can(`${$route.meta.permission}.edit`)"
                                class="px-1"
                                :to="`/contract-types/${contractType.id}/edit`"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-pen-to-square"
                                />
                            </router-link>
                            <button
                                v-if="$can(`${$route.meta.permission}.delete`)"
                                @click.stop="destroy(contractType.id)"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-trash-can"
                                />
                            </button>
                        </td>
                    </tr>
                    <tr v-if="contractTypes?.data.length === 0">
                        <td class="" colspan="4">
                            {{ $t("No contract types found") }}.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <br />
            <br />
        </div>
    </div>
</template>

<script>
import SearchFilter from "../../Components/SearchFilter.vue";
import { mapGetters } from "vuex";

import PageHeader from "@/Components/Layouts/Page-header.vue";

export default {
    computed: {
        ...mapGetters("contractTypes", ["contractTypes"]),
        ...mapGetters("documentService", ["documentServices"]),
    },
    components: {
        SearchFilter,
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
                    text: this.$t("Contract Types"),
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Contract Type"),
                btn2Path: "/contract-types/create",
            },
            modifiedContractTypes: {
                data: [],
                links: [],
            },
            form: {
                search: "",
                sortBy: "",
                sortOrder: "",
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
                            "contractTypes/list",
                            this.pickBy(this.form)
                        );
                        this.modifiedContractTypes.data =
                            this.contractTypes?.data?.map((contractType) => {
                                return {
                                    ...contractType,
                                    template:
                                        this.documentServices?.data?.find(
                                            (document) =>
                                                document.id ==
                                                contractType.template
                                        )?.name ?? contractType.template,
                                };
                            });
                    } catch (e) {}
                }, 150)();
            },
        },
    },
    async mounted() {
        // fetch the document services listing
        try {
            if (!this.documentServices?.data?.length)
                await this.$store.dispatch("documentService/list", {
                    limit_start: 0,
                    limit_count: 25,
                });
        } catch (e) {
            console.log(e);
        }
        // fetch the contract types listing
        this.refresh();
    },
    methods: {
        async refresh() {
            try {
                await this.$store.dispatch("contractTypes/list", {
                    page: this.page,
                    search: this.form.search,
                });
                // map the contract type template to the object found from documentServices listing
                this.modifiedContractTypes.data = this.contractTypes?.data?.map(
                    (contractType) => {
                        return {
                            ...contractType,
                            template:
                                this.documentServices?.data?.find(
                                    (document) =>
                                        document.id == contractType.template
                                )?.name ?? contractType.template,
                        };
                    }
                );
            } catch (e) {
                console.log(e);
            }
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
                        await this.$store.dispatch("contractTypes/destroy", id);
                        this.refresh();
                    } catch (e) {}
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

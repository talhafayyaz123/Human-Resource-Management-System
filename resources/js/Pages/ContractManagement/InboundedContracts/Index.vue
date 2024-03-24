<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <div class="table-responsive">
            <table class="w-full doc-table">
                <tr class="text-left font-bold">
                    <th class="pb-4 pt-6 px-6">{{ $t("Name") }}</th>
                    <th class="pb-4 pt-6 px-6">
                        {{ $t("Document Template") }}
                    </th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Action") }}</th>
                </tr>
                <tr
                    v-for="contract in inboundedContracts?.data"
                    :key="contract.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                    @contextmenu.stop.prevent="
                        (e) => {
                            e.preventDefault();
                            let route = $router.resolve(
                                `/inbounded-contracts/${contract.id}/edit`
                            );
                            window.open(route.href, '_blank');
                        }
                    "
                >
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ contract.name }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ contract.template }}
                        </a>
                    </td>
                    <td class="w-px border-t">
                        <router-link
                            v-if="$can(`${$route.meta.permission}.edit`)"
                            class="mx-2"
                            :to="`/inbounded-contracts/${contract.id}/edit`"
                        >
                            <font-awesome-icon
                                icon="fa-regular fa-pen-to-square"
                            />
                        </router-link>
                        <button
                            v-if="$can(`${$route.meta.permission}.delete`)"
                            @click.stop="destroy(contract.id)"
                        >
                            <font-awesome-icon icon="fa-regular fa-trash-can" />
                        </button>
                    </td>
                </tr>
                <tr v-if="(inboundedContracts?.data?.length ?? 0) === 0">
                    <td class="px-6 py-4 border-t text-center" colspan="4">
                        {{ $t("No inbounded contracts found") }}.
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <br />
            <br />
        </div>
    </div>
</template>

<script>
import SearchFilter from "@/Components/SearchFilter.vue";
import { mapGetters } from "vuex";

import PageHeader from "@/Components/Layouts/Page-header.vue";
import { debounce } from "@/utils/debounce";
export default {
    computed: {
        ...mapGetters("inboundedContracts", ["inboundedContracts"]),
    },
    components: {
        SearchFilter,
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
                    text: this.$t("Contract Managment"),
                    to: "/inbounded-contracts",
                },
                {
                    text: this.$t("Inbounded Contracts"),
                    active: true,
                }
            ],
            form: {
                search: "",
            },
            window,
        };
    },
    watch: {
        "form.search"(...val) {
            this.debouncedFetch(...val);
        },
    },
    created() {
        this.debouncedFetch = debounce(async (newValue, oldValue) => {
            try {
                await this.$store.dispatch(
                    "inboundedContracts/list",
                    this.pickBy(this.form)
                );
            } catch (e) {}
        }, 300);
    },
    async mounted() {
        // fetch the contract types listing
        await this.refresh();
    },
    methods: {
        /**
         * get the contract types listing
         */
        async refresh() {
            try {
                await this.$store.dispatch("inboundedContracts/list", {
                    page: this.page,
                    search: this.form.search,
                });
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * hit the contract type delete API
         * @param {id} id of contract type to be deleted
         */
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
                    await this.$store.dispatch(
                        "inboundedContracts/destroy",
                        id
                    );
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

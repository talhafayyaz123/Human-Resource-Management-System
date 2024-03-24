<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />
        <div class="flex items-center justify-end mb-6"></div>
        <div class="table-responsive">
            <table class="w-full doc-table">
                <tr class="text-left font-bold">
                    <th class="pb-4 pt-6 px-6">{{ $t("Name") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Client ID") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Company") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Tenant") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Creation Date") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Status") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Roles") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Actions") }}</th>
                </tr>
                <tr
                    v-for="apiKey in modifiedAPIKeys ?? []"
                    :key="apiKey.id"
                    @contextmenu.stop.prevent="
                        (e) => {
                            e.preventDefault();
                            let route = $router.resolve(
                                `/api-keys/${apiKey.id}/edit`
                            );
                            window.open(route.href, '_blank');
                        }
                    "
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ apiKey.name }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ apiKey.client_id }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{
                                companies?.data?.find((company) => {
                                    return company.id === apiKey.company_id;
                                })?.companyName ?? "-"
                            }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{
                                partners?.data?.find((partner) => {
                                    return partner.id === apiKey.tenant_id;
                                })?.companyName ?? "-"
                            }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{
                                $dateFormatter(
                                    apiKey.creation_date?.date,
                                    globalLanguage
                                )
                            }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ apiKey.status ? $t("Active") : $t("Inactive") }}
                        </a>
                    </td>
                    <td class="border-t roles">
                        <MultiSelectInput
                            @update:modelValue="addRole($event, apiKey)"
                            @remove:modelValue="removeRole($event, apiKey)"
                            v-model="apiKey.roles"
                            :key="apiKey.roles"
                            :options="roles"
                            moduleName="roles"
                            :search-param-name="'search_string'"
                            :trackBy="'id'"
                            :label="'title'"
                        />
                    </td>
                    <td class="w-px border-t text-center">
                        <router-link
                            v-if="$can(`${$route.meta.permission}.edit`)"
                            class="px-1 mr-2"
                            :to="`/api-keys/${apiKey.id}/edit`"
                        >
                            <font-awesome-icon
                                icon="fa-regular fa-pen-to-square"
                            />
                        </router-link>
                        <button
                            v-if="$can(`${$route.meta.permission}.delete`)"
                            @click="destroy(apiKey.id)"
                        >
                            <font-awesome-icon icon="fa-regular fa-trash-can" />
                        </button>
                    </td>
                </tr>
                <tr v-if="(apiKeys?.length ?? 0) === 0">
                    <td class="px-6 py-4 border-t" colspan="4">
                        {{ $t("No API Keys found") }}.
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
                :length="apiKeys.length"
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
import PageHeader from "@/Components/Layouts/Page-header.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import Pagination from "../../Components/Pagination.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters("apiKeys", ["apiKeys", "count"]),
        ...mapGetters("companies", ["companies", "partners"]),
        ...mapGetters("roles", ["roles"]),
        modifiedAPIKeys() {
            return this.apiKeys.map((apiKey) => {
                return {
                    ...apiKey,
                    roles: apiKey.roles
                        .map((roleId) => {
                            return {
                                ...(this.roles?.find(
                                    (role) => role.id == roleId
                                ) ?? {}),
                            };
                        })
                        .filter((role) => role.id),
                };
            });
        },
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
                    text: this.$t("API Keys"),
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create API Key"),
                btn2Path: "/api-keys/create",
            },
            page: 1,
            currentPage: 1,
            start: 0,
            perPage: 25,
            window,
        };
    },
    async mounted() {
        await this.refresh(true, this.start, this.perPage);

        try {
            await this.$store.dispatch("roles/list");
        } catch (e) {}
    },
    methods: {
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("apiKeys/list", {
                page: this.page,
            });
        },
        async refresh(bypass, start, end) {
            try {
                if (!this.companies?.data?.length) {
                    await this.$store.dispatch("companies/list");
                }
                if (!this.partners?.data?.length) {
                    await this.$store.dispatch("companies/list", {
                        page: 1,
                        perPage: 25,
                        customerType: "partner",
                    });
                }
                if (!this.apiKeys.length || bypass) {
                    await this.$store.dispatch("apiKeys/list", {
                        limit_start: start,
                        limit_count: end,
                    });
                }
            } catch (e) {}
        },
        /**
         * triggers when a selected option is removed from multiselect
         * removes the role from the apiKey
         * @param {removedOption} value/object of the removed option
         * @param {id} id of the removed option
         */
        async removeRole(event, apiKey) {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("apiKeys/update", {
                    ...apiKey,
                    roles: event.map((role) => role.id),
                });
            } catch (e) {}
        },
        /**
         * triggers when an options is selected from multiselect
         * adds a role to the apiKey
         * @param {value} value/object of the added option
         * @param {id} id of the added option
         */
        async addRole(event, apiKey) {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("apiKeys/update", {
                    ...apiKey,
                    roles: event.map((role) => role.id),
                });
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
                    await this.$store.dispatch("apiKeys/destroy", {
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

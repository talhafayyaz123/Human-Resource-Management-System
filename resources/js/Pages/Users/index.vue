<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

        <div class="items-center mb-6">
            <div class="flex justify-end">
                <search-filter
                    class="self-center mr-5 mt-6"
                    v-model="form.search"
                    @reset="reset"
                    :isFilter="false"
                >
                </search-filter>
                <div class="flex">
                    <MultiSelectInput
                        v-model="form.tenant_id"
                        :options="partners"
                        :multiple="false"
                        :textLabel="$t('Partner')"
                        label="companyName"
                        :trackBy="'id'"
                        :moduleName="'companies'"
                        :searchParamName="`partner`"
                    />
                    <div class="mx-3">
                        <MultiSelectInput
                            v-model="form.company_id"
                            :options="customers"
                            :multiple="false"
                            :textLabel="$t('Company')"
                            label="companyName"
                            :trackBy="'id'"
                            :moduleName="'companies'"
                            @asyncSearch="companiesSearch"
                        />
                    </div>
                </div>

                <select-input
                    v-model="form.type"
                    class="self-center mr-5"
                    label="Type"
                >
                    <option value="">{{ $t("All") }}</option>
                    <option value="employee">{{ $t("Employee") }}</option>
                    <option value="customer_employee">
                        {{ $t("Customer Employee") }}
                    </option>
                    <option value="partner">{{ $t("Partner") }}</option>
                </select-input>
            </div>
        </div>
        <div class="table-responsive">
            <table class="w-full doc-table">
                <tr class="text-left font-bold">
                    <th class="pb-4 pt-6 px-6">{{ $t("Name") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Email") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("City") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Street") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("ZIP") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Phone") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Roles") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Actions") }}</th>
                </tr>
                <tr
                    v-for="userList in modifiedUsers ?? []"
                    :key="userList.id"
                    @contextmenu.stop.prevent="
                        (e) => {
                            e.preventDefault();
                            let route = $router.resolve(
                                `/employees/${userList.id}/edit?page=${page}`
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
                            {{ userList.first_name }} {{ userList.last_name }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ userList.email }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ userList.city }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ userList.street }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ userList.zip }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ userList.phone }}
                        </a>
                    </td>
                    <td class="border-t roles">
                        <MultiSelectInput
                            @open="getRolesListing"
                            @update:modelValue="addRole($event, userList)"
                            @remove:modelValue="removeRole($event, userList)"
                            v-model="userList.roles"
                            :key="userList.roles"
                            :options="roles"
                            moduleName="roles"
                            :search-param-name="'search_string'"
                            :trackBy="'id'"
                            :label="'title'"
                        />
                    </td>
                    <td class="w-px border-t text-center">
                        <div class="flex items-center gap-x-2">
                            <router-link
                                v-if="
                                    $can(`${$route.meta.permission}.edit`) ||
                                    userList.can_be_edited === 1 ||
                                    user.roles.includes('admin')
                                "
                                :to="`/employees/${userList.id}/edit?page=${page}`"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-pen-to-square"
                                />
                            </router-link>
                            <button
                                v-if="
                                    $can(`${$route.meta.permission}.delete`) ||
                                    userList.can_be_deleted === 1 ||
                                    user.roles.includes('admin')
                                "
                                @click="destroy(userList.id)"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-trash-can"
                                />
                            </button>
                        </div>
                    </td>
                </tr>
                <tr v-if="(users?.length ?? 0) === 0">
                    <td class="px-6 py-4 border-t" colspan="4">
                        {{ $t("No users found") }}.
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
                :length="users.length"
                :currentPage="currentPage"
                @paginationInfo="fetchUsers(true, $event.start, $event.end)"
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
import SearchFilter from "../../Components/SearchFilter.vue";
import { mapGetters } from "vuex";
import { debounce } from "../../utils/debounce";

export default {
    computed: {
        ...mapGetters("auth", ["users", "count", "user"]),
        ...mapGetters("roles", ["roles"]),
    },
    components: {
        Icon,
        Pagination,
        MultiSelectInput,
        SelectInput,
        SearchFilter,
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
                    text: "Users",
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create User"),
                btn2Path: "/employees/create",
            },
            userSpecificRoles: [], // contains roles listing specific to user assigned roles
            modifiedUsers: [],
            page: 1,
            currentPage: 1,
            start: 0,
            perPage: 25,
            partners: [],
            customers: [],
            form: {
                type: "employee",
                search: "",
                tenant_id: "",
                company_id: "",
            },
            window,
        };
    },
    watch: {
        "form.type"(...val) {
            this.debouncedFetch(...val);
        },
        "form.search"(...val) {
            this.debouncedFetch(...val);
        },
        "form.tenant_id"(...val) {
            this.debouncedFetch(...val);
        },
        "form.company_id"(...val) {
            this.debouncedFetch(...val);
        },
    },
    created() {
        this.debouncedFetch = debounce(async (newValue, oldValue) => {
            try {
                await this.refresh(true, this.start, this.perPage);
                await this.fetchUserSpecificRoles();
                this.setModifiedUsers();
            } catch (e) {
                console.log(e);
            }
        }, 300);
    },
    async mounted() {
        let isPaginate = false;
        if (this.$route.query.page) {
            this.page = this.$route.query.page;
            this.$router.replace({ query: null });
            isPaginate = true;
        }
        try {
            //get all the partners
            const partnerResponse = await this.$store.dispatch(
                "companies/list",
                {
                    customerType: "partner",
                }
            );
            const customerLeadResponse = await this.$store.dispatch(
                "companies/list",
                {
                    customerType: ["lead", "customer"],
                }
            );
            this.customers = customerLeadResponse?.data?.data;
            this.partners = partnerResponse?.data?.data;
            await this.refresh(isPaginate, this.start, this.perPage);
            await this.fetchUserSpecificRoles();
            this.setModifiedUsers();
        } catch (e) {
            console.log(e);
        }
    },
    methods: {
        companiesSearch(data) {
            this.customers = data?.data;
        },
        partnersSearch(data) {
            this.partners = data?.data;
        },
        /**
         * fetch roles listing on multiselect open if not already fetched
         */
        async getRolesListing() {
            try {
                if (!this.roles?.length) {
                    await this.$store.dispatch("roles/list");
                }
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * fetches user listing and calls 'setModifiedUsers' to modify the roles
         * @param {bypass} force fetch the list
         * @param {start} limit start
         * @param {end} limit end
         */
        async fetchUsers(bypass, start, end) {
            try {
                await this.refresh(bypass, start, end);
                this.setModifiedUsers();
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * modifies the roles array for users
         */
        setModifiedUsers() {
            this.modifiedUsers = this.users.map((user) => {
                return {
                    ...user,
                    roles: user.roles
                        .map((roleId) => {
                            return {
                                ...(this.userSpecificRoles?.find(
                                    (role) => role.id == roleId
                                ) ?? {}),
                            };
                        })
                        .filter((role) => role.id),
                };
            });
        },
        /**
         * makes use of role/show API to fetch user specific roles listing
         */
        fetchUserSpecificRoles() {
            return new Promise(async (resolve, reject) => {
                try {
                    let roles = new Set(
                        this.users.flatMap((user) =>
                            user.roles instanceof Array ? user.roles : []
                        )
                    );
                    if (!!Array.from(roles).length) {
                        const response = await this.$store.dispatch(
                            "roles/show",
                            {
                                id: Array.from(roles),
                            }
                        );
                        this.userSpecificRoles =
                            response?.data instanceof Array
                                ? response?.data
                                : [];
                    }
                    resolve();
                } catch (e) {
                    reject(e);
                }
            });
        },
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("auth/list", {
                page: this.page,
                search: this.form.search,
            });
            await this.fetchUserSpecificRoles();
            this.setModifiedUsers();
        },
        refresh(bypass, start, end) {
            return new Promise(async (resolve, reject) => {
                try {
                    if (!this.users.length || bypass) {
                        await this.$store.dispatch("auth/list", {
                            limit_start: start,
                            limit_count: end,
                            ...this.form,
                            tenant_id: this.form.tenant_id?.id,
                            company_id: this.form.company_id?.id,
                            search_string: this.form.search,
                        });
                    }
                    resolve();
                } catch (e) {
                    reject(e);
                }
            });
        },
        /**
         * triggers when a selected option is removed from multiselect
         * removes the role from the user
         * @param {removedOption} value/object of the removed option
         * @param {id} id of the removed option
         */
        async removeRole(event, user) {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("auth/update", {
                    ...user,
                    types: Object.keys(user.types).filter(
                        (type) => user.types[type] == 1
                    ),
                    roles: event.map((role) => role.id),
                });
            } catch (e) {}
        },
        /**
         * triggers when an options is selected from multiselect
         * adds a role to the user
         * @param {value} value/object of the added option
         * @param {id} id of the added option
         */
        async addRole(event, user) {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("auth/update", {
                    ...user,
                    types: Object.keys(user.types).filter(
                        (type) => user.types[type] == 1
                    ),
                    roles: event.map((role) => role.id),
                });
            } catch (e) {}
        },
        reset() {
            this.form = {
                type: "employee",
                search: "",
            };
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
                    await this.$store.dispatch("auth/destroy", {
                        id: id,
                    });
                    await this.refresh(true, this.start, this.perPage);
                    await this.fetchUserSpecificRoles();
                    this.setModifiedUsers();
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

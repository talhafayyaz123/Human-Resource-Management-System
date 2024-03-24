<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />
        <div class="flex items-center justify-end mb-6">
            <div class="pb-8 pr-6 w-full flex justify-end">

                <div class="form-group pr-6 w-full lg:w-1/4">
                    <MultiSelectInput
                        v-model="form.tenant"
                        :key="form.tenant"
                        @open="fetchPartners"
                        :textLabel="$t('Tenant')"
                        :placeholder="$t('Select Tenant')"
                        :options="partners.data"
                        :multiple="false"
                        label="companyName"
                        trackBy="id"
                        moduleName="companies"
                        :query="{ customerType: 'partner' }"
                        :countStore="'partnerCount'"
                    />
                </div>
                <div class="form-group pr-6 w-full lg:w-1/4">
                    <MultiSelectInput
                        v-model="form.company"
                        :key="form.company"
                        @open="fetchCompaniesAndLeads"
                        :textLabel="$t('Company')"
                        :placeholder="$t('Select Company')"
                        :options="companies.data"
                        :multiple="false"
                        label="companyName"
                        trackBy="id"
                        moduleName="companies"
                        :query="{ customerType: ['customer', 'lead'] }"
                    />
                </div>
                <div class="form-group min-w-[100px]">
                    <select-input
                        :label="$t('Per Page')"
                        @input="
                            refresh(true, 0, $event.target.value);
                            currentPage = 1;
                        "
                        v-model="perPage"
                        name=""
                        id=""
                    >
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select-input>
                </div>
                <div class="form-group w-full lg:w-1/4">
                    <search-filter
                        class="self-center ml-5 "
                        v-model="form.search"
                        @reset="reset"
                        :isFilter="false"
                    >
                    </search-filter>
                </div>
            </div>


        </div>
        <div class="table-responsive roles-table">
            <table class="w-full doc-table">
                <tr class="text-left font-bold">
                    <th class="pb-4 pt-6 px-6">{{ $t("Name") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Permissions") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Tenant") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Company") }}</th>
                    <th class="pb-4 pt-6 px-6 text-center">
                        {{ $t("Actions") }}
                    </th>
                </tr>
                <tr
                    v-for="role in roles ?? []"
                    :key="role.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                    @contextmenu.stop.prevent="
                        (e) => {
                            e.preventDefault();
                            let route = $router.resolve(
                                `/roles/${role.id}/edit`
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
                            {{ role.title }}
                        </a>
                    </td>
                    <td class="border-t relative">
                        <button
                            style="outline: none; font-size: 0.8rem"
                            :id="'permission-button-' + role.id"
                            @click="showPermissions(role.id)"
                            :class="[
                                'px-3',
                                'py-2',
                                'rounded',
                                role.id == showId
                                    ? 'docsHeroLogo'
                                    : 'docsHeroColorBlue',
                            ]"
                        >
                            {{ role.id == showId ? "Hide" : "Show" }}
                            Permissions
                        </button>
                        <treeselect
                            :class="role.id == showId ? '' : 'hidden'"
                            class="sm:w-[15rem] md:w-[20rem] lg:w-[30rem]"
                            :ref="`treeselect-${role.id}`"
                            :clearable="false"
                            @update:modelValue="syncPermissions(role)"
                            v-model="role.permissions"
                            :multiple="true"
                            :options="permissionsTree"
                            :placeholder="$t('Search')"
                            :alwaysOpen="true"
                            openDirection="above"
                            :valueConsistsOf="'LEAF_PRIORITY'"
                        >
                            <template #option-label="props">
                                <p :title="props?.node?.raw?.description ?? ''">
                                    {{ props?.node?.raw?.label ?? "" }}
                                </p>
                            </template>
                        </treeselect>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ role.tenant }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ role.company }}
                        </a>
                    </td>
                    <td class="w-px border-t text-center">
                        <router-link
                            v-if="
                                $can(`${$route.meta.permission}.edit`) ||
                                role.can_be_edited === 1 ||
                                user.roles.includes('admin')
                            "
                            class="px-1 mr-2"
                            :to="`/roles/${role.id}/edit`"
                        >
                            <font-awesome-icon
                                icon="fa-regular fa-pen-to-square"
                            />
                        </router-link>
                        <button
                            v-if="
                                $can(`${$route.meta.permission}.delete`) ||
                                role.can_be_deleted === 1 ||
                                user.roles.includes('admin')
                            "
                            @click="destroy(role.id)"
                        >
                            <font-awesome-icon icon="fa-regular fa-trash-can" />
                        </button>
                    </td>
                </tr>
                <tr v-if="(roles?.length ?? 0) === 0">
                    <td class="px-6 py-4 border-t" colspan="4">
                        {{ $t("No roles found") }}.
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
                :length="roles.length"
                :currentPage="currentPage"
                @paginationInfo="refresh(true, $event.start, $event.end)"
            ></pagination>
            <br />
            <br />
        </div>
    </div>
</template>

<script>
import Treeselect from "@tkmam1x/vue3-treeselect";
import "@tkmam1x/vue3-treeselect/dist/vue3-treeselect.css";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import Icon from "../../Components/Icon.vue";
import SelectInput from "@/Components/SelectInput.vue";
import Pagination from "../../Components/Pagination.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue"
import { mapGetters } from "vuex";
import { v4 as uuidv4 } from "uuid";
import { debounce } from "../../utils/debounce";
import SearchFilter from "@/Components/SearchFilter.vue";
export default {
    components: {
        SearchFilter,
        Icon,
        Pagination,
        MultiSelectInput,
        Treeselect,
        PageHeader,
        SelectInput
    },
    computed: {
        ...mapGetters("auth", ["user"]),
        ...mapGetters("roles", ["roles", "count"]),
        ...mapGetters("companies", ["partners", "companies"]),
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Roles",
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Roles"),
                btn2Path: "/roles/create",
            },
            showId: 0,
            permissionsTree: [],
            permissions: [],
            currentPage: 1,
            start: 0,
            perPage: 25,
            shouldShow: false,
            form: {
                search: "",
                tenant: "",
                company: "",
            },
            scopes: {},
            window,
        };
    },
    watch: {
        "form.search"(...val) {
            this.debouncedFetch(...val);
        },
        "form.tenant"(...val) {
            this.debouncedFetch(...val);
        },
        "form.company"(...val) {
            this.debouncedFetch(...val);
        },
    },
    created() {
        this.debouncedFetch = debounce(async (newValue, oldValue) => {
            try {
                await this.refresh(true, this.start, this.perPage);
            } catch (e) {
                console.log(e);
            }
        }, 300);
    },
    unmounted() {
        this.resetCompanyStore();
    },
    mounted() {
        this.resetCompanyStore();
        this.refresh(false, this.start, this.perPage);
    },
    methods: {
        /**
         * resets filtered companies/leads
         */
        resetCompanyStore() {
            this.$store.commit("companies/companies", {
                data: [],
                links: [],
            });
            this.$store.commit("companies/partners", {
                data: [],
                links: [],
            });
        },
        /**
         * gets mixed companies and leads listing
         */
        async fetchCompaniesAndLeads() {
            try {
                if (!this.companies?.data?.length) {
                    await this.$store.dispatch("companies/list", {
                        customerType: ["customer", "lead"],
                    });
                }
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * gets partners listing
         */
        async fetchPartners() {
            try {
                if (!this.partners?.data?.length) {
                    await this.$store.dispatch("companies/list", {
                        customerType: "partner",
                    });
                }
            } catch (e) {
                console.log(e);
            }
        },
        async showPermissions(id) {
            const treeselectDom = this.$refs[`treeselect-${id}`]?.[0]?.$el;
            treeselectDom.style.left = "5rem";
            treeselectDom.querySelector(".vue-treeselect__input").placeholder =
                "Search";
            if (this.showId == id) {
                this.showId = 0;
            } else {
                this.showId = id;
            }
        },
        async changePageOrSearch(page = 1) {
            await this.$store.dispatch("roles/list", {
                page: page,
                search_string: this.form.search,
            });
        },
        /**
         * triggers when an options is selected from multiselect
         */
        async syncPermissions(role) {
            await this.$nextTick();
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("roles/update", {
                    ...role,
                    permissions: role.permissions,
                    can_register: role.can_register === true ? 1 : 0,
                    active: role.active === true ? 1 : 0,
                });
            } catch (e) {}
        },
        async refresh(bypass, start, end) {
            await this.$nextTick();
            if (!this.roles?.length || bypass)
                await this.$store.dispatch("roles/list", {
                    limit_start: start,
                    limit_count: end,
                    tenant_id: this.form.tenant?.id,
                    company_id: this.form.company?.id,
                    search_string: this.form.search
                });
            this.$store
                .dispatch("permissions/list", {
                    limit_start: 0,
                    limit_count: 1000,
                })
                .then((res) => {
                    this.permissions = res?.data?.data ?? [];
                })
                .finally(() => {
                    if (!this.permissionsTree?.length) {
                        this.createBasePermissionGroups();
                        this.createSectionedGroups();
                    }
                });
        },
        /**
         * the permissions that we get from the backend are in the form like this:
         * {active: 1
         *  can_be_assigned: 1
         *  description: "this is a description"
         *  grouping: "settings/administration"
         *  id: 1
         *  needs_permission: null
         *  scope: "auth_backend"
         *  title: "permission.list"
         *  value: 1}
         * this function creates 'permission' as a group and pushes the 'list' in it's children array
         * if we encounter a permission with the title 'permssion.create' we grab the already created 'permission' group
         * and push 'create' in it's children array
         * @returns {permissionsTree} the created groups tree
         */
        createBasePermissionGroups() {
            const scopes = {};
            this.permissions.forEach((permission) => {
                if (typeof permission?.title === "string") {
                    if (!scopes[permission.scope]) {
                        scopes[permission.scope] = {};
                    }
                    if (!permission.grouping) {
                        scopes[permission.scope][permission.title] = permission;
                    } else {
                        if (!scopes[permission.scope][permission.grouping])
                            scopes[permission.scope][permission.grouping] = {};
                        const permissionsTree =
                            scopes[permission.scope][permission.grouping];
                        const permissionKey = permission.title.split(".")?.[0];
                        const permissionName = permission.title.split(".")?.[1];
                        if (
                            scopes[permission.scope][permission.grouping][
                                permissionKey
                            ]
                        ) {
                            const permissionNode =
                                permissionsTree[permissionKey];
                            permissionNode["children"].push({
                                id: permission.id,
                                label: permissionName,
                                grouping: permission.grouping,
                                description: permission.description,
                                scope: permission.scope,
                            });
                        } else {
                            permissionsTree[permissionKey] = {
                                id: uuidv4(),
                                label: permission.title.split(".")?.[0],
                                grouping: permission.grouping,
                                description: permission.description,
                                scope: permission.scope,
                            };
                            if (
                                permission.title.split(".")?.[0] !==
                                permission.title
                            ) {
                                permissionsTree[permissionKey]["children"] = [
                                    {
                                        id: permission.id,
                                        label: permissionName,
                                        grouping: permission.grouping,
                                        description: permission.description,
                                        scope: permission.scope,
                                    },
                                ];
                            } else {
                                permissionsTree[permissionKey]["id"] =
                                    permission.id;
                            }
                        }
                        scopes[permission.scope][permission.grouping] =
                            permissionsTree;
                    }
                }
            });
            this.scopes = scopes;
        },
        /**
         * this.groups has the groups in the form like {'sidebar/master data': {all the groups related to this permission group}}
         * this function splits the key('sidebar/master data') by '/' and creates a parent(sidebar) and a child(master data) group and
         * puts all the relevant permissions groups in their respective parent/child groups
         */
        createSectionedGroups() {
            let permissionsTree = [];
            for (let scope in this.scopes) {
                let sectionedGroups = [];
                for (let group in this.scopes[scope]) {
                    let keys = group.split("/");
                    let parent = null;
                    keys.forEach((key, index) => {
                        if (index === 0) {
                            let foundGroup = sectionedGroups.find(
                                (improvedGroup) => improvedGroup.label === key
                            );
                            if (!foundGroup) {
                                if (this.scopes[scope][group]) {
                                    sectionedGroups.push({
                                        id: uuidv4(),
                                        label: key,
                                        children: [],
                                    });
                                } else {
                                    sectionedGroups.push(
                                        this.scopes[scope][group]
                                    );
                                }
                            }
                            parent = sectionedGroups.find(
                                (improvedGroup) => improvedGroup.label === key
                            );
                        } else {
                            let foundGroup = parent.children.find(
                                (child) => child.label === key
                            );
                            if (!foundGroup) {
                                parent.children.push({
                                    id: uuidv4(),
                                    label: key,
                                    children: [],
                                });
                            }
                            parent = parent.children.find(
                                (child) => child.label === key
                            );
                        }
                    });
                    if (
                        this.scopes[scope][group] &&
                        !this.scopes[scope][group]?.id
                    ) {
                        parent.children = [
                            ...parent.children,
                            ...Object.values(this.scopes[scope][group]),
                        ];
                    } else {
                        parent.children = [
                            ...parent.children,
                            this.scopes[scope][group],
                        ];
                    }
                }
                permissionsTree.push({
                    id: uuidv4(),
                    label: scope,
                    children: [...sectionedGroups],
                });
            }
            this.permissionsTree = [...permissionsTree];
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
                    await this.$store.dispatch("roles/destroy", {
                        id: id,
                    });
                    this.refresh(true, this.start, this.perPage);
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
.table-layout-fixed {
    table-layout: fixed;
}
:deep(.page-item.active .page-link) {
    background-color: #2957a4;
    border-color: #2957a4;
}
:deep(.page-link) {
    color: #2957a4;
}
:deep(.multiselect__tags-wrap) {
    display: flex;
    flex-wrap: wrap;
}
:deep(.vue-treeselect) {
    position: absolute;
    z-index: 99999;
    margin-bottom: 1rem;
}
:deep(.vue-treeselect__control) {
    opacity: 1;
}
:deep(.vue-treeselect__input) {
    width: auto !important;
    margin-left: 0.5rem;
}
:deep(.vue-treeselect__multi-value-item-container) {
    display: none;
}
:deep(.vue-treeselect__placeholder) {
    display: none !important;
}
:deep(.vue-treeselect__menu) {
    max-height: 200px !important;
}
:deep(.vue-treeselect__label) {
    text-transform: capitalize;
}
:deep(.vue-treeselect__option) {
    display: flex;
}
:deep(.vue-treeselect__option-arrow-container) {
    align-self: center;
}
</style>

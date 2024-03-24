<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <div class="tab-header">
            <ul class="">
                <li class="nav-item">
                    <a
                        v-if="$can(`${$route.meta.permission}.list`)"
                        class="nav-link"
                        @click="activeTab = 'listing'"
                        :class="
                            activeTab === 'listing'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("My Expenses") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        v-if="$can(`${$route.meta.permission}.create`)"
                        class="nav-link"
                        @click="activeTab = 'create'"
                        :class="
                            activeTab === 'create'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("New Request") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        v-if="approvalPermissions.isTravelExpenseApprover"
                        class="nav-link"
                        @click="activeTab = 'approve'"
                        :class="
                            activeTab === 'approve'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("Request for Approval") }}
                    </a>
                </li>
            </ul>
        </div>
        <div id="myTabContent">
            <div
                v-if="
                    activeTab === 'listing' &&
                    $can(`${$route.meta.permission}.list`)
                "
                id="listing"
                role="tabpanel"
                aria-labelledby="listing-tab"
            >
                <Listing />
            </div>
            <div
                v-else-if="
                    activeTab === 'create' &&
                    $can(`${$route.meta.permission}.create`)
                "
                id="create"
                role="tabpanel"
                aria-labelledby="create-tab"
            >
                <Create @created="this.activeTab = 'listing'" />
            </div>
            <div
                v-else-if="activeTab === 'approve'"
                id="approve"
                role="tabpanel"
                aria-labelledby="approve-tab"
            >
                <Approver />
            </div>
        </div>
    </div>
</template>

<script>
import LoadingButton from "@/Components/LoadingButton.vue";
import Listing from "./Listing.vue";
import Create from "./Create.vue";
import Approver from "./Approver.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("userProfile", ["approvalPermissions"]),
    },
    components: {
        LoadingButton,
        Listing,
        Create,
        Approver,
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
                    text: "Travel Expenses",
                    active: true,
                },
            ],
            activeClasses: "active",
            inactiveClasses: "inactive",
            activeTab: "listing",
        };
    },
    async mounted() {},
    methods: {},
};
</script>

<style scoped>
.table-layout-fixed {
    table-layout: fixed;
}

.disabled {
    color: lightgray;
    cursor: not-allowed;
}
</style>

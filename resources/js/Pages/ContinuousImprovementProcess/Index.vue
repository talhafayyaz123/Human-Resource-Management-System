<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <div class="tab-header">
            <ul class="">
                <li class="nav-item">
                    <a
                        v-if="$can(`${$route.meta.permission}.create`)"
                        class="nav-link"
                        @click="activeTab = 'new-improvement'"
                        :class="
                            activeTab === 'new-improvement'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("New Improvement") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        v-if="$can(`${$route.meta.permission}.list`)"
                        class="nav-link"
                        @click="activeTab = 'cip-list'"
                        :class="
                            activeTab === 'cip-list'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("CIP List") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        @click="activeTab = 'request-for-approval'"
                        :class="
                            activeTab === 'request-for-approval'
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
                    activeTab === 'new-improvement' &&
                    $can(`${$route.meta.permission}.create`)
                "
                
                id="new-improvement"
                role="tabpanel"
                aria-labelledby="new-improvement-tab"
            >
                <AddImprovement />
            </div>
            <div
                v-if="
                    activeTab === 'cip-list' &&
                    $can(`${$route.meta.permission}.list`)
                "
                
                id="cip-list"
                role="tabpanel"
                aria-labelledby="cip-list-tab"
            >
                <CIPList />
            </div>
            <div
                v-if="activeTab === 'request-for-approval'"
                
                id="request-for-approval"
                role="tabpanel"
                aria-labelledby="request-for-approval-tab"
            >
                <RequestForApproval />
            </div>
        </div>
    </div>
</template>

<script>
import AddImprovement from "./Components/AddImprovement.vue";
import CIPList from "./Components/CIPList.vue";
import RequestForApproval from "./Components/RequestForApproval.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    name: "ContinuousImprovementProcess",
    components: {
        AddImprovement,
        CIPList,
        RequestForApproval,
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
                    text: this.$t("Continuous Improvement Process"),
                    active: true,
                },
            ],
            activeTab: "new-improvement",
            activeClasses:
                "active",
            inactiveClasses:
                "inactive",
        };
    },
};
</script>

<style scoped></style>

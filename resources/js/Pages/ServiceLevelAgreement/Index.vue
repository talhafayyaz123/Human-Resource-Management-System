<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <div
            class="tab-header"
        >
        <ul>
            <li class="nav-item">
                <a
                    class="nav-link" @click="activeTab = 'serviceTimes'"
                    :class="
                        activeTab === 'serviceTimes'
                            ? activeClasses
                            : inactiveClasses
                    "
                >
                    {{ $t("SLA Service Times") }}
                </a>
            </li>
            <li class="nav-item">
                <a
                    class="nav-link" @click="activeTab = 'slaLevel'"
                    :class="
                        activeTab === 'slaLevel'
                            ? activeClasses
                            : inactiveClasses
                    "
                >
                    {{ $t("SLA Level") }}
                </a>
            </li>
        </ul>
            
        </div>
        <div id="myTabContent">
            <div
                v-if="activeTab === 'serviceTimes' && $can('sla-levels.list')"
                id="profile"
                role="tabpanel"
                aria-labelledby="serviceTimes-tab"
            >
                <ServiceTime />
            </div>
            <div
                v-if="activeTab === 'slaLevel' && $can('sla.list')"
                id="profile"
                role="tabpanel"
                aria-labelledby="slaLevel-tab"
            >
                <Level />
            </div>
        </div>
    </div>
</template>

<script>
import ServiceTime from "../../Components/ServiceLevelAgreement/ServiceTime.vue";
import Level from "../../Components/ServiceLevelAgreement/Level.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    components: {
        ServiceTime,
        Level,
        PageHeader
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Service Level Agreement",
                    active: true,
                },
            ],
            activeTab: "serviceTimes",
            activeClasses:
                "active",
            inactiveClasses:
                "inactive",
        };
    },
};
</script>
<style scoped>
.success-badge {
    background-color: green;
    color: white;
    padding: 4px 8px;
    text-align: center;
    border-radius: 5px;
}
.error {
    color: red;
}
</style>

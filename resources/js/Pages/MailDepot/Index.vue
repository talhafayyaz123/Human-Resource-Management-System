<template>
    <div>
        
        <PageHeader :items="breadcrumbItems" />
        <div class="tab-header">
            <ul>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        @click="activeTab = 'overview'"
                        :class="
                            activeTab === 'overview'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("Overview") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        @click="activeTab = 'profiles'"
                        :class="
                            activeTab === 'profiles'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("Profiles") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        @click="activeTab = 'rules'"
                        :class="
                            activeTab === 'rules'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("Rules") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        @click="activeTab = 'mails'"
                        :class="
                            activeTab === 'mails'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("Mails") }}
                    </a>
                </li>
            </ul>
        </div>

        <div id="myTabContent">
            <div
                v-if="activeTab === 'overview'"
                class="p-4"
                id="overview"
                role="tabpanel"
                aria-labelledby="overview-tab"
            >
            </div>
            <div
                v-if="activeTab === 'profiles'"
                class="p-4"
                id="profile"
                role="tabpanel"
                aria-labelledby="profile-tab"
            >
            <Profiles />
            </div>
            <div
                v-if="activeTab === 'rules'"
                class="p-4"
                id="rules"
                role="tabpanel"
                aria-labelledby="rules-tab"
            >
            <Rules/>
            </div>
            <div
                v-if="activeTab === 'mails'"
                class="p-4"
                id="mails"
                role="tabpanel"
                aria-labelledby="mails-tab"
            >
            <Mails />
            </div>
        </div>
    </div>
</template>

<script>
import PageHeader from "@/Components/Layouts/Page-header.vue";
import Rules from "./Components/Rules.vue";
import Mails from "./Components/Mails.vue";
import Profiles from "./Components/Profiles.vue";
export default {
    components: {
        PageHeader,
        Rules,
        Mails,
        Profiles
    },
    data() {
        return {
           activeTab: "overview",
            activeClasses: "active",
            inactiveClasses: "inactive",
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Mail Depot",
                    active: true,
                },
            ]
        };
    },
    methods: {
        
    }
};
</script>

<style scoped>
.table-layout-fixed {
    table-layout: fixed;
}
.link {
    text-decoration: underline;
    color: blue;
}
.employee-list {
    list-style-type: circle;
    margin-left: 2rem;
}
</style>

<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <div class="tab-header">
            <ul>
                <li class="nav-item">
                    <a
                        class="nav-link" @click="activeTab = 'new-request'"
                        :class="
                            activeTab === 'new-request'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("New Request") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link" @click="activeTab = 'vacation-calendar'"
                        :class="
                            activeTab === 'vacation-calendar'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("Vacation Calendar") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link" @click="activeTab = 'my-vacations'"
                        :class="
                            activeTab === 'my-vacations'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("My Vacations") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        v-if="isApprover || $hasRole('admin')"
                        class="nav-link" @click="activeTab = 'requests-for-approval'"
                        :class="
                            activeTab === 'requests-for-approval'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("Requests For Approval") }}
                    </a>
                </li>
            </ul>
        </div>
        <div id="myTabContent">
            <div
                v-if="activeTab === 'new-request'"
                
                id="new-request"
                role="tabpanel"
                aria-labelledby="new-request"
            >
                <NewRequest @isApprover="isApprover = $event" />
            </div>
            <div
                v-show="activeTab === 'vacation-calendar'"
                
                id="vacation-calendar"
                role="tabpanel"
                aria-labelledby="vacation-calendar"
            >
                <VacationCalendar />
            </div>
            <div
                v-if="activeTab === 'my-vacations'"
                
                id="my-vacations"
                role="tabpanel"
                aria-labelledby="my-vacations"
            >
                <MyVacations />
            </div>
            <div
                v-if="
                    (isApprover || $hasRole('admin')) &&
                    activeTab === 'requests-for-approval'
                "
                
                id="requests-for-approval"
                role="tabpanel"
                aria-labelledby="requests-for-approval"
            >
                <RequestsForApproval />
            </div>
        </div>
    </div>
</template>

<script>
import NewRequest from "./NewRequest.vue";
import VacationCalendar from "./VacationCalendar.vue";
import MyVacations from "./MyVacations.vue";
import RequestsForApproval from "./RequestsForApproval.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Vacation Request",
                    active: true,
                },
            ],
            isApprover: false,
            activeTab: "new-request",
            activeClasses: "active",
            inactiveClasses: "inactive",
        };
    },
    components: {
        RequestsForApproval,
        NewRequest,
        VacationCalendar,
        MyVacations,
        PageHeader,
    },
};
</script>

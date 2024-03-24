<template>
    <div
        v-if="
            $route.params.id &&
            $route.name !== `User` &&
            $route.name !== `Role` &&
            $route.name !== `Permission` &&
            $route.name !== `MailWebhook` &&
            $route.name !== `MailTemplate` &&
            $route.name !== `APIKeys` &&
            $route.name !== `LicenseEventName` &&
            $route.name !== `Rules` &&
            $route.name !== `DocumentService`
        "
    >
        <div class="assets-list-btn">
            <ul>
                <li>
                    <div class="cursor-pointer" @click="toggleFeeds()">
                        <img src="@/assets/images/feeds.png" alt="" />
                    </div>
                </li>
                <li>
                    <div class="cursor-pointer" @click="toggleDocPilot()">
                        <img src="@/assets/images/doc-pilot.png" alt="" />
                    </div>
                </li>
                <li>
                    <div
                        class="cursor-pointer"
                        @click="historyToggle = !historyToggle"
                    >
                        <img src="@/assets/images/history.png" alt="" />
                    </div>
                </li>
            </ul>
        </div>

        <History
            v-if="routeName == 'UserProfile' && historyToggle && activeTab"
            ref="edit-history"
            @closeHistory="handleCloseHistory"
            :id="
                activeTab == 'profile'
                    ? $route.params.id
                    : activeTab == 'job'
                    ? jobFormId
                    : compensationFormId
            "
            :moduleName="
                activeTab == 'profile'
                    ? 'UserProfileData'
                    : activeTab == 'job'
                    ? 'UserJobData'
                    : 'UserCompensationData'
            "
            :displayName="
                activeTab == 'profile'
                    ? 'User Profile'
                    : activeTab == 'job'
                    ? 'Job'
                    : 'Compensation'
            "
        />

        <History
            v-else-if="historyToggle && routeName !== 'UserProfile'"
            @closeHistory="handleCloseHistory"
            ref="edit-history"
            :id="$route.params.id"
            :moduleName="routeName"
            :displayName="routeName"
        />

        <div class="feeds-setting" :class="{ show: objectFeed }">
            <ObjectFeeds
                :id="$route.params.id"
                :currentRoutePath="$route.path"
                :moduleName="routeName"
                @closefeeds="handleCloseFeeds"
            />
        </div>
        <div class="docPilot-setting" :class="{ show: docPilot }">
            <DocPilots @closeDocPilot="handleCloseDocPilot" />
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";

import ObjectFeeds from "./Components/ObjectFeeds.vue";
import DocPilots from "./Components/DocPilot.vue";
import History from "./Components/History.vue";
export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("suppliers", ["suppliers"]),
        ...mapGetters("assetList", ["assetLists"]),
        ...mapGetters("userProfile", [
            "activeTab",
            "jobFormId",
            "compensationFormId",
        ]),
        routeName() {
            const routeName = this.$route.name;
            let requiredRouteName = routeName.replace("ShowRecord", "");
            if (routeName === "SystemHostingSystem") {
                requiredRouteName = "System";
                return requiredRouteName;
            } else if (routeName === "SystemPremiseSystem") {
                requiredRouteName = "System";
                return requiredRouteName;
            } else if (routeName === "SystemCloudSystem") {
                requiredRouteName = "System";
                return requiredRouteName;
            } else if (
                routeName === "CompanyLead" ||
                routeName === "CompanyLeadShowRecord"
            ) {
                requiredRouteName = "Company";
                return requiredRouteName;
            } else if (
                routeName === "CompanyPartner" ||
                routeName === "CompanyPartnerShowRecord"
            ) {
                requiredRouteName = "Company";
                return requiredRouteName;
            } else if (
                routeName === "SaleOfferOrderConfirmation" ||
                routeName === "SaleOfferOrderConfirmationShowRecord"
            ) {
                requiredRouteName = "SaleOffer";
                return requiredRouteName;
            } else if (routeName === "InvoiceOpenPost") {
                requiredRouteName = "Invoice";
                return requiredRouteName;
            } else if (routeName === "WorkshopTemplatesEditTemp") {
                requiredRouteName = "WorkshopTemplate";
                return requiredRouteName;
            }
            return requiredRouteName;
        },
    },
    components: {
        ObjectFeeds,
        DocPilots,
        History,
    },
    data() {
        return {
            objectFeed: false,
            docPilot: false,
            historyToggle: false,
        };
    },
    watch: {
        objectFeed(newVal) {
            // Use a watcher to detect changes in toggleAssetListModal
            if (newVal) {
                document.body.classList.add("modal-layout");
            } else {
                document.body.classList.remove("modal-layout");
            }
        },
    },
    methods: {
        toggleFeeds() {
            this.objectFeed = !this.objectFeed;
            // if (this.objectFeed) {
            //     document.body.classList.add("modal-layout");
            // } else {
            //     document.body.classList.remove("modal-layout");
            // }
        },
        toggleDocPilot() {
            this.docPilot = !this.docPilot;
        },
        handleCloseFeeds() {
            this.objectFeed = false;
        },
        handleCloseDocPilot() {
            this.docPilot = false;
        },
        handleCloseHistory() {
            this.historyToggle = false;
        },
    },
};
</script>

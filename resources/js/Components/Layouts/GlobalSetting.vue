<template>
    <div class="main-setting">
        <div class="close-overlay" @click="closeGlobalSetting"></div>
        <div ref="splitContainer" class="flex">
            <div ref="pane1"></div>
            <div class="settings" ref="pane2">
                <div class="close-setting" @click="closeGlobalSetting">
                    <img src="@/assets/images/global-setting.svg" alt="" />
                </div>
                <div class="setting-border"></div>
                <div class="setting-header">
                    <!-- <img src="@/assets/images/globalname.svg" alt="" /> -->
                    <h5 class="whitespace-nowrap" style="color: #f59630">
                        {{ $t("G L O B A L") }}
                    </h5>
                    <h5 class="whitespace-nowrap ml-4" style="color: #f59630">
                        {{ $t("S E T T I N G") }}
                    </h5>

                    <div class="search-setting">
                        <input
                            type="text"
                            v-model="searchTerm"
                            :placeholder="$t('Search')"
                            class="form-control"
                        />
                    </div>
                </div>
                <div class="setting-border"></div>
                <div class="setting-accordians">
                    <img
                        src="@/assets/images/Union.svg"
                        class="union-img"
                        alt=""
                    />
                    <div class="accordion" id="setting-accordian">
                        <div class="setting-expand">
                            <div class="switch">
                                <div class="switch-label">
                                    {{ $t("Expand all") }}
                                </div>
                                <input
                                    type="checkbox"
                                    id="switch"
                                    v-model="expandAll"
                                    @change="toggleExpandAll()"
                                />
                                <label for="switch"></label>
                            </div>
                        </div>
                        <div class="row">
                            <div
                                :class="colClass"
                                v-for="menu in filteredMenus"
                                :key="menu.id"
                            >
                                <div class="accordion-item">
                                    <h2
                                        class="accordion-header"
                                        @click="toggleAccordion(menu.id)"
                                    >
                                        <div
                                            class="accordion-button"
                                            type="button"
                                        >
                                            <div class="flex items-center">
                                                <div class="icon">
                                                    <icon :name="menu.icon" />
                                                </div>
                                                <span>{{ $t(menu.name) }}</span>
                                            </div>
                                            <div class="down-icon">
                                                <icon name="cheveron-down" />
                                            </div>
                                        </div>
                                    </h2>
                                    <div
                                        v-show="
                                            openAccordions.includes(menu.id)
                                        "
                                        class="accordion-collapse"
                                    >
                                        <div class="accordion-body">
                                            <ul class="listing">
                                                <li
                                                    :id="menu.id"
                                                    v-for="child in menu.children"
                                                    :key="child.subId"
                                                >
                                                    <router-link
                                                        :to="child.subLink"
                                                    >
                                                        <div class="mr-2">
                                                            <icon
                                                                name="circleWhiteIcon"
                                                            />
                                                        </div>
                                                        <span>{{
                                                            $t(child.name)
                                                        }}</span>
                                                    </router-link>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Icon from "../Icon.vue";
import Split from "split.js";

export default {
    components: {
        Icon,
        Split,
    },
    data() {
        return {
            menus: [
                {
                    id: "administration",
                    icon: "AdministrationIcon",
                    name: "Administration",
                    children: [
                        {
                            subId: "user",
                            subIcon: "fas fa-circle",
                            subLink: "/employees",
                            name: "Users",
                        },
                        {
                            subId: "roles",
                            subIcon: "fas fa-circle",
                            subLink: "/roles",
                            name: "Roles",
                        },
                        {
                            subId: "permissions",
                            subIcon: "fas fa-circle",
                            subLink: "/permissions",
                            name: "Permissions",
                        },
                        {
                            subId: "mail-webhooks",
                            subIcon: "fas fa-circle",
                            subLink: "/mail-webhooks",
                            name: "Mail Webhooks",
                        },
                        {
                            subId: "mail-templates",
                            subIcon: "fas fa-circle",
                            subLink: "/mail-templates",
                            name: "Mail Templates",
                        },
                        {
                            subId: "api-keys",
                            subIcon: "fas fa-circle",
                            subLink: "/api-keys",
                            name: "Api Keys",
                        },
                    ],
                },
                {
                    id: "system",
                    icon: "GlobalSystemIcon",
                    name: "System",
                    children: [
                        {
                            subId: "hosting-system",
                            subIcon: "fas fa-circle",
                            subLink: "/system-hosts",
                            name: "Hosting System",
                        },
                        // {
                        //     subId: "database-cloud",
                        //     subIcon: "fas fa-circle",
                        //     subLink: "/database-cloud",
                        //     name: "Database Cloud",
                        // },
                        // {
                        //     subId: "distributed-filesystem",
                        //     subIcon: "fas fa-circle",
                        //     subLink: "/distributed-filesystem",
                        //     name: "Distributed Filesystem",
                        // },
                        // {
                        //     subId: "system-cloud",
                        //     subIcon: "fas fa-circle",
                        //     subLink: "/system-cloud",
                        //     name: "System Cloud",
                        // },
                        {
                            subId: "operating-system",
                            subIcon: "fas fa-circle",
                            subLink: "/operating-system",
                            name: "Operating System",
                        },
                    ],
                },
                {
                    id: "personal-management",
                    icon: "GlobalPersonalManagementIcon",
                    name: "Personal Management",
                    children: [
                        {
                            subId: "expiry-month",
                            subIcon: "fas fa-circle",
                            subLink: "/expiry-month",
                            name: "Expiry Month",
                        },
                        {
                            subId: "form-of-contract",
                            subIcon: "fas fa-circle",
                            subLink: "/form-of-contract",
                            name: "Form Of Contract",
                        },
                        {
                            subId: "job-level",
                            subIcon: "fas fa-circle",
                            subLink: "/job-level",
                            name: "Job Level",
                        },
                        {
                            subId: "highest-school-degrees",
                            subIcon: "fas fa-circle",
                            subLink: "/highest-school-degrees",
                            name: "Highest School Degrees",
                        },
                        {
                            subId: "highest-education-levels",
                            subIcon: "fas fa-circle",
                            subLink: "/highest-education-levels",
                            name: "Highest Education Levels",
                        },
                        {
                            subId: "managers",
                            subIcon: "fas fa-circle",
                            subLink: "/personal-modification-process-managers",
                            name: "Managers",
                        },
                        {
                            subId: "check-lists",
                            subIcon: "fas fa-circle",
                            subLink:
                                "/personal-modification-process-checklists",
                            name: "Checklists",
                        },
                        {
                            subId: "personal-request-approvers",
                            subIcon: "fas fa-circle",
                            subLink: "/personal-request-approvers",
                            name: "Personal Request Approvers",
                        },
                    ],
                },
                {
                    id: "products",
                    icon: "GlobalProductIcon",
                    name: "Products",
                    children: [
                        {
                            subId: "groups",
                            subIcon: "fas fa-circle",
                            subLink: "/product-groups",
                            name: "Groups",
                        },
                        {
                            subId: "version",
                            subIcon: "fas fa-circle",
                            subLink: "/versions",
                            name: "Versions",
                        },
                        {
                            subId: "categories",
                            subIcon: "fas fa-circle",
                            subLink: "/product-categories",
                            name: "Categories",
                        },
                        {
                            subId: "units",
                            subIcon: "fas fa-circle",
                            subLink: "/product-units",
                            name: "Units",
                        },
                        {
                            subId: "software",
                            subIcon: "fas fa-circle",
                            subLink: "/product-softwares",
                            name: "Software",
                        },
                        {
                            subId: "payment-period",
                            subIcon: "fas fa-circle",
                            subLink: "/product-periods",
                            name: "Payment Period",
                        },
                        {
                            subId: "price-list",
                            subIcon: "fas fa-circle",
                            subLink: "/product-prices",
                            name: "Price List",
                        },
                    ],
                },
                {
                    id: "global-settings",
                    icon: "GlobalSettingIcon",
                    name: "Global Settings",
                    children: [
                        {
                            subId: "document-assignment",
                            subIcon: "fas fa-circle",
                            subLink: "/document-assignment",
                            name: "Document Assignment",
                        },
                        {
                            subId: "mail-template-assignment",
                            subIcon: "fas fa-circle",
                            subLink: "/mail-template-assignment",
                            name: "Mail Template Assignment",
                        },
                        {
                            subId: "elo-configuration",
                            subIcon: "fas fa-circle",
                            subLink: "/elo-configuration",
                            name: "Elo Configuration",
                        },
                        {
                            subId: "terms-of-payment",
                            subIcon: "fas fa-circle",
                            subLink: "/terms-of-payment",
                            name: "Terms of Payment",
                        },
                        {
                            subId: "countries",
                            subIcon: "fas fa-circle",
                            subLink: "/countries",
                            name: "Countries",
                        },
                        {
                            subId: "affected-groups",
                            subIcon: "fas fa-circle",
                            subLink: "/affected-groups",
                            name: "Affected Groups",
                        },
                        {
                            subId: "cip-configuration",
                            subIcon: "fas fa-circle",
                            subLink: "/cip-configuration",
                            name: "CIP Configuration",
                        },
                    ],
                },
                {
                    id: "sales",
                    icon: "GlobalSaleIcon",
                    name: "Sales",
                    children: [
                        {
                            subId: "report-categories",
                            subIcon: "fas fa-circle",
                            subLink: "/report-categories",
                            name: "Report Categories",
                        },
                        {
                            subId: "contact-report-source",
                            subIcon: "fas fa-circle",
                            subLink: "/contact-report-source",
                            name: "Contact Report Source",
                        },
                        {
                            subId: "lead-status",
                            subIcon: "fas fa-circle",
                            subLink: "/lead-status",
                            name: "Lead status",
                        },
                        {
                            subId: "default-cover-letter-text",
                            subIcon: "fas fa-circle",
                            subLink: "/default-cover-letter-text",
                            name: "Default Cover Letter Text",
                        },
                        {
                            subId: "default-order-confirmation-cover-letter-text",
                            subIcon: "fas fa-circle",
                            subLink:
                                "/default-offer-confirmation-cover-letter-text",
                            name: "Default Order Confirmation Cover Letter Text",
                        },
                    ],
                },
                {
                    id: "travel-expense",
                    icon: "GlobaltravelIcon",
                    name: "Travel Expense",
                    children: [
                        {
                            subId: "request-type",
                            subIcon: "fas fa-circle",
                            subLink: "/travel-expenses/request-types",
                            name: "Request Type",
                        },
                        {
                            subId: "invoice-type",
                            subIcon: "fas fa-circle",
                            subLink: "/travel-expenses/invoice-types",
                            name: "Invoice Type",
                        },
                    ],
                },
                {
                    id: "licensing",
                    icon: "GloballicensingIcon",
                    name: "Licensing",
                    children: [
                        {
                            subId: "event-name",
                            subIcon: "fas fa-circle",
                            subLink: "/event-name",
                            name: "Event Name",
                        },
                        {
                            subId: "rules",
                            subIcon: "fas fa-circle",
                            subLink: "/rules",
                            name: "Rules",
                        },
                    ],
                },
                {
                    id: "customer-portal",
                    icon: "GlobalcustomerPortalIcon",
                    name: "Customer Portal",
                    children: [
                        {
                            subId: "customer-portal-news",
                            subIcon: "fas fa-circle",
                            subLink: "/customer-portal-news",
                            name: "News",
                        },
                        {
                            subId: "already-know",
                            subIcon: "fas fa-circle",
                            subLink: "/",
                            name: "Already know",
                        },
                    ],
                },
                {
                    id: "document-service",
                    icon: "GlobaldocumentserviceIcon",
                    name: "Document Service",
                    children: [
                        {
                            subId: "template-list",
                            subIcon: "fas fa-circle",
                            subLink: "/document-service",
                            name: "Template List",
                        },
                        {
                            subId: "data-source",
                            subIcon: "fas fa-circle",
                            subLink: "/document-service",
                            name: "Data Sources",
                        },
                        {
                            subId: "storage-method",
                            subIcon: "fas fa-circle",
                            subLink: "/document-service",
                            name: "Storage Method",
                        },
                    ],
                },
                {
                    id: "project-management",
                    icon: "GlobalserviceIcon",
                    name: "Project Management",
                    children: [
                        {
                            subId: "project-status",
                            subIcon: "fas fa-circle",
                            subLink: "/project-statuses",
                            name: "Project Statuses",
                        },
                        {
                            subId: "protocol-type",
                            subIcon: "fas fa-circle",
                            subLink: "/protocol-types",
                            name: "Protocol Types",
                        },
                    ],
                },
                {
                    id: "contract-management",
                    icon: "GlobalserviceIcon",
                    name: "Contract Management",
                    children: [
                        {
                            subId: "contract-types",
                            subIcon: "fas fa-circle",
                            subLink: "/contract-types",
                            name: "Contract Types",
                        },
                        {
                            subId: "attachments",
                            subIcon: "fas fa-circle",
                            subLink: "/attachments",
                            name: "Attachments",
                        },
                    ],
                },
                {
                    id: "finance",
                    icon: "financeIcon",
                    name: "Finance",
                    children: [
                        {
                            subId: "invoice-reminders",
                            subIcon: "fas fa-circle",
                            subLink: "/invoice-reminders",
                            name: "Reminder Levels",
                        },
                    ],
                },
                {
                    id: "TicketSystemSettings",
                    icon: "financeIcon",
                    name: "Ticket System Settings",
                    children: [
                        {
                            subId: "ticket-system-settings",
                            subIcon: "fas fa-circle",
                            subLink: "/ticket-system-settings/create",
                            name: "Global Notification List",
                        },
                    ],
                },
                // {
                //     id: 'service',
                //     icon: 'GlobalserviceIcon',
                //     name: "Service",
                //     children: [
                //         {
                //             subId: 'service-level-agreements',
                //             subIcon: 'fas fa-circle',
                //             subLink: "/service-level-agreements",
                //             name: "Service Level Agreements"
                //         }
                //     ],
                // },
                {
                    id: "fleet-management",
                    icon: "GlobalfleetManagemntIcon",
                    name: "Fleet Management",
                    children: [
                        {
                            subId: "interval-setting",
                            subIcon: "fas fa-circle",
                            subLink: "/interval-settings",
                            name: "Contract Types",
                        },
                    ],
                },
                {
                    id: "service",
                    icon: "GlobalserviceIcon",
                    name: "Service",
                    children: [
                        {
                            subId: "service-level-agreements",
                            subIcon: "fas fa-circle",
                            subLink: "/service-level-agreements",
                            name: "Service Level Agreements",
                        },
                    ],
                },
                {
                    id: "dashboard",
                    icon: "dashboardIcon",
                    name: "Dashboard",
                    children: [
                        {
                            subId: "consulting-teams",
                            subIcon: "fas fa-circle",
                            subLink: "/consulting-teams",
                            name: "Consulting Teams",
                        },
                        {
                            subId: "pm-dashboard-teams",
                            subIcon: "fas fa-circle",
                            subLink: "/pm-dashboard-teams",
                            name: "PM Dashbaord Teams",
                        },
                    ],
                },
                {
                    id: "asset-management",
                    icon: "GlobaltravelIcon",
                    name: "Asset Management",
                    children: [
                        {
                            subId: "storage-locations",
                            subIcon: "fas fa-circle",
                            subLink: "/storage-locations",
                            name: "Storage Locations",
                        },
                        {
                            subId: "asset-employee-list-text",
                            subIcon: "fas fa-circle",
                            subLink: "/asset-employee-list-text",
                            name: "Asset Employee List Text",
                        },
                    ],
                },
                {
                    id: "event-pipeline",
                    icon: "GlobaltravelIcon",
                    name: "Event Pipeline",
                    children: [
                        {
                            subId: "pipelines",
                            subIcon: "fas fa-circle",
                            subLink: "/pipelines",
                            name: "Pipeline List",
                        },
                    ],
                },
                {
                    id: "partner-management",
                    icon: "financeIcon",
                    name: "Partner Management",
                    children: [
                        {
                            subId: "partner-discount",
                            subIcon: "fas fa-circle",
                            subLink: "/partner-management/discount",
                            name: "Partner Discount",
                        },
                    ],
                },
                {
                    id: "skill-management",
                    icon: "GlobaltravelIcon",
                    name: "Skill Management",
                    children: [
                        {
                            subId: "skill-levels",
                            subIcon: "fas fa-circle",
                            subLink: "/skill-level",
                            name: "Skill Levels",
                        },
                    ],
                },
                {
                    id: "elo-business-solutions",
                    icon: "GlobaltravelIcon",
                    name: "Infrastructure Settings",
                    children: [
                        {
                            subId: "elo-business-solution",
                            subIcon: "fas fa-circle",
                            subLink: "/infrastructure-settings",
                            name: "Infrastructure Settings",
                        },
                    ],
                },
                {
                    id: "mail-depot",
                    icon: "GlobaltravelIcon",
                    name: "Mail Depot",
                    children: [
                        {
                            subId: "mail-depot",
                            subIcon: "fas fa-circle",
                            subLink: "/mail-depot",
                            name: "Mail Depot",
                        }
                    ],
                },
            ],
            openAccordions: [],
            searchTerm: "",
            expandAll: false,
            colClass: "col-md-4",
        };
    },
    async mounted() {
        await this.$nextTick(() => {});
        this.split = Split([this.$refs.pane1, this.$refs.pane2], {
            sizes: [50, 50],
            minSize: [300, 300], // Setting a minimum size in pixels
            gutterSize: 10,
            direction: "horizontal",
            elementStyle: function (dimension, size, gutterSize) {
                let finalSize = size;

                // Check if size is negative or greater than or equal to 100
                if (size < 0) {
                    finalSize = Math.abs(size);
                } else if (size >= 100) {
                    finalSize = 50; // Set a specific size when size is >= 100
                }

                return {
                    width: "calc(" + finalSize + "% - " + gutterSize + "px)",
                };
            },

            gutterStyle: function (dimension, gutterSize) {
                return {
                    width: "10px",
                    position: "fixed",
                    left: 50 + "%",
                    height: "100vh",
                    "z-index": "99999",
                    cursor: "col-resize",
                };
            }.bind(this),
            onDrag: function (event) {
                const gutterElement = document.querySelector(".gutter");
                const settings = document.querySelector("#setting-accordian");
                gutterElement.style.left = Math.round(event[0]) + "%";
                if (Math.round(event[0]) <= 30) {
                    this.colClass = "col-md-3";
                }
                if (Math.round(event[0]) > 30 && Math.round(event[0]) < 50) {
                    this.colClass = "col-md-4";
                }
                if (Math.round(event[0]) > 50 && Math.round(event[0]) < 70) {
                    this.colClass = "col-md-6";
                }
                if (Math.round(event[0]) > 70) {
                    this.colClass = "col-md-12";
                }
            }.bind(this),
            onDragEnd: function (event) {
                const gutterElement = document.querySelector(".gutter");
                gutterElement.style.left = Math.round(event[0]) + "%";
                console.log("Math.round(event[1])", Math.round(event[1]));
                if (Math.round(event[1]) < 25) {
                    this.$refs.pane2.style.width =
                        "calc(" + "25" + "% - " + "5" + "px)";
                    this.$refs.pane1.style.width =
                        "calc(" + "75" + "% - " + "5" + "px)";
                    gutterElement.style.left = "75%";
                }

                setTimeout(function () {
                    // Reapply cursor style after a short delay
                    const splitGutter = document.querySelector(".gutter");
                    if (splitGutter) {
                        splitGutter.style.cursor = "col-resize";
                    }
                }, 100);
            }.bind(this),
        });
    },
    beforeDestroy() {
        // Destroy Split.js instance to prevent memory leaks
        if (this.split) {
            this.split.destroy();
            this.split = null;
        }
    },
    created() {
        // By default, open all accordions by adding all menu.id values to openAccordions
        this.openAccordions = this.filteredMenus.map((menu) => menu.id);
    },
    methods: {
        closeGlobalSetting() {
            this.$emit("close-setting");
        },
        toggleAccordion(id) {
            if (this.openAccordions.includes(id)) {
                this.openAccordions = this.openAccordions.filter(
                    (acc) => acc !== id
                );
            } else {
                this.openAccordions.push(id);
            }
        },
        isOpen(id) {
            return this.openAccordions.includes(id);
        },
        toggleExpandAll() {
            if (this.expandAll) {
                this.openAccordions = this.filteredMenus.map((menu) => menu.id);
            } else {
                this.openAccordions = [];
            }
        },
    },
    computed: {
        filteredMenus() {
            const search = this.searchTerm.toLowerCase();

            return this.menus.filter((menu) => {
                if (menu.name.toLowerCase().includes(search)) {
                    this.expandAll = true;

                    return true;
                }

                const matchingChildren = menu.children.filter((child) =>
                    child.name.toLowerCase().includes(search)
                );
                return matchingChildren.length > 0;
            });
        },
    },
};
</script>

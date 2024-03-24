export const Menu = [
    {
        id: 1,
        label: "Dashboard",
        icon: "dashboardIcon",
        link: "/dashboard",
    },
    {
        id: 2,
        label: "Master Data",
        icon: "dataIcon",
        isMenuCollapsed: false,
        subItems: [
            {
                id: 3,
                icon: "productIcon",
                label: "Products",
                link: "/products",
            },
            {
                id: 4,
                icon: "customerIcon",
                label: "Customers",
                link: "/companies",
            },

            {
                id: 5,
                icon: "supplierIcon",
                label: "Suppliers",
                link: "/suppliers",
            },
            {
                id: 6,
                icon: "systemIcon",
                label: "Systems",
                isMenuCollapsed: false,
                subItems: [
                    {
                        id: 7,
                        icon: "circleWhiteIcon",
                        label: "On Premise",
                        link: "/systems/on-premise",
                    },
                    {
                        id: 8,
                        icon: "circleWhiteIcon",
                        label: "Cloud",
                        link: "/systems/cloud",
                    },
                    {
                        id: 9,
                        icon: "circleWhiteIcon",
                        label: "Hosting",
                        link: "/systems/hosting",
                    },
                ],
            },
        ],
    },

    {
        id: 10,
        label: "Sales",
        icon: "salesIcon",
        isMenuCollapsed: false,
        subItems: [
            {
                id: 11,
                icon: "dashboardIcon",
                label: "Dashboard",
                link: "/sales-dashboard",
            },
            {
                id: 11,
                icon: "leadsIcon",
                label: "Leads",
                link: "/leads",
            },
            {
                id: 12,
                icon: "contactReportsIcon",
                label: "Contact Reports",
                link: "/contact-reports",
            },
            {
                id: 13,
                icon: "surveyIcon",
                label: "Surveys",
                link: "/surveys",
            },
            {
                id: 14,
                icon: "offersIcon",
                label: "Offers",
                link: "/offers",
            },
            {
                id: 15,
                icon: "offersConfirmationIcon",
                label: "Order Confirmation",
                link: "/order-confirmation",
            },
            {
                id: 15,
                icon: "offerRequestIcon",
                label: "Offer Requests",
                link: "/offer-requests",
            },
        ],
    },

    {
        id: 16,
        label: "Finance",
        icon: "financeIcon",
        isMenuCollapsed: false,
        subItems: [
            {
                id: 17,
                label: "Dashboard",
                icon: "dashboardIcon",
                link: "/finance-dashboard",
            },
            {
                id: 18,
                label: "Invoices",
                icon: "invoiceIcon",
                link: "/invoices",
            },
            {
                id: 19,
                label: "Invoice Templates",
                icon: "invoiceTemplateIcon",
                link: "/invoices-templates",
            },
            {
                id: 19,
                label: "Creditor Invoices",
                icon: "CreditnvoiceIcon",
                link: "/creditor-invoices",
            },
            {
                id: 20,
                label: "Open Posts",
                icon: "OpenPostIcon",
                link: "/open-posts",
            },
        ],
    },
    {
        id: 21,
        label: "Consulting",
        icon: "consultingIcon",
        isMenuCollapsed: false,
        subItems: [
            {
                id: 22,
                label: "Dashboard",
                icon: "dashboardIcon",
                link: "/consulting-dashboard",
            },
            {
                id: 212,
                icon: "PlanningBoardIcon",
                label: "Planning Board",
                link: "/planning-dashboard",
            },
            {
                id: 23,
                label: "Workshop Templates",
                icon: "workShopIcon",
                link: "/workshop-templates",
            },
            {
                id: 24,
                label: "Handouts",
                icon: "handoutsIcon",
                link: "/handouts",
            },
        ],
    },
    {
        id: 25,
        label: "Project Management",
        icon: "projectManIcon",
        isMenuCollapsed: false,
        subItems: [
            {
                id: 21,
                icon: "dashboardIcon",
                label: "Dashboard",
                link: "/project-management-dashboard",
            },
            {
                id: 221,
                icon: "circleWhiteIcon",
                label: "Planning Board",
                link: "/planning-dashboard",
            },
            {
                id: 21,
                icon: "projectsIcon",
                label: "Projects",
                link: "/project-management/projects",
            },
            {
                id: 21,
                icon: "projectsIcon",
                label: "PM 2",
                link: "/project-management2/project",
            },
            {
                id: 22,
                icon: "projectsProtocolIcon",
                label: "Project Protocols",
                link: "/project-protocols",
            },
            {
                id: 22,
                icon: "TimeCheckingIcon",
                label: "Time Checking",
                link: "/time-checking",
            },
            {
                id: 22,
                icon: "performanceRecordIcon",
                label: "Performance Records",
                link: "/performance-records",
            },
        ],
    },
    {
        id: 25,
        label: "Partner Management",
        icon: "projectManIcon",
        isMenuCollapsed: false,
        subItems: [
            {
                id: 4,
                icon: "customerIcon",
                label: "Partners",
                link: "/partners",
            },
            {
                id: 21,
                icon: "offersConfirmationIcon",
                label: "Orders",
                link: "/partner-management/orders",
            },
            {
                id: 21,
                icon: "TicketsIcon",
                label: "Ticket System",
                link: "/partner-management/tickets",
            },
            {
                id: 22,
                icon: "customerIcon",
                label: "Customers",
                link: "/partner-management/companies",
            },


        ],
    },
    {
        id: 23,
        label: "Support",
        icon: "supportIcon",
        isMenuCollapsed: false,
        subItems: [
            {
                id: 24,
                icon: "dashboardIcon",
                label: "Dashboard",
                link: "/support-dashboard",
            },
            {
                id: 24,
                icon: "TicketsIcon",
                label: "Tickets",
                link: "/tickets",
            },
            {
                id: 25,
                icon: "AMSIcon",
                label: "AMS",
                link: "/ams",
            },
        ],
    },
    {
        id: 23,
        label: "Infrastructure",
        icon: "supportIcon",
        isMenuCollapsed: false,
        subItems: [
            {
                id: 24,
                icon: "CloudInfraIcon",
                label: "Cloud Infrastructure",
                link: "/infrastructures/cloud-infrastructures",
            },
            {
                id: 25,
                icon: "ServerPoolIcon",
                label: "Server Pool",
                link: "/infrastructures/server-pools",
            },
        ],
    },

    {
        id: 23,
        label: "Asset Management",
        icon: "supportIcon",
        isMenuCollapsed: false,
        subItems: [
            {
                id: 24,
                icon: "AssetIcon",
                label: "Assets",
                link: "/assets",
            },
            {
                id: 25,
                icon: "EmployeeListIcon",
                label: "Assets Employee List",
                link: "/asset-lists",
            },
            {
                id: 26,
                icon: "DeliveryIcon",
                label: "Assets Delivery",
                link: "/asset-delivery",
            },
        ],
    },

    {
        id: 26,
        label: "Personal Management",
        icon: "personalManIcon",
        isMenuCollapsed: false,
        subItems: [
            {
                id: 27,
                icon: "dashboardIcon",
                label: "Dashboard",
                link: "/dashboard",
            },
            {
                id: 27,
                icon: "EmployeeIcon",
                label: "Employees",
                link: "/user-profiles",
            },
            {
                id: 27,
                icon: "JOBIcon",
                label: "Job",
                link: "/job",
            },
            {
                id: 28,
                icon: "DepartmentIcon",
                label: "Departments",
                link: "/user/departments",
            },
            {
                id: 29,
                icon: "LocationIcon",
                label: "Locations",
                link: "/user/locations",
            },
            {
                id: 30,
                icon: "TeamsIcon",
                label: "Teams",
                link: "/user/teams",
            },
            {
                id: 30,
                icon: "ChangeProcessIcon",
                label: "Change Processes",
                link: "/change-processes",
            },
            {
                id: 30,
                icon: "PersonalReqIcon",
                label: "Personal Requirements",
                link: "/personal-requirements",
            },
            {
                id: 30,
                icon: "BonusIcon",
                label: "Bonus Report",
                link: "/bonus-report",
            },
            {
                id: 30,
                icon: "EmployeeInterviewIcon",
                label: "Employee Interview",
                link: "/employee-interview",
            },
        ],
    },
    {
        id: 31,
        label: "Contract Management",
        icon: "fleetManIcon",
        isMenuCollapsed: false,
        subItems: [
            {
                id: 32,
                icon: "inboundIcon",
                label: "Inbounded Contracts",
                link: "/inbounded-contracts",
            },
            {
                id: 32,
                icon: "outboundIcon",
                label: "Outbounded Contracts",
                link: "/outbounded-contracts",
            },
        ],
    },
    ////////////////////
    {
        id: 31,
        label: "Licenses",
        icon: "fleetManIcon",
        isMenuCollapsed: false,
        subItems: [
            {
                id: 32,
                icon: "LisenceIcon",
                label: "License Generator",
                link: "/licenses",
            },
        ],
    },
    {
        id: 31,
        label: "Product Store",
        icon: "productIcon",
        isMenuCollapsed: false,
        subItems: [
            {
                id: 32,
                icon: "StoreEntriesIcon",
                label: "Store Entries",
                link: "/store-entries",
            },
            {
                id: 32,
                icon: "ProductStoreIcon",
                label: "Product Store",
                link: "/product-store",
            },
            {
                id: 32,
                icon: "RequestsIcon",
                label: "Requests",
                link: "/requests",
            },
            {
                id: 33,
                icon: "RequestsIcon",
                label: "Cancelation Requests",
                link: "/cancel-requests",
            },
        ],
    },
    {
        id: 33,
        label: "Fleet Management",
        icon: "fleetManIcon",
        isMenuCollapsed: false,
        subItems: [
            {
                id: 34,
                label: "Fleet Cars",
                icon: "FleetListIcon",
                link: "/fleet-cars",
            },
            {
                id: 35,
                label: "Mileage Monitoring",
                icon: "MilageIcon",
                link: "/mileage-monitoring",
            },
            {
                id: 36,
                label: "Cost Monitoring",
                icon: "CostMoniteringIcon",
                link: "/cost-monitoring",
            },
            {
                id: 36,
                label: "Fuel Monitoring",
                icon: "FuelMonIcon",
                link: "/fuel-monitoring",
            },
            {
                id: 37,
                label: "Driver Licence Check",
                icon: "DriverCheckIcon",
                link: "/fleet-drivers",
            },
        ],
    },
    {
        id: 26,
        label: "Skill Management",
        icon: "fleetManIcon",
        isMenuCollapsed: false,
        subItems: [
            {
                id: 34,
                icon: "SkillsIcon",
                label: "Skills",
                link: "/skill",
            },
            {
                id: 35,
                icon: "SkillGroupIcon",
                label: "Skill Group",
                link: "/skill-group",
            },
            {
                id: 35,
                icon: "SkillMatrixIcon",
                label: "Skill Matrix",
                link: "/skill-matrix",
            },
        ],
    },
];

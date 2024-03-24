// NOTE: Don't change name of route, we are getting History in Edit screen, On Basic of Name, because name
//  is same as Modules in backend

import { createRouter, createWebHistory } from "vue-router";
import store from "../store";
const Main = () => import("../Layouts/Main.vue");
const Dashboard = () => import("../Pages/Dashboard/Index.vue");
const Login = () => import("../Pages/Auth/Login.vue");
const Profile = () => import("../Pages/Auth/Profile.vue");

/** Document Service */
const DocumentService = () => import("../Pages/DocumentService/Index.vue");
const DocumentServiceCreate = () =>
    import("../Pages/DocumentService/Create.vue");
const DocumentServiceEdit = () => import("../Pages/DocumentService/Edit.vue");
const DocumentServiceShow = () => import("../Pages/DocumentService/Show.vue");

/** Offers */
const Offers = () => import("../Pages/Offers/Index.vue");
const OffersCreate = () => import("../Pages/Offers/Create.vue");
const OffersEdit = () => import("../Pages/Offers/Edit.vue");
const OffersShow = () => import("../Pages/Offers/Show.vue");

/** Orders */
const Orders = () => import("../Pages/Orders/Index.vue");
const OrdersCreate = () => import("../Pages/Orders/Create.vue");
const OrdersEdit = () => import("../Pages/Orders/Edit.vue");
const OrdersShow = () => import("../Pages/Orders/Show.vue");

/** Offer Requests */
const OfferRequests = () => import("../Pages/OfferRequests/Index.vue");
const OfferRequestsCreate = () => import("../Pages/OfferRequests/Create.vue");
const OfferRequestsEdit = () => import("../Pages/OfferRequests/Edit.vue");
const OfferRequestsShow = () => import("../Pages/OfferRequests/Show.vue");

/** Mail Depot */
const MailDepot = () => import("../Pages/MailDepot/Index.vue");

/** Order Confirmation */
const OrderConfirmation = () => import("../Pages/OrderConfirmation/Index.vue");
const OrderConfirmationCreate = () =>
    import("../Pages/OrderConfirmation/Create.vue");
const OrderConfirmationEdit = () =>
    import("../Pages/OrderConfirmation/Edit.vue");
const OrderConfirmationShow = () =>
    import("../Pages/OrderConfirmation/Show.vue");

/** Self Service */
const SelfService = () => import("../Pages/SelfService/Index.vue");

/** Products */
const Products = () => import("../Pages/Products/Index.vue");
const ProductsCreate = () => import("../Pages/Products/Create.vue");
const ProductsEdit = () => import("../Pages/Products/Edit.vue");
const ProductImports = () => import("../Pages/Products/ProductImports.vue");

/** Companies */
const Companies = () => import("../Pages/Companies/Index.vue");
const CompaniesCreate = () => import("../Pages/Companies/Create.vue");
const CompaniesEdit = () => import("../Pages/Companies/Edit.vue");
const CompaniesShow = () => import("../Pages/Companies/Show.vue");

/** Partner Companies */
const PartnerCompanies = () => import("../Pages/PartnerCompanies/Index.vue");
const PartnerCompaniesEdit = () => import("../Pages/PartnerCompanies/Edit.vue");
const PartnerCompaniesShow = () => import("../Pages/PartnerCompanies/Show.vue");

/** Leads */
const Leads = () => import("../Pages/Leads/Index.vue");
const LeadsCreate = () => import("../Pages/Leads/Create.vue");
const LeadsEdit = () => import("../Pages/Leads/Edit.vue");
const LeadsShow = () => import("../Pages/Leads/Show.vue");

/** Partners */
const Partners = () => import("../Pages/Partners/Index.vue");
const PartnersCreate = () => import("../Pages/Partners/Create.vue");
const PartnersEdit = () => import("../Pages/Partners/Edit.vue");
const PartnersShow = () => import("../Pages/Partners/Show.vue");

/** Users */
const Users = () => import("../Pages/Users/index.vue");
const UsersCreate = () => import("../Pages/Users/Create.vue");
const UsersEdit = () => import("../Pages/Users/Edit.vue");

/** Roles */
const Roles = () => import("../Pages/Roles/Index.vue");
const RolesCreate = () => import("../Pages/Roles/Create.vue");
const RolesEdit = () => import("../Pages/Roles/Edit.vue");

/** Planning Dashboard */
const PlanningDashboard = () => import("../Pages/PlanningDashboard/Index.vue");

/** Permissions */
const Permissions = () => import("../Pages/Permissions/Index.vue");
const PermissionsCreate = () => import("../Pages/Permissions/Create.vue");
const PermissionsEdit = () => import("../Pages/Permissions/Edit.vue");

/** Invoices */
const Invoices = () => import("../Pages/Invoices/Index.vue");
const InvoicesCreate = () => import("../Pages/Invoices/Create.vue");
const InvoicesEdit = () => import("../Pages/Invoices/EditInvoice.vue");
const InvoicesShow = () => import("../Pages/Invoices/Show.vue");

/** Invoices Templates */
const InvoicesTemplates = () => import("../Pages/InvoicesTemplates/Index.vue");
const InvoicesTemplatesCreate = () =>
    import("../Pages/InvoicesTemplates/Create.vue");
const InvoicesTemplatesEdit = () =>
    import("../Pages/InvoicesTemplates/EditInvoice.vue");
const InvoicesTemplatesShow = () =>
    import("../Pages/InvoicesTemplates/Show.vue");

/** Creditor Invoices */
const CreditorInvoices = () => import("../Pages/CreditorInvoices/Index.vue");
const CreditorInvoicesCreate = () =>
    import("../Pages/CreditorInvoices/Create.vue");
const CreditorInvoicesEdit = () => import("../Pages/CreditorInvoices/Edit.vue");

/** ProductGroups */
const ProductGroups = () => import("../Pages/ProductGroups/Index.vue");
const ProductGroupsCreate = () => import("../Pages/ProductGroups/Create.vue");
const ProductGroupsEdit = () => import("../Pages/ProductGroups/Edit.vue");

/** Versions */
const Versions = () => import("../Pages/EloVersions/Index.vue");
const VersionsCreate = () => import("../Pages/EloVersions/Create.vue");
const VersionsEdit = () => import("../Pages/EloVersions/Edit.vue");

/** Systems */
const SystemsOnPremise = () => import("../Pages/Systems/OnPremise/Index.vue");
const SystemsOnPremiseCreate = () =>
    import("../Pages/Systems/OnPremise/Create.vue");
const SystemsOnPremiseEdit = () =>
    import("../Pages/Systems/OnPremise/Edit.vue");

const SystemsCloud = () => import("../Pages/Systems/Cloud/Index.vue");
const SystemsCloudCreate = () => import("../Pages/Systems/Cloud/Create.vue");
const SystemsCloudEdit = () => import("../Pages/Systems/Cloud/Edit.vue");

const SystemsHosting = () => import("../Pages/Systems/Hosting/Index.vue");
const SystemsHostingCreate = () =>
    import("../Pages/Systems/Hosting/Create.vue");
const SystemsHostingEdit = () => import("../Pages/Systems/Hosting/Edit.vue");

const OperatingSystem = () =>
    import("../Pages/Systems/OperatingSystem/Index.vue");
const OperatingSystemCreate = () =>
    import("../Pages/Systems/OperatingSystem/Create.vue");
const OperatingSystemEdit = () =>
    import("../Pages/Systems/OperatingSystem/Edit.vue");

const LeadStatus = () => import("../Pages/LeadStatus/Index.vue");
const LeadStatusCreate = () => import("../Pages/LeadStatus/Create.vue");
const LeadStatusEdit = () => import("../Pages/LeadStatus/Edit.vue");

const JobLevel = () => import("../Pages/JobLevel/Index.vue");
const JobLevelCreate = () => import("../Pages/JobLevel/Create.vue");
const JobLevelEdit = () => import("../Pages/JobLevel/Edit.vue");

/** SkillManagement */
const Skills = () => import("../Pages/SkillManagement/Skills/Index.vue");
const SkillCreate = () => import("../Pages/SkillManagement/Skills/Create.vue");
const SkillEdit = () => import("../Pages/SkillManagement/Skills/Edit.vue");

const SkillGroup = () =>
    import("../Pages/SkillManagement/Skill Group/Index.vue");
const SkillGroupCreate = () =>
    import("../Pages/SkillManagement/Skill Group/Create.vue");
const SkillGroupEdit = () =>
    import("../Pages/SkillManagement/Skill Group/Edit.vue");

const SkillMatrix = () =>
    import("../Pages/SkillManagement/SkillMatrix/Index.vue");
const SkillMatrixCreate = () =>
    import("../Pages/SkillManagement/SkillMatrix/Create.vue");
const SkillMatrixEdit = () =>
    import("../Pages/SkillManagement/SkillMatrix/Edit.vue");
const SkillMatrixShow = () =>
    import("../Pages/SkillManagement/SkillMatrix/Show.vue");

const SkillLevel = () => import("../Pages/SkillLevel/Index.vue");
const SkillLevelCreate = () => import("../Pages/SkillLevel/Create.vue");
const SkillLevelEdit = () => import("../Pages/SkillLevel/Edit.vue");

/** ReportCategory */
const ReportCategory = () => import("../Pages/ReportCategory/Index.vue");
const ReportCategoryCreate = () => import("../Pages/ReportCategory/Create.vue");
const ReportCategoryEdit = () => import("../Pages/ReportCategory/Edit.vue");

/** Product Category */
const ProductCategory = () => import("../Pages/ProductCategory/Index.vue");
const ProductCategoryCreate = () =>
    import("../Pages/ProductCategory/Create.vue");
const ProductCategoryEdit = () => import("../Pages/ProductCategory/Edit.vue");

/** ContactReports */
const ContactReports = () => import("../Pages/ContactReports/Index.vue");
const ContactReportsCreate = () => import("../Pages/ContactReports/Create.vue");
const ContactReportsEdit = () => import("../Pages/ContactReports/Edit.vue");
const ContactReportsShow = () => import("../Pages/ContactReports/Show.vue");

/** Project Management */
const ProjectManagementDashboard = () =>
    import("../Pages/ProjectManagement/Dashbaord/Index.vue");
const PMDashboardTeams = () => import("../Pages/PMDashboardTeams/Index.vue");
const ProjectManagement = () => import("../Pages/ProjectManagement/Index.vue");

// Projects
const Projects = () => import("../Pages/ProjectManagement/Projects/Index.vue");
const ProjectsCreate = () =>
    import("../Pages/ProjectManagement/Projects/Create.vue");
const ProjectsEdit = () =>
    import("../Pages/ProjectManagement/Projects/Edit.vue");

//Tasks
const MyTasks = () => import("../Pages/ProjectManagement/Tasks/Boards.vue");

/** Tickets */
const Tickets = () => import("../Pages/Tickets/Index.vue");
const TicketsCreate = () => import("../Pages/Tickets/Create.vue");
const TicketsView = () => import("../Pages/Tickets/View.vue");
const TicketsEdit = () => import("../Pages/Tickets/Edit.vue");

/**Partner Tickets */
const PartnerTickets = () => import("../Pages/PartnerTickets/Index.vue");
const PartnerTicketsCreate = () => import("../Pages/PartnerTickets/Create.vue");
const PartnerTicketsView = () => import("../Pages/PartnerTickets/View.vue");
const PartnerTicketsEdit = () => import("../Pages/PartnerTickets/Edit.vue");

/** Surveys */
const Surveys = () => import("../Pages/Surveys/Index.vue");
const SurveysCreate = () => import("../Pages/Surveys/Create.vue");
const SurveysEdit = () => import("../Pages/Surveys/Edit.vue");
const SurveysShow = () => import("../Pages/Surveys/Show.vue");

/** Time Trackers */
const TimeTrackers = () => import("../Pages/TimeTrackers/Index.vue");

/** User Profiles */
const UserProfile = () => import("../Pages/UserProfile/Index.vue");
const UserProfileCreate = () => import("../Pages/UserProfile/Create.vue");
const UserProfileUpdate = () => import("../Pages/UserProfile/Update.vue");

/** User Jobs */
const UserJob = () => import("../Pages/UserJobs/Index.vue");
const UserJobCreate = () => import("../Pages/UserJobs/Create.vue");
const UserJobEdit = () => import("../Pages/UserJobs/Edit.vue");

/** User Profiles */
const TermsOfPayment = () => import("../Pages/TermsOfPayment/Index.vue");
const TermsOfPaymentCreate = () => import("../Pages/TermsOfPayment/Create.vue");
const TermsOfPaymentUpdate = () => import("../Pages/TermsOfPayment/Edit.vue");

const Countries = () => import("../Pages/Countries/Index.vue");
const CountriesCreate = () => import("../Pages/Countries/Create.vue");
const CountriesEdit = () => import("../Pages/Countries/Edit.vue");

/** Vacation Request */
const VacationRequest = () => import("../Pages/VacationRequest/Index.vue");

/** Suppliers */
const Suppliers = () => import("../Pages/Supplier/Index.vue");
const SuppliersCreate = () => import("../Pages/Supplier/Create.vue");
const SuppliersEdit = () => import("../Pages/Supplier/Edit.vue");
const SuppliersShow = () => import("../Pages/Supplier/Show.vue");

/** Department */
const UserDepartment = () =>
    import("../Pages/UserProfile/Department/Index.vue");
const UserDepartmentCreate = () =>
    import("../Pages/UserProfile/Department/Create.vue");
const UserDepartmentEdit = () =>
    import("../Pages/UserProfile/Department/Edit.vue");

/** Location */
const UserLocation = () => import("../Pages/UserProfile/Location/Index.vue");
const UserLocationCreate = () =>
    import("../Pages/UserProfile/Location/Create.vue");
const UserLocationEdit = () => import("../Pages/UserProfile/Location/Edit.vue");

/** Team */
const UserTeam = () => import("../Pages/UserProfile/Team/Index.vue");
const UserTeamCreate = () => import("../Pages/UserProfile/Team/Create.vue");
const UserTeamEdit = () => import("../Pages/UserProfile/Team/Edit.vue");

/** Product Unit Edit  */
const ProductUnitCreate = () => import("../Pages/ProductUnits/Create.vue");
const ProductUnit = () => import("../Pages/ProductUnits/Index.vue");
const ProductUnitEdit = () => import("../Pages/ProductUnits/Edit.vue");

/** Product Software Edit */
const ProductSoftwareCreate = () =>
    import("../Pages/ProductSoftware/Create.vue");
const ProductSoftware = () => import("../Pages/ProductSoftware/Index.vue");
const ProductSoftwareEdit = () => import("../Pages/ProductSoftware/Edit.vue");

/** Product Software Edit */
const ServiceLevelAgreementCreate = () =>
    import("../Pages/ServiceLevelAgreement/Create.vue");
const ServiceLevelAgreement = () =>
    import("../Pages/ServiceLevelAgreement/Index.vue");
const ServiceLevelAgreementEdit = () =>
    import("../Pages/ServiceLevelAgreement/Edit.vue");

/** Product Period */
const ProductPeriodCreate = () => import("../Pages/ProductPeriods/Create.vue");
const ProductPeriod = () => import("../Pages/ProductPeriods/Index.vue");
const ProductPeriodEdit = () => import("../Pages/ProductPeriods/Edit.vue");
/** Global Settings */
const ExpiryMonth = () => import("../Pages/GlobalSettings/ExpiryMonth.vue");
const DocumentAssignment = () =>
    import("../Pages/GlobalSettings/DocumentAssignment.vue");
const MailTemplateAssignment = () =>
    import("../Pages/GlobalSettings/MailTemplateAssignment.vue");
const EloConfiguration = () =>
    import("../Pages/GlobalSettings/EloConfiguration.vue");
const DefaultCoverLetterText = () =>
    import("../Pages/GlobalSettings/DefaultCoverLetterText.vue");
const DefaultOfferConfirmationCoverLetterText = () =>
    import(
        "../Pages/GlobalSettings/DefaultOfferConfirmationCoverLetterText.vue"
    );

/** System Host */
const SystemHost = () => import("../Pages/SystemHost/Index.vue");
const SystemHostCreate = () => import("../Pages/SystemHost/Create.vue");
const SystemHostEdit = () => import("../Pages/SystemHost/Edit.vue");

/** ELO Business Solutions module name change into Infrastructure Settings */
const InfrastructureSettings = () =>
    import("../Pages/EloBusinessSolution/Index.vue");
const InfrastructureSettingsCreate = () =>
    import("../Pages/EloBusinessSolution/Create.vue");
const InfrastructureSettingsEdit = () =>
    import("../Pages/EloBusinessSolution/Edit.vue");

/** Performance Record */
const PerformanceRecord = () => import("../Pages/PerformanceRecord/Index.vue");
const PerformanceRecordShow = () =>
    import("../Pages/PerformanceRecord/Show.vue");
const PerformanceRecordCreate = () =>
    import("../Pages/PerformanceRecord/Create.vue");

/** Workshop Templates */
const WorkshopTemplates = () => import("../Pages/WorkshopTemplates/Index.vue");
const WorkshopTemplatesCreate = () =>
    import("../Pages/WorkshopTemplates/Create.vue");
const WorkshopTemplatesEdit = () =>
    import("../Pages/WorkshopTemplates/Edit.vue");
const WorkshopTemplatesEditTemp = () =>
    import("../Pages/WorkshopTemplates/EditTemp.vue");
const WorkshopTemplatesShow = () =>
    import("../Pages/WorkshopTemplates/Show.vue");

/** Handouts */
const Handouts = () => import("../Pages/Handouts/Index.vue");
const HandoutsCreate = () => import("../Pages/Handouts/Create.vue");

/** Cloud System */
const CloudSystemCreate = () =>
    import("../Pages/Infrastructures/CloudInfrastructures/Create.vue");
const CloudSystem = () =>
    import("../Pages/Infrastructures/CloudInfrastructures/Index.vue");
const CloudSystemEdit = () =>
    import("../Pages/Infrastructures/CloudInfrastructures/Edit.vue");

const ChangeTanentConfig = () =>
    import(
        "../Pages/Infrastructures/CloudInfrastructures/ChangeTanentConfig.vue"
    );

/** Server Pools */
const ServerPools = () =>
    import("../Pages/Infrastructures/ServerPools/Index.vue");
const ServerPoolsEdit = () =>
    import("../Pages/Infrastructures/ServerPools/Edit.vue");
const ServerPoolsShow = () =>
    import("../Pages/Infrastructures/ServerPools/Show.vue");

/** Database Cloud */
const DatabaseCloud = () => import("../Pages/DatabaseCloud/Index.vue");
const DatabaseCloudCreate = () => import("../Pages/DatabaseCloud/Create.vue");
const DatabaseCloudEdit = () => import("../Pages/DatabaseCloud/Edit.vue");

/** Distributed Filesystem */
const DistributedFilesystem = () =>
    import("../Pages/DistributedFilesystem/Index.vue");
const DistributedFilesystemCreate = () =>
    import("../Pages/DistributedFilesystem/Create.vue");
const DistributedFilesystemEdit = () =>
    import("../Pages/DistributedFilesystem/Edit.vue");

/** Contact Report Sources */
const ContactReportSourceCreate = () =>
    import("../Pages/ContactReportSources/Create.vue");
const ContactReportSourceEdit = () =>
    import("../Pages/ContactReportSources/Edit.vue");
const ContactReportSource = () =>
    import("../Pages/ContactReportSources/Index.vue");

/** Mail Template */
const MailTemplate = () => import("../Pages/MailTemplate/Index.vue");
const MailTemplateCreate = () => import("../Pages/MailTemplate/Create.vue");
const MailTemplateEdit = () => import("../Pages/MailTemplate/Edit.vue");

/** Fleet Management */
const FleetCarsCreate = () =>
    import("../Pages/FleetManagement/FleetCars/Create.vue");
const FleetCars = () => import("../Pages/FleetManagement/FleetCars/Index.vue");
const FleetCarsEdit = () =>
    import("../Pages/FleetManagement/FleetCars/Edit.vue");

/** Mileage Monitoring */
const MileageMonitoring = () =>
    import("../Pages/FleetManagement/MileageMonitoring/Index.vue");

/** Fuel Receipts */
const FuelReceiptsCreate = () =>
    import("../Pages/FleetManagement/FuelReceipts/Create.vue");

/** Fuel Monitoring */
const FuelMonitoring = () =>
    import("../Pages/FleetManagement/FuelMonitoring/Index.vue");
const FuelMonitoringShow = () =>
    import("../Pages/FleetManagement/FuelMonitoring/Show.vue");

/** Fleet Drivers */
const FleetDrivers = () =>
    import("../Pages/FleetManagement/FleetDrivers/Index.vue");
const FleetDriversCreate = () =>
    import("../Pages/FleetManagement/FleetDrivers/Create.vue");
const FleetDriversEdit = () =>
    import("../Pages/FleetManagement/FleetDrivers/Edit.vue");
const DriverLicenceCheck = () =>
    import("../Pages/FleetManagement/FleetDrivers/CreateLicenceCheck.vue");
const FleetDriverShow = () =>
    import("../Pages/FleetManagement/FleetDrivers/Show.vue");

/** Interval Settings */
const IntervalSettingsCreate = () =>
    import("../Pages/IntervalSettings/Create.vue");
const IntervalSettingsEdit = () => import("../Pages/IntervalSettings/Edit.vue");
const IntervalSettings = () => import("../Pages/IntervalSettings/Index.vue");

/** Travel Expenses */
const TravelExpense = () => import("../Pages/TravelExpenses/Index.vue");
const TravelExpenseCreate = () => import("../Pages/TravelExpenses/Create.vue");
const TravelExpenseEdit = () => import("../Pages/TravelExpenses/Edit.vue");
const TravelExpenseApprover = () =>
    import("../Pages/TravelExpenses/Approver.vue");

const TravelExpenseRequestType = () =>
    import("../Pages/TravelExpenses/RequestTypes/Index.vue");
const TravelExpenseRequestTypeCreate = () =>
    import("../Pages/TravelExpenses/RequestTypes/Create.vue");
const TravelExpenseRequestTypeEdit = () =>
    import("../Pages/TravelExpenses/RequestTypes/Edit.vue");

const TravelExpenseInvoiceType = () =>
    import("../Pages/TravelExpenses/InvoiceTypes/Index.vue");
const TravelExpenseInvoiceTypeCreate = () =>
    import("../Pages/TravelExpenses/InvoiceTypes/Create.vue");
const TravelExpenseInvoiceTypeEdit = () =>
    import("../Pages/TravelExpenses/InvoiceTypes/Edit.vue");

const TravelExpenseReport = () =>
    import("../Pages/TravelExpenses/ExpenseReport/Index.vue");

/** Licensing Module */

/** Event Name */
const LicenseEventName = () => import("../Pages/EventName/Index.vue");
const LicenseEventNameCreate = () => import("../Pages/EventName/Create.vue");
const LicenseEventNameEdit = () => import("../Pages/EventName/Edit.vue");

/** Rules */
const Rules = () => import("../Pages/Rules/Index.vue");
const RulesCreate = () => import("../Pages/Rules/Create.vue");
const RulesEdit = () => import("../Pages/Rules/Edit.vue");

/** Licenses */
const Licenses = () => import("../Pages/Licenses/Index.vue");
const LicensesCreate = () => import("../Pages/Licenses/Create.vue");
const LicensesEdit = () => import("../Pages/Licenses/Edit.vue");
const LicensesShow = () => import("../Pages/Licenses/Show.vue");

/** Requests */
const Requests = () => import("../Pages/Requests/Index.vue");
const RequestsCreate = () => import("../Pages/Requests/Create.vue");
const RequestsEdit = () => import("../Pages/Requests/Edit.vue");
const RequestsShow = () => import("../Pages/Requests/Show.vue");


/** Cancel Requests */
const CancelRequests = () => import("../Pages/CancelRequests/Index.vue");
const CancelRequestsCreate = () => import("../Pages/CancelRequests/Create.vue");
const CancelRequestsEdit = () => import("../Pages/CancelRequests/Edit.vue");


/** Store Entries */
const StoreEntries = () => import("../Pages/StoreEntries/Index.vue");
const StoreEntriesCreate = () => import("../Pages/StoreEntries/Create.vue");
const StoreEntriesEdit = () => import("../Pages/StoreEntries/Edit.vue");

/** API Keys */
const APIKeys = () => import("../Pages/APIKeys/Index.vue");
const APIKeysCreate = () => import("../Pages/APIKeys/Create.vue");
const APIKeysEdit = () => import("../Pages/APIKeys/Edit.vue");

/** Finance Dashboard */
const FinanceDashboard = () => import("../Pages/FinanceDashboard/Index.vue");

/** Customer Portal News */
const CustomerPortalNews = () =>
    import("../Pages/CustomerPortalNews/Index.vue");
const CustomerPortalNewsCreate = () =>
    import("../Pages/CustomerPortalNews/Create.vue");
const CustomerPortalNewsEdit = () =>
    import("../Pages/CustomerPortalNews/Edit.vue");

/** Form Of Contract */
const FormOfContract = () => import("../Pages/FormOfContract/Index.vue");
const FormOfContractCreate = () => import("../Pages/FormOfContract/Create.vue");
const FormOfContractEdit = () => import("../Pages/FormOfContract/Edit.vue");

/** Affected Groups */
const AffectedGroups = () => import("../Pages/AffectedGroups/Index.vue");
const AffectedGroupsCreate = () => import("../Pages/AffectedGroups/Create.vue");
const AffectedGroupsEdit = () => import("../Pages/AffectedGroups/Edit.vue");

/** Import Countries */
const ImportCountries = () => import("../Pages/Countries/ImportCountries.vue");

/** Continuous Improvement Process */
const ContinuousImprovementProcess = () =>
    import("../Pages/ContinuousImprovementProcess/Index.vue");
const CIPConfiguration = () => import("../Pages/CIPConfiguration/Index.vue");

/** Project Protocol */
const ProjectProtocol = () => import("../Pages/ProjectProtocols/Index.vue");
const ProjectProtocolCreate = () =>
    import("../Pages/ProjectProtocols/Create.vue");
const ProjectProtocolEdit = () => import("../Pages/ProjectProtocols/Edit.vue");

/** Project Statuses */
const ProjectStatuses = () => import("../Pages/ProjectStatuses/Index.vue");
const ProjectStatusesCreate = () =>
    import("../Pages/ProjectStatuses/Create.vue");
const ProjectStatusesEdit = () => import("../Pages/ProjectStatuses/Edit.vue");

/** Protocol Types */
const ProtocolTypes = () => import("../Pages/ProtocolTypes/Index.vue");
const ProtocolTypesCreate = () => import("../Pages/ProtocolTypes/Create.vue");
const ProtocolTypesEdit = () => import("../Pages/ProtocolTypes/Edit.vue");

/** Highest School Degrees */
const HighestSchoolDegrees = () =>
    import("../Pages/HighestSchoolDegrees/Index.vue");
const HighestSchoolDegreesCreate = () =>
    import("../Pages/HighestSchoolDegrees/Create.vue");
const HighestSchoolDegreesEdit = () =>
    import("../Pages/HighestSchoolDegrees/Edit.vue");

/** Highest Education Levels */
const HighestEducationLevels = () =>
    import("../Pages/HighestEducationLevels/Index.vue");
const HighestEducationLevelsCreate = () =>
    import("../Pages/HighestEducationLevels/Create.vue");
const HighestEducationLevelsEdit = () =>
    import("../Pages/HighestEducationLevels/Edit.vue");

/** Mail Webhooks */
const MailWebhooks = () => import("../Pages/MailWebhooks/Index.vue");
const MailWebhooksCreate = () => import("../Pages/MailWebhooks/Create.vue");
const MailWebhooksEdit = () => import("../Pages/MailWebhooks/Edit.vue");

/** AMS */
const AMS = () => import("../Pages/AMS/Index.vue");
const AMSCreate = () => import("../Pages/AMS/Create.vue");
const AMSEdit = () => import("../Pages/AMS/Edit.vue");
const AMSShow = () => import("../Pages/AMS/Show.vue");

/** Consulting Dashboard */
const ConsultingDashboard = () =>
    import("../Pages/ConsultingDashboard/Index.vue");

/** Invoice Reminder */
const InvoiceReminderCreate = () =>
    import("../Pages/InvoiceReminderLevel/Create.vue");
const InvoiceReminderIndex = () =>
    import("../Pages/InvoiceReminderLevel/Index.vue");
const InvoiceReminderEdit = () =>
    import("../Pages/InvoiceReminderLevel/Edit.vue");

/** Open Posts */
const OpenPosts = () => import("../Pages/OpenPosts/Index.vue");
const OpenPostsEdit = () => import("../Pages/OpenPosts/Edit.vue");

/** Product Price List */
const ProductPriceCreate = () => import("../Pages/ProductPrice/Create.vue");
const ProductPriceList = () => import("../Pages/ProductPrice/Index.vue");
const ProductPriceEdit = () => import("../Pages/ProductPrice/Edit.vue");

/** Assets */
const AssetsCreate = () => import("../Pages/AssetManagement/Asset/Create.vue");
const Assets = () => import("../Pages/AssetManagement/Asset/Index.vue");
const AssetsEdit = () => import("../Pages/AssetManagement/Asset/Edit.vue");

/** Sales Dashboard */
const SalesDashboard = () => import("../Pages/SalesDashboard/Index.vue");

/** Consulting Teams */
const ConsultingTeams = () => import("../Pages/ConsultingTeams/Index.vue");

/** Changelog */
const Changelog = () => import("../Pages/Dashboard/Changelog/Create.vue");
const ChangelogEdit = () => import("../Pages/Dashboard/Changelog/Edit.vue");

/** Cost Monitoring */
const CostMonitoring = () =>
    import("../Pages/FleetManagement/CostMonitoring/Index.vue");

/** Contract Types */
const ContractTypes = () => import("../Pages/ContractTypes/Index.vue");
const ContractTypesCreate = () => import("../Pages/ContractTypes/Create.vue");
const ContractTypesEdit = () => import("../Pages/ContractTypes/Edit.vue");

/** Global Notification List */
const TicketSystemSettings = () =>
    import("../Pages/TicketSystemSettings/Index.vue");
const TicketSystemSettingsCreate = () =>
    import("../Pages/TicketSystemSettings/Create.vue");
const TicketSystemSettingsEdit = () =>
    import("../Pages/TicketSystemSettings/Edit.vue");

/** Attachments */
const Attachments = () => import("../Pages/Attachments/Index.vue");
const AttachmentsCreate = () => import("../Pages/Attachments/Create.vue");
const AttachmentsEdit = () => import("../Pages/Attachments/Edit.vue");

/** Contract Management */

/** Inbounded Contracts */
const InboundedContracts = () =>
    import("../Pages/ContractManagement/InboundedContracts/Index.vue");
// My Asset
const MyAssets = () => import("../Pages/AssetManagement/MyAssets/Index.vue");

// Outbounded Contracts
const OutboundedContracts = () =>
    import("../Pages/ContractManagement/OutboundedContracts/Index.vue");
const OutboundedContractsCreate = () =>
    import("../Pages/ContractManagement/OutboundedContracts/Create.vue");
const OutboundedContractsEdit = () =>
    import("../Pages/ContractManagement/OutboundedContracts/Edit.vue");
const OutboundedContractsShow = () =>
    import("../Pages/ContractManagement/OutboundedContracts/Show.vue");

// Asset List
const AssetListCreate = () =>
    import("../Pages/AssetManagement/AssetList/Create.vue");
const AssetList = () => import("../Pages/AssetManagement/AssetList/Index.vue");
const AssetListEdit = () =>
    import("../Pages/AssetManagement/AssetList/Edit.vue");

// Time Checking
const TimeChecking = () => import("../Pages/TimeChecking/Index.vue");

// Modify My Data
// Personal Modification Processes
const PersonalModificationProcesses = () =>
    import("../Pages/ModifyMyData/PersonalModificationProcesses/Index.vue");
const PersonalModificationProcessesCreate = () =>
    import("../Pages/ModifyMyData/PersonalModificationProcesses/Create.vue");
const PersonalModificationProcessesEdit = () =>
    import("../Pages/ModifyMyData/PersonalModificationProcesses/Edit.vue");

// Personal Modification Process Managers
const PersonalModificationProcessManagers = () =>
    import(
        "../Pages/ModifyMyData/PersonalModificationProcessManagers/Index.vue"
    );

// Change Processes
const ChangeProcesses = () =>
    import("../Pages/ModifyMyData/ChangeProcesses/Index.vue");
const ChangeProcessesEdit = () =>
    import("../Pages/ModifyMyData/ChangeProcesses/Edit.vue");

// Personal Modification Process Checklists
const PersonalModificationProcessChecklists = () =>
    import(
        "../Pages/ModifyMyData/PersonalModificationProcessChecklists/Index.vue"
    );
const PersonalModificationProcessChecklistsCreate = () =>
    import(
        "../Pages/ModifyMyData/PersonalModificationProcessChecklists/Create.vue"
    );
const PersonalModificationProcessChecklistsEdit = () =>
    import(
        "../Pages/ModifyMyData/PersonalModificationProcessChecklists/Edit.vue"
    );

// Personal Requests
const PersonalRequests = () => import("../Pages/PersonalRequest/Index.vue");
const PersonalRequestsCreate = () =>
    import("../Pages/PersonalRequest/Create.vue");
const PersonalRequestsEdit = () => import("../Pages/PersonalRequest/Edit.vue");

// Approvers
const PersonalRequestApprovers = () =>
    import("../Pages/PersonalRequest/Approvers.vue");
const PersonalRequestApproverListing = () =>
    import("../Pages/PersonalRequest/ApproverListing.vue");

// Support Dashboard
const SupportDashboard = () => import("../Pages/SupportDashboard/Index.vue");

// Product Card
const ProductCard = () => import("../Pages/ProductStore/Index.vue");
const ProductDetail = () => import("../Pages/ProductStore/Detail.vue");

const MyFeeds = () => import("../Pages/Feeds/MyFeeds.vue");

//storage locations
const StorageLocationsCreate = () =>
    import("../Pages/StorageLocation/Create.vue");
const StorageLocations = () => import("../Pages/StorageLocation/Index.vue");
const StorageLocationsEdit = () => import("../Pages/StorageLocation/Edit.vue");

const AssetEmployeeListTextCreate = () =>
    import("../Pages/AssetEmployeeListText/Create.vue");
const AssetEmployeeListText = () =>
    import("../Pages/AssetEmployeeListText/Index.vue");
const AssetEmployeeListTextEdit = () =>
    import("../Pages/AssetEmployeeListText/Edit.vue");

const AssetDeliveryList = () =>
    import("../Pages/AssetManagement/AssetDelivery/Index.vue");
const AssetDeliveryEdit = () =>
    import("../Pages/AssetManagement/AssetDelivery/Edit.vue");

const AssetArticleShow = () =>
    import("../Pages/AssetManagement/Asset/Show.vue");

const Pipelines = () => import("../Pages/EventPipeline/Pipelines/Index.vue");
const PipelinesCreate = () =>
    import("../Pages/EventPipeline/Pipelines/Create.vue");

//bonus report
const bonusReport = () => import("../Pages/BonusReport/Index.vue");

const EmployeeInterview = () => import("../Pages/EmployeeInterview/Index.vue");
const EmployeeInterviewCreate = () =>
    import("../Pages/EmployeeInterview/Create.vue");
const EmployeeInterviewEdit = () =>
    import("../Pages/EmployeeInterview/Edit.vue");
//view tenant pods

const PartnerManagementDiscount = () =>
    import("../Pages/PartnerManagement/PartnerDiscount/Index.vue");

const tenantPods = () =>
    import("../Pages/Infrastructures/CloudInfrastructures/ViewTenantPods.vue");
//Project Management 2
const ProjectManagement2 = () =>
    import("../Pages/ProjectManagement2/Projects/Index.vue");

const ProjectSettingDetail = () =>
    import(
        "../Pages/ProjectManagement2/ProjectSetting/ProjectDetail/Index.vue"
    );
const ProjectSettingAccess = () =>
    import(
        "../Pages/ProjectManagement2/ProjectSetting/ProjectAccess/Index.vue"
    );
const ProjectSettingFeatures = () =>
    import(
        "../Pages/ProjectManagement2/ProjectSetting/ProjectFeatures/Index.vue"
    );
const ProjectSettingNotification = () =>
    import(
        "../Pages/ProjectManagement2/ProjectSetting/ProjectNotification/Index.vue"
    );
const ProjectSettingIssuseType = () =>
    import(
        "../Pages/ProjectManagement2/ProjectSetting/ProjectIssuseType/Index.vue"
    );
const ProjectSettingKanbanLines = () =>
    import(
        "../Pages/ProjectManagement2/ProjectSetting/ProjectKanbanLine/Index.vue"
    );

const ProjectShowNews = () =>
    import("../Pages/ProjectManagement2/ProjectShow/ProjectNews/Index.vue");
const ProjectShowBoard = () =>
    import("../Pages/ProjectManagement2/ProjectShow/ProjectBoard/Index.vue");
const ProjectShowSprintBoard = () =>
    import(
        "../Pages/ProjectManagement2/ProjectShow/ProjectSprintBoard/Index.vue"
    );
const ProjectShowBacklog = () =>
    import("../Pages/ProjectManagement2/ProjectShow/ProjectBacklog/Index.vue");
const ProjectShowSprintBacklog = () =>
    import(
        "../Pages/ProjectManagement2/ProjectShow/ProjectSprintBacklog/Index.vue"
    );
const ProjectShowSwimlaneBoard = () =>
    import(
        "../Pages/ProjectManagement2/ProjectShow/ProjectSwimlaneBoard/Index.vue"
    );
const ProjectShowGanttChart = () =>
    import(
        "../Pages/ProjectManagement2/ProjectShow/ProjectGanttChart/Index.vue"
    );
const ProjectShowStoryMap = () =>
    import("../Pages/ProjectManagement2/ProjectShow/ProjectStoryMap/Index.vue");

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            name: "Main",
            path: "/",
            component: Main,
            redirect: "/dashboard",
            meta: {
                title: "Home",
            },
            beforeEnter: async (to, from, next) => {
                if (to.meta.public && !store.getters["auth/authenticated"]) {
                    next();
                    return;
                }
                if (isLoggedIn() || to.meta.public) {
                    if (
                        !store.getters["permissions/permissionsGlobal"]?.length
                    ) {
                        try {
                            await store.dispatch(
                                "permissions/permissionsGlobal",
                                {
                                    limit_start: 0,
                                    limit_count: 1000,
                                }
                            );
                        } catch (e) {
                        } finally {
                            next();
                        }
                    } else {
                        next();
                    }
                    return;
                }
                next({ name: "Login" });
            },
            children: [
                {
                    name: "Dashboard",
                    path: "dashboard",
                    component: Dashboard,
                    meta: {
                        title: "Dashboard",
                    },
                },
                {
                    name: "SupportDashboard",
                    path: "support-dashboard",
                    component: SupportDashboard,
                    meta: {
                        title: "Dashboard",
                    },
                },
                {
                    name: "MyFeeds",
                    path: "my-feeds",
                    component: MyFeeds,
                    meta: {
                        title: "MyFeeds",
                    },
                },
                {
                    name: "ProductStoreList",
                    path: "/product-store",
                    component: ProductCard,
                    meta: {
                        title: "Product Store",
                    },
                },
                {
                    name: "ProductDetail",
                    path: "/product-detail/:id",
                    component: ProductDetail,
                    meta: {
                        title: "Product Detail",
                    },
                },
                {
                    name: "PersonalRequestApprovers",
                    path: "personal-request-approvers",
                    component: PersonalRequestApprovers,
                    meta: {
                        title: "Personal Request Approvers",
                    },
                },
                {
                    name: "PersonalRequestApproverListing",
                    path: "personal-requirements",
                    component: PersonalRequestApproverListing,
                    meta: {
                        title: "Personal Requirements",
                    },
                },
                {
                    name: "ChangeProcessesList",
                    path: "change-processes",
                    component: ChangeProcesses,
                    meta: {
                        title: "Change Processes",
                    },
                },
                {
                    name: "ModifyMyData",
                    path: "change-processes/:id/edit",
                    component: ChangeProcessesEdit,
                    meta: {
                        title: "Edit Change Process",
                    },
                },
                {
                    name: "PersonalRequests",
                    path: "personal-requests",
                    component: PersonalRequests,
                    meta: {
                        title: "Personal Requests",
                        permission: "personal-requests",
                    },
                },
                {
                    name: "PersonalRequestsCreate",
                    path: "personal-requests/create",
                    component: PersonalRequestsCreate,
                    meta: {
                        title: "Create Personal Request",
                        permission: "personal-requests",
                    },
                },
                {
                    name: "PersonalRequestsEdit",
                    path: "personal-requests/:id/edit",
                    component: PersonalRequestsEdit,
                    meta: {
                        title: "Edit Personal Request",
                        permission: "personal-requests",
                    },
                },
                {
                    name: "PersonalModificationProcessManagers",
                    path: "personal-modification-process-managers",
                    component: PersonalModificationProcessManagers,
                    meta: {
                        title: "Personal Modification Process Managers",
                    },
                },
                {
                    name: "PersonalModificationProcessChecklistsListing",
                    path: "personal-modification-process-checklists",
                    component: PersonalModificationProcessChecklists,
                    meta: {
                        title: "Personal Modification Process Checklists",
                    },
                },
                {
                    name: "PersonalModificationProcessChecklistsCreate",
                    path: "personal-modification-process-checklists/create",
                    component: PersonalModificationProcessChecklistsCreate,
                    meta: {
                        title: "Create Personal Modification Process Checklist",
                    },
                },
                {
                    name: "PersonalModificationProcessChecklist",
                    path: "personal-modification-process-checklists/:id/edit",
                    component: PersonalModificationProcessChecklistsEdit,
                    meta: {
                        title: "Edit Personal Modification Process Checklist",
                    },
                },
                {
                    name: "PersonalModificationProcesses",
                    path: "personal-modification-processes",
                    component: PersonalModificationProcesses,
                    meta: {
                        title: "Personal Modification Processes",
                    },
                },
                {
                    name: "PersonalModificationProcessesCreate",
                    path: "personal-modification-processes/create",
                    component: PersonalModificationProcessesCreate,
                    meta: {
                        title: "Create Personal Modification Process",
                    },
                },
                {
                    name: "PersonalModificationProcessesEdit",
                    path: "personal-modification-processes/:id/edit",
                    component: PersonalModificationProcessesEdit,
                    meta: {
                        title: "Edit Personal Modification Process",
                    },
                },
                {
                    name: "ContinuousImprovementProcess",
                    path: "continuous-improvement-process",
                    component: ContinuousImprovementProcess,
                    meta: {
                        title: "Continuous Improvement Process",
                        permission: "continuous-improvement-process",
                    },
                },
                {
                    name: "CIPConfiguration",
                    path: "cip-configuration",
                    component: CIPConfiguration,
                    meta: {
                        title: "CIP Configuration",
                        permission: "cip-configuration",
                    },
                },
                {
                    name: "TimeChecking",
                    path: "time-checking",
                    component: TimeChecking,
                    meta: {
                        title: "Time Checking",
                        permission: "time-checking",
                    },
                },
                {
                    name: "ConsultingTeams",
                    path: "consulting-teams",
                    component: ConsultingTeams,
                    meta: {
                        title: "Consulting Teams",
                        permission: "consulting-teams",
                    },
                },
                {
                    name: "InboundedContracts",
                    path: "inbounded-contracts",
                    component: InboundedContracts,
                    meta: {
                        title: "Inbounded Contracts",
                        permission: "inbounded-contracts",
                    },
                },
                {
                    name: "OutboundedContractsList",
                    path: "outbounded-contracts",
                    component: OutboundedContracts,
                    meta: {
                        title: "Outbounded Contracts",
                        permission: "outbounded-contracts",
                    },
                },
                {
                    name: "OutboundedContractsCreate",
                    path: "outbounded-contracts/create",
                    component: OutboundedContractsCreate,
                    meta: {
                        title: "Create Outbounded Contract",
                        permission: "outbounded-contracts",
                    },
                },
                {
                    name: "OutboundedContract",
                    path: "outbounded-contracts/:id/edit",
                    component: OutboundedContractsEdit,
                    meta: {
                        title: "Edit Outbounded Contract",
                        permission: "outbounded-contracts",
                    },
                },
                {
                    name: "OutboundedContractShowRecord",
                    path: "outbounded-contracts/:id",
                    component: OutboundedContractsShow,
                    meta: {
                        title: "Show Outbounded Contract",
                        permission: "outbounded-contracts",
                    },
                },
                {
                    name: "SalesDashboard",
                    path: "sales-dashboard",
                    component: SalesDashboard,
                    meta: {
                        title: "Sales Dashboard",
                    },
                },
                {
                    name: "SalesDashboard",
                    path: "sales-dashboard",
                    component: SalesDashboard,
                    meta: {
                        title: "Sales Dashboard",
                    },
                },
                {
                    name: "FinanceDashboard",
                    path: "finance-dashboard",
                    component: FinanceDashboard,
                    meta: {
                        title: "Finance Dashboard",
                    },
                },
                {
                    name: "ConsultingDashboard",
                    path: "consulting-dashboard",
                    component: ConsultingDashboard,
                    meta: {
                        title: "Consulting Dashboard",
                    },
                },
                {
                    name: "ProjectManagementDashboard",
                    path: "project-management-dashboard",
                    component: ProjectManagementDashboard,
                    meta: {
                        title: "Project Management Dashboard",
                        permission: "project-management-dashboard",
                    },
                },
                {
                    name: "PMDashboardTeams",
                    path: "pm-dashboard-teams",
                    component: PMDashboardTeams,
                    meta: {
                        title: "Project Management Dashboard Teams",
                        permission: "pm-dashboard-teams",
                    },
                },
                {
                    name: "ProjectManagement2",
                    path: "project-management2/project",
                    component: ProjectManagement2,
                    meta: {
                        title: "Projects",
                        permission: "projectmanagement2",
                    },
                },
                {
                    name: "ProjectSettingDetail",
                    path: "/project-settings-detail",
                    component: ProjectSettingDetail,
                    meta: {
                        title: "Project Settings Detail",
                        permission: "project-settings-detail",
                    },
                },
                {
                    name: "ProjectSettingAccess",
                    path: "/project-settings-access",
                    component: ProjectSettingAccess,
                    meta: {
                        title: "Project Settings Access",
                        permission: "project-settings-access",
                    },
                },
                {
                    name: "ProjectSettingFeatures",
                    path: "/project-settings-features",
                    component: ProjectSettingFeatures,
                    meta: {
                        title: "Project Settings Features",
                        permission: "project-settings-features",
                    },
                },
                {
                    name: "ProjectSettingNotification",
                    path: "/project-settings-notification",
                    component: ProjectSettingNotification,
                    meta: {
                        title: "Project Settings Notification",
                        permission: "project-settings-notification",
                    },
                },
                {
                    name: "ProjectSettingIssuseType",
                    path: "/project-settings-issuse-type",
                    component: ProjectSettingIssuseType,
                    meta: {
                        title: "Project Settings Issuse Type",
                        permission: "project-settings-issuse-type",
                    },
                },
                {
                    name: "ProjectSettingKanbanLines",
                    path: "/project-settings-kanban-lines",
                    component: ProjectSettingKanbanLines,
                    meta: {
                        title: "Project Settings Kanban Lines",
                        permission: "project-settings-kanban-lines",
                    },
                },
                {
                    name: "ProjectShowNews",
                    path: "/project-news",
                    component: ProjectShowNews,
                    meta: {
                        title: "Project News",
                        permission: "project-news",
                    },
                },
                {
                    name: "ProjectShowBoard",
                    path: "/project-board",
                    component: ProjectShowBoard,
                    meta: {
                        title: "Project Scrum Board",
                        permission: "project-board",
                    },
                },
                {
                    name: "ProjectShowSprintBoard",
                    path: "/project-sprint-board",
                    component: ProjectShowSprintBoard,
                    meta: {
                        title: "Project Sprint Board",
                        permission: "project-sprint-board",
                    },
                },
                {
                    name: "ProjectShowBacklog",
                    path: "/project-backlog",
                    component: ProjectShowBacklog,
                    meta: {
                        title: "Project Backlog",
                        permission: "project-backlog",
                    },
                },
                {
                    name: "ProjectShowSprintBacklog",
                    path: "/project-sprint-backlog",
                    component: ProjectShowSprintBacklog,
                    meta: {
                        title: "Project Sprint Backlog",
                        permission: "project-sprint-backlog",
                    },
                },
                {
                    name: "ProjectShowSwimlaneBoard",
                    path: "/project-swimlane-board",
                    component: ProjectShowSwimlaneBoard,
                    meta: {
                        title: "Project Swimlane Board",
                        permission: "project-swimlane-board",
                    },
                },
                {
                    name: "ProjectShowStoryMap",
                    path: "/project-story-map",
                    component: ProjectShowStoryMap,
                    meta: {
                        title: "Project Story Map",
                        permission: "project-story-map",
                    },
                },
                {
                    name: "ProjectShowGanttChart",
                    path: "/project-gantt-chart",
                    component: ProjectShowGanttChart,
                    meta: {
                        title: "Project Gantt Chart",
                        permission: "project-gantt-chart",
                    },
                },

                {
                    name: "FormOfContractList",
                    path: "form-of-contract",
                    component: FormOfContract,
                    meta: {
                        title: "Form Of Contract",
                    },
                },
                {
                    name: "FormOfContractCreate",
                    path: "form-of-contract/create",
                    component: FormOfContractCreate,
                    meta: {
                        title: "Create Form Of Contract",
                    },
                },
                {
                    name: "FormOfContract",
                    path: "form-of-contract/:id/edit",
                    component: FormOfContractEdit,
                    meta: {
                        title: "Edit Form Of Contract",
                    },
                },
                {
                    name: "ContractTypesList",
                    path: "contract-types",
                    component: ContractTypes,
                    meta: {
                        title: "Contract Types",
                        permission: "contract-types",
                    },
                },
                {
                    name: "ContractTypesCreate",
                    path: "contract-types/create",
                    component: ContractTypesCreate,
                    meta: {
                        title: "Create Contract Type",
                        permission: "contract-types",
                    },
                },
                {
                    name: "ContractType",
                    path: "contract-types/:id/edit",
                    component: ContractTypesEdit,
                    meta: {
                        title: "Edit Contract Type",
                        permission: "contract-types",
                    },
                },
                {
                    name: "TicketSystemSettings",
                    path: "ticket-system-settings",
                    component: TicketSystemSettings,
                    meta: {
                        title: "Ticket System Settings",
                        permission: "ticket-system-settings",
                    },
                },
                {
                    name: "TicketSystemSettingsCreate",
                    path: "ticket-system-settings/create",
                    component: TicketSystemSettingsCreate,
                    meta: {
                        title: "Ticket System Settings",
                        permission: "ticket-system-settings",
                    },
                },
                {
                    name: "TicketSystemSettingsEdit",
                    path: "ticket-system-settings/:id/edit",
                    component: TicketSystemSettingsEdit,
                    meta: {
                        title: "Ticket System Settings",
                        permission: "ticket-system-settings",
                    },
                },

                {
                    name: "AttachmentsList",
                    path: "attachments",
                    component: Attachments,
                    meta: {
                        title: "Attachments",
                        permission: "attachments",
                    },
                },
                {
                    name: "AttachmentsCreate",
                    path: "attachments/create",
                    component: AttachmentsCreate,
                    meta: {
                        title: "Create Attachment",
                        permission: "attachments",
                    },
                },
                {
                    name: "Attachment",
                    path: "attachments/:id/edit",
                    component: AttachmentsEdit,
                    meta: {
                        title: "Edit Attachment",
                        permission: "attachments",
                    },
                },
                {
                    name: "AffectedGroupsList",
                    path: "affected-groups",
                    component: AffectedGroups,
                    meta: {
                        title: "Affected Groups",
                        permission: "affected-groups",
                    },
                },
                {
                    name: "AffectedGroupsCreate",
                    path: "affected-groups/create",
                    component: AffectedGroupsCreate,
                    meta: {
                        title: "Create Affected Groups",
                        permission: "affected-groups",
                    },
                },
                {
                    name: "AffectedGroup",
                    path: "affected-groups/:id/edit",
                    component: AffectedGroupsEdit,
                    meta: {
                        title: "Edit Affected Groups",
                        permission: "affected-groups",
                    },
                },
                {
                    name: "AMSList",
                    path: "ams",
                    component: AMS,
                    meta: {
                        title: "Application Management Services",
                        permission: "application-management-services",
                    },
                },
                {
                    name: "AMSCreate",
                    path: "ams/create",
                    component: AMSCreate,
                    meta: {
                        title: "Create Application Management Service",
                        permission: "application-management-services",
                    },
                },
                {
                    name: "ApplicationManagementService",
                    path: "ams/:id/edit",
                    component: AMSEdit,
                    meta: {
                        title: "Edit Application Management Service",
                        permission: "application-management-services",
                    },
                },
                {
                    name: "ApplicationManagementServiceShowRecord",
                    path: "ams/:id/show",
                    component: AMSShow,
                    meta: {
                        title: "Show Application Management Service",
                        permission: "application-management-services",
                    },
                },
                {
                    name: "MailWebhooksList",
                    path: "mail-webhooks",
                    component: MailWebhooks,
                    meta: {
                        title: "Mail Webhooks",
                    },
                },
                {
                    name: "MailWebhooksCreate",
                    path: "mail-webhooks/create",
                    component: MailWebhooksCreate,
                    meta: {
                        title: "Create Mail Webhook",
                    },
                },
                {
                    name: "MailWebhook",
                    path: "mail-webhooks/:id/edit",
                    component: MailWebhooksEdit,
                    meta: {
                        title: "Edit Mail Webhook",
                    },
                },
                {
                    name: "ProjectProtocolList",
                    path: "project-protocols",
                    component: ProjectProtocol,
                    meta: {
                        title: "Project Protocol",
                        permission: "project-protocols",
                    },
                },
                {
                    name: "ProjectProtocolCreate",
                    path: "project-protocols/create",
                    component: ProjectProtocolCreate,
                    meta: {
                        title: "Create Project Protocol",
                        permission: "project-protocols",
                    },
                },
                {
                    name: "ProjectProtocol",
                    path: "project-protocols/:id/edit",
                    component: ProjectProtocolEdit,
                    meta: {
                        title: "Edit Project Protocol",
                        permission: "project-protocols",
                    },
                },
                {
                    name: "ProjectStatusesList",
                    path: "project-statuses",
                    component: ProjectStatuses,
                    meta: {
                        title: "Project Statuses",
                    },
                },
                {
                    name: "ProjectStatusesCreate",
                    path: "project-statuses/create",
                    component: ProjectStatusesCreate,
                    meta: {
                        title: "Create Project Status",
                    },
                },
                {
                    name: "ProjectStatus",
                    path: "project-statuses/:id/edit",
                    component: ProjectStatusesEdit,
                    meta: {
                        title: "Edit Project Status",
                    },
                },
                {
                    name: "ProtocolTypesList",
                    path: "protocol-types",
                    component: ProtocolTypes,
                    meta: {
                        title: "Protocol Types",
                    },
                },
                {
                    name: "ProtocolTypesCreate",
                    path: "protocol-types/create",
                    component: ProtocolTypesCreate,
                    meta: {
                        title: "Create Protocol Type",
                    },
                },
                {
                    name: "ProtocolType",
                    path: "protocol-types/:id/edit",
                    component: ProtocolTypesEdit,
                    meta: {
                        title: "Edit Protocol Type",
                    },
                },
                {
                    name: "HighestSchoolDegreesList",
                    path: "highest-school-degrees",
                    component: HighestSchoolDegrees,
                    meta: {
                        title: "Highest School Degrees",
                    },
                },
                {
                    name: "HighestSchoolDegreesCreate",
                    path: "highest-school-degrees/create",
                    component: HighestSchoolDegreesCreate,
                    meta: {
                        title: "Create Highest School Degree",
                    },
                },
                {
                    name: "HighestSchoolDegree",
                    path: "highest-school-degrees/:id/edit",
                    component: HighestSchoolDegreesEdit,
                    meta: {
                        title: "Edit Highest School Degree",
                    },
                },
                {
                    name: "HighestEducationLevelsList",
                    path: "highest-education-levels",
                    component: HighestEducationLevels,
                    meta: {
                        title: "Highest Education Levels",
                    },
                },
                {
                    name: "HighestEducationLevelsCreate",
                    path: "highest-education-levels/create",
                    component: HighestEducationLevelsCreate,
                    meta: {
                        title: "Create Highest Education Level",
                    },
                },
                {
                    name: "HighestEducationLevel",
                    path: "highest-education-levels/:id/edit",
                    component: HighestEducationLevelsEdit,
                    meta: {
                        title: "Edit Highest Education Level",
                    },
                },
                {
                    name: "APIKeysList",
                    path: "api-keys",
                    component: APIKeys,
                    meta: {
                        title: "API Keys",
                    },
                },
                {
                    name: "APIKeysCreate",
                    path: "api-keys/create",
                    component: APIKeysCreate,
                    meta: {
                        title: "Create API Key",
                    },
                },
                {
                    name: "APIKey",
                    path: "api-keys/:id/edit",
                    component: APIKeysEdit,
                    meta: {
                        title: "Edit API Key",
                    },
                },
                {
                    name: "LicensesList",
                    path: "licenses",
                    component: Licenses,
                    meta: {
                        title: "Licenses",
                    },
                },
                {
                    name: "LicensesCreate",
                    path: "licenses/create",
                    component: LicensesCreate,
                    meta: {
                        title: "Create License",
                    },
                },
                {
                    name: "License",
                    path: "licenses/:id/edit",
                    component: LicensesEdit,
                    meta: {
                        title: "Edit License",
                    },
                },
                {
                    name: "LicenseShowRecord",
                    path: "licenses/:id",
                    component: LicensesShow,
                    meta: {
                        title: "Show License",
                    },
                },
                // Product Store
                {
                    name: "StoreEntriesList",
                    path: "store-entries",
                    component: StoreEntries,
                    meta: {
                        title: "Store Entries",
                    },
                },
                {
                    name: "StoreEntriesCreate",
                    path: "store-entries/create",
                    component: StoreEntriesCreate,
                    meta: {
                        title: "Create Store Entries",
                    },
                },
                {
                    name: "ProductStore",
                    path: "store-entries/:id/edit",
                    component: StoreEntriesEdit,
                    meta: {
                        title: "Edit Store Entries",
                    },
                },

                {
                    name: "requestsList",
                    path: "requests",
                    component: Requests,
                    meta: {
                        title: "Requests",
                    },
                },
                {
                    name: "requestsCreate",
                    path: "requests/create",
                    component: RequestsCreate,
                    meta: {
                        title: "Create Requests",
                    },
                },
                {
                    name: "ProductStoreRequest",
                    path: "requests/:id/edit",
                    component: RequestsEdit,
                    meta: {
                        title: "Edit Requests",
                    },
                },
                {
                    name: "ProductStoreRequestShowRecord",
                    path: "requests/:id",
                    component: RequestsShow,
                    meta: {
                        title: "Show Requests",
                    },
                },
                {
                    name: "cancelRequestsList",
                    path: "cancel-requests",
                    component: CancelRequests,
                    meta: {
                        title: "Cancel Requests",
                    },
                },
                {
                    name: "cancelRequestsCreate",
                    path: "cancel/requests/create",
                    component: CancelRequestsCreate,
                    meta: {
                        title: "Create Cancel Requests",
                    },
                },
                {
                    name: "CancelProductStoreRequest",
                    path: "cancel/requests/:id/edit",
                    component: CancelRequestsEdit,
                    meta: {
                        title: "Edit Cancel Requests",
                    },
                },

                {
                    name: "WorkshopTemplatesList",
                    path: "workshop-templates",
                    component: WorkshopTemplates,
                    meta: {
                        title: "Workshop Templates",
                    },
                },
                {
                    name: "WorkshopTemplatesCreate",
                    path: "workshop-templates/create",
                    component: WorkshopTemplatesCreate,
                    meta: {
                        title: "Create Workshop Template",
                    },
                },
                {
                    name: "WorkshopTemplate",
                    path: "workshop-templates/:id/edit",
                    component: WorkshopTemplatesEdit,
                    meta: {
                        title: "Edit Workshop Template",
                    },
                },
                {
                    name: "WorkshopTemplatesEditTemp",
                    path: "workshop-templates/:id/edit-temp",
                    component: WorkshopTemplatesEditTemp,
                    meta: {
                        title: "Edit Workshop Template",
                    },
                },
                {
                    name: "WorkshopTemplatesShowRecord",
                    path: "workshop-templates/:id",
                    component: WorkshopTemplatesShow,
                    meta: {
                        title: "Show Workshop Template",
                    },
                },
                {
                    name: "Handouts",
                    path: "handouts",
                    component: Handouts,
                    meta: {
                        title: "Handouts",
                    },
                },
                {
                    name: "HandoutsCreate",
                    path: "handouts/create",
                    component: HandoutsCreate,
                    meta: {
                        title: "Create Handout",
                    },
                },
                {
                    name: "VacationRequest",
                    path: "vacation-request",
                    component: VacationRequest,
                    meta: {
                        title: "Vacation Request",
                        permission: "employee-vacation",
                    },
                },
                {
                    name: "ExpiryMonth",
                    path: "expiry-month",
                    component: ExpiryMonth,
                    meta: {
                        title: "Expiry Month",
                        permission: "expiry-month",
                    },
                },

                /* Lead system end start */

                {
                    name: "JobLevel",
                    path: "job-level/:id/edit",
                    component: JobLevelEdit,
                    meta: {
                        title: "Operating Job Level",
                        permission: "job-level",
                    },
                },
                {
                    name: "JobLevelCreate",
                    path: "job-level/create",
                    component: JobLevelCreate,
                    meta: {
                        title: "Create Job Level",
                        permission: "job-level",
                    },
                },
                {
                    name: "JobLevelList",
                    path: "job-level",
                    component: JobLevel,
                    meta: {
                        title: "Job Level",
                        permission: "job-level",
                    },
                },
                /* Lead system end urls */

                {
                    name: "DatabaseCloudList",
                    path: "database-cloud",
                    component: DatabaseCloud,
                    meta: {
                        title: "Database Cloud",
                        permission: "database-cloud",
                    },
                },
                {
                    name: "DatabaseCloudCreate",
                    path: "database-cloud/create",
                    component: DatabaseCloudCreate,
                    meta: {
                        title: "Create Database Cloud",
                        permission: "database-cloud",
                    },
                },
                {
                    name: "DatabaseCloud",
                    path: "database-cloud/:id/edit",
                    component: DatabaseCloudEdit,
                    meta: {
                        title: "Edit Database Cloud",
                        permission: "database-cloud",
                    },
                },
                {
                    name: "DistributedFilesystemList",
                    path: "distributed-filesystem",
                    component: DistributedFilesystem,
                    meta: {
                        title: "Distributed Filesystem",
                        permission: "distributed-filesystem",
                    },
                },
                {
                    name: "DistributedFilesystemCreate",
                    path: "distributed-filesystem/create",
                    component: DistributedFilesystemCreate,
                    meta: {
                        title: "Create Distributed Filesystem",
                        permission: "distributed-filesystem",
                    },
                },
                {
                    name: "DistributedFilesystem",
                    path: "distributed-filesystem/:id/edit",
                    component: DistributedFilesystemEdit,
                    meta: {
                        title: "Edit Distributed Filesystem",
                        permission: "distributed-filesystem",
                    },
                },
                {
                    name: "DocumentAssignment",
                    path: "document-assignment",
                    component: DocumentAssignment,
                    meta: {
                        title: "Document Assignment",
                        permission: "document-assignment",
                    },
                },
                {
                    name: "MailTemplateAssignment",
                    path: "mail-template-assignment",
                    component: MailTemplateAssignment,
                    meta: {
                        title: "Mail Template Assignment",
                        permission: "mail-template-assignment",
                    },
                },
                {
                    name: "EloConfiguration",
                    path: "elo-configuration",
                    component: EloConfiguration,
                    meta: {
                        title: "Elo Configuration",
                        permission: "elo-configuration",
                    },
                },
                {
                    name: "PerformanceRecordList",
                    path: "performance-records",
                    component: PerformanceRecord,
                    meta: {
                        title: "Performance Records",
                        permission: "performance-record",
                    },
                },
                {
                    name: "PerformanceRecord",
                    path: "performance-records/show",
                    component: PerformanceRecordShow,
                    meta: {
                        title: "Edit Performance Record",
                        permission: "performance-record",
                    },
                },
                {
                    name: "PerformanceRecordCreate",
                    path: "performance-records/create",
                    component: PerformanceRecordCreate,
                    meta: {
                        title: "Create Performance Record",
                        permission: "performance-record",
                    },
                },
                {
                    name: "PartnerCompanies",
                    path: "partner-management/companies",
                    component: PartnerCompanies,
                    meta: {
                        title: "Partner Companies",
                        permission: "partner-company",
                    },
                },

                {
                    name: "PartnerCompaniesEdit",
                    path: "partner/companies/:id/edit",
                    component: PartnerCompaniesEdit,
                    meta: {
                        title: "Edit Partner Company",
                        permission: "partner-company",
                    },
                },
                {
                    name: "PartnerCompanyShowRecord",
                    path: "partner/companies/:id",
                    component: PartnerCompaniesShow,
                    meta: {
                        title: "Show Partner Company",
                        permission: "partner-company",
                    },
                },
                {
                    name: "DocumentServiceList",
                    path: "document-service",
                    component: DocumentService,
                    meta: {
                        title: "Document Service",
                        permission: "template",
                    },
                },
                {
                    name: "DocumentServiceCreate",
                    path: "document-service/create",
                    component: DocumentServiceCreate,
                    meta: {
                        title: "Create Template",
                        permission: "template",
                    },
                },
                {
                    name: "DocumentService",
                    path: "document-service/:id/edit",
                    component: DocumentServiceEdit,
                    meta: {
                        title: "Edit Template",
                        permission: "template",
                    },
                },
                {
                    name: "DocumentServiceShow",
                    path: "document-service/:id",
                    component: DocumentServiceShow,
                    meta: {
                        title: "Show Template",
                        permission: "template",
                    },
                },
                {
                    name: "TermsOfPaymentList",
                    path: "terms-of-payment",
                    component: TermsOfPayment,
                    meta: {
                        title: "Terms Of Payment",
                        permission: "terms-of-payment",
                    },
                },
                {
                    name: "TermsOfPaymentCreate",
                    path: "terms-of-payment/create",
                    component: TermsOfPaymentCreate,
                    meta: {
                        title: "Create Terms Of Payment",
                        permission: "terms-of-payment",
                    },
                },
                {
                    name: "TermsOfPayment",
                    path: "terms-of-payment/:id/edit",
                    component: TermsOfPaymentUpdate,
                    meta: {
                        title: "Edit Terms Of Payment",
                        permission: "terms-of-payment",
                    },
                },
                {
                    name: "DefaultCoverLetterText",
                    path: "default-cover-letter-text",
                    component: DefaultCoverLetterText,
                    meta: {
                        title: "Default Cover Letter Text",
                        permission: "default-cover-letter-text",
                    },
                },
                {
                    name: "DefaultOfferConfirmationCoverLetterText",
                    path: "default-offer-confirmation-cover-letter-text",
                    component: DefaultOfferConfirmationCoverLetterText,
                    meta: {
                        title: "Default Offer Confirmation Cover Letter Text",
                        permission:
                            "default-offer-confirmation-cover-letter-text",
                    },
                },
                {
                    name: "CountriesList",
                    path: "countries",
                    component: Countries,
                    meta: {
                        title: "Countries",
                        permission: "countries",
                    },
                },
                {
                    name: "CountriesCreate",
                    path: "countries/create",
                    component: CountriesCreate,
                    meta: {
                        title: "Create Countries",
                        permission: "countries",
                    },
                },
                {
                    name: "Country",
                    path: "countries/:id/edit",
                    component: CountriesEdit,
                    meta: {
                        title: "Edit Countries",
                        permission: "countries",
                    },
                },
                {
                    name: "Offers",
                    path: "offers",
                    component: Offers,
                    meta: {
                        title: "Offers",
                        permission: "offer",
                    },
                },
                {
                    name: "OffersCreate",
                    path: "offers/create",
                    component: OffersCreate,
                    meta: {
                        title: "Create Offers",
                        permission: "offer",
                    },
                },
                {
                    name: "SaleOffer",
                    path: "offers/:id/edit",
                    component: OffersEdit,
                    meta: {
                        title: "Edit Offers",
                        permission: "offer",
                    },
                },
                {
                    name: "SaleOfferShowRecord",
                    path: "offers/:id",
                    component: OffersShow,
                    meta: {
                        title: "Show Offers",
                        permission: "offer",
                    },
                },
                {
                    name: "Orders",
                    path: "partner-management/orders",
                    component: Orders,
                    meta: {
                        title: "Orders",
                        permission: "partner-order",
                    },
                },
                {
                    name: "OrdersCreate",
                    path: "partner-management/orders/create",
                    component: OrdersCreate,
                    meta: {
                        title: "Create Orders",
                        permission: "partner-order",
                    },
                },
                {
                    name: "PartnerOrder",
                    path: "partner-management/orders/:id/edit",
                    component: OrdersEdit,
                    meta: {
                        title: "Edit Orders",
                        permission: "partner-order",
                    },
                },
                {
                    name: "PartnerOrderShowRecord",
                    path: "partner-management/orders/:id",
                    component: OrdersShow,
                    meta: {
                        title: "Show Orders",
                        permission: "partner-order",
                    },
                },
                {
                    name: "OfferRequestsList",
                    path: "offer-requests",
                    component: OfferRequests,
                    meta: {
                        title: "Offer Requests",
                        permission: "offer-requests",
                    },
                },
                {
                    name: "OfferRequestsCreate",
                    path: "offer-requests/create",
                    component: OfferRequestsCreate,
                    meta: {
                        title: "Create Offer Requests",
                        permission: "offer-requests",
                    },
                },
                {
                    name: "OfferRequest",
                    path: "offer-requests/:id/edit",
                    component: OfferRequestsEdit,
                    meta: {
                        title: "Edit Offer Requests",
                        permission: "offer-requests",
                    },
                },
                {
                    name: "OfferRequestShowRecord",
                    path: "offer-requests/:id",
                    component: OfferRequestsShow,
                    meta: {
                        title: "Show Offer Requests",
                        permission: "offer-requests",
                    },
                },
                {
                    name: "MailDepot",
                    path: "mail-depot",
                    component: MailDepot,
                    meta: {
                        title: "Mail Depot",
                        permission: "mail-depot",
                    },
                },
                {
                    name: "OrderConfirmationList",
                    path: "order-confirmation",
                    component: OrderConfirmation,
                    meta: {
                        title: "Offer Confirmation",
                        permission: "order-confirmation",
                    },
                },
                {
                    name: "OrderConfirmationCreate",
                    path: "order-confirmation/create",
                    component: OrderConfirmationCreate,
                    meta: {
                        title: "Create Offer Confirmation",
                        permission: "order-confirmation",
                    },
                },
                {
                    name: "SaleOfferOrderConfirmation",
                    path: "order-confirmation/:id/edit",
                    component: OrderConfirmationEdit,
                    meta: {
                        title: "Edit Offer Confirmation",
                        permission: "order-confirmation",
                    },
                },
                {
                    name: "SaleOfferOrderConfirmationShowRecord",
                    path: "order-confirmation/:id",
                    component: OrderConfirmationShow,
                    meta: {
                        title: "Show Offer Confirmation",
                        permission: "order-confirmation",
                    },
                },
                {
                    name: "SelfService",
                    path: "self-service",
                    component: SelfService,
                    meta: {
                        title: "Self Service",
                    },
                },
                {
                    name: "Profile",
                    path: "profile",
                    component: Profile,
                    meta: {
                        title: "Profile",
                    },
                },
                {
                    name: "Products",
                    path: "products",
                    component: Products,
                    meta: {
                        title: "Products",
                        permission: "product",
                    },
                },
                {
                    name: "ProductImports",
                    path: "products/import",
                    component: ProductImports,
                    meta: {
                        title: "Import Product",
                        permission: "product",
                    },
                },
                {
                    name: "ProductsCreate",
                    path: "products/create",
                    component: ProductsCreate,
                    meta: {
                        title: "Create Product",
                        permission: "product",
                    },
                },
                {
                    name: "Product",
                    path: "products/:id/edit",
                    component: ProductsEdit,
                    meta: {
                        title: "Edit Product",
                        permission: "product",
                    },
                },
                {
                    name: "Companies",
                    path: "companies",
                    component: Companies,
                    meta: {
                        title: "Companies",
                        permission: "company",
                    },
                },
                {
                    name: "CompaniesCreate",
                    path: "companies/create",
                    component: CompaniesCreate,
                    meta: {
                        title: "Create Company",
                        permission: "company",
                    },
                },
                {
                    name: "Company",
                    path: "companies/:id/edit",
                    component: CompaniesEdit,
                    meta: {
                        title: "Edit Company",
                        permission: "company",
                    },
                },
                {
                    name: "CompanyShowRecord",
                    path: "companies/:id",
                    component: CompaniesShow,
                    meta: {
                        title: "Show Company",
                        permission: "company",
                    },
                },
                {
                    name: "LeadListing",
                    path: "leads",
                    component: Leads,
                    meta: {
                        title: "Leads",
                        permission: "lead",
                    },
                },
                {
                    name: "LeadsCreate",
                    path: "leads/create",
                    component: LeadsCreate,
                    meta: {
                        title: "Create Lead",
                        permission: "lead",
                    },
                },
                {
                    name: "CompanyLead",
                    path: "leads/:id/edit",
                    component: LeadsEdit,
                    meta: {
                        title: "Edit Lead",
                        permission: "lead",
                    },
                },
                {
                    name: "CompanyLeadShowRecord",
                    path: "leads/:id",
                    component: LeadsShow,
                    meta: {
                        title: "Show Lead",
                        permission: "lead",
                    },
                },
                {
                    name: "Partners",
                    path: "partners",
                    component: Partners,
                    meta: {
                        title: "Partners",
                        permission: "partner",
                    },
                },
                {
                    name: "PartnersCreate",
                    path: "partners/create",
                    component: PartnersCreate,
                    meta: {
                        title: "Create Partner",
                        permission: "partner",
                    },
                },
                {
                    name: "CompanyPartner",
                    path: "partners/:id/edit",
                    component: PartnersEdit,
                    meta: {
                        title: "Edit Partner",
                        permission: "partner",
                    },
                },
                {
                    name: "CompanyPartnerShowRecord",
                    path: "partners/:id",
                    component: PartnersShow,
                    meta: {
                        title: "Show Partner",
                        permission: "partner",
                    },
                },
                {
                    name: "Suppliers",
                    path: "suppliers",
                    component: Suppliers,
                    meta: {
                        title: "Suppliers",
                        permission: "supplier",
                    },
                },
                {
                    name: "SuppliersCreate",
                    path: "suppliers/create",
                    component: SuppliersCreate,
                    meta: {
                        title: "Create Supplier",
                        permission: "supplier",
                    },
                },
                {
                    name: "Supplier",
                    path: "suppliers/:id/edit",
                    component: SuppliersEdit,
                    meta: {
                        title: "Edit Supplier",
                        permission: "supplier",
                    },
                },
                {
                    name: "SupplierShowRecord",
                    path: "suppliers/:id",
                    component: SuppliersShow,
                    meta: {
                        title: "Show Supplier",
                        permission: "supplier",
                    },
                },
                {
                    name: "UserList",
                    path: "employees",
                    component: Users,
                    meta: {
                        title: "Users",
                        permission: "user",
                    },
                },
                {
                    name: "UsersCreate",
                    path: "employees/create",
                    component: UsersCreate,
                    meta: {
                        title: "Create Users",
                        permission: "user",
                    },
                },
                {
                    name: "User",
                    path: "employees/:id/edit",
                    component: UsersEdit,
                    meta: {
                        title: "Edit Users",
                        permission: "user",
                    },
                },
                {
                    name: "PlanningDashboard",
                    path: "planning-dashboard",
                    component: PlanningDashboard,
                    meta: {
                        title: "Planning Board",
                        permission: "planning-dashboard",
                    },
                },
                {
                    name: "RolesList",
                    path: "roles",
                    component: Roles,
                    meta: {
                        title: "Roles",
                        permission: "role",
                    },
                },
                {
                    name: "RolesCreate",
                    path: "roles/create",
                    component: RolesCreate,
                    meta: {
                        title: "Create Role",
                        permission: "role",
                    },
                },
                {
                    name: "Role",
                    path: "roles/:id/edit",
                    component: RolesEdit,
                    meta: {
                        title: "Edit Role",
                        permission: "role",
                    },
                },
                {
                    name: "PermissionsList",
                    path: "permissions",
                    component: Permissions,
                    meta: {
                        title: "Permissions",
                        permission: "permission",
                    },
                },
                {
                    name: "PermissionsCreate",
                    path: "permissions/create",
                    component: PermissionsCreate,
                    meta: {
                        title: "Create Permission",
                        permission: "permission",
                    },
                },
                {
                    name: "Permission",
                    path: "permissions/:id/edit",
                    component: PermissionsEdit,
                    meta: {
                        title: "Edit Permission",
                        permission: "permission",
                    },
                },
                {
                    name: "InvoicesList",
                    path: "invoices",
                    component: Invoices,
                    meta: {
                        title: "Invoices",
                        permission: "invoice",
                    },
                },
                {
                    name: "InvoicesCreate",
                    path: "invoices/create",
                    component: InvoicesCreate,
                    meta: {
                        title: "Create Invoice",
                        permission: "invoice",
                    },
                },
                {
                    name: "Invoice",
                    path: "invoices/:id/edit",
                    component: InvoicesEdit,
                    meta: {
                        title: "Edit Invoice",
                        permission: "invoice",
                    },
                },
                {
                    name: "InvoiceShowRecord",
                    path: "invoices/:id",
                    component: InvoicesShow,
                    meta: {
                        title: "Show Invoice",
                        permission: "invoice",
                    },
                },

                {
                    name: "InvoicesTemplatesList",
                    path: "invoices-templates",
                    component: InvoicesTemplates,
                    meta: {
                        title: "Invoice Templates",
                        permission: "invoice-templates",
                    },
                },
                {
                    name: "InvoicesTemplatesCreate",
                    path: "invoices-templates/create",
                    component: InvoicesTemplatesCreate,
                    meta: {
                        title: "Create Invoice Templates",
                        permission: "invoice-templates",
                    },
                },
                {
                    name: "InvoiceTemplate",
                    path: "invoices-templates/:id/edit",
                    component: InvoicesTemplatesEdit,
                    meta: {
                        title: "Edit Invoice Templates",
                        permission: "invoice-templates",
                    },
                },
                {
                    name: "InvoiceTemplateShowRecord",
                    path: "invoices-templates/:id",
                    component: InvoicesTemplatesShow,
                    meta: {
                        title: "Show Invoice Templates",
                        permission: "invoice-templates",
                    },
                },

                {
                    name: "CreditorInvoices",
                    path: "creditor-invoices",
                    component: CreditorInvoices,
                    meta: {
                        title: "Creditor Invoices",
                        permission: "creditor-invoices",
                    },
                },
                {
                    name: "CreditorInvoicesCreate",
                    path: "creditor-invoices/create",
                    component: CreditorInvoicesCreate,
                    meta: {
                        title: "Create Creditor Invoice",
                        permission: "creditor-invoice",
                    },
                },
                {
                    name: "CreditorInvoice",
                    path: "creditor-invoices/:id/edit",
                    component: CreditorInvoicesEdit,
                    meta: {
                        title: "Edit Creditor Invoice",
                        permission: "creditor-invoices",
                    },
                },
                {
                    name: "ProductGroupsListing",
                    path: "product-groups",
                    component: ProductGroups,
                    meta: {
                        title: "Product Groups",
                        permission: "product-group",
                    },
                },
                {
                    name: "ProductGroupsCreate",
                    path: "product-groups/create",
                    component: ProductGroupsCreate,
                    meta: {
                        title: "Create Product Group",
                        permission: "product-group",
                    },
                },
                {
                    name: "ProductGroup",
                    path: "product-groups/:id/edit",
                    component: ProductGroupsEdit,
                    meta: {
                        title: "Edit Product Group",
                        permission: "product-group",
                    },
                },
                {
                    name: "VersionsList",
                    path: "versions",
                    component: Versions,
                    meta: {
                        title: "Versions",
                        permission: "elo-version",
                    },
                },
                {
                    name: "VersionsCreate",
                    path: "versions/create",
                    component: VersionsCreate,
                    meta: {
                        title: "Create Version",
                        permission: "elo-version",
                    },
                },
                {
                    name: "EloVersion",
                    path: "versions/:id/edit",
                    component: VersionsEdit,
                    meta: {
                        title: "Edit Version",
                        permission: "elo-version",
                    },
                },
                {
                    name: "SystemsOnPremiseList",
                    path: "systems/on-premise",
                    component: SystemsOnPremise,
                    meta: {
                        title: "On Premise Systems",
                        permission: "system",
                    },
                },
                {
                    name: "SystemsOnPremiseCreate",
                    path: "systems/on-premise/create",
                    component: SystemsOnPremiseCreate,
                    meta: {
                        title: "Create On Premise System",
                        permission: "system",
                    },
                },
                {
                    name: "SystemPremiseSystem",
                    path: "systems/on-premise/:id/edit",
                    component: SystemsOnPremiseEdit,
                    meta: {
                        title: "Edit On Premise System",
                        permission: "system",
                    },
                },
                {
                    name: "SystemsCloudList",
                    path: "systems/cloud",
                    component: SystemsCloud,
                    meta: {
                        title: "Cloud Systems",
                        permission: "system",
                    },
                },
                {
                    name: "SystemsCloudCreate",
                    path: "systems/cloud/create",
                    component: SystemsCloudCreate,
                    meta: {
                        title: "Create Cloud System",
                        permission: "system",
                    },
                },
                {
                    name: "SystemCloudSystem",
                    path: "systems/cloud/:id/edit",
                    component: SystemsCloudEdit,
                    meta: {
                        title: "Edit Cloud System",
                        permission: "system",
                    },
                },
                {
                    name: "SystemsHostingList",
                    path: "systems/hosting",
                    component: SystemsHosting,
                    meta: {
                        title: "Hosting Systems",
                        permission: "system",
                    },
                },
                {
                    name: "SystemsHostingCreate",
                    path: "systems/hosting/create",
                    component: SystemsHostingCreate,
                    meta: {
                        title: "Create Hosting System",
                        permission: "system",
                    },
                },
                {
                    name: "SystemHostingSystem",
                    path: "systems/hosting/:id/edit",
                    component: SystemsHostingEdit,
                    meta: {
                        title: "Edit Hosting System",
                        permission: "system",
                    },
                },
                {
                    name: "ReportCategoryList",
                    path: "report-categories",
                    component: ReportCategory,
                    meta: {
                        title: "Report Categories",
                        permission: "report-category",
                    },
                },
                {
                    name: "ReportCategoryCreate",
                    path: "report-categories/create",
                    component: ReportCategoryCreate,
                    meta: {
                        title: "Create Report Category",
                        permission: "report-category",
                    },
                },
                {
                    name: "ReportCategory",
                    path: "report-categories/:id/edit",
                    component: ReportCategoryEdit,
                    meta: {
                        title: "Edit Report Category",
                        permission: "report-category",
                    },
                },
                {
                    name: "ProductCategoryList",
                    path: "product-categories",
                    component: ProductCategory,
                    meta: {
                        title: "Product Categories",
                        permission: "product-category",
                    },
                },
                {
                    name: "ProductCategoryCreate",
                    path: "product-categories/create",
                    component: ProductCategoryCreate,
                    meta: {
                        title: "Create Product Category",
                        permission: "product-category",
                    },
                },
                {
                    name: "ProductCategory",
                    path: "product-categories/:id/edit",
                    component: ProductCategoryEdit,
                    meta: {
                        title: "Edit Product Category",
                        permission: "product-category",
                    },
                },
                {
                    name: "ContactReports",
                    path: "contact-reports",
                    component: ContactReports,
                    meta: {
                        title: "Contact Reports",
                        permission: "contact-report",
                    },
                },
                {
                    name: "ContactReportsCreate",
                    path: "contact-reports/create",
                    component: ContactReportsCreate,
                    meta: {
                        title: "Create Contact Report",
                        permission: "contact-report",
                    },
                },
                {
                    name: "ContactReport",
                    path: "contact-reports/:id/edit",
                    component: ContactReportsEdit,
                    meta: {
                        title: "Edit Contact Report",
                        permission: "contact-report",
                    },
                },
                {
                    name: "ContactReportShowRecord",
                    path: "contact-reports/:id",
                    component: ContactReportsShow,
                    meta: {
                        title: "Show Contact Report",
                        permission: "contact-report",
                    },
                },
                {
                    name: "ProjectManagement",
                    path: "project-management",
                    component: ProjectManagement,
                    meta: {
                        title: "Project Management",
                        permission: "projectmanagement",
                    },
                },
                {
                    name: "Projects",
                    path: "project-management/projects",
                    component: Projects,
                    meta: {
                        title: "Projects",
                        permission: "project",
                    },
                },
                {
                    name: "ProjectsCreate",
                    path: "project-management/projects/create",
                    component: ProjectsCreate,
                    meta: {
                        title: "Create Project",
                        permission: "project",
                    },
                },
                {
                    name: "Project",
                    path: "project-management/projects/:id/edit",
                    component: ProjectsEdit,
                    meta: {
                        title: "Edit Project",
                        permission: "project",
                    },
                },
                {
                    name: "MyTasks",
                    path: "/my-tasks",
                    component: MyTasks,
                    meta: {
                        title: "My Tasks",
                        permission: "mytasks",
                    },
                },
                {
                    name: "TicketsList",
                    path: "tickets",
                    component: Tickets,
                    meta: {
                        title: "Tickets",
                        permission: "ticket",
                    },
                },
                {
                    name: "TicketsCreate",
                    path: "tickets/create",
                    component: TicketsCreate,
                    meta: {
                        title: "Create Tickets",
                        permission: "ticket",
                    },
                },
                {
                    name: "TicketShowRecord",
                    path: "tickets/:id",
                    component: TicketsView,
                    meta: {
                        title: "View Ticket",
                        permission: "ticket",
                    },
                },
                {
                    name: "Ticket",
                    path: "tickets/:id/edit",
                    component: TicketsEdit,
                    meta: {
                        title: "Edit Ticket",
                        permission: "ticket",
                    },
                },
                {
                    name: "PartnerTicketsList",
                    path: "partner-management/tickets",
                    component: PartnerTickets,
                    meta: {
                        title: "Partner Tickets",
                        permission: "partner-ticket",
                    },
                },
                {
                    name: "PartnerTicketShowRecord",
                    path: "partner-management/tickets/:id",
                    component: PartnerTicketsView,
                    meta: {
                        title: "View Partner Ticket",
                        permission: "partner-ticket",
                    },
                },
                {
                    name: "PartnerTicketsCreate",
                    path: "partner-management/tickets/create",
                    component: PartnerTicketsCreate,
                    meta: {
                        title: "Create Partner Ticket",
                        permission: "partner-ticket",
                    },
                },
                {
                    name: "Partner Ticket",
                    path: "partner-management/tickets/:id/edit",
                    component: PartnerTicketsEdit,
                    meta: {
                        title: "Edit Partner Ticket",
                        permission: "partner-ticket",
                    },
                },
                {
                    name: "Surveys",
                    path: "surveys",
                    component: Surveys,
                    meta: {
                        title: "Surveys",
                        permission: "survey",
                    },
                },
                {
                    name: "SurveysCreate",
                    path: "surveys/create",
                    component: SurveysCreate,
                    meta: {
                        title: "Create Survey",
                        permission: "survey",
                    },
                },
                {
                    name: "Survey",
                    path: "surveys/:id/edit",
                    component: SurveysEdit,
                    meta: {
                        title: "Edit Survey",
                        permission: "survey",
                    },
                },
                {
                    name: "SurveyShowRecord",
                    path: "surveys/:id",
                    component: SurveysShow,
                    meta: {
                        title: "Show Survey",
                        permission: "survey",
                        public: true,
                    },
                },
                {
                    name: "TimeTrackers",
                    path: "time-trackers",
                    component: TimeTrackers,
                    meta: {
                        title: "Time Trackers",
                        permission: "time-tracker",
                    },
                },
                {
                    name: "UserProfileList",
                    path: "user-profiles",
                    component: UserProfile,
                    meta: {
                        title: "User Profile",
                        permission: "user-profile",
                    },
                },
                {
                    name: "UserProfileCreate",
                    path: "user-profiles/create",
                    component: UserProfileCreate,
                    meta: {
                        title: "Create User Profile",
                        permission: "user-profile",
                    },
                },
                {
                    name: "UserProfileData",
                    path: "user-profiles/:id/edit",
                    component: UserProfileUpdate,
                    meta: {
                        title: "Update User Profile",
                        permission: "user-profile",
                    },
                },
                {
                    name: "UserDepartmentList",
                    path: "user/departments",
                    component: UserDepartment,
                    meta: {
                        title: "Departments",
                        permission: "user-department",
                    },
                },
                {
                    name: "UserDepartmentCreate",
                    path: "user/departments/create",
                    component: UserDepartmentCreate,
                    meta: {
                        title: "Create Department",
                        permission: "user-department",
                    },
                },
                {
                    name: "UserDepartment",
                    path: "user/departments/:id/edit",
                    component: UserDepartmentEdit,
                    meta: {
                        title: "Edit Department",
                        permission: "user-department",
                    },
                },
                {
                    name: "UserLocationList",
                    path: "user/locations",
                    component: UserLocation,
                    meta: {
                        title: "Locations",
                        permission: "user-location",
                    },
                },
                {
                    name: "UserLocationCreate",
                    path: "user/locations/create",
                    component: UserLocationCreate,
                    meta: {
                        title: "Create Location",
                        permission: "user-location",
                    },
                },
                {
                    name: "UserLocation",
                    path: "user/locations/:id/edit",
                    component: UserLocationEdit,
                    meta: {
                        title: "Edit Location",
                        permission: "user-location",
                    },
                },
                {
                    name: "UserTeamsList",
                    path: "user/teams",
                    component: UserTeam,
                    meta: {
                        title: "Teams",
                        permission: "user-team",
                    },
                },
                {
                    name: "UserTeamCreate",
                    path: "user/teams/create",
                    component: UserTeamCreate,
                    meta: {
                        title: "Create Team",
                        permission: "user-team",
                    },
                },
                {
                    name: "UserTeam",
                    path: "user/teams/:id/edit",
                    component: UserTeamEdit,
                    meta: {
                        title: "Edit Team",
                        permission: "user-team",
                    },
                },
                {
                    name: "UnitCreate",
                    path: "product-unit/create",
                    component: ProductUnitCreate,
                    meta: {
                        title: "Create Unit",
                        permission: "product-unit",
                    },
                },
                {
                    name: "UnitList",
                    path: "product-units",
                    component: ProductUnit,
                    meta: {
                        title: "Units",
                        permission: "product-unit",
                    },
                },
                {
                    name: "ProductUnit",
                    path: "product-unit/:id/edit",
                    component: ProductUnitEdit,
                    meta: {
                        title: "Unit Edit",
                        permission: "product-unit",
                    },
                },
                {
                    name: "SoftwareCreate",
                    path: "product-software/create",
                    component: ProductSoftwareCreate,
                    meta: {
                        title: "Create Software",
                        permission: "product-software",
                    },
                },
                {
                    name: "ServiceLevelAgreements",
                    path: "service-level-agreements",
                    component: ServiceLevelAgreement,
                    meta: {
                        title: "Service Level Agreements",
                        permission: "sla",
                    },
                },
                {
                    name: "ServiceLevelAgreementEdit",
                    path: "service-level-agreements/:id/edit",
                    component: ServiceLevelAgreementEdit,
                    meta: {
                        title: "Service Level Agreement Edit",
                        permission: "sla",
                    },
                },
                {
                    name: "ServiceLevelAgreementCreate",
                    path: "service-level-agreements/create",
                    component: ServiceLevelAgreementCreate,
                    meta: {
                        title: "Create Service Level Agreement",
                        permission: "sla",
                    },
                },
                {
                    name: "SoftwaresList",
                    path: "product-softwares",
                    component: ProductSoftware,
                    meta: {
                        title: "Softwares",
                        permission: "product-software",
                    },
                },
                {
                    name: "ProductSoftware",
                    path: "product-software/:id/edit",
                    component: ProductSoftwareEdit,
                    meta: {
                        title: "Software Edit",
                        permission: "product-software",
                    },
                },
                {
                    name: "PaymentPeriodCreate",
                    path: "product-periods/create",
                    component: ProductPeriodCreate,
                    meta: {
                        title: "Create Payment Period",
                        permission: "product-period",
                    },
                },
                {
                    name: "PaymentPeriodList",
                    path: "product-periods",
                    component: ProductPeriod,
                    meta: {
                        title: "Payment-Period",
                        permission: "payment-period",
                    },
                },
                {
                    name: "PaymentPeriod",
                    path: "product-periods/:id/edit",
                    component: ProductPeriodEdit,
                    meta: {
                        title: "Update Payment Period",
                        permission: "product-period",
                    },
                },
                {
                    name: "SystemHostList",
                    path: "system-hosts",
                    component: SystemHost,
                    meta: {
                        title: "System Host",
                        permission: "system-host",
                    },
                },
                {
                    name: "SystemHostCreate",
                    path: "system-host/create",
                    component: SystemHostCreate,
                    meta: {
                        title: "System Host Create",
                        permission: "system-host",
                    },
                },
                {
                    name: "SystemHost",
                    path: "system-host/:id/edit",
                    component: SystemHostEdit,
                    meta: {
                        title: "System Host Edit",
                        permission: "system-host",
                    },
                },
                {
                    name: "EloBusinessSolutionList",
                    path: "infrastructure-settings",
                    component: InfrastructureSettings,
                    meta: {
                        title: "Infrastructure Settings List",
                        permission: "infrastructure-settings",
                    },
                },
                {
                    name: "EloBusinessSolutionCreate",
                    path: "infrastructure-settings/create",
                    component: InfrastructureSettingsCreate,
                    meta: {
                        title: "Infrastructure Settings Create",
                        permission: "infrastructure-settings",
                    },
                },
                {
                    name: "EloBusinessSolutionEdit",
                    path: "infrastructure-settings/:id/edit",
                    component: InfrastructureSettingsEdit,
                    meta: {
                        title: "Infrastructure Settings Edit",
                        permission: "infrastructure-settings",
                    },
                },

                {
                    name: "CloudSystemCreate",
                    path: "infrastructures/cloud-infrastructures/create",
                    component: CloudSystemCreate,
                    meta: {
                        title: "Cloud Infrastructure Create",
                        permission: "system-cloud",
                    },
                },
                {
                    name: "CloudSystemList",
                    path: "infrastructures/cloud-infrastructures",
                    component: CloudSystem,
                    meta: {
                        title: "Cloud Infrastructure",
                        permission: "system-cloud",
                    },
                },
                {
                    name: "ServerPools",
                    path: "infrastructures/server-pools",
                    component: ServerPools,
                    meta: {
                        title: "Server Pool",
                        permission: "server-pools",
                    },
                },
                {
                    name: "ServerPoolsEdit",
                    path: "infrastructures/server-pools/:id/edit",
                    component: ServerPoolsEdit,
                    meta: {
                        title: "Edit Server Pool",
                        permission: "server-pools",
                    },
                },
                {
                    name: "ServerPoolsShow",
                    path: "infrastructures/server-pools/:id",
                    component: ServerPoolsShow,
                    meta: {
                        title: "Show Server Pool",
                        permission: "server-pools",
                    },
                },
                {
                    name: "System",
                    path: "infrastructures/cloud-infrastructures/:id/edit",
                    component: CloudSystemEdit,
                    meta: {
                        title: "Update Cloud Infrastructure",
                        permission: "system-cloud",
                    },
                },
                {
                    name: "SystemTanentChangeConfig",
                    path: "infrastructures/cloud-infrastructures/:id/change-config",
                    component: ChangeTanentConfig,
                    meta: {
                        title: "Update Cloud Infrastructure",
                        permission: "system-cloud",
                    },
                },
                /* Operating system end start */
                {
                    name: "OperatingSystemCreate",
                    path: "operating-system/create",
                    component: OperatingSystemCreate,
                    meta: {
                        title: "Create Operating System",
                        permission: "operating-system",
                    },
                },
                {
                    name: "OperatingSystem",
                    path: "operating-system/:id/edit",
                    component: OperatingSystemEdit,
                    meta: {
                        title: "Operating System Edit",
                        permission: "operating-system",
                    },
                },
                {
                    name: "OperatingSystemList",
                    path: "operating-system",
                    component: OperatingSystem,
                    meta: {
                        title: "Operating System",
                        permission: "operating-system",
                    },
                },

                /* Operating system end urls */

                /* Lead system end start */

                {
                    name: "LeadStatus",
                    path: "lead-status/:id/edit",
                    component: LeadStatusEdit,
                    meta: {
                        title: "Operating Lead Status",
                        permission: "lead-status",
                    },
                },
                {
                    name: "LeadStatusCreate",
                    path: "lead-status/create",
                    component: LeadStatusCreate,
                    meta: {
                        title: "Create Lead Status",
                        permission: "lead-status",
                    },
                },
                {
                    name: "LeadStatusList",
                    path: "lead-status",
                    component: LeadStatus,
                    meta: {
                        title: "Lead Status",
                        permission: "lead-status",
                    },
                },
                /* Lead system end urls */

                {
                    name: "MailTemplateList",
                    path: "mail-templates",
                    component: MailTemplate,
                    meta: {
                        title: "Mail Template",
                        permission: "mail-template",
                    },
                },
                {
                    name: "MailTemplateCreate",
                    path: "mail-templates/create",
                    component: MailTemplateCreate,
                    meta: {
                        title: "Mail Template Create",
                        permission: "mail-template",
                    },
                },
                {
                    name: "MailTemplate",
                    path: "mail-templates/:id/edit",
                    component: MailTemplateEdit,
                    meta: {
                        title: "Edit Mail Template",
                        permission: "mail-template",
                    },
                },
                {
                    name: "ContactReportSourceCreate",
                    path: "contact-report-source/create",
                    component: ContactReportSourceCreate,
                    meta: {
                        title: "Contact Report Source Create",
                        permission: "contact-report-source",
                    },
                },
                {
                    name: "ContactReportSource",
                    path: "contact-report-source/:id/edit",
                    component: ContactReportSourceEdit,
                    meta: {
                        title: "Contact Report Source Edit",
                        permission: "contact-report-source",
                    },
                },
                {
                    name: "ContactReportSourceList",
                    path: "contact-report-source",
                    component: ContactReportSource,
                    meta: {
                        title: "Contact Report Source",
                        permission: "contact-report-source",
                    },
                },
                {
                    name: "FleetCarsCreate",
                    path: "fleet-cars/create",
                    component: FleetCarsCreate,
                    meta: {
                        title: "Fleet Cars Create",
                        permission: "fleet-cars",
                    },
                },
                {
                    name: "FleetCars",
                    path: "fleet-cars",
                    component: FleetCars,
                    meta: {
                        title: "Fleet Cars",
                        permission: "fleet-cars",
                    },
                },
                {
                    name: "FleetCar",
                    path: "fleet-cars/:id/edit",
                    component: FleetCarsEdit,
                    meta: {
                        title: "Fleet Cars Edit",
                        permission: "fleet-cars",
                    },
                },
                {
                    name: "MileageMonitoring",
                    path: "mileage-monitoring",
                    component: MileageMonitoring,
                    meta: {
                        title: "Mileage Monitoring",
                        permission: "mileage-monitoring",
                    },
                },
                {
                    name: "FuelReceiptsCreate",
                    path: "fuel-receipts/create",
                    component: FuelReceiptsCreate,
                    meta: {
                        title: "Fuel Receipts Create",
                        permission: "fuel-receipts",
                    },
                },
                {
                    name: "FuelReceipt",
                    path: "fuel-monitoring",
                    component: FuelMonitoring,
                    meta: {
                        title: "Fuel Monitoring",
                        permission: "fuel-receipts",
                    },
                },
                {
                    name: "FuelReceiptShowRecord",
                    path: "fuel-monitoring/:id/show",
                    component: FuelMonitoringShow,
                    meta: {
                        title: "Fuel Monitoring Show",
                        permission: "fuel-monitoring",
                    },
                },
                {
                    name: "FleetDriversList",
                    path: "fleet-drivers",
                    component: FleetDrivers,
                    meta: {
                        title: "Fleet Drivers",
                        permission: "fleet-drivers",
                    },
                },
                {
                    name: "FleetDriverCreate",
                    path: "fleet-drivers/create",
                    component: FleetDriversCreate,
                    meta: {
                        title: "Fleet Drivers Create",
                        permission: "fleet-drivers",
                    },
                },
                {
                    name: "FleetDriver",
                    path: "fleet-drivers/:id/edit",
                    component: FleetDriversEdit,
                    meta: {
                        title: "Fleet Drivers Edit",
                        permission: "fleet-drivers",
                    },
                },
                {
                    name: "FleetDriverLicenceCheck",
                    path: "fleet-drivers/licence-check",
                    component: DriverLicenceCheck,
                    meta: {
                        title: "Fleet Driver Licence Check",
                        permission: "drivers-licence",
                    },
                },
                {
                    name: "FleetDriverShowRecord",
                    path: "fleet-drivers/:id/show",
                    component: FleetDriverShow,
                    meta: {
                        title: "Fleet Driver Show",
                        permission: "drivers-licence",
                    },
                },
                {
                    name: "IntervalSettingsList",
                    path: "interval-settings",
                    component: IntervalSettings,
                    meta: {
                        title: "Interval Settings",
                        permission: "interval-settings",
                    },
                },
                {
                    name: "IntervalSettingsCreate",
                    path: "interval-settings/create",
                    component: IntervalSettingsCreate,
                    meta: {
                        title: "Interval Settings Create",
                        permission: "interval-settings/create",
                    },
                },
                {
                    name: "IntervalSettings",
                    path: "interval-settings/:id/edit",
                    component: IntervalSettingsEdit,
                    meta: {
                        title: "Interval Settings Edit",
                        permission: "interval-settings/edit",
                    },
                },

                {
                    name: "TravelExpense",
                    path: "travel-expenses",
                    component: TravelExpense,
                    meta: {
                        title: "Travel Expense",
                        permission: "travel-expenses",
                    },
                },
                {
                    name: "TravelExpenseCreate",
                    path: "travel-expenses/create",
                    component: TravelExpenseCreate,
                    meta: {
                        title: "Travel Expense Create",
                        permission: "travel-expenses",
                    },
                },
                {
                    name: "TravelExpenseEdit",
                    path: "travel-expenses/:id/edit",
                    component: TravelExpenseEdit,
                    meta: {
                        title: "Travel Expense Edit",
                        permission: "travel-expenses",
                    },
                },
                {
                    name: "TravelExpenseApprover",
                    path: "travel-expenses/approve",
                    component: TravelExpenseApprover,
                    meta: {
                        title: "Travel Expense Approvers",
                        permission: "travel-expenses",
                    },
                },
                {
                    name: "TravelExpenseRequestTypeList",
                    path: "travel-expenses/request-types",
                    component: TravelExpenseRequestType,
                    meta: {
                        title: "Request Type",
                        permission: "travel-expense-request-type",
                    },
                },
                {
                    name: "LicenseEventNameList",
                    path: "event-name",
                    component: LicenseEventName,
                    meta: {
                        title: "Event Name",
                        permission: "travel-expense-request-type",
                    },
                },
                {
                    name: "LicenseEventNameCreate",
                    path: "event-name/create",
                    component: LicenseEventNameCreate,
                    meta: {
                        title: "Event Name Create",
                        permission: "travel-expense-request-type",
                    },
                },
                {
                    name: "LicenseEventName",
                    path: "event-name/:id/edit",
                    component: LicenseEventNameEdit,
                    meta: {
                        title: "Event Name Edit",
                        permission: "travel-expense-request-type",
                    },
                },
                {
                    name: "RulesList",
                    path: "rules",
                    component: Rules,
                    meta: {
                        title: "Rules",
                        permission: "",
                    },
                },
                {
                    name: "RulesCreate",
                    path: "rules/create",
                    component: RulesCreate,
                    meta: {
                        title: "Rules Create",
                        permission: "",
                    },
                },
                {
                    name: "Rules",
                    path: "rules/:id/edit",
                    component: RulesEdit,
                    meta: {
                        title: "Rules Edit",
                        permission: "",
                    },
                },
                {
                    name: "TravelExpenseRequestTypeCreate",
                    path: "travel-expenses/request-types/create",
                    component: TravelExpenseRequestTypeCreate,
                    meta: {
                        title: "Request Type Create",
                        permission: "travel-expense-request-type",
                    },
                },
                {
                    name: "TravelExpenseRequestType",
                    path: "travel-expenses/request-types/:id/edit",
                    component: TravelExpenseRequestTypeEdit,
                    meta: {
                        title: "Request Type Edit",
                        permission: "travel-expense-request-type",
                    },
                },
                {
                    name: "TravelExpenseInvoiceTypeList",
                    path: "travel-expenses/invoice-types",
                    component: TravelExpenseInvoiceType,
                    meta: {
                        title: "Invoice Type",
                        permission: "travel-expense-invoice-type",
                    },
                },
                {
                    name: "TravelExpenseInvoiceTypeCreate",
                    path: "travel-expenses/invoice-types/create",
                    component: TravelExpenseInvoiceTypeCreate,
                    meta: {
                        title: "Invoice Type Create",
                        permission: "travel-expense-invoice-type",
                    },
                },
                {
                    name: "TravelExpenseInvoiceType",
                    path: "travel-expenses/invoice-types/:id/edit",
                    component: TravelExpenseInvoiceTypeEdit,
                    meta: {
                        title: "Invoice Type Edit",
                        permission: "travel-expense-invoice-type",
                    },
                },
                {
                    name: "PartnerManagementDiscountList",
                    path: "partner-management/discount",
                    component: PartnerManagementDiscount,
                    meta: {
                        title: "Partner Discount",
                        permission: "partner-management-discount",
                    },
                },
                {
                    name: "TravelExpenseReport",
                    path: "travel-expenses/reports/:id",
                    component: TravelExpenseReport,
                    meta: {
                        title: "Travel Expense Reports",
                        permission: "travel-expense-report",
                    },
                },
                {
                    name: "CustomerPortalNewsList",
                    path: "customer-portal-news",
                    component: CustomerPortalNews,
                    meta: {
                        title: "News",
                        permission: "customer-portal-news",
                    },
                },
                {
                    name: "CustomerPortalNewsCreate",
                    path: "customer-portal-news/create",
                    component: CustomerPortalNewsCreate,
                    meta: {
                        title: "News Create",
                        permission: "customer-portal-news",
                    },
                },
                {
                    name: "CustomerPortalNews",
                    path: "customer-portal-news/:id/edit",
                    component: CustomerPortalNewsEdit,
                    meta: {
                        title: "News Edit",
                        permission: "customer-portal-news",
                    },
                },
                {
                    name: "Skill",
                    path: "skill/:id/edit",
                    component: SkillEdit,
                    meta: {
                        title: "Skill Edit",
                        permission: "skills",
                    },
                },
                {
                    name: "SkillCreate",
                    path: "skill/create",
                    component: SkillCreate,
                    meta: {
                        title: "Skill Create",
                        permission: "skills",
                    },
                },
                {
                    name: "SkillsList",
                    path: "skill",
                    component: Skills,
                    meta: {
                        title: "Skills",
                        permission: "skills",
                    },
                },
                {
                    name: "SkillGroupList",
                    path: "skill-group",
                    component: SkillGroup,
                    meta: {
                        title: "Skill Group",
                        permission: "skill-group",
                    },
                },
                {
                    name: "SkillGroupCreate",
                    path: "skill-group/create",
                    component: SkillGroupCreate,
                    meta: {
                        title: "Skill Group Create",
                        permission: "skill-group",
                    },
                },
                {
                    name: "SkillGroup",
                    path: "skill-group/:id/edit",
                    component: SkillGroupEdit,
                    meta: {
                        title: "Skill Group Edit",
                        permission: "skill-group",
                    },
                },
                {
                    name: "SkillMatrixList",
                    path: "skill-matrix",
                    component: SkillMatrix,
                    meta: {
                        title: "Skill Matrix",
                        permission: "skill-matrix",
                    },
                },
                {
                    name: "SkillMatrixCreate",
                    path: "skill-matrix/create",
                    component: SkillMatrixCreate,
                    meta: {
                        title: "Skill Matrix Create",
                        permission: "skill-matrix",
                    },
                },
                {
                    name: "SkillMatrix",
                    path: "skill-matrix/:id/edit",
                    component: SkillMatrixEdit,
                    meta: {
                        title: "Skill Matrix Edit",
                        permission: "skill-matrix",
                    },
                },
                {
                    name: "SkillMatrixShowRecord",
                    path: "skill-matrix/:id/show",
                    component: SkillMatrixShow,
                    meta: {
                        title: "Skill Matrix Show",
                        permission: "skill-matrix",
                    },
                },
                {
                    name: "SkillLevel",
                    path: "skill-level/:id/edit",
                    component: SkillLevelEdit,
                    meta: {
                        title: "Skill Level Edit",
                        permission: "skill-level",
                    },
                },
                {
                    name: "SkillLevelCreate",
                    path: "skill-level/create",
                    component: SkillLevelCreate,
                    meta: {
                        title: "Create Skill Level",
                        permission: "skill-level",
                    },
                },
                {
                    name: "SkillLevelList",
                    path: "skill-level",
                    component: SkillLevel,
                    meta: {
                        title: "Skill Level",
                        permission: "skill-level",
                    },
                },
                {
                    name: "UserJob",
                    path: "job",
                    component: UserJob,
                    meta: {
                        title: "Job",
                        permission: "job",
                    },
                },
                {
                    name: "UserJobCreate",
                    path: "job/create",
                    component: UserJobCreate,
                    meta: {
                        title: "Job Create",
                        permission: "job",
                    },
                },
                {
                    name: "Job",
                    path: "job/:id/edit",
                    component: UserJobEdit,
                    meta: {
                        title: "Job Edit",
                        permission: "job",
                    },
                },
                {
                    name: "ImportCountries",
                    path: "import/countries",
                    component: ImportCountries,
                    meta: {
                        title: "Import Countries",
                        permission: "Countries",
                    },
                },
                {
                    name: "InvoiceReminderCreate",
                    path: "invoice-reminder/create",
                    component: InvoiceReminderCreate,
                    meta: {
                        title: "Invoice Reminder Levels",
                        permission: "InvoiceReminderLevel",
                    },
                },
                {
                    name: "InvoiceReminderIndexList",
                    path: "invoice-reminders",
                    component: InvoiceReminderIndex,
                    meta: {
                        title: "Invoice Reminders",
                        permission: "InvoiceReminderLevel",
                    },
                },
                {
                    name: "InvoiceReminderLevel",
                    path: "invoice-reminders/:id/edit",
                    component: InvoiceReminderEdit,
                    meta: {
                        title: "Invoice Reminders Edit",
                        permission: "InvoiceReminderLevel",
                    },
                },
                {
                    name: "OpenPostsList",
                    path: "open-posts",
                    component: OpenPosts,
                    meta: {
                        title: "Open Posts",
                        permission: "OpenPosts",
                    },
                },
                {
                    name: "InvoiceOpenPost",
                    path: "open-posts/:id/edit",
                    component: OpenPostsEdit,
                    meta: {
                        title: "Open Posts",
                        permission: "OpenPosts",
                    },
                },
                {
                    name: "Product Price Create",
                    path: "product-price/create",
                    component: ProductPriceCreate,
                    meta: {
                        title: "Product Price Create",
                        permission: "product-price",
                    },
                },
                {
                    name: "ProductPriceListing",
                    path: "product-prices",
                    component: ProductPriceList,
                    meta: {
                        title: "Product Price",
                        permission: "product-price",
                    },
                },
                {
                    name: "PriceList",
                    path: "product-price/:id/edit",
                    component: ProductPriceEdit,
                    meta: {
                        title: "Product Price Edit",
                        permission: "product-price",
                    },
                },
                {
                    name: "My Assets",
                    path: "my-assets",
                    component: MyAssets,
                    meta: {
                        title: "My Assets",
                    },
                },
                {
                    name: "Create Assets",
                    path: "asset-create",
                    component: AssetsCreate,
                    meta: {
                        title: "Create Assets",
                        permission: "asset",
                    },
                },
                {
                    name: "Assets",
                    path: "assets",
                    component: Assets,
                    meta: {
                        title: "Assets",
                        permission: "asset",
                    },
                },
                {
                    name: "Asset",
                    path: "assets/:id/edit",
                    component: AssetsEdit,
                    meta: {
                        title: "Assets Edit",
                        permission: "asset",
                    },
                },
                {
                    name: "Changelog Create",
                    path: "/changelog/create",
                    component: Changelog,
                    meta: {
                        title: "Changelog Create",
                        permission: "changelog",
                    },
                },
                {
                    name: "Changelog",
                    path: "/changelog/:id/edit",
                    component: ChangelogEdit,
                    meta: {
                        title: "Changelog Edit",
                        permission: "changelog",
                    },
                },
                {
                    name: "Cost Monitoring",
                    path: "/cost-monitoring",
                    component: CostMonitoring,
                    meta: {
                        title: "Cost Monitoring",
                        permission: "cost-monitoring",
                    },
                },
                {
                    name: "Asset List Create",
                    path: "/asset-list/create",
                    component: AssetListCreate,
                    meta: {
                        title: "Asset List Create",
                        permission: "asset-employee",
                    },
                },
                {
                    name: "Asset List",
                    path: "/asset-lists",
                    component: AssetList,
                    meta: {
                        title: "Asset List",
                        permission: "asset-employee",
                    },
                },
                {
                    name: "AssetList",
                    path: "/asset-list/:id/edit",
                    component: AssetListEdit,
                    meta: {
                        title: "Asset List Edit",
                        permission: "asset-employee",
                    },
                },
                {
                    name: "Asset Delivery List",
                    path: "/asset-delivery",
                    component: AssetDeliveryList,
                    meta: {
                        title: "Asset Delivery",
                        permission: "asset-delivery",
                    },
                },
                {
                    name: "Asset Delivery Edit",
                    path: "/asset-delivery/:id/edit",
                    component: AssetDeliveryEdit,
                    meta: {
                        title: "Asset Delivery Edit",
                        permission: "asset-delivery",
                    },
                },
                {
                    name: "Asset Article Show",
                    path: "/asset-article/:id",
                    component: AssetArticleShow,
                    meta: {
                        title: "Asset Article Show",
                        permission: "asset-article",
                    },
                },
                {
                    name: "StorageLocationsCreate",
                    path: "storage-locations/create",
                    component: StorageLocationsCreate,
                    meta: {
                        title: "Storage Location Create",
                        permission: "storage-location",
                    },
                },
                {
                    name: "StorageLocations",
                    path: "storage-locations",
                    component: StorageLocations,
                    meta: {
                        title: "Storage Locations",
                        permission: "storage-location",
                    },
                },
                {
                    name: "StorageLocationsEdit",
                    path: "storage-locations/:id/edit",
                    component: StorageLocationsEdit,
                    meta: {
                        title: "Storage Locations Edit",
                        permission: "storage-location",
                    },
                },

                {
                    name: "AssetEmployeeListTextCreate",
                    path: "asset-employee-list-text/create",
                    component: AssetEmployeeListTextCreate,
                    meta: {
                        title: "Asset Employe List Text Create",
                        permission: "asset-employee-list-text",
                    },
                },
                {
                    name: "AssetEmployeeListText",
                    path: "asset-employee-list-text",
                    component: AssetEmployeeListText,
                    meta: {
                        title: "Asset Employee List Text",
                        permission: "asset-employee-list-text",
                    },
                },
                {
                    name: "AssetEmployeeListTextEdit",
                    path: "asset-employee-list-text/:id/edit",
                    component: AssetEmployeeListTextEdit,
                    meta: {
                        title: "Asset Employee List Text Edit",
                        permission: "asset-employee-list-text",
                    },
                },
                {
                    name: "Pipelines",
                    path: "/pipelines",
                    component: Pipelines,
                    meta: {
                        title: "Pipelines",
                    },
                },
                {
                    name: "PipelinesCreate",
                    path: "/pipelines/create",
                    component: PipelinesCreate,
                    meta: {
                        title: "Create Pipeline",
                    },
                },
                {
                    name: "Bonus",
                    path: "/bonus-report",
                    component: bonusReport,
                    meta: {
                        title: "Bonus Report",
                    },
                },
                {
                    name: "tenantPods",
                    path: "/tenant-pods/:id",
                    component: tenantPods,
                    meta: {
                        title: "Tenant Pods",
                    },
                },
                {
                    name: "EmployeeInterviewList",
                    path: "/employee-interview",
                    component: EmployeeInterview,
                    meta: {
                        title: "Employee Interview",
                        permission: "employee-interview",
                    },
                },

                {
                    name: "CreateEmployeeInterview",
                    path: "/employee-interview-create",
                    component: EmployeeInterviewCreate,
                    meta: {
                        title: "Create Employee Interview",
                        permission: "employee-interview",
                    },
                },
                {
                    name: "EmployeeInterview",
                    path: "/employee-interview/:id/edit",
                    component: EmployeeInterviewEdit,
                    meta: {
                        title: "Edit Employee Interview",
                        permission: "employee-interview",
                    },
                },
            ],
        },
        {
            name: "Login",
            path: "/login",
            component: Login,
            meta: {
                title: "Login",
            },
        },
    ],
});

function isLoggedIn() {
    return JSON.parse(localStorage.getItem("authenticated") ?? false);
}

router.beforeEach((to, from, next) => {
    // clears the product filter store if the page is changed to something other than the products module
    if (
        to.name !== "Products" &&
        to.name !== "ProductsCreate" &&
        to.name !== "ProductsEdit"
    ) {
        store.commit("products/filter", {
            product_category_id: "",
            status: "",
            search: "",
            product_software_id: "",
            type: "",
            sortBy: "",
            sortOrder: "asc",
        });
    }
    document.title = to.meta.title;
    next();
});

router.onError((error, to) => {
    if (
        error.message.includes("Failed to fetch dynamically imported module") ||
        error.message.includes("Importing a module script failed")
    ) {
        if (!to?.fullPath) {
            window.location.reload();
        } else {
            window.location = to.fullPath;
        }
    }
});

export default router;

import { createStore } from "vuex";
import apiService from "../utils/ApiService";
import ClientShellApiService from "../utils/ClientShellApiService";
import authApiService from "../utils/AuthApiService";
import docApiService from "../utils/DocApiService";
import dhc2ApiService from "../utils/Dhc2ApiService";
import licenseApiService from "../utils/LicenseApiService";
import mailDepotApiService from "../utils/MailDepotApiService";
import mailApiService from "../utils/MailApiService";
import auth from "./auth";
import systems from "./systems";
import products from "./products";
import productCategory from "./productcategory";
import planningDashboard from "./planningDashboard";
import companies from "./companies";
import suppliers from "./suppliers";
import companyEmployees from "./companies/employees";
import groups from "./groups";
import versions from "./versions";
import roles from "./roles";
import permissions from "./permissions";
import invoices from "./invoices";
import invoiceTemplates from "./invoiceTemplates";
import reportCategories from "./ReportCategories";
import contactReports from "./ContactReports";
import tickets from "./Tickets";
import partnerTickets from "./partnertickets";
import ticketComments from "./TicketComments";
import requestComments from "./requestComments";
import timeTrackers from "./TimeTrackers";
import projectManagement from "./ProjectManagement";
import projects from "./ProjectManagement/Projects";
import milestones from "./ProjectManagement/Milestones";
import tasks from "./ProjectManagement/Tasks";
import surveys from "./Surveys";
import questions from "./Surveys/Questions";
import chapters from "./Surveys/Chapters";
import surveyConfiguration from "./Surveys/Configurations";
import documentService from "./DocumentService";
import requests from "./requests";
import cancelRequests from "./cancelrequests";
import employeeInterview from "./employeeInterview"
// import eventName from "./eventName";
import serviceLevelAgreements from "./serviceLevelAgreements";
import slaInfrastructures from "./slaInfrastructures";
import slaLevelIncidents from "./slaLevelIncidents";
import slaLevelChanges from "./slaLevelChanges";
import slaServiceTimes from "./slaServiceTimes";

import userProfile from "./UserProfiles";
import vacationRequest from "./VacationRequest";
import userDepartments from "./departments";
import userTeams from "./teams";
import userLocations from "./locations";
import permissionGroups from "./permissiongroups";
import termsOfPayment from "./termsofpayment";
import units from "./units";
import softwares from "./softwares";
import operatingSystem from "./OperatingSytem";
import leadStatus from "./LeadStatus";
import storeEntries from "./StoreEntries";
import periods from "./periods";
import globalSettings from "./globalsettings";
import hosts from "./hosts";
import eloBusinessSolutions from "./elobusinesssolutions";
import performanceRecords from "./performancerecord";
import handouts from "./handouts";

import systemCloud from "./systemcloud";
import databaseCloud from "./databasecloud";
import distributedFilesystem from "./distributedfilesystem";

import licenses from "./licenses";

import mailTemplates from "./mailtemplates";

import contactSource from "./contactsource";

//global notification list
import globalNotification from "./globalnotification";

//fleet cars
import fleetCars from "./fleetmanagement/fleetcars";
//mileage monitoring
import mileageMonitoring from "./fleetmanagement/mileagemonitoring";
//fuel receipt
import fuelReceipts from "./fleetmanagement/fuelreceipts";
//fuel monitoring
import fuelMonitoring from "./fleetmanagement/fuelmonitoring";

//cost monitoring
import costMonitoring from "./fleetmanagement/costmonitoring";
//fleet drivers
import fleetDrivers from "./fleetmanagement/fleetdrivers";

//fleet drivers licence check
import fleetLicenceCheck from "./fleetmanagement/licencecheck";

//countries
import countries from "./countries";

//Travel Expenses
import travelExpense from "./travelExpense";
import travelExpenseRequestType from "./travelExpense/requestType";
import travelExpenseInvoiceType from "./travelExpense/invoiceType";
import travelExpenseBills from "./travelExpense/billReport";
import travelExpenseDaySpecifications from "./travelExpense/dayReport";
import travelExpensePrivateTransportations from "./travelExpense/transportationReport";

//interval settings
import intervalSettings from "./intervalsettings";

//workshop templates
import workshopTemplates from "./workshoptemplates";
import workshopTemplateCards from "./workshoptemplates/cards";
import workshopTemplateRows from "./workshoptemplates/rows";
import workshopTemplateColumns from "./workshoptemplates/columns";
import workshopTemplateWidgets from "./workshoptemplates/widgets";

import customerPortalNews from "./customerPortalNews";

//API Keys
import apiKeys from "./apikeys";

//User Jobs
import userJobs from "./UserJobs";

// Licensing Module
import eventName from "./licensing/eventname";
import rules from "./licensing/rules";
import mailRules from "./mailDepot/rules";
import mailMails from "./mailDepot/mails";
import mailProfiles from "./mailDepot/profiles";
import licensingLicenses from "./licensing/licenses";

//skills-management
import skills from "./SkillManagement/skills";
import skillGroup from "./SkillManagement/skillGroup";
import skillsMatrix from "./SkillManagement/skillMatrix";
import skillLevels from "./skillLevels";

//Job Level GlobalSettings
import jobLevel from "./JobLevel";

//Form Of Contract
import formOfContract from "./formofcontract";

//Offer
import offers from "./offers";

//Order
import orders from "./orders";

//Order Confirmation
import orderConfirmation from "./orderconfirmation";

//Affected Groups
import affectedGroups from "./affectedgroups";

//Continuous Improvement Process
import continuousImprovementProcess from "./continuousimprovementprocess";

//Project Protocols
import projectProtocols from "./projectprotocols";

//Project Protocol Entries
import projectProtocolEntries from "./projectprotocolentries";

//Protocol Types
import protocolTypes from "./protocoltypes";

//Project Statuses
import projectStatuses from "./projectstatuses";

import invoicereminder from "./invoicereminder";

//Highest School Degrees
import highestSchoolDegrees from "./highestschooldegrees";

//Highest Education Levels
import highestEducationLevels from "./highesteducationlevels";

//Mail Webhooks
import mailWebhooks from "./mailwebhooks";

//Consulting Dashboard
import consultingDashboard from "./consultingdasboard";

//AMS
import ams from "./ams";
//openposts
import openposts from "./openposts";

//product price
import productprice from "./productprice";

//assets
import assets from "./assets";
import assetDelivery from "./assetDelivery";

// sales dashboard
import saleDashboard from "./saledashboard";

// Contract Types
import contractTypes from "./contracttypes";

// Attachments
import attachments from "./attachments";

// Changelog
import changelog from "./changelog";

//Contract Management
import inboundedContracts from "./contractmanagement/inboundedcontracts";
import outboundedContracts from "./contractmanagement/outboundedcontracts";

//assetarticle
import assetArticle from "./assetarticle";
//assetlist
import assetList from "./assetlist";

//time checking
import timeChecking from "./timechecking";

/** modify my data **/
// personal modifciation processes
import personalModificationProcesses from "./ModifyMyData/PersonalModificationProcesses";
import personalModificationProcessManagers from "./ModifyMyData/PersonalModificationProcessManagers";
import personalModificationProcessChecklists from "./ModifyMyData/PersonalModificationProcessChecklists";
import changeProcesses from "./ModifyMyData/changeprocesses";

/** personal request */
import personalRequests from "./personalrequests";
import personalRequestApprovers from "./personalrequestapprovers";
import personalRequirements from "./personalrequirements";

// SLA Levels
import slaLevel from "./slalevel";

//Offer Requests
import offerRequests from "./offerrequests";

//Creditor Invoices
import creditorInvoices from "./creditorinvoices";

import supportDashboard from "./supportdashboard";

import myTasks from "./mytasks";

import taskBoards from "./taskboards";

import taskStatuses from "./taskstatuses";

import myFeeds from "./MyFeeds";

import storageLocations from "./storagelocations";
import assetEmployeeText from "./assetEmployeeText";

//Server Pools
import serverPools from "./serverpools";

// task comments
import taskComments from "./taskcomments";

// pipelines
import pipelines from "./eventpipelines/pipelines";

// Create a new store instance.
const store = new createStore({
    state: {
        pusher: null,
        vacationCalendarLoader: false,
        isLoading: false,
        showLoader: false,
        errors: {},
        flashMessage: {
            show: false,
            message: "",
            type: "",
            link: "",
        },
        isPublic: false,
        language: "en",
    },
    getters: {
        pusher: (state) => state.pusher,
        errors: (state) => state.errors,
        flashMessage: (state) => state.flashMessage,
        isLoading: (state) => state.isLoading,
        showLoader: (state) => state.showLoader,
        isPublic: (state) => state.isPublic,
        vacationCalendarLoader: (state) => state.vacationCalendarLoader,
        language: (state) => state.language,
    },
    mutations: {
        pusher: (state, payload) => (state.pusher = payload),
        language: (state, payload) => (state.language = payload),
        errors: (state, payload) => {
            const tempErrors = {};
            for (let key in typeof payload === "object" ? payload : {}) {
                tempErrors[key] =
                    payload[key] instanceof Array
                        ? payload[key]?.[0]
                        : payload[key];
            }
            state.errors = tempErrors;
        },
        flashMessage: (state, payload) => {
            setTimeout(() => {
                state.flashMessage = payload;
                if (payload.show === true) {
                    setTimeout(() => {
                        state.flashMessage = {
                            show: false,
                            message: "",
                            type: "",
                            link: "",
                        };
                    }, 10000);
                }
            }, 1);
        },
        isLoading: (state, payload) => (state.isLoading = payload),
        showLoader: (state, payload) => (state.showLoader = payload),
        vacationCalendarLoader: (state, payload) =>
            (state.vacationCalendarLoader = payload),
        isPublic: (state, payload) => (state.isPublic = payload),
    },
    actions: {
        showResponse({ commit }, { type, message, errors, error, link }) {
            if (type === "success") {
                commit("flashMessage", {
                    show: true,
                    message: message,
                    type: "success",
                    link: link,
                });
            } else if (type === "error") {
                commit("errors", errors, {
                    root: true,
                });
                commit("flashMessage", {
                    show: true,
                    message: message,
                    type: "error",
                    link: link,
                });
                throw new Error(message, { cause: error });
            }
        },
    },
    modules: {
        auth,
        systems,
        products,
        productCategory,
        planningDashboard,
        companies,
        suppliers,
        versions,
        groups,
        roles,
        slaLevelIncidents,
        slaLevelChanges,
        slaServiceTimes,
        slaInfrastructures,
        permissions,
        invoices,
        invoiceTemplates,
        reportCategories,
        contactReports,
        tickets,
        mailRules,
        mailMails,
        mailProfiles,
        partnerTickets,
        ticketComments,
        requestComments,
        timeTrackers,
        projectManagement,
        projects,
        milestones,
        tasks,
        companyEmployees,
        surveys,
        chapters,
        questions,
        surveyConfiguration,
        documentService,
        eventName,
        serviceLevelAgreements,
        userProfile,
        vacationRequest,
        offers,
        orders,
        termsOfPayment,
        userDepartments,
        userTeams,
        userLocations,
        units,
        softwares,
        operatingSystem,
        leadStatus,
        periods,
        globalSettings,
        hosts,
        eloBusinessSolutions,
        performanceRecords,
        handouts,
        systemCloud,
        permissionGroups,
        databaseCloud,
        distributedFilesystem,
        mailTemplates,
        licenses,
        contactSource,
        fleetCars,
        mileageMonitoring,
        fuelMonitoring,
        costMonitoring,
        fuelReceipts,
        fleetDrivers,
        fleetLicenceCheck,
        intervalSettings,
        travelExpense,
        travelExpenseRequestType,
        travelExpenseInvoiceType,
        workshopTemplates,
        workshopTemplateCards,
        workshopTemplateRows,
        workshopTemplateColumns,
        workshopTemplateWidgets,
        customerPortalNews,
        apiKeys,
        rules,
        licensingLicenses,
        skills,
        skillGroup,
        skillsMatrix,
        skillLevels,
        jobLevel,
        userJobs,
        formOfContract,
        orderConfirmation,
        countries,
        affectedGroups,
        continuousImprovementProcess,
        projectProtocols,
        projectStatuses,
        protocolTypes,
        projectProtocolEntries,
        invoicereminder,
        highestEducationLevels,
        highestSchoolDegrees,
        mailWebhooks,
        ams,
        openposts,
        consultingDashboard,
        productprice,
        assets,
        saleDashboard,
        changelog,
        contractTypes,
        attachments,
        outboundedContracts,
        inboundedContracts,
        assetArticle,
        assetList,
        timeChecking,
        travelExpenseBills,
        travelExpensePrivateTransportations,
        travelExpenseDaySpecifications,
        personalModificationProcesses,
        changeProcesses,
        personalModificationProcessManagers,
        personalModificationProcessChecklists,
        personalRequests,
        personalRequestApprovers,
        personalRequirements,
        slaLevel,
        offerRequests,
        creditorInvoices,
        supportDashboard,
        globalNotification,
        requests,
        cancelRequests,
        storeEntries,
        myTasks,
        taskBoards,
        taskStatuses,
        myFeeds,
        storageLocations,
        assetEmployeeText,
        assetDelivery,
        serverPools,
        taskComments,
        pipelines,
        employeeInterview,
    },
});

store.$apiService = apiService;
store.$authApiService = authApiService;
store.$docApiService = docApiService;
store.$licenseApiService = licenseApiService;
store.$mailDepotApiService = mailDepotApiService;
store.$mailApiService = mailApiService;
store.$clientShellApiService = ClientShellApiService;
store.$dhc2ApiService = dhc2ApiService;
export default store;

export default {
    namespaced: true,
    state: {
        companies: {
            data: [],
            links: [],
        },
        Partnercompanies: {
            data: [],
            links: [],
        },
        leads: {
            data: [],
            links: [],
        },
        partners: {
            data: [],
            links: [],
        },
        count: 0,
        leadCount: 0,
        partnerCount: 0,
        totalPartnerCompanies: 0,
    },
    getters: {
        companies: (state) => state.companies,
        Partnercompanies: (state) => state.Partnercompanies,
        leads: (state) => state.leads,
        partners: (state) => state.partners,
        count: (state) => state.count,
        leadCount: (state) => state.leadCount,
        partnerCount: (state) => state.partnerCount,
    },
    mutations: {
        companies: (state, payload) => (state.companies = payload),
        Partnercompanies: (state, payload) =>
            (state.Partnercompanies = payload),
        leads: (state, payload) => (state.leads = payload),
        partners: (state, payload) => (state.partners = payload),
        countPartnerCompanies: (state, payload) =>
            (state.totalPartnerCompanies = payload),
        count: (state, payload) => (state.count = payload),
        leadCount: (state, payload) => (state.leadCount = payload),
        partnerCount: (state, payload) => (state.partnerCount = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/companies", {
                    params: queryParams,
                })
                .then((res) => {
                    if (queryParams?.customerType === "lead") {
                        commit("leads", res.data);
                        commit("leadCount", res.data?.total ?? 0);
                    } else if (queryParams?.customerType === "partner") {
                        commit("partners", res.data);
                        commit("partnerCount", res.data?.total ?? 0);
                    } else {
                        commit("companies", res.data);
                        commit("count", res.data?.total ?? 0);
                    }
                    return res;
                });
        },
        PartnerCompanylist({ commit }, queryParams) {
            return this.$apiService
                .get("/partner/customer", {
                    params: queryParams,
                })
                .then((res) => {
                    if (queryParams?.customerType === "lead") {
                        commit("leads", res.data);
                        commit("leadCount", res.data?.total ?? 0);
                    } else if (queryParams?.customerType === "partner") {
                        commit("partners", res.data);
                        commit("partnerCount", res.data?.total ?? 0);
                    } else {
                        commit("Partnercompanies", res.data);
                        commit("countPartnerCompanies", res.data?.total ?? 0);
                    }
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post("/companies", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/companies/${id}`);
        },
        showPartnerCompany({}, id) {
            return this.$apiService.get(`/partner/customer/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/companies/${id}`, data);
        },
        updatePartnerCompany({}, { id, data }) {
            return this.$apiService.put(`/partner/customer/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/companies/${id}`);
        },
        partnerCompanyDestroy({}, id) {
            return this.$apiService.delete(`/partner/customer/${id}`);
        },
        employeesList({}, id) {
            return this.$apiService.get(`/company-employees/company/${id}`);
        },
        employeesCreate({}, payload) {
            return this.$apiService.post("/company-employees", payload);
        },
        employeesUpdate({}, { id, data }) {
            return this.$apiService.put(`/company-employees/${id}`, data);
        },
        employeesDelete({}, id) {
            return this.$apiService.delete(`/company-employees/${id}`);
        },
        locationsList({}, id) {
            return this.$apiService.get(`/company-locations/company/${id}`);
        },
        locationsCreate({}, payload) {
            return this.$apiService.post("/company-locations", payload);
        },
        locationsUpdate({}, { id, data }) {
            return this.$apiService.put(`/company-locations/${id}`, data);
        },
        locationsDelete({}, id) {
            return this.$apiService.delete(`/company-locations/${id}`);
        },
        saveZammadUsers({}, { id, data }) {
            return this.$apiService.put(
                `/company-employees/${id}/assign`,
                data
            );
        },
        systemsByCompany({}, queryParams) {
            return this.$apiService.get(`/systems/company`, {
                params: queryParams,
            });
        },
        invoicesByCompany({}, queryParams) {
            return this.$apiService.get(`/invoices/company`, {
                params: queryParams,
            });
        },
        reportsByCompany({}, queryParams) {
            return this.$apiService.get(`/contact-reports/company`, {
                params: queryParams,
            });
        },
        projectsByCompany({}, queryParams) {
            return this.$apiService.get(
                `/project-management/projects/company`,
                {
                    params: queryParams,
                }
            );
        },
        ticketsByCompany({}, queryParams) {
            return this.$apiService.get(`/company/tickets`, {
                params: queryParams,
            });
        },
        contractsByCompany({}, queryParams) {
            return this.$apiService.get(`/contracts/company`, {
                params: queryParams,
            });
        },
        //download csv for companies
        download({}, queryParams) {
            return this.$apiService
                .get(`/companies/download-csv`, {
                    params: queryParams,
                })
                .then((response) => {
                    // Get the data from the response
                    const data = response.data;

                    // Create a Blob from the data
                    const blob = new Blob([data], { type: "text/csv" });

                    // Create a URL for the file
                    const url = window.URL.createObjectURL(blob);

                    // Create a link element
                    const link = document.createElement("a");
                    link.href = url;
                    link.setAttribute(
                        "download",
                        queryParams.type === "lead"
                            ? "leads.csv"
                            : queryParams.type === "partner"
                            ? "partners.csv"
                            : "customers.csv"
                    );

                    // Add the link to the DOM and click it to initiate the download
                    document.body.appendChild(link);
                    link.click();
                });
        },
        downloadLatestCsv({}, queryParams) {
            return this.$apiService
                .get(`/companies/download-latest-csv`, {
                    params: queryParams,
                })
                .then((response) => {
                    // Get the data from the response
                    const data = response.data;

                    // Create a Blob from the data
                    const blob = new Blob([data], { type: "text/csv" });

                    // Create a URL for the file
                    const url = window.URL.createObjectURL(blob);

                    // Create a link element
                    const link = document.createElement("a");
                    link.href = url;
                    link.setAttribute(
                        "download",
                        queryParams.type === "lead"
                            ? "leads.csv"
                            : queryParams.type === "partner"
                            ? "partners.csv"
                            : "customers.csv"
                    );

                    // Add the link to the DOM and click it to initiate the download
                    document.body.appendChild(link);
                    link.click();
                });
        },
        partnerCompanies({}, queryParams) {
            return this.$apiService.get(`/partner-companies`, {
                params: queryParams,
            });
        },
    },
};

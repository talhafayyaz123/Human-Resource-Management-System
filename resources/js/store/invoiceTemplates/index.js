export default {
    namespaced: true,
    state: {
        invoiceTemplates: {
            data: [],
            links: [],
        },
        totalSumOfInvoice: 0
        // invoiceTemplatesTypes: [], not coming from backend
    },
    getters: {
        invoiceTemplates: (state) => state.invoiceTemplates,
        totalSumOfInvoice: (state) => state.totalSumOfInvoice,
        // invoiceTemplatesTypes: (state) => state.invoiceTemplatesTypes,
    },
    mutations: {
        invoiceTemplates: (state, payload) => (state.invoiceTemplates = payload),
        totalSumOfInvoice: (state, payload) => (state.totalSumOfInvoice = payload),
        // invoiceTemplatesTypes: (state, payload) => (state.invoiceTemplatesTypes = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/invoice-templates", { params: queryParams })
                .then((res) => {
                    commit(
                        "invoiceTemplates",
                        res?.data?.invoiceTemplates ?? { data: [], links: [] }
                    );
                    commit(
                        "totalSumOfInvoice",
                        res?.data?.totalSum ?? 0
                    );
                    // commit("invoiceTemplatesTypes", res?.data?.invoiceTypes ?? []);
                });
        },
        create({}, payload) {
            return this.$apiService.post("/invoice-templates", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/invoice-templates/${id}`);
        },
        invoiceCreate({}, id) {
            return this.$apiService.get(`/invoice-templates/create-invoice/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/invoice-templates/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/invoice-templates/${id}`);
        },
        fetchData({}) {
            return this.$apiService.get("/invoice-templates/create");
        },

        // These below needs to be removed in case of invoice templates
        //download csv for companies
        download({}) {
            return this.$apiService
                .get(`/invoice-templates/download/csv`)
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
                    link.setAttribute("download", "invoice.csv");

                    // Add the link to the DOM and click it to initiate the download
                    document.body.appendChild(link);
                    link.click();
                });
        },
        downloadLatestCSV({}) {
            return this.$apiService
                .get(`/invoices/download/latest-csv`)
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
                    link.setAttribute("download", "invoice.csv");

                    // Add the link to the DOM and click it to initiate the download
                    document.body.appendChild(link);
                    link.click();
                });
        },
        downloadGeneratedInvoice({}, id){
            return this.$apiService.get(`/invoice-templates/document-generation/${id}`);
        },
        invoiceStatistics({}, payload) {
            return this.$apiService.post("/invoice-templates/statistics", payload);
        },
        updateStatus({}, payload) {
            return this.$apiService.patch(
                `/update-invoice-templates-status/${payload.id}`,
                payload.data
            );
        },
        eloRequestData({}, id) {
            return this.$apiService.get(`/invoice-templates/elo-request-data/${id}`);
        },
    },
};

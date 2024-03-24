export default {
    namespaced: true,
    state: {
        invoices: {
            data: [],
            links: [],
        },
        invoiceTypes: [],
    },
    getters: {
        invoices: (state) => state.invoices,
        invoiceTypes: (state) => state.invoiceTypes,
    },
    mutations: {
        invoices: (state, payload) => (state.invoices = payload),
        invoiceTypes: (state, payload) => (state.invoiceTypes = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/invoices", { params: queryParams })
                .then((res) => {
                    commit(
                        "invoices",
                        res?.data?.invoices ?? { data: [], links: [] }
                    );
                    commit("invoiceTypes", res?.data?.invoiceTypes ?? []);
                });
        },
        create({}, payload) {
            return this.$apiService.post("/invoices", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/invoices/${id}`);
        },
        referenceInvoices({}, id) {
            return this.$apiService.get(`/reference/invoices/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/invoices/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/invoices/${id}`);
        },
        fetchData({}) {
            return this.$apiService.get("/invoices/create");
        },
        //download csv for companies
        download({}) {
            return this.$apiService
                .get(`/invoices/download/csv`)
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
        downloadGeneratedInvoice({}, id) {
            return this.$apiService.get(`/invoices/document-generation/${id}`);
        },
        invoiceStatistics({}, payload) {
            return this.$apiService.post("/invoices/statistics", payload);
        },
        updateStatus({}, payload) {
            return this.$apiService.patch(
                `/update-invoice-status/${payload.id}`,
                payload.data
            );
        },
        eloRequestData({}, id) {
            return this.$apiService.get(`/invoices/elo-request-data/${id}`);
        },
    },
};

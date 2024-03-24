export default {
    namespaced: true,
    state: {
        suppliers: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        suppliers: (state) => state.suppliers,
        count: (state) => state.count,
    },
    mutations: {
        suppliers: (state, payload) => (state.suppliers = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/suppliers", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("suppliers", res.data);
                    commit("count", res.data?.total ?? 0);
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post("/suppliers", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/suppliers/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/suppliers/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/suppliers/${id}`);
        },
        locationsList({}, id) {
            return this.$apiService.get(`/supplier-locations/supplier/${id}`);
        },
        locationsCreate({}, payload) {
            return this.$apiService.post("/supplier-locations", payload);
        },
        locationsUpdate({}, { id, data }) {
            return this.$apiService.put(`/supplier-locations/${id}`, data);
        },
        locationsDelete({}, id) {
            return this.$apiService.delete(`/supplier-locations/${id}`);
        },
        //download csv for companies
        download() {
            return this.$apiService
                .get(`/suppliers/download-csv`)
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
                    link.setAttribute("download", "suppliers.csv");

                    // Add the link to the DOM and click it to initiate the download
                    document.body.appendChild(link);
                    link.click();
                });
        },
        downloadLatestCsv() {
            return this.$apiService
                .get(`/suppliers/download-latest-csv`)
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
                    link.setAttribute("download", "suppliers.csv");

                    // Add the link to the DOM and click it to initiate the download
                    document.body.appendChild(link);
                    link.click();
                });
        },
    },
};

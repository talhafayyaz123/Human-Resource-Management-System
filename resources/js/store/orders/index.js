export default {
    namespaced: true,
    state: {
        orders: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        orders: (state) => state.orders,
        count: (state) => state.count,
    },
    mutations: {
        orders: (state, payload) => (state.orders = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/partner/order", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "orders",
                        res?.data?.partnerOrder?.data ?? {
                            data: [],
                            links: [],
                        }
                    );
                    commit(
                        "count",
                        res?.data?.partnerOrder?.data?.meta?.total ?? 0
                    );
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post("/partner/order", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/partner/order/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/partner/order/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/partner/order/${id}`);
        },
        // getOffersByCustomer({}, queryParams) {
        //     return this.$apiService.get(`/sale-offers-by-customer`, {
        //         params: queryParams,
        //     });
        // },
        // //download csv for companies
        // download() {
        //     return this.$apiService
        //         .get(`/sale-offers/download-csv`)
        //         .then((response) => {
        //             // Get the data from the response
        //             const data = response.data;

        //             // Create a Blob from the data
        //             const blob = new Blob([data], { type: "text/csv" });

        //             // Create a URL for the file
        //             const url = window.URL.createObjectURL(blob);

        //             // Create a link element
        //             const link = document.createElement("a");
        //             link.href = url;
        //             link.setAttribute("download", "offers.csv");

        //             // Add the link to the DOM and click it to initiate the download
        //             document.body.appendChild(link);
        //             link.click();
        //         });
        // },
        // downloadLatestCsv() {
        //     return this.$apiService
        //         .get(`/sale-offers/download-latest-csv`)
        //         .then((response) => {
        //             // Get the data from the response
        //             const data = response.data;

        //             // Create a Blob from the data
        //             const blob = new Blob([data], { type: "text/csv" });

        //             // Create a URL for the file
        //             const url = window.URL.createObjectURL(blob);

        //             // Create a link element
        //             const link = document.createElement("a");
        //             link.href = url;
        //             link.setAttribute("download", "offers.csv");

        //             // Add the link to the DOM and click it to initiate the download
        //             document.body.appendChild(link);
        //             link.click();
        //         });
        // },
        // downloadGeneratedOffer({}, id) {
        //     return this.$apiService.get(
        //         `/sale-offers/document-generation/${id}`
        //     );
        // },
        // updateStatus({}, { id, data }) {
        //     return this.$apiService.post(
        //         `/sale-offers/update-status/${id}`,
        //         data
        //     );
        // },
        // eloRequestData({}, id) {
        //     return this.$apiService.get(`/sale-offers/elo-request-data/${id}`);
        // },
    },
};

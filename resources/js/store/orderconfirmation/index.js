export default {
    namespaced: true,
    state: {
        orderConfirmation: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        orderConfirmation: (state) => state.orderConfirmation,
        count: (state) => state.count,
    },
    mutations: {
        orderConfirmation: (state, payload) =>
            (state.orderConfirmation = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/order-confirmation", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "orderConfirmation",
                        res?.data ?? {
                            data: [],
                            links: [],
                        }
                    );
                    commit("count", res?.data?.meta?.total ?? 0);
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post("/order-confirmation", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/order-confirmation/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/order-confirmation/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/order-confirmation/${id}`);
        },
        getOrderConfirmationByCustomer({}, queryParams) {
            return this.$apiService.get(
                `/sale-offers-by-customer`, {
                params: queryParams,
            });
        },
        createInvoices({}, id) {
            return this.$apiService.get(
                `/order-confirmation/create-invoices/${id}`
            );
        },
        downloadGeneratedOffer({}, id) {
            return this.$apiService.get(
                `/order-confirmation/document-generation/${id}`
            );
        },
        updateStatus({}, { id, data }) {
            return this.$apiService.post(
                `/order-confirmation/update-status/${id}`,
                data
            );
        },
    },
};

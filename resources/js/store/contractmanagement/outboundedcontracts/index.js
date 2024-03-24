export default {
    namespaced: true,
    state: {
        outboundedContracts: {
            data: [],
            links: [],
        },
    },
    getters: {
        outboundedContracts: (state) => state.outboundedContracts,
    },
    mutations: {
        outboundedContracts: (state, payload) =>
            (state.outboundedContracts = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/outbounded-contracts", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "outboundedContracts",
                        res?.data ?? {
                            data: [],
                            links: [],
                        }
                    );
                });
        },
        create({}, payload) {
            return this.$apiService.post("/outbounded-contracts", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/outbounded-contracts/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/outbounded-contracts/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/outbounded-contracts/${id}`);
        },
        createInvoice({}, id) {
            return this.$apiService.get(
                `/outbounded-contracts/create-invoice/${id}`
            );
        },
        createInvoiceTemplate({}, id) {
            return this.$apiService.get(
                `/outbounded-contracts/create-invoice-template/${id}`
            );
        },
    },
};

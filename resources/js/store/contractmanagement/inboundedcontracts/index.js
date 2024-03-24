export default {
    namespaced: true,
    state: {
        inboundedContracts: {
            data: [],
            links: [],
        },
    },
    getters: {
        inboundedContracts: (state) => state.inboundedContracts,
    },
    mutations: {
        inboundedContracts: (state, payload) =>
            (state.inboundedContracts = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/inbounded-contracts", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "inboundedContracts",
                        res?.data ?? {
                            data: [],
                            links: [],
                        }
                    );
                });
        },
        create({}, payload) {
            return this.$apiService.post("/inbounded-contracts", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/inbounded-contracts/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/inbounded-contracts/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/inbounded-contracts/${id}`);
        },
    },
};

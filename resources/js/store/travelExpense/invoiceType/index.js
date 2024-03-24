export default {
    namespaced: true,
    state: {
        invoiceTypes: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        invoiceTypes: (state) => state.invoiceTypes,
        count: (state) => state.count,
    },
    mutations: {
        invoiceTypes: (state, payload) => (state.invoiceTypes = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/travel-expenses/invoice-types", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("invoiceTypes", res.data);
                    commit("count", res.data?.total ?? 0);
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post(
                "/travel-expenses/invoice-types",
                payload
            );
        },
        show({}, id) {
            return this.$apiService.get(`/travel-expenses/invoice-types/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(
                `/travel-expenses/invoice-types/${id}`,
                data
            );
        },
        destroy({}, id) {
            return this.$apiService.delete(
                `/travel-expenses/invoice-types/${id}`
            );
        },
    },
};

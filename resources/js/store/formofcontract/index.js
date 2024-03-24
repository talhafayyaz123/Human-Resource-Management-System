export default {
    namespaced: true,
    state: {
        formOfContract: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        formOfContract: (state) => state.formOfContract,
        count: (state) => state.count,
    },
    mutations: {
        formOfContract: (state, payload) => (state.formOfContract = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/form-of-contract", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("formOfContract", res.data);
                    commit("count", res.data?.total ?? 0);
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post("/form-of-contract", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/form-of-contract/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/form-of-contract/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/form-of-contract/${id}`);
        },
    },
};

export default {
    namespaced: true,
    state: {
        rules: [],
        count: 0,
    },
    getters: {
        rules: (state) => state.rules,
        count: (state) => state.count,
    },
    mutations: {
        rules: (state, payload) => (state.rules = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$mailDepotApiService
                .get("/list-rules", { params: queryParams })
                .then((res) => {
                    commit("rules", res?.data ?? []);
                    commit("count", res?.data?.count ?? 0);
                    return res;
                });
        },
        create({}, payload) {
            return this.$mailDepotApiService.post(`/create-rule`, payload);
        },
        show({}, id) {
            return this.$mailDepotApiService.get(`list-rule-by-id?id=${id}`);
        },
        update({}, payload) {
            return this.$mailDepotApiService.post(`/update-rule`, payload);
        },
        destroy({}, payload) {
            return this.$mailDepotApiService.post(`/delete-rule`, payload);
        },
    },
};

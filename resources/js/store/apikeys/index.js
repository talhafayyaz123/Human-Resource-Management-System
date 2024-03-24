export default {
    namespaced: true,
    state: {
        apiKeys: [],
        count: 0,
    },
    getters: {
        apiKeys: (state) => state.apiKeys,
        count: (state) => state.count,
    },
    mutations: {
        apiKeys: (state, payload) => (state.apiKeys = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$authApiService
                .get("/list-api-tokens", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("apiKeys", res?.data?.data ?? []);
                    commit("count", res?.data?.count ?? 0);
                    return res;
                });
        },
        show({}, id) {
            return this.$authApiService.get("/list-api-token-by-id", {
                params: {
                    id: id,
                },
            });
        },
        create({}, payload) {
            return this.$authApiService.post("/create-api-token", payload);
        },
        update({}, data) {
            return this.$authApiService.post(`/update-api-token`, data);
        },
        destroy({}, payload) {
            return this.$authApiService.post(`/delete-api-token`, payload);
        },
    },
};

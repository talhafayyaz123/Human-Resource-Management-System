export default {
    namespaced: true,
    state: {
        roles: [],
        count: 0,
    },
    getters: {
        roles: (state) => state.roles,
        count: (state) => state.count,
    },
    mutations: {
        roles: (state, payload) => (state.roles = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/roles", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("roles", res?.data?.data ?? []);
                    commit("count", res?.data?.count ?? 0);
                });
        },
        create({}, payload) {
            return this.$authApiService.post("/create-role", payload);
        },
        show({}, queryParams) {
            return this.$authApiService.get(`/list-role-by-id`, {
                params: queryParams,
            });
        },
        update({}, data) {
            return this.$authApiService.post(`/update-role`, data);
        },
        destroy({}, payload) {
            return this.$authApiService.post(`/delete-role`, payload);
        },
    },
};

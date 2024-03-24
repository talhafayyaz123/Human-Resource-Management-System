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
        list({ commit }) {
            return this.$apiService.get("/rules").then((res) => {
                commit("rules", res?.data?.data ?? []);
                commit("count", res?.data?.count ?? 0);
                return res;
            });
        },
        create({}, payload) {
            return this.$licenseApiService.post(`/create-rule`, payload);
        },
        show({}, id) {
            return this.$apiService.get(`/rules/${id}`);
        },
        update({}, payload) {
            return this.$licenseApiService.post(`/update-rule`, payload);
        },
        destroy({}, payload) {
            return this.$licenseApiService.post(`/delete-rule`, payload);
        },
    },
};

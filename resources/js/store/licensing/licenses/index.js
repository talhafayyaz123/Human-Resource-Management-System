export default {
    namespaced: true,
    state: {
        licenses: [],
        count: 0,
    },
    getters: {
        licenses: (state) => state.licenses,
        count: (state) => state.count,
    },
    mutations: {
        licenses: (state, payload) => (state.licenses = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }) {
            return this.$apiService.get("/licensing/licenses").then((res) => {
                commit("licenses", res?.data?.data ?? []);
                commit("count", res?.data?.count ?? 0);
                return res;
            });
        },
        create({}, payload) {
            return this.$licenseApiService.post(`/create-license`, payload);
        },
        show({}, id) {
            return this.$apiService.get(`/licensing/licenses/${id}`);
        },
        update({}, payload) {
            return this.$licenseApiService.post(`/update-license`, payload);
        },
        destroy({}, payload) {
            return this.$licenseApiService.post(`/delete-license`, payload);
        },
        usageCount({}, payload) {
            return this.$licenseApiService.post("/list-usage-count", payload);
        },
    },
};

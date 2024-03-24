export default {
    namespaced: true,
    state: {
        profiles: [],
        count: 0,
    },
    getters: {
        profiles: (state) => state.profiles,
        count: (state) => state.count,
    },
    mutations: {
        profiles: (state, payload) => (state.profiles = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$mailDepotApiService
                .get("/list-profiles", { params: queryParams })
                .then((res) => {
                    commit("profiles", res?.data ?? []);
                    commit("count", res?.data?.count ?? 0);
                    return res;
                });
        },
        create({}, payload) {
            return this.$mailDepotApiService.post(`/create-profile`, payload);
        },
        show({}, id) {
            return this.$mailDepotApiService.get(`/list-profile-by-id?id=${id}`
            );
        },
        update({}, payload) {
            return this.$mailDepotApiService.post(`/update-profile`, payload);
        },
        destroy({}, payload) {
            return this.$mailDepotApiService.post(`/delete-profile`, payload);
        },
    },
};

export default {
    namespaced: true,
    state: {
        locations: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        locations: (state) => state.locations,
        count: (state) => state.count,
    },
    mutations: {
        locations: (state, payload) => (state.locations = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/user/locations", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("locations", res.data);
                    commit("count", res.data?.total ?? 0);
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post("/user/locations", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/user/locations/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/user/locations/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/user/locations/${id}`);
        },
    },
};

export default {
    namespaced: true,
    state: {
        changelog: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        changelogs: (state) => state.changelogs,
    },
    mutations: {
        changelogs: (state, payload) => (state.changelogs = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/changelogs", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("changelogs", res.data);
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post("/changelogs", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/changelogs/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/changelogs/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/changelogs/${id}`);
        },
    },
};

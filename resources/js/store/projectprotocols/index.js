export default {
    namespaced: true,
    state: {
        projectProtocols: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        projectProtocols: (state) => state.projectProtocols,
        count: (state) => state.count,
    },
    mutations: {
        projectProtocols: (state, payload) => (state.projectProtocols = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/project-protocols", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "projectProtocols",
                        res?.data ?? {
                            data: [],
                            links: [],
                        }
                    );
                    commit("count", res?.data?.total ?? 0);
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post("/project-protocols", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/project-protocols/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/project-protocols/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/project-protocols/${id}`);
        },
    },
};

export default {
    namespaced: true,
    state: {
        projectStatuses: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        projectStatuses: (state) => state.projectStatuses,
        count: (state) => state.count,
    },
    mutations: {
        projectStatuses: (state, payload) => (state.projectStatuses = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/project-statuses", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "projectStatuses",
                        res?.data?.data ?? {
                            data: [],
                            links: [],
                        }
                    );
                    commit("count", res?.data?.data?.total ?? 0);
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post("/project-statuses", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/project-statuses/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/project-statuses/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/project-statuses/${id}`);
        },
    },
};

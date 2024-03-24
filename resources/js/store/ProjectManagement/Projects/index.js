export default {
    namespaced: true,
    state: {
        projects: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        projects: (state) => state.projects,
        count: (state) => state.count,
    },
    mutations: {
        projects: (state, payload) => (state.projects = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/project-management/projects", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("projects", res.data);
                    commit("count", res.data?.total ?? 0);
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post(
                "/project-management/projects",
                payload
            );
        },
        show({}, id) {
            return this.$apiService.get(`/project-management/projects/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(
                `/project-management/projects/${id}`,
                data
            );
        },
        destroy({}, id) {
            return this.$apiService.delete(
                `/project-management/projects/${id}`
            );
        },
        offerConfirmationWithProjects({}, id) {
            return this.$apiService.get(`/order-confirmation-with-id/${id}`);
        },
    },
};

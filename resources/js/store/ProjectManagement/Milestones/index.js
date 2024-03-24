export default {
    namespaced: true,
    state: {
        milestones: {
            data: [],
            links: [],
        },
    },
    getters: {
        milestones: (state) => state.milestones,
    },
    mutations: {
        milestones: (state, payload) => (state.milestones = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService.get("/project-management/milestones", {
                params: queryParams,
            });
        },
        create({}, payload) {
            return this.$apiService.post(
                "/project-management/milestones",
                payload
            );
        },
        show({}, id) {
            return this.$apiService.get(`/project-management/milestones/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(
                `/project-management/milestones/${id}`,
                data
            );
        },
        destroy({}, id) {
            return this.$apiService.delete(
                `/project-management/milestones/${id}`
            );
        },
        milestonesByProject({ commit }, id) {
            return this.$apiService
                .get(`/project-management/milestones/by-project/${id}`)
                .then((res) => {
                    commit(
                        "milestones",
                        res?.data?.milestones ?? {
                            data: [],
                            links: [],
                        }
                    );
                });
        },
    },
};

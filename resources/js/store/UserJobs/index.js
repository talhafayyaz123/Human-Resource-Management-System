export default {
    namespaced: true,
    state: {
        jobs: {
            data: [],
            links: [],
        },
    },
    getters: {
        jobs: (state) => state.jobs,
    },
    mutations: {
        jobs: (state, payload) => (state.jobs = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/jobs", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "jobs",
                        res?.data?.jobs ?? {
                            data: [],
                            links: [],
                        }
                    );
                });
        },
        create({}, payload) {
            return this.$apiService.post("/jobs", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/jobs/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/jobs/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/jobs/${id}`);
        },
    },
};

export default {
    namespaced: true,
    state: {
        pipelines: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        pipelines: (state) => state.pipelines,
        count: (state) => state.count,
    },
    mutations: {
        pipelines: (state, payload) => (state.pipelines = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/pipelines", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "pipelines",
                        res?.data?.data ?? {
                            data: [],
                            links: [],
                        }
                    );
                    commit("count", res?.data?.total ?? 0);
                });
        },
        create({}, payload) {
            return this.$apiService.post("/pipelines", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/pipelines/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/pipelines/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/pipelines/${id}`);
        },
    },
};

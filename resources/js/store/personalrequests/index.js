export default {
    namespaced: true,
    state: {
        requests: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        requests: (state) => state.requests,
    },
    mutations: {
        requests: (state, payload) => (state.requests = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/personal-requests", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "requests",
                        res?.data ?? {
                            data: [],
                            links: [],
                        }
                    );
                    commit("count", res?.data?.total ?? 0);
                });
        },
        create({}, payload) {
            return this.$apiService.post("/personal-requests", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/personal-requests/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/personal-requests/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/personal-requests/${id}`);
        },
    },
};

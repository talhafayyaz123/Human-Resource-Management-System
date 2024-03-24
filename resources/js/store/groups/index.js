export default {
    namespaced: true,
    state: {
        groups: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        groups: (state) => state.groups,
        count: (state) => state.count,
    },
    mutations: {
        groups: (state, payload) => (state.groups = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/groups", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "groups",
                        res?.data?.groups ?? {
                            data: [],
                            links: [],
                        }
                    );
                    commit("count", res?.data?.groups?.total ?? 0);
                });
        },
        create({}, payload) {
            return this.$apiService.post("/groups", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/groups/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/groups/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/groups/${id}`);
        },
    },
};

export default {
    namespaced: true,
    state: {
        affectedGroups: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        affectedGroups: (state) => state.affectedGroups,
        count: (state) => state.count,
    },
    mutations: {
        affectedGroups: (state, payload) => (state.affectedGroups = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/affected-groups", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "affectedGroups",
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
            return this.$apiService.post("/affected-groups", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/affected-groups/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/affected-groups/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/affected-groups/${id}`);
        },
    },
};

export default {
    namespaced: true,
    state: {
        skillLevel: {
            data: [],
            links: [],
        },
    },
    getters: {
        skillLevel: (state) => state.skillLevel,
    },
    mutations: {
        skillLevel: (state, payload) => (state.skillLevel = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/skill-level", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "skillLevel",
                        res?.data?.skillLevel ?? {
                            data: [],
                            links: [],
                        }
                    );
                });
        },
        create({}, payload) {
            return this.$apiService.post("/skill-level", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/skill-level/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/skill-level/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/skill-level/${id}`);
        },
    },
};

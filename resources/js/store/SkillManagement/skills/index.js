export default {
    namespaced: true,
    state: {
        skills: {
            data: [],
            links: [],
        },
    },
    getters: {
        skills: (state) => state.skills,
    },
    mutations: {
        skills: (state, payload) => (state.skills = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/skills", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "skills",
                        res?.data?.skills ?? {
                            data: [],
                            links: [],
                        }
                    );
                });
        },
        create({}, payload) {
            return this.$apiService.post("/skills", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/skills/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/skills/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/skills/${id}`);
        },
    },
};

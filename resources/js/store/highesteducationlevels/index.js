export default {
    namespaced: true,
    state: {
        highestEducationLevels: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        highestEducationLevels: (state) => state.highestEducationLevels,
        count: (state) => state.count,
    },
    mutations: {
        highestEducationLevels: (state, payload) => (state.highestEducationLevels = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/highest-education-levels", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "highestEducationLevels",
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
            return this.$apiService.post("/highest-education-levels", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/highest-education-levels/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/highest-education-levels/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/highest-education-levels/${id}`);
        },
    },
};

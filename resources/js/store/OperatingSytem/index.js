export default {
    namespaced: true,
    state: {
        operatingSystem: {
            data: [],
            links: [],
        },
    },
    getters: {
        operatingSystem: (state) => state.operatingSystem,
    },
    mutations: {
        operatingSystem: (state, payload) => (state.operatingSystem = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/operating-system", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "operatingSystem",
                        res?.data?.operatingSystem ?? {
                            data: [],
                            links: [],
                        }
                    );
                });
        },
        create({}, payload) {
            return this.$apiService.post("/operating-system", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/operating-system/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/operating-system/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/operating-system/${id}`);
        },
    },
};

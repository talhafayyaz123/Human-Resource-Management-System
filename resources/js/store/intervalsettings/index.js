export default {
    namespaced: true,
    state: {
        intervalSettings: {
            data: [],
            links: [],
        },
    },
    getters: {
        intervalSettings: (state) => state.intervalSettings,
    },
    mutations: {
        intervalSettings: (state, payload) =>
            (state.intervalSettings = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/interval-settings", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "intervalSettings",
                        res?.data?.intervalSettings ?? {
                            data: [],
                            links: [],
                        }
                    );
                });
        },
        create({}, payload) {
            return this.$apiService.post("/interval-settings", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/interval-settings/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/interval-settings/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/interval-settings/${id}`);
        },
    },
};

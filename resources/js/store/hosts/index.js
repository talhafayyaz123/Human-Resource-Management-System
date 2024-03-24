export default {
    namespaced: true,
    state: {
        hosts: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        hosts: (state) => state.hosts,
        count: (state) => state.count,
    },
    mutations: {
        hosts: (state, payload) => (state.hosts = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/hosts", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "hosts",
                        res?.data?.hosts ?? {
                            data: [],
                            links: [],
                        }
                    );
                    commit(
                        "count",
                        res?.data?.hosts?.total ?? 0
                    );
                    return res?.data?.hosts;
                });
        },
        create({}, payload) {
            return this.$apiService.post("/hosts", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/hosts/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/hosts/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/hosts/${id}`);
        },
    },
};

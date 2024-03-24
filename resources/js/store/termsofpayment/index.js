export default {
    namespaced: true,
    state: {
        termsOfPayment: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        termsOfPayment: (state) => state.termsOfPayment,
        count: (state) => state.count,
    },
    mutations: {
        termsOfPayment: (state, payload) => (state.termsOfPayment = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/terms-of-payment", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "termsOfPayment",
                        res?.data ?? {
                            data: [],
                            links: [],
                        }
                    );
                    commit(
                        "count",
                        res?.data?.terms?.total ?? 0
                    );
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post("/terms-of-payment", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/terms-of-payment/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/terms-of-payment/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/terms-of-payment/${id}`);
        },
    },
};

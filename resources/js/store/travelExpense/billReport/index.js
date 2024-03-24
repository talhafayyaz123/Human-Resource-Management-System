export default {
    namespaced: true,
    state: {
        bills: [],
    },
    getters: {
        bills: (state) => state.bills,
    },
    mutations: {
        bills: (state, payload) => (state.bills = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/travel-expense/bill-report", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "bills",
                        res?.data ?? []
                    );
                    return res?.data;
                });
        },
        create({}, payload) {
            return this.$apiService.post(
                "/travel-expense/bill-report",
                payload,
                {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                }
            );
        },
        show({}, id) {
            return this.$apiService.get(`/travel-expense/bill-report/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.post(
                `/travel-expense/bill-report/${id}`,
                data,
                {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                }
            );
        },
        destroy({}, id) {
            return this.$apiService.delete(`/travel-expense/bill-report/${id}`);
        },
    },
};

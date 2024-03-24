export default {
    namespaced: true,
    state: {
        travelExpenses: {
            data: [],
            links: [],
        },
        count: 0,
        travelExpensesApprove: {
            data: [],
            links: [],
        },
        countApprove: 0,
    },
    getters: {
        travelExpenses: (state) => state.travelExpenses,
        count: (state) => state.count,
        travelExpensesApprove: (state) => state.travelExpensesApprove,
        countApprove: (state) => state.countApprove,
    },
    mutations: {
        travelExpenses: (state, payload) => (state.travelExpenses = payload),
        count: (state, payload) => (state.count = payload),
        travelExpensesApprove: (state, payload) =>
            (state.travelExpensesApprove = payload),
        countApprove: (state, payload) => (state.countApprove = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/travel-expenses", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("travelExpenses", res.data);
                    commit("count", res.data?.total ?? 0);
                    return res;
                });
        },
        approveList({ commit }, queryParams) {
            return this.$apiService
                .get("/travel-expenses/approve", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("travelExpensesApprove", res.data);
                    commit("countApprove", res.data?.total ?? 0);
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post("/travel-expenses", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/travel-expenses/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/travel-expenses/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/travel-expenses/${id}`);
        },
        sendForApproval({}, id) {
            return this.$apiService.get(
                `/travel-expenses/send-for-approval/${id}`
            );
        },
        setApprovalStatus({}, payload) {
            return this.$apiService.post(
                "/travel-expenses/set-approval-status",
                payload
            );
        },
    },
};

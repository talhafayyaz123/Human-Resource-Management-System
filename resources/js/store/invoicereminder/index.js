export default {
    namespaced: true,
    state: {
        invoiceReminder: {
            data: [],
            links: [],
        },
    },
    getters: {
        invoiceReminder: (state) => state.invoiceReminder,
    },
    mutations: {
        invoiceReminder: (state, payload) => (state.invoiceReminder = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/invoice-reminder-level", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "invoiceReminder",
                        res?.data ?? {
                            data: [],
                            links: [],
                        }
                    );
                });
        },
        create({}, payload) {
            return this.$apiService.post("/invoice-reminder-level", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/invoice-reminder-level/${id}`);
        },
        showByLevelName({}, queryParams) {
            return this.$apiService.get(
                `/invoice-reminder-level/get-by-level-name`,
                {
                    params: queryParams,
                }
            );
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/invoice-reminder-level/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/invoice-reminder-level/${id}`);
        },
    },
};

export default {
    namespaced: true,
    state: {
        fuelReceipts: {
            data: [],
            links: [],
        },
    },

    actions: {
        create({}, payload) {
            return this.$apiService.post("/fuel-receipts", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/fuel-receipts/${id}`);
        },
    },
};

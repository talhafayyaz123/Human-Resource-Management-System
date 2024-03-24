export default {
    namespaced: true,
    actions: {
        create({}, payload) {
            return this.$apiService.post("/driver-licence-check", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/driver-licence-check/${id}`);
        },
    },
};

export default {
    namespaced: true,
    actions: {
        managers() {
            return this.$apiService.get(
                "/personal-modification-process-managers"
            );
        },
        save({}, payload) {
            return this.$apiService.post(
                "/personal-modification-process-managers",
                payload
            );
        },
    },
};

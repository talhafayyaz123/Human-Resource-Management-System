export default {
    namespaced: true,
    actions: {
        supportDashboard({}, queryParams) {
            return this.$apiService.get("/ticket-dashboard", {
                params: queryParams,
            });
        },
        supportDashboardAms({}, queryParams) {
            return this.$apiService.get("/application-management-services/support/dashboard", {
                params: queryParams,
            });
        },
    },
};

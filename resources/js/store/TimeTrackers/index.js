export default {
    namespaced: true,
    state: {},
    getters: {},
    mutations: {},
    actions: {
        date({}, params) {
            return this.$apiService.get(`/time-trackers`, {
                params: params,
            });
        },
        overView({}, params) {
            return this.$apiService.get(`/time-trackers/overview`, {
                params: params,
            });
        },
        overtime({}, payload) {
            return this.$apiService.post(`/time-trackers/overtime`, payload);
        },
        overViewReporting({}, params) {
            return this.$apiService.get(`/time-trackers/overview/reporting`, {
                params: params,
            });
        },
        save({}, data) {
            return this.$apiService.post(`/time-tracker`, data);
        },
        removeTimeTrackerGovernment({}, id) {
            return this.$apiService.delete(`/tracker-government/${id}`);
        },
        removeTimeTrackerCompany({}, id) {
            return this.$apiService.delete(`/tracker-company/${id}`);
        },
        validateUserProfile({}, data) {
            return this.$apiService.post(
                "/time-tracker/validate-profile",
                data
            );
        },
        removeComment({}, data) {
            return this.$apiService.post("/remove-ticket-comment", data);
        },
        updateGovernment({}, { data, id }) {
            return this.$apiService.patch(
                `/time-tracker-government/${id}`,
                data
            );
        },
        updateCompany({}, payload) {
            return this.$apiService.patch(
                `/time-tracker-company/${payload.id}`,
                payload
            );
        },
        timeTrackerStatistics({}, payload) {
            return this.$apiService.post(`/time-trackers/statistics`, payload);
        },
        timeTrackerMonthlyForecast({}, queryParams) {
            return this.$apiService.get(`/time-trackers/monthly-forecast`, {
                params: queryParams,
            });
        },
        exportTimeTrackerData({}, params) {
            return this.$apiService
                .get(`/time-trackers/export-user-data`, {
                    responseType: "blob", // Important for handling binary data
                    params: params,
                })
                .then((response) => {
                    const fileName =
                        response.headers["content-disposition"].split(
                            "filename="
                        )[1];
                    const blob = new Blob([response.data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                    });
                    const link = document.createElement("a");
                    link.href = window.URL.createObjectURL(blob);
                    link.download = fileName;
                    link.click();
                });
        },
    },
};

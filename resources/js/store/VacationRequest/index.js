export default {
    namespaced: true,
    state: {
        vacationRequests: {
            data: [],
            links: [],
        },
        requestsForApproval: {
            data: [],
            links: [],
        },
        vacationCalendar: {
            data: [],
            links: [],
        },
        count: 0,
        userPerPage: 0,
    },
    getters: {
        vacationRequests: (state) => state.vacationRequests,
        requestsForApproval: (state) => state.requestsForApproval,
        count: (state) => state.count,
        userPerPage: (state) => state.userPerPage,
        vacationCalendar: (state) => state.vacationCalendar,
    },
    mutations: {
        vacationRequests: (state, payload) =>
            (state.vacationRequests = payload),
        requestsForApproval: (state, payload) =>
            (state.requestsForApproval = payload),
        count: (state, payload) => (state.count = payload),
        userPerPage: (state, payload) => (state.userPerPage = payload),
        vacationCalendar: (state, payload) =>
            (state.vacationCalendar = payload),
        vacationCalendar: (state, payload) =>
            (state.vacationCalendar = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/employee-vacation", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("vacationRequests", res.data);
                    commit("count", res.data?.total ?? 0);
                });
        },
        requestsForApproval({ commit }, queryParams) {
            return this.$apiService
                .get("/employee-vacation/requests-for-approval", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("requestsForApproval", res.data);
                });
        },
        setApprovalStatus({}, payload) {
            return this.$apiService.post(
                "/employee-vacation/set-approval-status",
                payload
            );
        },
        create({}, payload) {
            return this.$apiService.post("/employee-vacation", payload);
        },
        createIllnessLeave({}, payload) {
            return this.$apiService.post("/employee-vacation/illness", payload);
        },
        getIllnessLeaves({}, queryParams) {
            return this.$apiService.get(
                "/employee-vacation/get-illness-leaves",
                {
                    params: queryParams,
                }
            );
        },
        show({}, id) {
            return this.$apiService.get(`/employee-vacation/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/employee-vacation/${id}`, data);
        },
        cancelVacation({}, payload) {
            return this.$apiService.post("/employee-vacation/cancel-vacation", payload);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/employee-vacation/${id}`);
        },
        updateIllnessLeave({}, { id, data }) {
            return this.$apiService.put(`/update-employee-vacation-illness-leave/${id}`, data);
        },
        deleteIllnessLeave({}, id) {
            return this.$apiService.delete(`/employee-vacation/delete/${id}`);
        },
        approvalDestroy({}, id) {
            return this.$apiService.delete(`/employee-vacation/delete-approval/${id}`);
        },
        sendMail({}, id) {
            return this.$apiService.get(`/employee-vacation/send-mail/${id}`);
        },
        profileData({}, queryParams) {
            return this.$apiService.get(`/employee-vacation/profile-data`, {
                params: queryParams,
            });
        },
        listUserVacationDataByDates({ commit }, { payload, queryParams }) {
            return this.$apiService
                .post(
                    `/employee-vacation/calendar`,
                    {
                        ...payload,
                    },
                    {
                        params: queryParams,
                    }
                )
                .then((res) => {
                    commit(
                        "vacationCalendar",
                        res?.data ?? {
                            data: [],
                            links: [],
                        }
                    );
                    commit("userPerPage", res?.data?.total ?? 0);
                    return res;
                });
        },
    },
};

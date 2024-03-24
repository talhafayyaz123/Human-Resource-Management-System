export default {
    namespaced: true,
    state: {
        authUserProfile: null,
        userProfiles: {
            data: [],
            links: [],
        },
        count: 0,
        approvalPermissions: {
            isVacationRequestApprover: false,
            isTravelExpenseApprover: false,
        },
        activeTab: "profile",
        jobFormId: "",
        compensationFormId: "",
    },
    getters: {
        authUserProfile: (state) => state.authUserProfile,
        userProfiles: (state) => state.userProfiles,
        count: (state) => state.count,
        approvalPermissions: (state) => state.approvalPermissions,
        activeTab: (state) => state.activeTab,
        jobFormId: (state) => state.jobFormId,
        compensationFormId: (state) => state.compensationFormId,
    },
    mutations: {
        authUserProfile: (state, payload) => (state.authUserProfile = payload),
        userProfiles: (state, payload) => (state.userProfiles = payload),
        count: (state, payload) => (state.count = payload),
        approvalPermissions: (state, payload) =>
            (state.approvalPermissions = payload),
        activeTab: (state, payload) => (state.activeTab = payload),
        jobFormId: (state, payload) => (state.jobFormId = payload),
        compensationFormId: (state, payload) => (state.compensationFormId = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/user-profile", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "userProfiles",
                        res?.data ?? {
                            data: [],
                            links: [],
                        }
                    );
                    commit("count", res?.data?.total ?? 0);
                });
        },
        setActiveTab({ commit }, tabValue) {
            return commit("activeTab", tabValue);
        },
        setJobFormId({ commit }, id) {
            return commit("jobFormId", id);
        },
        setCompensationFormId({ commit }, id) {
            return commit("compensationFormId", id);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/user-profile/profile/${id}`);
        },
        showProfile({}, id) {
            return this.$apiService.get(`/user-profile/profile/${id}`);
        },
        showCompleteEmployeeInfo({}, id) {
            return this.$apiService.get(`/user-profile/employee-info/${id}`);
        },
        showProfileByUserId({}, id) {
            return this.$apiService.get(`/user-profile/profile/${id}`, {
                params: {
                    getByUserId: true,
                },
            });
        },
        showJob({}, id) {
            return this.$apiService.get(`/user-profile/job/${id}`);
        },
        showJobByUser({}, id) {
            return this.$apiService.get(`/user-profile/job-by-user/${id}`);
        },
        showJobByUserBasic({}, id) {
            return this.$apiService.get(
                `/user-profile/job-by-user-basic/${id}`
            );
        },
        showBasicEmployeeInfo({}, id) {
            return this.$apiService.get(
                `/user-profile/basic-employee-info/${id}`
            );
        },
        showCompensation({}, id) {
            return this.$apiService.get(`/user-profile/compensation/${id}`);
        },
        saveProfile({}, data) {
            return this.$apiService.post(`/user-profile/profile`, data);
        },
        saveJob({}, data) {
            return this.$apiService.post(`/user-profile/job`, data);
        },
        saveCompensation({}, data) {
            return this.$apiService.post(`/user-profile/compensation`, data);
        },
        uploadDocumentAndContract({}, data) {
            return this.$apiService.post(`/user-document-contract`, data, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });
        },
        deleteDocumentAndContract({}, data) {
            return this.$apiService.post(`/delete-document-contract`, data);
        },
        getDocumentAndContract({}, queryParams) {
            return this.$apiService.get(`/get-document-contract`, {
                params: queryParams,
            });
        },
        showProfilePicForSpecificUser({}, queryParams) {
            return this.$apiService
                .get(`/profile-picture/get/${queryParams}`)
                .then((res) => {
                    return res;
                });
        },
        getApprovalPermissions({ commit }) {
            return this.$apiService
                .get("/user-profile/approval-permissions")
                .then((res) => {
                    commit("approvalPermissions", res?.data ?? []);
                });
        },
    },
};

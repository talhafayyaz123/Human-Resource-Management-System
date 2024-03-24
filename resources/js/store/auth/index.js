export default {
    namespaced: true,
    state: {
        authenticated: !!localStorage.getItem("authenticated"),
        user: localStorage.getItem("user") ?? {},
        users: [],
        employees: [],
        userPermissions: [],
        count: 0,
        employeesCount: 0,
        userProfilePicture: localStorage.getItem("userProfileImage") ?? null,
        userProfilePictures: {},
    },
    getters: {
        userProfilePictures(state) {
            return state.userProfilePictures;
        },
        authenticated(state) {
            return state.authenticated;
        },
        user(state) {
            return state.user;
        },
        users: (state) => state.users,
        employees: (state) => state.employees,
        userPermissions: (state) => state.userPermissions,
        count: (state) => state.count,
        employeesCount: (state) => state.employeesCount,
        profilePicture: (state) => state.userProfilePicture,
    },
    mutations: {
        userProfilePictures(state, value) {
            state.userProfilePictures = value;
        },
        set_authenticated(state, value) {
            state.authenticated = !!value;
        },
        set_user(state, value) {
            state.user = value;
        },
        users: (state, payload) => (state.users = payload),
        employees: (state, payload) => (state.employees = payload),
        userPermissions: (state, payload) => (state.userPermissions = payload),
        count: (state, payload) => (state.count = payload),
        employeesCount: (state, payload) => (state.employeesCount = payload),
        profilePicture: (state, payload) =>
            (state.userProfilePicture = payload),
    },
    actions: {
        refreshToken({}, payload) {
            return this.$authApiService.post("/refresh-token", payload);
        },
        login({}, payload) {
            return this.$authApiService.post("/login", payload);
        },
        list({ commit }, queryParams) {
            return this.$authApiService
                .get("/list-users", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("users", res?.data?.data ?? []);
                    commit("count", res?.data?.count ?? []);
                    return res;
                });
        },
        employees({ commit }, queryParams) {
            this.$authApiService
                .get("/list-users", {
                    params:
                        typeof queryParams === "object"
                            ? { ...queryParams, type: "employee" }
                            : { type: "employee" },
                })
                .then((res) => {
                    commit("employees", res?.data?.data ?? []);
                    commit("employeesCount", res?.data?.count ?? []);
                    return res;
                });
        },
        show({ commit }, queryParams) {
            return this.$authApiService
                .get("/list-user-by-id", {
                    params: queryParams,
                })
                .then((res) => {
                    return res;
                });
        },
        create({}, payload) {
            return this.$authApiService.post("/create-user", payload);
        },
        update({}, data) {
            return this.$authApiService.post(`/update-user`, data);
        },
        destroy({}, payload) {
            return this.$authApiService.post(`/delete-user`, payload);
        },
        uploadProfilePic({}, payload) {
            return this.$apiService.post(`/profile-picture/upload`, payload, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });
        },
        showProfilePic({ commit }, queryParams) {
            return this.$apiService
                .get(`/profile-picture/get/${queryParams}`)
                .then((res) => {
                    if (queryParams === localStorage.getItem("user_id")) {
                        commit("profilePicture", res?.data?.url ?? null);
                        localStorage.setItem(
                            "userProfileImage",
                            res.data.url ?? null
                        );
                    }
                });
        },
        profile({}, id) {
            return this.$apiService.get(`/profile/user/${id}`);
        },
    },
};

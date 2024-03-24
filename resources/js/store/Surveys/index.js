export default {
    namespaced: true,
    state: {
        id: null,
        surveys: [],
        errorList: {},
        allOptions: {},
        allConditions: {},
    },
    getters: {
        id: (state) => state.id,
        surveys: (state) => state.surveys,
        errorList: (state) => state.errorList,
        allOptions: (state) => state.allOptions,
        allConditions: (state) => state.allConditions,
    },
    mutations: {
        id: (state, payload) => (state.id = payload),
        surveys: (state, payload) => (state.surveys = payload),
        errorList: (state, payload) => (state.errorList = payload),
        allOptions: (state, payload) => (state.allOptions = payload),
        allConditions: (state, payload) => (state.allConditions = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/surveys", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("surveys", res?.data);
                });
        },
        create({ commit }, data) {
            return this.$apiService.post("/surveys", data).then((res) => {
                commit("id", res?.data?.surveyId);
            });
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/surveys/${id}`, data);
        },
        show({}, data) {
            return this.$apiService.get(
                `${data?.public ? "/public" : ""}/surveys/${data?.id}`
            );
        },
        destroy({}, id) {
            return this.$apiService.delete(`/surveys/${id}`);
        },
        surveyProducts({}, id) {
            return this.$apiService.get(`/survey-products/${id}`);
        },
        saveStylesConfigurations({}, payload) {
            return this.$apiService.post(
                `/surveys/store-style-configurations`,
                payload
            );
        },
    },
};

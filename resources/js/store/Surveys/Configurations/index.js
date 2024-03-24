export default {
    namespaced: true,
    state: {},
    getters: {},
    mutations: {},
    actions: {
        list({}, data) {
            return this.$apiService.get(
                `${data?.public ? "/public" : ""}/survey-configuration`,
                {
                    params: {
                        surveyId: data.id,
                    },
                }
            );
        },
        create({}, payload) {
            return this.$apiService.post("/survey-configuration", payload);
        },
        update({}, { id, data }) {
            return this.$apiService.patch(
                `/survey-configuration/update/${id}`,
                data
            );
        },
        destroy({}, id) {
            return this.$apiService
                .delete(`/survey-configuration/${id}`)
                .then(() => {
                    return true;
                });
        },
    },
};

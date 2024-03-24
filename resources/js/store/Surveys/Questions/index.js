export default {
    namespaced: true,
    state: {},
    getters: {},
    mutations: {},
    actions: {
        list({}, id) {
            return this.$apiService.get(`/survey-question/${id}`);
        },
        create({}, payload) {
            return this.$apiService.post("/survey-question/create", payload);
        },
        update({}, { id, data }) {
            return this.$apiService.patch(
                `/survey-question/update/${id}`,
                data
            );
        },
        destroy({}, id) {
            return this.$apiService.delete(`/survey-question/delete/${id}`);
        },
        move({}, payload) {
            return this.$apiService.post(`/survey-question/move`, payload);
        },
        changeOrder({}, payload) {
            return this.$apiService.post(
                `/survey-question-change/order`,
                payload
            );
        },
    },
};

export default {
    namespaced: true,
    state: {},
    getters: {},
    mutations: {},
    actions: {
        list({}, id) {
            return this.$apiService.get(`/survey-chapter/${id}`);
        },
        create({}, payload) {
            return this.$apiService.post("/survey-chapter/create", payload);
        },
        update({}, { id, data }) {
            return this.$apiService.patch(
                `/survey-chapter/update/${id}`,
                data
            );
        },
        destroy({}, id) {
            return this.$apiService.delete(`/survey-chapter/delete/${id}`);
        },
        changeOrder({}, payload) {
            return this.$apiService.post(
                `/survey-chapter-change/order`,
                payload
            );
        },
    },
};

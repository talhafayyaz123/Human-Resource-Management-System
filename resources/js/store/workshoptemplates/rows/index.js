export default {
    namespaced: true,
    actions: {
        create({}, payload) {
            return this.$apiService.post("/workshop-templates/rows", payload);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/workshop-templates/rows/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/workshop-templates/rows/${id}`);
        },
    },
};

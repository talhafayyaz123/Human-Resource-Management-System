export default {
    namespaced: true,
    actions: {
        create({}, payload) {
            return this.$apiService.post("/workshop-templates/cards", payload);
        },
        update({}, { id, data }) {
            return this.$apiService.put(
                `/workshop-templates/cards/${id}`,
                data
            );
        },
        destroy({}, id) {
            return this.$apiService.delete(`/workshop-templates/cards/${id}`);
        },
    },
};

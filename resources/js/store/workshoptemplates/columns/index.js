export default {
    namespaced: true,
    actions: {
        create({}, payload) {
            return this.$apiService.post(
                "/workshop-templates/columns",
                payload
            );
        },
        update({}, { id, data }) {
            return this.$apiService.put(
                `/workshop-templates/columns/${id}`,
                data
            );
        },
        destroy({}, id) {
            return this.$apiService.delete(`/workshop-templates/columns/${id}`);
        },
    },
};

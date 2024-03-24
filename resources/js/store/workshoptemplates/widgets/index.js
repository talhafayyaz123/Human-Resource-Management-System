export default {
    namespaced: true,
    actions: {
        create({}, payload) {
            return this.$apiService.post(
                "/workshop-templates/widgets",
                payload
            );
        },
        update({}, { id, data }) {
            return this.$apiService.put(
                `/workshop-templates/widgets/${id}`,
                data
            );
        },
        destroy({}, id) {
            return this.$apiService.delete(`/workshop-templates/widgets/${id}`);
        },
    },
};

<template>
    <SLALevelForm
        @save="save($event)"
        :form="slaLevel"
    />

    <div
        v-if="$can(`${$route.meta.permission}.list`)"
        class="table-responsive my-3"
    >
        <SLALevelTable @refresh="refresh(true)" />
    </div>
</template>

<script>
import SLALevelForm from "./Components/SLALevelForm.vue";
import SLALevelTable from "./Components/SLALevelTable.vue";
import { mapGetters } from "vuex";
import Modal from "@/Components/EditModal.vue";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("slaLevel", ["slaLevels"]),
    },
    components: {
        Modal,
        SLALevelForm,
        SLALevelTable,
    },
    mounted() {
        this.refresh();
    },
    data() {
        return {
            toggleModal: false,
            slaLevel: {
                name: "",
                reactionTimeUrgent: "",
                reactionTimeHigh: "",
                reactionTimeLow: "",
                factor: 0,
            },
        };
    },
    methods: {
        async refresh(bypass) {
            if (!this.slaLevels?.data?.length || bypass)
                await this.$store.dispatch("slaLevel/list");
        },
        async save(form) {
            this.$store.commit("isLoading", true);
            await this.$store.dispatch(
                `slaLevel/create`,
                {
                    ...form,
                }
            );
            this.refresh(true);
            this.resetSlaLevel();
        },
        resetSlaLevel() {
            this.slaLevel = {
                name: "",
                reactionTimeUrgent: "",
                reactionTimeHigh: "",
                reactionTimeLow: "",
                factor: 0,
            };
        },
    },
};
</script>
<style scoped>
.error {
    color: red;
}
</style>

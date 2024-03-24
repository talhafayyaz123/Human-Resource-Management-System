<template>
    <div
        class="w-full"
        v-for="(status, index) in statusesByModalType"
        :key="'status-' + index"
    >
        <div class="grid items-center grid-cols-5 gap-6 mb-3">
            <div class="w-full col-span-1">
                <div class="form-group">
                    <h3 class="capitalize text-left">{{ status.name }}</h3>
                </div>
            </div>
            <div class="w-full  col-span-2">
                <div class="form-group">
                    <div class="form-group">
                        <MultiSelectInput
                            v-if="!!linksByStatus[status.id]"
                            class=""
                            v-model="linksByStatus[status.id]"
                            :key="linksByStatus?.[status.id] ?? []"
                            :options="
                                statusesByModalType.filter(
                                    (state) => state.id != status.id
                                )
                            "
                            label="name"
                            trackBy="id"
                            :multiple="true"
                            @update:model-value="connect(status.id, $event)"
                            @remove="remove(status.id, $event)"
                        />
                    </div>
                </div>
            </div>
            <div class="w-full">
                <div class="form-group"></div>
            </div>
        </div>

    </div>
</template>

<script>
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";

export default {
    emit: ["refresh"],
    props: {
        boardId: {
            type: Number,
        },
        boardStatuses: {
            type: Array,
            default: [],
        },
        modalType: {
            type: String,
            default: "add",
        },
    },
    computed: {
        ...mapGetters("taskStatuses", ["statuses"]),
        statusesByModalType() {
            if (this.modalType === "add") {
                return this.boardStatuses;
            }
            return this.statuses;
        },
    },
    watch: {
        statuses() {
            this.getStatusLinks();
        },
        boardStatuses: {
            handler: function () {
                this.getStatusLinks();
            },
            deep: true,
        },
    },
    data() {
        return {
            linksByStatus: {},
        };
    },
    mounted() {
        this.linksByStatus = {};
        this.getStatusLinks();
    },

    components: {
        MultiSelectInput,
    },
    methods: {
        /**
         * creates elements(nodes, edges) for the flowchart from statuses and status links fetched from API
         */
        async getStatusLinks() {
            this.statusesByModalType.forEach((status) => {
                this.linksByStatus[status.id] = [];
            });
            if (!!this.boardId) {
                try {
                    const response = await this.$store.dispatch(
                        "taskStatuses/getStatusLinks",
                        this.boardId
                    );
                    let statusLinks = response?.data?.data;
                    statusLinks.forEach((link) => {
                        this.linksByStatus[link.id] =
                            this.statusesByModalType.filter((status) =>
                                link.outgoingTransitions
                                    .map((transition) => transition.toStatus)
                                    .some((statusId) => statusId == status.id)
                            );
                    });
                } catch (e) {
                    console.log(e);
                } finally {
                }
            }
        },
        /**
         * creates a link between two statuses
         * @param {connection} connection object containing source and target node IDs
         */
        async connect(from, to) {
            if (to?.[to.length - 1]?.id && this.modalType === "edit") {
                try {
                    await this.$store.dispatch(
                        "taskStatuses/createStatusLink",
                        {
                            fromStatusId: from,
                            toStatusId: to?.[to.length - 1]?.id,
                        }
                    );
                    this.$emitter.emit("refresh", this.boardId);
                    this.getStatusLinks();
                } catch (e) {
                    console.log(e);
                }
            }
        },
        /**
         * triggered on edge double click, removes the edge from elements array
         * @param {event} double click event
         */
        async remove(from, to) {
            if (this.modalType === "edit") {
                try {
                    await this.$store.dispatch(
                        "taskStatuses/removeStatusLink",
                        {
                            fromStatusId: from,
                            toStatusId: to.id,
                        }
                    );
                    this.$emitter.emit("refresh", this.boardId);
                    this.getStatusLinks();
                } catch (e) {
                    console.log(e);
                }
            }
        },
    },
};
</script>

<style scoped></style>

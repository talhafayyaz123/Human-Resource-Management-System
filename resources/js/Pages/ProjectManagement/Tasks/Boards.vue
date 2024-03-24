<template>
    <template v-for="board in boards" :key="'board-' + board.id">
        <MyTasks
            :key="uniqueKey"
            :board="board"
            v-if="selectedBoard?.id === board.id"
        />
    </template>
</template>

<script>
import MyTasks from "./MyTasks.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters("taskBoards", ["boards", "selectedBoard"]),
        ...mapGetters("auth", ["user"]),
    },
    components: {
        MyTasks,
    },
    watch: {
        "user.id"() {
            if (this.user.id) this.fetchBoards();
        },
    },
    data() {
        return {
            uniqueKey: Math.random().toString(36).substring(7),
        };
    },
    async mounted() {
        if (this.user.id) this.fetchBoards();
        try {
            await this.$store.dispatch("auth/list", {
                limit_start: 0,
                limit_count: 25,
                type: "employee",
            });
        } catch (e) {
            console.log(e);
        }
    },
    unmounted() {
        // clear all emitters
        this.$emitter.all.clear();
    },
    methods: {
        /**
         * fetch board listing
         */
        async fetchBoards() {
            try {
                // initialize default board
                await this.$store.dispatch("taskBoards/initialize", {
                    userId: this.user.id,
                });
                await this.$store.dispatch("taskBoards/list", {
                    userId: this.user.id,
                });
                this.$store.commit(
                    "taskBoards/selectedBoard",
                    this.boards?.[0] ?? null
                );
            } catch (e) {
                console.log(e);
            }
        },
    },
    beforeRouteEnter(to, from, next) {
        document.body.classList.add("my-task-page");
        next();
    },
    beforeRouteLeave(to, from, next) {
        document.body.classList.remove("my-task-page");
        next();
    },
};
</script>

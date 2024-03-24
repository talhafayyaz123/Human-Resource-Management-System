<template>
    <div class="sidebar">
        <div class="logo">
            <icon name="logoDochero" />
        </div>
        <div class="upper-menu">
            <ul class="menu">
                <li class="menu-item">
                    <router-link to="/my-feeds" class="menu-link">
                        <div class="icon">
                            <icon name="feedsIcon" />
                        </div>
                        <span>My Feed</span>
                    </router-link>
                </li>
                <li class="menu-item">
                    <router-link to="/my-tasks" class="menu-link">
                        <div class="icon">
                            <icon name="feedsIcon" />
                        </div>
                        <span>My Task</span>
                    </router-link>
                </li>
                <li class="menu-item">
                    <router-link to="/self-service" class="menu-link">
                        <div class="icon">
                            <icon name="feedsIcon" />
                        </div>
                        <span>Self Service</span>
                    </router-link>
                </li>
            </ul>
        </div>
        <div class="divider"></div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="menu-item">
                    <div class="menu-link add-worksapce">
                        <div class="">
                            <span>Workspace</span>
                        </div>
                        <div class="add">
                            <icon
                                @click.prevent="openBoardModal('add')"
                                name="addWorkIcon"
                            />
                        </div>
                    </div>
                </li>
                <li class="menu-item">
                    <router-link to="/my-tasks" class="menu-link">
                        <div class="flex items-center">
                            <div class="icon">
                                <icon name="circleIcon" />
                            </div>
                            <span>My Board</span>
                        </div>

                        <div class="action-btn">
                            <div class="flex items-center">
                                <div class="mr-1">
                                    <icon name="sideBarIcon" />
                                </div>
                                <div class="setting">
                                    <icon
                                        @click.prevent="openBoardModal('edit')"
                                        name="sidebarSettingIcon"
                                    />
                                </div>
                            </div>
                        </div>
                    </router-link>
                </li>
                <li
                    v-for="(board, index) in boards"
                    :key="'board-' + index"
                    class="menu-item"
                >
                    <a href="javascript:void(0)" class="menu-link">
                        <div class="flex items-center">
                            <div class="icon">
                                <icon name="circleIcon" />
                            </div>
                            <span>{{ board.name }}</span>
                        </div>

                        <div class="action-btn">
                            <div class="flex items-center">
                                <div class="mr-1">
                                    <icon name="sideBarIcon" />
                                </div>
                                <div class="setting">
                                    <icon name="sidebarSettingIcon" />
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <CreateBoardModal
        :modalType="modalType"
        :toggleBoardModal="toggleBoardModal"
        @toggleBoardModal="toggleBoardModal = false"
    />
</template>
<script>
import Icon from "@/Components/Icon.vue";
import CreateBoardModal from "./CreateBoardModal.vue";

export default {
    components: {
        Icon,
        CreateBoardModal,
    },
    data() {
        return {
            boards: [],
            modalType: "add",
            toggleBoardModal: false,
        };
    },
    methods: {
        /**
         * opens board modal and sets the modalType
         * @param {action} modalType to be set
         */
        openBoardModal(action) {
            this.modalType = action;
            this.toggleBoardModal = true;
        },
    },
};
</script>

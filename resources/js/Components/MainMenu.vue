<template>
    <div class="main-sidebar" :class="{ 'compact-upper-menu': isCompact }">
        <!---==========================================================-->
        <!--===Logo===-->
        <!---==========================================================-->
        <div class="logo-box">
            <router-link to="/dashboard" class="logo logo-light text-center">
                <span class="logo-sm">
                    <img src="@/assets/images/docFav.png" alt />
                </span>
                <span class="logo-lg">
                    <!-- <img src="~/assets/images/docsadmin.png" alt /> -->
                    <icon name="logoDochero" />
                </span>
            </router-link>
        </div>
        <!---==========================================================-->
        <!--===Upper Nav===-->
        <!---==========================================================-->
        <div class="upper-menu">
            <ul class="menu">
                <li class="menu-item">
                    <router-link
                        to="/my-feeds"
                        class="menu-link"
                        title="My Feeds"
                    >
                        <div class="icon">
                            <icon name="feedsIcon" />
                        </div>
                        <span>{{ $t("My Feed") }}</span>
                        <span class="tasks">16</span>
                    </router-link>
                </li>
                <li class="menu-item">
                    <router-link
                        to="/my-tasks"
                        class="menu-link"
                        title="My Task"
                    >
                        <div class="icon">
                            <icon name="myTaskIcon" />
                        </div>
                        <span>{{ $t("My Task") }}</span>
                        <span class="tasks">16</span>
                    </router-link>
                </li>
                <li class="menu-item">
                    <router-link
                        to="/self-service"
                        class="menu-link"
                        title="Self Service"
                    >
                        <div class="icon">
                            <icon name="selfServiceIcon" />
                        </div>
                        <span>{{ $t("Self Service") }}</span>
                    </router-link>
                </li>
                <li class="menu-item">
                    <router-link
                        to="/itranet"
                        class="menu-link"
                        title="Intranet"
                    >
                        <div class="icon">
                            <icon name="intranetIcon" />
                        </div>
                        <span>{{ $t("Intranet") }}</span>
                    </router-link>
                </li>
            </ul>
        </div>
        <div class="flex items-center upper-menu-dropdown-btn">
            <div class="divider"></div>
            <div
                class="ml-2 cursor-pointer upper-menu-dropdown"
                @click="dropdownUpperMenu()"
            >
                <icon name="downIcon" />
            </div>
        </div>
        <!---==========================================================-->
        <!--===Main Sidebar===-->
        <!---==========================================================-->
        <div class="sidebar-menu">
            <ul class="side-menu" id="side-menu">
                <template
                    v-if="filteredMenuItems.length > 0"
                    v-for="item in filteredMenuItems"
                >
                    <li class="menu-title" v-if="item.isTitle" :key="item.id">
                        {{ $t(item.label) }}
                    </li>
                    <li
                        class="menu-item"
                        v-if="!item.isTitle && !item.isLayout"
                        :key="item.id"
                        v-show="true"
                        :class="{ dropdown: item.isMenuCollapsed }"
                    >
                        <!--==========================-->
                        <router-link
                            v-show="true"
                            :to="item.link"
                            v-if="!hasItems(item)"
                            class="menu-link"
                            :title="$t(item.label)"
                        >
                            <div class="flex items-center">
                                <div class="menu-icon">
                                    <icon
                                        :name="`${item.icon}`"
                                        v-if="item.icon"
                                    />
                                </div>
                                <span>{{ $t(item.label) }}</span>
                            </div>
                            <span
                                :class="`badge badge-pill badge-${item.badge.variant} float-right`"
                                v-if="item.badge"
                                >{{ $t(item.badge.text) }}</span
                            >
                        </router-link>
                        <!--==========================-->
                        <a
                            v-if="hasItems(item)"
                            href="javascript:void(0);"
                            @click="
                                item.isMenuCollapsed = !item.isMenuCollapsed
                            "
                            :title="$t(item.label)"
                            class="menu-link has-arrow"
                        >
                            <div class="flex items-center">
                                <div class="menu-icon">
                                    <icon
                                        :name="`${item.icon}`"
                                        v-if="item.icon"
                                    />
                                </div>
                                <span>{{ $t(item.label) }}</span>
                            </div>
                            <span class="menu-arrow" v-if="!item.badge">
                                <icon name="nextIcon" />
                            </span>
                            <span
                                :class="`badge badge-pill badge-${item.badge.variant} float-right`"
                                v-if="item.badge"
                                >{{ $t(item.badge.text) }}</span
                            >
                        </a>

                        <div
                            class="collapse"
                            :class="{ show: item.isMenuCollapsed }"
                            id="sidebarTasks"
                        >
                            <ul
                                v-if="hasItems(item)"
                                class="sub-menu"
                                aria-expanded="false"
                            >
                                <li
                                    class="menu-item"
                                    v-for="(subitem, index) of item.subItems"
                                    :key="index"
                                >
                                    <!--==========================-->
                                    <router-link
                                        v-show="true"
                                        :to="subitem.link"
                                        v-if="!hasItems(subitem)"
                                        class="menu-link"
                                        :title="$t(subitem.label)"
                                    >
                                        <div class="flex items-center">
                                            <div class="menu-icon">
                                                <icon
                                                    :name="`${subitem.icon}`"
                                                    v-if="subitem.icon"
                                                />
                                            </div>
                                            <span>{{ $t(subitem.label) }}</span>
                                            <span
                                                v-if="
                                                    subitem.label === `Tickets`
                                                "
                                                class="notification-badge font-bold"
                                                >{{ openTickets ?? 0 }}</span
                                            >
                                        </div>
                                    </router-link>
                                    <!--==========================-->
                                    <a
                                        v-if="hasItems(subitem)"
                                        class="menu-link has-arrow"
                                        @click="
                                            subitem.isMenuCollapsed =
                                                !subitem.isMenuCollapsed
                                        "
                                        :title="$t(subitem.label)"
                                        href="javascript:void(0);"
                                    >
                                        <div class="flex items-center">
                                            <div class="menu-icon">
                                                <icon
                                                    :name="`${subitem.icon}`"
                                                    v-if="subitem.icon"
                                                />
                                            </div>
                                            <span
                                                >{{ $t(subitem.label) }}
                                            </span>
                                        </div>
                                        <span class="menu-arrow">
                                            <icon name="nextIcon" />
                                        </span>
                                    </a>

                                    <div
                                        class="collapse"
                                        :class="{
                                            show: subitem.isMenuCollapsed,
                                        }"
                                    >
                                        <ul
                                            v-if="hasItems(subitem)"
                                            class="sub-sub-menu"
                                            aria-expanded="false"
                                        >
                                            <li
                                                class="menu-item"
                                                v-for="(
                                                    subSubitem, index
                                                ) of subitem.subItems"
                                                :key="index"
                                            >
                                                <router-link
                                                    v-show="true"
                                                    :to="subSubitem.link"
                                                    :title="
                                                        $t(subSubitem.label)
                                                    "
                                                    class="menu-link"
                                                >
                                                    <div
                                                        class="flex items-center"
                                                    >
                                                        <div class="menu-icon">
                                                            <icon
                                                                :name="`${subSubitem.icon}`"
                                                                v-if="
                                                                    subSubitem.icon
                                                                "
                                                            />
                                                        </div>
                                                        <span>{{
                                                            $t(subSubitem.label)
                                                        }}</span>
                                                    </div>
                                                </router-link>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </li>
                </template>
                <template v-else>
                    <div>
                        <h6 class="text-center text-white">
                            {{ $t("No matching records found.") }}
                        </h6>
                    </div>
                </template>
            </ul>
            <!-- <ul>
                <li class="menu-item">
                    <router-link class="menu-link" to="/">
                        <div class="flex items-center">
                            <div class="icon">
                                <icon name="dashboard" class="" />
                            </div>
                            <span>
                                {{ $t("Dashboard") }}
                            </span>
                        </div>
                    </router-link>
                </li>
                <li class="menu-item" v-if="canViewMasterData">
                    <a href="javascript:void(0);" class="menu-link has-arrow">
                        <div class="flex items-center">
                                {{ $t("Master Data") }}
                            </span>
                        </div>
                        <div class="arrow-icon">
                            <icon name="nextIcon"/>
                        </div>
                    </a>
                    <div :class="['tab-content', !collapsed ? 'pl-3 pt-3' : '']">
                        <ul class="sub-menu">
                            <li class="menu-item" v-if="$can('product.list')">
                                <router-link class="menu-link" to="/products">
                                    <icon name="office" class="mr-2 w-4 h-4" :class="isUrl('products')
                                        ? 'fill-white'
                                        : 'menuTextColor group-hover:fill-white'
                                        " />
                                    <div :class="isUrl('products')
                                        ? 'text-white menu-text'
                                        : 'menuTextColor group-hover:text-white menu-text'
                                        ">
                                        {{ $t("Products") }}
                                    </div>
                                </router-link>
                            </li>
                            <li class="menu-item" v-if="$can('product.list')">
                                <router-link class="menu-link" to="/products">
                                    <icon name="office" class="mr-2 w-4 h-4" :class="isUrl('products')
                                        ? 'fill-white'
                                        : 'menuTextColor group-hover:fill-white'
                                        " />
                                    <div :class="isUrl('products')
                                        ? 'text-white menu-text'
                                        : 'menuTextColor group-hover:text-white menu-text'
                                        ">
                                        {{ $t("Products") }}
                                    </div>
                                </router-link>
                            </li>
                            <li class="menu-item" v-if="$can('company.list')">
                                <router-link class="menu-link" to="/companies">
                                    <icon name="users" class="mr-2 w-4 h-4" :class="isUrl('companies')
                                        ? 'fill-white'
                                        : 'menuTextColor group-hover:fill-white'
                                        " />
                                    <div :class="isUrl('companies')
                                        ? 'text-white menu-text'
                                        : 'menuTextColor group-hover:text-white menu-text'
                                        ">
                                        {{ $t("Customers") }}
                                    </div>
                                </router-link>
                            </li>
                            <li class="menu-item" v-if="$can('partner.list')">
                                <router-link class="menu-link" to="/partners">
                                    <font-awesome-icon color="#818cf8" class="mr-2 w-4 h-4 menuTextColor"
                                        :inverse="isUrl('partners') !== 0" icon="handshake" />
                                    <div :class="isUrl('partners')
                                        ? 'text-white menu-text'
                                        : 'menuTextColor group-hover:text-white menu-text'
                                        ">
                                        {{ $t("Partners") }}
                                    </div>
                                </router-link>
                            </li>
                            <li class="menu-item" v-if="$can('supplier.list')">
                                <router-link class="menu-link" to="/suppliers">
                                    <font-awesome-icon color="#818cf8" class="mr-2 w-4 h-4 menuTextColor"
                                        :inverse="isUrl('suppliers') !== 0" icon="truck" />
                                    <div :class="isUrl('suppliers')
                                        ? 'text-white menu-text'
                                        : 'menuTextColor group-hover:text-white menu-text'
                                        ">
                                        {{ $t("Suppliers") }}
                                    </div>
                                </router-link>
                            </li>
                            <li class="menu-item" v-if="$can('system.list')">
                                <a href="javascript:void(0);" class="menu-link has-arrow">
                                    <input @click="toggleInnerAccordion" class="tab-checkbox inner-accordion"
                                        type="checkbox" id="systems-accordion" :checked="true" />
                                    <div :style="`${collapsed
                                        ? 'display: none !important;'
                                        : ''
                                        }`" class="flex py-1">
                                        <i :class="isUrl('systems')
                                            ? 'text-white mr-2 w-4 h-4 fas fa-gears self-center'
                                            : 'menuTextColor group-hover:text-white mr-2 w-4 h-4 fas fa-gears self-center'
                                            "></i>
                                        <label data-label="something"
                                            class="tab-label styles-configurator-tab-label inner-accordion-label"
                                            for="systems-accordion">
                                            {{ $t("Systems") }}
                                        </label>
                                    </div>
                                </a>
                                <div :class="['tab-content', !collapsed ? 'pl-5 pt-2' : 'mt-5',]">
                                    <ul class="sub-sub-menu">
                                        <li class="menu-item" v-if="$can('system.list')">
                                            <router-link class="menu-link" to="/systems/on-premise">
                                                <i class="mr-2 w-4 h-4" :class="isUrl('systems/on-premise')
                                                    ? 'fill-white fas fa-house'
                                                    : 'menuTextColor group-hover:fill-white fas fa-house'
                                                    "></i>
                                                <div :class="isUrl('systems/on-premise')
                                                    ? 'text-white menu-text'
                                                    : 'menuTextColor group-hover:text-white menu-text'
                                                    ">
                                                    {{ $t("On Premise") }}
                                                </div>
                                            </router-link>
                                        </li>
                                        <li class="menu-item" v-if="$can('system.list')">
                                            <router-link class="menu-link" to="/systems/cloud">
                                                <i class="mr-2 w-4 h-4" :class="isUrl('systems/cloud')
                                                    ? 'fill-white fas fa-cloud'
                                                    : 'menuTextColor group-hover:fill-white fas fa-cloud'
                                                    "></i>
                                                <div :class="isUrl('systems/cloud')
                                                    ? 'text-white menu-text'
                                                    : 'menuTextColor group-hover:text-white menu-text'
                                                    ">
                                                    {{ $t("Cloud") }}
                                                </div>
                                            </router-link>
                                        </li>
                                        <li class="menu-item" v-if="$can('system.list')">
                                            <router-link class="menu-link" to="/systems/hosting">
                                                <i :class="isUrl('systems/hosting')
                                                    ? 'text-white'
                                                    : 'menuTextColor group-hover:text-white'
                                                    " class="mr-2 w-4 h-4 fa-regular fa-hard-drive"></i>
                                                <div :class="isUrl('systems/hosting')
                                                    ? 'text-white menu-text'
                                                    : 'menuTextColor group-hover:text-white menu-text'
                                                    ">
                                                    {{ $t("Hosting") }}
                                                </div>
                                            </router-link>
                                        </li>
                                    </ul>



                                </div>
                            </li>
                        </ul>





                    </div>
                </li>
                <li class="menu-item"></li>
                <li class="menu-item"></li>
                <li class="menu-item"></li>
                <li class="menu-item"></li>
                <li class="menu-item"></li>
                <li class="menu-item"></li>
            </ul> -->
        </div>
        <!---==========================================================-->
        <!--===My Task===-->
        <!---==========================================================-->
        <div class="my-task-sidebar-menu">
            <div class="back-to-main">
                <router-link to="/dashboard" class="secondary-btn"
                    ><span class="mr-1"><CustomIcon name="backIcon" /></span
                    ><span>{{ $t("Back To Main") }}</span></router-link
                >
            </div>
            <div class="divider"></div>
            <ul class="menu">
                <li class="menu-item">
                    <div class="menu-link add-worksapce">
                        <div class="">
                            <span>Workspace</span>
                        </div>
                        <div @click="openBoardModal('add')" class="add">
                            <icon name="addWorkIcon" />
                        </div>
                    </div>
                </li>
                <li
                    v-for="(board, index) in boards"
                    :key="'board-' + index"
                    class="menu-item"
                >
                    <a
                        href="javascript:void(0)"
                        @click="
                            $store.commit('taskBoards/selectedBoard', board)
                        "
                        :class="[
                            'menu-link',
                            selectedBoard?.id === board.id
                                ? 'router-link-active'
                                : '',
                        ]"
                    >
                        <div class="flex items-center">
                            <div class="icon">
                                <icon
                                    :color="board.menuColor ?? '#FFB580'"
                                    name="circleIcon"
                                />
                            </div>
                            <span class="capitalize">{{ board.name }}</span>
                        </div>

                        <div class="action-btn">
                            <div class="flex items-center">
                                <div v-if="board.isDefault == 0" class="mr-1">
                                    <dropdown placement="bottom-end">
                                        <template #default>
                                            <div
                                                class="group flex items-center cursor-pointer select-none"
                                            >
                                                <icon
                                                    name="sideBarIcon"
                                                    class="cursor-pointer self-center"
                                                />
                                            </div>
                                        </template>
                                        <template #dropdown>
                                            <div
                                                class="mt-2 py-2 text-sm bg-white rounded shadow-xl"
                                            >
                                                <div
                                                    v-if="
                                                        board.boardAdminId ===
                                                        user.id
                                                    "
                                                    @click="
                                                        toggleCreateStatusModal = true
                                                    "
                                                    class="block cursor-pointer px-8 py-2 w-full text-left hover:text-white hover:bg-indigo-500"
                                                    as="button"
                                                >
                                                    {{ $t("Add Status") }}
                                                </div>
                                                <div
                                                    v-if="
                                                        board.boardAdminId ===
                                                            user.id &&
                                                        board.isDefault == 0
                                                    "
                                                    @click="
                                                        deleteBoard(board.id)
                                                    "
                                                    class="block cursor-pointer px-8 py-2 w-full text-left hover:text-white hover:bg-indigo-500"
                                                    as="button"
                                                >
                                                    {{ $t("Delete") }}
                                                </div>
                                            </div>
                                        </template>
                                    </dropdown>
                                </div>
                                <div
                                    @click.prevent="
                                        openBoardModal('edit', board)
                                    "
                                    class="setting"
                                >
                                    <icon name="sidebarSettingIcon" />
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
        <!---==========================================================-->
        <!--===Project Management Setting===-->
        <!---==========================================================-->
        <div class="pm-sidebar-menu">
            <div class="back-to-main">
                <div class="flex items-center mb-3">
                    <CustomIcon name="RewampIcon" />
                    <h4 class="ml-1">Adminportal - Revamp</h4>
                </div>
                <router-link to="/project-management2/project" class="secondary-btn"
                    ><span class="mr-1"><CustomIcon name="backIcon" /></span
                    ><span>{{ $t("Back To Main") }}</span></router-link
                >
            </div>
            <div class="divider"></div>
            <ul class="menu">
                <li class="menu-item">
                    <router-link to="/project-settings-detail">
                        <div class="flex items-center">
                            <div class="icon">
                                <CustomIcon name="iWhiteIcon" />
                            </div>
                            <span>{{ $t("Details") }}</span>
                        </div>
                        <span class="menu-arrow">
                            <icon name="nextIcon" />
                        </span>
                    </router-link>
                </li>
                <li class="menu-item">
                    <router-link to="/project-settings-access">
                        <div class="flex items-center">
                            <div class="icon">
                                <CustomIcon name="keyIcon" />
                            </div>
                            <span>{{ $t("Access") }}</span>
                        </div>
                        <span class="menu-arrow">
                            <icon name="nextIcon" />
                        </span>
                    </router-link>
                </li>
                <li class="menu-item">
                    <router-link to="/project-settings-features">
                        <div class="flex items-center">
                            <div class="icon">
                                <CustomIcon name="featureIcon" />
                            </div>
                            <span>{{ $t("Features") }}</span>
                        </div>
                        <span class="menu-arrow">
                            <icon name="nextIcon" />
                        </span>
                    </router-link>
                </li>
                <li class="menu-item">
                    <router-link to="/project-settings-notification">
                        <div class="flex items-center">
                            <div class="icon">
                                <CustomIcon name="notiIcon" />
                            </div>
                            <span>{{ $t("Notification") }}</span>
                        </div>
                        <span class="menu-arrow">
                            <icon name="nextIcon" />
                        </span>
                    </router-link>
                </li>
                <li class="menu-item">
                    <router-link to="/project-settings-issuse-type">
                        <div class="flex items-center">
                            <div class="icon">
                                <CustomIcon name="issuseIcon" />
                            </div>
                            <span>{{ $t("Issue Types") }}</span>
                        </div>
                        <span class="menu-arrow">
                            <icon name="nextIcon" />
                        </span>
                    </router-link>
                </li>
                <li class="menu-item">
                    <router-link to="/project-settings-kanban-lines">
                        <div class="flex items-center">
                            <div class="icon">
                                <CustomIcon name="khabanaIcon" />
                            </div>
                            <span>{{ $t("KANBAN Lines") }}</span>
                        </div>
                        <span class="menu-arrow">
                            <icon name="nextIcon" />
                        </span>
                    </router-link>
                </li>
                <li class="menu-item">
                    <router-link to="/Features">
                        <div class="flex items-center">
                            <div class="icon">
                                <CustomIcon name="workflowIcon" />
                            </div>
                            <span>{{ $t("Workflow") }}</span>
                        </div>
                        <span class="menu-arrow">
                            <icon name="nextIcon" />
                        </span>
                    </router-link>
                </li>
            </ul>
        </div>
        <!---==========================================================-->
        <!--===Project Management Show===-->
        <!---==========================================================-->
        <div class="pms-sidebar-menu">
            <div class="back-to-main">
                <div class="flex items-center mb-3">
                    <CustomIcon name="RewampIcon" />
                    <h4 class="ml-1">Adminportal - Revamp</h4>
                </div>
                <router-link to="/project-management2/project" class="secondary-btn"
                    ><span class="mr-1"><CustomIcon name="backIcon" /></span
                    ><span>{{ $t("Back To Main") }}</span></router-link
                >
            </div>
            <div class="divider"></div>
            <ul class="menu">
                <li class="menu-item">
                    <router-link to="/project-news">
                        <div class="flex items-center">
                            <div class="icon">
                                <CustomIcon name="newsIcon" />
                            </div>
                            <span>{{ $t("News") }}</span>
                        </div>
                        <span class="menu-arrow">
                            <icon name="nextIcon" />
                        </span>
                    </router-link>
                </li>
                <li class="menu-item">
                    <router-link to="/project-board">
                        <div class="flex items-center">
                            <div class="icon">
                                <CustomIcon name="boardlistIcon" />
                            </div>
                            <span>{{ $t("Scrum Board") }}</span>
                        </div>
                        <span class="menu-arrow">
                            <icon name="nextIcon" />
                        </span>
                    </router-link>
                </li>
                <li class="menu-item">
                    <router-link to="/project-sprint-board">
                        <div class="flex items-center">
                            <div class="icon">
                                <CustomIcon name="boardlistIcon" />
                            </div>
                            <span>{{ $t("Sprint Board") }}</span>
                        </div>
                        <span class="menu-arrow">
                            <icon name="nextIcon" />
                        </span>
                    </router-link>
                </li>
                <li class="menu-item">
                    <router-link to="/project-backlog">
                        <div class="flex items-center">
                            <div class="icon">
                                <CustomIcon name="backloglistIcon" />
                            </div>
                            <span>{{ $t("Scrum Backlog") }}</span>
                        </div>
                        <span class="menu-arrow">
                            <icon name="nextIcon" />
                        </span>
                    </router-link>
                </li>
                <li class="menu-item">
                    <router-link to="/project-sprint-backlog">
                        <div class="flex items-center">
                            <div class="icon">
                                <CustomIcon name="backloglistIcon" />
                            </div>
                            <span>{{ $t("Sprint Backlog") }}</span>
                        </div>
                        <span class="menu-arrow">
                            <icon name="nextIcon" />
                        </span>
                    </router-link>
                </li>
                <li class="menu-item">
                    <router-link to="/project-gantt-chart">
                        <div class="flex items-center">
                            <div class="icon">
                                <CustomIcon name="ganttChartIcon" />
                            </div>
                            <span>{{ $t("Gantt Chart") }}</span>
                        </div>
                        <span class="menu-arrow">
                            <icon name="nextIcon" />
                        </span>
                    </router-link>
                </li>
                <li class="menu-item">
                    <router-link to="/project-swimlane-board">
                        <div class="flex items-center">
                            <div class="icon">
                                <CustomIcon name="swimLaneIcon" />
                            </div>
                            <span>{{ $t("Swimlane Board") }}</span>
                        </div>
                        <span class="menu-arrow">
                            <icon name="nextIcon" />
                        </span>
                    </router-link>
                </li>
                <li class="menu-item">
                    <router-link to="/project-story-map">
                        <div class="flex items-center">
                            <div class="icon">
                                <CustomIcon name="storyMapIcon" />
                            </div>
                            <span>{{ $t("Story Map") }}</span>
                        </div>
                        <span class="menu-arrow">
                            <icon name="nextIcon" />
                        </span>
                    </router-link>
                </li>
            </ul>
        </div>
        <!---==========================================================-->
        <!--===Condensed Nav Button===-->
        <!---==========================================================-->
        <div class="bottom_nav">
            <a class="button-menu-mobile" @click="toggleMenu">
                <icon name="minimizeIcon" />
            </a>
        </div>
        <!---==========================================================-->
    </div>
    <CreateBoardModal
        v-if="toggleBoardModal"
        :modalType="modalType"
        :toggleBoardModal="toggleBoardModal"
        @toggleBoardModal="toggleBoardModal = false"
        @refresh="fetchBoards"
        :board="boardToBeEdited"
        :id="boardToBeEdited?.id"
        :key="boardToBeEdited?.id"
    />
    <CreateStatusModal
        :toggleCreateStatusModal="toggleCreateStatusModal"
        @toggleCreateStatusModal="toggleCreateStatusModal = false"
        @refresh="$emitter.emit('refresh', selectedBoard?.id)"
        :modalType="'add'"
        v-if="toggleCreateStatusModal"
    />
</template>
<script>
import Icon from "./Icon.vue";
import { Menu } from "./menu";
import CreateBoardModal from "@/Pages/ProjectManagement/Tasks/CreateBoardModal.vue";
import CreateStatusModal from "@/Pages/ProjectManagement/Tasks/CreateStatusModal.vue";
import Dropdown from "@/Components/Dropdown.vue";
import { mapGetters } from "vuex";

export default {
    components: {
        Icon,
        CreateBoardModal,
        CreateStatusModal,
        Dropdown,
    },
    data() {
        return {
            toggleCreateStatusModal: false,
            boardToBeEdited: null,
            modalType: "add",
            toggleBoardModal: false,
            menuItems: Menu,
            isSidebarCondensed: false,
            searchQuery: "",
            isCompact: true,
        };
    },
    props: {
        menu: {
            type: String,
            required: true,
        },
    },
    watch: {
        menu: {
            immediate: true,
            handler(newVal, oldVal) {
                if (newVal !== oldVal) {
                    switch (newVal) {
                        case "fixed":
                            document.body.setAttribute(
                                "data-layout-menu-position",
                                "fixed"
                            );
                            break;
                        case "scrollable":
                            document.body.setAttribute(
                                "data-layout-menu-position",
                                "scrollable"
                            );
                            break;
                        default:
                            document.body.setAttribute(
                                "data-layout-menu-position",
                                "fixed"
                            );
                            break;
                    }
                }
            },
        },
        isSidebarCondensed(newVal) {
            document.body.classList.toggle("sidebar-condensed", newVal);
        },
    },
    mounted: function () {
        this._activateMenuDropdown();
        this.$router.afterEach((routeTo, routeFrom) => {
            this._activateMenuDropdown();
        });
        // Set up an event listener
        this.$emitter.on("searchQueryGlobally", (receivedValue) => {
            // Handle the received value here
            this.searchQuery = receivedValue;
        });
    },

    methods: {
        /**
         * fetch boards
         */
        async fetchBoards() {
            try {
                this.$store.dispatch("taskBoards/list", {
                    userId: this.user.id,
                });
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * delete board based on id
         * @param {id} id to be deleted
         */
        async deleteBoard(id) {
            try {
                this.$swal({
                    title: this.$t("Do you want to delete this record?"),
                    text: this.$t("You can't revert your action"),
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: this.$t("Yes delete it!"),
                    cancelButtonText: this.$t("No"),
                    showCloseButton: true,
                    showLoaderOnConfirm: true,
                }).then(async (result) => {
                    if (result.isConfirmed === true) {
                        await this.$store.dispatch("taskBoards/destroy", id);
                        this.$store.commit(
                            "taskBoards/selectedBoard",
                            this.boards?.[0] ?? null
                        );
                        this.fetchBoards();
                    }
                });
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * opens board modal and sets the modalType
         * @param {action} modalType to be set
         */
        async openBoardModal(action, board = {}) {
            this.boardToBeEdited = null;
            this.$store.commit("showLoader", true);
            this.modalType = action;
            let users = [];
            const shareableUsers = (board?.shareableUsers ?? []).filter(
                (userId) => userId != this.user.id
            );
            if (action === "edit" && (shareableUsers?.length ?? 0) > 0) {
                try {
                    const response = await this.$store.dispatch("auth/show", {
                        id: shareableUsers,
                    });
                    users = response?.data ?? [];
                } catch (e) {
                    console.log(e);
                }
            }
            let escalationManager = null;
            if (action === "edit" && board?.escalationManager) {
                try {
                    const response = await this.$store.dispatch("auth/show", {
                        id: board?.escalationManager,
                    });
                    escalationManager = response?.data ?? null;
                } catch (e) {
                    console.log(e);
                }
            }
            this.toggleBoardModal = true;
            this.boardToBeEdited = {
                ...board,
                color: board.menuColor,
                useDefaultStatuses: false,
                escalationHours: board?.escalationHours ?? 0,
                escalationManager: escalationManager,
                uponTaskCreation: board?.isTaskCreationNotify == 1,
                whenTaskIsAssigned: board?.isTaskAssignedNotify == 1,
                inEscalationMode: board?.isEscalationModeNotify == 1,
                users: users,
            };
            this.$store.commit("showLoader", false);
        },
        dropdownUpperMenu() {
            this.isCompact = !this.isCompact;
        },
        hasItems(item) {
            return item.subItems !== undefined
                ? item.subItems.length > 0
                : false;
        },
        toggleMenu() {
            this.isSidebarCondensed = !this.isSidebarCondensed;
            this.isCompact = false;
        },
        _activateMenuDropdown() {
            const resetParent = (el) => {
                el.classList.remove("active");
                var parent = el.parentElement;
                if (parent) {
                    parent.classList.remove("menuitem-active");
                    const parent2 = parent.parentElement;
                    if (parent2) {
                        const parent3 = parent2.parentElement;
                        if (parent3) {
                            parent3.classList.remove("show");
                            const parent4 = parent3.parentElement;
                            if (parent4) {
                                parent4.classList.remove("menuitem-active");
                            }
                        }
                    }
                }
            };
            var links = document.getElementsByClassName("side-nav-link-ref");
            var matchingMenuItem = null;
            const paths = [];
            for (let i = 0; i < links.length; i++) {
                // reset menu
                resetParent(links[i]);
            }
            for (var i = 0; i < links.length; i++) {
                paths.push(links[i]["pathname"]);
            }
            var itemIndex = paths.indexOf(window.location.pathname);
            if (itemIndex === -1) {
                const strIndex = window.location.pathname.lastIndexOf("/");
                const item = window.location.pathname
                    .substr(0, strIndex)
                    .toString();
                matchingMenuItem = links[paths.indexOf(item)];
            } else {
                matchingMenuItem = links[itemIndex];
            }

            if (matchingMenuItem) {
                matchingMenuItem.classList.add("active");
                var parent = matchingMenuItem.parentElement;

                /**
                 * TODO: This is hard coded way of expading/activating parent menu dropdown and working till level 3.
                 * We should come up with non hard coded approach
                 */
                if (parent) {
                    parent.classList.add("menuitem-active");
                    const parent2 = parent.parentElement;
                    if (parent2) {
                        const parent3 = parent2.parentElement;
                        if (parent3) {
                            parent3.classList.add("show");
                            const parent4 = parent3.parentElement;
                            if (parent4) {
                                parent4.classList.add("menuitem-active");
                            }
                        }
                    }
                }
            }
        },
    },
    computed: {
        ...mapGetters("auth", ["user"]),
        ...mapGetters("tickets", ["openTickets"]),
        ...mapGetters("taskBoards", ["boards", "selectedBoard"]),
        filteredMenuItems() {
            const query = this.searchQuery.toLowerCase();
            return this.menuItems.filter((item) => {
                if (item.label.toLowerCase().includes(query)) {
                    return true;
                }
                if (item.subItems) {
                    // Check if any sub-item matches the query
                    return item.subItems.some((subItem) =>
                        subItem.label.toLowerCase().includes(query)
                    );
                }
                return false;
            });
        },
    },
};
</script>

<style scoped>
.notification-badge {
    background-color: #fa3e3e;
    border-radius: 2px;
    color: white;
    padding: 0px 3px;
    font-size: 10px;
    margin-left: 20px;
}
</style>

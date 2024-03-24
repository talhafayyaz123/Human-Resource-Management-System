<template>
    <div :key="key">
        <div class="">
            <div class="page-header">
                <div class="page-title">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <div class="flex items-center">
                                    <router-link to="/" class="">{{
                                        $t("Admin Portal")
                                    }}</router-link>
                                </div>
                            </li>
                            <li class="breadcrumb-item">
                                <div class="flex items-center">
                                    <span class="capitalize">{{
                                        board.name
                                    }}</span>
                                </div>
                            </li>
                        </ol>
                    </div>
                </div>
                <div class="other-items d-flex justify-center">
                    <div class="users-list mr-5">
                        <ul>
                            <li
                                v-for="(user, index) in (
                                    board?.shareableUsers ?? []
                                ).slice(0, 5)"
                                :key="'assignee-' + index"
                                @click="filterTasks(user)"
                                class="cursor-pointer"
                                :class="
                                    filterAssignees.some(
                                        (assigneeId) => assigneeId === user
                                    )
                                        ? 'selected-assignee'
                                        : ''
                                "
                                :title="
                                    (userProfilePictures?.[user]?.first_name ??
                                        '') +
                                    ' ' +
                                    (userProfilePictures?.[user]?.last_name ??
                                        '')
                                "
                            >
                                <img
                                    v-if="
                                        !!userProfilePictures?.[user]
                                            ?.profile_image
                                    "
                                    :src="
                                        userProfilePictures?.[user]
                                            ?.profile_image ?? ''
                                    "
                                    alt=""
                                />
                                <p class="default-user-avatar" v-else>
                                    {{
                                        getInitials(
                                            (userProfilePictures[user]
                                                ?.first_name ?? "") +
                                                " " +
                                                (userProfilePictures[user]
                                                    ?.last_name ?? "")
                                        )
                                    }}
                                </p>
                            </li>
                            <li
                                v-if="
                                    (board?.shareableUsers ?? []).slice(
                                        5,
                                        (board?.shareableUsers ?? []).length
                                    ).length > 0
                                "
                            >
                                <dropdown
                                    :showOnHover="true"
                                    placement="bottom-end"
                                >
                                    <template #default>
                                        <div
                                            class="group flex items-center cursor-pointer select-none"
                                        >
                                            <p class="show-more-assignees">
                                                +{{
                                                    (
                                                        board?.shareableUsers ??
                                                        []
                                                    ).slice(
                                                        5,
                                                        (
                                                            board?.shareableUsers ??
                                                            []
                                                        ).length
                                                    ).length
                                                }}
                                            </p>
                                        </div>
                                    </template>
                                    <template #dropdown>
                                        <div
                                            class="mt-2 py-2 text-sm bg-white rounded shadow-xl"
                                        >
                                            <div
                                                class="flex cursor-pointer px-8 py-2 w-full text-left hover:text-white hover:bg-indigo-500"
                                                v-for="user in (
                                                    board?.shareableUsers ?? []
                                                ).slice(
                                                    5,
                                                    (
                                                        board?.shareableUsers ??
                                                        []
                                                    ).length
                                                )"
                                                :key="'assignee-' + user"
                                                @click="filterTasks(user)"
                                            >
                                                <img
                                                    class="shareable-user-img"
                                                    v-if="
                                                        !!userProfilePictures?.[
                                                            user
                                                        ]?.profile_image
                                                    "
                                                    :src="
                                                        userProfilePictures?.[
                                                            user
                                                        ]?.profile_image ?? ''
                                                    "
                                                    alt=""
                                                />
                                                <p
                                                    class="default-user-avatar"
                                                    v-else
                                                >
                                                    {{
                                                        getInitials(
                                                            (userProfilePictures[
                                                                user
                                                            ]?.first_name ??
                                                                "") +
                                                                " " +
                                                                (userProfilePictures[
                                                                    user
                                                                ]?.last_name ??
                                                                    "")
                                                        )
                                                    }}
                                                </p>
                                                <p class="ml-2 self-center">
                                                    {{
                                                        userProfilePictures?.[
                                                            user
                                                        ]?.first_name
                                                    }}
                                                    {{
                                                        userProfilePictures?.[
                                                            user
                                                        ]?.last_name
                                                    }}
                                                </p>
                                                <input
                                                    class="ml-2"
                                                    type="checkbox"
                                                    :checked="
                                                        filterAssignees.some(
                                                            (filterAssignee) =>
                                                                filterAssignee ==
                                                                user
                                                        )
                                                    "
                                                />
                                            </div>
                                        </div>
                                    </template>
                                </dropdown>
                            </li>
                        </ul>
                    </div>
                    <button
                        v-if="
                            board.boardAdminId === user.id &&
                            board.isDefault == 0
                        "
                        @click="toggleShareableUsersBoardModal = true"
                        class="filterBtn mr-5"
                    >
                        <icon name="usersIcon" class="mr-1" />
                        <span>{{ $t("Share") }}</span>
                    </button>
                    <div class="search">
                        <div class="form-group m-0">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <icon name="search" class="mr-1" />
                                    </span>
                                </div>
                                <input
                                    v-model="form.search"
                                    type="search"
                                    placeholder="Search here"
                                    class="form-control"
                                />
                            </div>
                        </div>
                    </div>
                    <!---====Own Task====--->
                    <div class="mr-5">
                        <dropdown :auto-close="false" placement="bottom-end">
                            <template #default>
                                <button
                                    class="filterBtn"
                                    @click="fetchBoardsWithTasks"
                                >
                                    <icon name="projIcon" class="mr-1" />
                                    <span>{{ $t("Own Tasks") }}</span>
                                </button>
                            </template>
                            <template #dropdown>
                                <div class="my-work-dropdown">
                                    <div class="my-work-tabs">
                                        <ul class="">
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link font-medium"
                                                    @click="
                                                        activeTab =
                                                            'assignedToMe'
                                                    "
                                                    :class="
                                                        activeTab ===
                                                        'assignedToMe'
                                                            ? activeClasses +
                                                              ' '
                                                            : inactiveClasses
                                                    "
                                                >
                                                    <h4 class="">
                                                        {{
                                                            $t("Assigned to me")
                                                        }}
                                                    </h4>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    class="nav-link font-medium"
                                                    @click="
                                                        activeTab = 'boards'
                                                    "
                                                    :class="
                                                        activeTab === 'boards'
                                                            ? activeClasses +
                                                              ' '
                                                            : inactiveClasses
                                                    "
                                                >
                                                    <h4 class="">
                                                        {{ $t("Boards") }}
                                                    </h4>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="mywork-list">
                                        <div
                                            v-if="activeTab === 'assignedToMe'"
                                        >
                                            <div
                                                v-for="(
                                                    status, index
                                                ) in boardWithTasks"
                                                :key="index"
                                            >
                                                <h4 class="">
                                                    {{ index }}
                                                </h4>
                                                <div
                                                    v-for="(
                                                        task, tIndex
                                                    ) in status"
                                                    :key="tIndex"
                                                >
                                                    <div
                                                        class="mywork-task"
                                                        @click="
                                                            $store.commit(
                                                                'taskBoards/selectedBoard',
                                                                task.board
                                                            )
                                                        "
                                                    >
                                                        <div class="icon">
                                                            <CustomIcon
                                                                :name="
                                                                    task.icon
                                                                "
                                                            />
                                                        </div>
                                                        <div class="">
                                                            <h3 class="">
                                                                {{
                                                                    task.headline
                                                                }}
                                                            </h3>
                                                            <p>
                                                                Task #
                                                                {{ task.id }} -
                                                                {{
                                                                    task.board
                                                                        ?.name
                                                                }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-if="activeTab === 'boards'">
                                            <ul class="my-work-task">
                                                <li
                                                    v-for="(
                                                        board, index
                                                    ) in boards"
                                                    :key="index"
                                                >
                                                    <div
                                                        class="mywork-task"
                                                        @click="
                                                            $store.commit(
                                                                'taskBoards/selectedBoard',
                                                                board
                                                            )
                                                        "
                                                    >
                                                        <div class="icon">
                                                            <CustomIcon
                                                                name="themeIcon"
                                                            />
                                                        </div>
                                                        <div class="">
                                                            <h3 class="">
                                                                {{ board.name }}
                                                            </h3>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </dropdown>
                    </div>
                    <!---====End Own Task====--->
                    <!---========--->
                    <div class="mr-5">
                        <dropdown :auto-close="false" placement="bottom-end">
                            <template #default>
                                <button class="filterBtn">
                                    <icon name="filters" class="mr-1" />
                                    <span>{{ $t("Filters") }}</span>
                                </button>
                            </template>
                            <template #dropdown>
                                <div
                                    class="mt-2 py-5 text-sm bg-white rounded shadow-xl px-5"
                                    style="min-width: 300px"
                                >
                                    <SelectInput
                                        class="w-full"
                                        :label="$t('Status')"
                                        v-model="form.statusId"
                                    >
                                        <option value="">
                                            {{ $t("All") }}
                                        </option>
                                        <option
                                            v-for="status in filterStatuses"
                                            :key="'status-' + status.id"
                                            :value="status.id"
                                        >
                                            {{ $t(status.name) }}
                                        </option>
                                    </SelectInput>
                                    <div class="w-full flex mt-5">
                                        <label class="mr-2" for="in-escalation"
                                            >{{ $t("In Escalation") }}:</label
                                        >
                                        <input
                                            class="self-center"
                                            id="in-escalation"
                                            type="checkbox"
                                            v-model="form.inEscalation"
                                        />
                                    </div>
                                    <div class="w-full flex mt-5">
                                        <label class="mr-2" for="in-escalation"
                                            >{{
                                                $t("Show Resubmitted Tasks")
                                            }}:</label
                                        >
                                        <input
                                            class="self-center"
                                            id="in-escalation"
                                            type="checkbox"
                                            v-model="form.resubmit"
                                        />
                                    </div>
                                </div>
                            </template>
                        </dropdown>
                    </div>
                    <!---========--->
                    <div class="right-side">
                        <button
                            @click="toggleBoardModal = true"
                            class="primary-btn mr-2"
                        >
                            <icon name="add" class="mr-1" />
                            <span>{{ $t("Create Board") }}</span>
                        </button>
                        <button
                            class="primary-btn"
                            @click="openCreateTaskModal(null)"
                        >
                            <icon name="add" class="mr-1" />
                            <span>{{ $t("Create Task") }}</span>
                        </button>
                    </div>
                    <!---========--->
                </div>
            </div>
        </div>

        <div class="">
            <div class="tasks">
                <draggable
                    :move="onMove"
                    :list="statuses ?? []"
                    @change="onStatusOrderChange($event)"
                    group="statuses"
                    class="tasks-row"
                    item-key="id"
                >
                    <template #item="{ element: status, index: index }">
                        <div
                            :class="[
                                'tasks-col',
                                statusesCanBeDroppedOn.includes(status.id)
                                    ? 'bg-gray-100'
                                    : '',
                                'mr-5',
                            ]"
                            @drop="onDrop($event, status)"
                            @dragover="onDragover($event)"
                        >
                            <div
                                :style="`border-top: 2px solid ${status.color};`"
                                class="task-head"
                            >
                                <h4
                                    :style="`color: ${status.color};`"
                                    class="capitalize"
                                >
                                    {{ status.name }}&nbsp;<span
                                        >({{
                                            status?.tasks?.length ?? 0
                                        }})</span
                                    >
                                </h4>
                                <div class="action-btn">
                                    <div
                                        @click="openCreateTaskModal(status)"
                                        class="mr-3 cursor-pointer"
                                    >
                                        <icon name="addTask" />
                                    </div>
                                    <div
                                        v-if="board?.isDefault == 0"
                                        class="cursor-pointer"
                                    >
                                        <dropdown placement="bottom-end">
                                            <template #default>
                                                <div
                                                    class="action-task group flex items-center cursor-pointer select-none"
                                                >
                                                    <icon
                                                        name="bartaskIcon"
                                                        class="cursor-pointer"
                                                    />
                                                </div>
                                            </template>
                                            <template #dropdown>
                                                <div
                                                    class="mt-2 py-2 text-sm bg-white rounded shadow-xl"
                                                >
                                                    <div
                                                        @click="
                                                            openAddStatusModal(
                                                                status
                                                            )
                                                        "
                                                        class="block cursor-pointer px-8 py-2 w-full text-left hover:text-white hover:bg-indigo-500"
                                                        as="button"
                                                    >
                                                        {{ $t("Edit") }}
                                                    </div>
                                                    <hr />
                                                    <div
                                                        @click="
                                                            deleteStatus(
                                                                status.id
                                                            )
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
                                </div>
                            </div>
                            <div class="task-body">
                                <draggable
                                    :list="filterTasksForEscalation(status)"
                                    @change="onTaskOrderChange($event, index)"
                                    group="tasks"
                                    item-key="id"
                                >
                                    <template #item="{ element: task }">
                                        <div
                                            @dragstart="
                                                onDragstart(
                                                    $event,
                                                    task.id,
                                                    'task',
                                                    status
                                                )
                                            "
                                            @dragend="
                                                statusesCanBeDroppedOn.splice(
                                                    0,
                                                    statusesCanBeDroppedOn.length
                                                )
                                            "
                                            @contextmenu.stop.prevent="
                                                openCreateTaskModal(
                                                    status,
                                                    'edit',
                                                    task
                                                )
                                            "
                                            class="task-card"
                                            :style="
                                                'background-color: ' +
                                                task.color
                                            "
                                        >
                                            <div class="task-content">
                                                <div class="icon">
                                                    <i
                                                        :class="[
                                                            task.icon,
                                                            'text-3xl',
                                                            'mt-1',
                                                        ]"
                                                    ></i>
                                                </div>
                                                <div class="">
                                                    <h3
                                                        @click="
                                                            openUpdateModal(
                                                                task.id
                                                            )
                                                        "
                                                    >
                                                        {{ task.headline }}
                                                    </h3>
                                                    <template
                                                        v-for="content in task.content"
                                                        :key="
                                                            'content-' +
                                                            content.id
                                                        "
                                                    >
                                                        <img
                                                            v-if="
                                                                content.type ===
                                                                    'text' &&
                                                                !!imageFromHtmlString(
                                                                    content.value
                                                                )
                                                            "
                                                            :src="
                                                                imageFromHtmlString(
                                                                    content.value
                                                                )
                                                            "
                                                        />
                                                        <p
                                                            v-if="
                                                                content.type ===
                                                                'text'
                                                            "
                                                            v-html="
                                                                filterForImages(
                                                                    content.value
                                                                )
                                                            "
                                                        ></p>
                                                        <div
                                                            v-else-if="
                                                                content.type ===
                                                                'checkboxes'
                                                            "
                                                        >
                                                            <input
                                                                class="mr-1"
                                                                type="checkbox"
                                                                :id="
                                                                    'checkbox-' +
                                                                    content.id
                                                                "
                                                                v-model="
                                                                    content.isChecked
                                                                "
                                                                @change="
                                                                    updateTask(
                                                                        task
                                                                    )
                                                                "
                                                            />
                                                            <label
                                                                :for="
                                                                    'checkbox-' +
                                                                    content.id
                                                                "
                                                                >{{
                                                                    $t(
                                                                        content.value
                                                                    )
                                                                }}</label
                                                            >
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>

                                            <div class="w-full">
                                                <div class="users">
                                                    <div class="">
                                                        <icon
                                                            :name="
                                                                task.taskType ===
                                                                'personal'
                                                                    ? 'singleDocIcon'
                                                                    : 'usersDocIcon'
                                                            "
                                                            class=""
                                                        />
                                                    </div>
                                                    <div class="multiple-users">
                                                        <ul>
                                                            <li
                                                                v-for="(
                                                                    assignee,
                                                                    index
                                                                ) in task.assignees ??
                                                                []"
                                                                :key="
                                                                    'assignee-' +
                                                                    index
                                                                "
                                                            >
                                                                <img
                                                                    v-if="
                                                                        !!userProfilePictures[
                                                                            assignee
                                                                                ?.userId
                                                                        ]
                                                                            ?.profile_image
                                                                    "
                                                                    :src="
                                                                        userProfilePictures[
                                                                            assignee
                                                                                ?.userId
                                                                        ]
                                                                            ?.profile_image
                                                                    "
                                                                />
                                                                <p
                                                                    class="default-user-avatar-task"
                                                                    v-else
                                                                >
                                                                    {{
                                                                        getInitials(
                                                                            (userProfilePictures[
                                                                                assignee
                                                                                    ?.userId
                                                                            ]
                                                                                ?.first_name ??
                                                                                "") +
                                                                                " " +
                                                                                (userProfilePictures[
                                                                                    assignee
                                                                                        ?.userId
                                                                                ]
                                                                                    ?.last_name ??
                                                                                    "")
                                                                        )
                                                                    }}
                                                                </p>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="action-btns">
                                                    <dropdown
                                                        placement="bottom-end"
                                                    >
                                                        <template #default>
                                                            <div
                                                                class="action-task group flex items-center cursor-pointer select-none"
                                                            >
                                                                <icon
                                                                    name="bartaskIcon"
                                                                    class="cursor-pointer"
                                                                />
                                                            </div>
                                                        </template>
                                                        <template #dropdown>
                                                            <div
                                                                class="mt-2 py-2 text-sm bg-white rounded shadow-xl"
                                                            >
                                                                <div
                                                                    @click="
                                                                        openCreateTaskModal(
                                                                            status,
                                                                            'edit',
                                                                            task
                                                                        )
                                                                    "
                                                                    class="block cursor-pointer px-8 py-2 w-full text-left hover:text-white hover:bg-indigo-500"
                                                                    as="button"
                                                                >
                                                                    {{
                                                                        $t(
                                                                            "Edit"
                                                                        )
                                                                    }}
                                                                </div>
                                                                <hr />
                                                                <div
                                                                    @click="
                                                                        taskToBeEdited =
                                                                            task;
                                                                        toggleAssignBoardModal = true;
                                                                    "
                                                                    class="block cursor-pointer px-8 py-2 w-full text-left hover:text-white hover:bg-indigo-500"
                                                                    as="button"
                                                                >
                                                                    {{
                                                                        $t(
                                                                            "Assign to other board"
                                                                        )
                                                                    }}
                                                                </div>
                                                                <hr />
                                                                <div
                                                                    @click="
                                                                        taskToBeEdited =
                                                                            task;
                                                                        toggleDeleteModal = true;
                                                                    "
                                                                    class="block cursor-pointer px-8 py-2 w-full text-left hover:text-white hover:bg-indigo-500"
                                                                    as="button"
                                                                >
                                                                    {{
                                                                        $t(
                                                                            "Delete"
                                                                        )
                                                                    }}
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </dropdown>
                                                    <div
                                                        v-if="
                                                            task.priority ===
                                                            'high'
                                                        "
                                                        class="high-priority mt-3"
                                                    >
                                                        H
                                                    </div>
                                                    <div
                                                        v-if="
                                                            task.priority ===
                                                            'middle'
                                                        "
                                                        class="med-priority mt-3"
                                                    >
                                                        M
                                                    </div>
                                                    <div
                                                        v-if="
                                                            task.priority ===
                                                            'low'
                                                        "
                                                        class="low-priority mt-3"
                                                    >
                                                        L
                                                    </div>
                                                    <div
                                                        v-if="
                                                            task.priority ===
                                                            'critical'
                                                        "
                                                        class="critical-priority mt-3"
                                                    >
                                                        C
                                                    </div>
                                                    <div
                                                        v-if="
                                                            task.priority ===
                                                            'normal'
                                                        "
                                                        class="normal-priority mt-3"
                                                    >
                                                        N
                                                    </div>
                                                    <div
                                                        class="cursor-pointer mt-1"
                                                    >
                                                        <icon
                                                            v-if="
                                                                checkIfWithin24hours(
                                                                    task.dueDate,
                                                                    status
                                                                )
                                                            "
                                                            name="errorIcon"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="footer-task">
                                                    <div
                                                        class="flex items-center"
                                                    >
                                                        <div
                                                            class="cursor-pointer"
                                                            @click="
                                                                openTimeTrackerModal(
                                                                    task
                                                                )
                                                            "
                                                        >
                                                            <icon
                                                                name="clocktaskIcon"
                                                            />
                                                        </div>
                                                        <div class="">
                                                            <h4>
                                                                {{
                                                                    $t(
                                                                        "Due Date"
                                                                    )
                                                                }}
                                                            </h4>
                                                            <div
                                                                class="flex items-center mb-1"
                                                            >
                                                                <svg
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    width="13"
                                                                    height="15"
                                                                    viewBox="0 0 13 15"
                                                                    fill="none"
                                                                >
                                                                    <path
                                                                        fill-rule="evenodd"
                                                                        clip-rule="evenodd"
                                                                        d="M10.1088 1.35121C10.1088 1.03104 9.84922 0.771484 9.52905 0.771484C9.20887 0.771484 8.94932 1.03104 8.94932 1.35121V2.14683H4.61327V1.35121C4.61327 1.03104 4.35372 0.771484 4.03354 0.771484C3.71337 0.771484 3.45381 1.03104 3.45381 1.35121V2.14683H2.65702C1.57791 2.14683 0.703125 3.02162 0.703125 4.10073V6.84819V12.3457C0.703125 13.4248 1.57791 14.2996 2.65702 14.2996H10.902C11.9811 14.2996 12.8559 13.4248 12.8559 12.3457V6.84819V4.10073C12.8559 3.02162 11.9811 2.14683 10.902 2.14683H10.1088V1.35121ZM11.6965 6.26846V4.10073C11.6965 3.66197 11.3408 3.30628 10.902 3.30628H10.1088V4.09955C10.1088 4.41972 9.84922 4.67928 9.52905 4.67928C9.20887 4.67928 8.94932 4.41972 8.94932 4.09955V3.30628H4.61327V4.09955C4.61327 4.41972 4.35372 4.67928 4.03354 4.67928C3.71337 4.67928 3.45381 4.41972 3.45381 4.09955V3.30628H2.65702C2.21826 3.30628 1.86258 3.66197 1.86258 4.10073V6.26846H11.6965ZM1.86258 7.42791H11.6965V12.3457C11.6965 12.7845 11.3408 13.1402 10.902 13.1402H2.65702C2.21826 13.1402 1.86258 12.7845 1.86258 12.3457V7.42791Z"
                                                                        fill="#999999"
                                                                    />
                                                                </svg>
                                                                <p
                                                                    :style="
                                                                        checkIfWithin24hours(
                                                                            task.dueDate,
                                                                            status
                                                                        )
                                                                            ? 'color: red;'
                                                                            : ''
                                                                    "
                                                                >
                                                                    {{
                                                                        $dateFormatter(
                                                                            formatDate(
                                                                                task.dueDate.slice(
                                                                                    0,
                                                                                    10
                                                                                )
                                                                            ),
                                                                            globalLanguage
                                                                        )
                                                                    }}
                                                                </p>
                                                            </div>
                                                            <div
                                                                class="flex items-center"
                                                            >
                                                                <svg
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    width="15"
                                                                    height="15"
                                                                    viewBox="0 0 15 15"
                                                                    fill="none"
                                                                >
                                                                    <path
                                                                        fill-rule="evenodd"
                                                                        clip-rule="evenodd"
                                                                        d="M7.46682 2.01394C4.41052 2.01394 1.93289 4.49157 1.93289 7.54788C1.93289 10.6042 4.41052 13.0818 7.46682 13.0818C10.5231 13.0818 13.0008 10.6042 13.0008 7.54788C13.0008 4.49157 10.5231 2.01394 7.46682 2.01394ZM0.703125 7.54788C0.703125 3.81239 3.73134 0.78418 7.46682 0.78418C11.2023 0.78418 14.2305 3.81239 14.2305 7.54788C14.2305 11.2834 11.2023 14.3116 7.46682 14.3116C3.73134 14.3116 0.703125 11.2834 0.703125 7.54788Z"
                                                                        fill="#999999"
                                                                    />
                                                                    <path
                                                                        fill-rule="evenodd"
                                                                        clip-rule="evenodd"
                                                                        d="M7.4684 3.51758C7.80799 3.51758 8.08328 3.79287 8.08328 4.13246V7.29378L9.95279 9.16329C10.1929 9.40341 10.1929 9.79274 9.95279 10.0329C9.71266 10.273 9.32334 10.273 9.08322 10.0329L7.03361 7.98326C6.9183 7.86794 6.85352 7.71155 6.85352 7.54847V4.13246C6.85352 3.79287 7.12881 3.51758 7.4684 3.51758Z"
                                                                        fill="#999999"
                                                                    />
                                                                </svg>
                                                                <p
                                                                    :style="
                                                                        checkIfWithin24hours(
                                                                            task.dueDate,
                                                                            status
                                                                        )
                                                                            ? 'color: red;'
                                                                            : ''
                                                                    "
                                                                >
                                                                    {{
                                                                        timeFromDate(
                                                                            new Date(
                                                                                task.dueDate
                                                                            )
                                                                        )
                                                                    }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <dropdown
                                                        placement="bottom-end"
                                                    >
                                                        <template #default>
                                                            <div
                                                                class="group flex items-center cursor-pointer select-none"
                                                            >
                                                                <button
                                                                    class="secondary-btn"
                                                                >
                                                                    <icon
                                                                        name="nextIcon"
                                                                        class=""
                                                                    />
                                                                    <span
                                                                        >Action</span
                                                                    >
                                                                </button>
                                                            </div>
                                                        </template>
                                                        <template #dropdown>
                                                            <div
                                                                class="mt-2 py-2 text-sm bg-white rounded shadow-xl"
                                                            >
                                                                <div
                                                                    @mouseenter="
                                                                        showMoveDropdown = true
                                                                    "
                                                                    @mouseleave="
                                                                        showMoveDropdown = false
                                                                    "
                                                                    class="block cursor-pointer px-8 py-2 w-full text-left hover:text-white hover:bg-indigo-500"
                                                                    as="button"
                                                                >
                                                                    <div
                                                                        class="flex justify-between"
                                                                    >
                                                                        <span>{{
                                                                            $t(
                                                                                "Move To"
                                                                            )
                                                                        }}</span>
                                                                        <font-awesome-icon
                                                                            class="cursor-pointer text-xs text-gray-600 self-center"
                                                                            :icon="[
                                                                                'fa-solid',
                                                                                'fa-chevron-right',
                                                                            ]"
                                                                        />
                                                                    </div>
                                                                </div>
                                                                <hr />
                                                                <div
                                                                    @click="
                                                                        taskToBeEdited =
                                                                            task;
                                                                        toggleTaskResubmitModal = true;
                                                                    "
                                                                    class="block cursor-pointer px-8 py-2 w-full text-left hover:text-white hover:bg-indigo-500"
                                                                    as="button"
                                                                >
                                                                    {{
                                                                        $t(
                                                                            "Resubmit"
                                                                        )
                                                                    }}
                                                                </div>
                                                                <div
                                                                    v-if="
                                                                        showMoveDropdown
                                                                    "
                                                                    @mouseenter="
                                                                        showMoveDropdown = true
                                                                    "
                                                                    @mouseleave="
                                                                        showMoveDropdown = false
                                                                    "
                                                                    class="dropdown-content"
                                                                >
                                                                    <div
                                                                        v-for="(
                                                                            state,
                                                                            index
                                                                        ) in filterMoveStatuses(
                                                                            status.id
                                                                        )"
                                                                        :key="
                                                                            'status-' +
                                                                            index
                                                                        "
                                                                        class="capitalize block cursor-pointer px-8 py-2 w-full text-left hover:text-white hover:bg-indigo-500"
                                                                        as="button"
                                                                        @click="
                                                                            moveTo(
                                                                                state.id,
                                                                                task
                                                                            )
                                                                        "
                                                                    >
                                                                        {{
                                                                            $t(
                                                                                state.name
                                                                            )
                                                                        }}
                                                                    </div>
                                                                </div>
                                                                <hr />
                                                                <div
                                                                    @click="
                                                                        returnToUser(
                                                                            task.id
                                                                        )
                                                                    "
                                                                    class="block cursor-pointer px-8 py-2 w-full text-left hover:text-white hover:bg-indigo-500"
                                                                    as="button"
                                                                >
                                                                    {{
                                                                        $t(
                                                                            "Return"
                                                                        )
                                                                    }}
                                                                </div>
                                                                <hr />
                                                                <div
                                                                    @click="
                                                                        handOver(
                                                                            task
                                                                        )
                                                                    "
                                                                    class="block cursor-pointer px-8 py-2 w-full text-left hover:text-white hover:bg-indigo-500"
                                                                    as="button"
                                                                >
                                                                    {{
                                                                        $t(
                                                                            "Hand Over To"
                                                                        )
                                                                    }}
                                                                </div>
                                                                <div
                                                                    v-if="
                                                                        task.taskType ===
                                                                        'group'
                                                                    "
                                                                    @click="
                                                                        assume(
                                                                            task
                                                                        )
                                                                    "
                                                                    class="block cursor-pointer px-8 py-2 w-full text-left hover:text-white hover:bg-indigo-500"
                                                                    as="button"
                                                                >
                                                                    {{
                                                                        $t(
                                                                            "Assume"
                                                                        )
                                                                    }}
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </dropdown>
                                                </div>
                                            </div>
                                            <div
                                                v-if="
                                                    (lastCommentShow[task.id] ??
                                                        null) &&
                                                    (task?.latestComment ??
                                                        null)
                                                "
                                                class="last-comment-container"
                                            >
                                                <h4 class="font-bold">
                                                    {{ $t("Last Comment") }}
                                                </h4>
                                                <div
                                                    class="last-comment-heading flex items-center justify-between"
                                                >
                                                    <div
                                                        style="
                                                            color: #6b737a;
                                                            font-size: 15.03px;
                                                            font-style: normal;
                                                            font-weight: 400;
                                                            line-height: normal;
                                                            display: block;
                                                        "
                                                        class="task-content mt-1"
                                                    >
                                                        <QuillTextEditor
                                                            :isReadonly="true"
                                                            class="editor-instance-1"
                                                            :content="
                                                                task
                                                                    ?.latestComment
                                                                    ?.body
                                                            "
                                                            height="auto"
                                                            @delta="task.latestComment.body = $event"

                                                        />
                                                        <div class="pt-1 pl-3">
                                                            {{
                                                                task
                                                                    ?.latestComment
                                                                    ?.createdAt
                                                            }}
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="commented-user-name"
                                                    >
                                                        {{
                                                            getUsersNameFromId(
                                                                task
                                                                    ?.latestComment
                                                                    ?.userId
                                                            )
                                                        }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                v-else-if="
                                                    lastCommentShow[task.id] &&
                                                    !task?.latestComment
                                                "
                                                class="last-comment-container"
                                            >
                                                <h4 class="font-bold">
                                                    {{
                                                        $t("No Comment Exists!")
                                                    }}
                                                </h4>
                                            </div>
                                            <div class="post-footer mt-2">
                                                <div
                                                    class="add-comment flex items-center"
                                                >
                                                    <div
                                                        style="width: 90%"
                                                        class="form-group"
                                                    >
                                                        <div
                                                            class="input-group"
                                                        >
                                                            <input
                                                                @input="
                                                                    $event.target.value =
                                                                        ''
                                                                "
                                                                @click="
                                                                    openCreateTaskModal(
                                                                        status,
                                                                        'edit',
                                                                        task,
                                                                        true
                                                                    )
                                                                "
                                                                type="text"
                                                                class="form-control"
                                                                placeholder="Add a comment..."
                                                            />
                                                            <div
                                                                class="input-group-append cursor-pointer"
                                                            >
                                                                <span
                                                                    class="input-group-text"
                                                                >
                                                                    <icon
                                                                        name="sendIcon"
                                                                    />
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div
                                                        style="width: 10%"
                                                        class="form-group pl-1"
                                                    >
                                                        <font-awesome-icon
                                                            style="
                                                                font-size: 30px;
                                                            "
                                                            :class="
                                                                lastCommentShow[
                                                                    task.id
                                                                ] ?? null
                                                                    ? 'pb-2'
                                                                    : 'pt-2'
                                                            "
                                                            @click="
                                                                fetchLatestComment(
                                                                    task.id,
                                                                    task
                                                                )
                                                            "
                                                            class="cursor-pointer p-0 ml-2"
                                                            :icon="`fa-solid fa-sort-${
                                                                lastCommentShow[
                                                                    task.id
                                                                ] ?? null
                                                                    ? 'down'
                                                                    : 'up'
                                                            }`"
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </draggable>
                            </div>
                        </div>
                    </template>
                </draggable>
            </div>
        </div>
    </div>
    <CreateTaskModal
        v-if="toggleTasksModal"
        :toggleTasksModal="toggleTasksModal"
        @toggleTasksModal="
            toggleTasksModal = false;
            scrollToComment = false;
        "
        :modalType="modalType"
        :status="taskStatus"
        :upperOrder="upperOrder"
        :task="taskToBeEdited"
        :scrollToComment="scrollToComment"
        @refresh="refresh(true)"
    />
    <DeleteTaskModal
        :toggleDeleteModal="toggleDeleteModal"
        @toggleDeleteModal="toggleDeleteModal = false"
        :taskId="taskToBeEdited?.id"
        @refresh="refresh(true)"
    />
    <AssignBoardModal
        :toggleAssignBoardModal="toggleAssignBoardModal"
        @toggleAssignBoardModal="toggleAssignBoardModal = false"
        :task="taskToBeEdited"
        @refresh="refresh(true)"
    />

    <TaskResubmitModal
        :toggleTaskResubmitModal="toggleTaskResubmitModal"
        @toggleTaskResubmitModal="toggleTaskResubmitModal = false"
        :task="taskToBeEdited"
        @refresh="refresh(true)"
    />

    <AssumeTaskModal
        :toggleAssumeModal="toggleAssumeModal"
        @toggleAssumeModal="toggleAssumeModal = false"
        :task="taskToBeEdited"
        @refresh="refresh(true)"
    />
    <HandOverTaskModal
        :toggleHandOverModal="toggleHandOverModal"
        @toggleHandOverModal="toggleHandOverModal = false"
        :task="taskToBeEdited"
        @refresh="refresh(true)"
    />
    <CreateBoardModal
        :toggleBoardModal="toggleBoardModal"
        @toggleBoardModal="toggleBoardModal = false"
        @refresh="fetchBoards"
    />
    <TimeTrackerCompanyModal
        :toggleCompanyModal="toggleCompanyModal"
        @toggleCompanyModal="toggleCompanyModal = false"
    />
    <TimeTrackerGovernmentModal
        :toggleGovernmentModal="toggleGovernmentModal"
        @toggleGovernmentModal="toggleGovernmentModal = false"
    />

    <CreateStatusModal
        :toggleCreateStatusModal="toggleCreateStatusModal"
        @toggleCreateStatusModal="toggleCreateStatusModal = false"
        :status="statusToBeEdited"
        @refresh="refresh(true)"
        :modalType="'edit'"
        v-if="toggleCreateStatusModal"
    />

    <EditShareableUsersModal
        :toggleShareableUsersBoardModal="toggleShareableUsersBoardModal"
        @toggleShareableUsersBoardModal="toggleShareableUsersBoardModal = false"
        @refresh="fetchBoards"
    />
</template>

<script>
import draggable from "vuedraggable";
import Icon from "@/Components/Icon.vue";
import SelectInput from "@/Components/SelectInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";
import sidebar from "./TaskSidebar.vue";
import CreateTaskModal from "./CreateTaskModal.vue";
import DeleteTaskModal from "./DeleteTaskModal.vue";
import AssignBoardModal from "./AssignBoardModal.vue";
import AssumeTaskModal from "./AssumeTaskModal.vue";
import HandOverTaskModal from "./HandOverTaskModal.vue";
import CreateBoardModal from "./CreateBoardModal.vue";
import CreateStatusModal from "./CreateStatusModal.vue";
import EditShareableUsersModal from "./EditShareableUsersModal.vue";
import Dropdown from "@/Components/Dropdown.vue";
import TimeTrackerGovernmentModal from "./TimeTrackerGovernmentModal.vue";
import TimeTrackerCompanyModal from "./TimeTrackerCompanyModal.vue";
import TaskResubmitModal from "./TaskResubmitModal.vue";
import userProfilePicturesMixin from "@/Mixins/userProfilePicturesMixin.js";
import QuillTextEditor from "../../../Components/QuillTextEditor.vue";

export default {
    mixins: [userProfilePicturesMixin],
    props: {
        board: {
            type: Object,
            default: () => ({}),
        },
    },
    components: {
        CreateStatusModal,
        SelectInput,
        MultiSelectInput,
        Icon,
        sidebar,
        CreateTaskModal,
        Dropdown,
        DeleteTaskModal,
        AssignBoardModal,
        HandOverTaskModal,
        AssumeTaskModal,
        TimeTrackerGovernmentModal,
        TimeTrackerCompanyModal,
        CreateBoardModal,
        EditShareableUsersModal,
        draggable,
        TaskResubmitModal,
        QuillTextEditor,
    },
    computed: {
        ...mapGetters(["pusher"]),
        ...mapGetters("auth", ["users", "userProfilePictures", "user"]),
        ...mapGetters("myTasks", ["myTasks"]),
        ...mapGetters("taskStatuses", ["statuses", "assignees"]),
        ...mapGetters("projects", ["projects"]),
        ...mapGetters("taskBoards", [
            "boards",
            "boardWithTasks",
            "uniqueStatuses",
        ]),
    },
    data() {
        return {
            scrollToComment: false,
            filterStatuses: [],
            form: {
                search: "",
                statusId: "",
                inEscalation: false,
                resubmit: false,
            },
            statusesCanBeDroppedOn: [], // contains IDs of statuses on which the task can be dropped
            toggleCreateStatusModal: false,
            upperOrder: null,
            statusLinks: [],
            filterAssignees: [],
            toggleTaskResubmitModal: false,
            toggleBoardModal: false,
            toggleCompanyModal: false,
            toggleGovernmentModal: false,
            toggleAssumeModal: false,
            toggleHandOverModal: false,
            toggleAssignBoardModal: false,
            showMoveDropdown: false,
            toggleDeleteModal: false,
            toggleShareableUsersBoardModal: false,
            taskToBeEdited: null,
            statusToBeEdited: null,
            lastCommentShow: [],
            latestCommentData: {},
            taskStatus: null,
            draggedTask: null, // contains the id of the task being dragged
            toggleTasksModal: false,
            modalType: "update",
            task: null,
            key: 0,
            status: "",
            enums: {
                new: "New",
                done: "Done",
                "in-work": "In Work",
                "in-review": "In Review",
                blocked: "Blocked",
            },
            colorsByStatus: {
                new: "lightgreen",
                done: "green",
                "in-work": "orange",
                "in-review": "yellow",
                blocked: "red",
            },
            activeTab: "assignedToMe",
            activeClasses: "active",
            inactiveClasses: "in-active",
            currentUser: localStorage.getItem("user_id"),
        };
    },
    watch: {
        form: {
            handler: async function (val) {
                try {
                    await this.$store.dispatch("taskStatuses/list", {
                        taskBoardId: this.board.id,
                        assignees: this.filterAssignees,
                        setStore: true, // set the statuses store
                        ...val,
                        resubmit: !!val.resubmit ? true : undefined,
                    });
                } catch (e) {
                    console.log(e);
                }
            },
            deep: true,
        },
    },
    async mounted() {
        // await this.fetchBoardsWithTasks();
        this.$emitter.on("refresh", (id) => {
            if (this.board.id == id) this.refresh();
        });
        this.refresh(false);
    },
    methods: {
        /**
         * returns the src of the first img in htmlString
         * @param {htmlString} html string
         */
        imageFromHtmlString(htmlString) {
            // Extract src attribute from the first img tag
            var match = htmlString.match(/<img[^>]*src=["']([^"']+)["']/);

            // Check if there is a match and get the src attribute value
            var srcAttributeValue = match ? match[1] : null;

            return srcAttributeValue;
        },
        /**
         * remove images and only returns text from htmlString
         * @param {htmlString} html string
         */
        filterForImages(htmlString) {
            return htmlString.replace(/<img[^>]*>/g, "");
        },
        /**
         * sends a pusher message
         */
        sendPusherBoardUpdatedChange() {
            this.pusher.sendMessage(
                JSON.stringify({
                    boardId: this.board?.id, // the current board ID
                    action: "BoardUpdated", // pusher action
                    // username of the user who made the change
                    userName:
                        this.user?.first_name + " " + this.user?.last_name,
                }),
                (this.board?.shareableUsers ?? []).filter(
                    (userId) => userId != this.user.id
                ), // list of user IDs to whom the pusher message will be sent to. We exclude the current user from the list since he has made the change and already has latest changes
                null
            );
        },
        // triggred before drag is started on draggble component
        // prevents drag if the board is the default board
        onMove() {
            return this.board?.isDefault == 0;
        },
        /**
         * filter the status tasks with only tasks that are in escalation if the in escalation filter is applied
         * @param {status} status containing the tasks
         */
        filterTasksForEscalation(status) {
            // if in escalation filter is applied then filter by escalation
            if (this.form.inEscalation) {
                return (status?.tasks ?? []).filter((task) =>
                    this.checkIfWithin24hours(task.dueDate, status)
                );
            }
            // else return all tasks
            return status?.tasks ?? [];
        },
        /**
         * toggle delete status modal
         * @param {statusId} id of the status to be deleted
         */
        async deleteStatus(statusId) {
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
                        await this.$store.dispatch(
                            "taskStatuses/destroy",
                            statusId
                        );
                        this.refresh(true);
                    }
                });
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * changes task order vertically
         * @param {event} change event
         * @param {statusIndex} index of the status in which task is being moved around
         */
        async onTaskOrderChange(event, statusIndex) {
            if (event.moved) {
                const status = this.statuses[statusIndex];
                try {
                    if (event.moved.newIndex == 0) {
                        await this.$store.dispatch(`myTasks/changeTaskOrder`, {
                            taskId: event.moved.element.id,
                            lowerOrder:
                                status?.tasks[event.moved.newIndex + 1]?.order,
                        });
                    } else if (status?.tasks[event.moved.newIndex + 1]) {
                        await this.$store.dispatch(`myTasks/changeTaskOrder`, {
                            taskId: event.moved.element.id,
                            lowerOrder:
                                status?.tasks[event.moved.newIndex - 1]?.order,
                            upperOrder:
                                status?.tasks[event.moved.newIndex + 1]?.order,
                        });
                    } else {
                        await this.$store.dispatch(`myTasks/changeTaskOrder`, {
                            taskId: event.moved.element.id,
                            upperOrder:
                                status?.tasks[event.moved.newIndex - 1]?.order,
                        });
                    }
                } catch (e) {
                    console.log(e);
                }
                this.refresh(true);
            }
        },
        /**
         * changes status order
         * @param {event} change event
         */
        async onStatusOrderChange(event, statusIndex) {
            if (event.moved) {
                try {
                    if (event.moved.newIndex == 0) {
                        await this.$store.dispatch(
                            `taskStatuses/changeStatusOrder`,
                            {
                                id: event.moved.element.id,
                                lowerOrder:
                                    this.statuses[event.moved.newIndex + 1]
                                        ?.order,
                            }
                        );
                    } else if (this.statuses[event.moved.newIndex + 1]) {
                        await this.$store.dispatch(
                            `taskStatuses/changeStatusOrder`,
                            {
                                id: event.moved.element.id,
                                lowerOrder:
                                    this.statuses[event.moved.newIndex - 1]
                                        ?.order,
                                upperOrder:
                                    this.statuses[event.moved.newIndex + 1]
                                        ?.order,
                            }
                        );
                    } else {
                        await this.$store.dispatch(
                            `taskStatuses/changeStatusOrder`,
                            {
                                id: event.moved.element.id,
                                upperOrder:
                                    this.statuses[event.moved.newIndex - 1]
                                        ?.order,
                            }
                        );
                    }
                } catch (e) {
                    console.log(e);
                }
                this.refresh(true);
            }
        },
        /**
         * filter statuses based on task transitions in statusLinks
         * @param {fromId} id of status for which to find the transition links
         */
        filterMoveStatuses(fromId) {
            const fromStatus = this.statusLinks.find(
                (status) => status.id == fromId
            );
            return this.statuses.filter((status) =>
                fromStatus.outgoingTransitions
                    .map((transition) => transition.toStatus)
                    .some((statusId) => statusId == status.id)
            );
        },
        /**
         * adds assigneeId to filterAssignees array
         * @param {assigneeId} id to be added to filterAssignees
         */
        filterTasks(assigneeId) {
            const foundAssigneeIndex = this.filterAssignees.findIndex(
                (assignee) => assignee == assigneeId
            );
            if (foundAssigneeIndex > -1) {
                this.filterAssignees.splice(foundAssigneeIndex, 1);
            } else {
                this.filterAssignees.push(assigneeId);
            }
            this.refresh(false);
        },
        /**
         * Retrieves the initials from a string based on whitespace
         * @param {name} the string to fetch the initials from
         */
        getInitials(name) {
            const tokens = name?.split(" ");
            if (tokens)
                return `${tokens?.[0]?.[0] ?? ""}${
                    tokens?.[1]?.[0] ?? ""
                }`.toUpperCase();
            else return "";
        },
        async fetchBoards() {
            try {
                await this.$store.dispatch("taskBoards/list", {
                    userId: this.user.id,
                });
                this.$store.commit(
                    "taskBoards/selectedBoard",
                    this.boards.find((board) => board.id == this.board.id) ??
                        this.board
                );
                // get profile pictures for the assignees
                this.getUserProfilePictures(
                    (this.board?.shareableUsers ?? []).map((userId) => ({
                        userId: userId,
                    })),
                    "userId"
                );
            } catch (e) {
                console.log(e);
            }
        },
        async fetchBoardsWithTasks() {
            try {
                await this.$store.dispatch("taskBoards/listWithTasks", {
                    userId: this.user.id,
                });
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * update task upon checbox check/uncheck
         * @param {task} updated task
         */
        async updateTask(task) {
            await this.$nextTick();
            try {
                await this.$store.dispatch("myTasks/update", {
                    id: task.id,
                    data: {
                        ...task,
                        assignees: task.assignees.map((assignee) => ({
                            userId: assignee.userId,
                        })),
                    },
                });
                // upen task update send pusher message so that all the users of this board can receive the latest changes
                this.sendPusherBoardUpdatedChange();
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * refresh task listing
         * @param {sendPusherChange} boolean, determines if the pusher message should be sent or not
         */
        async refresh(sendPusherChange) {
            try {
                // if 'sendPusherChange' is true then send pusher message
                if (sendPusherChange) {
                    this.sendPusherBoardUpdatedChange();
                }
                this.upperOrder = null;
                await this.$store.dispatch("taskStatuses/list", {
                    taskBoardId: this.board.id,
                    assignees: this.filterAssignees,
                    setStore: true, // set the statuses store
                    ...this.form,
                    resubmit: !!this.form.resubmit ? true : undefined,
                });
                this.filterStatuses = this.statuses.map((status) => {
                    return {
                        id: status.id,
                        name: status.name,
                    };
                });
                const response = await this.$store.dispatch(
                    "taskStatuses/getStatusLinks",
                    this.board.id
                );
                this.statusLinks = response?.data?.data ?? [];
                // get profile pictures for the assignees
                this.getUserProfilePictures(
                    (this.board?.shareableUsers ?? []).map((userId) => ({
                        userId: userId,
                    })),
                    "userId"
                );
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * opens time tracker government/company modal based on task
         * @param {task} selected task
         */
        openTimeTrackerModal(task) {
            if (task.timeTrackerType === "company") {
                this.toggleCompanyModal = true;
                return;
            }
            this.toggleGovernmentModal = true;
        },
        async returnToUser(taskId) {
            try {
                await this.$store.dispatch("myTasks/returnTask", {
                    taskId: taskId,
                });
                this.refresh(true);
            } catch (e) {
                console.log(e);
            }
        },
        async assume(task) {
            this.taskToBeEdited = task;
            this.toggleAssumeModal = true;
        },
        async handOver(task) {
            this.taskToBeEdited = task;
            this.toggleHandOverModal = true;
        },
        /**
         * updates the task to move it to the specified statusId
         * @param {statusId} status id to move the task to
         * @param {task} task to be edited
         */
        async moveTo(statusId, task) {
            try {
                await this.$store.dispatch("myTasks/update", {
                    id: task.id,
                    data: {
                        ...task,
                        assignees: task.assignees.map((assignee) => ({
                            userId: assignee.userId,
                        })),
                        statusId: statusId,
                    },
                });
                this.refresh(true);
            } catch (e) {}
        },
        // checks if date is within 24 hours of the current date
        checkIfWithin24hours(date, status) {
            // Get the current date and time
            const currentDatetime = new Date();

            // Define the date you want to check (replace this with your specific date)
            const dateToCheck = new Date(date); // Example date and time

            // Calculate the time difference in milliseconds
            const timeDifference = currentDatetime - dateToCheck;

            const escalationHours =
                this.board.isDefault == 1
                    ? 24
                    : this.board.escalationHours ?? 0;

            // Check if the absolute value of the time difference is less than 24 hours in milliseconds
            const twentyFourHoursInMilliseconds =
                +escalationHours * 60 * 60 * 1000; // 24 hours in milliseconds
            return (
                (Math.abs(timeDifference) < twentyFourHoursInMilliseconds ||
                    currentDatetime > dateToCheck) &&
                status.isDoneStatus
            );
        },
        // Create a Date object with your date
        timeFromDate(date) {
            // Extract the hours, minutes, and seconds in 24-hour format
            const hours = date.getHours();
            const minutes = date.getMinutes();
            const seconds = date.getSeconds();

            // Format the time as a string in 24-hour format
            const timeIn24HourFormat = `${hours
                .toString()
                .padStart(2, "0")}:${minutes
                .toString()
                .padStart(2, "0")}:${seconds.toString().padStart(2, "0")}`;
            return timeIn24HourFormat;
        },
        // format the date
        formatDate(date) {
            try {
                console.log(date.toLocaleDateString());
                return date.toLocaleDateString();
            } catch (e) {
                return date;
            }
        },
        /**
         * opens the tasks modal with the modalType set to "add"
         */
        async openCreateTaskModal(
            status,
            action = "add",
            task,
            scrollToComment = false
        ) {
            this.$store.commit("showLoader", true);
            this.scrollToComment = scrollToComment;
            this.taskToBeEdited = null;
            this.modalType = action;
            this.taskStatus = status ?? this.statuses?.[0] ?? null;
            this.upperOrder =
                this.taskStatus?.tasks?.[this.taskStatus.tasks.length - 1]
                    ?.order ?? null;
            this.toggleTasksModal = true;
            if (task) {
                // fetch assignees and set contentType and checkboxes before opening the update modal
                let assignees = [];
                try {
                    const response = await this.$store.dispatch("auth/show", {
                        id: (task?.assignees ?? [])?.map(
                            (assignee) => assignee.userId
                        ),
                    });
                    assignees = response?.data ?? [];
                } catch (e) {
                    console.log(e);
                    this.$store.commit("showLoader", false);
                }
                let contentType = task?.content?.[0]?.type ?? "text";
                let taskToBeEdited = {
                    ...task,
                    assignees: assignees,
                    contentType: task?.content?.[0]?.type ?? "text",
                    checkboxes:
                        contentType === "checkboxes" ? task?.content ?? [] : [],
                    text: task?.content?.[0]?.value ?? "",
                };
                this.taskToBeEdited = taskToBeEdited;
            }
            this.$store.commit("showLoader", false);
        },
        /**
         * opens the tasks modal with the modalType set to "add"
         * @param {status} status to be updated
         */
        async openAddStatusModal(status) {
            this.statusToBeEdited = status;
            this.toggleCreateStatusModal = true;
        },
        onDragstart(event, taskId, type, status) {
            this.draggedTask = taskId; // set the draggedTask to the taskId, so that the card color can be changed
            event.dataTransfer.setData("taskId", taskId);
            event.dataTransfer.setData("type", type);
            event.dataTransfer.setData("status", status.id);
            // set the 'statusesCanBeDroppedOn' array with the IDs of the statuses that are linked to this 'status'
            this.statusesCanBeDroppedOn =
                this.statusLinks
                    .find((state) => state.id == status.id)
                    ?.outgoingTransitions?.map(
                        (transition) => transition.toStatus
                    ) ?? [];
        },
        async onDrop(event, status) {
            const fromStatus = this.statusLinks.find(
                (status) => status.id == event.dataTransfer.getData("status")
            );
            // if a connection exists between drag source and target then allow to drop
            if (
                fromStatus.outgoingTransitions.some(
                    (transition) => transition.toStatus == status.id
                )
            ) {
                event.preventDefault();
                const taskId = event.dataTransfer.getData("taskId");
                this.draggedTask = null; // set the draggedTask to null
                try {
                    await this.$store.dispatch("myTasks/changeStatus", {
                        taskId: taskId,
                        statusId: status?.id,
                    });
                    this.refresh(true);
                } catch (e) {}
            }
        },
        onDragover(event) {
            event.preventDefault();
        },
        getMilestoneName(id) {
            return (
                this.milestones.find((milestone) => milestone.id == id)?.name ??
                ""
            );
        },
        async fetchLatestComment(index, task) {
            this.lastCommentShow[index] = !this.lastCommentShow[index];
            if (this.lastCommentShow[index] ?? false) {
                let response = await this.$store.dispatch(
                    "taskComments/getLatest",
                    {
                        taskId: index,
                    }
                );
                task.latestComment = response?.data?.data ?? "";
                console.log(task.latestComment);
            }
        },
        getUsersNameFromId(id) {
            let user = this.users.find((user) => {
                return user.id === id;
            });
            return user?.first_name + " " + user?.last_name;
        },
    },
};
</script>

<style lang="scss" scoped>
.post-footer {
    padding: 0px;
    padding-top: 0;

    .add-comment {
        margin-top: 6px;
        position: relative;

        .form-group {
            margin: 0;

            .input-group {
                position: relative;

                .input-group-prepend {
                    position: absolute;
                    left: 6px;
                    top: 50%;
                    transform: translate(0, -50%);
                }

                .input-group-append {
                    position: absolute;
                    right: 6px;
                    top: 50%;
                    transform: translate(0, -50%);
                }

                .form-control {
                    padding-left: 44px;
                    padding-right: 44px;
                    border-radius: 6px;
                    border: 1px solid rgba(208, 213, 221, 0.7);
                    background: rgba(255, 255, 255, 0.15);
                    box-shadow: 0px 0px 4px 0px rgba(0, 0, 0, 0.25);
                    height: 40px;
                    color: #38414a;
                    font-size: 12px;
                    font-style: normal;
                    font-weight: 400;
                    line-height: normal;
                    width: 100%;

                    &::placeholder {
                        opacity: 1 !important;
                        color: #38414a;
                        font-size: 12px;
                        font-style: normal;
                        font-weight: 400;
                        line-height: normal;
                    }
                }
            }
        }
    }
}
.round-circle {
    border-radius: 50%;
    height: 10px;
    width: 10px;
}

.avatar {
    font-size: 1.2rem;
    background: lightgray;
    border-radius: 50%;
}

:deep(.page-item.active .page-link) {
    background-color: #2957a4;
    border-color: #2957a4;
}

:deep(.page-link) {
    color: #2957a4;
}

.dropdown-content {
    position: absolute;
    background-color: #ffffff;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    z-index: 1;
    margin-left: 130px;
    top: 0;
}

.selected-assignee {
    border: 2px solid red;
    border-radius: 50%;
}

.show-more-assignees {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    object-fit: cover;
    left: 0;
    top: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.5);
    color: white;
    font-size: 12px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
}

.default-user-avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    object-fit: cover;
    left: 0;
    top: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f59630;
    color: white;
    font-size: 12px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
}

.default-user-avatar-task {
    width: 30.061px;
    height: 30.061px;
    border-radius: 50%;
    object-fit: cover;
    left: 0;
    top: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f59630;
    color: white;
    font-size: 12px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
}

.shareable-user-img {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    -o-object-fit: cover;
    object-fit: cover;
}

.editor-instance-1 :deep(.ql-toolbar) {
    display: none !important;
}
.editor-instance-1 :deep(.ql-container) {
    border: none !important;
}
</style>

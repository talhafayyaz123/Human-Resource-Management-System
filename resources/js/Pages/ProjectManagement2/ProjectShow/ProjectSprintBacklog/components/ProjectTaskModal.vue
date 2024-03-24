<template>
    <div
        class="custom-modal"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
    >
        <div class="modal-overlay"></div>

        <div class="modal-content">
            <div
                class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0"
            >
                <div class="modal-bg modal-xl" style="height: 90vh">
                    <div class="modal-header">
                        <div class="flex items-center gap-2">
                            <h2 class="">
                                {{ $t("Prepare Rocket Launch") }}
                            </h2>
                            <CustomIcon name="iIcon" />
                        </div>
                        <div
                            class="cursor-pointer"
                            @click="$emit('toggleModal', false)"
                        >
                            <CustomIcon name="xCircleIcon" />
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="product-task-modal">
                            <div class="product-task-modal-left">
                                <div class="flex items-center gap-4">
                                    <button class="task-btn">
                                        <div class="icon">
                                            <CustomIcon name="linIcon" />
                                        </div>
                                        <span>Attach</span>
                                    </button>
                                    <button class="task-btn">
                                        <div class="icon">
                                            <CustomIcon name="SubTaskIcon" />
                                        </div>
                                        <span>Create Subtask</span>
                                    </button>
                                    <button class="task-btn">
                                        <div class="icon">
                                            <CustomIcon name="urlIcon" />
                                        </div>
                                        <span>Link Issue</span>
                                        <div class="divider"></div>
                                        <CustomIcon name="dowIcon" />
                                    </button>
                                    <dropdown
                                        :auto-close="false"
                                        placement="bottom-end"
                                    >
                                        <template #default>
                                            <div class="cursor-pointer">
                                                <CustomIcon name="dotBarIcon" />
                                            </div>
                                        </template>
                                        <template #dropdown>
                                            <div class="dropdown-menu">
                                                <ul class="dropdown-list">
                                                    <li class="dropdown-item">
                                                        <router-link
                                                            class="flex items-center"
                                                            to="/project-settings-detail"
                                                        >
                                                            <div
                                                                class="icon mr-2"
                                                            >
                                                                <CustomIcon
                                                                    name="settingIcon"
                                                                />
                                                            </div>
                                                            <span>{{
                                                                $t(
                                                                    "Project Setting"
                                                                )
                                                            }}</span>
                                                        </router-link>
                                                    </li>
                                                    <li
                                                        class="dropdown-item delete"
                                                    >
                                                        <div class="icon mr-2">
                                                            <CustomIcon
                                                                name="DeleteIcon"
                                                            />
                                                        </div>
                                                        <span>{{
                                                            $t("Delete")
                                                        }}</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </template>
                                    </dropdown>
                                </div>
                                <div class="tabs">
                                    <ul>
                                        <li>
                                            <a href="" class="active"
                                                >General</a
                                            >
                                        </li>
                                        <li>
                                            <a href="">QA Testing</a>
                                        </li>
                                        <li>
                                            <a href="">Admin</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="tab-content">
                                    <div class="tab-pane">
                                        <div class="description">
                                            <div
                                                class="flex items-center gap-2"
                                            >
                                                <h3 class="card-title">
                                                    {{ $t("Description") }}
                                                </h3>
                                                <CustomIcon name="iIcon" />
                                            </div>
                                            <div class="mt-2">
                                                <p>
                                                    We’re going to space! We
                                                    need to ensure our
                                                    astronauts are ready and
                                                    test our rocket on the
                                                    simulator so we can have a
                                                    safe launch.
                                                </p>
                                            </div>
                                        </div>
                                        <div class="attachments mt-4">
                                            <div
                                                class="flex items-center justify-between"
                                            >
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <h3 class="card-title">
                                                        {{
                                                            $t(
                                                                "Attachments (12) "
                                                            )
                                                        }}
                                                    </h3>
                                                    <CustomIcon name="iIcon" />
                                                </div>
                                                <div
                                                    class="flex items-center gap-2 action-icons"
                                                >
                                                    <span
                                                        ><CustomIcon
                                                            name="dotBarIcon"
                                                    /></span>
                                                    <span
                                                        ><CustomIcon
                                                            name="addDarkIcon"
                                                    /></span>
                                                </div>
                                            </div>
                                            <ul
                                                class="grid items-center grid-cols-7 gap-6 mt-2"
                                            >
                                                <li class="w-full">
                                                    <img
                                                        src="@/assets/images/attach.png"
                                                        alt=""
                                                    />
                                                </li>
                                                <li class="w-full">
                                                    <img
                                                        src="@/assets/images/attach.png"
                                                        alt=""
                                                    />
                                                </li>
                                                <li class="w-full">
                                                    <img
                                                        src="@/assets/images/attach.png"
                                                        alt=""
                                                    />
                                                </li>
                                                <li class="w-full">
                                                    <img
                                                        src="@/assets/images/attach.png"
                                                        alt=""
                                                    />
                                                </li>
                                                <li class="w-full">
                                                    <img
                                                        src="@/assets/images/attach.png"
                                                        alt=""
                                                    />
                                                </li>
                                                <li class="w-full">
                                                    <img
                                                        src="@/assets/images/attach.png"
                                                        alt=""
                                                    />
                                                </li>
                                                <li class="w-full">
                                                    <img
                                                        src="@/assets/images/attach.png"
                                                        alt=""
                                                    />
                                                    <div class="overlay"></div>
                                                    <h4 class="overlay-text">
                                                        +5
                                                    </h4>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="smart-checklist mt-4">
                                            <div
                                                class="flex items-center justify-between"
                                            >
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <h3 class="card-title">
                                                        {{
                                                            $t(
                                                                "Smart Checklist"
                                                            )
                                                        }}
                                                    </h3>
                                                    <CustomIcon name="iIcon" />
                                                </div>
                                                <div
                                                    class="flex items-center gap-2 action-icons"
                                                >
                                                    <span
                                                        ><CustomIcon
                                                            name="dotBarIcon"
                                                    /></span>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <div class="checklist-det">
                                                    <div
                                                        class="progress-bar"
                                                    ></div>
                                                    <div class="progress-num">
                                                        4 / 9
                                                    </div>
                                                    <button
                                                        class="progress-btn"
                                                    >
                                                        <CustomIcon
                                                            name="listingIcon"
                                                        />
                                                    </button>
                                                    <button
                                                        class="progress-btn"
                                                    >
                                                        <CustomIcon
                                                            name="editsmIcon"
                                                        />
                                                    </button>
                                                    <button
                                                        class="progress-btn"
                                                    >
                                                        <CustomIcon
                                                            name="optionsmIcon"
                                                        />
                                                    </button>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        placeholder="Add a checklist item"
                                                        name=""
                                                        id=""
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="smart-checklist mt-4">
                                            <div
                                                class="flex items-center justify-between"
                                            >
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <h3 class="card-title">
                                                        {{ $t("Checklist") }}
                                                    </h3>
                                                    <CustomIcon name="iIcon" />
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <div class="checkbox-group">
                                                    <input
                                                        type="checkbox"
                                                        id="feat9"
                                                        class="checkbox-input"
                                                    />
                                                    <label
                                                        for="feat9"
                                                        class="checkbox-label"
                                                        >The name of the
                                                        Department</label
                                                    >
                                                </div>
                                                <div
                                                    class="checkbox-group mt-2"
                                                >
                                                    <input
                                                        type="checkbox"
                                                        id="feat8"
                                                        class="checkbox-input"
                                                    />
                                                    <label
                                                        for="feat8"
                                                        class="checkbox-label"
                                                        >The name of the team
                                                    </label>
                                                </div>
                                                <div
                                                    class="checkbox-group mt-2"
                                                >
                                                    <input
                                                        type="checkbox"
                                                        id="feat7"
                                                        class="checkbox-input"
                                                    />
                                                    <label
                                                        for="feat7"
                                                        class="checkbox-label"
                                                        >Full Name of the
                                                        employee i.e.
                                                        <div class="tag blue">
                                                            ANNA
                                                        </div></label
                                                    >
                                                </div>
                                                <div
                                                    class="checkbox-group mt-2"
                                                >
                                                    <input
                                                        type="checkbox"
                                                        id="feat6"
                                                        class="checkbox-input"
                                                    />
                                                    <label
                                                        for="feat6"
                                                        class="checkbox-label"
                                                        ><div
                                                            class="progress-tag"
                                                        >
                                                            In Progress
                                                        </div>
                                                        The month of the report
                                                        in brackets i.e.
                                                        May.</label
                                                    >
                                                </div>
                                                <div
                                                    class="checkbox-group mt-2"
                                                >
                                                    <input
                                                        type="checkbox"
                                                        id="feat5"
                                                        class="checkbox-input"
                                                    />
                                                    <label
                                                        for="feat5"
                                                        class="checkbox-label"
                                                        ><div
                                                            class="progress-tag"
                                                        >
                                                            In Progress
                                                        </div>
                                                        The task’s deadline in
                                                        the following format
                                                        <div class="tag yellow">
                                                            20-06-2020
                                                        </div></label
                                                    >
                                                </div>
                                                <div
                                                    class="checkbox-group mt-2"
                                                >
                                                    <input
                                                        type="checkbox"
                                                        id="feat4"
                                                        class="checkbox-input"
                                                    />
                                                    <label
                                                        for="feat4"
                                                        class="checkbox-label"
                                                        ><div
                                                            class="progress-tag"
                                                        >
                                                            In Progress
                                                        </div>
                                                        Total number hours spent
                                                        on the task
                                                    </label>
                                                </div>
                                                <div
                                                    class="checkbox-group mt-2"
                                                >
                                                    <input
                                                        type="checkbox"
                                                        id="feat3"
                                                        class="checkbox-input"
                                                    />
                                                    <label
                                                        for="feat3"
                                                        class="checkbox-label"
                                                        >Number of leads
                                                        collected in a
                                                        month</label
                                                    >
                                                </div>
                                                <div
                                                    class="checkbox-group mt-2"
                                                >
                                                    <input
                                                        type="checkbox"
                                                        id="feat2"
                                                        class="checkbox-input"
                                                    />
                                                    <label
                                                        for="feat2"
                                                        class="checkbox-label"
                                                        >Display the date range
                                                        i.e from
                                                        <div class="tag yellow">
                                                            20-06-2020
                                                        </div>
                                                        To
                                                        <div class="tag yellow">
                                                            20-06-2020
                                                        </div></label
                                                    >
                                                </div>
                                                <div
                                                    class="checkbox-group mt-2"
                                                >
                                                    <input
                                                        type="checkbox"
                                                        id="feat1"
                                                        class="checkbox-input"
                                                    />
                                                    <label
                                                        for="feat1"
                                                        class="checkbox-label"
                                                        >Rock oof failing this
                                                        displayed task in
                                                        %</label
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="smart-checklist mt-4">
                                            <div
                                                class="flex items-center justify-between"
                                            >
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <h3 class="card-title">
                                                        {{
                                                            $t(
                                                                "Smart Checklist"
                                                            )
                                                        }}
                                                    </h3>
                                                    <CustomIcon name="iIcon" />
                                                </div>
                                                <div
                                                    class="flex items-center gap-2 action-icons"
                                                >
                                                    <span
                                                        ><CustomIcon
                                                            name="dotBarIcon"
                                                    /></span>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <div class="checklist-det">
                                                    <div
                                                        class="progress-bar"
                                                    ></div>
                                                    <h3
                                                        class="card-title"
                                                        style="
                                                            white-space: nowrap;
                                                        "
                                                    >
                                                        0% Done
                                                    </h3>
                                                </div>
                                                <div class="sub-task mt-2">
                                                    <ul>
                                                        <li>
                                                            <div
                                                                class="flex items-center"
                                                            >
                                                                <div
                                                                    class="icon"
                                                                >
                                                                    <CustomIcon
                                                                        name="subTaskIcon"
                                                                    />
                                                                </div>
                                                                <h3 class="">
                                                                    <span
                                                                        >TMTS-1</span
                                                                    >
                                                                    Ensure
                                                                    astronauts
                                                                    have
                                                                    everything
                                                                    they need
                                                                </h3>
                                                            </div>
                                                            <div
                                                                class="flex items-center gap-3"
                                                            >
                                                                <CustomIcon
                                                                    name="upYellowIcon"
                                                                />
                                                                <button
                                                                    class="ens-btn"
                                                                >
                                                                    In progress
                                                                    <div
                                                                        class=""
                                                                    >
                                                                        <CustomIcon
                                                                            name="downIcon"
                                                                        />
                                                                    </div>
                                                                </button>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div
                                                                class="flex items-center"
                                                            >
                                                                <div
                                                                    class="icon"
                                                                >
                                                                    <CustomIcon
                                                                        name="subTaskIcon"
                                                                    />
                                                                </div>
                                                                <h3 class="">
                                                                    <span
                                                                        >TMTS-1</span
                                                                    >
                                                                    Ensure
                                                                    astronauts
                                                                    have
                                                                    everything
                                                                    they need
                                                                </h3>
                                                            </div>
                                                            <div
                                                                class="flex items-center gap-3"
                                                            >
                                                                <CustomIcon
                                                                    name="upYellowIcon"
                                                                />
                                                                <button
                                                                    class="ens-btn"
                                                                >
                                                                    In progress
                                                                    <div
                                                                        class=""
                                                                    >
                                                                        <CustomIcon
                                                                            name="downIcon"
                                                                        />
                                                                    </div>
                                                                </button>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="smart-checklist mt-4">
                                            <div
                                                class="flex items-center justify-between"
                                            >
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <h3 class="card-title">
                                                        {{ $t("Activity") }}
                                                    </h3>
                                                    <CustomIcon name="iIcon" />
                                                </div>
                                                <div
                                                    class="flex items-center gap-2 action-icons"
                                                >
                                                    <span
                                                        ><CustomIcon
                                                            name="dotBarIcon"
                                                    /></span>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <div
                                                    class="flex items-center justify-between"
                                                >
                                                    <div class="tabs-show">
                                                        <ul>
                                                            <li>
                                                                <h3>Show:</h3>
                                                            </li>
                                                            <li>
                                                                <div
                                                                    class="btn"
                                                                >
                                                                    All
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div
                                                                    class="btn active"
                                                                >
                                                                    Comment
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div
                                                                    class="btn"
                                                                >
                                                                    History
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div
                                                                    class="btn"
                                                                >
                                                                    Time Tracker
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div
                                                        class="flex items-center gap-2 cursor-pointer"
                                                    >
                                                        Oldest First
                                                        <CustomIcon
                                                            name="listingIcon"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="ticket">
                                                    <div class="ticket-header">
                                                        <div
                                                            class="flex items-center gap-2"
                                                        >
                                                            <img
                                                                src="@/assets/images/tobais.png"
                                                                alt=""
                                                            />
                                                            <h3>
                                                                Tobias
                                                                Schmidt-Tudl
                                                            </h3>
                                                            <div
                                                                class="flex items-center gap-1"
                                                            >
                                                                <CustomIcon
                                                                    name="dateIcon"
                                                                />
                                                                <p>
                                                                    2023-08-20
                                                                </p>
                                                            </div>
                                                            <div
                                                                class="flex items-center gap-1"
                                                            >
                                                                <CustomIcon
                                                                    name="clockIcon"
                                                                />
                                                                <p>12:35 PM</p>
                                                            </div>
                                                        </div>
                                                        <dropdown
                                                            :auto-close="false"
                                                            placement="bottom-end"
                                                        >
                                                            <template #default>
                                                                <div
                                                                    class="cursor-pointer"
                                                                >
                                                                    <CustomIcon
                                                                        name="dotBarIcon"
                                                                    />
                                                                </div>
                                                            </template>
                                                            <template #dropdown>
                                                                <div
                                                                    class="dropdown-menu"
                                                                >
                                                                    <ul
                                                                        class="dropdown-list"
                                                                    >
                                                                        <li
                                                                            class="dropdown-item"
                                                                        >
                                                                            <router-link
                                                                                class="flex items-center"
                                                                                to="/project-settings-detail"
                                                                            >
                                                                                <div
                                                                                    class="icon mr-2"
                                                                                >
                                                                                    <CustomIcon
                                                                                        name="settingIcon"
                                                                                    />
                                                                                </div>
                                                                                <span
                                                                                    >{{
                                                                                        $t(
                                                                                            "Edit"
                                                                                        )
                                                                                    }}</span
                                                                                >
                                                                            </router-link>
                                                                        </li>
                                                                        <li
                                                                            class="dropdown-item delete"
                                                                        >
                                                                            <div
                                                                                class="icon mr-2"
                                                                            >
                                                                                <CustomIcon
                                                                                    name="DeleteIcon"
                                                                                />
                                                                            </div>
                                                                            <span
                                                                                >{{
                                                                                    $t(
                                                                                        "Delete"
                                                                                    )
                                                                                }}</span
                                                                            >
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </template>
                                                        </dropdown>
                                                    </div>
                                                    <div class="ticket-body">
                                                        <p>
                                                            Which rocket design
                                                            have we decided on?
                                                            I think it may be
                                                            beneficial to look
                                                            over existing
                                                            designs from the
                                                            simulator so we can
                                                            have a safe launch.
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="ticket-comment">
                                                    <div class="user">
                                                        <img
                                                            src="@/assets/images/tobais.png"
                                                            alt=""
                                                        />
                                                    </div>
                                                    <div class="form-group">
                                                        <div
                                                            class="input-group"
                                                        >
                                                            <div
                                                                class="input-group-prepend"
                                                            >
                                                                <span
                                                                    class="input-group-text"
                                                                >
                                                                    <CustomIcon
                                                                        name="emojiIcon"
                                                                    />
                                                                </span>
                                                            </div>
                                                            <input
                                                                type="text"
                                                                class="form-control"
                                                                placeholder="Add a comment..."
                                                            />
                                                            <div
                                                                class="input-group-append"
                                                            >
                                                                <span
                                                                    class="input-group-text cursor-pointer"
                                                                >
                                                                    <CustomIcon
                                                                        name="sendIcon"
                                                                    />
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="product-task-modal-right">
                                <div class="flex items-center gap-4">
                                    <button class="task-btn">
                                        <span>Done</span>
                                        <div class="divider"></div>
                                        <CustomIcon name="dowIcon" />
                                    </button>
                                    <h3 class="card-title">Done</h3>
                                    <button class="task-btn">
                                        <span>Actions</span>
                                        <div class="divider"></div>
                                        <CustomIcon name="dowIcon" />
                                    </button>
                                </div>
                                <div class="accordian">
                                    <div class="task-accord-item">
                                        <div
                                            class="task-accord-header"
                                            @click="toggleAccordion('section1')"
                                        >
                                            <h3>Task Details</h3>
                                            <span
                                                ><CustomIcon name="downIcon"
                                            /></span>
                                        </div>
                                        <div
                                            class="task-accord-body"
                                            v-show="
                                                activeAccordion === 'section1'
                                            "
                                        >
                                            <div class="flex items-center">
                                                <h3>Priority</h3>
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="19"
                                                        height="22"
                                                        viewBox="0 0 19 22"
                                                        fill="none"
                                                    >
                                                        <path
                                                            fill-rule="evenodd"
                                                            clip-rule="evenodd"
                                                            d="M9.01145 0.462755C9.48327 -0.00906949 10.2483 -0.00906949 10.7201 0.462755L18.4089 8.15158C18.8807 8.62341 18.8807 9.38839 18.4089 9.86021C17.9371 10.332 17.1721 10.332 16.7003 9.86021L11.0739 4.23388V20.648C11.0739 21.3153 10.533 21.8562 9.86576 21.8562C9.1985 21.8562 8.65758 21.3153 8.65758 20.648V4.23388L3.03125 9.86021C2.55942 10.332 1.79444 10.332 1.32262 9.86021C0.850794 9.38839 0.850794 8.62341 1.32262 8.15158L9.01145 0.462755Z"
                                                            fill="#E7284A"
                                                        />
                                                    </svg>
                                                    <p>Highest</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center mt-3">
                                                <h3>Epic Link</h3>
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="22"
                                                        height="23"
                                                        viewBox="0 0 22 23"
                                                        fill="none"
                                                    >
                                                        <g
                                                            clip-path="url(#clip0_6538_65035)"
                                                        >
                                                            <path
                                                                d="M6.02718 6.81104L4.20589 6.9566C3.6991 6.99712 3.24773 7.27189 2.96747 7.71044L0.867009 10.9968C0.654446 11.3294 0.620655 11.7456 0.776556 12.1102C0.932498 12.4748 1.25296 12.7289 1.63379 12.7899L3.3014 13.0571C3.6915 10.9155 4.62535 8.77919 6.02718 6.81104Z"
                                                                fill="#38414A"
                                                            />
                                                            <path
                                                                d="M9.98633 19.9736L10.2446 21.6987C10.3036 22.0927 10.5492 22.4242 10.9017 22.5855C11.0483 22.6526 11.2035 22.6857 11.3579 22.6857C11.5748 22.6857 11.7902 22.6203 11.978 22.4919L15.1549 20.319C15.5788 20.0291 15.8444 19.5621 15.8835 19.0379L16.0242 17.1538C14.1216 18.604 12.0566 19.5701 9.98633 19.9736Z"
                                                                fill="#38414A"
                                                            />
                                                            <path
                                                                d="M9.3596 18.7778C9.4175 18.7778 9.47572 18.7729 9.53382 18.7628C10.4011 18.6128 11.2371 18.3601 12.0346 18.0293L5.18057 10.939C4.86081 11.7639 4.61651 12.6287 4.47149 13.5259C4.41433 13.8796 4.52901 14.2399 4.77409 14.4935L8.59849 18.4498C8.80198 18.6603 9.07662 18.7778 9.3596 18.7778Z"
                                                                fill="#38414A"
                                                            />
                                                            <path
                                                                d="M20.026 10.5799C21.7032 7.2255 21.7653 3.68072 21.6777 2.01825C21.6476 1.44862 21.2086 0.99448 20.658 0.963431C20.3963 0.948649 20.0862 0.937988 19.7366 0.937988C17.9397 0.937988 15.0963 1.21955 12.3817 2.67207C10.2244 3.82641 7.39553 6.36814 5.72656 9.70845C5.74627 9.72438 5.76553 9.74128 5.78368 9.76006L13.1743 17.4055C13.1924 17.4243 13.2087 17.4442 13.2241 17.4646C16.4531 15.738 18.9101 12.8116 20.026 10.5799ZM12.9031 5.53547C14.1037 4.29346 16.0573 4.29333 17.258 5.53547C17.8396 6.13712 18.1599 6.93714 18.1599 7.78803C18.1599 8.63893 17.8396 9.43895 17.258 10.0406C16.6577 10.6615 15.869 10.9721 15.0805 10.9722C14.2918 10.9722 13.5035 10.6617 12.9031 10.0406C12.3214 9.43895 12.0011 8.63893 12.0011 7.78803C12.0011 6.93714 12.3214 6.13712 12.9031 5.53547Z"
                                                                fill="#38414A"
                                                            />
                                                            <path
                                                                d="M13.7736 9.13994C14.494 9.88517 15.6662 9.88521 16.3866 9.13994C16.7356 8.7789 16.9278 8.29893 16.9278 7.78838C16.9278 7.27783 16.7356 6.79786 16.3866 6.43686C16.0264 6.06423 15.5533 5.87793 15.0801 5.87793C14.607 5.87793 14.1338 6.06423 13.7736 6.43686C13.4247 6.79786 13.2324 7.27783 13.2324 7.78838C13.2324 8.29893 13.4246 8.77894 13.7736 9.13994Z"
                                                                fill="#38414A"
                                                            />
                                                            <path
                                                                d="M1.30536 18.8598C1.46299 18.8598 1.62062 18.7976 1.74084 18.6731L3.75164 16.593C3.99216 16.3442 3.99216 15.9408 3.75164 15.692C3.51115 15.4432 3.12117 15.4432 2.88065 15.692L0.869847 17.7721C0.629322 18.0209 0.629322 18.4243 0.869847 18.6731C0.99011 18.7976 1.14774 18.8598 1.30536 18.8598Z"
                                                                fill="#38414A"
                                                            />
                                                            <path
                                                                d="M5.59702 17.5982C5.35653 17.3494 4.96655 17.3494 4.72602 17.5982L0.860082 21.5975C0.619556 21.8463 0.619556 22.2497 0.860082 22.4985C0.980345 22.6229 1.13797 22.6851 1.2956 22.6851C1.45322 22.6851 1.61085 22.6229 1.73107 22.4985L5.59697 18.4993C5.83754 18.2504 5.83754 17.8471 5.59702 17.5982Z"
                                                                fill="#38414A"
                                                            />
                                                            <path
                                                                d="M6.56811 19.505L4.55735 21.5851C4.31682 21.8339 4.31682 22.2373 4.55735 22.4861C4.67761 22.6105 4.83524 22.6728 4.99282 22.6728C5.15041 22.6728 5.30808 22.6106 5.4283 22.4861L7.4391 20.406C7.67962 20.1572 7.67962 19.7538 7.4391 19.505C7.19861 19.2562 6.80863 19.2562 6.56811 19.505Z"
                                                                fill="#38414A"
                                                            />
                                                        </g>
                                                        <defs>
                                                            <clipPath
                                                                id="clip0_6538_65035"
                                                            >
                                                                <rect
                                                                    width="21.0224"
                                                                    height="21.7473"
                                                                    fill="white"
                                                                    transform="translate(0.679688 0.937988)"
                                                                />
                                                            </clipPath>
                                                        </defs>
                                                    </svg>
                                                    <p>Rocket</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center mt-3">
                                                <h3>Due Date</h3>
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <p>23-04-2024</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center mt-3">
                                                <h3>Assignee</h3>
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <img
                                                        src="@/assets/images/avatar-1.png"
                                                        alt=""
                                                    />
                                                    <p>Tobias Schmidt-Tudl</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center mt-3">
                                                <h3>Components</h3>
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <div class="tag">
                                                        Launch
                                                    </div>
                                                    <div class="tag">
                                                        Rocket
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center mt-3">
                                                <h3>Skill</h3>
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <div class="tag">
                                                        Launch
                                                    </div>
                                                    <div class="tag">
                                                        Rocket
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center mt-3">
                                                <h3>Skill Group</h3>
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <div class="tag">
                                                        Launch
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="task-accord-item">
                                        <div
                                            class="task-accord-header"
                                            @click="toggleAccordion('section2')"
                                        >
                                            <h3>Pinned Fields</h3>
                                            <span
                                                ><CustomIcon name="downIcon"
                                            /></span>
                                        </div>
                                        <div
                                            class="task-accord-body"
                                            v-show="
                                                activeAccordion === 'section2'
                                            "
                                        >
                                            <div class="flex items-center">
                                                <h3>Reporter</h3>
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <img
                                                        src="@/assets/images/avatar-1.png"
                                                        alt=""
                                                    />
                                                    <p>Tobias Schmidt-Tudl</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center mt-3">
                                                <h3>Projects</h3>
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <p>Spaceship</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center mt-3">
                                                <h3>Time Estimation</h3>
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <p>12h</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center mt-3">
                                                <h3>Time Tracker</h3>
                                                <div class="">
                                                    <p>
                                                        2w ,3d, 4h, 48m Logged
                                                    </p>
                                                    <div
                                                        class="checkbox-group mt-1"
                                                    >
                                                        <input
                                                            type="checkbox"
                                                            class="checkbox-input"
                                                            id="Subtasks"
                                                            checked
                                                        />
                                                        <label
                                                            for="Subtasks"
                                                            class="checkbox-label"
                                                            >Include
                                                            Subtasks</label
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center mt-3">
                                                <h3>Labels</h3>
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <div class="tag">
                                                        Back-Hole
                                                    </div>
                                                    <div class="tag">Earth</div>
                                                    <div class="tag">
                                                        Outer Space
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center mt-3">
                                                <h3>Automation</h3>
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <p>Rule Executions</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center mt-3">
                                                <h3>DoD</h3>
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <p>Rule Executions</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center mt-3">
                                                <h3>DoR</h3>
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <p>Rule Executions</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="task-accord-item">
                                        <div
                                            class="task-accord-header"
                                            @click="toggleAccordion('section3')"
                                        >
                                            <h3>
                                                More Fields
                                                <span
                                                    >Original estimate, Fix
                                                    versions</span
                                                >
                                            </h3>
                                            <span
                                                ><CustomIcon name="downIcon"
                                            /></span>
                                        </div>
                                        <div
                                            class="task-accord-body"
                                            v-show="
                                                activeAccordion === 'section3'
                                            "
                                        >
                                            <div class="flex items-center">
                                                <h3>Reporter</h3>
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <img
                                                        src="@/assets/images/avatar-1.png"
                                                        alt=""
                                                    />
                                                    <p>Tobias Schmidt-Tudl</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center mt-3">
                                                <h3>Projects</h3>
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <p>Spaceship</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center mt-3">
                                                <h3>Time Estimation</h3>
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <p>12h</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center mt-3">
                                                <h3>Time Tracker</h3>
                                                <div class="">
                                                    <p>
                                                        2w ,3d, 4h, 48m Logged
                                                    </p>
                                                    <div
                                                        class="checkbox-group mt-1"
                                                    >
                                                        <input
                                                            type="checkbox"
                                                            class="checkbox-input"
                                                            id="Subtasks"
                                                            checked
                                                        />
                                                        <label
                                                            for="Subtasks"
                                                            class="checkbox-label"
                                                            >Include
                                                            Subtasks</label
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center mt-3">
                                                <h3>Labels</h3>
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <div class="tag">
                                                        Back-Hole
                                                    </div>
                                                    <div class="tag">Earth</div>
                                                    <div class="tag">
                                                        Outer Space
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center mt-3">
                                                <h3>Automation</h3>
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <p>Rule Executions</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center mt-3">
                                                <h3>DoD</h3>
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <p>Rule Executions</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center mt-3">
                                                <h3>DoR</h3>
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <p>Rule Executions</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bottom flex items-start justify-between">
                                    <div><p>Create September 8, 2020, 9:42 AM</p>
                                    <p>Update 11 seconds ago</p>
                                    <p>Resolved 11 seconds ago</p></div>
                                    <div class="flex items-center gap-2">
                                        <CustomIcon name="settingIcon" />
                                        <p>Configure</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Icon from "@/Components/Icon.vue";
import SelectInput from "@/Components/SelectInput.vue";
import Pagination from "laravel-vue-pagination";
import SearchFilter from "@/Components/SearchFilter.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import Dropdown from "@/Components/Dropdown.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
export default {
    components: {
        Icon,
        Pagination,
        SearchFilter,
        PageHeader,
        SelectInput,
        Dropdown,
        MultiSelectInput,
    },

    data() {
        return {
            activeAccordion: null,
        };
    },
    methods: {
        toggleAccordion(section) {
            if (this.activeAccordion === section) {
                this.activeAccordion = null;
            } else {
                this.activeAccordion = section;
            }
        },
    },
};
</script>

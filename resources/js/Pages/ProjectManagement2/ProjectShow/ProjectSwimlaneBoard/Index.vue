<template>
    <div>
        <div class="page-header">
            <div class="page-title">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0 align-items-center">
                        <li class="breadcrumb-item">
                            <router-link to="/dashboard">{{
                                $t("Admin Portal")
                            }}</router-link>
                        </li>
                        <li class="breadcrumb-item">
                            <router-link to="/dashboard">{{
                                $t("Project")
                            }}</router-link>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="cursor-pointer">{{
                                $t("WA Board")
                            }}</span>
                        </li>
                    </ol>
                </div>
            </div>
            <div class="other-items d-flex justify-center">
                <!---========--->
                <div class="left-side flex items-center">
                    <div class="users-list mr-5">
                        <ul>
                            <li class="cursor-pointer">
                                <img
                                    src="@/assets/images/avatar-1.png"
                                    alt=""
                                />
                            </li>
                            <li class="cursor-pointer">
                                <img
                                    src="@/assets/images/avatar-2.png"
                                    alt=""
                                />
                            </li>
                            <li class="cursor-pointer">
                                <img
                                    src="@/assets/images/avatar-3.png"
                                    alt=""
                                />
                            </li>
                            <li class="cursor-pointer">
                                <img
                                    src="@/assets/images/avatar-4.png"
                                    alt=""
                                />
                            </li>
                        </ul>
                    </div>
                    <button class="filterBtn mr-4">
                        <icon name="shareIcon" class="mr-1" />
                        <span>{{ $t("Share") }}</span>
                    </button>
                    <div class="flex items-center">
                        <div
                            class="search-close"
                            @click="closeSearch"
                            v-if="showSearch"
                        >
                            <icon name="xIcon" />
                        </div>
                        <div class="search-icon" @click="searchToggle()" v-else>
                            <img
                                src="@/assets/images/searchheader.svg"
                                alt=""
                            />
                        </div>
                        <div class="search" v-if="showSearch">
                            <div class="form-group m-0">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <icon name="search" class="mr-1" />
                                        </span>
                                    </div>
                                    <input
                                        v-model="searchGlobal"
                                        type="search"
                                        placeholder="Search here"
                                        class="form-control"
                                        @blur="handleSearch"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="divider mr-4"></div>
                    <button class="filterBtn">
                        <icon name="filters" class="mr-1" />
                        <span>{{ $t("Filters") }}</span>
                    </button>
                    <div class="divider ml-4"></div>
                    <div class="mr-4 ml-4 cursor-pointer">
                        <CustomIcon name="FullScreenIcon" />
                    </div>
                    <div class="cursor-pointer">
                        <CustomIcon name="OptionIcon" />
                    </div>
                </div>
            </div>
        </div>

        <div class="project-board-page">
            <div class="swimboard">
                <div
                    class="boards"
                    style="padding: 5px 20px"
                    ref="box1" @scroll="handleScrollBox1"
                >
                    <div class="boards-col">
                        <div class="board-card sky">
                            <h3>BACKLOG <span>(03)</span></h3>
                        </div>
                    </div>
                    <div class="boards-col">
                        <div class="board-card blue">
                            <h3>SELECTED FOR DEVELOPMENT <span>(02)</span></h3>
                        </div>
                    </div>
                    <div class="boards-col">
                        <div class="board-card orange">
                            <h3>IN PROGRESS <span>(03)</span></h3>
                        </div>
                    </div>
                    <div class="boards-col">
                        <div class="board-card red">
                            <h3>RESERVED <span>(03)</span></h3>
                        </div>
                    </div>
                    <div class="boards-col">
                        <div class="board-card green">
                            <h3>DONE <span>(03)</span></h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="swimlane-board" ref="box2" @scroll="handleScrollBox2">
                <div class="swimlane-board-card">
                    <div
                        class="swimlane-board-card-header"
                        @click="toggleAccordion(1)"
                    >
                        <div class="dropdown-icon">
                            <span v-if="!isOpen(1)"
                                ><CustomIcon name="dropdownRightIcon"
                            /></span>
                            <span v-if="isOpen(1)"
                                ><CustomIcon name="dropdownIcon"
                            /></span>
                        </div>
                        <div class="user">
                            <img src="@/assets/images/tobais.png" alt="" />
                        </div>
                        <h3>Tobias Schmidt-Tudl</h3>
                    </div>
                    <div class="swimlane-board-card-body" v-if="isOpen(1)">
                        <div class="boards">
                            <div class="boards-col">
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="epicIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-3.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="themeIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-1.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Lorem ipsum dolor sit.</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon
                                                    name="initiateIcon"
                                                />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-2.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="boards-col">
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Lorem ipsum dolor sit.</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon
                                                    name="initiateIcon"
                                                />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-2.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="epicIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-3.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="themeIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-1.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="boards-col">
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="themeIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-1.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="themeIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-1.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="themeIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-1.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="boards-col">
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="epicIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-3.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="themeIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-1.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Lorem ipsum dolor sit.</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon
                                                    name="initiateIcon"
                                                />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-2.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="boards-col">
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Lorem ipsum dolor sit.</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon
                                                    name="initiateIcon"
                                                />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-2.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="epicIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-3.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="themeIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-1.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="primary-btn mt-3">
                            <span class="mr-2">
                                <CustomIcon name="addIcon" />
                            </span>
                            <span>{{ $t("Create Process") }}</span>
                        </button>
                    </div>
                </div>
                <div class="swimlane-board-card">
                    <div
                        class="swimlane-board-card-header"
                        @click="toggleAccordion(2)"
                    >
                        <div class="dropdown-icon">
                            <span v-if="!isOpen(2)"
                                ><CustomIcon name="dropdownRightIcon"
                            /></span>
                            <span v-if="isOpen(2)"
                                ><CustomIcon name="dropdownIcon"
                            /></span>
                        </div>
                        <div class="user">
                            <img src="@/assets/images/avatar-1.png" alt="" />
                        </div>
                        <h3>Mubashir Chishte</h3>
                    </div>
                    <div class="swimlane-board-card-body" v-if="isOpen(2)">
                        <div class="boards">
                            <div class="boards-col">
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="epicIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-3.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="themeIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-1.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Lorem ipsum dolor sit.</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon
                                                    name="initiateIcon"
                                                />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-2.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="boards-col">
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Lorem ipsum dolor sit.</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon
                                                    name="initiateIcon"
                                                />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-2.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="epicIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-3.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="themeIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-1.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="boards-col">
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="themeIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-1.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="themeIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-1.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="themeIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-1.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="boards-col">
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="epicIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-3.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="themeIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-1.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Lorem ipsum dolor sit.</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon
                                                    name="initiateIcon"
                                                />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-2.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="boards-col">
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Lorem ipsum dolor sit.</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon
                                                    name="initiateIcon"
                                                />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-2.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="epicIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-3.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="themeIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-1.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="primary-btn mt-3">
                            <span class="mr-2">
                                <CustomIcon name="addIcon" />
                            </span>
                            <span>{{ $t("Create Process") }}</span>
                        </button>
                    </div>
                </div>
                <div class="swimlane-board-card">
                    <div
                        class="swimlane-board-card-header"
                        @click="toggleAccordion(3)"
                    >
                        <div class="dropdown-icon">
                            <span v-if="!isOpen(3)"
                                ><CustomIcon name="dropdownRightIcon"
                            /></span>
                            <span v-if="isOpen(3)"
                                ><CustomIcon name="dropdownIcon"
                            /></span>
                        </div>
                        <div class="user">
                            <img src="@/assets/images/avatar-2.png" alt="" />
                        </div>
                        <h3>Marcel</h3>
                    </div>
                    <div class="swimlane-board-card-body" v-if="isOpen(3)">
                        <div class="boards">
                            <div class="boards-col">
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="epicIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-3.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="themeIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-1.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Lorem ipsum dolor sit.</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon
                                                    name="initiateIcon"
                                                />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-2.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="boards-col">
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Lorem ipsum dolor sit.</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon
                                                    name="initiateIcon"
                                                />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-2.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="epicIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-3.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="themeIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-1.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="boards-col">
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="themeIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-1.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="themeIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-1.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="themeIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-1.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="boards-col">
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="epicIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-3.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="themeIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-1.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Lorem ipsum dolor sit.</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon
                                                    name="initiateIcon"
                                                />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-2.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="boards-col">
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Lorem ipsum dolor sit.</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon
                                                    name="initiateIcon"
                                                />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-2.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="epicIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-3.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="themeIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-1.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="primary-btn mt-3">
                            <span class="mr-2">
                                <CustomIcon name="addIcon" />
                            </span>
                            <span>{{ $t("Create Process") }}</span>
                        </button>
                    </div>
                </div>
                <div class="swimlane-board-card">
                    <div
                        class="swimlane-board-card-header"
                        @click="toggleAccordion(1)"
                    >
                        <div class="dropdown-icon">
                            <span v-if="!isOpen(1)"
                                ><CustomIcon name="dropdownRightIcon"
                            /></span>
                            <span v-if="isOpen(1)"
                                ><CustomIcon name="dropdownIcon"
                            /></span>
                        </div>
                        <div class="user">
                            <img src="@/assets/images/tobais.png" alt="" />
                        </div>
                        <h3>Tobias Schmidt-Tudl</h3>
                    </div>
                    <div class="swimlane-board-card-body" v-if="isOpen(1)">
                        <div class="boards">
                            <div class="boards-col">
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="epicIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-3.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="themeIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-1.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Lorem ipsum dolor sit.</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon
                                                    name="initiateIcon"
                                                />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-2.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="boards-col">
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Lorem ipsum dolor sit.</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon
                                                    name="initiateIcon"
                                                />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-2.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="epicIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-3.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="themeIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-1.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="boards-col">
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="themeIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-1.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="themeIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-1.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="themeIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-1.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="boards-col">
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="epicIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-3.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="themeIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-1.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Lorem ipsum dolor sit.</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon
                                                    name="initiateIcon"
                                                />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-2.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="boards-col">
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Lorem ipsum dolor sit.</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon
                                                    name="initiateIcon"
                                                />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-2.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="epicIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-3.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="task-card">
                                    <div class="task-card-header">
                                        <h3>Filter im Finance Dashboard</h3>
                                    </div>
                                    <div class="task-card-body">
                                        <div class="area-tag">
                                            FINANCE AREA IN THE ADMIN PORTAL
                                        </div>
                                    </div>
                                    <div class="task-divider"></div>
                                    <div class="task-card-footer">
                                        <div class="task">
                                            <div class="icon">
                                                <CustomIcon name="themeIcon" />
                                            </div>
                                            <h4>WA-9</h4>
                                        </div>
                                        <div class="user">
                                            <img
                                                src="@/assets/images/avatar-1.png"
                                                alt=""
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="primary-btn mt-3">
                            <span class="mr-2">
                                <CustomIcon name="addIcon" />
                            </span>
                            <span>{{ $t("Create Process") }}</span>
                        </button>
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
import { mapGetters } from "vuex";
import SearchFilter from "@/Components/SearchFilter.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { debounce } from "@/utils/debounce";
import Dropdown from "@/Components/Dropdown.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
export default {
    computed: {
        ...mapGetters("projects", ["projects"]),
    },
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
            showSearch: false,
            form: {
                prname: "",
                participants: [],
                startDate: new Date(),
            },
            openSections: [],
            syncingScroll: false,
        };
    },
    methods: {
        handleScrollBox1() {
            if (!this.syncingScroll) {
                this.syncingScroll = true;
                this.$refs.box2.scrollLeft = this.$refs.box1.scrollLeft;
                this.syncingScroll = false;
            }
        },
        handleScrollBox2() {
            if (!this.syncingScroll) {
                this.syncingScroll = true;
                this.$refs.box1.scrollLeft = this.$refs.box2.scrollLeft;
                this.syncingScroll = false;
            }
        },
        toggleAccordion(section) {
            const index = this.openSections.indexOf(section);
            if (index === -1) {
                this.openSections.push(section);
            } else {
                this.openSections.splice(index, 1);
            }
        },
        isOpen(section) {
            return this.openSections.includes(section);
        },
        searchToggle() {
            this.showSearch = true;
        },
        closeSearch() {
            this.showSearch = false;
        },
    },
    beforeRouteEnter(to, from, next) {
        document.body.classList.add("project-management-show-page");
        document.body.classList.add("projectboard-page");
        next();
    },
    beforeRouteLeave(to, from, next) {
        document.body.classList.remove("project-management-show-page");
        document.body.classList.remove("projectboard-page");
        next();
    },
};
</script>

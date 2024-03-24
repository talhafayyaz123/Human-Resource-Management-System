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
                                $t("Story Map")
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
                </div>
                <div class="right-side">
                    <router-link to="/" class="primary-btn">
                        <div class="mr-2">
                            <CustomIcon name="saveIcon" />
                        </div>
                        <span>{{ $t("Complete Sprint") }}</span>
                    </router-link>
                </div>
            </div>
        </div>

        <div class="project-storymap-page">
            <div class="story-board" ref="box1" @scroll="handleScrollBox1">
                <div class="story-board-col">
                    <div class="story-board-card sky">
                        <h3>Book Commercial Space Travel</h3>
                        <div class="flex items-center justify-between">
                            <div class="icon flex items-center">
                                <span class="mr-1"
                                    ><CustomIcon name="epicIcon"
                                /></span>
                                <span><CustomIcon name="BlueIcon" /></span>
                            </div>
                            <p>TIS-135</p>
                        </div>
                    </div>
                </div>
                <div class="story-board-col">
                    <div class="story-board-card blue">
                        <h3>Manage Commercial Space Travel Booking</h3>
                        <div class="flex items-center justify-between">
                            <div class="icon flex items-center">
                                <span class="mr-1"
                                    ><CustomIcon name="epicIcon"
                                /></span>
                                <span><CustomIcon name="BlueIcon" /></span>
                            </div>
                            <p>TIS-135</p>
                        </div>
                    </div>
                </div>
                <div class="story-board-col">
                    <div class="story-board-card yellow">
                        <h3>Pre-Flight Zero Gravity Training</h3>
                        <div class="flex items-center justify-between">
                            <div class="icon flex items-center">
                                <span class="mr-1"
                                    ><CustomIcon name="epicIcon"
                                /></span>
                                <span><CustomIcon name="BlueIcon" /></span>
                            </div>
                            <p>TIS-135</p>
                        </div>
                    </div>
                </div>
                <div class="story-board-col">
                    <div class="story-board-card red">
                        <h3>Know Before You Go Call</h3>
                        <div class="flex items-center justify-between">
                            <div class="icon flex items-center">
                                <span class="mr-1"
                                    ><CustomIcon name="epicIcon"
                                /></span>
                                <span><CustomIcon name="BlueIcon" /></span>
                            </div>
                            <p>TIS-135</p>
                        </div>
                    </div>
                </div>
                <div class="story-board-col">
                    <div class="story-board-card green">
                        <h3>Onboard Entertainment</h3>
                        <div class="flex items-center justify-between">
                            <div class="icon flex items-center">
                                <span class="mr-1"
                                    ><CustomIcon name="epicIcon"
                                /></span>
                                <span><CustomIcon name="BlueIcon" /></span>
                            </div>
                            <p>TIS-135</p>
                        </div>
                    </div>
                </div>
                <div class="story-board-col">
                    <div class="story-board-card red">
                        <h3>Onboard Accommodation</h3>
                        <div class="flex items-center justify-between">
                            <div class="icon flex items-center">
                                <span class="mr-1"
                                    ><CustomIcon name="epicIcon"
                                /></span>
                                <span><CustomIcon name="BlueIcon" /></span>
                            </div>
                            <p>TIS-135</p>
                        </div>
                    </div>
                </div>
                <div class="story-board-col">
                    <div class="story-board-card purple">
                        <h3>Onboard Dining</h3>
                        <div class="flex items-center justify-between">
                            <div class="icon flex items-center">
                                <span class="mr-1"
                                    ><CustomIcon name="epicIcon"
                                /></span>
                                <span><CustomIcon name="BlueIcon" /></span>
                            </div>
                            <p>TIS-135</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="story-map" ref="box2" @scroll="handleScrollBox2">
                <div>
                    <div class="sprint-card">
                        <div class="flex items-center gap-3">
                            <h3>Sprint 1</h3>
                            <div class="tag">ACTIVE</div>
                            <p>
                                Customer Receive an automated response email
                                when booking
                            </p>
                            <p>Customer can book onboard dining options</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-3">
                                <h4 class="card-title">Retrospective</h4>
                                <CustomIcon name="dropdownIcon" />
                            </div>
                            <div class="tag">Complete Sprint</div>
                            <div class="board-tag dark-purple">4</div>
                            <div class="board-tag blue">3</div>
                            <div class="board-tag green">1</div>
                            <CustomIcon name="expandYellowIcon" />
                        </div>
                    </div>
                    <div class="story-board">
                        <div class="story-board-col">
                            <div class="story-det-card">
                                <p>
                                    1st Time Space Tourist / I want to be able
                                    to retrieve my travel booking information
                                </p>
                                <p class="mt-2">FY 22/23 Q1</p>
                                <div class="mt-1 meta flex items-center gap-3">
                                    <div class="flex items-center gap-2">
                                        <CustomIcon name="dateIcon" />
                                        <p>2023-08-20</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <CustomIcon name="clockIcon" />
                                        <p>12:35 PM</p>
                                    </div>
                                </div>
                                <div class="divider"></div>
                                <div class="flex items-center justify-between">
                                    <div class="icon flex items-center gap-1">
                                        <span class=""
                                            ><CustomIcon name="epicIcon"
                                        /></span>
                                        <span
                                            ><CustomIcon name="BlueIcon"
                                        /></span>
                                        <div class="no-tag">4</div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <p>TIS-135</p>
                                        <img
                                            src="@/assets/images/avatar-3.png"
                                            alt=""
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="story-board-col">
                            <div class="story-det-card">
                                <p>
                                    1st Time Space Tourist / I want to be able
                                    to retrieve my travel booking information
                                </p>
                                <p class="mt-2">FY 22/23 Q1</p>
                                <div class="mt-1 meta flex items-center gap-3">
                                    <div class="flex items-center gap-2">
                                        <CustomIcon name="dateIcon" />
                                        <p>2023-08-20</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <CustomIcon name="clockIcon" />
                                        <p>12:35 PM</p>
                                    </div>
                                </div>
                                <div class="divider"></div>
                                <div class="flex items-center justify-between">
                                    <div class="icon flex items-center gap-1">
                                        <span class=""
                                            ><CustomIcon name="themeIcon"
                                        /></span>
                                        <span
                                            ><CustomIcon name="BlueIcon"
                                        /></span>
                                        <div class="no-tag">4</div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <p>TIS-135</p>
                                        <img
                                            src="@/assets/images/avatar-2.png"
                                            alt=""
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="story-board-col">
                            <div class="story-det-card">
                                <p>
                                    1st Time Space Tourist / I want to be able
                                    to retrieve my travel booking information
                                </p>
                                <p class="mt-2">FY 22/23 Q1</p>
                                <div class="mt-1 meta flex items-center gap-3">
                                    <div class="flex items-center gap-2">
                                        <CustomIcon name="dateIcon" />
                                        <p>2023-08-20</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <CustomIcon name="clockIcon" />
                                        <p>12:35 PM</p>
                                    </div>
                                </div>
                                <div class="divider"></div>
                                <div class="flex items-center justify-between">
                                    <div class="icon flex items-center gap-1">
                                        <span class=""
                                            ><CustomIcon name="initiateIcon"
                                        /></span>
                                        <span
                                            ><CustomIcon name="BlueIcon"
                                        /></span>
                                        <div class="no-tag">4</div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <p>TIS-135</p>
                                        <img
                                            src="@/assets/images/avatar-1.png"
                                            alt=""
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="story-board-col">
                            <div class="story-det-card">
                                <p>
                                    1st Time Space Tourist / I want to be able
                                    to retrieve my travel booking information
                                </p>
                                <p class="mt-2">FY 22/23 Q1</p>
                                <div class="mt-1 meta flex items-center gap-3">
                                    <div class="flex items-center gap-2">
                                        <CustomIcon name="dateIcon" />
                                        <p>2023-08-20</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <CustomIcon name="clockIcon" />
                                        <p>12:35 PM</p>
                                    </div>
                                </div>
                                <div class="divider"></div>
                                <div class="flex items-center justify-between">
                                    <div class="icon flex items-center gap-1">
                                        <span class=""
                                            ><CustomIcon name="epicIcon"
                                        /></span>
                                        <span
                                            ><CustomIcon name="BlueIcon"
                                        /></span>
                                        <div class="no-tag">4</div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <p>TIS-135</p>
                                        <img
                                            src="@/assets/images/avatar-3.png"
                                            alt=""
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="story-board-col">
                            <div class="story-det-card">
                                <p>
                                    1st Time Space Tourist / I want to be able
                                    to retrieve my travel booking information
                                </p>
                                <p class="mt-2">FY 22/23 Q1</p>
                                <div class="mt-1 meta flex items-center gap-3">
                                    <div class="flex items-center gap-2">
                                        <CustomIcon name="dateIcon" />
                                        <p>2023-08-20</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <CustomIcon name="clockIcon" />
                                        <p>12:35 PM</p>
                                    </div>
                                </div>
                                <div class="divider"></div>
                                <div class="flex items-center justify-between">
                                    <div class="icon flex items-center gap-1">
                                        <span class=""
                                            ><CustomIcon name="themeIcon"
                                        /></span>
                                        <span
                                            ><CustomIcon name="BlueIcon"
                                        /></span>
                                        <div class="no-tag">4</div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <p>TIS-135</p>
                                        <img
                                            src="@/assets/images/avatar-2.png"
                                            alt=""
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="story-board-col">
                            <div class="story-det-card">
                                <p>
                                    1st Time Space Tourist / I want to be able
                                    to retrieve my travel booking information
                                </p>
                                <p class="mt-2">FY 22/23 Q1</p>
                                <div class="mt-1 meta flex items-center gap-3">
                                    <div class="flex items-center gap-2">
                                        <CustomIcon name="dateIcon" />
                                        <p>2023-08-20</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <CustomIcon name="clockIcon" />
                                        <p>12:35 PM</p>
                                    </div>
                                </div>
                                <div class="divider"></div>
                                <div class="flex items-center justify-between">
                                    <div class="icon flex items-center gap-1">
                                        <span class=""
                                            ><CustomIcon name="initiateIcon"
                                        /></span>
                                        <span
                                            ><CustomIcon name="BlueIcon"
                                        /></span>
                                        <div class="no-tag">4</div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <p>TIS-135</p>
                                        <img
                                            src="@/assets/images/avatar-1.png"
                                            alt=""
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="story-board-col">
                            <div class="story-det-card">
                                <p>
                                    1st Time Space Tourist / I want to be able
                                    to retrieve my travel booking information
                                </p>
                                <p class="mt-2">FY 22/23 Q1</p>
                                <div class="mt-1 meta flex items-center gap-3">
                                    <div class="flex items-center gap-2">
                                        <CustomIcon name="dateIcon" />
                                        <p>2023-08-20</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <CustomIcon name="clockIcon" />
                                        <p>12:35 PM</p>
                                    </div>
                                </div>
                                <div class="divider"></div>
                                <div class="flex items-center justify-between">
                                    <div class="icon flex items-center gap-1">
                                        <span class=""
                                            ><CustomIcon name="initiateIcon"
                                        /></span>
                                        <span
                                            ><CustomIcon name="BlueIcon"
                                        /></span>
                                        <div class="no-tag">4</div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <p>TIS-135</p>
                                        <img
                                            src="@/assets/images/avatar-1.png"
                                            alt=""
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="sprint-card">
                        <div class="flex items-center gap-3">
                            <h3>Sprint 2</h3>
                            <div class="tag">ACTIVE</div>
                            <p>
                                Customer Receive an automated response email
                                when booking
                            </p>
                            <p>Customer can book onboard dining options</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-3">
                                <h4 class="card-title">Retrospective</h4>
                                <CustomIcon name="dropdownIcon" />
                            </div>
                            <div class="tag">Complete Sprint</div>
                            <div class="board-tag dark-purple">4</div>
                            <div class="board-tag blue">3</div>
                            <div class="board-tag green">1</div>
                            <CustomIcon name="expandYellowIcon" />
                        </div>
                    </div>
                    <div class="story-board">
                        <div class="story-board-col">
                            <div class="story-det-card">
                                <p>
                                    1st Time Space Tourist / I want to be able
                                    to retrieve my travel booking information
                                </p>
                                <p class="mt-2">FY 22/23 Q1</p>
                                <div class="mt-1 meta flex items-center gap-3">
                                    <div class="flex items-center gap-2">
                                        <CustomIcon name="dateIcon" />
                                        <p>2023-08-20</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <CustomIcon name="clockIcon" />
                                        <p>12:35 PM</p>
                                    </div>
                                </div>
                                <div class="divider"></div>
                                <div class="flex items-center justify-between">
                                    <div class="icon flex items-center gap-1">
                                        <span class=""
                                            ><CustomIcon name="epicIcon"
                                        /></span>
                                        <span
                                            ><CustomIcon name="BlueIcon"
                                        /></span>
                                        <div class="no-tag">4</div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <p>TIS-135</p>
                                        <img
                                            src="@/assets/images/avatar-3.png"
                                            alt=""
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="story-board-col">
                            <div class="story-det-card">
                                <p>
                                    1st Time Space Tourist / I want to be able
                                    to retrieve my travel booking information
                                </p>
                                <p class="mt-2">FY 22/23 Q1</p>
                                <div class="mt-1 meta flex items-center gap-3">
                                    <div class="flex items-center gap-2">
                                        <CustomIcon name="dateIcon" />
                                        <p>2023-08-20</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <CustomIcon name="clockIcon" />
                                        <p>12:35 PM</p>
                                    </div>
                                </div>
                                <div class="divider"></div>
                                <div class="flex items-center justify-between">
                                    <div class="icon flex items-center gap-1">
                                        <span class=""
                                            ><CustomIcon name="themeIcon"
                                        /></span>
                                        <span
                                            ><CustomIcon name="BlueIcon"
                                        /></span>
                                        <div class="no-tag">4</div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <p>TIS-135</p>
                                        <img
                                            src="@/assets/images/avatar-2.png"
                                            alt=""
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="story-board-col">
                            <div class="story-det-card">
                                <p>
                                    1st Time Space Tourist / I want to be able
                                    to retrieve my travel booking information
                                </p>
                                <p class="mt-2">FY 22/23 Q1</p>
                                <div class="mt-1 meta flex items-center gap-3">
                                    <div class="flex items-center gap-2">
                                        <CustomIcon name="dateIcon" />
                                        <p>2023-08-20</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <CustomIcon name="clockIcon" />
                                        <p>12:35 PM</p>
                                    </div>
                                </div>
                                <div class="divider"></div>
                                <div class="flex items-center justify-between">
                                    <div class="icon flex items-center gap-1">
                                        <span class=""
                                            ><CustomIcon name="initiateIcon"
                                        /></span>
                                        <span
                                            ><CustomIcon name="BlueIcon"
                                        /></span>
                                        <div class="no-tag">4</div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <p>TIS-135</p>
                                        <img
                                            src="@/assets/images/avatar-1.png"
                                            alt=""
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="story-board-col">
                            <div class="story-det-card">
                                <p>
                                    1st Time Space Tourist / I want to be able
                                    to retrieve my travel booking information
                                </p>
                                <p class="mt-2">FY 22/23 Q1</p>
                                <div class="mt-1 meta flex items-center gap-3">
                                    <div class="flex items-center gap-2">
                                        <CustomIcon name="dateIcon" />
                                        <p>2023-08-20</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <CustomIcon name="clockIcon" />
                                        <p>12:35 PM</p>
                                    </div>
                                </div>
                                <div class="divider"></div>
                                <div class="flex items-center justify-between">
                                    <div class="icon flex items-center gap-1">
                                        <span class=""
                                            ><CustomIcon name="epicIcon"
                                        /></span>
                                        <span
                                            ><CustomIcon name="BlueIcon"
                                        /></span>
                                        <div class="no-tag">4</div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <p>TIS-135</p>
                                        <img
                                            src="@/assets/images/avatar-3.png"
                                            alt=""
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="story-board-col">
                            <div class="story-det-card">
                                <p>
                                    1st Time Space Tourist / I want to be able
                                    to retrieve my travel booking information
                                </p>
                                <p class="mt-2">FY 22/23 Q1</p>
                                <div class="mt-1 meta flex items-center gap-3">
                                    <div class="flex items-center gap-2">
                                        <CustomIcon name="dateIcon" />
                                        <p>2023-08-20</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <CustomIcon name="clockIcon" />
                                        <p>12:35 PM</p>
                                    </div>
                                </div>
                                <div class="divider"></div>
                                <div class="flex items-center justify-between">
                                    <div class="icon flex items-center gap-1">
                                        <span class=""
                                            ><CustomIcon name="themeIcon"
                                        /></span>
                                        <span
                                            ><CustomIcon name="BlueIcon"
                                        /></span>
                                        <div class="no-tag">4</div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <p>TIS-135</p>
                                        <img
                                            src="@/assets/images/avatar-2.png"
                                            alt=""
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="story-board-col">
                            <div class="story-det-card">
                                <p>
                                    1st Time Space Tourist / I want to be able
                                    to retrieve my travel booking information
                                </p>
                                <p class="mt-2">FY 22/23 Q1</p>
                                <div class="mt-1 meta flex items-center gap-3">
                                    <div class="flex items-center gap-2">
                                        <CustomIcon name="dateIcon" />
                                        <p>2023-08-20</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <CustomIcon name="clockIcon" />
                                        <p>12:35 PM</p>
                                    </div>
                                </div>
                                <div class="divider"></div>
                                <div class="flex items-center justify-between">
                                    <div class="icon flex items-center gap-1">
                                        <span class=""
                                            ><CustomIcon name="initiateIcon"
                                        /></span>
                                        <span
                                            ><CustomIcon name="BlueIcon"
                                        /></span>
                                        <div class="no-tag">4</div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <p>TIS-135</p>
                                        <img
                                            src="@/assets/images/avatar-1.png"
                                            alt=""
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="story-board-col">
                            <div class="story-det-card">
                                <p>
                                    1st Time Space Tourist / I want to be able
                                    to retrieve my travel booking information
                                </p>
                                <p class="mt-2">FY 22/23 Q1</p>
                                <div class="mt-1 meta flex items-center gap-3">
                                    <div class="flex items-center gap-2">
                                        <CustomIcon name="dateIcon" />
                                        <p>2023-08-20</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <CustomIcon name="clockIcon" />
                                        <p>12:35 PM</p>
                                    </div>
                                </div>
                                <div class="divider"></div>
                                <div class="flex items-center justify-between">
                                    <div class="icon flex items-center gap-1">
                                        <span class=""
                                            ><CustomIcon name="initiateIcon"
                                        /></span>
                                        <span
                                            ><CustomIcon name="BlueIcon"
                                        /></span>
                                        <div class="no-tag">4</div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <p>TIS-135</p>
                                        <img
                                            src="@/assets/images/avatar-1.png"
                                            alt=""
                                        />
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

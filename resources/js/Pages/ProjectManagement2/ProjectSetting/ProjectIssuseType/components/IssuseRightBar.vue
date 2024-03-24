<template>
    <div class="issuse-list">
        <ul class="">
            <li class="">
                <router-link to="/project-settings">
                    <div class="flex items-center">
                        <div class="icon">
                            <CustomIcon name="themeIcon" />
                        </div>
                        <span>{{ $t("Theme") }}</span>
                    </div>
                    <div class="next-ico">
                        <CustomIcon name="nextBoldIcon" />
                    </div>
                </router-link>
            </li>
            <li class="">
                <router-link to="/project-settings">
                    <div class="flex items-center">
                        <div class="icon">
                            <CustomIcon name="initiateIcon" />
                        </div>
                        <span>{{ $t("Initiative") }}</span>
                    </div>
                    <div class="next-ico">
                        <CustomIcon name="nextBoldIcon" />
                    </div>
                </router-link>
            </li>
            <li class="">
                <router-link to="/project-settings">
                    <div class="flex items-center">
                        <div class="icon">
                            <CustomIcon name="epicIcon" />
                        </div>
                        <span>{{ $t("Epic") }}</span>
                    </div>
                    <div class="next-ico">
                        <CustomIcon name="nextBoldIcon" />
                    </div>
                </router-link>
            </li>
            <li class="">
                <router-link to="/project-settings">
                    <div class="flex items-center">
                        <div class="icon">
                            <CustomIcon name="requestIcon" />
                        </div>
                        <span>{{ $t("Change Request") }}</span>
                    </div>
                    <div class="next-ico">
                        <CustomIcon name="nextBoldIcon" />
                    </div>
                </router-link>
            </li>
            <li class="">
                <router-link to="/project-settings">
                    <div class="flex items-center">
                        <div class="icon">
                            <CustomIcon name="SupportIcon" />
                        </div>
                        <span>{{ $t("Support") }}</span>
                    </div>
                    <div class="next-ico">
                        <CustomIcon name="nextBoldIcon" />
                    </div>
                </router-link>
            </li>
            <li class="divider"></li>
            <li class="">
                <router-link to="/project-settings-issuse-type">
                    <div class="flex items-center">
                        <div class="icon">
                            <CustomIcon name="taskIcon" />
                        </div>
                        <span>{{ $t("Task") }}</span>
                    </div>
                    <div class="next-ico">
                        <CustomIcon name="nextBoldIcon" />
                    </div>
                </router-link>
            </li>
            <li class="divider"></li>
            <li class="">
                <router-link to="/project-settings">
                    <div class="flex items-center">
                        <div class="icon">
                            <CustomIcon name="subTaskIcon" />
                        </div>
                        <span>{{ $t("Sub Task") }}</span>
                    </div>
                    <div class="next-ico">
                        <CustomIcon name="nextBoldIcon" />
                    </div>
                </router-link>
            </li>
            <li class="">
                <router-link to="/project-settings">
                    <div class="flex items-center">
                        <div class="icon">
                            <CustomIcon name="addIssuseIcon" />
                        </div>
                        <span>{{ $t("Add Issue Type") }}</span>
                    </div>
                </router-link>
            </li>
        </ul>
    </div>
</template>

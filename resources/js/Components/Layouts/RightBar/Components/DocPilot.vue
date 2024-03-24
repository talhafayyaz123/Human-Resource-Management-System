<template>
    <div class="doc-pilot">
        <div class="close-overlay" @click="closeDocPilot()"></div>
        <div class="docpilot">
            <div class="close-feeds" @click="closeDocPilot()">
                <img src="@/assets/images/doc-pilot.png" alt="" />
            </div>
            <div class="feeds-border"></div>
            <div class="feeds-header">
                <h2>DOC PILOT</h2>
            </div>
            <div class="feeds-border"></div>
            <div class="feeds-body">
                <div class="docpilot-actions">
                    <div class="flex items-center">
                        <div class="item mr-2" >
                            <icon name="upDocIcon" />
                        </div>
                        <div class="flex items-center">
                            <svg class="mr-1" xmlns="http://www.w3.org/2000/svg" width="24" height="25" viewBox="0 0 24 25" fill="none">
                                <path
                                    d="M5.51845 18.5932L5.51845 5.66945C5.51845 4.26907 6.65666 3.13086 8.05704 3.13086L18.2114 3.13086C19.6118 3.13086 20.75 4.26907 20.75 5.66945L20.75 9.73489C20.75 10.2463 20.5977 10.7402 20.3106 11.162L19.1031 12.9363C18.9721 13.1283 18.9038 13.3526 18.9038 13.5852L18.9038 18.5923C18.9038 19.9926 17.7655 21.1309 16.3652 21.1309L8.05704 21.1309C6.65666 21.1309 5.51845 19.9936 5.51845 18.5932ZM19.3653 5.66945C19.3653 5.03342 18.8474 4.51555 18.2114 4.51555L8.05704 4.51555C7.42101 4.51555 6.90314 5.03342 6.90314 5.66945L6.90314 18.5932C6.90314 19.2292 7.42101 19.7471 8.05704 19.7471L16.3652 19.7471C17.0012 19.7471 17.5191 19.2292 17.5191 18.5932L17.5191 13.5862C17.5191 13.0748 17.6714 12.5818 17.9585 12.159L19.1659 10.3838C19.297 10.1918 19.3653 9.96752 19.3653 9.73489L19.3653 5.66945Z"
                                    fill="white" />
                            </svg>
                            <h3>4500022663</h3>

                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="item mr-2">
                            <icon name="addDocIcon" />
                        </div>
                        <div class="item mr-2">
                            <icon name="downloadIcon" />
                        </div>
                        <div class="item mr-2">
                            <icon name="rightDocIcon" />
                        </div>
                        <div class="divider mr-2"></div>
                        <div class="item mr-2">
                            <icon name="search" />
                        </div>
                        <div class="item">
                            <icon name="settingIcon" />
                        </div>
                    </div>
                </div>
                <div class="doc-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Meta Mask</th>
                                <th>Uploader</th>
                                <th>Version</th>
                                <th>Date/Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="flex items-center">
                                        <Icon name="pdfIcon" class="mr-1" />
                                        <span>Invoice</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center">
                                        <span>Ordner</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center">
                                        <img src="@/assets/images/avatar-3.png"
                                            style="width: 30px; height: 30px;border-radius:50%;" alt="" class="mr-1">
                                        <span>Backes</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center">
                                        <span>Version 1</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center date">
                                        <div class="flex items-center mr-2">
                                            <icon name="dateIcon" class="mr-1" />
                                            <span>2023-08-20</span>
                                        </div>
                                        <div class="flex items-center">
                                            <icon name="clockIcon" class="mr-1" />
                                            <span>12:35 PM</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center">
                                        <Icon name="eyeIcon" class="mr-1" />
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="flex items-center">
                                        <Icon name="pdfIcon" class="mr-1" />
                                        <span>Invoice</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center">
                                        <span>Ordner</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center">
                                        <img src="@/assets/images/avatar-3.png"
                                            style="width: 30px; height: 30px;border-radius:50%;" alt="" class="mr-1">
                                        <span>Backes</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center">
                                        <span>Version 1</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center date">
                                        <div class="flex items-center mr-2">
                                            <icon name="dateIcon" class="mr-1" />
                                            <span>2023-08-20</span>
                                        </div>
                                        <div class="flex items-center">
                                            <icon name="clockIcon" class="mr-1" />
                                            <span>12:35 PM</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center">
                                        <Icon name="eyeIcon" class="mr-1" />
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="flex items-center">
                                        <Icon name="pdfIcon" class="mr-1" />
                                        <span>Invoice</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center">
                                        <span>Ordner</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center">
                                        <img src="@/assets/images/avatar-3.png"
                                            style="width: 30px; height: 30px;border-radius:50%;" alt="" class="mr-1">
                                        <span>Backes</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center">
                                        <span>Version 1</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center date">
                                        <div class="flex items-center mr-2">
                                            <icon name="dateIcon" class="mr-1" />
                                            <span>2023-08-20</span>
                                        </div>
                                        <div class="flex items-center">
                                            <icon name="clockIcon" class="mr-1" />
                                            <span>12:35 PM</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center">
                                        <Icon name="eyeIcon" class="mr-1" />
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="flex items-center">
                                        <Icon name="pdfIcon" class="mr-1" />
                                        <span>Invoice</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center">
                                        <span>Ordner</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center">
                                        <img src="@/assets/images/avatar-3.png"
                                            style="width: 30px; height: 30px;border-radius:50%;" alt="" class="mr-1">
                                        <span>Backes</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center">
                                        <span>Version 1</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center date">
                                        <div class="flex items-center mr-2">
                                            <icon name="dateIcon" class="mr-1" />
                                            <span>2023-08-20</span>
                                        </div>
                                        <div class="flex items-center">
                                            <icon name="clockIcon" class="mr-1" />
                                            <span>12:35 PM</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center">
                                        <Icon name="eyeIcon" class="mr-1" />
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="flex items-center">
                                        <Icon name="pdfIcon" class="mr-1" />
                                        <span>Invoice</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center">
                                        <span>Ordner</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center">
                                        <img src="@/assets/images/avatar-3.png"
                                            style="width: 30px; height: 30px;border-radius:50%;" alt="" class="mr-1">
                                        <span>Backes</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center">
                                        <span>Version 1</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center date">
                                        <div class="flex items-center mr-2">
                                            <icon name="dateIcon" class="mr-1" />
                                            <span>2023-08-20</span>
                                        </div>
                                        <div class="flex items-center">
                                            <icon name="clockIcon" class="mr-1" />
                                            <span>12:35 PM</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center">
                                        <Icon name="eyeIcon" class="mr-1" />
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="feeds-border"></div>
            <div class="feeds-footer">
                <h3>Offer // 4500022663</h3>
                <h3>Entries 14</h3>
            </div>
        </div>
    </div>
</template>
<script>
import Icon from "../../../Icon.vue";

export default {
    components: {
        Icon,
    },
    methods: {
        closeDocPilot() {
            this.$emit('closeDocPilot');
        }
    }
}
</script>

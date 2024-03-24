<template>
    <div class="planning-tooltip">
        <div class="tooltip-edge">
            <CustomIcon name="TooltipEdgeIcon" />
        </div>
        <div class="flex flex-wrap w-full h-full">
            <div class="det-left">
                <ul>
                    <li>
                        <div class="name">
                            <h3>Project Name:</h3>
                        </div>
                        <div class="desc">
                            <p>{{props.data?.name}}</p>
                        </div>
                    </li>
                    <li>
                        <div class="name">
                            <h3>Project Number:</h3>
                        </div>
                        <div class="desc">
                            <p>{{ props.data?.projectNumber }}</p>
                        </div>
                    </li>
                    <li>
                        <div class="name">
                            <h3>Project Description:</h3>
                        </div>
                        <div class="desc">
                            <p>
                                {{props.data?.description}}
                            </p>
                        </div>
                    </li>
                    <li>
                        <div class="name">
                            <h3>Customer:</h3>
                        </div>
                        <div class="desc">
                            <p>{{ props.data?.companyName }}</p>
                        </div>
                    </li>
                    <li class="items-center">
                        <div class="name">
                            <h3>Project Status:</h3>
                        </div>
                        <div class="desc">
                            <div class="tag">{{props.data?.status}}</div>
                        </div>
                    </li>
                    <li class="items-center">
                        <div class="name">
                            <h3>Escalation Level:</h3>
                        </div>
                        <div class="desc">
                            <div class="flag-icon cursor-pointer">
                                <CustomIcon name="flagIcon" />
                            </div>
                        </div>
                    </li>
                    <li class="items-center">
                        <div class="name">
                            <h3>Internal Project:</h3>
                        </div>
                        <div class="desc flex items-center">
                            <div class="checkbox-group mr-3">
                                <input
                                    type="checkbox"
                                    class="checkbox-input"
                                    id="yes"
                                />
                                <label for="yes" class="checkbox-label"
                                    >Yes</label
                                >
                            </div>
                            <div class="checkbox-group">
                                <input
                                    type="checkbox"
                                    class="checkbox-input"
                                    id="no"
                                />
                                <label for="no" class="checkbox-label"
                                    >No</label
                                >
                            </div>
                        </div>
                    </li>
                    <li class="items-center">
                        <div class="name">
                            <h3>Project Manager:</h3>
                        </div>
                        <div class="desc">
                            <ul class="project-team">
                                <li>
                                    <img
                                        src="@/assets/images/user.png"
                                        alt=""
                                    />
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="items-center">
                        <div class="name">
                            <h3>Project Team:</h3>
                        </div>
                        <div class="desc">
                            <ul class="project-team">
                                <li>
                                    <img
                                        src="@/assets/images/user.png"
                                        alt=""
                                    />
                                </li>
                                <li>
                                    <img
                                        src="@/assets/images/user.png"
                                        alt=""
                                    />
                                </li>
                                <li>
                                    <img
                                        src="@/assets/images/user.png"
                                        alt=""
                                    />
                                </li>
                                <li>
                                    <img
                                        src="@/assets/images/user.png"
                                        alt=""
                                    />
                                </li>
                                <li>
                                    <img
                                        src="@/assets/images/user.png"
                                        alt=""
                                    />
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <div class="name">
                            <h3>Start Date:</h3>
                        </div>
                        <div class="desc">
                            <p>{{props.data?.actualStartDate}}</p>
                        </div>
                    </li>
                    <li>
                        <div class="name">
                            <h3>End Date:</h3>
                        </div>
                        <div class="desc">
                            <p>{{props.data?.actualFinishedDate}}</p>
                        </div>
                    </li>
                    <li>
                        <div class="name">
                            <h3>Order Confirmation:</h3>
                        </div>
                        <div class="desc">
                            <p>AB2023-0777755</p>
                        </div>
                    </li>
                    <li class="items-center">
                        <div class="name">
                            <h3>Special Billing:</h3>
                        </div>
                        <div class="desc flex items-center">
                            <div class="checkbox-group mr-3">
                                <input
                                    type="checkbox"
                                    class="checkbox-input"
                                    id="yes"
                                />
                                <label for="yes" class="checkbox-label"
                                    >Yes</label
                                >
                            </div>
                            <div class="checkbox-group">
                                <input
                                    type="checkbox"
                                    class="checkbox-input"
                                    id="no"
                                />
                                <label for="no" class="checkbox-label"
                                    >No</label
                                >
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="name">
                            <h3>Available Budget:</h3>
                        </div>
                        <div class="desc">
                            <p>00</p>
                        </div>
                    </li>
                    <li>
                        <div class="name">
                            <h3>Hours:</h3>
                        </div>
                        <div class="desc">
                            <ul class="h-list">
                                <li>
                                    <div class="h-btn red">
                                        <span>Used:</span> 60 h
                                    </div>
                                </li>
                                <li>
                                    <div class="h-btn green">
                                        <span>Open:</span> 60 h
                                    </div>
                                </li>
                                <li>
                                    <div class="h-btn blue">
                                        <span>Planned Hours:</span> {{props.data.estimatedTime}} h
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="det-right">
                <div class="status-message">
                    <div class="flex items-center">
                        <h3 class="mr-2">Status Messages</h3>
                        <CustomIcon name="iIcon" />
                    </div>
                    <div class="message-card mt-3">
                        <div class="message-card-header">
                            <div class="flex items-center">
                                <div class="user-det mr-2">
                                    <img
                                        src="@/assets/images/avatar-1.png"
                                        alt=""
                                    />
                                    <h4>Muhammad Hassan</h4>
                                </div>
                                <div class="card-meta">
                                    <div class="flex items-center mr-1">
                                        <div class="mr-1">
                                            <CustomIcon name="datePrIcon" />
                                        </div>
                                        <p>Monday - 13th Nov</p>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="mr-1">
                                            <CustomIcon name="clockPrIcon" />
                                        </div>
                                        <p>12:00 PM</p>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <CustomIcon name="editCircleIcon" />
                            </div>
                        </div>
                        <div class="message-card-content">
                            <h2>Total cost plan value is adhered to</h2>
                            <p>
                                Lorem Ipsum is simply dummy text of the printing
                                and typesetting industry. Lorem Ipsum has been
                                the industry's standard dummy text ever since
                                the 1500s, when an unknown printer took a galley
                                of type and scrambled it to make a type specimen
                                book. It has survived not only five centuries,
                                but also the leap into electronic typesetting,
                                remaining essentially unchanged.
                            </p>
                        </div>
                    </div>
                    <div class="message-card mt-1 orange">
                        <div class="message-card-header">
                            <div class="flex items-center">
                                <div class="user-det mr-2">
                                    <img
                                        src="@/assets/images/avatar-1.png"
                                        alt=""
                                    />
                                    <h4>Muhammad Hassan</h4>
                                </div>
                                <div class="card-meta">
                                    <div class="flex items-center mr-1">
                                        <div class="mr-1">
                                            <CustomIcon name="datePrIcon" />
                                        </div>
                                        <p>Monday - 13th Nov</p>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="mr-1">
                                            <CustomIcon name="clockPrIcon" />
                                        </div>
                                        <p>12:00 PM</p>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <CustomIcon name="editCircleIcon" />
                            </div>
                        </div>
                        <div class="message-card-content">
                            <h2>Total cost plan value is adhered to</h2>
                            <p>
                                Lorem Ipsum is simply dummy text of the printing
                                and typesetting industry. Lorem Ipsum has been
                                the industry's standard dummy text ever since
                                the 1500s, when an unknown printer took a galley
                                of type and scrambled it to make a type specimen
                                book. It has survived not only five centuries,
                                but also the leap into electronic typesetting,
                                remaining essentially unchanged.
                            </p>
                        </div>
                    </div>
                    <div class="message-card mt-1 sky">
                        <div class="message-card-header">
                            <div class="flex items-center">
                                <div class="user-det mr-2">
                                    <img
                                        src="@/assets/images/avatar-1.png"
                                        alt=""
                                    />
                                    <h4>Muhammad Hassan</h4>
                                </div>
                                <div class="card-meta">
                                    <div class="flex items-center mr-1">
                                        <div class="mr-1">
                                            <CustomIcon name="datePrIcon" />
                                        </div>
                                        <p>Monday - 13th Nov</p>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="mr-1">
                                            <CustomIcon name="clockPrIcon" />
                                        </div>
                                        <p>12:00 PM</p>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <CustomIcon name="editCircleIcon" />
                            </div>
                        </div>
                        <div class="message-card-content">
                            <h2>Total cost plan value is adhered to</h2>
                            <p>
                                Lorem Ipsum is simply dummy text of the printing
                                and typesetting industry. Lorem Ipsum has been
                                the industry's standard dummy text ever since
                                the 1500s, when an unknown printer took a galley
                                of type and scrambled it to make a type specimen
                                book. It has survived not only five centuries,
                                but also the leap into electronic typesetting,
                                remaining essentially unchanged.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
});


</script>



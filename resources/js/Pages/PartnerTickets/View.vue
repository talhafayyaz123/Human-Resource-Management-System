<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <div class="flex flex-wrap">
            <div class="lg:w-8/12 p-2 w-full">
                <div class="card w-full bg-white rounded-md shadow p-3">
                    <div class="flex justify-between py-2">
                        <div class="flex">
                            <p
                                class="mr-5"
                                style="text-align: center; color: gray"
                            >
                                Ticket{{ ticket.ticketNumber }}
                            </p>
                            <h1 style="color: #2957a4; font-weight: bold">
                                {{ ticket.title }}
                            </h1>
                        </div>
                        <p style="text-align: center; color: gray">
                            {{ $t("Contact") }} {{ $t("Type") }}:
                            {{ $dashedToRegularFormat(ticket?.contactType) }}
                        </p>
                    </div>
                    <div
                        style="
                            background-color: #fff;
                            min-height: 500px;
                            max-height: 500px;
                            overflow: auto;
                        "
                        class="mt-4 p-2 chat"
                    >
                        <div
                            :class="[
                                'flex',
                                'chat_box',
                                'comment',
                                user?.id !== comment.userId
                                    ? 'justify-end'
                                    : '',
                            ]"
                            v-for="comment in comments?.data ?? []"
                            :key="'comment-' + comment.id"
                        >
                            <div
                                class="flex justify-center items-center"
                                :style="{
                                    backgroundImage:
                                        'url(' +
                                        (userProfilePictures?.[comment.userId]
                                            ?.profile_image ?? '') +
                                        ')',
                                }"
                                style="
                                    background-position: center center;
                                    background-repeat: no-repeat;
                                    background-size: cover;
                                    min-height: 40px;
                                    min-width: 40px;
                                    border-radius: 50%;
                                    background-color: #2957a4;
                                    color: white;
                                    align-self: start;
                                    position: absolute;
                                "
                            >
                                <span
                                    v-if="
                                        !userProfilePictures?.[comment.userId]
                                    "
                                >
                                    {{
                                        getInitials(
                                            comment.userName ||
                                                usernames[comment.userId]
                                        )
                                    }}
                                </span>
                            </div>
                            <div
                                v-if="user?.id === comment.userId"
                                style="width: 90%; margin: 0 0 1rem 3.5rem"
                                class="talk-bubble tri-right left-in relative"
                                :class="{
                                    'is-archived': comment.isArchived == 1,
                                }"
                            >
                                <div class="talktext mt-2 ql-snow">
                                    <div
                                        class="ql-editor"
                                        v-html="comment.text"
                                    ></div>
                                </div>
                                <p style="color: gray; font-size: 0.8rem">
                                    <span v-if="comment.time">
                                        {{ $t("Accounted Time") }}:
                                        {{ comment.time }}
                                    </span>
                                    <span v-else>
                                        {{ $t("Accounted Time") }}:
                                        {{ $t("Not Set") }}
                                    </span>
                                    <span class="flex">
                                        <span
                                            v-for="(
                                                file, index
                                            ) in comment.attachment"
                                            :key="index"
                                            class="flex pt-3"
                                            :class="[index > 0 ? 'ml-4' : '']"
                                        >
                                            <font-awesome-icon
                                                class="pr-2 self-center cursor-pointer"
                                                icon="fa-solid fa-cloud-arrow-up"
                                                @click="
                                                    downloadAttachment(
                                                        file.id,
                                                        file.viewable_name
                                                    )
                                                "
                                            />
                                            <span>{{
                                                file.viewable_name
                                            }}</span>
                                        </span>
                                    </span>
                                    {{
                                        comment.isArchived == 1
                                            ? " - " + $t("Is Deleted")
                                            : ""
                                    }}
                                </p>

                                <div
                                    v-if="comment?.visibility === 'internal'"
                                    class="bg-red-500 text-white px-2 rounded font-bold internal-tag"
                                >
                                    <p>{{ $t("Internal") }}</p>
                                </div>
                                <p
                                    style="
                                        color: gray;
                                        text-align: end;
                                        margin: 0.5rem;
                                        font-size: 0.8rem;
                                    "
                                >
                                    ~{{
                                        comment.userName ||
                                        usernames[comment.userId]
                                    }}
                                </p>
                                <p
                                    style="
                                        color: gray;
                                        font-size: 0.8rem;
                                        position: absolute;
                                        right: 0;
                                    "
                                >
                                    <!-- {{ getTimeString(comment.createdAt) }} -->
                                    {{ $dateFormatter(comment.createdAt, globalLanguage) }}
                                </p>
                            </div>
                            <div
                                v-if="user?.id !== comment.userId"
                                style="width: 90%; margin: 0 3.5rem 1rem 0"
                                class="talk-bubble tri-right right-in"
                            >
                                <div class="talktext mt-2 ql-snow">
                                    <div
                                        class="ql-editor"
                                        v-html="comment.text"
                                    ></div>
                                </div>
                                <p
                                    style="
                                        color: gray;
                                        margin: 0.5rem;
                                        font-size: 0.8rem;
                                    "
                                >
                                    <span v-if="comment.time">
                                        {{ $t("Accounted Time") }}:
                                        {{ comment.time }}
                                    </span>
                                    <span v-else>
                                        {{ $t("Accounted Time") }}:
                                        {{ $t("Not Set") }}
                                    </span>
                                    <span class="flex pt-4">
                                        <span
                                            v-for="(
                                                file, index
                                            ) in comment.attachment"
                                            :key="index"
                                            class="flex"
                                            :class="[index > 0 ? 'ml-4' : '']"
                                        >
                                            <font-awesome-icon
                                                class="pr-2 self-center cursor-pointer"
                                                icon="fa-solid fa-cloud-arrow-up"
                                                @click="
                                                    downloadAttachment(
                                                        file.id,
                                                        file.viewable_name
                                                    )
                                                "
                                            />
                                            <span>{{
                                                file.viewable_name
                                            }}</span>
                                        </span>
                                    </span>
                                </p>

                                <div
                                    v-if="comment?.visibility === 'internal'"
                                    class="bg-red-500 text-white px-2 rounded font-bold internal-tag"
                                >
                                    <p>{{ $t("Internal") }}</p>
                                </div>
                                <p
                                    style="
                                        color: gray;
                                        text-align: end;
                                        margin: 0.5rem;
                                        font-size: 0.8rem;
                                    "
                                >
                                    ~{{
                                        comment.userName ||
                                        usernames[comment.userId]
                                    }}
                                </p>
                                <p
                                    style="
                                        color: gray;
                                        margin: 0.5rem;
                                        font-size: 0.8rem;
                                        position: absolute;
                                        left: 0;
                                    "
                                >
                                    <!-- {{ getTimeString(comment.createdAt) }} -->
                                    {{ $dateFormatter(comment.createdAt, globalLanguage) }}
                                </p>
                            </div>
                        </div>
                        <p
                            ref="load"
                            v-show="perPage < comments?.total"
                            class="text-center mt-2"
                        >
                            {{ $t("Loading more") }}....
                        </p>
                    </div>
                </div>
            </div>
            <div
                v-if="userPermissions?.includes('ticket.show')"
                class="lg:w-1/3 p-2 w-full"
            >
                <div class="card max-w-3xl bg-white rounded-md shadow p-5">
                    <div>
                        <div class="flex justify-between mb-8">
                            <h6>
                                {{ companyDetails?.companyName ?? "" }}
                                <br />
                                {{ companyDetails?.address }}
                                <br />
                                {{ companyDetails?.code }}&nbsp;{{
                                    companyDetails?.city
                                }}
                                <br />
                                {{ companyDetails?.country }}
                                <br />
                            </h6>
                            <div class="text-sm">
                                <div
                                    class="grid gap-2"
                                    style="grid-template-columns: 1fr 1fr"
                                >
                                    <span class="font-bold"
                                        >{{ $t("SLA Level") }}:</span
                                    ><span>{{
                                        form.amsId
                                            ? form.amsId?.attachment?.slaLevelId
                                                  ?.name
                                            : "-"
                                    }}</span>
                                </div>
                                <div
                                    class="grid gap-2"
                                    style="grid-template-columns: 1fr 1fr"
                                >
                                    <span class="font-bold"
                                        >{{ $t("SLA Time") }}:</span
                                    ><span>{{
                                        form.amsId
                                            ? form.amsId?.attachment
                                                  ?.slaServiceTimeId?.name
                                            : "-"
                                    }}</span>
                                </div>
                                <div
                                    class="grid gap-2"
                                    style="grid-template-columns: 1fr 1fr"
                                >
                                    <span class="font-bold"
                                        >{{ $t("Recieved Time") }}:</span
                                    >
                                    <!-- <span>{{ ticket.createdAt }}</span> -->
                                    <span>{{ $dateFormatter(ticket.createdAt, globalLanguage) }}</span>
                                </div>
                                <div
                                    class="grid gap-2"
                                    style="grid-template-columns: 1fr 1fr"
                                >
                                    <span class="font-bold"
                                        >{{ $t("Reaction Time") }}:</span
                                    ><span
                                        >{{
                                            convertDecimalToTime(
                                                form.amsId
                                                    ? form.amsId?.attachment
                                                          ?.slaLevelId?.[
                                                          reactionTimeEnums[
                                                              form.priority
                                                          ]
                                                      ]
                                                    : 0
                                            )
                                        }}
                                    </span>
                                </div>
                                <div
                                    class="grid gap-2"
                                    style="grid-template-columns: 1fr 1fr"
                                >
                                    <span class="font-bold"
                                        >{{ $t("Deadline") }}:</span
                                    ><span>{{ calculateDeadline() }} </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 flex flex-wrap">
                        <MultiSelectInput
                            :isDisabled="true"
                            class="pb-8 pr-6 w-1/2"
                            v-model="form.softwareId"
                            :key="form.softwareId"
                            :options="softwares.data"
                            :multiple="false"
                            :textLabel="$t('Software')"
                            label="name"
                            :trackBy="'id'"
                            :moduleName="'softwares'"
                            :taggable="true"
                        />
                        <select-input
                            :is-read-only="true"
                            :required="true"
                            v-model="form.type"
                            :key="form.type"
                            class="pb-8 pr-6 w-full lg:w-1/2"
                            :label="$t('Type')"
                            :error="errors?.type ?? ''"
                        >
                            <option value="incident">
                                {{ $t("Incident") }}
                            </option>
                            <option value="change">{{ $t("Change") }}</option>
                        </select-input>
                        <select-input
                            :is-read-only="true"
                            v-model="form.priority"
                            :key="form.priority"
                            :required="true"
                            class="pb-8 pr-6 w-full lg:w-1/2"
                            :label="$t('Priority')"
                            :error="errors?.priority ?? ''"
                        >
                            <option value="low">1 {{ $t("low") }}</option>
                            <option value="normal">2 {{ $t("normal") }}</option>
                            <option value="high">3 {{ $t("high") }}</option>
                        </select-input>
                        <select-input
                            :is-read-only="true"
                            v-model="form.visibility"
                            :key="form.visibility"
                            :required="true"
                            class="w-full pb-8 pr-6 lg:w-1/2"
                            :label="$t('Visibility')"
                            :error="errors?.visibility ?? ''"
                        >
                            <option value="internal">
                                {{ $t("Internal Only") }}
                            </option>
                            <option value="public">
                                {{ $t("Public") }}
                            </option>
                        </select-input>
                        <select-input
                            :is-read-only="true"
                            v-model="form.state"
                            :key="form.state"
                            :required="true"
                            class="pb-8 pr-6 w-full lg:w-1/2"
                            :label="$t('Status')"
                            :error="errors?.state ?? ''"
                        >
                            <option value="new">{{ $t("New") }}</option>
                            <option value="open">{{ $t("Open") }}</option>
                            <option value="waiting-for-response">
                                {{ $t("Waiting For Response") }}
                            </option>
                            <option value="pending">
                                {{ $t("Pending") }}
                            </option>
                            <option value="resolved">
                                {{ $t("Resolved") }}
                            </option>
                        </select-input>
                        <MultiSelectInput
                            :isDisabled="true"
                            @update:model-value="setSoftware"
                            v-model="form.amsId"
                            @open="getAms()"
                            :key="form.amsId"
                            :options="ams"
                            class="pb-8 pr-6 w-1/2"
                            :multiple="false"
                            :textLabel="$t('Accounting')"
                            label="amsNumber"
                            trackBy="id"
                        />
                    </div>
                    <div class="flex justify-between text-sm">
                        <div>
                            <p>Reporter</p>
                            <div class="flex mt-2">
                                <div
                                    class="flex justify-center items-center mr-1"
                                    :style="{
                                        backgroundImage:
                                            'url(' +
                                            (form.reporter?.profile_image ??
                                                '') +
                                            ')',
                                    }"
                                    style="
                                        background-position: center center;
                                        background-repeat: no-repeat;
                                        background-size: cover;
                                        min-height: 32px;
                                        min-width: 32px;
                                        border-radius: 50%;
                                        background-color: #2957a4;
                                        color: white;
                                        align-self: start;
                                    "
                                >
                                    <span v-if="!form.reporter?.profile_image">
                                        {{
                                            getInitials(
                                                (form?.reporter?.first_name ??
                                                    "") +
                                                    " " +
                                                    (form?.reporter
                                                        ?.last_name ?? "")
                                            )
                                        }}
                                    </span>
                                </div>
                                <p class="self-center">
                                    {{
                                        (form?.reporter?.first_name ?? "") +
                                        " " +
                                        (form?.reporter?.last_name ?? "")
                                    }}
                                </p>
                            </div>
                        </div>
                        <div>
                            <p>Assignee</p>
                            <div class="flex mt-2">
                                <div
                                    class="flex justify-center items-center mr-1"
                                    :style="{
                                        backgroundImage:
                                            'url(' +
                                            (form.assignee?.profile_image ??
                                                '') +
                                            ')',
                                    }"
                                    style="
                                        background-position: center center;
                                        background-repeat: no-repeat;
                                        background-size: cover;
                                        min-height: 32px;
                                        min-width: 32px;
                                        border-radius: 50%;
                                        background-color: #2957a4;
                                        color: white;
                                        align-self: start;
                                    "
                                >
                                    <span v-if="!form.assignee?.profile_image">
                                        {{
                                            getInitials(
                                                (form?.assignee?.first_name ??
                                                    "") +
                                                    " " +
                                                    (form?.assignee
                                                        ?.last_name ?? "")
                                            )
                                        }}
                                    </span>
                                </div>
                                <p class="self-center">
                                    {{
                                        (form?.assignee?.first_name ?? "") +
                                        " " +
                                        (form?.assignee?.last_name ?? "")
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 text-sm">
                        <p>
                            {{ $t("Accounted Time") }}:&nbsp;{{
                                ticket?.totalAccountedTime
                            }}&nbsp;{{ $t("hrs") }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import "@vueup/vue-quill/dist/vue-quill.snow.css";
import TextInput from "../../Components/TextInput.vue";
import NumberInput from "../../Components/NumberInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import TextAreaInput from "../../Components/TextareaInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import Modal from "../../Components/EditModal.vue";
import QuillTextEditor from "../../Components/QuillTextEditor.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";
import userProfilePicturesMixin from "@/Mixins/userProfilePicturesMixin.js";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    mixins: [userProfilePicturesMixin],
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("auth", [
            "user",
            "userPermissions",
            "userProfilePictures",
        ]),
        ...mapGetters("softwares", ["softwares"]),
    },
    components: {
        MultiSelectInput,
        QuillTextEditor,
        Modal,
        LoadingButton,
        TextAreaInput,
        TextInput,
        SelectInput,
        NumberInput,
        PageHeader,
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Partner Management"),
                    to: "/partner-management/tickets",
                },
                {
                    text: this.$t("Tickets"),
                    to: "/partner-management/tickets?page="+this.$route.query.page,
                },
                {
                    text: "Show",
                    active: true,
                },
            ],
            reactionTimeEnums: {
                low: "reactionTimeLow",
                normal: "reactionTimeHigh",
                high: "reactionTimeLowUrgent",
            },
            companyDetails: {},
            shouldShow: false,
            observer: new IntersectionObserver(this.infiniteScroll),
            perPage: 10,
            toggleEditModal: false,
            toggleDeleteModal: false,
            selectedCommentId: "",
            companies: [],
            ticketEditToggle: false,
            ticketForm: {
                text: "",
                time: 0,
                ticketId: this.$route.params.id,
                visibility: "",
            },
            form: {
                title: "",
                state: "",
                priority: "",
                contactType: "",
                companyId: "",
                assignee: "",
                type: "",
                amsId: "",
                softwareId: "",
            },
            ticket: {},
            comments: {
                data: [],
            },
            usernames: [],
        };
    },
    methods: {
        /**
         * converts decimal hours to hours and minutes string
         * @param {decimalHours} time to be converted
         * @return converted string
         */
        convertDecimalToTime(decimalHours) {
            // Get the integer and fractional parts of the decimal hours
            const hours = Math.floor(decimalHours);
            const decimalPart = decimalHours - hours;

            // Convert the fractional part to minutes
            const minutes = Math.round(decimalPart * 60);

            // Create a human-readable time string
            let timeString = "";
            timeString += hours + " hour";
            if (hours > 1) {
                timeString += "s";
            }

            timeString += " ";

            timeString += minutes + " minute";
            if (minutes > 1) {
                timeString += "s";
            }

            return timeString;
        },
        /**
         * calculates the deadline date based on the from and to times from SLA service time for the linked attachment and the created at date
         */
        calculateDeadline() {
            let deadLine = "";
            const fromDate = new Date(this.ticket.createdAt); // set to the created at date
            const fromTokens = (
                this.form.amsId?.attachment?.slaServiceTimeId?.from ?? ""
            ).split(":"); // get the hour and minute but spliting with ":"
            // set the hour and minute
            fromDate.setHours(fromTokens?.[0] ?? 0);
            fromDate.setMinutes(fromTokens?.[1] ?? 0);
            fromDate.setSeconds(fromTokens?.[2] ?? 0);
            const toDate = new Date(this.ticket.createdAt);
            const toTokens = (
                this.form.amsId?.attachment?.slaServiceTimeId?.to ?? ""
            ).split(":"); // get the hour and minute but spliting with ":"
            // set the hour and minute
            toDate.setHours(toTokens?.[0] ?? 0);
            toDate.setMinutes(toTokens?.[1] ?? 0);
            toDate.setSeconds(toTokens?.[2] ?? 0);
            // get reaction time from the sla level
            const reactionTime =
                this.form.amsId?.attachment?.slaLevelId?.[
                    this.reactionTimeEnums[this.form.priority]
                ] ?? 0;
            if (new Date(this.ticket.createdAt) < fromDate) {
                const fromPlusReactionTime = this.addHours(
                    fromDate,
                    reactionTime
                );
                if (new Date(fromPlusReactionTime) <= toDate) {
                    deadLine = fromPlusReactionTime;
                } else {
                    const remainingTimeNextDay =
                        +reactionTime -
                        +this.getHoursDiffBetweenDates(fromDate, toDate);
                    deadLine = this.addHours(
                        fromDate,
                        24 + +remainingTimeNextDay
                    );
                }
            } else {
                deadLine = this.addHours(
                    new Date(this.ticket.createdAt),
                    +reactionTime
                );
            }
            return deadLine;
        },
        /**
         * calculates the difference in hours between two dates
         * @param {dateInitial} start date
         * @param {dateFinal} end date
         */
        getHoursDiffBetweenDates(dateInitial, dateFinal) {
            return (dateFinal - dateInitial) / (1000 * 3600);
        },
        /**
         * adds hours to a date
         * @param {date} the date
         * @param {hours} hours to be added
         */
        addHours(date, hours) {
            try {
                const d = new Date(date);
                d.setHours(d.getHours() + +hours);
                return (
                    d.toISOString().slice(0, 10) +
                    " " +
                    d.toTimeString().slice(0, 8)
                );
            } catch (e) {
                return date;
            }
        },
        downloadAttachment(id, viewableName) {
            this.$store.dispatch("partnerTickets/downloadTicketAttachment", {
                id: id,
                queryParams: { name: viewableName },
            });
        },
        async setSoftware() {
            await this.$nextTick();
            this.form.softwareId = this.form.amsId?.attachment?.software ?? "";
        },
        async getAms() {
            await this.$nextTick();
            try {
                const response = await this.$store.dispatch(
                    "ams/getByCustomerAndAttachment",
                    this.form.companyId
                );
                this.ams = response?.data?.data ?? [];
            } catch (e) {
                console.log(e);
            }
        },
        isJSON(str) {
            try {
                JSON.parse(str);
                return true;
            } catch (err) {
                return false;
            }
        },
        /**
         * fetches ticket comments listing
         */
        fetchTicketComments() {
            return new Promise(async (resolve, reject) => {
                try {
                    const responseComments = await this.$store.dispatch(
                        "ticketComments/list",
                        {
                            id: this.ticket.id,
                            queryParams: {
                                perPage: this.perPage,
                            },
                        }
                    );
                    this.comments = responseComments?.data?.data ?? [];
                    this.getUserProfilePictures(
                        this.comments?.data ?? [],
                        "userId"
                    );
                    resolve();
                } catch (e) {
                    reject(e);
                }
            });
        },
        async infiniteScroll([{ isIntersecting, target }]) {
            if (isIntersecting) {
                const ul = target.offsetParent;
                const scrollTop = target.offsetParent.scrollTop;
                this.perPage += 10;
                await this.fetchTicketComments();
                this.getUsernames();
                await this.$nextTick();
                ul.scrollTop = scrollTop;
            }
        },
        getUsernames() {
            let username = [];
            this.comments?.data.forEach(async (comment) => {
                if (!username.includes(comment.userId)) {
                    username.push(comment.userId);
                    let response = {};
                    if (comment.userId && comment.userId !== "n.a.") {
                        response = await this.$store.dispatch("auth/show", {
                            id: comment.userId,
                        });
                    }
                    if (
                        !response?.data?.first_name &&
                        !response?.data?.last_name
                    ) {
                        this.usernames[comment.userId] = "User not found";
                    } else
                        this.usernames[comment.userId] =
                            (response?.data?.first_name ?? "") +
                            " " +
                            (response?.data?.last_name ?? "");
                }
            });
        },
        getInitials(name) {
            const tokens = name?.split(" ");
            if (tokens)
                return `${tokens?.[0]?.[0] ?? ""}${
                    tokens?.[1]?.[0] ?? ""
                }`.toUpperCase();
            else return "";
        },
        getTimeString(time) {
            return (
                this.$dateFormatter(time?.substr(0, 10), this.globalLanguage) +
                " " +
                time?.substr(11, 8)
            );
        },
        async refresh() {
            try {
                const response = await this.$store.dispatch(
                    "partnerTickets/show",
                    this.$route.params.id
                );
                this.ticket = response?.data?.tickets ?? {};
                this.companyDetails =
                    response?.data?.tickets?.companyDetails ?? {};
                await this.fetchTicketComments();
                this.getUsernames();
                if (this.$refs.load) this.observer?.observe(this.$refs.load);
            } catch (e) {
                console.log(e);
            } finally {
                try {
                    if (!!this.ticket.userId) {
                        const response = await this.$store.dispatch(
                            "auth/show",
                            {
                                id: this.ticket.userId,
                            }
                        );
                        this.form.reporter = response?.data ?? "";
                    }
                } catch (e) {
                    console.log(e);
                }
            }
        },
    },
    async mounted() {
        try {
            this.$store.commit("showLoader", true);
            await this.$store.dispatch("userProfile/list");
            await this.$store.dispatch("softwares/list");
            this.refresh();
            this.$store
                .dispatch("partnerTickets/show", this.$route.params.id)
                .then((res) => {
                    this.form = {
                        ...this.form,
                        title: res?.data?.tickets?.title ?? "",
                        state: res?.data?.tickets?.state ?? "",
                        priority: res?.data?.tickets?.priority ?? "",
                        contactType: res?.data?.tickets?.contactType ?? "",
                        companyId: res?.data?.tickets?.companyId ?? "",
                        visibility: res?.data?.tickets?.visibility ?? "",
                        assignee: res?.data?.tickets?.assignee,
                        text: res?.data?.tickets?.text ?? "",
                        type: res?.data?.tickets?.type ?? "",
                        amsId: res?.data?.tickets?.ams ?? "",
                        softwareId: res?.data?.tickets?.software ?? "",
                    };
                    this.getAms();
                })
                .finally(async () => {
                    try {
                        if (!!this.form.assignee) {
                            const response = await this.$store.dispatch(
                                "auth/show",
                                {
                                    id: this.form.assignee,
                                }
                            );
                            this.form.assignee = response?.data ?? "";
                        }
                    } catch (e) {
                        console.log(e);
                    }
                });

            this.$store
                .dispatch("companies/list", {
                    perPage: 0,
                    page: 1,
                })
                .then((res) => {
                    this.companies = res?.data?.data ?? [];
                });
        } catch (e) {
        } finally {
            this.shouldShow = true;
            this.$store.commit("showLoader", false);
        }
    },
};
</script>

<style scoped>
.internal-tag {
    position: absolute;
    right: 8px;
    top: 8px;
    font-size: 0.8rem;
}

.ql-container.ql-snow {
    padding-bottom: 45px;
}

.dropdown-action:hover {
    background-color: #2957a4;
    color: white;
}

.dropdown {
    position: relative;
    display: inline-block;
    margin-top: -10px;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    z-index: 1;
    right: 0;
}

.dropdown:hover .dropdown-content {
    display: block;
}

.comment {
    position: relative;
}

.comment:hover svg {
    display: block !important;
}

.chat_box {
    border-bottom: 1px solid #e7eaed;
    padding: 15px 0px;
}

.chat::-webkit-scrollbar {
    width: 5px;
    height: 3px;
}

.chat::-webkit-scrollbar-track {
    box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
}

.chat::-webkit-scrollbar-thumb {
    background-color: darkgrey;
    outline: 1px solid slategrey;
    border-radius: 5px;
}

.talk-bubble {
    margin: 20px;
    display: inline-block;
    position: relative;
    /* width: 200px; */
    height: auto;
    background-color: white;
}

.round {
    border-radius: 30px;
    -webkit-border-radius: 30px;
    -moz-border-radius: 30px;
}

.tri-right.border.left-top:before {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: -40px;
    right: auto;
    top: -8px;
    bottom: auto;
    border: 32px solid;
    border-color: #666 transparent transparent transparent;
}

.tri-right.left-top:after {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: -22px;
    right: auto;
    top: 0px;
    bottom: auto;
    border: 22px solid;
    border-color: white transparent transparent transparent;
}

/* Right triangle, left side slightly down */
.tri-right.border.left-in:before {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: -22px;
    right: auto;
    top: 30px;
    bottom: auto;
    border: 20px solid;
    border-color: #666 #666 transparent transparent;
}

.tri-right.left-in:after {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: -12px;
    right: auto;
    top: 22px;
    bottom: auto;
    border: 12px solid;
    border-color: white white transparent transparent;
}

/*Right triangle, placed bottom left side slightly in*/
.tri-right.border.btm-left:before {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: -8px;
    right: auto;
    top: auto;
    bottom: -40px;
    border: 32px solid;
    border-color: transparent transparent transparent #666;
}

.tri-right.btm-left:after {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: 0px;
    right: auto;
    top: auto;
    bottom: -20px;
    border: 22px solid;
    border-color: transparent transparent transparent white;
}

/*Right triangle, placed bottom left side slightly in*/
.tri-right.border.btm-left-in:before {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: 30px;
    right: auto;
    top: auto;
    bottom: -40px;
    border: 20px solid;
    border-color: #666 transparent transparent #666;
}

.tri-right.btm-left-in:after {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: 38px;
    right: auto;
    top: auto;
    bottom: -20px;
    border: 12px solid;
    border-color: white transparent transparent white;
}

/*Right triangle, placed bottom right side slightly in*/
.tri-right.border.btm-right-in:before {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: auto;
    right: 30px;
    bottom: -40px;
    border: 20px solid;
    border-color: #666 #666 transparent transparent;
}

.tri-right.btm-right-in:after {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: auto;
    right: 38px;
    bottom: -20px;
    border: 12px solid;
    border-color: white white transparent transparent;
}

/*
	left: -8px;
  right: auto;
  top: auto;
	bottom: -40px;
	border: 32px solid;
	border-color: transparent transparent transparent #666;
	left: 0px;
  right: auto;
  top: auto;
	bottom: -20px;
	border: 22px solid;
	border-color: transparent transparent transparent white;

/*Right triangle, placed bottom right side slightly in*/
.tri-right.border.btm-right:before {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: auto;
    right: -8px;
    bottom: -40px;
    border: 20px solid;
    border-color: #666 #666 transparent transparent;
}

.tri-right.btm-right:after {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: auto;
    right: 0px;
    bottom: -20px;
    border: 12px solid;
    border-color: white white transparent transparent;
}

/* Right triangle, right side slightly down*/
.tri-right.border.right-in:before {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: auto;
    right: -40px;
    top: 30px;
    bottom: auto;
    border: 20px solid;
    border-color: #666 transparent transparent #666;
}

.tri-right.right-in:after {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: auto;
    right: -12px;
    top: 22px;
    bottom: auto;
    border: 12px solid;
    border-color: white transparent transparent white;
}

/* Right triangle placed top right flush. */
.tri-right.border.right-top:before {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: auto;
    right: -40px;
    top: -8px;
    bottom: auto;
    border: 32px solid;
    border-color: #666 transparent transparent transparent;
}

.tri-right.right-top:after {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: auto;
    right: -20px;
    top: 0px;
    bottom: auto;
    border: 20px solid;
    border-color: white transparent transparent transparent;
}

/* talk bubble contents */
.talktext {
    padding: 0 0.7rem 0.7rem 0.7rem;
    text-align: left;
    line-height: 1.5em;
    width: 80%;
    word-break: break-all;
}

.talktext p {
    /* remove webkit p margins */
    -webkit-margin-before: 0em;
    -webkit-margin-after: 0em;
}

.is-archived {
    text-decoration: line-through;
}
</style>

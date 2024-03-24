import { Pusher } from "@/utils/Pusher";
import Push from "push.js";

export default {
    methods: {
        initializePusher() {
            try {
                // Setup the connection and log on to the server
                // on production it must be "wss://" (with two 's')
                // e.g. wss://admin.docshero.de
                var webSocketBaseUrl = import.meta.env.VITE_WEB_SOCKET_BASE_URL;

                // This is accessToken from login procedure
                var accessToken = localStorage.getItem("token");

                var pusher = new Pusher();

                pusher.init(
                    webSocketBaseUrl,
                    async (currMsg) => {
                        try {
                            let data = JSON.parse(currMsg?.data ?? "{}");
                            if (
                                data?.action === "CommentCreated" ||
                                data?.action === "CommentUpdated"
                            ) {
                                const response = await this.$store.dispatch(
                                    "auth/show",
                                    {
                                        id: data?.ticket?.user_id,
                                    }
                                );
                                const action =
                                    data?.action === "CommentCreated"
                                        ? "added"
                                        : "updated";
                                this.$store.commit("flashMessage", {
                                    show: true,
                                    message:
                                        "<h3>" +
                                        "Ticket " +
                                        data?.ticket?.ticket?.ticket_number +
                                        "</h3>" +
                                        "Ticket-" +
                                        data?.ticket?.ticket?.title +
                                        "\n" +
                                        "Ticket comment " +
                                        action +
                                        " by " +
                                        (response?.data?.first_name ?? "") +
                                        " " +
                                        (response?.data?.last_name ?? ""),
                                    type: "info",
                                    link:
                                        window.location.protocol +
                                        "//" +
                                        window.location.host +
                                        "/tickets/" +
                                        data?.ticket?.ticket?.id,
                                });
                                Push.create(
                                    "Ticket " +
                                        data?.ticket?.ticket?.ticket_number,
                                    {
                                        body:
                                            "Ticket-" +
                                            data?.ticket?.ticket?.title +
                                            "\n" +
                                            "Ticket comment " +
                                            action +
                                            " by " +
                                            (response?.data?.first_name ?? "") +
                                            " " +
                                            (response?.data?.last_name ?? ""),
                                        icon: "icon.png",
                                        timeout: 120000, // Timeout before notification closes automatically.
                                        vibrate: [100, 100, 100], // An array of vibration pulses for mobile devices.
                                        onClick: function () {
                                            // Callback for when the notification is clicked.
                                            location.replace(
                                                window.location.protocol +
                                                    "//" +
                                                    window.location.host +
                                                    "/tickets/" +
                                                    data?.ticket?.ticket?.id
                                            );
                                        },
                                    }
                                );
                                this.$store.dispatch(
                                    "tickets/openTicketsCount"
                                );
                            } else if (data?.action === "BoardUpdated") {
                                // if the action if 'BoardUpdated' then we edit a 'refresh' event with the 'boardId' received from the pusher data
                                // each board has a 'refresh' event listener which triggers board refresh functionality to show changes in realtime
                                this.$emitter.emit("refresh", data?.boardId);
                            } else if (
                                data?.action === "FeedPollFinished" ||
                                data?.action === "FeedPollVoteAdded" ||
                                data?.action === "OwnFeedCreated" ||
                                data?.action === "OwnFeedUpdated" ||
                                data?.action === "OwnFeedDeleted" ||
                                data?.action === "OwnFeedCommentCreated" ||
                                data?.action === "OwnFeedCommentUpdated" ||
                                data?.action === "OwnFeedCommentDeleted"
                            ) {
                                this.$emitter.emit("getFeeds", true);
                            }
                        } catch (e) {
                            console.log(e);
                        }
                    },
                    accessToken
                );
                this.$store.commit("pusher", pusher);
            } catch (e) {
                console.log(e);
            }
        },
    },
};

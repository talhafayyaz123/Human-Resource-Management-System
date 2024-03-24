export class Pusher {
    close() {
        this.isConnected = false;
        if (this.socket != null) {
            this.socket.close();
            clearInterval(this.checkMessagesSent);
        }
    }

    init(
        serverDomain,
        onMessageReceiveCallback,
        myToken,
        messageQueue = [],
        callbacks = {}
    ) {
        this.messageQueue = [];
        this.serverDomain = serverDomain;
        this.isConnected = false;
        if (this.socket != null) {
            this.socket.close();
            clearInterval(this.checkMessagesSent);
        }

        this.token = myToken;

        if (
            typeof onMessageReceiveCallback !== "undefined" &&
            onMessageReceiveCallback !== null
        ) {
            this.onMessageReceiveCallback = onMessageReceiveCallback.bind(this);
        }

        this.lastId = 0;
        this.counter = 0;
        this.callbacks = callbacks;
        //this.serverUrl=serverDomain+":9003/pusher-service/msg";
        this.serverUrl = serverDomain + "/msg";
        this.socket = new WebSocket(this.serverUrl);

        this.checkMessagesSent = setInterval(
            function () {
                //console.log("interval");

                if (this.isConnected == false) {
                    this.init(
                        this.serverDomain,
                        this.onMessageReceiveCallback,
                        this.token,
                        this.messageQueue,
                        this.callbacks
                    );
                }

                for (var i = 0; i < this.messageQueue.length; i++) {
                    var trySend = this.messageQueue[i];
                    this.socket.send(trySend);
                    console.log("Send message: " + trySend);
                }
                this.messageQueue = [];
            }.bind(this),
            10000
        );

        this.socket.onmessage = function (event) {
            console.log("[message] Data received from server: ");
            console.log(event.data);
            var json = JSON.parse(event.data);
            if (typeof json == "object" && typeof json["id"] != "undefined") {
                if (
                    typeof this.callbacks[json["id"]] !== "undefined" &&
                    this.callbacks[json["id"]] != null
                ) {
                    console.log("Calling: " + json["id"]);
                    this.callbacks[json["id"]](json);

                    delete this.callbacks[json["id"]];
                } else if (
                    typeof this.onMessageReceiveCallback !== "undefined" &&
                    this.onMessageReceiveCallback !== null
                ) {
                    this.onMessageReceiveCallback(json);
                }
            } else if (json !== null) {
                if (
                    typeof this.onMessageReceiveCallback !== "undefined" &&
                    this.onMessageReceiveCallback !== null
                ) {
                    this.onMessageReceiveCallback(json);
                }
            }
        }.bind(this);

        this.socket.onclose = function (event) {
            if (event.wasClean) {
                console.log(
                    "[close] Connection closed cleanly, code=" +
                        event.code +
                        " reason=" +
                        event.reason
                );
            } else {
                // e.g. server process killed or network down
                // event.code is usually 1006 in this case
                console.log("[close] Connection died");
            }
            this.isConnected = false;
        }.bind(this);

        this.socket.onerror = function (error) {
            this.isConnected = false;
            console.log("[error] ");
            console.log(error);
        }.bind(this);

        this.socket.onopen = function (e) {
            this.isConnected = true;
            console.log("[open] Connection established");
            console.log("Sending token to server!");

            var initMessage = { token: this.token };
            this.sendMessage(initMessage);
        }.bind(this);
    }

    constructor() {
        this.socket = null;
        this.checkMessagesSent = null;
        this.serverDomain = "";
    }

    sendMessage(json, toUserIds, callbackOnAnswer) {
        this.lastId++;
        var uniqueId =
            this.lastId +
            "-" +
            Date.now().toString(36) +
            Math.random().toString(36).substring(2, 15);
        if (callbackOnAnswer != null) {
            this.callbacks[uniqueId] = callbackOnAnswer.bind(this);
        }
        json = { id: uniqueId, data: json, to_user_ids: toUserIds };
        this.messageQueue.push(JSON.stringify(json));

        if (this.isConnected == false) {
            return;
        } else {
            for (var i = 0; i < this.messageQueue.length; i++) {
                var trySend = this.messageQueue[i];
                this.socket.send(trySend);
                console.log("Send message: " + trySend);
            }
            this.messageQueue = [];
        }
    }
}

<template>
    <div>
        <div
            class="relative z-10"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true"
        >
            <div
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
            ></div>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div
                    class="flex h-full items-end justify-center p-4 text-center sm:items-center sm:p-0"
                >
                    <div
                        style="overflow: scroll; width: 70%; height: 95%"
                        class="relative transform rounded-lg bg-white text-left shadow-xl transition-all"
                    >
                        <div class="mt-4">
                            <div class="container mx-auto sm:px-8">
                                <h2
                                    class="text-2xl font-semibold leading-tight"
                                >
                                    {{ $t("Executing Commands") }}:
                                </h2>
                                <br />
                                <div class="fakeMenu">
                                    <div class="fakeButtons fakeClose"></div>
                                    <div class="fakeButtons fakeMinimize"></div>
                                    <div class="fakeButtons fakeZoom"></div>
                                </div>
                                <div class="fakeScreen">
                                    <p
                                        v-for="(command, index) in showCommands"
                                        :key="index"
                                        class="line1"
                                    >
                                        $ {{ command }}
                                    </p>
                                    <br />
                                    <span class="cursor1">_</span>
                                </div>
                                <div style="width: 550px; padding-top: 20px">
                                    <textarea-input
                                        class="w-full"
                                        :label="$t('Execute custom commands')"
                                        :isReadonly="isInstallation"
                                        v-model="command"
                                    />

                                    <div class="pb-6"></div>
                                    <div
                                        class="sm:flex sm:flex-row-reverse mb-6"
                                    >
                                        <button
                                            type="button"
                                            :disabled="isInstallation"
                                            class="secondary-btn"
                                            @click="onAdded"
                                        >
                                            {{ $t("Execute") }}
                                        </button>
                                        <button
                                            @click="onCancel"
                                            type="button"
                                            class="primary-btn mr-3"
                                        >
                                            {{ $t("Cancel") }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--   -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import TextareaInput from "../../Components/TextareaInput.vue";
import { NativeEventSource, EventSourcePolyfill } from "event-source-polyfill";
import axios from "axios";

export default {
    components: { TextareaInput },
    props: {
        commands: { type: Array, default: () => [] },
        isInstallation: Boolean,
        id: { type: String, default: () => ({}) },
        isInvoice: { type: Boolean, default: true },
    },
    data() {
        return {
            showCommands: this.commands,
            command: "",
            errors: "",
        };
    },

    methods: {
        onCancel() {
            this.$emit("cancelled");
        },
        onAdded() {
            var array = this.command
                .replace(/&/g, "")
                .split("\n")
                .map((trimString) => {
                    return trimString.trim();
                })
                .filter((filterResult) => {
                    return filterResult.length > 0;
                });
            let url = this.isInvoice
                ? "/api/execute-custom-commands/" +
                  this.id +
                  "?command=" +
                  JSON.stringify(array)
                : "/api/execute-system-commands/" +
                  this.id +
                  "?command=" +
                  JSON.stringify(array);
            let es = new EventSourcePolyfill(url, {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem("token")}`,
                },
            });

            es.addEventListener(
                "message",
                (event) => {
                    this.showCommands.push(event.data);
                },
                false
            );
            es.onerror = (err) => {
                es.close();
                console.error("EventSource failed:", err);
            };
        },
    },
};
</script>

<style scoped>
.fakeButtons {
    height: 10px;
    width: 10px;
    border-radius: 50%;
    border: 1px solid #000;
    position: relative;
    top: 6px;
    left: 6px;
    background-color: #ff3b47;
    border-color: #9d252b;
    display: inline-block;
}

.fakeMinimize {
    left: 11px;
    background-color: #ffc100;
    border-color: #9d802c;
}

.fakeZoom {
    left: 16px;
    background-color: #00d742;
    border-color: #049931;
}

.fakeMenu {
    width: 100%;
    box-sizing: border-box;
    height: 25px;
    background-color: #bbb;
    margin: 0 auto;
    border-top-right-radius: 5px;
    border-top-left-radius: 5px;
}

.fakeScreen {
    background-color: #151515;
    box-sizing: border-box;
    width: 100%;
    margin: 0 auto;
    padding: 20px;
    border-bottom-left-radius: 5px;
    border-bottom-right-radius: 5px;
    max-height: 300px;
    overflow: scroll;
}

p {
    position: relative;
    left: 13%;
    width: 90% !important;
    right: 0;
    margin-right: 0;
    margin-left: -8.5em;
    text-align: left;
    font-size: 1em;
    font-family: monospace;
    white-space: normal;
    overflow: hidden;
    margin-bottom: 10px;
    word-break: break-all;
}

span {
    color: #fff;
    font-weight: bold;
}

.line1 {
    color: #9cd9f0;
    -webkit-animation: type 0.5s 1s steps(20, end) forwards;
    -moz-animation: type 0.5s 1s steps(20, end) forwards;
    -o-animation: type 0.5s 1s steps(20, end) forwards;
    animation: type 0.5s 1s steps(20, end) forwards;
}

.cursor1 {
    -webkit-animation: blink 1s 2s 2 forwards;
    -moz-animation: blink 1s 2s 2 forwards;
    -o-animation: blink 1s 2s 2 forwards;
    animation: blink 1s 2s 2 forwards;
}

@-webkit-keyframes blink {
    0% {
        opacity: 0;
    }
    40% {
        opacity: 0;
    }
    50% {
        opacity: 1;
    }
    90% {
        opacity: 1;
    }
    100% {
        opacity: 0;
    }
}

@-moz-keyframes blink {
    0% {
        opacity: 0;
    }
    40% {
        opacity: 0;
    }
    50% {
        opacity: 1;
    }
    90% {
        opacity: 1;
    }
    100% {
        opacity: 0;
    }
}

@-o-keyframes blink {
    0% {
        opacity: 0;
    }
    40% {
        opacity: 0;
    }
    50% {
        opacity: 1;
    }
    90% {
        opacity: 1;
    }
    100% {
        opacity: 0;
    }
}

@keyframes blink {
    0% {
        opacity: 0;
    }
    40% {
        opacity: 0;
    }
    50% {
        opacity: 1;
    }
    90% {
        opacity: 1;
    }
    100% {
        opacity: 0;
    }
}

@-webkit-keyframes type {
    to {
        width: 17em;
    }
}

@-moz-keyframes type {
    to {
        width: 17em;
    }
}

@-o-keyframes type {
    to {
        width: 17em;
    }
}

@keyframes type {
    to {
        width: 17em;
    }
}
</style>

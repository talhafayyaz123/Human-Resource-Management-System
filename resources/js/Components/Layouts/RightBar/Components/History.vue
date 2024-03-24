<template>

    <div class="">
        <div class="history-overlay" @click="closehistory()"></div>
    
        <div v-if="toggle" class="history-bar">
            <div class="close-feeds" @click="closehistory()">
            
                <img src="@/assets/images/history.png" alt="" />
            </div>
            <div class="feeds-border"></div>
            <div class="feeds-header">
                <h2>History</h2>
        
            </div>
            <div class="feeds-border"></div>
            <div class="history-body">
                
                <div class="history-accrod" v-for="(record, index) in moduleHistory" :key="'module-history-' + index + '-' + record.dateAndTime">
                    <div class="history-accrod-header">
                    
                        <h3> {{ $dateFormatter(record.dateAndTime, globalLanguage) }}</h3>
                        <icon name="downIcon" />
                    </div>
                    <div class="history-accrod-body">
                        <div class="">
                            <h3>
                                {{ $t("User") }} {{ record.userName }}
                        <span v-if="record.moduleAction">{{
                            $t(
                                moduleActionEnums?.[record.moduleAction] ??
                                record.moduleAction
                            )
                        }}</span>
                        {{ $t(displayName) }}
                            </h3>
                                <br>
                            <h3>
                        {{ $t("Elo request sent: ")
                        }}{{ record.isEloRequestSent }}
                        </h3>

                        </div>
                        <div class="time">
                            <h3> {{ $dateFormatter(record.time, globalLanguage) }}</h3>
                        </div>
                    </div>
                </div>

        
                <OwnObjectStream
                v-if="feeds"
                    :ownStream="feeds"
                    :users="users"
                />
                
                <div v-if="(moduleHistory?.length ?? 0) < total" class="flex justify-center py-1 text-sm">
                    <button class="secondary-btn" @click="
                    page += 1;getModuleHistory();">
                        {{ $t("Show more") }}
                    </button>
                </div>



                <div class="flex justify-center text-white" v-if="(moduleHistory?.length ?? 0) == 0  && (feeds?.length ?? 0) == 0 ">
                    <p>{{ $t("No history found") }}</p>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import icon from "../../../Icon.vue";
import moduleHistoryMixin from "@/Mixins/moduleHistoryMixin";
import OwnObjectStream from "../.././../MyFeed/HistoryObjectStream.vue";
import EmojiPicker from "vue3-emoji-picker";
import "vue3-emoji-picker/css";

export default {
    components: {
        icon,
        OwnObjectStream
    },
    props: {
        id: {
            type: String,
            default: "",
        },
        moduleName: {
            type: String,
            default: "",
        },
        displayName: {
            type: String,
            default: "",
        },
       
    },
    mixins: [moduleHistoryMixin],
    watch: {
        toggle(val) {
            if (!!val) {
                this.getModuleHistory();
            } else {
                this.moduleHistory = [];
                this.perPage = 10;
                this.total = 0;
                this.page = 1;
            }
        },
    },
    data() {
        return {
            toggle: true,
            moduleHistory: [],
            perPage: 10,
            total: 0,
            page: 1,
            users: "",
            moduleActionEnums: {
                update: "updated",
                create: "created",
            },
        };
    },
    methods:{
        closehistory() {
            this.$emit('closeHistory');
        }
    },
    async mounted(){
        if (this.id) {
        this.getModuleHistory();
        const users = await this.$store.dispatch("assets/listEmployees");
        this.users = users.data.data;
    } else {
        this.moduleHistory = [];
        this.perPage = 10;
        this.total = 0;
        this.page = 1;
    }
    }
};
</script>
<style scoped lang="scss">
.delete-option {
    color: white;
    cursor: pointer;
}
.tag {
    display: inline-block;
    margin: 2px;
    padding: 4px 8px;
    background-color: #007bff;
    color: #ffffff;
    border-radius: 4px;
}

.text {
    margin: 2px;
}

.post-polls {
    ul {
        margin: 0;
        padding: 0;
        list-style: none;

        li {
            margin-bottom: 5px;
            padding: 5px 9px;
            border-radius: 6px;
            border: 1px solid rgba(208, 213, 221, 0.7);
            background: rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: space-between;

            p {
                color: white;
                font-size: 10px;
                font-style: normal;
                font-weight: 400;
                line-height: 20px;
                margin: 0;
            }
        }

        li.active {
            background: #f59630;

            p {
                color: #fff;
            }
        }
    }
}

input.add-option-input {
    background-color: transparent;
    color: white;
    border: 1px solid white;
    border-radius: 10px;
    padding: 5px;
}

button.add-option:disabled,
button.add-option[disabled] {
    cursor: not-allowed;
    background-color: #cccccc;
    color: #666666;
}

.poll-question {
    color: white;
    font-size: 14px;
    padding: 0 15px 15px;
}

.poll-date {
    color: white;
    font-size: 14px;
    padding-bottom: 10px;
    padding-left: 15px;
}
</style>

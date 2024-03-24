<template>
    <div :style="`right: ${toggle ? '21' : '0'
        }%; top: 40%; z-index: 1;position:fixed;`"
        class="" @click="toggle = !toggle">
        <img src="@/assets/images/history.png" alt="" />
    </div>
    <div class=""  v-if="toggle">
       
        <div class="history-bar">
            <div class="close-feeds">
            
                <div :style="`position:relative;right: ${toggle ? '7' : '1'
        }% !important; top: 40%;  z-index: 1;`"
        class="" @click="toggle = !toggle">
        <img src="@/assets/images/history.png" alt="" />
        </div>
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
                <div  v-if="(moduleHistory?.length ?? 0) < total" class="flex justify-center py-1 text-sm">
                    <button class="secondary-btn" @click="
                    page += 1;getModuleHistory();">
                        {{ $t("Show more") }}
                    </button>
                </div>
                <div class="flex justify-center text-white" v-if="(moduleHistory?.length ?? 0) == 0">
                    <p>{{ $t("No history found") }}</p>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import icon from "./Icon.vue";
import moduleHistoryMixin from "@/Mixins/moduleHistoryMixin";

export default {
    components: {
        icon
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
        moduleHistory: {
            type: Array,
            default: () => [],
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
            toggle: false,
            moduleHistory: [],
            perPage: 10,
            total: 0,
            page: 1,
            moduleActionEnums: {
                update: "updated",
                create: "created",
            },
        };
    },
};
</script>

<style scoped>
.toggled-card::-webkit-scrollbar {
    width: 4px;
    height: 4px;
}

.toggled-card::-webkit-scrollbar-track {
    box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
}

.toggled-card::-webkit-scrollbar-thumb {
    background-color: darkgrey;
    outline: 1px solid slategrey;
    border-radius: 5px;
}
</style>

<template>
    <div class="grid items-center grid-cols-2 gap-6">
        <div class="w-full">
            <div class="form-group">
                <label class="input-label"
                    ><span class="text-red-500">*&nbsp;</span
                    >{{ $t("Icon:") }}</label
                >
                <vue3-icon-picker
                    :class="!isBoardAdmin ? 'pointer-events-none' : ''"
                    v-model="form.icon"
                />
                <p class="form-error">{{ $t(errors.icon ?? "") }}</p>
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <select-input
                    :required="true"
                    v-model="form.color"
                    :key="form.color"
                    :isReadOnly="!isBoardAdmin"
                    :error="errors.color"
                    :label="$t('Color')"
                    class=""
                >
                    <option value="#fdefe0">{{ $t("Red") }}</option>
                    <option value="#dff1e2">{{ $t("Green") }}</option>
                    <option value="rgb(0 194 253 / 15%)">
                        {{ $t("Sky") }}
                    </option>
                </select-input>
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <select-input
                    :required="true"
                    v-model="form.priority"
                    :error="errors.priority"
                    :key="form.priority"
                    :isReadOnly="!isBoardAdmin"
                    :label="$t('Priority')"
                    class=""
                >
                    <option value="low">{{ $t("Low") }}</option>
                    <option value="normal">{{ $t("Normal") }}</option>
                    <option value="middle">{{ $t("Middle") }}</option>
                    <option value="high">{{ $t("High") }}</option>
                    <option value="critical">{{ $t("Critical") }}</option>
                </select-input>
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <select-input
                    :required="true"
                    v-model="form.timeTrackerType"
                    :error="errors.timeTrackerType"
                    :key="form.timeTrackerType"
                    :isReadOnly="!isBoardAdmin"
                    :label="$t('Time Tracker Type')"
                    class=""
                >
                    <option value="government">{{ $t("Government") }}</option>
                    <option value="company">{{ $t("Company") }}</option>
                </select-input>
            </div>
        </div>
    </div>
</template>

<script>
import SelectInput from "@/Components/SelectInput.vue";
import Vue3IconPicker from "vue3-universal-icon-picker";
import { mapGetters } from "vuex";

export default {
    props: {
        board: {
            type: Object,
            default: () => ({}),
        },
    },
    components: {
        Vue3IconPicker,
        SelectInput,
    },
    computed: {
        ...mapGetters(["errors"]),
        ...mapGetters("auth", ["user"]),
        ...mapGetters("taskBoards", ["selectedBoard"]),
        isBoardAdmin() {
            return this.selectedBoard?.boardAdminId == this.user.id;
        },
    },
    watch: {
        board: {
            handler: function () {
                this.syncBoardSettings();
            },
        },
        deep: true,
    },
    mounted() {
        this.syncBoardSettings();
    },
    methods: {
        /**
         * syncs form with the board prop
         */
        syncBoardSettings() {
            this.form = {
                color: this.board?.standardColor ?? "",
                icon: this.board?.standardIcon ?? "",
                priority: this.board?.standardPriority ?? "",
                timeTrackerType: this.board?.standardTimeTracker ?? "",
            };
        },
    },
    data() {
        return {
            form: {
                color: "",
                icon: "",
                priority: "",
                timeTrackerType: "",
            },
        };
    },
};
</script>

<style scoped>
:deep(.form-input) {
    min-height: 44px;
}

:deep(.vue3-icon-picker) {
    font-size: 2rem;
}
</style>

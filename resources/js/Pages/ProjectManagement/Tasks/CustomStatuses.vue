<template>
    <div
        class="w-full flex flex-wrap items-center"
        v-for="(status, index) in statuses"
        :key="'status-' + index"
    >
        <div class="form-group w-1/4 pr-2 pb-8">
            <TextInput
                :required="true"
                :label="$t('Name')"
                v-model="status.name"
                :error="errors?.[`customStatuses[${index}].name`]"
            />
        </div>
        <div class="form-group w-1/4 pr-2 pb-8">
            <SelectInput
                :required="true"
                :label="$t('Color')"
                v-model="status.color"
                :error="errors?.[`customStatuses[${index}].color`]"
            >
                <option value="red">{{ $t("Red") }}</option>
                <option value="green">{{ $t("Green") }}</option>
                <option value="blue">{{ $t("Blue") }}</option>
                <option value="black">{{ $t("Black") }}</option>
            </SelectInput>
        </div>
        <div class="w-1/4 pr-2 pb-8 flex items-center">
            <input
                class="mr-1"
                :id="'status' + index"
                type="checkbox"
                @input="keepOnlyOneDoneStatus(index)"
                v-model="status.isDoneStatus"
            />
            <label :for="'status' + index">{{ $t("Is Done Status") }}</label>
        </div>
        <button class="w-1/4 pr-2 pb-8" @click="statuses.splice(index, 1)">
            <font-awesome-icon icon="fa-regular fa-trash-can" />
        </button>
    </div>
    <button @click="addStatus" class="secondary-btn px-3 py-2 my-2">
        {{ $t("Add Status") }}
    </button>
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { v4 as uuid } from "uuid";
import { mapGetters } from "vuex";

export default {
    emits: ["statuses"],
    computed: {
        ...mapGetters(["errors"]),
        ...mapGetters("taskStatuses", {
            boardStatuses: "statuses",
        }),
    },
    components: {
        TextInput,
        SelectInput,
    },
    watch: {
        statuses: {
            handler: function () {
                this.$emit("statuses", this.statuses);
            },
            deep: true,
        },
    },
    data() {
        return {
            statuses: [],
        };
    },
    methods: {
        async keepOnlyOneDoneStatus(index) {
            await this.$nextTick();
            this.statuses = this.statuses.map((status, i) => {
                return {
                    ...status,
                    isDoneStatus: index === i,
                };
            });
        },
        addStatus() {
            this.statuses.push({
                id: uuid(),
                name: "",
                color: "",
                isDoneStatus: false,
            });
        },
    },
};
</script>

<style scoped></style>

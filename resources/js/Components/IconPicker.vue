<template>
    <div :class="$attrs.class">
        <label v-if="label" class="form-label" :for="id"
            ><span v-if="required" style="color: red">*</span>&nbsp;{{
                label
            }}:</label
        >
        <div class="input-box">
            <icon class="prefix" :name="displayValue" />
            <input
                :id="id"
                ref="input"
                v-bind="{ ...$attrs, class: null }"
                :readonly="isReadonly"
                class="form-input"
                :class="{ error: error }"
                :type="type"
                :value="displayValue"
                @click="isDisplay = !isDisplay"
                :placeholder="$t('Click to select')"
            />
        </div>
        <div v-if="error" class="form-error">{{ $t(error) ?? "" }}</div>
        <div
            :class="[
                'wrapper-class',
                pStore === 'milestone' ? 'w-200 bg-white relative' : '',
            ]"
        >
            <div
                v-if="isDisplay"
                :class="[
                    'select-box',
                    pStore === 'milestone' ? 'absolute bg-white' : '',
                ]"
            >
                <transition name="slided">
                    <div class="flex flex-wrap p-2">
                        <div
                            v-for="(icon, index) in icons"
                            :key="'icon-' + index"
                            class="cursor-pointer mr-2"
                            @click="
                                displayValue = icon;
                                isDisplay = false;
                                $emit('update:modelValue', displayValue);
                            "
                        >
                            <icon :name="icon" />
                        </div>
                    </div>
                </transition>
            </div>
        </div>
    </div>
</template>

<script>
import { v4 as uuid } from "uuid";
import Icon from "./Icon.vue";

export default {
    inheritAttrs: false,
    props: {
        required: { type: Boolean, required: false },
        id: {
            type: String,
            default() {
                return `text-input-${uuid()}`;
            },
        },
        type: {
            type: String,
            default: "text",
        },
        isReadonly: {
            type: Boolean,
            default: true,
        },
        error: String,
        label: String,
        selectValue: Object,
        modelValue: Object,
        icons: {
            type: Array,
            default: () => [],
        },
    },
    data() {
        return {
            displayValue: "",
            isDisplay: false,
        };
    },
    components: {
        Icon,
    },
    emits: ["update:modelValue"],
    methods: {
        focus() {
            this.$refs.input.focus();
        },
        select() {
            this.$refs.input.select();
        },
        setSelectionRange(start, end) {
            this.$refs.input.setSelectionRange(start, end);
        },
        selectedValue(option) {
            this.$emit("update:modelValue", option);
            this.displayValue = option.name;
            this.isDisplay = false;
        },
        close(e) {
            if (!this.$el.contains(e.target)) {
                this.isDisplay = false;
            }
        },
    },
    mounted() {
        if (this.modelValue) {
            this.displayValue = this.modelValue;
        }
        if (this.selectValue) {
            this.$emit("update:modelValue", this.selectValue);
            this.displayValue =
                this.selectValue?.[0]?.name ?? this.selectValue.name;
        }
        document.addEventListener("click", this.close);
    },
    beforeDestroy() {
        document.removeEventListener("click", this.close);
    },
};
</script>

<style scoped>
.w-200 {
    min-width: 200%;
}

.input-box {
    display: flex;
    align-items: center;
    background: white;
    border: 1px solid #c7c7c7;
    border-radius: 5px;
    padding-left: 0.5rem;
    padding-right: 0.5rem;
    overflow: hidden;
    font-family: sans-serif;
}

.input-box .prefix {
    font-size: 14px;
    color: black;
}

.input-box input {
    flex-grow: 1;
    font-size: 14px;
    background: #fff;
    border: none;
    outline: none;
    padding: 0.6rem;
    overflow: auto;
}
.form-input {
    outline: none !important;
    outline-width: 0;
    box-shadow: none;
}
</style>

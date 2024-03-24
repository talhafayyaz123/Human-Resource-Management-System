<template>
    <div :class="$attrs.class">
        <label
            v-if="label && !floatingLabel"
            class="form-label input-label"
            :for="id"
            >{{ label }}&nbsp;<span v-if="required" style="color: red">*</span>
            <span
                class="cursor-pointer"
                :title="tooltipMessage"
                v-if="tooltipMessage?.length"
            >
                <font-awesome-icon
                    icon="fa-solid fa-question"
                    class="tooltip"
                />&nbsp;</span
            ></label
        >
        <div v-if="floatingLabel" class="relative z-0">
            <input
                :id="'floating_standard_' + id"
                ref="input"
                v-bind="{ ...$attrs, class: null }"
                :readonly="isReadonly"
                class="form-control"
                :class="{ error: error }"
                :type="type"
                :value="modelValue"
                :style="isButton ? 'cursor: pointer;' : ''"
                @input="handleInput"
                :maxLength="maxLength"
            />
            <label
                :for="'floating_standard_' + id"
                class="input-label form-label"
                ><span v-if="required" style="color: red">*</span>&nbsp;{{
                    label
                }}
            </label>
        </div>
        <input
            v-else
            :id="id"
            ref="input"
            v-bind="{ ...$attrs, class: null }"
            :readonly="isReadonly"
            class="form-control"
            :class="{ error: error }"
            :type="type"
            :value="modelValue"
            :style="
                isButton
                    ? 'cursor: pointer;'
                    : shouldHighlight
                    ? 'border: 1px solid #2957a4;'
                    : ''
            "
            @input="handleInput"
            :maxLength="maxLength"
            :placeholder="placeholder"
        />
        <div class="input-count" v-if="type == 'text'">
            <div
                v-if="modelValue?.length"
                :style="
                    modelValue.length === this.maxLength
                        ? { color: 'red' }
                        : { color: 'gray' }
                "
            >
                {{ modelValue?.length }}/{{ maxLength }}
            </div>
        </div>
        <div
            class="input-group-append"
            v-if="type === 'password' || showPassword === true"
        >
            <span
                class="input-group-text cursor-pointer"
                @click="togglePassword"
            >
                <CustomIcon
                    :name="showPassword ? `eyeblackIcon` : `eyeblackClsoeIcon`"
                />
                <!-- {{
                        showPassword ? $t("Hide Password") : $t("Show Password")
                    }} -->
            </span>
        </div>

        <div v-if="error" class="form-error">{{ $t(error) ?? "" }}</div>
    </div>
</template>

<script>
import { v4 as uuid } from "uuid";

export default {
    inheritAttrs: true,

    props: {
        showPassword: { type: Boolean, default: false },
        shouldHighlight: { type: Boolean, default: false },
        isButton: { type: Boolean, required: false },
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
            default: false,
        },
        placeholder: {
            type: String,
            default: "",
        },

        floatingLabel: Boolean,
        error: String,
        label: String,
        modelValue: String,
        tooltipMessage: {
            type: String,
            required: false,
            default: "",
        },
        maxLength: {
            type: Number,
            default: 200, // Default character limit
        },
    },
    emits: ["update:modelValue", "child-event"],
    methods: {
        togglePassword() {
            this.$emit("child-event", this.showPassword);
        },
        focus() {
            this.$refs.input.focus();
        },
        select() {
            this.$refs.input.select();
        },
        setSelectionRange(start, end) {
            this.$refs.input.setSelectionRange(start, end);
        },
        handleInput(event) {
            const inputValue = event.target.value;
            const maxLength = this.maxLength; // Assuming maxLength is a property in your component
            if (inputValue.length > maxLength) {
                event.target.value = inputValue.slice(0, maxLength); // Set the input value to the truncated value
            } else {
                this.$emit("update:modelValue", inputValue); // Emit an event to update the model value
            }
        },
    },
};
</script>

<style scoped>
.tooltip {
    border-radius: 50%;
    font-size: 0.6rem;
    padding: 2px 4px 2px 4px;
    background-color: black;
    color: white;
}
</style>

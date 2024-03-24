<template>
    <div :class="$attrs.class">
        <label v-if="label" class="form-label input-label" :for="id">{{ label }}&nbsp;<span v-if="required" style="color: red;">*</span></label>
        <textarea
            :id="id"
            ref="input"
            v-bind="{ ...$attrs, class: null }"
            class="form-textarea form-control"
            :class="{ error: error }"
            :value="modelValue"
            :rows="rows"
            :readonly="isReadonly"
            :maxLength="maxLength"
            @input="handleInput"
        />
        <!-- @input="$emit('update:modelValue', $event.target.value)" -->

        <div class="input-count" v-if="showCount">
            <div v-if="modelValue?.length" :style="modelValue.length === this.maxLength ? { 'color': 'red' } : { 'color': 'gray' }" >{{ modelValue?.length }}/{{ maxLength }}</div>
         </div>
        <div v-if="error" class="form-error">{{ $t(error) ?? "" }}</div>
    </div>
</template>

<script>
import { v4 as uuid } from "uuid";

export default {
    inheritAttrs: false,
    props: {
        required: { type: Boolean, required: false },
        id: {
            type: String,
            default() {
                return `textarea-input-${uuid()}`;
            },
        },
        rows: {
            type: Number,
            default: 5,
        },
        error: String,
        label: String,
        modelValue: String,
        isReadonly: {
            type: Boolean,
            default: false,
        },
        showCount:true,
        maxLength: {
            type: Number,
            default: 10000, // Default character limit
        },
    },
    emits: ["update:modelValue"],
    methods: {
        focus() {
            this.$refs.input.focus();
        },
        select() {
            this.$refs.input.select();
        },
        handleInput(event) {
            const inputValue = event.target.value;
            const maxLength = this.maxLength; // Assuming maxlength is a property in your component
            if (inputValue.length > maxLength) {
                event.target.value = inputValue.slice(0, maxLength); // Set the input value to the truncated value
            } else {
                this.$emit('update:modelValue', inputValue); // Emit an event to update the model value
            }
        }
    },
};
</script>

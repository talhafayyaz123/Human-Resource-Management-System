<template>
    <div :class="$attrs.class">
        <label v-if="label" class="form-label" :for="id"><span v-if="required" style="color: red;">*</span>&nbsp;{{ label }}:</label>
        <input
            :id="id"
            ref="input"
            v-bind="{ ...$attrs, class: null }"
            class="form-input"
            :class="{ error: error }"
            type="text"
            :readonly="isReadonly"
            :value="modelPriceValue(dollarSign)"
            @input="
                $emit(
                    'update:modelValue',
                    String($event.target.value).replace(/[^0-9.]+/g, '')
                )
            "
        />
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
        isReadonly: {
            type: Boolean,
            default: false,
        },
        error: String,
        label: String,
        modelValue: String,
        dollarSign: { type: Boolean, default: true },
    },
    emits: ["update:modelValue"],
    methods: {
        modelPriceValue(appendDollarSign) {
            return appendDollarSign ? `${this.modelValue}€` : this.modelValue;
        },
    },
};
</script>

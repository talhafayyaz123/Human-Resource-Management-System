<template>
    <!-- <div :class="$attrs.class">
        <label v-if="label" class="form-label" :for="id"
            ><span v-if="required" style="color: red">*</span>&nbsp;{{
                label
            }}:</label
        >
        <div class="input-box">
            <span v-if="this.language === 'en' && showPrefix" class="prefix"
                >€</span
            >
            <input
                :id="id"
                ref="input"
                v-bind="{ ...$attrs, class: null }"
                :readonly="isReadonly"
                :style="forceLeftBound ? 'text-align: left' : ''"
                :class="[
                    this.language === 'de' ? 'text-right' : 'text-left',
                    error ? 'error' : '',
                ]"
                type="text"
                :value="maskedValue"
                @keypress="limitInput"
                @change="valueChanged"
                class="form-control"
            />
            <span v-if="this.language === 'de' && showPrefix" class="prefix"
                >€</span
            >
        </div>
        <div v-if="error" class="form-error">{{ $t(error) ?? "" }}</div>
    </div> -->
    <div :class="$attrs.class" class="relative form-group">
        <label v-if="label" class="form-label input-label" :for="id"
            >{{ label }}&nbsp;<span v-if="required" style="color: red"
                >*</span
            ></label
        >
        <!-- <div class="input-box"> -->
        <!-- <span v-if="this.language === 'en' && showPrefix" class="prefix"
                >€</span
            > -->
        <input
            :id="id"
            ref="input"
            v-bind="{ ...$attrs, class: null }"
            :readonly="isReadonly"
            :style="forceLeftBound ? 'text-align: left' : ''"
            :class="[
                this.language === 'de' ? 'text-right' : 'text-left',
                error ? 'error' : '',
            ]"
            type="text"
            :value="maskedValue"
            @keypress="limitInput"
            @change="valueChanged"
            class="form-control"
        />
        <!-- <span v-if="this.language === 'de' && showPrefix" class="prefix"
                >€</span
            >
        </div> -->
        <div v-if="error" class="form-error">{{ $t(error) ?? "" }}</div>
    </div>
</template>

<script>
import { v4 as uuid } from "uuid";
import { mapGetters } from "vuex";

export default {
    inheritAttrs: true,
    props: {
        allowNegative: { type: Boolean, default: false },
        minimumFractionDigits: { type: Number, default: 2 },
        maximumFractionDigits: { type: Number, default: 20 },
        forceLeftBound: { type: Boolean, required: false, default: false },
        roundNearQuarter: { type: Boolean, required: false, default: false },
        required: { type: Boolean, required: false },
        id: {
            type: String,
            default() {
                return `number-input-${uuid()}`;
            },
        },
        isReadonly: {
            type: Boolean,
            default: false,
        },
        error: String,
        label: String,
        modelValue: String,
        showPrefix: { type: Boolean, default: true },
        canBeNull: { type: Boolean, default: false },
    },
    data() {
        return {
            mapObj: {
                ",": ".",
                ".": "",
                "-": "-",
                0: 0,
                1: 1,
                2: 2,
                3: 3,
                4: 4,
                5: 5,
                6: 6,
                7: 7,
                8: 8,
                9: 9,
            },
            unmaskedValue: "",
            maskedValue: "",
        };
    },
    computed: {
        ...mapGetters(["language"]),
    },
    emits: ["update:modelValue", "inputEvent"],
    mounted() {
        let value = this.modelValue;
        if (!value) {
            if (this.canBeNull) {
                return;
            }
            value = "0";
        }
        value = this.roundNearQuarter
            ? this.roundNearQtr(parseFloat(value))
            : this.modelValue;

        let formattedNumber = this.roundNearQuarter
            ? this.roundNearQtr(parseFloat(value))
            : value;
        if (this.language === "en") {
            formattedNumber = new Intl.NumberFormat("en-GB", {
                currency: "EUR",
                minimumFractionDigits: this.minimumFractionDigits,
                maximumFractionDigits: this.maximumFractionDigits,
            }).format(value);
        } else if (this.language === "de") {
            formattedNumber = new Intl.NumberFormat("de-DE", {
                currency: "EUR",
                minimumFractionDigits: this.minimumFractionDigits,
                maximumFractionDigits: this.maximumFractionDigits,
            }).format(value);
        }
        // check for non numbers
        if (isNaN(value)) {
            value = "0";
        }
        this.unmaskedValue = value;
        this.maskedValue = formattedNumber;
    },
    watch: {
        modelValue(val) {
            let value = val;
            if (!value) {
                if (this.canBeNull) {
                    this.maskedValue = "";
                    this.unmaskedValue = "";
                    return;
                }
                value = "0";
            }
            value = this.roundNearQuarter
                ? this.roundNearQtr(parseFloat(value))
                : val;
            let formattedNumber = this.roundNearQuarter
                ? this.roundNearQtr(parseFloat(value))
                : value;
            if (this.language === "en") {
                formattedNumber = new Intl.NumberFormat("en-GB", {
                    currency: "EUR",
                    minimumFractionDigits: this.minimumFractionDigits,
                    maximumFractionDigits: this.maximumFractionDigits,
                }).format(value);
            } else if (this.language === "de") {
                formattedNumber = new Intl.NumberFormat("de-DE", {
                    currency: "EUR",
                    minimumFractionDigits: this.minimumFractionDigits,
                    maximumFractionDigits: this.maximumFractionDigits,
                }).format(value);
            }
            // check for non numbers
            if (isNaN(value)) {
                value = "0";
            }
            this.unmaskedValue = value;
            this.maskedValue = formattedNumber;
        },
        language() {
            let modifiedMapObj = {
                ...this.mapObj,
                ".": ",",
            };
            this.maskedValue = this.replaceAll(
                this.maskedValue,
                modifiedMapObj
            );
        },
    },
    methods: {
        roundNearQtr(number) {
            return (Math.round(number * 4) / 4).toFixed(2);
        },
        replaceAll(str, mapObj) {
            var re = new RegExp(Object.keys(mapObj).join("|"), "gi");

            return str.replace(re, (matched) => {
                return mapObj[matched.toLowerCase()];
            });
        },
        limitInput(event) {
            if (
                (event.charCode < 48 || event.charCode > 57) &&
                (this.allowNegative ? event.charCode != 45 : true)
            ) {
                if (
                    (this.language === "en" && event.charCode == 46) ||
                    (this.language === "de" && event.charCode == 44)
                ) {
                    if (
                        (this.language === "de" &&
                            event.target.value.includes(",")) ||
                        (this.language === "en" &&
                            event.target.value.includes("."))
                    ) {
                        event.preventDefault();
                    }
                    return;
                }
                event.preventDefault();
            }
        },
        async valueChanged(event) {
            let value = event.target.value;
            if (!value) {
                if (this.canBeNull) {
                    return;
                }
                value = "0";
            }
            let formattedNumber = value;
            if (this.language === "en") {
                value = value.replaceAll(",", "");
                value = this.roundNearQuarter
                    ? this.roundNearQtr(parseFloat(value))
                    : value;
                formattedNumber = new Intl.NumberFormat("en-GB", {
                    currency: "EUR",
                    minimumFractionDigits: this.minimumFractionDigits,
                    maximumFractionDigits: this.maximumFractionDigits,
                }).format(value);
            } else if (this.language === "de") {
                value = this.replaceAll(value, this.mapObj);
                value = this.roundNearQuarter
                    ? this.roundNearQtr(parseFloat(value))
                    : value;
                formattedNumber = new Intl.NumberFormat("de-DE", {
                    currency: "EUR",
                    minimumFractionDigits: this.minimumFractionDigits,
                    maximumFractionDigits: this.maximumFractionDigits,
                }).format(value);
            }
            // check for non numbers
            if (isNaN(value)) {
                value = "0";
            }
            this.unmaskedValue = value;
            this.maskedValue = formattedNumber;
            let e = event;
            e.target.unmaskedValue = this.unmaskedValue;
            this.$emit("update:modelValue", this.unmaskedValue);
            this.$emit("inputEvent", e);
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
    },
};
</script>

<style scoped>
.input-box {
    display: flex;
    align-items: center;
    background: white;
    border: 1px solid rgb(224, 224, 224);
    border-radius: 4px;
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
/* input:focus {
    flex-grow: 1;
    font-size: 14px;
    background: #fff;
    border: none;
    outline: none;
    padding: 0.6rem;
    outline-width: 0;
} */
</style>

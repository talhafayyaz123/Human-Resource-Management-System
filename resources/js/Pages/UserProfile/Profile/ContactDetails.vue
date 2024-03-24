<template>
    <div class="w-full">
        <div class="form-group">
            <text-input
                v-model="profileForm.phone"
                :error="errors.phone"
                class=""
                :label="$t('Phone')"
            />
        </div>
    </div>
    <div class="w-full">
        <div class="form-group">
            <text-input
                v-model="profileForm.privateEmail"
                :error="errors.privateEmail"
                class=""
                :label="$t('Private Email')"
            />
        </div>
    </div>
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
export default {
    inject: ["existingUser"],
    components: {
        TextInput,
    },
    props: ["profileForm", "errors"],
    data() {
        return {};
    },
};
</script>

<template>
    <div class="">
        <div
            v-for="(work, index) in jobForm.workHours"
            :key="index"
            class="grid items-center grid-cols-5 gap-6"
            :class="{
                'pb-0': index === jobForm.workHours.length - 1 && errorsExist,
            }"
        >
            <div class="form-group col-span-2">
                <MultiSelectInput
                    :required="true"
                    v-model="work.days"
                    class=""
                    :textLabel="$t('Working Days')"
                    :multiple="true"
                    :options="['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun']"
                >
                    <template #option="{ props }">
                        <p class="capitalize">{{ props.option }}</p>
                    </template>
                </MultiSelectInput>
            </div>
            <div class="form-group col-span-2">
                <text-input
                    type="number"
                    v-model="work.numOfHours"
                    :error="errors.numOfHours"
                    class=""
                    :label="$t('Daily Hours')"
                />
            </div>
            <div class="form-group col-span-1">
                <button
                    role="button"
                    type="button"
                    @click="jobForm.workHours.splice(index, 1)"
                    style="color: red"
                >
                    <font-awesome-icon
                        ref="icon"
                        icon="fa-regular fa-trash-can"
                    />
                </button>
            </div>
        </div>
        <div v-if="errorsExist" class="form-error mb-2">
            {{ $t("Duplication exists in the days. Please correct it") }}
        </div>
        <div class="mt-4">
            <button
                role="button"
                type="button"
                @click="
                    jobForm.workHours.push({
                        days: '',
                        numOfHours: '',
                    })
                "
                class="secondary-btn"
            >   
            <div class="icon mr-1">
                <CustomIcon name="addIcon" />
            </div>
                <span>{{ $t("Add") }}</span>
            </button>
        </div>
    </div>
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";

export default {
    components: {
        TextInput,
        MultiSelectInput,
    },
    props: ["jobForm", "errors"],
    computed: {
        errorsExist() {
            for (let key in this.errors) {
                if (key.startsWith("workHoursArray.")) {
                    return true;
                }
            }
            return false;
        },
    },
};
</script>

<style></style>

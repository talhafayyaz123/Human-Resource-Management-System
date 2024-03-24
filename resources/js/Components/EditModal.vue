<template>
    <div>
        <div
            class="custom-modal"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true"
        >
            <div class="modal-overlay"></div>

            <div class="modal-content">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0" >

                    <div :style="`width: ${width}`" :class="`${classSize}`" class="modal-bg">
                        <div class="modal-header">
                            <div>
                                <h2 class="">
                                    {{ title }}
                                </h2>
                                <slot name="warning"></slot>
                            </div>
                            <div class="cursor-pointer" @click="$emit('toggleModal', false)">
                                <CustomIcon name="xCircleIcon" />
                            </div>
                        </div>
                        <div class="modal-body">
                            <slot name="body"></slot>
                        </div>
                        <div class="modal-footer">
                            <slot name="footer"></slot>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        title: String,
        width: String,
        classSize: String,
    },
    components: {},
};
</script>

<style scoped>
.z-99999 {
    z-index: 99999;
}
.model-body {
    min-height: max-content;
    overflow-y: auto;
    overflow-x: hidden;
}
@media screen and (max-height: 90vh) {
    .model-body {
        max-height: 90vh;
        overflow-y: auto;
    }
}
</style>

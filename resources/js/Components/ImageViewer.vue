<template>
    <div class="modal">
        <div class="modal-content">
            <span class="close" @click="closeModal">&times;</span>

            <img :src="image" class="modal-image" alt="enlarged image" />
        </div>
    </div>
</template>
<script>
export default {
    props: {
        image: {
            type: String,
            required: true,
        },
        closeModal: {
            type: Function,
            required: true,
        },
    },
};
</script>
<style scoped>
.modal {
    display: flex;
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.7);
    justify-content: center;
    align-items: center;
    z-index: 999;
}

.modal-content {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 50rem;
    width: 100rem;
    border: 50px solid white;
    border-radius: 10px;
}

.modal-image {
    height: 100%;
    width: 100%;
    object-fit: fill;
    object-position: center;
}

.close {
    position: absolute;
    top: -70px;
    right: -40px;
    font-size: 3.5em;
    z-index: 10;
    color: #000;
    cursor: pointer;
}
</style>

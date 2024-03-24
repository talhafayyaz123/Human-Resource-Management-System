<template>
    <!-- The Modal -->
    <div id="myModal" class="modal">
        <span @click="$emit('toggleModal', false)" class="close">&times; </span>
        <div class="flex items-center justify-center" id="image-container">
            <img class="modal-content" id="img" :src="src" alt="somthing" />
        </div>
        <div id="caption"></div>
    </div>
</template>

<script>
export default {
    emits: ["toggleModal"],
    props: {
        src: {
            type: String,
            default: "",
            required: true,
        },
    },
    data() {
        return {};
    },
    components: {},
};
</script>

<style scoped>
/* The Modal (background) */
.modal {
    display: block; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 10; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0, 0, 0); /* Fallback color */
    background-color: rgba(0, 0, 0, 0.7); /* Black w/ opacity */
}

#image-container {
    height: 100%;
}

#img {
    position: relative;
}

/* Modal Content (image) */
.modal-content {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
}

/* Caption of Modal Image */
#caption {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
    text-align: center;
    color: #ccc;
    padding: 10px 0;
    height: 150px;
}

/* Add Animation */
.modal-content,
#caption {
    -webkit-animation-name: zoom;
    -webkit-animation-duration: 0.6s;
    animation-name: zoom;
    animation-duration: 0.6s;
}

@-webkit-keyframes zoom {
    from {
        -webkit-transform: scale(0);
    }
    to {
        -webkit-transform: scale(1);
    }
}

@keyframes zoom {
    from {
        transform: scale(0);
    }
    to {
        transform: scale(1);
    }
}

/* The Close Button */
.close {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
}

.close:hover,
.close:focus {
    color: #bbb;
    text-decoration: none;
    cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px) {
    .modal-content {
        width: 100%;
    }
}
</style>

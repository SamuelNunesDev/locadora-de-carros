<template>
    <div class="alert alert-dismissible fade show" :class="feedback" role="alert">
        <strong>{{msg_status}}</strong> {{msg}}
        <slot></slot>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</template>

<script>
    export default {
        props: [
            'class_status',
            'msg_status',
            'msg'
        ],
        computed: {
            feedback() {
                return `alert-${this.class_status}`
            }
        }
    }
</script>
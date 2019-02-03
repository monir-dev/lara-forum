<template>
    <button :class="classes" @click="subscribe">Subscribe</button>
</template>

<script>
    export default {
        props: ['subscribed'],
        computed: {
            classes() {
                return ['btn', this.subscribed ? 'btn-success' : 'btn-outline-dark']
            }
        },
        methods: {
            subscribe() {
                let requestType = this.subscribed ? 'delete' : 'post';

                axios[requestType](location.pathname + '/subscriptions');

                this.subscribed = !this.subscribed;
            }
        }
    }
</script>
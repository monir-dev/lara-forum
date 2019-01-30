<template>
    <div>
        <div v-for="(reply, index) in items">
            <Reply :item="reply" @deleted="remove(index)"></Reply>
        </div>
    </div>
</template>

<script>
    import Reply from './Reply';
    export default {
        props: ['data'],
        components: { Reply },
        data() {
            return {
                items: this.data
            }
        },
        methods: {
            remove(index) {
                this.items.splice(index, 1);
                this.$emit('removed');
                flash('Reply was deleted!');
            }
        }
    }
</script>

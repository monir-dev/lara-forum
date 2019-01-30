<template>
    <button type="submit" :class="classes" @click="toggle">
        <i class="fas fa-heart"></i>
        <span v-text="favoritesCount"></span>
    </button>
</template>

<script>
    export default {
        props: ['reply'],

        data() {
            return {
                favoritesCount: this.reply.favoritesCount,
                isFavorited: this.reply.isFavorited
            }
        },

        computed: {
            classes() {
                return ['btn btn-sm', this.isFavorited ? 'btn-outline-success' : 'btn-outline-secondary']
            },

            endpoint() {
                return '/replies/' + this.reply.id + '/favorites';
            }
        },

        methods: {
            toggle() {
                this.isFavorited ? this.unfavorite() : this.favorite();
            },

            favorite() {
                axios.post(this.endpoint);
                this.isFavorited = true;
                this.favoritesCount++;
            },

            unfavorite() {
                axios.delete(this.endpoint);
                this.isFavorited = false;
                this.favoritesCount--;
            }
        }
    }
</script>

<template>
    <div class="card mt-3" :class="isBest ? 'text-white bg-dark': ''" :id="'reply-'+id">
        <div class="card-header">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/'+item.owner.name" v-text="item.owner.name">
                    </a> said in <span v-text="ago"></span>...
                </h5>

                <div v-if="signedIn">
                    <Favorite :reply="item"></Favorite>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div v-if="editing">
                <form @submit="update">
                    <div class="form-group">
                        <textarea name="" id="" class="form-control" rows="2" v-model="body" required></textarea>
                    </div>
                    <button class="btn btn-primary btn-sm">Update</button>
                    <button class="btn btn-link" @click="editing = false" type="button">Cencel</button>
                </form>
            </div>
            <div v-else v-html="body"></div>
        </div>

        <div class="card-footer d-flex" v-if="authorize('owns', reply) || authorize('owns', reply.thread) && ! isBest">
            <div v-if="authorize('owns', reply)">
                <button class="btn btn-outline-warning btn-sm mr-2" @click="editing = true">Edit</button>
                <button class="btn btn-outline-danger btn-sm mr-2" @click="destroy">Delete</button>
            </div>
            <button class="btn btn-outline-dark btn-sm mr-2 ml-auto" @click="markAsBestReply" v-if="authorize('owns', reply.thread) && ! isBest">Best reply?</button>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';

    import Favorite from './Favorite.vue';

    export default {
        props: ['item'],
        components: { Favorite },
        data() {
            return {
                editing: false,
                id: this.item.id,
                body: this.item.body,
                isBest: this.item.isBest,
                reply: this.item
            }
        },
        computed: {
            ago() {
                const time = moment(this.item.created_at + "+00:00", "YYYY-MM-DD HH:mm:ssZ");
                return time.fromNow() + '...';
            }
        },

        created() {
            window.events.$on('best-reply-selected', id => this.isBest = (id == this.id));
        },

        methods: {
            update() {
                axios.patch('/replies/' + this.item.id, {
                    body: this.body
                })
                .then(() => {
                    this.editing = false;
                    flash('Updated!');
                })
                .catch(err => {
                    flash(err.response.data, 'danger')
                });


            },
            destroy() {
                axios.delete('/replies/' + this.item.id);

                this.$emit('deleted', this.item.id);
            },

            markAsBestReply()
            {
                axios
                    .post(`/replies/${this.id}/best`)
                    .then(() => window.events.$emit('best-reply-selected', this.id))
                    .catch(err => console.log(err));

            }
        }
    }
</script>

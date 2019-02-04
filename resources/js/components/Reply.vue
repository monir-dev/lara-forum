<template>
    <div class="card mt-3" :id="'reply-'+id">
        <div class="card-header">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/'+item.owner.name" v-text="item.owner.name">
                    </a> said in <span v-text="ago"></span>...
                </h5>

                <div v-if="signedId">
                    <Favorite :reply="item"></Favorite>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea name="" id="" class="form-control" rows="2" v-model="body"></textarea>
                </div>
                <button class="btn btn-primary btn-sm" @click="update">Update</button>
                <button class="btn btn-link" @click="editing = false">Cencel</button>
            </div>
            <div v-else v-text="body"></div>
        </div>

        <div class="card-footer d-flex" v-if="canDelete">
            <button class="btn btn-outline-warning btn-sm mr-2" @click="editing = true">Edit</button>
            <button class="btn btn-outline-danger btn-sm mr-2" @click="destroy">Delete</button>
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
                body: this.item.body
            }
        },
        computed: {
            ago() {
                const time = moment(this.item.created_at + "+00:00", "YYYY-MM-DD HH:mm:ssZ");
                return time.fromNow() + '...';
            },
            signedId() {
                return window.App.signedId;
            },
            canDelete() {
                return this.authorize(user => this.item.user_id === user.id);
            }
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
            }
        }
    }
</script>

<template>
    <div class="card mt-3" :id="'reply-'+id">
        <div class="card-header">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/'+item.owner.name" v-text="item.owner.name">
                    </a> said {{ item.created_at }}...
                </h5>
                <!--@if (Auth::check())-->
                    <!--<div>-->
                        <!--<Favorite :reply="{{ $reply }}"></Favorite>-->
                    <!--</div>-->
                <!--@endif-->
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

        <!--@can('delete', $reply)-->
            <div class="card-footer d-flex">
                <button class="btn btn-outline-warning btn-sm mr-2" @click="editing = true">Edit</button>
                <button class="btn btn-outline-danger btn-sm mr-2" @click="destroy">Delete</button>
            </div>
        <!--@endcan-->
    </div>
</template>

<script>
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
        methods: {
            update() {
                axios.patch('/replies/' + this.item.id, {
                    body: this.body
                });

                this.editing = false;
                flash('Updated!');
            },
            destroy() {
                axios.delete('/replies/' + this.item.id);

                this.$emit('deleted', this.item.id);

                // $(this.$el).fadeOut(300, () => {
                //     flash('Your Reply has been deleted');
                // });
            }
        }
    }
</script>

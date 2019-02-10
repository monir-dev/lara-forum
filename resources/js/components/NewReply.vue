<template>

    <div class="mt-4">
        <div v-if="signedIn">
            <div class="form-group">
                <textarea
                    name="body"
                    id="body"
                    class="form-control"
                    rows="4"
                    placeholder="Have something to say..."
                    required
                    v-model="body"
                ></textarea>
            </div>
            <button type="submit" class="btn btn-primary" @click="addReply">Post</button>
        </div>

        <p class="text-center" v-else>
            Please <a href="/login">Sign in</a> to participate in this disscussion.
        </p>
    </div>
</template>

<script>
    import 'jquery.caret';
    import 'at.js';

    export default {
        props: ['endpoint'],
        data() {
            return {
                body: ''
            }
        },
        mounted() {
            $("#body").atwho({
                at: "@",
                delay: 750,
                callbacks: {
                    remoteFilter: function(query, callback) {
                        $.getJSON("/api/users", { name: query }, function(usernames) {
                            callback(usernames)
                        });
                    }
                }
            });
        },

        methods: {
            addReply() {
                axios
                    .post(this.endpoint, { body: this.body })
                    .then(({data}) => {
                        this.body = '';

                        flash('Your reply has been added');

                        this.$emit('created', data);
                    })
                    .catch(err => {
                        flash(err.response.data, 'danger');
                    });
            }
        }
    }
</script>

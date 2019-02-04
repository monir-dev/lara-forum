<template>

    <div class="mt-4">
        <div v-if="signedId">
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
    export default {
        props: ['endpoint'],
        data() {
            return {
                body: ''
            }
        },
        computed:{
            signedId() {
                return window.App.signedId;
            }
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

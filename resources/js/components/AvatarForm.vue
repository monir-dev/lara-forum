<template>
    <div>
        <div class="level mb-2">
            <img :src="avatar" alt="avatar" width="50" height="50" class="mr-2">
            <h2 v-text="name"></h2>
        </div>

        <form v-if="canUpdate" method="post"  enctype="multipart/form-data">
            <image-upload name="avatar" class="mb-1" @uploaded="onUpload"></image-upload>
        </form>


    </div>
</template>

<script>
    import ImageUpload from './ImageUpload';
    export default {
        props: ['user'],
        components: {ImageUpload},
        data() {
            return {
                name : this.user.name,
                avatar: this.user.avatar_path
            }
        },
        computed: {
            canUpdate() {
                return this.authorize(user => user.id === this.user.id);
            }
        },
        methods: {
            onUpload(payload) {
                console.log(payload);
                this.avatar = payload.src;
                // persist to server
                this.persist(payload.file);
            },

            persist(file) {
                let data = new FormData();

                data.append('avatar', file);

                axios.post(`/api/users/${this.user.name}/avatar`, data)
                    .then(() => flash('Avatar uploaded.'));
            }
        }
    }
</script>
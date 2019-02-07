<template>
    <input type="file" name="avatar" id="avatar" class="form-control-file" accept="image/*" @change="onUpload">
</template>

<script>
    export default {
        methods: {
            onUpload(e) {
                if (!e.target.files.length) return;

                let file = e.target.files[0];
                let reader = new FileReader();
                reader.readAsDataURL(file);

                reader.onload = e => {
                    let src = e.target.result;

                    this.$emit('uploaded', { src, file });
                }
            }
        }
    }
</script>
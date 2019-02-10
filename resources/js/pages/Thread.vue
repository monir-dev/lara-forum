<script>
    import SubscribeButton from '../components/SubscribeButton';
    import Replies from '../components/Replies';
    export default {
        props: ['thread'],
        components: { Replies, SubscribeButton },
        data() {
            return {
                repliesCount: this.thread.replies_count,
                locked: this.thread.locked
            }
        },
        methods: {
            toggleLock() {
                axios[this.locked ? 'delete' : 'post'](`/threads-locked/${this.thread.slug}`)
                    .then(() => this.locked = !this.locked)
                    .catch(err => console.log(err));

            }
        }
    }

</script>

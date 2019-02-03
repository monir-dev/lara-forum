<script>
    import SubscribeButton from '../components/SubscribeButton';
    import Replies from '../components/Replies';
    export default {
        props: ['initialRepliesCount'],
        components: { Replies, SubscribeButton },
        data() {
            return {
                repliesCount: this.initialRepliesCount
            }
        }
    }

</script>

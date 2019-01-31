<template>
    <div>
        <div v-for="(reply, index) in items" :key="reply.id">
            <Reply :item="reply" @deleted="remove(index)"></Reply>
        </div>
        <paginator :dataSet="dataSet" @updated="fetch"></paginator>

        <new-reply :endpoint="endpoint" @created="add"></new-reply>

    </div>
</template>

<script>
    import Reply from './Reply';
    import NewReply from './NewReply';
    import collection from '../mixins/collection';

    export default {
        components: { Reply, NewReply },
        mixins: [collection],
        data() {
            return {
                dataSet: false,
                items: [],
                endpoint: location.pathname + '/replies'
            }
        },

        created() {
            this.fetch();
        },

        methods: {
            fetch(page) {
                axios.get(this.url(page))
                    .then(this.refresh);
            },

            url(page) {

                if ( !page ) {
                    const queryStirng = location.search.match(/page=(\d+)/);
                    page = queryStirng ? queryStirng[1] : 1;
                }

                return `${location.pathname}/replies?page=${page}`;
            },

            refresh({data}) {
                this.dataSet = data;
                this.items = data.data;

                window.scrollTo(0,0);
            }
        }
    }
</script>

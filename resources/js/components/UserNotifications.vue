<template>
    <li class="nav-item dropdown" v-if="notifications.length">
        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
            <i class="far fa-bell"></i>
            <span class="badge badge-success" v-text="notifications.length"></span>
            <span class="caret"></span>
        </a>

        <div class="dropdown-menu" aria-labelledby="notificationDropdown">

            <div  v-for="notification in notifications">
                <a
                    class="dropdown-item"
                    :href="notification.data.link"
                    v-text="notification.data.message"
                    @click="markAsRead(notification)"
                >
                </a>
            </div>

        </div>
    </li>
</template>

<script>
    export default  {
        data() {
            return {
                notifications: false
            }
        },

        created() {
            axios.get(`/profiles/${window.App.user.name}/notifications`)
                .then(res => this.notifications = res.data);
        },

        methods: {
            markAsRead(notification) {
                axios.delete(`/profiles/${window.App.user.name}/notification/${notification.id}`);

                // this.notifications.
            }
        }
    }
</script>
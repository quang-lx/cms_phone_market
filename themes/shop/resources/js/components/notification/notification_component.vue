<template>
  <div>
    <a class="nav-link" href="#" @click.prevent="showNotification">
          <i class="el-icon-bell"></i>
          <span class="badge badge-danger navbar-badge">{{this.list_notification.length}}</span>
    </a>
    <el-drawer
      title="I am the title"
      :visible.sync="drawer"
      :direction="direction"
      :before-close="handleClose"
    >
     <div class="">
          <a class="dropdown-item" href="#" @click.prevent="showBoxChat(item)" v-for="(item, index) in list_notification" v-bind:key = index >
            <!-- Message Start -->
            <div class="media">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  {{item.title}}
                </h3>
                <p class="text-sm">{{item.content}}</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <a class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
    </el-drawer>
  </div>
</template>

<script>
export default {
  data() {
    return {
        drawer: false,
        direction: "rtl",
        list_notification:[],
    };
  },
  methods: {
    handleClose(done) {
          done();
    },
    showNotification(){
        this.drawer = true
        setTimeout(() => {document.getElementsByClassName('v-modal')[0].classList.remove('v-modal')}, 300);
    },
    fetchNotification(){
        const properties = {
          page: 0,
          per_page: 1000,
          vt_shop_proudct: 1
        };
        axios.get(route('apishop.shopordernotification.index', _.merge(properties, {})))
        .then((response) => {
          this.list_notification = response.data.data;

        });
    },

  },
  mounted() {
    setInterval(this.fetchNotification,5000);
  },
};
</script>

<style>
</style>
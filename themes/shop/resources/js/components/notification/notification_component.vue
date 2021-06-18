<template>
  <div>
    <a class="nav-link" href="#" @click.prevent="showNotification">
      <i class="el-icon-bell"></i>
      <span class="badge badge-danger navbar-badge">{{
        this.list_notification.length
      }}</span>
    </a>
    <el-drawer
      title="I am the title"
      :visible.sync="drawer"
      :direction="direction"
      :before-close="handleClose"
    >
    <div>
      <div class="overflow-auto" data-spy="scroll" v-on:scroll="scrollLoadData" style="height:500px" id="list_notification">
        <a
          class="dropdown-item" v-bind:class="{ 'bg-noti': item.seen==0 }"
          href="#"
          @click.prevent="redirectRoute(item)"
          v-for="(item, index) in list_notification"
          v-bind:key="index"
        >
          <!-- Message Start -->
          <div class="media">
            <div class="media-body">
              <h3 class="dropdown-item-title">
                {{ item.title }}
              </h3>
              <p class="text-sm">{{ item.content }}</p>
              <p class="text-sm text-muted">
                <i class="far fa-clock mr-1"></i> {{time2TimeAgo(item.created_at)}}
              </p>
            </div>
          </div>
          <!-- Message End -->
        </a>
       
      </div>
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
      list_notification: [],
      page: 1,
      count_page: 1,
      per_page: 7
    };
  },
  methods: {
    handleClose(done) {
      done();
    },
    showNotification() {
      this.drawer = true;
      setTimeout(() => {
        document
          .getElementsByClassName("v-modal")[0]
          .classList.remove("v-modal");
      }, 300);
    },
    fetchNotification() {
      const properties = {
        page: 1,
        per_page: this.page*this.per_page,
      };
      axios
        .get(
          route("apishop.shopordernotification.index", _.merge(properties, {}))
        )
        .then((response) => {
          this.list_notification = response.data.data;
          this.count_page = Math.ceil(response.data.meta.total/this.per_page)
        });
    },

    fetchNotificationScoll() {
      const properties = {
        page: this.page,
        per_page: this.per_page,
      };
      axios
        .get(
          route("apishop.shopordernotification.index", _.merge(properties, {}))
        )
        .then((response) => {
            response.data.data.forEach(item => this.list_notification.push(item))

        });
    },

    async redirectRoute(data) {
      await this.updateSeen(data.id)
        switch (data.noti_type) {
          case 1:
            this.redirectRouteDetailOrder(data.order_type,data.order_id)
            break;

          case 2:
            this.$router.push({ name: "shop.storageproduct.edit",params: {storageproductId: data.order_id} }).catch(()=>{});
            break;

          default:
            break;
        }
    },

    redirectRouteDetailOrder(type,id) {
    
      switch (type) {
        case 'sua_chua': // sửa chữa
          this.$router.push({ name: "shop.orders.detail",params: {ordersId:id}}).catch(()=>{});
          break;

        case 'bao_hanh': // bảo hành
          this.$router.push({ name: "shop.orders.detailguarantee",params: {ordersId:id} }).catch(()=>{});
          break;
        
        case 'mua_hang': // mua bán
          this.$router.push({ name: "shop.orders.detailbuysell",params: {ordersId:id} }).catch(()=>{});
          break;

        default:
          break;
      }
    },

    updateSeen(id) {
        const data = { seen: 1 };
        axios.post(route('apishop.shopordernotification.update',{shopordernotification:id}), data)
        .then((response) => {
        })
        .catch((error) => {
          this.loading = false;
          this.$notify.error({
            title: this.$t("mon.error.Title"),
            message: this.getSubmitError(this.form.errors),
          });
        });
      },
    scrollLoadData(){
        var element_notification = this.$el.querySelector("#list_notification")
        if (this.count_page>this.page) {
            if(element_notification.scrollTop+element_notification.clientHeight >= element_notification.scrollHeight) {
            this.page++
            this.fetchNotificationScoll()
        }
        }
        
    },
    time2TimeAgo(ts) {


    var d=new Date(); 
    var nowTs = Math.floor(d.getTime()/1000); //
    var seconds = nowTs-ts;

    if (seconds > 2*24*3600) {
       return "Vài ngày trước";
    }
    if (seconds > 24*3600) {
       return "Hôm qua";
    }

    if (seconds > 3600) {
       return Math.floor(seconds/3600) + "tiếng trước";
    }

    if (seconds > 60) {
       return Math.floor(seconds/60) + " phút trước";
    }

    if (seconds < 60) {
       return seconds + " giây trước";
    }
}
  },
  mounted() {
    this.fetchNotification();
    setInterval(this.fetchNotification, 5000);
  },
};
</script>

<style>
.bg-noti{
  background-color: #efeeee;
}
</style>
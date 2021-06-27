<template>
  <div>
    <a class="nav-link" href="#" @click.prevent="showListUser">
      <i class="far fa-comments"></i>
      <span class="badge badge-danger navbar-badge">{{this.list_user.length}}</span>
    </a>
    <el-drawer
      title="I am the title"
      :visible.sync="drawer"
      :direction="direction"
      :before-close="handleClose"
    >
      <div class="">
        <a class="dropdown-item" href="#" @click.prevent="showBoxChat(item)" v-for="(item, index) in list_user"
           v-bind:key=index>
          <!-- Message Start -->
          <div class="media">
            <img src="https://www.iness91.fr/wp-content/uploads/2015/01/4_AnrHservices-500x500-674x259.jpg"
                 alt="User Avatar" class="img-size-50 mr-3 img-circle">
            <div class="media-body">
              <h3 class="dropdown-item-title">
                {{item.name}}
                <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
              </h3>
              <p class="text-sm">Call me whenever you can...</p>
              <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
            </div>
          </div>
          <!-- Message End -->
        </a>
        <a class="dropdown-item dropdown-footer">See All Messages</a>
      </div>
    </el-drawer>
    <div class="row justify-content-end" id="list-box-chat"
         style="position: fixed; bottom: -50px; right:20px; width: 100%;">
      <div class="chat-box col-md-2" style="display: block" v-for="(item, index) in box_chat" v-bind:key=index>
        <div class="chat-box-header">
          {{item.name}}
          <span class="chat-box-toggle"><i class="material-icons remove-box-chat"
                                           @click="removeBoxChat(index)">close</i></span>
        </div>
        <div class="chat-box-body">
          <div class="chat-box-overlay">
          </div>
          <div class="chat-logs">
            <div id="cm-msg-1" class="chat-msg self" style="">
              <span class="msg-avatar"> <img
                src="https://www.iness91.fr/wp-content/uploads/2015/01/4_AnrHservices-500x500-674x259.jpg"></span>
              <div class="cm-msg-text">435345</div>
            </div>
            <div id="cm-msg-2" class="chat-msg user" style="">
              <span class="msg-avatar"> <img
                src="https://www.iness91.fr/wp-content/uploads/2015/01/4_AnrHservices-500x500-674x259.jpg"></span>
              <div class="cm-msg-text">435345</div>
            </div>
          </div>
          <!--chat-log -->
        </div>
        <div class="chat-input">
          <form>
            <input type="text" id="chat-input" placeholder="Send a message..."/>
            <button type="submit" class="chat-submit" id="chat-submit"><i class="material-icons">send</i></button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import firebase from '../../firebase'

  export default {
    data() {
      return {
        drawer: false,
        direction: "rtl",
        chat_client_id: null,
        current_shop_id: window.MonCMS.shopId,
        list_user: [],
        box_chat: []
      };
    },
    computed: {

      dbRef() {
        let refDb = 'chats/shops/' + this.current_shop_id
        return refDb
      },

      dbShopInsert() {
        let refDb = 'chats/shops/' + this.current_shop_id
        return refDb
      },

      dbClientInsert() {
        let refDb = 'chats/clients/' + this.chat_client_id
        return refDb
      }

    },
    created() {
      this.getListChatClient()
    },

    methods: {
      handleClose(done) {
        this.$confirm("Are you sure you want to close this?")
          .then((_) => {
            done();
          })
          .catch((_) => {
          });
      },
      showListUser() {
        this.drawer = true
        setTimeout(() => {
          document.getElementsByClassName('v-modal')[0].classList.remove('v-modal')
        }, 300);
      },
      showBoxChat(data) {
        this.drawer = false
        if (this.box_chat.find(({id}) => id == data.id)) {
          return
        }

        this.box_chat.push(data)
        if (this.box_chat.length > 3) {
          this.box_chat.splice(0, 1)
        }
      },
      removeBoxChat(index) {
        this.box_chat.splice(index, 1)
      },
      getListChatClient() {
        const dbRef = firebase.database().ref(this.dbRef);
        dbRef.get().then((snapshot) => {
          if (snapshot.exists()) {
            console.log(snapshot.val());
          } else {
            console.log("No data available");
          }
        }).catch((error) => {
          console.error(error);
        });
      }

    },
  };
</script>

<style>
</style>

<template>
    <div>
        <a class="nav-link" href="#" @click.prevent="showListUser">
            <i class="far fa-comments"></i>
            <span class="badge badge-danger navbar-badge">{{this.list_user.length}}</span>
        </a>
        <el-drawer
            title="Chat"
            :visible.sync="drawer"
            :direction="direction"
            :before-close="handleClose"
        >
            <div class="">
                <a class="dropdown-item" href="#" @click.prevent="showBoxChat(item)" v-for="(item, index) in list_user"
                   v-bind:key=index>
                    <!-- Message Start -->
                    <div class="media">
                        <img :src="item.avatar"
                             alt="User Avatar" class="img-size-50 mr-3 img-circle">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                {{item.name}}
                                <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">{{item.lastMessage}}</p>
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
            </div>
        </el-drawer>
        <div class="row justify-content-end" id="list-box-chat"
             style="position: fixed; bottom: -50px; right:20px; width: 100%;">
            <div class="chat-box col-md-2" style="display: block" v-for="(item, index) in chatGroup" v-bind:key=index>
                <div class="chat-box-header">
                    {{item.name}}
                    <span class="chat-box-toggle"><i class="material-icons remove-box-chat"
                                                     @click="removeBoxChat(index)">close</i></span>
                </div>
                <div class="chat-box-body">
                    <div class="chat-box-overlay">
                    </div>
                    <div class="chat-logs">
                        <div v-for="(chat,chatIdx) in item.chats">
                            <div id="cm-msg-1"
                                 :class="{'chat-msg user': chat.user._id != current_shop_id, 'chat-msg self': chat.user._id == current_shop_id}"
                                 style="">

                                <div class="cm-msg-text" v-if="chat.type === type_text"><span>{{chat.user.name}}</span>:
                                    {{chat.text}}
                                </div>
                                <div class="cm-msg-text" v-if="chat.type === type_img">
                                    <span>{{chat.user.name}}</span>: <img :src="chat.image" width="100"/>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!--chat-log -->
                </div>
                <div class="chat-input">
                    <form>
                        <input type="text" id="chat-input" placeholder="Send a message..." v-model="item.new_message"/>
                        <button type="button" class="chat-submit" id="chat-submit"
                                @click="sendChat(item.user_id, item.name, item.new_message)"><i
                            class="material-icons">send</i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  import firebase from '../../firebase'
  import _ from 'lodash'

  export default {
    data() {
      return {
        drawer: false,
        direction: "rtl",
        chat_client_id: null,
        current_shop_id: window.MonCMS.shopId,
        current_username: window.MonCMS.current_username,
        list_user: [],
        box_chat: [],
        type_text: 1,
        type_img: 2,
        type_video: 3,
      };
    },
    computed: {

      dbRef() {
        let refDb = 'chats/shops/' + this.current_shop_id + '/'
        return refDb
      },

      dbShopInsert() {
        let refDb = 'chats/shops/' + this.current_shop_id + '/'
        return refDb
      },

      dbClientRef() {
        return 'chats/users/'
      },
      chatGroup() {
        if (this.box_chat.length === 0) return []
        return this.box_chat.map(user_id => {
          const idx = _.findIndex(this.list_user, {id: user_id})
          if (idx >= 0) {
            const chats = Object.values(this.list_user[idx].chat)
            const avatar = this.list_user[idx].avatar
            const name = this.list_user[idx].name
            return {
              avatar: avatar,
              name: name,
              chats: chats,
              user_id: user_id,
              new_message: null
            }
          }
        })
      }

    },
    created() {
      this.getListChatClient()
    },

    methods: {
      handleClose(done) {
        done();
      },
      showListUser() {
        this.drawer = true
        setTimeout(() => {
          document.getElementsByClassName('v-modal')[0].classList.remove('v-modal')
        }, 100);
      },
      showBoxChat(data) {
        this.drawer = false
        if (this.box_chat.find(({id}) => id == data.id)) {
          return
        }

        this.box_chat.push(data.id)
        if (this.box_chat.length > 3) {
          this.box_chat.splice(0, 1)
        }
      },
      removeBoxChat(index) {
        this.box_chat.splice(index, 1)
      },
      getListChatClient() {
        const dbRef = firebase.database().ref(this.dbRef);
        dbRef.on('value', (snapshot) => {
          this.list_user = [];
          snapshot.forEach((doc) => {
            let item = doc.val()
            item.key = doc.key
            this.list_user.push(item)
          });
        });

      },
      sendChat(user_id, user_name, new_message) {
        if (new_message === null || new_message == '') return
        // insert table shops
        const path = this.dbRef + user_id + '/'
        const dbRefLastMsg = firebase.database().ref(path);
        dbRefLastMsg.update({
          lastMessage: new_message,

        });
        const patShopChat = this.dbRef + user_id + '/chat';
        const dbShopChat = firebase.database().ref(patShopChat);
        let newShopChatData = dbShopChat.push()
        const currentTimestamp = this.getTimeStamp()
        newShopChatData.set({
          _id: currentTimestamp,
          createdAt: currentTimestamp,
          text: new_message,
          type: this.type_text,
          user: {
            _id: this.current_shop_id,
            avatar: 'https://placeimg.com/140/140/any',
            name: this.current_username
          }

        });

        //insert table user
        const userPath = this.dbClientRef + user_id + '/'
        const dbUserRefLastMsg = firebase.database().ref(userPath);
        dbUserRefLastMsg.update({
          lastMessage: new_message,

        });
        const patUserChat = userPath + this.current_shop_id + '/chat';
        const dbUserChat = firebase.database().ref(patUserChat);
        let newUserChatData = dbUserChat.push()
        newUserChatData.set({
          _id: currentTimestamp,
          createdAt: currentTimestamp,
          text: new_message,
          type: this.type_text,
          user: {
            _id: user_id,
            avatar: 'https://placeimg.com/140/140/any',
            name: user_name
          }

        });
      },
      getTimeStamp() {
        return new Date().getTime()
      }

    },
  };
</script>

<style>
</style>

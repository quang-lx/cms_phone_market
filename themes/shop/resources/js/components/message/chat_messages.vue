<template>
  <div>
    <a class="nav-link" href="#" @click.prevent="showListUser" v-if="current_shop_id">
      <i class="far fa-comments"></i>
      <span class="badge badge-danger navbar-badge">{{this.list_user.length}}</span>
    </a>
    <el-drawer
      title="Chat"
      :visible.sync="drawer"
      :direction="direction"
      :append-to-body="true"
      size="87%"
    >
      <div class="row">
        <div class="col-9">
          <div class="mesgs" style="display: block" v-if="boxChat" @click="holdBoxChat">
            <div class="msg_history" id="msg_history">
              <template v-for="(chat,chatIdx) in chatGroup.chats">


                <div class="incoming_msg" v-if="chat.user._id != current_shop_id">
                  <span>{{chat.user.name}}</span>
                  <div class="received_msg">
                    <div class="received_withd_msg">
                      <p v-if="chat.type === type_text"> {{chat.text}}</p>
                      <p v-if="chat.type === type_img" @click="showPopupImg(chat.image)"> : <img :src="chat.image"
                                                                                                 width="100"/></p>
                      <span class="time_date">  {{getDateDisplay(chat.createdAt)}}</span></div>
                  </div>
                </div>
                <div class="outgoing_msg" v-else>
                  <div class="sent_msg">
                    <p v-if="chat.type === type_text"> {{chat.text}}</p>
                    <p v-if="chat.type === type_img"> : <img :src="chat.image" width="100"/></p>
                    <span class="time_date"> {{getDateDisplay(chat.createdAt)}}</span></div>
                </div>
              </template>


              <div class="type_msg">
                <div class="input_msg_write">
                  <input type="text" class="write_msg" placeholder="Type a message" v-model="chatGroup.new_message"
                         v-on:keyup.enter="sendChat(chatGroup.user_id, chatGroup.name, chatGroup.new_message)"/>
                  <button class="msg_send_btn" type="button"
                          @click="sendChat(chatGroup.user_id, chatGroup.name, chatGroup.new_message)">
                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-3">
          <a class="dropdown-item" :class="{ active_chat : chat_user_id == index }" href="#"
             @click.prevent="showBoxChat(index)"
             v-for="(item, index) in list_user" v-bind:key=index>
            <!-- Message Start -->
            <div class="media">
              <img :src="item.avatar" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  {{item.name}}
                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">{{item.lastMessage}}</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
        </div>
      </div>
      <el-drawer
        size="100%"
        title=""
        :append-to-body="true"
        :visible.sync="dialogImgVisible">
        <div style="text-align: center">
          <img :src="img_selected" style="object-fit: contain"/>
        </div>

      </el-drawer>
    </el-drawer>


  </div>
</template>

<script>
  import firebase from '../../firebase'
  import _ from 'lodash'
  import moment from 'moment'

  export default {
    data() {
      return {
        boxChat: false,
        drawer: false,
        direction: "rtl",
        chat_client_id: null,
        current_shop_id: window.MonCMS.shopId,
        current_shop_avatar: window.MonCMS.shopAvatar,
        current_username: window.MonCMS.current_username,
        list_user: [],
        chat_user_id: null,
        type_text: 1,
        type_img: 2,
        type_video: 3,
        dialogImgVisible: false,
        img_selected: null
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
        if (this.chat_user_id === null) return null
        const chats = Object.values(this.list_user[this.chat_user_id].chat)
        const avatar = this.list_user[this.chat_user_id].avatar
        const name = this.list_user[this.chat_user_id].name
        return {
          avatar: avatar,
          name: name,
          chats: chats,
          user_id: this.list_user[this.chat_user_id].id,
          new_message: null
        }
      }

    },
    created() {
      this.getListChatClient()
    },
    mounted() {
      var objDiv = document.getElementById("msg_history");
      objDiv.scrollTop = objDiv.scrollHeight;
    },
    methods: {
      showPopupImg(img) {
        this.img_selected = img
        this.dialogImgVisible = true
      },
      handleClose(done) {
        done();
      },
      showListUser() {
        if (this.list_user.length === 0) return
        this.showBoxChat(0)
        this.drawer = true
        setTimeout(() => {
          document.getElementsByClassName('v-modal')[0].classList.remove('v-modal')
        }, 100);
      },
      showBoxChat(index) {
        this.boxChat = true;
        this.chat_user_id = index
      },
      removeBoxChat(index) {
        this.chat_user_id = null
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
        console.log(new_message, user_name, user_id)
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
            avatar: this.current_shop_avatar,
            name: this.current_username
          }

        });

        //insert table user
        const userPath = this.dbClientRef + user_id + '/' + this.current_shop_id + '/';
        const dbUserRefLastMsg = firebase.database().ref(userPath);
        dbUserRefLastMsg.update({
          lastMessage: new_message,

        });
        const patUserChat = userPath + 'chat';
        const dbUserChat = firebase.database().ref(patUserChat);
        let newUserChatData = dbUserChat.push()
        newUserChatData.set({
          _id: currentTimestamp,
          createdAt: currentTimestamp,
          text: new_message,
          type: this.type_text,
          user: {
            _id: this.current_shop_id,
            avatar: this.current_shop_avatar,
            name: this.current_username
          }

        });
      },
      getTimeStamp() {
        return new Date().getTime()
      },
      getDateDisplay(timestamp) {
        var day = '';
        try {
          day = moment.unix(timestamp / 1000).format("DD/MM/YYYY HH:mm");
          ;
        } catch (e) {

        }
        return day

      },
      holdBoxChat() {
        this.drawer = true
      },

    },
    watch: {
      drawer: function (val) {
        if (val) {

        } else {
          this.boxChat = false
        }
      },
    }
  };
</script>

<style>
  .container {
    max-width: 1170px;
    margin: auto;
  }

  img {
    max-width: 100%;
  }

  .inbox_people {
    background: #f8f8f8 none repeat scroll 0 0;
    float: left;
    overflow: hidden;
    width: 40%;
    border-right: 1px solid #c4c4c4;
  }

  .inbox_msg {
    border: 1px solid #c4c4c4;
    clear: both;
    overflow: hidden;
  }

  .top_spac {
    margin: 20px 0 0;
  }


  .recent_heading {
    float: left;
    width: 40%;
  }

  .srch_bar {
    display: inline-block;
    text-align: right;
    width: 60%;
  }

  .headind_srch {
    padding: 10px 29px 10px 20px;
    overflow: hidden;
    border-bottom: 1px solid #c4c4c4;
  }

  .recent_heading h4 {
    color: #05728f;
    font-size: 21px;
    margin: auto;
  }

  .srch_bar input {
    border: 1px solid #cdcdcd;
    border-width: 0 0 1px 0;
    width: 80%;
    padding: 2px 0 4px 6px;
    background: none;
  }

  .srch_bar .input-group-addon button {
    background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
    border: medium none;
    padding: 0;
    color: #707070;
    font-size: 18px;
  }

  .srch_bar .input-group-addon {
    margin: 0 0 0 -27px;
  }

  .chat_ib h5 {
    font-size: 15px;
    color: #464646;
    margin: 0 0 8px 0;
  }

  .chat_ib h5 span {
    font-size: 13px;
    float: right;
  }

  .chat_ib p {
    font-size: 14px;
    color: #989898;
    margin: auto
  }

  .chat_img {
    float: left;
    width: 11%;
  }

  .chat_ib {
    float: left;
    padding: 0 0 0 15px;
    width: 88%;
  }

  .chat_people {
    overflow: hidden;
    clear: both;
  }

  .chat_list {
    border-bottom: 1px solid #c4c4c4;
    margin: 0;
    padding: 18px 16px 10px;
  }

  .inbox_chat {
    height: 550px;
    overflow-y: scroll;
  }

  .active_chat {
    background: #ebebeb;
  }

  .incoming_msg_img {
    display: inline-block;
    width: 6%;
  }

  .received_msg {
    display: inline-block;
    padding: 0 0 0 10px;
    vertical-align: top;
    width: 92%;
  }

  .received_withd_msg p {
    background: #ebebeb none repeat scroll 0 0;
    border-radius: 3px;
    color: #646464;
    font-size: 14px;
    margin: 0;
    padding: 5px 10px 5px 12px;
    width: 100%;
  }

  .time_date {
    color: #747474;
    display: block;
    font-size: 12px;
    margin: 8px 0 0;
  }

  .received_withd_msg {
    width: 57%;
  }

  .mesgs {
    float: left;
    padding: 30px 15px 0 25px;
    width: 100%;
  }

  .sent_msg p {
    background: #05728f none repeat scroll 0 0;
    border-radius: 3px;
    font-size: 14px;
    margin: 0;
    color: #fff;
    padding: 5px 10px 5px 12px;
    width: 100%;
  }

  .outgoing_msg {
    overflow: hidden;
    margin: 26px 0 26px;
  }

  .sent_msg {
    float: right;
    width: 46%;
  }

  .input_msg_write input {
    background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
    border: medium none;
    color: #4c4c4c;
    font-size: 15px;
    min-height: 60px;
    width: 100%;
    padding: 10px;
  }

  .type_msg {
    border-top: 1px solid #c4c4c4;
    position: fixed;
    bottom: 2%;
    width: 64%;
  }

  .msg_send_btn {
    background: #05728f none repeat scroll 0 0;
    border: medium none;
    border-radius: 50%;
    color: #fff;
    cursor: pointer;
    font-size: 17px;
    height: 33px;
    position: absolute;
    right: 8px;
    top: 11px;
    width: 33px;
  }

  .messaging {
    padding: 0 0 50px 0;
  }

  .msg_history {
    position: fixed;
    top: 84px;
    bottom: 100px;
    overflow: auto;
    background: #fff;
    width: 64%;
  }

  /* .mesgs {
    position: fixed;
      z-index: 2000;
      padding-top: 100px;
      left: 16%;
      top: 0;
      width: 54%;
      height: 100%;
      background-color: rgb(0,0,0);
      background-color: rgb(255 255 255);
  } */
</style>

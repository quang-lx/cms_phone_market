<template>
    <section class="w-920 node5-1" id="news-detail">
        <div
                v-lazy:background-image="item.normal_image"
                class="h-376 rel bg-cover lazy node5-28"
        >
            <div class="w-60 h-20 bg-5 abs bottom-0 left-0">
                <span class="weight-normal color-2 txt-12-18 flex row center-center">{{ item.tags }}</span>
            </div>
        </div>
        <div class="flex row start-start bg-2 node5-31">
            <div class="w-128 flex col center-end node5-29">
                <div class="box-48 circle bg-10 flex col center-center mt-32 mr-16 node5-32" @click="socialShare('facebook')">
                    <img
                            :src="imageUrl('/images/png/fb-share.png')"
                            alt=""
                    >
                </div>
                <div class="box-48 circle bg-10 flex col center-center mt-8 mr-16" @click="socialShare('twitter')">
                    <img
                            :src="imageUrl('/images/png/twitter-share.png')"
                            alt=""
                    >
                </div>
                <!--<div class="box-48 circle bg-10 flex col center-center mt-8 mr-16" @click="socialShare('google')">
                    <img
                            :src="imageUrl('/images/png/google-sahre.png')"
                            alt=""
                    >
                </div>-->
            </div>
            <div class="w-792 node5-30">
                <h1 class="mt-28 color-3 txt-24-34 node5-33">
                    {{ item.title }}
                </h1>
                <p class="mt-4 color-7 txt-14-22 weight-normal">
                    {{ item.realesed_at }}
                </p>

                <div class="mt-12 pb-96">
                    <div class="txt-15-24-ror pr-32 node5-34">
                        <span v-if="item.short_description" class="s-itatic color-7">
                            {{ item.short_description }}
                        </span>
                        <div v-html="item.content"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
  import request from '../../utils/request'

  export default {
    name: 'MiddleContent',
    data() {
      return {
        item: {
          title: '',
          content: '',
          type: '',
          normal_image: '',
          link_video: '',
          tags: '',
          created_at: '',
          public_url: ''
        }
      }
    },
    created() {
      let vm = this
      let id = vm.$route.params.id
      vm.productId = id
      request.get('/news/find/' + id)
        .then((response) => {
          vm.item = response.data.data
          console.log('aaaaaa')
        })
        .catch((error) => {
          throw new Error(JSON.stringify(error))
        })
    },
    mounted() {
    },
    methods: {
      imageUrl(value) {
        return `${window.Vma.assetUrl}${value}`
      },
      socialShare(social) {
        if(social === 'facebook') {
          window.open('https://www.facebook.com/sharer/sharer.php?u='+this.item.public_url, '_blank')
        } else if (social === 'twitter') {
          window.open('https://twitter.com/home?status='+this.item.public_url, '_blank')
        } else if (social === 'google') {
          window.open('https://plus.google.com/share?url='+this.item.public_url, '_blank')
        }
      }
    }
  }
</script>

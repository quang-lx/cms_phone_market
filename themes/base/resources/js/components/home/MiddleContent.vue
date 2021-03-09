<template>
  <!-- start article list -->
  <section class="w-608 node3-2">
    <slot
      v-for="(item, index) in news"
    >
      <a
        v-if="item.type !== 'IMAGE COLLECTION'"
        :href="item.public_url"
        :title="item.title"
      >
        <article :class="index === 0 ? 'node3-4' : 'node3-4 mt-16'">
          <figure
            v-if="item.type !== 'IMAGE COLLECTION' && item.type !== 'NORMAL VIDEO'"
            v-lazy:background-image="item.normal_image"
            class="h-248 bg-cover rel lazy node3-5"
          />
          <figure
            v-if="item.type === 'NORMAL VIDEO'"
            v-lazy:background-image="item.normal_image"
            class="h-342 bg-cover rel flex col center-center lazy node3-5"
          >
            <iframe
              class="full-box border-none"
              :src="item.link_video"
            />
          </figure>
          <div class="pt-16 pb-16 bg-2 pl-16 node3-7">
            <div class="w-60 h-20 bg-5 ">
              <span class="weight-normal color-2 txt-12-18 flex row center-center">{{ item.tags }}</span>
            </div>
            <h2 class="txt-18-30 color-3 weight-bold mt-8 node3-8">
              {{ item.title }}
            </h2>
            <p class="mt-4 color-3 txt-16-26 weight-normal node3-9 pr-16">
              {{ item.short_description }}
            </p>
            <p class="mt-4 color-7 txt-14-22 weight-normal">
              {{ item.released_at }}
            </p>
          </div>
        </article>
      </a>
      <section
        v-if="item.type === 'IMAGE COLLECTION'"
        :class="index === 0 ? 'rel js-images js-active-modal pointer' : 'mt-16 rel js-images js-active-modal pointer'"
      >
        <div
          v-lazy:background-image="item.normal_image"
          class="h-248 bg-cover lazy node3-15"
        />
        <div
          v-if="item.collection_images.length > 0"
          class="mt-2 flex row between-center"
        >
          <slot
            v-for="(img, imgIndex) in item.collection_images"
          >
            <figure
              v-if="imgIndex < 3"
              v-lazy:background-image="imageUrl(img.thumbnail)"
              :class="imgIndex === 2 ? 'h-124 bg-cover w-202 rel pointer lazy node3-16' : 'h-124 bg-cover w-202 lazy node3-16'"
            >
              <div
                v-if="imgIndex === 2"
                class="full-box abs flex col center-center cover-thumb"
              >
                <span class="color-2 txt-20 weight-bold">{{ item.collection_images.length }}+</span>
              </div>
            </figure>
          </slot>
        </div>
        <div class="pt-12 pb-12 bg-2 pl-16 pr-16">
          <h1 class="color-3 txt-18-30 weight-bold">
            {{ item.title }}
          </h1>
          <p class="mt-4 color-7 txt-14-22 weight-normal">
            {{ item.released_at }}
          </p>
        </div>
        <div class="fixed full-w full-h bg-modal top-0 left-0 index-4 flex col center-center hide js-modal">
          <div class="h-623 w-816 bg-2 js-content-modal node10-14">
            <div class="h-48 flex row between-center">
              <div class="flex row start-center">
                <div class="box-32 bg-5 ml-12 flex col center-center node10-21">
                  <div class="spirit-image-white" />
                </div>
                <h1 class="pl-8 color-3 txt-18-30 weight-bold node10-19">
                  {{ item.title }}
                </h1>
              </div>
              <div class="spirit-exit mr-12 node10-20" />
            </div>
            <div class="full-w">
              <!-- start top carousel -->
              <section class="full-w rel">
                <div class="owl-carousel owl-carousel-1 h-488 owl-theme full-box common-custom-owl image-resouce-slider node10-15 owl-loaded owl-drag">
                  <a
                    v-for="(img, imgIndex) in item.collection_images"
                    :key="imgIndex"
                    :href="item.public_url"
                  >
                    <div
                      class="bg-cover full-box owl-lazy"
                      :data-src="img.origin"
                      :data-src-retina="img.origin"
                    />
                  </a>
                </div>
                <div class="hidden-overflow rel h-88 ">
                  <ul :id="'carousel-custom-dots-'+imgCollectNum[item.id]" class="disable-user-select top-carousel-custom-dots-img-resource js-dots-list owl-dots top-8 image-resource-slider-owl-dot bg-2 abs flex row start-center">
                    <li
                      v-for="(img, imgIndex) in item.collection_images"
                      :key="imgIndex"
                      class="owl-dot rel pointer ml-8"
                    >
                      <div
                        class="box-72 lazy bg-cover node10-16"
                        :data-src="imageUrl(img.thumbnail)"
                        v-lazy:background-image="imageUrl(img.thumbnail)"
                      />
                    </li>
                  </ul>
                </div>
              </section>
              <!-- end top carousel -->
            </div>
          </div>
        </div>
      </section>
    </slot>
    <button
      v-if="canLoad === true"
      class="full-w h-40 bg-5 mt-18 pointer"
      @click="fetchNewsFeed"
    >
      <span class="txt-16-19 color-2 weight-bold">{{ trans('base.See more') }}</span>
    </button>
  </section>
  <!-- end article list -->
</template>

<script>
import request from '../../utils/request'
import _ from 'lodash'

export default {
  name: 'MiddleContent',
  props: {
    isScroll: {
      type: Boolean,
      default: () => false
    }
  },
  data () {
    return {
      news: [],
      page: 1,
      canLoad: true
    }
  },
  computed: {
    imgCollectNum () {
      let nums = []
      let count = 0;
      _.each(this.news, function (item) {
        if (item.type === 'IMAGE COLLECTION') {
          nums['' + item.id + ''] = count
          count++
        }
      })
      return nums
    }
  },
  created () {
    this.fetchNewsFeed()
  },
  methods: {
    fetchNewsFeed () {
      if (this.canLoad === true) {
        let $self = this
        request.get('/news/feed?per_page=6&page=' + this.page)
          .then((response) => {
            let items = response.data.data
            if (items.length) {
              _.each(items, (item) => {
                $self.news.push(item)
              })
            }
            if (response.data.meta.current_page < response.data.meta.last_page) {
              $self.page = response.data.meta.current_page + 1
              $self.canLoad = true
            } else {
              $self.canLoad = false
            }
            this.$nextTick().then(function () {
              App.headerSnap();
              App.lazyLoadSupport();
              App.breadscrumb();
              App.activeHoverNavMenu();
              App.changeLanguage();
              App.sideMenuScrollEffect();
              App.playVideo();
              App.topCarousel()
              App.swipeList()
              App.modal()
              App.dropdownFunction()
              App.imageAlbumSlide()
              App.PdfReader()

            })
          })
          .catch((error) => {
            throw new Error(JSON.stringify(error))
          })
      }
    },
    imageUrl (value) {
      return `${window.Vma.assetUrl}${value}`
    }
  }
}
</script>

<style>

</style>

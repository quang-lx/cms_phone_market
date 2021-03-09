<template>
  <!-- start top carousel -->
  <section v-if="highlights.length>0" class="container pt-16 h-600 rel res-node2">
    <div class="owl-carousel owl-theme full-box common-custom-owl owl-top-slide">
      <a
        v-for="item in highlights"
        :key="item.id"
        :href="item.public_url"
        :title="item.title"
      >
        <div
          :data-src="imageUrl(item.highlight_image)"
          :data-src-retina="imageUrl(item.highlight_image)"
          class="bg-cover full-box owl-lazy node2-1 rel"
        >
          <p class="txt-16-26 color-3 weight-normal mt-8 ml-16 node2-10 two-line h-48">
            {{ item.title }}
          </p>
        </div>
      </a>
    </div>
    <ul class="top-carousel-custom-dots owl-dots top-owl-dot bg-2 abs w-1200 bottom-16 left-16 flex row start-center index-1 node2-2">
      <li
        v-for="item in highlights"
        :key="item.id"
        class="owl-dot rel pointer node2-7"
      >
        <a
          :href="item.public_url"
          :title="item.title"
        >
          <article class="w-284 h-120 flex row start-start  ml-16 node2-3">
            <figure
              v-lazy:background-image="item.normal_image"
              class="bg-cover box-88 mt-16 lazy node2-4"
            />
            <div class="offset-w-88 mt-16 node2-5">
              <div class="w-60 h-20 bg-5 ml-16 node2-9">
                <span class="weight-normal color-2 txt-12-18 flex row center-center node2-8">{{ item.tags }}</span>
              </div>
              <p class="txt-16-26 color-3 weight-normal mt-8 ml-16 node2-6 two-line">
                {{ item.title }}
              </p>
            </div>
          </article>
        </a>
      </li>
    </ul>
  </section>
  <!-- end top carousel -->
</template>

<script>
import request from '../../utils/request'
import { upperCase } from '../../filters'

export default {
  name: 'Slider',
  components: {},
  filters: { upperCase },
  data () {
    return {
      highlights: []
    }
  },
  created () {
  },
  mounted () {
    this.fetchHighlights()
  },
  methods: {
    fetchHighlights () {
      request.get('/news/highlight?page=1&per_page=4')
        .then((response) => {
          this.highlights = response.data.data
          this.$nextTick().then(function () {
            window.App.topCarousel()
          })
        })
        .catch((error) => {
          throw new Error(JSON.stringify(error))
        })
      return this
    },
    imageUrl (value) {
      return `${window.Vma.assetUrl}${value}`
    }
  }
}
</script>

<template>
  <!-- start right content -->
  <aside class="w-296 js-right-side node3-3">
    <div class="bg-2">
      <div class="h-48 flex row start-center rel pseu-bottom-1">
        <h1 class="txt-18-22 color-3 weight-bold ml-16">{{ trans('base.Featured Post') }}</h1>
      </div>
      <ul>
        <li v-for="(item, index) in related" :key="index">
          <a :href="item.public_url" :title="item.title">
            <article class="pt-16 pl-16 pr-16 pb-16 flex row start-center pseu-bottom-2 rel node3-20">
              <figure class="box-88 bg-cover lazy node3-21" v-lazy:background-image="imageUrl(item.normal_image)">
              </figure>
              <div class="offset-w-88 pl-16 node3-22">
                <span class="bg-5 pt-2 pb-2  pl-6 pr-6 weight-normal color-2 txt-12-18">{{ item.tags }}</span>
                <p class="color-3 weight-normal txt-16-26 mt-8 two-line">{{ item.title }}</p>
              </div>
            </article>
          </a>
        </li>
      </ul>
    </div>
    <div class="h-280 bg-cover mt-16  lazy node3-23 hide" data-src="https://i.ibb.co/MVyQ5nz/cover-1.png"></div>
  </aside>
  <!-- end right content -->
</template>
<script>
import request from '../../utils/request'
export default {
  name: 'RightContent',
  props: {
    isScroll: {
      type: Boolean,
      default: () => false
    }
  },
  data () {
    return {
      related: [],
      categories: { 'drifting': 'MOTOSPORT>>DRIFTING', 'formula-one': 'MOTOSPORT>>FORMULA ONE', 'off-road': 'MOTOSPORT>>OFFROAD', 'karting': 'MOTOSPORT>>KARTING' },
      category: '',
      page: 1
    }
  },
  created () {
    this.category = this.categories[this.$route.params.slug] !== undefined ? this.categories[this.$route.params.slug] : ''
    request.get('/motor-sport/featured?per_page=6&page=' + this.page + '&category=' + this.category)
      .then((response) => {
        this.related = response.data.data
      })
      .catch((error) => {
        throw new Error(JSON.stringify(error))
      })
  },
  methods: {
    imageUrl (value) {
      return `${window.Vma.assetUrl}${value}`
    }
  }
}
</script>

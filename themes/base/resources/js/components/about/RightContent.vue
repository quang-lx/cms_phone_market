<template>
  <aside class="w-296 js-right-side node5-2">
    <div class="mt-16 bg-2" v-if="jobs.length > 0">
      <div class="h-48 flex row between-center rel pseu-bottom-1">
        <h1 class="txt-18-22 color-3 weight-bold ml-16">{{ trans('base.Job Offers') }}</h1>
        <span class="txt-14-17 color-5 mr-16 pointer">{{ trans('base.View All') }}</span>
      </div>
      <ul>
        <li v-for="(job, jIndex) in jobs" :key="jIndex">
          <a href="">
            <div class="flex row between-start pt-16 pl-16 pr-16 pb-16 rel pseu-bottom-2">
              <div class="offset-w-64">
                <h1 class="color-3 txt-16-26 weight-bold pr-16">{{ job.title }}</h1>
                <button class="mt-8 w-88 h-32 flex col center-center border-8">
                  <span class="color-3 txt-13-16 weight-bold">{{ trans('Read More') }}</span>
                </button>
              </div>
              <div class="box-64 bg-cover lazy " v-lazy:background="job.normal_image"></div>
            </div>
          </a>
        </li>
      </ul>
    </div>
    <div class="bg-2 mt-16">
      <div class="h-48 flex row start-center rel pseu-bottom-1">
        <h1 class="txt-18-22 color-3 weight-bold ml-16">{{ trans('base.Featured Post') }}</h1>
      </div>
      <ul>
        <li v-for="(news, index) in related" :key="index">
          <a :href="news.public_url" :title="news.title">
            <article class="pt-16 pl-16 pr-16 pb-16 flex row start-center pseu-bottom-2 rel node3-20">
              <figure class="box-88 bg-cover lazy node3-21" v-lazy:background-image="news.normal_image">
              </figure>
              <div class="offset-w-88 pl-16 node3-22">
                <span class="bg-5 pt-2 pb-2  pl-6 pr-6 weight-normal color-2 txt-12-18">{{ news.tags }}</span>
                <p class="color-3 weight-normal txt-16-26 mt-8 two-line">{{ news.title }}</p>
              </div>
            </article>
          </a>
        </li>
      </ul>
    </div>
  </aside>
</template>
<script>
import request from '../../utils/request'

export default {
  name: 'AboutRightContent',
  props: {
    isScroll: {
      type: Boolean,
      default: () => false
    }
  },
  data () {
    return {
      related: [],
      jobs: []
    }
  },
  created () {
    request.get('/about/featured?page=1&per_page=6')
      .then((response) => {
        this.related = response.data.data
      })
      .catch((error) => {
        throw new Error(JSON.stringify(error))
      })
    this.fetchJobs()
  },
  methods: {
    fetchJobs () {
      request.get('/jobs?page=1&per_page=5')
        .then((response) => {
          this.partners = response.data.data
        })
        .catch((error) => {
          throw new Error(JSON.stringify(error))
        })
    },
    imageUrl (value) {
      return `${window.Vma.assetUrl}${value}`
    }
  }
}
</script>

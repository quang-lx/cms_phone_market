<template>
  <!-- start article list -->
  <section class="w-920 flex wrap-between node5-1">
    <div v-for="(item, index) in communities" :key="index" class="bg-2 w-452 mb-19 node5-16" @click="goToDetail(item)">
      <div class="h-184 bg-cover lazy" v-lazy:background-image="item.normal_image"></div>
      <div class="flex row start-start pt-12 pb-16 pr-16 pl-16">
        <div class="box-48 border-8 flex col center-center">
          <h2 class="txt-12-15 color-5 weight-normal">{{ item.released_at_month }}</h2>
          <h1 class="txt-20-24 color-3 weight-bold">{{ item.released_at_day }}</h1>
        </div>
        <div class="offset-w-48 ml-16">
          <h1 class="color-3 txt-18-30 weight-bold">
            {{ item.title }}
          </h1>
          <div class="mt-4 flex row start-center">
            <div class="spirit-joined"></div>
            <span class="ml-4 color-7 txt-14-22" v-if="item.type !== 'JOB OFFER' && item.type !== 'SPONSOR OFFER' && item.type !== 'PARTNER OFFER'">{{ item.num_of_join }} {{ trans('base.Joined') }}</span>
            <span class="ml-4 color-7 txt-14-22" v-if="item.type === 'JOB OFFER'">{{ item.num_of_join }} {{ trans('base.Jobs') }}</span>
            <span class="ml-4 color-7 txt-14-22" v-if="item.type === 'SPONSOR OFFER'">{{ item.num_of_join }} {{ trans('base.Sponsors') }}</span>
            <span class="ml-4 color-7 txt-14-22" v-if="item.type === 'PARTNER OFFER'">{{ item.num_of_join }} {{ trans('base.Sponsor') }}</span>
          </div>

          <button class="h-32 w-134 flex col center-center bg-5 mt-8">
            <span class="white txt-14-17 weight-bold" v-if="item.type !== 'JOB OFFER' && item.type !== 'SPONSOR OFFER' && item.type !== 'PARTNER OFFER'">{{ trans('base.Join Now') }}</span>
            <span class="white txt-14-17 weight-bold" v-if="item.type === 'JOB OFFER'">{{ trans('base.Apply Now') }}</span>
            <span class="white txt-14-17 weight-bold" v-if="item.type === 'SPONSOR OFFER'">{{ trans('base.Sponsor Now') }}</span>
            <span class="white txt-14-17 weight-bold" v-if="item.type === 'PARTNER OFFER'">{{ trans('base.Partner Now') }}</span>
          </button>
        </div>
      </div>
    </div>
    <button v-if="canLoad === true" @click="fetchCommunities" class="full-w h-40 bg-5 pointer">
      <span class="txt-16-19 color-2 weight-bold">{{ trans('base.See more') }}</span>
    </button>
  </section>
  <!-- end article list -->
</template>
<script>
  import request from '../../utils/request'

  export default {
    mixins: [],
    props: {
      pageTitle: {}
    },
    data () {
      return {
        page: 1,
        canLoad: false,
        communities: null
      }
    },
    watch: {},
    created () {
      this.fetchCommunities()
    },
    mounted () {
    },
    methods: {
      fetchCommunities () {
        let $self = this
        request.get('/communities?page=' + this.page + '&per_page=8')
                .then((response) => {
                  this.communities = response.data.data
                  if (response.data.meta.current_page < response.data.meta.last_page) {
                    $self.page = response.data.meta.current_page + 1
                    $self.canLoad = true
                  } else {
                    $self.canLoad = false
                  }
                })
                .catch((error) => {
                  throw new Error(JSON.stringify(error))
                })
      },
      goToDetail(item) {
        window.location.href=item.public_url
      }
    }
  }
</script>

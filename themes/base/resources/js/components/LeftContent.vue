<template>
  <!-- start left content -->
  <aside class="w-296 js-left-side node3-1">
    <div class="bg-2">
      <div class="h-48 flex row start-center">
        <h1 class="txt-18-22 color-3 weight-bold ml-16">{{ trans('base.Race Schedule') }}</h1>
      </div>
      <section class="h-264 bg-cover color-2 lazy node3-33" v-lazy:background-image="'https://i.ibb.co/mTD7J6X/race-road.png'" @click="goToHanoi" style="cursor: pointer">
        <div class="flex row pt-16 pl-16 node3-34">
          <div class="box-48 bg-5 flex col center-center">
            <span class="txt-12-18">2020</span>
            <span class="txt-20 weight-bold">{{ trans('base.Apr') }}</span>
          </div>
          <div class="pl-16">
            <h3 class="weight-normal txt-16-19">{{ trans('base.Planning') }}</h3>
            <div class="flex row start-center pt-6">
              <h2 class="txt-20 weight-bold ">Hanoi Grand Prix</h2>
              <div class="spirit-vietnam ml-24 mt-2"></div>
            </div>
          </div>
        </div>
      </section>
      <div class="race-schedules">
        <ul>
          <li v-for="({race_name, sport_type, season, race_day, race_month, public_url, country_code, id}, index) in events"   :key="index" v-if="id !== 7">
            <a :href="public_url"  >
              <div class="pt-16 pb-16 flex row between-center color-2 pl-16 rel pseu-bottom-borde-1 node3-35">
                <div class="flex row start-center">
                  <div class="box-48 bg-5 flex col center-center">
                    <span class="txt-12-18">{{ race_month }}</span>
                    <span class="txt-20 weight-bold">{{ race_day }}</span>
                  </div>
                  <h1 class="txt-16-26 color-3 weight-bold pl-16 pr-16 node3-36">{{ race_name }}</h1>

                </div>
                <div :class="'spirit-'+country_code+' mr-16'"></div>
              </div>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="h-280 bg-cover pb-16 lazy mt-16 node3-23 hide" data-src="https://i.ibb.co/XW8s7CL/cover-2.png"></div>
  </aside>
  <!-- end left content -->
</template>

<script>
import request from '../utils/request'

export default {

  name: 'LeftContent',
  props: {
    isScroll: {
      type: Boolean,
      default: () => false
    }
  },
  data () {
    return {
      events: []
    }
  },
  created () {
    request.get('/race-events?per_page=6')
      .then((response) => {
        this.events = response.data.data
      })
      .catch((error) => {
        throw new Error(JSON.stringify(error))
      })
  },
  methods: {
    imageUrl (value) {
      return `${window.Vma.assetUrl}${value}`
    },
    redictToDetail(link) {
      window.location.href= link
    },
    goToHanoi() {
      window.location.href = '/race-events/7/hanoi'
    }
  }
}
</script>

<template>
  <!-- start left content -->
  <aside class="w-296 js-left-side node3-1 node3-sub1-8">
      <div class="bg-2">
          <div class="h-48 flex row start-center">
              <h1 class="txt-18-22 color-3 weight-bold ml-16">{{ trans('base.Race Schedule') }}</h1>
          </div>
          <section class="h-264 bg-cover color-2 lazy node3-33" data-src="https://i.ibb.co/mTD7J6X/race-road.png"  @click="goToHanoi" style="cursor: pointer">
              <div class="flex row pt-16 pl-16 node3-34">
                  <div class="box-48 bg-5 flex col center-center">
                      <span class="txt-12-18">2020</span>
                      <span class="txt-20 weight-bold">{{ trans('base.Apr') }}</span>
                  </div>
                  <div class="pl-16">
                      <h3 class="weight-normal txt-16-19">{{ trans('base.Planning') }}</h3>
                      <div class="flex row start-center pt-6">
                          <h2 class="txt-20 weight-bold ">Hanoi Grand Prix</h2>
                          <div class="spirit-vietnam ml-24 mt-2 right-16"></div>
                      </div>
                  </div>

              </div>
          </section>
          <div class="race-schedules">
              <ul>
                  <li v-for="({ race_name, sport_type, season, race_day, race_month, public_url, country_code}, index) in events"
                  :key="index"
                   >
                      <a :href="public_url"  >
                          <div class="pt-16 pb-16 flex row between-center color-2 pl-16 rel pseu-bottom-borde-1 node3-35">
                              <div class="flex row start-center">
                                  <div class="box-48 bg-5 flex col center-center">
                                      <span class="txt-12-18">{{ race_month }}</span>
                                      <span class="txt-20 weight-bold">{{ race_day }}</span>
                                  </div>
                                  <h1 class="txt-16-26 color-3 weight-bold pl-16 pr-16 node3-36 node3-sub1-7">{{ race_name }}</h1>
                              </div>
                              <div :class="'spirit-'+country_code+' mr-16'"></div>
                          </div>
                      </a>
                  </li>
              </ul>
          </div>
      </div>
    <div class="bg-2 mt-16">
      <div class="h-44 flex row between-center pl-16 pr-16 node3-sub1-1">
        <h1 class="txt-18-22 color-3 weight-bold">
          {{ trans('base.Drivers Standings') }}
        </h1>
        <span class="txt-18-22 color-7 weight-normal">{{ year }}</span>
      </div>
      <ul>
        <li
          v-for="(driver,index) in drivers"
          :key="index"
        >
          <div class="bg-9 flex row between-center pl-16 pr-16 pt-12 pb-12">
            <div class="flex row start-center">
              <h1 class="txt-24-29 color-3">
                {{ driver.position }}
              </h1>
              <div class="ml-18">
                <h2 class="color-3 txt-16-20">
                  {{ driver.driver_name }}
                </h2>
                <p class="mt-4 color-7 txt-13-16 weight-normal">
                  {{ driver.contructor_name }}
                </p>
                <div class="w-60 h-20 border-8 bg-2 txt-13-16 flex row center-center mt-6">
                  <span class="weight-bold">{{ driver.pts }}</span>
                  <span class="weight-normal pl-4">PTS</span>
                </div>
              </div>
            </div>
            <div
              v-lazy:background-image="driver.avatar"
              class="box-64 circle bg-cover shadow-1 lazy"
            />
          </div>
        </li>
      </ul>
      <a :href="getLinkStandingDriver(year)">
        <div class="h-44 flex row center-center">
          <span class="txt-14-17 color-3 weight-bold">
            {{ trans('base.View Full Standings') }}
          </span>
        </div>
      </a>
    </div>
    <div class="bg-2 mt-16">
      <div class="h-44 flex row between-center pl-16 pr-16 node3-sub1-1">
        <h1 class="txt-18-22 color-3 weight-bold">
          {{ trans('base.Constructors Standings') }}
        </h1>
        <span class="txt-18-22 color-7 weight-normal">{{ year }}</span>
      </div>
      <ul>
        <li
          v-for="constructor in constructors"
          :key="constructor.id"
        >
          <div class="bg-9 flex row between-center pl-16 pr-16 pt-12 pb-12 node3-sub1-11">
            <div class="offset-w-70 flex row start-center node3-sub1-10">
              <h1 class="txt-24-29 color-3">
                {{ constructor.position }}
              </h1>
              <div class="ml-18">
                <h2 class="color-3 txt-16-20">
                  {{ constructor.contructor_name }}
                </h2>
                <img
                  :src="constructor.image"
                  class="w-134 h-40 bg-cover lazy node3-sub1-2"
                  :alt="constructor.contructor_name"
                >
                <div class="w-60 h-20 border-8 bg-2 txt-13-16 flex row center-center mt-6">
                  <span class="weight-bold">{{ constructor.pts }}</span>
                  <span class="weight-normal pl-4">PTS</span>
                </div>
              </div>
            </div>
            <div class="full-h w-70 flex col center-center node3-sub1-9">
              <img
                class="box-48 node3-sub1-3"
                :src="constructor.logo"
                :alt="constructor.contructor_name"
              >
            </div>
          </div>
        </li>
      </ul>
      <a :href="getLinkStandingConstructor(year)">
        <div class="h-44 flex row center-center">
          <span class="txt-14-17 color-3 weight-bold">
            {{ trans('base.View Full Standings') }}
          </span>
        </div>
      </a>
    </div>
    <div
      v-lazy:background-image="'https://i.ibb.co/XW8s7CL/cover-2.png'"
      class="h-280 bg-cover pb-16 lazy mt-16 node3-23 hide"
    />
  </aside>
  <!-- end left content -->
</template>

<script>
import request from '../../utils/request'

export default {

  name: 'MotorSportLeftContent',
  props: {
    isScroll: {
      type: Boolean,
      default: () => false
    }
  },
  data () {
    return {
      events: [],
      year: 2018,
      drivers: [],
      constructors: [],
        locale_prefix: window.Vma.locale_prefix
    }
  },
  created () {
    request.get('/race-events')
      .then((response) => {
        this.events = response.data.data
      })
      .catch((error) => {
        throw new Error(JSON.stringify(error))
      })
    this.fetchDriverStanding()
    this.fetchConstructorStanding()
  },
  methods: {
      getLinkStandingDriver(year) {
          if (this.locale_prefix) {
              return '/'+this.locale_prefix+'/standing/driver/'+year
          }
          return '/standing/driver/'+year
      },
      getLinkStandingConstructor(year) {
          if (this.locale_prefix) {
              return '/'+this.locale_prefix+'/standing/constructor/'+year
          }
          return '/standing/constructor/'+year
      },
    fetchDriverStanding () {
      request.get('/standing/driver/' + this.year + '?per_page=3')
        .then((response) => {
          this.drivers = response.data.data
        })
        .catch((error) => {
          throw new Error(JSON.stringify(error))
        })
    },
    fetchConstructorStanding () {
      request.get('/standing/constructor/' + this.year + '?per_page=3')
        .then((response) => {
          this.constructors = response.data.data
        })
        .catch((error) => {
          throw new Error(JSON.stringify(error))
        })
    },
    imageUrl (value) {
      return `${window.Vma.assetUrl}${value}`
    },
    redictToDetail (link) {
      window.location.href = link
    },
      goToHanoi() {
          window.location.href = '/race-events/7/hanoi'
      }
  }
}
</script>

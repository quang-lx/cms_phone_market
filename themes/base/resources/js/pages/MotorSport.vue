<template>
  <div>
    <div class="bg-4 pb-64">
      <section class="container pt-16 flex row between-start js-three-parts res-node3 res-node3-sub1-1">
        <LeftContent :is-scroll="isScroll" />
        <MiddleContent :is-scroll="isScroll" />
        <RightContent :is-scroll="isScroll" />
      </section>
    </div>
    <Footer/>
  </div>
</template>

<script>
import LeftContent from '../components/motorsport/LeftContent'
import MiddleContent from '../components/motorsport/MiddleContent'
import RightContent from '../components/motorsport/RightContent'
import Footer from '../components/Footer'

export default {
  name: 'MotorSport',
  components: { LeftContent, MiddleContent, RightContent, Footer },
  data () {
    return {
      isScroll: false
    }
  },
  created () {
    window.addEventListener('scroll', this.handleScroll)
  },
  destroyed () {
    window.removeEventListener('scroll', this.handleScroll)
  },
  methods: {
    handleScroll () {
      if (window.scrollY >= 540) {
        this.isScroll = true
      } else {
        this.isScroll = false
      }
    }
  }
}
</script>

<style scoped>

</style>

<template>
  <div>
    <section class="bg-4">
      <div class="container pt-16 flex row between-start res-node3 res-node5 js-sub-right">
        <MiddleContent :is-scroll="isScroll" />
        <RightContent :is-scroll="isScroll" />
      </div>
    </section>
    <Footer/>
  </div>
</template>

<script>
import MiddleContent from '../components/mobility/MiddleContent'
import RightContent from '../components/mobility/RightContent'
import Footer from '../components/Footer'

export default {
  name: 'Mobility',
  components: { MiddleContent, RightContent, Footer },
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

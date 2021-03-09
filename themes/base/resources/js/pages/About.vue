<template>
  <div>
    <section class="bg-4">
      <div class="container flex row between-start res-node3 res-node5 js-sub-right">
        <MiddleContent :is-scroll="isScroll" />
        <RightContent :is-scroll="isScroll" />
      </div>
    </section>
    <Footer/>
  </div>
</template>

<script>
import MiddleContent from '../components/about/MiddleContent'
import RightContent from '../components/about/RightContent'
import Footer from '../components/Footer'

export default {
  name: 'About',
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

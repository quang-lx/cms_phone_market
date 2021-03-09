<template>
  <div>
    <section class="bg-4 pb-104">
      <div class="pt-16">
        <div class="container flex row start-start bg-9 js-sub-right res-node3 res-node5">
          <MiddleContent/>
          <RightContent/>
        </div>
      </div>
    </section>
    <Footer/>
  </div>
</template>

<script>
import MiddleContent from '../components/detail/MiddleContent'
import RightContent from '../components/detail/RightContent'
import Footer from '../components/Footer'

export default {
  name: 'Detail',
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

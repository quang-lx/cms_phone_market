<template>
  <div>
    <section class="bg-4 pb-64">
      <Slider v-if="renderComponent" />
      <section class="container pt-16 flex row between-start js-three-parts res-node3">
        <LeftContent v-if="renderComponent" :is-scroll="isScroll"/>
        <MiddleContent v-if="renderComponent" :is-scroll="isScroll" />
        <RightContent v-if="renderComponent" :is-scroll="isScroll" />
      </section>
    </section>
    <Footer/>
  </div>
</template>

<script>
import Slider from '../components/home/Slider'
import LeftContent from '../components/LeftContent'
import MiddleContent from '../components/home/MiddleContent'
import RightContent from '../components/home/RightContent'
import Footer from '../components/Footer'

export default {
  name: 'Home123',
  components: { Slider, LeftContent, MiddleContent, RightContent, Footer },
  data () {
    return {
      isScroll: false,
      renderComponent: true
    }
  },
  created () {
    this.forceRerender()
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
    },
    forceRerender() {
      // Remove my-component from the DOM
      this.renderComponent = false

      this.$nextTick(() => {
        // Add the component back in
        this.renderComponent = true
      })
    }
  }
}
</script>

<style scoped>

</style>

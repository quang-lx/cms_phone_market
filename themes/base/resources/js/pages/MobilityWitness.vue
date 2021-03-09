<template>
    <div>
        <section class="bg-4 pb-64">
            <div class="container">
                <h1 class="color-3 weight-bold txt-32-38 pt-20">{{ trans('base.Witness') }}</h1>
            </div>
            <section class="container pt-16 flex row between-start js-sub-right res-node3 res-node5">
                <MiddleContent :is-scroll="isScroll"/>
                <RightContent :is-scroll="isScroll"/>
            </section>
        </section>
        <Footer/>
    </div>
</template>

<script>
  import MiddleContent from '../components/mobility/WitnessMiddleContent'
  import RightContent from '../components/mobility/RightContent'
  import Footer from '../components/Footer'

  export default {
    name: 'MobilityWitness',
    components: {MiddleContent, RightContent, Footer},
    data() {
      return {
        isScroll: false
      }
    },
    created() {
      window.addEventListener('scroll', this.handleScroll)
    },
    destroyed() {
      window.removeEventListener('scroll', this.handleScroll)
    },
    methods: {
      handleScroll() {
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

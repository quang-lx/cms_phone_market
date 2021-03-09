<template>
    <div>
        <section class="bg-4 pb-64">
            <div class="container res-node6">
                <h1 class="color-3 weight-bold txt-32-38 pt-20 node6-1">{{ trans('base.VMA Community') }}</h1>
            </div>
            <section class="bg-4">
                <div class="container pt-16 flex row between-start js-sub-right res-node3 res-node5">
                    <MiddleContent :is-scroll="isScroll"/>
                    <RightContent :is-scroll="isScroll"/>
                </div>
            </section>
        </section>
        <Footer/>
    </div>
</template>

<script>
  import MiddleContent from '../components/community/MiddleContent'
  import RightContent from '../components/community/RightContent'
  import Footer from '../components/Footer'

  export default {
    name: 'Mobility',
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

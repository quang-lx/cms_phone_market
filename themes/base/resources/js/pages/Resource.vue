<template>
    <div>
        <section class="bg-4 pb-64">

            <section class="container pt-16 flex row between-start js-sub-left res-node10">
                <LeftContent :is-scroll="isScroll" :filters.sync="filters"/>
                <MiddleContent :is-scroll="isScroll" :filters="filters"/>

            </section>
        </section>
        <Footer/>
    </div>
</template>

<script>
    import LeftContent from '../components/resource/left/index'
    import MiddleContent from '../components/resource/MiddleContent'
    import Footer from '../components/Footer'

    export default {
        name: 'Resource',
        components: {LeftContent, MiddleContent, Footer},
        data() {
            return {
                isScroll: false,
                filters: {
                    category: 'all',
                    tag: ''
                },
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
        },

    }
</script>

<style scoped>

</style>

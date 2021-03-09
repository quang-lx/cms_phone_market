<template>
    <div>
        <section class="bg-4 pb-64 res-node8">
            <div class="bg-11 full-w h-280 bg-cover rel flex row center-start">
                <div class="h-140 full-w left-0 bottom-0 lazy bg-cover abs flex row center-start"
                     data-src="https://i.ibb.co/7Jdj2nr/Frame-2.png"></div>
                <h1 class="color-2 txt-52-61 pt-48 weight-normal">{{trans('base.Drivers Standings')}}</h1>
            </div>
            <div class="container rel mt--120 node8-1">
                <div class="flex row between-end mb-2">
                    <div class="w-410 h-160 bg-9 rel" v-if="top[1]">
                        <div class="pt-20 pl-20">
                            <div class="flex row start-center">
                                <div class="box-32 circle flex row center-center bg-gradient-1 node8-4">
                                    <span class="white txt-18-22 node8-5">2</span>
                                </div>
                                <div class="border-8 bg-2 txt-15-18 flex row start-center pt-4 pb-4 pl-10 pr-10 ml-12 node8-6">
                                    <strong class="color-3  weight-bold">{{top[1].pts}}</strong>
                                    <span class="color-3  weight-normal pl-4">PTS</span>
                                </div>
                            </div>
                            <h1 class="txt-25-29x5 color-3 mt-10 node8-7">
                                <span class="weight-normal">{{top[1].first_name}}</span>
                                <br>
                                <span class="weight-bold">{{top[1].last_name}}</span>
                            </h1>
                            <div class="mt-6 color-7 txt-15-18">{{top[1].contructor_name}}</div>
                        </div>
                        <div class="w-268 h-214 bg-cover lazy abs right-0 bottom-0 node8-2"
                             :data-src="imageUrl(top[1].avatar)"></div>
                    </div>
                    <div class="w-415 h-180 bg-9 rel" v-if="top[0]">
                        <div class="pt-20 pl-20">
                            <div class="flex row start-center">
                                <div class="box-32 circle flex row center-center bg-gradient-2 node8-4">
                                    <span class="white txt-18-22 node8-5">1</span>
                                </div>
                                <div class="border-8 bg-2 txt-15-18 flex row start-center pt-4 pb-4 pl-10 pr-10 ml-12 node8-6">
                                    <strong class="color-3  weight-bold">{{top[0].pts}}</strong>
                                    <span class="color-3  weight-normal pl-4">PTS</span>
                                </div>
                            </div>
                            <h1 class="txt-25-29x5 color-3 mt-10 node8-7">
                                <span class="weight-normal">{{top[0].first_name}}</span>
                                <br>
                                <span class="weight-bold">{{top[0].last_name}}</span>
                            </h1>
                            <div class="mt-6 color-7 txt-15-18">{{top[0].contructor_name}}</div>
                        </div>
                        <div class="w-291 h-232 bg-cover lazy abs right-0 bottom-0 node8-3"
                             :data-src="imageUrl(top[0].avatar)"></div>
                    </div>
                    <div class="w-410 h-160 bg-9 rel" v-if="top[2]">
                        <div class="pt-20 pl-20">
                            <div class="flex row start-center">
                                <div class="box-32 circle flex row center-center bg-gradient-3 node8-4">
                                    <span class="white txt-18-22 node8-5">3</span>
                                </div>
                                <div class="border-8 bg-2 txt-15-18 flex row start-center pt-4 pb-4 pl-10 pr-10 ml-12 node8-6">
                                    <strong class="color-3  weight-bold">{{top[2].pts}}</strong>
                                    <span class="color-3  weight-normal pl-4">PTS</span>
                                </div>
                            </div>
                            <h1 class="txt-25-29x5 color-3 mt-10 node8-7">
                                <span class="weight-normal">{{top[2].first_name}}</span>
                                <br>
                                <span class="weight-bold">{{top[2].last_name}}</span>
                            </h1>
                            <div class="mt-6 color-7 txt-15-18">{{top[2].contructor_name}}</div>
                        </div>
                        <div class="w-268 h-214 bg-cover lazy abs right-0 bottom-0 node8-2"
                             :data-src="imageUrl(top[2].avatar)"></div>
                    </div>
                </div>


                <div class="h-64 bg-2 row flex between-center pr-32 pl-18 mb-2" v-for="driver in listDriver" :key="driver.id">
                    <div class="row flex start-center mb-2">
                        <span class="txt-16-19 color-3 weight-bold">{{driver.position}}</span>
                        <div class="w-4 h-16 bg-14 ml-17"></div>
                        <div class="box-40 border-15 circle lazy ml-24"
                             :data-src="imageUrl(driver.avartar)"></div>
                        <h1 class="color-3 txt-16-20 weight-bold ml-24">{{driver.driver_name}}</h1>
                        <h2 class="color-4 txt-15-23 color-7 weight-normal ml-24">{{driver.contructor_name}}</h2>
                    </div>
                    <div class="border-8 bg-2 txt-15-18 flex row start-center pt-4 pb-4 pl-10 pr-10 ml-12">
                        <strong class="color-3  weight-bold">{{driver.pts}}</strong>
                        <span class="color-3  weight-normal pl-4">PTS</span>
                    </div>
                </div>








            </div>
        </section>
        <Footer/>
    </div>
</template>

<script>
    import request from '../utils/request'
    import _ from 'lodash'
    import Footer from '../components/Footer'


    export default {
        name: 'StandingDriver',
        props: {},
        components: {   Footer },

        data() {
            return {
                drivers: [],
                page: 1,
                canLoad: true
            }
        },
        mounted() {
            this.fetchData()
        },
        computed: {
            top: function() {
                return _.take(this.drivers,3)
            },
            listDriver: function() {
                return _.slice(this.drivers, 3);
            }
        },
        methods: {
            fetchData() {
                if (this.canLoad === true) {
                    let $self = this
                    request.get('/standing/driver/'+ this.$route.params.season)
                        .then((response) => {
                            let items = response.data.data
                            _.each(items, (item) => {
                                $self.drivers.push(item)
                            })
                            if (response.data.meta.current_page < response.data.meta.last_page) {
                                $self.page = response.data.meta.current_page + 1
                            } else {
                                $self.canLoad = false
                            }

                        })
                        .catch((error) => {
                            throw new Error(JSON.stringify(error))
                        })
                }
            },
            imageUrl(value) {
                return `${window.Vma.assetUrl}${value}`
            }
        },
        updated() {
            App.lazyLoadSupport();
        }

    }
</script>

<style>

</style>

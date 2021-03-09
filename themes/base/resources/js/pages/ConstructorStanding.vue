<template>
    <div>
    <section class="bg-4 pb-64 res-node9">
        <div class="bg-11 full-w h-280 bg-cover rel flex row center-start">
            <div class="h-140 full-w left-0 bottom-0 lazy bg-cover abs flex row center-start" data-src="https://i.ibb.co/7Jdj2nr/Frame-2.png"></div>
            <h1 class="color-2 txt-52-61 pt-48 weight-normal node9-12">{{trans('base.Constructors Standings')}}</h1>
        </div>
        <div class="container rel mt--120 node9-1">
            <div class="flex row between-end mb-2">
                <div class="w-410 h-160 bg-9 rel row flex between-start node9-2"  v-if="top[1]">
                    <div class="mt-20 node9-9">
                        <div class="row flex start-center ml-20">
                            <div class="box-32 circle flex row center-center bg-gradient-2 node9-8">
                                <span class="white txt-18-22">2</span>
                            </div>
                            <h1 class="txt-25-30 color-3 weight-bold ml-16">{{top[1].contructor_name}}</h1>
                        </div>
                        <div class="w-268 h-80 bg-cover lazy mt-12 ml-20 node9-4" v-lazy:background-image="imageUrl(top[1].image)" ></div>
                    </div>
                    <div class="flex col start-center mt-24 mr-24 node9-10">
                        <div class="border-8 bg-2 txt-15-18 flex row start-center pt-4 pb-4 pl-10 pr-10 node9-11">
                            <strong class="color-3  weight-bold">{{top[1].pts}}</strong>
                            <span class="color-3  weight-normal pl-4">PTS</span>
                        </div>
                        <div class="w-48 h-62 bg-cover lazy mt-26 node9-5" v-lazy:background-image="imageUrl(top[1].logo)"></div>
                    </div>
                </div>


                <div class="w-415 h-180 bg-9 rel row flex between-start node9-3"  v-if="top[0]">

                    <div class="mt-20 node9-9">
                        <div class="row flex start-center ml-20">
                            <div class="box-32 circle flex row center-center bg-gradient-1 node9-8">
                                <span class="white txt-18-22">1</span>
                            </div>
                            <h1 class="txt-25-30 color-3 weight-bold ml-16">{{top[0].contructor_name}}</h1>
                        </div>
                        <div class="w-268 h-80 bg-cover lazy mt-12 ml-20 node9-4" v-lazy:background-image="imageUrl(top[0].image)" ></div>
                    </div>
                    <div class="flex col start-center mt-24 mr-24 node9-10">
                        <div class="border-8 bg-2 txt-15-18 flex row start-center pt-4 pb-4 pl-10 pr-10 node9-11">
                            <strong class="color-3  weight-bold">{{top[0].pts}}</strong>
                            <span class="color-3  weight-normal pl-4">PTS</span>
                        </div>
                        <div class="box-64 bg-cover lazy mt-26 node9-6" v-lazy:background-image="imageUrl(top[0].logo)"></div>
                    </div>


                </div>

                <div class="w-410 h-160 bg-9 rel row flex between-start node9-2" v-if="top[2]">
                    <div class="mt-20 node9-9">
                        <div class="row flex start-center ml-20">
                            <div class="box-32 circle flex row center-center bg-gradient-3 node9-8">
                                <span class="white txt-18-22">3</span>
                            </div>
                            <h1 class="txt-25-30 color-3 weight-bold ml-16">{{top[2].contructor_name}}</h1>
                        </div>
                        <div class="w-268 h-80 bg-cover lazy mt-12 ml-20 node9-4" v-lazy:background-image="imageUrl(top[2].image)" ></div>
                    </div>
                    <div class="flex col start-center mt-24 mr-24 node9-10">
                        <div class="border-8 bg-2 txt-15-18 flex row start-center pt-4 pb-4 pl-10 pr-10 node9-11">
                            <strong class="color-3  weight-bold">{{top[2].pts}}</strong>
                            <span class="color-3  weight-normal pl-4">PTS</span>
                        </div>
                        <div class="w-80 h-36 bg-cover lazy mt-26 node9-7" v-lazy:background-image="imageUrl(top[2].logo)"></div>
                    </div>
                </div>

            </div>

            <div class="h-64 bg-2 row flex between-center pr-32 pl-18 mb-2" v-for="item in listConstructors" :key="item.id">
                <div class="row flex start-center mb-2">
                    <span class="txt-16-19 color-3 weight-bold">{{item.position}}</span>
                    <div class="w-4 h-16 bg-14 ml-17"></div>
                    <h1 class="color-3 txt-16-20 weight-bold ml-16">{{item.contructor_name}}</h1>
                </div>
                <div class="row flex end-center">
                    <div class="h-36 w-123 lazy bg-cover" v-lazy:background-image="imageUrl(item.image)" ></div>
                    <div class="border-8 bg-2 txt-15-18 flex row start-center pt-4 pb-4 pl-10 pr-10 ml-12">
                        <strong class="color-3  weight-bold">{{item.pts}}</strong>
                        <span class="color-3  weight-normal pl-4">PTS</span>
                    </div>
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
        name: 'StandingConstructor',
        props: {},
        components: {   Footer },

        data() {
            return {
                constructors: [],
                page: 1,
                canLoad: true
            }
        },
        mounted() {
            this.fetchData()
        },
        computed: {
            top: function() {
                return _.take(this.constructors,3)
            },
            listConstructors: function() {
                return _.slice(this.constructors, 3);
            }
        },
        methods: {
            fetchData() {
                if (this.canLoad === true) {
                    let $self = this
                    request.get('/standing/constructor/'+ this.$route.params.season)
                        .then((response) => {
                            let items = response.data.data
                            _.each(items, (item) => {
                                $self.constructors.push(item)
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

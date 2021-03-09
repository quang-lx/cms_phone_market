<template>
    <div class="rel bg-4 res-node7">
        <div class="h-320 bg-cover lazy abs top-0 left-0 full-w"  v-lazy:background-image="imageUrl(detail.city_image)"></div>
        <div class="container rel index-2 pb-32 node7-1">
            <h1 class="txt-64-75 weight-bold white pt-52 pl-32" style="text-transform: uppercase;">{{detail.country_code}}</h1>
            <img :src="default2019Url()" alt="" class="pl-32 pt-12" v-if="detail.country_code!='vietnam'">

            <div class="bg-2 mt-72 pl-32 pr-32 pt-32 row flex between-start pb-32 node7-4">
                <div class="color-3 w-382 node7-3">
                    <div :class="'spirit-big-'+detail.country_code"></div>

                    <h1 class="txt-40-47 color-3">{{detail.race_name}}</h1>

                    <div class="row flex between-start mt-28">
                        <div class="w-191">
                            <h2 class="txt-14-17 weight-normal">{{trans('base.First Grand Prix')}}</h2>
                            <h2 class="txt-32-38 pt-8">{{detail.first_grand_prix}}</h2>
                        </div>
                        <div class="w-191">
                            <h2 class="txt-14-17 weight-normal">{{trans('base.Number of Laps')}}</h2>
                            <h2 class="txt-32-38 pt-8">{{detail.num_of_laps}}</h2>
                        </div>
                    </div>
                    <div class="row flex between-start mt-24">
                        <div class="w-191">
                            <h2 class="txt-14-17 weight-normal">{{trans('base.Circuit Length')}}</h2>
                            <h2 class="txt-32-38 pt-8">{{detail.circuit_length}} km</h2>
                        </div>
                        <div class="w-191">
                            <h2 class="txt-14-17 weight-normal">{{trans('base.Race Distance')}}</h2>
                            <h2 class="txt-32-38 pt-8">{{detail.race_distance}} km</h2>
                        </div>
                    </div>
                    <div class="mt-24">
                        <h2 class="txt-14-17 weight-normal">{{trans('base.Lap Record')}}</h2>
                        <p>
                            <span class="txt-32-38 pt-8 weight-bold">{{detail.lap_record}}</span>
                            <span class="txt-14-17">{{detail.lap_record_driver}}</span>
                        </p>
                    </div>
                </div>
                <div class="w-772 h-434 bg-cover lazy node7-2"  v-lazy:background-image="imageUrl(detail.circuit)"></div>
            </div>


            <div class="mt-16 color-3 bg-2 pt-32 pl-32 pr-32 pb-32 custon-font node7-7" v-html="detail.race_description">
            </div>

            <div class="mt-16 flex row between-start node7-6">
                <div class="w-296 bg-2 node7-5" v-for="item in related">
                    <div class="h-148 bg-cover lazy" v-lazy:background-image="imageUrl(item.normal_image)"></div>
                    <div class="pt-12 pl-16 pb-16 pr-16">
                        <span class="bg-5 pt-2 pb-2  pl-6 pr-6 weight-normal color-2 txt-12-18">{{item.tags}}</span>
                        <h1 class="mt-8 color-3 txt-16-26 two-line" @click="redictToDetail(item.public_url)">{{item.title}} </h1>
                        <p class="color-7 txt-14-22 mt-2"> {{ item.realesed_at }}</p>
                    </div>
                </div>

            </div>
        </div>



    </div>
</template>

<script>
    import request from '../utils/request'
    import _ from 'lodash'


    export default {
        name: 'RaceEventDetail',

        data() {
            return {
                detail: {},
                related:[]

            }
        },
        mounted() {
            this.fetchData()
        },

        methods: {
            fetchData() {
                let $self = this
                let id = this.$route.params.id
                request.get('/race-events/find/' + id)
                    .then((response) => {
                        $self.detail = response.data.data
                        request.get('/news/race-news?per_page=4&tags='+ $self.detail.sport_type)
                            .then((response) => {
                                $self.related = response.data.data
                                App.lazyLoadSupport();
                            })
                            .catch((error) => {
                                throw new Error(JSON.stringify(error))
                            })
                    })
                    .catch((error) => {
                        throw new Error(JSON.stringify(error))
                    })
            },
            imageUrl(value) {
                return `${window.Vma.assetUrl}${value}`
            },
            default2019Url() {
                return `/images/2019.png`;
            },
            redictToDetail(link) {
                window.location.href= link
            }
        },

    }
</script>

<style>
.custon-font {
    font-family: 'Roboto Condensed';
    line-height: 24px;
}
</style>

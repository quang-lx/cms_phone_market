<template>
    <!-- start article list -->
    <section class="w-920 pointer node10-2">

        <div class="flex wrap-between">
            <component v-for="item in resources" :is="item.type =='album'? 'Album': (item.type === 'video'? 'Video': 'Document')" :key="item.id" :resource="item" :indexResource="imgCollectNum[item.id]"></component>

        </div>
        <div class="mt-16 full-w h-48 bg-5 flex col center-center"  @click="fetchResource" v-if="canLoad">
            <span class="txt-16-19 white weight-bold">{{ trans('base.See more') }}</span>
        </div>
    </section>
    <!-- end article list -->
</template>

<script>
    import request from '../../utils/request'
    import _ from 'lodash'
    import Album from './type/Album'
    import Document from './type/Document'
    import Video from './type/Video'

    export default {
        name: 'ResourceMiddleContent',
        props: {
            isScroll: {
                type: Boolean,
                default: () => false
            },
            filters: {
                type: Object,
                default: () => {category: ''}
            }
        },
        components: {
            Album,
            Document,
            Video,
        },
        data() {
            return {
                resources: [],
                page: 1,
                canLoad: true,
                albumCount: 0
            }
        },
        mounted() {
            this.fetchResource()
        },
        computed: {
            imgCollectNum () {
                let nums = []
                let count = 0;
                _.each(this.resources, function (item) {
                    console.log(item.id)
                    if (item.type === 'album') {
                        nums['' + item.id + ''] = count
                        count++
                    }
                })
                return nums
            }
        },

        methods: {
            getAlbumCount(resouce) {
                console.log(resouce.id)
                let albumIndex = this.albumCount;
                if (resouce.type == 'album') {
                    this.albumCount++

                }
                return albumIndex;
            },
            fetchResource() {
                if (this.canLoad === true) {
                    let $self = this
                    request.get('/resources?per_page=10&page=' + this.page + '&type=' + this.filters.category)
                        .then((response) => {
                            let items = response.data.data
                            _.each(items, (item) => {
                                $self.resources.push(item)
                            })
                            if (response.data.meta.current_page < response.data.meta.last_page) {
                                $self.page = response.data.meta.current_page + 1
                            } else {
                                $self.canLoad = false
                            }
                            $(function () {
                                App.headerSnap();
                                App.lazyLoadSupport();
                                App.breadscrumb();
                                App.activeHoverNavMenu();
                                App.changeLanguage();
                                App.sideMenuScrollEffect();
                                App.playVideo();
                                App.topCarousel()
                                App.swipeList()
                                App.modal()
                                App.dropdownFunction()
                                App.imageAlbumSlide()
                                App.PdfReader()
                            })
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
        watch: {
            filters: {
                deep: true,
                handler(val){
                    this.resources = []
                    this.page = 1
                    this.albumCount=0
                    this.canLoad= true
                    this.fetchResource()

                },


            }
        }

    }
</script>

<style>

</style>

<template>



    <div class="w-452 h-289 bg-cover lazy mb-16 rel js-images js-active-modal node10-3" v-lazy:background-image="imageUrl(resource.image)">
        <h1 class="white abs bottom-0 left-0 pl-16 pr-16 txt-18-30 weight-bold pb-12 two-line">{{resource.name}}</h1>
        <div class="box-32 flex col center-center bg-5 top-0 right-0 abs">
            <div class="spirit-image-white"></div>
        </div>


        <div class="fixed full-w full-h bg-modal top-0 left-0 index-4 flex col center-center hide js-modal">
            <div class="h-623 w-816 bg-2 js-content-modal node10-14">
                <div class="h-48 flex row between-center">
                    <div class="flex row start-center">
                        <div class="box-32 bg-5 ml-12 flex col center-center node10-21">
                            <div class="spirit-image-white"></div>
                        </div>
                        <h1 class="pl-8 color-3 txt-18-30 weight-bold node10-19">{{resource.name}}</h1>
                    </div>
                    <div class="spirit-exit mr-12 node10-20"></div>
                </div>
                <div class="full-w">
                    <!-- start top carousel -->
                    <section class="full-w rel">
                        <div class="owl-carousel owl-carousel-1 h-488 owl-theme full-box common-custom-owl image-resouce-slider node10-15 owl-loaded owl-drag">
                            <a v-for="image in resource.collections" @click.prevent="_=>{}" :key="'image-' + image.origin">
                                <div class="bg-cover full-box owl-lazy" :data-src="imageUrl(image.origin)"  :data-src-retina="imageUrl(image.origin)"></div>
                            </a>

                        </div>
                        <div class="hidden-overflow rel h-88 js-dot-wrapper node10-17">
                            <ul :id="'carousel-custom-dots-' + indexResource" class="disable-user-select top-carousel-custom-dots js-dots-list owl-dots top-8 image-resource-slider-owl-dot bg-2 abs flex row start-center node10-18">
                                <li class='owl-dot rel pointer ml-8'  v-for="image in resource.collections"  :key="'image-hidden-' + image.thumbnail">
                                    <div class="box-72 lazy bg-cover node10-16"  :data-src="imageUrl(image.thumbnail)" v-lazy:background-image="imageUrl(image.thumbnail)"></div>
                                </li>

                            </ul>
                        </div>
                    </section>
                    <!-- end top carousel -->
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import request from '../../../utils/request'
    import _ from 'lodash'

    export default {
        name: 'ResourceAlbum',
        props: {

            resource: {
                type: Object,
                default: () => {}
            },
            indexResource: {
                type: Number,
                default: 0
            },
        },
        data() {
            return {

            }
        },
        methods: {
            imageUrl(value) {
                return `${window.Vma.assetUrl}${value}`;
            },
        },
        mounted() {

        },


    }
</script>

<style>

</style>

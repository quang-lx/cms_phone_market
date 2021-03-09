<template>
    <div class="w-452 h-289 bg-cover lazy mb-16 rel   node10-3" v-lazy:background-image="resource.video_thumbnail" @click="showPopup=true">
        <h1 class="white abs bottom-0 left-0 pl-16 pr-16 txt-18-30 weight-bold pb-12 two-line" >{{resource.name}}</h1>
        <div class="box-32 flex col center-center bg-5 top-0 right-0 abs">
            <div class="spirit-play-white"></div>
        </div>
        <div class="fixed full-w full-h bg-modal top-0 left-0 index-4 flex col center-center "   v-if="showPopup">
            <div class="h-536 w-816 bg-2  node10-9">

                <div class="h-48 flex row between-center">
                    <div class="flex row start-center">
                        <div class="box-32 bg-5 ml-12 flex col center-center node10-21">
                            <div class="spirit-image-white"></div>
                        </div>
                        <h1 class="pl-8 color-3 txt-18-30 weight-bold node10-19">{{resource.name}}</h1>
                    </div>
                    <div class="spirit-exit mr-12 node10-20" @click.stop="showPopup=false"></div>
                </div>

                <div class="full-w h-488 node10-10">
                    <iframe class="full-box border-none" :src="resource.embbed_link" v-if="resource.video_type='youtube'">
                    </iframe>

                    <iframe v-else :src="resource.embbed_link"    frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                 </div>
            </div>
        </div>
    </div>


</template>

<script>
    import request from '../../../utils/request'
    import _ from 'lodash'

    export default {
        name: 'ResourceVideo',
        props: {

            resource: {
                type: Object,
                default: () => {
                }
            }
        },
        data() {
            return {
                showPopup: false
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

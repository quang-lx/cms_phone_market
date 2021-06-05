<template>
    <div>
        <label class="el-form-item__label">{{ getFieldLabel() }}</label>
        <div class="jsThumbnailImageWrapper jsSingleThumbnailWrapper" v-if="hasSelectedMedia" >
            <draggable :list="this.selectedMedia" :options="{group:'images'}" @start="drag=true" @end="drag=false"  @change="changeOrder">
                <div style="display:flex">
                    <div class="row">
                        <div v-for="media in this.selectedMedia" :key="media.id" class="file-in-multiple col-md-3" style="display: flex">
                            <figure
                              v-if="media.mimetype == 'video/mp4' || media.mimetype == 'video/quicktime' || media.mimetype == 'video/x-matroska'"
                              style="display: flex; max-height:200px">
                                <video-player
                                  class="video-player-box"
                                  ref="videoPlayer"
                                  style="background:gray"

                                  :playsinline="true"
                                  :options="{
                                  height: 120,
                                    sources: [{
                                    type: media.minetype,
                                    src: media.full_url
                                }],
                                  }"
                                ></video-player>
                                <span class="el-icon-error remove-media" @click="unSelectMedia(media.id)"></span>
                            </figure>
                            <figure  v-else>
                                <img :src="media.medium_thumb" alt="" v-if="media.is_image"/>
                                <i :class="`fa ${media.fa_icon}`" style="font-size: 60px;" v-if="! media.is_image"></i>
                                <span v-if="! media.is_image" style="display:block;">{{ media.filename }}</span>
                                <span class="el-icon-error remove-media" @click="unSelectMedia(media.id)"></span>
                            </figure>
                        </div>
                    </div>

                </div>
            </draggable>
            <div class="clearfix"></div>
        </div>
        <div>
            <el-button type="button" @click="dialogVisible = true">{{ $t('media.Browse') }}</el-button>
        </div>
        <el-dialog
                :visible.sync="dialogVisible"
                fullscreen
                :before-close="handleClose">

            <media-list single-modal hide-after-insert :event-name="this.eventName"></media-list>

            <span slot="footer" class="dialog-footer">
                <el-button @click="dialogVisible = false">{{ $t('mon.button.close') }}</el-button>
            </span>
        </el-dialog>
    </div>
</template>

<script>
    import axios from 'axios';
    import UploadZone from './UploadZone.vue';
    import MediaList from './MediaList.vue';
    import StringHelpers from './../../../../mixins/StringHelpers.vue';
    import draggable from 'vuedraggable'


    import 'video.js/dist/video-js.css'

    import { videoPlayer } from 'vue-video-player'


    export default {
        mixins: [StringHelpers],
        props: {
            zone: { type: String, required: true },
            entity: { type: String, required: true },
            entityId: { default: null },
            label: { type: String },
        },
        components: {
            'upload-zone': UploadZone,
            'media-list': MediaList,
            videoPlayer,
            draggable,
        },
        watch: {
            entityId() {
                if (this.entityId) {
                    this.fetchMedia();
                }
            },
        },
        data() {
            return {
                dialogVisible: false,
                selectedMedia: [],
                eventName: '',
                videooptions: {
                    autoplay: false,
                    volume: 0.6,
                    poster: 'http://covteam.u.qiniudn.com/poster.png'
                }
            };
        },
        computed: {
            hasSelectedMedia() {
                return this.selectedMedia !== undefined && !_.isEmpty(this.selectedMedia);
            },
        },
        methods: {
            changeOrder() {
                let imageAfterOrders = this.selectedMedia.map(item => item.id);
                this.$emit('fileSorted',  {sorted:imageAfterOrders, zone: this.zone });
            },
            handleClose(done) {
                done();

            },
            unSelectMedia(id) {
                this.selectedMedia = _.reject(this.selectedMedia, media => media.id === id);
                this.$emit('fileUnselected', { id, zone: this.zone });
            },
            fetchMedia() {
                axios.get(route('api.media.get-by-zone-and-entity', {
                    zone: this.zone,
                    entity: this.entity,
                    entity_id: this.entityId,
                }))
                    .then((response) => {
                        this.selectedMedia = response.data.data;
                        _.forEach(this.selectedMedia, (file) => {
                            this.$emit('multipleFileSelected', { id: file.id, zone: this.zone });
                        });
                    });
            },
            getFieldLabel() {
                return this.label || this.ucwords(this.zone.replace('_', ' '));
            },
            makeId() {
                let text = '';
                const possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

                for (let i = 0; i < 5; i++) { text += possible.charAt(Math.floor(Math.random() * possible.length)); }

                return text;
            },
        },
        mounted() {
            if (this.entityId) {
                this.fetchMedia();
            }
            this.eventName = `fileWasSelected${this.makeId()}${Math.floor(Math.random() * 999999)}`;

            this.$events.listen(this.eventName, (mediaData) => {
                if (_.find(this.selectedMedia, mediaData) === undefined) {
                    if (!this.selectedMedia) this.selectedMedia = [];
                    this.selectedMedia.push(mediaData);
                    this.$emit('multipleFileSelected', _.merge(mediaData, { zone: this.zone }));
                }
            });
        },
    };
</script>
<style>
    .remove-media{
        position: absolute;
        top: 5px;
        left: 5px;
        color: #FA5555;
    }
    .file-in-multiple:not(:first-child) {
        padding-left: 20px;

    }
    vue-video-center {
        background: gray;
    }
    .vue-video-player >> video {
        background: gray;
    }

</style>

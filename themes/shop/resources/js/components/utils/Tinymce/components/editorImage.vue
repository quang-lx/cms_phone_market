<template>
    <div class="upload-container editor-upload-btn">
        <el-button type="primary" size="small" style="width:150px" @click="dialogVisible = true">
            <div>{{$t('media.insert')}}&nbsp &nbsp<i class="el-icon-upload el-icon-right"></i></div>
        </el-button>
        <el-dialog
                :visible.sync="dialogVisible"
                :destroy-on-close="true"
                :show-close	="false">

            <media-for-editor single-modal hide-after-insert :event-name="this.eventName" @insertToEditor="insertToEditor"></media-for-editor>

            <span slot="footer" class="dialog-footer">
                <el-button @click="dialogVisible = false">{{ $t('mon.button.close') }}</el-button>
            </span>
        </el-dialog>
    </div>
</template>

<script>
    import MediaForEditor from './../../../media/js/components/MediaForEditor';
    export default {
        name: 'EditorSlideUpload',
        components: { 'media-for-editor': MediaForEditor,},
        data () {
            return {
                dialogVisible: false,
                listObj: {},
                fileList: [],
                images: [],
                newImages: [],
                eventName: 'insertToEditor',
            }
        },
        mounted () {

        },
        methods: {
            handleClose() {
                this.dialogVisible=false;
            },
            insertToEditor(row) {
                this.$emit(this.eventName, row);
            }
        },

    }
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
    .editor-slide-upload {
        margin-bottom: 20px;

    }
</style>

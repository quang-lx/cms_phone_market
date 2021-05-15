<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-12">
                        <el-breadcrumb separator="/" v-if="! singleModal">
                            <el-breadcrumb-item>
                                <a href="/backend">Home</a>
                            </el-breadcrumb-item>
                            <el-breadcrumb-item :to="{name: 'admin.media.index'}">{{ $t('media.label.media') }}
                            </el-breadcrumb-item>
                        </el-breadcrumb>
                    </div>

                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-md-6">

                        <el-button-group>

                            <el-button type="danger" :disabled="selectedMedia.length === 0"
                                       @click.prevent="batchDelete" :loading="filesAreDeleting">
                                {{ $t('mon.button.delete') }}
                            </el-button>
                        </el-button-group>
                    </div>
                    <div class="offset-md-2 col-md-4 float-right">
                        <el-input prefix-icon="el-icon-search" @keyup.native="performSearch"
                                  v-model="searchQuery">
                        </el-input>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header ui-sortable-handle" style="cursor: move;">
                                <h3 class="card-title" v-if="singleModal">{{ $t('media.label.choose_file') }}</h3>
                                <h3 class="card-title" v-if="!singleModal">{{ $t('media.label.media') }}</h3>

                            </div><!-- /.card-header -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-8">


                                    </div>
                                    <div class="col-sm-4">

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <vue-dropzone ref="myVueDropzone" id="dropzone" :options="dropzoneOptions"
                                                      @vdropzone-file-added="vdropzoneFileAdded"
                                                      @vdropzone-success="vdropzoneSuccess"></vue-dropzone>

                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <el-breadcrumb separator="/" style="margin-bottom: 20px;">
                                            <el-breadcrumb-item v-for="(folder, index) in folderBreadcrumb"
                                                                @click.native="changeRoot(folder.id, index)"
                                                                :key="folder.id">
                                                {{ folder.name }}
                                            </el-breadcrumb-item>
                                        </el-breadcrumb>
                                    </div>
                                </div>
                                <rename-folder></rename-folder>
                                <move-dialog></move-dialog>
                                <div class="sc-table">

                                    <el-table
                                        :data="data"
                                        stripe
                                        style="width: 100%"
                                        ref="mediaTable"
                                        v-loading.body="tableIsLoading"
                                        @sort-change="handleSortChange"
                                        @selection-change="handleSelectionChange">
                                        <el-table-column
                                            type="selection"
                                            width="55">
                                        </el-table-column>
                                        <el-table-column label="" width="150">
                                            <template slot-scope="scope">
                                                <img :src="scope.row.small_thumb" alt="" v-if="scope.row.is_image"/>
                                                <i :class="`fa ${scope.row.fa_icon}`" style="font-size: 38px;"
                                                   v-if="! scope.row.is_image && ! scope.row.is_folder"></i>
                                                <i class="fa fa-folder" style="font-size: 38px;"
                                                   v-if="scope.row.is_folder"></i>
                                            </template>
                                        </el-table-column>

                                        <el-table-column prop="filename" :label="$t('media.label.filename')"
                                                         sortable="custom">
                                            <template slot-scope="scope">
                                                <strong v-if="scope.row.is_folder" style="cursor: pointer;"
                                                        @click="enterFolder(scope)">
                                                    {{ scope.row.filename }}
                                                </strong>
                                                <span v-else>
                                        {{ scope.row.filename }}
                                    </span>
                                            </template>
                                        </el-table-column>
                                        <el-table-column prop="created_at" :label="$t('media.label.created_at')"
                                                         sortable="custom"
                                                         width="150">
                                        </el-table-column>
                                        <el-table-column prop="actions" label="" width="150">
                                            <template slot-scope="scope">
                                                <div class="pull-right">
                                                    <el-button
                                                        type="primary"
                                                        size="small"
                                                        @click.prevent="insertMedia(scope)"
                                                        v-if="singleModal && ! scope.row.is_folder && !scope.row.hideAfterInsert ">
                                                        {{ $t('media.insert') }}
                                                    </el-button>
                                                    <i class="el-icon-success" v-if="scope.row.hideAfterInsert"
                                                       style="color: green;font-size: 24px;"></i>
                                                    <div v-if="! singleModal">
                                                        <el-button-group>
                                                            <delete-button :scope="scope" :rows="data"></delete-button>
                                                        </el-button-group>
                                                    </div>
                                                </div>
                                            </template>
                                        </el-table-column>
                                    </el-table>
                                    <div class="pagination-wrap" style="text-align: center; padding-top: 20px;">
                                        <el-pagination
                                            @size-change="handleSizeChange"
                                            @current-change="handleCurrentChange"
                                            :current-page.sync="meta.current_page"
                                            :page-sizes="[10, 20, 50, 100]"
                                            :page-size="parseInt(meta.per_page)"
                                            layout="total, sizes, prev, pager, next, jumper"
                                            :total="meta.total">
                                        </el-pagination>
                                    </div>

                                </div>
                            </div><!-- /.card-body -->
                        </div>
                    </div>
                </div>

            </div>
            <el-dialog
                title="Crop ảnh"
                :visible.sync="cropVisible"
                :close-on-click-modal="false"
                :show-close	="false"
                :destroy-on-close="true"
                class="crop-dialog"
                width="50%">
                <div class="row">
                    <div class="col-10">
                        <section class="cropper-area">
                            <div class="img-cropper">
                                <vue-cropper
                                    v-if="cropFile"
                                    ref="cropper"
                                    :aspect-ratio="aspectRatio"
                                    :src="cropFile"
                                    preview=".preview"
                                />
                            </div>
                        </section>
                    </div>
                    <div class="col-2"  >
                        <div class="row">
                            <div class="col-12">
                                <el-form
                                    ref="form"
                                    :model="cropForm"
                                    label-position="top"
                                >
                                    <el-form-item
                                        label="Tỷ lệ"
                                    >
                                        <el-input :min="1" v-model="cropForm.rotationW" style="width:45%" @change="changeAspectRatio"></el-input>
                                        /
                                        <el-input :min="1" v-model="cropForm.rotationH" style="width:45%" @change="changeAspectRatio"></el-input>

                                    </el-form-item>
                                </el-form>
                            </div>
                        </div>
                    </div>
                </div>

                <span slot="footer" class="dialog-footer">
                    <el-button type="danger" size="small" @click="noCropImage">Giữ nguyên</el-button>
                    <el-button type="warning" size="small" @click="cropImage">Crop</el-button>
                    <el-button type="info" size="small" @click="cancelImage">Hủy</el-button>
                </span>
            </el-dialog>

        </section>
    </div>

</template>

<script>
  import axios from 'axios';
  import NewFolder from './NewFolder.vue';
  import UploadButton from './UploadButton.vue';
  import RenameFolder from './RenameFolder.vue';
  import MoveMediaDialog from './MoveMediaDialog.vue';
  import _ from 'lodash';
  import vue2Dropzone from 'vue2-dropzone'
  import 'vue2-dropzone/dist/vue2Dropzone.min.css'
  import VueCropper from 'vue-cropperjs';
  import 'cropperjs/dist/cropper.css';

  export default {
    components: {
      'new-folder': NewFolder,
      'upload-button': UploadButton,
      'rename-folder': RenameFolder,
      'move-dialog': MoveMediaDialog,
      vueDropzone: vue2Dropzone,
      VueCropper
    },
    props: {
      singleModal: {type: Boolean},
      eventName: {},
      hideAfterInsert: {type: Boolean},
    },
    data() {
      return {
        cropForm: {
          rotationW: 3,
          rotationH: 2
        },
        cropVisible: false,
        data: [],
        tableIsLoading: false,
        meta: {
          current_page: 1,
          per_page: 10,
        },
        order_meta: {
          order_by: '',
          order: '',
        },
        links: {},
        searchQuery: '',
        folderId: 0,
        selectedMedia: [],
        folderBreadcrumb: [
          {id: 0, name: 'Home'},
        ],
        filesAreDeleting: false,
        canEditFolders: true,
        dropzoneOptions: {
          autoProcessQueue: false,
          url: route('api.media.store').template + '?parent_id=' + (this.$route.query.folder_id ? this.$route.query.folder_id : 0),
          thumbnailWidth: 100,
          maxFilesize: 100,
          headers: {"Authorization": 'Bearer ' + window.MonCMS.user_token},
          dictDefaultMessage: 'Kéo thả file hoặc Click để tải file lên (tỷ lệ ảnh 375:140)',
          init: function () {
            this.on("error", function (file, response) {

              $('.dz-error-message').html(response.errors.file[0]);

            });

          },

        },
        cropFile: null,
        aspectRatio: 3/2
      };
    },
    methods: {
      queryServer(customProperties) {
        const properties = {
          page: this.meta.current_page,
          per_page: this.meta.per_page,
          order_by: this.order_meta.order_by,
          order: this.order_meta.order,
          search: this.searchQuery,
          folder_id: this.folderId,
        };
        axios.get(route('api.media.all-vue', _.merge(properties, customProperties)))
        .then((response) => {
          this.tableIsLoading = false;
          this.data = response.data.data;
          this.meta = response.data.meta;
          this.links = response.data.links;

          this.order_meta.order_by = properties.order_by;
          this.order_meta.order = properties.order;
        });
      },
      fetchMediaData() {
        this.tableIsLoading = true;
        if (this.$route.query.folder_id !== undefined) {
          this.queryServer({folder_id: this.$route.query.folder_id});
          this.fetchFolderBreadcrumb(this.$route.query.folder_id);
          return;
        }
        this.queryServer();
      },
      fetchFolderBreadcrumb(folderId) {
        this.$refs.myVueDropzone.dropzone.options.url = route('api.media.store').template + '?parent_id=' + folderId

        if (folderId === 0) {
          this.folderBreadcrumb = [
            {id: 0, name: 'Home'},
          ];
          return;
        }
        axios.get(route('api.media.folders.breadcrumb', {folder: folderId}))
        .then((response) => {
          this.folderBreadcrumb = response.data;
        });
      },
      handleSizeChange(event) {
        console.log(`per page :${event}`);
        this.tableIsLoading = true;
        this.queryServer({per_page: event});
      },
      handleCurrentChange(event) {
        console.log(`current page :${event}`);
        this.tableIsLoading = true;
        this.queryServer({page: event});
      },
      handleSortChange(event) {
        console.log('sorting', event);
        this.tableIsLoading = true;
        this.queryServer({order_by: event.prop, order: event.order});
      },
      performSearch: _.debounce(function (query) {
        console.log(`searching:${query.target.value}`);
        this.tableIsLoading = true;
        this.queryServer({search: query.target.value});
      }, 300),
      enterFolder(scope) {
        this.tableIsLoading = true;
        this.queryServer({folder_id: scope.row.id});
        this.folderId = scope.row.id;
        this.$router.push({query: {folder_id: scope.row.id}});
        this.fetchFolderBreadcrumb(scope.row.id);
      },
      insertMedia(scope) {
        this.$events.emit(this.eventName, scope.row);
        if (this.hideAfterInsert) {
          scope.row.hideAfterInsert = true;
        }
      },
      handleSelectionChange(selectedMedia) {
        this.selectedMedia = selectedMedia;
      },
      showEditFolder(scope) {
        this.$events.emit('editFolderWasClicked', scope);
      },
      showMoveMedia() {
        this.$events.emit('moveMediaWasClicked', this.selectedMedia);
      },
      changeRoot(folderId) {
        this.tableIsLoading = true;
        this.queryServer({folder_id: folderId});
        this.folderId = folderId;
        if (folderId === 0) {
          this.$router.push({query: {}});
        } else {
          this.$router.push({query: {folder_id: folderId}});
        }

        this.fetchFolderBreadcrumb(folderId);
      },
      batchDelete() {
        this.$confirm(this.$t('mon.modal.confirmation-message'), this.$t('mon.modal.title'), {
          confirmButtonText: this.$t('mon.button.delete'),
          cancelButtonText: this.$t('mon.button.cancel'),
          type: 'warning',
        })
        .then(() => {
          this.filesAreDeleting = true;
          axios.post(route('api.media.batch-destroy'), {
            files: this.selectedMedia,
          })
          .then((response) => {
            this.$message({
              type: 'success',
              message: response.data.message,
            });
            this.filesAreDeleting = false;
            this.$refs.mediaTable.clearSelection();
            this.queryServer();
          });
        })
        .catch(() => {
          this.$message({
            type: 'info',
            message: this.$t('mon.delete cancelled'),
          });
        });
      },
      goToEdit(scope) {
        this.$router.push({name: 'admin.media.edit', params: {mediaId: scope.row.id}});
      },
      vdropzoneSuccess(file, response) {
        console.log(response);
        this.$events.emit('fileWasUploaded', {data: response});
        this.$refs.myVueDropzone.removeAllFiles();

      },
      vdropzoneFileAdded(file) {
        console.log(file)
        const reader = new FileReader();
        const that = this
        reader.onload = (event) => {
          this.cropFile = event.target.result;
          // rebuild cropperjs with the updated source
          that.$refs.cropper.replace(event.target.result);

        };
        reader.readAsDataURL(file);
        this.cropVisible = true
        setTimeout(function () {
          $(".v-modal").remove();
        }, 300);


      },
      getAspectRatio() {
        if (this.cropForm.rotationW && this.cropForm.rotationH) {
          return this.cropForm.rotationW / this.cropForm.rotationH
        }
        return 3 / 2
      },
      changeAspectRatio() {
        this.aspectRatio = this.getAspectRatio()
        this.$refs.cropper.setAspectRatio(this.aspectRatio)
      },
      cropImage() {
        const croppedData = this.$refs.cropper.getCroppedCanvas().toDataURL();
        let queuedFiles = this.$refs.myVueDropzone.getQueuedFiles();
        console.log(queuedFiles)
        if (queuedFiles[0])
        {
          let file = queuedFiles[0]
          file.dataURL = croppedData
          this.$refs.myVueDropzone.removeAllFiles();
          let fileCropped = this.dataURItoBlob(croppedData);
          fileCropped.name = file.name;
          this.$refs.myVueDropzone.addFile(fileCropped)
          this.$refs.myVueDropzone.processQueue()

        }
        this.cropVisible = false
        //

      },
      noCropImage() {
        this.$refs.myVueDropzone.processQueue()
        this.cropVisible = false
      },
      cancelImage() {
        this.cropVisible = false
        this.$refs.myVueDropzone.removeAllFiles();
      },
      dataURItoBlob(dataURI) {
        var byteString = atob(dataURI.split(',')[1]);
        var ab = new ArrayBuffer(byteString.length);
        var ia = new Uint8Array(ab);
        for (var i = 0; i < byteString.length; i++) {
          ia[i] = byteString.charCodeAt(i);
        }
        return new Blob([ab], { type: 'image/jpeg' });
      }

    },
    computed: {
      uploadUrl() {
        return route('api.media.store').template;
      },
      requestHeaders() {
        const userApiToken = document.head.querySelector('meta[name="user-api-token"]');
        return {
          Authorization: `Bearer ${userApiToken.content}`,
        };
      },
      aspectRatioT() {
        if (this.cropForm.rotationW && this.cropForm.rotationH) {
          return this.cropForm.rotationW / this.cropForm.rotationH
        }
        return 3 / 2
      }
    },
    mounted() {
      if (window.MonCMS.filesystem === 's3') {
        this.canEditFolders = false;
      }
      this.fetchMediaData();
      this.$events.listen('fileWasUploaded', (eventData) => {
        this.tableIsLoading = true;
        this.queryServer();
      });
      this.$events.listen('folderWasCreated', (eventData) => {
        this.tableIsLoading = true;
        this.queryServer();
      });
      this.$events.listen('folderWasUpdated', (eventData) => {
        this.tableIsLoading = true;
        this.queryServer();
      });
      this.$events.listen('mediaWasMoved', (eventData) => {
        this.tableIsLoading = true;
        this.queryServer();
      });
    },
  };
</script>
<style>
    .cropper-area {
        width: 100%;
    }

    .actions {
        margin-top: 1rem;
    }

    .actions a {
        display: inline-block;
        padding: 5px 15px;
        background: #0062CC;
        color: white;
        text-decoration: none;
        border-radius: 3px;
        margin-right: 1rem;
        margin-bottom: 1rem;
    }

    textarea {
        width: 100%;
        height: 100px;
    }

    .preview-area {
        width: 307px;
    }

    .preview-area p {
        font-size: 1.25rem;
        margin: 0;
        margin-bottom: 1rem;
    }

    .preview-area p:last-of-type {
        margin-top: 1rem;
    }

    .preview {
        width: 100%;
        height: calc(372px * (9 / 16));
        overflow: hidden;
    }

    .crop-placeholder {
        width: 100%;
        height: 200px;
        background: #ccc;
    }

    .cropped-image img {
        max-width: 100%;
    }
    .crop-dialog .el-dialog__header {
        background: #E6A23C;
    }
    .crop-dialog .el-dialog__title {
        color:#ffffff;
    }
</style>

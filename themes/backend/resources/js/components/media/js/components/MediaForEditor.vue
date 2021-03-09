<template>
    <div class="row" style="margin-top: -35px;">
        <div class="col-md-12">
            <div class="sc-table">
                 <div class="el-row">
                    <div class="title">
                        <h4 v-if="singleModal">{{ $t('media.label.choose_file') }}</h4>
                        <h3 v-if="! singleModal">{{ $t('media.label.media') }}</h3>
                        <div class="media-breadcrumb">
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
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="tool-bar el-row" style="padding-bottom: 20px;">

                            <div class="search el-col el-col-5">
                                <el-input prefix-icon="el-icon-search" @keyup.native="performSearch" v-model="searchQuery">
                                </el-input>
                            </div>
                        </div>
                        <el-row>
                            <el-col :span="24">
                                <vue-dropzone ref="myVueDropzone" id="dropzone" :options="dropzoneOptions" @vdropzone-success="vdropzoneSuccess"></vue-dropzone>

                            </el-col>
                        </el-row>
                        <el-row>
                            <el-col :span="24">
                                <el-breadcrumb separator="/" style="margin-bottom: 20px;">
                                    <el-breadcrumb-item v-for="(folder, index) in folderBreadcrumb" @click.native="changeRoot(folder.id, index)" :key="folder.id">
                                        {{ folder.name }}
                                    </el-breadcrumb-item>
                                </el-breadcrumb>
                            </el-col>
                        </el-row>

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

                            <el-table-column prop="filename" :label="$t('media.label.filename')" sortable="custom">
                                <template slot-scope="scope">
                                    <strong v-if="scope.row.is_folder" style="cursor: pointer;" @click="enterFolder(scope)">
                                        {{ scope.row.filename }}
                                    </strong>
                                    <span v-else>
                                        <a href="#"
                                           @click.prevent="goToEdit(scope)">{{ scope.row.filename }}</a>
                                    </span>
                                </template>
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
                                        <i class="el-icon-success" v-if="scope.row.hideAfterInsert" style="color: green;font-size: 24px;"></i>
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
                </div>
            </div>
        </div>

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

    export default {
        components: {
            'new-folder': NewFolder,
            'upload-button': UploadButton,
            'rename-folder': RenameFolder,
            'move-dialog': MoveMediaDialog,
            vueDropzone: vue2Dropzone
        },
        props: {
            singleModal: { type: Boolean },
            eventName: {},
            hideAfterInsert: { type: Boolean },
        },
        data() {
            return {
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
                    { id: 0, name: 'Home' },
                ],
                filesAreDeleting: false,
                canEditFolders: true,
                dropzoneOptions: {
                    url: route('api.media.store').template + '?parent_id=' + (this.$route.query.folder_id? this.$route.query.folder_id: 0),
                    thumbnailWidth: 100,
                    maxFilesize: 100,
                    headers: { "Authorization": 'Bearer ' + window.MonCMS.user_token }
                }
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
                    this.queryServer({ folder_id: this.$route.query.folder_id });
                    this.fetchFolderBreadcrumb(this.$route.query.folder_id);
                    return;
                }
                this.queryServer();
            },
            fetchFolderBreadcrumb(folderId) {
                this.$refs.myVueDropzone.dropzone.options.url = route('api.media.store').template + '?parent_id=' + folderId

                if (folderId === 0) {
                    this.folderBreadcrumb = [
                        { id: 0, name: 'Home' },
                    ];
                    return;
                }
                axios.get(route('api.media.folders.breadcrumb', { folder: folderId }))
                    .then((response) => {
                        this.folderBreadcrumb = response.data;
                    });
            },
            handleSizeChange(event) {
                console.log(`per page :${event}`);
                this.tableIsLoading = true;
                this.queryServer({ per_page: event });
            },
            handleCurrentChange(event) {
                console.log(`current page :${event}`);
                this.tableIsLoading = true;
                this.queryServer({ page: event });
            },
            handleSortChange(event) {
                console.log('sorting', event);
                this.tableIsLoading = true;
                this.queryServer({ order_by: event.prop, order: event.order });
            },
            performSearch: _.debounce(function (query) {
                console.log(`searching:${query.target.value}`);
                this.tableIsLoading = true;
                this.queryServer({ search: query.target.value });
            }, 300),
            enterFolder(scope) {
                this.tableIsLoading = true;
                this.queryServer({ folder_id: scope.row.id });
                this.folderId = scope.row.id;
                this.$router.push({ query: { folder_id: scope.row.id } });
                this.fetchFolderBreadcrumb(scope.row.id);
            },
            insertMedia(scope) {

                this.$emit(this.eventName, scope.row);
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
                this.queryServer({ folder_id: folderId });
                this.folderId = folderId;
                if (folderId === 0) {
                    this.$router.push({ query: {} });
                } else {
                    this.$router.push({ query: { folder_id: folderId } });
                }

                this.fetchFolderBreadcrumb(folderId);
            },
            batchDelete() {
                this.$confirm(this.$t('mon.modal.confirmation-message'), this.$t('mon.modal.title'), {
                    confirmButtonText: this.$t('core.button.delete'),
                    cancelButtonText: this.$t('core.button.cancel'),
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
                            message: this.$t('core.delete cancelled'),
                        });
                    });
            },
            goToEdit(scope) {
                this.$router.push({ name: 'admin.media.edit', params: { mediaId: scope.row.id } });
            },
            vdropzoneSuccess(file,response) {
                console.log(response);
                this.$events.emit('fileWasUploaded', {data:response});
                this.$refs.myVueDropzone.removeAllFiles();

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
        },
        mounted() {
            if (window.MonCMS.filesystem === 's3') {
                this.canEditFolders = false;
            }
            this.fetchMediaData();
            this.$events.listen('fileWasUploaded', (eventData) => {
                this.tableIsLoading = true;
                this.queryServer( );
            });
            this.$events.listen('folderWasCreated', (eventData) => {
                this.tableIsLoading = true;
                this.queryServer( );
            });
            this.$events.listen('folderWasUpdated', (eventData) => {
                this.tableIsLoading = true;
                this.queryServer( );
            });
            this.$events.listen('mediaWasMoved', (eventData) => {
                this.tableIsLoading = true;
                this.queryServer( );
            });
        },
    };
</script>

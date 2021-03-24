<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-12">
                        <el-breadcrumb separator="/">
                            <el-breadcrumb-item>
                                <a href="/admin">{{ $t('mon.breadcrumb.home') }}</a>
                            </el-breadcrumb-item>
                            <el-breadcrumb-item :to="{name: 'admin.news.index'}">{{ $t('news.label.news') }}
                            </el-breadcrumb-item>
                            <el-breadcrumb-item> {{ $t(pageTitle) }}
                            </el-breadcrumb-item>
                        </el-breadcrumb>
                    </div>

                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header ui-sortable-handle" style="cursor: move;">
                                <h3 class="card-title">
                                    {{ $t(pageTitle) }}<span v-if="modelForm.name">: &nbsp{{modelForm.name}}</span>
                                </h3>

                            </div><!-- /.card-header -->
                            <div class="card-body" style="padding-top:0px">

                                <el-form ref="form"
                                         :model="modelForm"
                                         label-width="200px"
                                         label-position="top"
                                         v-loading.body="loading"
                                >
                                    <div class="row">
                                        <div class="col-md-9" style="padding-top:10px;padding-right:30px">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <el-form-item :label="$t('news.label.content')"
                                                                  :class="{'el-form-item is-error': form.errors.has(  'content') }">
                                                        <div slot="label">
                                                            <label class="el-form-item__label">{{$t('news.label.content')}}</label>
                                                        </div>
                                                        <tinymce v-model="modelForm.content" :height="800"></tinymce>
                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('content')"
                                                             v-text="form.errors.first('content')"></div>
                                                    </el-form-item>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="  col-md-3" style="padding-top:10px;border-left: 1px solid rgba(0,0,0,.125);">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <el-form-item :label="$t('news.label.title')"
                                                                  :class="{'el-form-item is-error': form.errors.has('title') }">

                                                        <el-input v-model="modelForm.title"></el-input>
                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('title')"
                                                             v-text="form.errors.first('title')"></div>
                                                    </el-form-item>
                                                </div>
                                                <div class="col-md-12">
                                                    <el-form-item :label="$t('news.label.slug')"
                                                                  :class="{'el-form-item is-error': form.errors.has('slug') }">

                                                        <el-input v-model="modelForm.slug"></el-input>
                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('slug')"
                                                             v-text="form.errors.first('slug')"></div>
                                                    </el-form-item>
                                                </div>


                                                <div class="col-md-12">
                                                    <el-form-item :label="$t('news.label.description')"
                                                                  :class="{'el-form-item is-error': form.errors.has('description') }">

                                                        <el-input v-model="modelForm.description"></el-input>
                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('description')"
                                                             v-text="form.errors.first('description')"></div>
                                                    </el-form-item>
                                                </div>
                                                <div class="col-md-12">
                                                    <el-form-item :label="$t('news.label.author')"
                                                                  :class="{'el-form-item is-error': form.errors.has('author') }">

                                                        <el-input v-model="modelForm.author"></el-input>
                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('author')"
                                                             v-text="form.errors.first('author')"></div>
                                                    </el-form-item>
                                                </div>

                                                <div class="col-md-12">
                                                    <el-form-item :label="$t('news.label.category_id')"
                                                                  :class="{'el-form-item is-error': form.errors.has(  'category_id') }">

                                                        <el-select v-model="modelForm.category_id" :placeholder="$t('news.label.category_id')"
                                                                   filterable style="width: 100% !important">
                                                            <el-option
                                                                v-for="item in categoryArr"
                                                                :key="'status'+ item.id"
                                                                :label="item.title"
                                                                :value="item.id">
                                                            </el-option>

                                                        </el-select>
                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('category_id')"
                                                             v-text="form.errors.first('category_id')"></div>
                                                    </el-form-item>
                                                </div>
                                                <div class="col-md-12">
                                                    <el-form-item :label="$t('news.label.tags')"
                                                                  :class="{'el-form-item is-error': form.errors.has('tags') }">

                                                        <el-select
                                                            v-model="modelForm.tags"
                                                            multiple
                                                            filterable
                                                            allow-create
                                                            default-first-option
                                                            placeholder="Nhập tags">

                                                        </el-select>
                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('tags')"
                                                             v-text="form.errors.first('tags')"></div>
                                                    </el-form-item>
                                                </div>
                                                <div class="col-md-12">
                                                    <el-form-item :label="$t('news.label.status')"
                                                                  :class="{'el-form-item is-error': form.errors.has(  'status') }">

                                                        <el-select v-model="modelForm.status" :placeholder="$t('news.label.status')"
                                                                   filterable style="width: 100% !important">
                                                            <el-option
                                                                v-for="item in listStatus"
                                                                :key="'status'+ item.value"
                                                                :label="item.label"
                                                                :value="item.value">
                                                            </el-option>

                                                        </el-select>
                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('status')"
                                                             v-text="form.errors.first('status')"></div>
                                                    </el-form-item>
                                                </div>


                                                <div class="col-md-12">
                                                    <single-media zone="thumbnail"
                                                                  @singleFileSelected="selectSingleFile($event, 'modelForm')"
                                                                  label="Ảnh đại diện"
                                                                  entity="Modules\Mon\Entities\news"
                                                                  :entity-id="$route.params.newsId"></single-media>
                                                    <div class="el-form-item__error"  style="margin-left:208px"
                                                         v-if="form.errors.has('medias_single.thumbnail')"
                                                         v-text="form.errors.first('medias_single.thumbnail')"></div>
                                                </div>
                                                <div class="col-md-12">
                                                    <el-form-item :label="$t('news.label.meta_title')"
                                                                  :class="{'el-form-item is-error': form.errors.has('meta_title') }">

                                                        <el-input v-model="modelForm.meta_title"></el-input>
                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('meta_title')"
                                                             v-text="form.errors.first('meta_title')"></div>
                                                    </el-form-item>
                                                </div>
                                                <div class="col-md-12">
                                                    <el-form-item :label="$t('news.label.meta_keywords')"
                                                                  :class="{'el-form-item is-error': form.errors.has('meta_keywords') }">

                                                        <el-input v-model="modelForm.meta_keywords"></el-input>
                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('meta_keywords')"
                                                             v-text="form.errors.first('meta_keywords')"></div>
                                                    </el-form-item>
                                                </div>
                                                <div class="col-md-12">
                                                    <el-form-item :label="$t('news.label.meta_description')"
                                                                  :class="{'el-form-item is-error': form.errors.has('meta_description') }">

                                                        <el-input v-model="modelForm.meta_description"></el-input>
                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('meta_description')"
                                                             v-text="form.errors.first('meta_description')"></div>
                                                    </el-form-item>
                                                </div>
                                            </div>
                                        </div>


                                    </div>


                                </el-form>
                            </div><!-- /.card-body -->
                            <div class="card-footer d-flex justify-content-end">
                                <el-button type="primary" @click="onSubmit()" size="small" :loading="loading"
                                           class="btn btn-flat ">
                                    {{ $t('mon.button.save') }}
                                </el-button>
                                <el-button class="btn btn-flat " size="small" @click="onCancel()">{{
                                    $t('mon.button.cancel') }}
                                </el-button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

    </div>


</template>

<script>
    import axios from 'axios';
    import moment from 'moment';
    import Form from 'form-backend-validation';
    import Tinymce from '../utils/Tinymce';
    import SingleFileSelector from '../../mixins/SingleFileSelector.js';

    export default {
        mixins: [SingleFileSelector],
        components: {
            Tinymce,
        },
        props: {
            pageTitle: {default: null, String},
        },
        data() {
            return {
                form: new Form(),
                loading: false,
                modelForm:{
                    title: '',
                    description: '',
                    content: '',
                    category_id: '',
                    status: 'publish',
                    slug: '',
                    author: '',
                    flag_hot: '',
                    flag_featured: '',
                    flag_most_read: '',
                    flag_video: '',
                    meta_keywords: '',
                    meta_title: '',
                    meta_description: '',
                    tags: []

                },
                locales: window.MonCMS.locales,
                categoryArr: [],
                listStatus: [

                    {
                        value: 'publish',
                        label: 'Hiển thị'
                    },
                    {
                        value: 'hide',
                        label: 'Ẩn'
                    }
                ],
                listTag: []


            };
        },
        methods: {
            onSubmit() {
                this.form = new Form(_.merge(this.modelForm, { }));
                this.loading = true;

                this.form.post(this.getRoute())
                    .then((response) => {
                        this.loading = false;
                        this.$message({
                            type: 'success',
                            message: response.message,
                        });
                        this.$router.push({name: 'admin.news.index'});
                    })
                    .catch((error) => {

                        this.loading = false;
                        this.$notify.error({
                            title: this.$t('mon.error.Title'),
                            message: this.getSubmitError(this.form.errors),
                        });
                    });
            },
            onCancel() {

                this.$confirm(this.$t('mon.cancel.Are you sure to cancel?'), {
                    confirmButtonText: this.$t('mon.cancel.Yes'),
                    cancelButtonText: this.$t('mon.cancel.No'),
                    type: 'warning'
                }).then(() => {
                    this.$router.push({name: 'admin.news.index'});
                }).catch(() => {

                });


            },

            fetchData() {
                this.loading = true;
                let locale = this.$route.params.locale? this.$route.params.locale: 'en';
                axios.get(route('api.news.find', {news: this.$route.params.newsId }))
                    .then((response) => {
                        this.loading = false;
                        this.modelForm = response.data.data;

                    });
            },
            fetchTags() {
                this.loading = true;

                axios.get(route('api.tags.index' ))
                    .then((response) => {
                        this.loading = false;
                        this.listTag = response.data;

                    });
            },

            getRoute() {
                if (this.$route.params.newsId !== undefined) {
                    return route('api.news.update', {news: this.$route.params.newsId});
                }
                return route('api.news.store');
            },

            fetchCategory() {
                const properties = {
                    page: 0,
                    per_page: 1000,

                };

                axios.get(route('api.category.index', _.merge(properties, {})))
                    .then((response) => {

                        this.categoryArr = response.data.data;

                    });
            },
        },
        mounted() {
            this.fetchCategory();
            if (this.$route.params.newsId !== undefined) {
                this.fetchData();
            }

        },
        computed: {

        },

    }
</script>

<style scoped>
    .block-status {
        padding: 6px 20px;
        margin-left: 20px;
    }

    .btn-default {
        background-color: #f4f4f4;
        color: #444;
        border-color: #ddd;
        color: white;
    }

    .btn-default:hover,
    .btn-default:active,
    .btn-default.hover {
        background-color: #e7e7e7;
        color: white;
    }

    .btn-primary {
        background-color: #3c8dbc;
        border-color: #367fa9;
        color: white;
    }

    .btn-primary:hover,
    .btn-primary:active,
    .btn-primary.hover {
        background-color: #367fa9;
        color: white;
    }

    .btn-success {
        background-color: #00a65a;
        border-color: #008d4c;
        color: white;
    }

    .btn-success:hover,
    .btn-success:active,
    .btn-success.hover {
        background-color: #008d4c;
        color: white;
    }

    .btn-info {
        background-color: #00c0ef;
        border-color: #00acd6;
        color: white;
    }

    .btn-info:hover,
    .btn-info:active,
    .btn-info.hover {
        background-color: #00acd6;
        color: white;
    }

    .btn-danger {
        background-color: #dd4b39;
        border-color: #d73925;
        color: white;
    }

    .btn-danger:hover,
    .btn-danger:active,
    .btn-danger.hover {
        background-color: #d73925;
        color: white;
    }

    .btn-warning {
        background-color: #f39c12;
        border-color: #e08e0b;
        color: white;
    }

    .btn-warning:hover,
    .btn-warning:active,
    .btn-warning.hover {
        background-color: #e08e0b;
        color: white;
    }

    .btn-outline {
        border: 1px solid #fff;
        background: transparent;
        color: #fff;
    }

    .btn-outline:hover,
    .btn-outline:focus,
    .btn-outline:active {
        color: rgba(255, 255, 255, 0.7);
        border-color: rgba(255, 255, 255, 0.7);
        color: white;
    }
</style>

<template>
    <div>
        <div class="content-header">
            <h1>
                {{ $t('media.title.edit media') }}
            </h1>
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>
                    <a href="/backend">Home</a>
                </el-breadcrumb-item>
                <el-breadcrumb-item :to="{name: 'admin.media.index', query: {folder_id: media.folder_id}}">{{ $t('media.breadcrumb.media') }}
                </el-breadcrumb-item>
                <el-breadcrumb-item :to="{name: 'admin.media.edit'}">{{ $t('media.title.edit media') }}
                </el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <el-form ref="form" :model="media" label-width="120px" label-position="top"
                 v-loading.body="loading"
                 @keydown="form.errors.clear($event.target.name);">
            <div class="row">
                <div class="col-md-8">
                    <div class="box box-primary">
                        <div class="box-body">
                            <el-form-item :label="$t('media.label.title')"
                                          :class="{'el-form-item is-error': form.errors.has(  'title') }">
                                <el-input v-model="media.title"></el-input>
                                <div class="el-form-item__error" v-if="form.errors.has( 'title')"
                                     v-text="form.errors.first('title')"></div>
                            </el-form-item>

                            <el-form-item :label="$t('media.label.description')"
                                          :class="{'el-form-item is-error': form.errors.has( 'description') }">
                                <el-input type="textarea" v-model="media.description"></el-input>
                                <div class="el-form-item__error" v-if="form.errors.has( 'description')"
                                     v-text="form.errors.first( 'description')"></div>
                            </el-form-item>

                            
                            <el-form-item>
                                <el-button type="primary" @click="onSubmit()" :loading="loading">
                                    {{ $t('mon.save') }}
                                </el-button>
                                <el-button @click="onCancel()">{{ $t('mon.button.cancel') }}
                                </el-button>
                            </el-form-item>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <img :src="media.path" alt="" v-if="media.is_image" style="width: 100%;"/>
                    <i class="fa fa-file" style="font-size: 50px;" v-else></i>
                </div>
            </div>
        </el-form>

        <div class="row">
            <div class="col-md-12">
                <h3>Thumbnails</h3>
                <ul class="list-unstyled">
                    <li v-for="thumbnail in media.thumbnails" :key="thumbnail.name" style="float:left; margin-right: 10px">
                        <img :src="thumbnail.path" alt=""/>
                        <p class="text-muted" style="text-align: center">{{ thumbnail.name }} ({{ thumbnail.size }})</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    import Form from 'form-backend-validation';
    import axios from 'axios';

    export default {
        props: {

        },
        data() {
            return {
                media: {
                    description: '',
                    title: '',
                },
                form: new Form(),
                loading: false,

            };
        },
        methods: {
            fetchMedia() {
                this.loading = true;
                axios.get(route('api.media.find', { file: this.$route.params.mediaId }))
                    .then((response) => {
                        this.loading = false;
                        this.media = response.data.data;
                     });
            },
            onSubmit() {
                this.form = new Form(this.media);
                this.loading = true;

                this.form.put(route('api.media.update', { file: this.media.id }))
                    .then((response) => {
                        this.loading = false;
                        this.$message({
                            type: 'success',
                            message: response.message,
                        });
                        this.$router.push({ name: 'admin.media.index', query: { folder_id: this.media.folder_id } });
                    })
                    .catch((error) => {
                        console.log(error);
                        this.loading = false;
                        this.$notify.error({
                            title: 'Error',
                            message: 'There are some errors in the form.',
                        });
                    });
            },
            onCancel() {
                if (this.media.folder_id == 0) {
                    this.$router.push({ name: 'admin.media.index', query: {} });
                    return;
                }
                this.$router.push({ name: 'admin.media.index', query: { folder_id: this.media.folder_id } });
            },
        },
        mounted() {
            if (this.$route.params.mediaId !== undefined) {
                this.fetchMedia();
            }
        },
    };
</script>
<style>
    .el-select{
        display: block;
    }
</style>

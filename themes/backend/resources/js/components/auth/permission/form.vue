<template>
    <div  >
           <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-12">
                      <el-breadcrumb separator="/">
                <el-breadcrumb-item>
                    <a href="/admin">{{ $t('mon.breadcrumb.home') }}</a>
                </el-breadcrumb-item>
                <el-breadcrumb-item :to="{name: 'admin.permissions.index'}">{{ $t('permission.label.permissions') }}
                </el-breadcrumb-item>
                <el-breadcrumb-item >{{ $t('permission.label.create_permission') }}
                </el-breadcrumb-item>
            </el-breadcrumb>

                    </div>

                </div>
            </div>
        </div>


    

          <section class="content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header ui-sortable-handle" style="cursor: move;">
                                <h3 class="card-title">
                                    {{ $t(pageTitle) }}
                                </h3>

                            </div><!-- /.card-header -->
                            <div class="card-body">
                                           <el-form ref="form" :model="modelForm" label-width="120px" label-position="top"
                                 v-loading.body="loading"
                        >

                            <el-form-item :label="$t('permission.label.name')"
                                          :class="{'el-form-item is-error': form.errors.has( 'name') }">
                                <el-input v-model="modelForm.name" @focus="form.errors.clear('name')"></el-input>
                                <div class="el-form-item__error"
                                     v-if="form.errors.has('name')"
                                     v-text="form.errors.first('name')"></div>
                            </el-form-item>

                            <el-form-item :label="$t('permission.label.group')"
                                          :class="{'el-form-item is-error': form.errors.has( 'group') }">
                                <el-input v-model="modelForm.group"></el-input>
                                <div class="el-form-item__error"
                                     v-if="form.errors.has('group')"
                                     v-text="form.errors.first('group')"></div>
                            </el-form-item>
                            <el-form-item :label="$t('permission.label.title')"
                                          :class="{'el-form-item is-error': form.errors.has( 'title') }">
                                <el-input v-model="modelForm.title"></el-input>
                                <div class="el-form-item__error"
                                     v-if="form.errors.has('title')"
                                     v-text="form.errors.first('title')"></div>
                            </el-form-item>

                        </el-form>
                       
            
                            </div><!-- /.card-body -->
                            <div class="card-footer d-flex justify-content-end ">
                                 <el-button type="primary" @click="onSubmit()"  size="small" :loading="loading"  class="btn btn-flat ">
                            {{ $t('mon.button.save') }}
                        </el-button>

                        <el-button class="btn btn-flat pull-right"  size="small" @click="onCancel()">{{ $t('mon.button.cancel') }}
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
    import Form from 'form-backend-validation';
    export default {
        props: {
            locales: {default: null},
            pageTitle: {default: null, String},
        },
        data() {
            return {
                form: new Form(),
                loading: false,
                modelForm: {
                    name: '',
                    group: '',
                    title: '',
                    guard_name:'web'

                },
            };
        },
        methods: {
            onSubmit() {
                this.form = new Form(_.merge(this.modelForm, {}));
                this.loading = true;

                this.form.post(this.getRoute())
                    .then((response) => {
                        this.loading = false;
                        this.$message({
                            type: 'success',
                            message: response.message,
                        });
                        this.$router.push({name: 'admin.permissions.index'});
                    })
                    .catch((error) => {

                        this.loading = false;
                        this.$notify.error({
                            title: 'Error',
                            message:  this.getSubmitError(this.form.errors),
                        });
                    });
            },
            onCancel() {

                this.$confirm(this.$t('mon.cancel.Are you sure to cancel?'), {
                    confirmButtonText: this.$t('mon.cancel.Yes'),
                    cancelButtonText: this.$t('mon.cancel.No'),
                    type: 'warning'
                }).then(() => {
                    this.$router.push({name: 'admin.permissions.index'});
                }).catch(() => {

                });


            },


            fetchData() {
                this.loading = true;
                axios.get(route('api.permissions.find', {permission: this.$route.params.permissionId}))
                    .then((response) => {
                        this.loading = false;
                        this.modelForm = response.data.data;

                    });
            },

            getRoute() {
                if (this.$route.params.permissionId !== undefined) {
                    return route('api.permissions.update', {permission: this.$route.params.permissionId});
                }
                return route('api.permissions.store');
            },


        },
        mounted() {

            if (this.$route.params.permissionId !== undefined) {
                this.fetchData();
            }

        },
        computed: {}
    }
</script>

<style scoped>

</style>

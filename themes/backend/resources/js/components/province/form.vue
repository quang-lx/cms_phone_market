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
                            <el-breadcrumb-item :to="{name: 'admin.province.index'}">{{ $t('province.label.province') }}
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
                                    {{ $t(pageTitle) }}<span v-if="modelForm.title">: &nbsp{{modelForm.title}}</span>
                                </h3>

                            </div><!-- /.card-header -->
                            <div class="card-body">

                                <el-form ref="form"
                                         :model="modelForm"
                                         label-width="200px"
                                         label-position="left"
                                         v-loading.body="loading"
                                >
                                    <div class="row">
                                        <div class="col-md-12">


                                            <div class="row">
                                                <div class="col-md-10">

                                                    <el-form-item :label="$t('province.label.name')"
                                                                  :class="{'el-form-item is-error': form.errors.has('name') }">
                                                        <el-input v-model="modelForm.name"></el-input>
                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('name')"
                                                             v-text="form.errors.first('name')"></div>
                                                    </el-form-item>


                                                </div>
                                                <div class="col-md-10">

                                                    <el-form-item :label="$t('province.label.code')"
                                                                  :class="{'el-form-item is-error': form.errors.has('code') }">
                                                        <el-input v-model="modelForm.code"></el-input>
                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('code')"
                                                             v-text="form.errors.first('code')"></div>
                                                    </el-form-item>


                                                </div>
                                                <div class="col-md-10">

                                                    <el-form-item :label="$t('province.label.type')"
                                                                  :class="{'el-form-item is-error': form.errors.has(  'type') }">


                                                        <el-radio-group v-model="modelForm.type" size="mini">
                                                            <el-radio v-for="item in listType"
                                                                      :key="'type-'+ item.value"

                                                                      :label="item.value"  >{{item.label}}
                                                            </el-radio>
                                                        </el-radio-group>
                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('type')"
                                                             v-text="form.errors.first('type')"></div>
                                                    </el-form-item>


                                                </div>
                                                <div class="col-md-10">

                                                    <el-form-item :label="$t('province.label.phone_code')"
                                                                  :class="{'el-form-item is-error': form.errors.has('phone_code') }">
                                                        <el-input v-model="modelForm.phone_code"></el-input>
                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('phone_code')"
                                                             v-text="form.errors.first('phone_code')"></div>
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
    import Form from 'form-backend-validation';
    import SingleFileSelector from '../../mixins/SingleFileSelector.js';

    export default {
        props: {
            locales: {default: null},
            pageTitle: {default: null, String},
        },
        mixins: [SingleFileSelector],
        data() {
            return {
                form: new Form(),
                loading: false,
                modelForm: {
                    name: '',
                    code: '',
                    type: '',
                    phone_code: '',

                },
                listType: [

                    {
                        value: 'Tỉnh',
                        label: 'Tỉnh'
                    },
                    {
                        value: 'Thành Phố',
                        label: 'Thành Phố'
                    }
                ],

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
                      this.$router.push({name: 'admin.province.index'});
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
                    this.$router.push({name: 'admin.province.index'});
                }).catch(() => {

                });


            },


            fetchData() {


                let routeUri = '';
                if (this.$route.params.provinceId !== undefined) {
                    this.loading = true;
                    routeUri = route('api.province.find', {province: this.$route.params.provinceId});
                    axios.get(routeUri)
                      .then((response) => {
                          this.loading = false;
                          this.modelForm = response.data.data;
                          this.modelForm.is_new = false;
                      });
                } else {
                    this.modelForm.is_new = true;
                }
            },


            getRoute() {
                if (this.$route.params.provinceId !== undefined) {
                    return route('api.province.update', {province: this.$route.params.provinceId});
                }
                return route('api.province.store');
            },



        },
        mounted() {
            this.fetchData();

        },
        computed: {}
    }
</script>

<style scoped>

</style>

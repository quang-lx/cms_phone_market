<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <el-breadcrumb separator="/">
                                    <el-breadcrumb-item>
                                        <a href="/shop-admin">{{ $t('mon.breadcrumb.home') }}</a>
                                    </el-breadcrumb-item>
                                    <el-breadcrumb-item :to="{name: 'shop.user.index'}">{{ $t('user.label.manager') }}
                                    </el-breadcrumb-item>
                                    <el-breadcrumb-item> {{ $t(pageTitle) }}
                                    </el-breadcrumb-item>
                                </el-breadcrumb>

                            </div>

                        </div>
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
                                <div class="card-tools">
                                    <ul class="nav nav-pills ml-auto">
                                        <li class="nav-item">
                                            <el-button v-if="modelForm.id" type="danger" class="btn btn-flat btn-danger"
                                                       @click="changePassDialogVisible = true">
                                                {{$t('user.label.change_password')}}
                                            </el-button>
                                        </li>

                                    </ul>
                                </div>

                            </div><!-- /.card-header -->
                            <div class="card-body" style="padding-top:20px">
                                <!-- dialog -->
                                <el-dialog
                                    :title="($t('user.label.change_password') + ': ' + modelForm.username)"
                                    :visible.sync="changePassDialogVisible"
                                    width="30%"
                                    center>
                                    <el-form ref="changepassForm"
                                             :model="modelForm"
                                             label-width="200px"
                                             label-position="left"
                                             v-loading.body="loadingPassword"
                                    >
                                        <el-form-item :label="$t('user.label.password')"
                                                      :class="{'el-form-item is-error': changepassForm.errors.has('password') }">
                                            <el-input v-model="modelForm.password" autocomplete="off"
                                                      type="password"></el-input>
                                            <div class="el-form-item__error" v-if="changepassForm.errors.has('password')"
                                                 v-text="changepassForm.errors.first('password')"></div>
                                        </el-form-item>
                                        <el-form-item :label="$t('user.label.password_confirmation')"
                                                      :class="{'el-form-item is-error': changepassForm.errors.has('password_confirmation') }">
                                            <el-input v-model="modelForm.password_confirmation" autocomplete="off"
                                                      type="password"></el-input>
                                            <div class="el-form-item__error"
                                                 v-if="changepassForm.errors.has('password_confirmation')"
                                                 v-text="changepassForm.errors.first('password_confirmation')"></div>
                                        </el-form-item>
                                    </el-form>

                                    <span slot="footer" class="dialog-footer">
                                        <el-button @click="changePassDialogVisible = false">  {{$t('mon.button.cancel') }}</el-button>
                                        <el-button type="primary"
                                                   @click="changePassword"> {{ $t('mon.button.save') }}</el-button>
                                      </span>
                                </el-dialog>

                                <el-form ref="form"
                                         :model="modelForm"
                                         label-width="200px"
                                         label-position="left"
                                         v-loading.body="loading"
                                >
                                    <div class="row">
                                        <div class="col-md-12">

                                            <el-tabs>
                                                <el-tab-pane :label="$t('user.tabs.data')">
                                                            <span slot="label"
                                                                  :class="{'error' : form.errors.any()}">
                                                                {{ $t('user.tabs.data') }}
                                                            </span>
                                                    <div class="row">
                                                        <div class="col-md-10">
                                                            <el-form-item :label="$t('user.label.username')"
                                                                          :class="{'el-form-item is-error': form.errors.has('username') }">
                                                                <el-input v-model="modelForm.username"
                                                                          autocomplete="off"></el-input>
                                                                <div class="el-form-item__error"
                                                                     v-if="form.errors.has('username')"
                                                                     v-text="form.errors.first('username')"></div>
                                                            </el-form-item>
                                                            <el-form-item :label="$t('user.label.name')"
                                                                          :class="{'el-form-item is-error': form.errors.has('name') }">
                                                                <el-input v-model="modelForm.name"></el-input>
                                                                <div class="el-form-item__error"
                                                                     v-if="form.errors.has('name')"
                                                                     v-text="form.errors.first('name')"></div>
                                                            </el-form-item>

                                                            <el-form-item :label="$t('user.label.email')"
                                                                          :class="{'el-form-item is-error': form.errors.has('email') }">
                                                                <el-input v-model="modelForm.email"
                                                                          autocomplete="off"></el-input>
                                                                <div class="el-form-item__error"
                                                                     v-if="form.errors.has('email')"
                                                                     v-text="form.errors.first('email')"></div>
                                                            </el-form-item>

                                                            <el-form-item :label="$t('user.label.status')"
                                                                          :class="{'el-form-item is-error': form.errors.has(  'status') }">

                                                                <el-select v-model="modelForm.status" :placeholder="$t('user.label.status')"
                                                                           filterable style="width: 100% !important">
                                                                    <el-option
                                                                            v-for="item in listStatus"
                                                                            :key="'status'+ item.value"
                                                                            :label="item.label"
                                                                            :value="item.value">
                                                                    </el-option>

                                                                </el-select>
                                                                <div class="el-form-item__error"
                                                                     v-if="form.errors.has('shop_id')"
                                                                     v-text="form.errors.first('shop_id')"></div>
                                                            </el-form-item>


                                                            <el-form-item :label="$t('user.label.shop_id')"
                                                                          :class="{'el-form-item is-error': form.errors.has('shop_id') }">

                                                                <el-select v-model="modelForm.shop_id" :placeholder="$t('user.label.shop_id')"
                                                                           filterable style="width: 100% !important">
                                                                    <el-option
                                                                            v-for="item in listShop"
                                                                            :key="'shop_id'+ item.id"
                                                                            :label="item.name"
                                                                            :value="item.id">
                                                                    </el-option>

                                                                </el-select>
                                                                <div class="el-form-item__error"
                                                                     v-if="form.errors.has('shop_id')"
                                                                     v-text="form.errors.first('shop_id')"></div>
                                                            </el-form-item>
                                                                                                          
                                                            <div v-if="modelForm.is_new">
                                                                <el-form-item :label="$t('user.label.password')"
                                                                              :class="{'el-form-item is-error': form.errors.has('password') }">
                                                                    <el-input v-model="modelForm.password"
                                                                              autocomplete="off"
                                                                              type="password"></el-input>
                                                                    <div class="el-form-item__error"
                                                                         v-if="form.errors.has('password')"
                                                                         v-text="form.errors.first('password')"></div>
                                                                </el-form-item>
                                                                <el-form-item
                                                                    :label="$t('user.label.password_confirmation')"
                                                                    :class="{'el-form-item is-error': form.errors.has('password_confirmation') }">
                                                                    <el-input v-model="modelForm.password_confirmation"
                                                                              autocomplete="off"
                                                                              type="password"></el-input>
                                                                    <div class="el-form-item__error"
                                                                         v-if="form.errors.has('password_confirmation')"
                                                                         v-text="form.errors.first('password_confirmation')"></div>
                                                                </el-form-item>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </el-tab-pane>
                                                <el-tab-pane :label="$t('user.tabs.roles')">
                                                    <el-checkbox style="margin-bottom:20px"
                                                                 :indeterminate="isIndeterminate"
                                                                 v-model="checkAll"
                                                                 @change="handleCheckAllChange" border
                                                                 size="medium">
                                                        {{ $t('mon.all') }}
                                                    </el-checkbox>

                                                    <el-checkbox-group v-model="modelForm.roles"
                                                                       @change="handleCheckedChange">
                                                        <el-checkbox v-for="item in roles" :label="item.id"
                                                                     :key="'role-'+ item.id"
                                                                     border size="medium">{{item.name}}
                                                        </el-checkbox>
                                                    </el-checkbox-group>


                                                </el-tab-pane>


                                            </el-tabs>
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

    export default {
        props: {
            locales: {default: null},
            pageTitle: {default: null, String},
        },
        data() {
            return {
                form: new Form(),
                changepassForm: new Form(),
                loading: false,
                loadingPassword: false,
                modelForm: {
                    username: '',
                    name: '',
                    email: '',
                    phone: '',
                    roles: [],
                    is_new: false,
                    type: 1,
                    status: 1,
                    shop_id:''

                },
                roles: [],
                checkAll: false,
                isIndeterminate: false,
                changePassDialogVisible: false,
                listShop:[],
                listStatus: [

                    {
                        value: 1,
                        label: 'Hoạt động'
                    },
                    {
                        value: 0,
                        label: 'Chưa hoạt động'
                    },
                    {
                        value: 2,
                        label: 'Đã khóa'
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
                        window.location.href =  route('shop.user.index') + '?msg=' + response.message
                    })
                    .catch((error) => {

                        this.loading = false;
                        this.$notify.error({
                            title: this.$t('mon.error.Title'),
                            message: this.getSubmitError(this.form.errors),
                        });
                    });
            },
            changePassword() {
                this.changepassForm = new Form({
                    password: this.modelForm.password,
                    password_confirmation: this.modelForm.password_confirmation
                });
                this.loadingPassword = true;

                this.changepassForm.post(route('api.users.change-password', {user: this.$route.params.userId}))
                    .then((response) => {
                        this.loadingPassword = false;
                        this.$message({
                            type: 'success',
                            message: response.message,
                        });
                        this.changePassDialogVisible = false;
                    })
                    .catch((error) => {
                        this.loadingPassword = false;
                        this.$notify.error({
                            title: this.$t('mon.error.Title'),
                            message: this.getSubmitError(this.changepassForm.errors),
                        });
                    });
            },
            onCancel() {

                this.$confirm(this.$t('mon.cancel.Are you sure to cancel?'), {
                    confirmButtonText: this.$t('mon.cancel.Yes'),
                    cancelButtonText: this.$t('mon.cancel.No'),
                    type: 'warning'
                }).then(() => {
                    this.$router.push({name: 'shop.user.index'});
                }).catch(() => {

                });


            },


            fetchData() {


                let routeUri = '';
                if (this.$route.params.userId !== undefined) {
                    this.loading = true;
                    routeUri = route('api.users.find', {user: this.$route.params.userId});
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

            fetchRoles() {
                axios.get(route('apishop.roles.index'))
                    .then((response) => {
                        this.roles = response.data.data;
                    });
            },
            getRoute() {
                if (this.$route.params.userId !== undefined) {
                    return route('api.user.update', {user: this.$route.params.userId});
                }
                return route('api.user.store');
            },
            handleCheckAllChange(val) {
                this.modelForm.roles = val ? this.roles.map(item => item.id) : [];
                this.isIndeterminate = false;
            },
            handleCheckedChange(value) {

                let checkedCount = value.length;
                this.checkAll = checkedCount === this.roles.length;
                this.isIndeterminate = checkedCount > 0 && checkedCount < this.roles.length;
            },

            fetchShop() {
                const properties = {
                page: 0,
                per_page: 1000,

                };

                axios.get(route('api.shop.index', _.merge(properties, {})))
                .then((response) => {
                this.listShop = response.data.data;
                });
            },


        },
        mounted() {
            this.fetchShop();
            this.fetchData();
            this.fetchRoles();
         

        },
        computed: {}
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

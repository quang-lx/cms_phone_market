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
                                        <a href="/admin">{{ $t('mon.breadcrumb.home') }}</a>
                                    </el-breadcrumb-item>
                                    <el-breadcrumb-item :to="{name: 'admin.admins.index'}">{{ $t('user.label.admins') }}
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
                                    {{ $t(pageTitle) }}<span
                                    v-if="modelForm.username">: &nbsp{{modelForm.username}}</span>
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
                            <div class="card-body">
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
                                        <el-form-item :label="$t('user.label.password_new')" style="margin-bottom:30px"
                                                      :class="{'el-form-item is-error': changepassForm.errors.has('password') }">
                                            <el-input v-model="modelForm.password" autocomplete="off"
                                                      type="password"></el-input>
                                            <div class="el-form-item__error"
                                                 v-if="changepassForm.errors.has('password')"
                                                 v-text="changepassForm.errors.first('password')"></div>
                                        </el-form-item>
                                        <el-form-item :label="$t('user.label.password_confirmation_new')"
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
                                            <el-form-item :label="$t('user.label.username')"
                                                          :class="{'el-form-item is-error': form.errors.has('username') }">
                                                <el-input v-model="modelForm.username" :disabled="!modelForm.is_new"
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

                                            <el-form-item :label="$t('user.label.phone')"
                                                          :class="{'el-form-item is-error': form.errors.has('phone') }">
                                                <el-input v-model="modelForm.phone"
                                                          autocomplete="off"></el-input>
                                                <div class="el-form-item__error"
                                                     v-if="form.errors.has('phone')"
                                                     v-text="form.errors.first('phone')"></div>
                                            </el-form-item>
                                            <el-form-item :label="$t('company.label.status')"
                                                          :class="{'el-form-item is-error': form.errors.has('status') }">
                                                <el-radio-group v-model="modelForm.status">
                                                    <el-radio v-for="item in listStatus" :label="item.value"
                                                              :key="item.lable">{{item.label}}
                                                    </el-radio>

                                                </el-radio-group>
                                                <div class="el-form-item__error"
                                                     v-if="form.errors.has('status')"
                                                     v-text="form.errors.first('status')"></div>
                                            </el-form-item>

                                              <single-media class="mb-3"
                                                zone="thumbnail"
                                                @singleFileSelected="
                                                  selectSingleFile($event, 'modelForm')
                                                "
                                                label="Ảnh đại diện"
                                                entity="Modules\Mon\Entities\User"
                                                :entity-id="$route.params.userId"
                                              ></single-media>
                                              <div
                                                class="el-form-item__error"
                                                style="margin-left: 208px"
                                                v-if="form.errors.has('medias_single.thumbnail')"
                                                v-text="
                                                  form.errors.first('medias_single.thumbnail')
                                                "
                                              ></div>
                                            <div v-if="modelForm.is_new">
                                              <div class="mb-4">
                                                <el-form-item :label="$t('user.label.password')" 
                                                              :class="{'el-form-item is-error': form.errors.has('password') }">
                                                    <el-input v-model="modelForm.password"
                                                              autocomplete="off"
                                                              type="password"></el-input>
                                                    <div class="el-form-item__error"
                                                         v-if="form.errors.has('password')"
                                                         v-text="form.errors.first('password')"></div>
                                                </el-form-item>
                                                </div>
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

                                            <h3 style="font-size: 1.1rem;font-weight: 400;margin: 0;">
                                                {{$t('user.tabs.roles')}}</h3>
                                            <hr>
                                            <div style="margin-left:200px">
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
  import SingleFileSelector from "../../../mixins/SingleFileSelector.js";

  export default {
    props: {
      locales: {default: null},
      pageTitle: {default: null, String},
    },
    mixins: [SingleFileSelector],
    data() {
      return {
        form: new Form(),
        changepassForm: new Form(),
        loading: false,
        loadingPassword: false,
        modelForm: {
          username: '',
          name: '',
          status: 1,
          phone:'',
          email: '',
          phone: '',
          roles: [],
          is_new: false,
          type: 1

        },
        listStatus: [

          {
            value: 1,
            label: 'Hoạt động'
          },
          {
            value: 2,
            label: 'Khóa'
          }
        ],
        roles: [],
        checkAll: false,
        isIndeterminate: false,
        changePassDialogVisible: false
      };
    },
    methods: {
      onSubmit() {
        this.form = new Form(_.merge(this.modelForm, {}));
        this.loading = true;

        this.form.post(this.getRoute())
        .then((response) => {
          this.loading = false;

          window.location.href = route('admin.admins.index') + '?msg=' + response.message
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
          this.$router.push({name: 'admin.admins.index'});
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
        axios.get(route('api.roles.all', {module: 'admin', per_page: 1000}))
        .then((response) => {
          this.roles = response.data.data;
        });
      },
      getRoute() {
        if (this.$route.params.userId !== undefined) {
          return route('api.users.update', {user: this.$route.params.userId});
        }
        return route('api.users.store');
      },
      handleCheckAllChange(val) {
        this.modelForm.roles = val ? this.roles.map(item => item.id) : [];
        this.isIndeterminate = false;
      },
      handleCheckedChange(value) {

        let checkedCount = value.length;
        this.checkAll = checkedCount === this.roles.length;
        this.isIndeterminate = checkedCount > 0 && checkedCount < this.roles.length;
      }


    },
    mounted() {
      this.fetchData();
      this.fetchRoles();

    },
    computed: {}
  }
</script>

<style scoped>

</style>

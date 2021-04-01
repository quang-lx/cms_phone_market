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
                                    <el-breadcrumb-item :to="{name: 'admin.roles.index'}">{{ $t('role.label.roles') }}
                                    </el-breadcrumb-item>
                                    <el-breadcrumb-item>  {{ $t(pageTitle) }}
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

                            <div class="card-body">
                                <el-tabs>
                                    <el-tab-pane>
                                        <span slot="label">{{ $t('role.label.information')}}</span>
                                        <el-form ref="form" :model="modelForm" label-width="200px" label-position="left"
                                                 v-loading.body="loading"
                                        >
                                            <div class="row">
                                                <div class="col-md-10">

                                                    <el-form-item :label="$t('permission.label.name')"
                                                                  :class="{'el-form-item is-error': form.errors.has( 'name') }">
                                                        <el-input v-model="modelForm.name"
                                                                  @focus="form.errors.clear('name')"></el-input>
                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('name')"
                                                             v-text="form.errors.first('name')"></div>
                                                    </el-form-item>
                                                    <el-form-item :label="$t('role.label.description')"
                                                                  :class="{'el-form-item is-error': form.errors.has( 'description') }">
                                                        <el-input v-model="modelForm.description"></el-input>
                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('description')"
                                                             v-text="form.errors.first('description')"></div>
                                                    </el-form-item>
                                                </div>
                                            </div>
                                        </el-form>
                                    </el-tab-pane>
                                    <el-tab-pane class="card-content-bg">
                                        <span slot="label">{{ $t('role.label.permissions')}}</span>
                                        <div class="row" style="padding: 10px">
                                            <div class="col-12">
                                                <role-permission v-model="modelForm.permissions"
                                                                 :current-permissions="modelForm.permissions"/>
                                            </div>

                                        </div>
                                    </el-tab-pane>
                                </el-tabs>
                            </div><!-- /.card-body -->
                            <div class="card-footer d-flex justify-content-end ">
                                <el-button type="primary" @click="onSubmit()" size="small"
                                           :loading="loading" class="btn btn-flat ">
                                    {{ $t('mon.button.save') }}
                                </el-button>
                                <el-button class="btn btn-flat" size="small"
                                           @click="onCancel()">
                                    {{ $t('mon.button.cancel') }}
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
  import RolePermission from './role-permissions';

  export default {
    props: {
      locales: {default: null},
      pageTitle: {default: null, String},
    },
    components: {
      RolePermission
    },
    data() {
      return {
        form: new Form(),
        loading: false,
        modelForm: {
          id: '',
          name: '',
          description: '',
          guard_name: 'web',
          module: 'admin',
          permissions: {},

        },
        permissions: [],
        checkedPermissions: [],
        checkAll: false,
        isIndeterminate: true,
        roleId: null
      };
    },
    methods: {
      onSubmit() {
        this.form = new Form(_.merge(this.modelForm));
        this.loading = true;

        this.form.post(this.getRoute())
        .then((response) => {
          this.loading = false;
          this.$message({
            type: 'success',
            message: response.message,
          });
          this.$router.push({name: 'admin.roles.index'});
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
          this.$router.push({name: 'admin.roles.index'});
        }).catch(() => {

        });


      },


      fetchData() {
        this.loading = true;
        let routeUri = '';
        if (this.$route.params.roleId !== undefined) {
          routeUri = route('api.roles.find', {role: this.$route.params.roleId});
        } else {
          routeUri = route('api.roles.find-new');
        }
        axios.get(routeUri)
        .then((response) => {
          this.loading = false;
          this.modelForm = response.data.data;
        });
      },

      getRoute() {
        if (this.$route.params.roleId !== undefined) {
          return route('api.roles.update', {role: this.$route.params.roleId});
        }
        return route('api.roles.store');
      },
      reloadRemoveTable() {
        this.$refs.removeTable.reloadData();
      },
      reloadAddTable() {
        this.$refs.addTable.reloadData();
      }


    },
    created() {
      this.roleId = this.$route.params.roleId;
    },
    mounted() {
      this.fetchData();
    },
    computed: {}
  }
</script>

<style scoped>
    .box-permission-header {
        display: flex;
        align-content: center;

    }
</style>

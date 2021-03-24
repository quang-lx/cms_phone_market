<template>
    <div class="card">
        <div class="card-header ui-sortable-handle" style="cursor: move;" v-if="type ==='add'">
            <h3 class="card-title">
                {{ $t('role.label.list_permissions')}}
            </h3>
            <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                    <li class="nav-item">
                        <el-button type="primary" size="small" class="btn btn-flat" @click="storePermission('add')">
                            {{ $t('role.label.assign') }}
                        </el-button>
                    </li>

                </ul>
            </div>
        </div><!-- /.card-header -->
        <div class="card-header ui-sortable-handle" style="cursor: move;" v-if="type ==='remove'">
            <h3 class="card-title">
                {{ $t('role.label.selected_permissions')}}
            </h3>
            <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                    <li class="nav-item">
                        <el-button type="danger" size="small" class="btn btn-flat" @click="storePermission('remove')">
                            {{ $t('role.label.remove') }}
                        </el-button>
                    </li>

                </ul>
            </div>
        </div><!-- /.card-header -->
        <div class="card-body">
            <div class="sc-table">
                <div class="tool-bar table-toolbar-container " style="padding-bottom: 20px;">


                    <div class="row">

                        <div class=" col-sm-6">
                            <el-input prefix-icon="el-icon-search" @keyup.native="performSearch"
                                      v-model="searchQuery">
                            </el-input>
                        </div>
                    </div>


                </div>
                <el-table
                    ref="permissionTable"
                    :data="data"
                    stripe
                    style="width: 100%"
                    v-loading.body="tableIsLoading"
                    @selection-change="handleSelectionChange"
                    @sort-change="handleSortChange">
                    <el-table-column
                        type="selection"
                        width="55">
                    </el-table-column>
                    <el-table-column prop="id" :label="$t('permission.label.id')" width="75" sortable="custom">

                    </el-table-column>
                    <el-table-column prop="name" :label="$t('permission.label.name')" sortable="custom">

                    </el-table-column>


                </el-table>
                <div class="pagination-wrap">
                    <el-pagination
                        @size-change="handleSizeChange"
                        @current-change="handleCurrentChange"
                        :current-page.sync="meta.current_page"
                        :page-sizes="[25, 50, 75, 100]"
                        :page-size="parseInt(meta.per_page)"
                        :layout="pagingSetting.layout"
                        :total="meta.total">
                    </el-pagination>
                </div>


            </div>

        </div><!-- /.card-body -->
    </div>

</template>

<script>
  import axios from 'axios';
  import _ from 'lodash';

  export default {
    props: ['type', 'roleId'],
    data() {
      return {
        data: [],
        meta: {
          current_page: 1,
          per_page: 15,
        },
        order_meta: {
          order_by: '',
          order: '',
        },
        links: {},
        searchQuery: '',
        tableIsLoading: false,

        columnsSearch: [],
        listFilterColumn: [],
        checkedPermissions: [],
        guards: [
          {
            value: 'api',
            label: 'api'
          },
          {
            value: 'web',
            label: 'web'
          }
        ],
        guard_name: ''


      };
    },
    methods: {
      reloadData() {
        this.meta.current_page = 1;
        this.queryServer();
      },
      queryServer(customProperties) {

        const properties = {
          page: this.meta.current_page,
          per_page: this.meta.per_page,
          order_by: this.order_meta.order_by,
          order: this.order_meta.order,
          search: this.searchQuery,
          role_id: this.roleId,
          guard_name: this.guard_name,
          module: 'shop',
          in_role: (this.type === 'add') ? 0 : 1
        };

        axios.get(route('api.permissions.index', _.merge(properties, customProperties)))
        .then((response) => {
          this.tableIsLoading = false;
          this.tableIsLoading = false;
          this.data = response.data.data;
          this.meta = response.data.meta;
          this.links = response.data.links;
          this.order_meta.order_by = properties.order_by;
          this.order_meta.order = properties.order;
        });
      },
      fetchData() {
        this.tableIsLoading = true;
        this.queryServer({per_page: 15});
      },

      handleSizeChange(event) {
        this.tableIsLoading = true;
        this.queryServer({per_page: event});
      },
      handleCurrentChange(event) {
        this.tableIsLoading = true;
        this.queryServer({page: event});
      },
      handleSortChange(event) {
        this.tableIsLoading = true;
        this.queryServer({order_by: event.prop, order: event.order});
      },
      handleSelectionChange(val) {
        this.checkedPermissions = val;
      },
      performSearch: _.debounce(function (query) {
        this.tableIsLoading = true;
        this.queryServer({search: query.target.value, page: this.meta.current_page,});
      }, 300),

      storePermission(type) {
        this.tableIsLoading = true;
        if (this.checkedPermissions.length) {
          const route = this.getRoute(type)
          let checkedIds = this.checkedPermissions.map(permission => permission.name);
          axios.post(route, {permissions: checkedIds})
          .then((response) => {

            this.$message({
              type: 'success',
              message: response.data.message,
            });
            this.reloadData();
            this.$emit('reload-permissions');
          })
          .catch((error) => {

            this.loading = false;
            this.$notify.error({
              title: 'Error',
              message: 'There are some errors in the form.',
            });
          }).finally(_ => this.tableIsLoading = false);
        }
      },

      getRoute(type) {
        if (type === 'add') {
          return route('api.roles.assignPermissions', {role: this.$route.params.roleId});
        } else {
          return route('api.roles.removePermissions', {role: this.$route.params.roleId});
        }

      },


    },
    mounted() {
      this.fetchData();

    },
  }
</script>

<style scoped>

</style>

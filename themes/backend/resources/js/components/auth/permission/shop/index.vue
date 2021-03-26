<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-12">
                        <el-breadcrumb separator="/">
                            <el-breadcrumb-item>
                                <a href="/admin">{{ $t("mon.breadcrumb.home") }}</a>
                            </el-breadcrumb-item>
                            <el-breadcrumb-item
                            >{{ $t("permission.label.permissions") }}
                            </el-breadcrumb-item>
                        </el-breadcrumb>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row justify-content-end mb-2">
                    <div class="col-md-4">
                        <el-input
                            prefix-icon="el-icon-search"
                            @keyup.native="performSearch"
                            v-model="searchQuery"
                        >
                        </el-input>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header ui-sortable-handle" style="cursor: move">
                                <h3 class="card-title">
                                    {{ $t("permission.label.permissions") }}
                                </h3>
                                <div class="card-tools">
                                    <ul class="nav nav-pills ml-auto">
                                        <li class="nav-item">
                                            <router-link
                                                :to="{ name: 'admin.permissions.create' }"
                                                class="float-sm-right"
                                            >
                                                <el-button type="primary" size="small" class="btn">
                                                    {{ $t("permission.label.create_permission") }}
                                                </el-button>
                                            </router-link>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="sc-table">
                                    <el-table
                                        :data="data"
                                        stripe
                                        style="width: 100%"
                                        ref="dataTable"
                                        v-loading.body="tableIsLoading"
                                        @sort-change="handleSortChange"
                                    >
                                        <el-table-column
                                            prop="id"
                                            :label="$t('permission.label.id')"
                                            width="75"
                                            sortable="custom"
                                        >
                                        </el-table-column>
                                        <el-table-column
                                            prop="name"
                                            :label="$t('permission.label.name')"
                                            sortable="custom"
                                        >
                                        </el-table-column>
                                        <el-table-column
                                            prop="group"
                                            :label="$t('permission.label.group')"
                                            sortable="custom"
                                        >
                                        </el-table-column>
                                        <el-table-column
                                            prop="title"
                                            :label="$t('permission.label.title')"
                                            sortable="custom"
                                        >
                                        </el-table-column>
                                        <el-table-column
                                            prop="created_at"
                                            :label="$t('permission.label.created_at')"
                                            sortable="custom"
                                        >
                                        </el-table-column>
                                        <el-table-column
                                            prop="updated_at"
                                            :label="$t('permission.label.updated_at')"
                                            sortable="custom"
                                        >
                                        </el-table-column>

                                        <el-table-column prop="actions" width="130">
                                            <template slot-scope="scope">
                                                <edit-button
                                                    :to="{
                            name: 'admin.permissions.edit',
                            params: { permissionId: scope.row.id },
                          }"
                                                ></edit-button>
                                            </template>
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
                                            :total="meta.total"
                                        >
                                        </el-pagination>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
  import axios from "axios";

  export default {
    data() {
      return {
        data: [],
        meta: {
          current_page: 1,
          per_page: 25,
        },
        order_meta: {
          order_by: "",
          order: "",
        },
        links: {},
        searchQuery: "",
        tableIsLoading: false,

        columnsSearch: [],
        listFilterColumn: [],
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
          module: 'shop',
        };

        axios
        .get(
          route("api.permissions.index", _.merge(properties, customProperties))
        )
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
        this.queryServer({per_page: 25});
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
    },
    mounted() {
      this.fetchData();
    },
  };
</script>

<style scoped>
</style>

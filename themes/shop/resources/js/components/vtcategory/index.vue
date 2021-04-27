<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-3 d-flex align-items-center">
                <el-breadcrumb separator="/">
                  <el-breadcrumb-item>
                    <a href="/admin">{{ $t("mon.breadcrumb.home") }}</a>
                  </el-breadcrumb-item>
                  <el-breadcrumb-item
                    >{{ $t("vtcategory.label.vtcategory") }}
                  </el-breadcrumb-item>
                </el-breadcrumb>
              </div>
              <div class="col-sm-9 text-right">
                <div class="row">
                  <div class="col-sm-8">
                    <el-input
                      prefix-icon="el-icon-search"
                      @keyup.native="performSearch"
                      v-model="searchQuery"
                    >
                    </el-input>
                  </div>
                  <div class="col-sm-4">
                    <router-link :to="{ name: 'admin.vtcategory.create' }">
                      <el-button type="primary" class="btn btn-flat">
                        {{ $t("vtcategory.label.create_vtcategory") }}
                      </el-button>
                    </router-link>
                  </div>
                </div>
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
                      :label="$t('vtcategory.label.id')"
                      width="75"
                      sortable="custom"
                    >
                    </el-table-column>
                    <el-table-column
                      prop=""
                      :label="$t('vtcategory.label.image')"
                    >
                      <template slot-scope="scope">
                        <img
                          :src="scope.row.thumbnail.path_string"
                          v-if="scope.row.thumbnail"
                          width="100"
                          height="100"
                          style="object-fit: contain"
                        />
                      </template>
                    </el-table-column>
                    <el-table-column
                      prop="name"
                      :label="$t('vtcategory.label.name')"
                      sortable="custom"
                    >
                    </el-table-column>
                    <el-table-column
                      :label="$t('vtcategory.label.type')"
                      sortable="custom"
                    >
                      <template slot-scope="scope">
                        <span class="pl-4" v-if="scope.row.parent_id">{{
                          scope.row.name
                        }}</span>
                        <span v-else>{{ scope.row.name }}</span>
                      </template>
                    </el-table-column>

                    <el-table-column prop="actions" width="130">
                      <template slot-scope="scope">
                        <edit-button
                          :to="{
                            name: 'admin.vtcategory.edit',
                            params: { vtcategoryId: scope.row.id },
                          }"
                        ></edit-button>
                        <delete-button
                          :scope="scope"
                          :rows="data"
                        ></delete-button>
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
      };

      axios
        .get(
          route("shopapi.vtcategory.index", _.merge(properties, customProperties))
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
  },
  mounted() {
    this.fetchData();
  },
};
</script>

<style scoped>
</style>

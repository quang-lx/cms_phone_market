<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-6 d-flex align-items-center">
                <el-breadcrumb separator="/">
                  <el-breadcrumb-item>
                    <a href="/shop-admin">{{ $t("mon.breadcrumb.home") }}</a>
                  </el-breadcrumb-item>
                  <el-breadcrumb-item
                    >{{ $t("vtshopproduct.label.vtshopproduct") }}
                  </el-breadcrumb-item>
                </el-breadcrumb>
              </div>
              <div class="col-sm-6 text-right">
                <div class="row justify-content-end">
                  <div class="col-sm-4">
                    <el-input
                      prefix-icon="el-icon-search"
                      @keyup.native="performSearch"
                      v-model="searchQuery"
                       placeholder="Tên linh kiện"
                    >
                    </el-input>
                  </div>
                  <div class="col-sm-4" v-if="!this.currentShop">
                    <el-select
                      v-model="filter.shop_id"
                      filterable
                      placeholder="Lọc theo chi nhánh"
                      @change="onSearchChange()"
                      clearable
                    >
                      <el-option
                        v-for="item in listShop"
                        :key="item.id"
                        :label="item.name"
                        :value="item.id"
                      >
                      </el-option>
                    </el-select>
                  </div>
                  <div class="col-sm-4">
                    <router-link :to="{ name: 'shop.vtshopproduct.create' }">
                      <el-button type="primary" class="btn btn-flat">
                        {{ $t("vtshopproduct.label.create_vtshopproduct") }}
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
                      :label="$t('vtshopproduct.label.id')"
                      width="75"
                      sortable="custom"
                    >
                    </el-table-column>

                    <el-table-column
                      prop="vt_product_name"
                      :label="$t('vtshopproduct.label.vt_product_id')"
                      sortable="custom"
                    >
                    </el-table-column>

                    <el-table-column
                      prop=""
                      :label="$t('vtshopproduct.label.amount')"
                      sortable="custom"
                    >
                      <template slot-scope="scope">
                          <span v-number="scope.row.amount"></span>
                      </template>
                    </el-table-column>

                    <el-table-column
                      prop="shop_name"
                      :label="$t('vtshopproduct.label.shop_id')"
                      sortable="custom"
                    >
                    </el-table-column>

                    <el-table-column
                      prop="created_by"
                      :label="$t('vtshopproduct.label.created_by')"
                      sortable="custom"
                    >
                    </el-table-column>

                    <el-table-column
                      prop="created_at"
                      :label="$t('vtshopproduct.label.created_at')"
                      sortable="custom"
                    >
                    </el-table-column>
                    

                    <!-- <el-table-column prop="actions" width="130">
                      <template slot-scope="scope">
                        <edit-button
                          :to="{
                            name: 'shop.vtshopproduct.edit',
                            params: { vtshopproductId: scope.row.id },
                          }"
                        ></edit-button>
                        <delete-button
                          :scope="scope"
                          :rows="data"
                        ></delete-button>
                      </template>
                    </el-table-column> -->
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
      listShop: [],
      currentShop : window.MonCMS.current_user.shop_id,
      filter: {
          shop_id:'',
          locale: window.MonCMS.currentLocale || 'en'
      },
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
        shop_id:this.filter.shop_id,
      };

      axios
        .get(
          route("apishop.vtshopproduct.index", _.merge(properties, customProperties))
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
    fetchShop() {
      const properties = {
        page: 0,
        per_page: 1000,
        check_company: true,
      };

      axios
        .get(route("api.shop.index", _.merge(properties, {})))
        .then((response) => {
          this.listShop = response.data.data;
        });
    },
    onSearchChange() {
        this.meta.current_page = 0;
        this.queryServer({})
    },
  },
  mounted() {
    this.fetchData();
    this.fetchShop();

  },
};
</script>

<style scoped>
</style>

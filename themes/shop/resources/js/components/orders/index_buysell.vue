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
                    <a href="/shop-admin">{{ $t("mon.breadcrumb.home") }}</a>
                  </el-breadcrumb-item>
                  <el-breadcrumb-item
                    >{{ $t("orders.label.buysell") }}
                  </el-breadcrumb-item>
                </el-breadcrumb>
              </div>
              <div class="col-sm-9 text-right">
                <div class="row">
                  <div class="col-sm-3">
                    <el-date-picker @change="queryServer"
                        v-model="filter.searchDate"
                        type="daterange"
                        start-placeholder="Start date"
                        end-placeholder="End date"
                         format="dd/MM/yyyy"
                    value-format="yyyy-MM-dd"
                       >
                    </el-date-picker>
                  </div>
                   <div class="col-sm-3">
                    <el-select v-model="filter.shop" @change="queryServer" placeholder="Lọc theo cửa hàng">
                      <el-option
                        v-for="item in listShop"
                        :key="item.id"
                        :label="item.name"
                        :value="item.id">
                      </el-option>
                    </el-select>
                  </div>
                   <div class="col-sm-3">
                    <el-select v-model="filter.status" @change="queryServer" placeholder="Lọc theo trạng thái">
                      <el-option
                        v-for="item in listStatus"
                        :key="item.value"
                        :label="item.label"
                        :value="item.value">
                      </el-option>
                    </el-select>
                  </div>
                   <div class="col-sm-3">
                    <el-input
                      prefix-icon="el-icon-search"
                      @keyup.native="performSearch"
                      v-model="searchQuery"
                      placeholder="Mã đơn hàng/SĐT user"
                    >
                    </el-input>
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
                      :label="$t('orders.label.id')"
                      width="150"
                      sortable="custom"
                    >
                    </el-table-column>
                    <el-table-column
                      prop="user_name"
                      :label="$t('orders.label.user_name')"
                      sortable="custom"
                    >

                    </el-table-column>

                     <el-table-column
                      prop="product_name"
                      :label="$t('orders.label.product_name')"
                      sortable="custom"
                    >
                    </el-table-column>

                    <el-table-column

                      :label="$t('orders.label.shop_id')"
                      sortable="custom"
                    >
                     <template slot-scope="scope"  v-if="scope.row.shop">
                          <div> {{ scope.row.shop.name }}</div>
                          <div> {{ scope.row.shop.phone }}</div>
                          <div> {{ scope.row.shop.address }}</div>
                     </template>
                    </el-table-column>

                    <el-table-column
                      prop="created_at"
                      :label="$t('orders.label.created_at')"
                      sortable="custom"
                    >
                    </el-table-column>

                    <el-table-column
                      prop=""
                      :label="$t('orders.label.pay_price')"
                      sortable="custom"
                    >
                    <template slot-scope="scope">
                      <span>{{scope.row.pay_price.toLocaleString('vi-VN', currency)}}</span>
                    </template>
                    </el-table-column>

                    <el-table-column
                      prop="status"
                      :label="$t('orders.label.status')"
                      sortable="custom"
                    >
                    </el-table-column>


                    <el-table-column prop="actions" width="130">
                       <template slot-scope="scope">
                                                <router-link :to="{name: 'shop.orders.detailbuysell', params: {ordersId: scope.row.id}}">
                                                    <i class="el-icon-view"></i>
                                                </router-link>
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
      listShop:[],
      listStatus: [
          {
              value: 0,
              label: 'Chưa xác nhận'
          },
          {
              value: 1,
              label: 'Xác nhận'
          }
      ],
      filter: {
          status: '',
          searchDate: '',
          shop: '',
      }
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
        status : this.filter.status,
        shop : this.filter.shop,
        searchDate : this.filter.searchDate,
        order_type : 'mua_hang',
      };

      axios
        .get(
          route("apishop.orders.index", _.merge(properties, customProperties))
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
      let routeUri = "";
      this.loading = true;
      routeUri = route("api.shop.index", { page: 1, per_page: 1000,shop_admin:1});
      axios.get(routeUri).then((response) => {
        this.loading = false;
        this.listShop = response.data.data;
      });
    },
  },
  mounted() {
    this.fetchData();
    this.fetchShop();
  },
};
</script>

<style scoped>
.el-input__inner{
    width: 100% !important;
}

</style>

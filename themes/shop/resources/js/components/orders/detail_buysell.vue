<template>
  <div v-if="modelForm && modelForm.id">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-12">
            <el-breadcrumb separator="/">
              <el-breadcrumb-item>
                <a href="/shop-admin">{{ $t("mon.breadcrumb.home") }}</a>
              </el-breadcrumb-item>
              <el-breadcrumb-item :to="{ name: 'shop.orders.index' }"
                >{{ $t("orders.label.buysell") }}
              </el-breadcrumb-item>
              <el-breadcrumb-item>
                Đơn hàng #{{ modelForm.id }}
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
              <div class="card-header ui-sortable-handle" style="cursor: move">
                <div class="row">
                  <h3 class="card-title col-md-8">
                    {{ $t(pageTitle)
                    }}<span v-if="modelForm.title"
                      >: &nbsp{{ modelForm.title }}</span
                    >
                  </h3>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="row">
                      <div class="col-md-2">
                        <div class="row">
                          <div class="col-md-12">
                            <div><h4>Thông tin user đặt hàng</h4></div>
                            <div>
                              {{ modelForm.user.username }}
                            </div>
                            <div>
                              {{ modelForm.user.phone }}
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div><h4>Địa chỉ giao hàng</h4></div>
                        <div>
                              {{ modelForm.user.name }}
                            </div>
                            <div>
                              {{ modelForm.user.phone }}
                            </div>

                        <div>
                          {{ modelForm.ship_province_name }}
                        </div>
                        <div>
                          {{ modelForm.ship_district_name }}
                        </div>
                        <div>
                          {{ modelForm.ship_phoenix_name }}
                        </div>
                        <div>
                          {{ modelForm.ship_address }}
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div><h4>Đặt hàng tại</h4></div>

                        <div>
                          {{ modelForm.shop.name }}
                        </div>
                        <div>
                          {{ modelForm.shop.phone }}
                        </div>
                        <div>
                          {{ modelForm.shop.address }}
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div><h4>Thanh toán</h4></div>
                            {{ modelForm.pay_method_name }}
                        <div></div>
                      </div>

                      <div class="col-md-3">
                        <div><h4>Trạng thái đơn hàng</h4></div>
                        <div v-for="(item, index) in modelForm.order_status_history" :key="index">
                            <span>{{item.title}}</span>
                            <span>({{item.date}})</span>
                        </div>
                         <div>
                          <span>{{modelForm.status}}</span>
                            <p v-if="modelForm.shop_done == 1 && modelForm.status_value!='done'">(Người bán đã xác nhận giao hàng thành công)</p>

                        </div>
                      </div>
                      <!-- <div class="col-md-12 mt-5">
                            {{ $t("orders.label.description") }}:
                            <div v-html="modelForm.description"></div>
                        </div> -->
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
          </div>
          <div class="col-12">
            <div class="card">
              <div class="card-header ui-sortable-handle" style="cursor: move">
                <div class="row">
                  <h3 class="card-title col-md-8">Danh sách sản phẩm</h3>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="sc-table">
                      <el-table
                        :data="modelForm.list_product"
                        stripe
                        style="width: 100%"
                        ref="dataTable"
                        v-loading.body="tableIsLoading"
                        @sort-change="handleSortChange"
                      >
                        <el-table-column
                          prop=""
                          label="Tên sản phẩm"
                          
                        >
                          <template slot-scope="scope">
                            <img
                              class="mr-2"
                              :src="scope.row.thumbnail.path_string"
                              v-if="scope.row.thumbnail"
                              width="100"
                              height="100"
                              style="object-fit: contain"
                            />
                            <span>{{ scope.row.name }}</span>
                          </template>
                        </el-table-column>
                        <el-table-column prop="" label="Giá" >
                          <template slot-scope="scope">
                            <span v-currency="scope.row.price_product"></span>
                          </template>
                        </el-table-column>

                         <el-table-column
                          prop=""
                          label="Thuộc tính"
                          
                        >
                          <template slot-scope="scope">
                            <span v-number="scope.row.attr_value"></span>
                          </template>
                        </el-table-column>

                        <el-table-column
                          prop=""
                          label="Số lượng"
                          
                        >
                          <template slot-scope="scope">
                            <span v-number="scope.row.quantity"></span>
                          </template>
                        </el-table-column>

                        <el-table-column
                          prop=""
                          label="Tổng tiền"
                          
                        >
                          <template slot-scope="scope">
                            <span v-currency="scope.row.price_amount"></span>
                          </template>
                        </el-table-column>
                      </el-table>
                    </div>
                  </div>
                  <div class="col-md-12 mt-4">
                    <div class="row">
                      <div class="col-md-6"></div>
                      <div class="col-md-6">
                        <table class="w-100">
                          <tbody>
                            <tr>
                              <th scope="row" style="width: 60%;">Tạm tính</th>
                              <td style="width: 40%;">
                                <span v-currency="modelForm.total_price"></span>
                              </td>
                            </tr>
                            <tr>
                              <th scope="row">Phí vận chuyển</th>
                              <td>
                                <span v-currency="modelForm.ship_fee"></span>
                              </td>
                            </tr>
                            <tr>
                              <th scope="row">Giảm giá</th>
                              <span v-currency="modelForm.discount"></span><span class="ml-1" v-if="modelForm.sys_voucher_code">({{modelForm.sys_voucher_code}})</span><span  class="ml-1" v-if="modelForm.shop_voucher_code">({{modelForm.shop_voucher_code}})</span>
                              <td></td>
                            </tr>
                            <tr>
                              <th scope="row">Tổng tiền</th>
                              <td>
                                <span v-currency="modelForm.pay_price"></span>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                 <div class="col-md-12 mt-4 text-right">
                          <status-buysell :data="modelForm"></status-buysell>
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
import Form from "form-backend-validation";
import SingleFileSelector from "../../mixins/SingleFileSelector.js";
import MultipleMedia from "../media/js/components/MultipleMedia";
import MultipleFileSelector from "../../mixins/MultipleFileSelector.js";
import StatusBuysell from "./status_buysell";

export default {
  props: {
    locales: { default: null },
    pageTitle: { default: null, String },
  },
  mixins: [SingleFileSelector, MultipleFileSelector],
  components: {
    MultipleMedia,
    StatusBuysell
  },
  data() {
    return {
      formLabelWidth: "120px",
      form: new Form(),
      loading: false,
      list_orders: [],
      modelForm: {},
      dialogRank: false,
      message: "",
      dialogVisible: false,
    };
  },
  methods: {
    fetchData() {
      let routeUri = "";
      this.loading = true;
      routeUri = route("apishop.orders.findbuysell", {
        orders: this.$route.params.ordersId,
      });
      axios.get(routeUri).then((response) => {
        this.loading = false;
        this.modelForm = response.data.data;
        this.modelForm.is_new = false;
      });
    },
  },
  mounted() {
    this.fetchData();
  },
  computed: {},
};
</script>

<style scoped></style>

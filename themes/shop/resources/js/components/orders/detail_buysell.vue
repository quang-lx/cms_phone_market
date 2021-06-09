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
                            <div><h4>Thông tin user</h4></div>
                            <div>
                              {{ modelForm.user.name }}
                            </div>
                            <div>
                              {{ modelForm.user.phone }}
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div><h4>Địa chỉ lấy hàng</h4></div>

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
                        <div><h4>Đặt sửa tại</h4></div>

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

                        <div></div>
                        <div><h4>Chi phí</h4></div>
                        <div>
                          {{ modelForm.pay_price.toLocaleString('vi-VN', currency) }}
                        </div>
                      </div>

                      <div class="col-md-3">
                        <div><h4>Trạng thái</h4></div>
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
                          sortable="custom"
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
                        <el-table-column prop="" label="Giá" sortable="custom">
                          <template slot-scope="scope">
                            <span>{{
                            
                                scope.row.price_product.toLocaleString('vi-VN', currency)
                              
                            }}</span>
                          </template>
                        </el-table-column>

                        <el-table-column
                          prop=""
                          label="Số lượng"
                          sortable="custom"
                        >
                          <template slot-scope="scope">
                            <span>{{
                              Intl.NumberFormat().format(scope.row.quantity)
                            }}</span>
                          </template>
                        </el-table-column>

                        <el-table-column
                          prop=""
                          label="Tổng tiền"
                          sortable="custom"
                        >
                          <template slot-scope="scope">
                            <span>{{
                              scope.row.price_sale.toLocaleString('vi-VN', currency)
                            }}</span>
                          </template>
                        </el-table-column>
                      </el-table>
                    </div>
                  </div>
                  <div class="col-md-12 mt-4">
                    <div class="row">
                      <div class="col-md-8"></div>
                      <div class="col-md-4">
                        <table>
                          <tbody>
                            <tr>
                              <th scope="row" class="w-75">Tạm tính</th>
                              <td>
                                {{
                                  
                                    modelForm.total_price.toLocaleString('vi-VN', currency)
                                  
                                }}
                              </td>
                            </tr>
                            <tr>
                              <th scope="row">Phí vận chuyển</th>
                              <td>
                                {{
                                  modelForm.ship_fee.toLocaleString('vi-VN', currency)
                                }}
                              </td>
                            </tr>
                            <tr>
                              <th scope="row">Giảm giá</th>
                              <td>
                                {{
                                  modelForm.discount.toLocaleString('vi-VN', currency)
                                }}
                              </td>
                            </tr>
                            <tr>
                              <th scope="row">Tổng tiền</th>
                              <td>
                                {{
                                  
                                    modelForm.pay_price.toLocaleString('vi-VN', currency)
                                  
                                }}
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div
                    class="col-md-12 text-right"
                    v-if="
                      modelForm.order_type == 'mua_hang' &&
                      modelForm.status_value == 'created'
                    "
                  >
                    <el-button type="primary" @click="dialogVisible = true"
                      >Nhận đơn</el-button
                    >
                  </div>
                  <el-dialog
                    title="Xác nhận"
                    :visible.sync="dialogVisible"
                    width="30%"
                  >
                    <span>Xác nhận đơn mua hàng</span>
                    <span slot="footer" class="dialog-footer">
                      <el-button @click="dialogVisible = false">Hủy</el-button>
                      <el-button type="primary" @click="onSubmit"
                        >Xác nhận</el-button
                      >
                    </span>
                  </el-dialog>
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
export default {
  props: {
    locales: { default: null },
    pageTitle: { default: null, String },
  },
  mixins: [SingleFileSelector, MultipleFileSelector],
  components: {
    MultipleMedia,
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

    showDataModal(key) {
      this.dialogRank = true;
    },

      onSubmit() {
  

      axios
        .post(this.getRoute())
        .then((response) => {
          this.loading = false;
          this.$message({
            type: "success",
            message: response.data.message,
          });
          this.dialogVisible = false;
          this.$router.push({ name: "shop.ordersbuysell.index" });
        })
        .catch((error) => {
          this.loading = false;
          this.dialogVisible = false;
          this.$notify.error({
            title: 'Lỗi xác nhận',
            message: error.response.data.message,
          });
        });
    },
    getRoute() {
      return route("apishop.orders.update_buysell", {
        orders: this.$route.params.ordersId,
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

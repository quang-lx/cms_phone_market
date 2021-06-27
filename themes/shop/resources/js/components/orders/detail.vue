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
                >{{ $t("orders.label.orders") }}
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
                            <div><h5>Thông tin user đặt hàng</h5></div>
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
                        <div><h5>Địa chỉ lấy hàng</h5></div>
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
                        <div><h5>Đặt sửa tại</h5></div>

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
                        <div><h5>Thanh toán</h5></div>
                            {{ modelForm.pay_method_name }}

                        <div><h5>Chi phí</h5></div>
                        <div>
                            <span v-currency="modelForm.pay_price"></span>
                      </div>
                      </div>

                      <div class="col-md-3">
                        <div><h5>Trạng thái đơn hàng</h5></div>
                        <div v-for="(item, index) in modelForm.order_status_history" :key="index">
                            <span>{{item.title}}</span>
                            <span>({{item.date}})</span>
                        </div>
                        <div>
                          <span>{{modelForm.status}}
                          </span>
                            <p v-if="modelForm.shop_done == 1">(Người bán đã xác nhận giao hàng thành công)</p>
                            <p v-if="
                              modelForm.order_type == 'sua_chua' &&
                              modelForm.type_other == 1 && modelForm.fix_time_date !=null &&
                              modelForm.status_value == 'wait_client'
                              ">(Chờ khách hàng xác nhận trên app)</p>
                        </div>

                      </div>

                      <div class="col-md-12 mt-3">
                          <span>Sản phẩm sửa chữa: </span>
                          <span>{{modelForm.product_name}}
                          </span>

                      </div>
                      <div class="col-md-12 mt-3">
                        <span>Thực trạng sản phẩm: </span>
                            <span v-html="modelForm.product_note"></span>
                        </div>
                      <div class="col-md-12 mt-4 text-right">
                          <status-repair :data="modelForm"></status-repair>
                      </div>
                       

                    </div>
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
import StatusRepair from "./status_repair";
export default {
  props: {
    locales: { default: null },
    pageTitle: { default: null, String },
  },
  mixins: [SingleFileSelector, MultipleFileSelector],
  components: {
    MultipleMedia,
    StatusRepair
  },
  data() {
    return {
      formLabelWidth: "120px",
      form: new Form(),
      loading: false,
      list_orders: [],
      modelForm: {},
      message: "",

    };
  },
  methods: {
    fetchData() {
      let routeUri = "";
      this.loading = true;
      routeUri = route("apishop.orders.find", {
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

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
                >{{ $t("orders.label.guarantee") }}
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
                        <div>{{ Intl.NumberFormat().format(modelForm.pay_price) }}</div>
                      </div>

                      <div class="col-md-3">
                        <div><h4>Trạng thái</h4></div>
                      </div>
                      <!-- <div class="col-md-12 mt-5">
                            {{ $t("orders.label.description") }}:
                            <div v-html="modelForm.description"></div>
                        </div> -->
                        <div
                          class="col-md-12 text-right"
                          v-if="
                            modelForm.order_type == 'bao_hanh' &&
                            modelForm.status_value == 'created'
                          "
                        >
                          <el-button type="primary" @click="dialogVisible = true">Nhận đơn</el-button>
                        
                        </div>
                           <el-dialog title="Xác nhận" :visible.sync="dialogVisible" width="30%">
                              <span>Xác nhận đơn bảo hành</span>
                              <span slot="footer" class="dialog-footer">
                                <el-button @click="dialogVisible = false">Hủy</el-button>
                                <el-button type="primary" @click="onSubmit">Xác nhận</el-button>
                              </span>
                           </el-dialog>
                        
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
      message: "",
      dialogVisible: false,
      
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
          this.$router.push({ name: "shop.ordersguarantee.index" });
        })
        .catch((error) => {
          this.loading = false;
          this.$notify.error({
            title: this.$t("mon.error.Title"),
            message: this.getSubmitError(this.form.errors),
          });
        });
    },

    getRoute() {
      return route("apishop.orders.update_guarantee", {
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

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
                      <div class="col-md-3">
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
                              {{ modelForm.ship_fullname }}
                            </div>
                            <div>
                              {{ modelForm.ship_phone }}
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
                      <div class="col-md-3">
                        <div><h4>Đặt bảo hành tại</h4></div>

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

                      <div class="col-md-3">
                        <div><h4>Trạng thái đơn hàng</h4></div>
                         <div v-for="(item, index) in modelForm.order_status_history" :key="index">
                            <span>{{item.title}}</span>
                            <span>({{item.date}})</span>
                        </div>
                        <div>
                          <span>{{modelForm.status}}</span>
                            <p v-if="modelForm.shop_done == 1  && modelForm.status_value!='done'">(Người bán đã xác nhận giao hàng thành công)</p>

                        </div>
                      </div>
                      <div class="col-md-12 mt-3">
                        <span>Sản phẩm bảo hành: </span>
                          <span>{{modelForm.product_name}}
                          </span>

                      </div>
                         <div class="col-md-12 mt-3">
                       <span>Tình trạng sản phẩm: </span>
                            <span v-html="modelForm.product_note"></span>
                        </div>
                         <div class="row mt-5 w-100">
                        <p class="col-12"> Ảnh/Video</p>
                       
                          <div class="col-3"
                            v-for="(item, index) in modelForm.files"
                            :key="index"
                          >
                          <video class="w-100"  v-if="item.media_type == 'video'" :src="item.path_string" controls></video>
                            <img v-else
                              :src="item.path_string"
                              class="w-100"
                              style="object-ielseit: contain"
                            />
                          </div>
                      </div>
                       <div class="col-md-12 mt-4 text-right">
                          <status-guarantee :data="modelForm"></status-guarantee>
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
import StatusGuarantee from "./status_guarantee";

export default {
  props: {
    locales: { default: null },
    pageTitle: { default: null, String },
  },
  mixins: [SingleFileSelector, MultipleFileSelector],
  components: {
    MultipleMedia,
    StatusGuarantee
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

  },
  mounted() {
    this.fetchData();
  },
  computed: {},
};
</script>

<style scoped></style>

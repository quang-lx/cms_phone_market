<template>
  <div v-if="modelForm && modelForm.id">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-12">
            <el-breadcrumb separator="/">
              <el-breadcrumb-item>
                <a href="/admin">{{ $t("mon.breadcrumb.home") }}</a>
              </el-breadcrumb-item>
              <el-breadcrumb-item :to="{ name: 'admin.product.index' }"
                >{{ $t("product.label.detail_product") }}
              </el-breadcrumb-item>
              <el-breadcrumb-item>
                Sản phẩm {{ modelForm.name }}
              </el-breadcrumb-item>
            </el-breadcrumb>
          </div>
          company
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
                            <div>
                              {{ $t("product.label.name") }}:
                              {{ modelForm.name }}
                            </div>
                            <div>
                              {{ $t("product.label.sku") }}:
                              {{ modelForm.sku }}
                            </div>
                            <div>
                              {{ $t("product.label.category_id") }}:
                              <span
                                v-for="(item, index) in modelForm.category_name"
                                :key="index"
                              >
                                <span
                                  v-if="
                                    modelForm.category_name.length - 1 == index
                                  "
                                >
                                  {{ item }}</span
                                >
                                <span v-else> {{ item }},</span>
                              </span>
                            </div>
                            <div>
                              {{ $t("product.label.brand_id") }}:
                              {{ modelForm.brand_name }}
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-3" v-if="modelForm.company">
                        <div>
                          {{ $t("product.label.company_id") }}:
                          {{ modelForm.company.name }}
                        </div>
                        <div>
                          {{ modelForm.company.email }}
                        </div>
                        <div>
                          {{ modelForm.company.phone }}
                        </div>
                        <div>
                          {{ modelForm.company.address }}
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div>
                          {{ $t("product.label.p_state") }}:
                          {{ modelForm.p_state_name }}
                        </div>
                        <div>
                          {{ $t("product.label.price") }}
                          :
                          {{ modelForm.price.toLocaleString('vi-VN', currency) }}
                        </div>
                        <div>
                          {{ $t("product.label.amount") }}
                          :
                          {{ modelForm.amount.toLocaleString('vi-VN', currency) }}
                        </div>
                        <div>
                          {{ $t("product.label.status") }}
                          :
                          {{ modelForm.amount_name }}
                        </div>
                        <div>
                          {{ $t("product.label.warranty_time") }}
                          :
                          {{ modelForm.warranty_time }}
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div>
                          {{ $t("product.label.p_weight") }}:
                          {{ modelForm.p_weight }} (gram)
                        </div>
                        <div>
                          {{ $t("product.label.packing_size") }}
                          :
                          {{ modelForm.s_height }} x {{ modelForm.s_width }} x
                          {{ modelForm.s_long }} (cm)
                        </div>
                      </div>
                      <div class="col-md-6 mt-5">
                        Thông tin chi tiết
                        <el-table
                          :data="modelForm.pinformations"
                          stripe
                          style="width: 100%"
                          v-loading.body="tableIsLoading"
                        >
                          <el-table-column prop="title"> </el-table-column>
                          <el-table-column prop="value"> </el-table-column>
                        </el-table>
                      </div>

                      <div class="col-md-6 mt-5"  v-if="modelForm.attribute_selected !=null">
                        Thuộc tính mở rộng
                        <el-table
                          :data="modelForm.attribute_selected.values"
                          stripe
                          style="width: 100%"
                          v-loading.body="tableIsLoading"
                        >
                          <el-table-column
                            prop="name"
                            :label="modelForm.attribute_selected.name"
                          >
                          </el-table-column>
                          <el-table-column prop="" label="Giá">
                            <template slot-scope="scope">
                              {{
                                  (scope.row.price*scope.row.sale_price/100).toLocaleString('vi-VN', currency)
                              }}
                            </template>
                          </el-table-column>
                        </el-table>
                      </div>
                      <div class="col-md-12 mt-5" v-if="modelForm.type==2">
                        {{ $t("product.label.fix_time") }}:
                        <div v-html="modelForm.fix_time"></div>
                        <div class="mt-3"> {{ $t("product.label.problem_id") }} :</div>
                        <div >
                          <span
                            v-for="(item, index) in modelForm.problem_id"
                            :key="index"
                          >
                            <span
                              v-if="modelForm.problem_id.length - 1 == index"
                            >
                              {{ item.title }}</span
                            >
                            <span v-else> {{ item.title }},</span>
                          </span>
                        </div>
                      </div>
                      <div class="col-md-12 mt-5">
                        Ảnh/Video
                        <div>
                          <span
                            v-for="(item, index) in modelForm.files"
                            :key="index"
                          >
                            <img
                              :src="item.path_string"
                              width="100"
                              height="100"
                              style="object-ielseit: contain"
                            />
                          </span>
                        </div>
                      </div>
                      <div class="col-md-12">
                        {{ $t("product.label.description") }}:
                        <div v-html="modelForm.description"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
              <div class="card-footer d-flex justify-content-end">
                <el-button class="btn btn-flat" size="small" @click="onCancel()"
                  >{{ $t("mon.button.cancel") }}
                </el-button>
              </div>
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
      list_product: [],
      modelForm: {},
      dialogRank: false,
      message: "",
    };
  },
  methods: {
    fetchData() {
      let routeUri = "";
      this.loading = true;
      routeUri = route("api.product.find", {
        product: this.$route.params.productId,
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
  },
  mounted() {
    this.fetchData();
  },
  computed: {},
};
</script>

<style scoped></style>

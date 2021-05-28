<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <el-breadcrumb separator="/">
                  <el-breadcrumb-item>
                    <a href="/admin">{{ $t("mon.breadcrumb.home") }}</a>
                  </el-breadcrumb-item>
                  <el-breadcrumb-item :to="{ name: 'admin.voucher.index' }"
                    >{{ $t("voucher.label.list") }}
                  </el-breadcrumb-item>
                  <el-breadcrumb-item> {{ $t(pageTitle) }} </el-breadcrumb-item>
                </el-breadcrumb>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <el-form
          ref="form"
          :model="modelForm"
          label-width="200px"
          label-position="top"
          v-loading.body="loading"
        >
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="row mb-20">
                    <div class="col-md-12">
                      <el-form-item label="Loại mã">
                        <el-radio-group v-model="modelForm.type">
                          <el-radio
                            v-for="item in list_type"
                            :key="item.value"
                            :value="item.value"
                            :label="item.value"
                            @change="changeTypeVoucher()"
                            >{{ item.label }}</el-radio
                          >
                        </el-radio-group>
                      </el-form-item>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <el-form-item
                        :label="$t('voucher.label.title')"
                        :class="{
                          'el-form-item is-error': form.errors.has('title'),
                        }"
                      >
                        <el-input v-model="modelForm.title"></el-input>
                        <div
                          class="el-form-item__error"
                          v-if="form.errors.has('title')"
                          v-text="form.errors.first('title')"
                        ></div>
                      </el-form-item>
                    </div>

                    <div class="col-md-12">
                      <el-form-item
                        :label="$t('voucher.label.code')"
                        :class="{
                          'el-form-item is-error': form.errors.has('code'),
                        }"
                      >
                        <el-input v-model="modelForm.code"></el-input>
                        <div
                          class="el-form-item__error"
                          v-if="form.errors.has('code')"
                          v-text="form.errors.first('code')"
                        ></div>

                        <div
                          class="form-text text-muted"
                          v-text="$t('voucher.label.code-help')"
                        ></div>
                      </el-form-item>
                    </div>

                    <div class="col-md-12">
                      <el-form-item label="Thời gian sử dụng mã">
                        <el-col :span="11">
                          <el-date-picker
                            type="datetime"
                            value-format="yyyy-MM-dd HH:mm:ss"
                            placeholder="Thời gian bắt đầu hiệu lực"
                            v-model="modelForm.actived_at"
                            style="width: 100%"
                            :class="{
                              'el-form-item is-error': form.errors.has('code'),
                            }"
                          ></el-date-picker>
                        </el-col>
                        <el-col class="line text-center" :span="2">-</el-col>
                        <el-col :span="11">
                          <el-date-picker
                            type="datetime"
                            value-format="yyyy-MM-dd HH:mm:ss"
                            placeholder="Thời gian kết thúc hiệu lực"
                            v-model="modelForm.expired_at"
                            style="width: 100%"
                            :class="{
                              'el-form-item is-error': form.errors.has('code'),
                            }"
                          ></el-date-picker>
                        </el-col>
                        <div
                          class="el-form-item__error"
                          v-if="form.errors.has('actived_at')"
                          v-text="form.errors.first('actived_at')"
                        ></div>

                        <div
                          class="el-form-item__error"
                          v-else-if="form.errors.has('expired_at')"
                          v-text="form.errors.first('expired_at')"
                        ></div>
                      </el-form-item>
                    </div>

                    <div class="col-md-12 check-discount-product hide">
                      <el-form-item
                        :label="$t('voucher.label.products')"
                        :class="{
                          'el-form-item is-error': form.errors.has('products'),
                        }"
                      >
                        <el-autocomplete
                          prefix-icon="el-icon-search"
                          v-model="modelForm.product_key"
                          :fetch-suggestions="onSearchProduct"
                          placeholder="Tìm sản phẩm"
                          @select="handleSelect"
                          clearable
                        ></el-autocomplete>
                        <div
                          class="el-form-item__error"
                          v-if="form.errors.has('products')"
                          v-text="form.errors.first('products')"
                        ></div>
                      </el-form-item>
                    </div>
                  </div>
                </div>

                <div class="offset-md-1 col-md-5">
                  <div class="col-md-10">
                    <div class="row mb-20">
                      <el-form-item label="Loại giảm giá">
                        <el-radio-group v-model="modelForm.discount_type">
                          <el-radio
                            v-for="item in list_discount_type"
                            :key="item.value"
                            :value="item.value"
                            :label="item.value"
                            >{{ item.label }}</el-radio
                          >
                        </el-radio-group>
                      </el-form-item>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <el-form-item
                        :label="$t('voucher.label.discount_amount')"
                        :class="{
                          'el-form-item is-error': form.errors.has(
                            'discount_amount'
                          ),
                        }"
                      >
                        <el-input
                          v-model="modelForm.discount_amount"
                        ></el-input>
                        <div
                          class="el-form-item__error"
                          v-if="form.errors.has('discount_amount')"
                          v-text="form.errors.first('discount_amount')"
                        ></div>
                      </el-form-item>
                    </div>

                    <div class="col-md-12">
                      <el-form-item
                        :label="$t('voucher.label.require_min_amount')"
                        :class="{
                          'el-form-item is-error': form.errors.has(
                            'require_min_amount'
                          ),
                        }"
                      >
                        <el-input
                          v-model="modelForm.require_min_amount"
                        ></el-input>
                        <div
                          class="el-form-item__error"
                          v-if="form.errors.has('require_min_amount')"
                          v-text="form.errors.first('require_min_amount')"
                        ></div>
                      </el-form-item>
                    </div>

                    <div class="col-md-12">
                      <el-form-item
                        :label="$t('voucher.label.total')"
                        :class="{
                          'el-form-item is-error': form.errors.has('total'),
                        }"
                      >
                        <el-input-number
                          v-model="modelForm.total"
                          :min="0"
                          :max="100000000"
                          placeholder="Tổng số mã"
                        ></el-input-number>
                        <div
                          class="el-form-item__error"
                          v-if="form.errors.has('total')"
                          v-text="form.errors.first('total')"
                        ></div>
                      </el-form-item>
                    </div>
                  </div>
                </div>
                <div class="clear-both"></div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <el-form-item
                    :label="$t('voucher.label.use_condition')"
                    :class="{
                      'el-form-item is-error': form.errors.has('use_condition'),
                    }"
                  >
                    <div slot="label">
                      <label class="el-form-item__label">{{
                        $t("voucher.label.use_condition")
                      }}</label>
                    </div>
                    <tinymce
                      v-model="modelForm.use_condition"
                      :height="500"
                    ></tinymce>
                    <div
                      class="el-form-item__error"
                      v-if="form.errors.has('use_condition')"
                      v-text="form.errors.first('use_condition')"
                    ></div>
                  </el-form-item>
                </div>

                <div class="col-md-12">
                  <el-form-item
                    :label="$t('voucher.label.description')"
                    :class="{
                      'el-form-item is-error': form.errors.has('description'),
                    }"
                  >
                    <div slot="label">
                      <label class="el-form-item__label">{{
                        $t("voucher.label.description")
                      }}</label>
                    </div>
                    <tinymce
                      v-model="modelForm.description"
                      :height="500"
                    ></tinymce>
                    <div
                      class="el-form-item__error"
                      v-if="form.errors.has('description')"
                      v-text="form.errors.first('description')"
                    ></div>
                  </el-form-item>
                </div>
              </div>
              <div class="row check-discount-product hide">
                <!-- Danh sách product được giảm giá -->
                <div class="col-md-12">
                  <el-table
                    :data="modelForm.products"
                    stripe
                    style="width: 100%"
                    ref="dataTable"
                    v-loading.body="tableIsLoading"
                  >
                    <el-table-column
                      prop="id"
                      :label="$t('product.label.id')"
                      width="75"
                    >
                    </el-table-column>

                    <el-table-column prop="" :label="$t('product.label.image')">
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
                      :label="$t('product.label.name')"
                    >
                    </el-table-column>

                    <el-table-column
                      prop=""
                      :label="$t('product.label.amount')"
                    >
                      <template slot-scope="scope">
                        {{ formatNumber(scope.row.amount) }}
                      </template>
                    </el-table-column>

                    <el-table-column
                      prop=""
                      :label="$t('product.label.category_id')"
                    >
                      <template slot-scope="scope">
                        <span
                          v-for="(item, index) in scope.row.category_name"
                          :key="index"
                        >
                          <span
                            v-if="scope.row.category_name.length - 1 == index"
                            class="dont-break-out"
                            >{{ item }}</span
                          >
                          <span v-else class="dont-break-out"
                            >{{ item }},&nbsp</span
                          >
                        </span>
                      </template>
                    </el-table-column>

                    <el-table-column prop="" :label="$t('product.label.price')">
                      <template slot-scope="scope">
                        {{ formatNumber(scope.row.price) }}
                      </template>
                    </el-table-column>

                    <el-table-column
                      prop="status"
                      :label="$t('product.list.status')"
                    >
                      <template slot-scope="scope">
                        <span :style="{ color: scope.row.status_color }">{{
                          scope.row.status_name
                        }}</span>
                      </template>
                    </el-table-column>

                    <el-table-column prop="actions" width="130">
                      <template slot-scope="scope">
                        <button
                          type="button"
                          class="el-button el-button--danger el-button--mini"
                          @click="deleteProductVoucher(scope.row)"
                        >
                          <span><i class="fas fa-trash"></i></span>
                        </button>
                      </template>
                    </el-table-column>
                  </el-table>
                </div>
                <!-- End table -->
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-12">
              <div class="card-footer d-flex justify-content-end">
                <el-button
                  type="primary"
                  @click="onSubmit()"
                  size="small"
                  :loading="loading"
                  class="btn btn-flat"
                >
                  {{ $t("mon.button.save") }}
                </el-button>
                <el-button class="btn btn-flat" size="small" @click="onCancel()"
                  >{{ $t("mon.button.cancel") }}
                </el-button>
              </div>
            </div>
          </div>
        </el-form>
      </div>
    </section>
  </div>
</template>

<script>
import axios from "axios";
import Form from "form-backend-validation";
import Tinymce from "../utils/Tinymce";

export default {
  components: {
    Tinymce,
  },
  props: {
    pageTitle: { default: null, String },
  },
  data() {
    return {
      form: new Form(),
      loading: false,
      modelForm: {
        title: "",
        code: "",
        type: 1,
        discount_type: 1,
        actived_at: new Date("Y-m-d H:i:s"),
        expired_at: new Date("Y-m-d H:i:s"),
        products: [],
        discount_amount: "",
        require_min_amount: "",
        total: "",
        product_key: "",
        use_condition: "",
        description: ""
      },
      locales: window.MonCMS.locales,
      list_discount_type: [
        {
          value: 1,
          label: "Theo số tiền",
        },
        {
          value: 2,
          label: "Theo %",
        },
      ],

      list_type: [
        {
          value: 1,
          label: "Giảm giá toàn cửa hàng",
        },
        {
          value: 2,
          label: "Giảm giá sản phẩm",
        },
      ],

      productSearchResult: [],
    };
  },
  methods: {
    onSubmit() {
      this.form = new Form(_.merge(this.modelForm, {}));
      this.loading = true;

      this.form
        .post(this.getRoute())
        .then((response) => {
          this.loading = false;
          this.$message({
            type: "success",
            message: response.message,
          });
          this.$router.push({ name: "admin.voucher.index" });
        })
        .catch((error) => {
          this.loading = false;
          this.$notify.error({
            title: this.$t("mon.error.Title"),
            message: this.getSubmitError(this.form.errors),
          });
        });
    },
    onCancel() {
      this.$confirm(this.$t("mon.cancel.Are you sure to cancel?"), {
        confirmButtonText: this.$t("mon.cancel.Yes"),
        cancelButtonText: this.$t("mon.cancel.No"),
        type: "warning",
      })
        .then(() => {
          this.$router.push({ name: "admin.voucher.index" });
        })
        .catch(() => {});
    },

    fetchData() {
      this.loading = true;
      let locale = this.$route.params.locale ? this.$route.params.locale : "en";
      axios
        .get(
          route("api.admin.voucher.find", {
            voucher: this.$route.params.voucherId,
          })
        )
        .then((response) => {
          this.loading = false;
          this.modelForm = response.data.data;
          if (this.modelForm.type == "2") {
            $(".check-discount-product")
              .removeClass("hide show")
              .addClass("show");
          }
        });
    },

    getRoute() {
      if (this.$route.params.voucherId !== undefined) {
        return route("api.admin.voucher.update", {
          voucher: this.$route.params.voucherId,
        });
      }
      return route("api.admin.voucher.store");
    },
    fetchProduct(queryString) {
      const properties = {
        page: 0,
        per_page: 1000,
        search: queryString,
        source: "voucher",
      };

      axios
        .get(route("api.product.index", _.merge(properties, {})))
        .then((response) => {
          this.productSearchResult = response.data.data;
        });
    },
    onSearchProduct(queryString, cb) {
      this.fetchProduct(queryString);
      cb(this.productSearchResult);
    },
    formatNumber(number) {
      return number.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
    },
    handleSelect(item) {
      //add thêm vào biến products lưu danh sách các product đc giảm giá
      this.modelForm.products.push(item);
    },
    deleteProductVoucher(item) {
      var removeByAttr = function (arr, attr, value) {
        var i = arr.length;
        while (i--) {
          if (
            arr[i] &&
            arr[i].hasOwnProperty(attr) &&
            arguments.length > 2 &&
            arr[i][attr] === value
          ) {
            arr.splice(i, 1);
          }
        }
        return arr;
      };
      removeByAttr(this.modelForm.products, "id", item.id);
    },

    changeTypeVoucher() {
      $(".check-discount-product").toggleClass("hide").toggleClass("show");
    },
  },
  mounted() {
    if (this.$route.params.voucherId !== undefined) {
      this.fetchData();
    }
  },
  computed: {},
};
</script>

<style scoped>
.mb-20 {
  margin-bottom: 20px;
}
.mb-10 {
  margin-bottom: 10px;
}
.clear-both {
  clear: both;
}
.el-autocomplete {
  width: 100%;
}
.hide {
  display: none;
}
.show {
  display: block;
}
</style>

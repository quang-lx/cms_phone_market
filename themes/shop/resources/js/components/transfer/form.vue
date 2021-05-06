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
                    <a href="/shop-admin">{{ $t("mon.breadcrumb.home") }}</a>
                  </el-breadcrumb-item>
                  <el-breadcrumb-item :to="{ name: 'shop.transfer.index' }"
                    >{{ $t("transfer.label.manager") }}
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
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header ui-sortable-handle" style="cursor: move">
                <h3 class="card-title">
                  {{ $t(pageTitle)
                  }}<span v-if="modelForm.title"
                    >: &nbsp{{ modelForm.title }}</span
                  >
                </h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body" style="padding-top: 20px">
                <el-form
                  ref="form"
                  :model="modelForm"
                  label-width="200px"
                  label-position="left"
                  v-loading.body="loading"
                >
                  <div class="row">
                    <div
                      class="col-md-6"
                      style="padding-top: 10px; padding-right: 30px"
                    >
                      <div class="row">
                        <div class="col-md-12">
                          <el-form-item
                            :label="$t('transfer.label.title')"
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
                            :label="$t('transfer.label.shop_id')"
                            :class="{
                              'el-form-item is-error': form.errors.has(
                                'shop_id'
                              ),
                            }"
                          >
                            <el-select
                              v-model="modelForm.shop_id"
                              :placeholder="$t('transfer.label.shop_id')"
                              filterable
                              style="width: 100% !important"
                            >
                              <el-option
                                v-for="item in shopArr"
                                :key="'shop' + item.id"
                                :label="item.name"
                                :value="item.id"
                              >
                              </el-option>
                            </el-select>
                            <div
                              class="el-form-item__error"
                              v-if="form.errors.has('shop_id')"
                              v-text="form.errors.first('shop_id')"
                            ></div>
                          </el-form-item>
                        </div>

                        <div class="col-md-12">
                          <el-form-item
                            :label="$t('transfer.label.products')"
                            :class="{
                              'el-form-item is-error': form.errors.has(
                                'products'
                              ),
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
                    <div class="col-md-6" style="padding-top: 10px">
                      <div class="row">
                        <div class="col-md-12">
                          <el-form-item
                            :label="$t('transfer.label.received_at')"
                            :class="{
                              'el-form-item is-error': form.errors.has(
                                'received_at'
                              ),
                            }"
                          >
                            <el-date-picker
                              v-model="modelForm.received_at"
                              value-format="yyyy-MM-dd HH:mm:ss"
                              type="datetime"
                              placeholder="Thời gian chuyển"
                            >
                            </el-date-picker>
                            <div
                              class="el-form-item__error"
                              v-if="form.errors.has('received_at')"
                              v-text="form.errors.first('received_at')"
                            ></div>
                          </el-form-item>
                        </div>
                      </div>
                    </div>
                  </div>
                </el-form>
              </div>
            </div>

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Thông tin chi tiết vật tư</h3>
              </div>
              <div class="card-body">
                <div
                  class="row mt-2"
                  v-for="(pinfo, key) in modelForm.products"
                  :key="key"
                >
                  <div class="col-md-3">
                    <el-input v-model="pinfo.name"></el-input>
                  </div>

                  <div class="col-md-2">
                    <el-input-number style="width: 100%"
                            v-model="pinfo.count" :min="1"
                            :max="100000000"
                            placeholder="Số lượng"></el-input-number>
                  </div>
                  <div class="col-md-1 text-right d-flex justify-content-end align-items-center">
                    <i
                      class="el-icon-circle-close"
                      style="color: red; cursor: pointer"
                      @click="removeInfo(key)"
                    ></i>
                  </div>
                </div>
              </div>

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
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import axios from "axios";
import Form from "form-backend-validation";

export default {
  props: {
    pageTitle: { default: null, String },
  },
  data() {
    return {
      form: new Form(),
      loading: false,
      modelForm: {
        title: "",
        status: 1,
        received_at: new Date("Y-m-d H:i:s"),
        shop_id: "",
        product_key: "",
        products: [],
      },
      shopArr: [],
      locales: window.MonCMS.locales,
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
          this.$router.push({ name: "shop.transfer.index" });
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
          this.$router.push({ name: "shop.transfer.index" });
        })
        .catch(() => {});
    },

    fetchData() {
      this.loading = true;
      let locale = this.$route.params.locale ? this.$route.params.locale : "en";
      axios
        .get(
          route("apishop.transfer.find", {
            transferhistory: this.$route.params.transferId,
          })
        )
        .then((response) => {
          this.loading = false;
          this.modelForm = response.data.data;
        });
    },

    getRoute() {
      if (this.$route.params.transferId !== undefined) {
        return route("apishop.transfer.update", {
          transferhistory: this.$route.params.transferId,
        });
      }
      return route("apishop.transfer.store");
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
          this.shopArr = response.data.data;
        });
    },

    onSearchProduct(queryString, cb) {
      this.fetchProduct(queryString);
      cb(this.productSearchResult);
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

    handleSelect(item) {
      console.log(item);
      //add thêm vào biến products lưu danh sách các product đc giảm giá
      this.modelForm.products.push(item);
    },
    removeInfo(key) {
      this.modelForm.products.splice(key,1);
    },
  },

  mounted() {
    this.fetchShop();
    if (this.$route.params.transferId !== undefined) {
      this.fetchData();
    }
  },
  computed: {},
};
</script>

<style scoped>
.el-autocomplete {
  width: 100%;
}
.el-date-editor.el-input,
.el-date-editor.el-input__inner {
  width: 100%;
}
</style>

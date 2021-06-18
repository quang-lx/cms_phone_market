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
                  <el-breadcrumb-item :to="{ name: 'shop.storageproduct.index' }"
                    >{{ $t("storageproduct.label.manager") }}
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
                  :disabled="true"
                >
                  <div class="row">
                    <div
                      class="col-md-6"
                      style="padding-top: 10px; padding-right: 30px"
                    >
                      <div class="row">
                        <div class="col-md-12">
                          <el-form-item
                            :label="$t('storageproduct.label.title')"
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
                            :label="$t('storageproduct.label.type')"
                            :class="{
                              'el-form-item is-error': form.errors.has(
                                'type'
                              ),
                            }"
                          >
                            <el-radio-group v-model="modelForm.type" :disabled="!modelForm.isEdit">
                              <el-radio
                                v-for="item in listType"
                                :key="item.value"
                                :value="item.value"
                                :label="item.value"
                                >{{ item.label }}</el-radio>
                            </el-radio-group>
                          </el-form-item>
                        </div>

                        <div class="col-md-12">
                          <el-form-item
                            :label="$t('storageproduct.label.shop_id')"
                            :class="{
                              'el-form-item is-error': form.errors.has(
                                'shop_id'
                              ),
                            }"
                          >
                            <el-select
                              v-model="modelForm.shop_id"
                              :placeholder="$t('storageproduct.label.shop_id')"
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

                      </div>
                    </div>
                    <div class="col-md-6" style="padding-top: 10px">
                      <div class="row">
                        <div class="col-md-12">
                          <el-form-item
                            :label="$t('storageproduct.label.received_at')"
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
                              format="HH:mm dd/MM/yyyy"
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

                  <div class="row">
                    <div class="col-md-12">
                        <el-form-item :label="$t('transfer.label.note')"
                                      :class="{'el-form-item is-error': form.errors.has(  'note') }">
                            <div slot="label">
                                <label class="el-form-item__label">{{$t('transfer.label.note')}}</label>
                            </div>
                          <el-input v-model="modelForm.note" type="textarea"
                                    :rows="2"></el-input>

                            <div class="el-form-item__error"
                                  v-if="form.errors.has('note')"
                                  v-text="form.errors.first('note')"></div>
                        </el-form-item>
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
                  v-for="(pinfo, key) in modelForm.vtProducts"
                  :key="key"
                >
                  <div class="col-md-3">
                      <el-select
                          v-model="pinfo.catId"
                          filterable
                          @change="changeVtCategory(key)"
                          :disabled="!modelForm.isEdit"
                          placeholder="Chọn danh mục">
                          <el-option
                                label="Chọn danh mục"
                                value=""
                              >
                          </el-option>
                          <el-option
                            v-for="item in list_vtcategory"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id"
                          >
                          </el-option>
                      </el-select>
                  </div>
                  <div class="col-md-3">
                    <el-select
                        v-model="pinfo.id"
                        filterable
                        :disabled="!modelForm.isEdit"
                        placeholder="Chọn vật tư">
                        <el-option
                          v-for="item in pinfo.listVtProduct"
                          :key="item.id"
                          :label="item.name"
                          :value="item.id"
                        >
                        </el-option>
                    </el-select>
                  </div>

                  <div class="col-md-2">
                      <cleave v-model="pinfo.count" :options="options" class="form-control"
                         name="count" placeholder="Số lượng" :disabled="!modelForm.isEdit"></cleave>
                  </div>
                  <div v-if="modelForm.isEdit" class="col-md-1 text-right d-flex justify-content-end align-items-center">
                    <i
                      class="el-icon-circle-close"
                      style="color: red; cursor: pointer"
                      @click="removeInfo(key)"
                    ></i>
                  </div>


                </div>
                <div
                    class="el-form-item__error row"
                    v-if="form.errors.has('vtproduct')"
                    v-text="form.errors.first('vtproduct')"
                    style="position: inherit !important; margin-left:0px !important"
                  ></div>

              </div>

              <div class="card-footer d-flex justify-content-end">
                <el-button
                  type="primary"
                  @click="onSubmit()"
                  size="small"
                  :loading="loading"
                  class="btn btn-flat"
                  :disabled="modelForm.status != 1 || is_admin"
                >
                  {{ $t("storageproduct.button.save") }}
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
import Cleave from 'vue-cleave-component';
import Tinymce from '../utils/Tinymce';

export default {
  props: {
    pageTitle: { default: null, String },
  },
  components: {
    Cleave, Tinymce
  },
  data() {
    return {
      options: {
        prefix: '',
        numeral: true,
        numeralPositiveOnly: true,
        noImmediatePrefix: true,
        rawValueTrimPrefix: true,
        numeralIntegerScale: 12,
        numeralDecimalScale: 0
      },
      form: new Form(),
      loading: false,
      modelForm: {
        title: "",
        status: 1,
        received_at: new Date("Y-m-d H:i:s"),
        to_shop_id: "",
        product_key: "",
        vtProducts: [],
        type: 1,
        isEdit: true,
        note:''
      },
      shopArr: [],
      locales: window.MonCMS.locales,
      productSearchResult: [],
      listType: [
        {
          value: 1,
          label: "Xuất kho",
        },
        {
          value: 2,
          label: "Chuyển kho",
        },
      ],
      list_vtcategory: [],
      is_admin : window.MonCMS.current_user.is_admin_company
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
          this.$router.push({ name: "shop.storageproduct.index" });
        })
        .catch((error) => {
          this.loading = false;
          this.$notify.error({
            title: this.$t("mon.error.Title"),
            message: this.getSubmitError(this.form.errors),
          });
        });
    },

    fetchData() {
      this.loading = true;
      let locale = this.$route.params.locale ? this.$route.params.locale : "en";
      axios
        .get(
          route("apishop.storageproduct.find", {
            storageproduct: this.$route.params.storageproductId,
          })
        )
        .then((response) => {
          this.loading = false;
          this.modelForm = response.data.data;
        });
    },

    getRoute() {
      if (this.$route.params.storageproductId !== undefined) {
        return route("apishop.storageproduct.update", {
          storageproduct: this.$route.params.storageproductId,
        });
      }
      return route("apishop.storageproduct.store");
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
      //add thêm vào biến products lưu danh sách các product đc giảm giá
      this.modelForm.products.push(item);
    },
    removeInfo(key) {
      this.modelForm.products.splice(key,1);
    },
    fetchVtCategory() {
      let routeUri = "";
      this.loading = true;
      routeUri = route("apishop.vtcategory.index", { page: 1, per_page: 1000, not_check_shop: true});
      axios.get(routeUri).then((response) => {
        this.loading = false;
        this.list_vtcategory = response.data.data;
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
          this.shopArr = response.data.data;
        });
    },

    changeVtCategory(key){
      //load danh sach vật tư của category mới chọn
      let vtCatId = this.modelForm.vtProducts[key].catId;
      this.fetchVtProduct(vtCatId, key);
    },
    fetchVtProduct(vtCatId, key) {
      this.loading = true;
      const properties = {
        page: 0,
        per_page: 1000,
        catId: vtCatId
      };

      axios
        .get(route("apishop.vtproduct.index", _.merge(properties, {})))
        .then((response) => {
          this.loading = false;
          this.modelForm.vtProducts[key].listVtProduct = response.data.data;
          return response.data.data;
        });
    },
  },

  mounted() {
    this.fetchShop();
    this.fetchVtCategory();
    if (this.$route.params.storageproductId !== undefined) {
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

<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-12">
            <el-breadcrumb separator="/">
              <el-breadcrumb-item>
                <a href="/shop-admin">{{ $t("mon.breadcrumb.home") }}</a>
              </el-breadcrumb-item>
              <el-breadcrumb-item :to="{ name: 'shop.ordersbuysell.index' }"
                >{{ $t("orders.label.buysell") }}
              </el-breadcrumb-item>
              <el-breadcrumb-item> {{ $t(pageTitle) }} </el-breadcrumb-item>
            </el-breadcrumb>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <el-form ref="form"
                      :model="modelForm"
                      label-width="200px"
                      label-position="top"
                      v-loading.body="loading"
            >
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                              <div class="row">
                                <div class="col-md-12">
                                  <el-form-item
                                    :label="$t('orders.label.phone')"
                                    :class="{
                                      'el-form-item is-error': form.errors.has('phone'),
                                    }"
                                  >
                                    <el-input v-model="modelForm.phone" type="number"></el-input>
                                    <div
                                      class="el-form-item__error"
                                      v-if="form.errors.has('phone')"
                                      v-text="form.errors.first('phone')"
                                    ></div>
                                  </el-form-item>
                                </div>

                                <div class="col-md-12">
                                  <el-form-item
                                    :label="$t('orders.label.customer_name')"
                                    :class="{
                                      'el-form-item is-error': form.errors.has('customer_name'),
                                    }"
                                  >
                                    <el-input v-model="modelForm.customer_name"></el-input>
                                    <div
                                      class="el-form-item__error"
                                      v-if="form.errors.has('customer_name')"
                                      v-text="form.errors.first('customer_name')"
                                    ></div>
                                  </el-form-item>
                                </div>

                                <div class="col-md-12">
                                  <el-form-item
                                    :label="$t('orders.label.created_at')"
                                    :class="{
                                      'el-form-item is-error': form.errors.has('created_at'),
                                    }"
                                  >
                                    <el-date-picker
                                      type="datetime"
                                      value-format="yyyy-MM-dd HH:mm:ss"
                                      format="HH:mm dd/MM/yyyy"
                                      placeholder="Thời gian tiếp nhận"
                                      v-model="modelForm.created_at"
                                      style="width: 100%"
                                      :class="{'el-form-item is-error': form.errors.has('created_at'),}"
                                    ></el-date-picker>
                                    <div
                                      class="el-form-item__error"
                                      v-if="form.errors.has('created_at')"
                                      v-text="form.errors.first('created_at')"
                                    ></div>
                                  </el-form-item>
                                </div>

                                <div class="col-md-12 check-discount-product hide">
                                  <el-form-item
                                    :label="$t('orders.label.product_id')"
                                    :class="{
                                      'el-form-item is-error': form.errors.has('product_id'),
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
                                      v-if="form.errors.has('product_id')"
                                      v-text="form.errors.first('product_id')"
                                    ></div>
                                  </el-form-item>
                                </div>

                                <div class="col-md-6">
                                    <el-form-item :label="$t('orders.label.price')" 
                                                  :class="{'el-form-item is-error': form.errors.has('price') }">
                                        <cleave v-model="modelForm.price" :options="options" 
                                            class="form-control" name="price" :disabled="true"></cleave>
                                        <div class="el-form-item__error"
                                              v-if="form.errors.has('price')"
                                              v-text="form.errors.first('price')"></div>
                                    </el-form-item>
                                </div>
                                <div class="col-md-6">
                                    <el-form-item :label="$t('product.label.sale_price')" 
                                                  :class="{'el-form-item is-error': form.errors.has('sale_price') }">
                                        <cleave v-model="modelForm.sale_price" :options="options_odd_number" 
                                            class="form-control" name="sale_price" :disabled="true"></cleave>
                                        <div class="el-form-item__error"
                                              v-if="form.errors.has('sale_price')"
                                              v-text="form.errors.first('sale_price')"></div>
                                    </el-form-item>
                                </div>


                              </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <el-form-item
                                    :label="$t('product.label.attribute extend')"
                                    :class="{
                                      'el-form-item is-error': form.errors.has('list_attribute'),
                                    }"
                                >
                                    <el-select clearable
                                        v-model="modelForm.attribute_id"
                                        placeholder=""
                                        :disabled="true"
                                    >
                                        <el-option
                                            v-for="item in list_attribute"
                                            :key="item.id"
                                            :label="item.name"
                                            :value="item.id"
                                        >
                                        </el-option>
                                    </el-select>
                                    <div
                                        class="el-form-item__error"
                                        v-if="form.errors.has('list_attribute')"
                                        v-text="form.errors.first('list_attribute')"
                                    ></div>
                                </el-form-item>

                            </div>
                            <div class="card-body" v-if="modelForm.attribute_selected">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class = "card-title">{{modelForm.attribute_selected.name}}</h3>
                                                <div class="card-tools" >
                                                    <el-select
                                                        v-model="value_id"
                                                        :disabled="values.length == 0"
                                                    >
                                                        <el-option
                                                            v-if="values.length> 0 "
                                                            v-for="item in values"
                                                            :key="item.id"
                                                            :label="item.name"
                                                            :value="item.id"
                                                        >
                                                        </el-option>
                                                    </el-select>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-2 col-sm-6"> </div>
                                                    <div class="col-md-3 col-sm-6">
                                                        Giá
                                                    </div>
                                                    <div class="col-md-3 col-sm-6">
                                                        Chiết khấu (%)
                                                    </div>
                                                    <div class="col-md-3 col-sm-6">
                                                        Số lượng
                                                    </div>
                                                    <div
                                                        class="col-md-1 col-sm-6 text-right d-flex justify-content-end align-items-center">

                                                    </div>
                                                </div>
                                                <div class="row"
                                                      v-for="(itemValue, valueKey) in modelForm.attribute_selected.values"
                                                      :key="valueKey">
                                                    <div class="col-12 mt-2">
                                                        <div class="row">
                                                            <div class="col-md-2 col-sm-6"> {{itemValue.name}}</div>
                                                            <div class="col-md-3 col-sm-6">
                                                                <cleave v-model="itemValue.price" :options="options" class="form-control" name="price" :disabled="true"></cleave>
                                                            </div>
                                                            <div class="col-md-3 col-sm-6">
                                                                <cleave v-model="itemValue.sale_price" :options="options" class="form-control" name="sale_price" :disabled="true"></cleave>
                                                            </div>
                                                            <div class="col-md-3 col-sm-6">
                                                              <cleave v-model="itemValue.amount" :options="options" class="form-control" name="amount"></cleave>

                                                            </div>
                                                            <div
                                                                class="col-md-1 col-sm-6 text-right d-flex justify-content-end align-items-center">
                                                                <i class="el-icon-circle-close" style="color:red; cursor:pointer"
                                                                    @click="removeAttributeValue(itemValue.id)"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 ">
                                        <el-form-item :label="$t('orders.label.category_id')"
                                                      :class="{'el-form-item is-error': form.errors.has('category_id') }">
                                            <div class="tree-container">

                                                <el-checkbox-group v-model="modelForm.category_id"  class="problem-container">
                                                    <el-checkbox v-for="(item,key) in category_tree_data" :key="key"
                                                                  :label="item.id"> {{item.name}}
                                                    </el-checkbox>
                                                </el-checkbox-group>
                                            </div>

                                            <div class="el-form-item__error"
                                                  v-if="form.errors.has('category_id')"
                                                  v-text="form.errors.first('category_id')"></div>
                                        </el-form-item>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                  <div class="col-md-12">
                                    <el-form-item :label="$t('orders.label.brand_id')"
                                                  :class="{'el-form-item is-error': form.errors.has(  'brand_id') }">

                                        <el-select v-model="modelForm.brand_id"
                                                    :placeholder="$t('orders.label.brand_id')"
                                                    filterable style="width: 100% !important">
                                            <el-option
                                                v-for="item in brandArr"
                                                :key="'brand'+ item.id"
                                                :label="item.name"
                                                :value="item.id">
                                            </el-option>

                                        </el-select>
                                        <div class="el-form-item__error"
                                              v-if="form.errors.has('brand_id')"
                                              v-text="form.errors.first('brand_id')"></div>
                                    </el-form-item>
                                  </div>

                                  <div class="col-md-12">
                                    <el-form-item :label="$t('orders.label.payment_method')"
                                                  :class="{'el-form-item is-error': form.errors.has(  'payment_method') }">

                                        <el-select v-model="modelForm.payment_method"
                                                    :placeholder="$t('orders.label.payment_method')"
                                                    filterable style="width: 100% !important">
                                            <el-option
                                                v-for="item in paymentMethodArr"
                                                :key="'brand'+ item.id"
                                                :label="item.name"
                                                :value="item.id">
                                            </el-option>

                                        </el-select>
                                        <div class="el-form-item__error"
                                              v-if="form.errors.has('payment_method')"
                                              v-text="form.errors.first('payment_method')"></div>
                                    </el-form-item>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card-footer d-flex justify-content-end">
                            <el-button type="primary" @click="onSubmit()" size="small" :loading="loading"
                                        class="btn btn-flat ">
                                {{ $t('orders.label.button.save') }}
                            </el-button>
                            <el-button class="btn btn-flat " size="small" @click="onCancel()">{{
                                $t('mon.button.cancel') }}
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
import Cleave from 'vue-cleave-component';

export default {
  props: {
    locales: { default: null },
    pageTitle: { default: null, String },
  },
  components: {
    Cleave
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
      options_odd_number: {
        prefix: '',
        numeral: true,
        numeralPositiveOnly: true,
        noImmediatePrefix: true,
        rawValueTrimPrefix: true,
        numeralIntegerScale: 12,
        numeralDecimalScale: 2
      },
      form: new Form(),
      loading: false,
      modelForm: {
        phone: '',
        customer_name: '',
        category_id: [],
        brand_id: '',
        price: '',
        sale_price: '',
        created_at: new Date(),
        payment_method: 1,
        product_id: '',
        attribute_selected: null,
      },
      brandArr: [],
      paymentMethodArr: [],
      currentShop : window.MonCMS.current_user.shop_id,
      productSearchResult: [],
      category_tree_data: [],
      list_problem: [],
      list_attribute: [],
      list_information: [],
      value_id: '',
    };
  },
  methods: {
    onSearchProduct(queryString, cb) {
      this.fetchProduct(queryString);
      cb(this.productSearchResult);
    },
    fetchProduct(queryString) {
      const properties = {
        page: 0,
        per_page: 1000,
        search: queryString,
        source: 'voucher',
        shop_id: this.currentShop
      
      };

      axios
        .get(route("apishop.product.index", _.merge(properties, {})))
        .then((response) => {
          this.productSearchResult = response.data.data;
        });
    },
    handleSelect(item) {
      //add thêm vào biến products lưu danh sách các product đc giảm giá
      // this.modelForm.products.push(item);
      this.modelForm.product_id = item.id;
      this.modelForm.price = item.price;
      this.modelForm.sale_price = item.sale_price;
      this.modelForm.attribute_selected = item.attribute_selected;
      console.log(this.modelForm.attribute_selected);
    },
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
          this.$router.push({ name: "shop.ordersbuysell.index" });
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
          this.$router.push({ name: "shop.pinformation.index" });
        })
        .catch(() => {});
    },

    fetchData() {
      let routeUri = "";
      if (this.$route.params.pinformationId !== undefined) {
        this.loading = true;
        routeUri = route("apishop.pinformation.find", {
          pinformation: this.$route.params.pinformationId,
        });
        axios.get(routeUri).then((response) => {
          this.loading = false;
          this.modelForm = response.data.data;
          this.modelForm.is_new = false;
        });
      } else {
        this.modelForm.is_new = true;
      }
    },

    getRoute() {
      if (this.$route.params.orderId !== undefined) {
        return route("apishop.orders.updateBuysell", {
          orderId: this.$route.params.orderId,
        });
      }
      return route("apishop.orders.storeBuysell");
    },

      fetchCategory() {
        const properties = {
          page: 0,
          per_page: 1000,

        };

        axios.get(route('apishop.product.tree', _.merge(properties, {})))
        .then((response) => {

          this.category_tree_data = response.data.categories_tree;
          this.list_problem = response.data.list_problem;
          this.list_attribute = response.data.list_attribute;
          this.list_information = response.data.list_information;

        });
      },

    fetchBrand() {
      const properties = {
        page: 0,
        per_page: 1000,

      };

      axios.get(route('apishop.brand.index', _.merge(properties, {})))
      .then((response) => {

        this.brandArr = response.data;

      });
    },

    fetchPaymentMethod() {
      const properties = {
        page: 0,
        per_page: 1000,

      };

      axios.get(route('apishop.paymentmethod.index', _.merge(properties, {})))
      .then((response) => {

        this.paymentMethodArr = response.data.data;

      });
    },

    removeAttributeValue(attribute_value_id) {
      const valueIndex = _.findIndex(this.modelForm.attribute_selected.values, {id: attribute_value_id})
      if (valueIndex !== -1) {
        this.modelForm.attribute_selected.values.splice(valueIndex, 1)
      }
    },
  },
  mounted() {
    this.fetchCategory();
    this.fetchBrand();
    this.fetchPaymentMethod();
    this.fetchData();
  },
  computed: {
    values: function() {
      if (this.modelForm.attribute_id) {
        const index = _.findIndex(this.list_attribute,{id: this.modelForm.attribute_id});
        if (index !== -1) {
          const values  = this.list_attribute[index].values
          return values.filter(item => _.findIndex(this.modelForm.attribute_selected.values, {id: item.id}) === -1)
        }
      }
      return []
    }
  },
};
</script>

<style scoped>
    .el-autocomplete {
        width: 100%;
    }

    .tree-container {
        max-height: 200px;
        overflow-y: auto;
    }

    .problem-container {
        max-height: 200px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }
</style>

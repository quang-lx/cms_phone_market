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
              <el-breadcrumb-item :to="{ name: 'shop.orders.index' }"
                >{{ $t("orders.label.orders") }}
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
                                    <!-- <el-input v-model="modelForm.phone" type="number"></el-input> -->
                                    <el-autocomplete
                                      prefix-icon="el-icon-search"
                                      v-model="modelForm.phone"
                                      :fetch-suggestions="querySearch"
                                      placeholder="Nhập số điện thoại"
                                      @select="handleSelect"
                                      clearable
                                    ></el-autocomplete>
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
                                    <el-form-item :label="$t('orders.label.brand_id')"
                                                  :class="{'el-form-item is-error': form.errors.has(  'brand_id') }">

                                        <el-select v-model="modelForm.brand_id"
                                                    :placeholder="$t('orders.label.brand_id')"
                                                    @change="changeBrand()"
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

                                <div class="col-md-12">
                                  <el-form-item label="Loại sản phẩm">
                                    <el-radio-group v-model="modelForm.type_product">
                                      <el-radio
                                        v-for="item in list_type_product"
                                        :key="item.id"
                                        :value="item.value"
                                        :label="item.id"
                                        @change="changeTypeProduct()"
                                        >{{ item.value }}</el-radio
                                      >
                                    </el-radio-group>
                                  </el-form-item>
                                </div>
                              </div>
                            </div>
                        </div>

                        <div class="card">
                          <div class="card-header"  >
                              <h3 class="card-title">Danh sách sản phẩm</h3>
                              <div class="card-tools">
                                  <el-button size="mini" type="warning"  icon="el-icon-plus" @click="addMoreInfo">Thêm</el-button>

                              </div>
                          </div>
                          <div class="card-body" v-if="modelForm.type_product == 1">
                              <div class="row mt-2" v-for="(pinfo,key) in modelForm.products" :key="key">
                                <div class="col-md-4">
                                  <el-form-item :label="$t('orders.label.problem_id')" 
                                                  :class="{'el-form-item is-error': form.errors.has('problem_id') }">
                                      <el-select
                                          v-model="pinfo.problem_id"
                                          allow-create
                                          filterable
                                          placeholder="Chọn vấn đề sửa chữa">
                                          <el-option
                                              v-for="item in list_problem"
                                              :key="item.id"
                                              :label="item.title"
                                              :value="item.id">
                                          </el-option>
                                      </el-select>
                                      <div class="el-form-item__error"
                                              v-if="form.errors.has('problem_id')"
                                              v-text="form.errors.first('problem_id')"></div>
                                  </el-form-item>
                                  
                                </div>

                                <div class="col-md-4">
                                  <el-form-item :label="$t('orders.label.category_id')" 
                                                  :class="{'el-form-item is-error': form.errors.has('category_id') }">
                                      <el-select
                                          v-model="pinfo.category_id"
                                          allow-create
                                          filterable
                                          @change="handleSelectCategory(key, pinfo.category_id)"
                                          placeholder="Chọn Danh mục">
                                          <el-option
                                              v-for="item in category_tree_data"
                                              :key="item.id"
                                              :label="item.name"
                                              :value="item.id">
                                          </el-option>
                                      </el-select>
                                  </el-form-item>
                                </div>
                                  <div class="col-md-3">
                                    <el-form-item :label="$t('orders.label.product')" 
                                                  :class="{'el-form-item is-error': form.errors.has('product') }">
                                      <el-select
                                          v-model="pinfo.id"
                                          allow-create
                                          filterable
                                          @change="handleSelectProduct(key, pinfo.id)"
                                          placeholder="Chọn sản phẩm">
                                          <el-option
                                              v-for="item in list_product[key]"
                                              :key="item.id"
                                              :label="item.name"
                                              :value="item.id">
                                          </el-option>
                                      </el-select>
                                    </el-form-item>
                                  </div>

                                  <div
                                      class="col-md-1   text-right d-flex justify-content-end align-items-center">
                                      <i class="el-icon-circle-close" style="color:red; cursor:pointer"
                                          @click="removeInfo(key)"></i>
                                  </div>

                                  <div class="col-md-4 mt-15">
                                    <el-form-item :label="$t('orders.label.attribute')" 
                                                  :class="{'el-form-item is-error': form.errors.has('attribute') }">
                                      <el-select
                                          v-model="pinfo.attribute_id"
                                          allow-create
                                          filterable
                                          :disabled="true"
                                          placeholder="Thuộc tính">
                                          <el-option
                                              v-for="item in list_attribute"
                                              :key="item.id"
                                              :label="item.name"
                                              :value="item.id">
                                          </el-option>
                                      </el-select>
                                    </el-form-item>
                                  </div>
                                  
                                  <div class="col-md-4 mt-15">
                                    <el-form-item :label="$t('orders.label.attribute_value')" 
                                                  :class="{'el-form-item is-error': form.errors.has('attribute_value') }">
                                      <el-select
                                          v-model="pinfo.attribute_value_id"
                                          allow-create
                                          filterable
                                          @change="changeAttribute(key)"
                                          :disabled="pinfo.attribute_selected ? false: true"
                                          placeholder="Giá trị thuộc tính">
                                          <el-option
                                              v-for="item in list_attribute_values[key]"
                                              :key="item.id"
                                              :label="item.name"
                                              :value="item.id">
                                          </el-option>
                                      </el-select>
                                      <div class="el-form-item__error"
                                              v-if="form.errors.has('attribute_value')"
                                              v-text="form.errors.first('attribute_value')"></div>
                                    </el-form-item>
                                  </div>

                                  <div class="col-md-3 mt-15">
                                    <el-form-item :label="$t('orders.label.price')" 
                                                  :class="{'el-form-item is-error': form.errors.has('price') }">
                                        <cleave v-model="pinfo.price" :options="options" 
                                            class="form-control" name="price" :disabled="true"></cleave>
                                        <div class="el-form-item__error"
                                              v-if="form.errors.has('price')"
                                              v-text="form.errors.first('price')"></div>
                                    </el-form-item>
                                </div>
                                <div class="col-md-4 mt-15">
                                    <el-form-item :label="$t('product.label.sale_price')" 
                                                  :class="{'el-form-item is-error': form.errors.has('sale_price') }">
                                        <cleave v-model="pinfo.sale_price" :options="options_odd_number" 
                                            class="form-control" name="sale_price" :disabled="true"></cleave>
                                        <div class="el-form-item__error"
                                              v-if="form.errors.has('sale_price')"
                                              v-text="form.errors.first('sale_price')"></div>
                                    </el-form-item>
                                </div>

                                <div class="col-md-4 mt-15">
                                    <el-form-item :label="$t('product.label.amount')" 
                                                  :class="{'el-form-item is-error': form.errors.has('amount') }">
                                        <input v-model="pinfo.amount" 
                                            class="form-control" name="amount" 
                                            v-on:keyup="updateTotalPrice"/>

                                        <div class="el-form-item__error"
                                              v-if="form.errors.has('amount')"
                                              v-text="form.errors.first('amount')"></div>
                                    </el-form-item>
                                </div>
                                <el-divider></el-divider>

                              </div>
                          </div>

                          <div class="card-body" v-else>
                              <div class="row mt-2" v-for="(pinfo,key) in modelForm.products" :key="key">
                                <div class="col-md-11">
                                  <el-form-item :label="$t('orders.label.product_title')" 
                                                  :class="{'el-form-item is-error': form.errors.has('product_title') }">
                                      <el-input v-model="pinfo.product_title"
                                              :rows="3"></el-input>
                                      <div class="el-form-item__error"
                                            v-if="form.errors.has('product_title')"
                                            v-text="form.errors.first('product_title')">
                                      </div>
                                  </el-form-item>
                                </div>

                                <div class="col-md-11">
                                    <el-form-item :label="$t('orders.label.pay_price')"
                                                  :class="{'el-form-item is-error': form.errors.has(  'pay_price') }">

                                        <cleave v-model="pinfo.pay_price" :options="options" v-on:keyup.native="updateTotalPrice"
                                            class="form-control" name="pay_price"></cleave>
                                        <div class="el-form-item__error"
                                            v-if="form.errors.has('pay_price')"
                                            v-text="form.errors.first('pay_price')">
                                        </div>
                                    </el-form-item>
                                  </div>
                                  
                                <div
                                    class="col-md-1   text-right d-flex justify-content-end align-items-center">
                                    <i class="el-icon-circle-close" style="color:red; cursor:pointer"
                                        @click="removeInfo(key)"></i>
                                </div>
                                <div class="col-md-11 mt-15">
                                  <el-form-item :label="$t('orders.label.product_detail')" 
                                                :class="{'el-form-item is-error': form.errors.has('product_detail') }">
                                    <el-input v-model="pinfo.product_detail" type="textarea"
                                              :rows="3"></el-input>
                                      <div class="el-form-item__error"
                                            v-if="form.errors.has('product_detail')"
                                            v-text="form.errors.first('product_detail')">
                                      </div>
                                  </el-form-item>
                                </div>

                                <el-divider></el-divider>

                              </div>
                          </div>
                      </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
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

                                  <div class="col-md-12">
                                    <el-form-item :label="$t('orders.label.total_price')"
                                                  :class="{'el-form-item is-error': form.errors.has(  'total_money') }">

                                        <cleave v-model="modelForm.total_price" :options="options" 
                                            class="form-control" name="total_price" :disabled="true"></cleave>
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
        brand_id: '',
        created_at: new Date(),
        payment_method: 1,
        products: [],
        total_price: 0,
        type_product: 1,
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
      list_attribute_values: [],
      total_price: 0,
      list_product: [],
      userSearchResult: [],
      list_type_product: [
        {
          id: 1,
          value: "Sản phẩm có sẵn",
        },
        {
          id: 2,
          value: "Sản phẩm khác",
        },
      ]
    };
  },
  methods: {
    changeBrand(){
      this.modelForm.products = [];
      this.modelForm.total_price = 0;
    },
    changeTypeProduct(){
      this.modelForm.products = [];
      this.modelForm.total_price = 0;
    },
    removeInfo(index) {
        this.modelForm.products.splice(index,1);
        this.list_product.splice(index,1);
        this.list_attribute_values.splice(index,1);
        this.updateTotalPrice();
      },
    addMoreInfo() {
        this.modelForm.products.push({
          problem_id: '',
          category_id: '',
          id: '',
          attribute_id: '',
          attribute_value_id: '',
          price: '',
          sale_price: '',
          amount: '',
          product_title: '',
          product_detail: '',
          pay_price: '',
        });
      },
    fetchProduct(key, catId) {
      const properties = {
        page: 0,
        per_page: 1000,
        source: 'voucher',
        shop_id: this.currentShop,
        category_id: catId,
        problem_id: this.modelForm.products[key].problem_id,
        brand_id: this.modelForm.brand_id,
        type: 2, //Sửa chữa
      };

      axios
        .get(route("apishop.product.index", _.merge(properties, {})))
        .then((response) => {
          this.list_product.splice(key,1);
          this.list_product[key] = response.data.data;
        });
    },
    handleSelectProduct(key, pid) {
      this.list_product[key].forEach(product => {
        if (product.id == pid){
          let item = product;
          item.amount = 0;
          item.category_id = this.modelForm.products[key].category_id;
          item.problem_id = this.modelForm.products[key].problem_id;
          this.modelForm.products.splice(key,1);
          this.modelForm.products[key] = (item);
          if (item.attribute_selected){
            this.list_attribute_values.splice(key,1);
            this.list_attribute_values[key] = item.attribute_selected.values;
          }
        }
      });
      
    },
    handleSelectCategory(key, catId) {
      if (typeof this.list_product[key] != 'undefined'){
        this.list_product.splice(key,1);
        this.list_attribute_values.splice(key,1);
        this.modelForm.products[key].id = '';
        
      }
      this.fetchProduct(key, catId);
      
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
          this.$router.push({ name: "shop.orders.index" });
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
          this.$router.push({ name: "shop.orders.index" });
        })
        .catch(() => {});
    },

    fetchData() {
      let routeUri = "";
      if (this.$route.params.orderId !== undefined) {
        this.loading = true;
        routeUri = route("apishop.orders.find", {
          orderId: this.$route.params.orderId,
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
        return route("apishop.orders.updateRepair", {
          orderId: this.$route.params.orderId,
        });
      }
      return route("apishop.orders.storeRepair");
    },

      fetchCategory() {
        const properties = {
          page: 0,
          per_page: 1000,
          type: 'service'
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
        type: 'service'
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

    changeAttribute(key){
      let attributeSelectedValue = this.modelForm.products[key].attribute_selected.values;
      let attributeSelectedId = this.modelForm.products[key].attribute_value_id;
      
      attributeSelectedValue.forEach(value => {
        if (value.id == attributeSelectedId){
          this.modelForm.products[key].price = value.price;
          this.modelForm.products[key].sale_price = value.sale_price;
        }
      });
      this.updateTotalPrice();
    },

    updateTotalPrice(){
        let payPrice = 0;
        let totalPrice = 0;
        let discount = 0;
        if (typeof this.modelForm.products !== 'undefined' && this.modelForm.products.length > 0){
          for(let i = 0; i < this.modelForm.products.length; i++){
            if (this.modelForm.type_product == 1){
              totalPrice += parseInt(this.modelForm.products[i].amount) * parseInt(this.modelForm.products[i].price);
              discount += (parseInt(this.modelForm.products[i].sale_price)/100) * parseInt(this.modelForm.products[i].price) 
                * parseInt(this.modelForm.products[i].amount);
            } else {
              totalPrice += parseInt(this.modelForm.products[i].pay_price);
              discount += 0;
            }
            
          }

          payPrice = totalPrice - discount;
        }
        this.modelForm.total_price = payPrice;
    },
    querySearch(queryString, cb) {
      this.fetchUserByPhone(queryString);
      cb(this.userSearchResult);
      // cb([{'id':1,'value':'test1'},{'id':2,'value':'test2'}]);
    },

    fetchUserByPhone(queryString) {
      const properties = {
        page: 0,
        per_page: 1000,
        phone: queryString,
        not_check_company: true
      
      };

      axios
        .get(route("api.user.index", _.merge(properties, {})))
        .then((response) => {
          this.userSearchResult = response.data.data;
          this.userSearchResult.forEach(user => {
            user.value = user.phone + ' - ' + user.name;
          });
        });
    },

    handleSelect(item) {
      this.modelForm.phone = item.phone;
      this.modelForm.customer_name = item.name;
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
    },
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
    .mt-15 {
      margin-top: 15px;
    }
</style>

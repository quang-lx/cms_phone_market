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
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header ui-sortable-handle" style="cursor: move">
                <h3 class="card-title">
                  {{ $t(pageTitle)
                  }}
                </h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <el-form
                  ref="form"
                  :model="modelForm"
                  label-width="200px"
                  label-position="left"
                  v-loading.body="loading"
                >
                  <div class="row">
                    <div class="col-md-12">
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

                        <div class="col-md-12">
                          <el-form-item
                            :label="$t('orders.label.category_id')"
                            :class="{
                              'el-form-item is-error': form.errors.has(
                                'category_id'
                              ),
                            }"
                          >
                            <el-select
                              v-model="modelForm.category_id"
                              :placeholder="$t('orders.label.category_id')"
                              filterable
                              style="width: 100% !important"
                            >
                              <el-option
                                v-for="item in categoryArr"
                                :key="'cat'+ item.id"
                                :label="item.name"
                                :value="item.id"
                              >
                              </el-option>
                            </el-select>
                            <div
                              class="el-form-item__error"
                              v-if="form.errors.has('category_id')"
                              v-text="form.errors.first('category_id')"
                            ></div>
                          </el-form-item>
                        </div>

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
                          <el-form-item
                            :label="$t('orders.label.product_name')"
                            :class="{
                              'el-form-item is-error': form.errors.has('product_name'),
                            }"
                          >
                            <el-input v-model="modelForm.product_name"></el-input>
                            <div
                              class="el-form-item__error"
                              v-if="form.errors.has('product_name')"
                              v-text="form.errors.first('product_name')"
                            ></div>
                          </el-form-item>
                        </div>

                        <div class="col-md-12">
                          <el-form-item
                            :label="$t('orders.label.price')"
                            :class="{
                              'el-form-item is-error': form.errors.has('price'),
                            }"
                          >
                            <el-input v-model="modelForm.price"></el-input>
                            <div
                              class="el-form-item__error"
                              v-if="form.errors.has('price')"
                              v-text="form.errors.first('price')"
                            ></div>
                          </el-form-item>
                        </div>

                        <div class="col-md-12">
                          <el-form-item
                            :label="$t('orders.label.payment_method')"
                            :class="{
                              'el-form-item is-error': form.errors.has('payment_method'),
                            }"
                          >
                            <el-input v-model="modelForm.payment_method"></el-input>
                            <div
                              class="el-form-item__error"
                              v-if="form.errors.has('payment_method')"
                              v-text="form.errors.first('payment_method')"
                            ></div>
                          </el-form-item>
                        </div>

                        
                      </div>
                    </div>
                  </div>
                </el-form>
              </div>
              <!-- /.card-body -->
              <div class="card-footer d-flex justify-content-end">
                <el-button
                  type="primary"
                  @click="onSubmit()"
                  size="small"
                  :loading="loading"
                  class="btn btn-flat"
                >
                  {{ $t("orders.label.button.save") }}
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
    locales: { default: null },
    pageTitle: { default: null, String },
  },
  data() {
    return {
      form: new Form(),
      loading: false,
      modelForm: {
        phone: '',
        customer_name: '',
        category_id: '',
        brand_id: '',
        price: '',
        created_at: new Date(),
        payment_method: '',
      },
      categoryArr: [],
      brandArr: [],
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

        axios.get(route('apishop.pcategory.index', _.merge(properties, {})))
        .then((response) => {

            this.categoryArr = response.data;

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
  },
  mounted() {
    this.fetchCategory();
    this.fetchBrand();
    this.fetchData();
  },
  computed: {},
};
</script>

<style scoped>
</style>

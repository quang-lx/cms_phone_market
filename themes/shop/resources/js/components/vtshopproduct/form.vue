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
              <el-breadcrumb-item :to="{ name: 'shop.vtshopproduct.index' }"
                >{{ $t("vtshopproduct.label.vtshopproduct") }}
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
                  }}<span v-if="modelForm.title"
                    >: &nbsp{{ modelForm.title }}</span
                  >
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
                        <div class="col-md-10">
                          <el-form-item
                            :label="$t('vtshopproduct.label.vt_product_id')"
                            :class="{
                              'el-form-item is-error': form.errors.has('vt_product_id'),
                            }"
                          >
                          <el-select v-model="modelForm.vt_product_id" filterable placeholder="Select">
                            <el-option
                              v-for="item in vtProduct"
                              :key="item.id"
                              :label="item.name"
                              :value="item.id">
                            </el-option>
                          </el-select>
                            <div
                              class="el-form-item__error"
                              v-if="form.errors.has('vt_product_id')"
                              v-text="form.errors.first('vt_product_id')"
                            ></div>
                          </el-form-item>
                        </div>

                        <div class="col-md-10">
                          <el-form-item
                            :label="$t('vtshopproduct.label.amount')"
                            :class="{
                              'el-form-item is-error': form.errors.has('amount'),
                            }"
                          >
                            <cleave v-model="modelForm.amount" :options="options" class="form-control" name="amount"></cleave>

                            <div
                              class="el-form-item__error"
                              v-if="form.errors.has('amount')"
                              v-text="form.errors.first('amount')"
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
import SingleFileSelector from "../../mixins/SingleFileSelector.js";
import Cleave from 'vue-cleave-component';

export default {
  components: {
      Cleave
  },
  props: {
    locales: { default: null },
    pageTitle: { default: null, String },
  },
  mixins: [SingleFileSelector],
  data() {
    return {
      form: new Form(),
      loading: false,
      modelForm: {
        vt_product_id: "",
        amount: "",
      },
      
      options: {
          prefix: '',
          numeral: true,
          numeralPositiveOnly: true,
          noImmediatePrefix: true,
          rawValueTrimPrefix: true,
          numeralIntegerScale: 12,
          numeralDecimalScale: 0
      },
      vtProduct:[]
    };
  },
  methods: {
    onSubmit() {
     this.$confirm(this.$t("vtshopproduct.label.alert_import"), {
        confirmButtonText: this.$t("mon.cancel.Yes"),
        cancelButtonText: this.$t("mon.cancel.No"),
        type: "warning",
      })
        .then(() => {
          this.saveData();
        })
        .catch(() => {});
    },

    saveData(){
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
          this.$router.push({ name: "shop.vtshopproduct.index" });
        })
        .catch((error) => {
          this.loading = false;
            this.$notify.error({
            title: this.$t("mon.error.Title"),
            message: this.getSubmitError(this.form.errors),
          });
          
        });
    },

    fetchVtProduct(){
        const properties = {
          page: 0,
          per_page: 1000,
          vt_shop_proudct: 1
        };
        axios.get(route('apishop.vtproduct.index', _.merge(properties, {})))
        .then((response) => {
          this.vtProduct = response.data.data;

        });
    },

    onCancel() {
      this.$confirm(this.$t("mon.cancel.Are you sure to cancel?"), {
        confirmButtonText: this.$t("mon.cancel.Yes"),
        cancelButtonText: this.$t("mon.cancel.No"),
        type: "warning",
      })
        .then(() => {
          this.$router.push({ name: "shop.vtshopproduct.index" });
        })
        .catch(() => {});
    },

    fetchData() {
      let routeUri = "";
      if (this.$route.params.vtshopproductId !== undefined) {
        this.loading = true;
        routeUri = route("apishop.vtshopproduct.find", {
          vtshopproduct: this.$route.params.vtshopproductId,
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
      if (this.$route.params.vtshopproductId !== undefined) {
        return route("apishop.vtshopproduct.update", {
          vtshopproduct: this.$route.params.vtshopproductId,
        });
      }
      return route("apishop.vtshopproduct.store");
    },
  },
  mounted() {
    this.fetchData();
    this.fetchVtProduct()
  },
  computed: {},
};
</script>

<style scoped>
</style>

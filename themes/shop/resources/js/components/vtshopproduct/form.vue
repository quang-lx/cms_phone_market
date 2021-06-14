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
                            <el-input v-model="modelForm.vt_product_name"></el-input>
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
                            <el-input v-model="modelForm.amount"></el-input>
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

export default {
  props: {
    locales: { default: null },
    pageTitle: { default: null, String },
  },
  mixins: [SingleFileSelector],
  data() {
    return {
      form: new Form(),
      loading: false,
      list_vtshopproduct: [],
      modelForm: {
        vt_product_name: "",
        amount: "",
      },
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
          this.$router.push({ name: "shop.vtshopproduct.index" });
        })
        .catch((error) => {
          this.loading = false;
          // console.log(error.response.status)
          if (error.response.status == 422) {
            this.$notify.error({
            title: this.$t("mon.error.Title"),
            message: this.getSubmitError(this.form.errors),
          });
          }else{
            this.$notify.error({
            title: this.$t("mon.error.Title"),
            message: error.response.data.message,
          });
          }
          
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
  },
  computed: {},
};
</script>

<style scoped>
</style>

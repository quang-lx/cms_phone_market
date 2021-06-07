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
              <el-breadcrumb-item :to="{ name: 'shop.vtimportexcel.index' }"
                >{{ $t("vtimportexcel.label.vtimportexcel") }}
              </el-breadcrumb-item>
              <el-breadcrumb-item>
                {{ $t(pageTitle) }}
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
                <h3 class="card-title"><span>Thông tin file</span>
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
                        <div class="col-md-6">
                          {{ $t("vtimportexcel.label.filename") }}:{{modelForm.filepath}}
                        </div>
                        <div class="col-md-3">
                          {{ $t("vtimportexcel.label.number_product") }}:{{modelForm.number_product}}
                        </div>
                        <div class="col-md-3">
                          {{ $t("vtimportexcel.label.status") }}:{{modelForm.status}}
                        </div>
  
                      </div>
                    </div>
                  </div>
                </el-form>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="card">
              <div class="card-header ui-sortable-handle" style="cursor: move">
                <h3 class="card-title"><span>Chi tiết file</span>
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
                          <el-table
                            :data="modelForm.vt_import_product"
                            stripe
                            style="width: 100%"
                            ref="dataTable"
                            v-loading.body="tableIsLoading"
                            @sort-change="handleSortChange"
                          >
                            <el-table-column
                              prop="vt_product_id"
                              :label="$t('vtimportproduct.label.ma_vat_tu')"
                              width="150"
                              sortable="custom"
                            >
                            </el-table-column>
                            <el-table-column
                              prop="vt_product_name"
                              :label="$t('vtimportproduct.label.vt_product_name')"
                            >
                            </el-table-column>
                            <el-table-column
                              prop="amount"
                               :label="$t('vtimportproduct.label.amount')"
                              sortable="custom"
                            >
                            </el-table-column>
                          </el-table>
                        </div>
                      </div>
                    </div>
                  </div>
                </el-form>
              </div>
              <!-- /.card-body -->
              <div class="card-footer d-flex justify-content-end">
                <el-button v-if="modelForm.status==1"
                  type="primary"
                  @click="onSubmit()"
                  size="small"
                  :loading="loading"
                  class="btn btn-flat"
                >
                Import
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
      list_vtcategory: [],
      modelForm: {
        filepath: "",
        number_product: "",
        status: "",
        vt_import_product: [],
      },
    };
  },
  methods: {
    onSubmit() {
      this.form = new Form({vtimportexcel: this.$route.params.vtimportexcelId})
      this.loading = true;
      this.form
        .post(this.getRoute())
        .then((response) => {
          this.loading = false;
          this.$message({
            type: "success",
            message: response.message,
          });
          this.$router.push({ name: "shop.vtimportexcel.index" });
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
          this.$router.push({ name: "shop.vtimportexcel.index" });
        })
        .catch(() => {});
    },

    fetchData() {
      let routeUri = "";
      this.loading = true;
      routeUri = route("apishop.vtimportexcel.find", {
        vtimportexcel: this.$route.params.vtimportexcelId,
      });
      axios.get(routeUri).then((response) => {
        this.loading = false;
        this.modelForm = response.data.data;
      });
    },
    getRoute() {
      return route("apishop.vtproduct.import");
    },
  },
  mounted() {
    this.fetchData();
  },
   
  computed: {},
};
</script>

<style scoped></style>

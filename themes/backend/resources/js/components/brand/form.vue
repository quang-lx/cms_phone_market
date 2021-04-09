<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-12">
            <el-breadcrumb separator="/">
              <el-breadcrumb-item>
                <a href="/admin">{{ $t("mon.breadcrumb.home") }}</a>
              </el-breadcrumb-item>
              <el-breadcrumb-item :to="{ name: 'admin.brand.index' }"
                >{{ $t("brand.label.brand") }}
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
                            :label="$t('brand.label.name')"
                            :class="{
                              'el-form-item is-error': form.errors.has('name'),
                            }"
                          >
                            <el-input v-model="modelForm.name"></el-input>
                            <div
                              class="el-form-item__error"
                              v-if="form.errors.has('name')"
                              v-text="form.errors.first('name')"
                            ></div>
                          </el-form-item>
                        </div>
                        <div class="col-md-10">
                          <el-form-item
                            :label="$t('brand.label.type')"
                            :class="{
                              'el-form-item is-error': form.errors.has('type'),
                            }"
                          >
                            <el-radio-group
                              v-model="modelForm.type"
                              size="mini"
                              @change="changeType"
                            >
                              <el-radio
                                v-for="item in listService"
                                :key="'type-' + item.value"
                                :label="item.value"
                                >{{ item.label }}
                              </el-radio>
                            </el-radio-group>
                            <div
                              class="el-form-item__error"
                              v-if="form.errors.has('type')"
                              v-text="form.errors.first('type')"
                            ></div>
                          </el-form-item>
                        </div>
                        <div class="col-md-10">
                          <el-form-item
                            :label="$t('brand.label.category')"
                            :class="{
                              'el-form-item is-error': form.errors.has('category_id'),
                            }"
                          >
                            <el-select
                              v-model="modelForm.category_id"
                              multiple
                              placeholder="Select"
                            >
                              <el-option
                                v-for="item in options"
                                :key="item.id"
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
                        <div class="col-md-10">
                          <el-form-item
                            :label="$t('brand.label.status')"
                            :class="{
                              'el-form-item is-error': form.errors.has(
                                'status'
                              ),
                            }"
                          >
                            <el-radio-group
                              v-model="modelForm.status"
                              size="mini"
                            >
                              <el-radio
                                v-for="item in listStatus"
                                :key="'status-' + item.value"
                                :label="item.value"
                                >{{ item.label }}
                              </el-radio>
                            </el-radio-group>
                            <div
                              class="el-form-item__error"
                              v-if="form.errors.has('status')"
                              v-text="form.errors.first('status')"
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
      list_district: [],
      list_province: [],
      options: [],
      modelForm: {
        name: "",
        type: "",
        category_id: [],
        status: "",
      },
      listService: [
        {
          value: "product",
          label: "Hàng hóa",
        },
        {
          value: "service",
          label: "Dịch vụ",
        },
      ],

      listStatus: [
        {
          value: 0,
          label: "Khóa",
        },
        {
          value: 1,
          label: "Hoạt động",
        },
      ],
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
          this.$router.push({ name: "admin.brand.index" });
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
          this.$router.push({ name: "admin.brand.index" });
        })
        .catch(() => {});
    },

    fetchData() {
      let routeUri = "";
      if (this.$route.params.brandId !== undefined) {
        this.loading = true;
        routeUri = route("api.brand.find", {
          brand: this.$route.params.brandId,
        });
        axios.get(routeUri).then((response) => {
          this.loading = false;
          this.modelForm = response.data.data;
          this.modelForm.is_new = false;
          this.getCateUpdate()

        });
      } else {
        this.modelForm.is_new = true;
      }
    },

    changeType() {
      this.modelForm.category_id=[]
      let routeUri = "";
        routeUri = route("api.pcategory.index", {
          type: this.modelForm.type,
        });
        axios.get(routeUri).then((response) => {
          this.loading = false;
          this.options = response.data.data;
        });
    },

     getCateUpdate() {
      let routeUri = "";
        routeUri = route("api.pcategory.index", {
          type: this.modelForm.type,
        });
        axios.get(routeUri).then((response) => {
          this.loading = false;
          this.options = response.data.data;
        });
    },

    getRoute() {
      if (this.$route.params.brandId !== undefined) {
        return route("api.brand.update", {
          brand: this.$route.params.brandId,
        });
      }
      return route("api.brand.store");
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

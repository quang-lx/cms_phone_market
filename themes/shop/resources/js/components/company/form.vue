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
                  <el-breadcrumb-item :to="{ name: 'shop.company.index' }"
                    >{{ $t("company.label.company") }}
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
              <div
                class="card-header ui-sortable-handle"
                style="cursor: move"
              ></div>
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
                    <div class="col-md-6 pr-5">
                      <el-form-item
                        :label="$t('company.label.name')"
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
                      <el-form-item
                        :label="$t('company.label.phone')"
                        :class="{
                          'el-form-item is-error': form.errors.has('phone'),
                        }"
                      >
                        <el-input v-model="modelForm.phone"></el-input>
                        <div
                          class="el-form-item__error"
                          v-if="form.errors.has('phone')"
                          v-text="form.errors.first('phone')"
                        ></div>
                      </el-form-item>
                      <el-form-item
                        :label="$t('company.label.email')"
                        :class="{
                          'el-form-item is-error': form.errors.has('email'),
                        }"
                      >
                        <el-input v-model="modelForm.email"></el-input>
                        <div
                          class="el-form-item__error"
                          v-if="form.errors.has('email')"
                          v-text="form.errors.first('email')"
                        ></div>
                      </el-form-item>
                      <el-form-item
                        :label="$t('company.label.address')"
                        :class="{
                          'el-form-item is-error': form.errors.has('address'),
                        }"
                      >
                        <el-input v-model="modelForm.address"></el-input>
                        <div
                          class="el-form-item__error"
                          v-if="form.errors.has('address')"
                          v-text="form.errors.first('address')"
                        ></div>
                      </el-form-item>

                      <single-media
                          zone="thumbnail"
                          @singleFileSelected="
                            selectSingleFile($event, 'modelForm')
                          "
                          label="Ảnh đại diện"
                          entity="Modules\Mon\Entities\Company"
                          :entity-id="modelForm.id"
                      ></single-media>
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
      changepassForm: new Form(),
      loading: false,
      loadingPassword: false,
      modelForm: {
        username: "",
        email: "",
        phone: "",
        address: "",
      },
    };
  },
  methods: {
    onSubmit() {
      console.log(this.modelForm);
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
          this.$router.push({ name: "shop.company.index" });
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
          this.$router.push({ name: "shop.company.index" });
        })
        .catch(() => {});
    },

    fetchData() {
      let routeUri = "";
      this.loading = true;
      routeUri = route("apishop.company.find");
      axios.get(routeUri).then((response) => {
        this.loading = false;
        this.modelForm = response.data.data;
      });
    },
    getRoute() {
      return route("apishop.company.update");
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

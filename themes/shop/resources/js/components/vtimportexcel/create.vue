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
                        <div class="col-md-4">
                          <input
                            type="file"
                            id="file"
                            ref="file"
                            v-on:change="handleFileUpload()"
                          />
                          <!-- <el-upload
                            ref="upload"
                            :label="$t('vtimportexcel.label.file')"
                            :class="{
                              'el-form-item is-error': form.errors.has('file'),
                            }"
                          >
                            <el-input v-model="modelForm.file"></el-input>
                            <div
                              class="el-form-item__error"
                              v-if="form.errors.has('file')"
                              v-text="form.errors.first('file')"
                            ></div>
                          </el-upload> -->
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
        file: "",
      },
    };
  },
  methods: {
    handleFileUpload() {
      this.modelForm.file = this.$refs.file.files[0];
    },
    onSubmit() {
      this.form = new Form(_.merge(this.modelForm, {}));
      this.loading = true;

      //   this.form
      //     .post(this.getRoute())
      //     .then((response) => {
      //       this.loading = false;
      //       this.$message({
      //         type: "success",
      //         message: response.message,
      //       });
      //       this.$router.push({ name: "shop.vtimportexcel.index" });
      //     })
      //     .catch((error) => {
      //       this.loading = false;
      //       this.$notify.error({
      //         title: this.$t("mon.error.Title"),
      //         message: this.getSubmitError(this.form.errors),
      //       });
      //     });
      let formData = new FormData();
      formData.append("file", this.modelForm.file);
      axios
        .post(this.getRoute(), formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then(function () {
          this.loading = false;
          this.$message({
            type: "success",
            message: response.message,
          });
          this.$router.push({ name: "shop.vtimportexcel.index" });
        })
        .catch(function () {
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

    getRoute() {
      if (this.$route.params.vtimportexcelId !== undefined) {
        return route("apishop.vtimportexcel.update", {
          vtimportexcel: this.$route.params.vtimportexcelId,
        });
      }
      return route("apishop.vtimportexcel.store");
    },
  },
  computed: {},
};
</script>

<style scoped>
</style>

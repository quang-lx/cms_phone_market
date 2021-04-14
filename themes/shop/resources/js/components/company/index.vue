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
              <div class="card-body">
                <el-dialog
                  :title="
                    $t('user.label.change_password') + ': ' + modelForm.username
                  "
                  :visible.sync="changePassDialogVisible"
                  width="30%"
                  center
                >
                  <el-form
                    ref="changepassForm"
                    :model="modelForm"
                    label-width="200px"
                    label-position="left"
                    v-loading.body="loadingPassword"
                  >
                    <el-form-item
                      :label="$t('user.label.password')"
                      :class="{
                        'el-form-item is-error': changepassForm.errors.has(
                          'password'
                        ),
                      }"
                    >
                      <el-input
                        v-model="modelForm.password"
                        autocomplete="off"
                        type="password"
                      ></el-input>
                      <div
                        class="el-form-item__error"
                        v-if="changepassForm.errors.has('password')"
                        v-text="changepassForm.errors.first('password')"
                      ></div>
                    </el-form-item>
                    <el-form-item
                      :label="$t('user.label.password_confirmation')"
                      :class="{
                        'el-form-item is-error': changepassForm.errors.has(
                          'password_confirmation'
                        ),
                      }"
                    >
                      <el-input
                        v-model="modelForm.password_confirmation"
                        autocomplete="off"
                        type="password"
                      ></el-input>
                      <div
                        class="el-form-item__error"
                        v-if="
                          changepassForm.errors.has('password_confirmation')
                        "
                        v-text="
                          changepassForm.errors.first('password_confirmation')
                        "
                      ></div>
                    </el-form-item>
                  </el-form>

                  <span slot="footer" class="dialog-footer">
                    <el-button @click="changePassDialogVisible = false">
                      {{ $t("mon.button.cancel") }}</el-button
                    >
                    <el-button type="primary" @click="changePassword">
                      {{ $t("mon.button.save") }}</el-button
                    >
                  </span>
                </el-dialog>
                <el-tabs>
                  <div class="row">
                    <div class="col-md-12 mx-auto mb-3 font-weight-bold">
                      <div class="row">
                        <div class="col-md-9 text-primary">
                          <span>Quản lý thông tin</span>
                        </div>
                        <div class="col-md-3">
                            <router-link :to="{name: 'shop.company.edit'}">
                                  <el-button type="primary" :to="{ name: 'shop.company.index' }">Sửa</el-button>
                                        </router-link>
                       
                          <el-button
                            v-if="modelForm.id"
                            type="danger"
                            class="btn btn-flat btn-danger"
                            @click="changePassDialogVisible = true"
                          >
                            {{ $t("user.label.change_password") }}
                          </el-button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <span slot="label">Thông tin</span>
                  <div class="row">
                    <div class="col-md-12 mx-auto">
                      <div class="row mb-3">
                        <div class="col-md-2">
                          <span>{{ $t("company.label.name") }}</span>
                        </div>
                        <div class="col-md-10">{{ modelForm.name }}</div>
                      </div>
                      <div class="row mb-3">
                        <div class="col-md-2">
                          <span>{{ $t("company.label.address") }}</span>
                        </div>
                        <div class="col-md-10">{{ modelForm.address }}</div>
                      </div>

                      <div class="row mb-3">
                        <div class="col-md-2">
                          <span>{{ $t("company.label.phone") }}</span>
                        </div>
                        <div class="col-md-10">{{ modelForm.phone }}</div>
                      </div>

                      <div class="row mb-3">
                        <div class="col-md-2">
                          <span>{{ $t("company.label.email") }}</span>
                        </div>
                        <div class="col-md-10">{{ modelForm.email }}</div>
                      </div>

                      <div class="row mb-3">
                        <div class="col-md-2">
                          <span>Ảnh</span>
                        </div>
                        <div class="col-md-10">
                          <template slot-scope="scope">
                            <img
                              :src="scope.row.thumbnail.path_string"
                              v-if="scope.row.thumbnail"
                              width="100"
                            />
                          </template>
                        </div>
                      </div>
                    </div>
                  </div>
                </el-tabs>
              </div>
              <!-- /.card-body -->
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
      changepassForm: new Form(),
      loadingPassword: false,
      loading: false,
      modelForm: {},
      changePassDialogVisible: false,
    };
  },
  methods: {
    fetchData() {
      let routeUri = "";
      this.loading = true;
      routeUri = route("apishop.company.find", {
        company: this.$route.params.companyId,
      });
      axios.get(routeUri).then((response) => {
        this.loading = false;
        this.modelForm = response.data.data;
      });
    },
    changePassword() {
      this.changepassForm = new Form({
        password: this.modelForm.password,
        password_confirmation: this.modelForm.password_confirmation,
      });
      this.loadingPassword = true;

      this.changepassForm
        .post(
          route("apife.user.changePassword")
        )
        .then((response) => {
          this.loadingPassword = false;
          this.$message({
            type: "success",
            message: response.message,
          });
          this.changePassDialogVisible = false;
        })
        .catch((error) => {
          this.loadingPassword = false;
          this.$notify.error({
            title: this.$t("mon.error.Title"),
            message: this.getSubmitError(this.changepassForm.errors),
          });
        });
    },
  },
  mounted() {
    this.fetchData();
  },
};
</script>

<style scoped>
.box-company-header {
  display: flex;
  align-content: center;
}
</style>

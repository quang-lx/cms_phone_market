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
                  <el-breadcrumb-item :to="{ name: 'admin.company.index' }"
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
              <div class="card-header ui-sortable-handle" style="cursor: move">
                <div class="card-tools">
                  <ul class="nav nav-pills ml-auto">
                    <li class="nav-item">
                      <el-button
                        v-if="modelForm.id"
                        type="danger"
                        class="btn btn-flat btn-danger"
                        @click="changePassDialogVisible = true"
                      >
                        {{ $t("user.label.change_password") }}
                      </el-button>
                    </li>
                  </ul>
                </div>
              </div>
              <!-- /.card-header -->
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
                      :label="$t('user.label.password')" style="margin-bottom: 30px;"
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
                        :label="$t('company.label.level')"
                        :class="{
                          'el-form-item is-error': form.errors.has('level'),
                        }"
                      >
                        <el-radio-group v-model="modelForm.level">
                          <el-radio
                            v-for="item in listLevel"
                            :label="item.value"
                            :key="item.lable"
                            >{{ item.label }}</el-radio
                          >
                        </el-radio-group>
                        <div
                          class="el-form-item__error"
                          v-if="form.errors.has('level')"
                          v-text="form.errors.first('level')"
                        ></div>
                      </el-form-item>

                    </div>
                    <div class="col-md-6 pl-5">
                      <el-form-item
                        :label="$t('user.label.username')"
                        :class="{
                          'el-form-item is-error': form.errors.has('username'),
                        }"
                      >
                        <el-input
                          v-model="modelForm.username"
                          :disabled="!modelForm.is_new"
                          autocomplete="off"
                        ></el-input>
                        <div
                          class="el-form-item__error"
                          v-if="form.errors.has('username')"
                          v-text="form.errors.first('username')"
                        ></div>
                      </el-form-item>

                      <el-form-item
                        :label="$t('user.label.password')"
                        :class="{
                          'el-form-item is-error': form.errors.has('password'),
                        }"
                      >
                        <el-input
                          v-model="modelForm.password"
                          :disabled="!modelForm.is_new"
                          autocomplete="off"
                          type="password"
                        ></el-input>
                        <div
                          class="el-form-item__error"
                          v-if="form.errors.has('password')"
                          v-text="form.errors.first('password')"
                        ></div>
                      </el-form-item>
                      <el-form-item
                        :label="$t('user.label.password_confirmation')"
                        :class="{
                          'el-form-item is-error': form.errors.has(
                            'password_confirmation'
                          ),
                        }"
                      >
                        <el-input
                          v-model="modelForm.password_confirmation"
                          autocomplete="off"
                          :disabled="!modelForm.is_new"
                          type="password"
                        ></el-input>
                        <div
                          class="el-form-item__error"
                          v-if="form.errors.has('password_confirmation')"
                          v-text="form.errors.first('password_confirmation')"
                        ></div>
                      </el-form-item>

                      <el-form-item
                        :label="$t('company.label.status')"
                        :class="{
                          'el-form-item is-error': form.errors.has('status'),
                        }"
                      >
                        <el-radio-group v-model="modelForm.status">
                          <el-radio
                            v-for="item in listStatus"
                            :label="item.value"
                            :key="item.lable"
                            >{{ item.label }}</el-radio
                          >
                        </el-radio-group>
                        <div
                          class="el-form-item__error"
                          v-if="form.errors.has('status')"
                          v-text="form.errors.first('status')"
                        ></div>
                      </el-form-item>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-4">
                      <el-form-item
                        :label="$t('company.label.province')"
                        :class="{
                          'el-form-item is-error': form.errors.has(
                            'province_id'
                          ),
                        }"
                      >
                        <el-select
                          v-model="modelForm.province_id"
                          @change="changeProvince" filterable
                          placeholder="Chọn tỉnh"
                        >
                          <el-option
                            v-for="item in list_province"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id"
                          >
                          </el-option>
                        </el-select>
                        <div
                          class="el-form-item__error"
                          v-if="form.errors.has('province_id')"
                          v-text="form.errors.first('province_id')"
                        ></div>
                      </el-form-item>
                    </div>
                    <div class="col-sm-4">
                      <el-form-item
                        :label="$t('company.label.district')"
                        :class="{
                          'el-form-item is-error': form.errors.has(
                            'district_id'
                          ),
                        }"
                      >
                        <el-select
                          v-model="modelForm.district_id" filterable 
                          placeholder="Chọn quận/huyện"
                          @change="changeDistrict"
                        >
                          <el-option
                            v-for="item in list_district"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id"
                          >
                          </el-option>
                        </el-select>
                        <div
                          class="el-form-item__error"
                          v-if="form.errors.has('district_id')"
                          v-text="form.errors.first('district_id')"
                        ></div>
                      </el-form-item>
                    </div>
                    <div class="col-sm-4">
                      <el-form-item
                        :label="$t('company.label.phoenix')"
                        :class="{
                          'el-form-item is-error': form.errors.has(
                            'phoenix_id'
                          ),
                        }"
                      >
                        <el-select
                          v-model="modelForm.phoenix_id" filterable
                          placeholder="Chọn xã/phường"
                        >
                          <el-option
                            v-for="item in list_phoenix"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id"
                          >
                          </el-option>
                        </el-select>
                        <div
                          class="el-form-item__error"
                          v-if="form.errors.has('phoenix_id')"
                          v-text="form.errors.first('phoenix_id')"
                        ></div>
                      </el-form-item>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-6 pr-5">
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
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <el-form-item
                        :label="$t('company.label.description')"
                        :class="{
                          'el-form-item is-error': form.errors.has('description'),
                        }"
                      >
                        <tinymce
                          v-model="modelForm.description"
                          :height="350"
                        ></tinymce>
                        <div
                          class="el-form-item__error"
                          v-if="form.errors.has('description')"
                          v-text="form.errors.first('description')"
                        ></div>
                      </el-form-item>
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
import Tinymce from "../utils/Tinymce";

export default {
  components: {
    Tinymce,
  },
  props: {
    locales: { default: null },
    pageTitle: { default: null, String },
  },
  data() {
    return {
      form: new Form(),
      changepassForm: new Form(),
      loading: false,
      loadingPassword: false,
      list_district: [],
      list_province: [],
      list_phoenix: [],
      modelForm: {
        username: "",
        name: "",
        email: "",
        phone: "",
        level: 1,
        status: 1,
        is_new: false,
        district_id: "",
        province_id: "",
        phoenix_id: "",
      },
      roles: [],
      checkAll: false,
      isIndeterminate: true,
      changePassDialogVisible: false,
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
      listLevel: [
        {
          value: 1,
          label: "Cấp 1",
        },
        {
          value: 2,
          label: "Cấp 2",
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
          this.$router.push({ name: "admin.company.index" });
        })
        .catch((error) => {
          this.loading = false;
          this.$notify.error({
            title: this.$t("mon.error.Title"),
            message: this.getSubmitError(this.form.errors),
          });
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
          route("api.users.change-password", { user: this.modelForm.admin_id })
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
          console.log(error);
          this.loadingPassword = false;
          this.$notify.error({
            title: this.$t("mon.error.Title"),
            message: this.getSubmitError(this.changepassForm.errors),
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
          this.$router.push({ name: "admin.company.index" });
        })
        .catch(() => {});
    },

    fetchData() {
      let routeUri = "";
      if (this.$route.params.companyId !== undefined) {
        this.loading = true;
        routeUri = route("api.company.find", {
          company: this.$route.params.companyId,
        });
        axios.get(routeUri).then((response) => {
          this.loading = false;
          this.modelForm = response.data.data;
          this.modelForm.is_new = false;
          if (this.modelForm.province_id != null) {
            this.fetchDistrict();
            this.fetchPhoenix();
          }
        });
      } else {
        this.modelForm.is_new = true;
      }
    },

    fetchRoles() {
      axios.get(route("api.roles.all")).then((response) => {
        this.roles = response.data.data;
      });
    },
    getRoute() {
      if (this.$route.params.companyId !== undefined) {
        return route("api.company.update", {
          company: this.$route.params.companyId,
        });
      }
      return route("api.company.store");
    },
    handleCheckAllChange(val) {
      this.modelForm.roles = val ? this.roles.map((item) => item.id) : [];
      this.isIndeterminate = false;
    },
    handleCheckedChange(value) {
      let checkedCount = value.length;
      this.checkAll = checkedCount === this.roles.length;
      this.isIndeterminate =
        checkedCount > 0 && checkedCount < this.roles.length;
    },
    fetchProvince() {
      let routeUri = "";
      this.loading = true;
      routeUri = route("api.province.index", { page: 1, per_page: 1000 });
      axios.get(routeUri).then((response) => {
        this.loading = false;
        this.list_province = response.data.data;
      });
    },

    changeProvince() {
      this.fetchDistrict(this.modelForm.province_id);
      this.modelForm.district_id = "";
    },

    changeDistrict() {
      this.fetchPhoenix(this.modelForm.district_id);
      this.modelForm.phoenix_id = "";
    },

    fetchDistrict() {
      let routeUri = "";
      this.loading = true;
      routeUri = route("api.district.index", {
        page: 1,
        per_page: 1000,
        province_id: this.modelForm.province_id,
      });
      axios.get(routeUri).then((response) => {
        this.loading = false;
        this.list_district = response.data.data;
      });
    },

    fetchPhoenix() {
      let routeUri = "";
      this.loading = true;
      routeUri = route("api.phoenix.index", {
        page: 1,
        per_page: 1000,
        district_id: this.modelForm.district_id,
      });
      axios.get(routeUri).then((response) => {
        this.loading = false;
        this.list_phoenix = response.data.data;
      });
    },
  },
  mounted() {
    this.fetchData();
    this.fetchRoles();
    this.fetchProvince();
  },
  computed: {},
};
</script>

<style scoped>
</style>

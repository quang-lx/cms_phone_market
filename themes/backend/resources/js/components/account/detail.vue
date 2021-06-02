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
              <el-breadcrumb-item :to="{ name: 'admin.account.index' }"
                >{{ $t("account.label.account") }}
              </el-breadcrumb-item>
              <el-breadcrumb-item>
                Tài khoản khách hàng #{{ this.$route.params.accountId }}
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
                <div class="row">
                  <h3 class="card-title col-md-8">
                    {{ $t(pageTitle)
                    }}<span v-if="modelForm.title"
                      >: &nbsp{{ modelForm.title }}</span
                    >
                  </h3>
                  <div class="col-md-4 text-right">
                    <el-button @click="showDataModal(1)" type="primary"
                      >Tùy chỉnh xếp hạng</el-button
                    >

                    <el-button
                      v-if="modelForm.status == 1"
                      type="primary"
                      @click="open()"
                      >Khóa tài khoản</el-button
                    >
                    <el-button v-else type="primary" @click="open()"
                      >Bỏ khóa tài khoản</el-button
                    >
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <el-dialog
                  title="Tuỳ chỉnh xếp hạng"
                  :visible.sync="dialogRank"
                >
                  <el-form 
                    ref="form"
                    :model="modelForm"
                    label-width="150px"
                    label-position="left"
                    v-loading.body="loading"
                  >
                    <div class="row">
                      <div class="col-md-offset-2 col-md-8">
                        <p><b>Username:</b> {{ modelForm.username }}</p>
                      </div>
                      <div class="col-md-offset-2 col-md-8">
                        <el-form-item
                          :label="$t('account.label.rank_title')"
                        >
                          <el-select v-model="modelForm.rank_id" placeholder="Chọn xếp hạng">
                            
                            <el-option
                              v-for="item in rank"
                              :key="item.id"
                              :label="item.name"
                              :value="item.id"
                            >
                            </el-option>
                          </el-select>
                        </el-form-item>
                      </div>

                      <div class="col-md-offset-2 col-md-8">
                        <el-form-item
                          :label="$t('account.label.point')"
                          :class="{
                            'el-form-item is-error': form.errors.has('rank_point'),
                          }"
                        >
                          <el-input v-model="modelForm.rank_point"></el-input>
                          <div
                              class="el-form-item__error"
                              v-if="form.errors.has('rank_point')"
                              v-text="form.errors.first('rank_point')"
                            ></div>
                        </el-form-item>
                      </div>
                    </div>
                  </el-form>
                  <span slot="footer" class="dialog-footer">
                    <el-button type="primary" @click="onSubmit()"
                      >CẬP NHẬT</el-button
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
                    <div class="col-md-12">
                      <div class="row">
                        <div class="col-md-4">
                          <div class="row">
                            <div class="col-md-4">
                              <img :src="modelForm.avatar" width="75" alt="" />
                            </div>
                            <div class="col-md-8">
                              <div>
                                {{ $t("account.label.username") }}:
                                {{ modelForm.username }}
                              </div>
                              <div>
                                {{ $t("account.label.name") }}:
                                {{ modelForm.name }}
                              </div>
                              <div>
                                {{ $t("account.label.birthday") }}:
                                {{ modelForm.birthday }}
                              </div>
                              <div>
                                {{ $t("account.label.gender") }}:
                                {{ modelForm.gender_name }}
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div>
                            {{ $t("account.label.phone") }}:
                            {{ modelForm.phone }}
                          </div>
                          <div>
                            {{ $t("account.label.email") }}:
                            {{ modelForm.email }}
                          </div>
                          <div>
                            {{ $t("account.label.rank") }}:
                            {{ modelForm.rank_name }}
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div>
                            {{ $t("account.label.status") }}:
                            {{ modelForm.status_name }}
                          </div>
                          <div>
                            {{ $t("account.label.created_at") }}
                            :
                            {{ modelForm.created_at }}
                          </div>
                          <div>
                            {{ $t("account.label.last_login") }}
                            :
                            {{ modelForm.last_login }}
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </el-form>
              </div>
              <!-- /.card-body -->
              <div class="card-footer d-flex justify-content-end">
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
      formLabelWidth: "120px",
      form: new Form(),
      loading: false,
      list_account: [],
      modelForm: {
        description: "",
      },
      dialogRank: false,
      rank: [
      ],
      status: [
        {
          value: 1,
          label: "Hoạt đông",
        },
        {
          value: 2,
          label: "Đã khóa",
        },
      ],
      message: "",
    };
  },
  methods: {
    onCancel() {
      this.$confirm(this.$t("mon.cancel.Are you sure to cancel?"), {
        confirmButtonText: this.$t("mon.cancel.Yes"),
        cancelButtonText: this.$t("mon.cancel.No"),
        type: "warning",
      })
        .then(() => {
          this.$router.push({ name: "admin.account.index" });
        })
        .catch(() => {});
    },

    open() {
      if (this.modelForm.status == 1) {
        this.message = "Bạn có muốn khóa tài khoản " + this.modelForm.username;
      } else {
        this.message =
          "Bạn có muốn bỏ khóa tài khoản " + this.modelForm.username;
      }
      this.$confirm(this.message, "Xác nhận", {
        confirmButtonText: "ĐỒNG Ý",
        cancelButtonText: "HỦY",
        type: "warning",
      })
        .then(() => {
          if (this.modelForm.status == 1) {
            this.modelForm.status = 2;
          } else {
            this.modelForm.status = 1;
          }
          this.onSubmit();
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "Đã hủy",
          });
        });
    },

    onSubmit() {
      this.form = new Form(_.merge(this.modelForm, {}));
      this.loading = true;

      this.form
        .post(this.getRoute())
        .then((response) => {
          this.loading = false;
          this.dialogRank = false;

          this.fetchData();
          this.$message({
            type: "success",
            message: response.message,
          });
        })
        .catch((error) => {
          this.loading = false;
          this.dialogRank = false;
          this.$notify.error({
            title: this.$t("mon.error.Title"),
            message: this.getSubmitError(this.form.errors),
          });
        });
    },

    fetchData() {
      let routeUri = "";
      this.loading = true;
      routeUri = route("api.account.find", {
        account: this.$route.params.accountId,
      });
      axios.get(routeUri).then((response) => {
        this.loading = false;
        this.modelForm = response.data.data;
        this.modelForm.is_new = false;
      });
    },

    getRoute() {
      if (this.$route.params.accountId !== undefined) {
        return route("api.account.update", {
          account: this.$route.params.accountId,
        });
      }
      return route("api.account.store");
    },
    showDataModal(key) {
      this.dialogRank = true;
    },
    fetchRank() {
      let routeUri = "";
        this.loading = true;
        routeUri = route('api.rank.index',{page: 1,per_page:1000 })
        axios.get(routeUri).then((response) => {
          this.loading = false;
          this.rank = response.data.data;
        });
    },
  },
  mounted() {
    this.fetchData();
    this.fetchRank();
  },
  computed: {},
};
</script>

<style scoped>
</style>

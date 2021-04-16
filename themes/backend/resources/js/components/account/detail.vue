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
                              <div class="row">
                                  <div class="col-md-4">
                                      <img src="" width="75" alt="">
                                  </div>
                                  <div class="col-md-8">
                                      <div>{{$t('account.label.username')}}: {{modelForm.username}}</div>
                                      <div>{{$t('account.label.name')}}: {{modelForm.name}}</div>
                                      <div>{{$t('account.label.birthday')}}: {{modelForm.birthday}}</div>
                                      <div>{{$t('account.label.sex')}}: {{modelForm.sex}}</div>
                                  </div>
                              </div>
                          </div>
                          <div class="col-md-4">
                              <div>{{$t('account.label.phone')}}: {{modelForm.phone}}</div>
                                      <div>{{$t('account.label.email')}}: {{modelForm.email}}</div>
                                      <div>{{$t('account.label.rank')}}: {{modelForm.rank}}</div>
                          </div>
                          <div class="col-md-4">
                              <div>{{$t('account.label.status')}}: {{modelForm.status_name}}</div>
                                      <div>{{$t('account.label.created_at')}} : {{modelForm.created_at}}</div>
                                      <div>{{$t('account.label.last_login')}} : {{modelForm.last_login}}</div>
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
      list_account: [],
      modelForm: {
        description: "",
      },
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
  },
  mounted() {
    this.fetchData();
  },
  computed: {},
};
</script>

<style scoped>
</style>

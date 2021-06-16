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
              <el-breadcrumb-item :to="{ name: 'admin.fbnotification.index' }"
                >{{ $t("fbnotification.label.fbnotification") }}
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
                        <div class="col-md-6">
                          <el-form-item
                            :label="$t('fbnotification.label.title')"
                            :class="{
                              'el-form-item is-error': form.errors.has('title'),
                            }"
                          >
                            <el-input v-model="modelForm.title"></el-input>
                            <div
                              class="el-form-item__error"
                              v-if="form.errors.has('title')"
                              v-text="form.errors.first('title')"
                            ></div>
                          </el-form-item>
                        </div>
                        <div class="col-md-6">
                          <el-form-item
                            :label="$t('fbnotification.label.content')"
                            :class="{
                              'el-form-item is-error': form.errors.has('content'),
                            }"
                          >
                            <el-input v-model="modelForm.content"></el-input>
                            <div
                              class="el-form-item__error"
                              v-if="form.errors.has('content')"
                              v-text="form.errors.first('content')"
                            ></div>
                          </el-form-item>
                        </div>
                          <div class="col-md-6">
                          <el-form-item
                            :label="$t('fbnotification.label.topic')"
                            :class="{
                              'el-form-item is-error': form.errors.has('topic'),
                            }"
                          >
                            <el-select v-model="modelForm.topic" placeholder="Select">
                              <el-option
                                v-for="item in list_topic"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value"
                              >
                              </el-option>
                            </el-select>
                            <div
                              class="el-form-item__error"
                              v-if="form.errors.has('topic')"
                              v-text="form.errors.first('topic')"
                            ></div>
                          </el-form-item>
                        </div>
                        <div class="col-md-6">
                          <el-form-item
                            :label="$t('fbnotification.label.scheduled_at')"
                            :class="{
                              'el-form-item is-error': form.errors.has('scheduled_at'),
                            }"
                          >
                           <el-date-picker
                              v-model="modelForm.scheduled_at"
                              type="datetime"
                              value-format="yyyy-MM-dd HH:mm:ss"
                              placeholder="Chọn thời gian">
                          </el-date-picker>
                            <div
                              class="el-form-item__error"
                              v-if="form.errors.has('scheduled_at')"
                              v-text="form.errors.first('scheduled_at')"
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
      list_fbnotification: [],
      modelForm: {
        title: "",
        content: "",
        topic: null,
        scheduled_at: "",
      },
      list_topic: [
        {
          label: 'Tất cả',
          value: null
        },
        {
          label: 'Android',
          value: 'android'
        },
        {
          label: 'IOS',
          value: 'ios'
        },
      ]
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
          this.$router.push({ name: "admin.fbnotification.index" });
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
          this.$router.push({ name: "admin.fbnotification.index" });
        })
        .catch(() => {});
    },

    fetchData() {
      let routeUri = "";
      if (this.$route.params.fbnotificationId !== undefined) {
        this.loading = true;
        routeUri = route("api.fbnotification.find", {
          fbnotification: this.$route.params.fbnotificationId,
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
      if (this.$route.params.fbnotificationId !== undefined) {
        return route("api.fbnotification.update", {
          fbnotification: this.$route.params.fbnotificationId,
        });
      }
      return route("api.fbnotification.store");
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

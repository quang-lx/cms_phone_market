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
              <el-breadcrumb-item :to="{ name: 'admin.rank.index' }"
                >{{ $t("rank.label.rank") }}
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
                            :label="$t('rank.label.name')"
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
                            :label="$t('rank.label.description')"
                            :class="{
                              'el-form-item is-error': form.errors.has('description'),
                            }"
                          >
                            <el-input v-model="modelForm.description"></el-input>
                            <div
                              class="el-form-item__error"
                              v-if="form.errors.has('description')"
                              v-text="form.errors.first('description')"
                            ></div>
                          </el-form-item>
                        </div>
                        <div class="col-md-10">
                          <el-form-item
                            :label="$t('rank.label.point')"
                            :class="{
                              'el-form-item is-error': form.errors.has('point'),
                            }"
                          >
                            <el-input v-model="modelForm.point"></el-input>
                            <div
                              class="el-form-item__error"
                              v-if="form.errors.has('point')"
                              v-text="form.errors.first('point')"
                            ></div>
                          </el-form-item>
                        </div>
                           <div class="col-md-10">
                          <el-form-item
                            :label="$t('rank.label.max_point')"
                            :class="{
                              'el-form-item is-error': form.errors.has('max_point'),
                            }"
                          >
                            <el-input v-model="modelForm.max_point"></el-input>
                            <div
                              class="el-form-item__error"
                              v-if="form.errors.has('max_point')"
                              v-text="form.errors.first('max_point')"
                            ></div>
                          </el-form-item>
                        </div>
                        <div class="col-md-10">
                          <single-media
                            zone="thumbnail"
                            @singleFileSelected="
                              selectSingleFile($event, 'modelForm')
                            "
                            label="Ảnh"
                            entity="Modules\Mon\Entities\rank"
                            :entity-id="$route.params.rankId"
                          ></single-media>
                          <div
                            class="el-form-item__error"
                            style="margin-left: 208px"
                            v-if="form.errors.has('medias_single.thumbnail')"
                            v-text="
                              form.errors.first('medias_single.thumbnail')
                            "
                          ></div>
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
      list_rank: [],
      modelForm: {
        name: "",
        description: "",
        point: "",
        max_point: "",
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
          this.$router.push({ name: "admin.rank.index" });
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
          this.$router.push({ name: "admin.rank.index" });
        })
        .catch(() => {});
    },

    fetchData() {
      let routeUri = "";
      if (this.$route.params.rankId !== undefined) {
        this.loading = true;
        routeUri = route("api.rank.find", {
          rank: this.$route.params.rankId,
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
      if (this.$route.params.rankId !== undefined) {
        return route("api.rank.update", {
          rank: this.$route.params.rankId,
        });
      }
      return route("api.rank.store");
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

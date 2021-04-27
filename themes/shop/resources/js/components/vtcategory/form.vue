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
              <el-breadcrumb-item :to="{ name: 'shop.vtcategory.index' }"
                >{{ $t("vtcategory.label.vtcategory") }}
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
                            :label="$t('vtcategory.label.name')"
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
                            :label="$t('vtcategory.label.parent_id')"
                            :class="{
                              'el-form-item is-error': form.errors.has(
                                'parent_id'
                              ),
                            }"
                          >
                            <el-select
                              v-model="modelForm.parent_id"
                              placeholder="Select"
                            >
                              <el-option
                                label="Chọn"
                                value=""
                              >
                              
                              </el-option>
                    
                              <el-option
                                v-for="item in list_vtcategory"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id"
                              >
                              </el-option>
                            </el-select>
                            <div
                              class="el-form-item__error"
                              v-if="form.errors.has('parent_id')"
                              v-text="form.errors.first('parent_id')"
                            ></div>
                          </el-form-item>
                        </div>
                        <div class="col-md-10">
                          <single-media
                            zone="thumbnail"
                            @singleFileSelected="
                              selectSingleFile($event, 'modelForm')
                            "
                            label="Ảnh đại diện"
                            entity="Modules\Mon\Entities\vtcategory"
                            :entity-id="$route.params.vtcategoryId"
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
      list_vtcategory: [],
      modelForm: {
        name: "",
        type: "",
        parent_id: "",
      },
      listType: [
        {
          value: "product",
          label: "Hàng hóa",
        },
        {
          value: "service",
          label: "Dịch vụ",
        }
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
          this.$router.push({ name: "shop.vtcategory.index" });
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
          this.$router.push({ name: "shop.vtcategory.index" });
        })
        .catch(() => {});
    },

    fetchData() {
      let routeUri = "";
      if (this.$route.params.vtcategoryId !== undefined) {
        this.loading = true;
        routeUri = route("apishop.vtcategory.find", {
          vtcategory: this.$route.params.vtcategoryId,
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

    fetchvtcategory() {
      let routeUri = "";
      this.loading = true;
      routeUri = route("apishop.vtcategory.index", { page: 1, per_page: 1000,parent_id:0,id_edit:this.$route.params.vtcategoryId });
      axios.get(routeUri).then((response) => {
        this.loading = false;
        this.list_vtcategory = response.data.data;
      });
    },

    getRoute() {
      if (this.$route.params.vtcategoryId !== undefined) {
        return route("apishop.vtcategory.update", {
          vtcategory: this.$route.params.vtcategoryId,
        });
      }
      return route("apishop.vtcategory.store");
    },
  },
  mounted() {
    this.fetchData();
    this.fetchvtcategory();
  },
  computed: {},
};
</script>

<style scoped>
</style>

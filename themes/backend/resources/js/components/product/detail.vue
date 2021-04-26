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
              <el-breadcrumb-item :to="{ name: 'admin.product.index' }"
                >{{ $t("product.label.detail_product") }}
              </el-breadcrumb-item>
              <el-breadcrumb-item>
                Sản phẩm {{ modelForm.name }}
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
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="row">
                        <div class="col-md-3">
                          <div class="row">
                            <div class="col-md-12">
                              <div>
                                {{ $t("product.label.name") }}:
                                {{ modelForm.name }}
                              </div>
                              <div>
                                {{ $t("product.label.sku") }}:
                                {{ modelForm.sku }}
                              </div>
                              <div>
                                {{ $t("product.label.category_id") }}:
                                 <span
                                    v-for="(item, index) in modelForm.category_name"
                                    :key="index"
                                    >
                                    <span v-if="modelForm.category_name.length-1 == index"> {{item}}</span>
                                    <span v-else> {{item}},</span>
                                  
                                  </span>
                              </div>
                              <div>
                                {{ $t("product.label.brand_id") }}:
                                 {{ modelForm.brand_name }}
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div>
                            {{ $t("product.label.company_id") }}:
                            {{ modelForm.company.name }}
                          </div>
                          <div>
                            {{ modelForm.company.email }}
                          </div>
                             <div>
                            {{ modelForm.company.phone }}
                          </div>
                             <div>
                            {{ modelForm.company.address }}
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div>
                            {{ $t("product.label.p_state") }}:
                            {{ modelForm.p_state_name }}
                          </div>
                          <div>
                            {{ $t("product.label.price") }}
                            :
                            {{ modelForm.price }}
                          </div>
                          <div>
                            {{ $t("product.label.amount") }}
                            :
                            {{ modelForm.amount }}
                          </div>
                          <div>
                            {{ $t("product.label.status") }}
                            :
                            <span :style="{ color: modelForm.status_color }">{{
                          modelForm.status_name
                        }}</span>
                          </div>
                        </div>
                        <!-- <div class="col-md-3">
                          <div>
                            {{ $t("product.label.p_state") }}:
                            {{ modelForm.p_state }}
                          </div>
                          <div>
                            {{ $t("product.label.price") }}
                            :
                            {{ modelForm.price }}
                          </div>
                          <div>
                            {{ $t("product.label.amount") }}
                            :
                            {{ modelForm.amount }}
                          </div>
                          <div>
                            {{ $t("product.label.p_state") }}
                            :
                            {{ modelForm.p_state }}
                          </div>
                        </div> -->
                        <div class="col-md-12 mt-5">
                          Ảnh/Video
                              <div>
                                <span
                                    v-for="(item, index) in modelForm.filse"
                                    :key="index"
                                    >
                                    <img :src="item.path_string" width="100" height="100" style="object-fit:contain"/>
                                </span>
                             </div>
                        </div>
                        <div class="col-md-12">
                            {{ $t("product.label.description") }}:
                            <div v-html="modelForm.description"></div>
                        </div>
                      </div>
                    </div>
                  </div>
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
import MultipleMedia from '../media/js/components/MultipleMedia';
import MultipleFileSelector from '../../mixins/MultipleFileSelector.js';
export default {
  props: {
    locales: { default: null },
    pageTitle: { default: null, String },
  },
    mixins: [SingleFileSelector, MultipleFileSelector],
    components: {
      MultipleMedia
    },
  data() {
    return {
      formLabelWidth: "120px",
      form: new Form(),
      loading: false,
      list_product: [],
      modelForm: {
        description: "",
      },
      dialogRank: false,
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
          this.$router.push({ name: "admin.product.index" });
        })
        .catch(() => {});
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
      routeUri = route("api.product.find", {
        product: this.$route.params.productId,
      });
      axios.get(routeUri).then((response) => {
        this.loading = false;
        this.modelForm = response.data.data;
        this.modelForm.is_new = false;
      });
    },

    getRoute() {
      if (this.$route.params.productId !== undefined) {
        return route("api.product.update", {
          product: this.$route.params.productId,
        });
      }
      return route("api.product.store");
    },
    showDataModal(key) {
      this.dialogRank = true;
    },
  },
  mounted() {
    this.fetchData();
  },
  computed: {},
};
</script>

<style scoped></style>

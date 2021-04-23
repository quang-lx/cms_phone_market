<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-12">
            <el-breadcrumb separator="/">
              <el-breadcrumb-item>
                <a href="/admin-shop">{{ $t("mon.breadcrumb.home") }}</a>
              </el-breadcrumb-item>
              <el-breadcrumb-item :to="{ name: 'shop.attribute.index' }"
                >{{ $t("attribute.label.attribute") }}
              </el-breadcrumb-item>
              <el-breadcrumb-item> {{ $t(pageTitle) }} </el-breadcrumb-item>
            </el-breadcrumb>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <el-form
       :rules="rules"
        ref="modelForm"
        :model="modelForm"
        label-width="200px"
        label-position="left"
        v-loading.body="loading"
      >
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div
                  class="card-header ui-sortable-handle"
                  style="cursor: move"
                >
                  <h3 class="card-title">
                    {{ $t(pageTitle)
                    }}<span v-if="modelForm.title"
                      >: &nbsp{{ modelForm.title }}</span
                    >
                  </h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="row">
                        <div class="col-md-6">
                          <el-form-item
                          prop="name"
                            :label="$t('attribute.label.name')"
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
                        <div class="col-md-6">
                          <el-form-item
                          prop="code"
                            :label="$t('attribute.label.code')"
                            :class="{
                              'el-form-item is-error': form.errors.has('code'),
                            }"
                          >
                            <el-input v-model="modelForm.code"></el-input>
                            <div
                              class="el-form-item__error"
                              v-if="form.errors.has('code')"
                              v-text="form.errors.first('code')"
                            ></div>
                          </el-form-item>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
              </div>

              <div class="card">
                <div
                  class="card-header ui-sortable-handle"
                  style="cursor: move"
                >
                  <h3 class="card-title">Giá trị</h3>
                  <div class="row">
                    <div class="col-md-6">
                      <el-form-item
                      prop="attr_value"
                        :class="{
                          'el-form-item is-error': form.errors.has(
                            'attr_value'
                          ),
                        }"
                      >
                        <el-input placeholder="giá trị" v-model="modelForm.attr_value"></el-input>
                        <div
                          class="el-form-item__error"
                          v-if="form.errors.has('attr_value')"
                          v-text="form.errors.first('attr_value')"
                        ></div>
                      </el-form-item>
                    </div>
                    <div class="col-md-6">
                      <el-button
                        v-if="is_new_value == true"
                        type="primary"
                        @click="createAttr('modelForm')"
                        class="btn btn-flat"
                      >
                        Thêm
                      </el-button>
                      <div v-else>
                        <el-button
                          type="primary"
                          @click="updateAttr"
                          class="btn btn-flat"
                        >
                          Cập nhật
                        </el-button>
                        <el-button
                          type="primary"
                          @click="onCancelValue"
                          class="btn btn-flat"
                        >
                          Hủy
                        </el-button>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <div class="row">
                    <div class="col-12">
                      <div class="sc-table">
                        <el-table
                          :data="modelForm.list_attribute_value"
                          stripe
                          style="width: 100%"
                          ref="dataTable"
                        >
                          <el-table-column
                            label="Stt"
                            width="75"
                            sortable="custom"
                          >
                            <template slot-scope="scope">
                              {{ (scope.$index += 1) }}
                            </template>
                          </el-table-column>

                          <el-table-column prop="name" label="Giá trị">
                          </el-table-column>

                          <el-table-column width="240">
                            <template slot-scope="scope">
                              <el-button
                                type="primary"
                                @click="editAttr(scope.$index, modelForm.list_attribute_value)"
                                icon="el-icon-edit"
                              ></el-button>
                              <el-button
                                type="primary"
                                @click="
                                  deleteAttr(scope.$index, modelForm.list_attribute_value)
                                "
                                icon="el-icon-delete"
                              ></el-button>
                            </template>
                          </el-table-column>
                        </el-table>
                      </div>

                      <!-- /.card-body -->
                    </div>
                  </div>
                </div>
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
      </el-form>
    </section>
  </div>
</template>

<script>
import axios from "axios";
import Form from "form-backend-validation";
import SingleFileSelector from "../../mixins/SingleFileSelector.js";
import Vue from "vue";
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
      is_new_value: true,
      key_update: "",

      modelForm: {
        name: "",
        code: "",
        attr_value: "",
        list_attribute_value: [],
      },
       rules: {
          attr_value: [
            { required: true, message: 'Giá trị không được để trống',trigger: 'submit' }
          ]
        }
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
          this.$router.push({ name: "shop.attribute.index" });
        })
        .catch((error) => {
          this.loading = false;
          this.$notify.error({
            title: this.$t("mon.error.Title"),
            message: this.getSubmitError(this.form.errors),
          });
        });
    },

     createAttr(formName) {
      this.$refs[formName].validate((valid) => {
          if (valid) {
            let attr_value={};
            attr_value.name= this.modelForm.attr_value
            this.modelForm.list_attribute_value.push(attr_value);
            this.modelForm.attr_value = ''
          } else {
            return false;
          }
      })

    },
    editAttr(index, rows) {
      this.modelForm.attr_value = rows[index].name;
      this.is_new_value = false;
      this.key_update = index;
    },

    deleteAttr(index, rows) {
      rows.splice(index, 1);
    },

    updateAttr() {
      this.modelForm.list_attribute_value[this.key_update].name = this.modelForm.attr_value
      this.is_new_value = true;
      this.modelForm.attr_value = ''
    },

    onCancelValue() {
      this.is_new_value = true;
    },


    onCancel() {
      this.$confirm(this.$t("mon.cancel.Are you sure to cancel?"), {
        confirmButtonText: this.$t("mon.cancel.Yes"),
        cancelButtonText: this.$t("mon.cancel.No"),
        type: "warning",
      })
        .then(() => {
          this.$router.push({ name: "shop.attribute.index" });
        })
        .catch(() => {});
    },

    getRoute() {
      if (this.$route.params.attributeId !== undefined) {
        return route("apishop.attribute.update", {
          attribute: this.$route.params.attributeId,
        });
      }
      return route("apishop.attribute.store");
    },
    fetchData() {
      let routeUri = "";
      if (this.$route.params.attributeId !== undefined) {
        this.loading = true;
        routeUri = route("apishop.attribute.find", {
          attribute: this.$route.params.attributeId,
        });
        axios.get(routeUri).then((response) => {
          this.loading = false;
          this.modelForm = response.data.data;
          Vue.set(this.modelForm, 'attr_value', "")
          this.modelForm.is_new = false;
        });
      } else {
        this.modelForm.is_new = true;
      }
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

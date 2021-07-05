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
              <el-breadcrumb-item :to="{ name: 'admin.gift.index' }"
                >{{ $t("gift.label.gift") }}
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
                            :label="$t('gift.label.name')"
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
                            :label="$t('gift.label.point')"
                            :class="{
                              'el-form-item is-error': form.errors.has('point'),
                            }"
                          >
                             <cleave v-model="modelForm.point" :options="options" class="form-control" name="point"></cleave>
                            <div
                              class="el-form-item__error"
                              v-if="form.errors.has('point')"
                              v-text="form.errors.first('point')"
                            ></div>
                          </el-form-item>
                        </div>
                        <div class="col-md-6">
                          <el-form-item
                            :label="$t('gift.label.amount')"
                            :class="{
                              'el-form-item is-error': form.errors.has('amount'),
                            }"
                          >
                             <cleave v-model="modelForm.amount" :options="options" class="form-control" name="amount"></cleave>
                            <div
                              class="el-form-item__error"
                              v-if="form.errors.has('amount')"
                              v-text="form.errors.first('amount')"
                            ></div>
                          </el-form-item>
                        </div>
                         <div class="col-md-6">
                          <single-media
                            zone="thumbnail"
                            @singleFileSelected="
                              selectSingleFile($event, 'modelForm')
                            "
                            :label="$t('gift.label.image')"
                            entity="Modules\Mon\Entities\Gift"
                            :entity-id="$route.params.giftId"
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
                         <!-- <div class="col-md-12">
                          <el-form-item
                            :label="$t('gift.label.description')"
                            :class="{
                              'el-form-item is-error': form.errors.has('description'),
                            }"
                          >
                           <el-input type="textarea" v-model="modelForm.description"></el-input>
                             <div
                              class="el-form-item__error"
                              v-if="form.errors.has('description')"
                              v-text="form.errors.first('description')"
                            ></div>

                          </el-form-item>
                        </div> -->
                          <div class="col-md-12">
                  <el-form-item
                    :label="$t('gift.label.description')"
                    :class="{
                      'el-form-item is-error': form.errors.has('description'),
                    }"
                  >
                    <div slot="label">
                      <label class="el-form-item__label">{{
                        $t("gift.label.description")
                      }}</label>
                    </div>
                    <tinymce
                      v-model="modelForm.description"
                      :height="500"
                    ></tinymce>
                    <div
                      class="el-form-item__error"
                      v-if="form.errors.has('description')"
                      v-text="form.errors.first('description')"
                    ></div>
                  </el-form-item>
                </div>
                          <div class="col-md-6">
                          <el-form-item
                            :label="$t('gift.label.status')"
                            :class="{
                              'el-form-item is-error': form.errors.has('status'),
                            }"
                          >
                            <el-radio-group
                              v-model="modelForm.status"
                              size="mini"
                            >
                              <el-radio
                                v-for="item in listStatus"
                                :key="'status-' + item.value"
                                :label="item.value"
                                >{{ item.label }}
                              </el-radio>
                            </el-radio-group>

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
import Cleave from 'vue-cleave-component';
import Tinymce from "../utils/Tinymce";

export default {
  props: {
    locales: { default: null },
    pageTitle: { default: null, String },
  },
  mixins: [SingleFileSelector],
  components: {
      Cleave,
      Tinymce
    },
  data() {
    return {
      form: new Form(),
      loading: false,
      list_gift: [],
      modelForm: {
        id: "",
        name: "",
        point: "",
        amount: "",
        used_amount: "",
        status: 1,
        description: ""
      },
      options: {
          prefix: '',
          numeral: true,
          numeralPositiveOnly: true,
          noImmediatePrefix: true,
          rawValueTrimPrefix: true,
          numeralIntegerScale: 12,
          numeralDecimalScale: 0
        },
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
          this.$router.push({ name: "admin.gift.index" });
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
          this.$router.push({ name: "admin.gift.index" });
        })
        .catch(() => {});
    },

    fetchData() {
      let routeUri = "";
      if (this.$route.params.giftId !== undefined) {
        this.loading = true;
        routeUri = route("api.gift.find", {
          gift: this.$route.params.giftId,
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
      if (this.$route.params.giftId !== undefined) {
        return route("api.gift.update", {
          gift: this.$route.params.giftId,
        });
      }
      return route("api.gift.store");
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

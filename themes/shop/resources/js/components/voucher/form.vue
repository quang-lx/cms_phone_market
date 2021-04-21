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
                    <a href="/shop-admin">{{ $t("mon.breadcrumb.home") }}</a>
                  </el-breadcrumb-item>
                  <el-breadcrumb-item :to="{ name: 'shop.voucher.index' }"
                    >{{ $t("voucher.label.list") }}
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
        <el-form
          ref="form"
          :model="modelForm"
          label-width="200px"
          label-position="top"
          v-loading.body="loading"
        >
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="row mb-20">
                    <div class="col-md-12">
                      <el-form-item label="Loại mã">
                        <el-radio-group v-model="modelForm.type">
                          <el-radio v-for="item in list_type"
                            :key="item.value"
                            :value="item.value"
                            :label="item.value">{{ item.label }}</el-radio>
                        </el-radio-group>
                      </el-form-item>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <el-form-item
                        :label="$t('voucher.label.title')"
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

                    <div class="col-md-12">
                      <el-form-item
                        :label="$t('voucher.label.code')"
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

                        <div
                          class="form-text text-muted"
                          v-text="$t('voucher.label.code-help')"
                        ></div>
                      </el-form-item>
                    </div>

                    <div class="col-md-12">
                      <el-form-item label="Thời gian sử dụng mã">
                        <el-col :span="11">
                          <el-date-picker
                            type="date"
                            placeholder="Thời gian bắt đầu hiệu lực"
                            v-model="modelForm.actived_at"
                            style="width: 100%"
                          ></el-date-picker>
                        </el-col>
                        <el-col class="line text-center" :span="2">-</el-col>
                        <el-col :span="11">
                          <el-date-picker
                            type="date"
                            placeholder="Thời gian kết thúc hiệu lực"
                            v-model="modelForm.expired_at"
                            style="width: 100%"
                          ></el-date-picker>
                        </el-col>
                      </el-form-item>
                    </div>

                    <div class="col-md-12">
                      <el-form-item
                        :label="$t('voucher.label.products')"
                        :class="{
                          'el-form-item is-error': form.errors.has(
                            'products'
                          ),
                        }"
                      >
                        <el-input
                          prefix-icon="el-icon-search"
                          @keyup.native="performSearch"
                          placeholder="Tìm sản phẩm"
                          v-model="searchQuery"
                        >
                        </el-input>
                        <div
                          class="el-form-item__error"
                          v-if="form.errors.has('products')"
                          v-text="form.errors.first('products')"
                        ></div>
                      </el-form-item>
                    </div>
                  </div>
                </div>

                <div class="offset-md-1 col-md-5">
                  <div class="col-md-10">
                    <div class="row mb-20">
                      <el-form-item label="Loại giảm giá">
                        <el-radio-group v-model="modelForm.discount_type">
                          <el-radio v-for="item in list_discount_type"
                            :key="item.value"
                            :value="item.value"
                            :label="item.value">{{ item.label }}</el-radio>
                        </el-radio-group>
                      </el-form-item>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <el-form-item
                        :label="$t('voucher.label.discount_amount')"
                        :class="{
                          'el-form-item is-error': form.errors.has('discount_amount'),
                        }"
                      >
                        <el-input v-model="modelForm.discount_amount"></el-input>
                        <div
                          class="el-form-item__error"
                          v-if="form.errors.has('discount_amount')"
                          v-text="form.errors.first('discount_amount')"
                        ></div>
                      </el-form-item>
                    </div>

                    <div class="col-md-12">
                      <el-form-item
                        :label="$t('voucher.label.require_min_amount')"
                        :class="{
                          'el-form-item is-error': form.errors.has('require_min_amount'),
                        }"
                      >
                        <el-input v-model="modelForm.require_min_amount"></el-input>
                        <div
                          class="el-form-item__error"
                          v-if="form.errors.has('require_min_amount')"
                          v-text="form.errors.first('require_min_amount')"
                        ></div>
                      </el-form-item>
                    </div>

                    <div class="col-md-12">
                      <el-form-item
                        :label="$t('voucher.label.total')"
                        :class="{
                          'el-form-item is-error': form.errors.has('total'),
                        }"
                      >
                        <el-input v-model="modelForm.total"></el-input>
                        <div
                          class="el-form-item__error"
                          v-if="form.errors.has('total')"
                          v-text="form.errors.first('total')"
                        ></div>
                      </el-form-item>
                    </div>
                  </div>
                </div>
                <div class="clear-both"></div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-12">
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
        </el-form>
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
    pageTitle: { default: null, String },
  },
  data() {
    return {
      medias_multi: {},
      form: new Form(),
      loading: false,
      modelForm: {
        title: "",
        code: "",
        type: 1,
        discount_type: 1,
        actived_at: new Date,
        expired_at: new Date,
        products: [],
        discount_amount: '',
        require_min_amount: '',
        total: '',
      },
      locales: window.MonCMS.locales,
      list_discount_type: [
        {
          value: 1,
          label: "Theo số tiền",
        },
        {
          value: 2,
          label: "Theo %",
        },
      ],

      list_type: [
        {
          value: 1,
          label: "Giảm giá toàn cửa hàng",
        },
        {
          value: 2,
          label: "Giảm giá sản phẩm",
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
          this.$router.push({ name: "shop.voucher.index" });
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
          this.$router.push({ name: "shop.voucher.index" });
        })
        .catch(() => {});
    },

    fetchData() {
      this.loading = true;
      let locale = this.$route.params.locale ? this.$route.params.locale : "en";
      axios
        .get(
          route("api.voucher.find", { voucher: this.$route.params.voucherId })
        )
        .then((response) => {
          this.loading = false;
          this.modelForm = response.data.data;
        });
    },

    getRoute() {
      if (this.$route.params.voucherId !== undefined) {
        return route("api.voucher.update", {
          voucher: this.$route.params.voucherId,
        });
      }
      return route("api.voucher.store");
    },
  },
  mounted() {
    if (this.$route.params.voucherId !== undefined) {
      this.fetchData();
    }
  },
  computed: {
  },
};
</script>

<style scoped>
.mb-20 {
  margin-bottom: 20px;
}
.mb-10 {
  margin-bottom: 10px;
}
.clear-both {
  clear: both;
}
</style>

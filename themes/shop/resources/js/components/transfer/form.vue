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
                  <el-breadcrumb-item :to="{ name: 'shop.transfer.index' }"
                    >{{ $t("transfer.label.manager") }}
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
                <h3 class="card-title">
                  {{ $t(pageTitle)
                  }}<span v-if="modelForm.title"
                    >: &nbsp{{ modelForm.title }}</span
                  >
                </h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body" style="padding-top: 20px">
                <el-form
                  ref="form"
                  :model="modelForm"
                  label-width="200px"
                  label-position="left"
                  v-loading.body="loading"
                >
                  <div class="row">
                    <div
                      class="col-md-6"
                      style="padding-top: 10px; padding-right: 30px"
                    >
                      <div class="row">
                        <div class="col-md-12" v-if="modelForm.id">
                          <el-form-item
                            :label="$t('transfer.label.id')"
                            :class="{
                              'el-form-item is-error': form.errors.has('id'),
                            }"
                          >
                            <el-input v-model="modelForm.id" :disabled="!modelForm.isEdit"></el-input>
                          </el-form-item>
                        </div>

                        <div class="col-md-12">
                          <el-form-item
                            :label="$t('transfer.label.title')"
                            :class="{
                              'el-form-item is-error': form.errors.has('title'),
                            }"
                          >
                            <el-input v-model="modelForm.title" :disabled="!modelForm.isEdit"></el-input>
                            <div
                              class="el-form-item__error"
                              v-if="form.errors.has('title')"
                              v-text="form.errors.first('title')"
                            ></div>
                          </el-form-item>
                        </div>

                        <div class="col-md-12">
                          <el-form-item
                            :label="$t('transfer.label.type')"
                            :class="{
                              'el-form-item is-error': form.errors.has(
                                'type'
                              ),
                            }"
                          >
                            <el-radio-group v-model="modelForm.type">
                              <el-radio
                                v-for="item in listType"
                                :key="item.value"
                                :value="item.value"
                                :label="item.value"
                                :disabled="!modelForm.isEdit && modelForm.type != item.value"
                                >{{ item.label }}</el-radio>
                            </el-radio-group>
                          </el-form-item>
                        </div>

                        <div class="col-md-12" :class="modelForm.type == 2 ? 'show' : 'hide'">
                          <el-form-item
                            :label="$t('transfer.label.to_shop_id')"
                            :class="{
                              'el-form-item is-error': form.errors.has(
                                'to_shop_id'
                              ),
                            }"
                          >
                            <el-select
                              v-model="modelForm.to_shop_id"
                              :placeholder="$t('transfer.label.to_shop_id')"
                              filterable
                              style="width: 100% !important"
                              :disabled="!modelForm.isEdit"
                            >
                              <el-option
                                v-for="item in shopArr"
                                :key="'shop' + item.id"
                                :label="item.name"
                                :value="item.id"
                              >
                              </el-option>
                            </el-select>
                            <div
                              class="el-form-item__error"
                              v-if="form.errors.has('to_shop_id')"
                              v-text="form.errors.first('to_shop_id')"
                            ></div>
                          </el-form-item>
                        </div>

                      </div>
                    </div>
                    <div class="col-md-6" style="padding-top: 10px">
                      <div class="row">
                        <div class="col-md-12">
                          <el-form-item
                            :label="$t('transfer.label.received_at')"
                            :class="{
                              'el-form-item is-error': form.errors.has(
                                'received_at'
                              ),
                            }"
                          >
                            <el-date-picker
                              v-model="modelForm.received_at"
                              value-format="yyyy-MM-dd HH:mm:ss"
                              format="HH:mm dd/MM/yyyy"
                              type="datetime"
                              placeholder="Thời gian chuyển"
                              :disabled="!modelForm.isEdit"
                            >
                            </el-date-picker>
                            <div
                              class="el-form-item__error"
                              v-if="form.errors.has('received_at')"
                              v-text="form.errors.first('received_at')"
                            ></div>
                          </el-form-item>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                        <el-form-item :label="$t('transfer.label.note')"
                                      :class="{'el-form-item is-error': form.errors.has(  'note') }">
                            <div slot="label">
                                <label class="el-form-item__label">{{$t('transfer.label.note')}}</label>
                            </div>
                            <el-input v-model="modelForm.note" type="textarea"
                                    :rows="2"></el-input>
                            <div class="el-form-item__error"
                                  v-if="form.errors.has('note')"
                                  v-text="form.errors.first('note')"></div>
                        </el-form-item>
                    </div>
                  </div>

                </el-form>
              </div>
            </div>

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Thông tin chi tiết vật tư</h3>
                <div class="card-tools">
                    <el-button size="mini" type="warning"  icon="el-icon-plus" @click="addMoreInfo">Thêm</el-button>
                </div>
              </div>
              <div class="card-body">
                <div
                  class="row mt-2"
                  v-for="(pinfo, key) in modelForm.vtProducts"
                  :key="key"
                >
                  <div class="col-md-3">
                      <el-select
                      
                          v-model="pinfo.catId"
                          filterable
                          @change="changeVtCategory(key)"
                          :disabled="!modelForm.isEdit"
                          placeholder="Chọn danh mục">
                          <el-option
                                label="Chọn danh mục"
                                value=""
                              >
                          </el-option>
                          <el-option
                            v-for="item in list_vtcategory"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id"
                            :class="item.parent_id ? 'pl-5' : ''"
                          >
                          </el-option>

                      </el-select>
                  </div>
                  <div class="col-md-3">
                    <el-select
                        v-model="pinfo.id"
                        filterable
                        :disabled="!modelForm.isEdit"
                        placeholder="Chọn vật tư">
                        <el-option
                          v-for="item in pinfo.listVtProduct"
                          :key="item.id"
                          :label="item.name"
                          :value="item.id"
                        >
                        </el-option>
                    </el-select>
                  </div>

                  <div class="col-md-2">
                      <cleave v-model="pinfo.count" :options="options" class="form-control"
                         name="count" placeholder="Số lượng" :disabled="!modelForm.isEdit"></cleave>
                  </div>
                  <div v-if="modelForm.isEdit" class="col-md-1 text-right d-flex justify-content-end align-items-center">
                    <i
                      class="el-icon-circle-close"
                      style="color: red; cursor: pointer"
                      @click="removeInfo(key)"
                    ></i>
                  </div>

                  
                </div>
                <div
                    class="el-form-item__error row"
                    v-if="form.errors.has('vtproduct')"
                    v-text="form.errors.first('vtproduct')"
                    style="position: inherit !important; margin-left:0px !important"
                  ></div>

              </div> 

              <el-dialog
                title="Xác nhận"
                :visible.sync="dialogVisible"
                width="30%">
                <span>Bạn có chắc muốn tạo đơn {{ modelForm.type == 1 ? 'Xuất' : 'Chuyển'}} kho</span>
                <span slot="footer" class="dialog-footer">
                  <el-button @click="dialogVisible = false">Hủy bỏ</el-button>
                  <el-button type="primary" @click="confirmDialog()">Xác nhận</el-button>
                </span>
              </el-dialog>               

              <div class="card-footer d-flex justify-content-end">
                  <el-button
                    v-if="modelForm.isEdit"
                    type="primary"
                    @click="dialogVisible = true"
                    size="small"
                    :loading="loading"
                    class="btn btn-flat"
                  >
                    {{ $t("mon.button.save") }}
                  </el-button>
                <el-button class="btn btn-flat" size="small" @click="onCancel()" v-if="modelForm.isEdit"
                  >{{ $t("mon.button.cancel") }}
                </el-button>

                <el-button 
                  v-if="!modelForm.isEdit"
                  type="primary" 
                  class="btn btn-flat"  
                  v-print>
                    IN PHIẾU
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
import Cleave from 'vue-cleave-component';
import print from 'vue-print-nb'

export default {
  props: {
    pageTitle: { default: null, String },
  },
  components: {
    Cleave
  },
  directives: {
    print   
  },
  data() {
    return {
      dialogVisible: false,
      options: {
        prefix: '',
        numeral: true,
        numeralPositiveOnly: true,
        noImmediatePrefix: true,
        rawValueTrimPrefix: true,
        numeralIntegerScale: 12,
        numeralDecimalScale: 0
      },
      form: new Form(),
      loading: false,
      list_vtcategory: [],
      modelForm: {
        title: "",
        status: 1,
        received_at: new Date("Y-m-d H:i:s"),
        to_shop_id: "",
        product_key: "",
        vtProducts: [],
        type: 1,
        isEdit: true,
        note:''
      },
      shopArr: [],
      locales: window.MonCMS.locales,
      productSearchResult: [],
      listType: [
        {
          value: 1,
          label: "Xuất kho",
        },
        {
          value: 2,
          label: "Chuyển kho",
        },
      ],
      currentShop : window.MonCMS.current_user.shop_id,
    };
  },
  methods: {
    confirmDialog(){
      this.dialogVisible = false;
      this.onSubmit();
    },
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
          this.$router.push({ name: "shop.transfer.index" });
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
          this.$router.push({ name: "shop.transfer.index" });
        })
        .catch(() => {});
    },

    fetchData() {
      this.loading = true;
      let locale = this.$route.params.locale ? this.$route.params.locale : "en";
      axios
        .get(
          route("apishop.transfer.find", {
            transferhistory: this.$route.params.transferId,
          })
        )
        .then((response) => {
          this.loading = false;
          this.modelForm = response.data.data;
          this.modelForm.isEdit = false;
        });
    },

    getRoute() {
      if (this.$route.params.transferId !== undefined) {
        return route("apishop.transfer.update", {
          transferhistory: this.$route.params.transferId,
        });
      }
      return route("apishop.transfer.store");
    },

    fetchShop() {
      const properties = {
        page: 0,
        per_page: 1000,
        check_company: true,
        except_current: true
      };

      axios
        .get(route("api.shop.index", _.merge(properties, {})))
        .then((response) => {
          this.shopArr = response.data.data;
        });
    },

    onSearchProduct(queryString, cb) {
      this.fetchVtProduct(queryString);
      cb(this.productSearchResult);
    },
    handleSelect(item) {
      //add thêm vào biến products lưu danh sách các product đc giảm giá
      this.modelForm.products.push(item);
    },
    removeInfo(key) {
      this.modelForm.vtProducts.splice(key,1);
    },
    fetchVtCategory() {
      let routeUri = "";
      this.loading = true;
      routeUri = route("apishop.vtcategory.index", { page: 1, per_page: 1000, not_check_shop: true});
      axios.get(routeUri).then((response) => {
        this.loading = false;
        this.list_vtcategory = response.data.data;
      });
    },
    addMoreInfo() {
      this.modelForm.vtProducts.push({
        catId: '',
        id: '',
        count: '',
        listVtProduct: [],
      })
    },
    changeVtCategory(key){
      //load danh sach vật tư của category mới chọn
      let vtCatId = this.modelForm.vtProducts[key].catId;
      this.fetchVtProduct(vtCatId, key);
    },
    fetchVtProduct(vtCatId, key) {
      this.loading = true;
      const properties = {
        page: 0,
        per_page: 1000,
        catId: vtCatId,
        not_check_shop: true
      };

      axios
        .get(route("apishop.vtproduct.index", _.merge(properties, {})))
        .then((response) => {
          this.loading = false;
          this.modelForm.vtProducts[key].listVtProduct = response.data.data;
          return response.data.data;
        });
    },
  },

  mounted() {
    this.fetchShop();
    this.fetchVtCategory();
    if (this.$route.params.transferId !== undefined) {
      this.fetchData();
    }
  },
  computed: {},
};
</script>

<style scoped>
.el-autocomplete {
  width: 100%;
}
.el-date-editor.el-input,
.el-date-editor.el-input__inner {
  width: 100%;
}
.show {
  display: block;
}
.hide {
  display: none;
}
</style>

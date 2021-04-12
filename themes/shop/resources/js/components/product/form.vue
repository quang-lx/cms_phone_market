<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-12">
                        <el-breadcrumb separator="/">
                            <el-breadcrumb-item>
                                <a href="/shop-admin">{{ $t('mon.breadcrumb.home') }}</a>
                            </el-breadcrumb-item>
                            <el-breadcrumb-item :to="{name: 'shop.product.index'}">{{ $t('product.label.list') }}
                            </el-breadcrumb-item>
                            <el-breadcrumb-item> {{ $t(pageTitle) }}
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
                            <div class="card-header ui-sortable-handle" style="cursor: move;">
                                <h3 class="card-title">
                                    {{ $t(pageTitle) }}<span v-if="modelForm.name">: &nbsp{{modelForm.name}}</span>
                                </h3>

                            </div><!-- /.card-header -->
                            <div class="card-body" style="padding-top:0px">

                                <el-form ref="form"
                                         :model="modelForm"
                                         label-width="200px"
                                         label-position="top"
                                         v-loading.body="loading"
                                >
                                    <div class="row">
                                        <div class="col-md-9" style="padding-top:10px;padding-right:30px">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <el-form-item :label="$t('product.label.name')"
                                                                  :class="{'el-form-item is-error': form.errors.has('name') }">

                                                        <el-input v-model="modelForm.name"></el-input>
                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('name')"
                                                             v-text="form.errors.first('name')"></div>
                                                    </el-form-item>
                                                </div>

                                                <div class="col-md-12">
                                                    <el-form-item :label="$t('product.label.description')"
                                                                  :class="{'el-form-item is-error': form.errors.has(  'description') }">
                                                        <div slot="label">
                                                            <label class="el-form-item__label">{{$t('product.label.description')}}</label>
                                                        </div>
                                                        <tinymce v-model="modelForm.description"
                                                                 :height="600"></tinymce>
                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('description')"
                                                             v-text="form.errors.first('description')"></div>
                                                    </el-form-item>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3"
                                             style="padding-top:10px;border-left: 1px solid rgba(0,0,0,.125);">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <el-form-item :label="$t('product.label.status')"
                                                                  :class="{'el-form-item is-error': form.errors.has(  'status') }">

                                                        <el-select v-model="modelForm.status"
                                                                   :placeholder="$t('product.label.status')"
                                                                   filterable style="width: 100% !important">
                                                            <el-option
                                                                v-for="item in listStatus"
                                                                :key="'status'+ item.value"
                                                                :label="item.label"
                                                                :value="item.value">
                                                            </el-option>

                                                        </el-select>
                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('status')"
                                                             v-text="form.errors.first('status')"></div>
                                                    </el-form-item>
                                                </div>

                                                <div class="col-md-12">
                                                    <el-form-item :label="$t('product.label.p_state')"
                                                                  :class="{'el-form-item is-error': form.errors.has(  'p_state') }">

                                                        <el-select v-model="modelForm.p_state"
                                                                   :placeholder="$t('product.label.p_state')"
                                                                   filterable style="width: 100% !important">
                                                            <el-option
                                                                v-for="item in listState"
                                                                :key="'state'+ item.value"
                                                                :label="item.label"
                                                                :value="item.value">
                                                            </el-option>

                                                        </el-select>
                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('p_state')"
                                                             v-text="form.errors.first('p_state')"></div>
                                                    </el-form-item>
                                                </div>

                                                <div class="col-md-12">
                                                    <el-form-item :label="$t('product.label.p_weight')"
                                                                  :class="{'el-form-item is-error': form.errors.has('p_weight') }">

                                                        <el-input v-model="modelForm.p_weight"></el-input>
                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('p_weight')"
                                                             v-text="form.errors.first('p_weight')"></div>
                                                    </el-form-item>
                                                </div>
                                                <div class="col-md-12">
                                                    <el-form-item :label="$t('product.label.s_long')"
                                                                  :class="{'el-form-item is-error': form.errors.has('s_long') }">

                                                        <el-input v-model="modelForm.s_long"></el-input>
                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('s_long')"
                                                             v-text="form.errors.first('s_long')"></div>
                                                    </el-form-item>
                                                </div>
                                                <div class="col-md-12">
                                                    <el-form-item :label="$t('product.label.s_width')"
                                                                  :class="{'el-form-item is-error': form.errors.has('s_width') }">

                                                        <el-input v-model="modelForm.s_width"></el-input>
                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('s_width')"
                                                             v-text="form.errors.first('s_width')"></div>
                                                    </el-form-item>
                                                </div>
                                                <div class="col-md-12">
                                                    <el-form-item :label="$t('product.label.s_height')"
                                                                  :class="{'el-form-item is-error': form.errors.has('s_height') }">

                                                        <el-input v-model="modelForm.s_height"></el-input>
                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('s_height')"
                                                             v-text="form.errors.first('s_height')"></div>
                                                    </el-form-item>
                                                </div>

                                                <div class="col-md-12">
                                                    <el-form-item :label="$t('product.label.brand_id')"
                                                                  :class="{'el-form-item is-error': form.errors.has(  'brand_id') }">

                                                        <el-select v-model="modelForm.brand_id"
                                                                   :placeholder="$t('product.label.brand_id')"
                                                                   filterable style="width: 100% !important">
                                                            <el-option
                                                                v-for="item in brandArr"
                                                                :key="'brand'+ item.id"
                                                                :label="item.name"
                                                                :value="item.id">
                                                            </el-option>

                                                        </el-select>
                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('brand_id')"
                                                             v-text="form.errors.first('brand_id')"></div>
                                                    </el-form-item>
                                                </div>


                                            </div>
                                        </div>


                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header ui-sortable-handle" style="cursor: move;">
                                                    <h3 class="card-title">
                                                        Mua nhiều giảm giá
                                                        <el-button type="primary" @click="addProductPrice()"
                                                                   size="small" style="margin-left:20px"
                                                                   class="btn btn-flat "
                                                                   icon="el-icon-circle-plus-outline">
                                                            Thêm khoảng giá
                                                        </el-button>
                                                    </h3>

                                                </div><!-- /.card-header -->
                                                <div class="card-body" style="padding-top:20px">
                                                    <div class="row" v-for="(item, index) in modelForm.product_prices"
                                                         :key="index">
                                                        <div class="col-md-2">
                                                            <el-form-item label="Số tối thiểu"
                                                                          :class="{'el-form-item is-error': form.errors.has('product_price.'+index + 'min' )  }">

                                                                <el-input-number v-model="item.min"
                                                                                 placeholder="Số tối thiểu"></el-input-number>
                                                                <div class="el-form-item__error"
                                                                     v-if="form.errors.has('product_price.'+index + 'min')"
                                                                     v-text="form.errors.first('product_price.'+index + 'min')"></div>
                                                            </el-form-item>


                                                        </div>
                                                        <div class="col-md-2">

                                                            <el-form-item label="Số tối đa"
                                                                          :class="{'el-form-item is-error': form.errors.has('product_price.'+index + 'max')  }">

                                                                <el-input-number v-model="item.max"
                                                                                 placeholder="Số tối đa"></el-input-number>
                                                                <div class="el-form-item__error"
                                                                     v-if="form.errors.has('product_price.'+index + 'max')"
                                                                     v-text="form.errors.first('product_price.'+index + 'max')"></div>
                                                            </el-form-item>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <el-form-item label="Giá sản phẩm"
                                                                          :class="{'el-form-item is-error': form.errors.has('product_price.'+index + 'price' )  }">

                                                                <el-input-number v-model="item.price"
                                                                                 placeholder="Giá sản phẩm"></el-input-number>
                                                                <div class="el-form-item__error"
                                                                     v-if="form.errors.has('product_price.'+index + 'price')"
                                                                     v-text="form.errors.first('product_price.'+index + 'price')"></div>
                                                            </el-form-item>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <el-form-item  label=" " >

                                                            <i class="el-icon-circle-close" @click="removeProductPrice(index)" style="cursor:pointer; color:red;font-size:26px"></i>
                                                            </el-form-item>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </el-form>
                            </div><!-- /.card-body -->
                            <div class="card-footer d-flex justify-content-end">
                                <el-button type="primary" @click="onSubmit()" size="small" :loading="loading"
                                           class="btn btn-flat ">
                                    {{ $t('mon.button.save') }}
                                </el-button>
                                <el-button class="btn btn-flat " size="small" @click="onCancel()">{{
                                    $t('mon.button.cancel') }}
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
  import axios from 'axios';
  import Form from 'form-backend-validation';
  import Tinymce from '../utils/Tinymce';
  import SingleFileSelector from '../../mixins/SingleFileSelector.js';

  export default {
    mixins: [SingleFileSelector],
    components: {
      Tinymce,
    },
    props: {
      pageTitle: {default: null, String},
    },
    data() {
      return {
        form: new Form(),
        loading: false,
        modelForm: {
          name: '',
          description: '',
          status: '1',
          p_state: '1',
          p_weight: '',
          s_long: '',
          s_width: '',
          s_height: '',
          brand_id: '',
          product_prices: []

        },
        locales: window.MonCMS.locales,
        brandArr: [],
        listStatus: [
          {
            value: '1',
            label: 'Có'
          },
          {
            value: '0',
            label: 'Không'
          },

        ],
        listState: [
          {
            value: '1',
            label: 'Mới'
          },
          {
            value: '2',
            label: '99%'
          },

        ],


      };
    },
    methods: {
      onSubmit() {
        this.form = new Form(_.merge(this.modelForm, {}));
        this.loading = true;

        this.form.post(this.getRoute())
        .then((response) => {
          this.loading = false;
          this.$message({
            type: 'success',
            message: response.message,
          });
          this.$router.push({name: 'shop.product.index'});
        })
        .catch((error) => {

          this.loading = false;
          this.$notify.error({
            title: this.$t('mon.error.Title'),
            message: this.getSubmitError(this.form.errors),
          });
        });
      },
      onCancel() {

        this.$confirm(this.$t('mon.cancel.Are you sure to cancel?'), {
          confirmButtonText: this.$t('mon.cancel.Yes'),
          cancelButtonText: this.$t('mon.cancel.No'),
          type: 'warning'
        }).then(() => {
          this.$router.push({name: 'shop.product.index'});
        }).catch(() => {

        });


      },

      fetchData() {
        this.loading = true;
        let locale = this.$route.params.locale ? this.$route.params.locale : 'en';
        axios.get(route('api.product.find', {product: this.$route.params.productId}))
        .then((response) => {
          this.loading = false;
          this.modelForm = response.data.data;

        });
      },

      getRoute() {
        if (this.$route.params.productId !== undefined) {
          return route('api.product.update', {product: this.$route.params.productId});
        }
        return route('api.product.store');
      },

      fetchBrand() {
        const properties = {
          page: 0,
          per_page: 1000,

        };

        axios.get(route('api.brand.index', _.merge(properties, {})))
        .then((response) => {

          this.brandArr = response.data.data;

        });
      },
      addProductPrice() {
        this.modelForm.product_prices.push({min: '', max: '', price: ''})
      },
      removeProductPrice(index) {
        this.modelForm.product_prices.splice(index, 1)
      }
    },
    mounted() {
      this.fetchBrand();
      if (this.$route.params.productId !== undefined) {
        this.fetchData();
      } else {
        this.addProductPrice();
      }

    },
    computed: {},

  }
</script>

<style scoped>

</style>

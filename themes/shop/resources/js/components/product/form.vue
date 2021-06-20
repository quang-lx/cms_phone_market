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
                                        <a href="/shop-admin">{{ $t('mon.breadcrumb.home') }}</a>
                                    </el-breadcrumb-item>
                                    <el-breadcrumb-item :to="{name: 'shop.product.index'}">{{ $t('product.label.list')
                                        }}
                                    </el-breadcrumb-item>
                                    <el-breadcrumb-item> {{ $t(pageTitle) }}
                                    </el-breadcrumb-item>
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
                <el-form ref="form"
                         :model="modelForm"
                         label-width="200px"
                         label-position="top"
                         v-loading.body="loading"
                >
                    <div class="row">

                        <div class="col-md-8">
                            <div class="card">

                                <div class="card-body">


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
                                                         :height="500"></tinymce>
                                                <div class="el-form-item__error"
                                                     v-if="form.errors.has('description')"
                                                     v-text="form.errors.first('description')"></div>
                                            </el-form-item>
                                        </div>

                                    </div>

                                </div>


                            </div>
                            <div class="card">

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <multiple-media zone="product_collection"
                                                            v-if ="showMedia"
                                                            label="Ảnh/Video"
                                                            @multipleFileSelected="selectMultipleFile($event, 'modelForm')"
                                                            @fileUnselected="unselectFile($event, 'modelForm')"
                                                            @fileSorted="fileSorted($event, 'modelForm')"
                                                            entity="Modules\Mon\Entities\Product"
                                                            :entity-id="$route.params.productId"></multiple-media>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header"  >
                                    <h3 class="card-title">Thông tin chi tiết</h3>
                                    <div class="card-tools">
                                        <el-button size="mini" type="warning"  icon="el-icon-plus" @click="addMoreInfo">Thêm</el-button>

                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row mt-2" v-for="(pinfo,key) in modelForm.pinformations" :key="key">
                                        <div class="col-md-3">
                                            <el-select
                                                v-model="pinfo.id"
                                                allow-create
                                                filterable
                                                placeholder="Chọn nhãn">
                                                <el-option
                                                    v-for="item in list_information"
                                                    :key="item.id"
                                                    :label="item.title"
                                                    :value="item.id">
                                                </el-option>
                                            </el-select>
                                        </div>
                                        <div class="col-md-8">
                                            <el-input v-model="pinfo.value"></el-input>
                                        </div>
                                        <div
                                            class="col-md-1   text-right d-flex justify-content-end align-items-center">
                                            <i class="el-icon-circle-close" style="color:red; cursor:pointer"
                                               @click="removeInfo(key)"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <el-form-item
                                        :label="$t('product.label.attribute extend')"
                                        :class="{
                              'el-form-item is-error': form.errors.has('list_attribute'),
                            }"
                                    >
                                        <el-select clearable
                                            v-model="modelForm.attribute_id"
                                            placeholder=""
                                            @change="changeAttribute"
                                        >
                                            <el-option
                                                v-for="item in list_attribute"
                                                :key="item.id"
                                                :label="item.name"
                                                :value="item.id"
                                            >
                                            </el-option>
                                        </el-select>
                                        <div
                                            class="el-form-item__error"
                                            v-if="form.errors.has('list_attribute')"
                                            v-text="form.errors.first('list_attribute')"
                                        ></div>
                                    </el-form-item>

                                </div>
                                <div class="card-body" v-if="modelForm.attribute_selected">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h3 class = "card-title">{{modelForm.attribute_selected.name}}</h3>
                                                    <div class="card-tools" >
                                                        <el-select
                                                            v-model="value_id"
                                                            @change="changeValue"
                                                            :disabled="values.length == 0"
                                                        >
                                                            <el-option
                                                                v-if="values.length> 0 "
                                                                v-for="item in values"
                                                                :key="item.id"
                                                                :label="item.name"
                                                                :value="item.id"
                                                            >
                                                            </el-option>
                                                        </el-select>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-2 col-sm-6"> </div>
                                                        <div class="col-md-3 col-sm-6">
                                                            Giá
                                                        </div>
                                                        <div class="col-md-3 col-sm-6">
                                                            Chiết khấu (%)
                                                        </div>
                                                        <div class="col-md-3 col-sm-6">
                                                            Số lượng
                                                        </div>
                                                        <div
                                                            class="col-md-1 col-sm-6 text-right d-flex justify-content-end align-items-center">

                                                        </div>
                                                    </div>
                                                    <div class="row"
                                                         v-for="(itemValue, valueKey) in modelForm.attribute_selected.values"
                                                         :key="valueKey">
                                                        <div class="col-12 mt-2">
                                                            <div class="row">
                                                                <div class="col-md-2 col-sm-6"> {{itemValue.name}}</div>
                                                                <div class="col-md-3 col-sm-6">
                                                                    <cleave v-model="itemValue.price" :options="options" class="form-control" name="price"></cleave>
                                                                </div>
                                                                <div class="col-md-3 col-sm-6">
                                                                    <cleave v-model="itemValue.sale_price" :options="options" class="form-control" name="sale_price"></cleave>
                                                                </div>
                                                                <div class="col-md-3 col-sm-6">
                                                                  <cleave v-model="itemValue.amount" :options="options" class="form-control" name="amount"></cleave>

                                                                </div>
                                                                <div
                                                                    class="col-md-1 col-sm-6 text-right d-flex justify-content-end align-items-center">
                                                                    <i class="el-icon-circle-close" style="color:red; cursor:pointer"
                                                                       @click="removeAttributeValue(itemValue.id)"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-4">
                            <div class="card">

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 ">
                                            <el-form-item :label="$t('product.label.type')"
                                                          :class="{'el-form-item is-error': form.errors.has('type') }">
                                                <el-radio-group v-model="modelForm.type">
                                                    <el-radio v-for = "(item, key) in list_type" :label="item.value" :key="key">{{item.label}}</el-radio>

                                                </el-radio-group>

                                                <div class="el-form-item__error"
                                                     v-if="form.errors.has('type')"
                                                     v-text="form.errors.first('type')"></div>
                                            </el-form-item>
                                        </div>
                                        <div class="col-md-6">
                                            <el-form-item :label="$t('product.label.warranty_time')"
                                                          :class="{'el-form-item is-error': form.errors.has('warranty_time') }">

                                                <cleave v-model="modelForm.warranty_time" :options="options" class="form-control" name="warranty_time"></cleave>
                                                <div class="el-form-item__error"
                                                     v-if="form.errors.has('warranty_time')"
                                                     v-text="form.errors.first('warranty_time')"></div>
                                            </el-form-item>
                                        </div>
                                        <div class="col-md-6" v-if="modelForm.type == 2">
                                            <el-form-item :label="$t('product.label.fix_time')"
                                                          :class="{'el-form-item is-error': form.errors.has('fix_time') }">

                                                <cleave v-model="modelForm.fix_time" :options="options" class="form-control" name="fix_time"></cleave>
                                                <div class="el-form-item__error"
                                                     v-if="form.errors.has('fix_time')"
                                                     v-text="form.errors.first('fix_time')"></div>
                                            </el-form-item>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="card">

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 ">
                                            <el-form-item :label="$t('product.label.category_id')"
                                                          :class="{'el-form-item is-error': form.errors.has('category_id') }">
                                                <div class="tree-container">

                                                    <el-checkbox-group v-model="modelForm.category_id"  class="problem-container" @change="changeCategory">
                                                        <el-checkbox v-for="(item,key) in category_tree_data" :key="key"
                                                                     :label="item.id"> {{item.name}}
                                                        </el-checkbox>
                                                    </el-checkbox-group>
                                                </div>

                                                <div class="el-form-item__error"
                                                     v-if="form.errors.has('category_id')"
                                                     v-text="form.errors.first('category_id')"></div>
                                            </el-form-item>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="card" v-if="modelForm.type == 2">

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 ">
                                            <el-form-item :label="$t('product.label.problem_id')"
                                                          :class="{'el-form-item is-error': form.errors.has('problem_id') }">

                                                <el-checkbox-group v-model="modelForm.problem_id"
                                                                   class="problem-container">
                                                    <el-checkbox v-for="(item,key) in list_problem" :key="key"
                                                                 :label="item.id"> {{item.title}}
                                                    </el-checkbox>
                                                </el-checkbox-group>


                                                <div class="el-form-item__error"
                                                     v-if="form.errors.has('problem_id')"
                                                     v-text="form.errors.first('problem_id')"></div>
                                            </el-form-item>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="card">

                                <div class="card-body">
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

                                        <div class="col-md-6">
                                            <el-form-item :label="$t('product.label.p_weight')"
                                                          :class="{'el-form-item is-error': form.errors.has('p_weight') }">

                                                <cleave v-model="modelForm.p_weight" :options="options_odd_number" class="form-control" name="p_weight"></cleave>

                                                <div class="el-form-item__error"
                                                     v-if="form.errors.has('p_weight')"
                                                     v-text="form.errors.first('p_weight')"></div>
                                            </el-form-item>
                                        </div>
                                        <div class="col-md-6">
                                            <el-form-item :label="$t('product.label.s_long')"
                                                          :class="{'el-form-item is-error': form.errors.has('s_long') }">

                                                <cleave v-model="modelForm.s_long" :options="options_odd_number" class="form-control" name="s_long"></cleave>
                                                <div class="el-form-item__error"
                                                     v-if="form.errors.has('s_long')"
                                                     v-text="form.errors.first('s_long')"></div>
                                            </el-form-item>
                                        </div>
                                        <div class="col-md-6">
                                            <el-form-item :label="$t('product.label.s_width')"
                                                          :class="{'el-form-item is-error': form.errors.has('s_width') }">

                                                <cleave v-model="modelForm.s_width" :options="options_odd_number" class="form-control" name="s_width"></cleave>
                                                <div class="el-form-item__error"
                                                     v-if="form.errors.has('s_width')"
                                                     v-text="form.errors.first('s_width')"></div>
                                            </el-form-item>
                                        </div>
                                        <div class="col-md-6">
                                            <el-form-item :label="$t('product.label.s_height')"
                                                          :class="{'el-form-item is-error': form.errors.has('s_height') }">

                                                <cleave v-model="modelForm.s_height" :options="options_odd_number" class="form-control" name="s_height"></cleave>
                                                <div class="el-form-item__error"
                                                     v-if="form.errors.has('s_height')"
                                                     v-text="form.errors.first('s_height')"></div>
                                            </el-form-item>
                                        </div>
                                        <div class="col-md-12">
                                            <el-form-item :label="$t('product.label.sku')"
                                                          :class="{'el-form-item is-error': form.errors.has('sku') }">

                                                <el-input v-model="modelForm.sku"></el-input>
                                                <div class="el-form-item__error"
                                                     v-if="form.errors.has('sku')"
                                                     v-text="form.errors.first('sku')"></div>
                                            </el-form-item>
                                        </div>
                                        <div class="col-md-12">
                                            <el-form-item :label="$t('product.label.amount')"
                                                          :class="{'el-form-item is-error': form.errors.has('amount') }">
                                                <cleave v-model="modelForm.amount" :options="options" class="form-control" name="amount"></cleave>
                                                <div class="el-form-item__error"
                                                     v-if="form.errors.has('amount')"
                                                     v-text="form.errors.first('amount')"></div>
                                            </el-form-item>
                                        </div>
                                        <div class="col-md-6">
                                            <el-form-item :label="$t('product.label.price')"
                                                          :class="{'el-form-item is-error': form.errors.has('price') }">
                                                <cleave v-model="modelForm.price" :options="options" class="form-control" name="price"></cleave>
                                                <div class="el-form-item__error"
                                                     v-if="form.errors.has('price')"
                                                     v-text="form.errors.first('price')"></div>
                                            </el-form-item>
                                        </div>
                                        <div class="col-md-6">
                                            <el-form-item :label="$t('product.label.sale_price')"
                                                          :class="{'el-form-item is-error': form.errors.has('sale_price') }">
                                                <cleave v-model="modelForm.sale_price" :options="options" class="form-control" name="sale_price"></cleave>
                                                <div class="el-form-item__error"
                                                     v-if="form.errors.has('sale_price')"
                                                     v-text="form.errors.first('sale_price')"></div>
                                            </el-form-item>
                                        </div>

                                        <div class="col-md-12" v-if="!this.currentShop">
                                          <el-form-item
                                            :label="$t('product.label.shop_id')"
                                            :class="{
                                              'el-form-item is-error': form.errors.has('shop_id'),
                                            }"
                                          >
                                            <el-select v-model="modelForm.shop_id" placeholder="Chọn chi nhánh">
                                                <el-option
                                                        v-for="item in listShop"
                                                        :key="item.id"
                                                        :label="item.name"
                                                        :value="item.id">
                                                </el-option>
                                            </el-select>
                                            <div
                                              class="el-form-item__error"
                                              v-if="form.errors.has('shop_id')"
                                              v-text="form.errors.first('shop_id')"
                                            ></div>

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

                        </div>
                        <div class="col-12">
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
                </el-form>
            </div>
        </section>

    </div>


</template>

<script>
  import axios from 'axios';
  import Form from 'form-backend-validation';
  import Tinymce from '../utils/Tinymce';
  import SingleFileSelector from '../../mixins/SingleFileSelector.js';
  import MultipleMedia from '../media/js/components/MultipleMedia';
  import MultipleFileSelector from '../../mixins/MultipleFileSelector.js';
  import Cleave from 'vue-cleave-component';

  export default {
    mixins: [SingleFileSelector, MultipleFileSelector],
    components: {
      Tinymce,
      MultipleMedia,
      Cleave
    },
    props: {
      pageTitle: {default: null, String},
    },
    data() {
      return {
        options: {
          prefix: '',
          numeral: true,
          numeralPositiveOnly: true,
          noImmediatePrefix: true,
          rawValueTrimPrefix: true,
          numeralIntegerScale: 12,
          numeralDecimalScale: 0
        },
        options_odd_number: {
          prefix: '',
          numeral: true,
          numeralPositiveOnly: true,
          noImmediatePrefix: true,
          rawValueTrimPrefix: true,
          numeralIntegerScale: 12,
          numeralDecimalScale: 2
        },
        defaultProps: {
          children: 'children',
          label: 'label'
        },
        value_id: '',
        medias_multi: {},
        form: new Form(),
        loading: false,
        list_selected_values: [],
        list_type: [
          {
            value: 1,
            label: 'Sản phẩm bán hàng'
          },
          {
            value: 2,
            label: 'Sản phẩm sửa chữa'
          }
        ],
        modelForm: {
          name: '',
          description: '',
          status: 1,
          p_state: 1,
          p_weight: '',
          s_long: '',
          s_width: '',
          s_height: '',
          brand_id: '',
          sku: '',
          price: '',
          sale_price: '',
          amount: '',
          category_id: [],
          problem_id: [],
          pinformations: [
            {
              id:'',
              value: ''
            }
          ],
          attribute_id: '',
          attribute_selected: null,
          type: 1,
          warranty_time: null,
          fix_time: null,
          shop_id: '',

        },
        showMedia: false,
        locales: window.MonCMS.locales,
        brandArr: [],
        category_tree_data: [],
        list_problem: [],
        list_attribute: [],
        list_information: [],
        listStatus: [
            {
                value: 1,
                label: 'Hoạt động'
            },
            {
                value: 0,
                label: 'Đã ẩn'
            },
            {
                value: 2,
                label: 'Hàng sắp về'
            },

        ],
        listState: [
          {
            value: 1,
            label: 'Mới'
          },
          {
            value: 2,
            label: 'Đã sử dụng'
          },

        ],
        currentShop : window.MonCMS.current_user.shop_id,
        listShop: [],

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
        axios.get(route('apishop.product.find', {product: this.$route.params.productId}))
        .then((response) => {
          this.loading = false;
          this.modelForm = response.data.data;
          this.showMedia = true
        });
      },

      getRoute() {
        if (this.$route.params.productId !== undefined) {
          return route('apishop.product.update', {product: this.$route.params.productId});
        }
        return route('apishop.product.store');
      },

      fetchBrand() {
        const properties = {
          page: 0,
          per_page: 1000,

        };

        axios.get(route('apishop.brand.index', _.merge(properties, {})))
        .then((response) => {

          this.brandArr = response.data;

        });
      },
      fetchCategory() {
        const properties = {
          page: 0,
          per_page: 1000,

        };

        axios.get(route('apishop.product.tree', _.merge(properties, {})))
        .then((response) => {

          this.category_tree_data = response.data.categories_tree;
          this.list_problem = response.data.list_problem;
          this.list_attribute = response.data.list_attribute;
          // this.list_attribute = this.removeDefaultPrice(response.data.list_attribute);
          this.list_information = response.data.list_information;

        });
      },
        changeCategory() {
            axios.get(route('apishop.product.problemByCat',{category_id: this.modelForm.category_id}))
              .then((response) => {
                  this.list_problem = response.data;

              });
        },
        // removeDefaultPrice(listAttribute){
        //   listAttribute.forEach(attr => {
        //     attr.values.forEach(element => {
        //       element.price = '';
        //     });
        //   });

        //   return listAttribute;
        // },
      updateValue: function (value) {
        // update parent data so that we can still v-model on the parent
        this.$emit('input', value);
      },

      handleCheckChange(checkedNode, treeCheckedStatus) {
        this.modelForm.category_id = treeCheckedStatus.checkedKeys
      },

      changeAttribute() {
        const itemInRoot = _.findIndex(this.list_attribute, {id: this.modelForm.attribute_id})
        console.log(itemInRoot)
        if (itemInRoot !== -1) {
          if (this.modelForm.attribute_selected) {
            this.list_selected_values[this.modelForm.attribute_selected.id] = _.cloneDeep(this.modelForm.attribute_selected);
          }
          this.modelForm.attribute_selected = _.cloneDeep(this.list_attribute[itemInRoot]);
          // gan lai cac gia tri da chon
          if (this.list_selected_values[this.modelForm.attribute_selected.id]) {
            this.modelForm.attribute_selected.values = _.cloneDeep(this.list_selected_values[this.modelForm.attribute_selected.id].values);
          }
        }else{
            this.modelForm.attribute_id = ""
            this.modelForm.attribute_selected = null
        }
      },

      removeAttributeValue(attribute_value_id) {
        const valueIndex = _.findIndex(this.modelForm.attribute_selected.values, {id: attribute_value_id})
        if (valueIndex !== -1) {
          this.modelForm.attribute_selected.values.splice(valueIndex, 1)
        }
      },
      changeValue(value_id) {
        const valueIndex = _.findIndex(this.values, {id: value_id})
        if (valueIndex !== -1) {
          this.modelForm.attribute_selected.values.push(_.cloneDeep(this.values[valueIndex]))
          this.value_id = ''
        }
      },
      addMoreInfo() {
        this.modelForm.pinformations.push({
          id: '',
          value: ''
        })
      },
      removeInfo(index) {
        this.modelForm.pinformations.splice(index,1)
      },
      fetchShop() {
        const properties = {
            page: 0,
            per_page: 1000,
            check_company: true,
        };

        axios
            .get(route("api.shop.index", _.merge(properties, {})))
            .then((response) => {
                this.listShop = response.data.data;

            });
      },
    },
    mounted() {

      this.fetchCategory();
      this.fetchBrand();
      if (this.$route.params.productId !== undefined) {
        this.fetchData();
      } else {
        this.showMedia = true
      }
      if (!this.currentShop){
        this.fetchShop();
      }

    },
    computed: {
      values: function() {
        if (this.modelForm.attribute_id) {
          const index = _.findIndex(this.list_attribute,{id: this.modelForm.attribute_id});
          if (index !== -1) {
            const values  = this.list_attribute[index].values
            return values.filter(item => _.findIndex(this.modelForm.attribute_selected.values, {id: item.id}) === -1)
          }
        }
        return []
      }
    },

  }
</script>

<style scoped>
    .tree-container {
        max-height: 200px;
        overflow-y: auto;
    }

    .problem-container {
        max-height: 200px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }
</style>

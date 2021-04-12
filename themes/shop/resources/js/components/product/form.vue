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
                                                        <tinymce v-model="modelForm.description" :height="800"></tinymce>
                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('description')"
                                                             v-text="form.errors.first('description')"></div>
                                                    </el-form-item>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3" style="padding-top:10px;border-left: 1px solid rgba(0,0,0,.125);">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <el-form-item :label="$t('product.label.status')"
                                                                    :class="{'el-form-item is-error': form.errors.has(  'status') }">

                                                        <el-select v-model="modelForm.status" :placeholder="$t('product.label.status')"
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

                                                        <el-select v-model="modelForm.p_state" :placeholder="$t('product.label.p_state')"
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

<!--                                                        <el-input v-model="modelForm.amount"></el-input>-->
                                                        <el-input-number v-model="modelForm.amount" :min="1" :max="100000"></el-input-number>

                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('amount')"
                                                             v-text="form.errors.first('amount')"></div>
                                                    </el-form-item>
                                                </div>
                                                <div class="col-md-12">
                                                    <el-form-item :label="$t('product.label.price')"
                                                                  :class="{'el-form-item is-error': form.errors.has('price') }">

                                                        <el-input v-model="modelForm.price"></el-input>
                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('price')"
                                                             v-text="form.errors.first('price')"></div>
                                                    </el-form-item>
                                                </div>

                                                <div class="col-md-12">
                                                    <el-form-item :label="$t('product.label.brand_id')"
                                                                  :class="{'el-form-item is-error': form.errors.has(  'brand_id') }">

                                                        <el-select v-model="modelForm.brand_id" :placeholder="$t('product.label.brand_id')"
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
                                                <div class="col-md-12">
                                                    <el-form-item :label="$t('product.label.category_id')"
                                                                  :class="{'el-form-item is-error': form.errors.has(  'category_id') }">

                                                        <el-select v-model="modelForm.category_id" :placeholder="$t('product.label.category_id')"
                                                                   filterable style="width: 100% !important">
                                                            <el-option
                                                                    v-for="item in categoryArr"
                                                                    :key="'category'+ item.id"
                                                                    :label="item.name"
                                                                    :value="item.id">
                                                            </el-option>

                                                        </el-select>
                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('category_id')"
                                                             v-text="form.errors.first('category_id')"></div>
                                                    </el-form-item>
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
                modelForm:{
                    name: '',
                    description: '',
                    status: 1,
                    p_state: 1,
                    p_weight: '',
                    s_long: '',
                    s_width: '',
                    s_height: '',
                    brand_id: '',
                    category_id: '',

                },
                locales: window.MonCMS.locales,
                brandArr: [],
                categoryArr: [],
                listStatus: [
                    {
                        value: 1,
                        label: 'Có'
                    },
                    {
                        value: 0,
                        label: 'Không'
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


            };
        },
        methods: {
            onSubmit() {
                this.form = new Form(_.merge(this.modelForm, { }));
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
                let locale = this.$route.params.locale? this.$route.params.locale: 'en';
                axios.get(route('api.product.find', {product: this.$route.params.productId }))
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
            fetchCategory() {
                const properties = {
                    page: 0,
                    per_page: 1000,

                };

                axios.get(route('api.category.index', _.merge(properties, {})))
                    .then((response) => {

                        this.categoryArr = response.data.data;

                    });
            },
        },
        mounted() {
            this.fetchBrand();
            this.fetchCategory();
            if (this.$route.params.productId !== undefined) {
                this.fetchData();
            }

        },
        computed: {

        },

    }
</script>

<style scoped>
    .block-status {
        padding: 6px 20px;
        margin-left: 20px;
    }

    .btn-default {
        background-color: #f4f4f4;
        color: #444;
        border-color: #ddd;
        color: white;
    }

    .btn-default:hover,
    .btn-default:active,
    .btn-default.hover {
        background-color: #e7e7e7;
        color: white;
    }

    .btn-primary {
        background-color: #3c8dbc;
        border-color: #367fa9;
        color: white;
    }

    .btn-primary:hover,
    .btn-primary:active,
    .btn-primary.hover {
        background-color: #367fa9;
        color: white;
    }

    .btn-success {
        background-color: #00a65a;
        border-color: #008d4c;
        color: white;
    }

    .btn-success:hover,
    .btn-success:active,
    .btn-success.hover {
        background-color: #008d4c;
        color: white;
    }

    .btn-info {
        background-color: #00c0ef;
        border-color: #00acd6;
        color: white;
    }

    .btn-info:hover,
    .btn-info:active,
    .btn-info.hover {
        background-color: #00acd6;
        color: white;
    }

    .btn-danger {
        background-color: #dd4b39;
        border-color: #d73925;
        color: white;
    }

    .btn-danger:hover,
    .btn-danger:active,
    .btn-danger.hover {
        background-color: #d73925;
        color: white;
    }

    .btn-warning {
        background-color: #f39c12;
        border-color: #e08e0b;
        color: white;
    }

    .btn-warning:hover,
    .btn-warning:active,
    .btn-warning.hover {
        background-color: #e08e0b;
        color: white;
    }

    .btn-outline {
        border: 1px solid #fff;
        background: transparent;
        color: #fff;
    }

    .btn-outline:hover,
    .btn-outline:focus,
    .btn-outline:active {
        color: rgba(255, 255, 255, 0.7);
        border-color: rgba(255, 255, 255, 0.7);
        color: white;
    }
</style>

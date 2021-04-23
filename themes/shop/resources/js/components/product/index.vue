<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6 d-flex align-items-center">
                                <el-breadcrumb separator="/">
                                    <el-breadcrumb-item>
                                        <a href="/shop-admin">{{ $t('mon.breadcrumb.home') }}</a>
                                    </el-breadcrumb-item>
                                    <el-breadcrumb-item  >{{ $t('product.label.create_product') }}
                                    </el-breadcrumb-item>

                                </el-breadcrumb>

                            </div>
                            <div class="col-sm-6">
                                <div class="row pull-right">

                                    <div class="col-6">
                                        <el-input prefix-icon="el-icon-search" @keyup.native="performSearch"
                                                  placeholder="Tên sản phẩm/SKU"
                                                  v-model="searchQuery">
                                        </el-input>
                                    </div>
                                    <div class="col-3">
                                        <router-link :to="{name: 'shop.product.create'}">
                                            <el-button type="primary" class="btn btn-flat">
                                                {{ $t('product.label.create_product') }}
                                            </el-button>
                                        </router-link>
                                    </div>
                                </div>


                            </div>

                        </div>

                        <div class="row pull-right search-block">
                            <div class="col-sm-4">

                                <el-select v-model="filter.brand_id" placeholder="Lọc theo thương hiệu"
                                           @change="onSearchChange()" clearable>
                                    <el-option
                                            v-for="item in brandArr"
                                            :key="item.id"
                                            :label="item.name"
                                            :value="item.id">
                                    </el-option>
                                </el-select>
                            </div>

                            <div class="col-sm-4">

                                <el-select v-model="filter.status" placeholder="Lọc theo trạng thái"
                                           @change="onSearchChange()" clearable>
                                    <el-option
                                            v-for="item in listStatus"
                                            :key="item.value"
                                            :label="item.label"
                                            :value="item.value">
                                    </el-option>
                                </el-select>
                            </div>

                            <!-- <div class="col-sm-3">

                                <el-select v-model="filter.company_id" placeholder="Lọc theo cửa hàng"
                                           @change="onSearchChange()" clearable>
                                    <el-option
                                            v-for="item in listStatus"
                                            :key="item.value"
                                            :label="item.label"
                                            :value="item.value">
                                    </el-option>
                                </el-select>
                            </div> -->

                            <div class="col-sm-4">
                                <el-select v-model="filter.category_id" placeholder="Lọc theo danh mục"
                                           @change="onSearchChange()" clearable>
                                    <el-option
                                            v-for="item in categoryArr"
                                            :key="item.id"
                                            :label="item.name"
                                            :value="item.id">
                                    </el-option>
                                </el-select>
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

                            <div class="card-body">
                                <div class="sc-table">

                                    <el-table
                                      :data="data"
                                      stripe
                                      style="width: 100%"
                                      ref="dataTable"
                                      v-loading.body="tableIsLoading"
                                      @sort-change="handleSortChange">
                                        <el-table-column prop="id" :label="$t('product.label.id')" width="75" sortable="custom">
                                        </el-table-column>

                                        <el-table-column prop=""  :label="$t('product.label.image')" >
                                            <template slot-scope="scope">
                                                <img :src="scope.row.thumbnail.path_string" v-if="scope.row.thumbnail"
                                                     width="100" height="100" style="object-fit:contain"/>
                                            </template>
                                        </el-table-column>

                                        <el-table-column prop="name" :label="$t('product.label.name')" sortable="custom">

                                        </el-table-column>

                                        <el-table-column prop="" :label="$t('product.label.amount')" sortable="custom">
                                            <template slot-scope="scope">
                                                {{ formatNumber(scope.row.amount)}}
                                            </template>
                                        </el-table-column>

                                        <el-table-column prop="" :label="$t('product.label.category_id')" sortable="custom">
                                            <template slot-scope="scope">
                                                <span
                                                        v-for="(item, index) in scope.row.category_name"
                                                        :key="index"
                                                >
                                               <span v-if="scope.row.category_name.length-1==index" class="dont-break-out">{{item}}</span>
                                               <span v-else class="dont-break-out">{{item}},&nbsp</span>
                                               </span>
                                            </template>
                                        </el-table-column>

                                        <el-table-column prop="company_id" :label="$t('product.label.company_id')" sortable="custom">
                                            <template slot-scope="scope">
                                                {{ scope.row.company_name}}
                                            </template>
                                        </el-table-column>

                                        <el-table-column prop="" :label="$t('product.label.price')" sortable="custom">
                                            <template slot-scope="scope">
                                                {{ formatNumber(scope.row.price)}}
                                            </template>
                                        </el-table-column>

                                        <el-table-column prop="status" :label="$t('product.list.status')"
                                                         sortable="custom">
                                            <template slot-scope="scope">
                                                <span :style="{'color': scope.row.status_color}">{{scope.row.status_name}}</span>
                                            </template>
                                        </el-table-column>

                                        <el-table-column prop="updated_at" label="Ngày cập nhật" sortable="custom">
                                        </el-table-column>

                                        <el-table-column prop="actions" width="130">
                                            <template slot-scope="scope">
                                                <edit-button
                                                  :to="{name: 'shop.product.edit', params: {productId: scope.row.id}}"></edit-button>
                                                <delete-button :scope="scope" :rows="data"></delete-button>
                                            </template>
                                        </el-table-column>
                                    </el-table>

                                    <div class="pagination-wrap">
                                        <el-pagination
                                            @size-change="handleSizeChange"
                                            @current-change="handleCurrentChange"
                                            :current-page.sync="meta.current_page"
                                            :page-sizes="[25, 50, 75, 100]"
                                            :page-size="parseInt(meta.per_page)"
                                            :layout="pagingSetting.layout"
                                            :total="meta.total">
                                        </el-pagination>
                                    </div>

                                </div>
                            </div><!-- /.card-body -->
                        </div>
                    </div>
                </div>

            </div>
        </section>

    </div>

</template>

<script>
    import axios from 'axios';

    export default {

        data() {
            return {
                data: [],


                currentLocale: window.MonCMS.currentLocale || 'en',

                filter: {
                    category_id:'',
                    status: '',
                    brand_id: '',
                    company_id: '',
                    locale: window.MonCMS.currentLocale || 'en'
                },
                listLocales: window.MonCMS.locales,
                brandArr: [],
                categoryArr: [],
                listCompany: [
                    {
                        value: 1,
                        label: 'TEST'
                    }
                ],
                listStatus: [
                    {
                        value: 1,
                        label: 'Hoạt động'
                    },
                    {
                        value: 0,
                        label: 'Đã xóa'
                    },

                ],

            };
        },
        methods: {
            queryServer(customProperties) {
                const properties = {
                    page: this.meta.current_page,
                    per_page: this.meta.per_page,
                    order_by: this.order_meta.order_by,
                    order: this.order_meta.order,
                    search: this.searchQuery,
                    status: this.filter.status,
                    brand_id: this.filter.brand_id,
                    category_id: this.filter.category_id,

                };

                axios.get(route('api.product.index', _.merge(properties, customProperties)))
                    .then((response) => {
                        this.tableIsLoading = false;
                        this.data = response.data.data;
                        this.meta = response.data.meta;
                        this.links = response.data.links;
                        this.order_meta.order_by = properties.order_by;
                        this.order_meta.order = properties.order;
                    });
            },
            formatNumber(number){
                return number.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')
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

                axios.get(route('apishop.pcategory.index', _.merge(properties, {})))
                .then((response) => {

                    this.categoryArr = response.data;

                });
            },

            onSearchChange() {
                this.meta.current_page = 0;
                this.queryServer({})
            },


        },
        mounted() {
            this.fetchData();
            this.fetchBrand();
            this.fetchCategory();

        },
    }

    
</script>

<style scoped>
    .search-block {
        margin-top: 10px;
        padding-right: 33px;
    }
</style>

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
                                        <a href="/admin">{{ $t('mon.breadcrumb.home') }}</a>
                                    </el-breadcrumb-item>
                                    <el-breadcrumb-item  >{{ $t('product.label.manager') }}
                                    </el-breadcrumb-item>

                                </el-breadcrumb>

                            </div>
                            <div class="col-sm-6">
                                <div class="row  ">

                                    <div class="col-sm-6 offset-sm-6">
                                        <el-input prefix-icon="el-icon-search" @keyup.native="performSearch"
                                                  placeholder="Tên sản phẩm/SKU"
                                                  style="width:100%"
                                                  v-model="searchQuery">
                                        </el-input>
                                    </div>
                                </div>


                            </div>

                        </div>

                        <div class="row pull-right search-block">
                            <div class="col-sm-3">

                                <el-select v-model="filter.brand_id" placeholder="Lọc theo thương hiệu" filterable
                                           @change="onSearchChange()" clearable>
                                    <el-option
                                            v-for="item in brandArr"
                                            :key="item.id"
                                            :label="item.name"
                                            :value="item.id">
                                    </el-option>
                                </el-select>
                            </div>

                            <div class="col-sm-3">

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

                            <div class="col-sm-3">

                                <el-select v-model="filter.company_id" placeholder="Lọc theo cửa hàng" filterable
                                           @change="onSearchChange()" clearable>
                                    <el-option
                                            v-for="item in companyArr"
                                            :key="item.id"
                                            :label="item.name"
                                            :value="item.id">
                                    </el-option>
                                </el-select>
                            </div>

                            <div class="col-sm-3">
                                <el-select v-model="filter.category_id" placeholder="Lọc theo danh mục" filterable
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
                                                {{scope.row.amount.toLocaleString('vi-VN', {style: 'currency',currency : 'VND'})}}
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

                                        <el-table-column prop="company.name" :label="$t('product.label.company_id')" sortable="custom">
                                        </el-table-column>

                                        <el-table-column prop="" :label="$t('product.label.price')" sortable="custom">
                                            <template slot-scope="scope">
                                                {{ scope.row.price.toLocaleString('vi-VN', {style: 'currency',currency : 'VND'})}}
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
                                                <router-link :to="{name: 'admin.product.detail', params: {productId: scope.row.id}}">
                                                    <i class="el-icon-view"></i>
                                                </router-link>
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
                companyArr: [],
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
                        label: 'Đã ẩn'
                    },
                    {
                        value: 2,
                        label: 'Hàng sắp về'
                    },

                ],

                properties: {
                    page: 0,
                    per_page: 1000,
                    order_by:'name',
                    order:'ascending'
                }

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
                    company_id: this.filter.company_id,

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
            fetchBrand() {

                axios.get(route('api.brand.index', _.merge(this.properties, {})))
                .then((response) => {

                    this.brandArr = response.data.data;

                });
            },
            fetchCategory() {

                axios.get(route('api.pcategory.index', _.merge(this.properties, {})))
                .then((response) => {

                    this.categoryArr = response.data.data;

                });
            },

            fetchCompany() {

                axios.get(route('api.company.index', _.merge(this.properties, {})))
                .then((response) => {

                    this.companyArr = response.data.data;

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
            this.fetchCompany()

        },
    }


</script>

<style scoped>
    .search-block {
        margin-top: 10px;

    }
</style>

<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4 d-flex align-items-center">
                                <el-breadcrumb separator="/">
                                    <el-breadcrumb-item>
                                        <a href="/shop-admin">{{ $t('mon.breadcrumb.home') }}</a>
                                    </el-breadcrumb-item>
                                    <el-breadcrumb-item  >{{ $t('voucher.label.list') }}
                                    </el-breadcrumb-item>

                                </el-breadcrumb>

                            </div>
                            <div class="col-sm-8">
                                <div class="row pull-right">

                                    <div class="col-6">
                                        <el-input prefix-icon="el-icon-search" @keyup.native="performSearch"
                                                  placeholder="Tìm theo mã giảm giá"
                                                  v-model="searchQuery" clearable>
                                        </el-input>
                                    </div>
                                    <div class="col-6">
                                        <router-link :to="{name: 'shop.voucher.create'}">
                                            <el-button type="primary" class="btn btn-flat">
                                                {{ $t('voucher.label.btn_add_voucher') }}
                                            </el-button>
                                        </router-link>
                                    </div>
                                </div>


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
                                        <el-table-column prop="id" :label="$t('voucher.label.id')" width="60" sortable="custom">
                                        </el-table-column>

                                        <el-table-column prop="title" :label="$t('voucher.label.title')" sortable="address" width="170">
                                            <template slot-scope="scope">
                                                <span class="dont-break-out">{{scope.row.title}}</span>
                                            </template>
                                        </el-table-column>

                                        <el-table-column prop="code" :label="$t('voucher.label.code')" sortable="custom" width="120">
                                        </el-table-column>

                                        <el-table-column prop="type" :label="$t('voucher.label.type_name')" sortable="custom" >
                                            <template slot-scope="scope">
                                                <span class="dont-break-out">{{scope.row.type_name}}</span>
                                            </template>
                                        </el-table-column>

                                        <el-table-column prop="discount_amount" :label="$t('voucher.label.discount_amount')"
                                            sortable="custom" width="180">
                                        </el-table-column>

                                        <el-table-column prop="actived_at" :label="$t('voucher.label.status')" sortable="custom"  >
                                            <template slot-scope="scope">
                                                <span class="dont-break-out">{{scope.row.status_name}}</span>
                                            </template>
                                        </el-table-column>

                                        <el-table-column prop="total" :label="$t('voucher.label.total')" sortable="custom"  >
                                        </el-table-column>

                                        <el-table-column prop="total_used" :label="$t('voucher.label.total_used')" sortable="custom" width="100">
                                        </el-table-column>

                                        <el-table-column prop="created_by" :label="$t('voucher.label.updated_by')" sortable="custom" width="140">
                                            <template slot-scope="scope">
                                                <span class="dont-break-out">{{scope.row.created_name}}</span>
                                            </template>
                                        </el-table-column>

                                        <el-table-column prop="updated_at" :label="$t('voucher.label.updated_at')" sortable="custom" width="140">
                                            <template slot-scope="scope">
                                                <span class="dont-break-out">{{scope.row.updated_at}}</span>
                                            </template>
                                        </el-table-column>

                                        <el-table-column prop="actions" width="130">
                                            <template slot-scope="scope">
                                                <edit-button
                                                  :to="{name: 'shop.voucher.edit', params: {voucherId: scope.row.id}}"></edit-button>
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
                    locale: window.MonCMS.currentLocale || 'en'
                },
                listLocales: window.MonCMS.locales,


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

                };

                axios.get(route('api.voucher.index', _.merge(properties, customProperties)))
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

            onSearchChange() {
                this.meta.current_page = 0;
                this.queryServer({})
            },


        },
        mounted() {
            this.fetchData();

        },
    }


</script>

<style scoped>
</style>

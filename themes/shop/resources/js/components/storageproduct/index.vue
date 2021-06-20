<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="row ">
                            <div class="col-sm-3 d-flex align-items-center">
                                <el-breadcrumb separator="/">
                                    <el-breadcrumb-item>
                                        <a href="/shop-admin">{{ $t('mon.breadcrumb.home') }}</a>
                                    </el-breadcrumb-item>
                                    <el-breadcrumb-item>{{ $t('storageproduct.label.manager') }}
                                    </el-breadcrumb-item>
                                </el-breadcrumb>
                            </div>
                            <div class="col-sm-9">
                                <div class="row pull-right col-sm-4 offset-sm-8">
                                    <div class="col-12">
                                        <el-input prefix-icon="el-icon-search" @keyup.native="performSearch"
                                                  placeholder="Tên đơn hàng"
                                                  v-model="searchQuery" clearable>
                                        </el-input>
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
                                        <el-table-column prop="id" :label="$t('storageproduct.label.id')" width="75"
                                                         sortable="custom">

                                        </el-table-column>
                                        <el-table-column prop="title" :label="$t('storageproduct.label.title')"
                                                         sortable="custom"/>

                                        <el-table-column prop="shop_name" :label="$t('storageproduct.label.shop_name')"
                                                         sortable="address">
                                            <template slot-scope="scope">
                                                <span class="dont-break-out">{{scope.row.shop_id ? scope.row.shop_name : $t('storageproduct.label.shop_admin')}}</span>
                                            </template>
                                        </el-table-column>
                                        <el-table-column prop="to_shop_name" :label="$t('storageproduct.label.to_shop_name')"
                                                         sortable="address">
                                            <template slot-scope="scope">
                                                <span class="dont-break-out">{{scope.row.to_shop_name}}</span>
                                            </template>
                                        </el-table-column>

                                        <el-table-column prop="status" :label="$t('storageproduct.label.status')"
                                                         sortable="custom">
                                            <template slot-scope="scope">
                                                <span :style="{'color': scope.row.status_color}">{{scope.row.status_name}}</span>
                                            </template>
                                        </el-table-column>

                                        <el-table-column prop="received_at" :label="$t('storageproduct.label.received_at')"
                                                         sortable="custom" width="200">
                                            <template slot-scope="scope">
                                                <span>{{ scope.row.received_at_format }}</span>
                                            </template>
                                        </el-table-column>

                                        <el-table-column prop="updated_at" :label="$t('storageproduct.label.updated_at')" sortable="custom">
                                        </el-table-column>

                                        <el-table-column prop="actions" width="130">
                                            <template slot-scope="scope">

                                                <el-button type="primary" icon="el-icon-view"
                                                    @click="gotosite(scope.row.id)">
                                                </el-button>
                                                
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

                columnsSearch: [],
                listFilterColumn: [],
                filter: {
                    searchQuery: '',
                },
                shopArr: [],

            };
        },
        methods: {
            queryServer(customProperties) {

                const properties = {
                    page: this.meta.current_page,
                    per_page: this.meta.per_page,
                    order_by: this.order_meta.order_by,
                    order: this.order_meta.order,
                    search: this.searchQuery
                };

                axios.get(route('apishop.storageproduct.index', _.merge(properties, customProperties)))
                    .then((response) => {
                        this.tableIsLoading = false;
                        this.tableIsLoading = false;
                        this.data = response.data.data;
                        this.meta = response.data.meta;
                        this.links = response.data.links;
                        this.order_meta.order_by = properties.order_by;
                        this.order_meta.order = properties.order;
                    });
            },
            onSearchChange() {
                this.meta.current_page = 0;
                this.queryServer({})
            },
            gotosite(storageproductId){
                window.location.href = route('shop.storageproduct.edit', storageproductId);
            }
            

        },

        mounted() {
            this.fetchData();

        },
    }
</script>

<style scoped>

</style>

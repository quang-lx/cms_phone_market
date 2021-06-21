<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">


                <div class="card">
                    <div class="card-body">
                        <div class="row ">
                            <div :class="!this.currentShop ? 'col-sm-4' : 'col-sm-5'">
                                <el-breadcrumb separator="/">
                                    <el-breadcrumb-item>
                                        <a href="/shop-admin">{{ $t('mon.breadcrumb.home') }}</a>
                                    </el-breadcrumb-item>
                                    <el-breadcrumb-item>{{ $t('transfer.label.manager') }}
                                    </el-breadcrumb-item>

                                </el-breadcrumb>

                            </div>
                            <div :class="!this.currentShop ? 'col-sm-8' : 'col-sm-7'">
                                <div class="row pull-right col-sm-12">

                                    <div class="col-3" v-if="!this.currentShop">

                                        <el-select v-model="filter.to_shop_id" placeholder="Lọc theo chi nhánh"
                                                   @change="onSearchChange()" clearable>
                                            <el-option
                                                    v-for="item in shopArr"
                                                    :key="item.id"
                                                    :label="item.name"
                                                    :value="item.id">
                                            </el-option>
                                        </el-select>
                                    </div>
                                    <div class="col-3">

                                        <el-select v-model="filter.type" placeholder="Lọc theo loại đơn"
                                                   @change="onSearchChange()" clearable>
                                            <el-option
                                                    v-for="item in listType"
                                                    :key="item.value"
                                                    :label="item.label"
                                                    :value="item.value">
                                            </el-option>
                                        </el-select>
                                    </div>
                                    <div :class="!this.currentShop ? 'col-3' : 'col-6'">
                                        <el-input prefix-icon="el-icon-search" @keyup.native="performSearch"
                                                  placeholder="Tên đơn hàng"
                                                  v-model="searchQuery">
                                        </el-input>
                                    </div>
                                    <div class="col-3">
                                        <router-link :to="{name: 'shop.transfer.create'}">
                                            <el-button type="primary" class="btn btn-flat" style="width:100%">
                                                {{ $t('transfer.label.create_new') }}
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
                                        <el-table-column prop="id" :label="$t('transfer.label.id')" width="90"
                                                         sortable="custom">
                                        </el-table-column>
                                        <el-table-column prop="title" :label="$t('transfer.label.title')"
                                                         sortable="custom" width="100"/>
                                        <el-table-column prop="type" :label="$t('transfer.label.type')"
                                                         sortable="custom" width="100">
                                            <template slot-scope="scope">
                                                <span :style="{'color': scope.row.type_color}">{{scope.row.type_name}}</span>
                                            </template>
                                        </el-table-column>
                                        
                                        <el-table-column prop="shop_name" :label="$t('transfer.label.shop_name')"
                                                         sortable="address" width="130">
                                            <template slot-scope="scope">
                                                <span class="dont-break-out">{{scope.row.shop_id ? scope.row.shop_name : scope.row.company_name}}</span>
                                            </template>
                                        </el-table-column>

                                        <el-table-column prop="to_shop_name" :label="$t('transfer.label.to_shop_name')"
                                                         sortable="address" width="120">
                                            <template slot-scope="scope">
                                                <span class="dont-break-out">{{scope.row.to_shop_name}}</span>
                                            </template>
                                        </el-table-column>

                                        <el-table-column prop="status" :label="$t('transfer.label.status')"
                                                         sortable="custom" width="120">
                                            <template slot-scope="scope">
                                                <span :style="{'color': scope.row.status_color}">{{scope.row.status_name}}</span>
                                            </template>
                                        </el-table-column>

                                        <el-table-column prop="created_name" :label="$t('transfer.label.created_name')"
                                                         sortable="address" width="120">
                                            <template slot-scope="scope">
                                                <span class="dont-break-out">{{scope.row.created_name}}</span>
                                            </template>
                                        </el-table-column>

                                        <el-table-column prop="received_at" :label="$t('transfer.label.received_at')"
                                                         sortable="custom" width="200">
                                            <template slot-scope="scope">
                                                <span>{{ scope.row.received_at_format }}</span>
                                            </template>
                                        </el-table-column>

                                        <el-table-column prop="updated_at" :label="$t('transfer.label.updated_at')" 
                                            sortable="custom" width="150">
                                            <template slot-scope="scope">
                                                <span>{{ scope.row.updated_at_format }}</span>
                                            </template>
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
                    to_shop_id: '',
                    searchQuery: '',
                    type: '',
                },
                shopArr: [],
                listType: [
                    {
                        value: 1,
                        label: 'Xuất kho'
                    },
                    {
                        value: 2,
                        label: 'Chuyển kho'
                    }
                ],
                currentShop : window.MonCMS.current_user.shop_id,

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
                    to_shop_id: this.filter.to_shop_id,
                    type: this.filter.type,
                };

                axios.get(route('apishop.transfer.index', _.merge(properties, customProperties)))
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
            fetchShop() {
                const properties = {
                    page: 0,
                    per_page: 1000,
                    check_company: true,
                };

                axios.get(route('api.shop.index', _.merge(properties, {})))
                .then((response) => {

                    this.shopArr = response.data.data;

                });
            },

            gotosite(transferId){
                window.location.href = route('shop.transfer.edit', transferId);
            }

        },

        mounted() {
            this.fetchData();
            this.fetchShop();

        },
    }
</script>

<style scoped>

</style>

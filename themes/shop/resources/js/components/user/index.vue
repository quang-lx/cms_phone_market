<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">


                <div class="card">
                    <div class="card-body">
                        <div class="row ">
                            <div class="col-sm-6 d-flex align-items-center">
                                <el-breadcrumb separator="/">
                                    <el-breadcrumb-item>
                                        <a href="/shop-admin">{{ $t('mon.breadcrumb.home') }}</a>
                                    </el-breadcrumb-item>
                                    <el-breadcrumb-item>{{ $t('user.label.manager') }}
                                    </el-breadcrumb-item>

                                </el-breadcrumb>

                            </div>
                            <div class="col-sm-6 text-right">
                                <div class="row">

                                    <div class="col-4">

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
                                    <div class="col-6">
                                        <el-input prefix-icon="el-icon-search" @keyup.native="performSearch"
                                                  placeholder="Tên đăng nhập/SĐT/Email"
                                                  v-model="searchQuery">
                                        </el-input>
                                    </div>
                                    <div class="col-2">
                                        <router-link :to="{name: 'shop.user.create'}">
                                            <el-button type="primary" class="btn btn-flat">
                                                {{ $t('user.label.btn_add_user') }}
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
                                        <el-table-column prop="id" :label="$t('user.label.id')" width="75"
                                                         sortable="custom">

                                        </el-table-column>
                                        <el-table-column prop="name" :label="$t('user.label.username')"
                                                         sortable="custom"/>
                                        <el-table-column prop="email" :label="$t('user.label.phone')"
                                                         sortable="custom"/>
                                        <el-table-column prop="email" :label="$t('user.label.email')"
                                                         sortable="custom"/>
                                        <el-table-column prop="" :label="$t('user.label.role')"
                                                         sortable="custom"
                                                         >
                                        <template slot-scope="scope">
                                               <span
                                                v-for="(item, index) in scope.row.role"
                                                :key="index"
                                               >
                                               <span v-if="scope.row.role.length-1==index">{{item.name}}</span>
                                               <span v-else>{{item.name}},</span>
                                               </span>
                                            </template>
                                        </el-table-column>


                                        <el-table-column prop="status" :label="$t('user.label.status')"
                                                         sortable="custom">
                                            <template slot-scope="scope">
                                                <span :style="{'color': scope.row.status_color}">{{scope.row.status_name}}</span>
                                            </template>
                                        </el-table-column>
                                        <el-table-column prop="created_name" :label="$t('user.label.updated_by')"
                                                         sortable="custom"/>
                                        <el-table-column prop="updated_at" :label="$t('user.label.updated_at')"
                                                         sortable="custom"/>


                                        <el-table-column prop="actions" width="130">
                                            <template slot-scope="scope">
                                                <edit-button
                                                        :to="{name: 'shop.user.edit', params: {userId: scope.row.id}}"></edit-button>
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

                columnsSearch: [],
                listFilterColumn: [],
                listStatus: [
                    {
                        value: 0,
                        label: 'Chưa hoạt động'
                    },
                    {
                        value: 1,
                        label: 'Hoạt động'
                    },
                    {
                        value: 2,
                        label: 'Đã khóa'
                    }
                ],
                filter: {
                    status: ''
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
                    status: this.filter.status
                };

                axios.get(route('api.user.index', _.merge(properties, customProperties)))
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

        },

        mounted() {
            this.fetchData();

        },
    }
</script>

<style scoped>

</style>

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
                                        <a href="/admin">{{ $t('mon.breadcrumb.home') }}</a>
                                    </el-breadcrumb-item>
                                    <el-breadcrumb-item  >{{ $t('company.label.company') }}
                                    </el-breadcrumb-item>

                                </el-breadcrumb>

                            </div>
                            <div class="col-sm-6 text-right">
                                <div class="row">
                                    <div class="col-10">
                                        <el-input prefix-icon="el-icon-search" @keyup.native="performSearch"
                                                  v-model="searchQuery">
                                        </el-input>
                                    </div>
                                    <div class="col-2">
                                        <router-link :to="{name: 'admin.company.create'}">
                                            <el-button type="primary"  size="small"   class="btn btn-flat">
                                                {{ $t('company.label.create_title') }}
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
                                        <el-table-column prop="id" :label="$t('company.label.id')" width="75" sortable="custom">

                                        </el-table-column>
                                        <el-table-column prop="adminUser.username" :label="$t('company.label.username')" sortable="custom"> </el-table-column>
                                        <el-table-column prop="name" :label="$t('company.label.name')" sortable="custom"/>
                                        <el-table-column prop="address" :label="$t('company.label.address')" sortable="address"/>
                                        <el-table-column prop="phone" :label="$t('company.label.phone')" sortable="address"/>


                                        <el-table-column prop="status" :label="$t('company.label.status')" sortable="custom">
                                            <template slot-scope="scope">
                                                <span :style="{'color': scope.row.status_color}">{{scope.row.status_name}}</span>
                                            </template>
                                        </el-table-column>
                                        <el-table-column prop="updated_by" :label="$t('mon.updated_by')" sortable="custom">

                                        </el-table-column>
                                        <el-table-column prop="updated_at" :label="$t('mon.updated_at')" sortable="custom">

                                        </el-table-column>

                                        <el-table-column prop="actions" width="130">
                                            <template slot-scope="scope">
                                                <edit-button
                                                        :to="{name: 'admin.company.edit', params: {companyId: scope.row.id}}"></edit-button>
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

                axios.get(route('api.company.index', _.merge(properties, customProperties)))
                    .then((response) => {
                        this.tableIsLoading = false;
                        this.tableIsLoading = false;
                        this.data = response.data.data;
                        this.meta = response.data.meta;
                        this.links = response.data.links;
                        this.order_meta.order_by = properties.order_by;
                        this.order_meta.order = properties.order;
                    });
            }

        },
        mounted() {
            this.fetchData();

        },
    }
</script>

<style scoped>

</style>

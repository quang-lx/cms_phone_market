<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-12">
                        <el-breadcrumb separator="/">
                            <el-breadcrumb-item>
                                <a href="/admin">{{ $t('mon.breadcrumb.home') }}</a>
                            </el-breadcrumb-item>
                            <el-breadcrumb-item  >{{ $t('user.label.admins') }}
                            </el-breadcrumb-item>

                        </el-breadcrumb>
                    </div>

                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row justify-content-end mb-2">
                    <div class="col-md-4   ">
                        <el-input prefix-icon="el-icon-search" @keyup.native="performSearch" placeholder="Nhập ID, Tên, Tài khoản, Email"
                                  v-model="searchQuery">
                        </el-input>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header ui-sortable-handle" style="cursor: move;">
                                <h3 class="card-title">
                                    {{ $t('user.label.admins') }}
                                </h3>
                                <div class="card-tools">
                                    <ul class="nav nav-pills ml-auto">
                                        <li class="nav-item">
                                            <router-link :to="{name: 'admin.admins.create'}">
                                                <el-button type="primary"  size="small"   class="btn btn-flat">
                                                    {{ $t('user.label.create_admin') }}
                                                </el-button>
                                            </router-link>
                                        </li>

                                    </ul>
                                </div>
                            </div><!-- /.card-header -->
                            <div class="card-body">
                                <div class="sc-table">

                                    <el-table
                                            :data="data"
                                            stripe
                                            style="width: 100%"
                                            ref="dataTable"
                                            v-loading.body="tableIsLoading"
                                            @sort-change="handleSortChange">
                                        <el-table-column prop="id" :label="$t('user.label.id')" width="75" sortable="custom">

                                        </el-table-column>
                                        <el-table-column prop="username" :label="$t('user.label.username')" sortable="custom"> </el-table-column>

                                        <el-table-column prop="name" :label="$t('user.label.name')" sortable="custom">

                                        </el-table-column>
                                        <el-table-column prop="email" :label="$t('user.label.email')" sortable="custom">

                                        </el-table-column>


                                        <el-table-column prop="updated_at" :label="$t('user.label.updated_at')" sortable="custom">

                                        </el-table-column>

                                        <el-table-column prop="actions"  width="130">
                                            <template slot-scope="scope">
                                                <edit-button
                                                        :to="{name: 'admin.admins.edit', params: {userId: scope.row.id}}"></edit-button>
                                                <delete-button :scope="scope" :rows="data"></delete-button>
                                            </template>
                                        </el-table-column>
                                    </el-table>
                                    <div class="pagination-wrap" style="text-align: center; padding-top: 20px;"  v-if="$isMobile()">
                                        <el-pagination
                                          @size-change="handleSizeChange"
                                          @current-change="handleCurrentChange"
                                          :current-page.sync="meta.current_page"
                                          :page-sizes="[6, 12, 24, 48]"
                                          :page-size="parseInt(meta.per_page)"
                                          layout="total,   prev, pager, next"
                                          :total="meta.total">
                                        </el-pagination>
                                    </div>
                                    <div class="pagination-wrap" style="text-align: center; padding-top: 20px;" v-else>
                                        <el-pagination
                                          @size-change="handleSizeChange"
                                          @current-change="handleCurrentChange"
                                          :current-page.sync="meta.current_page"
                                          :page-sizes="[25, 50, 75, 100]"
                                          :page-size="parseInt(meta.per_page)"
                                          layout="total, sizes, prev, pager, next, jumper"
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
                    type: 1
                };

                axios.get(route('api.users.index', _.merge(properties, customProperties)))
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
        created() {
            if (this.$route.query.msg) {
                this.$message({
                    type: 'success',
                    message: this.$route.query.msg
                });
            }

        }
    }
</script>

<style scoped>

</style>

<template>
    <div>

        <div class="content-header">

            <el-breadcrumb separator="/">
                <el-breadcrumb-item>
                    <a href="/admin">{{ $t('mon.breadcrumb.home') }}</a>
                </el-breadcrumb-item>
                <el-breadcrumb-item  >{{ $t('permission.label.permissions') }}
                </el-breadcrumb-item>

            </el-breadcrumb>

        </div>


        <div class="row">
            <div class="col-md-12 row-action">
                <h3 class="row-left">{{ $t('permission.label.permissions') }}</h3>

                <div class="row-right">
                    <router-link :to="{name: 'admin.permissions.create'}">
                        <el-button type="primary"  size="small"    class="btn btn-flat">
                            {{ $t('permission.label.create_permission') }}
                        </el-button>
                    </router-link>
                </div>
            </div>
        </div>
        <div class="box box-widget">

            <div class="box-body">

                <div class="sc-table">

                    <el-table
                            :data="data"
                            stripe
                            style="width: 100%"
                            ref="dataTable"
                            v-loading.body="tableIsLoading"
                            @sort-change="handleSortChange">
                        <el-table-column prop="id" :label="$t('permission.label.id')" width="75" sortable="custom">

                        </el-table-column>
                        <el-table-column prop="name" :label="$t('permission.label.name')" sortable="custom">

                        </el-table-column>
                        <el-table-column prop="title" :label="$t('permission.label.title')" sortable="custom">

                        </el-table-column>

                        <el-table-column prop="guard_name" :label="$t('permission.label.guard_name')" sortable="custom">

                        </el-table-column>
                        <el-table-column prop="group" :label="$t('permission.label.group')" sortable="custom">

                        </el-table-column>

                        <el-table-column prop="actions" width="130">
                            <template slot-scope="scope">
                                <edit-button
                                        :to="{name: 'admin.permissions.edit', params: {permissionId: scope.row.id}}"></edit-button>
                            </template>
                        </el-table-column>
                    </el-table>
                    <div class="pagination-wrap" style="text-align: center; padding-top: 20px;">
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
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {

        data() {
            return {
                data: [],
                meta: {
                    current_page: 1,
                    per_page: 25,
                },
                order_meta: {
                    order_by: '',
                    order: '',
                },
                links: {},
                searchQuery: '',
                tableIsLoading: false,

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

                axios.get(route('api.permissions.index', _.merge(properties, customProperties)))
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
            fetchData() {
                this.tableIsLoading = true;
                this.queryServer({per_page: 25});
            },

            handleSizeChange(event) {
                this.tableIsLoading = true;
                this.queryServer({ per_page: event });
            },
            handleCurrentChange(event) {
                this.tableIsLoading = true;
                this.queryServer({ page: event });
            },
            handleSortChange(event) {
                this.tableIsLoading = true;
                this.queryServer({ order_by: event.prop, order: event.order });
            },


        },
        mounted() {
            this.fetchData();

        },
    }
</script>

<style scoped>

</style>

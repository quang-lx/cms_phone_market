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
                            <el-breadcrumb-item  >{{ $t('shopshiptype.label.shopshiptype') }}
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
                                    {{ $t('shopshiptype.label.shopshiptype') }}
                                </h3>
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
                                        <el-table-column prop="id" :label="$t('shopshiptype.label.id')" width="75" sortable="custom">

                                        </el-table-column>
                                        <el-table-column prop="name"  :label="$t('shopshiptype.label.name')" >
                                        </el-table-column>

                                        <el-table-column prop="actions" width="130" :label="$t('shopshiptype.label.status')" v-if="this.shop_id>0">
                                            <template slot-scope="scope">
                                                <el-switch v-model="scope.row.status_shop_ship_type" :inactive-value="1" :active-value="2"  @change="changStatus(scope.row.id)"></el-switch>
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
                shop_id : window.MonCMS.current_user.shop_id


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

                axios.get(route('shopapi.shopshiptype.index', _.merge(properties, customProperties)))
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
            changStatus(id){
            axios.post(route('shopapi.shopshiptype.create_or_update'),{id: id})
            .then((response) => {
                    
            });
        },

        },
        
        mounted() {
            this.fetchData();

        },
    }
</script>

<style scoped>

</style>

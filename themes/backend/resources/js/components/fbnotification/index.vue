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
                                    <el-breadcrumb-item  >{{ $t('fbnotification.label.fbnotification') }}
                                    </el-breadcrumb-item>

                                </el-breadcrumb>

                            </div>
                            <div class="col-sm-6 text-right">
                                <div class="row">
                                    <div class="col-8">
                                         <el-select v-model="topic" @change="fetchData" placeholder="Select">
                                            <el-option
                                                v-for="item in list_topic"
                                                :key="item.value"
                                                :label="item.label"
                                                :value="item.value"
                                            >
                                            </el-option>
                                            </el-select>
                                    </div>
                                    <div class="col-4">
                                        <router-link :to="{name: 'admin.fbnotification.create'}">
                                            <el-button type="primary"    class="btn btn-flat">
                                                {{ $t('fbnotification.label.create_title') }}
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
                                        <el-table-column prop="id" :label="$t('fbnotification.label.id')" width="75" sortable="custom">

                                        </el-table-column>
                                        <el-table-column  prop="title" :label="$t('fbnotification.label.title')" sortable="custom"> </el-table-column>
                                        <el-table-column  prop="sent" :label="$t('fbnotification.label.sent')" sortable="custom">
                                         <template slot-scope="scope">
                                                <span :style="{'color': scope.row.sent_color}">{{scope.row.sent_name}}</span>
                                            </template>    
                                        </el-table-column>
                                        <el-table-column  prop="updated_at" :label="$t('fbnotification.label.updated_at')" sortable="custom">
                                        </el-table-column>
                                        <el-table-column prop="actions" width="130">
                                            <template slot-scope="scope">
                                                <edit-button
                                                        :to="{name: 'admin.fbnotification.edit', params: {fbnotificationId: scope.row.id}}"></edit-button>
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
                list_topic: [
                    {
                    label: 'Tất cả',
                    value: null
                    },
                    {
                    label: 'Android',
                    value: 'android'
                    },
                    {
                    label: 'IOS',
                    value: 'ios'
                    },
                ],
                topic:null


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
                    topic: this.topic,
                };

                axios.get(route('api.fbnotification.index', _.merge(properties, customProperties)))
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

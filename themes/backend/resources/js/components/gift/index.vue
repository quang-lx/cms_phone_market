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
                                        <a href="/admin">{{ $t('mon.breadcrumb.home') }}</a>
                                    </el-breadcrumb-item>
                                    <el-breadcrumb-item  >{{ $t('gift.label.gift') }}
                                    </el-breadcrumb-item>

                                </el-breadcrumb>

                            </div>
                            <div class="col-sm-9 text-right">
                                <div class="row">
                                            <div class="col-3">
                                                    <el-date-picker @change="queryServer"
                                                        v-model="filter.searchDate"
                                                        type="daterange"
                                                        start-placeholder="Start date"
                                                        end-placeholder="End date"
                                                        format="dd/MM/yyyy"
                                                        value-format="yyyy-MM-dd"
                                                    >
                                                    </el-date-picker>
                                                </div>
                                                <div class="col-3">
                                                    <el-select v-model="filter.status" clearable @change="queryServer" placeholder="Lọc theo trạng thái">
                                                    <el-option
                                                        v-for="item in list_status"
                                                        :key="item.value"
                                                        :label="item.label"
                                                        :value="item.value">
                                                    </el-option>
                                                    </el-select>
                                                </div>
                                                 <div class="col-3">
                                                    <el-input prefix-icon="el-icon-search" @keyup.native="performSearch" placeholder="Tên quà tặng/điểm"
                                                            v-model="searchQuery">
                                                    </el-input>
                                                </div>
                                    <div class="col-3">
                                        <router-link :to="{name: 'admin.gift.create'}">
                                            <el-button type="primary"    class="btn btn-flat">
                                                {{ $t('gift.label.create_title') }}
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
                                        <el-table-column prop="id" :label="$t('gift.label.id')" width="75" sortable="custom">

                                        </el-table-column>
                                        <el-table-column  prop="name" :label="$t('gift.label.name')" sortable="custom"> </el-table-column>
                                       <el-table-column prop=""  :label="$t('gift.label.image')" >
                                            <template slot-scope="scope">
                                                <img :src="scope.row.thumbnail.path_string" v-if="scope.row.thumbnail"
                                                     width="100" height="100" style="object-fit:contain"/>
                                            </template>
                                        </el-table-column>
                                        <el-table-column  prop="created_at" :label="$t('gift.label.created_at')" sortable="custom">
                                        </el-table-column>
                                        <el-table-column  prop="" :label="$t('gift.label.point')" sortable="custom">
                                             <template slot-scope="scope">
                                                <span v-number="scope.row.point"></span>
                                            </template>
                                        </el-table-column>
                                        <el-table-column  prop="" :label="$t('gift.label.amount')" sortable="custom">
                                             <template slot-scope="scope">
                                                <span v-number="scope.row.amount"></span>
                                            </template>
                                        </el-table-column>
                                        <el-table-column  prop="created_by" :label="$t('gift.label.created_by')" sortable="custom">
                                        </el-table-column>
                                        <el-table-column  prop="status" :label="$t('gift.label.status')" sortable="custom">

                                         <template slot-scope="scope">
                                                <span :style="{'color': scope.row.status_color}">{{scope.row.status_name}}</span>
                                            </template>    
                                        </el-table-column>
                                        <el-table-column prop="actions" width="130">
                                            <template slot-scope="scope">
                                                <edit-button
                                                        :to="{name: 'admin.gift.edit', params: {giftId: scope.row.id}}"></edit-button>
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
                list_status: [
                    {
                    label: 'Khóa',
                    value: 0
                    },
                    {
                    label: 'Hoạt động',
                    value: 1
                    },
                ],
              filter: {
                status: '',
                searchDate: '',
            },


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
                    status : this.filter.status,
                    searchDate : this.filter.searchDate,
                };

                axios.get(route('api.gift.index', _.merge(properties, customProperties)))
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
.el-input__inner{
    width: 100% !important;
}
</style>

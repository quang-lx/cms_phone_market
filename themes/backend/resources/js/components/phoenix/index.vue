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
                            <el-breadcrumb-item  >{{ $t('phoenix.label.phoenix') }}
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
                    <div class="col-md-8">
                    </div>
                    <div class="col-md-4   ">
                        <el-input prefix-icon="el-icon-search" @keyup.native="performSearch" placeholder="Nhập ID, Tên,code,Mã số"
                                  v-model="searchQuery">
                        </el-input>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header ui-sortable-handle" style="cursor: move;">
                                <h3 class="card-title">
                                    {{ $t('phoenix.label.phoenix') }}
                                </h3>
                                <div class="card-tools">
                                    <ul class="nav nav-pills ml-auto">
                                        <li class="nav-item">
                                            <router-link :to="{name: 'admin.phoenix.create'}">
                                                <el-button type="primary"  size="small"   class="btn btn-flat">
                                                    {{ $t('phoenix.label.create_phoenix') }}
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
                                        <el-table-column prop="id" :label="$t('phoenix.label.id')" width="75" sortable="custom">

                                        </el-table-column>
                                        <el-table-column prop="name" :label="$t('phoenix.label.name')" sortable="custom">

                                        </el-table-column>

                                         <el-table-column prop="province" :label="$t('phoenix.label.province')" sortable="custom">

                                        </el-table-column>

                                        <el-table-column prop="district" :label="$t('phoenix.label.district')" sortable="custom">

                                        </el-table-column>

                                        <el-table-column prop="code" :label="$t('phoenix.label.code')" sortable="custom">
                                           
                                        </el-table-column>
                                        <el-table-column prop="type" :label="$t('phoenix.label.type')" sortable="custom">

                                        </el-table-column>
                                    

                                        <el-table-column prop="actions" width="130">
                                            <template slot-scope="scope">
                                                <edit-button
                                                  :to="{name: 'admin.phoenix.edit', params: {phoenixId: scope.row.id}}"></edit-button>
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
                categoryArr: window.MonCMS.phoenixListCategory,
                statusArr: window.MonCMS.phoenixListStatus,

                filter: {
                    category:'',
                    status: '',
                    filter_feature: '',
                    locale: window.MonCMS.currentLocale || 'en'
                },
                listLocales: window.MonCMS.locales


            };
        },
        methods: {
            queryServer(customProperties) {
                    console.log(this.filter.locale);
                const properties = {
                    page: this.meta.current_page,
                    per_page: this.meta.per_page,
                    order_by: this.order_meta.order_by,
                    order: this.order_meta.order,
                    search: this.searchQuery,

                    filter_feature: this.filter.filter_feature,

                };

                axios.get(route('api.phoenix.index', _.merge(properties, customProperties)))
                    .then((response) => {
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

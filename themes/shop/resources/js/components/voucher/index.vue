<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3 d-flex align-items-center">
                                <el-breadcrumb separator="/">
                                    <el-breadcrumb-item>
                                        <a href="/shop-admin">{{ $t('mon.breadcrumb.home') }}</a>
                                    </el-breadcrumb-item>
                                    <el-breadcrumb-item  >{{ $t('voucher.label.list') }}
                                    </el-breadcrumb-item>

                                </el-breadcrumb>

                            </div>
                            <div class="col-sm-9">
                                <div class="row  pull-right col-sm-6">

                                    <div class="col-sm-6">
                                        <el-input prefix-icon="el-icon-search" @keyup.native="performSearch"
                                                  placeholder="Tìm theo mã giảm giá"
                                                  v-model="searchQuery" clearable>
                                        </el-input>
                                    </div>
                                    <div class="col-sm-6 d-block">
                                        <router-link :to="{name: 'shop.voucher.create'}">
                                            <el-button type="primary" class="btn btn-flat d-block" style="width:100%">
                                                {{ $t('voucher.label.btn_add_voucher') }}
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
                                        <el-table-column prop="id" :label="$t('voucher.label.id')" width="60" sortable="custom">
                                        </el-table-column>

                                        <el-table-column prop="title" :label="$t('voucher.label.title')" sortable="address" width="150">
                                            <template slot-scope="scope">
                                                <span class="dont-break-out">{{scope.row.title}}</span>
                                            </template>
                                        </el-table-column>

                                        <el-table-column prop="code" :label="$t('voucher.label.code')" sortable="custom" width="120">
                                        </el-table-column>

                                        <el-table-column prop="type" :label="$t('voucher.label.type_name')" sortable="custom" width="120" >
                                            <template slot-scope="scope">
                                                <span class="dont-break-out">{{scope.row.type_name}}</span>
                                            </template>
                                        </el-table-column>

                                        <el-table-column prop="discount_amount" :label="$t('voucher.label.discount_amount')"
                                            sortable="custom" width="140" >
                                             <template slot-scope="scope">
                                                <span><span v-number="scope.row.discount_amount"></span><span v-if="scope.row.discount_type == 1">đ</span><span v-else>%</span>
                                                </span>
                                            </template>
                                        </el-table-column>

                                        <el-table-column prop="actived_at" :label="$t('voucher.label.status')" sortable="custom" width="130" >
                                            <template slot-scope="scope">
                                                <span class="dont-break-out">{{scope.row.status_name}}</span>
                                            </template>
                                        </el-table-column>

                                        <el-table-column prop="" :label="$t('voucher.label.total')" sortable="custom" width="130" >
                                             <template slot-scope="scope">
                                                <span v-number="scope.row.total"></span>
                                            </template>
                                        </el-table-column>

                                        <el-table-column prop="" :label="$t('voucher.label.total_used')" sortable="custom" width="120">
                                             <template slot-scope="scope">
                                                <span v-number="scope.row.total_used"></span>
                                            </template>
                                        </el-table-column>

                                        <el-table-column prop="created_by" :label="$t('voucher.label.updated_by')" sortable="custom" width="140">
                                            <template slot-scope="scope">
                                                <span class="dont-break-out">{{scope.row.created_name}}</span>
                                            </template>
                                        </el-table-column>

                                        <el-table-column prop="updated_at" :label="$t('voucher.label.updated_at')" sortable="custom" width="140">
                                            <template slot-scope="scope">
                                                <span class="dont-break-out">{{scope.row.updated_at}}</span>
                                            </template>
                                        </el-table-column>

                                        <el-table-column prop="actions" width="130">
                                            <template slot-scope="scope">
                                                <el-switch v-model="scope.row.status" 
                                                    :disabled="(currentShop != null && scope.row.shop_id != currentShop) || scope.row.company_id != currentCompany"
                                                    @change="changeStatus(scope.row.id)">
                                                </el-switch>
                                                <edit-button
                                                  :to="{name: 'shop.voucher.edit', params: {voucherId: scope.row.id}}"></edit-button>
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

                filter: {
                    locale: window.MonCMS.currentLocale || 'en'
                },
                listLocales: window.MonCMS.locales,
                currentShop : window.MonCMS.current_user.shop_id,
                currentCompany : window.MonCMS.current_user.company_id,


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

                axios.get(route('api.voucher.index', _.merge(properties, customProperties)))
                    .then((response) => {
                        this.tableIsLoading = false;
                        this.data = response.data.data;
                        this.meta = response.data.meta;
                        this.links = response.data.links;
                        this.order_meta.order_by = properties.order_by;
                        this.order_meta.order = properties.order;
                    });
            },
            formatNumber(number){
                return number.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')
            },

            onSearchChange() {
                this.meta.current_page = 0;
                this.queryServer({})
            },

            changeStatus(voucherId) {
                this.$confirm('Bạn có chắc muốn thay đổi trạng thái khuyến mại này không?', this.$t('mon.modal.title'), {
                    confirmButtonText: 'Đồng ý?',
                    cancelButtonText: 'Hủy bỏ',
                    type: 'warning',
                })
                    .then(() => {
                        const properties = {
                            page: this.meta.current_page,
                            per_page: this.meta.per_page,
                            order_by: this.order_meta.order_by,
                            order: this.order_meta.order,
                            voucher_id: voucherId,
                        };

                        axios.post(route('api.voucher.changeStatus', properties))
                            .then((response) => {
                                console.log(response);
                                this.$message({
                                    type: 'success',
                                    message: response.data.message,
                                });
                                // window.location.reload();
                            });
                        })
                    .catch(() => {
                        this.$message({
                            type: 'info',
                            message: 'Hủy bỏ!',
                        });
                        window.location.reload();
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

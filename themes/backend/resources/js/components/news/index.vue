<template>

<div>
        <div class="content-header">
            <div class="container-fluid">


                <div class="card">
                    <div class="card-body">
                        <div class="row ">
                            <div class="col-md-6">

                        <el-radio-group v-model="filter.status" @change="fetchData">
                            <el-radio v-for="item in listStatus"
                                      :key="'status-'+ item.value"
                                      border
                                      :label="item.value">{{item.label}}
                            </el-radio>
                        </el-radio-group>
                    </div>
                        <div class="col-sm-6 text-right">
                                <div class="row">
                                    <div class="col-sm-9">
                                        <el-input prefix-icon="el-icon-search" @keyup.native="performSearch"
                                                  v-model="searchQuery">
                                        </el-input>
                                    </div>
                                    <div class="col-sm-3">
                                         <router-link :to="{name: 'admin.news.create'}">
                                                <el-button type="primary" size="small" class="btn btn-flat">
                                                    {{ $t('news.label.create_news') }}
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
                                        <el-table-column prop="id" :label="$t('news.label.id')" width="75"
                                                         sortable="custom">

                                        </el-table-column>
                                        <el-table-column prop="" label="Ảnh đại diện">
                                            <template slot-scope="scope">
                                                <img :src="scope.row.thumbnail.path_string" v-if="scope.row.thumbnail"
                                                     width="100"/>
                                            </template>
                                        </el-table-column>
                                        <el-table-column prop="title" :label="$t('news.label.title')" sortable="custom">

                                        </el-table-column>
                                        <el-table-column prop="category_id" :label="$t('news.label.category_id')"
                                                         sortable="custom">
                                            <template slot-scope="scope">
                                                <span v-if="scope.row.category">{{scope.row.category.title}}</span>
                                            </template>
                                        </el-table-column>
                                        <el-table-column prop="author" :label="$t('news.label.author')"
                                                         sortable="custom">

                                        </el-table-column>
                                        <el-table-column prop="status" :label="$t('category.label.status')"
                                                         sortable="status">
                                            <template slot-scope="scope">
                                                <span class="badge bg-success" v-if="scope.row.status == 'publish'">Hiển thị</span>
                                                <span class="badge bg-danger" v-else>Ẩn</span>
                                            </template>

                                        </el-table-column>
                                        <el-table-column prop="created_by" label="Người tạo" sortable="custom">

                                        </el-table-column>
                                        <el-table-column prop="updated_at" label="Ngày cập nhật" sortable="custom">

                                        </el-table-column>

                                        <el-table-column prop="actions" width="130">
                                            <template slot-scope="scope">
                                                <edit-button
                                                    :to="{name: 'admin.news.edit', params: {newsId: scope.row.id}}"></edit-button>
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
        categoryArr: window.MonCMS.newsListCategory,
        statusArr: window.MonCMS.newsListStatus,

        listStatus: [
          {
            value: '',
            label: 'Tất cả'
          },
          {
            value: 'publish',
            label: 'Hiển thị'
          },
          {
            value: 'hide',
            label: 'Ẩn'
          }
        ],
        filter: {
          category: '',
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

          status: this.filter.status,

        };

        axios.get(route('api.news.index', _.merge(properties, customProperties)))
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

<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-12">
            <el-breadcrumb separator="/">
              <el-breadcrumb-item>
                <a href="/admin">{{ $t("mon.breadcrumb.home") }}</a>
              </el-breadcrumb-item>
              <el-breadcrumb-item
                >{{ $t("account.label.account") }}
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
          <div class="col-md-6"></div>
          <div class="col-md-2">
            <el-select
              v-model="rank_value"
              @change="onSearchChange"
              placeholder="Select"
            >
              <el-option
                v-for="item in rank"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              >
              </el-option>
            </el-select>
          </div>
          <div class="col-md-2">
            <el-select
              v-model="status_value"
              @change="onSearchChange"
              placeholder="Select"
            >
              <el-option
                v-for="item in status"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              >
              </el-option>
            </el-select>
          </div>
          <div class="col-md-2">
            <el-input
              prefix-icon="el-icon-search"
              @keyup.native="performSearch"
              placeholder="Nhập ID, Tên,code,Mã số"
              v-model="searchQuery"
            >
            </el-input>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header ui-sortable-handle" style="cursor: move">
                <h3 class="card-title">
                  {{ $t("account.label.account") }}
                </h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="sc-table">
                  <el-table
                    :data="data"
                    stripe
                    style="width: 100%"
                    ref="dataTable"
                    v-loading.body="tableIsLoading"
                    @sort-change="handleSortChange"
                  >
                    <el-table-column
                      prop="id"
                      :label="$t('account.label.id')"
                      width="75"
                      sortable="custom"
                    >
                    </el-table-column>
                    <el-table-column
                      prop="avatar"
                      :label="$t('account.label.avatar')"
                      width="100"
                      sortable="custom"
                    >
                    </el-table-column>
                    <el-table-column
                      prop="name"
                      :label="$t('account.label.name')"
                      sortable="custom"
                    >
                    </el-table-column>

                    <el-table-column
                      :label="$t('account.label.info')"
                      sortable="custom"
                    >
                      <template slot-scope="scope">
                        <div>{{ scope.row.phone }}</div>
                        <div>{{ scope.row.email }}</div>
                      </template>
                    </el-table-column>

                    <el-table-column
                      prop="rank"
                      :label="$t('account.label.rank')"
                      sortable="custom"
                    >
                      <template slot-scope="scope">
                        <div>{{ scope.row.rank_name }}</div>
                      </template>
                    </el-table-column>

                    <el-table-column
                      prop="created_at"
                      :label="$t('account.label.created_at')"
                      sortable="custom"
                    >
                    </el-table-column>

                    <el-table-column
                      :label="$t('account.label.status')"
                      sortable="custom"
                    >
                      <template slot-scope="scope">
                        <span :style="{ color: scope.row.status_color }">{{
                          scope.row.status_name
                        }}</span>
                      </template>
                    </el-table-column>
                    <el-table-column
                      prop="updated_by"
                      :label="$t('account.label.updated_by')"
                      sortable="custom"
                    >
                    </el-table-column>
                    <el-table-column
                      prop="updated_at"
                      :label="$t('account.label.updated_at')"
                      sortable="custom"
                    >
                    </el-table-column>

                    <el-table-column prop="actions" width="130">
                      <template slot-scope="scope">
                        <el-button
                          :to="{
                            name: 'admin.account.detail',
                            params: { accountId: scope.row.id },
                          }"
                          type="primary"
                          icon="el-icon-view"
                        ></el-button>
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
                      :total="meta.total"
                    >
                    </el-pagination>
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      data: [],

      currentLocale: window.MonCMS.currentLocale || "en",
      categoryArr: window.MonCMS.accountListCategory,
      statusArr: window.MonCMS.accountListStatus,

      filter: {
        category: "",
        status: "",
        filter_feature: "",
        locale: window.MonCMS.currentLocale || "en",
      },
      listLocales: window.MonCMS.locales,
      rank: [
        {
          value: 1,
          label: "Cơ bản",
        },
        {
          value: 2,
          label: "Bạc",
        },
        {
          value: 3,
          label: "Vàng",
        },
        {
          value: 4,
          label: "Bạch Kim",
        },
        {
          value: 5,
          label: "Kim Cương",
        },
      ],
      rank_value: "",

      status: [
        {
          value: 1,
          label: "Hoạt đông",
        },
        {
          value: 2,
          label: "Đã khóa",
        },
      ],
      status_value: "",
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
        rank: this.rank_value,
        status: this.status_value,

        filter_feature: this.filter.filter_feature,
      };

      axios
        .get(route("api.account.index", _.merge(properties, customProperties)))
        .then((response) => {
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
      this.queryServer({});
    },
  },

  mounted() {
    this.fetchData();
  },
};
</script>

<style scoped>
</style>

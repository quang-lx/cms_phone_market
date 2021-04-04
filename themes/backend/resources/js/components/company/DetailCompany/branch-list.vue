<template>
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
                  @sort-change="handleSortChange"
                >
                  <el-table-column
                    prop="id"
                    :label="$t('company.label.id')"
                    width="75"
                    sortable="custom"
                  >
                  </el-table-column>
                  <el-table-column label="Ảnh" width="75" sortable="custom">
                    <template slot-scope="scope">
                      <img
                        :src="scope.row.thumbnail.path_string"
                        v-if="scope.row.thumbnail"
                        width="100"
                      />
                    </template>
                  </el-table-column>
                  <el-table-column
                    prop="name"
                    :label="$t('company.label.name')"
                    sortable="custom"
                  />
                  <el-table-column
                    prop="address"
                    :label="$t('company.label.address')"
                    sortable="address"
                  >
                    <template slot-scope="scope">
                      <span class="dont-break-out">{{
                        scope.row.address
                      }}</span>
                    </template>
                  </el-table-column>
                  <el-table-column label="Liên hệ">
                    <template slot-scope="scope">
                      <div>{{ scope.row.phone }}</div>
                      <div>{{ scope.row.email }}</div>
                    </template>
                  </el-table-column>
                  <el-table-column
                    prop="status"
                    :label="$t('company.label.status')"
                    sortable="custom"
                  >
                    <template slot-scope="scope">
                      <span :style="{ color: scope.row.status_color }">{{
                        scope.row.status_name
                      }}</span>
                    </template>
                  </el-table-column>

                  <el-table-column prop="actions" width="130">
                    <template slot-scope="scope">
                      <edit-button
                        :to="{
                          name: 'admin.company.edit',
                          params: { companyId: scope.row.id },
                        }"
                      ></edit-button>
                      <delete-button
                        :scope="scope"
                        :rows="data"
                      ></delete-button>
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
</template>

<script>
import axios from "axios";

export default {
  props: {
    company_id: { default: null },
  },

  data() {
    return {
      data: [],

      columnsSearch: [],
      listFilterColumn: [],
      filter: {
        status: "",
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
        status: this.filter.status,
        company_id: this.company_id,
      };

      axios
        .get(route("api.shop.index", _.merge(properties, customProperties)))
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

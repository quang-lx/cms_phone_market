<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-6 d-flex align-items-center">
                <el-breadcrumb separator="/">
                  <el-breadcrumb-item>
                    <a href="/admin">{{ $t("mon.breadcrumb.home") }}</a>
                  </el-breadcrumb-item>
                  <el-breadcrumb-item
                    >{{ $t("company.label.company") }}
                  </el-breadcrumb-item>
                </el-breadcrumb>
              </div>
              <div class="col-sm-6 text-right">
                <div class="row">
                  <div class="col-4">
                    <el-select
                      v-model="filter.level"
                      placeholder="Lọc theo cấp độ ưu tiên"
                      @change="onSearchChange()"
                      clearable
                    >
                     <el-option
                        label="Tất cả"
                        value=""
                      >
                      </el-option>
                      <el-option
                        v-for="item in listPrioritize"
                        :key="item.value"
                        :label="item.label"
                        :value="item.value"
                      >
                      </el-option>
                    </el-select>
                  </div>
                  <div class="col-6">
                    <el-input
                      prefix-icon="el-icon-search"
                      @keyup.native="performSearch"
                      placeholder="Tên đăng nhập/Tên ch/SĐT/Email"
                      v-model="searchQuery"
                    >
                    </el-input>
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
                <el-dialog
                  title="Cài đặt cửa hàng ưu tiên"
                  :visible.sync="dialogFormVisible"
                >
                  <el-form :model="form">
                    <el-form-item
                      label="Tên cửa hàng"
                      :label-width="formLabelWidth"
                    >
                      <el-input
                      :disabled="true"
                        v-model="form.name"
                        autocomplete="off"
                      ></el-input>
                    </el-form-item>
                    <el-form-item
                      label="Mức ưu tiên"
                      :label-width="formLabelWidth"
                    >
                      <el-select
                        v-model="form.level"
                        placeholder="Please select a zone"
                      >
                        <el-option
                          v-for="item in listPrioritize"
                          :key="item.value"
                          :label="item.label"
                          :value="item.value"
                        >
                        </el-option>
                      </el-select>
                    </el-form-item>
                  </el-form>
                  <span slot="footer" class="dialog-footer">
                    <el-button type="primary" @click="onSubmit()"
                      >Sét ưu tiên</el-button
                    >
                  </span>
                </el-dialog>
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
                    <el-table-column
                      prop="adminUser.username"
                      :label="$t('company.label.username')"
                      sortable="custom"
                    >
                    </el-table-column>
                    <el-table-column
                      prop="name"
                      :label="$t('company.label.name')"
                      sortable="custom"
                    />
                    <el-table-column
                      prop="branchnumber"
                      :label="$t('company.label.branchnumber')"
                      sortable="custom"
                    />
                      <el-table-column
                      prop="level"
                      :label="$t('company.label.level')"
                      sortable="custom"
                    />
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
                    <el-table-column
                      prop="updated_by"
                      :label="$t('mon.updated_by')"
                      sortable="custom"
                    >
                    </el-table-column>
                    <el-table-column
                      prop="updated_at"
                      :label="$t('mon.updated_at')"
                      sortable="custom"
                    >
                    </el-table-column>

                    <el-table-column prop="actions" width="130">
                      <template slot-scope="scope">
                        <edit-button
                          :to="{
                            name: 'admin.listcompany.edit',
                            params: { listcompanyId: scope.row.id },
                          }"
                        ></edit-button>
                        <delete-button
                          :scope="scope"
                          :rows="data"
                        ></delete-button>
                      </template>
                    </el-table-column>

                    <el-table-column prop="actions" width="130">
                      <template slot-scope="scope">
                        <el-button  :scope="scope"
                          @click="showDataModal(scope.row)" type="primary" icon="el-icon-arrow-up"></el-button>
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
      dialogFormVisible: false,
      columnsSearch: [],
      listFilterColumn: [],
      filter: {
        level: "",
      },

      listPrioritize: [
        {
          value: 1,
          label: "Mức 1",
        },
        {
          value: 2,
          label: "Mức 2",
        },
        {
          value: 3,
          label: "Mức 3",
        },
        {
          value: 4,
          label: "Mức 4",
        },
        {
          value: 5,
          label: "Mức 5",
        },
        {
          value: 6,
          label: "Mức 6",
        },
        {
          value: 7,
          label: "Mức 7",
        },
        {
          value: 8,
          label: "Mức 9",
        },
        {
          value: 9,
          label: "Mức 9",
        },
        {
          value: 10,
          label: "Mức 10",
        },
      ],

      form: {
        id: "",
        name: "",
        level: "",
      },
      formLabelWidth: "120px",
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
        level: this.filter.level,
      };

      axios
        .get(route("api.company.index1", _.merge(properties, customProperties)))
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
    showDataModal(data) {
      (this.form.id = data.id),
        (this.form.name = data.name),
        (this.form.level = data.level),
        (this.dialogFormVisible = true);
    },
    onSearchChange() {
      this.meta.current_page = 0;
      this.queryServer({});
    },

    onSubmit() {
      this.loading = true;

      axios
        .post(route('api.company.update', {company: this.form.id}),this.form)
        .then((response) => {
          this.dialogFormVisible = false
          this.loading = false;
          this.$message({
            type: "success",
            message: response.data.message,
          });
            this.queryServer({});
        })
        .catch((error) => {
        this.dialogFormVisible = false
          this.loading = false;
          this.$notify.error({
            title: this.$t("mon.error.Title"),
            message: this.getSubmitError(this.form.errors),
          });
        });
    },
  },

  mounted() {
    this.fetchData();
  },
};
</script>

<style scoped>
</style>

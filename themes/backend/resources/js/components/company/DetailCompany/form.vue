<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <el-breadcrumb separator="/">
                  <el-breadcrumb-item>
                    <a href="/admin">{{ $t("mon.breadcrumb.home") }}</a>
                  </el-breadcrumb-item>
                  <el-breadcrumb-item :to="{ name: 'admin.company.index1' }"
                    >{{ $t("company.label.company") }}
                  </el-breadcrumb-item>
                  <el-breadcrumb-item> {{ $t(pageTitle) }} </el-breadcrumb-item>
                </el-breadcrumb>
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
                <el-tabs>
                  <div class="row">
                    <div class="col-md-12 mx-auto font-weight-bold">
                        <div class="row">
                      <div class="col-md-2 text-primary">
                        <span>Thông tin</span>
                      </div>
                      <div class="col-md-10">
                        Danh sách chi nhánh ({{ modelForm.branchnumber }})
                      </div>
                      </div>
                    </div>
                  </div>
                  <el-tab-pane>
                    <span slot="label">Thông tin</span>
                    <div class="row">
                      <div class="col-md-12 mx-auto">
                        <div class="row mb-3">
                          <div class="col-md-2">
                            <span>{{ $t("company.label.name") }}</span>
                          </div>
                          <div class="col-md-10">{{ modelForm.name }}</div>
                        </div>
                        <div class="row mb-3">
                          <div class="col-md-2">
                            <span>{{ $t("company.label.address") }}</span>
                          </div>
                          <div class="col-md-10">{{ modelForm.address }}</div>
                        </div>

                        <div class="row mb-3">
                          <div class="col-md-2">
                            <span>{{ $t("company.label.phone") }}</span>
                          </div>
                          <div class="col-md-10">{{ modelForm.phone }}</div>
                        </div>

                        <div class="row mb-3">
                          <div class="col-md-2">
                            <span>{{ $t("company.label.email") }}</span>
                          </div>
                          <div class="col-md-10">{{ modelForm.email }}</div>
                        </div>

                        <div class="row mb-3">
                          <div class="col-md-2">
                            <span>Ảnh</span>
                          </div>
                          <div class="col-md-10">
                            <template slot-scope="scope">
                              <img
                                :src="scope.row.thumbnail.path_string"
                                v-if="scope.row.thumbnail"
                                width="100"
                              />
                            </template>
                          </div>
                        </div>

                        <div
                          v-if="modelForm.approve_status == 0"
                          class="row mt-5"
                        >
                          <div class="col-md-2">
                            <el-button
                              type="primary"
                              size="small"
                              :loading="loading"
                              class="btn btn-flat"
                            >
                              Duyệt thông tin
                            </el-button>
                          </div>
                          <div class="col-md-10"></div>
                        </div>
                      </div>
                    </div>
                  </el-tab-pane>
                  <el-tab-pane class="card-content-bg">
                    <span slot="label">Danh sách chi nhánh</span>
                    <div class="row mb-3" style="padding: 10px">
                      <div class="col-12">
                        <branch-list
                          :company_id="this.$route.params.companyId"
                        />
                      </div>
                    </div>
                  </el-tab-pane>
                </el-tabs>
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
import Form from "form-backend-validation";
import BranchList from "./branch-list";

export default {
  props: {
    locales: { default: null },
    pageTitle: { default: null, String },
  },
  components: {
    BranchList,
  },
  data() {
    return {
      loading: false,
      modelForm: {},
    };
  },
  methods: {
    fetchData() {
      let routeUri = "";
      if (this.$route.params.companyId !== undefined) {
        this.loading = true;
        routeUri = route("api.company.findCompany", {
          company: this.$route.params.companyId,
        });
        axios.get(routeUri).then((response) => {
          this.loading = false;
          this.modelForm = response.data.data;
        });
      }
    },
  },
  mounted() {
    this.fetchData();
  },
};
</script>

<style scoped>
.box-company-header {
  display: flex;
  align-content: center;
}
</style>

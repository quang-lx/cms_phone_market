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
                                    <el-breadcrumb-item>Cấu hình trang chủ
                                    </el-breadcrumb-item>

                                </el-breadcrumb>

                            </div>
                            <div class="col-sm-6 d-flex justify-content-end text-right">
                                <div class="row">

                                    <div class="col-sm-3">

                                            <el-button type="primary"      class="btn btn-flat" @click="addBlock">
                                                Thêm block
                                            </el-button>

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
                <el-form
                    ref="form"
                    :model="modelForm"
                    label-width="200px"
                    label-position="left"
                    v-loading.body="loading"
                >
                    <draggable
                        :list="modelForm.blocks"
                        group="partSelected"
                        handle=".handle-sort"
                        class="parts-selected-part"
                        :fallback-on-body="true"
                        :forceFallback="true"
                        drag-class="part-selected-drag"
                    >
                    <div class="card" v-for="(item, key) in modelForm.blocks" :key="key">
                        <div class="card-body" >
                    <div class="row"  >
                        <div class="col-1  d-flex justify-content-center align-items-center" >
                            <div class="drag-item-bt z-index-2 handle-sort d-flex justify-content-center align-items-center">
                                <i class="fa fa-arrows-alt back-to-step-icon drag-drop-icon"
                                   aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="col-10">
                            <el-form-item
                              label="Loại"
                              :class="{'el-form-item is-error': form.errors.has('blocks.'+key + '.type')}">
                                <el-select v-model="item.type" placeholder="Chọn loại">
                                    <el-option
                                      v-for="item in listType"
                                      :key="item.value"
                                      :label="item.label"
                                      :value="item.value"
                                    >
                                    </el-option>
                                </el-select>
                                <div
                                  class="el-form-item__error"
                                  v-if="form.errors.has('blocks.'+key + '.type')"
                                  v-text="form.errors.first('blocks.'+key + '.type')"
                                ></div>
                            </el-form-item>
                                    <el-form-item
                                        label="Tiêu đề"
                                        :class="{'el-form-item is-error': form.errors.has('blocks.'+'blocks.'+key + '.title')}">
                                        <el-input v-model="item.title"></el-input>
                                        <div
                                            class="el-form-item__error"
                                            v-if="form.errors.has('blocks.'+key + '.title')"
                                            v-text="form.errors.first('blocks.'+key + '.title')"
                                        ></div>
                                    </el-form-item>
                            <el-form-item
                              label="Nội dung (ID cách nhau bởi dấu phẩy)"
                              :class="{'el-form-item is-error': form.errors.has('blocks.'+key + '.content')}">
                                <el-input v-model="item.content"></el-input>
                                <div
                                  class="el-form-item__error"
                                  v-if="form.errors.has('blocks.'+key + '.content')"
                                  v-text="form.errors.first('blocks.'+key + '.content')"
                                ></div>
                            </el-form-item>

                                </div><!-- /.card-body -->
                        <div class="col-1  d-flex justify-content-center align-items-center"  v-if="modelForm.blocks.length>1" >
                            <div class=" z-index-2   d-flex justify-content-center align-items-center">
                                <i class="fa fa-times back-to-step-icon drag-drop-icon" @click="removeBlock(key)"
                                   aria-hidden="true"></i>
                            </div>
                        </div>
                            </div>
                        </div>

                    </div>
                    </draggable>
                </el-form>

            </div>
            <div class="card-footer d-flex justify-content-center">
                <el-button
                    type="primary"
                    @click="onSubmit()"
                    size="small"
                    :loading="loading"
                    class="btn btn-flat"
                >
                    Lưu
                </el-button>

            </div>
        </section>


    </div>
</template>

<script>
  import axios from 'axios';
  import Form from "form-backend-validation";
  import draggable from 'vuedraggable'

  export default {
    components: {
      draggable,
    },
    data() {
      return {

        form: new Form(),
        loading: false,
        modelForm: {
            blocks: []
        },
        listType: [
          {
            value: 'banner',
            label: 'Banner'
          },
          {
            value: 'best_sell',
            label: 'Bán chạy'
          },
          {
            value: 'buy_now',
            label: 'Mua luôn'
          },
          {
            value: 'category',
            label: 'Danh mục'
          }
        ]

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

        axios.get(route('api.homesetting.index', _.merge(properties, customProperties)))
        .then((response) => {
          this.tableIsLoading = false;
          this.tableIsLoading = false;
          this.modelForm.blocks = response.data.data;
          if (this.modelForm.blocks.length == 0) {
            this.addBlock()
          }
          this.meta = response.data.meta;
          this.links = response.data.links;
          this.order_meta.order_by = properties.order_by;
          this.order_meta.order = properties.order;
        });
      },
      addBlock() {
        this.modelForm.blocks.push({
          title: '',
          type: '',
          content: ''
        })
      },
      removeBlock(key) {
        this.modelForm.blocks.splice(key,1)
      },
      onSubmit() {
        this.form = new Form(_.merge(this.modelForm, {}));
        this.loading = true;

        this.form
        .post(route("api.homesetting.store"))
        .then((response) => {
          this.loading = false;
          this.$message({
            type: "success",
            message: response.message,
          });
          window.location.href = route('admin.homesetting.index');
        })
        .catch((error) => {
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
  }
</script>

<style scoped>
.drag-drop-icon{
    cursor:pointer;
    font-size: 30px;
}
</style>

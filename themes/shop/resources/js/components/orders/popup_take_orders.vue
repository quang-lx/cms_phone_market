<template>
  <div
    class="col-md-12"
    v-if="
      data.order_type == 'sua_chua' &&
      data.type_other == 1 &&
      data.status_value == 'created'
    "
  >
    <el-button type="primary" @click="dialogVisible = true">Nhận đơn</el-button>
    <el-dialog title="Nhận đơn" :visible.sync="dialogVisible" width="30%">
      <el-form ref="form" :model="modelUpdae" label-width="120px">
        <el-form-item label="Số tiền">
          <el-input v-model="modelUpdae.price"></el-input>
          <div
            class="el-form-item__error"
            v-if="form.errors.has('price')"
            v-text="form.errors.first('price')"
          ></div>
        </el-form-item>
        <el-form-item label="Số ngày sửa chữa">
          <el-input v-model="modelUpdae.numberDate"></el-input>
          <div
            class="el-form-item__error"
            v-if="form.errors.has('numberDate')"
            v-text="form.errors.first('numberDate')"
          ></div>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="dialogVisible = false">Hủy</el-button>
        <el-button type="primary" @click="onSubmit">Xác nhận</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import Form from "form-backend-validation";
export default {
  props: {
    data: { default: null },
  },
  data() {
    return {
      form: new Form(),
      loading: false,
      dialogVisible: false,
      modelUpdae: {
        price:'',
        numberDate:'',
        type: 'sua_chua'
      },
      message: "",
    };
  },
  methods: {
    onSubmit() {
      this.form = new Form(_.merge(this.modelUpdae, {}));
      this.loading = true;

      this.form
        .post(this.getRoute())
        .then((response) => {
          this.loading = false;
          this.$message({
            type: "success",
            message: response.message,
          });
          this.dialogVisible = false;
          this.$router.push({ name: "shop.orders.index" });
        })
        .catch((error) => {
          this.loading = false;
          this.$notify.error({
            title: this.$t("mon.error.Title"),
            message: this.getSubmitError(this.form.errors),
          });
        });
    },

    getRoute() {
      return route("apishop.orders.update", {
        orders: this.$route.params.ordersId,
      });
    },
  },
};
</script>

<style scoped>
</style>

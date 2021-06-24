<template>
    <div>
        <!-- xác nhận đơn hàng -->
        <div
            class="col-md-12"
            v-if="
                data.order_type == 'sua_chua' && data.status_value == 'created'
            "
        >
            <el-button type="secondary" @click="cancelOrder">{{
                $t("orders.label.button.cancel")
            }}</el-button>
            <el-button type="primary" @click="waitClient">{{
                $t("orders.label.button.wait_client")
            }}</el-button>
        </div>
          <!-- xác nhận đơn hàng từ app-->
        <div
            class="col-md-12"
            v-if="
                data.order_type == 'sua_chua' &&   data.type_other == 1  && data.fix_time_date !=null && data.status_value == 'wait_client'
            "
        >
            <el-button type="secondary" @click="cancelOrder">{{
                $t("orders.label.button.cancel")
            }}</el-button>
        </div>
        <!-- nhận hàng thường -->
        <div
            class="col-md-12"
            v-if="
                data.order_type == 'sua_chua' &&
                    data.type_other != 1 &&
                    data.status_value == 'confirmed'
            "
        >
            <el-button type="secondary" @click="cancelOrder">{{
                $t("orders.label.button.cancel")
            }}</el-button>
            <el-button type="primary" @click="confirmed">{{
                $t("orders.label.button.confirmed")
            }}</el-button>
        </div>

        <!-- nhận hàng khác -->
        <div
            class="col-md-12"
            v-if="
                data.order_type == 'sua_chua' &&
                 data.type_other == 1 && data.fix_time_date ==null &&
                    data.status_value == 'confirmed'
            "
        >
            <el-button type="secondary" @click="cancelOrder">{{
                $t("orders.label.button.cancel")
            }}</el-button>
            <el-button type="primary" @click="dialogVisible = true">{{
                $t("orders.label.button.confirmed")
            }}</el-button>
        </div>
        <el-dialog title="Nhận đơn" class="text-left" :visible.sync="dialogVisible" width="30%">
            <el-form ref="form" :model="modelUpdae" label-width="120px">
                <el-form-item label="Số tiền">
                    <cleave v-model="modelUpdae.price" :options="options" class="form-control" name="price"></cleave>

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
                <el-button type="primary" @click="onConfirmedOther"
                    >Xác nhận</el-button
                >
            </span>
        </el-dialog>

        <!-- chờ sửa chữa -->
        <div
            class="col-md-12"
            v-if="
                data.order_type == 'sua_chua' && data.status_value == 'fixing'
            "
        >
            <el-button type="secondary" @click="cancelOrder">{{
                $t("orders.label.button.cancel")
            }}</el-button>
            <el-button type="primary" @click="sending">{{
                $t("orders.label.button.fixing")
            }}</el-button>
        </div>
        <!-- chờ giao hàng -->
        <div
            class="col-md-12"
            v-if="
                data.order_type == 'sua_chua' &&
                    data.shop_done == 0 &&
                    data.status_value == 'sending'
            "
        >
            <el-button type="secondary" @click="cancelOrder">{{
                $t("orders.label.button.cancel")
            }}</el-button>
            <el-button type="primary" @click="done">{{
                $t("orders.label.button.done")
            }}</el-button>
        </div>
    </div>
</template>

<script>
import Form from "form-backend-validation";
import Cleave from 'vue-cleave-component';

export default {
    components: {
      Cleave
    },
    props: {
        data: { default: null }
    },
    data() {
        return {
            form: new Form(),
            loading: false,
            dialogVisible: false,
            modelUpdae: {
                price: "",
                numberDate: "",
                type: "sua_chua"
            },
            message: "",
            options: {
                prefix: '',
                numeral: true,
                numeralPositiveOnly: true,
                noImmediatePrefix: true,
                rawValueTrimPrefix: true,
                numeralIntegerScale: 12,
                numeralDecimalScale: 0
            },
        };
    },
    methods: {
        // hủy đơn hàng
        cancelOrder() {
            this.$confirm(this.$t("orders.label.confirm.cancel"), {
                confirmButtonText: this.$t("mon.cancel.Yes"),
                cancelButtonText: this.$t("mon.cancel.No"),
                type: "warning"
            })
                .then(() => {
                    this.onCancel();
                })
                .catch(() => {});
        },

        onCancel() {
            axios
                .post(
                    route("apishop.orders.repair_cancel", {
                        orders: this.$route.params.ordersId
                    })
                )
                .then(response => {
                    this.loading = false;
                    this.$message({
                        type: "success",
                        message: response.data.message
                    });
                    this.dialogVisible = false;
                    this.$router.push({ name: "shop.orders.index" });
                })
                .catch(error => {
                    this.loading = false;
                    this.dialogVisible = false;
                    this.$notify.error({
                        title: "Lỗi xác nhận",
                        message: error.response.data.message
                    });
                });
        },

        // xác nhận đơn hàng
        waitClient() {
            this.$confirm(this.$t("orders.label.confirm.wait_client"), {
                confirmButtonText: this.$t("mon.cancel.Yes"),
                cancelButtonText: this.$t("mon.cancel.No"),
                type: "warning"
            })
                .then(() => {
                    this.onCwaitClient();
                })
                .catch(() => {});
        },

        onCwaitClient() {
            axios
                .post(
                    route("apishop.orders.repair_confirmed", {
                        orders: this.$route.params.ordersId
                    })
                )
                .then(response => {
                    this.loading = false;
                    this.$message({
                        type: "success",
                        message: response.data.message
                    });
                    this.dialogVisible = false;
                    this.$router.push({ name: "shop.orders.index" });
                })
                .catch(error => {
                    this.loading = false;
                    this.dialogVisible = false;
                    this.$notify.error({
                        title: "Lỗi xác nhận",
                        message: error.response.data.message
                    });
                });
        },

        // nhận đơn hàng
        // 1. thuong
        confirmed() {
            this.$confirm(this.$t("orders.label.confirm.confirmed"), {
                confirmButtonText: this.$t("mon.cancel.Yes"),
                cancelButtonText: this.$t("mon.cancel.No"),
                type: "warning"
            })
                .then(() => {
                    this.onConfirmed();
                })
                .catch(() => {});
        },

        onConfirmed() {
            axios
                .post(
                    route("apishop.orders.repair_fixing", {
                        orders: this.$route.params.ordersId
                    })
                )
                .then(response => {
                    this.loading = false;
                    this.$message({
                        type: "success",
                        message: response.data.message
                    });
                    this.dialogVisible = false;
                    this.$router.push({ name: "shop.orders.index" });
                })
                .catch(error => {
                    this.loading = false;
                    this.dialogVisible = false;
                    this.$notify.error({
                        title: "Lỗi xác nhận",
                        message: error.response.data.message
                    });
                });
        },

        // 2. khác
        onConfirmedOther() {
            this.form = new Form(_.merge(this.modelUpdae, {}));
            this.loading = true;

            this.form
                .post(this.getRoute())
                .then(response => {
                    this.loading = false;
                    this.$message({
                        type: "success",
                        message: response.message
                    });
                    this.dialogVisible = false;
                    this.$router.push({ name: "shop.orders.index" });
                })
                .catch(error => {
                    this.loading = false;
                    this.$notify.error({
                        title: this.$t("mon.error.Title"),
                        message: this.getSubmitError(this.form.errors)
                    });
                });
        },

        //chờ sửa chữa
        sending() {
            this.$confirm(this.$t("orders.label.confirm.fixing"), {
                confirmButtonText: this.$t("mon.cancel.Yes"),
                cancelButtonText: this.$t("mon.cancel.No"),
                type: "warning"
            })
                .then(() => {
                    this.onSending();
                })
                .catch(() => {});
        },

        onSending() {
            axios
                .post(
                    route("apishop.orders.repair_sending", {
                        orders: this.$route.params.ordersId
                    })
                )
                .then(response => {
                    this.loading = false;
                    this.$message({
                        type: "success",
                        message: response.data.message
                    });
                    this.dialogVisible = false;
                    this.$router.push({ name: "shop.orders.index" });
                })
                .catch(error => {
                    this.loading = false;
                    this.dialogVisible = false;
                    this.$notify.error({
                        title: "Lỗi xác nhận",
                        message: error.response.data.message
                    });
                });
        },

        // chờ giao hàng

        done() {
            this.$confirm(this.$t("orders.label.confirm.done"), {
                confirmButtonText: this.$t("mon.cancel.Yes"),
                cancelButtonText: this.$t("mon.cancel.No"),
                type: "warning"
            })
                .then(() => {
                    this.onDone();
                })
                .catch(() => {});
        },

        onDone() {
            axios
                .post(
                    route("apishop.orders.repair_done", {
                        orders: this.$route.params.ordersId
                    })
                )
                .then(response => {
                    this.loading = false;
                    this.$message({
                        type: "success",
                        message: response.data.message
                    });
                    this.dialogVisible = false;
                    this.$router.push({ name: "shop.orders.index" });
                })
                .catch(error => {
                    this.loading = false;
                    this.dialogVisible = false;
                    this.$notify.error({
                        title: "Lỗi xác nhận",
                        message: error.response.data.message
                    });
                });
        },

        getRoute() {
            return route("apishop.orders.repair_fixing_other", {
                orders: this.$route.params.ordersId
            });
        }
    }
};
</script>

<style scoped></style>

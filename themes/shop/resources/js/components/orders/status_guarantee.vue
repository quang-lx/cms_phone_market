<template>
    <div>
        <!-- xác nhận đơn hàng -->
        <div
            class="col-md-12"
            v-if="
                data.order_type == 'bao_hanh' && data.status_value == 'created'
            "
        >
            <el-button type="secondary" @click="cancelOrder">{{
                $t("orders.label.button.cancel")
            }}</el-button>
            <el-button type="primary" @click="waitClient">{{
                $t("orders.label.button.wait_client")
            }}</el-button>
        </div>
        <!-- nhận hàng -->
        <div
            class="col-md-12"
            v-if="
                data.order_type == 'bao_hanh' &&
                    data.type_other == 1 &&
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
        <!-- chờ bảo hành -->
        <div
            class="col-md-12"
            v-if="
                data.order_type == 'bao_hanh' && data.status_value == 'warranting'
            "
        >
            <el-button type="secondary" @click="cancelOrder">{{
                $t("orders.label.button.cancel")
            }}</el-button>
            <el-button type="primary" @click="warranting">{{
                $t("orders.label.button.warranting")
            }}</el-button>
        </div>
        <!-- chờ giao hàng -->
         <div
            class="col-md-12"
            v-if="
                data.order_type == 'bao_hanh' && data.status_value == 'sending'
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
export default {
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
                type: "bao_hanh"
            },
            message: ""
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
                    route("apishop.orders.guarantee_cancel", {
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
                    route("apishop.orders.guarantee_confirmed", {
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
                    route("apishop.orders.guarantee_warranting", {
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
        //chờ bảo hành
        warranting() {
            this.$confirm(this.$t("orders.label.confirm.warranting"), {
                confirmButtonText: this.$t("mon.cancel.Yes"),
                cancelButtonText: this.$t("mon.cancel.No"),
                type: "warning"
            })
                .then(() => {
                    this.onWarranting();
                })
                .catch(() => {});
        },

        onWarranting() {
            axios
                .post(
                    route("apishop.orders.guarantee_sending", {
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
                    route("apishop.orders.guarantee_done", {
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
    }
};
</script>

<style scoped></style>

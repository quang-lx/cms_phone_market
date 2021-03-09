<template>
    <el-button type="danger" @click="deleteRow" size="mini"><i class="fas fa-trash"></i></el-button>
</template>

<script>
    export default {
        props: {
            rows: { default: null },
            scope: { default: null },
        },
        data() {
            return {
                deleteMessage: '',
                deleteTitle: '',
            };
        },
        methods: {
            deleteRow(event) {
                this.$confirm(this.deleteMessage, this.deleteTitle, {
                    confirmButtonText: this.$t('mon.button.delete'),
                    cancelButtonText: this.$t('mon.button.cancel'),
                    type: 'warning',
                    confirmButtonClass: 'el-button--danger',
                }).then(() => {
                    const vm = this;
                    axios.delete(this.scope.row.urls.delete_url)
                        .then((response) => {
                            if (response.data.errors === false) {
                                vm.$message({
                                    type: 'success',
                                    message: response.data.message,
                                });

                                vm.rows.splice(vm.scope.$index, 1);
                            }else {
                                vm.$message({
                                    type: 'error',
                                    message: response.data.message,
                                });
                            }
                        })
                        .catch((error) => {
                            vm.$message({
                                type: 'error',
                                message: error.data.message,
                            });
                        });
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: this.$t('mon.delete cancelled'),
                    });
                });
            },
        },
        mounted() {
            this.deleteMessage = this.$t('mon.modal.confirmation-message');
            this.deleteTitle = this.$t('mon.modal.title');
        },
    };
</script>

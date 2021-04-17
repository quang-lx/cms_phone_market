<template>
    <el-button
            size="mini"
            :disabled="disabled"
            icon="el-icon-view"
            @click.prevent="goToViewPage()">

    </el-button>
</template>

<script>
    export default {
        props: {
            to: { type: Object, required: true },
            disabled: { type: Boolean, required:false, default: false}
        },
        methods: {
            goToViewPage() {
                this.$router.push(this.to);
            },
        },
    };
</script>

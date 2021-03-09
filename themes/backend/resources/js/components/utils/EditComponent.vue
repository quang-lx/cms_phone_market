<template>
    <el-button
            size="mini"
            :disabled="disabled"
            @click.prevent="goToEditPage()">
        <i class="far fa-edit"></i>
    </el-button>
</template>

<script>
    export default {
        props: {
            to: { type: Object, required: true },
            disabled: { type: Boolean, required:false, default: false}
        },
        methods: {
            goToEditPage() {
                this.$router.push(this.to);
            },
        },
    };
</script>

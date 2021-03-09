<template>
    <aside class="w-296 js-left-side node10-1">
        <FilterCategory :filters.sync="filters"/>


     </aside>
</template>
<script>
    import FilterCategory from './category'

    export default {
        name: 'ResourceLeftContent',
        props: {
            filters: {
                type: Object,
                default: () => {}
            }
        },
        components: {FilterCategory },
        data() {
            return {

                page: 1
            }
        },
        created() {

        },
        methods: {}
    }
</script>

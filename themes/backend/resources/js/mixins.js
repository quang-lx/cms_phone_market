import Vue from 'vue';

const mixin = {
    data() {
        return {
            pagingSetting: {
                layout: this.$isMobile() ? 'total,   prev, pager, next' : 'total, sizes, prev, pager, next, jumper'
            },
            meta: {
                current_page: 1,
                per_page: 25,
            },
            order_meta: {
                order_by: '',
                order: '',
            },
            links: {},
            searchQuery: '',
            tableIsLoading: false,


        }
    },
    methods: {
        getSubmitError(error) {
            const firstPropValue = Object.values(error.errors)[0];
            return firstPropValue[0];
        },
        fetchData() {
            this.tableIsLoading = true;
            this.queryServer({per_page: 25});
        },

        handleSizeChange(event) {
            this.tableIsLoading = true;
            this.queryServer({per_page: event});
        },
        handleCurrentChange(event) {
            this.tableIsLoading = true;
            this.queryServer({page: event});
        },
        handleSortChange(event) {
            this.tableIsLoading = true;
            this.queryServer({order_by: event.prop, order: event.order});
        },
        performSearch: _.debounce(function (query) {
            this.tableIsLoading = true;
            this.queryServer({search: query.target.value});
        }, 300),

    },
};
Vue.mixin(mixin);

<template>
 <span>

 </span>


</template>

<script>
    import axios from 'axios';
    import Form from 'form-backend-validation';
    import Tinymce from '../utils/Tinymce'

    export default {
        components: {Tinymce},
        props: {
            locales: {default: null},
            pageTitle: {default: null, String},
        },
        data() {
            return {
                form: new Form(),

                loading: false,
                modelForm: {
                   content:''


                },
            };
        },
        methods: {



        },
        mounted() {


        },
        computed: {}
    }
</script>

<style scoped>

</style>
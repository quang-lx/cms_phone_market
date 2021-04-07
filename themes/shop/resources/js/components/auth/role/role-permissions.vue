<template>
        <div class="row">
            <div v-for="(group, index) in groupPermissions" :key="'g-' + index" class="col-md-4" style="margin-bottom: 15px">
                <div class="card" style="height:97%">
                    <div class="card-header ui-sortable-handle" style="cursor: move;">
                        <h3 class="card-title">
                            {{$t('permission.group_mapping.' + index)}}
                        </h3>
                        <div class="card-tools">
                            <el-button type="text" size="mini"
                                       @click="changeState(group, 1)">
                                {{ $t('role.allow all') }}
                            </el-button>

                            <el-button type="text"  size="mini"
                                       @click="changeState(group, -1)">
                                {{ $t('role.deny all') }}
                            </el-button>
                        </div>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="row" v-for="(permission, pIndex) in group" :key="'p-'+pIndex">
                            <div class="col-3"><label class="control-label text-right" style="text-transform: capitalize;display: block;">{{permission.title}}</label></div>
                            <div class="col-9">
                                <el-radio-group v-model="permissions[permission.name]" size="mini">
                                    <el-radio-button :label="1" @click="triggerEvent(permission.name, 1)">{{ $t('role.allow') }}</el-radio-button>
                                    <el-radio-button :label="-1" @click="triggerEvent(permission.name, -1)">{{ $t('role.deny') }}</el-radio-button>
                                </el-radio-group>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>



</template>

<script>
    import axios from 'axios';
    import _ from 'lodash';

    export default {
        props: {
            currentPermissions: { default: null },
        },
        data() {
            return {
                groupPermissions: [],

                links: {},
                checkedPermissions:[],
                guards: [
                    {
                        value: 'api',
                        label: 'api'
                    },
                    {
                        value: 'web',
                        label: 'web'
                    }
                ],
                guard_name: '',
                permissions: {}

            };
        },
        methods: {
            queryServer(customProperties) {

                const properties = {
                    role_id: this.roleId,
                    guard_name: this.guard_name,
                    in_role: (this.type === 'add')? 0: 1
                };

                axios.get(route('apishop.permissions.all-by-group', _.merge({}, customProperties)))
                    .then((response) => {

                        this.groupPermissions = response.data;

                    });
            },
            fetchData() {
                this.tableIsLoading = true;
                this.queryServer({});
            },
            changeState(group, state) {
                _.forEach(group, (permission, key) => {
                    this.permissions[permission.name] = state;
                });
            },
            triggerEvent(permission_name, state) {
                this.$emit('input', this.permissions);
            }


        },
        watch: {
            currentPermissions() {
                if (this.currentPermissions !== null) {
                    this.permissions = this.currentPermissions;
                }
            },
        },
        mounted() {
            this.fetchData();

        },
    }
</script>

<style scoped>

</style>

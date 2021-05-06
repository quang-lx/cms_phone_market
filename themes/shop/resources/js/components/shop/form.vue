<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <el-breadcrumb separator="/">
                                    <el-breadcrumb-item>
                                        <a href="/shop-admin">{{ $t('mon.breadcrumb.home') }}</a>
                                    </el-breadcrumb-item>
                                    <el-breadcrumb-item :to="{name: 'shop.shop.index'}">{{ $t('shop.label.manager') }}
                                    </el-breadcrumb-item>
                                    <el-breadcrumb-item> {{ $t(pageTitle) }}
                                    </el-breadcrumb-item>
                                </el-breadcrumb>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header ui-sortable-handle" style="cursor: move;">
                                <h3 class="card-title">
                                    {{ $t(pageTitle) }}<span v-if="modelForm.name">: &nbsp{{modelForm.name}}</span>
                                </h3>

                            </div><!-- /.card-header -->
                            <div class="card-body" style="padding-top:20px">
                                <el-form
                                    ref="form"
                                    :model="modelForm"
                                    label-width="200px"
                                    label-position="left"
                                    v-loading.body="loading"
                                >
                                    <div class="row">
                                        <div class="col-md-6" style="padding-top:10px;padding-right:30px">
                                            <div class="row">
                                                <div class="col-md-12 mb-4">
                                                      <single-media zone="thumbnail"
                                                                  @singleFileSelected="selectSingleFile($event, 'modelForm')"
                                                                  label="Ảnh đại diện"
                                                                  entity="Modules\Mon\Entities\Shop"
                                                                  :entity-id="$route.params.shopId"></single-media>
                                                            <div class="el-form-item__error"  style="margin-left:208px"
                                                                v-if="form.errors.has('medias_single.thumbnail')"
                                                                v-text="form.errors.first('medias_single.thumbnail')"></div>
                                                </div>
                                                <div class="col-md-12">
                                                    <el-form-item :label="$t('shop.label.name')"
                                                                  :class="{'el-form-item is-error': form.errors.has('name') }">

                                                        <el-input v-model="modelForm.name"></el-input>
                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('name')"
                                                             v-text="form.errors.first('name')"></div>
                                                    </el-form-item>
                                                </div>
                                                <div class="col-md-12">
                                                    <el-form-item :label="$t('shop.label.address')"
                                                                  :class="{'el-form-item is-error': form.errors.has('address') }">

                                                        <el-input v-model="modelForm.address"></el-input>
                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('address')"
                                                             v-text="form.errors.first('address')"></div>
                                                    </el-form-item>
                                                </div>
                                                <div class="col-md-12">
                                                    <el-form-item :label="$t('shop.label.status')"
                                                                  :class="{'el-form-item is-error': form.errors.has(  'status') }">

                                                        <el-select v-model="modelForm.status"
                                                                   :placeholder="$t('shop.label.status')"
                                                                   filterable style="width: 100% !important">
                                                            <el-option
                                                                v-for="item in listStatus"
                                                                :key="'status'+ item.value"
                                                                :label="item.label"
                                                                :value="item.value">
                                                            </el-option>

                                                        </el-select>
                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('status')"
                                                             v-text="form.errors.first('status')"></div>
                                                    </el-form-item>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6"
                                             style="padding-top:10px;">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <el-form-item :label="$t('shop.label.phone')"
                                                                  :class="{'el-form-item is-error': form.errors.has('phone') }">

                                                        <el-input v-model="modelForm.phone"></el-input>
                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('phone')"
                                                             v-text="form.errors.first('phone')"></div>
                                                    </el-form-item>
                                                </div>
                                                <div class="col-md-12">
                                                    <el-form-item :label="$t('shop.label.email')"
                                                                  :class="{'el-form-item is-error': form.errors.has('email') }">

                                                        <el-input v-model="modelForm.email"></el-input>
                                                        <div class="el-form-item__error"
                                                             v-if="form.errors.has('email')"
                                                             v-text="form.errors.first('email')"></div>
                                                    </el-form-item>
                                                </div>

                                            </div>
                                        </div>


                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <el-form-item label="Địa chỉ trên bản đồ"
                                                          :class="{'el-form-item is-error': form.errors.has(  'place') }">

                                                <gmap-autocomplete
                                                    ref="location"
                                                    placeholder=""
                                                    :select-first-on-enter="true"
                                                    class="el-input__inner"

                                                    :value="modelForm.place"
                                                    :id="modelForm.id? modelForm.id: 'location'"
                                                    @focusout="(place) => {
                                            $refs.location.$refs.input.value=modelForm.place;}"
                                                    @place_changed="setPlace">
                                                </gmap-autocomplete>
                                                <div class="el-form-item__error"
                                                     v-if="form.errors.has('place')"
                                                     v-text="form.errors.first('place')"></div>
                                            </el-form-item>


                                        </div>
                                        <div class="col-md-12">
                                            <Gmap-Map style="width: 100%; height: 300px;" :zoom="10"
                                                      :center="{lat:21.0227788, lng:105.8194541}">

                                                <Gmap-Marker

                                                    :position="{
                                                lat : parseFloat( modelForm.lat ),
                                                lng : parseFloat( modelForm.lng )
                                            }"
                                                ></Gmap-Marker>
                                            </Gmap-Map>
                                        </div>
                                    </div>
                                </el-form>
                            </div><!-- /.card-body -->
                            <div class="card-footer d-flex justify-content-end">
                                <el-button type="primary" @click="onSubmit()" size="small" :loading="loading"
                                           class="btn btn-flat ">
                                    {{ $t('mon.button.save') }}
                                </el-button>
                                <el-button class="btn btn-flat " size="small" @click="onCancel()">{{
                                    $t('mon.button.cancel') }}
                                </el-button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

    </div>


</template>

<script>
  import axios from 'axios';
  import Form from 'form-backend-validation';
  import Vue from 'vue'
  import * as VueGoogleMaps from 'vue2-google-maps'
  import Tinymce from '../utils/Tinymce';
  import SingleFileSelector from '../../mixins/SingleFileSelector.js';

  Vue.use(VueGoogleMaps, {
    load: {
      key: window.MonCMS.googleApiKey,
      libraries: 'places'
    },
  });
  export default {
    mixins: [SingleFileSelector],
    components: {
        Tinymce,
    },
    props: {
      pageTitle: {default: null, String},
    },
    data() {
      return {
        form: new Form(),
        loading: false,
        modelForm: {
          name: '',
          address: '',
          phone: '',
          email: '',
          status: 1,
          lat: '',
          lng: '',
          place: '',

        },
        locales: window.MonCMS.locales,
        listStatus: [

          {
            value: 1,
            label: 'Hoạt động'
          },
          {
            value: 0,
            label: 'Không hoạt động'
          }
        ],


      };
    },
    methods: {
      setPlace(place) {
        this.modelForm.place = place.name;
        this.modelForm.lat = place.geometry.location.lat();
        this.modelForm.lng = place.geometry.location.lng();
      },
      onSubmit() {
        this.form = new Form(_.merge(this.modelForm, {}));
        this.loading = true;

        this.form.post(this.getRoute())
        .then((response) => {
          this.loading = false;
          this.$message({
            type: 'success',
            message: response.message,
          });
          this.$router.push({name: 'shop.shop.index'});
        })
        .catch((error) => {

          this.loading = false;
          this.$notify.error({
            title: this.$t('mon.error.Title'),
            message: this.getSubmitError(this.form.errors),
          });
        });
      },
      onCancel() {

        this.$confirm(this.$t('mon.cancel.Are you sure to cancel?'), {
          confirmButtonText: this.$t('mon.cancel.Yes'),
          cancelButtonText: this.$t('mon.cancel.No'),
          type: 'warning'
        }).then(() => {
          this.$router.push({name: 'shop.shop.index'});
        }).catch(() => {

        });


      },

      fetchData() {
        this.loading = true;
        let locale = this.$route.params.locale ? this.$route.params.locale : 'en';
        axios.get(route('api.shop.find', {shop: this.$route.params.shopId}))
        .then((response) => {
          this.loading = false;
          this.modelForm = response.data.data;

        });
      },

      getRoute() {
        if (this.$route.params.shopId !== undefined) {
          return route('api.shop.update', {shop: this.$route.params.shopId});
        }
        return route('api.shop.store');
      },

    },
    mounted() {
      if (this.$route.params.shopId !== undefined) {
        this.fetchData();
      }

    },
    computed: {},

  }
</script>

<style scoped>
    .block-status {
        padding: 6px 20px;
        margin-left: 20px;
    }

    .btn-default {
        background-color: #f4f4f4;
        color: #444;
        border-color: #ddd;
        color: white;
    }

    .btn-default:hover,
    .btn-default:active,
    .btn-default.hover {
        background-color: #e7e7e7;
        color: white;
    }

    .btn-primary {
        background-color: #3c8dbc;
        border-color: #367fa9;
        color: white;
    }

    .btn-primary:hover,
    .btn-primary:active,
    .btn-primary.hover {
        background-color: #367fa9;
        color: white;
    }

    .btn-success {
        background-color: #00a65a;
        border-color: #008d4c;
        color: white;
    }

    .btn-success:hover,
    .btn-success:active,
    .btn-success.hover {
        background-color: #008d4c;
        color: white;
    }

    .btn-info {
        background-color: #00c0ef;
        border-color: #00acd6;
        color: white;
    }

    .btn-info:hover,
    .btn-info:active,
    .btn-info.hover {
        background-color: #00acd6;
        color: white;
    }

    .btn-danger {
        background-color: #dd4b39;
        border-color: #d73925;
        color: white;
    }

    .btn-danger:hover,
    .btn-danger:active,
    .btn-danger.hover {
        background-color: #d73925;
        color: white;
    }

    .btn-warning {
        background-color: #f39c12;
        border-color: #e08e0b;
        color: white;
    }

    .btn-warning:hover,
    .btn-warning:active,
    .btn-shop.hover {
        background-color: #e08e0b;
        color: white;
    }

    .btn-outline {
        border: 1px solid #fff;
        background: transparent;
        color: #fff;
    }

    .btn-outline:hover,
    .btn-outline:focus,
    .btn-outline:active {
        color: rgba(255, 255, 255, 0.7);
        border-color: rgba(255, 255, 255, 0.7);
        color: white;
    }
</style>

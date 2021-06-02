<template>
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Dashboard</h1>
        </div>
      </div>
    </div>

<!-- block tổng quan -->
    <div class="container-fluid">
      <div class="row">
          <div class="col-sm-3">
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{ totalRevenue }}đ</h3>

                <p>{{ $t("dashboard.label.totalRevenue") }}</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
            </div>
          </div>

          <div class="col-sm-3">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{ totalFee }}đ</h3>

                <p>{{ $t("dashboard.label.totalFee") }}</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-sm-6">
            <div class="small-box box-2">
            <!-- small box -->
              <div class="text-center box-title">{{ $t("dashboard.label.new-users-count") }}</div>
              <div class="row">
                    <div class="col-sm-4 border-right">
                      <div class="description-block">
                        <h5 class="description-header">{{ $t("dashboard.label.day") }}</h5>
                        <span class="description-text">{{ newUser.day }}</span>
                        
                      </div>
                      <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-4 border-right">
                      <div class="description-block">
                        <h5 class="description-header">{{ $t("dashboard.label.week") }}</h5>
                        <span class="description-text">{{ newUser.week }}</span>
                      </div>
                      <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-4">
                      <div class="description-block">
                        <h5 class="description-header">{{ $t("dashboard.label.month") }}</h5>
                        <span class="description-text">{{ newUser.month }}</span>
                      </div>
                      <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                  </div>
              </div>
            </div>
      </div>

      <div class="row">
        <div class="col-sm-6">
          <div class="small-box box-2">
            <!-- small box -->
            <div class="text-center box-title">{{ $t("dashboard.label.new-shop") }}</div>
            <div class="row">
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <h5 class="description-header">{{ $t("dashboard.label.day") }}</h5>
                      <span class="description-text">{{ newShop.day }}</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <h5 class="description-header">{{ $t("dashboard.label.week") }}</h5>
                      <span class="description-text">{{ newShop.week }}</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4">
                    <div class="description-block">
                      <h5 class="description-header">{{ $t("dashboard.label.month") }}</h5>
                      <span class="description-text">{{ newShop.month }}</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                </div>
            </div>
          </div>
        <div class="col-sm-6">
          <div class="small-box box-2">
          <!-- small box -->
            <div class="text-center box-title">{{ $t("dashboard.label.new-shop-not-active") }}</div>
            <div class="row">
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <h5 class="description-header">{{ $t("dashboard.label.day") }}</h5>
                      <span class="description-text">{{ newShopInactive.day }}</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <h5 class="description-header">{{ $t("dashboard.label.week") }}</h5>
                      <span class="description-text">{{ newShopInactive.week }}</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4">
                    <div class="description-block">
                      <h5 class="description-header">{{ $t("dashboard.label.month") }}</h5>
                      <span class="description-text">{{ newShopInactive.month }}</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                </div>
            </div>
          </div>
      </div>
          
    </div>

    <div class="container-fluid">
      <div class="card">
        <div class="card-header"><strong>{{ $t("dashboard.label.count-order") }}</strong></div>

        <div class="card-body">
          <div>
            <line-chart :chart-data="dataCountOrder" :options="chartOptions"></line-chart>
          </div>
        </div>
      </div>
    </div>

    <div class="container-fluid" style="margin-top:30px">
      <div class="card">
        <div class="card-header"><strong>{{ $t("dashboard.label.revenue-order") }}</strong></div>

        <div class="card-body">
          <div>
            <line-chart :chart-data="dataRevenueOrder" :options="chartOptions"></line-chart>
          </div>
        </div>
      </div>
    </div>

    <div class="container-fluid" style="margin-top:30px">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-sm-6">
              <div class="row">
                <ul>
                  <li style="font-weight:bold">{{ $t("dashboard.label.top-shop") }}</li>
                  <li v-for="company in topCompany" :key="company.index">{{ company.value }}</li>
                </ul>
              </div>

              <div class="row" style="margin-top:40px">
                <ul>
                  <li style="font-weight:bold">{{ $t("dashboard.label.top-category") }}</li>
                  <li v-for="category in topCategory" :key="category.index">{{ category.value }}</li>
                </ul>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="row">
                <ul>
                  <li style="font-weight:bold">{{ $t("dashboard.label.top-product") }}</li>
                  <li v-for="product in topProduct" :key="product.key">
                    {{ product.productInfo }} <br/> {{ product.shopInfo}}
                  </li>
                </ul>
              </div>

            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import LineChart from './LineChart.js'

export default {
  components: {
    LineChart,
  },
  data () {
      return {
        dataCountOrder: null,
        dataRevenueOrder: null,
        totalFee: null,
        totalRevenue: null,
        newUser: {},
        newShop: {},
        newShopInactive: {},
        topCompany: [],
        topProduct: [],
        topCategory: [],
        chartOptions: { responsive: true, maintainAspectRatio: false }
      }
    },
    mounted () {
      this.getOrderStatistical();
      this.getUserStatistical();
      this.getShopStatistical();
      this.getTopCompany();
      this.getTopProduct();
    },
    methods: {
      formatNumber(number){
          return number.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')
      },
      getOrderStatistical() {
        const properties = {};

        axios
          .get(route("api.dashboards.orders", _.merge(properties, {})))
          .then((response) => {
            this.totalFee = this.formatNumber(response.data.totalFee);
            this.totalRevenue = this.formatNumber(response.data.totalRevenue);
            this.dataCountOrder = {
                labels: response.data.listDate,
                datasets: [
                  {
                    label: "Sửa chữa",
                    backgroundColor: "#0066ff",
                    data: response.data.listRepairCount,
                  },
                  {
                    label: "Bảo hành",
                    backgroundColor: "#a2c3c3",
                    data: response.data.listGuaranteeCount,
                  },
                  {
                    label: "Mua bán hàng",
                    backgroundColor: "#00ff00",
                    data: response.data.listBuyCount,
                  },
                ],
              };

              this.dataRevenueOrder = {
                labels: response.data.listDate,
                datasets: [
                  {
                    label: "Sửa chữa",
                    backgroundColor: "#0066ff",
                    data: response.data.listRepairMoney,
                  },
                  {
                    label: "Bảo hành",
                    backgroundColor: "#a2c3c3",
                    data: response.data.listGuaranteeMoney,
                  },
                  {
                    label: "Mua bán hàng",
                    backgroundColor: "#00ff00",
                    data: response.data.listBuyMoney,
                  },
                ],
              };
          });
      },

      getUserStatistical() {
        const properties = {};

        axios
          .get(route("api.dashboards.users", _.merge(properties, {})))
          .then((response) => {
            this.newUser = {
              day: this.formatNumber(response.data.userToday),
              week: this.formatNumber(response.data.userWeek),
              month: this.formatNumber(response.data.userMonth),
            }
                        
          });
      },

      getTopCompany() {
        const properties = {};
        axios
          .get(route("api.dashboards.topCompany", _.merge(properties, {})))
          .then((response) => {
            this.topCompany = response.data;
                        
          });
      },

      getTopProduct() {
        const properties = {};
        axios
          .get(route("api.dashboards.topProduct", _.merge(properties, {})))
          .then((response) => {
            this.topProduct = response.data.topProduct;
            this.topCategory = response.data.topCategory;
                        
          });
      },

      getShopStatistical() {
        const properties = {};

        axios
          .get(route("api.dashboards.shops", _.merge(properties, {})))
          .then((response) => {
            this.newShop = {
              day: this.formatNumber(response.data.shopActive.day),
              week: this.formatNumber(response.data.shopActive.week),
              month: this.formatNumber(response.data.shopActive.month),
            }

            this.newShopInactive = {
              day: this.formatNumber(response.data.shopInactive.day),
              week: this.formatNumber(response.data.shopInactive.week),
              month: this.formatNumber(response.data.shopInactive.month),
            }
                        
          });
      },
    },
};
</script>

<style>
.small {
  max-width: 600px;
  margin: 150px auto;
}
.box-title {
  margin-top:15px;
  font-weight: bold;
}
.box-2 {
  padding-top: 5px;
  padding-bottom: 5px;
}
ul > li {
  list-style-type: none;
}
</style>
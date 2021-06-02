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
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{ totalRevenue }}đ</h3>

                <p>{{ $t("dashboard.label.totalRevenue") }}</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{ paid }}đ</h3>

                <p>{{ $t("dashboard.label.paid") }}</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{ waitPaid }}đ</h3>

                <p>{{ $t("dashboard.label.waitPaid") }}</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>{{ totalFee }}đ</h3>

                <p>{{ $t("dashboard.label.totalFee") }}</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
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

    <div class="container-fluid">
      <div class="card">
        <div class="card-header"><strong>{{ $t("dashboard.label.revenue-order") }}</strong></div>

        <div class="card-body">
          <div>
            <line-chart :chart-data="dataRevenueOrder" :options="chartOptions"></line-chart>
          </div>
        </div>
      </div>
    </div>

    <!-- Top doanh thu -->
    <div class="container-fluid" style="margin-top:30px">
      <div class="card">
        <div class="card-body">
          <div class="row">
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

            <div class="col-sm-6">
              <div class="row">
                <ul>
                  <li style="font-weight:bold">{{ $t("dashboard.label.top-category") }}</li>
                  <li v-for="category in topCategory" :key="category.index">{{ category.value }}</li>
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
        paid: null,
        totalFee: null,
        totalRevenue: null,
        waitPaid: null,
        topProduct: [],
        topCategory: [],
        chartOptions:{ responsive: true, maintainAspectRatio: false }
      }
    },
    mounted () {
      this.getOrderStatistical();
      this.getTopProduct();
    },
    methods: {
      formatNumber(number){
          return number.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')
      },
      getOrderStatistical() {
        const properties = {};

        axios
          .get(route("apishop.orders.statistical", _.merge(properties, {})))
          .then((response) => {
            this.paid = this.formatNumber(response.data.paid);
            this.totalFee = this.formatNumber(response.data.totalFee);
            this.totalRevenue = this.formatNumber(response.data.totalRevenue);
            this.waitPaid = this.formatNumber(response.data.waitPaid);
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

      getTopProduct() {
        const properties = {};
        axios
          .get(route("apishop.dashboards.topProduct", _.merge(properties, {})))
          .then((response) => {
            this.topProduct = response.data.topProduct;
            this.topCategory = response.data.topCategory;
                        
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
ul > li {
  list-style-type: none;
}
</style>
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
            <line-chart :chart-data="dataCountOrder"></line-chart>
          </div>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="card">
        <div class="card-header"><strong>{{ $t("dashboard.label.revenue-order") }}</strong></div>

        <div class="card-body">
          <div>
            <line-chart :chart-data="dataRevenueOrder"></line-chart>
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
      }
    },
    mounted () {
      this.getOrderStatistical();
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
    },
};
</script>

<style>
.small {
  max-width: 600px;
  margin: 150px auto;
}
</style>
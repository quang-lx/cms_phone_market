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
                <h3>1.000.000.000đ</h3>

                <p>Tổng doanh thu</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>900.000.000đ</h3>

                <p>Đã thanh toán</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>100.000.000đ</h3>

                <p>Sẽ thanh toán</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>100.000.000đ</h3>

                <p>Tổng phí</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
    </div>

    <div class="container-fluid">
      <div class="card">
        <div class="card-header">{{ $t("dashboard.label.shop-product") }}</div>

        <div class="card-body">
          <div>
            <bar-chart />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import BarChart from "./BarChart.js";

export default {
  name: "app",
  components: {
    BarChart,
  },
};
</script>

<style>
.small {
  max-width: 600px;
  margin: 150px auto;
}
</style>
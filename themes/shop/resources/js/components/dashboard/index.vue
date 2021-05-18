<template>
  <div class="content-header">
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
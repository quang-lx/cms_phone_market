  <div class="small">
    <bar-chart :chart-data="datacollection"></bar-chart>
    <!-- <button @click="fillData()">Randomize</button> -->
  </div>

<script>
import { Bar } from 'vue-chartjs';

export default {
  extends: Bar,
  // components: {
  //   BarChart,
  // },
  // api.shop.index
  data() {
    return {
      shopArr: [],
      datacollection: null,
    };
  },
  mounted() {
    this.fetchShop();
    this.renderChart(
      {
        labels: [
          "January",
          "February",
          "March",
          "April",
          "May",
          "June",
          "July",
          "August",
          "September",
          "October",
          "November",
          "December",
        ],
        datasets: [
          {
            label: "Data One",
            backgroundColor: "#f87979",
            data: [40, 20, 12, 39, 10, 40, 39, 80, 40, 20, 12, 11],
          }
        ],
      },
      { responsive: true, maintainAspectRatio: false }
    );
  },
  methods: {
    fetchShop() {
      const properties = {
        page: 0,
        per_page: 1000,
        check_company: true,
      };

      axios
        .get(route("api.shop.index", _.merge(properties, {})))
        .then((response) => {
          this.shopArr = response.data.data;
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
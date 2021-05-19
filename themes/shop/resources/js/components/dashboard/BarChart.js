import { Bar } from "vue-chartjs";

export default {
  extends: Bar,
  data() {
    return {
      datacollection: null,
    };
  },
  async mounted() {
    await this.fetchShop();
    this.renderChart(
      this.datacollection, 
      { responsive: true, maintainAspectRatio: false }
    );
  },
  methods: {
    async fetchShop() {
      const properties = {
        page: 0,
        per_page: 1000,
      };

      await axios
        .get(route("apishop.dashboard.listShop", _.merge(properties, {})))
        .then((response) => {
          console.log(response);
          this.datacollection = {
              labels: response.data.listShopName,
              datasets: [
                {
                  label: "Sản phẩm",
                  backgroundColor: "#f87979",
                  data: response.data.listCountProduct,
                },
              ],
            };
        });
    },
  },
};


  <div class="small">
    <bar-chart />
  </div>

<script>
import { Bar } from "vue-chartjs";

export default {
  extends: Bar,
  data() {
    return {
      listShopName: [],
      listCountProduct: [],
    };
  },
  mounted() {
    this.fetchShop();
    this.renderChart(
      {
        labels: this.listShopName,
        datasets: [
          {
            label: "Sản phẩm",
            backgroundColor: "#f87979",
            data: this.listCountProduct,
          },
        ],
      },
      { responsive: true, maintainAspectRatio: false }
    );
  },
  methods: {
    async fetchShop() {
      const properties = {
        page: 0,
        per_page: 1000,
        check_company: true,
      };

      await axios
        .get(route("api.shop.index", _.merge(properties, {})))
        .then((response) => {
          let listShop = response.data.data;
          listShop.forEach((shop) => {
            this.listShopName.push(shop.name);
            let productCount = 0;
            shop.products.forEach((product) => {
              productCount += parseInt(product.amount);
            });
            this.listCountProduct.push(productCount);
          });
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
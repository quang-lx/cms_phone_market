<div>
    <div class="card-header">{{ $t('dashboard.label.shop-product') }}</div>
    <div class="content-header">
        <div class="container-fluid">
            <div class="card">

                <div class="card-body">
                  <div class="small">
                    <bar-chart :chart-data="datacollection"></bar-chart>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
import { Bar } from "vue-chartjs";

export default {
  extends: Bar,
  data() {
    return {
      listShopName: [],
      listCountProduct: [],
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

          this.datacollection = {
              labels: this.listShopName,
              datasets: [
                {
                  label: "Sản phẩm",
                  backgroundColor: "#f87979",
                  data: this.listCountProduct,
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
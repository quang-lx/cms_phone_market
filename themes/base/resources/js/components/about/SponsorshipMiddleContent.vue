<template>
  <section class="w-920 mt-16 pb-32 node5-1">
    <slot v-if="locale === 'en'">
      <section class="w-920 flex wrap-between node5-1 node5-19 bg-2 pb-32">
        <div>
          <h1 class="txt-18-22 color-3 weight-bold pt-16 pl-16 pb-16">Potential Sponsorship</h1>
          <p class="txt-15-24-ror pl-16 pr-16">
            VMA was initially funded by private philanthropic donations by two passionate motor sport supporters. In addition to ongoing private donations, VMA will be supported by sponsorship and donations from several of Vietnam’s largest corporations who understand the social, economic and tourism potential of motorsport for Vietnam
          </p>
        </div>
        <div class="m-auto pt-100">
          <div class="w-800 border-top-5 node5-26">
            <div class="txt-center mt-16">
              <img src="/images/png/logo-vingroup.png" alt="" class="w-577 node5-27">
            </div>
            <p class="txt-15-24-ror mt-16">
              Vingroup is the largest enterprise in Vietnam by market value. In its early days, Vingroup focused on developing hospitality, commercial, and residential properties under the Vinpearl and Vincom brands. Today, Vingroup operate in seven major business segments:property, hospitality and entertainment, consumer retail, industrials, healthcare, education and agriculture.
            </p>
          </div>
        </div>

        <div class="m-auto pt-100">
          <div class="w-800 border-top-5 node5-26">
            <div class="txt-center mt-16">
              <img src="/images/png/logo_vna-mobile.png" alt="" class="w-577 node5-27">
            </div>
            <p class="txt-15-24-ror mt-16">
              Vietnam Airlines is a leading 4 star global airline and Vietnam’s national ﬂag carrier. Over 20 years Vietnam Airlines has become a major competitor in the Asian aviation market, owning a modern ﬂeet of Airbus and Boeing planes ﬂown by well trained professionals
            </p>
          </div>
        </div>

        <div class="m-auto pt-100">
          <div class="w-800 border-top-5 node5-26">
            <div class="txt-center mt-16">
              <img src="/images/png/Techcombank_logo.png" alt="" class="w-577 node5-27">
            </div>
            <p class="txt-15-24-ror mt-16">
              Vietnam Technological and Commercial Joint Stock Bank, commonly known as Techcombank, was founded in 1993 with the capital of VND 20 billion. Since then, Techcombank has evolved to be one of the top joint stock commercial banks in Vietnam with Total Equity of VND 14,986 billion, Total Assets of VND 175,902 billion and a total workforce of 7,242 employees.
            </p>
          </div>
        </div>
      </section>
    </slot>
    <slot v-if="locale === 'vi'">
      <section class="w-920 flex wrap-between node5-1 node5-19 bg-2 pb-32">
        <div>
          <h1 class="txt-18-22 color-3 weight-bold pt-16 pl-16 pb-16">Bảo trợ</h1>
          <p class="txt-15-24-ror pl-16 pr-16">
            VMA ban đầu được tài trợ bởi hai cá nhân đam mê thể thao xe động cơ. Ngoài các khoản đóng góp hiện tại của các cá nhân, VMA sẽ được hỗ trợ bởi sự tài trợ và quyên góp từ một số tập đoàn lớn nhất Việt Nam trên các lĩnh vực xã hội, kinh tế và du lịch tạo tiền đề cho sự phát triển của môn thể thao đua xe tại Việt Nam
          </p>
        </div>


        <div class="m-auto pt-100">
          <div class="w-800 border-top-5 node5-26">
            <div class="txt-center mt-16">
              <img src="/images/png/logo-vingroup.png" alt="" class="w-577 node5-27">
            </div>
            <p class="txt-15-24-ror mt-16">
              Vingroup là doanh nghiệp lớn nhất tại Việt Nam tính theo giá trị thị trường. Trong những ngày đầu, Vingroup tập trung phát triển các bất động sản cho  thương mại và cho dân cư dưới thương hiệu Barbie và Vincom. Ngày nay, Vingroup hoạt động trong bảy mảng kinh doanh chính: bất động sản, khách sạn và giải trí, bán lẻ tiêu dùng, công nghiệp, y tế, giáo dục và nông nghiệp.

            </p>
          </div>
        </div>

        <div class="m-auto pt-100">
          <div class="w-800 border-top-5 node5-26">
            <div class="txt-center mt-16">
              <img src="/images/png/logo_vna-mobile.png" alt="" class="w-577 node5-27">
            </div>
            <p class="txt-15-24-ror mt-16">
              Vietnam Airlines là hãng hàng không quốc tế 4 sao hàng đầu và hãng hàng không quốc gia Việt Nam. Hơn 20 năm hoạt động, Vietnam Airlines đã trở thành hãng hàng không lớn mạnh có năng lực cạnh tranh cao trong thị trường hàng không châu Á, sở hữu một máy bay hiện đại của Airbus và Boeing do các chuyên gia được đào tạo chuyên nghiệp vận hành khai thác.
            </p>
          </div>
        </div>

        <div class="m-auto pt-100">
          <div class="w-800 border-top-5 node5-26">
            <div class="txt-center mt-16">
              <img src="/images/png/Techcombank_logo.png" alt="" class="w-577 node5-27">
            </div>
            <p class="txt-15-24-ror mt-16">
              Ngân hàng TMCP Kỹ thương Việt Nam, thường được gọi là Techcombank, được thành lập năm 1993 với số vốn 20 tỷ đồng. Kể từ đó, Techcombank đã phát triển thành một trong những ngân hàng thương mại cổ phần hàng đầu tại Việt Nam với Tổng vốn chủ sở hữu là 14.986 tỷ đồng, Tổng tài sản là 175.902 tỷ đồng và tổng lực lượng lao động là 7.242 nhân viên..
            </p>
          </div>
        </div>
      </section>
    </slot>
  </section>
</template>
<script>
import request from '../../utils/request'

export default {
  name: 'AboutMiddleContent',
  mixins: [],
  props: {
    pageTitle: {}
  },
  data () {
    return {
      page: 1,
      witness: null,
      locale: window.Vma.locale
    }
  },
  watch: {},
  created () {
    this.fetchEvents()
  },
  mounted () {
  },
  methods: {
    fetchEvents () {
      request.get('/mobility/witness?page=' + this.page + '&per_page=6')
        .then((response) => {
          this.witness = response.data.data
        })
        .catch((error) => {
          throw new Error(JSON.stringify(error))
        })
    },
    imageUrl (value) {
      return `${window.Vma.assetUrl}${value}`
    }
  }
}
</script>

<template>
  <section class="w-920 mt-16 pb-32 node5-1">
    <slot v-if="locale === 'en'">
      <!-- start article list -->
      <section class="w-920 flex wrap-between node5-1 node5-19 bg-2">
        <div>
          <h1 class="txt-18-22 color-3 weight-bold pt-16 pl-16 pb-16">General objectives</h1>
          <p class="txt-15-24-ror pl-16 pr-16">
            Vietnamese Motorsport Association (VMA) has been established as an independent, non-profit organisation committed to the safe and sustainable development, management and promotion of motorsport in Vietnam.
            <br>
            <br>
            In December 2018, VMA has been appointed by The Ministry of Culture, Sports and Tourism as the sole government body to sanction and manage motorsport-related activities in Vietnam. According to that, VMW will work closely with the General Department of Sports and Physical Training and competent government and local body to organize and manage motor racing competitions over the country in compliance with vietnamese and FIA sporting code.
            <br>
            <br>
            VMA strikes to ensure self-sustainability by generating earnings from various sources of revenue such as providing event sanction services, offcials training and licensing, sponsorship etc. to fully fill the role of a motorsport promoter and to commit to social responsibilities
          </p>
        </div>

        <div class="mt-26">
          <h1 class="txt-18-22 color-3 weight-bold pt-16 pl-16 pb-16">Main lines of activities</h1>
          <ul class="txt-15-24-ror pl-16 pr-16">
            <li class="pl-32 pb-8">
              1.	Be responsible for the governance and administration of all major forms of motor sport in the country, controlling the technical and sporting rules across the various disciplines.
            </li>
            <li class="pl-32 pb-8">
              2.	Promote and improve motor sport as a widely recognised, accepted and respected sport, and by valorizing the values and spirit of competitive sport and fairness.
            </li>
            <li class="pl-32 pb-8">
              3.	Develop and maintain safety, technical, judicial, accreditation, training and compliance systems, and other intellectual property necessary for the effective management and development of the sport and for these systems and services to be provided to members, other customers and the community.
            </li>
            <li class="pl-32 pb-8">
              4.	Formulate, issue, interpret, implement, enforce and amend as required the national comprtition rules as necessary for the control and conduct of motor sport in the country.
            </li>
            <li class="pl-32 pb-8">
              5.	Issue competition licences, officials’ licences, track/course licences and competition permits in accordance with the national competition rules ad FIA international sporting code.
            </li>
            <li class="pl-32 pb-8">
              6.	Advance and grow motorsports for Vietnamese participants at all levels.
            </li>
            <li class="pl-32 pb-8">
              7.	Design and promote programmes aimed at safety, training and education in motoring and motor sport.
            </li>
            <li class="pl-32 pb-8">
              8.	Engage with local and regional motorsport stakeholders to advance public interest and involvement in motorsport. Collaborate thourougly with others FIA members in the region and over the world for the development of motorsport.
            </li>
            <li class="pl-32 pb-8">
              9.	Promote and be committed to road safety
            </li>
          </ul>

        </div>

        <div class="mt-26 pb-64">
          <h1 class="txt-18-22 color-3 weight-bold pt-16 pl-16 pb-16">Mission statement</h1>
          <ul class="txt-15-24-ror pl-16 pr-16">
            <li class="pl-32 pb-8">
              -	Recruitment, management and training of officials.
            </li>

            <li class="pl-32 pb-8">
              -	Organisation of Formula 1 Hanoi Grand Prix (VMA with training support of CAMS).

            </li>

            <li class="pl-32 pb-8">
              -	Establishment of licence and membership systems.
            </li>

            <li class="pl-32 pb-8">
              -	Establish appropriate organisational structure and appoint suitably qualified personnel to organize and sanction competitive motor sport activity, including the F1 Hanoi Grand Prix.
            </li>

            <li class="pl-32 pb-8">
              -	Provide high level of training and accreditation to Vietnamese Officials in line with FIA best practices.
            </li>

            <li class="pl-32 pb-8">
              -	Ensure a safe environment for competitors, officials and fans.
            </li>

            <li class="pl-32 pb-8">
              -	Development of other motorsport activities & driver development programs.
            </li>

            <li class="pl-32 pb-8">
              -	Engagement with existing National Motorsport activities (Off Road Cup).
            </li>

            <li class="pl-32 pb-8">
              -	Development of rules, regulations and championship structure.
            </li>

            <li class="pl-32 pb-8">
              -	Development of grassroots development programs including Karting and Cross Kart.
            </li>
            <li class="pl-32 pb-8">
              -	Provision of medical services for motorsport events.
            </li>
            <li class="pl-32 pb-8">
              -	Development of infrastructure and facilities.
            </li>
            <li class="pl-32 pb-8">
              -	Establishment of young driver academy.
            </li>
            <li class="pl-32 pb-8">
              -	Work in collaboration with stakeholders in both public and private sectors and leverage the profile of motorsport to tackle the road safety challenge through advocacy activities
            </li>
          </ul>

        </div>
      </section>
      <!-- end article list -->
    </slot>
    <slot v-if="locale === 'vi'">
      <!-- start article list -->
      <section class="w-920 flex wrap-between node5-1 node5-19 bg-2">
        <div>
          <h1 class="txt-18-22 color-3 weight-bold pt-16 pl-16 pb-16">Tôn chỉ, mục đích hoạt động</h1>
          <p class="txt-15-24-ror pl-16 pr-16">
            Công ty TNHH Hiệp hội Thể thao xe động cơ Việt Nam (VMA) là tổ chức phi lợi nhuận, đại diện cho các tay đua và tổ chức hoạt động trong lĩnh vực thể thao xe cơ giới với mục tiêu thúc đẩy một cách toàn diện sự phát triển của môn thể thao này tại Việt Nam.
            <br>
            <br>
            Tháng 12 năm 2018, VMA đã được Bộ Văn hóa, Thể thao và Du lịch công nhận là tổ chức trong nước duy nhất chịu trách nhiệm quản lí và điều hành các giải đua xe thể thao động cơ trên lãnh thổ Việt Nam. Với vai trò này, VMA sẽ chủ động phối hợp với Tổng cục Thể dục Thể thao và cơ quan chức năng có thẩm quyền địa phương và trên cả nước để tổ chức, điều hành và kiểm duyệt các giải đấu trên toàn quốc theo các quy định của pháp luật Việt Nam và Liên đoàn Thể thao ôtô Thế giới (FIA).
            <br>
            <br>
            VMA hoạt động theo cơ chế tự chủ về tài chính thông qua cung cấp các dịch vụ đào tạo, điều hành giải đua, vận động tài trợ vì sự phát triển bền vững của các môn thể thao đua xe tại Việt Nam và các trách nhiệm xã hội mà VMA theo đuổi.
          </p>
        </div>

        <div class="mt-26">
          <h1 class="txt-18-22 color-3 weight-bold pt-16 pl-16 pb-16">Sứ mệnh</h1>
          <ul class="txt-15-24-ror pl-16 pr-32">
            <li class="pl-32 pb-8">
              1.	Chịu trách nhiệm quản trị và điều hành tất cả các hình thức thi đấu thể thao xe cơ giới trong nước, kiểm soát các quy tắc kỹ thuật và thể thao trong các bộ môn đua xe ở tất cả các cấp độ.
            </li>
            <li class="pl-32 pb-8">
              2.	Thúc đẩy và cải thiện môn thể thao mô-tô như một môn thể thao được công nhận và tôn trọng rộng rãi thông qua đề cao các giá trị và tinh thần của thể thao cạnh tranh và công bằng.
            </li>
            <li class="pl-32 pb-8">
              3.	Phát triển và duy trì các hệ thống an toàn, kỹ thuật, tư pháp, công nhận, đào tạo của FIA và tuân thủ và các tài sản trí tuệ cần thiết khác để quản lý hiệu quả và phát triển môn thể thao đua xe cơ giới tại Việt Nam.
            </li>
            <li class="pl-32 pb-8">
              4.	Cung cấp hệ thống và dịch vụ này cho các thành viên, khách hàng và cộng đồng nhằm đồng bộ hóa chất lượng các giải đấu theo tiêu chuẩn quốc tế.
            </li>
            <li class="pl-32 pb-8">
              5.	Xây dựng, ban hành, giải thích, thực hiện, thi hành và sửa đổi các quy tắc thi đấu theo yêu cầu của các cơ quan quản lí nhà nước trong việc kiểm soát và thực hiện môn thể thao mô tô trong nước.
            </li>
            <li class="pl-32 pb-8">
              6.	Cấp giấy phép thi đấu, giấy phép điều hành, giấy phép đường đua theo quy định của Pháp luật Việt Nam và theo Điều lệ của FIA.
            </li>
            <li class="pl-32 pb-8">
              7.	Phát triển bộ môn đua xe thể thao tốc độ ở Việt Nam thông qua khuyến khích người chơi ở tất cả các trình độ.
            </li>
            <li class="pl-32 pb-8">
              8.	Thiết kế và thúc đẩy các chương trình hướng đến mục đích an toàn, đào tạo và giáo dục về mô tô và thể thao mô tô.
            </li>
            <li class="pl-32 pb-8">
              9.	Phối hợp chặt chẽ với các cơ quan điều hành trong khu vực cũng như trên thế giới trong hệ thống của FIA vì sự phát triển chung của môn thể thao đua xe mô tô trên khắp thế giới.
            </li>
            <li class="pl-32 pb-8">
              10.	Cam kết và thúc đẩy tuyên truyền về an toàn giao thông đường bộ.
            </li>
          </ul>

        </div>

        <div class="mt-26 pb-64">
          <h1 class="txt-18-22 color-3 weight-bold pt-16 pl-16 pb-16">Định hướng hoạt động</h1>
          <ul class="txt-15-24-ror pl-16 pr-16">
            <li class="pl-32 pb-8">
              -	Cung cấp các khóa đào tạo và kiểm định cho các cán bộ điều hành giải đua xe ở Việt Nam theo các tiêu chuẩn đặt ra của FIA.
            </li>
            <li class="pl-32 pb-8">
              -	Thiết lập cơ cấu tổ chức phù hợp và chỉ định nhân sự có trình độ phù hợp để tổ chức và quản lí hoạt động thể thao mô tô động cơ đặc biệt là tại Giải đua xe Công thức 1 tại Hà Nội.
            </li>
            <li class="pl-32 pb-8">
              -	Xây dựng hệ thông cấp giấy phép và hệ thống thành viên.
            </li>
            <li class="pl-32 pb-8">
              -	Thiết kế và xây dựng hệ thống các giải đua xe thể thao trong nước một cách đồng bộ và có tổ chức.
            </li>
            <li class="pl-32 pb-8">
              -	Đảm bảo an toàn cho các tay đua, quan chức và người hâm mộ trong các sự kiện đua xe thể thao mà VMA quản lí.
            </li>
            <li class="pl-32 pb-8">
              -	Cung cấp các dịch vụ y tế và thanh tra y tế cho các giải đua.
            </li>
            <li class="pl-32 pb-8">
              -	Xây dựng đề án thành lập học viện đào tạo cho các tay đua trẻ để ươm mầm và nuôi dưỡng đam mê với môn thể thao này ví sự phổ biến và phát triển của môn thể thao mô-tô tại Việt Nam.
            </li>
            <li class="pl-32 pb-8">
              -	Hợp tác và vận động các bên liên quan trong lĩnh vực đua xe thể thao cấp quốc gia và quốc tế để thúc đẩy sự quan tâm của công chúng và sự tham gia vào môn thể thao đua xe.
            </li>
            <li class="pl-32 pb-8">
              -	Thông qua môn thể thao đua xe để hợp tác với các nhà chức trách trung ương và địa phương, các doanh nghiệp, các hiệp hội có liên quan, các cơ quan truyền thông v.v. để góp phần giải quyết các thách thức liên quan đến vấn nạn an toàn giao thông đường bộ ở Việt Nam và trong khu vực.
            </li>

          </ul>

        </div>
      </section>
      <!-- end article list -->
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

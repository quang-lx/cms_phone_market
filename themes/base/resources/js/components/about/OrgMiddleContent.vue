<template>
  <section class="w-920 mt-16 pb-32 node5-1">
    <slot v-if="locale === 'en'">
      <section class="w-920 flex wrap-between node5-1 node5-19 bg-2">
        <div>
          <h1 class="txt-18-22 color-3 weight-bold pt-16 pl-16 pb-16">Structure and Mission</h1>
          <p class="txt-15-24-ror pl-16 pr-16">
            VMA has been established as an independent, non-proft organisation committed to the safe and sustainable development, management and promotion of motorsport in Vietnam.
            <br>
            <br>
            VMA has the support of the Ministry of Culture, Sports and Tourism; the Ministry of Foreign Aﬀairs; the Ministry of Public Security; the Ministry of Finance the Ministry of Planning and Investment and the General Department of Sports and Physical Training (Ministry of Culture, Sports andTourism) of Vietnam to fulfl this role.
          </p>
        </div>

        <div class="mt-26">
          <h1 class="txt-18-22 color-3 weight-bold pt-16 pl-16 pb-16">Board of Management</h1>
          <p class="txt-15-24-ror pl-16 pr-16">
            VMA has established an interim board of management with signifcant management, commercial and motorsport experience
            <br>
            <br>
            The Board’s immediate priority is to guide VMA through the establishment phase by appointing suitable personnel to key roles, developing a strategic plan for development of motorsport in Vietnam and building VMA’s capacity through training and partnerships in preparation for the 2020 Vietnam Formula 1 Grand Prix.
            <br>
            <br>
            The VMA board of management including:
            <br>
            <br>
         </p>

        </div>

        <div class="mt-26 pb-64">
          <div class="node5-20">
            <div class="pl-128 node5-24">
              <h1 class="txt-18-22 color-3 weight-bold">Nguyen Thi Quy Phuong, Ph.D</h1>
              <h3 class="txt-15-24-ror color-x">President</h3>
            </div>


            <div class="txt-15-24-ror pl-16 pr-16 node5-22 mt-16">
              <div class="w-308 h-431 bg-cover lazy node5-21" data-src="/images/png/girl.png"></div>
              <p class="node5-22">
                Nguyen Thi Quy Phuong earned her Ph.D degree in Information and Communication Sciences at PanthéonAssas University, France. From 2008 – 2009, Nguyen Thi Quy Phuong founded “Tinh Hoa” Magazine and served as the Managing Editor. She is also the former Deputy Director of the Institute for Journalism and Communication Studies.
              <br>
              <br>
                Prior to the experiences in Communication, she served as the Secretary of Youth Union of the Centrally Ofces and Ministries (with 125,000 union members, 56 unions of Ministries, Branches, Party, State, National Assembly and the Government); former Standing Member of Central Committee of Ho Chi Minh Communist Youth Union (holding more than 4 million members).
              <br>
              <br>
                From 2016 to 2018, she served as the President of VinTalent (Development Fund for Vietnam Talents) of Vingroup Corporation along with the Chief Executive Ofcer position of Promotion Fund of Vietnamese Football Talents (PVF).
              </p>
            </div>


          </div>

          <div class="node5-20 node5-23">
            <div class="pl-128 node5-24">
              <h1 class="txt-18-22 color-3 weight-bold">Ngo Viet Dung</h1>
              <h3 class="txt-15-24-ror color-x">Vice-President</h3>
            </div>

            <div class="txt-15-24-ror pl-16 pr-16 node5-22 mt-16">
              <div class="w-308 h-431 bg-cover lazy node5-21" data-src="/images/png/boy.png"></div>
              <p class="node5-22">
                Ngo Viet Dung graduated from Hanoi University of Science & Technology. Since 1990, he has been involved in founding his family’s factory with the brand HD Antenna. In 1999, Mr. Dung established Gia Viet Dung Co., Ltd., which is the business of distributing electrical appliances. From 2008 until present, he is a founding shareholder and Chairman of OTV Joint Stock   Company - OTOFUN.NET Company. He is also a founding member and administrator of OTOFUN forum since 2006.
                <br>
                <br>
                With the passion for car and technology, Ngo Viet Dung – Nick name “NAKIO” is one of the frst oﬀ-road players in Vietnam. He is the initiator of Vietnam’s top-of-the-range automotive with the launch of the Vietnam Oﬀ-road Cup (VOC) and is also the frst to host a racing team representing Vietnam in the Rainforest Challenge (RFC) in Malaysia in 2010. From 2008 to 2009, Ngo Viet Dung served as the Head of the Organizing Committee of the Golden Lion – the forerunner of the Vietnam Oﬀ-road Cup. He has 10 years of management experience in organizing racing tournaments while serving as the Head, Deputy Director of the Vietnam Oﬀroad Cup from 2010 to 2018.
              </p>
            </div>
          </div>

          <ul class="pl-128 mt-100 txt-15-24-ror node5-25">
            <li>
              -	<span class="weight-bold">Dr. Renaud Guiu</span> – Member
            </li>
            <li class="pt-8">
              -	<span class="weight-bold">Mr. Ngo Viet Hung</span> – Member (Event Management)

            </li>
            <li class="pt-8">
              -	<span class="weight-bold">Mr. Rich Pham</span> –  Member (Training and Licensing)

            </li>
            <li class="pt-8">
              -	<span class="weight-bold">Mr. Nguyen Quoc Bao</span> – Member (Circuit Commission)

            </li>
            <li class="pt-8">
              -	<span class="weight-bold">Mrs. Đang Thy Huong</span> – Member (Membership and Social Activities)

            </li>
            <li class="pt-8">
              -	<span class="weight-bold">Mrs. Le Thi Thanh Tu</span> – Member (Communication and International Relations)

            </li>
            <li class="pt-8">
              -	<span class="weight-bold">Mrs. Đo Huong Lan</span> – Member (Markerting and Commercial Affairs)

            </li>
            <li class="pt-8">
              -	<span class="weight-bold">Mrs. Chu Thanh Thanh An</span> – Member (Administration)
            </li>
          </ul>
        </div>
      </section>
    </slot>
    <slot v-if="locale === 'vi'">
      <section class="w-920 flex wrap-between node5-1 node5-19 bg-2">
        <div>
          <h1 class="txt-18-22 color-3 weight-bold pt-16 pl-16 pb-16">Cơ cấu và sứ mệnh</h1>
          <p class="txt-15-24-ror pl-16 pr-16">
            VMA được thành lập như một tổ chức phi lợi nhuận độc lập, cam kết cho sự phát triển, quản lý và thúc đẩy an toàn và bền vững của môn thể thao đua xe tại Việt Nam.
            <br>
            <br>
            VMA có sự hỗ trợ của Bộ Văn hóa, Thể thao và Du lịch; Bộ Ngoại giao; Bộ Công an; Bộ Tài chính Bộ Kế hoạch và Đầu tư và Tổng cục Thể dục Thể thao (Bộ Văn hóa, Thể thao và Du lịch) của Việt Nam để hoàn thành vai trò này.
          </p>
        </div>

        <div class="mt-26">
          <h1 class="txt-18-22 color-3 weight-bold pt-16 pl-16 pb-16">Ban điều hành</h1>
          <p class="txt-15-24-ror pl-16 pr-16">
            VMA đã thành lập một ban quản lý tạm thời với những người có kinh nghiệm trong lĩnh vực quản lý, thương mại và đua xe.
            <br>
            <br>
            Việc ưu tiên trước mắt của Ban điều hành trong giai đoạn đầu thành lập VMA là bổ nhiệm nhân sự phù hợp vào vai trò chủ chốt, xây dựng kế hoạch chiến lược phát triển môn thể thao đua xe tại Việt Nam và xây dựng năng lực VMA thông qua đào tạo và hợp tác để chuẩn bị cho Giải đua Công thức 1 Việt Nam 2020
            <br>
            <br>
            Nhân sự nằm trong Ban điều hành của VMA bao gồm:
            <br>
            <br>
           </p>

        </div>

        <div class="mt-26 pb-64">
          <div class="node5-20">
            <div class="pl-128 node5-24">
              <h1 class="txt-18-22 color-3 weight-bold">Tiến sĩ Nguyễn Thị Quý Phương</h1>
              <h3 class="txt-15-24-ror color-x">Chủ tịch</h3>
            </div>


            <div class="txt-15-24-ror pl-16 pr-16 node5-22 mt-16">
              <div class="w-308 h-431 bg-cover lazy node5-21" data-src="/images/png/girl.png"></div>
              <p class="node5-22">
                Bà Nguyễn Thị Quý Phương nhận bằng tiến sĩ về Khoa học Thông tin và Truyền thông tại Đại học PanthéonAssas, Pháp. Từ 2008 - 2009, Nguyễn Thị Quý Phương thành lập Tạp chí Tinh Tinh Hoa và làm Tổng biên tập. Bà cũng là cựu Phó Viện trưởng Viện Nghiên cứu Báo chí và Truyền thông
              <br>
              <br>
                Trước khi hoạt động trong lĩnh vực truyền thông, bà từng là Bí thư Đoàn Thanh niên Trung ương (với 125.000 đoàn viên, 56 đoàn thể, Bộ, Đảng, Nhà nước, Quốc hội và Chính phủ); nguyên Ủy viên Ban Chấp hành Trung ương Đoàn Thanh niên Cộng sản Hồ Chí Minh (nắm giữ hơn 4 triệu đảng viên)
              <br>
              <br>
                Từ năm 2016 đến 2018, bà là Chủ tịch của VinTalent (Quỹ phát triển tài năng Việt Nam) của Tập đoàn Vingroup cùng với vị trí Giám đốc điều hành của Quỹ tài năng bóng đá trẻ Việt Nam (PVF).
              </p>
            </div>
          </div>

          <div class="node5-20 node5-23">
            <div class="pl-128 node5-24">
              <h1 class="txt-18-22 color-3 weight-bold">Ông Ngô Việt Dũng</h1>
              <h3 class="txt-15-24-ror color-x">Phó Chủ Tịch</h3>
            </div>

            <div class="txt-15-24-ror pl-16 pr-16 node5-22 mt-16">
              <div class="w-308 h-431 bg-cover lazy node5-21" data-src="/images/png/boy.png"></div>
              <p class="node5-22">
                Ông Ngô Việt Dũng tốt nghiệp Đại học Khoa học & Công nghệ Hà Nội. Từ năm 1990, ông đã thành lập nhà máy gia đình của với nhãn hiệu Ăng ten HD. Năm 1999, ông Dũng thành lập Công ty TNHH Gia Việt Dũng, chuyên kinh doanh phân phối thiết bị điện. Từ năm 2008 đến nay, ông là cổ đông sáng lập và Chủ tịch của Công ty Cổ phần OTV - Công ty OTOFUN.NET. Ông cũng là thành viên sáng lập và quản trị viên của diễn đàn OTOFUN từ năm 2006
              <br>
              <br>
                Với niềm đam mê về xe hơi và công nghệ, ông Ngô Việt Dũng - Nick name NAKIO là một trong những người chơi xe offroad đầu tiên tại Việt Nam. Ông là người khởi xướng hàng đầu của Việt Nam với việc ra mắt Việt Nam Offroad Cup (VOC) và cũng là người đầu tiên tổ chức một đội đua đại diện cho Việt Nam tham gia Thử thách rừng nhiệt đới (RFC) tại Malaysia năm 2010. Từ năm 2008 đến 2009, Ngô Việt Dũng giữ chức Trưởng ban tổ chức giải đua Sư tử vàng - tiền thân của Việt Nam Offroad Cup. Ông có 10 năm kinh nghiệm quản lý trong việc tổ chức các giải đấu đua xe khi giữ chức vụ Trưởng ban, Phó Ban Việt Nam Offroad Cup từ 2010 đến 2018
              </p>
            </div>
          </div>

          <ul class="pl-128 mt-50 txt-15-24-ror node5-25">
            <li>
              -	<span class="weight-bold">Ts. Renaud Guiu</span> – Thành viên
            </li>
            <li class="pt-8">
              -	<span class="weight-bold">Ông. Ngô Việt Hưng </span> -  Thành viên (Quản lý sự kiện)

            </li>
            <li class="pt-8">
              -	<span class="weight-bold">Ông. Rich Phạm </span> –  Thành viên (Đào tạo và cấp bằng đào tạo)

            </li>
            <li class="pt-8">
              -	<span class="weight-bold">Ông. Nguyễn Quốc Bảo</span> – Thành viên (Vận hành đường đua)

            </li>
            <li class="pt-8">
              -	<span class="weight-bold">Bà. Đặng Thúy Hường </span> – Thành viên(Thành viên và hoạt động xã hội)

            </li>
            <li class="pt-8">
              -	<span class="weight-bold">Bà. Lê Thị Thanh Tú </span> – Thành viên (Truyền thông và quan hệ quốc tế)

            </li>
            <li class="pt-8">
              -	<span class="weight-bold">Bà. Đỗ Hương Lan </span> – Thành viên (Marketing và thương mại)

            </li>
            <li class="pt-8">
              -	<span class="weight-bold">Bà. Chu Thanh Thanh An </span> – Bà. Chu Thanh Thanh An
            </li>
          </ul>
        </div>
      </section>
    </slot>
  </section>
</template>
<script>
import request from '../../utils/request'

export default {
  name: 'OrgMiddleContent',
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

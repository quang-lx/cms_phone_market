<template>
  <section class="w-920 mt1-16 pb-32 node5-1">
    <slot v-if="locale === 'en'">
      <div class="h-512 bg-cover lazy node5-6" data-src="https://i.ibb.co/BwQ7Sk0/image.png"/>

      <div class="flex row between-start bg-2 ">
        <div>
          <div class="flex row start-center mt-32">
            <div class="w-12 h-24 lazy no-repeat" data-src="https://i.ibb.co/f9gLscs/Intersect.png"/>
            <div class="pl-20">
              <h1 class="color-3 txt-24-32 weight-bold">
                THE CHALLENGE
              </h1>
            </div>
          </div>
          <div class="pl-32 mt-16 pr-4">
            <p class="txt-15-24-ror color-3">
              Every day, 3,500 people are killed on the roads. Young people are particularly affected as
              road crashes are the number one cause of death of 15 to 29 year olds.
              <br>
              <br>
              This is an alarming trend, a plague that needs to be stopped, a human, economic and social
              cost which has become unacceptable. The FIA is on the front line in tackling this challenge,
              together with our 245 member organisations around the world.
              <br>
              <br>
              Everyone has a role to play in making safer roads for all. The #3500LIVES campaign outlines
              Golden Rules that can help save your life and the lives of others. We also encourage you to
              sign up to the FIA Manifesto for Global Road Safety which calls on all governments to
              prioritise road safety and introduce effective legislation on key risk factors on the road.
            </p>
          </div>
        </div>
      </div>

      <div class="flex row between-start bg-2 ">
        <div>
          <div class="flex row start-center mt-32">
            <div
              class="w-12 h-24 lazy no-repeat"
              data-src="https://i.ibb.co/f9gLscs/Intersect.png"
            />
            <div class="pl-20">
              <h1 class="color-3 txt-24-32 weight-bold">
                MANIFESTO AT GLANCE
              </h1>
            </div>
          </div>
          <div class="pl-32 mt-16 pr-4">
            <p class="txt-15-24-ror color-3">
              1. Ambitious targets
              <br>
              2. Better infrastructure
              <br>
              3. Safer vehicles
              <br>
              4. More effective laws
              <br>
              5. Tackle drink-driving
              <br>
              6. Improved data collection
              <br>
              7. Global fund for safer roads
              <br>
              8. Private sector support
              <br>
              9. Increase coordinated action
            </p>
          </div>
        </div>
      </div>

      <div class="row flex center-center pt-16 bg-2 pb-32">
        <div class="w-856 border-8 shadow-1 node5-7">
          <div class="h-56 col flex center-center">
            <h1 class="txt-24-32 color-3">
              THE GOLDEN RULES
            </h1>
          </div>
          <div class="flex wrap-between pl-16 pr-16 pb-8 node5-8">
            <div class="w-269 h-80 bg-6 flex row start-center  mb-8 node5-9">
              <div
                v-lazy:background-image="imageUrl('/images/png/mobi-1.png')"
                class="ml-8 box-64 circle lazy bg-cover node5-10 border-box"
              />
              <div class="w-205 node5-12">
                <h1 class="pl-16 color-3 txt-16-24 node5-11">
                  SLOW DOWN FOR KIDS
                </h1>
              </div>
            </div>
            <div class="w-269 h-80 bg-6 flex row start-center  mb-8 node5-9">
              <div
                v-lazy:background-image="imageUrl('/images/png/mobi-2.png')"
                class="ml-8 box-64 circle lazy bg-cover node5-10 border-box"
              />
              <div class="w-205 node5-12">
                <h1 class="pl-16 color-3 txt-16-24 node5-11">
                  OBEY THE SPEED LIMIT
                </h1>
              </div>
            </div>
            <div class="w-269 h-80 bg-6 flex row start-center  mb-8 node5-9">
              <div
                v-lazy:background-image="imageUrl('/images/png/mobi-3.png')"
                class="ml-8 box-64 circle lazy bg-cover node5-10 border-box"
              />
              <div class="w-205 node5-12">
                <h1 class="pl-16 color-3 txt-16-24 node5-11">
                  CHECK YOUR TYRES
                </h1>
              </div>
            </div>
            <div class="w-269 h-80 bg-6 flex row start-center  mb-8 node5-9">
              <div
                v-lazy:background-image="imageUrl('/images/png/mobi-4.png')"
                class="ml-8 box-64 circle lazy bg-cover node5-10 border-box"
              />
              <div class="w-205 node5-12">
                <h1 class="pl-16 color-3 txt-16-24 node5-11">
                  USE YOUR CHILD SAFETY SEAT
                </h1>
              </div>
            </div>
            <div class="w-269 h-80 bg-6 flex row start-center  mb-8 node5-9">
              <div
                v-lazy:background-image="imageUrl('/images/png/mobi-5.png')"
                class="ml-8 box-64 circle lazy bg-cover node5-10 border-box"
              />
              <div class="w-205 node5-12">
                <h1 class="pl-16 color-3 txt-16-24 node5-11">
                  DON'T TEXT AND DRIVE
                </h1>
              </div>
            </div>
            <div class="w-269 h-80 bg-6 flex row start-center  mb-8 node5-9">
              <div
                v-lazy:background-image="imageUrl('/images/png/mobi-6.png')"
                class="ml-8 box-64 circle lazy bg-cover node5-10 border-box"
              />
              <div class="w-205 node5-12">
                <h1 class="pl-16 color-3 txt-16-24 node5-11">
                  BUCKLE UP
                </h1>
              </div>
            </div>
            <div class="w-269 h-80 bg-6 flex row start-center  mb-8 node5-9">
              <div
                v-lazy:background-image="imageUrl('/images/png/mobi-7.png')"
                class="ml-8 box-64 circle lazy bg-cover node5-10 border-box"
              />
              <div class="w-205 node5-12">
                <h1 class="pl-16 color-3 txt-16-24 node5-11">
                  NEVER DRINK AND DRIVE
                </h1>
              </div>
            </div>
            <div class="w-269 h-80 bg-6 flex row start-center  mb-8 node5-9">
              <div
                v-lazy:background-image="imageUrl('/images/png/mobi-8.png')"
                class="ml-8 box-64 circle lazy bg-cover node5-10 border-box"
              />
              <div class="w-205 node5-12">
                <h1 class="pl-16 color-3 txt-16-24 node5-11">
                  ALWAYS PAY ATTENTION
                </h1>
              </div>
            </div>
            <div class="w-269 h-80 bg-6 flex row start-center  mb-8 node5-9">
              <div
                v-lazy:background-image="imageUrl('/images/png/mobi-9.png')"
                class="ml-8 box-64 circle lazy bg-cover node5-10 border-box"
              />
              <div class="w-205 node5-12">
                <h1 class="pl-16 color-3 txt-16-24 node5-11">
                  WEAR A HELMET
                </h1>
              </div>
            </div>
            <div class="w-269 h-80 bg-6 flex row start-center  mb-8 node5-9">
              <div
                v-lazy:background-image="imageUrl('/images/png/mobi-10.png')"
                class="ml-8 box-64 circle lazy bg-cover node5-10 border-box"
              />
              <div class="w-205 node5-12">
                <h1 class="pl-16 color-3 txt-16-24 node5-11">
                  STAY BRIGHT
                </h1>
              </div>
            </div>
            <div class="w-269 h-80 bg-6 flex row start-center  mb-8 node5-9">
              <div
                v-lazy:background-image="imageUrl('/images/png/mobi-11.png')"
                class="ml-8 box-64 circle lazy bg-cover node5-10 border-box"
              />
              <div class="w-205 node5-12">
                <h1 class="pl-16 color-3 txt-16-24 node5-11">
                  CHECK YOUR VISION
                </h1>
              </div>
            </div>
            <div class="w-269 h-80 bg-6 flex row start-center  mb-8 node5-9">
              <div
                v-lazy:background-image="imageUrl('/images/png/mobi-12.png')"
                class="ml-8 box-64 circle lazy bg-cover node5-10 border-box"
              />
              <div class="w-205 node5-12">
                <h1 class="pl-16 color-3 txt-16-24 node5-11">
                  STOP WHEN YOU'RE TIRED
                </h1>
              </div>
            </div>
          </div>
        </div>
      </div>
    </slot>
    <slot v-if="locale === 'vi'">
      <div class="flex row between-start bg-2 ">
        <div>
          <div class="flex row start-center mt-16">
            <div
              class="w-12 h-24 lazy no-repeat"
              data-src="https://i.ibb.co/f9gLscs/Intersect.png"
            />
            <div class="pl-20">
              <h1 class="color-3 txt-24-32 weight-bold">
                CHIẾN DỊCH "#3500 LIVES"
              </h1>
            </div>
          </div>
          <div class="pl-32 mt-16 pr-4">
            <p class="txt-15-24-ror color-3">
              Theo ước tính, có tới 3500 người thiệt mạng mỗi ngày trên thế giới vì tai nạn giao thông.
              Đáng báo động hơn, đây cũng là nguyên nhân hàng đầu gây tử vong cho thanh thiếu niên trong
              độ tuối từ 15 đến 29. Chính vì vậy, năm 2015, lần đầu tiên an toàn đường bộ được đưa vào một
              trong những Mục tiêu Phát triển bền vững của Liên Hợp Quốc (LHQ) với tham vọng giảm một nửa
              số ca tử vong từ nay cho đến năm 2020.
              <br>
              <br>
              Chiến dịch "#3500 Lives" được phát động vào ngày 10/03/2017 tại Paris, là một phần trong cam
              kết của Liên đoàn ôtô thế giới (FIA) nhằm hiện thực hóa mục tiêu mà LHQ đề ra, qua đó góp
              phần tạo nên một “môi trường giao thông” an toàn và văn minh cho công dân toàn cầu, đặc biệt
              là tại các quốc gia đang phát triển. Với quy mô toàn cầu do FIA phát động nhằm nâng cao nhận
              thức của công dân trên thế giới về tầm quan trọng của an toàn giao thông đường bộ. Chiến
              dịch cũng khuyến khích các Chính phủ gia tăng cam kết trong việc đảm bảo an toàn cơ sở hạ
              tầng giao thông cho người sử dụng cũng như tích cực tìm kiếm và áp dụng các giải pháp để đối
              phó với thách thức trong vấn đề an toàn đường bộ.
              <br>
              <br>
              Tính cho đến cuối năm 2017, chiến dịch đã lan tỏa ra hơn 900 thành phố tại gần 80 quốc gia
              và trở thành chiến dịch vận động an toàn đường bộ lớn nhất trong lịch sử. 15 đại sứ toàn cầu
              là những VĐV thể thao chuyên nghiệp, chính trị gia và người nổi tiếng trong nhiều lĩnh vực
              như thị trưởng thành phố Paris Anne Hidalgo, tay đua công thức 1 Fernando Alonso, cầu thủ
              bóng đá Antoine Griezmann v.v đã tích cực truyền đi những thông điệp về lái xe an toàn thông
              qua 12 quy tắc vàng (Golden Rules) mà FIA đặt ra và mong muốn phổ biến rộng rãi trên toàn
              cầu.
            </p>
          </div>
        </div>
      </div>

      <div class="flex row between-start bg-2 ">
        <div>
          <div class="flex row start-center mt-32">
            <div
              class="w-12 h-24 lazy no-repeat"
              data-src="https://i.ibb.co/f9gLscs/Intersect.png"
            />
            <div class="pl-20">
              <h1 class="color-3 txt-24-32 weight-bold">
                CHIẾN DỊCH "#3500 LIVES" TẠI VIỆT NAM
              </h1>
              <p class="txt-15-24-ror color-3 color-5">
                Giao thông an toàn, cứu ngàn sinh mạng
              </p>
            </div>
          </div>
          <div class="pl-32 mt-16 pr-4">
            <p class="txt-15-24-ror color-3">
              Tại Việt Nam, vấn đề an toàn giao thông là một trong những vấn đề gây nhức nhối hàng đầu và
              luôn là bài toán hóc búa chưa có lời giải cho các nhà chức trách.
              <br>
              <br>
              Theo thống kê từ Ủy ban An toàn Giao thông Quốc gia Việt Nam, trong năm 2017, cả nước xảy ra
              hơn 20.000 vụ tai nạn làm hơn 8000 người chết và 17.000 người bị thương. Tuy nhiên đây chỉ
              là ghi nhận tại hiện trường của cảnh sát giao thông. Trên thực tế, con số tử vong ghi nhận
              tại các bệnh viện do tai nạn giao thông còn lớn hơn rất nhiều. Cụ thể, trong năm 2015, tổng
              số người thiệt mạng vì tai nạn giao thông lên tới 15.386 người, cao gần gấp đôi con số thu
              thập tại hiện trường (8670 người).
              <br>
              <br>
              Chiến dịch #3500lives Việt Nam được đồng thực hiện bởi VMA và Câu lạc bộ xe hơi Việt Nam
              (AAV), dưới sự hướng dẫn của Ủy ban An toàn Giao Thông Quôc gia, Tổng cục Thể dục Thể thao
              Việt Nam và Sở Văn hóa, Thể thao và Du lịch Hà Nội. Hội Sinh viên Việt Nam, Hội Liên hiệp
              Thanh niên Việt Nam, Hội sinh viên và Đoàn thanh niên Học viện Ngoại giao là những đơn vị
              phối hợp thực hiện chiến dịch #3500lives 2019.
              <br>
              <br>
              Kế thừa tinh thần và sứ mệnh của chiến dịch #3500 lives toàn cầu , chiến dịch tại Việt Nam
              sẽ phổ biến và khuyến khích cộng đồng tuân theo 12 QUY TẮC VÀNG của FIA khi tham gia giao
              thông qua đó góp phần nâng cao ý thức chấp hành luật giao thông đường bộ. Việc Việt Nam đăng
              cai giải đua F1 Hanoi Grand Prix 2020, cùng với sự ủng hộ từ FIA là cơ hội vàng để thực hiện
              chiến dịch này trong nỗ lực cải thiện tình trạng giao thông đường bộ ở Việt Nam. Đây sẽ vừa
              là động lực vừa là mục tiêu tạo nên một hệ thống giao thông hiện đại, văn minh, hiệu quả,
              hướng tới một môi trường giao thông an toàn, nhân ái, thân thiện . Chiến dịch không chỉ là
              một bước đi cần thiết nhằm giảm thiểu thương vong do tai nạn giao thông, mà còn thể hiện cam
              kết mạnh mẽ của Chính phủ cũng như công dân Việt Nam trước cộng đồng quốc tế trong tiến
              trình quảng bá với thế giới hình ảnh về một Việt Nam “an toàn, thân thiện, văn minh”.
            </p>
          </div>
        </div>
      </div>

      <div class="row flex center-center pt-16 bg-2 pb-32">
        <div class="w-856 border-8 shadow-1 node5-7">
          <div class="h-56 col flex center-center">
            <h1 class="txt-24-32 color-3">
              CÁC QUY TẮC VÀNG
            </h1>
          </div>
          <div class="flex wrap-between pl-16 pr-16 pb-8 node5-8">
            <div class="w-269 h-80 bg-6 flex row start-center  mb-8 node5-9">
              <div
                v-lazy:background-image="imageUrl('/images/png/mobi-1.png')"
                class="ml-8 box-64 circle lazy bg-cover node5-10 border-box"
              />
              <div class="w-205 node5-12">
                <h1 class="pl-16 color-3 txt-16-24 node5-11">
                  Đi chậm khi có trẻ em
                </h1>
              </div>
            </div>
            <div class="w-269 h-80 bg-6 flex row start-center  mb-8 node5-9">
              <div
                v-lazy:background-image="imageUrl('/images/png/mobi-2.png')"
                class="ml-8 box-64 circle lazy bg-cover node5-10 border-box"
              />
              <div class="w-205 node5-12">
                <h1 class="pl-16 color-3 txt-16-24 node5-11">
                  Dùng ghế ngồi an toàn cho trẻ em
                </h1>
              </div>
            </div>
            <div class="w-269 h-80 bg-6 flex row start-center  mb-8 node5-9">
              <div
                v-lazy:background-image="imageUrl('/images/png/mobi-3.png')"
                class="ml-8 box-64 circle lazy bg-cover node5-10 border-box"
              />
              <div class="w-205 node5-12">
                <h1 class="pl-16 color-3 txt-16-24 node5-11">
                  Thắt dây an toàn
                </h1>
              </div>
            </div>
            <div class="w-269 h-80 bg-6 flex row start-center  mb-8 node5-9">
              <div
                v-lazy:background-image="imageUrl('/images/png/mobi-4.png')"
                class="ml-8 box-64 circle lazy bg-cover node5-10 border-box"
              />
              <div class="w-205 node5-12">
                <h1 class="pl-16 color-3 txt-16-24 node5-11">
                  Không lái xe sau khi sử dụng đồ uống có
                  cồn
                </h1>
              </div>
            </div>
            <div class="w-269 h-80 bg-6 flex row start-center  mb-8 node5-9">
              <div
                v-lazy:background-image="imageUrl('/images/png/mobi-5.png')"
                class="ml-8 box-64 circle lazy bg-cover node5-10 border-box"
              />
              <div class="w-205 node5-12">
                <h1 class="pl-16 color-3 txt-16-24 node5-11">
                  Mặc quần áo dễ quan sát
                </h1>
              </div>
            </div>
            <div class="w-269 h-80 bg-6 flex row start-center  mb-8 node5-9">
              <div
                v-lazy:background-image="imageUrl('/images/png/mobi-6.png')"
                class="ml-8 box-64 circle lazy bg-cover node5-10 border-box"
              />
              <div class="w-205 node5-12">
                <h1 class="pl-16 color-3 txt-16-24 node5-11">
                  Dừng lại khi mệt mỏi
                </h1>
              </div>
            </div>
            <div class="w-269 h-80 bg-6 flex row start-center  mb-8 node5-9">
              <div
                v-lazy:background-image="imageUrl('/images/png/mobi-7.png')"
                class="ml-8 box-64 circle lazy bg-cover node5-10 border-box"
              />
              <div class="w-205 node5-12">
                <h1 class="pl-16 color-3 txt-16-24 node5-11">
                  Tuân thủ tốc độ cho phép
                </h1>
              </div>
            </div>
            <div class="w-269 h-80 bg-6 flex row start-center  mb-8 node5-9">
              <div
                v-lazy:background-image="imageUrl('/images/png/mobi-8.png')"
                class="ml-8 box-64 circle lazy bg-cover node5-10 border-box"
              />
              <div class="w-205 node5-12">
                <h1 class="pl-16 color-3 txt-16-24 node5-11">
                  Kiểm tra lốp xe
                </h1>
              </div>
            </div>
            <div class="w-269 h-80 bg-6 flex row start-center  mb-8 node5-9">
              <div
                v-lazy:background-image="imageUrl('/images/png/mobi-9.png')"
                class="ml-8 box-64 circle lazy bg-cover node5-10 border-box"
              />
              <div class="w-205 node5-12">
                <h1 class="pl-16 color-3 txt-16-24 node5-11">
                  Không sử dụng điện thoại khi lái xe
                </h1>
              </div>
            </div>
            <div class="w-269 h-80 bg-6 flex row start-center  mb-8 node5-9">
              <div
                v-lazy:background-image="imageUrl('/images/png/mobi-10.png')"
                class="ml-8 box-64 circle lazy bg-cover node5-10 border-box"
              />
              <div class="w-205 node5-12">
                <h1 class="pl-16 color-3 txt-16-24 node5-11">
                  Luôn chú ý quan sát
                </h1>
              </div>
            </div>
            <div class="w-269 h-80 bg-6 flex row start-center  mb-8 node5-9">
              <div
                v-lazy:background-image="imageUrl('/images/png/mobi-11.png')"
                class="ml-8 box-64 circle lazy bg-cover node5-10 border-box"
              />
              <div class="w-205 node5-12">
                <h1 class="pl-16 color-3 txt-16-24 node5-11">
                  Đội mũ bảo hiểm
                </h1>
              </div>
            </div>
            <div class="w-269 h-80 bg-6 flex row start-center  mb-8 node5-9">
              <div
                v-lazy:background-image="imageUrl('/images/png/mobi-12.png')"
                class="ml-8 box-64 circle lazy bg-cover node5-10 border-box"
              />
              <div class="w-205 node5-12">
                <h1 class="pl-16 color-3 txt-16-24 node5-11">
                  Kiểm tra thị lực của bạn
                </h1>
              </div>
            </div>
          </div>
        </div>
      </div>
    </slot>
    <div class="mt-16 bg-2">
      <div class="flex row start-center pt-24 pb-24">
        <div
          class="w-12 h-24 lazy no-repeat"
          style="background-image: url('https://i.ibb.co/f9gLscs/Intersect.png');"
        />
        <div class="pl-20">
          <h1 class="color-3 txt-24-32 weight-bold">
            Witness
          </h1>
        </div>
      </div>

      <div class="flex wrap-between pl-32 pr-32 node5-15">
        <div
          v-for="item in witness"
          :key="item.id"
          class="w-420 node5-13 pb-16"
        >
          <div
            v-lazy:background-image="item.normal_image"
            class="h-172 node5-14 bg-cover lazy border-8"
          />
          <div class="bg-6 pt-8 pb-8 pl-16 pr-16">
            <h1 class="color-3 txt-18-30 weight-bold">
              {{ item.title }}
            </h1>
            <p class="pt-4 color-3 txt-14-22">
              {{ item.short_description }}
            </p>
            <p class="mt-4 color-7 txt-14-22 weight-normal">
              {{ item.released_at }}
            </p>
          </div>
        </div>
      </div>

      <ul class="flex row center-center  pb-16 mt-16">
        <li class="box-32 border-8 flex col center-center txt-14-22 color-3 weight-bold ml-4 mr-4" @click="queryServer(prevPage)">
          <div class="spirit-left" />
        </li>
        <li v-if="fromPage > 2" class="box-32 flex col center-center txt-14-22 color-3 weight-bold ml-4 mr-4">
          <div class="spirit-more" />
        </li>
        <li
          v-for="(p,index) in pageRanges"
          :key="index"
          :class="p === meta.current_page ? 'box-32 bg-5 white border-8 flex col center-center txt-14-22 color-3 weight-bold ml-4 mr-4' : 'box-32 white border-8 flex col center-center txt-14-22 color-3 weight-bold ml-4 mr-4'"
          @click="queryServer(p)"
        >
          <span>{{ p }}</span>
        </li>
        <li v-if="meta.last_page > meta.current_page + 2" class="box-32 flex col center-center txt-14-22 color-3 weight-bold ml-4 mr-4">
          <div class="spirit-more" />
        </li>
        <li class="box-32 border-8 flex col center-center txt-14-22 color-3 weight-bold ml-4 mr-4" @click="queryServer(nextPage)">
          <div class="spirit-right" />
        </li>
      </ul>
    </div>
  </section>
</template>
<script>
import request from '../../utils/request'

export default {
  name: 'MobilityMiddleContent',
  mixins: [],
  props: {
    pageTitle: {}
  },
  data () {
    return {
      page: 1,
      witness: null,
      locale: window.Vma.locale,
      meta: {
        current_page: 1,
        last_page: 1
      }
    }
  },
  computed: {
    fromPage () {
      let page = this.meta.current_page - 2
      if (page < 1) {
        page = 1
      }
      return page
    },
    toPage () {
      let page = this.meta.current_page + 2
      if (page > this.meta.last_page) {
        page = this.meta.last_page
      }
      return page
    },
    pageRanges () {
      if (this.fromPage === this.toPage) {
        return [1]
      }
      return _.range(this.fromPage, this.toPage)
    },
    prevPage() {
      let page = this.meta.current_page - 1
      if (page < 1) {
        page = 1
      }
      return page
    },
    nextPage () {
      let page = this.meta.current_page + 1
      if (page > this.meta.last_page) {
        page = this.meta.last_page
      }
      return page
    }
  },
  watch: {},
  created () {
    this.fetchWitnesses()
  },
  mounted () {
  },
  methods: {
    queryServer (page) {
      this.page = page
      this.fetchWitnesses()
    },
    fetchWitnesses () {
      let $self = this
      request.get('/mobility/witness?page=' + this.page + '&per_page=8')
        .then((response) => {
          console.log(response)
          $self.witness = response.data.data
          $self.meta.current_page = response.data.meta.current_page
          $self.meta.last_page = response.data.meta.last_page
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

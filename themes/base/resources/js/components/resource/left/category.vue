<template>
    <div class="bg-2 pl-16 pr-16 pt-14 pb-16">
        <h1 class="txt-18-22 weight-bold color-3"> {{ trans('base.Categories') }}</h1>



        <div class="h-40 border-8 mt-12 flex row start-center" :class="filters.category === 'all' ? 'bg-5':'bg-2'" @click="filters.category='all'">
            <div class="pl-16 flex col center-center">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                          d="M12.5 3H9V16.9987L19 17V9.5H14.5017C13.3988 9.5 12.5 8.60733 12.5 7.50398V3ZM14.5017 7.5H18.6431L14.5001 3.29372C14.5003 4.90761 14.5017 7.5 14.5017 7.5ZM17 21.9832C17 22.5338 16.5501 22.9809 16.0044 22.9816L3.99562 22.9986C3.44576 22.9994 3 22.5551 3 22.0001V5.99985C3 5.44765 3.44502 5 4.00274 5H7V1.99985C7 1.44765 7.44365 1 8.00185 1H14.5358C14.8136 1 15.1981 1.16049 15.3938 1.35736L20.6449 6.64264C20.841 6.84 21 7.22862 21 7.49952V17.9794C21 18.5321 20.5501 18.9809 20.0044 18.9816L17 18.9859V21.9832ZM7.99562 18.9986C7.44576 18.9994 7 18.5551 7 18.0001V7H5.01001L5.00066 21H15V18.9887L7.99562 18.9986Z"
                          :fill="filters.category === 'all' ? 'white':'#333333'"/>
                </svg>
            </div>
            <h2 class=" txt-15-18 weight-bold pl-16"  :class="filters.category === 'all' ? 'white':'color-3'">{{ trans('base.All') }}</h2>
        </div>

        <div class="h-40 border-8 mt-12 flex row start-center bg-2" :class="filters.category === 'video' ? 'bg-5':'bg-2'"  @click="filters.category='video'">
            <div class="pl-16 flex col center-center">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                          d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22ZM12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20Z"
                          :fill="filters.category === 'video' ? 'white':'#333333'"/>
                    <path d="M9 16L16 12L9 8V16Z"  :fill="filters.category === 'video' ? 'white':'#333333'"/>
                </svg>
            </div>
            <h2 class=" txt-15-18 weight-bold pl-16" :class="filters.category === 'video' ? 'white':'color-3'">{{ trans('base.Video') }}</h2>
        </div>

        <div class="h-40 border-8 mt-12 flex row start-center bg-2" :class="filters.category === 'album' ? 'bg-5':'bg-2'"  @click="filters.category='album'">
            <div class="pl-16 flex col center-center">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                          d="M2 6C2 4.89844 2.89844 4 4 4H20C21.1016 4 22 4.89844 22 6V18C22 19.1016 21.1016 20 20 20H4C2.89844 20 2 19.1016 2 18V6ZM5 6C4.44772 6 4 6.44772 4 7V17C4 17.5523 4.44772 18 5 18H19C19.5523 18 20 17.5523 20 17V7C20 6.44772 19.5523 6 19 6H5ZM16.5 8C15.6719 8 15 8.67188 15 9.5C15 10.3281 15.6719 11 16.5 11C17.3281 11 18 10.3281 18 9.5C18 8.67188 17.3281 8 16.5 8ZM6 12L8 10L12 13L14 12L18 14.4062V16H6V12Z"
                          :fill="filters.category === 'album' ? 'white':'#333333'"/>

                </svg>
            </div>
            <h2 class="  txt-15-18 weight-bold pl-16" :class="filters.category === 'album' ? 'white':'color-3'">{{ trans('base.Gallery') }}</h2>
        </div>


        <div class="h-40 border-8 mt-12 flex row start-center bg-2" :class="filters.category === 'documents' ? 'bg-5':'bg-2'"  @click="filters.category='documents'">
            <div class="pl-16 flex col center-center">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                          d="M11 4H5.99547L6 20L17.9979 20C17.9979 20 18.0001 14.1717 18.0001 11H12C11.4477 11 11 10.5523 11 10V4ZM13 9H18L13 4V9ZM5.99547 2H13.3028C13.5787 2 13.9618 2.15949 14.1533 2.3511L19.649 7.84885C19.8429 8.04276 20 8.42796 20 8.69925V20.0007C20 21.1049 19.1055 22 17.9979 22H6.00213C4.89638 22 4 21.1018 4 20V4.00001C4 2.89544 4.89588 2 5.99547 2ZM7 17C7 16.4477 7.44911 16 7.9954 16H16.0046C16.5543 16 17 16.4439 17 17C17 17.5523 16.5509 18 16.0046 18H7.9954C7.44565 18 7 17.5561 7 17ZM7 14C7 13.4477 7.45097 13 8.00426 13H11.9957C12.5504 13 13 13.4439 13 14C13 14.5523 12.549 15 11.9957 15H8.00426C7.44962 15 7 14.5561 7 14Z"
                          :fill="filters.category === 'documents' ? 'white':'#333333'"/>
                </svg>
            </div>
            <h2 class="  txt-15-18 weight-bold pl-16" :class="filters.category === 'documents' ? 'white':'color-3'">{{ trans('base.Documents') }}</h2>
        </div>

        <div class="h-40 border-8 mt-12 flex row start-center bg-2" :class="filters.category === 'publications' ? 'bg-5':'bg-2'"  @click="filters.category='publications'">
            <div class="pl-16 flex col center-center">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                          d="M2 6C2 4.89844 2.89844 4 4 4H20C21.1016 4 22 4.89844 22 6V18C22 19.1016 21.1016 20 20 20H4C2.89844 20 2 19.1016 2 18V6ZM5 6C4.44772 6 4 6.44772 4 7V17C4 17.5523 4.44772 18 5 18H19C19.5523 18 20 17.5523 20 17V7C20 6.44772 19.5523 6 19 6H5ZM16.5 8C15.6719 8 15 8.67188 15 9.5C15 10.3281 15.6719 11 16.5 11C17.3281 11 18 10.3281 18 9.5C18 8.67188 17.3281 8 16.5 8ZM6 12L8 10L12 13L14 12L18 14.4062V16H6V12Z"
                          :fill="filters.category === 'publications' ? 'white':'#333333'"/>

                </svg>
            </div>
            <h2 class="  txt-15-18 weight-bold pl-16" :class="filters.category === 'publications' ? 'white':'color-3'">{{ trans('base.Publication') }}</h2>
        </div>

    </div>
</template>
<script>
    export default {
        name: 'ResourceLeftCategory',
        props: {
            filters: {
                type: Object,
                default: () => {}
            }
        },
        data() {
            return {}
        },
        created() {

        },
        methods: {}
    }
</script>

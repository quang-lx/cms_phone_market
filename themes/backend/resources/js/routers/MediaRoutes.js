import MediaManager from './../components/media/js/components/MediaManager.vue';
import MediaList from './../components/media/js/components/MediaList.vue';
import MediaForm from './../components/media/js/components/MediaForm.vue';
const currentLocale = '/' + window.MonCMS.currentLocale;

const locales = [];

export default [
    {
        path: '/admin/media/media',
        component: MediaManager,
        children: [
            {
                path: '',
                component: MediaList,
                name: 'admin.media.index',
            },
            {
                path: ':mediaId/edit',
                component: MediaForm,
                name: 'admin.media.edit',
                props: {
                    locales,
                },
            },
        ],
    },
];

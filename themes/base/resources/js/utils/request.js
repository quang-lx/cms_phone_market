import axios from 'axios';

export default axios.create({
    baseURL: (window.Vma.locale_prefix ? ('/' + window.Vma.locale_prefix) : '' ) + '/fe-api',
});

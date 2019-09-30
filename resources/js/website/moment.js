import moment from 'moment-jalaali';
// import numeral from 'numeral'

export default {

    created() {
        moment.locale('fa')
    } ,

    filters: {

        created(date) {
            return 'ثبت شده در ' + moment(date, "YYYY-MM-DD ساعت HH:mm:ss").format('jYYYY/jM/jD ساعت HH:mm:ss');
        } ,

        edited(date) {
            return 'اصلاح شده در ' + moment(date, "YYYY-MM-DD ساعت HH:mm:ss").format('jYYYY/jM/jD ساعت HH:mm:ss');
        } ,

        time(date, label) {
            return `${label} در ` + moment(date, "YYYY-MM-DD ساعت HH:mm:ss").format('jYYYY/jM/jD ساعت HH:mm:ss');
        } ,

        //  HH:mm:ss
        to_fa(date) {
            return moment(date).format('jYY/jMM/jDD').split('/').reverse()
            .map( el => el = parseInt(el).toLocaleString('fa-IR') ).join(' / ')
        } ,

        //  HH:mm:ss
        to_en(date) {
            return moment(date).format('YYYY-MM-DD')
        } ,

        ago(date) {
            return moment(date, "YYYY-MM-DD hh:mm:ss").fromNow();
        } ,

        timestampAgo(date) {
            return moment(date * 1000).fromNow();
        } ,

        // comma(number) {
        //     return numeral(number).format('0,0')
        // } ,

        // price(price) {
        //     return numeral(price).format('0,0')
        // }

    } ,

    methods: {
        to_en(date) {
            if(date) {
                return moment(date, 'jYYYY-jMM-jDD').format('YYYY-MM-DD');
            } else {
                return '';
            }
        }
    }

}
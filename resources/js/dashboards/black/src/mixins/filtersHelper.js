import moment from 'moment-jalaali'
import numeral from 'numeral'
import Color from 'color'
import persianJs from 'persianjs'
import Num2persian from 'num2persian'

export default {
    created() {
        moment.locale('fa')
    },
    filters: {
        created(date) {
            return 'ثبت شده در ' + moment(date, "YYYY-MM-DD ساعت HH:mm:ss").format('jYYYY/jM/jD ساعت HH:mm:ss');
        },
        edited(date) {
            return 'اصلاح شده در ' + moment(date, "YYYY-MM-DD ساعت HH:mm:ss").format('jYYYY/jM/jD ساعت HH:mm:ss');
        },
        numToText(number) {
            if ( number > 999999999999 )
                return `${(number / 1000000000000).toFixed(2)} بیلیون`
            
            else if ( number > 999999999 )
                return `${Math.round(number / 1000000000 * 10) / 10} ملیارد`
        
            if ( number > 999999 )
                return `${Math.round(number / 1000000 * 2) / 2} ملیون`
        
            else
                return `${Math.round(number / 1000)} هزار`
        },
        numToPersian(number) {
            return (number ? persianJs(number).persianNumber() * 1 : 0).toPersianLetter()
        },
        expired(date) {
            const label = moment(date).isBefore( moment() ) ? ' منقضی شده است'  : ' منقضی میشود'

            return 'در ' + moment(date, "YYYY-MM-DD ساعت HH:mm:ss").format('jYYYY/jM/jD ساعت HH:mm:ss') + label
        },
        isBefore(date) {
            return moment(date).isBefore( moment() )
        },
        time(date, label) {
            return `${label} در ` + moment(date, "YYYY-MM-DD ساعت HH:mm:ss").format('jYYYY/jM/jD ساعت HH:mm:ss');
        },
        jalaali(date) {
            return moment( date ).format('jYYYY-jMM-jDD HH:mm:ss')
        },
        ago(date) {
            return moment(date, "YYYY-MM-DD hh:mm:ss").fromNow();
        },
        timestampAgo(date) {
            return moment(date * 1000).fromNow();
        },
        comma(number) {
            return numeral(number).format('0,0')
        },
        price(price) {
            return numeral(price).format('0,0') + ' تومان'
        },
        darken(color) {
            return Color(color).darken(0.5);
        },
        lighten(color) {
            return Color(color).lighten(0.5);
        },
    }
}
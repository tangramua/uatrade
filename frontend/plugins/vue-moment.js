import Vue from 'vue'
import VueMoment from 'vue-moment'
import moment from 'moment-timezone'

moment.tz.setDefault('Asia/Shanghai')

Vue.use(VueMoment, { moment })

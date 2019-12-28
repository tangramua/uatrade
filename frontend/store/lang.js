import Cookies from 'js-cookie'
import moment from 'moment'

// state
export const state = () => ({
  locale: process.env.appLocale,
  locales: {
    'en': 'Eng',
    'cn': '中文',
  }
})

// getters
export const getters = {
  locale: state => state.locale,
  locales: state => state.locales
}

// mutations
export const mutations = {
  SET_LOCALE (state, { locale }) {
    state.locale = locale
  }
}

// actions
export const actions = {
  setLocale ({ commit }, { locale }) {
    commit('SET_LOCALE', { locale })


    switch (locale) {
      case 'en':
        moment.locale('en-GB')
        break
      case 'cn':
        moment.locale('zh-CN')
        break
      default:
        moment.locale('en-GB')
    }

    Cookies.set('locale', locale, { expires: 365 })
  }
}

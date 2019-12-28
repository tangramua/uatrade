import axios from 'axios'
import Cookies from 'js-cookie'

// state
export const state = () => ({
  user: null,
  token: null,
  subscribe: null
})

// getters
export const getters = {
  user: state => state.user,
  token: state => state.token,
  check: state => state.user !== null,
  subscribe: state => state.subscribe
}

// mutations
export const mutations = {
  SET_TOKEN (state, token) {
    state.token = token
  },

  FETCH_USER_SUCCESS (state, user) {
    state.user = user
  },

  FETCH_USER_FAILURE (state) {
    state.token = null
  },

  LOGOUT (state) {
    state.user = null
    state.token = null
  },

  UPDATE_USER (state, { user }) {
    state.user = user
  },

  SET_SUBSCRIBE(state, id) {
    state.subscribe = id
  },

  CLEAR_SUBSCRIBE(state) {
    state.subscribe = null
  },
}

// actions
export const actions = {

  saveToken ({ commit }, { token, remember }) {
    commit('SET_TOKEN', token)
    Cookies.set('token', token, { expires: remember ? 365 : null })
  },

  async fetchUser ({ commit }) {
    try {
      const { data } = await axios.get('/auth/user')

      commit('FETCH_USER_SUCCESS', data.data)
    } catch (e) {
      Cookies.remove('token')

      commit('FETCH_USER_FAILURE')
    }
  },

  updateUser ({ commit }, payload) {
    commit('UPDATE_USER', payload)
  },

  async logout ({ commit }) {
    try {
      await axios.post('/auth/logout')
    } catch (e) {
      //
    }

    Cookies.remove('token')

    commit('LOGOUT')
  },

  async saveSubscribe({ commit }, id) {
    commit('SET_SUBSCRIBE', id)
    await Cookies.set('subscribe', id, { expires: 365 })
  },

  async clearSubscribe({ commit }) {
    commit('CLEAR_SUBSCRIBE')
    await Cookies.remove('subscribe')
  }
}

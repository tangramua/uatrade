export const state = () => ({
  pavilion: {
    img: '/img/map/pavilion2.svg',
    bound: [[31.19608,121.2924],[31.18724,121.30238]]
  },

  event: null,
})

export const getters = {
  pavilion: state => {
    return state.pavilion
  },

  event: state => {
    return state.event
  }
}

export const mutations = {
  SET_EVENT(state, event) {
    state.event = event
  },

  RESET_EVENT(state) {
    state.event = null
  }
}

import Cookies from "js-cookie";

export const state = () => ({
  searchText: null
})


export const getters = {
  searchText: state => state.searchText
}


export const mutations = {
  SET_SEARCH_TEXT (state, text) {
    state.searchText = text
  },
}

export const actions = {
  setSearchText (store, text) {
    Cookies.set('search', text, { expires: 1 })
  }
}

import axios from 'axios'
import swal from 'sweetalert2'

process.env.NODE_TLS_REJECT_UNAUTHORIZED = '0'

export default ({ store, redirect }) => {
  axios.defaults.baseURL = process.env.apiUrl

  if (process.server) {
    return
  }

  // Request interceptor
  axios.interceptors.request.use(request => {
    request.baseURL = process.env.apiUrl

    const token = store.getters['auth/token']

    // request.headers.common['Accept'] = `applcation/json`

    if (token) {
      request.headers.common['Authorization'] = `Bearer ${token}`
    }

    const locale = store.getters['lang/locale']
    if (locale) {
      request.headers.common['Accept-Language'] = locale
      request.headers.common['X-localization'] = locale
    }

    return request
  })

  // Response interceptor
  axios.interceptors.response.use(response => response, error => {
    const { status } = error.response || {}

    if (status >= 500) {
      swal({
        type: 'warning',
        title: 'Error 500',
        text: '',
        reverseButtons: true,
        confirmButtonText: 'ok',
        cancelButtonText: 'cancel'
      })
    }

    if (status === 401 && store.getters['auth/check']) {
      swal({
        type: 'warning',
        title: 'Error 401',
        text: '',
        reverseButtons: true,
        confirmButtonText: 'ok',
        cancelButtonText: 'cancel'
      }).then(async () => {
        await store.dispatch('auth/logout')

        redirect({ name: 'login' })
      })
    }

    if (status === 403) {
      swal({
        type: 'warning',
        title: 'Error',
        text: 'You don\'t have permission for this',
        reverseButtons: true,
        confirmButtonText: 'ok',
        cancelButtonText: 'cancel'
      })
    }

    if (status === 404) {
      swal({
        type: 'warning',
        title: 'Page not found',
        text: '',
        reverseButtons: true,
        confirmButtonText: 'ok',
        cancelButtonText: 'cancel'
      }).then(async () => {
        redirect({ path: '/' })
      })
    }

    return Promise.reject(error)
  })
}

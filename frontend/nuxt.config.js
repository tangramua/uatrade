require('dotenv').config()

module.exports = {

  srcDir: __dirname,

  env: {
    apiUrl: process.env.APP_URL || 'http://localhost:8000',
    appName: process.env.APP_NAME || 'AppName',
    appLocale: process.env.APP_LOCALE || 'en',
    defaultMapLat: process.env.DEFAULT_MAP_LAT || 45,
    defaultMapLng: process.env.DEFAULT_MAP_LNG || 45,
    rocketchatUrl: process.env.ROCKETCHAT_URL || 'http://localhost:3000',
  },

  /*
  ** Headers of the page
  */
  head: {
    title: 'frontend-web',
    meta: [
      {charset: 'utf-8'},
      {name: 'viewport', content: 'width=device-width, initial-scale=1'},
      {hid: 'description', name: 'description', content: 'Nuxt.js project'}
    ],
    link: [
      {rel: 'icon', type: 'image/x-icon', href: '/favicon.ico'},
      {
        rel: 'stylesheet',
        href: 'https://fonts.googleapis.com/css?family=Montserrat:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&amp;subset=cyrillic,cyrillic-ext,latin-ext'
      },
      {rel: 'stylesheet', href: 'https://unpkg.com/leaflet@1.3.4/dist/leaflet.css'}
    ]
  },

  css: [
    'swiper/dist/css/swiper.css',
    {src: '~/assets/sass/main.sass', lang: 'sass'},
  ],

  /*
  ** Customize the progress bar color
  */
  loading: {color: '#005480'},

  router: {
    middleware: ['locale', 'check-auth']
  },

  plugins: [
    '~components/global',
    '~plugins/vue-moment',
    '~plugins/axios',
    '~plugins/vform',
    '~plugins/i18n',
    '~/plugins/font-awesome',
    {src: '~/plugins/vueselect', ssr: false},
    {src: '~/plugins/vue2-leaflet', ssr: false},
    {src: '~/plugins/nuxt-swiper-plugin', ssr: false}
  ],


  modules: [
    '@nuxtjs/toast',
    'bootstrap-vue/nuxt',
  ],

  toast: {
    position: 'top-right',
    duration: 5000,
  },

  /*
  ** Build configuration
  */
  build: {
    cssSourceMap: false,
    extend(config, {isDev, isClient}) {
      if (isDev && isClient) {
        config.module.rules.push({
          enforce: 'pre',
          test: /\.(js|vue)$/,
          loader: 'eslint-loader',
          exclude: /(node_modules)/,
          options: {
            formatter: require('eslint-friendly-formatter')
          }
        })
      }
    }
  }
}

<template>
    <section class="header__item header__item_sticky">
        <div class="container">
            <div class="header__item-nav">

                <b-navbar toggleable="md" type="dark" variant="info">

                    <b-navbar-toggle class="btn-mobile-menu" target="nav_collapse">
                        <span class="btn-mobile-menu__item"></span>
                        <span class="btn-mobile-menu__item btn-mobile-menu__item-center">
                            <span></span><span></span>
                        </span>
                        <span class="btn-mobile-menu__item"></span>
                    </b-navbar-toggle>

                    <b-collapse is-nav id="nav_collapse">

                        <b-navbar-nav class="navigation-menu">

                            <b-nav-item-dropdown class="navigation-menu__item" text="" right>
                                <figure slot="button-content">
                                    <div class="ukraine-now">
                                        <span>Ukraine</span>
                                        <span>Now <i><b-img class="img" src="/img/ua-icon.svg" alt=""/></i></span>
                                    </div>
                                    <i class="una-down">
                                        <font-awesome-icon icon="chevron-down"/>
                                    </i>
                                </figure>
                                <div class="container">
                                    <div class="col-md-5 dropdown-menu-shadow">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <b-dropdown-item class="drop-down-link" to="/introduction">
                                                  {{$t('footer.link.introduction')}}
                                                </b-dropdown-item>
                                                <b-dropdown-item class="drop-down-link" to="/trade-and-export">
                                                  {{$t('trade_and_export')}}
                                                </b-dropdown-item>
                                                <b-dropdown-item class="drop-down-link" to="/governmental-institutions">
                                                  {{$t('footer.link.governmental_institutions')}}
                                                </b-dropdown-item>
                                                <b-dropdown-item class="drop-down-link" to="/trade-support-agencies">
                                                  {{$t('trade_support_agencies')}}
                                                </b-dropdown-item>
                                                <b-dropdown-item class="drop-down-link" to="/discover-ukraine">
                                                  {{$t('discover_ukraine')}}
                                                </b-dropdown-item>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </b-nav-item-dropdown>

                            <b-nav-item class="li-item" to="/exporters">{{$t('exporters')}}</b-nav-item>

                            <b-nav-item-dropdown class="li-item li-agenda" text="" right>
                                <div slot="button-content">
                                    <span>{{$t('footer.link.agenda')}}</span>
                                    <i class="lang-down">
                                        <font-awesome-icon icon="chevron-down"/>
                                    </i>
                                </div>
                                <div class="container">
                                    <div class="col-md-5 dropdown-menu-shadow">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <b-dropdown-item class="drop-down-link" to="/map">
                                                    {{$t('map_of_grounds')}}
                                                </b-dropdown-item>
                                                <b-dropdown-item class="drop-down-link" to="/events">
                                                    {{$t('events')}}
                                                </b-dropdown-item>
                                                <b-dropdown-item class="drop-down-link" to="/speakers">
                                                    {{$t('speakers')}}
                                                </b-dropdown-item>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </b-nav-item-dropdown>

                            <b-nav-item class="li-item" to="/massmedia">{{$t('footer.link.massmedia')}}</b-nav-item>
                            <b-nav-item class="li-item" to="/contacts">{{$t('footer.link.contacts')}}</b-nav-item>


                            <b-nav-item-dropdown class="change-language" text="" right>
                                <div slot="button-content">
                                    <span>{{locales[locale]}}</span>
                                    <i class="lang-down">
                                        <font-awesome-icon icon="chevron-down"/>
                                    </i>
                                </div>

                                <div class="container">
                                    <div class="col-md-5 dropdown-menu-shadow">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <b-dropdown-item class="drop-down-link"
                                                                 v-for="(lang, key) in locales"
                                                                 :key="key" @click="setLocale(key)"
                                                                 :class="{active: (locale == key)}">
                                                    <span>{{lang}}</span>
                                                </b-dropdown-item>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </b-nav-item-dropdown>

                            <!-- Authenticated -->
                            <template v-if="userCheck">
                                <b-nav-item class="log-out-link" @click.prevent="logout">
                                    <span>{{ $t('logout') }}</span>
                                    <span><i><b-img class="img" src="/img/logout-icon.svg" alt=""/></i></span>
                                </b-nav-item>
                            </template>
                            <!-- Guest -->
                            <template v-else>
                                <!--<b-nav-item class="log-in-link" to="/auth/login">-->
                                <b-nav-item class="log-in-link" v-b-modal.modal-login>
                                    <span>Log In</span>
                                    <span><i><b-img class="img" src="/img/circle-blue-right-arrow.svg" alt=""/></i></span>
                                </b-nav-item>
                                <b-modal id="modal-login"
                                         centered
                                         :hide-footer="true">
                                    <auth-form></auth-form>
                                    <!--<p class="my-4">Hello from modal!</p>-->
                                </b-modal>
                            </template>
                            <!--  -->
                        </b-navbar-nav>

                    </b-collapse>

                    <b-navbar-brand class="logo" to="/">
                        <figure class="logo__item">
                            <b-img class="img" src="/img/head-logo.svg" alt="Trade Width Ukraine"/>
                        </figure>
                    </b-navbar-brand>
                </b-navbar>
            </div>
            <b-link class="logo-mobile" to="/">
                <figure class="logo__item">
                    <b-img class="img" src="/img/head-logo.svg" alt="Trade Width Ukraine"/>
                </figure>
            </b-link>
        </div>
        <event-subscribe-form></event-subscribe-form>
    </section>
</template>

<script>
  import {mapGetters} from 'vuex'
  import AuthForm from '~/components/Forms/AuthForm'
  import EventSubscribeForm from '~/components/Forms/EventSubscribeForm'

  export default {
    components: { AuthForm, EventSubscribeForm },
    data: () => ({
      appName: process.env.appName
    }),

    computed: {
      ...mapGetters({
        userCheck: 'auth/check',
        locale: 'lang/locale',
        locales: 'lang/locales'
      }),
      authCheck() {
        return this.$store.getters['auth/check']
      }
    },

    methods: {

      setLocale(locale) {
        if (this.$i18n.locale !== locale) {
          this.$store.dispatch('lang/setLocale', {locale})
          window.location.reload()
        }
      },

      async logout() {
        // Log out the user.
        await this.$store.dispatch('auth/logout')

        // Redirect to login.
        this.$router.push({path: '/'})
      }
    }
  }
</script>

<style>
  .qr-img{
    height: 93.56px;
    position: absolute;
    /*margin-top: -129px;*/
    /*margin-left: 93%;*/
    top: -32px;
    right: 0;
    z-index: -1;
  }
  .block-with-angle{
    position:relative;
    background:#fff;
    border-top-right-radius: 60px;
    box-shadow: 0 0 0 1px #666 inset;
    /*border: 1px solid #000000;*/

  }
  .block-with-angle:before{
    content:'';
    position:absolute;
    top:-30px;
    right:5px;
    width:25px;
    height:30px;
    background: linear-gradient(30deg, #999 1px, #f1f1f1 90%);
    box-shadow: 0 0 0 1px #999 inset;
    transform: skew(20deg, 20deg) rotate(-9deg);
    border-top-right-radius: 60px;

  }
  .block-with-angle:after{
    content:'';
    position:absolute;
    top:22px;
    right:40px;
    width:50px;
    height:50px;
    transform:skew(-20deg);
    z-index:9;
  }

  .qr-code-wrapper{
    background: #fff;
  }
</style>

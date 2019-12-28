<template>
    <div>
        <footer class="footer">
            <div class="container">
                <div class="footer__item">
                    <div class="footer__item-columns footer-columns">
                        <b-link class="logo" to="/">
                            <figure class="logo__item">
                                <b-img class="img" src="/img/foo-logo.svg" alt="Trade width Ukraine" />
                            </figure>
                        </b-link>
                    </div>
                    <div class="footer__item-columns-group footer-columns">
                        <div class="footer__item-columns">
                            <b-list-group class="clone-menu-top">
                                <!--<b-list-group-item class="clone-menu-top__item">
                                    <b-link class="clone-menu-top__item-link" to="/ukraine-now">{{$t('footer.link.ukraine_now_ua')}}</b-link>
                                </b-list-group-item>-->
                                <b-list-group-item class="clone-menu-top__item">
                                    <b-link class="clone-menu-top__item-link" to="/introduction">{{$t('footer.link.introduction')}}</b-link>
                                </b-list-group-item>
                                <b-list-group-item class="clone-menu-top__item">
                                    <b-link class="clone-menu-top__item-link" to="/trade-and-export">{{$t('footer.link.trade_and_export')}}</b-link>
                                </b-list-group-item>
                                <b-list-group-item class="clone-menu-top__item">
                                    <b-link class="clone-menu-top__item-link" to="/governmental-institutions">{{$t('footer.link.governmental_institutions')}}</b-link>
                                </b-list-group-item>
                                <b-list-group-item class="clone-menu-top__item">
                                    <b-link class="clone-menu-top__item-link" to="/trade-support-agencies">{{$t('footer.link.trade_support_agencies')}}</b-link>
                                </b-list-group-item>
                                <b-list-group-item class="clone-menu-top__item">
                                    <b-link class="clone-menu-top__item-link" to="/discover-ukraine">{{$t('discover_ukraine')}}</b-link>
                                </b-list-group-item>
                            </b-list-group>
                        </div>
                        <div class="footer__item-columns">
                            <b-list-group class="clone-menu-top">
                                <b-list-group-item class="clone-menu-top__item">
                                    <b-link class="clone-menu-top__item-link" to="/exporters">{{$t('exporters')}}</b-link>
                                </b-list-group-item>
                                <b-list-group-item class="clone-menu-top__item">
                                    <b-link class="clone-menu-top__item-link" to="/map">{{$t('map_of_grounds')}}</b-link>
                                </b-list-group-item>
                                <b-list-group-item class="clone-menu-top__item">
                                    <b-link class="clone-menu-top__item-link" to="/events">{{$t('events')}}</b-link>
                                </b-list-group-item>
                                <b-list-group-item class="clone-menu-top__item">
                                    <b-link class="clone-menu-top__item-link" to="/speakers">{{$t('speakers')}}</b-link>
                                </b-list-group-item>
                                <b-list-group-item class="clone-menu-top__item">
                                    <b-link class="clone-menu-top__item-link" to="/massmedia">{{$t('footer.link.massmedia')}}</b-link>
                                </b-list-group-item>
                                <b-list-group-item class="clone-menu-top__item">
                                    <b-link class="clone-menu-top__item-link" to="/testimonials">{{$t('testimonials')}}</b-link>
                                </b-list-group-item>
                                <b-list-group-item class="clone-menu-top__item">
                                    <b-link class="clone-menu-top__item-link" to="/contacts">{{$t('footer.link.contacts')}}</b-link>
                                </b-list-group-item>
                            </b-list-group>
                        </div>
                        <!--<div class="footer__item-columns">
                            <div class="subscribe-form">
                                <header class="entry-title">
                                    <h4>{{$t('footer.news_service')}}</h4>
                                    <p>{{$t('footer.to_get_interesting_offers_and_news_first')}}</p>
                                </header>
                                <div class="subscribe-form__item">
                                    <label class="subscribe-form__item-label">
                                        <b-form-input class="input-item input-subscribe" type="email" name="subscribe" :placeholder="$t('footer.subscribe_input_placeholder')" />
                                    </label>
                                    <label class="subscribe-form__item-label">
                                        <button class="btn-item btn-subscribe" type="button">{{$t('footer.button.subscribe')}}</button>
                                    </label>
                                </div>
                            </div>
                        </div>-->
                    </div>
                </div>
            </div>
        </footer>
        <!--<div class="sub-footer">
            <div class="sub-footer__item">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 caption-columns">
                            <header class="entry-title">
                                <h5>
                                    {{$t('footer.bottom_center_text')}}
                                </h5>
                            </header>
                        </div>
                    </div>
                </div>
            </div>
        </div>-->
    </div>
</template>

<script>
  export default {
    name: 'MainFooter'
  }
</script>

<template>
    <div class="wrapper">

        <!-- Begin header -->
        <header v-scroll-class:isSticky="10" class="header">
            <main-navbar></main-navbar>
        </header>
        <!-- End header -->

        <div class="content">

            <nuxt/>

        </div>

        <!-- Begin footer -->
        <main-footer></main-footer>
        <!-- End footer -->

    </div>

</template>

<script>
  import MainNavbar from '~/components/MainNavbar'
  import MainFooter from '~/components/MainFooter'
  import VueScrollClass from 'vue-scroll-class'

  export default {
    components: {
      MainNavbar,
      MainFooter
    },
    directives: {
      'scroll-class': VueScrollClass,
    },
  }
</script>

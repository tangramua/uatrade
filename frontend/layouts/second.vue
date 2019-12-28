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
        <second-footer></second-footer>
        <!-- End footer -->

    </div>

</template>

<script>
  import MainNavbar from '~/components/MainNavbar'
  import SecondFooter from '~/components/SecondFooter'
  import VueScrollClass from 'vue-scroll-class'

  export default {
    components: {
      MainNavbar,
      SecondFooter
    },
    directives: {
      'scroll-class': VueScrollClass,
    },
  }
</script>

<template>
    <div>
        <b-modal id="modal-event-subscribe"
                 v-model="showModal"
                 centered
                 :hide-footer="true">

            <b-card v-if="userCheck" no-body key="dv-subscribe">
                <figure class="logo__item">
                    <b-img class="img" src="/img/head-logo.svg" alt="Trade Width Ukraine"/>
                </figure>
                <form class="form-login-registry-modal">

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-md-right">{{ $t('accepted_modal_text_label') }}</label>
                        <div class="col-md-7">
                            <b-form-textarea id="textarea1"
                                             value=" asda \n asda"
                                             readonly
                                             :max-rows="6">
                            </b-form-textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <b-form-checkbox id="checkbox1"
                                         v-model="status"
                                         unchecked-value="not_accepted">
                            {{ $t('accepted_modal_checkbox_label') }}
                        </b-form-checkbox>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-7 offset-md-3">
                            <!-- Submit Button -->
                            <b-btn :disabled="!status" variant="success" class="btn-login-registry-modal" @click="subscribeToEvent">
                                {{ $t('subscribe_to_event') }}
                            </b-btn>
                        </div>
                    </div>
                </form>
            </b-card>
            <auth-form v-else :after-login-redirect="false" key="dv-auth"></auth-form>
        </b-modal>
    </div>
</template>

<script>

  import AuthForm from '~/components/Forms/AuthForm'
  import axios from 'axios'
  import { mapGetters } from 'vuex'
  export default {
    name: 'EventSubscribtionForm',
    components: { AuthForm },
    data() {
      return {
        status: false,
        showModal: false,
        subscribeId: null
      }
    },
    computed: {
      ...mapGetters({
        userCheck: 'auth/check',
        subscribe: 'auth/subscribe'
      })
    },

    watch: {
      subscribe(n){
        // console.log(process.client)
        if(n && process.client) {
          this.showModal =  true
          this.subscribeId = n
          this.$store.dispatch('auth/clearSubscribe')
        }
      }
    },

    created() {
      if(this.$route.name === 'events') {
        this.subscribeId = this.subscribe
        if(this.subscribeId){
          this.showModal =  true
          this.subscribeId = this.subscribe
        }
        this.$store.dispatch('auth/clearSubscribe')
      }
    },

    methods: {
      subscribeToEvent(){
        axios.post(`/client/events/${this.subscribeId}`)
          .then(() => {
            this.$toast.success('Ok')
            this.showModal = false
            this.$router.push('/account/events')
          })
          .catch((e) => {
            this.$toast.error(e.message)
          })
      }
    }
  }
</script>

<style scoped>

</style>

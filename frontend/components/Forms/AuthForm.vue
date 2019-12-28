<template>
    <b-card no-body>
        <figure class="logo__item">
            <b-img class="img" src="/img/head-logo.svg" alt="Trade Width Ukraine"/>
        </figure>
        <b-tabs pills card v-model="tabIndex">
            <b-tab :title="$t('login')" :active="activeLogin">
                <form class="form-login-registry-modal" @submit.prevent="login" @keydown="formLogin.onKeydown($event)">
                    <!-- Email -->
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-md-right">{{ $t('username') }}</label>
                        <div class="col-md-7">
                            <input v-model="formLogin.username" :class="{ 'is-invalid': formLogin.errors.has('username') }" name="username"
                                   class="form-control">
                            <has-error :form="formLogin" field="username"/>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-md-right">{{ $t('password') }}</label>
                        <div class="col-md-7">
                            <input v-model="formLogin.password" :class="{ 'is-invalid': formLogin.errors.has('password') }" type="password" name="password"
                                   class="form-control">
                            <has-error :form="formLogin" field="password"/>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <!--<div class="form-group row">
                        <div class="col-md-3"/>
                        <div class="col-md-7 d-flex">
                            <b-form-checkbox v-model="remember">
                                {{ $t('remember_me') }}
                            </b-form-checkbox>

                            <router-link to="/auth/password/email" class="small ml-auto my-auto">
                                {{ $t('forgot_password') }}
                            </router-link>
                        </div>
                    </div>-->

                    <div class="form-group row">
                        <div class="col-md-7 offset-md-3">
                            <!-- Submit Button -->
                            <b-button class="btn-login-registry-modal" type="submit" variant="success">
                                {{ $t('login') }}
                            </b-button>
                        </div>
                    </div>
                </form>
            </b-tab>
            <b-tab :title="$t('register')">
                <form class="form-login-registry-modal" @submit.prevent="register" @keydown="formRegister.onKeydown($event)">
                    <!-- Name -->
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-md-right">{{ $t('username') }}</label>
                        <div class="col-md-7">
                            <input v-model="formRegister.username" :class="{ 'is-invalid': formRegister.errors.has('username') }" type="text"
                                   class="form-control">
                            <has-error :form="formRegister" field="username"/>
                        </div>
                    </div>
                    <!-- First Name -->
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-md-right">{{ $t('first_name') }}</label>
                        <div class="col-md-7">
                            <input v-model="formRegister.first_name" :class="{ 'is-invalid': formRegister.errors.has('first_name') }" type="text"
                                   class="form-control">
                            <has-error :form="formRegister" field="first_name"/>
                        </div>
                    </div>
                    <!-- Last Name -->
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-md-right">{{ $t('last_name') }}</label>
                        <div class="col-md-7">
                            <input v-model="formRegister.last_name" :class="{ 'is-invalid': formRegister.errors.has('last_name') }" type="text"
                                   class="form-control">
                            <has-error :form="formRegister" field="last_name"/>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-md-right">{{ $t('email') }}</label>
                        <div class="col-md-7">
                            <input v-model="formRegister.email" :class="{ 'is-invalid': formRegister.errors.has('email') }" type="email" name="email"
                                   class="form-control">
                            <has-error :form="formRegister" field="email"/>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-md-right">{{ $t('password') }}</label>
                        <div class="col-md-7">
                            <input v-model="formRegister.password" :class="{ 'is-invalid': formRegister.errors.has('password') }" type="password" name="password"
                                   class="form-control">
                            <has-error :form="formRegister" field="password"/>
                        </div>
                    </div>

                    <!-- Password Confirmation -->
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-md-right">{{ $t('confirm_password') }}</label>
                        <div class="col-md-7">
                            <input v-model="formRegister.password_confirmation" :class="{ 'is-invalid': formRegister.errors.has('password_confirmation') }" type="password" name="password_confirmation"
                                   class="form-control">
                            <has-error :form="formRegister" field="password_confirmation"/>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-7 offset-md-3 d-flex">
                            <!-- Submit Button -->
                            <b-button class="btn-login-registry-modal" type="submit" variant="success">
                                {{ $t('register') }}
                            </b-button>
                        </div>
                    </div>
                </form>
            </b-tab>
        </b-tabs>
    </b-card>
</template>

<script>
  import Form from 'vform'

  export default {
    name: "AuthForm",
    props: {
      afterLoginRedirect: {
        default: '/account'
      }
    },
    data: () => ({
      tabIndex: 0,
      activeLogin: true,
      formLogin: new Form({
        username: null, // 'visitor_1',
        password: null  // 'visitor_1'
      }),
      formRegister: new Form({
        username: '',
        first_name: '',
        last_name: '',
        email: '',
        password: '',
        password_confirmation: ''
      }),
      remember: false
    }),
    methods: {
      login () {
        // Submit the form.
        this.formLogin.post('auth/login')
          .then(({ data }) => {
            // Save the token.
            this.$store.dispatch('auth/saveToken', {
              token: data.access_token,
              remember: this.remember
            });

            // Fetch the user.
            this.$store.dispatch('auth/fetchUser')

            // Redirect home.
            if(this.afterLoginRedirect){
              this.$router.push({ path: this.afterLoginRedirect })
            }
          })

      },

      register () {
        // Register the user.
        // const { data } = await this.formRegister.post('guest/visitor')
        this.formRegister.post('guest/visitor')
          .then(() => {
            this.$toast.success('Registration successful');
            // Redirect to log in.
            this.tabIndex = 0;
          });
      }
    }
  }
</script>

<style scoped>

</style>

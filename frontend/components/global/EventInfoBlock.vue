<template>
    <div class="filter-event-result__item">
        <div class="filter-event-result__item-description">
            <div class="item-description">
                <div class="item-description-title">
                    <b-link class="item-description-title__link" @click="goEventMap">
                        <span>{{$moment(event.start_date).format('MMMM DD, YYYY HH:mm z')}}</span>
                    </b-link>
                </div>
                <div class="item-description-info">
                    <div class="item-description-info__column">
                        <div class="item-description-info__column-title">
                            {{event.display_name}}
                        </div>
                        <div class="item-description-info__column-opening"
                             v-if="event.type">
                            {{event.type.display_name}}
                        </div>
                        <div class="item-description-info__column-text" v-if="event.topics">
                            Topic:
                            <span v-for="(topic, topicIndex) in event.topics"
                                  :key="topic.id">
                                                                        {{topic.display_name}}<template
                                    v-if="topicIndex < event.topics.length">,</template>
                                                                    </span>
                        </div>
                        <b-btn class="btn-subscribe-event" v-if="unsubscribeBtn" key="unsubscribe-btn" @click="eventUnsubscribe">Unsubscribe</b-btn>
                        <b-btn class="btn-subscribe-event" v-else key="subscribe-btn" @click="eventSubscribe">Subscribe</b-btn>
                    </div>
                    <div class="item-description-info__column" v-if="event.speakers">
                        <div v-for="(speaker, speakerIndex) in event.speakers"
                             :key="speaker.id">
                            <template v-if="speakerIndex === 0 || event.speakers_all">
                                <div class="item-description-info__column-title">
                                    {{speaker.first_name}} {{speaker.last_name}}
                                </div>
                                <div class="item-description-info__column-text">
                                    {{speaker.position}}
                                </div>
                            </template>
                        </div>
                        <div class="item-description-info__column-speakers"
                             v-if="event.speakers.length > 1">
                            <button class="item-description-info__column-speakers-link"
                                    v-if="event.speakers_all"
                                    @click="$set( event, 'speakers_all', false )"
                                    key="collapce">- collapse
                            </button>
                            <button class="item-description-info__column-speakers-link"
                                    v-else
                                    @click="$set( event, 'speakers_all', true )"
                                    key="more">+ more speaker
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="filter-event-result__item-place">
            <div class="item-place">
                                                        <span class="place-icon" v-if="event.location">
                                                            <b-img class="img" src="/img/place-icon.svg" alt=""/>
                                                        </span>
                <span class="place-text place-hall" v-if="event.location">{{event.location.display_name}}</span>
                <template v-if="event.basic_languages">
                                                <span class="place-text place-language"
                                                      v-for="lang in event.basic_languages" :key="lang.id">{{lang.display_name}}</span>
                </template>
                <span class="place-text place-language"
                      v-if="event.live_translation_languages">Live:</span>
                <template v-if="event.live_translation_languages">
                                                <span class="place-text place-language"
                                                      v-for="live in event.live_translation_languages" :key="live.id">{{live.display_name}}</span>
                </template>
            </div>
        </div>
    </div>
</template>

<script>

  import axios from 'axios'
  import swal from 'sweetalert2'

  export default {
    name: 'EventInfoBlock',
    props: {
      event: {
        type: Object
      },
      unsubscribeBtn: {
        type: Boolean,
        default: false
      }
    },
    methods: {
      goEventMap() {
        this.$store.commit('map_settings/SET_EVENT', this.event)
        this.$router.push('/map')
      },

      eventSubscribe() {
        this.$store.commit('auth/SET_SUBSCRIBE', this.event.id)
      },

      eventUnsubscribe() {
        swal({
          title: 'Are you sure?',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes!',
          showLoaderOnConfirm: true,
          preConfirm: () => {
            return axios.delete(`/client/events/${this.event.id}`)
              .then(async () => {
                await this.$emit('update')
                swal(
                  'Successful!',
                  'success'
                )
              })
          },
          allowOutsideClick: () => !swal.isLoading()
        })
      }
    }
  }
</script>

<style scoped>

</style>

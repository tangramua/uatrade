<template>
    <div class="filter-event-speakers">
        <div class="banner-top__filter-content">

            <div class="filter-select">
                <div class="filter-select">
                    <div class="location-topic">
                        <no-ssr>
                            <v-select class="location-topic__item" :options="dayOptions" label="day" v-model="eventFilterDay" @input="searchEvent"/>
                            <v-select class="location-topic__item" :options="timeOptions"  v-model="eventFilterTime"  @input="searchEvent"/>
                        </no-ssr>
                    </div>

                    <div class="location-topic">
                        <no-ssr>
                            <v-select class="location-topic__item" :options="locationOptions" label="display_name"  v-model="eventFilterLocation"  @input="searchEvent"/>
                            <v-select class="location-topic__item" :options="topicOptions" label="display_name"  v-model="eventFilterTopic"  @input="searchEvent"/>
                        </no-ssr>
                    </div>
                </div>
            </div>

            <div class="filter-event-result">

                <div class="row">
                    <div class="col-md-6 filter-event-result__item" v-for="(event, index) in searchEventResult.data" :key="event.id">
                        <div class="filter-event-result__item-description">
                            <div class="item-description">
                                <div class="item-description-title">
                                    <b-link class="item-description-title__link" to="#">
                                        <span>{{$moment(event.start_date).format('MMMM DD, YYYY HH:mm z')}}</span>
                                    </b-link>
                                </div>
                                <div class="item-description-info">
                                    <div class="item-description-info__column">
                                        <div class="item-description-info__column-title">
                                            {{event.display_name}}
                                        </div>
                                        <div class="item-description-info__column-opening" v-if="event.type">
                                            {{event.type.display_name}}
                                        </div>
                                        <div class="item-description-info__column-text" v-if="event.topics">
                                            Topic:
                                            <span v-for="(topic, topicIndex) in event.topics" :key="topic.id">
                                                                        {{topic.display_name}}<template v-if="topicIndex < event.topics.length">,</template>
                                                                    </span>
                                        </div>
                                    </div>
                                    <div class="item-description-info__column" v-if="event.speakers">
                                        <div v-for="(speaker, speakerIndex) in event.speakers" :key="speaker.id">
                                            <template v-if="speakerIndex === 0 || event.speakers_all">
                                                <div class="item-description-info__column-title">
                                                    {{speaker.first_name}} {{speaker.last_name}}
                                                </div>
                                                <div class="item-description-info__column-text">
                                                    {{speaker.position}}
                                                </div>
                                            </template>
                                        </div>
                                        <div class="item-description-info__column-speakers" v-if="event.speakers.length > 1">
                                            <button class="item-description-info__column-speakers-link" v-if="event.speakers_all" @click="$set( searchEventResult.data[index], 'speakers_all', false )" key="collapce">- collapse</button>
                                            <button class="item-description-info__column-speakers-link" v-else @click="$set( searchEventResult.data[index], 'speakers_all', true )" key="more">+ more speaker</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="filter-event-result__item-place">
                            <div class="item-place">
                                                        <span class="place-icon" v-if="event.location">
                                                            <b-img class="img" src="/img/place-icon.svg" alt="" />
                                                        </span>
                                <span class="place-text place-hall" v-if="event.location">{{event.location.display_name}}</span>
                                <template v-if="event.basic_languages">
                                    <span class="place-text place-language" v-for="lang in event.basic_languages" :key="lang.id">{{lang.display_name}}</span>
                                </template>
                                <span class="place-text place-language" v-if="event.live_translation_languages">Live:</span>
                                <template v-if="event.live_translation_languages">
                                    <span class="place-text place-language" v-for="live in event.live_translation_languages" :key="live.id">{{live.display_name}}</span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="all-more" v-if="searchEventResult.current_page < searchEventResult.last_page">
                <button class="all-more__link" @click="loadMoreEvent">{{$t('buttons.load_more_btn')}}</button>
            </div>
        </div>
    </div>
</template>

<script>

  import axios from 'axios'
  import moment from 'moment'
  import { debounce } from 'lodash'

  const LIMIT = 5
  const DEFAULT_PARENT_TYPE = 'company'
  const DEFAULT_SORT = 'asc'
  const DEFAULT_QUERY_DELAY = 500

  const getFilterOption = function () {
    const filter = {'speakers.company.type': 'commercial'}
    return axios.post(`/guest/event/filter-options`, {filter: filter})
  }

  const getEvents = function (page = 1, limit = 5, params) {
    return axios.post(`/guest/event/paginate?page=${page}&limit=${limit}`, params)
  }

  const dayOptionsFormatter = function (startDates){
    const startDatesLen = startDates.length

    let dayOptions = []
    const days = {}

    for(let i = 0; i < startDatesLen; i++) {

      const date = moment(startDates[i], 'X')

      if(!(date.format('D MMM. YYYY') in days)) {
        days[date.format('D MMM. YYYY')] = {}
      }

      days[date.format('D MMM. YYYY')][date.format('HH:mm')] = true
    }

    for(let day in days){

      let times = []
      for(let time in days[day]){
        times.push(time)
      }

      dayOptions.push({
        day: day,
        times: times
      })
    }

    return dayOptions
  }

  export default {
    name: 'EventListWithFilter',

    data() {
      return {
        eventFilterDay: null,
        eventFilterTime: null,
        eventFilterLocation: null,
        eventFilterTopic: null,

        timeOptions: [],

        initDebounceEvent: true
      }
    },

    props: {
      searchEventResult: {
        type: Object,
        default: function () { return { data: [] } }
      },
      dayOptions: {
        type: Array,
        default: function () { return  [] }
      },
      locationOptions: {
        type: Array,
        default: function () { return  [] }
      },
      topicOptions: {
        type: Array,
        // default: function () { return  [] }
        default: []
      }
    },

    watch: {
      eventFilterDay(n) {
        this.timeOptions = []
        if(n){
          this.timeOptions = n.times
        }
      },
    },

    created() {
      this.searchEvent = debounce(this.searchEventQuery, this.searchDelay)
    },

    methods: {

      getQueryParamsEvent() {

        const filter = {}
        filter['speakers.company.type'] = 'commercial'

        let day = this.$moment()
        if(this.eventFilterDay) {
          day = this.$moment(this.eventFilterDay.day, 'D MMM. YYYY')
        }

        if(this.eventFilterTime){
          const hour =  this.$moment(this.eventFilterTime, 'HH:mm').get('hour')
          const minute =  this.$moment(this.eventFilterTime, 'HH:mm').get('minute')
          day.set({hour: hour, minute: minute})
        } else {
          day.set({hour: 0, minute: 0})
        }

        day.utc()

        filter['start_date'] = {
          operator: '>=',
          value: day.format('YYYY-MM-DD HH:mm:00')
        }

        if(this.eventFilterLocation) {
          filter['location.id'] = this.eventFilterLocation.id
        }

        if(this.eventFilterTopic) {
          filter['topics.id'] = this.eventFilterTopic.id
        }

        const sort = {
          start_date: 'asc'
        }

        const params = {
          filter: filter,
          sort: sort
        }
        return params
      },

      searchEventQuery() {
        if(this.initDebounceEvent){
          this.initDebounceEvent = false
          return
        }

        getEvents(1, LIMIT, this.getQueryParamsEvent())
          .then(({data}) => {
            this.searchEventResult = data.data
          })
      },

      loadMoreEvent() {
        if (this.searchEventResult.current_page >= this.searchEventResult.total_page) return
        getEvents(this.searchEventResult.current_page + 1, LIMIT,this.getQueryParamsEvent())
          .then(({data}) => {

            this.searchEventResult.current_page = data.data.current_page
            const result = data.data.data
            const resultLen = result.length

            for (let i = 0; i < resultLen; i++) {
              this.searchEventResult.data.push(result[i])
            }
          })
          .catch(e => {
            console.log(e)
          })
      },
    }
  }
</script>

<style scoped>

</style>

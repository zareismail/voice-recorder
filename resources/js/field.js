import AudioRecorder from 'vue-audio-recorder'

Nova.booting((Vue, router, store) => {
  Vue.use(AudioRecorder)
  Vue.component('index-voice-recorder', require('./components/IndexField'))
  Vue.component('detail-voice-recorder', require('./components/DetailField'))
  Vue.component('form-voice-recorder', require('./components/FormField'))
})

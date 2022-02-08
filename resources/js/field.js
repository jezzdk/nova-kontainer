Nova.booting((Vue, router, store) => {
  Vue.component('index-nova-kontainer', require('./components/IndexField'))
  Vue.component('detail-nova-kontainer', require('./components/DetailField'))
  Vue.component('form-nova-kontainer', require('./components/FormField'))
})

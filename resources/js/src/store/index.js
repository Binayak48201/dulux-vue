import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
// Modules
import ecommerceStoreModule from '@/views/apps/e-commerce/eCommerceStoreModule'
import app from './app'
import appConfig from './app-config'
import verticalMenu from './vertical-menu'

Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    app,
    appConfig,
    verticalMenu,
    'app-ecommerce': ecommerceStoreModule,
  },
  strict: process.env.DEV,
  state: {
    user: null,
    disabled: false,
  },
  mutations: {
    setUserData(state, userData) {
      state.user = userData
      localStorage.setItem('user', JSON.stringify(userData))
      // localStorage.setItem('device_key', userData.device_key)
      axios.defaults.headers.common.Authorization = `Bearer ${userData.token}`
    },
    // Stores the auth user data in local storage.
    getUserData() {
      localStorage.getItem('user')
    },
    // Clear the auth user data from local storage.
    clearUserData() {
      localStorage.removeItem('user')
      localStorage.removeItem('kycVerified')
      localStorage.removeItem('showPopup')
      const base = window.location.origin
      location.href = base
    },
  },
  actions: {
    async login({ commit }, credentials) {
      const { data: { data } } = await axios.post('api/login', credentials)
      commit('setUserData', data)
    },
    setUserData({ commit }, data) {
      commit('setUserData', data)
    },
    logout({ commit }) {
      commit('clearUserData')
    },
  },
  getters: {
    isLogged: state => !!state.user,
    user: state => state.user,
  },
})

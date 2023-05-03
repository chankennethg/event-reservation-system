import { createStore } from "vuex";
import axiosClient from "../axios";

const store = createStore({
  state: {
    user: {
      data: JSON.parse(sessionStorage.getItem("USER")),
      token: sessionStorage.getItem("TOKEN"),
    },
    events: {
      loading: false,
      links: [],
      data: []
    },
    notification: {
      show: false,
      type: 'success',
      message: ''
    }
  },
  getters: {},
  actions: {
    register({commit}, user) {
      return axiosClient.post('/auth/register', user)
        .then(({data}) => {
          commit('setUser', data.data);
          commit('setToken', data.data.token)
          return data;
        })
    },
    login({commit}, user) {
      return axiosClient.post('/auth/login', user)
        .then(({data}) => {
          commit('setUser', data.data);
          commit('setToken', data.data.token)
          return data;
        })
    },
    logout({commit}) {
        commit('logout');
    },
    getUser({commit}) {
    //   @TODO User Endpoint
    //   return axiosClient.get('/user')
    //   .then(res => {
    //     commit('setUser', res.data)
    //   })
    },
    getEvents({ commit }, {url = null} = {}) {
      commit('setEventsLoading', true)
      url = url || "/events/list?status=available";
      return axiosClient.get(url).then((res) => {
        commit('setEventsLoading', false)
        commit("setEvents", res.data);
        return res;
      });
    },
    createEvent({}, event) {
      let response = axiosClient.post('/events/create', event).then((res) => {
          return res;
        });

      return response;
    },
    reserveEvent({ dispatch }, uuid) {
      let payload = {
        'event_uuid': uuid
      };
      return axiosClient
        .post('/tickets/create', payload)
        .then((res) => {
          dispatch('getEvents')
          return res;
        })
        .catch((err) => {
          store.commit("notify", {
            type: "error",
            message: err.response.data.message,
          });
          throw err;
        });
    },
  },
  mutations: {
    logout: (state) => {
      state.user.token = null;
      state.user.data = {};
      sessionStorage.removeItem("TOKEN");
    },

    setUser: (state, user) => {
      state.user.data = user;
      sessionStorage.setItem('USER', JSON.stringify(user));
    },
    setToken: (state, token) => {
      state.user.token = token;
      sessionStorage.setItem('TOKEN', token);
    },
    setEventsLoading: (state, loading) => {
      state.events.loading = loading;
    },
    setEvents: (state, events) => {
      state.events.links = events.meta.links;
      state.events.data = events.data;
    },
    notify: (state, {message, type}) => {
      state.notification.show = true;
      state.notification.type = type;
      state.notification.message = message;
      setTimeout(() => {
        state.notification.show = false;
      }, 3000)
    },
  },
  modules: {},
});

export default store;

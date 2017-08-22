const state = {
  token: '',
  professional: {},
  login_email: '',
  redirect_to: '/patients'
}

const mutations = {
  
  setLoginData (state, data) {
    state.token = data.token;
    state.professional = data.professional;
  },

  clearLoginData (state) {
    state.token = '';
    state.professional = {};
  },

  setLoginEmail(state, data){
    state.login_email = data.login_email;
  },

  setRedirectTo(){
    state.redirect_to = data.redirect_to;
  }
  
}

const actions = {
  sendToken ({ commit }, response) {
  	commit('setLoginData', {
    	'token': response.token,
      'professional': response.professional
    });
  }
}

export default {
  state,
  actions,
  mutations
}
require('../bootstrap');

import common 			from './common';
import authentication 	from './authentication';

Vue.use(Vuex);

export default new Vuex.Store({
	modules: {
    	common: common,
    	authentication: authentication
  	},
  	plugins: [createPersistedState()]
});
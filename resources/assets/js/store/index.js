require('../bootstrap');

import common from './common';

Vue.use(Vuex);

export default new Vuex.Store({
	modules: {
    	common: common
  	}
});
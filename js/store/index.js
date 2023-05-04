import { createStore } from 'vuex';

import companyInformation from './companyInformation';
import cpi from './measure/cpi';
import exchange from './exchange';
import roc from './roc';
import stats from './stats';
import stock from './stock';
import stockPrice from './stockPrice';
import stockPriceVelocity from './stockPriceVelocity';
import stockShare from './stockShare';
import stockPriceSma10 from './stockPriceSma';
import stockPriceSma100 from './stockPriceSma';

import state from './state';

export default createStore({
	modules: {
		companyInformation,
		cpi,
		exchange,
		roc,
		stats,
		stock,
		stockPrice,
		stockPriceVelocity,
		stockShare,
		stockPriceSma10,
		stockPriceSma100,
	},
	state,
});

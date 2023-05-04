import Home from "./vue/Views/Home";
import Shares from "./vue/Views/Shares";
import Roc from "./vue/Views/Roc";
import RocPrice from "./vue/Views/RocPrice";
import RocPriceVelocitySma from "./vue/Views/RocPriceVelocitySma";
import RocSma from "./vue/Views/RocSma";
import RocVolume from "./vue/Views/RocVolume";
import Cpi from "./vue/Views/Cpi";
import StockShow from "./vue/Views/StockShow";
import StockListChart from "./vue/Views/StockListChart";

export default [
	{
		path: '/',
		component: Home,
	},
	{
		path: '/shares',
		component: Shares,
	},
	{
		path: '/roc',
		component: Roc,
		redirect: '/roc/price',
		children: [
			{
				path: 'price/velocity/sma',
				redirect: '/roc/price/velocity/sma/mean',
				component: RocPriceVelocitySma,
				children: [
					{
						path: ':metric',
						component: RocPriceVelocitySma,
					},
				],
			},
			{
				path: 'price',
				component: RocPrice,
				redirect: '/roc/price/median',
				children: [
					{
						path: ':metric',
						component: RocPrice,
					},
				],
			},
			{
				path: 'sma',
				component: RocSma,
				redirect: '/roc/sma/mean',
				children: [
					{
						path: ':metric',
						component: RocSma,
					},
				],
			},
			{
				path: 'volume',
				component: RocVolume,
			}
		],
	},
	{
		path: '/timeline',
		component: StockListChart,
	},
	{
		path: '/cpi',
		component: Cpi,
	},
	{
		path: '/stock/:stockId',
		component: StockShow,
		name: 'stock-show',
	},
];

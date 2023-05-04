import { nodeResolve } from '@rollup/plugin-node-resolve';
import alias from '@rollup/plugin-alias';
import globals from 'rollup-plugin-node-globals';
import builtins from 'rollup-plugin-node-builtins';
import replace from '@rollup/plugin-replace';
import vue from '@vitejs/plugin-vue';
import postcss from 'rollup-plugin-postcss';

const sources = [
	{
		inputPath: 'js/main.js',
		outputPath: 'www/js/main.min.js',
	},
];

const configurePath = ({inputPath, outputPath}) => ({
	input: inputPath,
	output: {
		file: outputPath,
		format: 'esm',
		sourcemap: true,
	},
	plugins: getPlugins(),
});

function getPlugins() {
	return [
		vue({
			preprocessStyles: true,
		}),
		postcss(),
		replace({
			'__VUE_OPTIONS_API__': true,
			'__VUE_PROD_DEVTOOLS__': false,
			preventAssignment: true,
		}),
    alias({
      entries: [
        {find: 'stocks', replacement: __dirname + '/js'}
      ],
      stocks: __dirname + '/js',
    }),
		nodeResolve({
      extensions: ['.js', '.vue'],
		}),
		globals(),
		builtins(),
	];
}

export default sources.map(configurePath)

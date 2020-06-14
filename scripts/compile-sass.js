const sass = require('./../node_modules/node-sass');
const fs = require('fs');

/* test.scss content: $text-color:#555555;\nbody{background:$text-color;} */

//var dataTemp = '$text-color:#555555;body{background:$text-color;}';

let sassString = process.argv[2];

let path;
for (let key in process.argv) {
	let arg = process.argv[key];
	if (arg.startsWith('--path=')) {
		path = arg.replace('--path=', '');
	}
}

if (!sassString || !path) {
	console.log('arguments missing');
	return;
}
sassString = sassString
	.replace('\\n', '\n')
	.replace('\\r', '\r')
	.replace('\\v', '\v')
	.replace('\\t', '\t')
	.split('<br>')
	.join('\n');

var output = sass.render(
	{
		data: sassString,
		outputStyle: 'expanded',
	},
	function(err, result) {
		if (err) console.log(JSON.stringify({ ...err, string: sassString }));
		if (result) {
			fs.writeFileSync(path, result.css.toString());
		}
	}
);

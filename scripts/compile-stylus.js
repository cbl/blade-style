const stylus = require('./../node_modules/stylus');
const fs = require('fs');

let stylusString = process.argv[2];

let path;
for (let key in process.argv) {
	let arg = process.argv[key];
	if (arg.startsWith('--path=')) {
		path = arg.replace('--path=', '');
	}
}

if (!stylusString || !path) {
	console.log('arguments missing');
	return;
}
stylusString = stylusString
	.replace('\\n', '\n')
	.replace('\\r', '\r')
	.replace('\\v', '\v')
	.replace('\\t', '\t')
	.split('<br>')
	.join('\n');

fs.writeFileSync(path, stylus.render(stylusString));

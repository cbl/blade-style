const less = require('./../node_modules/less');
const fs = require('fs');

let lessString = process.argv[2];

let path;
for (let key in process.argv) {
	let arg = process.argv[key];
	if (arg.startsWith('--path=')) {
		path = arg.replace('--path=', '');
	}
}

if (!lessString || !path) {
	console.log('arguments missing');
	return;
}
lessString = lessString
	.replace('\\n', '\n')
	.replace('\\r', '\r')
	.replace('\\v', '\v')
	.replace('\\t', '\t')
	.split('<br>')
	.join('\n');

less.render(lessString)
	.then(function (result) {
		fs.writeFileSync(path, result.css);
	})
	.catch(function (e) {
		console.log(JSON.stringify(e));
	});

const fs = require('node:fs');
const path = require('node:path');

let assetContent = (filename) => fs.readFileSync(path.resolve('assets', filename)).toString();

test('Dist style is minified', () => {
    // Minified style does not have more than 5 lines.
    return expect(assetContent('dist/thepay.css').split(/\n/).length).toBeLessThan(5);
});

test('Dist script is minified', () => {
    // Minified script does not have more than 5 lines.
    return expect(assetContent('dist/thepay.js').split(/\n/).length).toBeLessThan(5);
});

// copied from woltlab. Good things are
(function () {
    var config;
    config = {
        name: "Teralios/_Meta",
        out: "Teralios.QuizMaker.min.js",
        useStrict: true,
        preserveLicenseComments: false,
        optimize: 'uglify2',
        uglify2: {
            compress: {
                sequences: true,
                properties: true,
                dead_code: true,
                conditionals: true,
                comparisons: true,
                booleans: true,
                loops: true,
                hoist_funs: true,
                hoist_vars: true,
                if_return: true,
                join_vars: true,
                cascade: true,
                /* this is basically the `--define` argument */
                global_defs: {
                    COMPILER_TARGET_DEFAULT: true
                }
            }
        },
        excludeShallow: [
            'Teralios/_Meta'
        ],
        rawText: {
            'Teralios/_Meta': 'define([], function() {});'
        },
        onBuildRead: function(moduleName, path, contents) {
            if (!process.versions.node) {
                throw new Error('You need to run node.js');
            }

            if (moduleName === 'Teralios/_Meta') {
                if (global.allModules === undefined) {
                    var fs   = module.require('fs'),
                        path = module.require('path');
                    global.allModules = [];

                    var queue = ['Teralios'];
                    var folder;
                    while (folder = queue.shift()) {
                        var files = fs.readdirSync(folder);
                        for (var i = 0; i < files.length; i++) {
                            var filename = path.join(folder, files[i]).replace(/\\/g, '/');
                            if (filename === 'Teralios/Acp') continue;

                            if (path.extname(filename) === '.js') {
                                global.allModules.push(filename);
                            }
                            else if (fs.statSync(filename).isDirectory()) {
                                queue.push(filename);
                            }
                        }
                    }
                }

                return 'define([' + global.allModules.map(function (item) { return "'" + item.replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/\.js$/, '') + "'"; }).join(', ') + '], function() { });';
            }

            return contents;
        }
    };

    var _isSupportedBuildUrl = require._isSupportedBuildUrl;
    require._isSupportedBuildUrl = function (url) {
        var result = _isSupportedBuildUrl(url);
        if (!result) return result;
        if (Object.keys(config.rawText).map(function (item) { return (process.cwd() + '/' + item + '.js').replace(/\\/g, '/'); }).indexOf(url.replace(/\\/g, '/')) !== -1) return result;

        var fs = module.require('fs');
        try {
            fs.statSync(url);
        }
        catch (e) {
            console.log('Unable to find module:', url, 'ignoring.');

            return false;
        }
        return true;
    };

    if (module) module.exports = config;

    return config;
})();
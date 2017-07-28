const webpack = require('webpack')

function dealWithErrors(err, stats) {
    if (err) {
        console.error(err.stack || err)
        if (err.details) {
            console.error(err.details)
        }
        return true
    }

    const info = stats.toJson()

    if (stats.hasErrors()) {
        console.error(info.errors)
    }

    if (stats.hasWarnings()) {
        console.warn(info.warnings)
    }
}

function logResult(stats) {
    process.stdout.write(stats.toString({
        colors: true,
        modules: false,
        children: false,
        chunks: false,
        chunkModules: false
    }) + '\n\n')
}

webpack(require('./webpack.dll.config.js'), (err, stats) => {
    if (dealWithErrors(err, stats)) {
        return
    }

    logResult(stats)

    webpack(require('./webpack.config.js'), (err, stats) => {
        if (dealWithErrors(err, stats)) {
            return
        }

        logResult(stats)
    })
})

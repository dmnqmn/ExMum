const path = require('path')

const assetsPath = './resources/assets/'
const jsEntryPath = `${assetsPath}js/entry/`
const publicPath = './public/'
const publicJsPath = `${publicPath}/js/`
const publicCssPath = `${publicPath}/css/`

const resolvedPublicPath = path.resolve(__dirname, '../public/')
const resolvedDllInfo = path.resolve(__dirname, '../public/dll-info.json')

module.exports = {
    assetsPath, jsEntryPath, publicPath, publicJsPath,
    publicCssPath, resolvedPublicPath, resolvedDllInfo
}

const {
  resolvedAssetsPath
} = require('./config.js')

module.exports = {
    '@': resolvedAssetsPath,
    '@js': `${resolvedAssetsPath}/js/`,
    '@css': `${resolvedAssetsPath}/css/`,
    'vue': 'vue/dist/vue.js'
}

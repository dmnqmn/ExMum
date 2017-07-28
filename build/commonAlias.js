const {
  resolvedAssetsPath
} = require('./config.js')

module.exports = {
    '@js': `${resolvedAssetsPath}/js/`,
    '@css': `${resolvedAssetsPath}/css/`,
    'vue': 'vue/dist/vue.js'
}

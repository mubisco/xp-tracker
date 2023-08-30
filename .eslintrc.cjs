module.exports = {
  root: true,
  env: {
    node: true
  },
  extends: [
    'standard',
    'plugin:vue/vue3-recommended',
    'eslint:recommended',
    '@vue/eslint-config-typescript'
  ],
  rules: {
    // 'vue/multi-word-component-names': 'off'
  }
}

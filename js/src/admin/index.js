import QQSettingsModal from './components/QQSettingsModal'

app.initializers.add('hehongyuanlove/flarum-auth-qq', () => {
  console.log('[hehongyuanlove/flarum-auth-qq] Hello, admin!')
  app.extensionSettings['hehongyuanlove/flarum-auth-qq'] = () => app.modal.show(new QQSettingsModal())
})

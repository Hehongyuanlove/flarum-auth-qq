import QQSettingsModal from './components/QQSettingsModal'

app.initializers.add('hehongyuanlove/flarum-auth-qq', () => {
  app.extensionSettings['hehongyuanlove/flarum-auth-qq'] = () => app.modal.show(new QQSettingsModal())
})

import QQSettingsModal from './components/QQSettingsModal'

app.initializers.add('hehongyuanlove/auth-qq', () => {
  app.extensionSettings['hehongyuanlove/auth-qq'] = () => app.modal.show(new QQSettingsModal())
})

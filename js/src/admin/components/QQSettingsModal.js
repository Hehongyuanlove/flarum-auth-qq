import SettingsModal from 'flarum/components/SettingsModal'

export default class QQSettingsModal extends SettingsModal {
  className() {
    return 'QQSettingsModal Modal--small'
  }

  title() {
    return app.translator.trans('hehongyuanlove-auth-qq.admin.qq_settings.title')
  }

  form() {
    return [
      <div className="Form-group">
        <label>{app.translator.trans('hehongyuanlove-auth-qq.admin.qq_settings.client_id_label')}</label>
        <input className="FormControl" bidi={this.setting('hehongyuanlove-auth-qq.client_id')} />
      </div>,

      <div className="Form-group">
        <label>{app.translator.trans('hehongyuanlove-auth-qq.admin.qq_settings.client_secret_label')}</label>
        <input className="FormControl" bidi={this.setting('hehongyuanlove-auth-qq.client_secret')} />
      </div>
    ]
  }
}

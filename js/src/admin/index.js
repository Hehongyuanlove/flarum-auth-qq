import app from 'flarum/app';

app.initializers.add('hehongyuanlove-auth-qq', app => {
  app.extensionData
    .for('hehongyuanlove-auth-qq')
    .registerSetting(
      {
        setting: 'hehongyuanlove-auth-qq.client_id',
        label: app.translator.trans('hehongyuanlove-auth-qq.admin.qq_settings.client_id_label'),
        type: 'text',
      },
      30
    )
    .registerSetting(
      {
        setting: 'hehongyuanlove-auth-qq.client_secret',
        label: app.translator.trans('hehongyuanlove-auth-qq.admin.qq_settings.client_secret_label'),
        type: 'text',
      },
      30
    );
});

import app from "flarum/app";
import QQSettingsModal from "./components/QQSettingsModal";

app.initializers.add("hehongyuanlove-auth-qq", (app) => {
  // 区分新旧版本
  let version = app.data.settings.version.split(".")[0];

  // 旧版本
  if (version < 1) {
    app.extensionSettings["hehongyuanlove-auth-qq"] = () =>
      app.modal.show(new QQSettingsModal());
      return 
  }

  // 新版本
  app.extensionData
    .for("hehongyuanlove-auth-qq")
    .registerSetting(
      {
        setting: "hehongyuanlove-auth-qq.client_id",
        label: app.translator.trans(
          "hehongyuanlove-auth-qq.admin.qq_settings.client_id_label"
        ),
        type: "text",
      },
      30
    )
    .registerSetting(
      {
        setting: "hehongyuanlove-auth-qq.client_secret",
        label: app.translator.trans(
          "hehongyuanlove-auth-qq.admin.qq_settings.client_secret_label"
        ),
        type: "text",
      },
      30
    ).registerSetting({
      setting: 'hehongyuanlove-auth-qq.close_email_register',
      type: 'switch',
      label: "关闭邮箱注册"
    });
  return;
});

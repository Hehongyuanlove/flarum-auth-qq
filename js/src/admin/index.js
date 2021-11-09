import app from "flarum/app";
import QQSettingsModal from './components/QQSettingsModal';

app.initializers.add("hehongyuanlove-auth-qq", (app) => {
  app.extensionSettings["hehongyuanlove-auth-qq"] = () =>
    app.modal.show(new QQSettingsModal());
});

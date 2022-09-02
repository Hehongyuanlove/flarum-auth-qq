import { extend } from "flarum/extend";
import app from "flarum/app";
import LogInButtons from "flarum/components/LogInButtons";
import QQLogInButton from "./components/QQLogInButton";
import AuthQQPage from "./components/AuthQQPage"

app.initializers.add("hehongyuanlove-auth-qq", () => {
  extend(LogInButtons.prototype, "items", function (items) {

    items.add(
      "QQAndH5",
      <QQLogInButton
        className="Button LogInButton--QQ"
        icon="fab fa-qq"
      >
        {app.translator.trans(
          "hehongyuanlove-auth-qq.forum.log_in.with_qq_button"
        )}
      </QQLogInButton>
    );
    return

  });

  app.routes['hhy-auth-qq'] = {
    path: '/auth/qq',
    component: AuthQQPage,
  };
});

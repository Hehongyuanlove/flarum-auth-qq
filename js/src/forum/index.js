import { extend } from 'flarum/extend'
import app from 'flarum/app'
import LogInButtons from 'flarum/components/LogInButtons'
// import LogInButton from 'flarum/components/LogInButton'
import QQLogInButton from './components/QQLogInButton'

app.initializers.add('hehongyuanlove-auth-qq', () => {
  // extend(LogInButtons.prototype, 'items', function(items) {
  //   items.add(
  //     'QQ',
  //     <LogInButton className="Button LogInButton--QQ" icon="fab fa-qq" path="/auth/qq">
  //       {app.translator.trans('hehongyuanlove-auth-qq.forum.log_in.with_qq_button')}
  //     </LogInButton>
  //   )
  // })
  extend(LogInButtons.prototype, 'items', function(items) {
    items.add(
      'QQAndH5',
      <QQLogInButton className="Button LogInButton--QQ" icon="fab fa-qq" path="/auth/qq">
        {app.translator.trans('hehongyuanlove-auth-qq.forum.log_in.with_qq_button')}
      </QQLogInButton>
    )
  })
})

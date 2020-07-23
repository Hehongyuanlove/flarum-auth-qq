import { extend } from 'flarum/extend'
import app from 'flarum/app'
import LogInButtons from 'flarum/components/LogInButtons'
import LogInButton from 'flarum/components/LogInButton'

app.initializers.add('hehongyuanlove/flarum-auth-qq', () => {
  extend(LogInButtons.prototype, 'items', function(items) {
    items.add(
      'QQ',
      <LogInButton className="Button LogInButton--QQ" icon="fa fa-qq" path="/auth/qq">
        {app.translator.trans('hehongyuanlove/flarum-auth-qq.forum.log_in.with_qq_button')}
      </LogInButton>
    )
  })
})

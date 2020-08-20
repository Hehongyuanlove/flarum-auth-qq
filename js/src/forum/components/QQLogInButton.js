import Button from 'flarum/components/Button'

/**
 * The `LogInButton` component displays a social login button which will open
 * a popup window containing the specified path.
 *
 * ### Props
 *
 * - `path`
 */
export default class QQLogInButton extends Button {
  init() {
    this.auths = null

    super.init()
  }

  view() {
    this.props.onclick = this.checkH5.bind(this)
    this.props.className = (this.props.className || '') + ' LogInButton'
    return super.view()
  }

  // static initProps(props) {
  //   props.className = (props.className || '') + ' LogInButton'

  //   props.onclick = function() {
  //     this.checkH5()
  //   }

  //   super.initProps(props)
  // }

  checkH5() {
    if (navigator.userAgent.indexOf('Html5Plus') > -1) {
      this.loading = true
      plus.oauth.getServices(
        services => {
          this.auths = services
          this.authLogin()
        },
        e => {
          alert('获取分享服务列表失败：' + e.message + ' - ' + e.code)
        }
      )
    } else {
      const width = 580
      const height = 400
      const $window = $(window)

      window.open(
        app.forum.attribute('baseUrl') + this.props.path,
        'logInPopup',
        `width=${width},` + `height=${height},` + `top=${$window.height() / 2 - height / 2},` + `left=${$window.width() / 2 - width / 2},` + 'status=no,scrollbars=yes,resizable=no'
      )
    }
  }

  authLogin() {
    var s = this.auths[0]
    // if (!s.authResult) {
    s.login(
      e => {
        // 获取登录操作结果
        var result = e.target.authResult
        //alert('登录认证成功：' + JSON.stringify(result))

        this.authUserInfo()
      },
      e => {
        alert('登录认证失败！')
      },
      {}
    )
    // }
  }

  authLogout() {
    for (var i in this.auths) {
      var s = auths[i]
      if (s.authResult) {
        s.logout(
          function(e) {
            alert('注销登录认证成功！')
          },
          function(e) {
            alert('注销登录认证失败！')
          }
        )
      }
    }
  }
  // 获取登录用户信息操作
  authUserInfo() {
    var s = this.auths[0]
    if (!s.authResult) {
      alert('未登录授权！')
    } else {
      s.getUserInfo(
        e => {
          // alert('获取用户信息成功：' + JSON.stringify(s.userInfo))

          //拿到用户信息，进行相关处理，ajax传用户数据到服务器等
          var prame = JSON.stringify(s.userInfo)
          // fl.himi3d.cn/api/authh5/qq?param={"openid":"sd;fslkdf"}
          m.request({
            method: 'GET',
            url: '/api/authh5/qq?param=' + prame,
            deserialize: function(value) {
              return value
            }
            //params: { prame: JSON.stringify(s.userInfo) }
          }).then(result => {
            result = result.replace('window.close();', '')
            result = result.replace('.opener', '')
            result = result.replace('<script>', '')
            result = result.replace(';</script>', '')
            // console.log(result)
            eval(result)
            // m.mount(document.body, result)
            // window.app.authenticationComplete({ avatar_url: 'www.baidu.com', email: '', username: 'YCCSDA', token: 'sqGHPhfbIMHUGICbX2G7l5t3g3jrQWH5p3NJ3JnB', provided: ['avatar_url'] })
          })

          // console.log('/api/authh5/qq?param=' + prame)
          // console.log(app.forum.attribute('baseUrl'))
          // window.open(app.forum.attribute('baseUrl') + '/api/authh5/qq?param=' + prame)

          // app
          //   .request({
          //     url: '/api/authh5/qq?param=' + prame,
          //     method: 'GET'
          //   })
          //   .then(res => {
          //     console.log(11111111111111)
          //     console.log(res)
          //     // m.mount(document.body, res)
          //   })
        },
        e => {
          alert('获取用户信息失败：' + e.message + ' - ' + e.code)
        }
      )
    }
  }
}

import Modal from 'flarum/components/Modal';
import Button from 'flarum/components/Button';

export default class AuthQQModal extends Modal {

    oninit(vnode) {
        super.oninit(vnode);
        // 发送 code state 给后端
        const { qq_code: code, qq_state: state } = vnode.attrs

        // this.normalTime = 5     // 倒计时
        // this.errorTime = 5      // 错误倒计时
        this.isError = false    // 状态
        this.msgError = ""      // 展示错误信息

        if (!code || !state) {
            this.setError({ msg: "信息缺失", code, state })
            return
        }

        m.request({
            method: "GET",
            url: `/api/auth/qq/${code}/${state}`,
        }).then((result) => {
            console.log("result", result)
            // 区分登录与注册
            if (result.token || result.loggedIn) {
                // 打开弹窗的
                if (window.opener) {
                    window.opener.app.authenticationComplete(result);
                    window.close();
                    return
                }
                app.authenticationComplete(result);
                window.location.href = app.forum.attribute('baseUrl')
                return
            } else {
                //返回信息 可能超出范围
                this.setError(result)
            }
        }).catch((err) => {
            console.log(err);
            this.setError(err)
        });

    }
    className() {
        return `QQ LinkModal Modal--small`;
    }

    setError(obj) {
        this.isError = true
        this.msgError = JSON.stringify(obj)
    }


    content() {
        return (
            <div className="Modal-body">
                <div className="Form Form--centered">
                    {!isError ? <div> 正在登录 请稍候 </div> : ""}
                    {isError ? <div> 登录发生异常 </div> : ""}
                    {isError ? <div> {this.msgError} </div> : ""}
                    {isError ? Button.component({
                        className: "Button",
                        onclick: () => { window.location.href = app.forum.attribute('baseUrl') }
                    }, "返回主页重新登录") : ""}
                </div>
            </div>
        );
    }
}
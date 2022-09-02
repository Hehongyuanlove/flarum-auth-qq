import app from 'flarum/forum/app';
import Page from 'flarum/forum/components/Page';
import Component from 'flarum/forum/Component';
import IndexPage from 'flarum/forum/components/IndexPage';
import AuthQQModal from './AuthQQModal';



export default class AuthQQPage extends IndexPage {
    oncreate(vnode) {
        super.oncreate(vnode);

        // 假如用户已经登录 
        if(app.session.user){
            window.location.href = app.forum.attribute('baseUrl')
            return 
        }


        console.log(vnode)
        const qq_code = vnode.attrs.code
        const qq_state = vnode.attrs.state

        // 检测 code state 不存在的话 报错提示
        if (qq_code && qq_code) {
            setTimeout(() => app.modal.show(AuthQQModal, { qq_code, qq_state }), 200)
        }

    }

    getUrlParam(name) {

        //构造一个含有目标参数的正则表达式对象
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");

        //匹配目标参数
        var r = window.location.search.substr(1).match(reg);

        //返回参数值
        if (r != null) {
            return decodeURI(r[2]);
        }
        return null;
    }


}
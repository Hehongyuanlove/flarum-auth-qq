(()=>{var __webpack_modules__={710:(__unused_webpack_module,__webpack_exports__,__webpack_require__)=>{"use strict";__webpack_require__.d(__webpack_exports__,{Z:()=>QQLogInButton});var _babel_runtime_helpers_esm_inheritsLoose__WEBPACK_IMPORTED_MODULE_1__=__webpack_require__(282),flarum_components_Button__WEBPACK_IMPORTED_MODULE_0__=__webpack_require__(243),flarum_components_Button__WEBPACK_IMPORTED_MODULE_0___default=__webpack_require__.n(flarum_components_Button__WEBPACK_IMPORTED_MODULE_0__),QQLogInButton=function(_Button){function QQLogInButton(){return _Button.apply(this,arguments)||this}(0,_babel_runtime_helpers_esm_inheritsLoose__WEBPACK_IMPORTED_MODULE_1__.Z)(QQLogInButton,_Button),QQLogInButton.initAttrs=function(e){e.authsQQ=this.authsQQ,_Button.initAttrs.call(this,e)};var _proto=QQLogInButton.prototype;return _proto.view=function(e){var t=_Button.prototype.view.call(this,e);return t.attrs.onclick=this.checkH5.bind(this),t.attrs.className+=" LogInButton",t},_proto.checkH5=function(){var e=this;if(console.log(this),navigator.userAgent.indexOf("Html5Plus")>-1)this.loading=!0,plus.oauth.getServices((function(t){for(var _ in t)"qq"==t[_].id&&(e.authsQQ=t[_]);e.authLogin()}),(function(e){alert("获取分享服务列表失败："+e.message+" - "+e.code)}));else{var t=app.forum.attribute("baseUrl")+"/"+this.attrs.path;window.location.href=t}},_proto.authLogin=function(){var e=this;this.authsQQ.login((function(t){t.target.authResult,e.authUserInfo()}),(function(e){alert("登录认证失败！")}),{})},_proto.authLogout=function(){for(var e in this.auths){var t=auths[e];t.authResult&&t.logout((function(e){alert("注销登录认证成功！")}),(function(e){alert("注销登录认证失败！")}))}},_proto.authUserInfo=function authUserInfo(){var s=this.authsQQ;s.authResult?s.getUserInfo((function(e){var pload={openid:s.authResult.openid,access_token:s.authResult.access_token,pay_token:s.authResult.pay_token,nickname:s.userInfo.nickname,figureurl_qq_2:s.userInfo.figureurl_qq_2},prame=escape(JSON.stringify(pload));m.request({method:"GET",url:"/api/authh5/qq?param="+prame,deserialize:function(e){return e}}).then((function(result){result=result.replace("window.close();",""),result=result.replace(".opener",""),result=result.replace("<script>",""),result=result.replace(";<\/script>",""),eval(result)})).catch((function(e){console.log(e)}))}),(function(e){alert("获取用户信息失败："+e.message+" - "+e.code)})):alert("未登录授权！")},QQLogInButton}(flarum_components_Button__WEBPACK_IMPORTED_MODULE_0___default())},243:e=>{"use strict";e.exports=flarum.core.compat["components/Button"]},282:(e,t,_)=>{"use strict";_.d(t,{Z:()=>r});var o=_(806);function r(e,t){e.prototype=Object.create(t.prototype),e.prototype.constructor=e,(0,o.Z)(e,t)}},806:(e,t,_)=>{"use strict";function o(e,t){return o=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e},o(e,t)}_.d(t,{Z:()=>o})}},__webpack_module_cache__={};function __webpack_require__(e){var t=__webpack_module_cache__[e];if(void 0!==t)return t.exports;var _=__webpack_module_cache__[e]={exports:{}};return __webpack_modules__[e](_,_.exports,__webpack_require__),_.exports}__webpack_require__.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return __webpack_require__.d(t,{a:t}),t},__webpack_require__.d=(e,t)=>{for(var _ in t)__webpack_require__.o(t,_)&&!__webpack_require__.o(e,_)&&Object.defineProperty(e,_,{enumerable:!0,get:t[_]})},__webpack_require__.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),__webpack_require__.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})};var __webpack_exports__={};(()=>{"use strict";__webpack_require__.r(__webpack_exports__);const e=flarum.core.compat.extend,t=flarum.core.compat.app;var _=__webpack_require__.n(t);const o=flarum.core.compat["components/LogInButtons"];var r=__webpack_require__.n(o),n=__webpack_require__(710);_().initializers.add("hehongyuanlove-auth-qq",(function(){(0,e.extend)(r().prototype,"items",(function(e){e.add("QQAndH5",m(n.Z,{className:"Button LogInButton--QQ",icon:"fab fa-qq",path:"/api/auth/qq"},_().translator.trans("hehongyuanlove-auth-qq.forum.log_in.with_qq_button")))}))}))})(),module.exports=__webpack_exports__})();
//# sourceMappingURL=forum.js.map
module.exports=function(t){var n={};function o(e){if(n[e])return n[e].exports;var a=n[e]={i:e,l:!1,exports:{}};return t[e].call(a.exports,a,a.exports,o),a.l=!0,a.exports}return o.m=t,o.c=n,o.d=function(t,n,e){o.o(t,n)||Object.defineProperty(t,n,{enumerable:!0,get:e})},o.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},o.t=function(t,n){if(1&n&&(t=o(t)),8&n)return t;if(4&n&&"object"==typeof t&&t&&t.__esModule)return t;var e=Object.create(null);if(o.r(e),Object.defineProperty(e,"default",{enumerable:!0,value:t}),2&n&&"string"!=typeof t)for(var a in t)o.d(e,a,function(n){return t[n]}.bind(null,a));return e},o.n=function(t){var n=t&&t.__esModule?function(){return t.default}:function(){return t};return o.d(n,"a",n),n},o.o=function(t,n){return Object.prototype.hasOwnProperty.call(t,n)},o.p="",o(o.s=9)}([function(t,n){t.exports=flarum.core.compat.app},function(t,n,o){"use strict";function e(t,n){t.prototype=Object.create(n.prototype),t.prototype.constructor=t,t.__proto__=n}o.d(n,"a",(function(){return e}))},function(t,n){t.exports=flarum.core.compat["components/Button"]},function(t,n){t.exports=flarum.core.compat["components/Modal"]},function(t,n){t.exports=flarum.core.compat.extend},function(t,n){t.exports=flarum.core.compat["components/SettingsPage"]},function(t,n){t.exports=flarum.core.compat["components/LogInButtons"]},function(t,n){t.exports=flarum.core.compat["components/LogInButton"]},,function(t,n,o){"use strict";o.r(n);var e=o(4),a=o(0),r=o.n(a),u=o(5),i=o.n(u),s=o(6),l=o.n(s),c=o(7),d=o.n(c),p=o(2),f=o.n(p),h=o(1),b=o(3),g=o.n(b),v={module:{name:"hehongyuanlove-auth-qq",id:"QQ",icon:"fab fa-qq"},package:{name:"hehongyuanlove-auth-qq"},api:{uri:"/api/auth/qq"}},y=function(t){function n(){return t.apply(this,arguments)||this}Object(h.a)(n,t);var o=n.prototype;return o.className=function(){return v.module.id+"UnlinkModal Modal--small"},o.title=function(){return app.translator.trans(v.module.name+".forum.modals.unlink.title")},o.content=function(){var t=this,n=app.session.user.data.attributes.QQAuth.providersCount,o=void 0===n?0:n;return m("div",{className:"Modal-body"},m("div",{className:"Form Form--centered"},m("div",{className:"Form-group",id:"submit-button-group"},m("h3",null,app.translator.trans(v.module.name+".forum.modals.unlink.info.confirm")),o<=1?m("p",{className:v.module.id+"Text--danger"},m("i",{className:"fas fa-exclamation-triangle fa-fw"}),m("b",null,app.translator.trans(v.module.name+".forum.modals.unlink.info.no_providers"))):"",m("br",null),m("div",{className:"ButtonGroup"},m(f.a,{type:"submit",className:"Button "+v.module.id+"Button--danger",icon:"fas fa-exclamation-triangle",loading:this.loading},app.translator.trans(v.module.name+".forum.modals.unlink.buttons.confirm")),m(f.a,{className:"Button Button--primary",icon:"fas fa-exclamation-triangle",onclick:function(){return t.hide()},disabled:this.loading},app.translator.trans(v.module.name+".forum.modals.unlink.buttons.cancel"))))))},o.onsubmit=function(t){var n,o=this;t.preventDefault(),this.loading=!0,app.request({method:"POST",url:""+app.forum.attribute("baseUrl")+v.api.uri+"/unlink"}).then((function(){app.session.user.savePreferences(),o.hide(),n=app.alerts.show({type:"success"},app.translator.trans(v.module.name+".forum.alerts.unlink_success"))})),setTimeout((function(){app.alerts.dismiss(n)}),5e3)},n}(g.a),k=function(t){function n(){return t.apply(this,arguments)||this}Object(h.a)(n,t);var o=n.prototype;return o.className=function(){return v.module.id+"LinkModal Modal--small"},o.title=function(){return app.translator.trans(v.module.name+".forum.modals.link.title")},o.content=function(){var t=this;return m("div",{className:"Modal-body"},m("div",{className:"Form Form--centered"},m("div",{className:"Form-group"},m(f.a,{className:"Button LogInButton--"+v.module.id,icon:v.module.icon,loading:this.loading,disabled:this.loading,path:"/auth/"+name,onclick:function(){return t.showLogin()}},app.translator.trans(v.module.name+".forum.buttons.login")))))},o.showLogin=function(){var t=$(window);window.open(""+app.forum.attribute("baseUrl")+v.api.uri+"/link",v.module.id+"LinkPopup","width=600,height=400,top="+(t.height()/2-200)+",left="+(t.width()/2-300)+",status=no,scrollbars=no,resizable=no"),this.loading=!0},n}(g.a);r.a.initializers.add("hehongyuanlove-auth-qq",(function(){Object(e.extend)(i.a.prototype,"accountItems",(function(t){var n=r.a.session.user.data.attributes.QQAuth.isLinked,o=void 0!==n&&n;t.add("link"+v.module.id,m(f.a,{className:"Button "+v.module.id+"Button--"+(o?"danger":"success"),icon:v.module.icon,path:"/auth/"+name,onclick:function(){return r.a.modal.show(o?y:k)}},r.a.translator.trans(v.module.name+".forum.buttons."+(o?"unlink":"link"))))})),Object(e.extend)(l.a.prototype,"items",(function(t){t.add(v.package.id,m(d.a,{className:"Button LogInButton--"+v.module.id,icon:v.module.icon,path:v.api.uri},r.a.translator.trans(v.module.name+".forum.log_in.with_qq_button")))}))}))}]);
//# sourceMappingURL=forum.js.map
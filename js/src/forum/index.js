import { extend, override } from "flarum/extend";
import app from "flarum/app";

import SettingsPage from 'flarum/components/SettingsPage';
import LogInButtons from "flarum/components/LogInButtons";
import LogInButton from 'flarum/components/LogInButton';
import SignUpModal from 'flarum/components/SignUpModal';

import Button from 'flarum/components/Button';
import ItemList from 'flarum/utils/ItemList';

import UnlinkModal from "./components/UnLinkModal";
import LinkModal from "./components/LinkModal";
import config from '../config';

app.initializers.add("hehongyuanlove-auth-qq", () => {

    extend(SettingsPage.prototype, 'accountItems', (items) => {
        const {
            data: {
                attributes: {
                    QQAuth: {
                        isLinked = false
                    },
                },
            },
        } = app.session.user;

        items.add(`link${config.module.id}`,
            <Button className={`Button ${config.module.id}Button--${isLinked ? 'danger' : 'success'}`} icon={config.module.icon}
                path={`/auth/${name}`} onclick={() => app.modal.show(isLinked ? UnlinkModal : LinkModal)}>
                {app.translator.trans(`${config.module.name}.forum.buttons.${isLinked ? 'unlink' : 'link'}`)}
            </Button>
        );
    });


    extend(LogInButtons.prototype, 'items', (items) => {
        items.add(config.module.id,
            <LogInButton
                className={`Button LogInButton--${config.module.id}`}
                icon={config.module.icon}
                path={config.api.uri}>
                {app.translator.trans(`${config.module.name}.forum.log_in.with_qq_button`)}
            </LogInButton>
        );
    });

    //SignUpModal
    override(SignUpModal.prototype, 'body', function (original) {

        const removeEmailRegister = app.forum.attribute("removeEmailRegister")
        if (removeEmailRegister) {
            return [!this.attrs.token && <LogInButtons />];
        }
        return original();
    });
}, -10);

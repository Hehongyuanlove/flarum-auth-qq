import Modal from 'flarum/components/Modal';
import Button from 'flarum/components/Button';

export default class LinkModal extends Modal {
    className() {
        return `QQ LinkModal Modal--small`;
    }

    content() {
        return (
            <div className="Modal-body">
                <div className="Form Form--centered">
                    <div className="Form-group">
                        <Button className={`Button LogInButton--${config.module.id}`} icon={config.module.icon} loading={this.loading} disabled={this.loading}
                            path={`/auth/${name}`} onclick={() => this.showLogin()}>
                            {app.translator.trans(`${config.module.name}.forum.buttons.login`)}
                        </Button>
                    </div>
                </div>
            </div>
        );
    }
}
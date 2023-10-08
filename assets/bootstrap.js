import '@symfony/ux-live-component/styles/live.css';
import LiveController from '@symfony/ux-live-component';
import { startStimulusApp } from '@symfony/stimulus-bridge';

export const app = startStimulusApp(require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.[jt]sx?$/
));

app.register('live', LiveController);
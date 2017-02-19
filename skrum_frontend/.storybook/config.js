import { configure } from '@kadira/storybook';

function loadStories() {
  require('../stories/auth');
}

configure(loadStories, module);

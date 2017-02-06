import { configure } from '@kadira/storybook';
import '../public/css/reset.css';
import '../public/css/base.css';

function loadStories() {
  require('../stories');
}

configure(loadStories, module);

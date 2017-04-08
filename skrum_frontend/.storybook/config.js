import { configure } from '@kadira/storybook';

function loadStories() {
  require('../stories/auth');
  require('../stories/component/menubar');
  require('../stories/component/sidebar');
  require('../stories/component/okr-bar');
  require('../stories/component/kr-bar');
  require('../stories/component/kr-weighting-bar');
  require('../stories/component/group-bar');
  require('../stories/component/user-info-panel');
  require('../stories/component/group-info-panel');
  require('../stories/component/post-bar');
  require('../stories/component/comment-bar');
  require('../stories/container/okr-list');
  require('../stories/container/group-list');
  require('../stories/container/timeline-bar');
  require('../stories/dialogs/okr-copy-move');
  require('../stories/dialogs/kr-weighting-setting');
  require('../stories/dialogs/okr-delete');
  require('../stories/dialogs/okr-close');
  require('../stories/dialogs/okr-open');
}

configure(loadStories, module);

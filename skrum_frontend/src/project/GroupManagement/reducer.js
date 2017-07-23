import { Action } from './action';
import { mergeUpdateById } from '../../util/ActionUtil';

export default (state = {
  user: {},
  group: {},
  isFetching: false,
  isPutting: false,
  isChangingLeader: false,
}, action) => {
  switch (action.type) {
    case Action.REQUEST_FETCH_USER_GROUPS:
    case Action.REQUEST_FETCH_GROUP_MEMBERS:
      return Object.assign({}, state, { isFetching: true });

    case Action.FINISH_FETCH_USER_GROUPS:
    case Action.FINISH_FETCH_GROUP_MEMBERS: {
      const { payload, error } = action;
      if (error) {
        return Object.assign({}, state, {
          isFetching: false,
          error: {
            message: payload.message,
          },
        });
      }
      return Object.assign({}, state, {
        isFetching: false,
        ...payload,
        error: null,
      });
    }

    case Action.REQUEST_PUT_USER: {
      const { payload } = action;
      const { id, ...data } = payload.data;
      const { user, groups } = state.user;
      const newUser = mergeUpdateById(user, 'userId', data, id);
      return { ...state, user: { user: newUser, groups }, isPutting: true };
    }

    case Action.REQUEST_PUT_GROUP: {
      const { payload } = action;
      const { id, ...data } = payload.data;
      const { group, members } = state.group;
      const newGroup = mergeUpdateById(group, 'groupId', data, id);
      return { ...state, group: { group: newGroup, members }, isPutting: true };
    }

    case Action.FINISH_PUT_USER:
    case Action.FINISH_PUT_GROUP: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isPutting: false, error: { message: payload.message } };
      }
      return { ...state, isPutting: false, error: null };
    }

    case Action.REQUEST_CHANGE_GROUP_LEADER:
      return { ...state, isChangingLeader: true };

    case Action.FINISH_CHANGE_GROUP_LEADER: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isChangingLeader: false, error: { message: payload.message } };
      }
      const { userId: leaderUserId, userName: leaderName } = payload.changeGroupLeader;
      const { group: groupRoot } = state;
      const { group: groupInfo } = groupRoot;
      const group = { ...groupRoot, group: { ...groupInfo, leaderUserId, leaderName } };
      return { ...state, group, isChangingLeader: false, error: null };
    }

    default:
      return state;
  }
};

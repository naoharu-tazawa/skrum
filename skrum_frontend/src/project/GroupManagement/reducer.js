import { Action } from './action';
import { mergeUpdateById } from '../../util/ActionUtil';

export default (state = {
  user: {},
  group: {},
  isFetching: false,
  isPutting: false,
  isChangingLeader: false,
  isAddingGroupMember: false,
  isDeletingGroupMember: false,
  isJoiningGroup: false,
  isLeavingGroup: false,
}, action) => {
  switch (action.type) {
    case Action.REQUEST_FETCH_USER_GROUPS:
      return { ...state, isFetching: true };

    case Action.FINISH_FETCH_USER_GROUPS: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isFetching: false, error: { message: payload.message } };
      }
      return { ...state, isFetching: false, user: payload.data, error: null };
    }

    case Action.REQUEST_FETCH_GROUP_MEMBERS:
      return { ...state, isFetching: true };

    case Action.FINISH_FETCH_GROUP_MEMBERS: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isFetching: false, error: { message: payload.message } };
      }
      return { ...state, isFetching: false, group: payload.data, error: null };
    }

    case Action.REQUEST_PUT_USER:
      return { ...state, isPutting: true };

    case Action.FINISH_PUT_USER: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isPutting: false, error: { message: payload.message } };
      }
      const { id, ...data } = payload.data;
      const { user, groups } = state.user;
      const newUser = mergeUpdateById(user, 'userId', data, id);
      return { ...state, user: { user: newUser, groups }, isPutting: false, error: null };
    }

    case Action.REQUEST_PUT_GROUP:
      return { ...state, isPutting: true };

    case Action.FINISH_PUT_GROUP: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isPutting: false, error: { message: payload.message } };
      }
      const { id, ...data } = payload.data;
      const { group, members } = state.group;
      const newGroup = mergeUpdateById(group, 'groupId', data, id);
      return { ...state, group: { group: newGroup, members }, isPutting: false, error: null };
    }

    case Action.REQUEST_CHANGE_GROUP_LEADER:
      return { ...state, isChangingLeader: true };

    case Action.FINISH_CHANGE_GROUP_LEADER: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isChangingLeader: false, error: { message: payload.message } };
      }
      const { userId: leaderUserId, userName: leaderName } = payload.data;
      const { group: groupRoot } = state;
      const { group: groupInfo } = groupRoot;
      const group = { ...groupRoot, group: { ...groupInfo, leaderUserId, leaderName } };
      return { ...state, group, isChangingLeader: false, error: null };
    }

    case Action.REQUEST_ADD_GROUP_MEMBER:
      return { ...state, isAddingGroupMember: true };

    case Action.FINISH_ADD_GROUP_MEMBER: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isAddingGroupMember: false, error: { message: payload.message } };
      }
      const { group, members } = state.group;
      const newMembers = [...members, payload.data.user];
      const newGroup = { group, members: newMembers };
      return { ...state, group: newGroup, isAddingGroupMember: false, error: null };
    }

    case Action.REQUEST_DELETE_GROUP_MEMBER:
      return { ...state, isDeletingGroupMember: true };

    case Action.FINISH_DELETE_GROUP_MEMBER: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isDeletingGroupMember: false, error: { message: payload.message } };
      }
      const { group, members } = state.group;
      const newMembers = members.filter(({ userId }) =>
        userId !== payload.data.userId);
      const newGroup = { group, members: newMembers };
      return { ...state, group: newGroup, isDeletingGroupMember: false, error: null };
    }

    case Action.REQUEST_JOIN_GROUP:
      return { ...state, isJoiningGroup: true };

    case Action.FINISH_JOIN_GROUP: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isJoiningGroup: false, error: { message: payload.message } };
      }
      const { user, groups } = state.user;
      const newGroups = [...groups, payload.data.group];
      const newUser = { user, groups: newGroups };
      return { ...state, user: newUser, isJoiningGroup: false, error: null };
    }

    case Action.REQUEST_LEAVE_GROUP:
      return { ...state, isLeavingGroup: true };

    case Action.FINISH_LEAVE_GROUP: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isLeavingGroup: false, error: { message: payload.message } };
      }
      const { user, groups } = state.user;
      const newGroups = groups.filter(({ groupId }) =>
        groupId !== payload.data.groupId);
      const newUser = { user, groups: newGroups };
      return { ...state, user: newUser, isLeavingGroup: false, error: null };
    }

    default:
      return state;
  }
};

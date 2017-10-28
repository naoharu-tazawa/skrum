import { Action } from './action';
import { mergeUpdateById } from '../../util/ActionUtil';

export default (state = {
  isFetchingRoles: false,
  isFetchingUsers: false,
  isPostingInvite: false,
  isResettingPassword: false,
  isAssigningRole: false,
  isDeletingUser: false,
  roles: [],
  count: 0,
  users: [],
}, { type: actionType, payload, error }) => {
  switch (actionType) {
    case Action.REQUEST_FETCH_COMPANY_ROLES:
      return { ...state, isFetchingRoles: true };

    case Action.FINISH_FETCH_COMPANY_ROLES: {
      if (error) {
        return { ...state, isFetchingRoles: false, error: { message: payload.message } };
      }
      return { ...state, isFetchingRoles: false, roles: payload.data, error: null };
    }

    case Action.REQUEST_FETCH_USERS:
      return { ...state, isFetchingUsers: true };

    case Action.FINISH_FETCH_USERS: {
      if (error) {
        return { ...state, isFetchingUsers: false, error: { message: payload.message } };
      }
      const { count, results: users } = payload.data;
      return { ...state, isFetchingUsers: false, count, users, error: null };
    }

    case Action.REQUEST_POST_INVITE:
      return { ...state, isPostingInvite: true };

    case Action.FINISH_POST_INVITE: {
      if (error) {
        return { ...state, isPostingInvite: false, error: { message: payload.message } };
      }
      return { ...state, isPostingInvite: false, error: null };
    }

    case Action.REQUEST_RESET_PASSWORD:
      return { ...state, isResettingPassword: true };

    case Action.FINISH_RESET_PASSWORD: {
      if (error) {
        return { ...state, isResettingPassword: false, error: { message: payload.message } };
      }
      return { ...state, isResettingPassword: false, error: null };
    }

    case Action.REQUEST_ASSIGN_ROLE:
      return { ...state, isAssigningRole: true };

    case Action.FINISH_ASSIGN_ROLE: {
      if (error) {
        return { ...state, isAssigningRole: false, error: { message: payload.message } };
      }
      const { id, data } = payload.data;
      const users = state.users.map(user => mergeUpdateById(user, 'userId', data, id));
      return { ...state, users, isAssigningRole: false, error: null };
    }

    case Action.REQUEST_DELETE_USER:
      return { ...state, isDeletingUser: true };

    case Action.FINISH_DELETE_USER: {
      if (error) {
        return { ...state, isDeletingUser: false, error: { message: payload.message } };
      }
      const { id } = payload.data;
      const users = state.users.filter(user => user.userId !== id);
      return { ...state, users, isDeletingUser: false, error: null };
    }

    default:
      return state;
  }
};

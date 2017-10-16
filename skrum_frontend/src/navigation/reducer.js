import { Action } from './action';
import { GroupType } from '../util/GroupUtil';

export default (state = {
  needsFetching: true,
  isFetching: false,
  isPosting: false,
  data: {},
}, { type, payload, error }) => {
  switch (type) {
    case Action.REQUEST_FETCH_USER_TOP:
      return { ...state, needsFetching: false, isFetching: true };

    case Action.FINISH_FETCH_USER_TOP: {
      if (error) {
        return { ...state, isFetching: false, error: { message: payload.message } };
      }
      return { ...state, isFetching: false, data: payload.data, error: null };
    }

    case Action.REQUIRE_FETCH_USER_TOP:
      return { ...state, needsFetching: true };

    case Action.REQUEST_SETUP_COMPANY:
    case Action.REQUEST_SETUP_USER:
      return { ...state, isPosting: true };

    case Action.FINISH_SETUP_COMPANY:
    case Action.FINISH_SETUP_USER: {
      if (error) {
        return { ...state, isPosting: false, error: { message: payload.message } };
      }
      return { ...state, isPosting: false, error: null };
    }

    case Action.ADD_GROUP: {
      if (error) return state;
      const { groupId, groupName, groupType } = payload.data.group;
      const { teams, departments, ...others } = state.data;
      const group = { groupId, groupName };
      const data = {
        teams: groupType === GroupType.TEAM ? [...teams, group] : teams,
        departments: groupType === GroupType.DEPARTMENT ? [...departments, group] : departments,
        ...others,
      };
      return { ...state, data };
    }

    case Action.REMOVE_GROUP: {
      if (error) return state;
      const { groupId } = payload.data;
      const { teams, departments, ...others } = state.data;
      const data = {
        teams: teams.filter(team => team.groupId !== groupId),
        departments: departments.filter(dept => dept.groupId !== groupId),
        ...others,
      };
      return { ...state, data };
    }

    default:
      return state;
  }
};

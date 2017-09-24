import { Action } from './action';
import { mergeUpdateById } from '../../util/ActionUtil';

export default (state = {
  isFetching: false,
  isFetchingMore: false,
  hasMorePosts: false,
  isPosting: false,
  isChangingDisclosureType: false,
  isDeleting: false,
  isPostingLike: false,
  isDeletingLike: false,
  isPostingReply: false,
  posts: [],
}, action) => {
  switch (action.type) {
    case Action.REQUEST_FETCH_USER_POSTS:
    case Action.REQUEST_FETCH_GROUP_POSTS:
    case Action.REQUEST_FETCH_COMPANY_POSTS:
      return { ...state, isFetching: true };

    case Action.FINISH_FETCH_USER_POSTS:
    case Action.FINISH_FETCH_GROUP_POSTS:
    case Action.FINISH_FETCH_COMPANY_POSTS: {
      const { payload, error } = action;
      if (error) {
        const { message } = payload;
        return { ...state, posts: [], isFetching: false, hasMorePosts: false, error: { message } };
      }
      const hasMorePosts = payload.data.length > 0;
      return { ...state, posts: payload.data, isFetching: false, hasMorePosts, error: null };
    }

    case Action.REQUEST_MORE_USER_POSTS:
    case Action.REQUEST_MORE_GROUP_POSTS:
    case Action.REQUEST_MORE_COMPANY_POSTS:
      return { ...state, isFetchingMore: true };

    case Action.FINISH_MORE_USER_POSTS:
    case Action.FINISH_MORE_GROUP_POSTS:
    case Action.FINISH_MORE_COMPANY_POSTS: {
      const { payload, error } = action;
      if (error) {
        const { message } = payload;
        return { ...state, isFetchingMore: false, hasMorePosts: false, error: { message } };
      }
      const hasMorePosts = payload.data.length > 0;
      const posts = [...state.posts, ...payload.data];
      return { ...state, posts, isFetchingMore: false, hasMorePosts, error: null };
    }

    case Action.REQUEST_POST_GROUP_POST:
    case Action.REQUEST_POST_COMPANY_POST:
      return { ...state, isPosting: true };

    case Action.FINISH_POST_GROUP_POST:
    case Action.FINISH_POST_COMPANY_POST: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isPosting: false, error: { message: payload.message } };
      }
      return { ...state, posts: [payload.data, ...state.posts], isPosting: false, error: null };
    }

    case Action.REQUEST_CHANGE_POST_DISCLOSURE_TYPE:
      return { ...state, isChangingDisclosureType: true };

    case Action.FINISH_CHANGE_POST_DISCLOSURE_TYPE: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isChangingDisclosureType: false, error: { message: payload.message } };
      }
      const { postId, ...update } = payload.data;
      const posts = state.posts.map(post => mergeUpdateById(post, 'postId', update, postId));
      return { ...state, posts, isChangingDisclosureType: false, error: null };
    }

    case Action.REQUEST_DELETE_POST:
      return { ...state, isDeleting: true };

    case Action.FINISH_DELETE_POST: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isDeleting: false, error: { message: payload.message } };
      }
      const { postId } = payload.data;
      const posts = state.posts.filter(post => post.postId !== postId);
      return { ...state, posts, isDeleting: false, error: null };
    }

    case Action.REQUEST_POST_LIKE:
      return { ...state, isPostingLike: true };

    case Action.FINISH_POST_LIKE: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isPostingLike: false, error: { message: payload.message } };
      }
      const { postId } = payload.data;
      const posts = state.posts.map(post => (post.postId !== postId ? post :
        { ...post, likedFlg: 1, likesCount: post.likesCount + 1 }));
      return { ...state, posts, isPostingLike: false, error: null };
    }

    case Action.REQUEST_DELETE_LIKE:
      return { ...state, isDeletingLike: true };

    case Action.FINISH_DELETE_LIKE: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isDeletingLike: false, error: { message: payload.message } };
      }
      const { postId } = payload.data;
      const posts = state.posts.map(post => (post.postId !== postId ? post :
        { ...post, likedFlg: 0, likesCount: post.likesCount - 1 }));
      return { ...state, posts, isDeletingLike: false, error: null };
    }

    case Action.REQUEST_POST_REPLY:
      return { ...state, isPostingReply: true };

    case Action.FINISH_POST_REPLY: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isPostingReply: false, error: { message: payload.message } };
      }
      const { postId, reply } = payload.data;
      const posts = state.posts.map(post => (post.postId !== postId ? post :
          { ...post, replies: [...(post.replies || []), reply] }));
      return { ...state, posts, isPostingReply: false, error: null };
    }

    default:
      return state;
  }
};

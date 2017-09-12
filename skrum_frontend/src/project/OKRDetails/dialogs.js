import React from 'react';
import { toNumber, isNumber, sum, round, fromPairs, toPairs, isEmpty } from 'lodash';
import ConfirmationPrompt from '../../dialogs/ConfirmationPrompt';
import DialogForm from '../../dialogs/DialogForm';
import NumberInput from '../../editors/NumberInput';
import EntitySubject from '../../components/EntitySubject';
import DisclosureTypeOptions from '../../components/DisclosureTypeOptions';
import OwnerSearch from '../OwnerSearch/OwnerSearch';
import OKRSearch from '../OKRSearch/OKRSearch';
import styles from './dialogs.css';

const overrideKeyResults = (keyResults, data) => {
  const ratioFallback = (ratio, krLocked, krRatio, fallback) => {
    if (isNumber(ratio)) return ratio;
    if (ratio === null) return fallback;
    return krLocked ? toNumber(krRatio) : fallback;
  };
  const lockedRatios = keyResults.map(({ id, weightedAverageRatio, ratioLockedFlg }) =>
    ratioFallback(data[id], ratioLockedFlg, weightedAverageRatio, null))
    .filter(ratio => isNumber(ratio));
  const lockedRatiosSum = sum(lockedRatios);
  const unlockedCount = keyResults.length - lockedRatios.length;
  const unlockedRatio = round((100 - lockedRatiosSum) / unlockedCount, 1);
  return {
    lockedRatiosSum,
    unlockedCount,
    overrides: fromPairs(
      keyResults.map(({ id, weightedAverageRatio, ratioLockedFlg }) => (
        [id, {
          weightedAverageRatio:
            ratioFallback(data[id], ratioLockedFlg, weightedAverageRatio, unlockedRatio),
          ratioLockedFlg: data[id] !== null && (isNumber(data[id]) || ratioLockedFlg) ? 1 : 0,
        }]
      ))),
  };
};

export const setRatiosDialog =
  // eslint-disable-next-line react/prop-types
  ({ id, name, owner, keyResults, dispatch, onClose }) => (
    <DialogForm
      title="サブ目標の目標への影響度合い設定"
      submitButton="設定"
      constrainHeight
      onSubmit={data => (!data ? Promise.resolve() :
        dispatch(id,
          toPairs(overrideKeyResults(keyResults, data).overrides)
            .filter(([, { ratioLockedFlg }]) => ratioLockedFlg)
            .map(([keyResultId, { weightedAverageRatio }]) =>
              ({ keyResultId: toNumber(keyResultId), weightedAverageRatio }))))
      }
      onClose={onClose}
    >
      {({ setFieldData, data }) => {
        const { lockedRatiosSum, /* unlockedCount, */overrides } =
          overrideKeyResults(keyResults, data);
        // console.log({ data, overrides });
        return (
          <div>
            <EntitySubject entity={owner} heading="対象目標" subject={name} />
            {keyResults.map((kr) => {
              const {
                weightedAverageRatio: ratio = toNumber(kr.weightedAverageRatio),
                ratioLockedFlg: locked = kr.ratioLockedFlg,
              } = overrides[kr.id] || {};
              const maxRatio = (100 - lockedRatiosSum) + (locked ? ratio : 0);
              // console.log({ id: kr.id, locked, ratio });
              return (<EntitySubject
                key={kr.id}
                plain
                componentClassName={styles.ratioBar}
                entityClassName={styles.ratioEntity}
                entity={kr.owner}
                entityStyle={{ width: '10em' }}
                subject={(
                  <div className={styles.ratioDetails}>
                    <div className={styles.ratioKR}>{kr.name}</div>
                    <div className={styles.ratio}>
                      <NumberInput
                        min={0}
                        max={maxRatio}
                        decimalPrecision={1}
                        value={locked ? ratio : ''}
                        placeholder={locked ? '' : `${ratio.toFixed(1)}`}
                        // disabled={unlockedCount === 0 || (unlockedCount === 1 && !locked)}
                        onChange={e =>
                          setFieldData({
                            [kr.id]: Math.min(Math.max(toNumber(e.target.value), 0), maxRatio),
                          })}
                      />
                      <span>%</span>
                      <button
                        type="button"
                        onClick={() =>
                          setFieldData({ [kr.id]: locked ? null : ratio })}
                      >
                        <img src={locked ? '/img/lock.png' : '/img/unlock.png'} alt="" />
                      </button>
                    </div>
                  </div>
                )}
              />);
            })}
          </div>);
      }}
    </DialogForm>);

const changeOwnerDialog =
  // eslint-disable-next-line react/prop-types
  ({ heading, id, name, owner, parentOkrOwner, dispatch, onSuccess, onClose }) => (
    <DialogForm
      title="担当者の変更"
      submitButton="変更"
      onSubmit={({ changedOwner } = {}) =>
        dispatch(id, changedOwner).then(({ error, payload }) => {
          if (onSuccess && !error) onSuccess({ payload });
          return { error, payload };
        })}
      valid={({ changedOwner }) => !isEmpty(changedOwner)}
      onClose={onClose}
    >
      {({ setFieldData }) =>
        <div>
          <EntitySubject entity={owner} heading={heading} subject={name} />
          <section>
            <label>担当者検索</label>
            <OwnerSearch
              onChange={value => setFieldData({ changedOwner: value })}
              exclude={[owner, parentOkrOwner]}
            />
          </section>
        </div>}
    </DialogForm>);

export const changeOkrOwnerDialog = props =>
  changeOwnerDialog({ ...props, heading: '担当者を変更する目標' });

export const changeKROwnerDialog = props =>
  changeOwnerDialog({ ...props, heading: '担当者を変更するサブ目標' });

const changeParentDialog =
  // eslint-disable-next-line react/prop-types
  ({ title, parentHeading, childHeading, id, parentOkr = {}, okr, dispatch, onClose }) => (
    <DialogForm
      title={title}
      submitButton="変更"
      onSubmit={({ changedParent } = {}) => dispatch(id, changedParent.id)}
      valid={({ changedParent }) => !isEmpty(changedParent)}
      onClose={onClose}
    >
      {({ setFieldData }) =>
        <div>
          <EntitySubject
            entity={okr.owner}
            heading={parentHeading}
            subject={okr.name}
          />
          <div className={styles.parentOkrToChange}>
            <EntitySubject
              entity={parentOkr.owner}
              heading={childHeading}
              subject={parentOkr.name}
              componentClassName={styles.parentEntitySubject}
            />
          </div>
          <section>
            <label>紐付け先検索</label>
            <OKRSearch
              owner={okr.owner}
              exclude={[parentOkr.id]}
              onChange={value => setFieldData({ changedParent: value })}
            />
          </section>
        </div>}
    </DialogForm>);

export const changeOkrParentDialog = props =>
  changeParentDialog({ ...props,
    title: '目標の紐付け先設定/変更',
    parentHeading: '紐付け先目標を設定または変更する目標（子目標）',
    childHeading: '現在の紐付け先目標/サブ目標（親目標）' });

export const changeKRParentDialog = props =>
  changeParentDialog({ ...props,
    title: 'サブ目標の紐付け先変更',
    parentHeading: '紐付け先目標を変更するサブ目標（子目標）',
    childHeading: '現在の紐付け先目標（親目標）' });

const changeDisclosureTypeDialog =
  // eslint-disable-next-line react/prop-types
  ({ heading, id, name, owner, disclosureType, dispatch, onClose }) => (
    <DialogForm
      title="公開範囲設定変更"
      submitButton="設定"
      onSubmit={({ changedDisclosureType } = {}) =>
        (!changedDisclosureType || changedDisclosureType === disclosureType ? Promise.resolve() :
          dispatch(id, changedDisclosureType))}
      onClose={onClose}
    >
      {({ setFieldData }) =>
        <div>
          <EntitySubject entity={owner} heading={heading} subject={name} />
          <section>
            <label>公開範囲</label>
            <DisclosureTypeOptions
              entityType={owner.type}
              renderer={({ value, label }) => (
                <label key={value}>
                  <input
                    name="disclosureType"
                    type="radio"
                    defaultChecked={value === disclosureType}
                    onClick={() => setFieldData({ changedDisclosureType: value })}
                  />
                  {label}
                </label>)}
            />
          </section>
        </div>}
    </DialogForm>);

export const changeOkrDisclosureTypeDialog = props =>
  changeDisclosureTypeDialog({ ...props, heading: '対象目標' });

export const changeKRDisclosureTypeDialog = props =>
  changeDisclosureTypeDialog({ ...props, heading: '対象サブ目標' });

const deletePrompt =
  // eslint-disable-next-line react/prop-types
  ({ title, prompt, warnings, id, name, owner, dispatch, onSuccess, onClose }) => (
    <ConfirmationPrompt
      title={title}
      prompt={prompt}
      warning={(
        <ul>
          {warnings.map((warning, index) => (
            <li key={index}>{warning}</li>
          ))}
        </ul>
      )}
      confirmButton="削除"
      onConfirm={() => dispatch(id).then(({ error, payload }) => {
        if (onSuccess && !error) onSuccess({ payload });
        return { error, payload };
      })}
      onClose={onClose}
    >
      <EntitySubject entity={owner} subject={name} />
    </ConfirmationPrompt>);

export const deleteOkrPrompt = props =>
  deletePrompt({ ...props,
    title: '目標の削除',
    prompt: 'こちらの目標を削除しますか？',
    warnings: [
      '一度削除した目標は復元できません。',
      '上記の目標に直接紐づいている全ての目標/サブ目標、およびその下に紐づいている全ての目標/サブ目標も同時に削除されます。',
    ] });

export const deleteKRPrompt = props =>
  deletePrompt({ ...props,
    title: 'サブ目標の削除',
    prompt: 'こちらのサブ目標を削除しますか？',
    warnings: [
      '一度削除したサブ目標は復元できません。',
      '上記のサブ目標に直接紐づいている全ての目標/サブ目標、およびその下に紐づいている全ての目標/サブ目標も同時に削除されます。',
    ] });

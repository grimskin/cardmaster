import React, {useEffect, useRef, useState} from 'react';
import ConditionItem from "./ConditionItem";
import {useDispatch, useSelector} from "react-redux";
import AcInput2 from "../common/AcInput2";


const ConditionPicker2 = (props) => {
    const conditionSelect = useRef();
    const paramInput = useRef();
    const [acKey, setAcKey] = useState();
    const dispatch = useDispatch();
    const conditionsList = useSelector(state => state.conditionsReducer.conditions);

    const addCondition = (cardName = null) => {
        cardName = cardName ?? paramInput.current.value;

        if (!conditionSelect.current.value || !cardName) return;

        dispatch({ type: 'ADD_CONDITION_2', payload: {
                name: conditionSelect.current.value,
                title: conditionSelect.current.selectedOptions.item(0).text,
                param: cardName,
                turn: 0
            } });

        setAcKey(acKey+1);
    }

    return (
        <div id="condition-picker" className="container">
            Condition Picker
            <br />
            <select ref={conditionSelect}>
                <option value="">--</option>
                {props.conditions.map((item, i) => {
                    return <option value={item.name} key={i}>{item.title}</option>
                })}
            </select>
            <select>
                <option value="1">starting hand</option>
                <option value="2">Turn 2</option>
            </select>
            <AcInput2 id="condition-picker-2"
                     key={acKey}
                     ref={paramInput}
                     cards={props.cards}
                     addCardCallback={addCondition}
            />
            <button onClick={() => addCondition()}>add</button>
            <div>
                {conditionsList.map((item, i) => {
                    return <ConditionItem key={i} {...item} />;
                })}
            </div>
        </div>
    );
}

export default ConditionPicker2;

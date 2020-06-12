import React, { Component } from 'react';
import AcInput from "../common/AcInput";
import ConditionItem from "./ConditionItem";

class ConditionPicker extends Component {
    constructor(props) {
        super(props);

        this.state = {
            conditionParam: "",
            conditions: []
        };

        this.acParamInput = React.createRef();
        this.conditionSelect = React.createRef();
        this.conditionKey = 0;

        this.updateConditionParam = this.updateConditionParam.bind(this);
        this.addCondition = this.addCondition.bind(this);
        this.removeCondition = this.removeCondition.bind(this)
        this.getData = this.getData.bind(this);
    }

    getData() {
        return this.state.conditions;
    }

    updateConditionParam(value) {
        this.setState({ conditionParam: value });
    }

    addCondition() {
        const conditionName = this.conditionSelect.current.selectedOptions.item(0).value;
        const conditionTitle = this.conditionSelect.current.selectedOptions.item(0).text;

        if (!conditionName || !this.state.conditionParam) return;

        let newConditions = this.state.conditions.concat([{
            key: this.conditionKey,
            name: conditionName,
            title: conditionTitle,
            param: this.state.conditionParam
        }]);
        this.conditionKey++;
        this.setState({ conditions: newConditions });
        this.acParamInput.current.clearCardName();
    }

    removeCondition(itemKey) {
        const newConditions = this.state.conditions.filter((item) => {
            if (item.key !== itemKey) return item;
        });

        this.setState({ conditions: newConditions });
    }

    render() {
        return (
            <div id="condition-picker" className="container">
                Condition Picker
                <br />
                <select ref={this.conditionSelect}>
                    <option value="">--</option>
                    {this.props.conditions.map((item, i) => {
                        return <option value={item.name} key={i}>{item.title}</option>
                    })}
                </select>
                <AcInput id="condition-picker"
                         ref={this.acParamInput}
                         cards={this.props.cards}
                         updateCardCallback={this.updateConditionParam}
                         addCardCallback={this.addCondition}
                />
                <button onClick={this.addCondition}>add</button>
                <div>
                    {this.state.conditions.map((item, i) => {
                        return <ConditionItem
                                    key={i}
                                    itemKey={item.key}
                                    name={item.name}
                                    title={item.title}
                                    param={item.param}
                                    removeConditionCallback={this.removeCondition}
                        />;
                    })}
                </div>
            </div>
        );
    }
}

export default ConditionPicker;

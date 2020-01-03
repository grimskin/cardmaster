import React, { Component } from 'react';
import AcInput from "../common/AcInput";

class ConditionPicker extends Component {
    constructor(props) {
        super(props);

        this.state = {
            conditionParam: "",
            conditions: []
        };

        this.acParamInput = React.createRef();
        this.conditionSelect = React.createRef();
        this.conditionKey = 1;

        this.updateConditionParam = this.updateConditionParam.bind(this);
        this.addCondition = this.addCondition.bind(this);
        this.removeCondition = this.removeCondition.bind(this)
    }

    updateConditionParam(value) {
        this.setState({ conditionParam: value });
    }

    addCondition() {
        const conditionName = this.conditionSelect.current.selectedOptions.item(0).value;

        if (!conditionName || !this.state.conditionParam) return;

        let newConditions = this.state.conditions.concat([{
            key: this.conditionKey,
            name: conditionName,
            param: this.state.conditionParam
        }]);
        this.conditionKey++;
        this.setState({ conditions: newConditions });
        this.acParamInput.current.clearCardName();
    }

    removeCondition(key) {
        const newConditions = this.state.conditions.filter((item) => {
            if (item.key !== key) return item;
        });

        this.setState({ conditions: newConditions });
    }

    render() {
        return (
            <div id="condition-picker">
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
                        return <div className="card_in_deck" key={i}>
                            {item.name}: {item.param}
                            <a onClick={() => {
                                this.removeCondition(item.key)
                            }}/>
                        </div>;
                    })}
                </div>
            </div>
        );
    }
}

export default ConditionPicker;

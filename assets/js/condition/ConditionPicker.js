import React, { Component } from 'react';
import AcInput from "../common/AcInput";

class ConditionPicker extends Component {
    constructor(props) {
        super(props);

        this.acInput = React.createRef();
        this.updateConditionParam = this.updateConditionParam.bind(this);
        this.confirmParam = this.confirmParam.bind(this);
        this.addCondition = this.addCondition.bind(this);
    }

    updateConditionParam(value) {

    }

    confirmParam() {

    }

    addCondition() {

    }

    render() {
        return (
            <div id="condition-picker">
                Condition Picker
                <br />
                <select>
                    <option>--</option>
                    {this.props.conditions.map((item, i) => {
                        return <option value={item.name} key={i}>{item.title}</option>
                    })}
                </select>
                <AcInput id="condition-picker"
                         ref={this.acInput}
                         cards={this.props.cards}
                         updateCardCallback={this.updateConditionParam}
                         addCardCallback={this.confirmParam}
                />
                <button onClick={this.addCondition}>add</button>
            </div>
        );
    }
}

export default ConditionPicker;

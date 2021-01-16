import React, { Component } from 'react';
import AcInput from "../common/AcInput";
import ConditionItem from "./ConditionItem";
import {connect} from "react-redux";

class ConditionPicker extends Component {
    constructor(props) {
        super(props);

        this.state = {
            conditionParam: "",
        };

        this.acParamInput = React.createRef();
        this.conditionSelect = React.createRef();

        this.updateConditionParam = this.updateConditionParam.bind(this);
        this.addCondition = this.addCondition.bind(this);
    }

    updateConditionParam(value) {
        this.setState({ conditionParam: value });
    }

    addCondition() {
        const conditionName = this.conditionSelect.current.selectedOptions.item(0).value;
        const conditionTitle = this.conditionSelect.current.selectedOptions.item(0).text;

        if (!conditionName || !this.state.conditionParam) return;

        this.acParamInput.current.clearCardName();

        this.props.addCondition(conditionName, conditionTitle, this.state.conditionParam);
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
                    {this.props.conditionsList.map((item, i) => {
                        return <ConditionItem key={i} {...item} />;
                    })}
                </div>
            </div>
        );
    }
}

const mapStateToProps = state => {
    return { conditionsList: state.conditionsReducer.conditions };
};

const mapDispatchToProps = dispatch => {
    return {
        addCondition: (name, title, param) => dispatch({ type: 'ADD_CONDITION', payload: {name, title, param} }),
    }
};

export default connect(mapStateToProps, mapDispatchToProps)(ConditionPicker);

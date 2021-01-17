import React, { Component } from 'react';
import AcInput from "../common/AcInput";
import ConditionItem from "./ConditionItem";
import {connect} from "react-redux";

class ConditionPicker extends Component {
    constructor(props) {
        super(props);

        this.state = {
            conditionParam: '',
            conditionName: '',
            conditionTitle: '',
        };

        this.acParamInput = React.createRef();

        this.updateConditionParam = this.updateConditionParam.bind(this);
        this.addCondition = this.addCondition.bind(this);
        this.handleNameChange = this.handleNameChange.bind(this);
    }

    updateConditionParam(value) {
        this.setState({ conditionParam: value });
    }

    handleNameChange(e) {
        this.state.conditionName = e.target.value;
        this.state.conditionTitle = e.target.selectedOptions.item(0).text;
    }

    addCondition() {
        if (!this.state.conditionName || !this.state.conditionParam) return;

        this.acParamInput.current.clearCardName();

        this.props.addCondition(
            this.state.conditionName,
            this.state.conditionTitle,
            this.state.conditionParam
        );
    }

    render() {
        return (
            <div id="condition-picker" className="container">
                Condition Picker
                <br />
                <select onChange={this.handleNameChange}>
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

import React, { Component } from 'react';
import {connect} from "react-redux";

class ConditionItem extends Component {
    removeCondition() {
        this.props.removeCondition(this.props.name, this.props.param);
    }

    render() {
        return <div className="condition_item">
            <div className="condition_name">
                {this.props.title ? this.props.title : this.props.name}
            </div>
            <div className="condition_param">
                {this.props.param}
            </div>
            <div className="condition_control">
                <button onClick={() => { this.removeCondition(); }}>remove</button>
            </div>
        </div>;
    }
}

ConditionItem.defaultProps = {
    name: "",
    title: "",
    param: "",
};

const mapDispatchToProps = dispatch => {
    return {
        removeCondition: (name, param) => dispatch({type: 'REMOVE_CONDITION', payload: {name, param}}),
    }
};

export default connect(null, mapDispatchToProps)(ConditionItem);
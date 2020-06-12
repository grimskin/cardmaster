import React, { Component } from 'react';

class ConditionItem extends Component {
    constructor(props) {
        super(props);

        this.removeCondition = this.removeCondition.bind(this);
    }

    removeCondition() {
        this.props.removeConditionCallback(this.props.itemKey);
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
    itemKey: "",
    name: "",
    title: "",
    param: "",
    removeConditionCallback: (itemKey) => {}
};

export default ConditionItem;
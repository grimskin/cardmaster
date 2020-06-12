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
            {this.props.title ? this.props.title : this.props.name}: {this.props.param}
            <a onClick={() => { this.removeCondition(); }}/>
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
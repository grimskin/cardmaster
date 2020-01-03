import React, { Component } from "react";

class AcList extends Component {
    constructor(props) {
        super(props);

        this.itemClicked = this.itemClicked.bind(this);
    }

    itemClicked(e) {
        this.props.callback(e.target.textContent);
    }

    render() {
        let items = this.props.items.map((item, i) => {
            return <div
                className="ac_option"
                key={i}
                onClick={this.itemClicked}>
                {item}
            </div>;
        });

        return (
            <div id="ac_container" className="ac_items">
                {items}
            </div>
        );
    };
}

export default AcList;
import React, { Component } from 'react';


class CardInDeck extends Component {
    constructor(props) {
        super(props);

        this.increaseAmount = this.increaseAmount.bind(this);
        this.reduceAmount = this.reduceAmount.bind(this);
        this.removeCard = this.removeCard.bind(this);
    }

    increaseAmount() {
        this.props.setAmountCallback(this.props.name, this.props.amount+1)
    }

    reduceAmount() {
        this.props.setAmountCallback(this.props.name, this.props.amount-1);
    }

    removeCard() {
        this.props.setAmountCallback(this.props.name, 0);
    }

    render() {
        return (
            <div className="card_in_deck">
                <button onClick={this.reduceAmount}>-</button>
                {this.props.amount}
                <button onClick={this.increaseAmount}>+</button>
                {this.props.name}

                <button onClick={this.removeCard}>X</button>
            </div>
        );
    }
}

CardInDeck.defaultProps = {
    amount: 0,
    name: "",
    setAmountCallback: (name, amount) => {}
};


export default CardInDeck;
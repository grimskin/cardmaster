import React, { Component } from 'react';
import CardPicker from "./CardPicker";

class DeckComposer extends Component {
    constructor(props) {
        super(props);

        this.state = {
            deck: []
        };
        this.addCard = this.addCard.bind(this);
        this.removeCard = this.removeCard.bind(this);
        this.getData = this.getData.bind(this);
    }

    getData() {
        return this.state.deck;
    }

    removeCard(name) {
        const newDeck = this.state.deck.filter((item) => {
            if (item.name !== name) return item;
        });

        this.setState({ deck: newDeck });
    }

    addCard(name, amount) {
        let currentAmount = this.state.deck.reduce((total, item) => {
            return ((item.name === name) ? item.amount : 0) + total;
        }, 0);

        let newDeck;
        if (currentAmount) {
            newDeck = this.state.deck.map((item, i) => {
                if (item.name === name) {
                    item.amount += Number(amount);
                }

                return item;
            });
        } else {
            newDeck = this.state.deck.concat([{name: name, amount: Number(amount)}]);
        }

        this.setState({ deck: newDeck });
    }

    renderDeck() {
        return this.state.deck.map((item, i) => {
            return <div className="card_in_deck" key={i}>
                {item.amount}x {item.name}
                <a onClick={() => {
                    this.removeCard(item.name)
                }}/>
            </div>;
        });
    }

    render() {
        return (
            <div id="deck_composer_container">
                Deck Composer
                <CardPicker callBackAddCard={this.addCard} cards={this.props.cards} />
                <div id="cards-list-container">
                    {this.renderDeck()}
                </div>
            </div>
        );
    }
}

export default DeckComposer;

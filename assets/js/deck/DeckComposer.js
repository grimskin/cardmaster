import React, { Component } from 'react';
import CardPicker from "./CardPicker";
import axios from "axios";
import CardInDeck from "./CardInDeck";

class DeckComposer extends Component {
    constructor(props) {
        super(props);

        this.state = {
            deck: [],
            deckUrl: ""
        };

        this.addCard = this.addCard.bind(this);
        this.removeCard = this.removeCard.bind(this);
        this.getData = this.getData.bind(this);
        this.fetchDeck = this.fetchDeck.bind(this);
        this.changeCardAmount = this.changeCardAmount.bind(this);

        this.urlInput = React.createRef();
    }

    getData() {
        return this.state.deck;
    }

    fetchDeck() {
        let url = this.urlInput.current.value;

        axios.get('/api/fetch/deck', { params: {deck_url: url} })
            .then(response => {
                Object.values(response.data).map((item) => {
                    this.addCard(item.card_name, item.amount);
                });
            })
            .catch(function (error) {
            });

    }

    changeCardAmount(name, newAmount) {
        if (newAmount === 0) {
            this.removeCard(name);

            return;
        }

        let currentAmount = this.state.deck.reduce((total, item) => {
            return ((item.name === name) ? item.amount : 0) + total;
        }, 0);

        this.addCard(name, newAmount-currentAmount);
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
            newDeck = this.state.deck.map((item) => {
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

    render() {
        return (
            <div id="deck-composer" className="container">
                Deck Composer
                <CardPicker callBackAddCard={this.addCard} cards={this.props.cards} />
                <div id="cards-list-container">
                    {this.state.deck.map((item, i) => {
                        return <CardInDeck
                            name={item.name}
                            amount={item.amount}
                            setAmountCallback={this.changeCardAmount}
                            key={i}
                        />;
                    })}
                </div>
                <div className="deck_importer">
                    <input
                        name={"deckUrl"}
                        id={"input_deck_url"}
                        placeholder={"Enter deck url"}
                        ref={this.urlInput}
                        defaultValue={"https://www.mtggoldfish.com/deck/3666706#paper"}
                    />
                    <button onClick={this.fetchDeck}>load</button>
                </div>
            </div>
        );
    }
}

export default DeckComposer;

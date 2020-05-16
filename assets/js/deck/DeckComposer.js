import React, { Component } from 'react';
import CardPicker from "./CardPicker";
import axios from "axios";

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
            <div id="deck_composer_container" className={"container"}>
                Deck Composer
                <CardPicker callBackAddCard={this.addCard} cards={this.props.cards} />
                <input
                    name={"deckUrl"}
                    id={"input_deck_url"}
                    placeholder={"Enter deck url"}
                    ref={this.urlInput}
                />
                <button onClick={this.fetchDeck}>load</button>
                <div id="cards-list-container">
                    {this.renderDeck()}
                </div>
            </div>
        );
    }
}

export default DeckComposer;

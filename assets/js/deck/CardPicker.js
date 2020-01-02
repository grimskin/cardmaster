import React, { Component } from 'react';
import axios from "axios";
import AcList from "./AcList";

class CardPicker extends Component {
    constructor(props) {
        super(props);

        this.state = {
            cardName: "",
            cardAmount: 4,
            cards: [],
            acItems: this.props.acItems
        };
        this.acFocus = -1;

        this.handleInputChange = this.handleInputChange.bind(this);
        this.setCardName = this.setCardName.bind(this);
        this.hideAutocomplete = this.hideAutocomplete.bind(this);
        this.textInputBlur = this.textInputBlur.bind(this);
        this.textInputKeyDown = this.textInputKeyDown.bind(this);
        this.numberInputKeyDown = this.numberInputKeyDown.bind(this);
        this.addCard = this.addCard.bind(this);
    }

    addCard() {
        if (!this.state.cardName || !this.state.cardAmount) return;

        this.props.callBackAddCard(this.state.cardName, this.state.cardAmount);
        this.setState({ cardName: "", cardAmount: 4 });
    }

    numberInputKeyDown(e)
    {
        const keyCode = e.keyCode;
        if (keyCode === 13) {
            e.preventDefault();
            this.addCard();
        }
    }

    showAutocomplete(partialName) {
        if (partialName.length > 2) {
            const canonizedPartial = partialName.toUpperCase();
            let suggestions = this.state.cards.filter((item) => {
                return item.toUpperCase().includes(canonizedPartial) ? item : null;
            });
            this.setState({ acItems: suggestions.slice(0, 5) });
        } else {
            this.setState({ acItems: [] });
        }
        this.acFocus = -1;
    }

    componentDidMount() {
        axios.get('/api/cards')
            .then(response => {
                this.setState({ cards: response.data });
            })
            .catch(function (error) {
            });
    }

    setCardName(newCardName) {
        this.setState({ cardName: newCardName });
        this.hideAutocomplete();
    }

    hideAutocomplete() {
        this.setState({ acItems: [] });
        this.acFocus = -1;
    }

    textInputBlur() {
        setTimeout(this.hideAutocomplete, 500);
    }

    removeActive(elems) {
        for (let i = 0; i < elems.length; i++) {
            elems[i].classList.remove("ac_active");
        }
    }

    addActive(elems) {
        if (!elems) return false;
        if (!elems.length) return false;

        this.removeActive(elems);

        if (this.acFocus >= elems.length) {
            this.acFocus = elems.length-1;
        }
        if (this.acFocus < 0) {
            this.acFocus = 0;
        }

        elems[this.acFocus].classList.add("ac_active");
    }

    // modified version of this - https://www.w3schools.com/howto/howto_js_autocomplete.asp
    textInputKeyDown(e) {
        let acContainer = document.getElementById("ac_container");
        if (!acContainer) return;

        let elems = acContainer.getElementsByTagName("div");

        const keyCode = e.keyCode;
        if (keyCode === 40) {
            this.acFocus++;
            this.addActive(elems);
        } else if (e.keyCode === 38) { //up
            this.acFocus--;
            this.addActive(elems);
        } else if (keyCode === 13) {
            e.preventDefault();
            if (elems.length === 0) {
                this.addCard();
            } else {
                if (this.acFocus > -1) {
                    if (elems) elems[this.acFocus].click();
                } else if (elems.length === 1) {
                    elems[0].click();
                }
                this.acFocus = -1;
            }
        }
    }

    handleInputChange(event) {
        const target = event.target;
        this.setState({
            [target.name]: target.value
        });
        if (target.name === "cardName") {
            this.showAutocomplete(event.target.value);
        }
    }

    render() {
        return (
            <div>
                <div className="autocomplete">
                    <input type="text" id="card_name_input"
                           name="cardName"
                           value={this.state.cardName}
                           onChange={this.handleInputChange}
                           onBlur={this.textInputBlur}
                           onKeyDown={this.textInputKeyDown}
                    />
                    <input type="number"
                           id="card_amount_input"
                           name="cardAmount"
                           value={this.state.cardAmount}
                           onChange={this.handleInputChange}
                           onKeyDown={this.numberInputKeyDown}
                    />
                    <button onClick={this.addCard}>add</button>
                    <AcList items={this.state.acItems} callback={this.setCardName} />
                </div>
            </div>
        );
    }
}

CardPicker.defaultProps = {
    acItems: [],
    callBackAddCard: (name, amount) => {}
};

export default CardPicker;

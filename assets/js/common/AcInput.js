import React, { Component } from "react";
import AcList from "./AcList";

class AcInput extends Component {
    constructor(props) {
        super(props);

        this.state = {
            cardName: "",
            acItems: []
        };
        this.acFocus = -1;

        this.handleInputChange = this.handleInputChange.bind(this);
        this.textInputKeyDown = this.textInputKeyDown.bind(this);
        this.hideAutocomplete = this.hideAutocomplete.bind(this);
        this.textInputBlur = this.textInputBlur.bind(this);
        this.setCardName = this.setCardName.bind(this);
        this.clearCardName = this.clearCardName.bind(this);
    }

    clearCardName() {
        this.setState({ cardName: "" });
    }

    handleInputChange(event) {
        const target = event.target;
        this.setState({
            [target.name]: target.value
        });
        if (target.name === "cardName") {
            this.showAutocomplete(event.target.value);
            this.props.updateCardCallback(event.target.value);
        }
    }

    showAutocomplete(partialName) {
        if (partialName.length > 2) {
            const canonizedPartial = partialName.toUpperCase();
            let suggestions = this.props.cards.filter((item) => {
                return item.toUpperCase().includes(canonizedPartial) ? item : null;
            });
            this.setState({ acItems: suggestions.slice(0, 5) });
        } else {
            this.setState({ acItems: [] });
        }
        this.acFocus = -1;
    }

    textInputKeyDown(e) {
        let acContainer = document.getElementById(this.props.id + "-ac-container");
        if (!acContainer) return;

        let elems = acContainer.getElementsByTagName("div");

        const keyCode = e.keyCode;
        if (keyCode === 40) {
            this.acFocus++;
            this.addActive(elems);
        } else if (e.keyCode === 38) {
            this.acFocus--;
            this.addActive(elems);
        } else if (keyCode === 13) {
            e.preventDefault();
            if (elems.length === 0) {
                this.props.addCardCallback();
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

    textInputBlur() {
        setTimeout(this.hideAutocomplete, 500);
    }

    hideAutocomplete() {
        this.setState({ acItems: [] });
        this.acFocus = -1;
    }

    setCardName(newCardName) {
        this.setState({ cardName: newCardName });
        this.props.updateCardCallback(newCardName);
        this.hideAutocomplete();
    }

    render() {
        return <div className="ac_input_container">
            <input type="text"
                   name="cardName"
                   value={this.state.cardName}
                   onChange={this.handleInputChange}
                   onBlur={this.textInputBlur}
                   onKeyDown={this.textInputKeyDown}
            />
            <AcList items={this.state.acItems} callback={this.setCardName} parentId={this.props.id} />
        </div>;
    }
}

AcInput.defaultProps = {
    id: "",
    updateCardCallback: (name) => {},
    addCardCallback: () => {}
};

export default AcInput;
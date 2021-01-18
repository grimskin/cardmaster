import React, {useState, useEffect} from "react";

const AcInput2 = (props) => {
    const [cardName, setCardName] = useState('');
    const [acItems, setAcItems] = useState([]);
    const [acFocus, setAcFocus] = useState(0);

    useEffect(() => {
        if (cardName.length > 2) {
            let itemCandidates = props.cards.filter(item => {
                return item.toUpperCase().includes(cardName.toUpperCase()) ? item : null;
            }).slice(0, 5);

            if (itemCandidates.length === 1 && itemCandidates[0].toUpperCase() === cardName.toUpperCase()) {
                itemCandidates = [];
            }

            setAcItems(itemCandidates);
            setAcFocus(0);
        }

        return () => {
            setAcItems([]);
            setAcFocus(0);
        };
    }, [cardName]);

    useEffect(() => {
        props.updateCardCallback(cardName);
    }, [cardName]);

    const onKeyDown = (e) => {
        const keyCode = e.keyCode;

        if (keyCode === 40) {
            const newAcFocus = (acFocus + 1) % acItems.length;
            setAcFocus(newAcFocus);
            props.updateCardCallback(acItems[newAcFocus]);
        } else if (keyCode === 38) {
            const newAcFocus = (acFocus - 1 + acItems.length) % acItems.length;
            setAcFocus(newAcFocus);
            props.updateCardCallback(acItems[newAcFocus]);
        } else if (keyCode === 13) {
            e.preventDefault();
            if (acItems.length > 0) {
                setCardName(acItems[acFocus]);
                props.updateCardCallback(acItems[acFocus]);
            }
            props.addCardCallback();
        }
    };

    return <>
        <div className="ac_input_container">
            <input type="text"
                   name="cardName"
                   className="input_auto_complete"
                   value={cardName}
                   onChange={e => setCardName(e.target.value)}
                   onBlur={() => setTimeout(() => setAcItems([]), 100)}
                   onKeyDown={onKeyDown}
            />
            <div id={props.parentId + "-ac-container"} className="ac_items">
                {acItems.map((item, i) => {
                    return <div className={"ac_option" + (acFocus === i ? ' ac_active' : '')}
                               key={i}
                               onClick={() => setCardName(item)}
                           >{item}</div>;
                })}
            </div>
        </div>
    </>;
}

AcInput2.defaultProps = {
    updateCardCallback: () => {},
    addCardCallback: () => {console.log('aaa')}
};

export default AcInput2;
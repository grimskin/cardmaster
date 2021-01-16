import { combineReducers } from "redux";
import deckReducer from "./deckComposerReducer";
import conditionsReducer from "./conditionPickerReducer";

export default combineReducers({
    deckComposer: deckReducer,
    conditionsReducer: conditionsReducer,
});
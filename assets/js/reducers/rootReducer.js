import { combineReducers } from "redux";
import deckReducer from "./deckComposerReducer";

export default combineReducers({
    deckComposer: deckReducer,
});
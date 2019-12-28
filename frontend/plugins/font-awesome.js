import Vue from "vue";
import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";


// icon imports

// Example works!
//import { faThumbsUp as thumbsUp_SolidIcon } from "@fortawesome/free-solid-svg-icons";
//library.add ( thumbsUp_SolidIcon );

import { faChevronDown, faExternalLinkAlt, faChevronLeft, faChevronRight, faPhone, faEnvelope } from "@fortawesome/free-solid-svg-icons";
library.add ( faChevronDown, faExternalLinkAlt, faChevronLeft, faChevronRight, faPhone, faEnvelope );

// register component
Vue.component("font-awesome-icon", FontAwesomeIcon);

Vue.config.productionTip = false;
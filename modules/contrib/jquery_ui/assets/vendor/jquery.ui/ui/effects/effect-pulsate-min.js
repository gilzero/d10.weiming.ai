/*!
 * jQuery UI Effects Pulsate 1.13.2
 * http://jqueryui.com
 *
 * Copyright jQuery Foundation and other contributors
 * Released under the MIT license.
 * http://jquery.org/license
 */
!function(e){"use strict";"function"==typeof define&&define.amd?define(["jquery","../version","../effect"],e):e(jQuery)}((function(e){"use strict";return e.effects.define("pulsate","show",(function(i,t){var n=e(this),s=i.mode,f="show"===s,u=f||"hide"===s,o=2*(i.times||5)+(u?1:0),a=i.duration/o,c=0,r=1,d=n.queue().length;for(!f&&n.is(":visible")||(n.css("opacity",0).show(),c=1);r<o;r++)n.animate({opacity:c},a,i.easing),c=1-c;n.animate({opacity:c},a,i.easing),n.queue(t),e.effects.unshift(n,d,o+1)}))}));
//# sourceMappingURL=effect-pulsate-min.js.map
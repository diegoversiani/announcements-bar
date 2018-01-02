/**
 * File polyfill-closest.js.
 *
 * Extend browser support for Element.closest and Element.matches all the way back to IE9.
 *
 * Author: MDN
 * https://developer.mozilla.org/en-US/docs/Web/API/Element/closest#Polyfill
 */

(function () {
  if (!Element.prototype.matches)
    Element.prototype.matches = Element.prototype.msMatchesSelector || 
                                Element.prototype.webkitMatchesSelector;

  if (!Element.prototype.closest)
    Element.prototype.closest = function(s) {
        var el = this;
        var ancestor = this;
        if (!document.documentElement.contains(el)) return null;
        do {
            if (ancestor.matches(s)) return ancestor;
            ancestor = ancestor.parentElement;
        } while (ancestor !== null); 
        return null;
    };
})();

/**
 * File frontend-announcement-bar.js.
 *
 * Initialize announcements bar admin backend view.
 *
 * Author: Diego Versiani
 * Contact: https://diegoversiani.me/
 */

(function(){

  'use strict';

  // Run initialize on pageload
  window.addEventListener( 'load', init );

  /**
   * Initialize component and set related handlers
   */
  function init() {
    document.addEventListener( 'click', handleDocumentClick );
  };



  /**
   * Detects clicks to dismiss announcement button.
   * @param  Object e Click event data.
   */
  function handleDocumentClick( e ) {
    // Dismiss announcement
    if ( e.target.closest( '.announcement-bar .dismiss' ) ) {
      dismissAnnouncementBar(e);
    }
  };



  /**
   * Remove announcement bar row to the view.
   * @param  Object e Click event data.
   */
  function dismissAnnouncementBar( e ) {
    e.preventDefault();

    var announcementBar = e.target.closest( '.announcement-bar' );
    announcementBar.parentNode.removeChild( announcementBar );
  };

})();

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
 * File admin-announcements.js.
 *
 * Initialize announcements bar admin backend view.
 *
 * Author: Diego Versiani
 * Contact: https://diegoversiani.me/
 */

(function( $ ){

  'use strict';

  // Run initialize on pageload
  window.addEventListener( 'load', init );

  /**
   * Initialize component and set related handlers
   */
  function init() {
    document.addEventListener( 'click', handleDocumentClick );

    // Initialize color picker fields
    if ( jQuery ) {
      $('.wp-color-picker').wpColorPicker();
    }
  };



  /**
   * Detects clicks to add or remove announcement buttons.
   * @param  Object e Click event data.
   */
  function handleDocumentClick( e ) {
    // Add announcement
    if ( e.target.closest( '.button-add' ) ) {
      addAnnouncement(e);
    }

    // Remove announcement
    if ( e.target.closest( '.button-remove' ) ) {
      removeAnnouncement(e);
    }
  };


  /**
   * Add announcement row to the view.
   * @param  Object e Click event data.
   */
  function addAnnouncement( e ) {
    e.preventDefault();

    var announcementRow = e.target.closest( '.announcement-content' );

    // Clone announcement row and change it's attributes
    var newAnnouncementRow = announcementRow.cloneNode( true );
    var newInput = newAnnouncementRow.querySelector( 'input[name="post_meta[announcement-content][]"]' );
    newInput.value = '';

    // Add new row bellow
    announcementRow.parentNode.insertBefore( newAnnouncementRow, announcementRow.nextSibling);

    newInput.focus();
  };



  /**
   * Remove announcement row to the view.
   * @param  Object e Click event data.
   */
  function removeAnnouncement( e ) {
    e.preventDefault();

    var announcementRow = e.target.closest( '.announcement-content' );
    var qtdRows = announcementRow.parentNode.querySelectorAll( '.announcement-content' ).length;
    
    // Last row
    if ( qtdRows == 1 ) {
      // Clear input value
      var input = announcementRow.querySelector( 'input[name="post_meta[announcement-content][]"]' );
      input.value = '';
      input.focus();
    }
    // Not last row
    else {
      // Remove row
      announcementRow.parentNode.removeChild( announcementRow );
    }
  };

})( jQuery );

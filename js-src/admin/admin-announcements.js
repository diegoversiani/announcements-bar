/**
 * File admin-announcements.js.
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
    }
    // Not last row
    else {
      // Remove row
      announcementRow.parentNode.removeChild( announcementRow );
    }
  };

})();

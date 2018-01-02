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

  var _animatedElements = [];
  var _animatedBars,
      _animatedBarsSelector = '.announcement-bar.animated',
      _velocity = 100;


  /**
   * Initialize component and set related handlers
   */
  function init() {
    document.addEventListener( 'click', handleDocumentClick );

    _animatedBars = document.querySelectorAll( _animatedBarsSelector );
    if ( _animatedBars ) {
      // Start animating
      initializeElementsPosition();
      loopUpdatePositions();
    }
  };



  /**
   * Keep updating elements position infinitelly.
   */
  function loopUpdatePositions() {
    updateElementsPosition();
    requestAnimationFrame( loopUpdatePositions );
  };



  /**
   * Updates moving elements position.
   * @private
   */
  var updateElementsPosition = function () {
    for (var i = 0; i < _animatedElements.length; i++) {
      // Go to next if animation stopped for the current element
      if ( _animatedElements[i].stopped ) {
        continue;
      }

      // Update element position in memory
      _animatedElements[i].position = _animatedElements[i].position - ( _animatedElements[i].velocity / 60); // 60 fps
      
      // Fix position to start end of parent
      if ( _animatedElements[i].position < ( _animatedElements[i].width * -1 ) ) {
        _animatedElements[i].position = _animatedElements[i].parentWidth;
      }

      // update element style
      _animatedElements[i].element.style.transform = 'translateX(' + _animatedElements[i].position + 'px)';
    }
  };



  /**
   * Initializes positions for each moving element.
   */
  function initializeElementsPosition() {
    for (var i = 0; i < _animatedBars.length; i++) {
      var innerElement = _animatedBars[i].querySelector( '.announcements' );

      var elementPosition = {
        element: innerElement,
        stopped: false,
        position: 0,
        velocity: _velocity,
        width: innerElement.getBoundingClientRect().width,
        parentWidth: innerElement.parentNode.getBoundingClientRect().width,
      };

      _animatedElements.push( elementPosition );

      // Add event listeners for stop/restart animation
      _animatedBars[i].addEventListener( 'mouseenter', handleMouseEnterBar );
      _animatedBars[i].addEventListener( 'mouseleave', handleMouseLeaveBar );
    }
  };



  /**
   * Stop animation when hover a bar.
   * @param  Object e Click event data.
   */
  function handleMouseEnterBar( e ) {
    var target = e.target.querySelector( '.announcements' );
    for (var i = 0; i < _animatedElements.length; i++) {
      if ( _animatedElements[i].element == target ) {
        _animatedElements[i].stopped = true;
        break;
      }
    }
  };



  /**
   * Restart animation when mouse leave a bar.
   * @param  Object e Click event data.
   */
  function handleMouseLeaveBar( e ) {
    var target = e.target.querySelector( '.announcements' );
    for (var i = 0; i < _animatedElements.length; i++) {
      if ( _animatedElements[i].element == target ) {
        _animatedElements[i].stopped = false;
        break;
      }
    }
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

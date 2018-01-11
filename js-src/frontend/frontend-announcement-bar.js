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
      _allBars,
      _allBarsSelector = '.announcement-bar',
      _delay = 2000, // milliseconds
      _velocity = 20, // pixels per second
      _bodyClass = 'has-announcement-bar',
      _bodyClassTop = 'has-announcement-bar--top';


  /**
   * Initialize component and set related handlers
   */
  function init() {
    document.addEventListener( 'click', handleDocumentClick );

    // Get bars
    _animatedBars = document.querySelectorAll( _animatedBarsSelector );
    _allBars = document.querySelectorAll( _allBarsSelector );

    // Prepare animations
    if ( _animatedBars ) {
      initializeElementsPosition();
      
      // Start animating
      setTimeout( loopUpdatePositions, _delay );
    }

    // Add body class
    document.body.classList.add( _bodyClass );

    // Add body class 'top' if there are top bars being displayed
    for (var i = 0; i < _allBars.length; i++) {
      if ( _allBars[i].classList.contains( 'top' ) ) {
        document.body.classList.add( _bodyClassTop );
        break;
      }
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
      var velocity = _animatedBars[i].getAttribute( 'data-velocity' );
      velocity = isNaN( parseInt(velocity) ) ? _velocity : parseInt( velocity );

      var elementPosition = {
        element: innerElement,
        stopped: false,
        position: 0,
        velocity: velocity,
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

    // Update _allBars
    _allBars = document.querySelectorAll( _allBarsSelector );

    // Remove top class from body element
    if ( announcementBar.classList.contains( 'top' ) ) {
      document.body.classList.remove( _bodyClassTop );
    }

    // Remove has bar body class
    if ( !_allBars || _allBars.length <= 0 ) {
      document.body.classList.remove( _bodyClass );
    }
  };

})();

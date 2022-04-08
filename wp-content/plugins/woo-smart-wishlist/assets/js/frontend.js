'use strict';

(function($) {
  $(function() {
    if (woosw_get_cookie('woosw_key') == '') {
      woosw_set_cookie('woosw_key', woosw_get_key(), 7);
    }

    if ($('.woosw-custom-menu-item').length) {
      // load the count if have a custom menu item
      woosw_load_count();
    }
  });

  // woovr
  $(document).on('woovr_selected', function(e, selected, variations) {
    var id = selected.attr('data-id');
    var pid = selected.attr('data-pid');

    if (id > 0) {
      $('.woosw-btn-' + pid).
          removeClass('woosw-btn-added woosw-added').
          attr('data-id', id);
    } else {
      $('.woosw-btn-' + pid).
          removeClass('woosw-btn-added woosw-added').
          attr('data-id', pid);
    }
  });

  // found variation
  $(document).on('found_variation', function(e, t) {
    var product_id = $(e['target']).attr('data-product_id');

    $('.woosw-btn-' + product_id).
        removeClass('woosw-btn-added woosw-added').
        attr('data-id', t.variation_id);
  });

  // reset data
  $(document).on('reset_data', function(e) {
    var product_id = $(e['target']).attr('data-product_id');

    $('.woosw-btn-' + product_id).
        removeClass('woosw-btn-added woosw-added').
        attr('data-id', product_id);
  });

  // quick view
  $(document).
      on('click touch', '.woosw-area .woosq-link, .woosw-area .woosq-btn',
          function(e) {
            woosw_hide();
            e.preventDefault();
          });

  // add
  $(document).on('click touch', '.woosw-btn', function(e) {
    var $this = $(this);
    var id = $this.attr('data-id');
    var pid = $this.attr('data-pid');
    var product_id = $this.attr('data-product_id');

    if (typeof pid !== typeof undefined && pid !== false) {
      id = pid;
    }

    if (typeof product_id !== typeof undefined && product_id !== false) {
      id = product_id;
    }

    var data = {
      action: 'wishlist_add',
      product_id: id,
    };

    if ($this.hasClass('woosw-added')) {
      if (woosw_vars.button_action_added === 'page') {
        // open wishlist page
        window.location.href = woosw_vars.wishlist_url;
      } else {
        // open wishlist popup
        if ($('#woosw-area .woosw-content-mid').
            hasClass('woosw-content-loaded')) {
          woosw_show();
        } else {
          woosw_load();
        }
      }
    } else {
      $this.addClass('woosw-adding');
      $.post(woosw_vars.ajax_url, data, function(response) {
        $this.removeClass('woosw-adding');
        response = JSON.parse(response);

        if (woosw_vars.button_action === 'list') {
          $('#woosw-area').removeClass('woosw-message');
          $('#woosw-area .woosw-content-mid').
              html(response['value']).
              removeClass('woosw-content-loaded-message').
              addClass('woosw-content-loaded');

          if (response['notice'] != null) {
            $('#woosw-area .woosw-notice').html(response['notice']);
            woosw_notice_show();
            setTimeout(function() {
              woosw_notice_hide();
            }, 3000);
          }

          woosw_perfect_scrollbar();
          woosw_show();
        } else if (woosw_vars.button_action === 'message') {
          $('#woosw-area').addClass('woosw-message');

          var message = '<div class="woosw-content-mid-message">';

          if (response['image'] != null) {
            message += '<img src="' + response['image'] + '"/>';
          }

          if (response['notice'] != null) {
            message += '<span>' + response['notice'] + '</span>';
          }

          message += '</div>';
          $('#woosw-area .woosw-content-mid').
              html(message).
              removeClass('woosw-content-loaded').
              addClass('woosw-content-loaded-message');

          woosw_show();
        } else if (woosw_vars.button_action === 'no') {
          // add to wishlist solely
          $('#woosw-area .woosw-content-mid').
              removeClass(
                  'woosw-content-loaded woosw-content-loaded-message');
        }

        if (response['status'] == 1) {
          $this.addClass('woosw-added').html(woosw_vars.button_text_added);
        }

        if (response['count'] != null) {
          woosw_change_count(response['count']);
        }

        $(document.body).trigger('woosw_add', [id]);
      });
    }

    e.preventDefault();
  });

  // remove
  $(document).
      on('click touch', '.woosw-content-item--remove span', function(e) {
        var $this = $(this);
        var $this_item = $this.closest('.woosw-content-item');
        var product_id = $this_item.attr('data-id');
        var data = {
          action: 'wishlist_remove',
          product_id: product_id,
        };

        $this.addClass('removing');

        $.post(woosw_vars.ajax_url, data, function(response) {
          $this.removeClass('removing');
          $this_item.remove();
          response = JSON.parse(response);

          if (response['status'] == 1) {
            $('.woosw-btn-' + product_id).
                removeClass('woosw-added').
                html(woosw_vars.button_text);

            if (response['notice'] != null) {
              $('#woosw-area .woosw-notice').html(response['notice']);
              woosw_notice_show();
              setTimeout(function() {
                woosw_notice_hide();
              }, 3000);
            }

            if (response['value'] != null) {
              $('#woosw-area .woosw-content-mid').
                  html(response['value']).
                  removeClass('woosw-content-loaded-message').
                  addClass('woosw-content-loaded');
            }
          } else {
            if (response['notice'] != null) {
              $('#woosw-area .woosw-content-mid').
                  html('<div class="woosw-content-mid-notice">' +
                      response['notice'] + '</div>');
            }
          }

          if (response['count'] != null) {
            woosw_change_count(response['count']);
          }

          $(document.body).trigger('woosw_remove', [product_id]);
        });

        e.preventDefault();
      });

  // empty
  $(document).on('click touch', '.woosw-empty', function(e) {
    if (confirm(woosw_vars.empty_confirm)) {
      var data = {
        action: 'wishlist_empty',
      };

      $.post(woosw_vars.ajax_url, data, function(response) {
        response = JSON.parse(response);

        if (response['status'] == 1) {
          $('#woosw-area .woosw-content-mid').
              html(response['value']).
              removeClass('woosw-content-loaded-message').
              addClass('woosw-content-loaded');

          $('.woosw-btn').
              removeClass('woosw-added').
              html(woosw_vars.button_text);

          if (response['notice'] != null) {
            $('#woosw-area .woosw-notice').html(response['notice']);
            woosw_notice_show();
            setTimeout(function() {
              woosw_notice_hide();
            }, 3000);
          }
        } else {
          if (response['notice'] != null) {
            $('#woosw-area .woosw-content-mid').
                html('<div class="woosw-content-mid-notice">' +
                    response['notice'] + '</div>');
          }
        }

        if (response['count'] != null) {
          woosw_change_count(response['count']);
        }
      });
    }

    $(document.body).trigger('woosw_empty', [product_id]);

    e.preventDefault();
  });

  // click on area
  $(document).on('click touch', '#woosw-area', function(e) {
    var woosw_content = $('.woosw-content');

    if ($(e.target).closest(woosw_content).length == 0) {
      woosw_hide();
    }
  });

  // continue
  $(document).on('click touch', '.woosw-continue', function(e) {
    var url = $(this).attr('data-url');
    woosw_hide();

    if (url !== '') {
      window.location.href = url;
    }

    e.preventDefault();
  });

  // close
  $(document).on('click touch', '.woosw-close', function(e) {
    woosw_hide();
    e.preventDefault();
  });

  // menu item
  $(document).on('click touch', '.woosw-menu-item a', function(e) {
    if (woosw_vars.menu_action === 'open_popup') {
      if ($('#woosw-area .woosw-content-mid').
          hasClass('woosw-content-loaded')) {
        woosw_show();
      } else {
        woosw_load();
      }

      e.preventDefault();
    }
  });

  // copy link
  $(document).
      on('click touch', '#woosw_copy_url, #woosw_copy_btn', function(e) {
        woosw_copy_to_clipboard('#woosw_copy_url');
      });

  // add note
  $(document).on('click touch', '.woosw-content-item--note', function() {
    if ($(this).
        closest('.woosw-content-item').
        find('.woosw-content-item--note-add').length) {
      $(this).
          closest('.woosw-content-item').
          find('.woosw-content-item--note-add').
          show();
      $(this).hide();
    }
  });

  $(document).on('click touch', '.woosw_add_note', function() {
    var $this = $(this);
    var product_id = $this.closest('.woosw-content-item').attr('data-id');
    var key = $this.closest('.woosw-content-item').attr('data-key');
    var note = $this.closest('.woosw-content-item').find('textarea').val();
    var data = {
      action: 'add_note',
      woosw_key: key,
      product_id: product_id,
      note: woosw_html_entities(note),
    };

    $.post(woosw_vars.ajax_url, data, function(response) {
      $this.closest('.woosw-content-item').
          find('.woosw-content-item--note').
          html(response).show();
      $this.closest('.woosw-content-item').
          find('.woosw-content-item--note-add').hide();
    });
  });

  $(window).on('resize', function() {
    woosw_fix_height();
  });

  function woosw_load() {
    var data = {
      action: 'wishlist_load',
    };

    $.post(woosw_vars.ajax_url, data, function(response) {
      $('#woosw-area').removeClass('woosw-message');
      response = JSON.parse(response);

      if (response['status'] == 1) {
        $('#woosw-area .woosw-content-mid').html(response['value']);

        if (response['count'] != null) {
          woosw_change_count(response['count']);
        }
      } else {
        if (response['notice'] != null) {
          $('#woosw-area .woosw-content-mid').
              html('<div class="woosw-content-mid-notice">' +
                  response['notice'] +
                  '</div>');
        }
      }

      $('#woosw-area .woosw-content-mid').
          removeClass('woosw-content-loaded-message').
          addClass('woosw-content-loaded');
      woosw_perfect_scrollbar();
      woosw_show();
    });
  }

  function woosw_load_count() {
    var data = {
      action: 'wishlist_load_count',
    };

    $.post(woosw_vars.ajax_url, data, function(response) {
      response = JSON.parse(response);

      if (response['count'] != null) {
        var count = response['count'];

        woosw_change_count(count);
        $(document.body).trigger('woosw_load_count', [count]);
      }
    });
  }

  function woosw_show() {
    $('#woosw-area').addClass('woosw-open');
    woosw_fix_height();

    if ($('#woosw-area').hasClass('woosw-message')) {
      // timer
      var woosw_counter = 6;
      var woosw_interval = setInterval(function() {
        woosw_counter--;
        $('.woosw-close').html('Close in ' + woosw_counter + 's');

        if (woosw_counter === 0) {
          woosw_hide();
          $('.woosw-close').html('');
          clearInterval(woosw_interval);
        }
      }, 1000);
    }

    $(document.body).trigger('woosw_show');
  }

  function woosw_hide() {
    $('#woosw-area').removeClass('woosw-open');
    $(document.body).trigger('woosw_hide');
  }

  function woosw_change_count(count) {
    $('#woosw-area .woosw-count').html(count);

    if (count > 0) {
      $('.woosw-empty').show();
    } else {
      $('.woosw-empty').hide();
    }

    if ($('.woosw-menu-item .woosw-menu-item-inner').length) {
      $('.woosw-menu-item .woosw-menu-item-inner').
          attr('data-count', count);
    } else {
      $('.woosw-menu-item a').
          html('<span class="woosw-menu-item-inner" data-count="' + count +
              '"><i class="woosw-icon"></i><span>' + woosw_vars.menu_text +
              '</span></span>');
    }

    $(document.body).trigger('woosw_change_count', [count]);
  }

  function woosw_notice_show() {
    $('#woosw-area .woosw-notice').addClass('woosw-notice-show');
  }

  function woosw_notice_hide() {
    $('#woosw-area .woosw-notice').removeClass('woosw-notice-show');
  }

  function woosw_fix_height() {
    if ((
        woosw_vars.button_action === 'list'
    ) && (
        $('#woosw-area').find('.woosw-content-items').length
    )) {
      var woosw_window_height = $(window).height();
      var $woosw_content = $('#woosw-area').find('.woosw-content');
      var $woosw_table = $('#woosw-area').find('.woosw-content-items');
      var woosw_content_height = $woosw_table.outerHeight() + 96;

      if (
          woosw_content_height < (
              woosw_window_height * .8
          )) {
        if (parseInt(woosw_content_height) % 2 !== 0) {
          $woosw_content.height(parseInt(woosw_content_height) - 1);
        } else {
          $woosw_content.height(parseInt(woosw_content_height));
        }
      } else {
        if ((
            parseInt(woosw_window_height * .8)
        ) % 2 !== 0) {
          $woosw_content.height(parseInt(woosw_window_height * .8) - 1);
        } else {
          $woosw_content.height(parseInt(woosw_window_height * .8));
        }
      }
    }
  }

  function woosw_perfect_scrollbar() {
    if (woosw_vars.perfect_scrollbar === 'yes') {
      jQuery('#woosw-area .woosw-content-mid').perfectScrollbar({theme: 'wpc'});
    }
  }

  function woosw_copy_url() {
    var wooswURL = document.getElementById('woosw_copy_url');
    wooswURL.select();
    document.execCommand('copy');
    alert(woosw_vars.copied_text + ' ' + wooswURL.value);
  }

  function woosw_copy_to_clipboard(el) {
    // resolve the element
    el = (typeof el === 'string') ? document.querySelector(el) : el;

    // handle iOS as a special case
    if (navigator.userAgent.match(/ipad|ipod|iphone/i)) {
      // save current contentEditable/readOnly status
      var editable = el.contentEditable;
      var readOnly = el.readOnly;

      // convert to editable with readonly to stop iOS keyboard opening
      el.contentEditable = true;
      el.readOnly = true;

      // create a selectable range
      var range = document.createRange();
      range.selectNodeContents(el);

      // select the range
      var selection = window.getSelection();
      selection.removeAllRanges();
      selection.addRange(range);
      el.setSelectionRange(0, 999999);

      // restore contentEditable/readOnly to original state
      el.contentEditable = editable;
      el.readOnly = readOnly;
    } else {
      el.select();
    }

    // execute copy command
    document.execCommand('copy');

    // alert
    alert(woosw_vars.copied_text + ' ' + el.value);
  }

  function woosw_html_entities(str) {
    return String(str).
        replace(/&/g, '&amp;').
        replace(/</g, '&lt;').
        replace(/>/g, '&gt;').
        replace(/"/g, '&quot;');
  }

  function woosw_get_key() {
    var result = [];
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    var charactersLength = characters.length;

    for (var i = 0; i < 6; i++) {
      result.push(characters.charAt(Math.floor(Math.random() *
          charactersLength)));
    }

    return result.join('');
  }

  function woosw_set_cookie(cname, cvalue, exdays) {
    var d = new Date();

    d.setTime(d.getTime() + (
        exdays * 24 * 60 * 60 * 1000
    ));

    var expires = 'expires=' + d.toUTCString();

    document.cookie = cname + '=' + cvalue + '; ' + expires + '; path=/';
  }

  function woosw_get_cookie(cname) {
    var name = cname + '=';
    var ca = document.cookie.split(';');

    for (var i = 0; i < ca.length; i++) {
      var c = ca[i];

      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }

      if (c.indexOf(name) == 0) {
        return decodeURIComponent(c.substring(name.length, c.length));
      }
    }

    return '';
  }
})(jQuery);